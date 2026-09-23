<?php
/**
 * Job Listings widget settings.
 *
 * @package  Classifid-listing
 * @since    1.3.1.1
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use RadiusTheme\ClassifiedListingToolkits\Abstracts\ElementorWidgetBase;
use RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\ELWidgetsTraits\ListingStyleTrait;
use RtclJobManager\Helpers\Functions as JobFns;
use RtclJobManager\Helpers\JobFields;

/**
 * JobListingsSettings Class
 */
class JobListingsSettings extends ElementorWidgetBase {

	/**
	 * Listing view (list / grid) images.
	 */
	use ListingStyleTrait;

	/**
	 * List styles. More can be added through the filter.
	 *
	 * @return array
	 */
	public function job_list_style() {
		return apply_filters(
			'rtcl_el_job_listings_list_style',
			[
				'style-1' => esc_html__( 'Style 1', 'classified-listing-toolkits' ),
			]
		);
	}

	/**
	 * Grid styles. More can be added through the filter.
	 *
	 * @return array
	 */
	public function job_grid_style() {
		return apply_filters(
			'rtcl_el_job_listings_grid_style',
			[
				'style-1' => esc_html__( 'Style 1', 'classified-listing-toolkits' ),
			]
		);
	}

	/**
	 * A show / hide switch.
	 *
	 * @param string $id    Control id.
	 * @param string $label Control label.
	 *
	 * @return array
	 */
	private function visibility_switch( $id, $label ) {
		return [
			'type'      => Controls_Manager::SWITCHER,
			'id'        => $id,
			'label'     => $label,
			'label_on'  => __( 'Show', 'classified-listing-toolkits' ),
			'label_off' => __( 'Hide', 'classified-listing-toolkits' ),
			'default'   => 'yes',
		];
	}

	/**
	 * A switch per job field, straight from Job Manager's registry, so the widget stays in step with
	 * the fields the add-on ships. Fields with their own control (company logo / name, deadline) and
	 * ones the card never lists (tagline) are left out.
	 *
	 * @return array
	 */
	public function job_field_switches(): array {
		if ( ! class_exists( JobFields::class ) ) {
			return [];
		}

		$switches = [];
		foreach ( JobFields::definitions() as $key => $definition ) {
			if ( ! empty( $definition['own_display'] ) || 'job_deadline' === $key ) {
				continue;
			}

			$switches[] = array_merge(
				$this->visibility_switch( self::field_control_id( $key ), $definition['label'] ),
				[ 'condition' => [ 'rtcl_job_show_fields' => 'yes' ] ]
			);
		}

		return $switches;
	}

	/**
	 * @param string $key Job field element id.
	 *
	 * @return string
	 */
	public static function field_control_id( $key ) {
		return 'rtcl_job_show_field_' . $key;
	}

