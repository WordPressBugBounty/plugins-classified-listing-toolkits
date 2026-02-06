<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @author        RadiusTheme
 * @version       1.0.0
 */

use Rtcl\Helpers\Functions;

?>

<div class="rtcl rtcl-categories-elementor rtcl-categories rtcl-categories-grid rt-el-listing-cat-box-1 <?php echo esc_attr( $settings['rtcl_cat_box_alignment']
	? 'cat-box-' . $settings['rtcl_cat_box_alignment'] . '-alignment' : '' ); ?>  <?php echo esc_attr( $settings['rtcl_equal_height'] ? 'rtcl-equal-height'
	: '' ); ?>">
    <div class="rtcl-row rtcl-no-margin">
		<?php
		$classes = 'rtcl-col-xl-' . $settings['rtcl_col_xl'];
		$classes .= ' rtcl-col-lg-' . $settings['rtcl_col_lg'];
		$classes .= ' rtcl-col-md-' . $settings['rtcl_col_md'];
		$classes .= ' rtcl-col-sm-' . $settings['rtcl_col_sm'];
		$classes .= ' rtcl-col-xs-' . $settings['rtcl_col_mobile'];
		$classes .= ' rtcl-col-' . $settings['rtcl_col_mobile'];
		$i       = 0;
		foreach ( $terms as $trm ) {
			$count = 0;
			if ( ! empty( $settings['rtcl_hide_empty'] ) || ! empty( $settings['rtcl_show_count'] ) ) {
				$count = Functions::get_listings_count_by_taxonomy(
					$trm->term_id,
					rtcl()->category,
					! empty( $settings['rtcl_pad_counts'] ) ? 1 : 0
				);

				if ( ! empty( $settings['rtcl_hide_empty'] ) && 0 == $count ) {
					continue;
				}
			}


			$content_alignemnt = ! empty( $settings['rtcl_content_alignment'] ) ? $settings['rtcl_content_alignment'] : null;
			echo '<div class="cat-item-wrap equal-item ' . esc_attr( $classes ) . '">';
			echo '<div class="cat-details text-' . esc_attr( $settings['rtcl_cat_box_alignment'] ) . ' ' . esc_attr( $content_alignemnt ) . '">';
			echo '<div class="cat-details-inner">';

			$view_post = sprintf(
			/* translators: %s: Category term */
				__( 'View all posts in %s', 'classified-listing-toolkits' ),
				$trm->name
			);
			if ( $settings['rtcl_show_image'] ) {
				$icon_image_html = '';
				if ( 'image' === $settings['rtcl_icon_type'] ) {
					$image_size = isset( $settings['rtcl_icon_image_size_size'] ) ? $settings['rtcl_icon_image_size_size'] : 'medium';
					if ( 'custom' === $image_size ) {
						$image_size = isset( $settings['rtcl_icon_image_size_custom_dimension'] ) ? $settings['rtcl_icon_image_size_custom_dimension']
							: 'medium';
					}
					$image_id         = get_term_meta( $trm->term_id, '_rtcl_image', true );
					$image_attributes = wp_get_attachment_image_src( (int) $image_id, $image_size );
					$image            = isset( $image_attributes[0] ) && ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
					if ( '' !== $image ) {
						echo "<div class='image'>";
						$icon_image_html .= '<a href="' . esc_url( get_term_link( $trm ) ) . '" class="rtcl-responsive-container" title="'
						                    . esc_attr( $view_post ) . '">';
						$icon_image_html .= '<img src="' . esc_url( $image ) . '" class="rtcl-responsive-img" alt="Category Image" />';
						$icon_image_html .= '</a>';
						echo wp_kses_post( $icon_image_html ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '</div>';
					}
				}

				if ( 'icon' === $settings['rtcl_icon_type'] ) {
					$icon_id = get_term_meta( $trm->term_id, '_rtcl_icon', true );
					if ( $icon_id && ! is_array( $icon_id ) ) {
						if ( ! str_contains( $icon_id, 'fa-' ) ) {
							$icon_id = 'rtcl-icon-' . $icon_id;
						}
						echo "<div class='icon'>";
						printf(
							'<a href="%s" title="%s"><span class="rtcl-icon %s"></span></a>',
							esc_url( get_term_link( $trm ) ),
							esc_attr( $view_post ),
							esc_attr( $icon_id )
						);
						echo '</div>';

					}
				}
			}

			if ( $settings['rtcl_show_category_title'] ) {
				printf(
					"<h3 class='rtcl-category-title'><a href='%s' title='%s'>%s</a></h3>",
					esc_url( get_term_link( $trm ) ),
					esc_attr( $view_post ),
					esc_html( $trm->name )
				);

			}

			if ( ! empty( $settings['rtcl_show_count'] ) ) {
				$ads_text = null;
				if ( ! empty( $settings['display_text_after_count'] ) ) {
					$ads_text = $settings['display_text_after_count'];
				}
				printf( "<div class='views'>%d <span class='ads-count'>%s</span></div>", absint( $count ), esc_html( $ads_text ) );
			}
			if ( $settings['rtcl_description'] && $trm->description ) {
				$word_limit = wp_trim_words( $trm->description, $settings['rtcl_content_limit'] );
				printf( '<p>%s</p>', esc_html( $word_limit ) );
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
    </div>
</div>
