<?php
/**
 * Control Setting(s).
 * @package Maps
 */

$form->add_element( 'group', 'map_control_setting', array(
	'value' => esc_html__( 'Control Settings', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

$form->add_element( 'checkbox', 'map_all_control[zoom_control]', array(
	'lable' => esc_html__( 'Turn Off Zoom Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_zoom_control',
	'current' => (isset($data['map_all_control']['zoom_control']) ) ? $data['map_all_control']['zoom_control'] : '',
	'desc' => esc_html__( 'Please check to disable zoom control.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));


$form->add_element( 'checkbox', 'map_all_control[full_screen_control]', array(
	'lable' => esc_html__( 'Turn Off Full Screen Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'full_screen_control',
	'current' => (isset($data['map_all_control']['full_screen_control'])) ? $data['map_all_control']['full_screen_control'] : '',
	'desc' => esc_html__( 'Please check to disable full screen control.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));


$form->add_element( 'checkbox', 'map_all_control[map_type_control]', array(
	'lable' => esc_html__( 'Turn Off Map Type Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'map_type_control',
	'current' => (isset($data['map_all_control']['map_type_control'])) ? $data['map_all_control']['map_type_control'] : '',
	'desc' => esc_html__( 'Please check to disable map type control.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));

$form->add_element( 'checkbox', 'map_all_control[street_view_control]', array(
	'lable' => esc_html__( 'Turn Off Street View Control', 'wpgmp_google_map' ),
	'value' => 'false',
	'id' => 'wpgmp_street_view_control',
	'current' => (isset($data['map_all_control']['street_view_control'])) ? $data['map_all_control']['street_view_control'] : '',
	'desc' => esc_html__( 'Please check to disable street view control.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));

$form->add_element( 'checkbox', 'map_all_control[search_control]', array(
	'lable' => esc_html__( 'Turn On Search Control', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'search_control',
	'current' => (isset($data['map_all_control']['search_control'])) ? $data['map_all_control']['search_control'] : '',
	'desc' => esc_html__( 'Please check to enable search box control.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));

$form->add_element(
	'group', 'map_styles_settings', array(
		'value'  => esc_html__( 'Map Style Settings', 'wpgmp-google-map' ),
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);


$snazzy_link = '<a href="http://snazzymaps.com" target="_blank">  '.esc_html__( 'Snazzy Maps','wpgmp-google-map').'</a>';
$slink =  sprintf( esc_html__( 'Get free style for your google maps from %s You can copy javascript style array from there and paste here.', 'wpgmp-google-map' ), $snazzy_link );

$form->add_element(
	'message', 'styles_message', array(
		'value'  => $slink,
		'class'  => 'alert',
		'id'     => 'styles_message',
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);


$form->add_element(
	'textarea', 'map_all_control[custom_style]', array(
		'label'         => esc_html__( 'Paste Style here', 'wpgmp-google-map' ),
		'value'         => ( isset( $data['map_all_control']['custom_style'] ) and ! empty( $data['map_all_control']['custom_style'] ) ) ? $data['map_all_control']['custom_style'] : '',
		'desc'          => sprintf( esc_html__( 'Copy google map javascript style array from %s paste here.', 'wpgmp-google-map' ), $snazzy_link ),
		'textarea_rows' => 20,
		'textarea_name' => 'location_messages',
		'class'         => 'form-control',
		'id'            => 'map_custom_style',
		'before'        => '<div class="fc-11">',
		'after'         => '</div>',
	)
);
