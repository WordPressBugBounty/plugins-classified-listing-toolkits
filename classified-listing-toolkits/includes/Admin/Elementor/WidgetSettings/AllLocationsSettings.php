<?php
/**
 * Main Elementor ListingCategoryBox Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  Classifid-listing
 * @since 1.0.0
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings;

use Elementor\Group_Control_Image_Size;
use RadiusTheme\ClassifiedListingToolkits\Abstracts\ElementorWidgetBase;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;

/**
 * Elementor AllLocationsSettings Widget.
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class AllLocationsSettings extends ElementorWidgetBase {

	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public function location_box_list_style() {
		$style = apply_filters(
			'rtcl_el_location_boxes_list_style',
			[
				'style-1' => __( 'Style 1', 'classified-listing-toolkits' ),
			],
		);

		return $style;
	}

	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public function location_box_grid_style() {
		$style = apply_filters(
			'rtcl_el_location_boxes_grid_style',
			[
				'style-1' => __( 'Style 1', 'classified-listing-toolkits' ),
				'style-2' => __( 'Style 2', 'classified-listing-toolkits' ),
			],
		);

		return $style;
	}

	/**
	 * Undocumented function.
	 *
	 * @return array
	 */
	public function location_box_col() {
		$style = [
			'12' => __( '1 Col', 'classified-listing-toolkits' ),
			'6'  => __( '2 Col', 'classified-listing-toolkits' ),
			'4'  => __( '3 Col', 'classified-listing-toolkits' ),
			'3'  => __( '4 Col', 'classified-listing-toolkits' ),
			'2'  => __( '6 Col', 'classified-listing-toolkits' ),
		];
		$style = apply_filters( 'rtcl_el_location_box_col', $style );

		return $style;
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
				'id'      => 'rtcl_location_view',
				'label'   => __( 'View', 'classified-listing-toolkits' ),
				'options' => [
					'grid' => __( 'Grid View', 'classified-listing-toolkits' ),
					'list' => __( 'List View', 'classified-listing-toolkits' ),
				],
				'default' => 'grid',
			],

			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_location_grid_style',
				'label'     => __( 'Style', 'classified-listing-toolkits' ),
				'options'   => $this->location_box_grid_style(),
				'default'   => 'style-1',
				'condition' => [
					'rtcl_location_view' => 'grid',
				],
			],

			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_location_display_rule',
				'label'   => __( 'Location Display Type', 'classified-listing-toolkits' ),
				'options' => [
					'all'      => __( 'All Location', 'classified-listing-toolkits' ),
					'selected' => __( 'Selected Location', 'classified-listing-toolkits' ),
				],
				'default' => 'all',
			],
			[
				'type'        => Controls_Manager::SELECT2,
				'id'          => 'rtcl_location',
				'label'       => __( 'Select Location', 'classified-listing-toolkits' ),
				'multiple'    => true,
				'options'     => $location_dropdown,
				'description' => __( 'Only Location that has listings', 'classified-listing-toolkits' ),
				'condition'   => [
					'rtcl_location_display_rule' => 'selected',
				],
			],
			[
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'rtcl_location_limit',
				'label'       => __( 'Location Limit', 'classified-listing-toolkits' ),
				'default'     => '',
				'description' => __( 'How Many Location will Display? Leave empty for all.', 'classified-listing-toolkits' ),
				'condition'   => [
					'rtcl_location_display_rule' => 'all',
				],

			],

			[
				'type'       => Controls_Manager::SWITCHER,
				'id'         => 'child_location',
				'label'      => __( 'Show Child location', 'classified-listing-toolkits' ),
				'label_on'   => __( 'On', 'classified-listing-toolkits' ),
				'label_off'  => __( 'Off', 'classified-listing-toolkits' ),
				'default'    => 'yes',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],

			],

			[
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'rtcl_sub_location_limit',
				'label'       => __( 'Sub Location Limit', 'classified-listing-toolkits' ),
				'default'     => '5',
				'description' => __( 'How Many Child Location will Display ?', 'classified-listing-toolkits' ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'child_location',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],
					],
				],

			],

			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'display_count',
				'label'     => __( 'Show Counts', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',

			],

			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'display_count_position',
				'label'     => __( 'Count position', 'classified-listing-toolkits' ),
				'options'   => [
					'inline'   => __( 'Inline', 'classified-listing-toolkits' ),
					'new_line' => __( 'New Line', 'classified-listing-toolkits' ),
				],
				'default'   => 'inline',
				'condition' => [
					'display_count'      => 'yes',
					'rtcl_location_view' => 'list',
				],
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'display_descriptiuon',
				'label'     => __( 'Show Description', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
			],
			[
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'rtcl_content_limit',
				'label'       => __( 'Short Description Word Limit', 'classified-listing-toolkits' ),
				'default'     => '20',
				'description' => __( 'Number of Words to display', 'classified-listing-toolkits' ),
				'condition'   => [ 'display_descriptiuon' => [ 'yes' ] ],
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_show_image',
				'label'     => __( 'Show Image', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => '',
			],
			[
				'label'     => __( 'Image Size', 'classified-listing-toolkits' ),
				'type'      => Group_Control_Image_Size::get_type(),
				'id'        => 'rtcl_image_size',
				'mode'      => 'group',
				'default'   => 'rtcl-thumbnail',
				'separator' => 'none',
				'condition' => [
					'rtcl_show_image' => 'yes',
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
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_orderby',
				'label'   => __( 'Order By', 'classified-listing-toolkits' ),
				'options' => [
					'none'    => __( 'None', 'classified-listing-toolkits' ),
					'term_id' => __( 'ID', 'classified-listing-toolkits' ),
					'date'    => __( 'Date', 'classified-listing-toolkits' ),
					'name'    => __( 'Title', 'classified-listing-toolkits' ),
					'count'   => __( 'Count', 'classified-listing-toolkits' ),
					'custom'  => __( 'Custom Order', 'classified-listing-toolkits' ),
				],
				'default' => 'name',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_order',
				'label'   => __( 'Sort By', 'classified-listing-toolkits' ),
				'options' => [
					'asc'  => __( 'Ascending', 'classified-listing-toolkits' ),
					'desc' => __( 'Descending', 'classified-listing-toolkits' ),
				],
				'default' => 'asc',
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_hide_empty',
				'label'       => __( 'Hide Empty', 'classified-listing-toolkits' ),
				'label_on'    => __( 'On', 'classified-listing-toolkits' ),
				'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
				'default'     => 'yes',
				'description' => __( 'Hide Categories that has no listings. Default: On', 'classified-listing-toolkits' ),
			],
			[
				'mode' => 'section_end',
			],

			// Responsive Columns.
			[
				'mode'      => 'section_start',
				'id'        => 'rtcl_sec_responsive',
				'label'     => __( 'Number of Responsive Columns', 'classified-listing-toolkits' ),
				'condition' => [
					'rtcl_location_view' => 'grid',
				],
			],
			[
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'rtcl_col_xl',
				'label'   => __( 'Desktops: >1199px', 'classified-listing-toolkits' ),
				'options' => $this->location_box_col(),
				'default' => '3',
			],
			[
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'rtcl_col_lg',
				'label'   => __( 'Desktops: >991px', 'classified-listing-toolkits' ),
				'options' => $this->location_box_col(),
				'default' => '3',
			],
			[
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'rtcl_col_md',
				'label'   => __( 'Tablets: >767px', 'classified-listing-toolkits' ),
				'options' => $this->location_box_col(),
				'default' => '4',
			],
			[
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'rtcl_col_sm',
				'label'   => __( 'Phones: >575px', 'classified-listing-toolkits' ),
				'options' => $this->location_box_col(),
				'default' => '6',
			],
			[
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'rtcl_col_mobile',
				'label'   => __( 'Small Phones: <576px', 'classified-listing-toolkits' ),
				'options' => $this->location_box_col(),
				'default' => '12',
			],
			[
				'mode' => 'section_end',
			],

		];

		return $fields;
	}

	/**
	 * Set Style controlls
	 *
	 * @return array
	 */
	public function widget_style_fields(): array {
		$fields = [
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_sec_wrapper',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Box style', 'classified-listing-toolkits' ),
			],

			[
				'label'      => __( 'Gutter pading', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_gutter_padding',
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .location-boxes-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};margin-bottom: {{BOTTOM}}{{UNIT}}',
				],
			],

			[
				'label'      => __( 'Box pading', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_wrapper_padding',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .el-all-locations:not(.grid-style-2) .location-boxes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'rtcl_location_view',
							'operator' => '!in',
							'value'    => [ 'grid' ],
						],
						[
							'name'     => 'rtcl_location_grid_style',
							'operator' => '!in',
							'value'    => [ 'style-2' ],
						],
					],
				],
			],
			[
				'label'      => __( 'Border Radius', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_location_wrapper_border_radius',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .el-all-locations .location-boxes' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			[
				'type'     => Group_Control_Border::get_type(),
				'mode'     => 'group',
				'id'       => 'location_border',
				'selector' => '{{WRAPPER}} .el-all-locations .location-boxes',
			],
			[
				'label'     => esc_html__( 'Special style Settings', 'classified-listing-toolkits' ),
				'id'        => 'location_control_heading',
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'rtcl_location_grid_style' => [ 'style-2' ],
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'header_border_color',
				'label'     => __( 'Header Border Color', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .location-boxes-header' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'rtcl_location_grid_style' => [ 'style-2' ],
				],
			],
			[
				'label'      => __( 'Header pading', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_header_padding',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .location-boxes .location-boxes-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],
			],
			[
				'type'       => \Elementor\Group_Control_Background::get_type(),
				'mode'       => 'group',
				'types'      => [ 'classic', 'gradient' ],
				'id'         => 'headerbg',
				'label'      => __( 'Background', 'classified-listing-toolkits' ),
				'selector'   => '{{WRAPPER}}  .location-boxes .location-boxes-header',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],
			],
			[
				'label'     => esc_html__( 'Special Style Body Settings', 'classified-listing-toolkits' ),
				'id'        => 'location_control_body_heading',
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'rtcl_location_grid_style' => [ 'style-2' ],
				],
			],
			[
				'label'      => __( 'Body pading', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'id'         => 'rtcl_body_padding',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .location-boxes .location-boxes-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],
			],
			[
				'type'       => \Elementor\Group_Control_Background::get_type(),
				'mode'       => 'group',
				'types'      => [ 'classic', 'gradient' ],
				'id'         => 'bodybg',
				'label'      => __( 'Background', 'classified-listing-toolkits' ),
				'selector'   => '{{WRAPPER}} .location-boxes',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],
			],
			[
				'mode' => 'section_end',
			],
			// Style Tab.
			[
				'mode'  => 'section_start',
				'id'    => 'sec_style_color',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Color', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'title_color',
				'label'     => __( 'Title', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .rtcl-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .rtcl-title a' => 'color: {{VALUE}}',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'title_hover_color',
				'label'     => __( 'Title Hover', 'classified-listing-toolkits' ),
				'selectors' => [
					'{{WRAPPER}} .location-boxes:hover .rtcl-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .location-boxes:hover .rtcl-title a' => 'color: {{VALUE}}',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'counter_color',
				'label'     => __( 'Counter', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .rtcl-counter' => 'color: {{VALUE}}' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'description_color',
				'label'     => __( 'Description Color', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .rtcl-description' => 'color: {{VALUE}}' ],
				'condition' => [
					'display_descriptiuon' => 'yes',
				],
			],
			// Tab For Hover view.
			[
				'mode'      => 'tabs_start',
				'id'        => 'image_icon_tabs_start',
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'mode'      => 'tab_start',
				'id'        => 'rtcl_child_list_tab_color',
				'label'     => esc_html__( 'Normal', 'classified-listing-toolkits' ),
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'child_list_color',
				'label'     => __( 'Child List Color', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .el-all-locations .location-boxes .rtin-sub-location li a' => 'color: {{VALUE}}' ],
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'child_list_icon_color',
				'label'     => __( 'Child List Icon Color', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .el-all-locations .location-boxes .rtin-sub-location li a i' => 'color: {{VALUE}}' ],
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'mode' => 'tab_end',
			],
			[
				'mode'      => 'tab_start',
				'id'        => 'child_list_color_tab_hover',
				'label'     => esc_html__( 'Hover', 'classified-listing-toolkits' ),
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'child_list_color_hover',
				'label'     => __( 'Child List Hover Color', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .el-all-locations .location-boxes .rtin-sub-location li a:hover' => 'color: {{VALUE}}' ],
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
				],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'child_list_icon_hover',
				'label'     => __( 'Child List Icon Hover Color', 'classified-listing-toolkits' ),
				'selectors' => [ '{{WRAPPER}} .el-all-locations .location-boxes .rtin-sub-location li a:hover i' => 'color: {{VALUE}}' ],
				'condition' => [
					'rtcl_location_grid_style' => 'style-2',
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
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'id'       => 'title_typo',
				'label'    => __( 'Title', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-title',
			],
			[
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'id'       => 'counter_typo',
				'label'    => __( 'Counter', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-counter',
			],
			[
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'id'       => 'description_typo',
				'label'    => __( 'Description', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-description',
			],
			[
				'mode'       => 'group',
				'type'       => \Elementor\Group_Control_Typography::get_type(),
				'id'         => 'child_location_typo',
				'label'      => __( 'Child Location', 'classified-listing-toolkits' ),
				'selector'   => '{{WRAPPER}} .rtin-sub-location li a',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'rtcl_location_view',
									'operator' => 'in',
									'value'    => [ 'grid' ],
								],
								[
									'name'     => 'rtcl_location_grid_style',
									'operator' => 'in',
									'value'    => [ 'style-2' ],
								],
							],
						],

					],
				],
			],
			[
				'mode' => 'section_end',
			],

			[
				'mode'  => 'section_start',
				'tab'   => Controls_Manager::TAB_STYLE,
				'id'    => 'sec_background',
				'label' => __( 'Background', 'classified-listing-toolkits' ),
			],
			[
				'type'     => \Elementor\Group_Control_Background::get_type(),
				'mode'     => 'group',
				'types'    => [ 'classic', 'gradient', 'video' ],
				'id'       => 'bgimg',
				'label'    => __( 'Background', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .location-boxes',
			],
			[
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

}
