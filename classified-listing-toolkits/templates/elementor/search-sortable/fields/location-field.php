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
 * @var    $orderby
 * @var    $order
 * @var    $field_Label
 * @var $repeater_id
 * @var $field_Label
 * @var $placeholder *
 *
 */

use Rtcl\Helpers\Text;
use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options;

if ( ! empty( $placeholder ) ) {
    $locationText = $placeholder;
} else {
    $locationText = Text::get_select_location_text();
}

$geo_location = 'geo' === Functions::location_type();
if ( $geo_location ) {
    $rs_data = Options::radius_search_options();
    ?>
    <div class="rtcl-form-group ws-item ws-location rtcl-geo-address-field elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
        <?php if ( $controllers['fields_label'] ) { ?>
            <label class="rtcl-from-label" for="rtc-geo-search"><?php echo esc_html( $field_Label ); ?></label>
        <?php } else { ?>
            <label class="screen-reader-text" for="rtc-geo-search"><?php echo esc_html( $locationText ); ?></label>
        <?php } ?>
        <div class="rtc-geo-search-wrapper">
            <input id='rtc-geo-search' type="text" name="geo_address" autocomplete="off" value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( sanitize_text_field( $_GET['geo_address'] ) ) : ''; //phpcs:ignore?>" placeholder="<?php esc_html_e( 'Select a location', 'classified-listing-toolkits' ); ?>" class="rtcl-form-control rtcl-geo-address-input"/>
            <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
            <input type="hidden" class="latitude" name="center_lat" value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['center_lat'] ) ) ) : ''; ?>">
            <input type="hidden" class="longitude" name="center_lng" value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['center_lng'] ) ) ) : ''; //phpcs:ignore?>">
        </div>
    </div>
    <?php if ( ! empty( $field['geo_location_range'] ) ) { ?>
        <div class=" rtcl-form-group ws-item ws-location rtcl-range-slider-field elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
            <?php
            /* translators: %s: unit of measurement */
            $radius_placeholder = sprintf( __( 'Radius (%1$s)', 'classified-listing-toolkits' ), isset( $rs_data['units'] ) ? $rs_data['units'] : '' );

            ?>

            <?php if ( $controllers['fields_label'] ) { ?>
                <label class="rtcl-from-label" for="rtc-geo-search"><?php echo esc_html( $radius_placeholder ); ?></label>
            <?php } else { ?>
                <label class="screen-reader-text" for="rtc-geo-search"><?php echo esc_html( $locationText ); ?></label>
            <?php } ?>

            <input type="number" class="rtcl-form-control-range rtcl-range-slider-input rtcl-form-control" title='<?php echo esc_attr( $radius_placeholder ); ?>' placeholder="<?php echo esc_attr( $radius_placeholder ); ?>" name="distance" max="<?php echo absint( $rs_data['max_distance'] ); ?>" value="<?php echo absint( isset( $_GET['distance'] ) ? sanitize_text_field( $_GET['distance'] ) : $rs_data['default_distance'] ); //phpcs:ignore?>">
        </div>
    <?php } ?>
<?php } elseif ( 'local' === Functions::location_type() ) { ?>
    <div class="rtcl-form-group ws-item ws-location rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
        <?php if ( $controllers['fields_label'] ) { ?>
            <label class="rtcl-from-label" for="rtcl-location-search-<?php echo esc_attr( $id ); ?>"> <?php echo esc_html( $field_Label ); ?> </label>
        <?php } else { ?>
            <label class="screen-reader-text" for="rtcl-location-search-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $locationText ); ?></label>
        <?php } ?>
        <?php if ( $style === 'suggestion' ) { ?>
            <div class="location-field-wrapper">
                <input type="text" id="rtcl-location-search-<?php echo esc_attr( $id ); ?>" data-type="location" class="rtcl-autocomplete rtcl-location rtcl-form-control" placeholder="<?php echo esc_html( $locationText ); ?>" value="<?php echo esc_attr( $selected_location ? $selected_location->name : '' ); ?>">
                <input type="hidden" name="rtcl_location" value="<?php echo esc_attr( $selected_location ? $selected_location->slug : '' ); ?>">
            </div>
            <?php
        } elseif ( $style === 'standard' ) {
            $location_args = [
                    'show_option_none'  => $locationText,
                    'option_none_value' => '',
                    'taxonomy'          => rtcl()->location,
                    'name'              => 'rtcl_location',
                    'id'                => 'rtcl-location-search-' . $id,
                    'class'             => 'rtcl-form-control rtcl-location-search',
                    'selected'          => get_query_var( 'rtcl_location' ),
                    'hierarchical'      => true,
                    'value_field'       => 'slug',
                    'depth'             => Functions::get_location_depth_limit(),
                    'orderby'           => $orderby,
                    'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
                    'show_count'        => false,
                    'hide_empty'        => false,
            ];
            if ( '_rtcl_order' === $orderby ) {
                $location_args['orderby']  = 'meta_value_num';
                $location_args['meta_key'] = '_rtcl_order';
            }
            wp_dropdown_categories( $location_args );
        } elseif ( $style === 'dependency' ) {
            Functions::dropdown_terms(
                    [
                            'show_option_none' => $locationText,
                            'taxonomy'         => rtcl()->location,
                            'name'             => 'l',
                            'id'               => 'rtcl-location-search-' . $id,
                            'class'            => 'rtcl-form-control',
                            'selected'         => $selected_location ? $selected_location->term_id : 0,
                    ],
            );
        } elseif ( $style == 'popup' ) {
            ?>
            <div class="rtcl-search-input-button rtcl-form-control rtcl-search-input-location elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
				<span class="search-input-label location-name">
					<?php echo $selected_location ? esc_html( $selected_location->name ) : esc_html( $locationText ); ?>
				</span>
                <input type="hidden" id="rtcl-location-search-<?php echo esc_attr( $id ); ?>" class="rtcl-term-field" name="rtcl_location" value="<?php echo $selected_location ? esc_attr( $selected_location->slug ) : ''; ?>">
            </div>
        <?php } ?>
    </div>
<?php } ?>
