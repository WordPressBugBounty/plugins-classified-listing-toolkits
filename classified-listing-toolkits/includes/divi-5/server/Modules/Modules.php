<?php
/**
 * All modules registration for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;

// Register modules with Divi 5's dependency tree.
// Module classes are loaded inside the callback when Divi's autoloader is available.
add_action(
	'divi_module_library_modules_dependency_tree',
	function ( $dependency_tree ) {
		// Load core module classes.
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/ListingsGrid/ListingsGrid.php';
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/ListingsList/ListingsList.php';
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/ListingCategories/ListingCategories.php';
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/AllLocations/AllLocations.php';
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/SingleLocation/SingleLocation.php';
		require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/SearchForm/SearchForm.php';

		// Register core modules.
		$dependency_tree->add_dependency( new RTCL_Divi5_ListingsGrid() );
		$dependency_tree->add_dependency( new RTCL_Divi5_ListingsList() );
		$dependency_tree->add_dependency( new RTCL_Divi5_ListingCategories() );
		$dependency_tree->add_dependency( new RTCL_Divi5_AllLocations() );
		$dependency_tree->add_dependency( new RTCL_Divi5_SingleLocation() );
		$dependency_tree->add_dependency( new RTCL_Divi5_SearchForm() );

		// Listings Slider (requires Classified Listing Pro).
		if ( defined( 'RTCL_PRO_VERSION' ) ) {
			require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/ListingsSlider/ListingsSlider.php';
			$dependency_tree->add_dependency( new RTCL_Divi5_ListingsSlider() );
		}

		// Conditionally load Listing Store (requires both RTCL Pro and RTCL Store).
		if ( defined( 'RTCL_PRO_VERSION' ) && defined( 'RTCL_STORE_VERSION' ) ) {
			require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/ListingStore/ListingStore.php';
			$dependency_tree->add_dependency( new RTCL_Divi5_ListingStore() );
		}

		// Conditionally load Store Categories (requires both RTCL Pro and RTCL Store).
		if ( defined( 'RTCL_PRO_VERSION' ) && defined( 'RTCL_STORE_VERSION' ) ) {
			require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/StoreCategories/StoreCategories.php';
			$dependency_tree->add_dependency( new RTCL_Divi5_StoreCategories() );
		}
	}
);
