<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\SearchForm;

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\Base\DiviModule;

class SearchForm extends DiviModule {

	public $slug = 'rtcl_search_form';
	public $vb_support = 'on';
	public $icon_path;
	public $bind_wrapper = '';
	protected $module_credits
		= [
			'author'     => 'RadiusTheme',
			'author_uri' => 'https://radiustheme.com',
		];

	public function init() {
		$this->name         = esc_html__( 'Listing Search Form', 'classified-listing-toolkits' );
		$this->icon_path    = plugin_dir_path( __FILE__ ) . 'icon.svg';
		$this->bind_wrapper = Helper::is_divi_plugin_active() ? '' : '.et-db .et-l ';
		$this->folder_name  = 'et_pb_classified_general_module';

		$this->settings_modal_toggles = [
			'general'  => [
				'toggles' => [
					'general' => esc_html__( 'General', 'classified-listing-toolkits' ),
				],
			],
			'advanced' => [
				'toggles' => [
					'form'   => esc_html__( 'Form', 'classified-listing-toolkits' ),
					'fields' => esc_html__( 'Fields', 'classified-listing-toolkits' ),
					'button' => esc_html__( 'Search Button', 'classified-listing-toolkits' ),
				],
			],
		];
	}

	public function get_fields() {

		return [
			'search_style'       => [
				'label'       => esc_html__( 'Style', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => Helper::get_search_options(),
				'default'     => 'dependency',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'search_orientation' => [
				'label'       => esc_html__( 'Orientation', 'classified-listing-toolkits' ),
				'type'        => 'select',
				'options'     => [
					'inline'   => esc_html__( 'Inline', 'classified-listing-toolkits' ),
					'vertical' => esc_html__( 'Vertical', 'classified-listing-toolkits' ),
				],
				'default'     => 'inline',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'fields_label'       => [
				'label'       => esc_html__( 'Show Fields Label', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'keyword_field'      => [
				'label'       => esc_html__( 'Keywords Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'description' => __( 'Show / Hide keyword field.', 'classified-listing-toolkits' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'types_field'        => [
				'label'       => esc_html__( 'Ad Types Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'category_field'     => [
				'label'       => esc_html__( 'Category Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'location_field'     => [
				'label'       => esc_html__( 'Location Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'radius_field'       => [
				'label'       => esc_html__( 'Radius Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'off',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			'price_field'        => [
				'label'       => esc_html__( 'Price Field', 'classified-listing-toolkits' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'classified-listing-toolkits' ),
					'off' => esc_html__( 'No', 'classified-listing-toolkits' ),
				],
				'default'     => 'on',
				'tab_slug'    => 'general',
				'toggle_slug' => 'general',
			],
			// computed.
			'__form_html'        => array(
				'type'                => 'computed',
				'computed_callback'   => array( SearchForm::class, 'get_html' ),
				'computed_depends_on' => array(
					'search_style',
					'search_orientation',
					'fields_label',
					'keyword_field',
					'types_field',
					'category_field',
					'location_field',
					'radius_field',
					'price_field'
				)
			),
			// Style
			'form_background'    => [
				'label'       => esc_html__( 'Form Background Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for form background.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'form',
			],
			'form_label_color'   => [
				'label'       => esc_html__( 'Label Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for form label.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'fields',
			],
			'field_background'   => [
				'label'       => esc_html__( 'Input Background Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for field background.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'fields',
			],
			'field_text_color'   => [
				'label'       => esc_html__( 'Input Text Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for input text.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'fields',
			],
			'field_gap'          => [
				'label'          => esc_html__( 'Fields Gap', 'classified-listing-toolkits' ),
				'description'    => esc_html__( 'Here you can define gutter for the fields gap.', 'classified-listing-toolkits' ),
				'type'           => 'range',
				'default'        => '5px',
				'allowed_units'  => [ 'px' ],
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 30,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'fields',
				'mobile_options' => true,
			],
			'button_background'  => [
				'label'       => esc_html__( 'Background Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom color for submit button background.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'button',
				'hover'       => 'tabs',
			],
			'button_color'       => [
				'label'       => esc_html__( 'Text Color', 'classified-listing-toolkits' ),
				'description' => esc_html__( 'Here you can define a custom text color for submit button.', 'classified-listing-toolkits' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'button',
				'hover'       => 'tabs',
			],
		];
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];

		$advanced_fields['fonts'] = [
			'label'  => [
				'css'              => array(
					'main' => $this->bind_wrapper . '%%order_class%% .rtcl-divi-listing-search .form-group > label',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'fields',
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
			'button' => [
				'css'              => array(
					'main' => $this->bind_wrapper . '%%order_class%% .rtcl-divi-listing-search .rtcl-btn-holder .rtcl-search-btn',
				),
				'important'        => 'all',
				'hide_text_color'  => true,
				'hide_text_shadow' => true,
				'hide_text_align'  => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'button',
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
				'main'      => '%%order_class%% .rtcl-divi-listing-search',
				'important' => 'all',
			],
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'form',
		];

		return $advanced_fields;
	}

	public static function get_html( $settings = [] ) {
		$style       = isset( $settings['search_style'] ) ? sanitize_text_field( $settings['search_style'] ) : 'dependency';
		$orientation = ! empty( $settings['search_orientation'] ) ? sanitize_text_field( $settings['search_orientation'] ) : 'inline';

		$template = 'divi/search-form/listing-search';

		$data = [
			'template'      => $template,
			'style'         => $style,
			'orientation'   => $orientation,
			'settings'      => $settings,
			'template_path' => Helper::get_plugin_template_path(),
		];

		$data = apply_filters( 'rtcl_divi_listing_search_form_options', $data );

		return Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	}

	public function render( $unprocessed_props, $content, $render_slug ) {
		$settings = $this->props;

		$this->render_css( $render_slug );

		return self::get_html( $settings );
	}

	protected function render_css( $render_slug ) {
		$wrapper                 = $this->bind_wrapper . '%%order_class%% .rtcl-divi-listing-search';
		$form_background         = $this->props['form_background'];
		$label_color             = $this->props['form_label_color'];
		$input_background        = $this->props['field_background'];
		$input_text_color        = $this->props['field_text_color'];
		$button_background       = $this->props['button_background'];
		$button_hover_background = $this->get_hover_value( 'button_background' );
		$button_color            = $this->props['button_color'];
		$button_hover_color      = $this->get_hover_value( 'button_color' );
		$field_gutter            = $this->props['field_gap'];
		$orientation             = $this->props['search_orientation'];

		// Form
		if ( ! empty( $form_background ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper",
					'declaration' => sprintf( 'background-color: %1$s;', $form_background ),
				]
			);
		}
		if ( ! empty( $label_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .form-group > label",
					'declaration' => sprintf( 'color: %1$s;', $label_color ),
				]
			);
		}
		if ( ! empty( $input_background ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-search-input-button",
					'declaration' => sprintf( 'background-color: %1$s;', $input_background ),
				]
			);
		}
		if ( ! empty( $input_text_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-search-input-button .rtcl-form-control, $wrapper .rtcl-search-input-button .rtcl-form-control::placeholder",
					'declaration' => sprintf( 'color: %1$s;', $input_text_color ),
				]
			);
		}
		if ( ! empty( $button_background ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-btn-holder .rtcl-search-btn",
					'declaration' => sprintf( 'background-color: %1$s;', $button_background ),
				]
			);
		}
		if ( ! empty( $button_hover_background ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-btn-holder .rtcl-search-btn:hover",
					'declaration' => sprintf( 'background-color: %1$s;', $button_hover_background ),
				]
			);
		}
		if ( ! empty( $button_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-btn-holder .rtcl-search-btn",
					'declaration' => sprintf( 'color: %1$s;', $button_color ),
				]
			);
		}
		if ( ! empty( $button_hover_color ) ) {
			\ET_Builder_Element::set_style(
				$render_slug,
				[
					'selector'    => "$wrapper .rtcl-btn-holder .rtcl-search-btn:hover",
					'declaration' => sprintf( 'color: %1$s;', $button_hover_color ),
				]
			);
		}
		if ( 'vertical' === $orientation ) {
			if ( ! empty( $field_gutter ) ) {
				$this->get_responsive_styles(
					'field_gap',
					"$wrapper .rtcl-widget-search-form div + div",
					array( 'primary' => 'margin-top' ),
					array( 'default' => '20px' ),
					$render_slug
				);
			}
		} else {
			if ( ! empty( $field_gutter ) ) {
				$this->get_responsive_styles(
					'field_gap',
					"$wrapper .rtcl-widget-search-form.rtcl-search-inline",
					array( 'primary' => 'gap' ),
					array( 'default' => '5px' ),
					$render_slug
				);
			}
		}
	}
}