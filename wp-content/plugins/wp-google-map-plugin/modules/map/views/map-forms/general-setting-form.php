<?php
/**
 * Map's general setting(s).
 * @package Maps
 */

$form->add_element( 'text', 'map_title', array(
	'lable' => esc_html__( 'Map Title', 'wpgmp_google_map' ),
	'value' => ( isset($data['map_title']) ) ? $data['map_title'] : '',
	'desc' => esc_html__( 'Enter here the map title.', 'wpgmp_google_map' ),
	'required' => true,
	'placeholder' => '',
));
$form->add_element( 'text', 'map_width', array(
	'lable' => esc_html__( 'Map Width', 'wpgmp_google_map' ),
	'value' => (isset($data['map_width'])) ? $data['map_width'] : '',
	'desc' => esc_html__( 'Enter here the map width in pixel. Leave it blank for 100% width.', 'wpgmp_google_map' ),
	'placeholder' => '',
));
$form->add_element( 'text', 'map_height', array(
	'lable' => esc_html__( 'Map Height', 'wpgmp_google_map' ),
	'value' => (isset($data['map_height'])) ? $data['map_height'] : '',
	'desc' => esc_html__( 'Enter here the map height in pixel.', 'wpgmp_google_map' ),
	'required' => true,
	'placeholder' => '',
));

$zoom_level = array();
for ( $i = 0; $i < 20; $i++ ) {
	$zoom_level[ $i ] = $i;
}
$form->add_element( 'select', 'map_zoom_level', array(
	'lable' => esc_html__( 'Map Zoom Level', 'wpgmp_google_map' ),
	'current' => (isset($data['map_zoom_level'])) ? $data['map_zoom_level'] : '5',
	'desc' => esc_html__( 'Available options 0 to 19.', 'wpgmp_google_map' ),
	'options' => $zoom_level,
	'default_value' => 5,
));

$map_type = array( 'ROADMAP' => 'ROADMAP','SATELLITE' => 'SATELLITE','HYBRID' => 'HYBRID','TERRAIN' => 'TERRAIN' );
$form->add_element( 'select', 'map_type', array(
	'lable' => esc_html__( 'Map Type', 'wpgmp_google_map' ),
	'current' => (isset($data['map_type'])) ? $data['map_type'] : '',
	'options' => $map_type,
));

$form->add_element( 'checkbox', 'map_scrolling_wheel', array(
	'lable' => esc_html__( 'Turn Off Scrolling Wheel', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_map_scrolling_wheel',
	'current' => (isset($data['map_scrolling_wheel'])) ? $data['map_scrolling_wheel'] : '',
	'desc' => esc_html__( 'Please check to disable scroll wheel zoom.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class ',
));
$form->add_element( 'checkbox', 'map_all_control[map_draggable]', array(
	'lable' => esc_html__( 'Map Draggable', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_map_draggable',
	'current' => (isset($data['map_all_control']['map_draggable'])) ? $data['map_all_control']['map_draggable'] : '',
	'desc' => esc_html__( 'Please check to disable map draggable.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));

$form->add_element( 'checkbox', 'map_45imagery', array(
	'lable' => esc_html__( '45&deg; Imagery', 'wpgmp_google_map' ),
	'value' => '45',
	'id' => 'wpgmp_map_45imagery',
	'current' => (isset($data['map_45imagery'])) ? $data['map_45imagery'] : '',
	'desc' => esc_html__( 'Apply 45&deg; Imagery ? (only available for map type SATELLITE and HYBRID).', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));

