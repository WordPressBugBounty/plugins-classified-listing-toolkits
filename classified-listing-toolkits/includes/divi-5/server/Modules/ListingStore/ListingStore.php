<?php
/**
 * Listing Store Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/ListingStoreTraits/CustomCssTrait.php';
require_once __DIR__ . '/ListingStoreTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/ListingStoreTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/ListingStoreTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/ListingStoreTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_ListingStore
 */
class RTCL_Divi5_ListingStore implements DependencyInterface {

	use RTCL_Divi5_ListingStore_RenderCallbackTrait;
	use RTCL_Divi5_ListingStore_ModuleClassnamesTrait;
	use RTCL_Divi5_ListingStore_ModuleStylesTrait;
	use RTCL_Divi5_ListingStore_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		// Check if both RTCL Pro and RTCL Store plugins are active.
		if ( ! defined( 'RTCL_PRO_VERSION' ) || ! defined( 'RTCL_STORE_VERSION' ) ) {
			return;
		}

		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/listing-store';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_ListingStore::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
