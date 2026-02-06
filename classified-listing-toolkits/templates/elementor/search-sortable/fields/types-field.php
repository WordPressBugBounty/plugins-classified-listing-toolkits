<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * @var number $id Random id
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
 * @var bool $controllers
 * @var bool $widget_base
 * @var $repeater_id
 * @var $field_Label
 * @var $placeholder
 */

use Rtcl\Helpers\Functions;

if ( ! empty( $placeholder ) ) {
    $typeText = $placeholder;
} else {
    $typeText = esc_html__( 'Select type', 'classified-listing-toolkits' );
}

?>
<div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
    <?php if ( $controllers['fields_label'] ) { ?>
        <label class="rtcl-from-label" for="rtcl-search-type-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field_Label ); ?></label>
    <?php } else { ?>
        <label class="screen-reader-text" for="rtcl-search-type-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field_Label ); ?></label>
    <?php } ?>
    <div class="rtcl-search-type">
        <select class="rtcl-form-control" id="rtcl-search-type-<?php echo esc_attr( $id ); ?>" name="filters[ad_type]">
            <option value=""><?php echo esc_html( $typeText ); ?></option>
            <?php
            $listing_types
                    = Functions::get_listing_types();
            if ( ! empty( $listing_types ) ) {
                foreach ( $listing_types as $key => $listing_type ) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $_GET['filters']['ad_type'] ) && trim( $_GET['filters']['ad_type'] ) == $key ? ' selected' : null; //phpcs:ignore?>><?php echo esc_html( $listing_type ); ?></option>
                <?php }
            }
            ?>
        </select>
    </div>
</div>