<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class FormFieldSettings {

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function fields_settings(): array  {
		return [
			'fields_label_style_start'      => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Form Label', 'classified-listing-toolkits' ),
			],
			'fields_label_typo'       => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Label Typography', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-widget-search-sortable :is( label )',
			],
			'fields_label_color'      => [
				'label'     => esc_html__( 'Label Color', 'classified-listing-toolkits' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is( label )' => 'color: {{VALUE}} !important;',
				],
			],
			'fields_label_margin'     => [
				'label'      => esc_html__( 'Label Margin', 'classified-listing-toolkits' ),
				'type'       => 'dimensions',
				'mode'      => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is( label )' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_label_style_end'        => [
				'mode' => 'section_end',
			],
			'fields_style_start'      => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Form Field\'s', 'classified-listing-toolkits' ),
			],
			'fields_text_typo'       => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input, .rtcl-search-input-button )',
			],
			'fields_height'           => [
				'label'     => esc_html__( 'Field\'s Height', 'classified-listing-toolkits' ),
				'type'      => 'slider',
				'mode'      => 'responsive',
				'separator' => 'default',
				'range'     => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable .select2-container .select2-selection--single' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input, .rtcl-search-input-button )' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .rtcl-widget-search-sortable .select2-container--classic .select2-selection--single' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'fields_width'           => [
				'label'     => esc_html__( 'Field\'s Width', 'classified-listing-toolkits' ),
				'type'      => 'slider',
				'mode'      => 'responsive',
				'separator' => 'default',
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable-inline .rtcl-form-group' => 'width: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important; flex: 0 0 {{SIZE}}{{UNIT}} !important;',
				],
			],

			'fields_tabs_start'       => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'fields_normal'           => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'classified-listing-toolkits' ),
			],
			'fields_border'           => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => '
					{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group-field:not(.ws-button-inner)
				',
				'size_units' => [ 'px' ],
			],
			'fields_text_color'       => [
				'label'     => esc_html__( 'Text Color', 'classified-listing-toolkits' ),
				'type'      => 'color',

				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input, .rtcl-search-input-button )' => 'color: {{VALUE}};',
				],
			],
			'fields_bg_color'         => [
				'label'     => esc_html__( 'Background Color', 'classified-listing-toolkits' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input, .rtcl-search-input-button )' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group-field:not(.ws-button-inner)' => 'background-color: {{VALUE}};',
				],
			],
			'fields_normal_end'       => [
				'mode' => 'tab_end',
			],
			'fields_hover'            => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover & Focus', 'classified-listing-toolkits' ),
			],

			'fields_hover_border'     => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => '
					{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group-field:hover
				',
				'size_units' => [ 'px' ],
			],
			'fields_hover_text_color' => [
				'label'     => esc_html__( 'Text Color', 'classified-listing-toolkits' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input):hover' => 'color: {{VALUE}};',
				],
			],
			'fields_hover_bg_color'   => [
				'label'     => esc_html__( 'Background Color', 'classified-listing-toolkits' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input):hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group-field:hover' => 'background-color: {{VALUE}};',
				],
			],

			'fields_hover_end'        => [
				'mode' => 'tab_end',
			],
			'fields_tabs_end'         => [
				'mode' => 'tabs_end',
			],
			'fields_border_radius'    => [
				'label'      => esc_html__( 'Border Radius', 'classified-listing-toolkits' ),
				'size_units' => [ 'px' ],
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'selectors'  => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input)' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group-field:not(.ws-button-inner)' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			],
			'fields_padding'          => [
				'label'      => esc_html__( 'Fields Padding (px)', 'classified-listing-toolkits' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rtcl-widget-search-sortable :is(select, input, .rtcl-search-input-button )' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rtcl-widget-search-sortable .select2-container .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_style_end'        => [
				'mode' => 'section_end',
			],
		];
	}
}