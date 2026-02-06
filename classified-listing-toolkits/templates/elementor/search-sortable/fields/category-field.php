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
 * @var $repeater_id
 * @var $field_Label
 * @var $placeholder
 */

use Rtcl\Helpers\Text;
use Rtcl\Helpers\Functions;

if ( ! empty( $placeholder ) ) {
    $categoryText = $placeholder;
} else {
    $categoryText = Text::get_select_category_text();
}

?>
<div class="rtcl-form-group rtcl-flex rtcl-flex-column ws-item ws-category ws-category-<?php echo esc_attr( $style ); ?> elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
    <?php if ( $controllers['fields_label'] ) { ?>
        <label for="rtcl-category-search-<?php echo esc_attr( $id ); ?>" class="rtcl-from-label"><?php echo esc_html( $field_Label ); ?></label>
    <?php } else { ?>
        <label for="rtcl-category-search-<?php echo esc_attr( $id ); ?>" class="screen-reader-text"><?php echo esc_html( $field_Label ); ?></label>
    <?php } ?>
    <?php
    if ( $style === 'standard' || $style === 'suggestion' ) {
        $cat_args = [
                'show_option_none'  => $categoryText,
                'option_none_value' => '',
                'taxonomy'          => rtcl()->category,
                'name'              => 'rtcl_category',
                'id'                => 'rtcl-category-search-' . $id,
                'class'             => 'rtcl-form-control rtcl-category-search',
                'selected'          => get_query_var( 'rtcl_category' ),
                'hierarchical'      => true,
                'value_field'       => 'slug',
                'depth'             => Functions::get_category_depth_limit(),
                'orderby'           => $orderby,
                'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
                'show_count'        => false,
                'hide_empty'        => false,
        ];
        if ( '_rtcl_order' === $orderby ) {
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = '_rtcl_order';
        }
        wp_dropdown_categories( $cat_args );
    } elseif ( $style === 'dependency' ) {
        Functions::dropdown_terms(
                [
                        'show_option_none'  => $categoryText,
                        'option_none_value' => - 1,
                        'taxonomy'          => rtcl()->category,
                        'name'              => 'c',
                        'id'                => 'rtcl-category-search-' . $id,
                        'class'             => 'rtcl-form-control rtcl-category-search',
                        'selected'          => $selected_category ? $selected_category->term_id : 0,
                ],
        );
    } elseif ( $style == 'popup' ) {
        ?>
        <div class="rtcl-search-input-button rtcl-form-control  rtcl-search-input-category ">
			<span class="search-input-label category-name">
				<?php echo $selected_category ? esc_html( $selected_category->name ) : esc_html( $categoryText ); ?>
			</span>
            <input type="hidden" name="rtcl_category" id="rtcl-category-search-<?php echo esc_attr( $id ); ?>" class="rtcl-term-field" value="<?php echo $selected_category ? esc_attr( $selected_category->slug ) : ''; ?>">
        </div>
    <?php } ?>
</div>