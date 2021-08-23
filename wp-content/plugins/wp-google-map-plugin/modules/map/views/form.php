<?php
/**
 * Template for Add & Edit Map
 * @author  Flipper Code <hello@flippercode.com>
 * @package Maps
 */

if ( isset( $_POST['save_entity_data'] ) )
$data = $_POST;	

global $wpdb;
$modelFactory = new WPGMP_Model();
$map_obj = $modelFactory->create_object( 'map' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['map_id'] ) ) {
	$map_obj = $map_obj->fetch( array( array( 'map_id', '=', intval( wp_unslash( $_GET['map_id'] ) ) ) ) );
	$map = $map_obj[0];
	if(!empty($map)) {
		$map->map_street_view_setting = unserialize( $map->map_street_view_setting );
		$map->map_route_direction_setting = unserialize( $map->map_route_direction_setting );
		$map->map_all_control = unserialize( $map->map_all_control );
		$map->map_info_window_setting = unserialize( $map->map_info_window_setting );
		$map->style_google_map = unserialize( $map->style_google_map );
		$map->map_locations = unserialize( $map->map_locations );
		$map->map_layer_setting = unserialize( $map->map_layer_setting );
		$map->map_polygon_setting = unserialize( $map->map_polygon_setting );
		$map->map_polyline_setting = unserialize( $map->map_polyline_setting );
		$map->map_cluster_setting = unserialize( $map->map_cluster_setting );
		$map->map_overlay_setting = unserialize( $map->map_overlay_setting );
		$map->map_infowindow_setting = unserialize( $map->map_infowindow_setting );
		$map->map_geotags = unserialize( $map->map_geotags );
	}
	
	$data = (array) $map;
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}

$form  = new WPGMP_Template();
if( isset($_GET['doaction']) && $_GET['doaction'] == 'edit') {
	$edit_mode_params = array( 'page' => 'wpgmp_form_map','doaction' => 'edit', 'map_id' => intval( $_GET['map_id'] )  );
	$form->form_action = esc_url ( add_query_arg( $edit_mode_params , esc_url( admin_url ('admin.php') ) ) );
}else{
	$form->form_action = esc_url ( add_query_arg( 'page', 'wpgmp_form_map', admin_url ('admin.php') )  );	
}
$form->set_header( esc_html__( 'Map Information', 'wpgmp_google_map' ), $response, esc_html__( 'Manage Maps', 'wpgmp_google_map' ), 'wpgmp_manage_map' );
$form->add_element('hidden', 'form_action_url', array('value' => $form->form_action ) );

if( get_option( 'wpgmp_api_key' ) == '' ) {

$link = '<a target="_blank" href="http://bit.ly/29Rlmfc">'.esc_html__("create google maps api key","wpgmp_google_map").'</a>';
$setting_link = '<a target="_blank" href="' . admin_url( 'admin.php?page=wpgmp_manage_settings' ) . '">'.esc_html__("here","wpgmp_google_map").'</a>';
	
$form->add_element( 'message', 'wpgmp_key_required', array(
	'value'  => sprintf( esc_html__( 'Google Maps API Key is missing. Follow instructions to %1$s and then insert your key %2$s.', 'wpgmp_google_map' ), $link, $setting_link ),
	'class' => 'fc-msg fc-danger',
	'before' => '<div class="fc-12 wpgmp_key_required">',
	'after' => '</div>',
));

}

include( 'map-forms/general-setting-form.php' );
include( 'map-forms/map-center-settings.php' );
include( 'map-forms/locations-form.php' );
include( 'map-forms/control-setting-form.php' );
include( 'map-forms/control-position-style-form.php' );
include( 'map-forms/listing-setting-form.php' ); 
include( 'map-forms/layers-form.php' );
include( 'map-forms/street-view-setting-form.php' );
include( 'map-forms/extensible-settings.php' );
$form->add_element('extensions','wpgmp_map_form',array(
	'value' => (isset($data)) ? $data : array(),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
	));
$form->add_element( 'submit', 'save_entity_data', array(
	'value' => esc_html__( 'Save Map','wpgmp_google_map' ),
));
$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));
$form->add_element( 'hidden', 'map_locations', array(
	'value' => '',
));
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['map_id'] ) ) {

	$form->add_element( 'hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['map_id'] ) ),
	));
}
$form->render();
