<?php
/**
 * @author  RadiusTheme
 *
 * @since   1.0
 *
 * @version 1.0
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Widgets;

use RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings\ListingStoreSettings;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

/**
 * ListingStore class
 */
class ListingStore extends ListingStoreSettings {
	/**
	 * ListingStore Init
	 *
	 * @param array $data others data
	 * @param [type] $args Others args
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtcl_name = __( 'Listing Store', 'classified-listing-toolkits' );
		$this->rtcl_base = 'rtcl-listing-store';
		parent::__construct( $data, $args );
	}

	/**
	 * Store Query
	 *
	 * @param [type] $settings Query
	 *
	 * @return array
	 */
	private function store_query( $data ) {
		$args = [
			'post_type'           => 'store',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => $data['posts_per_page'],
		];

		$args['paged'] = Pagination::get_page_number();

		// Taxonomy
		if ( ! empty( $data['store_cat'] ) ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'store_category',
					'field'    => 'term_id',
					'terms'    => $data['store_cat'],
				],
			];
		}

		$args['orderby'] = $data['store_orderby'];
		$args['order']   = $data['store_order'];

		$loop_obj = new \WP_Query( $args );

		return $loop_obj;
	}

	protected function render() {
		wp_enqueue_style( 'fontawesome' );
		wp_enqueue_style( 'rtcl-store-public' );
		wp_enqueue_script( 'rtcl-store-public' );

		$settings           = $this->get_settings();
		$settings['stores'] = $this->store_query( $settings );
		$template_style     = 'elementor/listing-store/' . $settings['rtcl_store_view'] . '-store';
		$data               = [
			'template'              => $template_style,
			'instance'              => $settings,
			'default_template_path' => Helper::get_plugin_template_path()
		];
		$data               = apply_filters( 'rtcl_el_store_data', $data );
		Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
	}
}
