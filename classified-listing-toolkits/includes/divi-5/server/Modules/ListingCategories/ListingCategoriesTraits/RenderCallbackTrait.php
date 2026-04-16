<?php
/**
 * Render Callback Trait for Listing Categories.
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

trait RTCL_Divi5_ListingCategories_RenderCallbackTrait {

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

		// Get categories.
		$categories = self::get_categories_query( $settings );

		// Build the inner content.
		$inner_content = self::render_categories( $categories, $settings );

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
				'moduleClassName'     => 'rtcl-listing-categories-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_ListingCategories::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_ListingCategories::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_ListingCategories::class, 'module_script_data' ],
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
			'style'           => $attrs['style']['innerContent']['desktop']['value'] ?? 'style-1',
			'gridColumn'      => $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3',
			'categories'      => $attrs['categories']['innerContent']['desktop']['value'] ?? '',
			'catLimit'        => $attrs['catLimit']['innerContent']['desktop']['value'] ?? '10',
			'orderby'         => $attrs['orderby']['innerContent']['desktop']['value'] ?? 'name',
			'order'           => $attrs['order']['innerContent']['desktop']['value'] ?? 'asc',
			'hideEmpty'       => $attrs['hideEmpty']['innerContent']['desktop']['value'] ?? 'off',
			'showCount'       => $attrs['showCount']['innerContent']['desktop']['value'] ?? 'on',
			'showDescription'     => $attrs['showDescription']['innerContent']['desktop']['value'] ?? 'off',
			'showIcon'            => $attrs['showIcon']['innerContent']['desktop']['value'] ?? 'on',
			'showChildCategories' => $attrs['showChildCategories']['innerContent']['desktop']['value'] ?? 'off',
			'childCategoryLimit'  => $attrs['childCategoryLimit']['innerContent']['desktop']['value'] ?? '5',
		];
	}

	/**
	 * Get categories query.
	 *
	 * @param array $settings Module settings.
	 * @return array
	 */
	private static function get_categories_query( $settings ) {
		$args = self::build_query_args( $settings );

		return get_terms( $args );
	}

	/**
	 * Build query arguments.
	 *
	 * @param array $settings Module settings.
	 * @return array
	 */
	private static function build_query_args( $settings ) {
		$allowed_orderby = [ 'name', 'term_id', 'count', 'custom' ];
		$args = [
			'taxonomy'   => rtcl()->category,
			'hide_empty' => 'on' === $settings['hideEmpty'],
			'number'     => intval( $settings['catLimit'] ),
			'orderby'    => in_array( $settings['orderby'] ?? '', $allowed_orderby, true ) ? $settings['orderby'] : 'name',
			'order'      => 'desc' === strtolower( $settings['order'] ?? '' ) ? 'DESC' : 'ASC',
			'parent'     => 0, // Only parent categories.
		];

		// Filter by specific category IDs if provided.
		// CheckboxesContainer produces pipe-separated values like "1|2|3".
		if ( ! empty( $settings['categories'] ) ) {
			$category_ids = self::parse_category_ids( $settings['categories'] );
			if ( ! empty( $category_ids ) ) {
				$args['include'] = $category_ids;
			}
		}

		// Handle custom orderby.
		if ( 'custom' === $settings['orderby'] && ! empty( $settings['categories'] ) ) {
			$args['orderby'] = 'include';
		}

		return $args;
	}

	/**
	 * Parse category IDs from string or array.
	 *
	 * Divi 5 CheckboxesContainer may store data as:
	 * - Array of selected values: ['15', '23', '42']
	 * - Pipe-separated string: "15|23|42"
	 * - Comma-separated string: "15,23,42"
	 *
	 * @param mixed $categories Category IDs (string or array).
	 * @return array Category IDs array.
	 */
	private static function parse_category_ids( $categories ) {
		if ( empty( $categories ) ) {
			return [];
		}

		$category_ids = [];

		// Handle array input (Divi 5 CheckboxesContainer format).
		if ( is_array( $categories ) ) {
			$category_ids = array_values( $categories );
		}
		// Handle string input (pipe or comma separated).
		elseif ( is_string( $categories ) ) {
			$separator = strpos( $categories, '|' ) !== false ? '|' : ',';
			$category_ids = array_filter( explode( $separator, $categories ) );
		}

		// Filter to only include valid numeric IDs.
		$valid_ids = array_filter( $category_ids, function( $id ) {
			return is_numeric( $id ) && intval( $id ) > 0;
		});

		return ! empty( $valid_ids ) ? array_map( 'intval', $valid_ids ) : [];
	}

	/**
	 * Render categories HTML.
	 *
	 * @param array $categories Categories array.
	 * @param array $settings   Module settings.
	 * @return string
	 */
	private static function render_categories( $categories, $settings ) {
		// Map settings to template format (matching Divi 4 naming).
		$template_settings = self::map_settings_to_instance( $settings );

		$style          = in_array( $settings['style'] ?? '', [ 'style-1' ], true ) ? $settings['style'] : 'style-1';
		$template_style = 'divi/listing-cats/' . $style;

		$data = [
			'template'      => $template_style,
			'settings'      => $template_settings,
			'terms'         => $categories,
			'style'         => $style,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_listing_categories_data', $data );

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			try {
				$output = Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
			} catch ( \Exception $e ) {
				$output = '<p>' . esc_html__( 'Error rendering categories.', 'classified-listing-toolkits' ) . '</p>';
				error_log( 'RTCL Divi5 ListingCategories render error: ' . $e->getMessage() );
			}
		} else {
			$output = '<p>' . esc_html__( 'No categories found.', 'classified-listing-toolkits' ) . '</p>';
		}

		return $output;
	}

	/**
	 * Map Divi 5 settings to template instance format.
	 * Uses the same keys as Divi 4 for template compatibility.
	 *
	 * @param array $settings Divi 5 settings.
	 * @return array Instance array for templates.
	 */
	private static function map_settings_to_instance( $settings ) {
		return [
			'rtcl_cats_style'        => $settings['style'],
			'rtcl_grid_column'       => $settings['gridColumn'],
			'rtcl_cats'              => $settings['categories'],
			'rtcl_category_limit'    => $settings['catLimit'],
			'rtcl_orderby'           => $settings['orderby'],
			'rtcl_order'             => $settings['order'],
			'rtcl_hide_empty'        => $settings['hideEmpty'],
			'rtcl_show_count'        => $settings['showCount'],
			'rtcl_description'           => $settings['showDescription'],
			'rtcl_show_image'            => 'on' === $settings['showIcon'],
			'rtcl_icon_type'             => 'icon',
			'rtcl_content_limit'         => 20,
			'rtcl_content_alignment'     => 'center',
			'rtcl_show_child_categories' => $settings['showChildCategories'],
			'rtcl_child_category_limit'  => $settings['childCategoryLimit'],
		];
	}
}
