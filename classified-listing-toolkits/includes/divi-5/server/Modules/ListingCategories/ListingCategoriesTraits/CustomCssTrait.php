<?php
/**
 * Custom CSS Trait for Listing Categories.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_ListingCategories_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'categoriesWrapper' => [
				'subName'        => 'categoriesWrapper',
				'selectorSuffix' => ' .rtcl-categories-wrapper',
			],
			'categoryItem'      => [
				'subName'        => 'categoryItem',
				'selectorSuffix' => ' .rtcl-category-item',
			],
			'categoryIcon'      => [
				'subName'        => 'categoryIcon',
				'selectorSuffix' => ' .rtcl-category-icon',
			],
			'categoryImage'     => [
				'subName'        => 'categoryImage',
				'selectorSuffix' => ' .rtcl-category-image',
			],
			'categoryTitle'     => [
				'subName'        => 'categoryTitle',
				'selectorSuffix' => ' .rtcl-category-title a',
			],
			'categoryCount'     => [
				'subName'        => 'categoryCount',
				'selectorSuffix' => ' .rtcl-category-count',
			],
			'categoryDesc'      => [
				'subName'        => 'categoryDesc',
				'selectorSuffix' => ' .rtcl-category-description',
			],
		];
	}
}
