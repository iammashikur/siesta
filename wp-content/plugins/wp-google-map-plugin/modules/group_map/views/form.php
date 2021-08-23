<?php
/**
 * Template for Add & Edit Category
 * @author  Flipper Code <hello@flippercode.com>
 * @package Maps
 */

if ( isset( $_POST['create_group_map_location'] ) )
$data = $_POST;	

global $wpdb;
$modelFactory = new WPGMP_Model();
$category = $modelFactory->create_object( 'group_map' );
$categories = (array) $category->fetch();
if ( isset( $_GET['doaction'] ) &&  'edit' == $_GET['doaction'] && isset( $_GET['group_map_id'] ) ) {
	$category_obj   = $category->fetch( array( array( 'group_map_id', '=', intval( wp_unslash( $_GET['group_map_id'] ) ) ) ) );
	$_POST = (array) $category_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) && isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $_POST );
}
$form  = new WPGMP_Template();
if( isset($_GET['doaction']) && $_GET['doaction'] == 'edit') {
	$edit_mode_params = array( 'page' => 'wpgmp_form_group_map','doaction' => 'edit', 'group_map_id' => intval( $_GET['group_map_id'] )  );
	$form->form_action = esc_url ( add_query_arg( $edit_mode_params , esc_url( admin_url ('admin.php') ) ) );
}else{
	$form->form_action = esc_url ( add_query_arg( 'page', 'wpgmp_form_group_map', admin_url ('admin.php') )  );	
}

$form->set_header( esc_html__( 'Marker Category', 'wpgmp_google_map' ), $response, esc_html__( 'Manage Marker Categories', 'wpgmp_google_map' ), 'wpgmp_manage_group_map' );

if ( is_array( $categories ) ) {
	$markers = array( ' ' => 'Please Select' );
	foreach ( $categories as $i => $single_category ) {
			$markers[ $single_category->group_map_id ] = $single_category->group_map_title;
	}

	$form->add_element('select', 'group_parent', array(
		'lable' => esc_html__( 'Parent Category', 'wpgmp_google_map' ),
		'current' => (isset( $_POST['group_parent'] ) and ! empty( $_POST['group_parent'] )) ? intval( wp_unslash( $_POST['group_parent'] ) ) : '',
		'desc' => esc_html__( 'Assign parent category if any.', 'wpgmp_google_map' ),
		'options' => $markers,
	));

}

$form->add_element('text', 'group_map_title', array(
	'lable' => esc_html__( 'Marker Category Title', 'wpgmp_google_map' ),
	'value' => (isset( $_POST['group_map_title'] ) and ! empty( $_POST['group_map_title'] )) ? sanitize_text_field( wp_unslash( $_POST['group_map_title'] ) ) : '',
	'id' => 'group_map_title',
	'desc' => esc_html__( 'Enter here marker category title.', 'wpgmp_google_map' ),
	'class' => 'create_map form-control',
	'placeholder' => esc_html__( 'Marker Category Title', 'wpgmp_google_map' ),
	'required' => true,
));


$form->add_element('image_picker', 'group_marker', array(
	'lable' => esc_html__( 'Choose Marker Image', 'wpgmp_google_map' ),
	'src' => (isset( $_POST['group_marker'] ) ) ? wp_unslash( $_POST['group_marker'] ) : WPGMP_IMAGES.'/default_marker.png',
	'required' => false,
	'choose_button' => esc_html__( 'Choose', 'wpgmp_google_map' ),
	'remove_button' => esc_html__( 'Remove','wpgmp_google_map' ),
	'id' => 'marker_category_icon',
));

$form->set_col( 1 );


$form->add_element('extensions','wpgmp_category_form',array(
	'value' => (isset($_POST['extensions_fields'])) ? $_POST['extensions_fields'] : array(),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
	));


$form->add_element('submit', 'create_group_map_location', array(
	'value' => 'Save Marker Category',
	'before' => '<div class="fc-12">',
	'after' => '</div>'

));

$form->add_element('hidden', 'operation', array(
	'value' => 'save',
));

if ( isset( $_GET['doaction'] ) and  'edit' == $_GET['doaction'] ) {
	$form->add_element('hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['group_map_id'] ) ),
	));
}

$form->render();
