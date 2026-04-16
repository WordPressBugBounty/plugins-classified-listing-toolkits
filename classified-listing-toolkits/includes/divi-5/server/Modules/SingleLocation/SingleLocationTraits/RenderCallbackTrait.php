<?php
/**
 * Render Callback Trait for Single Location.
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

trait RTCL_Divi5_SingleLocation_RenderCallbackTrait {

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
		$inner_content = self::render_location( $settings );

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
				'moduleClassName'     => 'rtcl-single-location-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_SingleLocation::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_SingleLocation::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_SingleLocation::class, 'module_script_data' ],
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
			'style'              => $attrs['style']['innerContent']['desktop']['value'] ?? 'style-1',
			'locationId'         => $attrs['locationId']['innerContent']['desktop']['value'] ?? '',
			'showChildLocations' => $attrs['showChildLocations']['innerContent']['desktop']['value'] ?? 'off',
			'childLocationLimit' => $attrs['childLocationLimit']['innerContent']['desktop']['value'] ?? '5',
			'showCount'          => $attrs['showCount']['innerContent']['desktop']['value'] ?? 'on',
			'showDescription'    => $attrs['showDescription']['innerContent']['desktop']['value'] ?? 'off',
			'contentAlignment'   => $attrs['contentAlignment']['innerContent']['desktop']['value'] ?? 'center',
		];
	}

	/**
	 * Render location HTML.
	 *
	 * @param array $settings Module settings.
	 * @return string
	 */
	private static function render_location( $settings ) {
		// Map settings to template format (matching Divi 4 naming).
		$template_settings = self::map_settings_to_instance( $settings );

		$style          = in_array( $settings['style'] ?? '', [ 'style-1' ], true ) ? $settings['style'] : 'style-1';
		$template_path  = Helper::get_plugin_template_path();

		// Fallback to style-1 if selected style template does not exist.
		if ( ! file_exists( $template_path . 'divi/single-location/' . $style . '.php' ) ) {
			$style = 'style-1';
		}

		$template_style = 'divi/single-location/' . $style;

		$location = self::get_location( $settings );

		// Calculate count for the location.
		$count = 0;
		if ( $location && 'on' === $settings['showCount'] ) {
			$count = Functions::get_listings_count_by_taxonomy( $location->term_id, rtcl()->location );
		}

		$data = [
			'template'        => $template_style,
			'settings'        => $template_settings,
			'location'        => $location,
			'permalink'       => $location ? get_term_link( $location ) : '',
			'title'           => $location ? $location->name : '',
			'description'     => $location ? $location->description : '',
			'count'           => $count,
			'child_locations' => self::get_child_locations( $settings, $location ),
			'style'           => $style,
			'template_path'   => $template_path,
		];

		$data = apply_filters( 'rtcl_divi_filter_single_location_data', $data );

		if ( ! empty( $data['location'] ) ) {
			$output = Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
		} else {
			$output = '<p class="rtcl-no-location">' . esc_html__( 'No location found. Please select a valid location.', 'classified-listing-toolkits' ) . '</p>';
		}

		return '<div class="rtcl-single-location-wrapper">' . $output . '</div>';
	}

	/**
	 * Get single location based on settings.
	 *
	 * @param array $settings Module settings.
	 * @return object|null
	 */
	private static function get_location( $settings ) {
		if ( empty( $settings['locationId'] ) ) {
			return null;
		}

		$location_id = intval( $settings['locationId'] );

		$location = get_term( $location_id, rtcl()->location );

		if ( is_wp_error( $location ) || empty( $location ) ) {
			return null;
		}

		return $location;
	}

	/**
	 * Get child locations based on settings.
	 *
	 * @param array  $settings Module settings.
	 * @param object $location Parent location.
	 * @return array
	 */
	private static function get_child_locations( $settings, $location ) {
		if ( empty( $location ) || 'on' !== $settings['showChildLocations'] ) {
			return [];
		}

		$args = [
			'taxonomy'   => rtcl()->location,
			'hide_empty' => false,
			'parent'     => $location->term_id,
			'number'     => intval( $settings['childLocationLimit'] ),
			'orderby'    => 'name',
			'order'      => 'ASC',
		];

		$child_locations = get_terms( $args );

		if ( is_wp_error( $child_locations ) ) {
			return [];
		}

		return $child_locations;
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
			'rtcl_location_style'       => $settings['style'],
			'rtcl_location_id'          => $settings['locationId'],
			'rtcl_show_child_locations' => $settings['showChildLocations'],
			'rtcl_child_location_limit' => $settings['childLocationLimit'],
			'rtcl_show_count'           => $settings['showCount'],
			'rtcl_show_description'     => $settings['showDescription'],
			'rtcl_content_alignment'    => $settings['contentAlignment'],
			'rtcl_enable_link'          => 'on',
		];
	}
}
