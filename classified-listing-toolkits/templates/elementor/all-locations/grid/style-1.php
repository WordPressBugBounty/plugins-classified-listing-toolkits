<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
/**
 * Main Elementor locationbox.
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since 1.0.0
 */

?>
<div class="rtcl el-all-locations grid-<?php
echo esc_attr( $style ); ?> ">
    <div class="rtcl-row">
        <?php
        $classes = 'rtcl-col-xl-' . $settings['rtcl_col_xl'];
        $classes .= ' rtcl-col-lg-' . $settings['rtcl_col_lg'];
        $classes .= ' rtcl-col-md-' . $settings['rtcl_col_md'];
        $classes .= ' rtcl-col-sm-' . $settings['rtcl_col_sm'];
        $classes .= ' rtcl-col-' . $settings['rtcl_col_mobile'];
        ?>
        <?php
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $trm ) {
                $count_html = null;
                if ( $settings['display_count'] ) {
                    ob_start();
                    $count_data = sprintf(  /* translators: Ads count */ _n( '(%s Ad)', '(%s Ads)', $trm->count, 'classified-listing-toolkits' ), $trm->count );

                    ?>
                    <span class="rtcl-counter">
								<span><?php
                                    echo esc_html( $count_data ); ?></span>
							</span>
                    <?php
                    $count_html = ob_get_clean();
                }
                $image = '';
                if ( $settings['rtcl_show_image'] ) {
                    $image_size = $settings['rtcl_image_size'] ?? 'rtcl-thumbnail';
                    if ( 'custom' === $image_size ) {
                        $image_size = $settings['rtcl_image_size_custom_dimension'] ?? 'rtcl-thumbnail';
                    }
                    $image_id         = get_term_meta( $trm->term_id, '_rtcl_image', true );
                    $image_attributes = wp_get_attachment_image_src( (int) $image_id, $image_size );
                    $image            = ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
                }
                ?>
                <div class="location-boxes-wrapper <?php echo esc_attr( $classes ); ?>">
                    <div class="location-boxes">
                        <?php if ( ! empty( $image ) ) : ?>
                            <div class="rtcl-location-image">
                                <?php if ( $settings['enable_link'] ) { ?>
                                    <a href="<?php echo esc_url( get_term_link( $trm ) ); ?>">
                                        <img src="<?php echo esc_url( $image ); ?>" class="rtcl-responsive-img"/>
                                    </a>
                                <?php } else { ?>
                                    <img src="<?php echo esc_url( $image ); ?>" class="rtcl-responsive-img"/>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <div class="title-wrap">
                            <h3 class="rtcl-title">
                                <?php
                                if ( $settings['enable_link'] ) { ?>
                                    <a href="<?php
                                    echo esc_url( get_term_link( $trm ) ); ?>">
                                        <?php
                                        echo esc_html( $trm->name ); ?>
                                    </a>
                                    <?php
                                } else {
                                    echo esc_html( $trm->name );
                                }
                                ?>
                            </h3>
                        </div>
                        <?php
                        $arr = [
                                'span' => [
                                        'class' => [],
                                ],
                        ];
                        echo wp_kses( $count_html, $arr );
                        ?>
                        <?php
                        if ( $settings['display_descriptiuon'] && ! empty( $trm->description ) ) { ?>
                            <div class="rtcl-description">
                                <?php
                                if ( $settings['rtcl_content_limit'] ) {
                                    echo esc_html( wp_trim_words( $trm->description, $settings['rtcl_content_limit'] ) );
                                } else {
                                    echo wp_kses_post( $trm->description );
                                }
                                ?>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
