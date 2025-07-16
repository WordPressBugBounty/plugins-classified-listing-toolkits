<?php

namespace RadiusTheme\ClassifiedListingToolkits\Hooks;


use RadiusTheme\ClassifiedListingToolkits\Common\Keys;
use Rtcl\Helpers\Functions;

class Helper {

	public static function get_plugin_template_path() {
		return CLASSIFIED_LISTING_TOOLKITS_PATH . '/templates/';
	}

	public static function is_enable_compare() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_compare', false, 'checkbox' );
	}

	public static function is_enable_quick_view() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_quick_view', false, 'checkbox' );
	}

	public static function get_listing_taxonomy( $parent = 'all', $taxonomy = '' ) {
		$args = [
			'taxonomy'   => rtcl()->category,
			'fields'     => 'id=>name',
			'hide_empty' => true,
		];

		if ( ! empty( $taxonomy ) ) {
			$args['taxonomy'] = sanitize_text_field( $taxonomy );
		}

		if ( 'parent' === $parent ) {
			$args['parent'] = 0;
		}

		$terms = get_terms( $args );

		$category_dropdown = [];

		foreach ( $terms as $id => $name ) {
			if ( is_array( $name ) ) {
				$category_dropdown[ $id ] = '';
			} else {
				$category_dropdown[ $id ] = html_entity_decode( $name );
			}
		}

		return $category_dropdown;
	}

	public static function get_image_sizes_select() {

		global $_wp_additional_image_sizes;

		$intermediate_image_sizes = get_intermediate_image_sizes();

		$image_sizes = array();
		foreach ( $intermediate_image_sizes as $size ) {
			if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$image_sizes[ $size ] = array(
					'width'  => $_wp_additional_image_sizes[ $size ]['width'],
					'height' => $_wp_additional_image_sizes[ $size ]['height']
				);
			} else {
				$image_sizes[ $size ] = array(
					'width'  => intval( get_option( "{$size}_size_w" ) ),
					'height' => intval( get_option( "{$size}_size_h" ) )
				);
			}
		}

		$sizes_arr = [];
		foreach ( $image_sizes as $key => $value ) {
			$sizes_arr[ $key ] = ucwords( strtolower( preg_replace( '/[-_]/', ' ', $key ) ) ) . " - {$value['width']} x {$value['height']}";
		}

		$sizes_arr['full'] = __( 'Full Size', 'classified-listing-toolkits' );

		return $sizes_arr;
	}

	public static function get_order_options() {
		$order_by = [
			'title' => esc_html__( 'Title', 'classified-listing-toolkits' ),
			'date'  => esc_html__( 'Date', 'classified-listing-toolkits' ),
			'ID'    => esc_html__( 'ID', 'classified-listing-toolkits' ),
			'price' => esc_html__( 'Price', 'classified-listing-toolkits' ),
			'views' => esc_html__( 'Views', 'classified-listing-toolkits' ),
			'none'  => esc_html__( 'None', 'classified-listing-toolkits' ),
		];

		return apply_filters( 'rtcl_divi_listing_order_by', $order_by );
	}

	public static function divi_get_user_selected_terms( $category_includes, $taxonomy = 'rtcl_category' ) {
		// available categories.
		$available_cat = self::get_listing_taxonomy( 'parent', $taxonomy );

		ksort( $available_cat );

		$includes_keys = array_filter(
			$category_includes,
			function ( $cat ) {
				if ( $cat === 'on' ) {
					return $cat;
				}
			}
		);

		$available_terms = array_keys( $available_cat );
		$selected_terms  = array();

		foreach ( $includes_keys as $key => $value ) {
			array_push( $selected_terms, $available_terms[ $key ] );
		}

		return $selected_terms;
	}


	public static function is_divi_plugin_active() {
		return defined( 'ET_BUILDER_PLUGIN_VERSION' );
	}

	public static function get_search_options() {
		$style = apply_filters(
			'rtcl_el_search_style',
			array(
				'dependency' => esc_html__( 'Dependency Selection', 'classified-listing-toolkits' ),
			)
		);

		return $style;
	}

	public static function is_divi_builder_preview(): bool {
		return ( ( isset( $_GET['et_fb'] ) && $_GET['et_fb'] == '1' ) || ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'et_theme_builder' ) );
	}

	public static function is_pro_with_old_dependency() {
		return rtcl()->has_pro() || version_compare( get_option( 'rtcl_installed_from' ), '5.0.1', '<' );
	}
}