	/**
	 * Content tab controls.
	 *
	 * @return array
	 */
	public function widget_general_fields(): array {
		$order_by = apply_filters(
			'rtcl_el_job_listings_order_by',
			[
				'date'  => __( 'Date', 'classified-listing-toolkits' ),
				'title' => __( 'Title', 'classified-listing-toolkits' ),
				'ID'    => __( 'ID', 'classified-listing-toolkits' ),
				'price' => __( 'Salary', 'classified-listing-toolkits' ),
				'views' => __( 'Views', 'classified-listing-toolkits' ),
				'rand'  => __( 'Random', 'classified-listing-toolkits' ),
			]
		);

		$fields = [
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_sec_layout',
				'label' => __( 'Layout', 'classified-listing-toolkits' ),
			],
			[
				'type'    => 'rtcl-image-selector',
				'id'      => 'rtcl_job_view',
				'options' => $this->listings_view(),
				'default' => 'list',
			],
			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_job_list_style',
				'label'     => __( 'Style', 'classified-listing-toolkits' ),
				'options'   => $this->job_list_style(),
				'default'   => 'style-1',
				'condition' => [ 'rtcl_job_view' => 'list' ],
			],
			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_job_grid_style',
				'label'     => __( 'Style', 'classified-listing-toolkits' ),
				'options'   => $this->job_grid_style(),
				'default'   => 'style-1',
				'condition' => [ 'rtcl_job_view' => 'grid' ],
			],
			[
				'type'           => Controls_Manager::SELECT,
				'mode'           => 'responsive',
				'id'             => 'rtcl_job_columns',
				'label'          => __( 'Column', 'classified-listing-toolkits' ),
				'options'        => $this->column_number(),
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'condition'      => [ 'rtcl_job_view' => 'grid' ],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_sec_query',
				'label' => __( 'Query', 'classified-listing-toolkits' ),
			],
			[
				'type'        => Controls_Manager::SELECT2,
				'id'          => 'rtcl_job_categories',
				'label'       => __( 'Categories', 'classified-listing-toolkits' ),
				'options'     => $this->taxonomy_list(),
				'multiple'    => true,
				'label_block' => true,
				'description' => __( 'Leave empty to show jobs from all categories.', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_job_categories_children',
				'label'     => __( 'Include Children Categories', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
				'condition' => [ 'rtcl_job_categories!' => '' ],
			],
			[
				'type'        => Controls_Manager::SELECT2,
				'id'          => 'rtcl_job_locations',
				'label'       => __( 'Locations', 'classified-listing-toolkits' ),
				'options'     => $this->taxonomy_list( 'all', 'rtcl_location' ),
				'multiple'    => true,
				'label_block' => true,
				'description' => __( 'Leave empty to show jobs from all locations.', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_job_locations_children',
				'label'     => __( 'Include Inner Locations', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
				'condition' => [ 'rtcl_job_locations!' => '' ],
			],
			[
				'type'    => Controls_Manager::NUMBER,
				'id'      => 'rtcl_job_per_page',
				'label'   => __( 'Jobs Per Page', 'classified-listing-toolkits' ),
				'default' => 6,
				'min'     => 1,
			],
			method_exists( JobFns::class, 'active_jobs_meta_query' ) ? [
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'rtcl_job_active_only',
				'label'       => __( 'Show only active jobs', 'classified-listing-toolkits' ),
				'label_on'    => __( 'On', 'classified-listing-toolkits' ),
				'label_off'   => __( 'Off', 'classified-listing-toolkits' ),
				'default'     => '',
				'description' => __( 'Hide jobs whose deadline has passed. Jobs without a deadline always show.', 'classified-listing-toolkits' ),
			] : [],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_job_pagination',
				'label'     => __( 'Pagination', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => '',
			],
			[
				'type'    => Controls_Manager::SELECT,
				'id'      => 'rtcl_job_orderby',
				'label'   => __( 'Order By', 'classified-listing-toolkits' ),
				'options' => $order_by,
				'default' => 'date',
			],
			[
				'type'      => Controls_Manager::SELECT,
				'id'        => 'rtcl_job_order',
				'label'     => __( 'Sort By', 'classified-listing-toolkits' ),
				'options'   => [
					'desc' => __( 'Descending', 'classified-listing-toolkits' ),
					'asc'  => __( 'Ascending', 'classified-listing-toolkits' ),
				],
				'default'   => 'desc',
				'condition' => [ 'rtcl_job_orderby!' => 'rand' ],
			],
			[
				'type'        => Controls_Manager::TEXT,
				'id'          => 'rtcl_job_no_result_text',
				'label'       => __( 'No Job Text', 'classified-listing-toolkits' ),
				'default'     => __( 'No Job Found', 'classified-listing-toolkits' ),
				'label_block' => true,
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_sec_content',
				'label' => __( 'Content Visibility', 'classified-listing-toolkits' ),
			],
			$this->visibility_switch( 'rtcl_job_show_logo', __( 'Company Logo', 'classified-listing-toolkits' ) ),
			$this->visibility_switch( 'rtcl_job_show_company', __( 'Company Name', 'classified-listing-toolkits' ) ),
			$this->visibility_switch( 'rtcl_job_show_location', __( 'Location', 'classified-listing-toolkits' ) ),
			$this->visibility_switch( 'rtcl_job_show_date', __( 'Posted Date', 'classified-listing-toolkits' ) ),
			array_merge(
				$this->visibility_switch( 'rtcl_job_show_fields', __( 'Archive Fields', 'classified-listing-toolkits' ) ),
				[ 'description' => __( 'Fields marked "Display at archive page" in Form Builder. The switches below can hide job fields as well.', 'classified-listing-toolkits' ) ]
			),
			...$this->job_field_switches(),
			$this->visibility_switch( 'rtcl_job_show_salary', __( 'Salary', 'classified-listing-toolkits' ) ),
			$this->visibility_switch( 'rtcl_job_show_deadline', __( 'Deadline', 'classified-listing-toolkits' ) ),
			$this->visibility_switch( 'rtcl_job_show_button', __( 'Details Button', 'classified-listing-toolkits' ) ),
			[
				'type'        => Controls_Manager::TEXT,
				'id'          => 'rtcl_job_button_text',
				'label'       => __( 'Button Text', 'classified-listing-toolkits' ),
				'placeholder' => __( 'Details', 'classified-listing-toolkits' ),
				'condition'   => [ 'rtcl_job_show_button' => 'yes' ],
			],
			[
				'mode' => 'section_end',
			],
		];

		return apply_filters( 'rtcl_el_job_listings_widget_general_field', $fields, $this );
	}

	/**
	 * Style tab controls.
	 *
	 * @return array
	 */
	public function widget_style_fields(): array {
		$card = '{{WRAPPER}} .rtcl-job-archive-card';

		$fields = [
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_style_card',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Card', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_card_bg',
				'label'     => __( 'Background', 'classified-listing-toolkits' ),
				'selectors' => [ $card => '--rtcl-job-card-bg: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_card_border',
				'label'     => __( 'Border Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card => '--rtcl-job-card-border: {{VALUE}};' ],
			],
			[
				'type'        => Controls_Manager::COLOR,
				'id'          => 'rtcl_job_card_primary',
				'label'       => __( 'Accent Color', 'classified-listing-toolkits' ),
				'description' => __( 'Logo letter, icons and hover colors.', 'classified-listing-toolkits' ),
				'selectors'   => [ $card => '--rtcl-job-card-primary: {{VALUE}};' ],
			],
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'mode'       => 'responsive',
				'id'         => 'rtcl_job_card_padding',
				'label'      => __( 'Padding', 'classified-listing-toolkits' ),
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ $card => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			],
			[
				'type'       => Controls_Manager::SLIDER,
				'id'         => 'rtcl_job_card_radius',
				'label'      => __( 'Border Radius', 'classified-listing-toolkits' ),
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
				'selectors'  => [ $card => 'border-radius: {{SIZE}}{{UNIT}};' ],
			],
			[
				'type'       => Controls_Manager::SLIDER,
				'mode'       => 'responsive',
				'id'         => 'rtcl_job_card_gap',
				'label'      => __( 'Space Between', 'classified-listing-toolkits' ),
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'selectors'  => [
					// Grid rows are spaced by the grid gap (Job Manager zeroes the card's
					// own margin there), the stacked list view by the card margin. Driving
					// both from one control keeps them from stacking up.
					'{{WRAPPER}} .rtcl-grid-view'                        => 'grid-row-gap: {{SIZE}}{{UNIT}}; row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rtcl-list-view .rtcl-job-archive-card' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_style_title',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Title', 'classified-listing-toolkits' ),
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_job_title_typo',
				'selector' => $card . ' .rtcl-job-archive-card__title',
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_title_color',
				'label'     => __( 'Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__title a' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_title_hover_color',
				'label'     => __( 'Hover Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__title a:hover' => 'color: {{VALUE}};' ],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_job_style_meta',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Meta', 'classified-listing-toolkits' ),
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_job_meta_typo',
				'selector' => $card . ' .rtcl-job-archive-card__meta',
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_meta_color',
				'label'     => __( 'Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__meta' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_company_color',
				'label'     => __( 'Company Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__company' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_meta_icon_color',
				'label'     => __( 'Icon Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__meta i' => 'color: {{VALUE}};' ],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'      => 'section_start',
				'id'        => 'rtcl_job_style_chips',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Archive Fields', 'classified-listing-toolkits' ),
				'condition' => [ 'rtcl_job_show_fields' => 'yes' ],
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_job_chip_typo',
				'selector' => $card . ' .rtcl-job-archive-card__chip',
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_chip_color',
				'label'     => __( 'Text Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__chip' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_chip_bg',
				'label'     => __( 'Background', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__chip' => 'background-color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_chip_border',
				'label'     => __( 'Border Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__chip' => 'border-color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_chip_icon_color',
				'label'     => __( 'Icon Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__chip > i' => 'color: {{VALUE}};' ],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'      => 'section_start',
				'id'        => 'rtcl_job_style_salary',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Salary', 'classified-listing-toolkits' ),
				'condition' => [ 'rtcl_job_show_salary' => 'yes' ],
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_job_salary_typo',
				'selector' => $card . ' .rtcl-job-archive-card__salary .rtcl-price-amount',
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_salary_color',
				'label'     => __( 'Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__salary .rtcl-price-amount' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_salary_unit_color',
				'label'     => __( 'Unit Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__salary .rtcl-price-meta, ' . $card . ' .rtcl-job-archive-card__salary .rtcl-price-unit-label' => 'color: {{VALUE}};' ],
			],
			[
				'mode' => 'section_end',
			],
			[
				'mode'      => 'section_start',
				'id'        => 'rtcl_job_style_button',
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => __( 'Button', 'classified-listing-toolkits' ),
				'condition' => [ 'rtcl_job_show_button' => 'yes' ],
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'id'       => 'rtcl_job_button_typo',
				'selector' => $card . ' .rtcl-job-archive-card__btn',
			],
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'id'         => 'rtcl_job_button_padding',
				'label'      => __( 'Padding', 'classified-listing-toolkits' ),
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [ $card . ' .rtcl-job-archive-card__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			],
			[
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'id'       => 'rtcl_job_button_border',
				'selector' => $card . ' .rtcl-job-archive-card__btn',
			],
			[
				'type'       => Controls_Manager::SLIDER,
				'id'         => 'rtcl_job_button_radius',
				'label'      => __( 'Border Radius', 'classified-listing-toolkits' ),
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
				'selectors'  => [ $card . ' .rtcl-job-archive-card__btn' => 'border-radius: {{SIZE}}{{UNIT}};' ],
			],
			[
				'mode' => 'tabs_start',
				'id'   => 'rtcl_job_button_tabs',
			],
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_job_button_normal',
				'label' => esc_html__( 'Normal', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_button_color',
				'label'     => __( 'Text Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__btn' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_button_bg',
				'label'     => __( 'Background', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__btn' => 'background-color: {{VALUE}};' ],
			],
			[
				'mode' => 'tab_end',
			],
			[
				'mode'  => 'tab_start',
				'id'    => 'rtcl_job_button_hover',
				'label' => esc_html__( 'Hover', 'classified-listing-toolkits' ),
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_button_hover_color',
				'label'     => __( 'Text Color', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__btn:hover' => 'color: {{VALUE}};' ],
			],
			[
				'type'      => Controls_Manager::COLOR,
				'id'        => 'rtcl_job_button_hover_bg',
				'label'     => __( 'Background', 'classified-listing-toolkits' ),
				'selectors' => [ $card . ' .rtcl-job-archive-card__btn:hover' => 'background-color: {{VALUE}};' ],
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

		return apply_filters( 'rtcl_el_job_listings_widget_style_field', $fields, $this );
	}
}
