<?php
/**
 * Listings List Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/ListingsListTraits/CustomCssTrait.php';
require_once __DIR__ . '/ListingsListTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/ListingsListTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/ListingsListTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/ListingsListTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_ListingsList
 */
class RTCL_Divi5_ListingsList implements DependencyInterface {

	use RTCL_Divi5_ListingsList_RenderCallbackTrait;
	use RTCL_Divi5_ListingsList_ModuleClassnamesTrait;
	use RTCL_Divi5_ListingsList_ModuleStylesTrait;
	use RTCL_Divi5_ListingsList_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/listings-list';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_ListingsList::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
