<?php
/**
 * Main Elementor ListingCategoryBox Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  Classifid-listing
 * @since    1.0.0
 */

namespace RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\Widgets;

use Elementor\Controls_Manager;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use RadiusTheme\ClassifiedListingToolkits\Abstracts\ElementorWidgetBaseV2;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClassifiedListingToolkits\Admin\Elementor\WidgetSettings;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

/**
 * ListingCategoryBox Class
 */
class ListingSearchSortableForm extends ElementorWidgetBaseV2 {

	/**
	 * @var array
	 */
	private static $cache = [];

	/**
	 * Undocumented function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtcl_name = __( 'Search Form - Sortable', 'classified-listing-toolkits' );
		$this->rtcl_base = 'rtcl-listing-search-sortable-form';
		parent::__construct( $data, $args );
	}

	/**
	 * Search from style
	 *
	 * @return array
	 */
	public function search_style() {
		$style = apply_filters(
			'rtcl_el_search_style',
			[
				'dependency' => esc_html__( 'Dependency Selection', 'classified-listing-toolkits' ),
			]
		);

		return $style;
	}

	/**
	 * @return mixed|null
	 */
	public static function get_all_fb_form() {
		if ( ! Functions::isEnableFb() ) {
			return [];
		}
		$cache_key = 'rtcl_get_all_fb_form';
		if ( isset( self::$cache[ $cache_key ] ) && ! empty( self::$cache[ $cache_key ] ) ) {
			return self::$cache[ $cache_key ];
		}
		$rawForms = Form::query()
		                ->where( 'status', 'publish' )
		                ->order_by( 'created_at', 'ASC' )
		                ->get();
		$forms    = [];
		if ( ! empty( $rawForms ) ) {
			foreach ( $rawForms as $raw_form ) {
				$_form = apply_filters( 'rtcl_fb_form', $raw_form );
				if ( is_a( $_form, Form::class ) ) {
					$forms[] = [ 'defaultValues' => FBHelper::getFormDefaultData( $_form ) ] + $_form->toArray();
				}
			}
		}
		$forms                     = apply_filters( 'rtcl_get_all_fb_form', $forms );
		self::$cache[ $cache_key ] = $forms;

		return $forms;
	}

	/**
	 * Search from style
	 *
	 * @return array
	 */
	/**
	 * @return mixed|null
	 */
	public static function get_all_fb_form_as_list() {
		$rawForms = self::get_all_fb_form();
		$list     = [];
		if ( ! empty( $rawForms ) && is_array( $rawForms ) ) {
			$list['0'] = esc_html__( 'Select Form', 'classified-listing-toolkits' );
			foreach ( $rawForms as $raw_form ) {
				$list[ esc_attr( $raw_form['id'] ) ] = $raw_form['title'];
			}
		}

		return $list;
	}

