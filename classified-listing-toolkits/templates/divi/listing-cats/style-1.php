<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author        RadiusTheme
 * @version       1.0.0
 */

use Rtcl\Helpers\Functions;

?>

<div class="rtcl rtcl-categories-wrapper rtcl-divi-module">
	<?php
	$i = 0;
	if ( ! empty( $terms ) ) {
		$class = ! empty( $settings['rtcl_grid_column'] ) ? ' columns-' . $settings['rtcl_grid_column'] : ' columns-3';
		$class .= ! empty( $settings['rtcl_grid_column_tablet'] ) ? ' tab-columns-' . $settings['rtcl_grid_column_tablet'] : ' tab-columns-2';
		$class .= ! empty( $settings['rtcl_grid_column_phone'] ) ? ' mobile-columns-' . $settings['rtcl_grid_column_phone'] : ' mobile-columns-1';
		?>
        <div class="rtcl-cat-items-wrapper rtcl-grid-view <?php echo esc_attr( $class ); ?>">
			<?php
			foreach ( $terms as $trm ) {
				$count = 0;
				if ( 'on' === $settings['rtcl_show_count'] ) {
					$count = Functions::get_listings_count_by_taxonomy( $trm->term_id, rtcl()->category );
				}

				$content_alignemnt = ! empty( $settings['rtcl_content_alignment'] ) ? $settings['rtcl_content_alignment'] : 'left';
				echo '<div class="rtcl-cat-item">';
				echo '<div class="cat-details text-' . esc_attr( $content_alignemnt ) . '">';
				echo '<div class="cat-details-inner">';

				$view_post = sprintf(
				/* translators: %s: Category term */
					__( 'View all posts in %s', 'classified-listing-toolkits' ),
					$trm->name
				);
				if ( $settings['rtcl_show_image'] ) {
					$icon_image_html = '';
					if ( 'image' === $settings['rtcl_icon_type'] ) {
						$image_size       = 'rtcl-thumbnail';
						$image_id         = get_term_meta( $trm->term_id, '_rtcl_image', true );
						$image_attributes = wp_get_attachment_image_src( (int) $image_id, $image_size );
						$image            = isset( $image_attributes[0] ) && ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
						if ( '' !== $image ) {
							echo "<div class='image'>";
							$icon_image_html .= '<a href="' . esc_url( get_term_link( $trm ) ) . '" title="'
							                    . esc_attr( $view_post ) . '">';
							$icon_image_html .= '<img src="' . esc_url( $image ) . '"/>';
							$icon_image_html .= '</a>';

							echo wp_kses_post($icon_image_html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</div>';
						}
					}

					if ( 'icon' === $settings['rtcl_icon_type'] ) {
						$icon_id = get_term_meta( $trm->term_id, '_rtcl_icon', true );
						if ( $icon_id ) {
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

				printf(
					"<h3 class='rtcl-category-title'><a href='%s' title='%s'>%s</a></h3>",
					esc_url( get_term_link( $trm ) ),
					esc_attr( $view_post ),
					esc_html( $trm->name )
				);

				if ( 'on' === $settings['rtcl_show_count'] ) {
					$ads_text = __( 'ads', 'classified-listing-toolkits' );
					printf( "<div class='count'>%d <span class='count-text'>%s</span></div>", absint( $count ), esc_html( $ads_text ) );
				}
				if ( 'on' === $settings['rtcl_description'] && $trm->description ) {
					$word_limit = wp_trim_words( $trm->description, $settings['rtcl_content_limit'] );
					printf( '<p>%s</p>', esc_html( $word_limit ) );
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			?>
        </div>
	<?php } ?>
</div>
