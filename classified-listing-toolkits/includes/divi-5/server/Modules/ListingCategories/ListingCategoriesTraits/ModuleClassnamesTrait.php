<?php
/**
 * Module Classnames Trait for Listing Categories.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Options\Text\TextClassnames;
use ET\Builder\Packages\Module\Options\Element\ElementClassnames;

trait RTCL_Divi5_ListingCategories_ModuleClassnamesTrait {

	/**
	 * Generate module classnames.
	 *
	 * @param array $args Arguments.
	 */
	public static function module_classnames( $args ) {
		$classnames_instance = $args['classnamesInstance'];
		$attrs               = $args['attrs'];

		// Add text classnames.
		$classnames_instance->add(
			TextClassnames::text_options_classnames(
				$attrs['module']['advanced']['text'] ?? []
			),
			true
		);

		// Add element classnames.
		$classnames_instance->add(
			ElementClassnames::classnames(
				[
					'attrs' => $attrs['module']['decoration'] ?? [],
				]
			)
		);
	}
}
