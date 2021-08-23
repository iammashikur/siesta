<?php
	/*	
	*	Goodlayers Booking File
	*/

	add_filter('template_include', 'gdlrs_hostel_booking_template');
	if( !function_exists('gdlrs_hostel_booking_template') ){
		function gdlrs_hostel_booking_template( $template ){
			global $hostel_option;
			if( isset($_GET[$hostel_option['booking-slug']]) ){
				add_filter('document_title_parts', 'gdlr_hostel_booking_title', 11);
				return dirname(dirname(__FILE__)) . '/single-booking.php';
			}
			return $template;
		}
	}
	if( !function_exists('gdlr_hostel_booking_title') ){
		function gdlr_hostel_booking_title( $title ){
			$title['tagline'] = $title['title'];
			$title['title'] = esc_html__('Booking', 'gdlr-hotel');
			return $title;
		}
	}
		
	
	add_filter('body_class', 'gdlrs_booking_template_class');
	if( !function_exists('gdlrs_booking_template_class') ){
		function gdlrs_booking_template_class( $classes ){
			global $hostel_option;
			if( isset($_GET[$hostel_option['booking-slug']]) ){
				$classes[] = 'single-booking';
			}
			return $classes;
		}
	}

?>