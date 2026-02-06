<?php

use RtclStore\Models\Store;

$store      = new Store( $id );
$count_html = sprintf( _nx( '%s ad', '%s ads', $store->get_ad_count(), 'Number of Ads', 'classified-listing-toolkits' ), number_format_i18n( $store->get_ad_count() ) );
?>
<div class="store-item" href="<?php echo esc_attr( $store->get_the_permalink() ); ?>">
	<?php if ( ! empty( $instance['rtcl_show_image'] ) ) { ?>
        <div class="store-logo">
            <a href="<?php echo $store->get_the_permalink(); ?>"><?php echo $store->get_the_logo(); ?></a>
        </div>
	<?php } ?>
	<?php if ( ! empty( $instance['rtcl_show_title'] ) ) { ?>
        <h3 class="store-title">
            <a href="<?php echo $store->get_the_permalink(); ?>"><?php echo esc_html( $store->get_the_title() ); ?></a>
        </h3>
	<?php } ?>
	<?php if ( ! empty( $instance['rtcl_show_time'] ) ) { ?>
        <div class="store-time"><?php echo sprintf( esc_html__( 'Since %s', 'classified-listing-toolkits' ), get_the_time( 'Y', get_the_ID() ) ); ?></div>
	<?php } ?>
	<?php if ( ! empty( $instance['rtcl_show_count'] ) ) { ?>
        <div class="store-count"><?php echo esc_html( $count_html ); ?></div>
	<?php } ?>

</div>