<?php
  
$form  = new WPGMP_Template();
echo $form->show_header();
    
if ( class_exists( 'WP_List_Table_Helper' ) && ! class_exists( 'Wpgmp_Location_Table' ) ) {

	class Wpgmp_Location_Table extends WP_List_Table_Helper {  public function __construct($tableinfo) {
			parent::__construct( $tableinfo ); }  }

	// Minimal Configuration :)
	global $wpdb;
	$columns   = array( 'location_title' => 'Title','location_address' => 'Address','location_latitude' => 'Latitude','location_longitude' => 'Longitude' );
	$sortable  = array( 'location_title','location_address','location_latitude','location_longitude' );
	$tableinfo = array(
	'table' => $wpdb->prefix.'map_locations',
	'textdomain' => 'wpgmp_google_map',
	'singular_label' => 'location',
	'plural_label' => 'locations',
	'admin_listing_page_name' => 'wpgmp_manage_location',
	'admin_add_page_name' => 'wpgmp_form_location',
	'primary_col' => 'location_id',
	'columns' => $columns,
	'sortable' => $sortable,
	'per_page' => 200,
	'actions' => array( 'edit','delete' ),
	'col_showing_links' => 'location_title',
	'translation' => array(
			'manage_heading'      => esc_html__( 'Manage Locations', 'wpgmp_google_map' ),
			'add_button'          => esc_html__( 'Add Location', 'wpgmp_google_map' ),
			'delete_msg'          => esc_html__( 'Location(s) deleted successfully', 'wpgmp_google_map' ),
			'insert_msg'          => esc_html__( 'Location added successfully', 'wpgmp_google_map' ),
			'update_msg'          => esc_html__( 'Location updated successfully', 'wpgmp_google_map' ),
		),
	);
	return new Wpgmp_Location_Table( $tableinfo );

}
?>
