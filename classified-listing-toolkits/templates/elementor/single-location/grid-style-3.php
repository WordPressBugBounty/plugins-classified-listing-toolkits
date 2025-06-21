<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author  RadiusTheme
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since   2.0.10
 * @version 1.0
 */

/* translators: %s: number of ads */
$count_html = sprintf( _nx( '%s Ad', '%s Ads', $count, 'Number of Ads', 'classified-listing-toolkits' ), number_format_i18n( $count ) );

$link_start   = $settings['enable_link'] ? '<a href="' . $permalink . '">' : '';
$link_end     = $settings['enable_link'] ? '</a>' : '';
$location_box = $settings['rtcl_location_style'] ? $settings['rtcl_location_style'] : ' style-1';
$class        = $settings['display_count'] ? ' rtin-has-count ' : '';
$class       .= ' location-box-' . $location_box;

?>
<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>">
	<div class="rtcl-image-wrapper">
		<?php echo wp_kses_post( $link_start ); ?>
		<div class="rtin-img"></div>
		<?php echo wp_kses_post( $link_end ); ?>
	</div>

	<div class="rtin-content">
		<?php if ( $settings['display_count'] ) : ?>
			<div class="rtin-counter"><?php echo  wp_kses_post( $count_html ); ?></div>
		<?php endif; ?>
		<h3 class="rtin-title"> 
			<?php
			if ( $settings['enable_link'] ) {
				?>
					<a href="<?php echo esc_url( $permalink ); ?>"> 
						<?php echo esc_html( $title ); ?>
					</a>
					<?php
			} else {
				echo esc_html( $title );
			}
			?>
		</h3>
		
		<?php if ( $settings['enable_link'] ) { ?>
			<a href="<?php echo esc_url( $permalink ); ?>"> 
				<?php echo wp_kses_post($icon); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<!-- <i class="fas fa-arrow-right link-icon"></i> -->
			</a>
		<?php } ?>
	</div>
</div>
