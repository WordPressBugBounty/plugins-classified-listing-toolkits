<?php
/**
 * All Locations Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/AllLocationsTraits/CustomCssTrait.php';
require_once __DIR__ . '/AllLocationsTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/AllLocationsTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/AllLocationsTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/AllLocationsTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_AllLocations
 */
class RTCL_Divi5_AllLocations implements DependencyInterface {

	use RTCL_Divi5_AllLocations_RenderCallbackTrait;
	use RTCL_Divi5_AllLocations_ModuleClassnamesTrait;
	use RTCL_Divi5_AllLocations_ModuleStylesTrait;
	use RTCL_Divi5_AllLocations_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/all-locations';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_AllLocations::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
