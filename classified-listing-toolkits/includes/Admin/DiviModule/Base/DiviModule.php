<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin\DiviModule\Base;

use ET_Builder_Element;

class DiviModule extends \ET_Builder_Module {

	public function get_conditional_responsive_styles( $styles, $data, $style ) {
		$important = isset( $styles['important'] ) ? $styles['important'] : false;

		if ( 'padding' === $style || 'margin' === $style ) {
			return $this->process_margin_padding( $data, $style, $important );
		} elseif ( 'align-self' === $style || 'align-items' === $style || 'justify-content' === $style ) {
			return $this->process_flex_style( $data, $style, $important );
		} elseif ( 'flex' === $style ) {
			return 'flex: 0 0 ' . $data . ';';
		} else {
			return sprintf(
				'
                %1$s:%2$s%3$s;',
				$style,
				$data,
				$important ? '!important;' : ''
			);
		}
	}

	protected function get_responsive_styles(
		$opt_name,
		$selector,
		$styles = null,
		$pre_values = null,
		$render_slug = null
	) {
		$styles     = ! empty( $styles ) ? $styles : array();
		$pre_values = ! empty( $styles ) ? $styles : array();
		$is_enabled = false;
		$style      = isset( $styles['primary'] ) ? $styles['primary'] : '';
		$_data      = $this->props[ $opt_name ];

		if ( isset( $this->props["{$opt_name}_last_edited"] ) ) {
			$is_enabled = et_pb_get_responsive_status( $this->props["{$opt_name}_last_edited"] );
		}

		if ( empty( $_data ) && ! empty( $pre_values ) ) {
			$is_default = true;
			if ( ! empty( $pre_values['conditional'] ) ) {
				foreach ( $pre_values['conditional']['values'] as $value ) {
					$property_val = $this->props[ $pre_values['conditional']['name'] ];
					if ( $property_val === $value['a'] ) {
						$_data      = $value['b'];
						$is_default = false;
					}
				}
			}

			if ( $is_default ) {
				$_data = isset( $pre_values['default'] ) ? $pre_values['default'] : null;
			}
		}

		if ( ! empty( $_data ) ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => $selector,
					'declaration' => $this->get_conditional_responsive_styles( $styles, $_data, $style ),
				)
			);

			if ( ! empty( $styles['secondary'] ) ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => $selector,
						'declaration' => isset( $styles['secondary'] ) ? isset( $styles['secondary'] ) : '',
					)
				);
			}
		}

		if ( $is_enabled ) {
			$_data_tablet = $this->props["{$opt_name}_tablet"];
			$_data_phone  = $this->props["{$opt_name}_phone"];

			if ( ! empty( $_data_tablet ) ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => $selector,
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
						'declaration' => $this->get_conditional_responsive_styles( $styles, $_data_tablet, $style ),
					)
				);

				if ( ! empty( $styles['secondary'] ) ) {
					ET_Builder_Element::set_style(
						$render_slug,
						array(
							'selector'    => $selector,
							'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
							'declaration' => $styles['secondary'],
						)
					);
				}
			}

			if ( ! empty( $_data_phone ) ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => $selector,
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
						'declaration' => $this->get_conditional_responsive_styles( $styles, $_data_phone, $style ),
					)
				);

				if ( ! empty( $styles['secondary'] ) ) {
					ET_Builder_Element::set_style(
						$render_slug,
						array(
							'selector'    => $selector,
							'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
							'declaration' => $styles['secondary'],
						)
					);
				}
			}
		}
	}

	public static function process_margin_padding(
		$val,
		$type,
		$imp
	) {
		$_top     = '';
		$_right   = '';
		$_bottom  = '';
		$_left    = '';
		$imp_text = '';
		$_val     = explode( '|', $val );

		if ( $imp ) {
			$imp_text = '!important';
		}

		if ( isset( $_val[0] ) && ! empty( $_val[0] ) ) {
			$_top = "{$type}-top:" . $_val[0] . $imp_text . ';';
		}

		if ( isset( $_val[1] ) && ! empty( $_val[1] ) ) {
			$_right = "{$type}-right:" . $_val[1] . $imp_text . ';';
		}

		if ( isset( $_val[2] ) && ! empty( $_val[2] ) ) {
			$_bottom = "{$type}-bottom:" . $_val[2] . $imp_text . ';';
		}

		if ( isset( $_val[3] ) && ! empty( $_val[3] ) ) {
			$_left = "{$type}-left:" . $_val[3] . $imp_text . ';';
		}

		return esc_html( "{$_top} {$_right} {$_bottom} {$_left}" );
	}

	public static function process_flex_style( $val, $type, $important ) {
		$flex_val = 'center';
		if ( 'left' === $val ) {
			$flex_val = 'flex-start';
		} elseif ( 'right' === $val ) {
			$flex_val = 'flex-end';
		}

		return sprintf(
			'%1$s:%2$s%3$s;',
			$type,
			$flex_val,
			$important ? '!important;' : ''
		);
	}
}