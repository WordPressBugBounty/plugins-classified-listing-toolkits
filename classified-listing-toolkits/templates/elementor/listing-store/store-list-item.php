<?php

use RtclStore\Models\Store;

$store      = new Store( get_the_ID() );
$count_text = $store->get_ad_count() > 1 ? sprintf( __( '%s Ads', 'classified-listing-toolkits' ), $store->get_ad_count() ) : sprintf( __( '%s Ad', 'classified-listing-toolkits' ), $store->get_ad_count() );
?>
<div class="store-item">
	<?php if ( ! empty( $instance['rtcl_show_image'] ) ) { ?>
        <div class="store-left">
            <a href="<?php echo esc_url( $store->get_the_permalink() ); ?>"><?php echo $store->get_the_logo(); ?></a>
        </div>
	<?php } ?>
    <div class="store-right">
		<?php if ( ! empty( $instance['rtcl_show_title'] ) ) { ?>
            <h3 class="store-title">
                <a href="<?php echo $store->get_the_permalink(); ?>"><?php echo $store->get_the_title(); ?></a>
            </h3>
		<?php } ?>
		<?php if ( ! empty( $instance['rtcl_show_time'] ) ) { ?>
            <div class="store-time"><?php echo sprintf( esc_html__( 'Since %s', 'classified-listing-toolkits' ), get_the_time( 'Y', get_the_ID() ) ); ?></div>
		<?php } ?>
		<?php if ( ! empty( $instance['rtcl_show_count'] ) ) { ?>
            <div class="store-count"><?php echo esc_html( $count_text ); ?></div>
		<?php } ?>
    </div>
</div>