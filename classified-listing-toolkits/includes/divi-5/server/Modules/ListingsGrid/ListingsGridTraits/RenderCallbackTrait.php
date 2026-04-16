<?php
/**
 * Render Callback Trait for Listings Grid.
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
use Rtcl\Helpers\Pagination;

trait RTCL_Divi5_ListingsGrid_RenderCallbackTrait {

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
		// Extract settings from attributes.
		$settings = self::get_settings_from_attrs( $attrs );

		// Get listings.
		$the_loops = self::get_listings_query( $settings );

		// Get style.
		$style = $settings['gridStyle'] ?? 'style-1';

		// Build the inner content.
		$inner_content = self::render_listings( $the_loops, $settings, $style );

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
				'moduleClassName'     => 'rtcl-listings-grid-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_ListingsGrid::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_ListingsGrid::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_ListingsGrid::class, 'module_script_data' ],
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
			'gridStyle'              => $attrs['gridStyle']['innerContent']['desktop']['value'] ?? 'style-1',
			'gridColumn'             => $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3',
			'gridColumnTablet'       => $attrs['gridColumn']['innerContent']['tablet']['value'] ?? '2',
			'gridColumnPhone'        => $attrs['gridColumn']['innerContent']['phone']['value'] ?? '1',
			'listingTypes'      => $attrs['listingTypes']['innerContent']['desktop']['value'] ?? 'all',
			'categories'        => $attrs['categories']['innerContent']['desktop']['value'] ?? '',
			'locations'         => $attrs['locations']['innerContent']['desktop']['value'] ?? '',
			'perPage'           => $attrs['perPage']['innerContent']['desktop']['value'] ?? '10',
			'pagination'        => $attrs['pagination']['innerContent']['desktop']['value'] ?? 'off',
			'orderby'           => $attrs['orderby']['innerContent']['desktop']['value'] ?? 'date',
			'order'             => $attrs['order']['innerContent']['desktop']['value'] ?? 'desc',
			'imageSize'         => $attrs['imageSize']['innerContent']['desktop']['value'] ?? 'rtcl-thumbnail',
			'noListingText'     => $attrs['noListingText']['innerContent']['desktop']['value'] ?? 'No Listing Found',
			'showImage'         => $attrs['showImage']['innerContent']['desktop']['value'] ?? 'on',
			'showDescription'   => $attrs['showDescription']['innerContent']['desktop']['value'] ?? 'off',
			'contentLimit'      => $attrs['contentLimit']['innerContent']['desktop']['value'] ?? '20',
			'showBadge'         => $attrs['showBadge']['innerContent']['desktop']['value'] ?? 'on',
			'showDate'          => $attrs['showDate']['innerContent']['desktop']['value'] ?? 'on',
			'showViews'         => $attrs['showViews']['innerContent']['desktop']['value'] ?? 'on',
			'showAdType'        => $attrs['showAdType']['innerContent']['desktop']['value'] ?? 'on',
			'showLocation'      => $attrs['showLocation']['innerContent']['desktop']['value'] ?? 'on',
			'showCategory'      => $attrs['showCategory']['innerContent']['desktop']['value'] ?? 'on',
			'showPrice'         => $attrs['showPrice']['innerContent']['desktop']['value'] ?? 'on',
			'showAuthor'        => $attrs['showAuthor']['innerContent']['desktop']['value'] ?? 'on',
			'showCustomFields'  => $attrs['showCustomFields']['innerContent']['desktop']['value'] ?? 'off',
			'showFavourites'    => $attrs['showFavourites']['innerContent']['desktop']['value'] ?? 'off',
			'showQuickView'     => $attrs['showQuickView']['innerContent']['desktop']['value'] ?? 'off',
			'showCompare'       => $attrs['showCompare']['innerContent']['desktop']['value'] ?? 'off',
		];
	}

	/**
	 * Get listings query.
	 *
	 * @param array $settings Module settings.
	 * @return \WP_Query
	 */
	private static function get_listings_query( $settings ) {
		$args = self::build_query_args( $settings );

		add_filter( 'excerpt_more', '__return_empty_string' );

		return new \WP_Query( $args );
	}

	/**
	 * Get preview HTML for Visual Builder REST endpoint.
	 *
	 * @param array $settings Module settings.
	 * @return string Rendered HTML.
	 */
	public static function get_preview_html( $settings ) {
		$the_loops = self::get_listings_query( $settings );
		$style     = $settings['gridStyle'] ?? 'style-1';

		return self::render_listings( $the_loops, $settings, $style );
	}

	/**
	 * Parse taxonomy IDs from various input formats.
	 *
	 * Handles:
	 * - Array: ['15', '23'] (CheckboxesContainer)
	 * - Pipe-separated: "15|23" (CheckboxesContainer stored value)
	 * - Comma-separated: "15,23" (legacy TextContainer)
	 *
	 * @param mixed $value Taxonomy IDs.
	 * @return array Integer IDs array.
	 */
	private static function parse_taxonomy_ids( $value ) {
		if ( empty( $value ) ) {
			return [];
		}

		if ( is_array( $value ) ) {
			$ids = array_values( $value );
		} elseif ( is_string( $value ) ) {
			$separator = strpos( $value, '|' ) !== false ? '|' : ',';
			$ids       = array_filter( explode( $separator, $value ) );
		} else {
			return [];
		}

		$valid_ids = array_filter(
			$ids,
			function ( $id ) {
				return is_numeric( $id ) && intval( $id ) > 0;
			}
		);

		return ! empty( $valid_ids ) ? array_map( 'intval', $valid_ids ) : [];
	}

	/**
	 * Build query arguments.
	 *
	 * @param array $settings Module settings.
	 * @return array
	 */
	private static function build_query_args( $settings ) {
		// Get selected categories.
		$categories_list = self::parse_taxonomy_ids( $settings['categories'] ?? '' );

		// Get selected locations.
		$location_list = self::parse_taxonomy_ids( $settings['locations'] ?? '' );

		$allowed_orderby   = [ 'date', 'title', 'ID', 'price', 'views', 'rand' ];
		$orderby           = in_array( $settings['orderby'] ?? '', $allowed_orderby, true ) ? $settings['orderby'] : 'date';
		$order             = 'asc' === strtolower( $settings['order'] ?? '' ) ? 'asc' : 'desc';
		$listings_per_page = $settings['perPage'] ?? '10';
		$listing_type      = $settings['listingTypes'] ?? 'all';

		$meta_queries = [];
		$the_args     = [
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => intval( $listings_per_page ),
			'post_status'    => 'publish',
			'tax_query'      => [
				'relation' => 'AND',
			],
		];

		$the_args['paged'] = Pagination::get_page_number();

		// Handle orderby.
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

		// Add category filter.
		if ( ! empty( $categories_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy' => rtcl()->category,
				'terms'    => $categories_list,
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}

		// Add location filter.
		if ( ! empty( $location_list ) ) {
			$the_args['tax_query'][] = [
				'taxonomy' => rtcl()->location,
				'terms'    => $location_list,
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}

		// Add listing type filter.
		if ( $listing_type && 'all' !== $listing_type && in_array( $listing_type, array_keys( Functions::get_listing_types() ), true ) && ! Functions::is_ad_type_disabled() ) {
			$meta_queries[] = [
				'key'     => 'ad_type',
				'value'   => $listing_type,
				'compare' => '=',
			];
		}

		// Add meta queries.
		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			$the_args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( [ 'relation' => 'AND' ], $meta_queries ) : $meta_queries;
		}

		return $the_args;
	}

	/**
	 * Render listings HTML.
	 *
	 * @param \WP_Query $the_loops Query object.
	 * @param array     $settings  Module settings.
	 * @param string    $style     Style name.
	 * @return string
	 */
	private static function render_listings( $the_loops, $settings, $style ) {
		// Map settings to template format.
		$instance = self::map_settings_to_instance( $settings );

		$style          = in_array( $style, [ 'style-1', 'style-2' ], true ) ? $style : 'style-1';
		$template_style = 'divi/listing-ads/grid/' . $style;

		$data = [
			'template'      => $template_style,
			'instance'      => $instance,
			'the_loops'     => $the_loops,
			'view'          => 'grid',
			'style'         => $style,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_listing_data', $data );

		if ( $the_loops->found_posts ) {
			$output = Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
		} elseif ( ! empty( $settings['noListingText'] ) ) {
			$output = '<h3>' . esc_html( $settings['noListingText'] ) . '</h3>';
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
			'rtcl_grid_style'         => $settings['gridStyle'],
			'rtcl_grid_column'         => $settings['gridColumn'],
			'rtcl_grid_column_tablet'  => $settings['gridColumnTablet'],
			'rtcl_grid_column_phone'   => $settings['gridColumnPhone'],
			'rtcl_listing_types'       => $settings['listingTypes'],
			'rtcl_listing_categories' => $settings['categories'],
			'rtcl_listing_location'   => $settings['locations'],
			'rtcl_listing_per_page'   => $settings['perPage'],
			'rtcl_listing_pagination' => $settings['pagination'],
			'rtcl_orderby'            => $settings['orderby'],
			'rtcl_sortby'             => $settings['order'],
			'rtcl_image_size'         => $settings['imageSize'],
			'rtcl_no_listing_text'    => $settings['noListingText'],
			'rtcl_show_image'         => $settings['showImage'],
			'rtcl_show_description'   => $settings['showDescription'],
			'rtcl_content_limit'      => $settings['contentLimit'],
			'rtcl_show_labels'        => $settings['showBadge'],
			'rtcl_show_date'          => $settings['showDate'],
			'rtcl_show_views'         => $settings['showViews'],
			'rtcl_show_ad_types'      => $settings['showAdType'],
			'rtcl_show_location'      => $settings['showLocation'],
			'rtcl_show_category'      => $settings['showCategory'],
			'rtcl_show_price'         => $settings['showPrice'],
			'rtcl_show_user'          => $settings['showAuthor'],
			'rtcl_show_custom_fields' => $settings['showCustomFields'],
			'rtcl_show_favourites'    => $settings['showFavourites'],
			'rtcl_show_quick_view'    => $settings['showQuickView'],
			'rtcl_show_compare'       => $settings['showCompare'],
		];
	}
}
