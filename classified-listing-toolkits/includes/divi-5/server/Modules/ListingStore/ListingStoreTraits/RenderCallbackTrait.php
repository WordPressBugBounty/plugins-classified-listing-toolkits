<?php
/**
 * Render Callback Trait for Listing Store.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\Packages\Module\Module;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;

trait RTCL_Divi5_ListingStore_RenderCallbackTrait {

	/**
	 * Render callback for the module.
	 *
	 * @param array  $attrs    Module attributes.
	 * @param string $content  Module content.
	 * @param object $block    Block object.
	 * @param object $elements Elements object.
	 *
	 * @return string Rendered HTML.
	 */
	public static function render_callback( $attrs, $content, $block, $elements ) {
		// Check if Store plugin is active.
		if ( ! defined( 'RTCL_PRO_VERSION' ) || ! defined( 'RTCL_STORE_VERSION' ) ) {
			return '<div class="rtcl-store-notice">' . esc_html__( 'RTCL Store plugin is required for this module.', 'classified-listing-toolkits' ) . '</div>';
		}

		// Extract settings from attributes.
		$settings = self::get_settings_from_attrs( $attrs );

		// Get stores.
		$stores = self::get_stores_query( $settings );

		// Get style (validated against known styles).
		$style = in_array( $settings['storeStyle'] ?? '', [ 'style-1' ], true ) ? $settings['storeStyle'] : 'style-1';

		// Build the inner content.
		$inner_content = self::render_stores( $stores, $settings, $style );

		$parent = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );

		return Module::render(
			[
				// FE only.
				'orderIndex'          => $block->parsed_block['orderIndex'],
				'storeInstance'       => $block->parsed_block['storeInstance'],

				// VB equivalent.
				'attrs'               => $attrs,
				'elements'            => $elements,
				'id'                  => $block->parsed_block['id'],
				'moduleClassName'     => 'rtcl-listing-store-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_ListingStore::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_ListingStore::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_ListingStore::class, 'module_script_data' ],
				'parentAttrs'         => $parent->attrs ?? [],
				'parentId'            => $parent->id ?? '',
				'parentName'          => $parent->blockName ?? '',
				'children'            => $elements->style_components(
					[
						'attrName' => 'module',
					]
				) . $inner_content,
			]
		);
	}

	/**
	 * Extract settings from Divi 5 attributes structure.
	 *
	 * @param array $attrs Module attributes.
	 * @return array Settings array.
	 */
	private static function get_settings_from_attrs( $attrs ) {
		return [
			'layoutType'         => $attrs['layoutType']['innerContent']['desktop']['value'] ?? 'grid',
			'storeStyle'         => $attrs['storeStyle']['innerContent']['desktop']['value'] ?? 'style-1',
			'gridColumn'         => $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '4',
			'gridColumnTablet'   => $attrs['gridColumn']['innerContent']['tablet']['value'] ?? '2',
			'gridColumnPhone'    => $attrs['gridColumn']['innerContent']['phone']['value'] ?? '1',
			'storeCategories'    => $attrs['storeCategories']['innerContent']['desktop']['value'] ?? '',
			'paginationEnabled'  => $attrs['paginationEnabled']['innerContent']['desktop']['value'] ?? 'off',
			'perPage'            => $attrs['perPage']['innerContent']['desktop']['value'] ?? '6',
			'orderby'            => $attrs['orderby']['innerContent']['desktop']['value'] ?? 'name',
			'order'              => $attrs['order']['innerContent']['desktop']['value'] ?? 'asc',
			'showImage'          => $attrs['showImage']['innerContent']['desktop']['value'] ?? 'on',
			'showName'           => $attrs['showName']['innerContent']['desktop']['value'] ?? 'on',
			'showDescription'    => $attrs['showDescription']['innerContent']['desktop']['value'] ?? 'on',
			'showListingsCount'  => $attrs['showListingsCount']['innerContent']['desktop']['value'] ?? 'on',
			'showContact'        => $attrs['showContact']['innerContent']['desktop']['value'] ?? 'off',
			'showSocialLinks'    => $attrs['showSocialLinks']['innerContent']['desktop']['value'] ?? 'off',
			'noStoreText'        => $attrs['noStoreText']['innerContent']['desktop']['value'] ?? 'No Store Found',
		];
	}

	/**
	 * Get stores query.
	 *
	 * @param array $settings Module settings.
	 * @return array Array of store objects.
	 */
	private static function get_stores_query( $settings ) {
		$args = self::build_query_args( $settings );

		// Use RTCL Store functions to get stores.
		if ( function_exists( 'rtclStore' ) && method_exists( rtclStore(), 'post_type' ) ) {
			$args['post_type'] = rtclStore()->post_type;
		} else {
			$args['post_type'] = 'store';
		}

		return new \WP_Query( $args );
	}

	/**
	 * Build query arguments.
	 *
	 * @param array $settings Module settings.
	 * @return array
	 */
	private static function build_query_args( $settings ) {
		$allowed_orderby = [ 'name', 'ID', 'date', 'rand' ];
		$orderby         = in_array( $settings['orderby'] ?? '', $allowed_orderby, true ) ? $settings['orderby'] : 'name';
		$order           = 'desc' === strtolower( $settings['order'] ?? '' ) ? 'desc' : 'asc';
		$per_page        = $settings['perPage'] ?? '6';

		$the_args = [
			'posts_per_page' => intval( $per_page ),
			'post_status'    => 'publish',
			'paged'          => \Rtcl\Helpers\Pagination::get_page_number(),
		];

		// Handle orderby.
		switch ( $orderby ) {
			case 'name':
				$the_args['orderby'] = 'title';
				$the_args['order']   = $order;
				break;
			case 'ID':
				$the_args['orderby'] = 'ID';
				$the_args['order']   = $order;
				break;
			case 'date':
				$the_args['orderby'] = 'date';
				$the_args['order']   = $order;
				break;
			case 'rand':
				$the_args['orderby'] = 'rand';
				break;
			default:
				$the_args['orderby'] = 'title';
				$the_args['order']   = $order;
		}

		// Add store category filter.
		$category_ids = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['storeCategories'] ?? '' );
		if ( ! empty( $category_ids ) && function_exists( 'rtclStore' ) ) {
			$store_category = is_callable( [ rtclStore(), 'category' ] ) ? rtclStore()->category : 'store_category';
			$the_args['tax_query'] = [
				[
					'taxonomy' => $store_category,
					'terms'    => $category_ids,
					'field'    => 'term_id',
					'operator' => 'IN',
				],
			];
		}

		return $the_args;
	}

	/**
	 * Render stores HTML.
	 *
	 * @param \WP_Query $stores   Query object.
	 * @param array     $settings Module settings.
	 * @param string    $style    Style name.
	 * @return string
	 */
	private static function render_stores( $stores, $settings, $style ) {
		// Map settings to template format.
		$instance = self::map_settings_to_instance( $settings );

		// Add stores query to instance as the template expects it there.
		$instance['stores'] = $stores;

		$view           = in_array( $settings['layoutType'] ?? '', [ 'grid', 'list' ], true ) ? $settings['layoutType'] : 'grid';
		$template_style = 'divi/listing-store/grid-store';

		$data = [
			'template'      => $template_style,
			'instance'      => $instance,
			'stores'        => $stores,
			'style'         => $style,
			'view'          => $view,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_store_data', $data );

		if ( $stores->found_posts ) {
			$output = Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
		} elseif ( ! empty( $settings['noStoreText'] ) ) {
			$output = '<h3>' . esc_html( $settings['noStoreText'] ) . '</h3>';
		} else {
			$output = '';
		}

		wp_reset_postdata();

		return $output;
	}

	/**
	 * Map Divi 5 settings to template instance format.
	 *
	 * @param array $settings Divi 5 settings.
	 * @return array Instance array for templates.
	 */
	private static function map_settings_to_instance( $settings ) {
		return [
			'rtcl_layout_type'          => $settings['layoutType'],
			'rtcl_store_style'          => $settings['storeStyle'],
			'rtcl_store_column'         => $settings['gridColumn'],
			'rtcl_store_column_tablet'  => $settings['gridColumnTablet'],
			'rtcl_store_column_phone'   => $settings['gridColumnPhone'],
			'rtcl_store_per_page'       => $settings['perPage'],
			'rtcl_store_orderby'        => $settings['orderby'],
			'rtcl_store_order'          => $settings['order'],
			'rtcl_show_image'           => $settings['showImage'],
			'rtcl_show_name'            => $settings['showName'],
			'rtcl_show_description'     => $settings['showDescription'],
			'rtcl_show_listings_count'  => $settings['showListingsCount'],
			'rtcl_show_contact'         => $settings['showContact'],
			'rtcl_show_social_links'    => $settings['showSocialLinks'],
			'rtcl_store_pagination'     => $settings['paginationEnabled'],
			'rtcl_no_store_text'        => $settings['noStoreText'],
		];
	}
}
