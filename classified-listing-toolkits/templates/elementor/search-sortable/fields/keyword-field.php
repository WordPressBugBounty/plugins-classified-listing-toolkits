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
 *
 */

use Rtcl\Helpers\Functions;

$keyword_id = 'rtcl-search-keyword-' . $id;
?>
<div class="rtcl-form-group rt-autocomplete-wrapper rtcl-flex rtcl-flex-column elementor-repeater-item-<?php
echo esc_attr( $repeater_id ); ?>">
    <?php
    $keywords = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( ( $_GET['q'] ) ) ) : '';    //phpcs:ignore?>
    <?php if ( $controllers['fields_label'] ) { ?>
        <label for="<?php echo esc_attr( $keyword_id ); ?>" class="rtcl-from-label"><?php echo esc_html( $field_Label ); ?></label>
    <?php } ?>
    <?php
    $search_class = 'keywords-field-wrapper';
    if ( Functions::is_semantic_search_enabled() ) {
        $search_class .= ' rtcl-ai-search-field';
    }
    ?>
    <div class="<?php echo esc_attr( $search_class ); ?>">
        <input type="text" id="<?php echo esc_attr( $keyword_id ); ?>" name="q" data-type="listing" class="rtcl-autocomplete rtcl-form-control" placeholder="<?php
        echo esc_html( $placeholder ); ?>" value="<?php echo esc_html( $keywords ); ?>" aria-label="<?php echo esc_attr( $field_Label ); ?>">
    </div>
</div>