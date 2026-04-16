<?php
/**
 * Listings Grid Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/ListingsGridTraits/CustomCssTrait.php';
require_once __DIR__ . '/ListingsGridTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/ListingsGridTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/ListingsGridTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/ListingsGridTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_ListingsGrid
 */
class RTCL_Divi5_ListingsGrid implements DependencyInterface {

	use RTCL_Divi5_ListingsGrid_RenderCallbackTrait;
	use RTCL_Divi5_ListingsGrid_ModuleClassnamesTrait;
	use RTCL_Divi5_ListingsGrid_ModuleStylesTrait;
	use RTCL_Divi5_ListingsGrid_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/listings-grid';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_ListingsGrid::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
