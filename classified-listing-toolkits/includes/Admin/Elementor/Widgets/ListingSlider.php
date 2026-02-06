<?php
/**
 * Main Elementor ListingSlider Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  Classifid-listing
 * @since    2.0.10
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Widgets;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Controllers\Hooks\AppliedBothEndHooks;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use Rtcl\Resources\Options;
use RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings\ListingSliderSettings;
use RtclPro\Controllers\Hooks\TemplateHooks as TemplateHooksPro;
use WP_Query;


/**
 * ListingSlider Class
 */
class ListingSlider extends ListingSliderSettings {

	/**
	 * Undocumented function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtcl_name = __( 'Listing Slider', 'classified-listing-toolkits' );
		$this->rtcl_base = 'rtcl-listing-slider';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget excerpt_limit.
	 *
	 * @param array $length default limit.
	 *
	 * @return init
	 */
	public function excerpt_limit( $length ) {
		$settings = $this->get_settings();
		$length   = ! empty( $settings['rtcl_content_limit'] ) ? $settings['rtcl_content_limit'] : $length;

		return $length;
	}

	/**
	 * Argument Settings.
	 *
	 * @return array
	 */
	public function widget_query_args() {
		$settings = $this->get_settings();

		$categories_list   = isset( $settings['rtcl_listings_by_categories'] ) && ! empty( $settings['rtcl_listings_by_categories'] )
			? $settings['rtcl_listings_by_categories'] : [];
		$location_list     = isset( $settings['rtcl_locations'] ) && ! empty( $settings['rtcl_locations'] ) ? $settings['rtcl_locations'] : [];
		$tag_list          = isset( $settings['rtcl_tags'] ) && ! empty( $settings['rtcl_tags'] ) ? $settings['rtcl_tags'] : [];
		$orderby           = isset( $settings['rtcl_orderby'] ) && ! empty( $settings['rtcl_orderby'] ) ? $settings['rtcl_orderby'] : 'date';
		$order             = isset( $settings['rtcl_order'] ) && ! empty( $settings['rtcl_order'] ) ? $settings['rtcl_order'] : 'desc';
		$listings_per_page = isset( $settings['rtcl_listing_per_page'] ) && ! empty( $settings['rtcl_listing_per_page'] ) ? $settings['rtcl_listing_per_page']
			: '5';
		$promotion_in      = isset( $settings['rtcl_listings_promotions'] ) && ! empty( $settings['rtcl_listings_promotions'] )
			? $settings['rtcl_listings_promotions'] : [];
		$promotion_not_in  = isset( $settings['rtcl_listings_promotions_not_in'] ) && ! empty( $settings['rtcl_listings_promotions_not_in'] )
			? $settings['rtcl_listings_promotions_not_in'] : [];

		$categories_children = isset( $settings['rtcl_listings_categories_include_children'] )
		                       && ! empty( $settings['rtcl_listings_categories_include_children'] ) ? true : false;
		$location_children   = isset( $settings['rtcl_listings_location_include_children'] ) && ! empty( $settings['rtcl_listings_location_include_children'] )
			? true : false;
		$listing_type        = isset( $settings['rtcl_listing_types'] ) && ! empty( $settings['rtcl_listing_types'] ) ? $settings['rtcl_listing_types'] : 'all';

		$meta_queries = [];
		$the_args     = [
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => $listings_per_page,
			'post_status'    => 'publish',
			'tax_query'      => [
				'relation' => 'AND',
			],
		];

		$the_args['paged'] = Pagination::get_page_number();

		if ( ! empty( $order ) && ! empty( $orderby ) ) {

			switch ( $orderby ) {
				case 'price':
					$the_args['meta_key'] = $orderby;
					$the_args['orderby']  = 'meta_value_num';
					$the_args['order']    = $order;
					break;
				case 'views':
					$the_args['meta_key'] = '_views';
					$the_args['orderby']  = 'meta_value_num';
					$the_args['order']    = $order;
					break;
				case 'rand':
					$the_args['orderby'] = $orderby;
					break;
				default:
					$the_args['orderby'] = $orderby;
					$the_args['order']   = $order;
			}
		}

		if ( ! empty( $categories_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy'         => rtcl()->category,
				'terms'            => $categories_list,
				'field'            => 'term_id',
				'operator'         => 'IN',
				'include_children' => $categories_children,
			];
		}
		if ( ! empty( $location_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy'         => rtcl()->location,
				'terms'            => $location_list,
				'field'            => 'term_id',
				'operator'         => 'IN',
				'include_children' => $location_children,
			];
		}
		if ( ! empty( $tag_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy' => rtcl()->tag,
				'terms'    => $tag_list,
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}

		// Promotions filter.

		$promotion_common = array_intersect( $promotion_in, $promotion_not_in );
		$promotion_in     = array_diff( $promotion_in, $promotion_common ); // Unic array

		if ( ! empty( $promotion_in ) && is_array( $promotion_in ) ) {
			$promotions = array_keys( Options::get_listing_promotions() );
			foreach ( $promotion_in as $promotion ) {
				if ( is_string( $promotion ) && in_array( $promotion, $promotions ) ) {
					$meta_queries[] = [
						'key'     => $promotion,
						'compare' => '=',
						'value'   => 1,
					];
				}
			}
		}

		if ( ! empty( $promotion_not_in ) && is_array( $promotion_not_in ) ) {
			$promotions = array_keys( Options::get_listing_promotions() );
			foreach ( $promotion_not_in as $promotion ) {
				if ( is_string( $promotion ) && in_array( $promotion, $promotions ) ) {
					$meta_queries[] = [
						'relation' => 'OR',
						[
							'key'     => $promotion,
							'compare' => '!=',
							'value'   => 1,
						],
						[
							'key'     => $promotion,
							'compare' => 'NOT EXISTS',
						],
					];
				}
			}
		}

		// Listing type filter.
		// TODO: Multiple select option needed.
		if ( $listing_type && in_array( $listing_type, array_keys( Functions::get_listing_types() ) ) && ! Functions::is_ad_type_disabled() ) {
			$meta_queries[] = [
				'key'     => 'ad_type',
				'value'   => $listing_type,
				'compare' => '=',
			];
		}

		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			$the_args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( [ 'relation' => 'AND' ], $meta_queries ) : $meta_queries;
		}

		return $the_args;
	}

