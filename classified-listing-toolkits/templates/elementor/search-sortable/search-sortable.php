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
 * @var    $orderby
 * @var    $order
 */

use Rtcl\Helpers\Text;
use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options;

$orderby   = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
$order     = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'DESC' ) );
$classes[] = 'rtcl-elementor-widget-search-sortable';

$data = array(
	'template'              => 'elementor/search-sortable/fields',
	'id'                    => $id,
	'controllers'           => $controllers,
	'style'                 => $style,
	'orientation'           => $orientation,
	'selected_category'     => $selected_category,
	'selected_location'     => $selected_location,
	'default_template_path' => \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path(),
);

$data = apply_filters( 'rtcl/elementor/search/data/' . $widget_base, $data );

?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
    <form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>" class=" rtcl-widget-search-form rtcl">
        <div class="rtcl-widget-search-sortable-wrapper rtcl-flex <?php echo 'inline' !== $orientation ? 'rtcl-flex-column' : ''; ?>">
			<?php
			if( ! empty( $controllers['sortable_form'] ) ){
				foreach ( $controllers['sortable_form'] as $field ) {

					$template = $data['template'] . '/' . str_replace("_","-",$field['sortable_form_fields']);

					$_data = array_merge( $data, [
						'repeater_id'    => $field['_id'],
						'template'    => $template,
						'field_Label' => $field['sortable_form_field_Label'] ?? null ,
						'placeholder' => $field['sortable_form_field_placeholder'] ?? null,
						'select_text' => $field['sortable_form_field_select_text'] ?? null,
						'orderby' => $orderby,
						'order' => $order,
						'field' => $field
					] );
					Functions::get_template( $template, $_data, '', $_data['default_template_path'] );
				}
			}
			$template = $data['template'] . '/submit-button';
			Functions::get_template( $template, $data, '', $data['default_template_path'] );
			?>
        </div>
    </form>
</div>
