<?php
/**
 * Custom CSS Trait for Listing Store.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_ListingStore_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'storeWrapper'     => [
				'subName'        => 'storeWrapper',
				'selectorSuffix' => ' .rtcl-stores-wrapper',
			],
			'storeItem'        => [
				'subName'        => 'storeItem',
				'selectorSuffix' => ' .rtcl-store-item',
			],
			'storeImage'       => [
				'subName'        => 'storeImage',
				'selectorSuffix' => ' .rtcl-store-image',
			],
			'storeName'        => [
				'subName'        => 'storeName',
				'selectorSuffix' => ' .rtcl-store-name a',
			],
			'storeDescription' => [
				'subName'        => 'storeDescription',
				'selectorSuffix' => ' .rtcl-store-description',
			],
			'storeListings'    => [
				'subName'        => 'storeListings',
				'selectorSuffix' => ' .rtcl-store-listings-count',
			],
			'storeContact'     => [
				'subName'        => 'storeContact',
				'selectorSuffix' => ' .rtcl-store-contact',
			],
			'storeSocial'      => [
				'subName'        => 'storeSocial',
				'selectorSuffix' => ' .rtcl-store-social-links',
			],
			'storeButton'      => [
				'subName'        => 'storeButton',
				'selectorSuffix' => ' .rtcl-store-button',
			],
		];
	}
}
