<?php
/**
 * Custom CSS Trait for Listings Grid.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_ListingsGrid_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'listingsWrapper'  => [
				'subName'        => 'listingsWrapper',
				'selectorSuffix' => ' .rtcl-listings-wrapper',
			],
			'listingItem'      => [
				'subName'        => 'listingItem',
				'selectorSuffix' => ' .rtcl-listing-item',
			],
			'listingImage'     => [
				'subName'        => 'listingImage',
				'selectorSuffix' => ' .rtcl-listing-image',
			],
			'listingTitle'     => [
				'subName'        => 'listingTitle',
				'selectorSuffix' => ' .rtcl-listing-title a',
			],
			'listingPrice'     => [
				'subName'        => 'listingPrice',
				'selectorSuffix' => ' .rtcl-price',
			],
			'listingMeta'      => [
				'subName'        => 'listingMeta',
				'selectorSuffix' => ' .rtcl-listing-meta-data',
			],
			'listingCategory'  => [
				'subName'        => 'listingCategory',
				'selectorSuffix' => ' .listing-cat',
			],
			'listingBadge'     => [
				'subName'        => 'listingBadge',
				'selectorSuffix' => ' .rtcl-badge',
			],
			'pagination'       => [
				'subName'        => 'pagination',
				'selectorSuffix' => ' .rtcl-pagination',
			],
		];
	}
}
