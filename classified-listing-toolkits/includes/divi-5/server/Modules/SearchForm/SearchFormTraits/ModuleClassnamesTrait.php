<?php
/**
 * Module Classnames Trait for Search Form.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Options\Text\TextClassnames;
use ET\Builder\Packages\Module\Options\Element\ElementClassnames;

trait RTCL_Divi5_SearchForm_ModuleClassnamesTrait {

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

		// Add orientation class.
		$orientation = $attrs['orientation']['innerContent']['desktop']['value'] ?? 'horizontal';
		if ( 'vertical' === $orientation ) {
			$classnames_instance->add( 'rtcl-search-vertical' );
		} else {
			$classnames_instance->add( 'rtcl-search-horizontal' );
		}

		// Add style class.
		$style = $attrs['style']['innerContent']['desktop']['value'] ?? 'standard';
		$classnames_instance->add( 'rtcl-style-' . $style );
	}
}
