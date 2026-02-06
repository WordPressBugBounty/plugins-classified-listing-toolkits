<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
/**
 * @var number $id Random id
 * @var         $settings
 * @var         $widget_base
 * @var         $orientation
 * @var         $style [classic , modern]
 * @var array $classes
 * @var int $active_count
 * @var WP_Term $selected_location
 * @var WP_Term $selected_category
 * @var bool $radius_search
 * @var bool $can_search_by_location
 * @var bool $can_search_by_category
 * @var array $data
 * @var bool $can_search_by_listing_types
 * @var bool $can_search_by_price
 */

/* phpcs:disable WordPress.Security.NonceVerification.Recommended */

use Rtcl\Helpers\Functions;

?>

<?php if ( $settings['types_field'] ) : ?>
    <div class="rtcl-form-group ws-item ws-type rtcl-col-sm-6 rtcl-col-12">
        <?php if ( $settings['fields_label'] ) { ?>
            <label for="rtcl-search-type-<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Type', 'classified-listing-toolkits' ); ?></label>
        <?php } else { ?>
            <label class="screen-reader-text" for="rtcl-search-type-<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Type', 'classified-listing-toolkits' ); ?></label>
        <?php } ?>
        <select class="rtcl-form-control" id="rtcl-search-type-<?php echo esc_attr( $id ); ?>" name="filters[ad_type]">
            <option value=""><?php esc_html_e( 'Select type', 'classified-listing-toolkits' ); ?></option>
            <?php
            $listing_types = Functions::get_listing_types();
            if ( ! empty( $listing_types ) ) {
                foreach ( $listing_types as $key => $listing_type ) {
                    $selected_ad_type = isset( $_GET['filters']['ad_type'] ) ? sanitize_text_field( wp_unslash( $_GET['filters']['ad_type'] ) ) : '';

                    ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $_GET['filters']['ad_type'] ) && trim( $selected_ad_type ) == $key ? ' selected' : null; //phpcs:ignore?>><?php echo esc_html( $listing_type ); ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
<?php endif; ?>