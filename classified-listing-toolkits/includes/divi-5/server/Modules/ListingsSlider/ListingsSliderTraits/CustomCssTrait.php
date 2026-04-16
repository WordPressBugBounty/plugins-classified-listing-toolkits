<?php
/**
 * Custom CSS Trait for Listings Slider.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_ListingsSlider_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'sliderWrapper'    => [
				'subName'        => 'sliderWrapper',
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
			'sliderArrows'     => [
				'subName'        => 'sliderArrows',
				'selectorSuffix' => ' .swiper-button-next, {{selector}} .swiper-button-prev',
			],
			'sliderDots'       => [
				'subName'        => 'sliderDots',
				'selectorSuffix' => ' .swiper-pagination-bullet',
			],
		];
	}
}
