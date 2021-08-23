<?php
/**
 * Template for Add & Edit Location
 * @author  Flipper Code <hello@flippercode.com>
 * @package Maps
 */

if ( isset( $_POST['save_entity_data'] ) ) 
$data = $_POST;	

global $wpdb;
$modelFactory = new WPGMP_Model();
$category_obj = $modelFactory->create_object( 'group_map' );
$categories = $category_obj->fetch();
if ( is_array( $categories ) and ! empty( $categories ) ) {
	$all_categories = array();
	foreach ( $categories as $category ) {
		$all_categories [ $category->group_map_id ] = $category;
	}
}
$location_obj = $modelFactory->create_object( 'location' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['location_id'] ) ) {
	$location_obj = $location_obj->fetch( array( array( 'location_id', '=', intval( wp_unslash( $_GET['location_id'] ) ) ) ) );
	$data = (array) $location_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}
$form  = new WPGMP_Template();
if( isset($_GET['doaction']) && $_GET['doaction'] == 'edit') {
	$edit_mode_params = array( 'page' => 'wpgmp_form_location','doaction' => 'edit', 'location_id' => intval( $_GET['location_id'] )  );
	$form->form_action = esc_url ( add_query_arg( $edit_mode_params , esc_url( admin_url ('admin.php') ) ) );
}else{
	$form->form_action = esc_url ( add_query_arg( 'page', 'wpgmp_form_location', admin_url ('admin.php') )  );	
}

$form->set_header( esc_html__( 'Location Information', 'wpgmp_google_map' ), $response, esc_html__( 'Manage Locations', 'wpgmp_google_map' ), 'wpgmp_manage_location' );
$form->add_element('hidden', 'form_action_url',  array('value' => $form->form_action ) );

if( get_option( 'wpgmp_api_key' ) == '' ) {

$link = '<a target="_blank" href="http://bit.ly/29Rlmfc">'.esc_html__("create google maps api key","wpgmp_google_map").'</a>';
	$setting_link = '<a target="_blank" href="' . admin_url( 'admin.php?page=wpgmp_manage_settings' ) . '">'.esc_html__("here","wpgmp_google_map").'</a>';
	
$form->add_element( 'message', 'wpgmp_key_required', array(
	'value' => sprintf( esc_html__( 'Google Maps API Key is missing. Follow instructions to %1$s and then insert your key %2$s.', 'wpgmp_google_map' ), $link, $setting_link ),
	'class' => 'fc-msg fc-danger',
	'before' => '<div class="fc-12 wpgmp_key_required">',
	'after' => '</div>',
));

}


