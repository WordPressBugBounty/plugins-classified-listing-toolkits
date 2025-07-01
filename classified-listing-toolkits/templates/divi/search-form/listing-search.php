<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var array  $settings
 * @var string $style
 * @var string $orientation
 */

use Rtcl\Helpers\Functions;

$loc_class    = 'rtcl-loc-space';
$radius_class = 'rtcl-radius-space';
$type_class   = 'rtcl-type-space';
$cat_class    = 'rtcl-cat-space';
$price_class  = 'rtcl-price-space';
$key_class    = 'rtcl-key-space';
$btn_class    = 'rtcl-btn-holder';

$loc_text            = apply_filters( 'rtcl_divi_listing_search_location_title', esc_html__( 'Select Location', 'classified-listing-toolkits' ) );
$cat_text            = apply_filters( 'rtcl_divi_listing_search_category_title', esc_html__( 'Select Category', 'classified-listing-toolkits' ) );
$typ_text            = apply_filters( 'rtcl_divi_listing_search_ad_type_title', esc_html__( 'Select Type', 'classified-listing-toolkits' ) );
$keyword_placeholder = apply_filters( 'rtcl_divi_listing_search_keyword_title', esc_html__( 'Enter Keyword here ...', 'classified-listing-toolkits' ) );

$selected_location = $selected_category = false;

if ( get_query_var( '__loc' ) && $location = get_term_by( 'slug', get_query_var( '__loc' ), rtcl()->location ) ) {
	$selected_location = $location;
}

if ( empty( $selected_location ) ) {
	if ( get_query_var( 'rtcl_location' ) && $location = get_term_by( 'slug', get_query_var( 'rtcl_location' ), rtcl()->location ) ) {
		$selected_location = $location;
	}
}

if ( get_query_var( '__cat' ) && $category = get_term_by( 'slug', get_query_var( '__cat' ), rtcl()->category ) ) {
	$selected_category = $category;
}

if ( empty( $selected_category ) ) {
	if ( get_query_var( 'rtcl_category' ) && $category = get_term_by( 'slug', get_query_var( 'rtcl_category' ), rtcl()->category ) ) {
		$selected_category = $category;
	}
}

$orderby = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
$order   = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'ASC' ) );

