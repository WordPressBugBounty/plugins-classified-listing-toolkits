<?php
/**
 * Module Script Data Trait for Single Location.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait RTCL_Divi5_SingleLocation_ModuleScriptDataTrait {

	/**
	 * Generate module script data.
	 *
	 * @param array $args Arguments.
	 */
	public static function module_script_data( $args ) {
		$elements = $args['elements'];

		// Add module script data.
		$elements->script_data(
			[
				'attrName' => 'module',
			]
		);
	}
}
