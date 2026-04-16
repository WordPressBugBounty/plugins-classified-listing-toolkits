<?php
/**
 * Listings Slider Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/ListingsSliderTraits/CustomCssTrait.php';
require_once __DIR__ . '/ListingsSliderTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/ListingsSliderTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/ListingsSliderTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/ListingsSliderTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_ListingsSlider
 */
class RTCL_Divi5_ListingsSlider implements DependencyInterface {

	use RTCL_Divi5_ListingsSlider_RenderCallbackTrait;
	use RTCL_Divi5_ListingsSlider_ModuleClassnamesTrait;
	use RTCL_Divi5_ListingsSlider_ModuleStylesTrait;
	use RTCL_Divi5_ListingsSlider_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/listings-slider';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_ListingsSlider::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
