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
//			'classified-listing-toolkits' ) . '</p>';

	return;
}




if ( isset( $customField['element'] ) && in_array( $customField['element'], [ 'select', 'radio', 'checkbox' ] ) ) {
	$options = $customField['options'];
    if ( ! empty( $placeholder ) ) {
        $typeText = $placeholder;
    } else {
        $firstChar = $fieldNameLabel ? explode( ' ', $fieldNameLabel )[0]:'';
        if($customField['element'] === 'select'){
            $typeText = esc_html__( $firstChar, 'classified-listing-toolkits' );
        }else{
            $typeText = esc_html__( 'Select' . ' ' . $firstChar, 'classified-listing-toolkits' );
        }

    }
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
<?php }
   elseif ( isset( $customField['element'] ) && in_array( $customField['element'], [ 'text','textarea' ] ) ) {

    $inputType = $customField['element'] === 'textarea' ? 'textarea' : 'text';

    // Get current value from GET parameters
    $currentValue = isset( $_GET[ 'cf_' . $cfField ] ) ? sanitize_text_field( $_GET[ 'cf_' . $cfField ] ) : '';

    if ( ! empty( $placeholder ) ) {
        $placeholderText = $placeholder;
    }elseif (! empty( $customField['placeholder'] )){
           $placeholderText = $customField['placeholder'];
    } else {
        $firstChar = $fieldNameLabel ? explode( ' ', $fieldNameLabel )[0]:'';
        $placeholderText = esc_html__( 'Enter ' . $firstChar, 'classified-listing-toolkits' );
    }
    ?>
    <div class="rtcl-form-group ws-item ws-input rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
        <?php if ( $controllers['fields_label'] ) { ?>
            <label class="rtcl-from-label" for="rtcl-search-input-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field_Label ); ?></label>
        <?php } ?>
        <div class="rtcl-search-input">
            <input
                    type="<?php echo esc_attr( $inputType ); ?>"
                    class="rtcl-form-control"
                    id="rtcl-search-input-<?php echo esc_attr( $id ); ?>"
                    name="cf_<?php echo esc_attr( $cfField ); ?>"
                    value="<?php echo esc_attr( $currentValue ); ?>"
                    placeholder="<?php echo esc_attr( $placeholderText ); ?>"
            />
        </div>
    </div>
    <?php
} elseif ( isset( $customField['element'] ) && $customField['element'] === 'number' ) {

    $fMinValue = !empty( $_GET['filters'][$cfField]['min'] ) ? esc_attr( $_GET['filters'][$cfField]['min'] ) : '';
    $fMaxValue = !empty( $_GET['filters'][$cfField]['max'] ) ? esc_attr( $_GET['filters'][$cfField]['max'] ) : '';

    ?>
    <div class="rtcl-form-group ws-item ws-number-range rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
        <?php if ( $controllers['fields_label'] ) { ?>
            <label class="rtcl-from-label"><?php echo esc_html( $field_Label ); ?></label>
        <?php } ?>
        <div class="rtcl-search-number-range">
            <div class="rtcl-flex">
                <div class="rtcl-flex rtcl-flex-column rtcl-form-group ws-item">
                    <div class="ui-field">
                        <input
                                id="filters_<?php echo esc_attr( $cfField ); ?>_min"
                                name="filters[<?php echo esc_attr( $cfField ); ?>][min]"
                                type="number"
                                value="<?php echo esc_attr( $fMinValue ); ?>"
                                class="ui-input form-control rtcl-form-control"
                                placeholder="<?php echo esc_attr( apply_filters( 'clt_cf_el_widgets_min_placeholder', esc_html__( 'Min.', 'classified-listing-toolkits' ) ) ); ?>"
                                step="any"
                        >
                    </div>
                </div>
                <div class="rtcl-flex rtcl-flex-column rtcl-form-group ws-item">
                    <div class="ui-field">
                        <input
                                id="filters_<?php echo esc_attr( $cfField ); ?>_max"
                                name="filters[<?php echo esc_attr( $cfField ); ?>][max]"
                                type="number"
                                value="<?php echo esc_attr( $fMaxValue ); ?>"
                                class="ui-input form-control rtcl-form-control"
                                placeholder="<?php echo esc_attr( apply_filters( 'clt_cf_el_widgets_max_placeholder', esc_html__( 'Max.', 'classified-listing-toolkits' ) ) ); ?>"
                                step="any"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif (isset( $customField['element'] ) && $customField['element'] === 'date'){

    $value = !empty( $_GET['filters'][$cfField] ) ? esc_attr( $_GET['filters'][$cfField] ) : '';

    if ( ! empty( $placeholder ) ) {
        $placeholderText = $placeholder;
    }elseif ( ! empty( $customField['placeholder'] ) ) {
        $placeholderText = $customField['placeholder'];
    } else {
        $placeholderText = esc_html__( 'Date / Time', 'classified-listing-toolkits' );
    }

    $dateType = isset( $customField['date_type'] ) ? $customField['date_type'] : 'single';
    $dateFormat = isset( $customField['date_format'] ) ? $customField['date_format'] : 'Y-m-d H:i';

    $js_options = [
        'Y-m-d'  => 'YYYY-MM-DD',
        'm/d/Y'  => 'MM/DD/YYYY',
        'd/m/Y'  => 'DD/MM/YYYY',
        'F j, Y' => 'MMMM D, YYYY',
        'j F, Y' => 'D MMMM, YYYY',
        'j F Y'  => 'D MMMM YYYY',
        'h:i:s'  => 'hh:mm:ss',
        'g:i a'  => 'h:mm a',
        'g:i A'  => 'h:mm A',
        'H:i'    => 'HH:mm'
    ];

    $find = array_keys( $js_options );
    $replace = array_values( $js_options );
    $jsFormat = str_replace( $find, $replace, $dateFormat );

    $filterableDateType = isset( $customField['filterable_date_type'] ) ? $customField['filterable_date_type'] : $dateType;

    $dateOptions = [
        'singleDatePicker' => $filterableDateType === 'single',
        'showDropdowns'    => true,
        'timePicker'       =>  false !== strpos( $dateFormat, 'h:i A' ) || false !== strpos( $dateFormat, 'H:i' ),
        'timePicker24Hour' => false !== strpos( $dateFormat, 'H:i' ),
        'autoUpdateInput'  => false,
        'locale'           => [
            'format' => $jsFormat
        ]
    ];

    $dateOptions = apply_filters( 'rtcl_custom_field_date_options', $dateOptions, $customField );
    ?>
    <div class="rtcl-form-group ws-item ws-date rtcl-flex rtcl-flex-column elementor-repeater-item-<?php echo esc_attr( $repeater_id ); ?>">
        <?php if ( $controllers['fields_label'] ) { ?>
            <label class="rtcl-from-label" for="filters_<?php echo esc_attr( $cfField ); ?>"><?php echo esc_html( $field_Label ); ?></label>
        <?php } ?>
        <div class="rtcl-search-date">
            <div class="form-group">
                <div class="ui-field">
                    <input
                            id="filters_<?php echo esc_attr( $cfField ); ?>"
                            autocomplete="false"
                            name="filters[<?php echo esc_attr( $cfField ); ?>]"
                            type="text"
                            value="<?php echo esc_attr( $value ); ?>"
                            data-options="<?php echo htmlspecialchars( wp_json_encode( $dateOptions ) ); ?>"
                            class="ui-input form-control rtcl-form-control rtcl-date"
                            placeholder="<?php echo esc_attr( $placeholderText ); ?>"
                            readonly
                    />
                </div>
            </div>
        </div>
    </div>
    <?php }
?>