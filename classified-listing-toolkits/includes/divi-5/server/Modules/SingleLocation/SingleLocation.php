<?php
/**
 * Single Location Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/SingleLocationTraits/CustomCssTrait.php';
require_once __DIR__ . '/SingleLocationTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/SingleLocationTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/SingleLocationTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/SingleLocationTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_SingleLocation
 */
class RTCL_Divi5_SingleLocation implements DependencyInterface {

	use RTCL_Divi5_SingleLocation_RenderCallbackTrait;
	use RTCL_Divi5_SingleLocation_ModuleClassnamesTrait;
	use RTCL_Divi5_SingleLocation_ModuleStylesTrait;
	use RTCL_Divi5_SingleLocation_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/single-location';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_SingleLocation::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
