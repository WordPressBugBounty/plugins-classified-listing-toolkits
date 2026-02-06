<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
/**
 * @author   RadiusTheme
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since    2.0.10
 * @version  1.0
 */

/* translators: %s: number of ads */
$count_html = sprintf( _nx( '%s Ad', '%s Ads', $count, 'Number of Ads', 'classified-listing-toolkits' ), number_format_i18n( $count ) );

$link_start   = $settings['enable_link'] ? '<a href="' . $permalink . '">' : '';
$link_end     = $settings['enable_link'] ? '</a>' : '';
$location_box = $settings['rtcl_location_style'] ? $settings['rtcl_location_style'] : ' style-1';
$class        = $settings['display_count'] ? ' rtin-has-count ' : '';
$class        .= ' location-box-' . $location_box;

?>
<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>">
    <div class="rtcl-image-wrapper">
        <?php echo wp_kses_post( $link_start ); ?>
        <?php if ( ! empty( $settings['rtcl_bg_image_style'] ) && 'featured_image' === $settings['rtcl_bg_image_style'] ):
            $term_id          = ! empty( $term_id ) ? $term_id : 0;
            $image_id         = get_term_meta( $term_id, '_rtcl_image', true );
            $image_attributes = wp_get_attachment_image_src( (int) $image_id, 'full' );
            $image            = ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
        endif; ?>
        <div class="rtin-img" <?php echo ! empty( $image ) ? 'style="background-image: url(' . esc_url( $image ) . ');"' : ''; ?>></div>
        <?php echo wp_kses_post( $link_end ); ?>
    </div>

    <div class="rtin-content">
        <?php if ( $settings['display_count'] ) :
            $ads_text = null;
            if ( ! empty( $settings['display_text_after_count'] ) ) {
                $ads_text = $settings['display_text_after_count'];
            }
            printf( "<div class='rtin-counter'>%d <span class='ads-count'>%s</span></div>", absint( $count ), esc_html( $ads_text ) );
        endif; ?>
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
                <?php echo wp_kses_post( $icon ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <!-- <i class="fas fa-arrow-right link-icon"></i> -->
            </a>
        <?php } ?>
    </div>
</div>
