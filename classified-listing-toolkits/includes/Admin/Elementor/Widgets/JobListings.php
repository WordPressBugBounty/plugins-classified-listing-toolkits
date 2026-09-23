<?php
/**
 * Job Listings Elementor widget — the Job Manager add-on's job card in list / grid view.
 *
 * @package  Classifid-listing
 * @since    1.3.1.1
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Widgets;

use WP_Query;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use RtclJobManager\Helpers\Functions as JobFns;
use RtclJobManager\Helpers\JobFields;
use RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings\JobListingsSettings;

/**
 * JobListings Class
 */
class JobListings extends JobListingsSettings {

	/**
	 * Constructor.
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtcl_name = __( 'Job Listings', 'classified-listing-toolkits' );
		$this->rtcl_base = 'rtcl-job-listings';
		parent::__construct( $data, $args );
	}

	/**
	 * The widget renders the Job Manager add-on's job card, so it is only available with a Job Manager version that ships it.
	 *
	 * @return bool
	 */
	public static function is_available() {
		return function_exists( 'rtcl_job_manager' )
			&& class_exists( JobFns::class )
			&& method_exists( rtcl_job_manager(), 'get_plugin_template_path' )
			&& method_exists( rtcl_job_manager(), 'job_type_id' )
			&& file_exists( rtcl_job_manager()->get_plugin_template_path() . 'job/listing/card.php' );
	}

	/**
	 * Stylesheet handler.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'rtcl-public' ];
	}

	/**
	 * Query args.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array
	 */
	public function widget_query_args( $settings ) {
		$orderby = ! empty( $settings['rtcl_job_orderby'] ) ? $settings['rtcl_job_orderby'] : 'date';
		$order   = ! empty( $settings['rtcl_job_order'] ) && 'asc' === $settings['rtcl_job_order'] ? 'ASC' : 'DESC';

		$args = [
			'post_type'      => rtcl()->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => ! empty( $settings['rtcl_job_per_page'] ) ? absint( $settings['rtcl_job_per_page'] ) : 6,
			'paged'          => Pagination::get_page_number(),
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'     => 'ad_type',
					'value'   => rtcl_job_manager()->job_type_id(),
					'compare' => '=',
				],
			],
			'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
			],
		];

		switch ( $orderby ) {
			case 'price':
				$args['meta_key'] = 'price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
				$args['order']    = $order;
				break;
			case 'views':
				$args['meta_key'] = '_views'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
				$args['order']    = $order;
				break;
			case 'rand':
				$args['orderby'] = 'rand';
				break;
			default:
				$args['orderby'] = in_array( $orderby, [ 'date', 'title', 'ID' ], true ) ? $orderby : 'date';
				$args['order']   = $order;
		}

		// Deadline rule lives in Job Manager, so both the widget and the add-on agree on "active".
		if ( ! empty( $settings['rtcl_job_active_only'] ) && method_exists( JobFns::class, 'active_jobs_meta_query' ) ) {
			$args['meta_query'][] = JobFns::active_jobs_meta_query();
			$args['meta_query']['relation'] = 'AND';
		}

		if ( ! empty( $settings['rtcl_job_categories'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => rtcl()->category,
				'terms'            => array_map( 'absint', (array) $settings['rtcl_job_categories'] ),
				'field'            => 'term_id',
				'operator'         => 'IN',
				'include_children' => ! empty( $settings['rtcl_job_categories_children'] ),
			];
		}

		if ( ! empty( $settings['rtcl_job_locations'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => rtcl()->location,
				'terms'            => array_map( 'absint', (array) $settings['rtcl_job_locations'] ),
				'field'            => 'term_id',
				'operator'         => 'IN',
				'include_children' => ! empty( $settings['rtcl_job_locations_children'] ),
			];
		}

		return apply_filters( 'rtcl_el_job_listings_widget_query_args', $args, $settings );
	}

	/**
	 * Which job fields the card may list, keyed by meta key. Fields without a switch are not limited.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array [ meta key => bool ]
	 */
	public function field_visibility( $settings ) {
		if ( ! class_exists( JobFields::class ) ) {
			return [];
		}

		$visibility = [];
		foreach ( JobFields::definitions() as $key => $definition ) {
			$control = JobListingsSettings::field_control_id( $key );
			if ( ! array_key_exists( $control, (array) $settings ) ) {
				continue;
			}
			$visibility[ $definition['name'] ] = ! empty( $settings[ $control ] );
		}

		return $visibility;
	}

	/**
	 * Display output.
	 *
	 * @return void
	 */
	protected function render() {
		if ( ! self::is_available() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$view     = ! empty( $settings['rtcl_job_view'] ) && 'grid' === $settings['rtcl_job_view'] ? 'grid' : 'list';
		$styles   = 'grid' === $view ? $this->job_grid_style() : $this->job_list_style();
		$style    = 'grid' === $view ? ( $settings['rtcl_job_grid_style'] ?? '' ) : ( $settings['rtcl_job_list_style'] ?? '' );
		$style    = isset( $styles[ $style ] ) ? $style : 'style-1';

		$the_loops = new WP_Query( $this->widget_query_args( $settings ) );

		$data = apply_filters(
			'rtcl_el_job_listings_filter_data',
			[
				'template'              => 'elementor/jobs/' . $view . '/' . $style,
				'view'                  => $view,
				'style'                 => $style,
				'instance'              => $settings,
				'the_loops'             => $the_loops,
				'card'                  => [
					'show_logo'     => ! empty( $settings['rtcl_job_show_logo'] ),
					'show_company'  => ! empty( $settings['rtcl_job_show_company'] ),
					'show_location' => ! empty( $settings['rtcl_job_show_location'] ),
					'show_date'     => ! empty( $settings['rtcl_job_show_date'] ),
					'show_fields'   => ! empty( $settings['rtcl_job_show_fields'] ),
					'fields'        => $this->field_visibility( $settings ),
					'show_salary'   => ! empty( $settings['rtcl_job_show_salary'] ),
					'show_deadline' => ! empty( $settings['rtcl_job_show_deadline'] ),
					'show_button'   => ! empty( $settings['rtcl_job_show_button'] ),
					'button_text'   => isset( $settings['rtcl_job_button_text'] ) ? (string) $settings['rtcl_job_button_text'] : '',
				],
				'default_template_path' => Helper::get_plugin_template_path(),
			],
			$settings
		);

		if ( $the_loops->have_posts() ) {
			Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
		} elseif ( ! empty( $settings['rtcl_job_no_result_text'] ) ) {
			echo '<h3 class="rtcl-job-listings-empty">' . esc_html( $settings['rtcl_job_no_result_text'] ) . '</h3>';
		}

		wp_reset_postdata();
	}
}
