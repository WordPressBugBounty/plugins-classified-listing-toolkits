<?php
/**
 * Trait for listing widget
 *
 * The Elementor builder.
 *
 * @package  Classifid-listing
 * @since    2.0.10
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\ELWidgetsTraits;

use Elementor\{Controls_Manager, Group_Control_Border, Group_Control_Typography};

trait ListingPaginationTrait {

	/**
	 * Set style controlls
	 *
	 * @return array
	 */
	public function widget_style_sec_pagination() {
		$fields = array(
			array(
				'mode'      => 'section_start',
				'id'        => 'rtcl_sec_pagination',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Pagination', 'classified-listing-toolkits' ),
				'condition' => array( 'rtcl_listing_pagination' => array( 'yes' ) ),
			),
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_pagination_typo',
				'label'    => __( 'Pagination Typography', 'classified-listing-toolkits' ),
				'selector' => '{{WRAPPER}} .rtcl-pagination a.page-numbers, {{WRAPPER}} .rtcl-pagination span.page-numbers',
			],
			array(
				'label'      => __( 'Pagination spacing', 'classified-listing-toolkits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_pagination_spacing',
				'mode'       => 'responsive',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rtcl-listings-sc-wrapper .rtcl-pagination ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_pagination_bg_color',
				'label'     => __( 'Background Color', 'classified-listing-toolkits' ),
				'selectors' => array(
					'{{WRAPPER}} .rtcl-pagination a.page-numbers' => 'background-color: {{VALUE}};',
				),
			),
			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_pagination_active_bg_color',
				'label'     => __( 'Active Background Color', 'classified-listing-toolkits' ),
				'selectors' => array(
					'{{WRAPPER}} .rtcl-pagination span.page-numbers.current, {{WRAPPER}} .rtcl-pagination a.page-numbers:hover' => 'background-color: {{VALUE}};',
				),
			),

			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_pagination_text_color',
				'label'     => __( 'Text Color', 'classified-listing-toolkits' ),
				'selectors' => array(
					'{{WRAPPER}} .rtcl-pagination a.page-numbers' => 'color: {{VALUE}};',
				),
			),
			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_pagination_active_text_color',
				'label'     => __( 'Active Text Color', 'classified-listing-toolkits' ),
				'selectors' => array(
					'{{WRAPPER}} .rtcl-pagination span.page-numbers.current, {{WRAPPER}} .rtcl-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				),
			),
			array(
				'type'     => Group_Control_Border::get_type(),
				'mode'     => 'group',
				'id'       => 'rtcl_pagination_border',
				'selector' => '{{WRAPPER}} .rtcl-pagination a.page-numbers, {{WRAPPER}} .rtcl-pagination span.page-numbers.current, {{WRAPPER}} .rtcl-pagination a.page-numbers:hover, {{WRAPPER}} {{WRAPPER}} .rtcl-pagination span.page-numbers.current:hover',
			),
			array(
				'mode' => 'section_end',
			),

		);
		return $fields;
	}
}
