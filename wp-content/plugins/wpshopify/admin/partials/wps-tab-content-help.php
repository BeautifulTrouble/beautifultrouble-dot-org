<?php

use WPS\Options;

?>

<!--

Tab Content: Help / Debug

-->

<div class="tab-content <?php echo $active_tab === 'tab-help' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-help">

  <div class="wps-admin-section">

    <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-sos"></span> <?php esc_html_e('Help', WPS_PLUGIN_TEXT_DOMAIN); ?></h3>

    <p><?php printf(__('Need help finding your Shopify API keys? Watch our <a href="%s" target="_blank"> video tutorial</a>.', WPS_PLUGIN_TEXT_DOMAIN), esc_url('https://www.youtube.com/watch?v=lYm6G35e8sI'));  ?></p>

    <p><?php printf(__('If you\'re running into problems, please review our exhaustive <a href="%1$s" target="_blank">documentation</a>. If you\'re not able to find the solution you can also send us an email to <a href="%2$s">hello@wpshop.io</a> with the problem you\'re having. The more detailed you are the better we can help! Also if you\'ve purchased a <a href="%3$s" target="_blank">WP Shopify Pro</a> license you can join our private Slack channel to receive dedicated live support. ', WPS_PLUGIN_TEXT_DOMAIN), esc_url("https://wpshop.io/docs"), esc_url("mailto:hello@wpshop.io"), esc_url("https://wpshop.io/purchase")); ?></p>
    <p><?php printf(__('WP Shopify documentation can be found here: <a href="%1$s" target="_blank">%2$s</a>', WPS_PLUGIN_TEXT_DOMAIN), esc_url(WPS_PLUGIN_ENV . "/docs"), esc_url(WPS_PLUGIN_ENV . "/docs")); ?></p>

    <textarea name="name" rows="30" cols="95" readonly>

<?php


// TODO: Clean this up and restructure
// We currently don't _need_ to translate the below content since they'll be sending it to me for debug purposes

function open_ssl_enabled() {

  if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
    return true;
  } else {
    return false;
  }

}


function print_plugin_details( $plugin_path, $suffix = '' ) {
  $plugin_data = get_plugin_data( $plugin_path );
  if ( empty( $plugin_data['Name'] ) ) {
    return;
  }

  printf( "%s%s (v%s) by %s\r\n", $plugin_data['Name'], $suffix, $plugin_data['Version'], $plugin_data['AuthorName'] );
}


global $wpdb;
$table_prefix = $wpdb->base_prefix;

echo 'site_url(): ';
echo esc_html( site_url() );
echo "\r\n";

echo 'home_url(): ';
echo esc_html( home_url() );
echo "\r\n";

echo 'Database Name: ';
echo esc_html( $wpdb->dbname );
echo "\r\n";

echo 'Table Prefix: ';
echo esc_html( $table_prefix );
echo "\r\n";

echo 'WordPress: ';
echo bloginfo( 'version' );
if ( is_multisite() ) {
  $multisite_type = defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL ? 'Sub-domain' : 'Sub-directory';
  echo ' Multisite (' . $multisite_type . ')';
  echo "\r\n";

  if ( defined( 'DOMAIN_CURRENT_SITE' ) ) {
    echo 'Domain Current Site: ';
    echo DOMAIN_CURRENT_SITE;
    echo "\r\n";
  }

  if ( defined( 'PATH_CURRENT_SITE' ) ) {
    echo 'Path Current Site: ';
    echo PATH_CURRENT_SITE;
    echo "\r\n";
  }

  if ( defined( 'SITE_ID_CURRENT_SITE' ) ) {
    echo 'Site ID Current Site: ';
    echo SITE_ID_CURRENT_SITE;
    echo "\r\n";
  }

  if ( defined( 'BLOG_ID_CURRENT_SITE' ) ) {
    echo 'Blog ID Current Site: ';
    echo BLOG_ID_CURRENT_SITE;
  }
}
echo "\r\n";

echo 'Web Server: ';
echo esc_html( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '' );
echo "\r\n";

echo 'PHP: ';
if ( function_exists( 'phpversion' ) ) {
  echo esc_html( phpversion() );
}
echo "\r\n";

echo 'MySQL: ';
echo esc_html( empty( $wpdb->use_mysqli ) ? mysql_get_server_info() : mysqli_get_server_info( $wpdb->dbh ) );


echo "\r\n";

echo 'ext/mysqli: ';
echo empty( $wpdb->use_mysqli ) ? 'no' : 'yes';
echo "\r\n";

echo 'WP Memory Limit: ';
echo esc_html( WP_MEMORY_LIMIT );
echo "\r\n";

echo 'Blocked External HTTP Requests: ';
if ( ! defined( 'WP_HTTP_BLOCK_EXTERNAL' ) || ! WP_HTTP_BLOCK_EXTERNAL ) {
  echo 'None';
} else {
  $accessible_hosts = ( defined( 'WP_ACCESSIBLE_HOSTS' ) ) ? WP_ACCESSIBLE_HOSTS : '';

  if ( empty( $accessible_hosts ) ) {
    echo 'ALL';
  } else {
    echo 'Partially (Accessible Hosts: ' . esc_html( $accessible_hosts ) . ')';
  }
}
echo "\r\n";

