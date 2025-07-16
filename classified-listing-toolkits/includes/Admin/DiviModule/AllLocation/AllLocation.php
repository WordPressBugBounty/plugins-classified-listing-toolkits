<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\AllLocation;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Controllers\Blocks\AdminAjaxController;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\Base\DiviModule;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper as DiviFunctions;

class AllLocation extends DiviModule {

	public $slug = 'rtcl_listing_all_location';
	public $vb_support = 'on';
	public $icon_path;

    public $bind_wrapper = '';

	protected $module_credits
		= [
			'author'     => 'RadiusTheme',
			'author_uri' => 'https://radiustheme.com',
		];

	public function init() {
        $this->bind_wrapper = Helper::is_divi_plugin_active() ? '' : '.et-db .et-l ' ;

		$this->name      = esc_html__( 'Listing Location', 'classified-listing-toolkits' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';
		$this->folder_name = 'et_pb_classified_general_module';
		$this->settings_modal_toggles = [
			'general'  => [
				'toggles' => [
					'general'    => esc_html__( 'General', 'classified-listing-toolkits' ),
					'query'      => esc_html__( 'Query', 'classified-listing-toolkits' ),
					'visibility' => esc_html__( 'Visibility', 'classified-listing-toolkits' ),
				],
			],
			'advanced' => [
				'toggles' => [
					'card'        => esc_html__( 'Card', 'classified-listing-toolkits' ),
					'title'       => esc_html__( 'Title', 'classified-listing-toolkits' ),
					'description' => esc_html__( 'Content', 'classified-listing-toolkits' ),
					'count'       => esc_html__( 'Count', 'classified-listing-toolkits' ),
				],
			],
		];
	}

	public function get_fields() {
		$location_dropdown = DiviFunctions::get_listing_taxonomy( 'parent', rtcl()->location );

		return [
			'rtcl_location_style'    => [
				'label'       => esc_html__( 'Style', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'style-1' => __( 'Style 1', 'classified-listing-toolkits' ),
				],
				'default'     => 'style-1',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_grid_column'       => [
				'label'          => esc_html__( 'Grid Column', 'classified-listing-toolkits' ),
				'type'           => 'select',
				'options'        => [
					'4' => __( 'Column 4', 'classified-listing-toolkits' ),
					'3' => __( 'Column 3', 'classified-listing-toolkits' ),
					'2' => __( 'Column 2', 'classified-listing-toolkits' ),
					'1' => __( 'Column 1', 'classified-listing-toolkits' ),
				],
				'default'        => '3',
				'description'    => esc_html__( 'Select column number to display location.', 'classified-listing-toolkits' ),
				'mobile_options' => true,
				'tab_slug'       => 'general',
				'toggle_slug'    => 'general',
			],
			'rtcl_location'          => [
				'label'       => esc_html__( 'Location', 'classified-listing-toolkits' ),
				'type'        => 'multiple_checkboxes',
				'options'     => $location_dropdown,
				'description' => esc_html__( 'Leave checkboxes unchecked to select all', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'query',
			],
			'rtcl_location_limit'    => [
				'label'       => esc_html__( 'Limit', 'classified-listing-toolkits' ),
				'type'        => 'number',
				'default'     => '10',
				'tab_slug'    => 'general',
				'toggle_slug' => 'query',
				'description' => esc_html__( 'Number of location to display', 'classified-listing-toolkits' ),
			],
			'rtcl_orderby'           => [
				'label'       => esc_html__( 'Order By', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'none'    => esc_html__( 'None', 'classified-listing-toolkits' ),
					'term_id' => esc_html__( 'ID', 'classified-listing-toolkits' ),
					'date'    => esc_html__( 'Date', 'classified-listing-toolkits' ),
					'name'    => esc_html__( 'Title', 'classified-listing-toolkits' ),
					'count'   => esc_html__( 'Count', 'classified-listing-toolkits' ),
					'custom'  => esc_html__( 'Custom Order', 'classified-listing-toolkits' ),
				],
				'default'     => 'name',
				'tab_slug'    => 'general',
				'toggle_slug' => 'query',
			],
			'rtcl_order'             => [
				'label'       => esc_html__( 'Sort By', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'asc'  => __( 'Ascending', 'classified-listing-toolkits' ),
					'desc' => __( 'Descending', 'classified-listing-toolkits' ),
				],
				'default'     => 'asc',
				'tab_slug'    => 'general',
				'toggle_slug' => 'query',
			],
			'rtcl_hide_empty'        => [
				'label'       => esc_html__( 'Hide Empty', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'off',
				'description' => __( 'Show / Hide location that has no listings.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'query',
			],
			// computed.
			'__location'             => array(
				'type'                => 'computed',
				'computed_callback'   => array( AllLocation::class, 'get_content' ),
				'computed_depends_on' => array(
					'rtcl_location',
					'rtcl_location_limit',
					'rtcl_orderby',
					'rtcl_order',
					'rtcl_hide_empty'
				)
			),
			// visibility
			'rtcl_show_count'        => [
				'label'       => esc_html__( 'Show Count', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'description' => __( 'Show / Hide listing counts.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			'rtcl_description'       => [
				'label'       => esc_html__( 'Show Description', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'off',
				'description' => __( 'Show / Hide location description.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			'rtcl_content_limit'     => [
				'label'       => esc_html__( 'Description Limit', 'classified-listing-toolkits' ),
				'type'        => 'number',
				'default'     => '20',
				'description' => __( 'Number of words to display.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
				'show_if'     => [
					'rtcl_description' => 'on',
				],
			],
			// Style
			'rtcl_content_alignment' => [
				'label'       => esc_html__( 'Content Alignment', 'classified-listing-toolkits' ),
				'type'        => 'text_align',
				'options'     => et_builder_get_text_orientation_options( [ 'justified' ] ),
				'default'     => 'center',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'card',
			],
			'rtcl_box_gutter'        => [
				'label'          => esc_html__( 'Gutter Space', 'classified-listing-toolkits' ),
				'description'    => esc_html__( 'Here you can define gutter for the box.', 'classified-listing-toolkits' ),
				'type'           => 'range',
				'default'        => '15px',
				'allowed_units'  => [ 'px' ],
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 5,
					'step' => 1,
					'max'  => 50,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'card',
				'mobile_options' => true,
			],
			'rtcl_title_color'       => [
				'label'       => esc_html__( 'Name Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for location name.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'title',
				'hover'       => 'tabs',
			],
			'rtcl_desc_color'        => [
				'label'       => esc_html__( 'Description Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for location description.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'description'
			],
			'rtcl_count_color'       => [
				'label'       => esc_html__( 'Count Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for listing count.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'count',
			],
		];
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];

		$advanced_fields['fonts'] = [
			'title'       => [
				'css'              => array(
					'main' => $this->bind_wrapper.'%%order_class%% .rtcl-location-wrapper .rtcl-location-title a',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'title',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.2em',
				),
				'font_size'        => array(
					'default' => '18px',
				),
				'font'             => [
					'default' => '|700|||||||',
				],
			],
			'description' => [
				'css'              => array(
					'main' => $this->bind_wrapper.'%%order_class%% .rtcl-location-wrapper .location-details-inner p',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'description',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.6em',
				),
				'font_size'        => array(
					'default' => '16px',
				),
				'font'             => [
					'default' => '|400|||||||',
				],
			],
			'count'       => [
				'css'              => array(
					'main' => $this->bind_wrapper.'%%order_class%% .rtcl-location-wrapper .location-details-inner .count',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'count',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.6em',
				),
				'font_size'        => array(
					'default' => '16px',
				),
				'font'             => [
					'default' => '|400|||||||',
				],
			]
		];

		$advanced_fields['margin_padding'] = [
			'css'         => [
				'main'      => $this->bind_wrapper.'%%order_class%% .rtcl-location-item .location-details',
				'important' => 'all',
			],
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'card',
		];

		return $advanced_fields;
	}

	public static function get_content( $args = [] ) {

		$available_location = DiviFunctions::get_listing_taxonomy( 'parent', rtcl()->location );
		$sort_locations     = $location_terms = [];


		if ( ! empty( $available_location ) ) {
			$location_includes = ! empty( $args['rtcl_location'] ) ? $args['rtcl_location'] : '';
			$location_includes = explode( '|', $location_includes );

			$location_terms = DiviFunctions::divi_get_user_selected_terms( $location_includes, rtcl()->location );

			if ( ! empty( $location_terms ) ) {
				foreach ( $location_terms as $term_id ) {
					$sort_locations[] = [
						'value' => $term_id,
						'title' => $available_location[ $term_id ]
					];
				}
			}
		}

		$config = [
			'location_limit'     => $args['rtcl_location_limit'],
			'hide_empty'         => 'on' === $args['rtcl_hide_empty'],
			'orderby'            => $args['rtcl_orderby'],
			'sortby'             => $args['rtcl_order'],
			'enable_parent'      => true,
			'location_type'      => empty( $location_terms ) ? 'all' : 'selected',
			'sub_location_limit' => 0,
			'locations'          => ! empty( $sort_locations ) ? $sort_locations : [],
		];

		$results = AdminAjaxController::rtcl_gb_all_location_query( $config );

		return is_array( $results ) ? $results : [];
	}

	/**
	 * Widget result.
	 *
	 * @param [array] $data array of query.
	 *
	 * @return array
	 */
	public function widget_results( $data ) {
		// user's selected location
		$location_includes = ! empty( $data['rtcl_location'] ) ? $data['rtcl_location'] : '';
		$location_includes = explode( '|', $location_includes );

		$location_terms = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::divi_get_user_selected_terms( $location_includes, rtcl()->location );

		$args = array(
			'taxonomy'     => rtcl()->location,
			'parent'       => 0,
			'orderby'      => ! empty( $data['rtcl_orderby'] ) ? $data['rtcl_orderby'] : 'name',
			'order'        => ! empty( $data['rtcl_order'] ) ? $data['rtcl_order'] : 'asc',
			'hide_empty'   => ! empty( $data['rtcl_hide_empty'] ) && 'on' === $data['rtcl_hide_empty'],
			'include'      => ! empty( $location_terms ) ? $location_terms : [],
			'hierarchical' => false,
		);
		if ( 'custom' === $data['rtcl_orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_rtcl_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		}
		$terms = get_terms( $args );
		if ( ! empty( $data['rtcl_location_limit'] ) ) {
			$number = $data['rtcl_location_limit'];
			$terms  = array_slice( $terms, 0, $number );
		}

		return $terms;
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings = $this->props;
		$terms    = $this->widget_results( $settings );

		$this->render_css( $render_slug );

		$style = isset( $settings['rtcl_location_style'] ) ? sanitize_text_field( $settings['rtcl_location_style'] ) : 'style-1';

		$template_style = 'divi/all-location/' . $style;

		$data = [
			'template'      => $template_style,
			'style'         => $style,
			'settings'      => $settings,
			'terms'         => $terms,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_listing_location_data', $data );

		return Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	}

	protected function render_css( $render_slug ) {
		$wrapper           = $this->bind_wrapper.'%%order_class%% .rtcl-location-wrapper';
		$title_color       = $this->props['rtcl_title_color'];
		$title_hover_color = $this->get_hover_value( 'rtcl_title_color' );
		$count_color       = $this->props['rtcl_count_color'];
		$description_color = $this->props['rtcl_desc_color'];
		$box_gutter        = $this->props['rtcl_box_gutter'];

		// Title
		if ( ! empty( $title_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-location-title a",
					'declaration' => sprintf( 'color: %1$s;', $title_color ),
				]
			);
		}
		if ( ! empty( $title_hover_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-location-title a:hover",
					'declaration' => sprintf( 'color: %1$s;', $title_hover_color ),
				]
			);
		}
		// count
		if ( ! empty( $count_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .location-details-inner .count",
					'declaration' => sprintf( 'color: %1$s;', $count_color ),
				]
			);
		}
		// description
		if ( ! empty( $description_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .location-details-inner p",
					'declaration' => sprintf( 'color: %1$s!important;', $description_color ),
				]
			);
		}
		// box
		if ( ! empty( $box_gutter ) ) {
			$this->get_responsive_styles(
				'rtcl_box_gutter',
				"$wrapper .rtcl-grid-view",
				array( 'primary' => 'grid-gap' ),
				array( 'default' => '15px' ),
				$render_slug
			);
		}
	}
}