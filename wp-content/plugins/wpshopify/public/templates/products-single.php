<?php

/*

@description   The main entry point for the 'products single' page. Used internally by the custom post type single template

@version       2.0.0
@since         1.0.49
@path          templates/products-single.php
@partials      templates/partials/products/single

@docs          https://wpshop.io/docs/templates/products-single

*/

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Utils;
use WPS\Factories;

$Templates 						= Factories\Templates_Factory::build();
$DB_Settings_General 	= Factories\DB\Settings_General_Factory::build();
$wps_product 					= $Templates->get_product_data();

get_header('wps');

if ( is_singular(WPS_PRODUCTS_POST_TYPE_SLUG) ) {

	do_action('wps_breadcrumbs');
  do_action('wps_product_single_start', $wps_product);

  do_action('wps_product_single_before', $wps_product);
  do_action('wps_product_single_gallery_start', $wps_product);

  do_action('wps_product_imgs_before', $wps_product);
  do_action('wps_product_single_imgs', $wps_product);
  do_action('wps_product_imgs_after', $wps_product);

  do_action('wps_product_single_gallery_end', $wps_product);
  do_action('wps_product_single_info_start', $wps_product);

  do_action('wps_product_single_header_before', $wps_product);
  do_action('wps_product_single_header', $wps_product);
  do_action('wps_product_single_header_after', $wps_product);

  do_action('wps_products_price_before', $wps_product);


	if ( $DB_Settings_General->get_products_compare_at() ) {
		do_action('wps_products_price_wrapper_start', $wps_product);
		do_action('wps_products_compare_at_price', $wps_product, true);
	}

  do_action('wps_products_price', $wps_product);

	if ( $DB_Settings_General->get_products_compare_at() ) {
		do_action('wps_products_price_wrapper_end', $wps_product);
	}


  do_action('wps_products_price_after', $wps_product);

  do_action('wps_product_single_content_before', $wps_product);
  do_action('wps_product_single_content', $wps_product);
  do_action('wps_product_single_content_after', $wps_product);

	// wps-product-meta wrapper
  do_action('wps_products_meta_start', $wps_product);
  do_action('wps_products_quantity', $wps_product);
  do_action('wps_product_single_actions_group_start', $wps_product);


  if ( Utils::has_available_variants($wps_product->variants) ) {

		do_action('wps_products_options', $wps_product);
    do_action('wps_products_button_add_to_cart', $wps_product);

  } else {
    do_action('wps_products_notice_out_of_stock', $wps_product);
		
  }

  do_action('wps_product_cart_buttons_after', $wps_product);
  do_action('wps_product_single_actions_group_end', $wps_product);
  do_action('wps_products_notice_inline', $wps_product);
  do_action('wps_products_meta_end', $wps_product);


  do_action('wps_product_single_info_end', $wps_product);
  do_action('wps_product_single_end', $wps_product);

  do_action('wps_product_single_after', $wps_product);

}

do_action('wps_product_single_sidebar');

get_footer('wps');
