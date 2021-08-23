<?php
/**
 * Contro Positioning over google maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

$form->add_element( 'group', 'map_control_settings', array(
	'value' => esc_html__( 'Infowindow Settings', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));
$url = admin_url( 'admin.php?page=wpgmp_how_overview' );
$link =  esc_html__( 'Enter placeholders {marker_title},{marker_address},{marker_message},{marker_latitude},{marker_longitude}.','wpgmp_google_map');


$default_value = '';

$default_value = (isset( $data['map_all_control']['infowindow_setting'] ) and '' != $data['map_all_control']['infowindow_setting'] ) ? $data['map_all_control']['infowindow_setting'] : $default_value;

$form->add_element( 'textarea', 'map_all_control[infowindow_setting]', array(
	'lable' => esc_html__( 'Infowindow Message', 'wpgmp_google_map' ),
	'value' => (isset($data['map_all_control']['infowindow_setting'])) ? $data['map_all_control']['infowindow_setting'] : '',
	'desc' => $link,
	'textarea_rows' => 10,
	'textarea_name' => 'location_messages',
	'class' => 'form-control',
	'id' => 'googlemap_infomessage',
	'default_value' => $default_value,
));

if ( isset($data) && isset($data['map_all_control']['infowindow_openoption']) && 'mouseclick' == $data['map_all_control']['infowindow_openoption'] ) {
	$data['map_all_control']['infowindow_openoption'] = 'click'; } else if ( isset($data) && 'mousehover' == $data['map_all_control']['infowindow_openoption'] ) {
	$data['map_all_control']['infowindow_openoption'] = 'mouseover'; }
	$event = array( 'click' => 'Mouse Click', 'mouseover' => 'Mouse Hover' );
	$form->add_element( 'select', 'map_all_control[infowindow_openoption]', array(
		'lable' => esc_html__( 'Show Infowindow on', 'wpgmp_google_map' ),
		'current' => (isset($data['map_all_control']['infowindow_openoption'])) ? $data['map_all_control']['infowindow_openoption'] : '',
		'desc' => esc_html__( 'Open infowindow on Mouse Click or Mouse Hover.', 'wpgmp_google_map' ),
		'options' => $event,
	));

	$form->add_element('image_picker', 'map_all_control[marker_default_icon]', array(
		'lable' => esc_html__( 'Choose Marker Image', 'wpgmp_google_map' ),
		'src' => (isset( $data['map_all_control']['marker_default_icon'] )  ? wp_unslash( $data['map_all_control']['marker_default_icon'] ) : WPGMP_IMAGES.'/default_marker.png'),
		'required' => false,
		'choose_button' => esc_html__( 'Choose', 'wpgmp_google_map' ),
		'remove_button' => esc_html__( 'Remove','wpgmp_google_map' ),
		'id' => 'marker_category_icon',
	));

	$form->add_element( 'checkbox', 'map_all_control[infowindow_open]', array(
		'lable' => esc_html__( 'InfoWindow Open', 'wpgmp_google_map' ),
		'value' => 'true',
		'id' => 'wpgmp_infowindow_open',
		'current' => (isset($data['map_all_control']['infowindow_open'])) ? $data['map_all_control']['infowindow_open'] : '',
		'desc' => esc_html__( 'Please check to enable infowindow default open.', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));

	$form->add_element( 'checkbox', 'map_all_control[infowindow_close]', array(
		'lable' => esc_html__( 'Close InfoWindow', 'wpgmp_google_map' ),
		'value' => 'true',
		'id' => 'wpgmp_infowindow_close',
		'current' => (isset($data['map_all_control']['infowindow_close'])) ? $data['map_all_control']['infowindow_close'] : '',
		'desc' => esc_html__( 'Please check to close infowindow on map click.', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));

	$event = array( '' => esc_html__( 'Select Animation','wpgmp_google_map' ),'click' => esc_html__( 'Mouse Click','wpgmp_google_map' ), 'mouseover' => esc_html__( 'Mouse Hover','wpgmp_google_map' ) );
	$form->add_element( 'select', 'map_all_control[infowindow_bounce_animation]', array(
		'lable' => esc_html__( 'Bounce Animation', 'wpgmp_google_map' ),
		'current' => (isset($data['map_all_control']['infowindow_bounce_animation'])) ? $data['map_all_control']['infowindow_bounce_animation'] : '' ,
		'desc' => esc_html__( 'Apply bounce animation on mousehover or mouse click. BOUNCE indicates that the marker should bounce in place.', 'wpgmp_google_map' ),
		'options' => $event,
	));

	$form->add_element( 'checkbox', 'map_all_control[infowindow_drop_animation]', array(
		'lable' => esc_html__( 'Apply Drop Animation', 'wpgmp_google_map' ),
		'value' => 'true',
		'id' => 'infowindow_drop_animation',
		'current' => (isset($data['map_all_control']['infowindow_drop_animation'])) ? $data['map_all_control']['infowindow_drop_animation'] : '',
		'desc' => esc_html__( 'DROP indicates that the marker should drop from the top of the map. ', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));


	$form->add_element( 'group', 'map_control_layers', array(
		'value' => esc_html__( 'Layers Settings', 'wpgmp_google_map' ),
		'before' => '<div class="fc-12">',
		'after' => '</div>',
	));

	$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][traffic_layer]', array(
		'lable' => esc_html__( 'Traffic Layer', 'wpgmp_google_map' ),
		'value' => 'TrafficLayer',
		'id' => 'wpgmp_traffic_layer',
		'current' => (isset($data['map_layer_setting']['choose_layer']['traffic_layer'])) ? $data['map_layer_setting']['choose_layer']['traffic_layer'] : '',
		'desc' => esc_html__( 'Please check to enable traffic Layer.', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));

	$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][transit_layer]', array(
		'lable' => esc_html__( 'Transit Layer', 'wpgmp_google_map' ),
		'value' => 'TransitLayer',
		'id' => 'wpgmp_transit_layer',
		'current' => (isset($data['map_layer_setting']['choose_layer']['transit_layer'])) ? $data['map_layer_setting']['choose_layer']['transit_layer'] : '',
		'desc' => esc_html__( 'Please check to enable Transit Layer.', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));


	$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][bicycling_layer]', array(
		'lable' => esc_html__( 'Bicycling Layer', 'wpgmp_google_map' ),
		'value' => 'BicyclingLayer',
		'id' => 'wpgmp_bicycling_layer',
		'current' => (isset($data['map_layer_setting']['choose_layer']['bicycling_layer']))? $data['map_layer_setting']['choose_layer']['bicycling_layer'] : '',
		'desc' => esc_html__( 'Please check to enable Bicycling Layer.', 'wpgmp_google_map' ),
		'class' => 'chkbox_class',
	));
