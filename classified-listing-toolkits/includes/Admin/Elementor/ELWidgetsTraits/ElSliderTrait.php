<?php
/**
 * Trait for listing widget
 *
 * The Elementor builder.
 *
 * @since    2.0.10
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\ELWidgetsTraits;

use Elementor\Controls_Manager;

trait ElSliderTrait {
	/**
	 * Slider Column
	 *
	 * @return array
	 */
	public function slider_column() {
		return apply_filters(
			'rtcl_listing_slider_column',
			[
				'1' => __('1 Col', 'classified-listing-toolkits'),
				'2' => __('2 Col', 'classified-listing-toolkits'),
				'3' => __('3 Col', 'classified-listing-toolkits'),
				'4' => __('4 Col', 'classified-listing-toolkits'),
				'5' => __('5 Col', 'classified-listing-toolkits'),
				'6' => __('6 Col', 'classified-listing-toolkits'),
			]
		);
	}

	/**
	 * Set style controlls
	 *
	 * @return array
	 */
	public function slider_options() {
		$fields = [
			// Slider Option.
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_sec_slider_settings',
				'label' => __('Slider Options', 'classified-listing-toolkits'),
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_auto_height',
				'label'       => __('Auto Height', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'description' => __('Auto Height. Default: On', 'classified-listing-toolkits'),
				'default'     => '',
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_loop',
				'label'       => __('Loop', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'default'     => '',
				'description' => __('Loop to first item. Default: On', 'classified-listing-toolkits'),
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_autoplay',
				'label'       => __('Autoplay', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'default'     => 'yes',
				'description' => __('Enable or disable autoplay. Default: On', 'classified-listing-toolkits'),
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_stop_on_hover',
				'label'       => __('Stop on Hover', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'default'     => 'yes',
				'description' => __('Stop autoplay on mouse hover. Default: On', 'classified-listing-toolkits'),
				'condition'   => ['slider_autoplay' => 'yes'],
			],
			[
				'type'        => Controls_Manager::SELECT2,
				'id'          => 'slider_delay',
				'label'       => __('Autoplay Delay', 'classified-listing-toolkits'),
				'options'     => [
					'7000' => __('7 Seconds', 'classified-listing-toolkits'),
					'6000' => __('6 Seconds', 'classified-listing-toolkits'),
					'5000' => __('5 Seconds', 'classified-listing-toolkits'),
					'4000' => __('4 Seconds', 'classified-listing-toolkits'),
					'3000' => __('3 Seconds', 'classified-listing-toolkits'),
					'2000' => __('2 Seconds', 'classified-listing-toolkits'),
					'1000' => __('1 Second', 'classified-listing-toolkits'),
				],
				'default'     => '5000',
				'description' => __('Set any value for example 5 seconds to play it in every 5 seconds. Default: 5 Seconds', 'classified-listing-toolkits'),
				'condition'   => ['slider_autoplay' => 'yes'],
			],
			[
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'slider_autoplay_speed',
				'label'       => __('Slide Speed', 'classified-listing-toolkits'),
				'default'     => 2000,
				'description' => __('Slide speed in milliseconds. Default: 200', 'classified-listing-toolkits'),
			],
			[
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'slider_space_between',
				'label'       => __('Space Between', 'classified-listing-toolkits'),
				'default'     => 20,
				'description' => __('Space Between. Default: 20', 'classified-listing-toolkits'),
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_nav',
				'label'       => __('Arrow Navigation', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'default'     => 'yes',
				'description' => __('Loop to first item. Default: On', 'classified-listing-toolkits'),
			],
			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_button_arrow_style',
				'label'     => __('Arrow Position', 'classified-listing-toolkits'),
				'options'   => [
					'style-1' => esc_html__('Center', 'classified-listing-toolkits'),
					'style-2' => esc_html__('Left Top', 'classified-listing-toolkits'),
					'style-3' => esc_html__('Right Top', 'classified-listing-toolkits'),
				],
				'default'   => 'style-1',
				'condition' => [
					'slider_nav' => 'yes',
				],
			],
			[
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_dots',
				'label'       => __('Dot Navigation', 'classified-listing-toolkits'),
				'label_on'    => __('On', 'classified-listing-toolkits'),
				'label_off'   => __('Off', 'classified-listing-toolkits'),
				'default'     => '',
				'description' => __('Loop to first item. Default: On', 'classified-listing-toolkits'),
			],
			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_button_dot_style',
				'label'     => __('Style', 'classified-listing-toolkits'),
				'options'   => [
					'style-1' => esc_html__('Style 1', 'classified-listing-toolkits'),
					'style-2' => esc_html__('Style 2', 'classified-listing-toolkits'),
					'style-3' => esc_html__('Style 3', 'classified-listing-toolkits'),
					'style-4' => esc_html__('Style 4', 'classified-listing-toolkits'),
				],
				'default'   => 'style-3',
				'condition' => [
					'slider_dots' => 'yes',
				],
			],
			[
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Set style controlls
	 *
	 * @return array
	 */
	public function slider_responsive() {
		$fields = [
			// Responsive Columns.
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_sec_responsive',
				'label' => __('Number of Responsive Columns', 'classified-listing-toolkits'),
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_col_xl',
				'label'   => __('Desktops: >1199px', 'classified-listing-toolkits'),
				'options' => $this->slider_column(),
				'default' => '4',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_col_lg',
				'label'   => __('Desktops: >991px', 'classified-listing-toolkits'),
				'options' => $this->slider_column(),
				'default' => '4',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_col_md',
				'label'   => __('Tablets: >767px', 'classified-listing-toolkits'),
				'options' => $this->slider_column(),
				'default' => '3',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_col_sm',
				'label'   => __('Phones: >575px', 'classified-listing-toolkits'),
				'options' => $this->slider_column(),
				'default' => '2',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_col_mobile',
				'label'   => __('Small Phones: <576px', 'classified-listing-toolkits'),
				'options' => $this->slider_column(),
				'default' => '1',
			],
			[
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}
