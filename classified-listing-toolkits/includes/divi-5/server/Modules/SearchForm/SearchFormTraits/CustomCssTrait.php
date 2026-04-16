<?php
/**
 * Custom CSS Trait for Search Form.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_SearchForm_CustomCssTrait {

	/**
	 * Get custom CSS fields.
	 *
	 * @return array
	 */
	public static function custom_css() {
		return [
			'searchFormWrapper' => [
				'subName'        => 'searchFormWrapper',
				'selectorSuffix' => ' .rtcl-search-form-wrapper',
			],
			'formFields'        => [
				'subName'        => 'formFields',
				'selectorSuffix' => ' .rtcl-search-fields',
			],
			'inputField'        => [
				'subName'        => 'inputField',
				'selectorSuffix' => ' .rtcl-search-input',
			],
			'selectField'       => [
				'subName'        => 'selectField',
				'selectorSuffix' => ' .rtcl-search-select',
			],
			'fieldLabel'        => [
				'subName'        => 'fieldLabel',
				'selectorSuffix' => ' .rtcl-field-label',
			],
			'submitButton'      => [
				'subName'        => 'submitButton',
				'selectorSuffix' => ' .rtcl-search-submit',
			],
			'priceRange'        => [
				'subName'        => 'priceRange',
				'selectorSuffix' => ' .rtcl-price-range',
			],
		];
	}
}
