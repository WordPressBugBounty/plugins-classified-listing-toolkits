<?php
/**
 * Custom CSS Trait for All Locations.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_AllLocations_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'locationsWrapper' => [
				'subName'        => 'locationsWrapper',
				'selectorSuffix' => ' .rtcl-all-locations-wrapper',
			],
			'locationItem'     => [
				'subName'        => 'locationItem',
				'selectorSuffix' => ' .rtcl-location-item',
			],
			'locationTitle'    => [
				'subName'        => 'locationTitle',
				'selectorSuffix' => ' .rtcl-location-title a',
			],
			'locationCount'    => [
				'subName'        => 'locationCount',
				'selectorSuffix' => ' .rtcl-location-count',
			],
			'locationDesc'     => [
				'subName'        => 'locationDesc',
				'selectorSuffix' => ' .rtcl-location-description',
			],
		];
	}
}
