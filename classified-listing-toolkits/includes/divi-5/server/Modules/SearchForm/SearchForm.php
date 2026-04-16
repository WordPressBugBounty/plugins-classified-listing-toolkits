<?php
/**
 * Search Form Module class for Divi 5.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

// Load traits.
require_once __DIR__ . '/SearchFormTraits/CustomCssTrait.php';
require_once __DIR__ . '/SearchFormTraits/RenderCallbackTrait.php';
require_once __DIR__ . '/SearchFormTraits/ModuleClassnamesTrait.php';
require_once __DIR__ . '/SearchFormTraits/ModuleStylesTrait.php';
require_once __DIR__ . '/SearchFormTraits/ModuleScriptDataTrait.php';

/**
 * Class RTCL_Divi5_SearchForm
 */
class RTCL_Divi5_SearchForm implements DependencyInterface {

	use RTCL_Divi5_SearchForm_RenderCallbackTrait;
	use RTCL_Divi5_SearchForm_ModuleClassnamesTrait;
	use RTCL_Divi5_SearchForm_ModuleStylesTrait;
	use RTCL_Divi5_SearchForm_ModuleScriptDataTrait;

	/**
	 * Load the module.
	 */
	public function load() {
		$module_json_folder_path = RTCL_TOOLKITS_DIVI5_PATH . 'visual-builder/modules/search-form';

		add_action(
			'init',
			function () use ( $module_json_folder_path ) {
				ModuleRegistration::register_module(
					$module_json_folder_path,
					[
						'render_callback' => [ RTCL_Divi5_SearchForm::class, 'render_callback' ],
					]
				);
			}
		);
	}
}
