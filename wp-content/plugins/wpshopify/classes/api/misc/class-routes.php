<?php

namespace WPS\API\Misc;


if (!defined('ABSPATH')) {
	exit;
}


class Routes extends \WPS\API {

	public $Routes;

	public function __construct($Routes) {
		$this->Routes = $Routes;
	}


  /*

	Show admin notices

	*/
	public function flush_routes() {

		$this->Routes->flush_routes();

	}


  /*

	Register route: collections_heading

	*/
  public function register_route_routes_flush() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/routes/flush', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'flush_routes']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_routes_flush']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
