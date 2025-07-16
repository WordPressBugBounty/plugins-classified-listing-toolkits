<?php

namespace RadiusTheme\ClassifiedListingToolkits\Hooks;


use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\AllLocation\AllLocation;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingCategories\ListingCategories;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsGrid\ListingsGrid;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsList\ListingsList;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsSlider\ListingsSlider;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingStore\ListingStore;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\SearchForm\SearchForm;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\SingleLocation\SingleLocation;

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
		new ListingCategories();
		new SingleLocation();
		new AllLocation();
		new SearchForm();

		if ( Helper::is_pro_with_old_dependency() ) {
			new ListingsSlider();
		}

		if ( defined( 'RTCL_PRO_VERSION' ) && defined( 'RTCL_STORE_VERSION' ) ) {
			new ListingStore();
		}

	}

}