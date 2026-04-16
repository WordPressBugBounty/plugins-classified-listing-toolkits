<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author        RadiusTheme
 * @version       1.1.0
 */

use Rtcl\Helpers\Functions;

?>

<div class="rtcl rtcl-location-wrapper rtcl-divi-module">
	<?php
	if ( ! empty( $terms ) ) {
		$class = ! empty( $settings['rtcl_grid_column'] ) ? ' columns-' . absint( $settings['rtcl_grid_column'] ) : ' columns-3';
		$class .= ! empty( $settings['rtcl_grid_column_tablet'] ) ? ' tab-columns-' . absint( $settings['rtcl_grid_column_tablet'] ) : ' tab-columns-2';
		$class .= ! empty( $settings['rtcl_grid_column_phone'] ) ? ' mobile-columns-' . absint( $settings['rtcl_grid_column_phone'] ) : ' mobile-columns-1';

		$show_child      = ! empty( $settings['rtcl_show_child_locations'] ) && $settings['rtcl_show_child_locations'] === 'on';
		$child_limit     = ! empty( $settings['rtcl_child_location_limit'] ) ? intval( $settings['rtcl_child_location_limit'] ) : 5;
		?>
        <div class="rtcl-location-items-wrapper rtcl-grid-view <?php echo esc_attr( $class ); ?>">
			<?php
			foreach ( $terms as $trm ) {
				$count = 0;
				if ( 'on' === $settings['rtcl_show_count'] ) {
					$count = Functions::get_listings_count_by_taxonomy( $trm->term_id, rtcl()->location );
				}

				$content_alignemnt = ! empty( $settings['rtcl_content_alignment'] ) ? $settings['rtcl_content_alignment'] : 'left';
				echo '<div class="rtcl-location-item">';
				echo '<div class="location-details text-' . esc_attr( $content_alignemnt ) . '">';
				echo '<div class="location-details-inner">';

				$view_post = sprintf(
				/* translators: %s: Location term */
					__( 'View all posts in %s', 'classified-listing-toolkits' ),
					$trm->name
				);

				printf(
					"<h3 class='rtcl-location-title'><a href='%s' title='%s'>%s</a></h3>",
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
					printf( '<p class="rtcl-location-description">%s</p>', esc_html( $word_limit ) );
				}

				echo '</div>';
				echo '</div>';

				// Child locations.
				if ( $show_child ) {
					$child_locations = get_terms( [
						'taxonomy'   => rtcl()->location,
						'hide_empty' => false,
						'parent'     => $trm->term_id,
						'number'     => $child_limit,
						'orderby'    => 'name',
						'order'      => 'ASC',
					] );

					if ( ! is_wp_error( $child_locations ) && ! empty( $child_locations ) ) {
						echo '<ul class="rtcl-child-locations">';
						foreach ( $child_locations as $child ) {
							$child_count = Functions::get_listings_count_by_taxonomy( $child->term_id, rtcl()->location );
							$child_link  = get_term_link( $child );
							if ( is_wp_error( $child_link ) ) {
								$child_link = '#';
							}
							echo '<li class="rtcl-child-location-item">';
							echo '<a href="' . esc_url( $child_link ) . '">';
							echo esc_html( $child->name );
							if ( 'on' === $settings['rtcl_show_count'] ) {
								echo '<span class="rtcl-child-location-count">(' . esc_html( $child_count > 0 ? str_pad( $child_count, 2, '0', STR_PAD_LEFT ) : '0' ) . ')</span>';
							}
							echo '</a>';
							echo '</li>';
						}
						echo '</ul>';
					}
				}

				echo '</div>';
			}
			?>
        </div>
	<?php } ?>
</div>
