<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "What you doin' in here?" );
}

/**
 * Adds a recommendation to upgrade to other plugins.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 */
class User_Photo_Upgrade_Notice {

	/**
	 * Set constants.
	 */
	const NO_BUG_OPTION = 'user-photo-no-bug';

	/**
	 * Fire the constructor up :)
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'add_notice' ), 5 );
		add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
	}

	/**
	 * Add admin notice if user hasn't requested not to see it yet.
	 */
	public function add_notice() {

		if ( true != get_site_option( self::NO_BUG_OPTION ) ) {
			add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
		}

	}

	/**
	 * Display Admin Notice, asking for a review.
	 */
	public function display_admin_notice() {

		$screen = get_current_screen(); 
		if ( isset( $screen->base ) && 'plugins' == $screen->base ) {

			$no_bug_url = wp_nonce_url( admin_url( '?' . self::NO_BUG_OPTION . '=true' ), 'user-photo-nonce' );

			echo '
			<div class="updated">

				<p>
					The User Photo plugin is out of date and no longer maintained. We recommend switching to the 
					<a href="https://wordpress.org/plugins/metronet-profile-picture/">User Profile Picture</a> plugin 
					by <a href="https://ronalfy.com/">Ronalfy</a>.
				</p>
				<p>
					<strong><a href="' . esc_url( $no_bug_url ) . '">' . __( "Don't show me this message again.", 'user-photo' ) . '</a></strong>
				</p>
			</div>';

		}

	}

	/**
	 * Set the plugin to no longer bug users if user asks not to be.
	 */
	public function set_no_bug() {

		// Bail out if not on correct page
		if (
			! isset( $_GET['_wpnonce'] )
			||
			(
				! wp_verify_nonce( $_GET['_wpnonce'], 'user-photo-nonce' )
				||
				! is_admin()
				||
				! isset( $_GET[self::NO_BUG_OPTION] )
				||
				! current_user_can( 'manage_options' )
			)
		) {
			return;
		}

		add_site_option( self::NO_BUG_OPTION, true );

	}

}
new User_Photo_Upgrade_Notice();
