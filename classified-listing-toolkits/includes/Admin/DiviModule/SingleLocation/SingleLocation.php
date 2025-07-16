<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\SingleLocation;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper as DiviFunctions;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\Base\DiviModule;

class SingleLocation extends DiviModule {

	public $slug = 'rtcl_single_location';
	public $vb_support = 'on';
	public $icon_path;
    public $bind_wrapper = '';
	protected $module_credits
		= [
			'author'     => 'RadiusTheme',
			'author_uri' => 'https://radiustheme.com',
		];

	public function init() {
		$this->name      = esc_html__( 'Listing Single Location', 'classified-listing-toolkits' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';

        $this->bind_wrapper = Helper::is_divi_plugin_active() ? '' : '.et-db .et-l ' ;
		$this->folder_name = 'et_pb_classified_general_module';

		$this->settings_modal_toggles = [
			'general'  => [
				'toggles' => [
					'general' => esc_html__( 'General', 'classified-listing-toolkits' ),
				],
			],
			'advanced' => [
				'toggles' => [
					'card'  => esc_html__( 'Box', 'classified-listing-toolkits' ),
					'title' => esc_html__( 'Title', 'classified-listing-toolkits' ),
					'count' => esc_html__( 'Count', 'classified-listing-toolkits' ),
				],
			],
		];
	}

	public function get_fields() {
		$location_dropdown = DiviFunctions::get_listing_taxonomy( 'parent', rtcl()->location );

		return [
			'rtcl_location_style'    => [
				'label'       => esc_html__( 'Style', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'style-1' => __( 'Style 1', 'classified-listing-toolkits' ),
				],
				'default'     => 'style-1',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_location_tax'      => [
				'label'       => esc_html__( 'Location', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => $location_dropdown,
				'description' => esc_html__( 'Select a location to display.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_enable_link'       => [
				'label'       => esc_html__( 'Enable Link', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'description' => __( 'Add / Remove listing location link.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'rtcl_show_count'        => [
				'label'       => esc_html__( 'Show Count', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'description' => __( 'Show / Hide listing counts.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			// computed.
			'__location'             => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'SingleLocation', 'get_content' ),
				'computed_depends_on' => array(
					'rtcl_location_tax'
				)
			),
			// Style
			'rtcl_content_alignment' => [
				'label'       => esc_html__( 'Content Alignment', 'classified-listing-toolkits' ),
				'type'        => 'text_align',
				'options'     => et_builder_get_text_orientation_options( [ 'justified' ] ),
				'default'     => 'center',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'card',
			],
			'rtcl_box_content_bg'    => [
				'label'       => esc_html__( 'Content Background Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for content background.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'card',
			],
			'rtcl_box_height'        => [
				'label'          => esc_html__( 'Adjust Box Height', 'classified-listing-toolkits' ),
				'description'    => esc_html__( 'Here you can define height for the box.', 'classified-listing-toolkits' ),
				'type'           => 'range',
				'default'        => '290px',
				'allowed_units'  => [ 'px' ],
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 100,
					'step' => 1,
					'max'  => 500,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'card',
				'mobile_options' => true,
			],
			'rtcl_title_color'       => [
				'label'       => esc_html__( 'Name Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for category name.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'title',
			],
			'rtcl_count_color'       => [
				'label'       => esc_html__( 'Count Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for listing count.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'count',
			]
		];
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];

		$advanced_fields['fonts'] = [
			'title' => [
				'css'              => array(
					'main' => $this->bind_wrapper.'%%order_class%% .rtcl-single-location .rtcl-location-name',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
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
			'count' => [
				'css'              => array(
					'main' => $this->bind_wrapper.'%%order_class%% .rtcl-single-location .rtcl-location-listing-count',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'count',
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
			]
		];

		$advanced_fields['margin_padding'] = [
			'css'         => [
				'main'      => $this->bind_wrapper.'%%order_class%% .rtcl-single-location .rtcl-location-content',
				'important' => 'all',
			],
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'card',
		];

		$advanced_fields['background'] = array(
			'css' => array(
				'main' => $this->bind_wrapper.'%%order_class%% .rtcl-single-location .rtcl-location-img'
			),
		);

		return $advanced_fields;
	}

	public static function get_content( $args = [] ) {

		return false;
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings = $this->props;

		$this->render_css( $render_slug );

		$style = isset( $settings['rtcl_location_style'] ) ? sanitize_text_field( $settings['rtcl_location_style'] ) : 'style-1';

		$template_style = 'divi/single-location/' . $style;

		$data = [
			'title'         => esc_html__( 'Please Select a Location and Background', 'classified-listing-toolkits' ),
			'count'         => 0,
			'permalink'     => '#',
			'template'      => $template_style,
			'style'         => $style,
			'settings'      => $settings,
			'template_path' => Helper::get_plugin_template_path(),
		];

		if ( ! empty( $settings['rtcl_location_tax'] ) ) {
			$term = get_term( $settings['rtcl_location_tax'], rtcl()->location );

			if ( $term && ! is_wp_error( $term ) ) {
				$data['title']     = $term->name;
				$data['count']     = $term->count;
				$data['permalink'] = get_term_link( $term );
			}
		}

		$data = apply_filters( 'rtcl_divi_filter_listing_location_data', $data );

		return Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	}

	protected function render_css( $render_slug ) {
		$wrapper            = $this->bind_wrapper.'%%order_class%% .rtcl-single-location';
		$content_background = $this->props['rtcl_box_content_bg'];
		$title_color        = $this->props['rtcl_title_color'];
		$title_font_weight  = explode( '|', $this->props['title_font'] )[1];
		$count_color        = $this->props['rtcl_count_color'];
		$box_height         = $this->props['rtcl_box_height'];

		// Title
		if ( ! empty( $title_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-location-name",
					'declaration' => sprintf( 'color: %1$s;', $title_color ),
				]
			);
		}
		if ( ! empty( $title_font_weight ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => "$wrapper .rtcl-location-name",
					'declaration' => sprintf( 'font-weight: %1$s;', $title_font_weight ),
				)
			);
		}
		// count
		if ( ! empty( $count_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-location-listing-count",
					'declaration' => sprintf( 'color: %1$s;', $count_color ),
				]
			);
		}
		// box
		if ( ! empty( $content_background ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-location-content",
					'declaration' => sprintf( 'background-color: %1$s;', $content_background ),
				]
			);
		}
		if ( ! empty( $box_height ) ) {
			$this->get_responsive_styles(
				'rtcl_box_height',
				"$wrapper .rtcl-single-location-inner",
				array( 'primary' => 'height' ),
				array( 'default' => 'auto' ),
				$render_slug
			);
		}
	}
}