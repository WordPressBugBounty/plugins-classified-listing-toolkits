<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var number  $id    Random id
 * @var         $settings
 * @var         $widget_base
 * @var         $orientation
 * @var         $style [classic , modern]
 * @var array   $classes
 * @var int     $active_count
 * @var WP_Term $selected_location
 * @var WP_Term $selected_category
 * @var bool    $radius_search
 * @var bool    $can_search_by_location
 * @var bool    $can_search_by_category
 * @var array   $data
 * @var bool    $can_search_by_listing_types
 * @var bool    $can_search_by_price
 */

/* phpcs:disable WordPress.Security.NonceVerification.Recommended */

use Rtcl\Helpers\Functions;

?>

<?php if ( $settings['keyword_field'] ) : ?>
	<div class="rtcl-form-group ws-item ws-text rtcl-col-sm-6">
		<div class="rt-autocomplete-wrapper">
			<?php
			$keywords = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( ( $_GET['q'] ) ) ) : ''; //phpcs:ignore
			?>
			<?php if ( $settings['fields_label'] ) { ?>
				<label> <?php esc_html_e( 'Keyword', 'classified-listing-toolkits' ); ?></label>
			<?php } ?>
			<div class="keywords-field-wrapper">
				<input type="text" name="q" data-type="listing" class="rtcl-autocomplete rtcl-form-control" placeholder="<?php esc_attr_e( 'Enter your keyword here ...', 'classified-listing-toolkits' ); ?>" value="<?php echo esc_attr( $keywords ); ?>">
			</div>
		</div>
	</div>
<?php endif; ?>