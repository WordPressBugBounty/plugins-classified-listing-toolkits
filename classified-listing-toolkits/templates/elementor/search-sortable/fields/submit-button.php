<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var number  $id    Random id
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
 * @var bool    $controllers
 * @var bool    $widget_base
 *
 */
use \Elementor\Icons_Manager;
?>
<div class="rtcl-form-group ws-item ws-button rtcl-action-buttons rtcl-flex rtcl-flex-column">
	<?php if( $controllers['fields_label'] ){ ?>
		<label class="rtcl-from-label" style="visibility: hidden; opacity: 0;"> Submit </label>
	<?php } ?>
	<?php
		ob_start();
			if( ! empty( $controllers['button_icon'] )){
				echo '<span class="icon-wrapper">';
				Icons_Manager::render_icon( $controllers['button_icon'], array( 'aria-hidden' => 'true' ) );
				echo '</span>';
			}
		$button_icon = ob_get_clean();
	?>
	<button type="submit" class="rtcl-btn btn-primary">
		<?php if( ! empty( $controllers['button_icon_alignment'] ) && 'left' === $controllers['button_icon_alignment'] ){
			echo $button_icon; //phpcs:ignore
		} ?>
		<?php if(!empty($controllers['button_text'])){ 
				echo esc_html($controllers['button_text']);
		 } ?>
		<?php if( ! empty( $controllers['button_icon_alignment'] ) && 'right' === $controllers['button_icon_alignment'] ){
			echo $button_icon; //phpcs:ignore
		} ?>
	</button>
</div>