	/**
	 * Search from style
	 *
	 * @return array
	 */
	public function custom_fields_list( $form_id ) {
		$data    = [];
		$cFields = FBHelper::getDirectoryCustomFields( $form_id );
		if ( ! empty( $cFields ) ) {
			if ( count( $cFields ) ) {
				$data[''] = esc_html__( 'Select Field', 'classified-listing-toolkits' );
				foreach ( $cFields as $index => $field ) {
					$field = new FBField( $field );
					if ( $field->isFilterable() ) {
						$data[ $field->getName() ] = ! empty( $field->getLabel() ) ? $field->getLabel() : $field->getName() . '( Label Is Empty )';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Search from style
	 *
	 * @return array
	 */
	public function search_oriantation() {
		$style = apply_filters(
			'rtcl_el_search_oriantation',
			[
				'inline'   => __( 'Inline', 'classified-listing-toolkits' ),
				'vertical' => __( 'Vertical', 'classified-listing-toolkits' ),
			]
		);

		return $style;
	}

	/**
	 * Set Query controlls
	 *
	 * @return array
	 */
	public function widget_general_fields(): array {

		$form_fields         = [
			'keyword_field'  => esc_html__( 'Keywords', 'classified-listing-toolkits' ),
			'location_field' => esc_html__( 'Location', 'classified-listing-toolkits' ),
			'category_field' => esc_html__( 'Category', 'classified-listing-toolkits' ),
			'types_field'    => esc_html__( 'Types', 'classified-listing-toolkits' ),
			'price_field'    => esc_html__( 'Price', 'classified-listing-toolkits' ),
			'custom_field'   => esc_html__( 'Custom Fields', 'classified-listing-toolkits' ),
		];
		$form_fields         = apply_filters( 'rtcl_elementor_sortable_search_field_list', $form_fields );
		$form_fields_default = [];
		foreach ( $form_fields as $key => $value ) {
			$form_fields_default[] = [
				'sortable_form_fields'      => $key,
				'sortable_form_field_Label' => $value,
			];
		}

		$sortable_form = [
			'sortable_form_fields'            => [
				'label'     => esc_html__( 'Field\'s', 'classified-listing-toolkits' ),
				'type'      => 'select',
				'separator' => 'default',
				'default'   => 'keyword_field',
				'options'   => $form_fields,
			],
			'sortable_form_field_Label'       => [
				'label'     => esc_html__( 'Label', 'classified-listing-toolkits' ),
				'type'      => 'text',
				'separator' => 'default',
			],
			'sortable_form_field_placeholder' => [
				'label'     => esc_html__( 'Placeholder', 'classified-listing-toolkits' ),
				'type'      => 'text',
				'separator' => 'default',
				'condition' => [
					'sortable_form_fields' => [ 'keyword_field', 'location_field', 'category_field', 'types_field', 'custom_field' ],
				],
			],
			'sortable_form_field_from_fields' => [
				'label'     => esc_html__( 'Select Form', 'classified-listing-toolkits' ),
				'type'      => 'select',
				'options'   => $this->get_all_fb_form_as_list(),
				'default'   => array_key_first( $this->get_all_fb_form_as_list() ),
				'separator' => 'default',
				'condition' => [
					'sortable_form_fields' => [ 'custom_field' ],
				],
			],
		];

		foreach ( $this->get_all_fb_form_as_list() as $id => $value ) {
			$sortable_form [ 'sortable_form_field_custom_fields_' . $id ] = [
				'label'     => esc_html__( 'Select Field', 'classified-listing-toolkits' ),
				'type'      => 'select',
				'options'   => $this->custom_fields_list( $id ),
				'default'   => array_key_first( $this->custom_fields_list( $id ) ),
				'separator' => 'default',
				'condition' => [ 'sortable_form_field_from_fields' => esc_attr( $id ) ],
			];
		}

		$sortable_form = $sortable_form + [
				'sortable_field_width'               => [
					'label'      => esc_html__( 'Width', 'classified-listing-toolkits' ),
					'type'       => 'slider',
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min' => 10,
							'max' => 1500,
						],
						'%'  => [
							'min' => 10,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .rtcl-widget-search-sortable-inline .rtcl-form-group{{CURRENT_ITEM}}'   => 'width:{{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .rtcl-widget-search-sortable-vertical .rtcl-form-group{{CURRENT_ITEM}}' => 'width:{{SIZE}}{{UNIT}};',
					],
					'condition'  => [
						'sortable_form_fields!' => [ 'price_field' ],
					],
				],
				'sortable_min_max_price_field_width' => [
					'label'      => esc_html__( 'Min & Max Field Width', 'classified-listing-toolkits' ),
					'type'       => 'slider',
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min' => 10,
							'max' => 500,
						],
						'%'  => [
							'min' => 10,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group.price-field{{CURRENT_ITEM}}' => 'flex: 0 0 {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
					],
					'condition'  => [
						'sortable_form_fields' => [ 'price_field' ],
					],
				],

			];

		if ( 'geo' === Functions::location_type() ) {
			$sortable_form['geo_location_range'] = [
				'type'      => 'switch',
				'label'     => __( 'Radius Search', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => '',
				'condition' => [
					'sortable_form_fields' => 'location_field',
				],
			];
		}

		$fields = [
			'rtcl_sec_general'   => [
				'mode'  => 'section_start',
				'label' => __( 'General', 'classified-listing-toolkits' ),
			],
			'search_style'       => [
				'type'    => 'select',
				'label'   => __( 'Style', 'classified-listing-toolkits' ),
				'options' => $this->search_style(),
				'default' => 'dependency',
			],
			'search_oriantation' => [
				'type'    => 'select',
				'label'   => __( 'Oriantation', 'classified-listing-toolkits' ),
				'options' => $this->search_oriantation(),
				'default' => 'inline',
			],
			'fields_label'       => [
				'type'      => 'switch',
				'label'     => __( 'Show fields Label', 'classified-listing-toolkits' ),
				'label_on'  => __( 'On', 'classified-listing-toolkits' ),
				'label_off' => __( 'Off', 'classified-listing-toolkits' ),
				'default'   => 'yes',
			],

			'sortable_form' => [
				'type'        => 'repeater',
				'mode'        => 'repeater',
				'label'       => esc_html__( 'Field Types', 'classified-listing-toolkits' ),
				'fields'      => $sortable_form,
				'default'     => $form_fields_default,
				'title_field' => '{{{ sortable_form_field_Label }}}',
			],

			'button_icon_alignment' => [
				'label'   => esc_html__( 'Icon Alignment', 'classified-listing-toolkits' ),
				'type'    => 'choose',
				'options' => [
					'left'  => [
						'title' => esc_html__( 'Left', 'classified-listing-toolkits' ),
						'icon'  => 'fas fa-angle-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'classified-listing-toolkits' ),
						'icon'  => 'fas fa-angle-right',
					],
				],
				'default' => 'right',
			],
			'button_icon'           => [
				'label'     => esc_html__( 'Button Icon', 'classified-listing-toolkits' ),
				'type'      => 'icons',
				'separator' => 'default',
			],
			'button_text'           => [
				'label'     => esc_html__( 'Button Text', 'classified-listing-toolkits' ),
				'type'      => 'text',
				'separator' => 'default',
				'default'   => esc_html__( 'Search', 'classified-listing-toolkits' ),
			],
			'rtcl_sec_general_end'  => [
				'mode' => 'section_end',
			],

		];

		return apply_filters( 'rtcl/elementor/widgets/controls/general/' . $this->rtcl_base, $fields, $this );
	}

	/**
	 * Set Query controlls
	 *
	 * @return array
	 */
	public function widget_style_fields(): array {
		$button         = WidgetSettings\ButtonSettings::style_settings();
		$form_fields    = WidgetSettings\FormFieldSettings::fields_settings();
		$icons_settings = WidgetSettings\IconSettings::style_settings();

		$new_fields = [
			'sortable_field_gap' => [
				'label'     => esc_html__( 'Field\'s Gap', 'classified-listing-toolkits' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-widget-search-sortable-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
		];

		$button['button_width']['selectors'] = [
			'{{WRAPPER}} .rtcl-widget-search-sortable .rtcl-form-group.ws-button' => 'width: {{SIZE}}{{UNIT}};max-width:{{SIZE}}{{UNIT}};',
		];

		$form_fields = $this->insert_controls( 'fields_text_typo', $form_fields, $new_fields, true );

		$fields = array_merge(
			$form_fields,
			$button,
			$icons_settings
		);

		return apply_filters( 'rtcl/elementor/widgets/controls/style/' . $this->rtcl_base, $fields, $this );
	}

	/**
	 * Display Output.
	 *
	 * @return void
	 */
	protected function render() {
		wp_enqueue_style( 'fontawesome' );
		$controllers        = $this->get_settings();
		$search_style       = $controllers['search_style'] ?? 'dependency';
		$search_oriantation = $controllers['search_oriantation'] ?? 'inline';

		$data = [
			'template'              => 'elementor/search-sortable/search-sortable',
			'id'                    => wp_rand(),
			'controllers'           => $controllers,
			'style'                 => $search_style,
			'orientation'           => $search_oriantation,
			'widget_base'           => $this->rtcl_base,
			'selected_category'     => false,
			'selected_location'     => false,
			'listing_form'          => $this->rtcl_base,
			'classes'               => [
				'rtcl-widget-search-sortable',
				'rtcl-widget-search-sortable-' . $search_oriantation,
				'rtcl-widget-search-sortable-style-' . $search_style,
			],
			'default_template_path' => Helper::get_plugin_template_path(),
		];
		$data = apply_filters( 'rtcl/elementor/search/data/' . $this->rtcl_base, $data );
		Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
	}
}
