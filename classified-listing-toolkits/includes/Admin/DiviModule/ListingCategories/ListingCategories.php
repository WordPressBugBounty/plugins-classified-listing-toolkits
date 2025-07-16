<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingCategories;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;

class ListingCategories extends Settings {

	/**
	 * Widget result.
	 *
	 * @param [array] $data array of query.
	 *
	 * @return array
	 */
	public function widget_results( $data ) {
		// user's selected category.
		$category_includes = ! empty( $data['rtcl_cats'] ) ? $data['rtcl_cats'] : '';
		$category_includes = explode( '|', $category_includes );

		$category_terms = Helper::divi_get_user_selected_terms( $category_includes );

		$args = array(
			'taxonomy'     => rtcl()->category,
			'parent'       => 0,
			'orderby'      => ! empty( $data['rtcl_orderby'] ) ? $data['rtcl_orderby'] : 'name',
			'order'        => ! empty( $data['rtcl_order'] ) ? $data['rtcl_order'] : 'asc',
			'hide_empty'   => ! empty( $data['rtcl_hide_empty'] ) && 'on' === $data['rtcl_hide_empty'],
			'include'      => ! empty( $category_terms ) ? $category_terms : [],
			'hierarchical' => false,
		);
		if ( 'custom' === $data['rtcl_orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_rtcl_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		}
		$terms = get_terms( $args );
		if ( ! empty( $data['rtcl_category_limit'] ) ) {
			$number = $data['rtcl_category_limit'];
			$terms  = array_slice( $terms, 0, $number );
		}

		return $terms;
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings = $this->props;
		$terms    = $this->widget_results( $settings );

		$this->render_css( $render_slug );

		$style = isset( $settings['rtcl_cats_style'] ) ? sanitize_text_field( $settings['rtcl_cats_style'] ) : 'style-1';

		$template_style = 'divi/listing-cats/' . $style;

		$data = [
			'template'      => $template_style,
			'style'         => $style,
			'settings'      => $settings,
			'terms'         => $terms,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_listing_categories_data', $data );

		return Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	}

	protected function render_css( $render_slug ) {
		$wrapper           = $this->bind_wrapper.'%%order_class%%  .rtcl-categories-wrapper';
		$title_color       = $this->props['rtcl_title_color'];
		$title_hover_color = $this->get_hover_value( 'rtcl_title_color' );
		$title_font_weight = explode( '|', $this->props['title_font'] )[1];
		$count_color       = $this->props['rtcl_count_color'];
		$count_text_size   = $this->props['rtcl_count_text_size'];
		$description_color = $this->props['rtcl_desc_color'];

		// Title
		if ( ! empty( $title_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-category-title a",
					'declaration' => sprintf( 'color: %1$s;', $title_color ),
                    'important'        => 'all',
				]
			);
		}
		if ( ! empty( $title_hover_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-category-title a:hover",
					'declaration' => sprintf( 'color: %1$s;', $title_hover_color ),
				]
			);
		}
		if ( ! empty( $title_font_weight ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '.et-db .et-l %%order_class%% .rtcl-categories-wrapper .rtcl-category-title',
					'declaration' => sprintf( 'font-weight: %1$s;', $title_font_weight ),
				)
			);
		}
		// count
		if ( ! empty( $count_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .cat-details-inner .count",
					'declaration' => sprintf( 'color: %1$s;', $count_color ),
				]
			);
		}
		if ( ! empty( $count_text_size ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => "$wrapper .cat-details-inner .count",
					'declaration' => sprintf( 'font-size: %1$s;', $count_text_size ),
				)
			);
		}
		// description
		if ( ! empty( $description_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .cat-details-inner p",
					'declaration' => sprintf( 'color: %1$s!important;', $description_color ),
				]
			);
		}
	}
}