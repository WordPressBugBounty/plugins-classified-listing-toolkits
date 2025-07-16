<?php
/**
 * Main Elementor ELFilterHooks Class
 *
 * The main class that filter the functionality.
 *
 * @since 1.0.0
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Hooks;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;

/**
 * ELFilterHooks class
 */
class ELFilterHooks {
	/**
	 * Initialize function.
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'rtcl_el_search_general_fields', [ __CLASS__, 'search_general_fields' ], 10, 2 );
		/* Elementor Addons Hooks */
		add_filter( 'rtcl_el_listing_order_by', [ __CLASS__, 'el_listing_order_by' ], 10 );
		add_filter( 'rtcl_el_listings_list_style', [ __CLASS__, 'el_listings_style' ], 10 );
		add_filter( 'rtcl_el_listings_grid_style', [ __CLASS__, 'el_listings_style_grid' ], 10 );
		add_filter( 'rtcl_divi_listing_grid_styles', [ __CLASS__, 'divi_listing_grid_styles' ], 10 );
		add_filter( 'rtcl_divi_listing_list_styles', [ __CLASS__, 'divi_listing_grid_styles' ], 10 );
		add_filter( 'rtcl_el_category_box_style', [ __CLASS__, 'el_listing_category_box_style' ], 10 );
		add_filter( 'rtcl_el_location_box_style', [ __CLASS__, 'el_listings_location_style' ], 10 );
		add_filter( 'el_listing_widget_content_visibility_fields', [ __CLASS__, 'listing_widget_content_visibility_fields' ], 10, 2 );
		add_filter( 'el_header_button_visibility_fields', [ __CLASS__, 'header_button_visibility_fields' ], 10, 2 );
		add_filter( 'rtcl_el_search_widget_data', [ __CLASS__, 'el_search_widget_data' ], 10, 2 );
		add_filter( 'rtcl_el_search_style', [ __CLASS__, 'el_search_style' ], 10, 2 );
		add_filter( 'rtcl_el_listing_widget_style_field', [ __CLASS__, 'custom_field_settings' ], 10, 2 );
		add_filter( 'rtcl_el_archive_listing_widget_style_field', [ __CLASS__, 'custom_field_settings' ], 10, 2 );
		add_filter( 'rtcl_el_related_listing_widget_style_field', [ __CLASS__, 'custom_field_settings' ], 10, 2 );

