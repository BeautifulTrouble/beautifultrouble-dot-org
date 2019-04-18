<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

class Posts_Collections_Custom extends \WPS\Processing\Posts {

	protected $action = 'wps_background_processing_posts_collections_custom';

	protected $DB_Settings_Syncing;
	protected $CPT_Query;
	public $meta;

	public function __construct($DB_Settings_Syncing, $CPT_Query) {

		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->CPT_Query 									= $CPT_Query;

		parent::__construct($DB_Settings_Syncing, $CPT_Query);

	}

}
