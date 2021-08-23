<?php
/*
Plugin Name: Goodlayers Hostel Plugin
Plugin URI: 
Description: A HOSTEL ROOM Plugin To Use With Goodlayers Theme ( This plugin functionality might not working properly on another theme )
Version: 3.0.4
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/

// create necessary table upon activation
include_once('framework/gdlrs-table-management.php');
register_activation_hook(__FILE__, 'gdlr_hostel_create_booking_table');
register_activation_hook(__FILE__, 'gdlr_hostel_event_init');

include_once('framework/gdlrs-plugin-option.php');
include_once('framework/gdlrs-transaction.php');
include_once('framework/gdlrs-summary-report.php');
include_once('framework/gdlrs-booking-option.php');
include_once('framework/gdlrs-room-option.php');	
include_once('framework/gdlr-service-option.php');	
include_once('framework/gdlr-coupon-option.php');	

include_once('include/gdlrs-paypal-payment.php');
include_once('include/gdlrs-stripe-payment.php');
include_once('include/gdlrs-paymill-payment.php');
include_once('include/gdlrs-authorize-payment.php');
if( !class_exists('Stripe') ){
	include_once('include/payment-api/stripe-php/lib/Stripe.php');
}
if( !function_exists('autoload') ){
	include_once('include/payment-api/paymill-php/autoload.php');
}
include_once('include/payment-api/authorize-php/autoload.php');
	
include_once('include/gdlrs-utility.php');
include_once('include/gdlrs-room-item.php');
include_once('include/gdlrs-booking-item.php');
include_once('include/gdlrs-reservation-bar.php');
include_once('include/gdlrs-price-calculation.php');
include_once('include/gdlrs-page-builder-sync.php');
include_once('include/gdlrs-hotel-event.php');



// action to loaded the plugin translation file
add_action('init', 'gdlr_hostel_init', 1);
if( !function_exists('gdlr_hostel_init') ){
	function gdlr_hostel_init() {
		global $hostel_option;
		$hostel_option = get_option('gdlr_hostel_option', array());
	}
}
add_action('plugins_loaded', 'gdlr_hostel_load_text_domain');
if( !function_exists('gdlr_hostel_load_text_domain') ){
	function gdlr_hostel_load_text_domain() {
		load_plugin_textdomain( 'gdlr-hostel', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' ); 
	}
}

// include script for front end
add_action( 'wp_enqueue_scripts', 'gdlr_hotel_include_script' );
if( !function_exists('gdlr_hotel_include_script') ){
	function gdlr_hotel_include_script(){
		wp_enqueue_style('hotel-style', plugins_url('gdlr-hotel.css', __FILE__) );
		
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('hotel-script', plugins_url('gdlr-hotel.js', __FILE__), array(), '1.0.0', true );
		
		// ref : https://gist.github.com/clubduece/4053820
		global $wp_locale;

		$aryArgs = array(
			'closeText'         => __( 'Done', 'gdlr-hostel' ),
			'currentText'       => __( 'Today', 'gdlr-hostel' ),
			'monthNames'        => gdlr_strip_array_indices( $wp_locale->month ),
			'monthNamesShort'   => gdlr_strip_array_indices( $wp_locale->month_abbrev ),
			'monthStatus'       => __( 'Show a different month', 'gdlr-hostel' ),
			'dayNames'          => gdlr_strip_array_indices( $wp_locale->weekday ),
			'dayNamesShort'     => gdlr_strip_array_indices( $wp_locale->weekday_abbrev ),
			'dayNamesMin'       => gdlr_strip_array_indices( $wp_locale->weekday_initial ),
			'firstDay'          => get_option( 'start_of_week' )
		);
	 
		// Pass the localized array to the enqueued JS
		wp_localize_script( 'hotel-script', 'objectL10n', $aryArgs );	
	}
}
if( !function_exists('gdlr_strip_array_indices') ){
	function gdlr_strip_array_indices( $ArrayToStrip ) {
		foreach( $ArrayToStrip as $objArrayItem) {
			$NewArray[] =  $objArrayItem;
		}

		return( $NewArray );
	}
}

// translate booking page link
add_filter('pll_the_language_link', 'gdlrs_hotel_pll_link', 10, 2);
if( !function_exists('gdlrs_hotel_pll_link') ){
	function gdlrs_hotel_pll_link($url, $slug) {
		global $hostel_option;
		
		if( isset($_GET[$hostel_option['booking-slug']]) ){
			return add_query_arg(array($hostel_option['booking-slug']=>''), $url);
		}
	    return $url;
	}
}