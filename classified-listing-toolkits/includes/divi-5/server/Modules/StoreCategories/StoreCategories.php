<?php
/**
 * Store Categories Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/StoreCategoriesTraits/CustomCssTrait.php';
require_once __DIR__ . '/StoreCategoriesTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/StoreCategoriesTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/StoreCategoriesTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/StoreCategoriesTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_StoreCategories
 */
class RTCL_Divi5_StoreCategories implements DependencyInterface {

	use RTCL_Divi5_StoreCategories_RenderCallbackTrait;
	use RTCL_Divi5_StoreCategories_ModuleClassnamesTrait;
	use RTCL_Divi5_StoreCategories_ModuleStylesTrait;
	use RTCL_Divi5_StoreCategories_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/store-categories';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_StoreCategories::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
