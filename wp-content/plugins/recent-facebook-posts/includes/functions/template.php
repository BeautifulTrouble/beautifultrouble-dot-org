<?php

if( ! defined( 'RFBP_VERSION' ) ) {
	exit;
}

/**
 * Prints a list of Recent Facebook Posts
 *
 * Accepted arguments are the same as the shortcode args
 *
 * - number: 5
 * - likes: true
 * - comments: true
 * - excerpt_length: 140
 * - el: div
 * - origin: shortcode
 * - show_page_link: false
 * - show_link_preview: false
 *
 * @param array $args
 * @return void
 */
function recent_facebook_posts( $args = array() ) {
	echo RFBP_Public::instance()->output( $args );
}