	/**
	 * Widget result.
	 *
	 * @return array
	 */
	public function widget_results() {
		$args = $this->widget_query_args();

		add_filter( 'excerpt_length', [ $this, 'excerpt_limit' ] );
		add_filter( 'excerpt_more', '__return_empty_string' );
		// The Query.
		$loop_obj = new WP_Query( $args );

		return $loop_obj;
	}

	/**
	 * Display Output.
	 *
	 * @return mixed
	 */
	protected function render() {
		wp_enqueue_style( 'fontawesome' );
		$settings = $this->get_settings();
		if ( ! $settings['rtcl_show_price_unit'] ) {
			remove_filter( 'rtcl_price_meta_html', [ AppliedBothEndHooks::class, 'add_price_unit_to_price' ], 10, 3 );
		}
		if ( ! $settings['rtcl_show_price_type'] ) {
			remove_filter( 'rtcl_price_meta_html', [ AppliedBothEndHooks::class, 'add_price_type_to_price' ], 20, 3 );
		}

		add_action( 'rtcl_listing_badges', [ TemplateHooks::class, 'listing_featured_badge' ], 20 );
		if ( rtcl()->has_pro() ) {
			add_action( 'rtcl_listing_badges', [ TemplateHooksPro::class, 'listing_popular_badge' ], 30 );
		}
		$the_loops = $this->widget_results();
		$view      = 'grid';
		// rtcl_el_listings_list_style.

		$style = $settings['rtcl_listings_grid_style'] ? $settings['rtcl_listings_grid_style'] : 'style-1';
		if ( ! in_array( $style, array_keys( $this->grid_style() ) ) ) {
			$style = 'style-1';
		}

		$settings['rtcl_thumb_image_size'] = $this->image_size();
		$template_style                    = 'elementor/listing-ads-slider/' . $view . '/' . $style;
		$data                              = [
			'template'              => $template_style,
			'view'                  => $view,
			'style'                 => $style,
			'instance'              => $settings,
			'the_loops'             => $the_loops,
			'default_template_path' => Helper::get_plugin_template_path(),
		];
		$data                              = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
		Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
		wp_reset_postdata();
		if ( ! $settings['rtcl_show_price_unit'] ) {
			add_filter( 'rtcl_price_meta_html', [ AppliedBothEndHooks::class, 'add_price_unit_to_price' ], 10, 3 );
		}
		if ( ! $settings['rtcl_show_price_type'] ) {
			add_filter( 'rtcl_price_meta_html', [ AppliedBothEndHooks::class, 'add_price_type_to_price' ], 20, 3 );
		}
		$this->edit_mode_script();
	}

}