echo 'WP Locale: ';
echo esc_html( get_locale() );
echo "\r\n";

echo 'DB Charset: ';
echo esc_html( DB_CHARSET );
echo "\r\n";


if ( function_exists( 'ini_get' ) && $suhosin_limit = ini_get( 'suhosin.post.max_value_length' ) ) {
  echo 'Suhosin Post Max Value Length: ';
  echo esc_html( is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit );
  echo "\r\n";
}

if ( function_exists( 'ini_get' ) && $suhosin_limit = ini_get( 'suhosin.request.max_value_length' ) ) {
  echo 'Suhosin Request Max Value Length: ';
  echo esc_html( is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit );
  echo "\r\n";
}

echo 'Debug Mode: ';
echo esc_html( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No' );
echo "\r\n";

echo 'WP Max Upload Size: ';
echo esc_html( size_format( wp_max_upload_size() ) );
echo "\r\n";

// echo 'PHP Post Max Size: ';
// echo esc_html( size_format( $this->get_post_max_size() ) );
// echo "\r\n";

echo 'PHP Max Execution Time: ';
if ( function_exists( 'ini_get' ) ) {
  echo esc_html( ini_get( 'max_execution_time' ) );
}
echo "\r\n";

echo 'PHP Error Log: ';
if ( function_exists( 'ini_get' ) ) {
  echo esc_html( ini_get( 'error_log' ) );
}
echo "\r\n";

echo 'fsockopen: ';
if ( function_exists( 'fsockopen' ) ) {
  echo 'Enabled';
} else {
  echo 'Disabled';
}
echo "\r\n";

echo 'OpenSSL: ';
if ( open_ssl_enabled() ) {
  echo esc_html( OPENSSL_VERSION_TEXT );
} else {
  echo 'Disabled';
}
echo "\r\n";

echo 'cURL: ';
if ( function_exists( 'curl_init' ) ) {
  echo 'Enabled';
} else {
  echo 'Disabled';
}
echo "\r\n";


echo 'Compatibility Mode: ';
if ( isset( $GLOBALS['wpmdb_compatibility'] ) ) {
  echo 'Yes';
} else {
  echo 'No';
}
echo "\r\n";

// echo 'Delay Between Requests: ';
// $delay_between_requests = $this->settings['delay_between_requests'];
// $delay_between_requests = $delay_between_requests > 0 ? $delay_between_requests / 1000 : $delay_between_requests;
// echo esc_html( $delay_between_requests ) . ' s';
// echo "\r\n\r\n";

do_action( 'wpmdb_diagnostic_info' );
if ( has_action( 'wpmdb_diagnostic_info' ) ) {
  echo "\r\n";
}

$theme_info = wp_get_theme();
echo "Active Theme Name: " . esc_html( $theme_info->Name ) . "\r\n";
echo "Active Theme Folder: " . esc_html( basename( $theme_info->get_stylesheet_directory() ) ) . "\r\n";
if ( $theme_info->get( 'Template' ) ) {
  echo "Parent Theme Folder: " . esc_html( $theme_info->get( 'Template' ) ) . "\r\n";
}
if ( ! file_exists( $theme_info->get_stylesheet_directory() ) ) {
  echo "WARNING: Active Theme Folder Not Found\r\n";
}

echo "\r\n";

echo "Active Plugins:\r\n";

// if ( isset( $GLOBALS['wpmdb_compatibility'] ) ) {
//   remove_filter( 'option_active_plugins', 'wpmdbc_exclude_plugins' );
//   remove_filter( 'site_option_active_sitewide_plugins', 'wpmdbc_exclude_site_plugins' );
//   $blacklist = array_flip( (array) $this->settings['blacklist_plugins'] );
// } else {
//   $blacklist = array();
// }

$active_plugins = (array) Options::get('active_plugins', []);

// if ( is_multisite() ) {
//   $network_active_plugins = wp_get_active_network_plugins();
//   $active_plugins         = array_map( array( $this, 'remove_wp_plugin_dir' ), $network_active_plugins );
// }

foreach ( $active_plugins as $plugin ) {
  $suffix = ( isset( $blacklist[ $plugin ] ) ) ? '*' : '';
  print_plugin_details( WP_PLUGIN_DIR . '/' . $plugin, $suffix );
}

if ( isset( $GLOBALS['wpmdb_compatibility'] ) ) {
  add_filter( 'option_active_plugins', 'wpmdbc_exclude_plugins' );
  add_filter( 'site_option_active_sitewide_plugins', 'wpmdbc_exclude_site_plugins' );
}

$mu_plugins = wp_get_mu_plugins();
if ( $mu_plugins ) {
  echo "\r\n";

  echo "Must-use Plugins:\r\n";

  foreach ( $mu_plugins as $mu_plugin ) {
    print_plugin_details( $mu_plugin );
  }

  echo "\r\n";
}




?>

    </textarea>


  </div>

</div>
