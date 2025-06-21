<?php

namespace RadisuTheme\ClassifiedListingToolkits\Hooks;



use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\AllLocation\AllLocation;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingCategories\ListingCategories;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsGrid\ListingsGrid;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsList\ListingsList;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsSlider\ListingsSlider;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingStore\ListingStore;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\SearchForm\SearchForm;
use RadisuTheme\ClassifiedListingToolkits\Admin\DiviModule\SingleLocation\SingleLocation;

class DiviHooks {

	/**
	 * @return void
	 */
	public static function init(): void {
		add_action( 'et_builder_ready', [ __CLASS__, 'load_modules' ], 9 );
	}

	public static function load_modules() {
		if ( ! class_exists( \ET_Builder_Element::class ) ) {
			return;
		}

		new ListingsGrid();
		new ListingsList();
		new ListingsSlider();
		new ListingCategories();
		new SingleLocation();
		new AllLocation();
		new SearchForm();

		if( defined( 'RTCL_PRO_VERSION' ) && defined('RTCL_STORE_VERSION')) {
			new ListingStore();
		}

	}

}