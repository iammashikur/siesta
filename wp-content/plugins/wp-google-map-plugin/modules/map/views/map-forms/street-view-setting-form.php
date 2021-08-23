<?php
/**
 * Contro Positioning over google maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

$form->add_element( 'group', 'map_street_view_setting', array(
	'value' => esc_html__( 'Street View Settings', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

$form->add_element( 'checkbox', 'map_street_view_setting[street_control]', array(
	'lable' => esc_html__( 'Turn On Street View', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'wpgmp_street_control',
	'current' => (isset($data['map_street_view_setting']['street_control'])) ? $data['map_street_view_setting']['street_control'] : '',
	'desc' => esc_html__( 'Please check to enable street view', 'wpgmp_google_map' ),
	'class' => 'chkbox_class switch_onoff',
	'data' => array( 'target' => '.street_view_setting' ),
));

$form->add_element( 'checkbox', 'map_street_view_setting[street_view_close_button]', array(
	'lable' => esc_html__( 'Turn On Close Button', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'wpgmp_street_view_close_button',
	'current' => ( isset($data['map_street_view_setting']['street_view_close_button']) ) ? $data['map_street_view_setting']['street_view_close_button'] : '',
	'desc' => esc_html__( 'Please check to turn on close button.', 'wpgmp_google_map' ),
	'data' => array( 'target' => '#geo_tags_table,#geo_tags_message' ),
	'class' => 'street_view_setting',
	'show' => 'false',
));

$form->add_element( 'checkbox', 'map_street_view_setting[links_control]', array(
	'lable' => esc_html__( 'Turn Off links Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_links_control',
	'current' => (isset($data['map_street_view_setting']['links_control'])) ? $data['map_street_view_setting']['links_control'] : '',
	'desc' => esc_html__( 'Please check to disable links control.', 'wpgmp_google_map' ),
	'data' => array( 'target' => '#geo_tags_table,#geo_tags_message' ),
	'class' => 'street_view_setting',
	'show' => 'false',
));

$form->add_element( 'checkbox', 'map_street_view_setting[street_view_pan_control]', array(
	'lable' => esc_html__( 'Turn Off Street View Pan Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_street_view_pan_control',
	'current' => (isset($data['map_street_view_setting']['street_view_pan_control'])) ? $data['map_street_view_setting']['street_view_pan_control'] : '',
	'desc' => esc_html__( 'Please check to disable Street View Pan control.', 'wpgmp_google_map' ),
	'data' => array( 'target' => '#geo_tags_table,#geo_tags_message' ),
	'class' => 'street_view_setting',
	'show' => 'false',
));

$form->add_element( 'text', 'map_street_view_setting[pov_heading]', array(
	'lable' => esc_html__( 'POV Heading', 'wpgmp_google_map' ),
	'value' => (isset($data['map_street_view_setting']['pov_heading'])) ? $data['map_street_view_setting']['pov_heading'] : '',
	'id' => 'pov_heading',
	'desc' => esc_html__( 'Please enter numeric integer value for POV heading.', 'wpgmp_google_map' ),
	'class' => 'form-control street_view_setting',
	'show' => 'false',
));

$form->add_element( 'text', 'map_street_view_setting[pov_pitch]', array(
	'lable' => esc_html__( 'POV Pitch', 'wpgmp_google_map' ),
	'value' => ( isset($data['map_street_view_setting']['pov_pitch']) ) ? $data['map_street_view_setting']['pov_pitch'] : '',
	'id' => 'pov_heading',
	'desc' => esc_html__( 'Please enter numeric integer value for POV Pitch.', 'wpgmp_google_map' ),
	'class' => 'form-control street_view_setting',
	'show' => 'false',
));
