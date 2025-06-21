<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
</div> <!-- End swiper-wrapper -->
</div>  <!-- End swiper -->
<?php if ( 'on' === $instance['rtcl_slider_arrow'] ) { ?>
    <!-- If we need navigation buttons -->
    <span class="rtcl-slider-btn button-left rtcl-icon-angle-left"></span>
    <span class="rtcl-slider-btn button-right rtcl-icon-angle-right"></span>

<?php } ?>
<?php if ( 'on' === $instance['rtcl_slider_dot'] ) { ?>
    <!-- If we need pagination -->
    <div class="rtcl-slider-pagination"></div>
<?php } ?>
</div> <!-- End rtcl-listings-wrapper -->
