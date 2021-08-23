<?php
/**
 * Map's Center Location setting(s).
 * @package Maps
 */

$form->add_element( 'group', 'map_center_setting', array(
	'value' => esc_html__( 'Map\'s Center', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

$form->add_element( 'text', 'map_all_control[map_center_latitude]', array(
	'lable' => esc_html__( 'Center Latitude', 'wpgmp_google_map' ),
	'value' => (isset($data['map_all_control']['map_center_latitude'])) ? $data['map_all_control']['map_center_latitude'] : '',
	'desc' => esc_html__( 'Enter here the center latitude.', 'wpgmp_google_map' ),
	'placeholder' => '',
));
$form->add_element( 'text', 'map_all_control[map_center_longitude]', array(
	'lable' => esc_html__( 'Center Longitude', 'wpgmp_google_map' ),
	'value' => (isset($data['map_all_control']['map_center_longitude'])) ? $data['map_all_control']['map_center_longitude'] : '',
	'desc' => esc_html__( 'Enter here the center longitude.', 'wpgmp_google_map' ),
	'placeholder' => '',
));
