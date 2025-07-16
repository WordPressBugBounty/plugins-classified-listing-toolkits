<?php
/**
 * Trait for listing widget
 *
 * The Elementor builder.
 *
 * @since    2.0.10
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\ELWidgetsTraits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

trait ArchiveGeneralTrait {
	/**
	 * Set Query controlls
	 *
	 * @return array
	 */
	public function archive_general_fields() {
		$fields = [
			[
				'mode'  => 'section_start',
				'id'    => 'rtcl_sec_general',
				'label' => __('General', 'classified-listing-toolkits'),
			],
			[
				'type'          => Controls_Manager::SWITCHER,
				'id'            => 'rtcl_archive_result_count',
				'label'         => __('Result count', 'classified-listing-toolkits'),
				'label_on'      => __('On', 'classified-listing-toolkits'),
				'label_off'     => __('Off', 'classified-listing-toolkits'),
				'description'   => __('Switch to Show Result Count', 'classified-listing-toolkits'),
				'default'       => 'yes',
			],
			[
				'type'          => Controls_Manager::SWITCHER,
				'id'            => 'rtcl_archive_catalog_ordering',
				'label'         => __('Catalog Ordering', 'classified-listing-toolkits'),
				'label_on'      => __('On', 'classified-listing-toolkits'),
				'label_off'     => __('Off', 'classified-listing-toolkits'),
				'description'   => __('Switch to Show Catalog Ordering', 'classified-listing-toolkits'),
				'default'       => 'yes',
			],
			[
				'type'          => Controls_Manager::SWITCHER,
				'id'            => 'rtcl_archive_view_switcher',
				'label'         => __('View Switcher', 'classified-listing-toolkits'),
				'label_on'      => __('On', 'classified-listing-toolkits'),
				'label_off'     => __('Off', 'classified-listing-toolkits'),
				'description'   => __('Switch to Show View Switcher', 'classified-listing-toolkits'),
				'default'       => 'yes',
			],
			[
				'type'          => Controls_Manager::SWITCHER,
				'id'            => 'rtcl_listing_pagination',
				'label'         => __('Pagination', 'classified-listing-toolkits'),
				'label_on'      => __('On', 'classified-listing-toolkits'),
				'label_off'     => __('Off', 'classified-listing-toolkits'),
				'description'   => __('Switch to Show Pagination', 'classified-listing-toolkits'),
				'default'       => 'yes',
			],
			[
				'label'         => __('Image Size', 'classified-listing-toolkits'),
				'type'          => Group_Control_Image_Size::get_type(),
				'id'            => 'rtcl_thumb_image',
				'exclude'       => ['custom'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude	
				'mode'          => 'group',
				'default'       => 'rtcl-thumbnail',
				'separator'     => 'none',
				'description'   => __('Select Image Size', 'classified-listing-toolkits'),
			],
			[
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}
