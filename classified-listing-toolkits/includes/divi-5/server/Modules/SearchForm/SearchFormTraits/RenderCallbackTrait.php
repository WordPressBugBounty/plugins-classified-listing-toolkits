<?php
/**
 * Render Callback Trait for Search Form.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\Packages\Module\Module;
use Rtcl\Helpers\Functions;

trait RTCL_Divi5_SearchForm_RenderCallbackTrait {

	/**
	 * Render callback for the module.
	 *
	 * @param array  $attrs    Module attributes.
	 * @param string $content  Module content.
	 * @param object $block    Block object.
	 * @param object $elements Elements object.
	 *
	 * @return string Rendered HTML.
	 */
	public static function render_callback( $attrs, $content, $block, $elements ) {
		// Extract settings from attributes.
		$settings = self::get_settings_from_attrs( $attrs );

		// Build the inner content.
		$inner_content = self::render_search_form( $settings );

		$parent = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );

		return Module::render(
			[
				// FE only.
				'orderIndex'          => $block->parsed_block['orderIndex'],
				'storeInstance'       => $block->parsed_block['storeInstance'],

				// VB equivalent.
				'attrs'               => $attrs,
				'elements'            => $elements,
				'id'                  => $block->parsed_block['id'],
				'moduleClassName'     => 'rtcl-search-form-module',
				'name'                => $block->block_type->name,
				'classnamesFunction'  => [ RTCL_Divi5_SearchForm::class, 'module_classnames' ],
				'moduleCategory'      => $block->block_type->category,
				'stylesComponent'     => [ RTCL_Divi5_SearchForm::class, 'module_styles' ],
				'scriptDataComponent' => [ RTCL_Divi5_SearchForm::class, 'module_script_data' ],
				'parentAttrs'         => $parent->attrs ?? [],
				'parentId'            => $parent->id ?? '',
				'parentName'          => $parent->blockName ?? '',
				'children'            => $elements->style_components(
					[
						'attrName' => 'module',
					]
				) . $inner_content,
			]
		);
	}

	/**
	 * Extract settings from Divi 5 attributes structure.
	 *
	 * @param array $attrs Module attributes.
	 * @return array Settings array.
	 */
	private static function get_settings_from_attrs( $attrs ) {
		return [
			'style'               => $attrs['style']['innerContent']['desktop']['value'] ?? 'standard',
			'orientation'         => $attrs['orientation']['innerContent']['desktop']['value'] ?? 'horizontal',
			'showFieldLabel'      => $attrs['showFieldLabel']['innerContent']['desktop']['value'] ?? 'on',
			'showKeyword'         => $attrs['showKeyword']['innerContent']['desktop']['value'] ?? 'on',
			'showCategory'        => $attrs['showCategory']['innerContent']['desktop']['value'] ?? 'on',
			'showLocation'        => $attrs['showLocation']['innerContent']['desktop']['value'] ?? 'on',
			'showAdType'          => $attrs['showAdType']['innerContent']['desktop']['value'] ?? 'off',
			'showPriceRange'      => $attrs['showPriceRange']['innerContent']['desktop']['value'] ?? 'off',
			'buttonText'          => $attrs['buttonText']['innerContent']['desktop']['value'] ?? 'Search',
			'keywordPlaceholder'  => $attrs['keywordPlaceholder']['innerContent']['desktop']['value'] ?? 'What are you looking for?',
			'categoryPlaceholder' => $attrs['categoryPlaceholder']['innerContent']['desktop']['value'] ?? 'Select Category',
			'locationPlaceholder' => $attrs['locationPlaceholder']['innerContent']['desktop']['value'] ?? 'Select Location',
			'minPricePlaceholder' => $attrs['minPricePlaceholder']['innerContent']['desktop']['value'] ?? 'Min Price',
			'maxPricePlaceholder' => $attrs['maxPricePlaceholder']['innerContent']['desktop']['value'] ?? 'Max Price',
		];
	}

	/**
	 * Render search form HTML.
	 *
	 * Uses the same HTML structure as the Visual Builder edit component
	 * to ensure consistent rendering between VB and frontend.
	 *
	 * @param array $settings Module settings.
	 * @return string
	 */
	private static function render_search_form( $settings ) {
		$orientation       = $settings['orientation'] ?? 'horizontal';
		$style             = $settings['style'] ?? 'standard';
		$orientation_class = 'horizontal' === $orientation ? 'rtcl-search-horizontal' : 'rtcl-search-vertical';
		$style_class       = 'rtcl-style-' . $style;

		$show_label = 'on' === ( $settings['showFieldLabel'] ?? 'on' );

		$orderby = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
		$order   = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'ASC' ) );

		// Get pre-selected values from URL query.
		$selected_location = false;
		$selected_category = false;

		if ( get_query_var( 'rtcl_location' ) && $location = get_term_by( 'slug', get_query_var( 'rtcl_location' ), rtcl()->location ) ) {
			$selected_location = $location;
		}
		if ( empty( $selected_location ) && get_query_var( '__loc' ) && $location = get_term_by( 'slug', get_query_var( '__loc' ), rtcl()->location ) ) {
			$selected_location = $location;
		}

		if ( get_query_var( 'rtcl_category' ) && $category = get_term_by( 'slug', get_query_var( 'rtcl_category' ), rtcl()->category ) ) {
			$selected_category = $category;
		}
		if ( empty( $selected_category ) && get_query_var( '__cat' ) && $category = get_term_by( 'slug', get_query_var( '__cat' ), rtcl()->category ) ) {
			$selected_category = $category;
		}

		ob_start();
		?>
		<div class="rtcl rtcl-search rtcl-search-form-wrapper rtcl-widget-search-form rtcl-search-style-<?php echo esc_attr( $style ); ?> <?php echo esc_attr( $orientation_class ); ?> <?php echo esc_attr( $style_class ); ?>">
			<form class="rtcl-search-form" method="get" action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>">
				<div class="rtcl-search-fields">

					<?php if ( 'on' === ( $settings['showAdType'] ?? 'off' ) ) : ?>
						<?php
						$listing_types = Functions::get_listing_types();
						$listing_types = empty( $listing_types ) ? [] : $listing_types;
						?>
						<?php if ( ! empty( $listing_types ) ) : ?>
							<div class="rtcl-search-field rtcl-search-ad-type">
								<?php if ( $show_label ) : ?><label class="rtcl-field-label"><?php esc_html_e( 'Ad Type', 'classified-listing-toolkits' ); ?></label><?php endif; ?>
								<select class="rtcl-search-select" name="filters[ad_type]">
									<option value=""><?php esc_html_e( 'Select Type', 'classified-listing-toolkits' ); ?></option>
									<?php foreach ( $listing_types as $key => $listing_type ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>"<?php echo isset( $_GET['filters']['ad_type'] ) && trim( sanitize_text_field( wp_unslash( $_GET['filters']['ad_type'] ) ) ) == $key ? ' selected' : ''; // phpcs:ignore ?>><?php echo esc_html( $listing_type ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( 'on' === $settings['showKeyword'] ) : ?>
						<div class="rtcl-search-field rtcl-search-keyword">
							<?php if ( $show_label ) : ?><label class="rtcl-field-label"><?php esc_html_e( 'Keyword', 'classified-listing-toolkits' ); ?></label><?php endif; ?>
							<input type="text" name="q" class="rtcl-search-input" placeholder="<?php echo esc_attr( $settings['keywordPlaceholder'] ); ?>" value="<?php echo isset( $_GET['q'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['q'] ) ) ) : ''; // phpcs:ignore ?>" />
						</div>
					<?php endif; ?>

					<?php if ( 'on' === $settings['showCategory'] ) : ?>
						<div class="rtcl-search-field rtcl-search-category">
							<?php if ( $show_label ) : ?><label class="rtcl-field-label"><?php esc_html_e( 'Category', 'classified-listing-toolkits' ); ?></label><?php endif; ?>
							<?php if ( 'popup' === $style ) : ?>
								<div class="rtcl-search-input-button rtcl-search-input-category rtcl-form-control">
									<span class="search-input-label category-name">
										<?php echo $selected_category ? esc_html( $selected_category->name ) : esc_html( $settings['categoryPlaceholder'] ); ?>
									</span>
									<input type="hidden" class="rtcl-term-field" name="rtcl_category"
										   value="<?php echo $selected_category ? esc_attr( $selected_category->slug ) : ''; ?>">
								</div>
							<?php elseif ( 'dependency' === $style ) : ?>
								<div class="rtcl-search-input-button rtcl-search-category">
									<?php
									Functions::dropdown_terms( [
										'show_option_none'  => $settings['categoryPlaceholder'],
										'option_none_value' => -1,
										'taxonomy'          => rtcl()->category,
										'name'              => 'c',
										'class'             => 'rtcl-form-control rtcl-category-search',
										'selected'          => $selected_category ? $selected_category->term_id : 0,
									] );
									?>
								</div>
							<?php else : ?>
								<?php
								$cat_args = [
									'show_option_none'  => $settings['categoryPlaceholder'],
									'option_none_value' => '',
									'taxonomy'          => rtcl()->category,
									'name'              => 'rtcl_category',
									'id'                => 'rtcl-category-search-' . wp_rand(),
									'class'             => 'rtcl-search-select',
									'selected'          => $selected_category ? $selected_category->slug : '',
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_category_depth_limit(),
									'orderby'           => $orderby,
									'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
									'show_count'        => false,
									'hide_empty'        => false,
								];
								wp_dropdown_categories( $cat_args );
								?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( 'on' === $settings['showLocation'] ) : ?>
						<div class="rtcl-search-field rtcl-search-location">
							<?php if ( $show_label ) : ?><label class="rtcl-field-label"><?php esc_html_e( 'Location', 'classified-listing-toolkits' ); ?></label><?php endif; ?>
							<?php if ( 'popup' === $style ) : ?>
								<div class="rtcl-search-input-button rtcl-search-input-location rtcl-form-control">
									<span class="search-input-label location-name">
										<?php echo $selected_location ? esc_html( $selected_location->name ) : esc_html( $settings['locationPlaceholder'] ); ?>
									</span>
									<input type="hidden" class="rtcl-term-field" name="rtcl_location"
										   value="<?php echo $selected_location ? esc_attr( $selected_location->slug ) : ''; ?>">
								</div>
							<?php elseif ( 'dependency' === $style ) : ?>
								<div class="rtcl-search-input-button rtcl-search-location">
									<?php
									Functions::dropdown_terms( [
										'show_option_none' => $settings['locationPlaceholder'],
										'taxonomy'         => rtcl()->location,
										'name'             => 'l',
										'class'            => 'rtcl-form-control',
										'selected'         => $selected_location ? $selected_location->term_id : 0,
									] );
									?>
								</div>
							<?php else : ?>
								<?php
								$loc_args = [
									'show_option_none'  => $settings['locationPlaceholder'],
									'option_none_value' => '',
									'taxonomy'          => rtcl()->location,
									'name'              => 'rtcl_location',
									'id'                => 'rtcl-location-search-' . wp_rand(),
									'class'             => 'rtcl-search-select',
									'selected'          => $selected_location ? $selected_location->slug : '',
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_location_depth_limit(),
									'orderby'           => $orderby,
									'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
									'show_count'        => false,
									'hide_empty'        => false,
								];
								wp_dropdown_categories( $loc_args );
								?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( 'on' === $settings['showPriceRange'] ) : ?>
						<div class="rtcl-search-field rtcl-search-price-range rtcl-price-range">
							<?php if ( $show_label ) : ?><label class="rtcl-field-label"><?php esc_html_e( 'Price Range', 'classified-listing-toolkits' ); ?></label><?php endif; ?>
							<div class="rtcl-price-inputs">
								<input type="number" name="filters[price][min]" class="rtcl-search-input rtcl-price-min" placeholder="<?php echo esc_attr( $settings['minPricePlaceholder'] ); ?>" value="<?php echo isset( $_GET['filters']['price']['min'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['filters']['price']['min'] ) ) ) : ''; // phpcs:ignore ?>" />
								<input type="number" name="filters[price][max]" class="rtcl-search-input rtcl-price-max" placeholder="<?php echo esc_attr( $settings['maxPricePlaceholder'] ); ?>" value="<?php echo isset( $_GET['filters']['price']['max'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['filters']['price']['max'] ) ) ) : ''; // phpcs:ignore ?>" />
							</div>
						</div>
					<?php endif; ?>

					<div class="rtcl-search-field rtcl-search-button">
						<?php if ( $show_label ) : ?><label class="rtcl-field-label">&nbsp;</label><?php endif; ?>
						<button type="submit" class="rtcl-search-submit">
							<?php echo esc_html( $settings['buttonText'] ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

}
