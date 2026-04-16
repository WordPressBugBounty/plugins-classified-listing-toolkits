<?php
/**
 * Module Styles Trait for All Locations.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_AllLocations_ModuleStylesTrait {

	use RTCL_Divi5_AllLocations_CustomCssTrait;

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

		// Read attributes.
		$grid_column       = $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3';
		$content_alignment = $attrs['contentAlignment']['innerContent']['desktop']['value'] ?? 'left';
		$title_color       = $attrs['titleColor']['innerContent']['desktop']['value'] ?? '';
		$title_hover_color = $attrs['titleHoverColor']['innerContent']['desktop']['value'] ?? '';
		$description_color = $attrs['descriptionColor']['innerContent']['desktop']['value'] ?? '';
		$count_color       = $attrs['countColor']['innerContent']['desktop']['value'] ?? '';
		$content_bg        = $attrs['contentBackground']['innerContent']['desktop']['value'] ?? '';
		$box_height        = $attrs['boxHeight']['innerContent']['desktop']['value'] ?? '';
		$wrapper_bg_color  = $attrs['wrapperBgColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_border_rad = $attrs['wrapperBorderRadius']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pt        = $attrs['wrapperPaddingTop']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pr        = $attrs['wrapperPaddingRight']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pb        = $attrs['wrapperPaddingBottom']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pl        = $attrs['wrapperPaddingLeft']['innerContent']['desktop']['value'] ?? '';

		// Build override styles as proper Divi 5 style arrays with !important.
		$color_styles = [];
		$item_sel     = "{$order_class} .rtcl-location-item";

		// Wrapper overrides on .rtcl-location-item.
		if ( $wrapper_bg_color ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "background-color: {$wrapper_bg_color} !important;" ];
		}
		if ( $wrapper_border_rad ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "border-radius: {$wrapper_border_rad} !important; overflow: hidden !important;" ];
		}
		if ( $wrapper_pt ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-top: {$wrapper_pt} !important;" ];
		}
		if ( $wrapper_pr ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-right: {$wrapper_pr} !important;" ];
		}
		if ( $wrapper_pb ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-bottom: {$wrapper_pb} !important;" ];
		}
		if ( $wrapper_pl ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-left: {$wrapper_pl} !important;" ];
		}

		// Grid layout.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-locations-grid",
			'declaration' => "display: grid !important; grid-template-columns: repeat({$grid_column}, 1fr) !important; gap: 20px !important;",
		];

		// Content alignment.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-location-item",
			'declaration' => "text-align: {$content_alignment} !important;",
		];

		if ( $content_bg ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-item",
				'declaration' => "background-color: {$content_bg} !important;",
			];
		}
		if ( $box_height ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-item",
				'declaration' => "height: {$box_height} !important;",
			];
		}

		// Title colors.
		if ( $title_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-title a",
				'declaration' => "color: {$title_color} !important;",
			];
		}
		if ( $title_hover_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-title a:hover",
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

		// Count color (template uses .count class).
		if ( $count_color ) {
			$color_styles[] = [
				'selector'    => "{$order_class} .rtcl-location-count, {$order_class} .count",
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
								'selector' => "{$order_class} .rtcl-all-locations-wrapper",
								'attr'     => $attrs['module']['advanced']['text'] ?? [],
							]
						),

						// Element font styles.
						$elements->style( [ 'attrName' => 'title' ] ),
						$elements->style( [ 'attrName' => 'count' ] ),
						$elements->style( [ 'attrName' => 'description' ] ),
					],
					// Grid/color override styles.
					[ $color_styles ]
				),
			]
		);
	}
}