$style       = $style ?? 'dependency';
$orientation = $orientation ?? 'inline';
?>
<div class="rtcl rtcl-search rtcl-divi-listing-search rtcl-search-style-<?php echo esc_attr( $style ); ?>">
    <form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>"
          class="rtcl-widget-search-form rtcl-search-<?php echo esc_attr( $orientation ); ?>">
		<?php if ( 'on' === $settings['types_field'] ): ?>
            <div class="<?php echo esc_attr( $type_class ); ?>">
                <div class="form-group">
					<?php if ( 'on' === $settings['fields_label'] ) { ?>
                        <label for="rtcl-search-ad-type"><?php esc_html_e( 'Ad Type', 'classified-listing-toolkits' ); ?></label>
					<?php } ?>
                    <div class="rtcl-search-input-button rtcl-search-input-type">
						<?php
						$listing_types = Functions::get_listing_types();
						$listing_types = empty( $listing_types ) ? array() : $listing_types;
						?>
                        <select class="rtcl-form-control" id="rtcl-search-ad-type" name="filters[ad_type]">
                            <option value=""><?php esc_html_e( 'Select Type', 'classified-listing-toolkits' ); ?></option>
							<?php
							if ( ! empty( $listing_types ) ) {
								foreach ( $listing_types as $key => $listing_type ) {
									?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $_GET['filters']['ad_type'] ) && trim( sanitize_text_field(wp_unslash($_GET['filters']['ad_type'])) ) == $key ? ' selected' : null; //phpcs:ignore?>><?php echo esc_html( $listing_type ); ?></option>
									<?php
								}
							}
							?>
                        </select>
                    </div>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( 'on' === $settings['location_field'] ): ?>
			<?php if ( 'local' === Functions::location_type() ): ?>
                <div class="<?php echo esc_attr( $loc_class ); ?>">
                    <div class="form-group">
						<?php if ( 'on' === $settings['fields_label'] ) { ?>
                            <label><?php esc_html_e( 'Location', 'classified-listing-toolkits' ); ?></label>
						<?php } ?>
						<?php if ( $style === 'suggestion' ): ?>
                            <div class="rtcl-search-input-button rtcl-search-location">
                                <input type="text" data-type="location" class="rtcl-autocomplete rtcl-location rtcl-form-control"
                                       placeholder="<?php echo esc_attr( $loc_text ); ?>"
                                       value="<?php echo $selected_location ? esc_attr( $selected_location->name ) : '' ?>">
                                <input type="hidden" name="rtcl_location"
                                       value="<?php echo $selected_location ? esc_attr( $selected_location->slug ) : '' ?>">
                            </div>
						<?php elseif ( $style === 'standard' ): ?>
                            <div class="rtcl-search-input-button rtcl-search-location">
								<?php
								$loc_args = array(
									'show_option_none'  => $loc_text,
									'option_none_value' => '',
									'taxonomy'          => rtcl()->location,
									'name'              => 'rtcl_location',
									'id'                => 'rtcl-location-search-' . wp_rand(),
									'class'             => 'rtcl-form-control rtcl-location-search',
									'selected'          => $selected_location ? $selected_location->slug : '',
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_location_depth_limit(),
									'orderby'           => $orderby,
									'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
									'show_count'        => false,
									'hide_empty'        => false,
								);
								if ( '_rtcl_order' === $orderby ) {
									$args['orderby']  = 'meta_value_num';
									$args['meta_key'] = '_rtcl_order';
								}
								wp_dropdown_categories( $loc_args );
								?>
                            </div>
						<?php elseif ( $style === 'dependency' ): ?>
                            <div class="rtcl-search-input-button rtcl-search-location">
								<?php
								Functions::dropdown_terms( array(
									'show_option_none' => $loc_text,
									'taxonomy'         => rtcl()->location,
									'name'             => 'l',
									'class'            => 'rtcl-form-control',
									'selected'         => $selected_location ? $selected_location->term_id : 0
								) );
								?>
                            </div>
						<?php else: ?>
                            <div class="rtcl-search-input-button rtcl-search-input-location">
                                <span class="search-input-label location-name rtcl-form-control">
                                    <?php echo $selected_location ? esc_html( $selected_location->name ) : esc_html( $loc_text ) ?>
                                </span>
                                <input type="hidden" class="rtcl-term-field" name="rtcl_location"
                                       value="<?php echo $selected_location ? esc_attr( $selected_location->slug ) : '' ?>">
                            </div>
						<?php endif; ?>
                    </div>
                </div>
			<?php else: ?>
                <div class="<?php echo esc_attr( $loc_class ); ?>">
                    <div class="form-group">
                        <div class="rtcl-search-input-button rtcl-search-location">
                            <input type="text" name="geo_address" autocomplete="off"
                                   value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( sanitize_text_field(wp_unslash($_GET['geo_address'])) ) : '' //phpcs:ignore?>"
                                   placeholder="<?php esc_html_e( "Select a location", 'classified-listing-toolkits' ); ?>"
                                   class="rtcl-form-control rtcl-geo-address-input"/>
                            <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                            <input type="hidden" class="latitude" name="center_lat"
                                   value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( sanitize_text_field(wp_unslash($_GET['center_lat'])) ) : '' //phpcs:ignore?>">
                            <input type="hidden" class="longitude" name="center_lng"
                                   value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( sanitize_text_field(wp_unslash($_GET['center_lng'])) ) : '' //phpcs:ignore?>">
                        </div>
                    </div>
                </div>
				<?php if ( 'on' === $settings['radius_field'] ): ?>
                    <div class="<?php echo esc_attr( $radius_class ); ?>">
                        <div class="form-group">
                            <div class="rtcl-search-input-button rtcl-search-radius">
                                <i class=""></i>
                                <input type="number" class="rtcl-form-control" name="distance"
                                       value="<?php echo ! empty( $_GET['distance'] ) ? absint( sanitize_text_field($_GET['distance']) ) : 30 //phpcs:ignore?>"
                                       placeholder="<?php esc_html_e( "Radius", 'classified-listing-toolkits' ); ?>">
                            </div>
                        </div>
                    </div>
				<?php else: ?>
                    <input type="hidden" class="distance" name="distance" value="30">
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( 'on' === $settings['category_field'] ): ?>
            <div class="<?php echo esc_attr( $cat_class ); ?>">
                <div class="form-group">
					<?php if ( 'on' === $settings['fields_label'] ) { ?>
                        <label><?php esc_html_e( 'Categories', 'classified-listing-toolkits' ); ?></label>
					<?php } ?>
					<?php if ( $style === 'suggestion' || $style === 'standard' ): ?>
                        <div class="rtcl-search-input-button rtcl-search-category">
							<?php
							$cat_args = array(
								'show_option_none'  => $cat_text,
								'option_none_value' => '',
								'taxonomy'          => rtcl()->category,
								'name'              => 'rtcl_category',
								'id'                => 'rtcl-category-search-' . wp_rand(),
								'class'             => 'rtcl-form-control rtcl-category-search',
								'selected'          => $selected_category ? esc_attr( $selected_category->slug ) : '',
								'hierarchical'      => true,
								'value_field'       => 'slug',
								'depth'             => Functions::get_category_depth_limit(),
								'orderby'           => $orderby,
								'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
								'show_count'        => false,
								'hide_empty'        => false,
							);
							if ( '_rtcl_order' === $orderby ) {
								$args['orderby']  = 'meta_value_num';
								$args['meta_key'] = '_rtcl_order';
							}
							wp_dropdown_categories( $cat_args );
							?>
                        </div>
					<?php elseif ( $style === 'dependency' ): ?>
                        <div class="rtcl-search-input-button rtcl-search-category">
							<?php
							Functions::dropdown_terms( array(
								'show_option_none'  => $cat_text,
								'option_none_value' => - 1,
								'taxonomy'          => rtcl()->category,
								'name'              => 'c',
								'class'             => 'rtcl-form-control rtcl-category-search',
								'selected'          => $selected_category ? $selected_category->term_id : 0
							) );
							?>
                        </div>
					<?php else: ?>
                        <div class="rtcl-search-input-button rtcl-search-input-category">
                            <span class="search-input-label category-name rtcl-form-control">
                                <?php echo $selected_category ? esc_html( $selected_category->name ) : esc_html( $cat_text ); ?>
                            </span>
                            <input type="hidden" name="rtcl_category" class="rtcl-term-field"
                                   value="<?php echo $selected_category ? esc_attr( $selected_category->slug ) : '' ?>">
                        </div>
					<?php endif; ?>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( 'on' === $settings['price_field'] ) : ?>
            <div class="<?php echo esc_attr( $price_class ); ?>">
                <div class="form-group">
					<?php if ( 'on' === $settings['fields_label'] ) { ?>
                        <label for="rtcl-search-price-min"><?php esc_html_e( 'Min Price', 'classified-listing-toolkits' ); ?></label>
					<?php } ?>
					<?php $min_price = isset( $_GET['filters']['price']['min'] ) ? sanitize_text_field(wp_unslash($_GET['filters']['price']['min'])) //phpcs:ignore
						: '';  ?>
                    <div class="rtcl-search-input-button">
                        <input id='rtcl-search-price-min' type="text" name="filters[price][min]" class="rtcl-form-control"
                               placeholder="<?php esc_attr_e( 'Min', 'classified-listing-toolkits' ); ?>" value="<?php echo esc_attr( $min_price ); ?>">
                    </div>
                </div>
            </div>
            <div class="<?php echo esc_attr( $price_class ); ?>">
                <div class="form-group">
					<?php if ( 'on' === $settings['fields_label'] ) { ?>
                        <label for="rtcl-search-price-max"><?php esc_html_e( 'Max Price', 'classified-listing-toolkits' ); ?></label>
					<?php } ?>
					<?php $max_price = isset( $_GET['filters']['price']['max'] ) ? sanitize_text_field(wp_unslash($_GET['filters']['price']['max'])) : ''; // phpcs:ignore ?>
                    <div class="rtcl-search-input-button">
                        <input id='rtcl-search-price-max' type="text" name="filters[price][max]" class="rtcl-form-control"
                               placeholder="<?php esc_attr_e( 'Max', 'classified-listing-toolkits' ); ?>" value="<?php echo esc_attr( $max_price ); ?>">
                    </div>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( 'on' === $settings['keyword_field'] ): ?>
            <div class="<?php echo esc_attr( $key_class ); ?>">
                <div class="form-group">
					<?php if ( 'on' === $settings['fields_label'] ) { ?>
                        <label for="rtcl-search-keyword-input"><?php esc_html_e( 'Keyword', 'classified-listing-toolkits' ); ?></label>
					<?php } ?>
                    <div class="rtcl-search-input-button rtcl-search-keyword">
                        <input type="text" id="rtcl-search-keyword-input" data-type="listing" name="q" class="rtcl-form-control rtcl-autocomplete"
                               placeholder="<?php echo esc_html( $keyword_placeholder ); ?>"
                               value="<?php if ( isset( $_GET['q'] ) ) { //phpcs:ignore
							       echo esc_attr( sanitize_text_field( wp_unslash( ( $_GET['q'] ) ) ) ); //phpcs:ignore
						       } ?>"/>
                    </div>
                </div>
            </div>
		<?php endif; ?>

        <div class="<?php echo esc_attr( $btn_class ); ?>">
			<?php if ( 'on' === $settings['fields_label'] ) { ?>
                <label><?php esc_html_e( 'Submit', 'classified-listing-toolkits' ); ?></label>
			<?php } ?>
            <button type="submit" class="btn rtcl-search-btn">
                <i class="fas fa-search" aria-hidden="true"></i><?php esc_html_e( 'Search', 'classified-listing-toolkits' ); ?>
            </button>
        </div>
    </form>
</div>