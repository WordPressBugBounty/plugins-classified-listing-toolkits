<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Store Categories - Style 1 template for Divi 5.
 *
 * @author        RadiusTheme
 * @version       1.0.0
 */

if ( ! function_exists( 'rtclStore' ) ) {
	return;
}

$store_taxonomy = property_exists( rtclStore(), 'category' ) ? rtclStore()->category : 'store_category';

?>

<div class="rtcl rtcl-store-categories-wrapper rtcl-divi-module">
	<?php
	if ( ! empty( $terms ) ) {
		$class = ! empty( $settings['rtcl_grid_column'] ) ? ' columns-' . absint( $settings['rtcl_grid_column'] ) : ' columns-3';
		$class .= ! empty( $settings['rtcl_grid_column_tablet'] ) ? ' tab-columns-' . absint( $settings['rtcl_grid_column_tablet'] ) : ' tab-columns-2';
		$class .= ! empty( $settings['rtcl_grid_column_phone'] ) ? ' mobile-columns-' . absint( $settings['rtcl_grid_column_phone'] ) : ' mobile-columns-1';
		?>
		<div class="rtcl-store-cat-items-wrapper rtcl-grid-view <?php echo esc_attr( $class ); ?>">
			<?php
			foreach ( $terms as $trm ) {
				$count = 0;
				if ( 'on' === $settings['rtcl_show_count'] ) {
					$store_post_type = function_exists( 'rtclStore' ) && property_exists( rtclStore(), 'post_type' ) ? rtclStore()->post_type : 'rtcl_store';
					$store_ids       = get_posts( [
						'post_type'      => $store_post_type,
						'tax_query'      => [ [ 'taxonomy' => $store_taxonomy, 'field' => 'term_id', 'terms' => $trm->term_id ] ],
						'posts_per_page' => -1,
						'fields'         => 'ids',
						'post_status'    => 'publish',
					] );
					if ( ! empty( $store_ids ) ) {
						$author_ids = array_unique( array_filter( array_map( fn( $id ) => (int) get_post_field( 'post_author', $id ), $store_ids ) ) );
						$lq         = new WP_Query( [
							'post_type'      => rtcl()->post_type,
							'author__in'     => $author_ids,
							'posts_per_page' => 1,
							'fields'         => 'ids',
							'post_status'    => 'publish',
							'no_found_rows'  => false,
						] );
						$count = (int) $lq->found_posts;
					}
				}

				$content_alignment = ! empty( $settings['rtcl_content_alignment'] ) ? $settings['rtcl_content_alignment'] : 'center';
				$term_link         = get_term_link( $trm );
				if ( is_wp_error( $term_link ) ) {
					$term_link = '#';
				}

				$view_text = sprintf(
					/* translators: %s: Store Category term */
					__( 'View all stores in %s', 'classified-listing-toolkits' ),
					$trm->name
				);

				echo '<div class="rtcl-store-cat-item">';
				echo '<div class="cat-details text-' . esc_attr( $content_alignment ) . '">';
				echo '<div class="cat-details-inner">';

				if ( $settings['rtcl_show_image'] ) {
					if ( 'image' === $settings['rtcl_icon_type'] ) {
						$image_id         = get_term_meta( $trm->term_id, '_rtcl_image', true );
						$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'rtcl-thumbnail' );
						$image            = isset( $image_attributes[0] ) && ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
						if ( '' !== $image ) {
							echo '<div class="rtcl-store-category-image">';
							printf(
								'<a href="%s" title="%s"><img src="%s" alt="%s" /></a>',
								esc_url( $term_link ),
								esc_attr( $view_text ),
								esc_url( $image ),
								esc_attr( $trm->name )
							);
							echo '</div>';
						}
					}

					if ( 'icon' === $settings['rtcl_icon_type'] ) {
						$icon_id = get_term_meta( $trm->term_id, '_rtcl_icon', true );
						if ( $icon_id ) {
							if ( ! str_contains( $icon_id, 'fa-' ) ) {
								$icon_id = 'rtcl-icon-' . $icon_id;
							}
							echo '<div class="rtcl-store-category-icon icon">';
							printf(
								'<a href="%s" title="%s"><span class="rtcl-icon %s"></span></a>',
								esc_url( $term_link ),
								esc_attr( $view_text ),
								esc_attr( $icon_id )
							);
							echo '</div>';
						}
					}
				}

				printf(
					'<h3 class="rtcl-store-category-title"><a href="%s" title="%s">%s</a></h3>',
					esc_url( $term_link ),
					esc_attr( $view_text ),
					esc_html( $trm->name )
				);

				if ( 'on' === $settings['rtcl_show_count'] ) {
					$listings_text = __( 'listings', 'classified-listing-toolkits' );
					printf(
						'<div class="rtcl-store-category-count">%d <span class="count-text">%s</span></div>',
						absint( $count ),
						esc_html( $listings_text )
					);
				}

				if ( 'on' === $settings['rtcl_description'] && $trm->description ) {
					$word_limit = wp_trim_words( $trm->description, $settings['rtcl_content_limit'] );
					printf( '<p class="rtcl-store-category-description">%s</p>', esc_html( $word_limit ) );
				}

				echo '</div>';
				echo '</div>';


				echo '</div>';
			}
			?>
		</div>
	<?php } ?>
</div>
