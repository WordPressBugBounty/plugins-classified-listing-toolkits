<?php
/**
 * Module Styles Trait for Search Form.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_SearchForm_ModuleStylesTrait {

	use RTCL_Divi5_SearchForm_CustomCssTrait;

	/**
	 * Generate module styles.
	 *
	 * Generates layout CSS matching the Visual Builder's module-styles.jsx
	 * to ensure consistent rendering between VB and frontend.
	 *
	 * @param array $args Arguments.
	 */
	public static function module_styles( $args ) {
		$attrs       = $args['attrs'] ?? [];
		$elements    = $args['elements'];
		$settings    = $args['settings'] ?? [];
		$order_class = $args['orderClass'];

		// User-set color overrides only.
		// Base/layout CSS is handled by rtcl-divi5-frontend.css (Divi 4 compatible).
		$input_bg_color          = $attrs['inputBgColor']['innerContent']['desktop']['value'] ?? '';
		$input_text_color        = $attrs['inputTextColor']['innerContent']['desktop']['value'] ?? '';
		$input_border_color      = $attrs['inputBorderColor']['innerContent']['desktop']['value'] ?? '';
		$button_bg_color         = $attrs['buttonBgColor']['innerContent']['desktop']['value'] ?? '';
		$button_bg_hover_color   = $attrs['buttonBgHoverColor']['innerContent']['desktop']['value'] ?? '';
		$button_text_color       = $attrs['buttonTextColor']['innerContent']['desktop']['value'] ?? '';
		$button_text_hover_color = $attrs['buttonTextHoverColor']['innerContent']['desktop']['value'] ?? '';
		$label_color             = $attrs['labelColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_bg_color        = $attrs['wrapperBgColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pt              = $attrs['wrapperPaddingTop']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pr              = $attrs['wrapperPaddingRight']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pb              = $attrs['wrapperPaddingBottom']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pl              = $attrs['wrapperPaddingLeft']['innerContent']['desktop']['value'] ?? '';
		$wrapper_border_radius   = $attrs['wrapperBorderRadius']['innerContent']['desktop']['value'] ?? '';

		// Build override styles as proper Divi 5 style arrays with !important.
		// Uses orderClass only (no .et_pb_module prefix) — !important beats base CSS specificity
		// and works in both VB and frontend contexts.
		$input_selector = "{$order_class} .rtcl-search-input, {$order_class} .rtcl-search-select, {$order_class} .rtcl-form-control, {$order_class} .rtcl-search-form select";
		$color_styles   = [];

		// Wrapper overrides.
		if ( $wrapper_bg_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "background-color: {$wrapper_bg_color} !important;",
			];
		}
		if ( $wrapper_pt ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "padding-top: {$wrapper_pt} !important;",
			];
		}
		if ( $wrapper_pr ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "padding-right: {$wrapper_pr} !important;",
			];
		}
		if ( $wrapper_pb ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "padding-bottom: {$wrapper_pb} !important;",
			];
		}
		if ( $wrapper_pl ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "padding-left: {$wrapper_pl} !important;",
			];
		}
		if ( $wrapper_border_radius ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-form-wrapper",
				'declaration' => "border-radius: {$wrapper_border_radius} !important;",
			];
		}

		if ( $label_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-field-label",
				'declaration' => "color: {$label_color} !important;",
			];
		}
		if ( $input_bg_color ) {
			$color_styles[] = [
				'selector'    => $input_selector,
				'declaration' => "background-color: {$input_bg_color} !important;",
			];
		}
		if ( $input_text_color ) {
			$color_styles[] = [
				'selector'    => $input_selector,
				'declaration' => "color: {$input_text_color} !important;",
			];
		}
		if ( $input_border_color ) {
			$color_styles[] = [
				'selector'    => $input_selector,
				'declaration' => "border-color: {$input_border_color} !important;",
			];
		}
		if ( $button_bg_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-submit",
				'declaration' => "background-color: {$button_bg_color} !important;",
			];
		}
		if ( $button_text_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-submit",
				'declaration' => "color: {$button_text_color} !important;",
			];
		}
		if ( $button_bg_hover_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-submit:hover",
				'declaration' => "background-color: {$button_bg_hover_color} !important;",
			];
		}
		if ( $button_text_hover_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-search-submit:hover",
				'declaration' => "color: {$button_text_hover_color} !important;",
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
								'selector' => "{$order_class} .rtcl-search-form-wrapper",
								'attr'     => $attrs['module']['advanced']['text'] ?? [],
							]
						),
					],
					// Color override styles as proper style arrays.
					[ $color_styles ]
				),
			]
		);
	}
}
