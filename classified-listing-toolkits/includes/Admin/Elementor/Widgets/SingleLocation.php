<?php
/**
 * Main Elementor ListingCategoryBox Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  Classifid-listing
 * @since    1.0.0
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Widgets;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use RadiusTheme\ClassifiedListingToolkits\Abstracts\ElementorWidgetBase;
use Elementor\Controls_Manager;
use Rtcl\Helpers\Functions;
use \Elementor\Icons_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Typography;

/**
 * Elementor SingleLocation Widget.
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class SingleLocation extends ElementorWidgetBase {

	/**
	 * Undocumented function
	 *
	 * @param  array  $data  default array.
	 * @param  mixed  $args  default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtcl_name = __( 'Single Location', 'classified-listing-toolkits' );
		$this->rtcl_base = 'rtcl-listing-single-location';
		parent::__construct( $data, $args );
	}

	/**
	 * Set Query controlls
	 *
	 * @return array
	 */
	public function widget_general_fields(): array {
		$location_dropdown = $this->taxonomy_list( 'all', 'rtcl_location' );
		$fields            = [
			[
				'mode'  => 'section_start',
				'id'    => 'sec_general',
				'label' => __( 'General', 'classified-listing-toolkits' ),
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_location_style',
				'label'   => __( 'Style', 'classified-listing-toolkits' ),
				'options' => $this->location_box_style(),
				'default' => 'style-1',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'location',
				'label'   => __( 'Location', 'classified-listing-toolkits' ),
				'options' => $location_dropdown,
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'display_count',
				'label'     => __( 'Show Listing Counts', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
			],

			[
				'type'      => Controls_Manager::TEXT,
				'id'        => 'display_text_after_count',
				'label'     => __( 'Text After Count ', 'classified-listing-toolkits' ),
				'default'   => 'Ads',
				'condition' => [
					'display_count' => 'yes',
				],
			],

			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'enable_link',
				'label'     => __( 'Enable Link', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
			],

			[
				'type'      => Controls_Manager::ICONS,
				'id'        => 'box_icon',
				'label'     => esc_html__( 'Icon', 'classified-listing-toolkits' ),
				'default'   => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'solid',
				],
				'condition' => [
					'rtcl_location_style' => 'style-3',
				],
			],
			[
				'type'       => Controls_Manager::SLIDER,
				'mode'       => 'responsive',
				'id'         => 'min-width',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'label'      => __( 'Width', 'classified-listing-toolkits' ),
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box' => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			[
				'type'       => Controls_Manager::SLIDER,
				'mode'       => 'responsive',
				'id'         => 'width',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'label'      => __( 'Max Width', 'classified-listing-toolkits' ),
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			],

			[
				'type'       => Controls_Manager::SLIDER,
				'mode'       => 'responsive',
				'id'         => 'height',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 290,
				],
				'label'      => __( 'Box Height', 'classified-listing-toolkits' ),
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			[
				'id'         => 'border_radius',
				'label'      => esc_html__( 'Border Radius', 'classified-listing-toolkits', '' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'  => 'section_start',
				'id'    => 'sec_background',
				'label' => __( 'Background', 'classified-listing-toolkits' ),
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_bg_image_style',
				'label'   => __( 'Image Type', 'classified-listing-toolkits' ),
				'options' => [
					'featured_image' => __( 'Featured Image', 'classified-listing-toolkits' ),
					'custom_image'   => __( 'Custom Image', 'classified-listing-toolkits' ),
				],
				'default' => 'custom_image',
			],
			[
				'type'      => Group_Control_Background::get_type(),
				'mode'      => 'group',
				'types'     => [ 'classic', 'gradient' ],
				'id'        => 'bgimg',
				'label'     => __( 'Background', 'classified-listing-toolkits' ),
				'selector'  => '{{WRAPPER}} .rtcl-el-listing-location-box .rtin-img',
				'condition' => [
					'rtcl_bg_image_style' => 'custom_image',
				],
			],
			[
				'label'     => esc_html__( 'Overlay Settings', 'classified-listing-toolkits' ),
				'id'        => 'bg_control_heading',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			],
			// Wrapper style settings.
			[
				'mode' => 'tabs_start',
				'id'   => 'rtcl_location_overlay_tabs_start',
			],

			// Tab For Normal view.
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_location_overlay_tab_normal',
				'label' => esc_html__( 'Normal', 'classified-listing-toolkits' ),
			],

			[
				'type'     => Group_Control_Background::get_type(),
				'mode'     => 'group',
				'types'    => [ 'gradient' ],
				'id'       => 'gradient_bg',
				'label'    => __( 'Overlay Background', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-el-listing-location-box:not(.location-box-style-3) .rtin-content,{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtcl-image-wrapper .rtin-img::before',
			],
			[
				'mode' => 'tab_end',
			],
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_location_overlay_tab_hover',
				'label' => esc_html__( 'Hover', 'classified-listing-toolkits' ),
			],
			[
				'type'     => Group_Control_Background::get_type(),
				'mode'     => 'group',
				'types'    => [ 'gradient' ],
				'id'       => 'gradient_bg_hover',
				'label'    => __( 'Overlay Background', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-el-listing-location-box:not(.location-box-style-3):hover .rtin-content,{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtcl-image-wrapper .rtin-img::after',
			],
			[
				'mode' => 'tab_end',
			],
			[
				'mode' => 'tabs_end',
			],
			[
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public function location_box_style() {
		$style = apply_filters(
			'rtcl_el_location_box_style',
			[
				'style-1' => __( 'Style 1', 'classified-listing-toolkits' ),
				'style-2' => __( 'Style 2', 'classified-listing-toolkits' ),
			],
		);

		return $style;
	}

	/**
	 * Set Style controlls
	 *
	 * @return array
	 */
	public function widget_style_fields(): array {
		$fields = [
			// Style Tab.
			[
				'mode'  => 'section_start',
				'id'    => 'sec_style_color',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Style', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'title_color',
				'label'     => __( 'Title', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtin-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .rtin-title a' => 'color: {{VALUE}}',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'title_hover_color',
				'label'     => __( 'Title Hover', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-el-listing-location-box:hover .rtin-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .rtcl-el-listing-location-box:hover .rtin-title a' => 'color: {{VALUE}}',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'counter_color',
				'label'     => __( 'Counter', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .rtin-counter' => 'color: {{VALUE}}' ],
			],

			[
				'mode' => 'section_end',
			],

			[
				'mode'      => 'section_start',
				'id'        => 'sec_icon_style',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Icon', 'classified-listing-toolkits' ),
				'condition' => [
					'rtcl_location_style' => 'style-3',
				],
			],
			// Wrapper style settings.
			[
				'mode' => 'tabs_start',
				'id'   => 'rtcl_location_icon_start',
			],

			// Tab For Normal view.
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_location_icon_normal',
				'label' => esc_html__( 'Normal', 'classified-listing-toolkits' ),
			],

			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'icon_bg_color',
				'label'     => __( 'Icon Background Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtin-content > a' => 'background: {{VALUE}}',

				],
			],

			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'icon_color',
				'label'     => __( 'Icon Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtin-content > a' => 'color: {{VALUE}}',
				],
			],

			[
				'id'         => 'icon_rotation',
				'label'      => esc_html__( 'Icon Rotate', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 5,
					],
				],
				'default'    => [
					'size' => '',
					'unit' => 'deg',
				],
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtin-content > a' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			],

			[
				'mode' => 'tab_end',
			],
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_location_icon_hover',
				'label' => esc_html__( 'Hover', 'classified-listing-toolkits' ),
			],

			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'icon_bg_hover_color',
				'label'     => __( 'Icon Background Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3:hover .rtin-content > a' => 'background: {{VALUE}}',

				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'icon_hover_color',
				'label'     => __( 'Icon Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3 .rtin-content > a:hover' => 'color: {{VALUE}}',

				],
			],
			[
				'id'         => 'icon_rotation_hover',
				'label'      => esc_html__( 'Icon Rotate', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 5,
					],
				],
				'default'    => [
					'size' => '',
					'unit' => 'deg',
				],
				'selectors'  => [
					'{{WRAPPER}} .rtcl-el-listing-location-box.location-box-style-3:hover .rtin-content>a' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			],

			[
				'mode' => 'tab_end',
			],
			[
				'mode' => 'tabs_end',
			],

			[
				'mode' => 'section_end',
			],

			[
				'mode'  => 'section_start',
				'id'    => 'sec_style_type',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Typography', 'classified-listing-toolkits' ),
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'title_typo',
				'label'    => __( 'Title', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtin-title',
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'counter_typo',
				'label'    => __( 'Counter', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtin-counter',
			],
			[
				'mode' => 'section_end',
			],

		];

		return $fields;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_style_depends() {
		return [ 'elementor-icons-shared-0', 'elementor-icons-fa-solid' ];
	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		wp_enqueue_style( 'fontawesome' );
		$settings = $this->get_settings();
		ob_start();
		Icons_Manager::render_icon( $settings['box_icon'], [ 'aria-hidden' => 'true' ] );

		$icon  = ob_get_clean();
		$style = isset( $settings['rtcl_location_style'] ) ? $settings['rtcl_location_style'] : 'style-1';
		if ( ! in_array( $style, array_keys( $this->location_box_style() ) ) ) {
			$style = 'style-1';
		}
		$data = [
			'template'              => 'elementor/single-location/grid-' . $style,
			'style'                 => $style,
			'icon'                  => $icon,
			'default_template_path' => Helper::get_plugin_template_path(),
		];
		$term = get_term( $settings['location'], 'rtcl_location' );
		if ( $term && ! is_wp_error( $term ) ) {
			$data['title']     = $term->name;
			$data['count']     = $term->count;
			$data['permalink'] = get_term_link( $term );
			$data['term_id']   = $term->term_id;
		} else {
			$data['title']             = __( 'Please Select a Location and Background', 'classified-listing-toolkits' );
			$data['count']             = 0;
			$settings['display_count'] = $data['enable_link'] = false;
			$data['permalink']         = '#';
		}

		$data['settings'] = $settings;
		$data             = apply_filters( 'rtcl_el_location_box_data', $data );
		Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
	}


}
