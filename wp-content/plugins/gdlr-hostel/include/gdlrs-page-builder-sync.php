<?php
	
	// add hotel availability item
	add_filter('gdlr_page_builder_option', 'gdlr_register_hostel_availability_item');
	if( !function_exists('gdlr_register_hostel_availability_item') ){
		function gdlr_register_hostel_availability_item( $page_builder = array() ){
			global $gdlr_spaces;
		
			$page_builder['content-item']['options']['hostel-availability'] = array(
				'title'=> __('Hostel Room Availability', 'gdlr_translate'), 
				'type'=>'item',
				'options'=> array_merge(gdlr_page_builder_title_option(true), array(	
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr_translate'),
						'type' => 'text',
						'default' => $gdlr_spaces['bottom-item'],
						'description' => __('Spaces after ending of this item', 'gdlr_translate')
					),														
				))
			);
			
			return $page_builder;
		}
	}
	add_action('gdlr_print_item_selector', 'gdlr_check_hostel_availability_item', 10, 2);
	if( !function_exists('gdlr_check_hostel_availability_item') ){
		function gdlr_check_hostel_availability_item( $type, $settings = array() ){
			if($type == 'hostel-availability'){
				echo gdlr_print_hostel_availability_item( $settings );
			}
		}
	}
	
	// print room item
	if( !function_exists('gdlr_print_hostel_availability_item') ){
		function gdlr_print_hostel_availability_item( $settings = array() ){	

			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $gdlr_spaces, $hostel_option;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $gdlr_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$current_date = current_time('Y-m-d');
			$minimum_night = empty($hostel_option['minimum-night'])? 1: intval($hostel_option['minimum-night']);
			$next_date = date('Y-m-d', strtotime($current_date . "+{$minimum_night} days"));
			$value = array(
				'gdlr-check-in' => $current_date,
				'gdlr-night' => $minimum_night,
				'gdlr-check-out' => $next_date,
				'gdlr-room-number' => 1
			);
			
			$ret  = gdlr_get_item_title($settings);	

			$ret .= '<div class="gdlr-hotel-availability-wrapper';
			if( !empty($hostel_option['enable-hotel-branch']) && $hostel_option['enable-hotel-branch'] == 'enable' ){
				$ret .= ' gdlr-hotel-branches-enable';
			}
			$ret .= '" ' . $margin_style . $item_id . ' >';
			$ret .= '<form class="gdlr-hotel-availability gdlr-hostel gdlr-item" id="gdlr-hotel-availability" method="post" action="' . esc_url(add_query_arg(array($hostel_option['booking-slug']=>''), (function_exists('pll_home_url')? pll_home_url(): home_url('/')))) . '" >';
			if( !empty($hostel_option['enable-hotel-branch']) && $hostel_option['enable-hotel-branch'] == 'enable' ){
				$ret .= gdlrs_get_reservation_branch_combobox(array(
					'title'=>__('Hotel Branches', 'gdlr-hostel'),
					'slug'=>'gdlr-hotel-branches',
					'id'=>'gdlr-hotel-branches',
					'value'=>''
				));
			}
			
			$ret .= gdlrs_get_reservation_datepicker(array(
				'title'=>__('Check In', 'gdlr-hostel'),
				'slug'=>'gdlr-check-in',
				'id'=>'gdlr-check-in',
				'value'=>$value['gdlr-check-in']
			));
			$ret .= gdlr_get_reservation_combobox(array(
				'title'=>__('Nights', 'gdlr-hostel'),
				'slug'=>'gdlr-night',
				'id'=>'gdlr-night',
				'value'=>$value['gdlr-night']
			), $minimum_night);
			$ret .= gdlrs_get_reservation_datepicker(array(
				'title'=>__('Check Out', 'gdlr-hostel'),
				'slug'=>'gdlr-check-out',
				'id'=>'gdlr-check-out',
				'minimum-night'=>$minimum_night,
				'value'=>$value['gdlr-check-out']
			));
			$ret .= gdlr_get_reservation_combobox(array(
				'title'=>__('Guests', 'gdlr-hostel'),
				'slug'=>'gdlr-room-number',
				'id'=>'gdlr-room-number',
				'value'=>$value['gdlr-room-number']
			), 1);
			$ret .= '<div class="gdlr-hotel-availability-submit" >';
			$ret .= '<input type="hidden" name="hotel_data" value="1" >';
			$ret .= '<input type="submit" class="gdlr-reservation-bar-button gdlr-button with-border" value="' . __('Check Availability', 'gdlr-hostel') . '" >';
			$ret .= '</div>';
			
			$ret .= '<div class="clear"></div>';
			$ret .= '</form>';
			$ret .= '</div>';
			
			return $ret;
		}
	}
	
?>