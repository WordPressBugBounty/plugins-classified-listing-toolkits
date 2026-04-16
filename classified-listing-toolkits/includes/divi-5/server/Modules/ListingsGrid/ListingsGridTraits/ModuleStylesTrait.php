<?php
/**
 * Module Styles Trait for Listings Grid.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_ListingsGrid_ModuleStylesTrait {

	use RTCL_Divi5_ListingsGrid_CustomCssTrait;

	public static function module_styles( $args ) {
		$attrs       = $args['attrs'] ?? [];
		$elements    = $args['elements'];
		$settings    = $args['settings'] ?? [];
		$order_class = $args['orderClass'];

		$grid_column        = $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3';
		$grid_column_tablet = $attrs['gridColumn']['innerContent']['tablet']['value'] ?? '2';
		$grid_column_phone  = $attrs['gridColumn']['innerContent']['phone']['value'] ?? '1';
		$title_color     = $attrs['titleColor']['innerContent']['desktop']['value'] ?? '';
		$title_hover     = $attrs['titleHoverColor']['innerContent']['desktop']['value'] ?? '';
		$meta_color      = $attrs['metaColor']['innerContent']['desktop']['value'] ?? '';
		$meta_icon_color = $attrs['metaIconColor']['innerContent']['desktop']['value'] ?? '';
		$cat_color       = $attrs['categoryColor']['innerContent']['desktop']['value'] ?? '';
		$cat_hover       = $attrs['categoryHoverColor']['innerContent']['desktop']['value'] ?? '';
		$price_color     = $attrs['priceColor']['innerContent']['desktop']['value'] ?? '';

		$color_styles = [];

		// Grid layout — selector includes .columns-X so Divi 5's !important
		// on each breakpoint rule properly overrides the previous one.
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-listings.columns-{$grid_column}",
			'declaration' => "display: grid; grid-template-columns: repeat({$grid_column}, 1fr); gap: 20px;",
		];
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-listings.tab-columns-{$grid_column_tablet}",
			'declaration' => "grid-template-columns: repeat({$grid_column_tablet}, 1fr);",
			'atRules'     => '@media (max-width: 1024px)',
		];
		$color_styles[] = [
			'selector'    => "{$order_class} .rtcl-listings.mobile-columns-{$grid_column_phone}",
			'declaration' => "grid-template-columns: repeat({$grid_column_phone}, 1fr);",
			'atRules'     => '@media (max-width: 767px)',
		];

		// Consistent item spacing.
		$color_styles[] = [ 'selector' => "{$order_class} .item-content > *", 'declaration' => 'margin-top: 0 !important; margin-bottom: 10px !important;' ];
		$color_styles[] = [ 'selector' => "{$order_class} .item-content > *:last-child", 'declaration' => 'margin-bottom: 0 !important;' ];

		if ( $title_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-title a, {$order_class} .listing-title a", 'declaration' => "color: {$title_color} !important;" ]; }
		if ( $title_hover ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-title a:hover, {$order_class} .listing-title a:hover", 'declaration' => "color: {$title_hover} !important;" ]; }
		if ( $meta_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-meta-data, {$order_class} .rtcl-listing-meta-data li", 'declaration' => "color: {$meta_color} !important;" ]; }
		if ( $meta_icon_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-meta-data i", 'declaration' => "color: {$meta_icon_color} !important;" ]; }
		if ( $cat_color ) { $color_styles[] = [ 'selector' => "{$order_class} .listing-cat, {$order_class} .listing-cat a", 'declaration' => "color: {$cat_color} !important;" ]; }
		if ( $cat_hover ) { $color_styles[] = [ 'selector' => "{$order_class} .listing-cat a:hover", 'declaration' => "color: {$cat_hover} !important;" ]; }
		if ( $price_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-price, {$order_class} .item-price", 'declaration' => "color: {$price_color} !important;" ]; }

		// Pagination button colors.
		$pag_bg       = $attrs['paginationBgColor']['innerContent']['desktop']['value'] ?? '';
		$pag_text     = $attrs['paginationTextColor']['innerContent']['desktop']['value'] ?? '';
		$pag_act_bg   = $attrs['paginationActiveBgColor']['innerContent']['desktop']['value'] ?? '';
		$pag_act_text = $attrs['paginationActiveTextColor']['innerContent']['desktop']['value'] ?? '';
		$pag_sel      = "{$order_class} .rtcl-pagination a, {$order_class} .rtcl-pagination span";

		if ( $pag_bg ) { $color_styles[] = [ 'selector' => $pag_sel, 'declaration' => "background-color: {$pag_bg} !important;" ]; }
		if ( $pag_text ) { $color_styles[] = [ 'selector' => $pag_sel, 'declaration' => "color: {$pag_text} !important;" ]; }
		if ( $pag_act_bg ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-pagination .current", 'declaration' => "background-color: {$pag_act_bg} !important;" ]; }
		if ( $pag_act_text ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-pagination .current", 'declaration' => "color: {$pag_act_text} !important;" ]; }

		// Action button colors.
		$act_bg       = $attrs['actionBtnBgColor']['innerContent']['desktop']['value'] ?? '';
		$act_bg_hover = $attrs['actionBtnBgHoverColor']['innerContent']['desktop']['value'] ?? '';
		$act_icon     = $attrs['actionBtnIconColor']['innerContent']['desktop']['value'] ?? '';
		$act_icon_hov = $attrs['actionBtnIconHoverColor']['innerContent']['desktop']['value'] ?? '';

		if ( $act_bg ) {
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites, {$order_class} a.rtcl-quick-view, {$order_class} a.rtcl-compare", 'declaration' => "background-color: {$act_bg} !important;" ];
		}
		if ( $act_bg_hover ) {
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites:hover, {$order_class} a.rtcl-quick-view:hover, {$order_class} a.rtcl-compare:hover", 'declaration' => "background-color: {$act_bg_hover} !important;" ];
		}
		if ( $act_icon ) {
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites .rtcl-icon, {$order_class} a.rtcl-favourites span.rtcl-icon", 'declaration' => "color: {$act_icon} !important;" ];
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-quick-view i, {$order_class} a.rtcl-quick-view .rtcl-icon", 'declaration' => "color: {$act_icon} !important;" ];
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-compare i, {$order_class} a.rtcl-compare .rtcl-icon", 'declaration' => "color: {$act_icon} !important;" ];
		}
		if ( $act_icon_hov ) {
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites:hover .rtcl-icon, {$order_class} a.rtcl-favourites:hover span.rtcl-icon", 'declaration' => "color: {$act_icon_hov} !important;" ];
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-quick-view:hover i, {$order_class} a.rtcl-quick-view:hover .rtcl-icon", 'declaration' => "color: {$act_icon_hov} !important;" ];
			$color_styles[] = [ 'selector' => "{$order_class} a.rtcl-compare:hover i, {$order_class} a.rtcl-compare:hover .rtcl-icon", 'declaration' => "color: {$act_icon_hov} !important;" ];
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
						TextStyle::style( [ 'selector' => "{$order_class} .rtcl-listings-wrapper", 'attr' => $attrs['module']['advanced']['text'] ?? [] ] ),
						$elements->style( [ 'attrName' => 'title' ] ),
						$elements->style( [ 'attrName' => 'price' ] ),
						$elements->style( [ 'attrName' => 'meta' ] ),
					],
					[ $color_styles ]
				),
			]
		);
	}
}
