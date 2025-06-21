<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var array $instance
 */

$cssstyle = null;
$rand     = wp_rand();
$classes  = " rtcl-unique-class-$rand ";

if ( 'on' === $instance['rtcl_slider_dot'] ) {
	$classes .= ' rtcl-slider-pagination-dot';
}
if ( 'on' === $instance['rtcl_slider_arrow'] ) {
	$classes .= ' rtcl-slider-btn-arrow';
}

$margin_right = 20;

// css variable for jumping issue
// Jumping Issue Reduce
if ( ! empty( $instance['rtcl_grid_column'] ) ) {
	$width    = 100 / ( $instance['rtcl_grid_column'] ?? 3 );
	$cssstyle .= "--xl-width: calc( {$width}% - {$margin_right}px );";
}
if ( ! empty( $instance['rtcl_grid_column_tablet'] ) ) {
	$width    = 100 / ( $instance['rtcl_grid_column_tablet'] ?? 2 );
	$cssstyle .= "--md-width:calc( {$width}% - {$margin_right}px );";
}
if ( ! empty( $instance['rtcl_grid_column_phone'] ) ) {
	$width    = 100 / ( $instance['rtcl_grid_column_phone'] ?? 1 );
	$cssstyle .= "--mb-width:calc( {$width}% - {$margin_right}px );";
}
$cssstyle .= '--margin-right: ' . $margin_right . 'px;';
$cssstyle .= '--nagative-margin-right: -' . $margin_right . 'px;';
?>

<div class="rtcl rtcl-listings-wrapper rtcl-elementor-widget rtcl-divi-module rtcl-el-slider-wrapper rtcl-slider-pagination-style-4 rtcl-slider-btn-style-1 <?php echo esc_html( $classes ); ?>"
     style="<?php echo esc_attr( $cssstyle ); ?>">
	<?php $class = ! empty( $style ) ? 'rtcl-grid-' . $style : 'rtcl-grid-style-1 '; ?>
	<?php
	$auto_height    = 'on' === $instance['rtcl_slider_auto_height'] ? '1' : '0';
	$loop           = 'on' === $instance['rtcl_slider_loop'] ? '1' : '0';
	$autoplay       = 'on' === $instance['rtcl_slider_autoplay'] ? '1' : '0';
	$stop_on_hover  = 'on' === $instance['rtcl_slider_stop_on_hover'] ? '1' : '0';
	$delay          = '5000';
	$autoplay_speed = '200';
	$dots           = 'on' === $instance['rtcl_slider_dot'] ? '1' : '0';
	$nav            = 'on' === $instance['rtcl_slider_arrow'] ? '1' : '0';
	$space_between  = '20';

	$autoplay   = boolval( $autoplay ) ? array(
		'delay'                => absint( $delay ),
		'pauseOnMouseEnter'    => boolval( $stop_on_hover ),
		'disableOnInteraction' => false,
	) : boolval( $autoplay );
	$pagination = boolval( $dots ) ? array(
		'el'        => ".rtcl-unique-class-$rand .rtcl-slider-pagination",
		'clickable' => true,
		'type'      => 'bullets',
	) : boolval( $dots );
	$navigation = boolval( $nav ) ? array(
		'nextEl' => ".rtcl-unique-class-$rand .button-right",
		'prevEl' => ".rtcl-unique-class-$rand .button-left",
	) : boolval( $nav );
	$break_0    = array(
		'slidesPerView'  => ! empty( $instance['rtcl_grid_column_phone'] ) ? absint( $instance['rtcl_grid_column_phone'] ) : 1,
		'slidesPerGroup' => ! empty( $instance['rtcl_grid_column_phone'] ) ? absint( $instance['rtcl_grid_column_phone'] ) : 1,
	);
	$break_767  = array(
		'slidesPerView'  => ! empty( $instance['rtcl_grid_column_tablet'] ) ? absint( $instance['rtcl_grid_column_tablet'] ) : 2,
		'slidesPerGroup' => ! empty( $instance['rtcl_grid_column_tablet'] ) ? absint( $instance['rtcl_grid_column_tablet'] ) : 2,
	);
	$break_1199 = array(
		'slidesPerView'  => ! empty( $instance['rtcl_grid_column'] ) ? absint( $instance['rtcl_grid_column'] ) : 3,
		'slidesPerGroup' => ! empty( $instance['rtcl_grid_column'] ) ? absint( $instance['rtcl_grid_column'] ) : 3,
	);

	$swiper_data = array(
		// Optional parameters
		'slidesPerView'  => ! empty( $instance['rtcl_grid_column'] ) ? absint( $instance['rtcl_grid_column'] ) : 3,
		'slidesPerGroup' => ! empty( $instance['rtcl_grid_column'] ) ? absint( $instance['rtcl_grid_column'] ) : 3,
		'spaceBetween'   => absint( $space_between ),
		'loop'           => boolval( $loop ),
		// If we need pagination
		'slideClass'     => 'swiper-slide-customize',
		'autoplay'       => $autoplay,
		// If we need pagination
		'pagination'     => $pagination,
		'speed'          => absint( $autoplay_speed ),
		// allowTouchMove: true,
		// Navigation arrows
		'navigation'     => $navigation,
		'autoHeight'     => boolval( $auto_height ),
		'breakpoints'    => array(
			0    => $break_0,
			767  => $break_767,
			1199 => $break_1199,
		),
	);

	$swiper_data = apply_filters( 'rtcl_divi_listing_slider_options', $swiper_data, $instance );

	$swiper_data = wp_json_encode( $swiper_data );


	?>
    <div class="rtcl-listings rtcl-listings-slider-container swiper rtcl-grid-view <?php echo esc_attr( $class ); ?> rtcl-carousel-slider"
         data-options="<?php echo esc_attr( $swiper_data ); ?>">
        <div class="rtcl-swiper-lazy-preloader">
            <svg class="spinner" viewBox="0 0 50 50">
                <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
            </svg>
        </div>
        <div class="swiper-wrapper">
