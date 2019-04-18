<?php

namespace WPS;

use WPS\Utils;
use WPS\Utils\Server;

if (!defined('ABSPATH')) {
	exit;
}


class Frontend {

	private $Settings_General;
	private $Settings_Connection;
	private $Settings_Shop;


	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Settings_General, $Settings_Connection, $Settings_Shop) {

		$this->Settings_General 		= $Settings_General;
		$this->Settings_Connection	= $Settings_Connection;
		$this->Settings_Shop 				= $Settings_Shop;

	}


	/*

	Public styles

	*/
	public function public_styles() {

		if (!is_admin()) {

			$styles_all = $this->Settings_General->get_column_single('styles_all');
			$styles_core = $this->Settings_General->get_column_single('styles_core');
			$styles_grid = $this->Settings_General->get_column_single('styles_grid');

			if (is_array($styles_all)) {

				if (Utils::array_not_empty($styles_all) && isset($styles_all[0]->styles_all)) {

					wp_enqueue_style(
						WPS_PLUGIN_TEXT_DOMAIN . '-styles-frontend-all',
						WPS_PLUGIN_URL . 'dist/public.min.css',
						[],
						filemtime( WPS_PLUGIN_DIR_PATH . 'dist/public.min.css' ),
						'all'
					);

				} else {

					if (Utils::array_not_empty($styles_all) && isset($styles_core[0]->styles_core)) {

						wp_enqueue_style(
							WPS_PLUGIN_TEXT_DOMAIN . '-styles-frontend-core',
							WPS_PLUGIN_URL . 'dist/core.min.css',
							[],
							filemtime( WPS_PLUGIN_DIR_PATH . 'dist/core.min.css' ),
							'all'
						);

					}

					if (Utils::array_not_empty($styles_all) && isset($styles_grid[0]->styles_grid)) {

						wp_enqueue_style(
							WPS_PLUGIN_TEXT_DOMAIN . '-styles-frontend-grid',
							WPS_PLUGIN_URL . 'dist/grid.min.css',
							[],
							filemtime( WPS_PLUGIN_DIR_PATH . 'dist/grid.min.css' ),
							'all'
						);

					}

				}

			}

		}

	}


	/*

	Public scripts

	*/
	public function public_scripts() {

		if (get_transient('wps_connection_connected')) {
			$connected = get_transient('wps_connection_connected');

		} else {

			set_transient('wps_connection_connected', $this->Settings_Connection->has_connection());

			$connected = get_transient('wps_connection_connected');

		}


		if (!is_admin()) {

			global $post;

			wp_enqueue_script(
				'anime-js',
				WPS_PLUGIN_URL . 'public/js/vendor/anime.min.js',
				[],
				filemtime( WPS_PLUGIN_DIR_PATH . 'public/js/vendor/anime.min.js' )
			);

			wp_enqueue_script(
				'promise-polyfill',
				WPS_PLUGIN_URL . 'public/js/vendor/es6-promise.auto.min.js',
				['jquery'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'public/js/vendor/es6-promise.auto.min.js' )
         );
         
         wp_enqueue_script(
            'fetch-polyfill', 
            WPS_PLUGIN_URL . 'public/js/vendor/fetch.umd.js', 
            ['promise-polyfill'], 
            filemtime(WPS_PLUGIN_DIR_PATH . 'public/js/vendor/fetch.umd.js')
         );

			// Commonly shared third-party libs first ...
			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-common',
				WPS_PLUGIN_URL . 'dist/vendors-admin-public.min.js',
				['promise-polyfill', 'fetch-polyfill'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/vendors-admin-public.min.js' )
			);


			// Public third-party libs second ...
			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-public',
				WPS_PLUGIN_URL . 'dist/vendors-public.min.js',
				['promise-polyfill', 'fetch-polyfill'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/vendors-public.min.js' )
			);

			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-frontend',
				WPS_PLUGIN_URL . 'dist/public.min.js',
				[
					'jquery',
               'promise-polyfill',
               'fetch-polyfill',
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-common',
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-public'
				],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/public.min.js' )
			);

			wp_localize_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-frontend',
				WPS_PLUGIN_NAME_JS,
				[
					'ajax' 										=> apply_filters('wps_admin_ajax_url', esc_url( Utils::convert_to_relative_url(admin_url('admin-ajax.php')) )),
					'pluginsPath' 						=> esc_url(plugins_url()),
					'pluginsDirURL' 					=> plugin_dir_url(dirname(__FILE__)),
					'productsSlug' 						=> $this->Settings_General->products_slug(),
					'is_connected' 						=> $connected,
					'post_id' 								=> is_object($post) ? $post->ID : false,
					'nonce'										=> wp_create_nonce(WPS_FRONTEND_NONCE_ACTION),
					'note_attributes'					=> '',
					'nonce_api'								=> wp_create_nonce('wp_rest'),
					'checkoutAttributes' 			=> [],
					'hasCartTerms' 						=> $this->Settings_General->enable_cart_terms(),
					'settings'								=> [
						'hasCurrencyCode' 						=> $this->Settings_General->get_price_with_currency(),
						'enableCustomCheckoutDomain' 	=> $this->Settings_General->get_enable_custom_checkout_domain(),
						'myShopifyDomain' 						=> $this->Settings_Connection->get_domain(),
						'checkoutButtonTarget'				=> $this->Settings_General->get_col_value('checkout_button_target', 'string'),
						'itemsLinkToShopify'					=> $this->Settings_General->get_col_value('products_link_to_shopify', 'bool'),
						'pricing' => [
							'baseCurrencyCode' 			=> $this->Settings_Shop->get_col_value('currency', 'string'),
							'enableLocalCurrency' 	=> $this->Settings_General->get_col_value('pricing_local_currency_toggle', 'bool')
						],
						'currentLocale' => Server::get_current_locale()
					],
					'API' => [
						'namespace'			=> WPS_SHOPIFY_API_NAMESPACE,
						'baseUrl' 			=> site_url(),
						'urlPrefix'			=> rest_get_url_prefix(),
						'restUrl'				=> get_rest_url()
					]
				]
			);

		}


	}


	/*

	Only hooks not meant for public consumption

	*/
	public function hooks() {

		add_action('wp_enqueue_scripts', [$this, 'public_styles']);
		add_action('wp_enqueue_scripts', [$this, 'public_scripts']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
