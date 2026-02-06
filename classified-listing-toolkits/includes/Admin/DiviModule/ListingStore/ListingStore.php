<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\ListingStore;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\Base\DiviModule;
use Rtcl\Helpers\Pagination;

class ListingStore extends DiviModule {

	public $slug = 'rtcl_listing_store';
	public $vb_support = 'on';
	public $icon_path;
    public $bind_wrapper = '';
	protected $module_credits
		= [
			'author'     => 'RadiusTheme',
			'author_uri' => 'https://radiustheme.com',
		];

	public function init() {
		$this->name      = esc_html__( 'Listing Store', 'classified-listing-toolkits' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';
        $this->bind_wrapper = Helper::is_divi_plugin_active() ? '' : '.et-db .et-l ' ;
		$this->folder_name = 'et_pb_classified_general_module';

		$this->settings_modal_toggles = [
			'general'  => [
				'toggles' => [
					'general' => esc_html__( 'General', 'classified-listing-toolkits' ),
					'visibility' => esc_html__( 'Visibility', 'classified-listing-toolkits' ),
				],
			],
			'advanced' => [
				'toggles' => [
					'title'   => esc_html__( 'Title', 'classified-listing-toolkits' ),
					'time' => esc_html__( 'Time', 'classified-listing-toolkits' ),
					'counter' => esc_html__( 'Counter', 'classified-listing-toolkits' ),
				],
			],
		];
	}
	protected static function taxonomy_list( $parent = 'all', $taxonomy = '' ) {
		$args = [
			'taxonomy'   => rtcl()->category,
			'fields'     => 'id=>name',
			'hide_empty' => true,
		];
		if ( ! empty( $taxonomy ) ) {
			$args['taxonomy'] = sanitize_text_field( $taxonomy );
		}
		if ( 'parent' === $parent ) {
			$args['parent'] = 0;
		}
		$terms = get_terms( $args );

		$category_dropdown = [];
		foreach ( $terms as $id => $name ) {
			$category_dropdown[ $id ] = $name;
		}
		return $category_dropdown;
	}
	public function get_fields() {
		$category_dropdown = self::taxonomy_list( 'parent', 'store_category' );
		return [
			'rtcl_store_view'       => [
				'label'       => esc_html__( 'Style', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'list' => esc_html__( 'List', 'classified-listing-toolkits' ),
					'grid'   => esc_html__( 'Grid', 'classified-listing-toolkits' ),
				],
				'default'     => 'grid',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'posts_per_page' => [
				'label'       => esc_html__( 'Post Per Page', 'classified-listing-toolkits' ),
				'type'        => 'text',
				'default'     => '4',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_store_pagination'       => [
				'label'       => esc_html__( 'Store Pagination', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_store_load_more_button'      => [
				'label'       => esc_html__( 'Load More Button', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'off',
				'description' => __( 'Show / Hide keyword field.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
				'show_if'    => [
					'rtcl_store_pagination'     => 'on',
				],
			],
			'store_cat'        => [
				'label'       => esc_html__( 'Category', 'classified-listing-toolkits' ),
				'type'        => 'multiple_checkboxes',
				'options'  => $category_dropdown,
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'store_orderby'     => [
				'label'       => esc_html__( 'Order By', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options' => [
					'date'  => __( 'Date', 'classified-listing-toolkits' ),
					'title' => __( 'Title', 'classified-listing-toolkits' ),
				],
				'default'     => 'date',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'store_order'     => [
				'label'       => esc_html__( 'Order', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options' => [
					'asc'  => __( 'Ascending', 'classified-listing-toolkits' ),
					'desc' => __( 'Descending', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_store_column'       => [
				'label'       => esc_html__( 'Column', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'8' => __( 'Column 8', 'classified-listing-toolkits' ),
					'7' => __( 'Column 7', 'classified-listing-toolkits' ),
					'6' => __( 'Column 6', 'classified-listing-toolkits' ),
					'5' => __( 'Column 5', 'classified-listing-toolkits' ),
					'4' => __( 'Column 4', 'classified-listing-toolkits' ),
					'3' => __( 'Column 3', 'classified-listing-toolkits' ),
					'2' => __( 'Column 2', 'classified-listing-toolkits' ),
					'1' => __( 'Column 1', 'classified-listing-toolkits' ),
				],
				'default'     => '4',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
				'show_if'    => [
					'rtcl_store_view' => 'grid',
				],
			],
			'rtcl_store_column_tablet'       => [
				'label'       => esc_html__( 'Tab Column', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'8' => __( 'Column 8', 'classified-listing-toolkits' ),
					'7' => __( 'Column 7', 'classified-listing-toolkits' ),
					'6' => __( 'Column 6', 'classified-listing-toolkits' ),
					'5' => __( 'Column 5', 'classified-listing-toolkits' ),
					'4' => __( 'Column 4', 'classified-listing-toolkits' ),
					'3' => __( 'Column 3', 'classified-listing-toolkits' ),
					'2' => __( 'Column 2', 'classified-listing-toolkits' ),
					'1' => __( 'Column 1', 'classified-listing-toolkits' ),
				],
				'default'     => '2',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
				'show_if'    => [
					'rtcl_store_view' => 'grid',
				],
			],
			'rtcl_store_column_mobile'       => [
				'label'       => esc_html__( 'Mobile Column', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'8' => __( 'Column 8', 'classified-listing-toolkits' ),
					'7' => __( 'Column 7', 'classified-listing-toolkits' ),
					'6' => __( 'Column 6', 'classified-listing-toolkits' ),
					'5' => __( 'Column 5', 'classified-listing-toolkits' ),
					'4' => __( 'Column 4', 'classified-listing-toolkits' ),
					'3' => __( 'Column 3', 'classified-listing-toolkits' ),
					'2' => __( 'Column 2', 'classified-listing-toolkits' ),
					'1' => __( 'Column 1', 'classified-listing-toolkits' ),
				],
				'default'     => '1',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
				'show_if'    => [
					'rtcl_store_view' => 'grid',
				],
			],
			'rtcl_show_image'        => [
				'label'       => esc_html__( 'Show Image', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			'rtcl_show_title'        => [
				'label'       => esc_html__( 'Show Title', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			'rtcl_show_time'        => [
				'label'       => esc_html__( 'Show Time', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			'rtcl_show_count'        => [
				'label'       => esc_html__( 'Show Count', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'visibility',
			],
			// computed.
			'__form_html'        => array(
				'type'                => 'computed',
				'computed_callback'   => array( ListingStore::class, 'get_html' ),
				'computed_depends_on' => array(
					'rtcl_show_count',
					'rtcl_show_time',
					'rtcl_show_title',
					'rtcl_show_image',
					'rtcl_store_column',
					'store_order',
					'store_orderby',
					'store_cat',
					'rtcl_store_load_more_button',
					'rtcl_store_pagination',
					'posts_per_page',
					'rtcl_store_view',
				)
			)
		];
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];

		$advanced_fields['fonts'] = [
			'title'  => [
				'css'              => array(
					'main' =>  '%%order_class%% .store-item .store-title',
				),
				'important'        => 'all',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'title',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.2em',
				),
				'font_size'        => array(
					'default' => '18px',
				),
				'font'             => [
					'default' => '|700|||||||',
				],
			],
			'time' => [
				'css'              => array(
					'main' => '%%order_class%% .store-item .store-time',
				),
				'important'        => 'all',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'time',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.6em',
				),
				'font_size'        => array(
					'default' => '16px',
				),
				'font'             => [
					'default' => '|400|||||||',
				],
			],
			'counter' => [
				'css'              => array(
					'main' => '%%order_class%% .store-item .store-count',
				),
				'important'        => 'all',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'counter',
				'line_height'      => array(
					'range_settings' => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '.1',
					),
					'default'        => '1.6em',
				),
				'font_size'        => array(
					'default' => '16px',
				),
				'font'             => [
					'default' => '|400|||||||',
				],
			],
		];

		return $advanced_fields;
	}
	private static function store_query( $data ) {
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

	public static function get_html( $settings ) {
		wp_enqueue_style( 'rtcl-store-public' );
		wp_enqueue_script( 'rtcl-store-public' );
		$settings['rtcl_store_pagination'] = $settings['rtcl_store_pagination'] == 'on' ? true : false;
		$settings['rtcl_store_load_more_button'] = $settings['rtcl_store_load_more_button'] == 'on' ? 'yes' : false;
		$settings['rtcl_show_image'] = $settings['rtcl_show_image'] == 'on' ? true : false;
		$settings['rtcl_show_title'] = $settings['rtcl_show_title'] == 'on' ? true : false;
		$settings['rtcl_show_time'] = $settings['rtcl_show_time'] == 'on' ? true : false;
		$settings['rtcl_show_count'] = $settings['rtcl_show_count'] == 'on' ? true : false;

		$settings['stores'] = self::store_query( $settings );
		$template_style     = 'divi/listing-store/' . $settings['rtcl_store_view'] . '-store';
		$data               = [
			'template'              => $template_style,
			'instance'              => $settings,
			'default_template_path' => Helper::get_plugin_template_path()
		];
		$data               = apply_filters( 'rtcl_el_store_data', $data );
		ob_start();
		 Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
		$output = ob_get_clean();
		return $output;
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings = $this->props;
		return self::get_html( $settings );
	}

}