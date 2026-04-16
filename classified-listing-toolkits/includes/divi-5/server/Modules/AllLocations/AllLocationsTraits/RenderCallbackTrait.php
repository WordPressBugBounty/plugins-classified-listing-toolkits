<?php
/**
 * Render Callback Trait for All Locations.
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

trait RTCL_Divi5_AllLocations_RenderCallbackTrait {

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

		// Build the inner content.
		$inner_content = self::render_locations( $settings );

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
				'moduleClassName'     => 'rtcl-all-locations-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_AllLocations::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_AllLocations::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_AllLocations::class, 'module_script_data' ],
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
			'style'            => $attrs['style']['innerContent']['desktop']['value'] ?? 'style-1',
			'gridColumn'       => $attrs['gridColumn']['innerContent']['desktop']['value'] ?? '3',
			'locations'        => $attrs['locations']['innerContent']['desktop']['value'] ?? '',
			'locationLimit'    => $attrs['locationLimit']['innerContent']['desktop']['value'] ?? '12',
			'orderby'          => $attrs['orderby']['innerContent']['desktop']['value'] ?? 'name',
			'order'            => $attrs['order']['innerContent']['desktop']['value'] ?? 'asc',
			'hideEmpty'          => $attrs['hideEmpty']['innerContent']['desktop']['value'] ?? 'off',
			'showCount'          => $attrs['showCount']['innerContent']['desktop']['value'] ?? 'on',
			'showDescription'    => $attrs['showDescription']['innerContent']['desktop']['value'] ?? 'off',
			'showChildLocations' => $attrs['showChildLocations']['innerContent']['desktop']['value'] ?? 'off',
			'childLocationLimit' => $attrs['childLocationLimit']['innerContent']['desktop']['value'] ?? '5',
			'contentAlignment'   => $attrs['contentAlignment']['innerContent']['desktop']['value'] ?? 'center',
		];
	}

	/**
	 * Render locations HTML.
	 *
	 * @param array $settings Module settings.
	 * @return string
	 */
	private static function render_locations( $settings ) {
		// Map settings to template format (matching Divi 4 naming).
		$template_settings = self::map_settings_to_instance( $settings );

		$style          = in_array( $settings['style'] ?? '', [ 'style-1' ], true ) ? $settings['style'] : 'style-1';
		$template_style = 'divi/all-location/' . $style;

		$terms = self::get_locations( $settings );

		$data = [
			'template'      => $template_style,
			'settings'      => $template_settings,
			'terms'         => $terms,
			'style'         => $style,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_filter_all_locations_data', $data );

		if ( ! empty( $data['terms'] ) && ! is_wp_error( $data['terms'] ) ) {
			$output = Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
		} else {
			$output = '<p class="rtcl-no-locations">' . esc_html__( 'No locations found.', 'classified-listing-toolkits' ) . '</p>';
		}

		return $output;
	}

	/**
	 * Get locations based on settings.
	 *
	 * @param array $settings Module settings.
	 * @return array
	 */
	private static function get_locations( $settings ) {
		$allowed_orderby = [ 'name', 'term_id', 'count', 'custom', 'none' ];
		$args = [
			'taxonomy'   => rtcl()->location,
			'hide_empty' => 'on' === $settings['hideEmpty'],
			'number'     => intval( $settings['locationLimit'] ),
			'orderby'    => in_array( $settings['orderby'] ?? '', $allowed_orderby, true ) ? $settings['orderby'] : 'name',
			'order'      => 'desc' === strtolower( $settings['order'] ?? '' ) ? 'DESC' : 'ASC',
		];

		// Handle custom orderby.
		if ( 'custom' === $settings['orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_rtcl_order';
		}

		// Filter by specific location IDs if provided.
		// Supports pipe-separated (CheckboxesContainer), comma-separated, and array formats.
		if ( ! empty( $settings['locations'] ) ) {
			$location_ids = self::parse_location_ids( $settings['locations'] );
			if ( ! empty( $location_ids ) ) {
				$args['include'] = $location_ids;
			}
		}

		$locations = get_terms( $args );

		if ( is_wp_error( $locations ) ) {
			return [];
		}

		return $locations;
	}

	/**
	 * Parse location IDs from various formats.
	 *
	 * @param mixed $value Location IDs (array, pipe-separated, or comma-separated).
	 * @return array Integer IDs.
	 */
	private static function parse_location_ids( $value ) {
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
		$valid = array_filter( $ids, function ( $id ) {
			return is_numeric( $id ) && intval( $id ) > 0;
		} );
		return ! empty( $valid ) ? array_map( 'intval', $valid ) : [];
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
			'rtcl_location_style'    => $settings['style'],
			'rtcl_grid_column'       => $settings['gridColumn'],
			'rtcl_location'          => $settings['locations'],
			'rtcl_location_limit'    => $settings['locationLimit'],
			'rtcl_orderby'           => $settings['orderby'],
			'rtcl_order'             => $settings['order'],
			'rtcl_hide_empty'           => $settings['hideEmpty'],
			'rtcl_show_count'           => $settings['showCount'],
			'rtcl_description'          => $settings['showDescription'],
			'rtcl_show_child_locations' => $settings['showChildLocations'],
			'rtcl_child_location_limit' => $settings['childLocationLimit'],
			'rtcl_content_alignment'    => $settings['contentAlignment'],
			'rtcl_content_limit'        => '20',
		];
	}
}