		/* End Elementor Addons Hooks */
	}

	/**
	 * Search functionality
	 *
	 * @param [type] $fields prev fields
	 * @param [type] $obj class object
	 *
	 * @return array
	 */
	public static function search_general_fields( $fields, $obj ) {
		$new_fields = [];
		if ( 'geo' === Functions::location_type() ) {
			$new_fields[] = [
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'geo_location_range',
				'label'     => __( 'Radius Search', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => '',
				'condition' => [
					'location_field' => 'yes',
				],
			];
		}

		return $obj->insert_new_controls( 'location_field', $new_fields, $fields, true );
	}


	/**
	 * Some pro field.
	 *
	 * @param [type] $fields Prev fields.
	 * @param [type] $obj class object.
	 *
	 * @return array
	 */
	public static function custom_field_settings( $fields, $obj ) {

		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $fields;
		}

		$new_fields = [
			[
				'mode'      => 'section_start',
				'id'        => 'rtcl_sec_custom_field',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Custom Field\'s', 'classified-listing-toolkits' ),
				'condition' => [ 'rtcl_show_custom_fields' => [ 'yes' ] ],
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_meta_custom_field_typo',
				'label'    => __( 'Typography', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl.rtcl-elementor-widget .rtcl-listings .rtcl-listable .rtcl-listable-item',
			],
			[
				'label'      => __( 'Spacing', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'mode'       => 'responsive',
				'id'         => 'rtcl_custom_field_spacing',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .rtcl.rtcl-elementor-widget .rtcl-listings .rtcl-listable' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_custom_field_color',
				'label'     => __( 'Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl.rtcl-elementor-widget .rtcl-listings .rtcl-listable .rtcl-listable-item' => 'color: {{VALUE}}',
				],
			],

			[
				'mode' => 'section_end',
			],
		];

		return $obj->insert_new_controls( 'rtcl_sec_description', $new_fields, $fields );
	}


	/**
	 * Undocumented function
	 *
	 * @param [array] $data order by option.
	 *
	 * @return array
	 */
	public static function el_listing_order_by( $data ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $data;
		}

		$new  = [
			'rand' => __( 'Rand', 'classified-listing-toolkits' ),
		];
		$data = array_merge(
			array_slice( $data, 0, 5 ),
			$new,
			array_slice( $data, 5 )
		);

		return $data;
	}

	/**
	 * Some pro field.
	 *
	 * @param [type] $fields Prev fields.
	 * @param [type] $obj class object.
	 *
	 * @return array
	 */
	public static function header_button_visibility_fields( $fields, $obj ) {
		$new_fields = [];

		if ( Helper::is_enable_compare() ) {
			$new_fields[] = [
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_compare',
				'label'       => __( 'Show compare icon', 'classified-listing-toolkits' ),
				'label_on'    => __( 'On', 'classified-listing-toolkits' ),
				'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
				'default'     => 'yes',
				'description' => __( 'Show or Hide Icon. Default: On', 'classified-listing-toolkits' ),
			];
			$new_fields[] = [
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'rtcl_compare_icon_order',
				'label'       => __( 'Compare icon order', 'classified-listing-toolkits' ),
				'default'     => '1',
				'description' => __( 'Icon order', 'classified-listing-toolkits' ),
				'condition'   => [
					'rtcl_show_compare' => 'yes',
				],
			];
		}
		$new_fields[] = [
			'type'        => Controls_Manager::SWITCHER,
			'id'          => 'rtcl_show_chat_option',
			'label'       => __( 'Show chat icon', 'classified-listing-toolkits' ),
			'label_on'    => __( 'On', 'classified-listing-toolkits' ),
			'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
			'default'     => 'yes',
			'description' => __( 'Show or Hide Icon. Default: On', 'classified-listing-toolkits' ),
		];
		$new_fields[] = [
			'type'        => Controls_Manager::NUMBER,
			'id'          => 'rtcl_show_chat_icon_order',
			'label'       => __( 'Chat icon order', 'classified-listing-toolkits' ),
			'default'     => '3',
			'description' => __( 'Icon order', 'classified-listing-toolkits' ),
			'condition'   => [
				'rtcl_show_chat_option' => 'yes',
			],
		];

		return $obj->insert_new_controls( 'rtcl_show_favourites', $new_fields, $fields );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $fields array filed.
	 * @param [type] $obj class obeject.
	 *
	 * @return array.
	 */
	public static function listing_widget_content_visibility_fields( $fields, $obj ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $fields;
		}

		$new_fields   = [];
		$new_fields[] = [
			'type'        => Controls_Manager::SWITCHER,
			'id'          => 'rtcl_show_custom_fields',
			'label'       => __( 'Show Custom fields', 'classified-listing-toolkits' ),
			'label_on'    => __( 'On', 'classified-listing-toolkits' ),
			'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
			'default'     => '',
			'description' => __( 'Only work for Pro. Default: Off', 'classified-listing-toolkits' ),
		];
		if ( Helper::is_enable_quick_view() ) {
			$new_fields[] = [
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_quick_view',
				'label'       => __( 'Show Quick View', 'classified-listing-toolkits' ),
				'label_on'    => __( 'On', 'classified-listing-toolkits' ),
				'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
				'default'     => 'yes',
				'description' => __( 'Only work for Pro. Default: Off', 'classified-listing-toolkits' ),
			];
		}
		if ( Helper::is_enable_compare() ) {
			$new_fields[] = [
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_show_compare',
				'label'       => __( 'Show Compare', 'classified-listing-toolkits' ),
				'label_on'    => __( 'On', 'classified-listing-toolkits' ),
				'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
				'default'     => 'yes',
				'description' => __( 'Only work for Pro. Default: Off', 'classified-listing-toolkits' ),
			];
		}


		return $obj->insert_new_controls( 'rtcl_show_favourites', $new_fields, $fields );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $style main style.
	 *
	 * @return array
	 */
	public static function el_search_style( $style ) {
		if ( rtcl()->has_pro() ) {
			$style['popup']      = esc_html__( 'Popup', 'classified-listing-toolkits' );
			$style['suggestion'] = esc_html__( 'Auto Suggestion', 'classified-listing-toolkits' );
			$style['standard']   = esc_html__( 'Standard', 'classified-listing-toolkits' );
		}

		return $style;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $data main data.
	 *
	 * @return array
	 */
	public static function el_search_widget_data( $data ) {
		if ( get_query_var( 'rtcl_location' ) && $location = get_term_by( 'slug', get_query_var( 'rtcl_location' ), rtcl()->location ) ) {
			$data['selected_location'] = $location;
		}

		if ( get_query_var( 'rtcl_category' ) && $category = get_term_by( 'slug', get_query_var( 'rtcl_category' ), rtcl()->category ) ) {
			$data['selected_category'] = $category;
		}

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $data main data.
	 *
	 * @return array
	 */
	public static function el_location_box_path( $data ) {
		$style       = isset( $data['style'] ) ? $data['style'] : 'style-1';
		$pro_version = [
			'style-3',
		];
		if ( in_array( $style, $pro_version ) ) {
			$data['template']              = 'elementor/single-location/grid-' . $style;
			$data['default_template_path'] = Helper::get_plugin_template_path();
		}

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listings_location_style( $data ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $data;
		}

		$data['style-3'] = esc_html__( 'Style 3', 'classified-listing-toolkits' );

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $data main data.
	 *
	 * @return array
	 */
	public static function el_listing_category_box_path( $data ) {
		$style = isset( $data['style'] ) ? $data['style'] : 'style-1';
		if ( 'style-1' !== $style ) {
			$data['default_template_path'] = Helper::get_plugin_template_path();
		}

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listing_category_box_style( $data ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $data;
		}

		$data['style-2'] = esc_html__( 'Style 2', 'classified-listing-toolkits' );

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $data main data.
	 *
	 * @return array
	 */
	public static function el_listing_filter_path( $data ) {
		$style = isset( $data['style'] ) ? $data['style'] : 'style-1';

		if ( 'style-1' !== $style ) {
			$data['default_template_path'] = Helper::get_plugin_template_path();
		}

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listings_style( $data = [] ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $data;
		}

		$data['style-2'] = [
			'title' => esc_html__( 'Style 2', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/list-style-02.png",
		];
		$data['style-3'] = [
			'title' => esc_html__( 'Style 3', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/list-style-03.png",
		];
		$data['style-4'] = [
			'title' => esc_html__( 'Style 4', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/list-style-04.png",
		];
		$data['style-5'] = [
			'title' => esc_html__( 'Style 5', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/list-style-05.png",
		];

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listings_style_grid( $data = [] ) {
		if ( ! Helper::is_pro_with_old_dependency() ) {
			return $data;
		}

		$data['style-2'] = [
			'title' => esc_html__( 'Style 2', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/grid-style-02.png",
		];
		$data['style-3'] = [
			'title' => esc_html__( 'Style 3', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/grid-style-03.png",
		];
		$data['style-4'] = [
			'title' => esc_html__( 'Style 4', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/grid-style-04.png",
		];
		$data['style-5'] = [
			'title' => esc_html__( 'Style 5', 'classified-listing-toolkits' ),
			'url'   => CLASSIFIED_LISTING_TOOLKITS_ASSETS . "/images/el-layout/grid-style-05.png",
		];

		return $data;
	}

	public static function divi_listing_grid_styles( $styles ) {

		if ( Helper::is_pro_with_old_dependency() ) {
			$styles['style-2'] = esc_html__( 'Style 2', 'classified-listing-toolkits' );
		}

		return $styles;
	}

}