$form->add_element( 'text', 'location_title', array(
	'lable' => esc_html__( 'Location Title', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_title'] ) and ! empty( $data['location_title'] )) ? $data['location_title'] : '',
	'required' => true,
	'placeholder' => esc_html__( 'Enter Location Title', 'wpgmp_google_map' ),
));

$form->add_element( 'text', 'location_address', array(
	'lable' => esc_html__( 'Location Address', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_address'] ) and ! empty( $data['location_address'] )) ? $data['location_address'] : '',
	'desc' => esc_html__( 'Enter here the address. Google auto suggest helps you to choose one.', 'wpgmp_google_map' ),
	'required' => true,
	'class' => 'form-control wpgmp_auto_suggest',
	'placeholder' => esc_html__( 'Type Location Address', 'wpgmp_google_map' ),
));
$form->set_col( 2 );
$form->add_element( 'text', 'location_latitude', array(
	'lable' => esc_html__( 'Latitude and Longitude', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_latitude'] ) and ! empty( $data['location_latitude'] )) ? $data['location_latitude'] : '',
	'id' => 'googlemap_latitude',
	'required' => true,
	'class' => 'google_latitude form-control',
	'placeholder' => esc_html__( 'Latitude', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->add_element( 'text', 'location_longitude', array(
	'value' => (isset( $data['location_longitude'] ) and ! empty( $data['location_longitude'] )) ? $data['location_longitude'] : '',
	'id' => 'googlemap_longitude',
	'required' => true,
	'class' => 'google_longitude form-control',
	'placeholder' => esc_html__( 'Longitude', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->add_element( 'text', 'location_city', array(
	'lable' => esc_html__( 'City and State', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_city'] ) and ! empty( $data['location_city'] )) ? $data['location_city'] : '',
	'id' => 'googlemap_city',
	'class' => 'google_city form-control',
	'placeholder' => esc_html__( 'City', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->add_element( 'text', 'location_state', array(
	'value' => (isset( $data['location_state'] ) and ! empty( $data['location_state'] )) ? $data['location_state'] : '',
	'id' => 'googlemap_state',
	'class' => 'google_state form-control',
	'placeholder' => esc_html__( 'State', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->add_element( 'text', 'location_country', array(
	'lable' => esc_html__( 'Country and Postal Code', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_country'] ) and ! empty( $data['location_country'] )) ? $data['location_country'] : '',
	'id' => 'googlemap_country',
	'class' => 'google_country form-control',
	'placeholder' => esc_html__( 'Country', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->add_element( 'text', 'location_postal_code', array(
	'value' => (isset( $data['location_postal_code'] ) and ! empty( $data['location_postal_code'] )) ? $data['location_postal_code'] : '',
	'id' => 'googlemap_postal_code',
	'class' => 'google_postal_code form-control',
	'placeholder' => esc_html__( 'Postal Code', 'wpgmp_google_map' ),
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));
$form->set_col( 1 );
$form->add_element( 'div', 'wpgmp_map', array(
	'lable' => esc_html__( 'Current Location', 'wpgmp_google_map' ),
	'id' => 'wpgmp_map',
	'style' => array( 'width' => '100%' ,'height' => '300px' ),
));


$form->add_element( 'radio', 'location_settings[onclick]', array(
	'lable' => esc_html__( 'On Click', 'wpgmp_google_map' ),
	'radio-val-label' => array( 'marker' => esc_html__( 'Display Infowindow','wpgmp_google_map' ),'custom_link' => esc_html__( 'Redirect','wpgmp_google_map' ) ),
	'current' => (isset($data['location_settings']['onclick'])) ? $data['location_settings']['onclick'] : '',
	'class' => 'chkbox_class switch_onoff',
	'default_value' => 'marker',
	'data' => array( 'target' => '.wpgmp_location_onclick' ),
));


$form->add_element( 'textarea', 'location_messages', array(
	'lable' => esc_html__( 'Infowindow Message', 'wpgmp_google_map' ),
	'value' => (isset( $data['location_messages'] ) and ! empty( $data['location_messages'] )) ?  $data['location_messages']  : '',
	'desc' => esc_html__( 'Enter here the infoWindow message.', 'wpgmp_google_map' ),
	'textarea_rows' => 10,
	'textarea_name' => 'location_messages',
	'class' => 'form-control wpgmp_location_onclick wpgmp_location_onclick_marker',
	'id' => 'googlemap_infomessage',
	'show' => 'false',
));

$form->add_element( 'text', 'location_settings[redirect_link]', array(
	'lable' => esc_html__( 'Redirect Url','wpgmp_google_map' ),
	'value' => isset($data['location_settings']['redirect_link']) ? $data['location_settings']['redirect_link'] : '',
	'desc' => esc_html__( 'Enter here the redirect url. e.g http://www.flippercode.com', 'wpgmp_google_map' ),
	'class' => 'wpgmp_location_onclick_custom_link wpgmp_location_onclick form-control',
	'before' => '<div class="fc-8">',
	'after' => '</div>',
	'show' => 'false',
));

$form->add_element( 'select', 'location_settings[redirect_link_window]', array(
	'options' => array( 'yes' => esc_html__( 'YES','wpgmp_google_map' ), 'no' => esc_html__( 'NO','wpgmp_google_map' ) ),
	'lable' => esc_html__( 'Open new tab','wpgmp_google_map' ),
	'current' => (isset($data['location_settings']['redirect_link_window'])) ? $data['location_settings']['redirect_link_window'] : '',
	'desc' => esc_html__( 'Open a new window tab.', 'wpgmp_google_map' ),
	'class' => 'wpgmp_location_onclick_redirect wpgmp_location_onclick form-control',
	'before' => '<div class="fc-2">',
	'after' => '</div>',
	'show' => 'false',
));


$form->add_element( 'checkbox', 'location_infowindow_default_open', array(
	'lable' => esc_html__( 'Infowindow Default Open', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'location_infowindow_default_open',
	'current' => (isset($data['location_infowindow_default_open'])) ? $data['location_infowindow_default_open'] : '',
	'desc' => esc_html__( 'Check to enable infowindow default open.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));
$form->add_element( 'checkbox', 'location_draggable', array(
	'lable' => esc_html__( 'Marker Draggable', 'wpgmp_google_map' ),
	'value' => 'true',
	'id' => 'location_draggable',
	'current' => isset($data['location_draggable']) ? $data['location_draggable'] : '' ,
	'desc' => esc_html__( 'Check if you want to allow visitors to drag the marker.', 'wpgmp_google_map' ),
	'class' => 'chkbox_class',
));
$form->add_element( 'select', 'location_animation', array(
	'lable' => esc_html__( 'Marker Animation', 'wpgmp_google_map' ),
	'current' => (isset( $data['location_animation'] ) and ! empty( $data['location_animation'] )) ? $data['location_animation'] : '',
	'options' => array( 'BOUNCE' => 'Bounce', 'DROP' => 'DROP' ),
	'before' => '<div class="fc-3">',
	'after' => '</div>',
));

$form->add_element( 'group', 'marker_category_listing', array(
	'value' => esc_html__( 'Marker Categories', 'wpgmp_google_map' ),
	'before' => '<div class="fc-12">',
	'after' => '</div>',
));

if ( ! empty( $all_categories ) ) {
	$category_data = array();
	$parent_category_data = array();
	if ( ! isset($data['location_group_map'] ) ) {
		$data['location_group_map'] = array(); }
	foreach ( $categories as $category ) {
		if ( is_null( $category->group_parent ) or 0 == $category->group_parent ) {
			$parent_category_data = ' ---- ';
		} else {
			$parent_category_data = $all_categories[ $category->group_parent ]->group_map_title;
		}
		if ( '' != $category->group_marker ) {
			$icon_src = "<img src='".$category->group_marker."' />";
		} else {
			$icon_src = "<img src='".WPGMP_IMAGES."default_marker.png' />";

		}
		$select_input = $form->field_checkbox('location_group_map[]',array(
			'value' => $category->group_map_id,
			'current' => ( isset($data['location_group_map']) && in_array( $category->group_map_id, $data['location_group_map'] ) ) ? $category->group_map_id : '',
			'class' => 'chkbox_class',
			'before' => '<div class="fc-1">',
			'after' => '</div>',
			));
		
		$category_data[] = array( $select_input,$category->group_map_title,$parent_category_data,$icon_src );
	}
	$category_data = $form->add_element( 'table', 'location_group_map', array(
		'heading' => array( 'Select','Category','Parent','Icon' ),
		'data' => $category_data,
		'id' => 'location_group_map',
		'class' => 'fc-table fc-table-layout3',
		'before' => '<div class="fc-12">',
		'after' => '</div>',
		));
		 
} else {
	$form->add_element( 'message', 'message', array(
		'value' => esc_html__( 'You don\'t have categorie(s).', 'wpgmp_google_map' ),
		'class' => 'fc-msg',
		'before' => '<div class="fc-12">',
		'after' => '</div>',
	));
}

$form->add_element('extensions','wpgmp_location_form',array(
	'value' => (isset($data['location_settings']['extensions_fields'])) ? $data['location_settings']['extensions_fields'] : array(),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
	));

$form->add_element( 'submit', 'save_entity_data', array(
	'value' => esc_html__( 'Save Location','wpgmp_google_map' ),
));
$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {

	$form->add_element( 'hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['location_id'] ) ),
	));
}
$form->render();
$infowindow_message  = (isset( $data['location_messages'] ) and ! empty( $data['location_messages'] )) ? $data['location_messages'] : '';
$infowindow_disable = (isset( $data['location_settings'] ) and ! empty( $data['location_settings'] )) ? $data['location_settings'] : '';
if(isset($_GET['group_map_id'])) {
$category_obj = $category_obj->get( array( array( 'group_map_id', '=', intval( wp_unslash( $_GET['group_map_id'] ) ) ) ) );
$category = (array) $category_obj[0];
}

if(isset($data['location_group_map'][0]))
$ckey =  $data['location_group_map'][0];
$category_group_marker = '';
if ( ! empty( $category->group_marker ) && !empty($data['location_group_map']) && isset($all_categories[$ckey]) ) {
	$category_group_marker = $all_categories[$ckey]->group_marker;
} else {
	$category_group_marker = WPGMP_IMAGES.'default_marker.png';
}
$map_data['map_options'] = array(
'center_lat'  => (isset( $data['location_latitude'] ) and ! empty( $data['location_latitude'] )) ? $data['location_latitude'] : '',
'center_lng'  => (isset( $data['location_longitude'] ) and ! empty( $data['location_longitude'] )) ? $data['location_longitude'] : '',
);
$map_data['places'][] = array(
'id'          => (isset( $data['location_id'] ) and ! empty( $data['location_id'] )) ? $data['location_id'] : '',
'title'       => (isset( $data['location_title'] ) and ! empty( $data['location_title'] )) ? $data['location_title'] : '',
'content'     => $infowindow_message,
'location'    => array(
'icon'      => ($category_group_marker),
'lat'       => (isset( $data['location_latitude'] ) and ! empty( $data['location_latitude'] )) ? $data['location_latitude'] : '',
'lng'       => (isset( $data['location_longitude'] ) and ! empty( $data['location_longitude'] )) ? $data['location_longitude'] : '',
'draggable' => true,
'infowindow_default_open' => (isset( $data['location_infowindow_default_open'] ) and ! empty( $data['location_infowindow_default_open'] )) ? $data['location_infowindow_default_open'] : '',
'animation' => (isset( $data['location_animation'] ) and ! empty( $data['location_animation'] )) ? $data['location_animation'] : '',
'infowindow_disable' => ( 'false' === @$infowindow_disable['hide_infowindow']),
'zoom'      => (isset( $data['location_zoom'] ) and ! empty( $data['location_zoom'] )) ? $data['location_zoom'] : '',
),
'categories'  => array( array(
'id'      => (isset($category->group_map_id)) ? $category->group_map_id : '',
'name'    => (isset($category->group_map_title)) ? $category->group_map_title : '',
'type'    => 'category',
'icon'    => $category_group_marker,
),
),
);
$map_data['page'] = 'edit_location';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
var map = $("#wpgmp_map").maps(<?php echo wp_json_encode( $map_data ); ?>).data('wpgmp_maps');
});
</script>
