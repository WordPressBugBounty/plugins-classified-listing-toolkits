<?php
/**
 * Listing Categories Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/ListingCategoriesTraits/CustomCssTrait.php';
require_once __DIR__ . '/ListingCategoriesTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/ListingCategoriesTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/ListingCategoriesTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/ListingCategoriesTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_ListingCategories
 */
class RTCL_Divi5_ListingCategories implements DependencyInterface {

	use RTCL_Divi5_ListingCategories_RenderCallbackTrait;
	use RTCL_Divi5_ListingCategories_ModuleClassnamesTrait;
	use RTCL_Divi5_ListingCategories_ModuleStylesTrait;
	use RTCL_Divi5_ListingCategories_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/listing-categories';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_ListingCategories::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
