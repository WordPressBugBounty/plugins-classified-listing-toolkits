<?php
/**
 * Custom CSS Trait for Single Location.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_SingleLocation_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'locationWrapper' => [
				'subName'        => 'locationWrapper',
				'selectorSuffix' => ' .rtcl-single-location',
			],
			'locationInner'   => [
				'subName'        => 'locationInner',
				'selectorSuffix' => ' .rtcl-single-location-inner',
			],
			'locationTitle'   => [
				'subName'        => 'locationTitle',
				'selectorSuffix' => ' .rtcl-location-name',
			],
			'locationCount'   => [
				'subName'        => 'locationCount',
				'selectorSuffix' => ' .rtcl-location-listing-count',
			],
			'locationContent' => [
				'subName'        => 'locationContent',
				'selectorSuffix' => ' .rtcl-location-content',
			],
		];
	}
}
