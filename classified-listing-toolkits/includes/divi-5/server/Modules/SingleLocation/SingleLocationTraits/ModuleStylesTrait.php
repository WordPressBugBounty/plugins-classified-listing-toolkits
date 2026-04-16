<?php
/**
 * Module Styles Trait for Single Location.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_SingleLocation_ModuleStylesTrait {

	use RTCL_Divi5_SingleLocation_CustomCssTrait;

	/**
	 * Generate module styles.
	 *
	 * @param array $args Arguments.
	 */
	public static function module_styles( $args ) {
		$attrs       = $args['attrs'] ?? [];
		$elements    = $args['elements'];
		$settings    = $args['settings'] ?? [];
		$order_class = $args['orderClass'];

		// Read color attributes.
		$title_color        = $attrs['titleColor']['innerContent']['desktop']['value'] ?? '';
		$title_hover_color  = $attrs['titleHoverColor']['innerContent']['desktop']['value'] ?? '';
		$description_color  = $attrs['descriptionColor']['innerContent']['desktop']['value'] ?? '';
		$count_color        = $attrs['countColor']['innerContent']['desktop']['value'] ?? '';
		$content_background = $attrs['contentBackground']['innerContent']['desktop']['value'] ?? '';
		$content_alignment    = $attrs['contentAlignment']['innerContent']['desktop']['value'] ?? 'center';
		$wrapper_bg_color     = $attrs['wrapperBgColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_border_rad   = $attrs['wrapperBorderRadius']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pt           = $attrs['wrapperPaddingTop']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pr           = $attrs['wrapperPaddingRight']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pb           = $attrs['wrapperPaddingBottom']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pl           = $attrs['wrapperPaddingLeft']['innerContent']['desktop']['value'] ?? '';

		// Build override styles as proper Divi 5 style arrays with !important.
		$color_styles  = [];
		$wrapper_sel   = "{$order_class} .rtcl-single-location-wrapper";
		$box_sel       = "{$order_class} .rtcl-single-location.rtcl-divi-module";

		// Wrapper — overflow:hidden + bg color.
		$color_styles[] = [ 'selector' => $wrapper_sel, 'declaration' => 'overflow: hidden !important;' ];
		if ( $wrapper_bg_color ) {
			$color_styles[] = [ 'selector' => $wrapper_sel, 'declaration' => "background-color: {$wrapper_bg_color} !important;" ];
		}

		// Border-radius on wrapper + inner box.
		if ( $wrapper_border_rad ) {
			$color_styles[] = [ 'selector' => $wrapper_sel, 'declaration' => "border-radius: {$wrapper_border_rad} !important;" ];
			$color_styles[] = [ 'selector' => $box_sel, 'declaration' => "border-radius: {$wrapper_border_rad} !important; overflow: hidden !important;" ];
		}
		if ( $wrapper_pt ) {
			$color_styles[] = [ 'selector' => $box_sel, 'declaration' => "padding-top: {$wrapper_pt} !important;" ];
		}
		if ( $wrapper_pr ) {
			$color_styles[] = [ 'selector' => $box_sel, 'declaration' => "padding-right: {$wrapper_pr} !important;" ];
		}
		if ( $wrapper_pb ) {
			$color_styles[] = [ 'selector' => $box_sel, 'declaration' => "padding-bottom: {$wrapper_pb} !important;" ];
		}
		if ( $wrapper_pl ) {
			$color_styles[] = [ 'selector' => $box_sel, 'declaration' => "padding-left: {$wrapper_pl} !important;" ];
		}

		// Wrapper transparent when no bg color set, so module background image shows through.
		if ( ! $wrapper_bg_color ) {
			$color_styles[] = [
				'selector'    => $wrapper_sel,
				'declaration' => 'background-color: transparent !important;',
			];
		}

		// Inner elements transparent.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-single-location, {$order_class} .rtcl-single-location-inner",
			'declaration' => 'background-color: transparent !important;',
		];

		// Inner wrapper layout + optional content background override.
		$inner_decl = 'position: relative; overflow: hidden;';
		if ( $content_background ) {
			$inner_decl .= " background-color: {$content_background} !important;";
		}
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-single-location-inner",
			'declaration' => $inner_decl,
		];

		// Content alignment.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-location-content",
			'declaration' => "text-align: {$content_alignment} !important;",
		];

		// Title colors.
		if ( $title_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-name, {$order_class} .rtcl-location-title a",
				'declaration' => "color: {$title_color} !important;",
			];
		}
		if ( $title_hover_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-single-location-inner a:hover .rtcl-location-name, {$order_class} .rtcl-location-title a:hover",
				'declaration' => "color: {$title_hover_color} !important;",
			];
		}

		// Description color.
		if ( $description_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-description",
				'declaration' => "color: {$description_color} !important;",
			];
		}

		// Count color.
		if ( $count_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-listing-count, {$order_class} .rtcl-location-count",
				'declaration' => "color: {$count_color} !important;",
			];
		}

		Style::add(
			[
				'id'            => $args['id'],
				'name'          => $args['name'],
				'orderIndex'    => $args['orderIndex'],
				'storeInstance' => $args['storeInstance'],
				'styles'        => array_merge(
					[
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
								'selector'  => $order_class,
								'attr'      => $attrs['css'] ?? [],
								'cssFields' => self::custom_css(),
							]
						),
						TextStyle::style(
							[
								'selector' => "{$order_class} .rtcl-single-location-wrapper",
								'attr'     => $attrs['module']['advanced']['text'] ?? [],
							]
						),

						// Element font styles (no color — colors handled by $color_styles with !important).
						$elements->style( [ 'attrName' => 'title' ] ),
						$elements->style( [ 'attrName' => 'description' ] ),
						$elements->style( [ 'attrName' => 'count' ] ),
					],
					// Color override styles as proper style arrays.
					[ $color_styles ]
				),
			]
		);
	}
}
