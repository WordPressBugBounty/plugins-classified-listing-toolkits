<?php
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
 * @var array   $field
 * @var bool    $can_search_by_listing_types
 * @var bool    $can_search_by_price
 * @var bool    $controllers
 * @var bool    $widget_base
 * @var         $repeater_id
 * @var         $field_Label
 * @var         $placeholder
 */

use Rtcl\Services\FormBuilder\FBHelper;

if ( ! isset( $field['sortable_form_field_from_fields'] ) ) {
	return;
}

$cfForm = $field['sortable_form_field_from_fields'];

if ( ! isset( $field[ 'sortable_form_field_custom_fields_' . $cfForm ] ) ) {
	return;
}

$cfField = $field[ 'sortable_form_field_custom_fields_' . $cfForm ];

$listingForm = FBHelper::getFormById( $cfForm );

$customField = null;

if ( isset( $listingForm ) && is_object( $listingForm ) && method_exists( $listingForm, 'getFieldByName' ) ) {
	$customField = $listingForm->getFieldByName( $cfField );
}

$fieldNameLabel = ucwords( str_replace( [ '-', '_' ], ' ', $cfField ) ); // Replace with space

if ( empty( $customField ) ) {
//	echo '<p class="notice" style="background-color: red; color: #fff; padding: 5px; margin: 0; height: 35px;">' . esc_html__( "Please select form & field.",
//			'classified-listing-pro' ) . '</p>';

	return;
}

if ( ! empty( $placeholder ) ) {
	$typeText = $placeholder;
} else {
	$typeText = esc_html__( 'Select' . ' ' . $fieldNameLabel, 'classified-listing-pro' );
}

if ( isset( $customField['element'] ) && in_array( $customField['element'], [ 'select', 'radio', 'checkbox' ] ) ) {
	$options = $customField['options'];

	foreach ( $options as $option ) {
		$items[ $option['value'] ] = esc_html( $option['label'] );
	}
	?>
	<div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
		<?php if ( $controllers['fields_label'] ) { ?>
			<label class="rtcl-from-label" for="rtcl-search-type-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field_Label ); ?></label>
		<?php } ?>
		<div class="rtcl-search-type">
			<select class="rtcl-form-control" id="rtcl-search-type-<?php echo esc_attr( $id ); ?>" name="cf_<?php echo esc_attr( $cfField ); ?>">
				<option value=""><?php echo esc_html( $typeText ); ?></option>
				<?php
				if ( ! empty( $items ) ) {
					foreach ( $items as $key => $value ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $_GET[ 'cf_' . $cfField ] )
																				   && trim( $_GET[ 'cf_' . $cfField ] ) == $key ? ' selected'
							: null; ?>><?php echo esc_html( $value ); ?></option>
					<?php }
				}
				?>
			</select>
		</div>
	</div>
<?php } ?>