<?php
/**
 * Module Styles Trait for Listing Categories.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_ListingCategories_ModuleStylesTrait {

	use RTCL_Divi5_ListingCategories_CustomCssTrait;

	public static function module_styles( $args ) {
		$attrs       = $args['attrs'] ?? [];
		$elements    = $args['elements'];
		$settings    = $args['settings'] ?? [];
		$order_class = $args['orderClass'];

		$grid_column       = $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3';
		$title_color       = $attrs['titleColor']['innerContent']['desktop']['value'] ?? '';
		$title_hover_color = $attrs['titleHoverColor']['innerContent']['desktop']['value'] ?? '';
		$description_color = $attrs['descriptionColor']['innerContent']['desktop']['value'] ?? '';
		$count_color       = $attrs['countColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_bg        = $attrs['wrapperBgColor']['innerContent']['desktop']['value'] ?? '';
		$wrapper_rad       = $attrs['wrapperBorderRadius']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pt        = $attrs['wrapperPaddingTop']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pr        = $attrs['wrapperPaddingRight']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pb        = $attrs['wrapperPaddingBottom']['innerContent']['desktop']['value'] ?? '';
		$wrapper_pl        = $attrs['wrapperPaddingLeft']['innerContent']['desktop']['value'] ?? '';

		$color_styles = [];
		$item_sel     = "{$order_class} .rtcl-cat-item";

		// Wrapper.
		if ( $wrapper_bg ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "background-color: {$wrapper_bg} !important;" ];
		}
		if ( $wrapper_rad ) {
			$color_styles[] = [ 'selector' => $item_sel, 'declaration' => "border-radius: {$wrapper_rad} !important; overflow: hidden !important;" ];
		}
		if ( $wrapper_pt ) { $color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-top: {$wrapper_pt} !important;" ]; }
		if ( $wrapper_pr ) { $color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-right: {$wrapper_pr} !important;" ]; }
		if ( $wrapper_pb ) { $color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-bottom: {$wrapper_pb} !important;" ]; }
		if ( $wrapper_pl ) { $color_styles[] = [ 'selector' => $item_sel, 'declaration' => "padding-left: {$wrapper_pl} !important;" ]; }

		// Grid.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-cat-items-wrapper",
			'declaration' => "display: grid !important; grid-template-columns: repeat({$grid_column}, 1fr) !important; gap: 20px !important;",
		];

		// Title.
		if ( $title_color ) {
			$color_styles[] = [ 'selector' => "{$order_class} .rtcl-category-title a", 'declaration' => "color: {$title_color} !important;" ];
		}
		if ( $title_hover_color ) {
			$color_styles[] = [ 'selector' => "{$order_class} .rtcl-category-title a:hover", 'declaration' => "color: {$title_hover_color} !important;" ];
		}

		// Description.
		if ( $description_color ) {
			$color_styles[] = [ 'selector' => "{$order_class} .rtcl-category-description, {$order_class} .cat-details p", 'declaration' => "color: {$description_color} !important;" ];
		}

		// Count.
		if ( $count_color ) {
			$color_styles[] = [ 'selector' => "{$order_class} .rtcl-category-count, {$order_class} .count", 'declaration' => "color: {$count_color} !important;" ];
		}

		Style::add(
			[
				'id'            => $args['id'],
				'name'          => $args['name'],
				'orderIndex'    => $args['orderIndex'],
				'storeInstance' => $args['storeInstance'],
				'styles'        => array_merge(
					[
						$elements->style( [ 'attrName' => 'module', 'styleProps' => [ 'disabledOn' => [ 'disabledModuleVisibility' => $settings['disabledModuleVisibility'] ?? null ] ] ] ),
						CssStyle::style( [ 'selector' => $order_class, 'attr' => $attrs['css'] ?? [], 'cssFields' => self::custom_css() ] ),
						TextStyle::style( [ 'selector' => "{$order_class} .rtcl-categories-wrapper", 'attr' => $attrs['module']['advanced']['text'] ?? [] ] ),
						$elements->style( [ 'attrName' => 'title' ] ),
						$elements->style( [ 'attrName' => 'count' ] ),
						$elements->style( [ 'attrName' => 'description' ] ),
					],
					[ $color_styles ]
				),
			]
		);
	}
}
