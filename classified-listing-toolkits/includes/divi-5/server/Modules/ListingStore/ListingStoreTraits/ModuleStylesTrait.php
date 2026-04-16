<?php
/**
 * Module Styles Trait for Listing Store.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_ListingStore_ModuleStylesTrait {

	use RTCL_Divi5_ListingStore_CustomCssTrait;

	/**
	 * Generate module styles.
	 *
	 * @param array $args Arguments.
	 */
	public static function module_styles( $args ) {
		$attrs    = $args['attrs'] ?? [];
		$elements = $args['elements'];
		$settings = $args['settings'] ?? [];

		Style::add(
			[
				'id'            => $args['id'],
				'name'          => $args['name'],
				'orderIndex'    => $args['orderIndex'],
				'storeInstance' => $args['storeInstance'],
				'styles'        => [
					// Element: Module.
					$elements->style(
						[
							'attrName'   => 'module',
							'styleProps' => [
								'disabledOn' => [
									'disabledModuleVisibility' => $settings['disabledModuleVisibility'] ?? null,
								],
							],
						]
					),
					CssStyle::style(
						[
							'selector'  => $args['orderClass'],
							'attr'      => $attrs['css'] ?? [],
							'cssFields' => self::custom_css(),
						]
					),
					TextStyle::style(
						[
							'selector' => "{$args['orderClass']} .rtcl-stores-wrapper",
							'attr'     => $attrs['module']['advanced']['text'] ?? [],
						]
					),

					// Element: Store Name.
					$elements->style(
						[
							'attrName' => 'storeName',
						]
					),

					// Element: Store Description.
					$elements->style(
						[
							'attrName' => 'storeDescription',
						]
					),

				],
			]
		);
	}
}
