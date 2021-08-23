<?php
/**
 * Contro Positioning over google maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

$positions = array(
'TOP_LEFT' => 'Top Left',
'TOP_RIGHT' => 'Top Right',
'LEFT_TOP' => 'Left Top',
'RIGHT_TOP' => 'Right Top',
'TOP_CENTER' => 'Top Center',
'LEFT_CENTER' => 'Left Center',
'RIGHT_CENTER' => 'Right Center',
'BOTTOM_RIGHT' => 'Bottom Right',
'LEFT_BOTTOM' => 'Left Bottom',
'RIGHT_BOTTOM' => 'Right Bottom',
'BOTTOM_CENTER' => 'Bottom Center',
'BOTTOM_LEFT' => 'Bottom Left',
'BOTTOM_RIGHT' => 'Bottom Right',
);
$form->add_element( 'group', 'map_control_position_setting', array(
	'value' => esc_html__( 'Control Position(s) Settings', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

$form->add_element( 'select', 'map_all_control[zoom_control_position]', array(
	'lable' => esc_html__( 'Zoom Control', 'wpgmp_google_map' ),
	'current' => (isset($data['map_all_control']['zoom_control_position'])) ? $data['map_all_control']['zoom_control_position'] : '',
	'desc' => esc_html__( 'Please select position of zoom control.', 'wpgmp_google_map' ),
	'options' => $positions,
));
$zoom_control_style = array( 'LARGE' => 'Large','SMALL' => 'Small' );
$form->add_element( 'select', 'map_all_control[zoom_control_style]', array(
	'lable' => esc_html__( 'Zoom Control Style', 'wpgmp_google_map' ),
	'current' => (isset($data['map_all_control']['zoom_control_style']))? $data['map_all_control']['zoom_control_style'] : '',
	'desc' => esc_html__( 'Please select style of zoom control.', 'wpgmp_google_map' ),
	'options' => $zoom_control_style,
));

$form->add_element( 'select', 'map_all_control[map_type_control_position]', array(
	'lable' => esc_html__( 'Map Type Control', 'wpgmp_google_map' ),
	'default_value' => 'TOP_RIGHT',
	'current' => (isset($data['map_all_control']['map_type_control_position'])) ? $data['map_all_control']['map_type_control_position'] : '',
	'desc' => esc_html__( 'Please select position of map type control.', 'wpgmp_google_map' ),
	'options' => $positions,
));


$map_type_control_style = array( 'HORIZONTAL_BAR' => 'Horizontal Bar', 'DROPDOWN_MENU' => 'Dropdown Menu' );
$form->add_element( 'select', 'map_all_control[map_type_control_style]', array(
	'lable' => esc_html__( 'Map Type Control Style', 'wpgmp_google_map' ),
	'current' => (isset($data['map_all_control']['map_type_control_style'])) ? $data['map_all_control']['map_type_control_style'] : '',
	'desc' => esc_html__( 'Please select style of map type control.', 'wpgmp_google_map' ),
	'options' => $map_type_control_style,
));


$form->add_element( 'select', 'map_all_control[full_screen_control_position]', array(
	'lable' => esc_html__( 'Full Screen Control', 'wpgmp_google_map' ),
	'default_value' => 'TOP_RIGHT',
	'current' => (isset($data['map_all_control']['full_screen_control_position'])) ? $data['map_all_control']['full_screen_control_position'] : '',
	'desc' => esc_html__( 'Please select position of full screen control.', 'wpgmp_google_map' ),
	'options' => $positions,
));

$form->add_element( 'select', 'map_all_control[street_view_control_position]', array(
	'lable' => esc_html__( 'Street View Control', 'wpgmp_google_map' ),
	'current' => ( isset($data['map_all_control']['street_view_control_position']) ) ? $data['map_all_control']['street_view_control_position'] : '',
	'desc' => esc_html__( 'Please select position of street view control.', 'wpgmp_google_map' ),
	'options' => $positions,
));

// Search Control Position
$form->add_element( 'select', 'map_all_control[search_control_position]', array(
	'lable' => esc_html__( 'Search Control', 'wpgmp_google_map' ),
	
	'current' => ( isset($data['map_all_control']['search_control_position']) ) ? $data['map_all_control']['search_control_position'] : '',
	'desc' => esc_html__( 'Please select position of search box control.', 'wpgmp_google_map' ),
	'options' => $positions,
));