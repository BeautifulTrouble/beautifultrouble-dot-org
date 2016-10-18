<?php

if( ! defined( 'RFBP_VERSION' ) ) {
	exit;
}

class RFBP_Admin {

	/**
	 * @var bool
	 */
	private $cache_cleared = false;

    /**
     * @var array
     */
    private $settings;

    /**
     * RFBP_Admin constructor.
     *
     * @param array $settings
     */
    public function __construct( $settings ) {
        $this->settings = $settings;
    }

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'init', array( $this, 'on_init' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'build_menu' ) );

		add_filter( "plugin_action_links_recent-facebook-posts/recent-facebook-posts.php", array( $this, 'add_settings_link' ) );

		// handle requests early, but only on rfb settings page
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'rfbp' ) {
			// load css
			add_action( 'admin_enqueue_scripts', array( $this, 'load_css' ) );
		}
	}

	public function on_init() {

		// check if we should run upgrade routine
		$this->upgrade();

		// maybe renew cache file
		if ( isset( $_POST['rfbp-clear-cache'] ) ) {

			delete_transient('rfbp_posts');
			delete_transient('rfbp_posts_fallback');

			$this->cache_cleared = true;
		}

		if( isset( $_POST['rfbp-test-config'] ) ) {
			add_action( 'admin_init', array( $this, 'test_facebook_api') );
		}
	}

	/**
	 * Upgrade Routine
	 */
	private function upgrade() {

		// Only run if code is newer than stored DB version
		$db_version = get_option( 'rfbp_version', 0 );
		if( version_compare( $db_version, RFBP_VERSION, '>=' ) ) {
			return;
		}

		// Delete transients for good measure
		delete_transient( 'rfbp_posts' );
		delete_transient( 'rfbp_posts_fallback' );

		// upgrade to 1.8.5
		if( version_compare( $db_version, '1.8.5', '<' ) ) {

			// rename `link_text` index to `page_link_text`
			if ( isset( $this->settings['link_text'] ) ) {
                $this->settings['page_link_text'] = $this->settings['link_text'];
				unset( $this->settings['link_text'] );
			}

			update_option( 'rfb_settings', $this->settings );
		}

		// Update version in database
		update_option('rfbp_version', RFBP_VERSION);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( 'rfb_settings_group', 'rfb_settings', array( $this, 'sanitize_settings' ) );
	}

	/**
	 * Sanitize settings
	 *
	 * @param $opts
	 *
	 * @return mixed
	 */
	public function sanitize_settings( $opts ) {
		$old_opts = $this->settings;

		// fb config
		$opts['app_id'] = sanitize_text_field( $opts['app_id'] );
		$opts['app_secret'] = sanitize_text_field( $opts['app_secret'] );
		$opts['fb_id'] = sanitize_text_field( $opts['fb_id'] );

		// If FB configuration changed, delete transients with posts cache so they'll be renewed
		if( ( $old_opts['fb_id'] !== $opts['fb_id'] ) ||  ( $old_opts['app_id'] !== $opts['app_id']) || ( $old_opts['app_secret'] !== $opts['app_secret'] ) ) {

			// delete cache transients
			delete_transient('rfbp_posts');
			delete_transient('rfbp_posts_fallback');

			// test facebook api
			if( ! empty( $opts['app_id'] ) && ! empty( $opts['app_secret'] ) && ! empty( $opts['fb_id'] ) ) {
				$this->test_facebook_api( $opts['app_id'], $opts['app_secret'], $opts['fb_id'] );
			}
		}

		// appearance opts
		$opts['page_link_text'] = strip_tags( trim( $opts['page_link_text'] ), '<span><strong><b><em><i><img>' );
		$opts['img_height'] = ! empty( $opts['img_height'] ) ? absint( $opts['img_height'] ) : '';
		$opts['img_width'] = ! empty( $opts['img_width'] ) ? absint( $opts['img_width'] ) : '';
		$opts['load_css'] = isset( $opts['load_css'] ) ? 1 : 0;
		$opts['show_links'] = isset($opts['show_links'] ) ? 1 : 0;

		return $opts;
	}

	/**
	 * Add page to WP Admin > Settings
	 */
	public function build_menu() {
		$page = add_options_page( 'Recent Facebook Posts - Settings', 'Recent Facebook Posts', 'manage_options', 'rfbp', array( $this, 'settings_page' ) );
	}

	/**
	 * Load CSS, only called on RFBP settings page.
	 */
	public function load_css() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style( 'rfb_admin_css', plugins_url( 'recent-facebook-posts/assets/css/admin' . $suffix . '.css' ) );
		wp_enqueue_script( 'rfb_admin_js', plugins_url( 'recent-facebook-posts/assets/js/admin' . $suffix . '.js' ), array( 'jquery' ), RFBP_VERSION, true );
	}

	/**
	 * Shows the RFBP settings page
	 */
	public function settings_page() {
		$opts = $this->settings;

		// show user-friendly error message
		if( $this->cache_cleared ) {
			 $notice = __( "<strong>Cache cleared!</strong> You succesfully cleared the cache.", 'recent-facebook-posts' );
		}

		include RFBP_PLUGIN_DIR . 'includes/views/settings_page.html.php';
	}

	/**
	 * Adds the settings link on the plugin's overview page
	 *
	 * @param array   $links Array containing all the settings links for the various plugins.
	 * @return array The new array containing all the settings links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=rfbp">'. __( 'Settings', 'recent-facebook-posts' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Ping the Facebook API for a quick test
	 *
	 * @param string $app_id
	 * @param string $app_secret
	 * @param string $page_id
	 * @return bool
	 */
	public function test_facebook_api( $app_id = '', $app_secret = '', $page_id = '' ){
		$opts = $this->settings;

		if( '' === $app_id ) {
			$app_id = $opts['app_id'];
		}

		if( '' === $app_secret ) {
			$app_secret = $opts['app_secret'];
		}

		if( '' === $page_id ) {
			$page_id = $opts['fb_id'];
		}

		require_once RFBP_PLUGIN_DIR . 'includes/class-api.php';
		$api = new RFBP_API( $app_id, $app_secret, $page_id );
		$ping = $api->ping();
		
		if( $ping ) {
			add_settings_error('rfbp', 'rfbp-api-success', __( 'Your configuration seems to be okay and working. Nice work!.', 'recent-facebook-posts' ), "updated");
		} else {
			add_settings_error('rfbp', 'rfbp-api-error', __('The following error was encountered when testing your configuration.', 'recent-facebook-posts' ) . '<br /><br />' . $api->get_error_message() );
		}

		return $ping;
	}
}
