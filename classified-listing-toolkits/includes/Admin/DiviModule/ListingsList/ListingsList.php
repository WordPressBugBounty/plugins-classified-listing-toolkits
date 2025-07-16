<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingsList;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

class ListingsList extends Settings {

	public function widget_results( $settings ) {
		$args = self::widget_query_args( $settings );

		add_filter( 'excerpt_more', '__return_empty_string' );
		// The Query.
		$loop_obj = new \WP_Query( $args );

		return $loop_obj;
	}

	public static function widget_query_args( $settings ) {

		$category_includes = ! empty( $settings['rtcl_listing_categories'] ) ? $settings['rtcl_listing_categories'] : '';
		$category_includes = explode( '|', $category_includes );

		$categories_list = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::divi_get_user_selected_terms( $category_includes );

		$location_includes = ! empty( $settings['rtcl_listing_location'] ) ? $settings['rtcl_listing_location'] : '';
		$location_includes = explode( '|', $location_includes );

		$location_list = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::divi_get_user_selected_terms( $location_includes, rtcl()->location );

		$orderby           = isset( $settings['rtcl_orderby'] ) && ! empty( $settings['rtcl_orderby'] ) ? $settings['rtcl_orderby'] : 'date';
		$order             = isset( $settings['rtcl_sortby'] ) && ! empty( $settings['rtcl_sortby'] ) ? $settings['rtcl_sortby'] : 'desc';
		$listings_per_page = isset( $settings['rtcl_listing_per_page'] ) && ! empty( $settings['rtcl_listing_per_page'] ) ? $settings['rtcl_listing_per_page']
			: '10';
		$listing_type      = isset( $settings['rtcl_listing_types'] ) && ! empty( $settings['rtcl_listing_types'] ) ? $settings['rtcl_listing_types'] : 'all';

		$meta_queries      = [];
		$the_args          = [
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
					$the_args['meta_key'] = $orderby; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					$the_args['orderby']  = 'meta_value_num';
					$the_args['order']    = $order;
					break;
				case 'views':
					$the_args['meta_key'] = '_views'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
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
				'taxonomy' => rtcl()->category,
				'terms'    => $categories_list,
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}

		if ( ! empty( $location_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy' => rtcl()->location,
				'terms'    => $location_list,
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}

		if ( $listing_type && in_array( $listing_type, array_keys( Functions::get_listing_types() ) ) && ! Functions::is_ad_type_disabled() ) {
			$meta_queries[] = [
				'key'     => 'ad_type',
				'value'   => $listing_type,
				'compare' => '=',
			];
		}

		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$the_args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( [ 'relation' => 'AND' ], $meta_queries ) : $meta_queries;
		}

		return $the_args;
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings  = $this->props;
		$the_loops = $this->widget_results( $settings );

		$this->render_css( $render_slug );

		$style = isset( $settings['rtcl_list_style'] ) ? sanitize_text_field( $settings['rtcl_list_style'] ) : 'style-1';

		$template_style = 'divi/listing-ads/list/' . $style;

		$data = [
			'template'      => $template_style,
			'instance'      => $settings,
			'the_loops'     => $the_loops,
			'view'          => 'list',
			'style'         => $style,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_listing_data', $data );

		if ( $the_loops->found_posts ) {
			return Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
		} else if ( ! empty( $settings['rtcl_no_listing_text'] ) ) {
			return '<h3>' . esc_html( $settings['rtcl_no_listing_text'] ) . '</h3>';
		}

		wp_reset_postdata();
	}

	protected function render_css( $render_slug ) {
		$wrapper              =  $this->bind_wrapper.'%%order_class%% .rtcl-listings-wrapper';
		$title_color          = $this->props['rtcl_title_color'];
		$title_hover_color    = $this->get_hover_value( 'rtcl_title_color' );
		$title_font_weight    = explode( '|', $this->props['title_font'] )[1];
		$meta_color           = $this->props['rtcl_meta_color'];
		$meta_icon_color      = $this->props['rtcl_meta_icon_color'];
		$category_color       = $this->props['rtcl_meta_category_color'];
		$category_hover_color = $this->get_hover_value( 'rtcl_meta_category_color' );
		$price_color          = $this->props['rtcl_price_color'];

		// Title
		if ( ! empty( $title_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.' .rtcl-listing-title a',
					'declaration' => sprintf( 'color: %1$s!important;', $title_color ),
				]
			);
		}
		if ( ! empty( $title_hover_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.' .rtcl-listing-title a:hover',
					'declaration' => sprintf( 'color: %1$s;', $title_hover_color ),
				]
			);
		}
		if ( ! empty( $title_font_weight ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => $wrapper.' .rtcl-listing-title',
					'declaration' => sprintf( 'font-weight: %1$s;', $title_font_weight ),
				)
			);
		}
		// Meta
		if ( ! empty( $meta_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.' .rtcl-listing-meta-data',
					'declaration' => sprintf( 'color: %1$s;', $meta_color ),
				]
			);
		}
		if ( ! empty( $meta_icon_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.' .rtcl-listing-meta-data i',
					'declaration' => sprintf( 'color: %1$s;', $meta_icon_color ),
				]
			);
		}
		if ( ! empty( $category_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.'  .listing-cat',
					'declaration' => sprintf( 'color: %1$s;', $category_color ),
				]
			);
		}
		if ( ! empty( $category_hover_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.'  .listing-cat a:hover',
					'declaration' => sprintf( 'color: %1$s;', $category_hover_color ),
				]
			);
		}
		// Price
		if ( ! empty( $price_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => $wrapper.'  .item-price .rtcl-price',
					'declaration' => sprintf( 'color: %1$s;', $price_color ),
				]
			);
		}
	}
}