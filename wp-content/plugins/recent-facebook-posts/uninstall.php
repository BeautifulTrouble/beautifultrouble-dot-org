<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

delete_transient('rfbp_posts');
delete_transient('rfbp_posts_fallback');

delete_option('rfb_settings');