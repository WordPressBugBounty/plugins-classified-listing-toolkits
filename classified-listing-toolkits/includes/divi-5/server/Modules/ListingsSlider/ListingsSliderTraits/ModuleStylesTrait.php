<?php
/**
 * Module Styles Trait for Listings Slider.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Text\TextStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait RTCL_Divi5_ListingsSlider_ModuleStylesTrait {

	use RTCL_Divi5_ListingsSlider_CustomCssTrait;

	public static function module_styles( $args ) {
		$attrs       = $args['attrs'] ?? [];
		$elements    = $args['elements'];
		$settings    = $args['settings'] ?? [];
		$order_class = $args['orderClass'];

		$title_color     = $attrs['titleColor']['innerContent']['desktop']['value'] ?? '';
		$title_hover     = $attrs['titleHoverColor']['innerContent']['desktop']['value'] ?? '';
		$meta_color      = $attrs['metaColor']['innerContent']['desktop']['value'] ?? '';
		$meta_icon_color = $attrs['metaIconColor']['innerContent']['desktop']['value'] ?? '';
		$cat_color       = $attrs['categoryColor']['innerContent']['desktop']['value'] ?? '';
		$cat_hover       = $attrs['categoryHoverColor']['innerContent']['desktop']['value'] ?? '';
		$price_color     = $attrs['priceColor']['innerContent']['desktop']['value'] ?? '';
		$arrow_color     = $attrs['arrowColor']['innerContent']['desktop']['value'] ?? '';
		$arrow_hover     = $attrs['arrowHoverColor']['innerContent']['desktop']['value'] ?? '';
		$arrow_bg        = $attrs['arrowBgColor']['innerContent']['desktop']['value'] ?? '';
		$arrow_bg_hover  = $attrs['arrowBgHoverColor']['innerContent']['desktop']['value'] ?? '';
		$dot_color       = $attrs['dotColor']['innerContent']['desktop']['value'] ?? '';
		$dot_active      = $attrs['dotActiveColor']['innerContent']['desktop']['value'] ?? '';

		$color_styles = [];
		$arrow_sel    = "{$order_class} .rtcl-slider-btn";

		// Description overflow fix.
		$color_styles[] = [ 'selector' => "{$order_class} .rtcl-widget-listing-item", 'declaration' => 'overflow: hidden !important;' ];
		$color_styles[] = [ 'selector' => "{$order_class} .rtcl-excerpt", 'declaration' => 'overflow: hidden !important; display: -webkit-box !important; -webkit-box-orient: vertical !important; -webkit-line-clamp: 3 !important;' ];

		if ( $title_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-title a, {$order_class} .listing-title a", 'declaration' => "color: {$title_color} !important;" ]; }
		if ( $title_hover ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-title a:hover, {$order_class} .listing-title a:hover", 'declaration' => "color: {$title_hover} !important;" ]; }
		if ( $meta_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-meta-data, {$order_class} .rtcl-listing-meta-data li", 'declaration' => "color: {$meta_color} !important;" ]; }
		if ( $meta_icon_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-listing-meta-data i", 'declaration' => "color: {$meta_icon_color} !important;" ]; }
		if ( $cat_color ) { $color_styles[] = [ 'selector' => "{$order_class} .listing-cat, {$order_class} .listing-cat a", 'declaration' => "color: {$cat_color} !important;" ]; }
		if ( $cat_hover ) { $color_styles[] = [ 'selector' => "{$order_class} .listing-cat a:hover", 'declaration' => "color: {$cat_hover} !important;" ]; }
		if ( $price_color ) { $color_styles[] = [ 'selector' => "{$order_class} .rtcl-price, {$order_class} .item-price", 'declaration' => "color: {$price_color} !important;" ]; }

		// Arrow bg + icon.
		if ( $arrow_bg ) { $color_styles[] = [ 'selector' => $arrow_sel, 'declaration' => "background-color: {$arrow_bg} !important;" ]; }
		if ( $arrow_bg_hover ) { $color_styles[] = [ 'selector' => "{$arrow_sel}:hover", 'declaration' => "background-color: {$arrow_bg_hover} !important;" ]; }
		if ( $arrow_color ) { $color_styles[] = [ 'selector' => $arrow_sel, 'declaration' => "color: {$arrow_color} !important;" ]; }
		if ( $arrow_hover ) { $color_styles[] = [ 'selector' => "{$arrow_sel}:hover", 'declaration' => "color: {$arrow_hover} !important;" ]; }

		if ( $dot_color ) { $color_styles[] = [ 'selector' => "{$order_class} .swiper-pagination-bullet", 'declaration' => "background-color: {$dot_color} !important;" ]; }
		if ( $dot_active ) { $color_styles[] = [ 'selector' => "{$order_class} .swiper-pagination-bullet-active", 'declaration' => "background-color: {$dot_active} !important;" ]; }

		// Action button colors.
		$act_bg       = $attrs['actionBtnBgColor']['innerContent']['desktop']['value'] ?? '';
		$act_bg_hover = $attrs['actionBtnBgHoverColor']['innerContent']['desktop']['value'] ?? '';
		$act_icon     = $attrs['actionBtnIconColor']['innerContent']['desktop']['value'] ?? '';
		$act_icon_hov = $attrs['actionBtnIconHoverColor']['innerContent']['desktop']['value'] ?? '';

		if ( $act_bg ) { $color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites, {$order_class} a.rtcl-quick-view, {$order_class} a.rtcl-compare", 'declaration' => "background-color: {$act_bg} !important;" ]; }
		if ( $act_bg_hover ) { $color_styles[] = [ 'selector' => "{$order_class} a.rtcl-favourites:hover, {$order_class} a.rtcl-quick-view:hover, {$order_class} a.rtcl-compare:hover", 'declaration' => "background-color: {$act_bg_hover} !important;" ]; }
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
