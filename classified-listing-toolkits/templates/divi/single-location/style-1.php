<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.1
 *
 * @var string  $permalink
 * @var string  $title
 * @var string  $description
 * @var integer $count
 * @var array   $child_locations
 * @var array   $settings
 */

$description     = isset( $description ) ? $description : '';
$child_locations = isset( $child_locations ) ? $child_locations : [];

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
			<?php if ( ! empty( $settings['rtcl_show_description'] ) && $settings['rtcl_show_description'] === 'on' && ! empty( $description ) ) : ?>
                <p class="rtcl-location-description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<?php if ( $settings['rtcl_show_count'] === 'on' ) : ?>
                <div class="rtcl-location-listing-count"><?php echo wp_kses_post( $count_html ); ?></div>
			<?php endif; ?>
        </div>
		<?php echo wp_kses_post( $link_end ); ?>
    </div>
	<?php if ( ! empty( $settings['rtcl_show_child_locations'] ) && $settings['rtcl_show_child_locations'] === 'on' && ! empty( $child_locations ) ) : ?>
        <ul class="rtcl-child-locations">
			<?php foreach ( $child_locations as $child ) :
				$child_count = \Rtcl\Helpers\Functions::get_listings_count_by_taxonomy( $child->term_id, rtcl()->location );
				$child_link  = get_term_link( $child );
				if ( is_wp_error( $child_link ) ) {
					$child_link = '#';
				}
				?>
                <li class="rtcl-child-location-item">
                    <a href="<?php echo esc_url( $child_link ); ?>">
                        <?php echo esc_html( $child->name ); ?><?php if ( $settings['rtcl_show_count'] === 'on' ) : ?><span class="rtcl-child-location-count">(<?php echo esc_html( $child_count > 0 ? str_pad( $child_count, 2, '0', STR_PAD_LEFT ) : '0' ); ?>)</span><?php endif; ?>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
	<?php endif; ?>
</div>
