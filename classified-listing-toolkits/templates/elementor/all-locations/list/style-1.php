<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Main Elementor locationbox.
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since    1.0.0
 */

?>
<div class="rtcl el-all-locations list-<?php echo esc_attr( $style ); ?>">

	<?php
	$classes = 'rtcl-col-12';
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $trm ) {
			$count_html = null;
			if ( $settings['display_count'] ) {
				ob_start();
				$count_data = sprintf( /* translators: Ads count */ _n( '(%s Ad)', '(%s Ads)', $trm->count, 'classified-listing-toolkits' ), $trm->count );
				?>
				<span class="rtcl-counter">
						<span><?php echo esc_html( $count_data ); ?></span>
					</span>
				<?php
				$count_html = ob_get_clean();
			}
			?>
			<div class="location-boxes-wrapper">
				<div class="location-boxes">
					<div class="title-wrap">
						<h3 class="rtcl-title">
							<?php if ( $settings['enable_link'] ) { ?>
								<a href="<?php echo esc_url( get_term_link( $trm ) ); ?>">
									<?php echo esc_html( $trm->name ); ?>
								</a>
								<?php
							} else {
								echo esc_html( $trm->name );
							}
							?>
							<?php
							if ( 'inline' === $settings['display_count_position'] ) {
								$arr = [
									'span' => [
										'class' => [],
									],
								];
								echo wp_kses( $count_html, $arr );
							}
							?>
						</h3>
						<?php
						if ( 'new_line' === $settings['display_count_position'] ) {
							$arr = [
								'span' => [
									'class' => [],
								],
							];
							echo wp_kses( $count_html, $arr );
						}
						?>
					</div>

					<?php if ( $settings['display_descriptiuon'] && ! empty( $trm->description ) ) { ?>
						<div class="rtcl-description">
							<?php
							if ( $settings['rtcl_content_limit'] ) {
								echo esc_html( wp_trim_words( $trm->description, $settings['rtcl_content_limit'] ) );
							} else {
								echo wp_kses_post( $trm->description );
							}
							?>
						</div>
					<?php } ?>

				</div>
			</div>
			<?php
		}
	}
	?>
</div>
