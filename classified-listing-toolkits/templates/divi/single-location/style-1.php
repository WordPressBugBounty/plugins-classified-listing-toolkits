<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 *
 * @var string  $permalink
 * @var string  $title
 * @var integer $count
 * @var array   $settings
 */

$count_html = sprintf( /* translators: Ads count */ _nx( '%s Ad', '%s Ads', $count, 'Number of Ads', 'classified-listing-toolkits' ), number_format_i18n( $count ) );

$link_start      = $settings['rtcl_enable_link'] === 'on' ? '<a href="' . esc_url( $permalink ) . '">' : '';
$link_end        = $settings['rtcl_enable_link'] === 'on' ? '</a>' : '';
$class           = $settings['rtcl_show_count'] === 'on' ? ' rtcl-has-count' : '';
$class           .= ' rtcl-single-location-' . $settings['rtcl_location_style'];
$alignment_class = isset( $settings['rtcl_content_alignment'] ) ? 'text-' . $settings['rtcl_content_alignment'] : '';
?>
<div class="rtcl rtcl-single-location rtcl-divi-module <?php echo esc_attr( $class ); ?>">
    <div class="rtcl-single-location-inner">
		<?php echo wp_kses_post( $link_start ); ?>
        <div class="rtcl-location-img"></div>
        <div class="rtcl-location-content <?php echo esc_attr( $alignment_class ); ?>">
            <h3 class="rtcl-location-name"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $settings['rtcl_show_count'] === 'on' ) : ?>
                <div class="rtcl-location-listing-count"><?php echo wp_kses_post( $count_html ); ?></div>
			<?php endif; ?>
        </div>
		<?php echo wp_kses_post( $link_end ); ?>
    </div>
</div>
