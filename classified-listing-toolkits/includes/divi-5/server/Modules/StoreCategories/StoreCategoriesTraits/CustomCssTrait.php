<?php
/**
 * Custom CSS Trait for Store Categories.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_StoreCategories_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'storeCategoriesWrapper' => [
				'subName'        => 'storeCategoriesWrapper',
				'selectorSuffix' => ' .rtcl-store-categories-wrapper',
			],
			'storeCategoryItem'      => [
				'subName'        => 'storeCategoryItem',
				'selectorSuffix' => ' .rtcl-store-cat-item',
			],
			'storeCategoryIcon'      => [
				'subName'        => 'storeCategoryIcon',
				'selectorSuffix' => ' .rtcl-store-category-icon',
			],
			'storeCategoryImage'     => [
				'subName'        => 'storeCategoryImage',
				'selectorSuffix' => ' .rtcl-store-category-image',
			],
			'storeCategoryTitle'     => [
				'subName'        => 'storeCategoryTitle',
				'selectorSuffix' => ' .rtcl-store-category-title a',
			],
			'storeCategoryCount'     => [
				'subName'        => 'storeCategoryCount',
				'selectorSuffix' => ' .rtcl-store-category-count',
			],
			'storeCategoryDesc'      => [
				'subName'        => 'storeCategoryDesc',
				'selectorSuffix' => ' .rtcl-store-category-description',
			],
		];
	}
}
