<?php

use RtclStore\Models\Store;

$store     = new Store( get_the_ID() );
$permalink = $store->get_the_permalink();

// Normalize D4 (boolean true/false) and D5 (string 'on'/'off') visibility values.
$normalize = function( $val, $default = true ) {
	if ( true === $val || 'on' === $val ) return true;
	if ( false === $val || 'off' === $val ) return false;
	return $default;
};

// D4 uses 'rtcl_show_title', D5 uses 'rtcl_show_name'.
$show_image   = $normalize( $instance['rtcl_show_image'] ?? null );
$show_name    = $normalize( $instance['rtcl_show_name'] ?? $instance['rtcl_show_title'] ?? null );
$show_desc    = $normalize( $instance['rtcl_show_description'] ?? null );
// D4 uses 'rtcl_show_count', D5 uses 'rtcl_show_listings_count'.
$show_count   = $normalize( $instance['rtcl_show_listings_count'] ?? $instance['rtcl_show_count'] ?? null );
$show_contact = $normalize( $instance['rtcl_show_contact'] ?? null, false );
$show_social  = $normalize( $instance['rtcl_show_social_links'] ?? null, false );

$phone   = $store->get_phone();
$email   = $store->get_email();
$address = $store->get_address();
$social  = $store->get_social_media();
?>
<article class="rtcl-store-item">
	<?php if ( $show_image ) : ?>
		<div class="rtcl-store-image">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php
				$logo = $store->get_the_logo();
				if ( $logo ) {
					echo $logo;
				} else {
					echo '<span class="rtcl-store-image-placeholder"><i class="rtcl-icon rtcl-icon-store"></i></span>';
				}
				?>
			</a>
		</div>
	<?php endif; ?>

	<div class="rtcl-store-body">
		<?php if ( $show_name ) : ?>
			<h3 class="rtcl-store-name">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $store->get_the_title() ); ?></a>
			</h3>
		<?php endif; ?>

		<?php if ( $show_count ) : ?>
			<div class="rtcl-store-listings-count">
				<i class="rtcl-icon rtcl-icon-listing"></i>
				<?php
				$count = $store->get_ad_count();
				echo sprintf(
					_nx( '%s Listing', '%s Listings', $count, 'Listings count', 'classified-listing-toolkits' ),
					number_format_i18n( $count )
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $show_desc ) :
			$description = $store->get_the_description();
			if ( $description ) : ?>
				<p class="rtcl-store-description"><?php echo wp_kses_post( wp_trim_words( $description, 20, '&hellip;' ) ); ?></p>
			<?php endif;
		endif; ?>

		<?php if ( $show_contact && ( $phone || $email || $address ) ) : ?>
			<div class="rtcl-store-contact">
				<?php if ( $phone ) : ?>
					<div class="rtcl-store-contact-item">
						<span class="rtcl-contact-icon"><i class="rtcl-icon rtcl-icon-phone"></i></span>
						<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<div class="rtcl-store-contact-item">
						<span class="rtcl-contact-icon"><i class="rtcl-icon rtcl-icon-envelope-open-o"></i></span>
						<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<div class="rtcl-store-contact-item">
						<span class="rtcl-contact-icon"><i class="rtcl-icon rtcl-icon-location"></i></span>
						<span><?php echo esc_html( $address ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_social && ! empty( $social ) ) : ?>
			<div class="rtcl-store-social-links">
				<?php foreach ( $social as $key => $url ) :
					if ( empty( $url ) ) continue;
					$icon_class = 'twitter' === $key ? 'fa-brands fa-x-twitter' : 'rtcl-icon rtcl-icon-' . $key;
					?>
					<a class="rtcl-social-link <?php echo esc_attr( $key ); ?>"
					   href="<?php echo esc_url( $url ); ?>"
					   target="_blank"
					   rel="nofollow noopener"
					   aria-label="<?php echo esc_attr( $key ); ?>">
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
