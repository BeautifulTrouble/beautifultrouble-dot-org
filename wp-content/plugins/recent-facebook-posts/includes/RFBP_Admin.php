<?php

class RFBP_Admin {

	public function __construct() {

		global $pagenow;

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'build_menu' ) );

		add_filter( "plugin_action_links_recent-facebook-posts/recent-facebook-posts.php", array( $this, 'add_settings_link' ) );

		// handle requests early, but only on rfb settings page
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'rfbp' ) {
			// load css
			add_action( 'admin_enqueue_scripts', array( $this, 'load_css' ) );
		}

		add_action( 'init', array( $this, 'on_init' ) );
	}

	public function on_init() {

		//delete_option('rfbp_version');

		if(version_compare(RFBP_VERSION, get_option('rfbp_version', 0))) {

			// code is newer than database, run upgrade routine
			delete_transient('rfbp_posts');
			delete_transient('rfbp_posts_fallback');

			$settings = $this->get_settings();
			if(isset($settings['link_text'])) { 
				$settings['page_link_text'] = $settings['link_text']; 
				unset($settings['link_text']);
			}
			update_option('rfb_settings', $settings);

			update_option('rfbp_version', RFBP_VERSION);
		}

		// maybe renew cache file
		if ( isset( $_POST['renew_cache'] ) ) {
			delete_transient('rfbp_posts');
			delete_transient('rfbp_posts_fallback');

			RFBP::instance()->get_posts();
		}
	}

	public function get_settings() {
		return RFBP::instance()->get_settings();
	}

	public function register_settings() {
		register_setting( 'rfb_settings_group', 'rfb_settings', array( $this, 'sanitize_settings' ) );
	}

	public function sanitize_settings( $opts ) {
		$old_opts = $this->get_settings();

		// fb config
		$opts['app_id'] = trim($opts['app_id']);
		$opts['app_secret'] = trim($opts['app_secret']);
		$opts['fb_id'] = trim($opts['fb_id']);

		if(($old_opts['fb_id'] !== $opts['fb_id']) || ($old_opts['img_size'] !== $opts['img_size']) || ($old_opts['app_id'] !== $opts['app_id']) || ($old_opts['app_secret'] !== $opts['app_secret'])) {
			add_settings_error('rfbp', 'rfbp-cache-invalid', __('Some settings have changed which invalidated the cached Facebook posts.', 'recent-facebook-posts'). '<form action="'. admin_url('options-general.php?page=rfbp') .'" method="post"><input type="hidden" name="renew_cache" value="1" /><input type="submit" class="button-primary" value="'. __('Renew Cache', 'recent-facebook-posts') .'" /></form>', "updated");
		}

		// appearance opts
		$opts['page_link_text'] = strip_tags($opts['page_link_text'], '<span><strong><b><em><i><img>');
		$opts['img_height'] = ( !empty( $opts['img_height'] ) ) ? (int) $opts['img_height'] : '';
		$opts['img_width'] = ( !empty( $opts['img_width'] ) ) ? (int) $opts['img_width'] : '';
		$opts['load_css'] = ( isset( $opts['load_css'] ) ) ? 1 : 0;
		$opts['show_links'] = isset($opts['show_links']) ? 1 : 0;

		return $opts;
	}

	public function build_menu() {
		$page = add_options_page( 'Recent Facebook Posts - Settings', 'Recent Facebook Posts', 'manage_options', 'rfbp', array( $this, 'settings_page' ) );
	}

	public function load_css() {
		wp_enqueue_style( 'rfb_admin_css', plugins_url( 'recent-facebook-posts/assets/css/admin.css' ) );
		wp_enqueue_script( 'rfb_admin_js', plugins_url( 'recent-facebook-posts/assets/js/admin.js' ), array( 'jquery' ), null, true );
	}

	public function settings_page() {

		$opts = $this->get_settings();

		$ready = (!empty($opts['app_id']) && !empty($opts['app_secret']) && !empty($opts['fb_id']));
		$api = RFBP::api();

		// show user-friendly error message
		if(RFBP::instance()->cache_renewed) {
			 $notice = __("<strong>Cache renewed!</strong> You succesfully renewed the cache.", 'recent-facebook-posts');
		}

		include_once RFBP_PLUGIN_DIR . 'includes/views/settings_page.html.php';
	}

	/**
	 * Adds the settings link on the plugin's overview page
	 *
	 * @param array   $links Array containing all the settings links for the various plugins.
	 * @return array The new array containing all the settings links
	 */
	function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=rfbp">'. __('Settings', 'recent-facebook-posts') . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}
