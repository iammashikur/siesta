<?php
/**
 * Filters Setting(s).
 * @package Maps
 */

$form->add_element( 'group', 'map_listing_setting', array(
	'value' => esc_html__( 'Filters Settings', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

$form->add_element( 
	'checkbox', 'map_all_control[display_listing]', array(
	'lable' => esc_html__( 'Display Filters', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'wpgmp_display_listing',
	'current' => isset($data['map_all_control']['display_listing'])  ? $data['map_all_control']['display_listing'] : '',
	'desc' => esc_html__( 'Display filters below the map', 'wpgmp_google_map' ),	
		'class'   => 'chkbox_class switch_onoff',
		'data'    => array( 'target' => '.wpgmp_display_listing' ),
));

$form->add_element( 'textarea', 'map_all_control[wpgmp_before_listing]', array(
	'lable' => esc_html__( 'Before Filters Heading', 'wpgmp_google_map' ),

	'value' => ( isset( $data['map_all_control']['wpgmp_before_listing']) && !empty($data['map_all_control']['wpgmp_before_listing']) ) ? $data['map_all_control']['wpgmp_before_listing'] : esc_html__( 'Filter Locations By Category', 'wpgmp-google-map' ),
	 'id' => 'before_listing',
	'desc' => esc_html__( 'Display a text/html content that will be displayed before filters.', 'wpgmp_google_map' ),
	'textarea_rows' => 10,
		'textarea_name' => 'map_all_control[wpgmp_before_listing]',
		'class'         => 'form-control wpgmp_display_listing',
		'show'          => 'false',
		'default_value' => esc_html__( 'Map Locations', 'wpgmp-google-map' ),
));

$form->add_element( 'checkbox', 'map_all_control[wpgmp_display_category_filter]', array(
	'lable' => esc_html__( 'Display Category Filter', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'wpgmp_display_category_filter',
	'current' => (isset($data['map_all_control']['wpgmp_display_category_filter'])) ? $data['map_all_control']['wpgmp_display_category_filter'] : '',
	'desc' => esc_html__( 'Check to display category filter.', 'wpgmp_google_map' ),
		'class'   => 'chkbox_class wpgmp_display_listing',
		'show'    => 'false',
));
