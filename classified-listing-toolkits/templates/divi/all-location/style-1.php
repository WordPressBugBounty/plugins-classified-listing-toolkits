<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author        RadiusTheme
 * @version       1.0.0
 */

use Rtcl\Helpers\Functions;

?>

<div class="rtcl rtcl-location-wrapper rtcl-divi-module">
	<?php
	$i = 0;
	if ( ! empty( $terms ) ) {
		$class = ! empty( $settings['rtcl_grid_column'] ) ? ' columns-' . $settings['rtcl_grid_column'] : ' columns-3';
		$class .= ! empty( $settings['rtcl_grid_column_tablet'] ) ? ' tab-columns-' . $settings['rtcl_grid_column_tablet'] : ' tab-columns-2';
		$class .= ! empty( $settings['rtcl_grid_column_phone'] ) ? ' mobile-columns-' . $settings['rtcl_grid_column_phone'] : ' mobile-columns-1';
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
