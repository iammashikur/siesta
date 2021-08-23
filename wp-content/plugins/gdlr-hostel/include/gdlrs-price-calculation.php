<?php
	/*	
	*	Goodlayers Price Calculation File
	*/	
	
	if( !function_exists('gdlrs_calculate_service_price') ){
		function gdlrs_calculate_service_price($data){
			$service_prices = array('total'=>0);
			
			// get the necessary variable
			$day_diff = floor((strtotime($data['gdlr-check-out']) - strtotime($data['gdlr-check-in'])) / 86400);
			$guest = $data['gdlr-room-number'];
			
			foreach( $data['service'] as $key => $service ){
				$option = json_decode(gdlr_decode_preventslashes(get_post_meta($service, 'post-option', true)), true);
				$service_prices[$service] = array(
					'title' => get_the_title($service),
					'price' => floatval($option['price'])
				);
				
				if( $option['service-type'] == 'parking-service' ){
					if( $option['unit'] == 'night' ){
						$service_prices[$service]['price'] *= $day_diff;
					}
					
					if( $option['car'] == 'car' ){
						$car_amount = empty($data['service-amount'][$key])? 1: $data['service-amount'][$key];
						$service_prices[$service]['title'] .= '<span class="gdlr-sep">/</span>' . $car_amount . ' ';
						if( !empty($option['car-unit']) ){
							$service_prices[$service]['title'] .= strtolower($option['car-unit']);
						}else{
							$service_prices[$service]['title'] .= __('cars', 'gdlr-hostel');
						}
						$service_prices[$service]['price'] *= $car_amount;
					}
				}else if( $option['service-type'] == 'regular-service' ){
					if( $option['unit'] == 'night' ){
						$service_prices[$service]['price'] *= $day_diff;
					}
					
					if( $option['per'] == 'guest' ){
						$service_prices[$service]['title'] .= '<span class="gdlr-sep">/</span>' . $guest . ' ' . __('guests', 'gdlr-hostel') ;
						$service_prices[$service]['price'] *= $guest;
					}else if( $option['per'] == 'room' ){
						$service_prices[$service]['title'] .= '<span class="gdlr-sep">/</span>' . sizeOf($data['gdlr-room-id']) . ' ' . __('room', 'gdlr-hostel') ;
						$service_prices[$service]['price'] *= sizeOf($data['gdlr-room-id']);
					}
				}
				
				$service_prices['total'] += $service_prices[$service]['price'];
			}
			return $service_prices;
		}
	}
	
	
	if( !function_exists('gdlr_split_date') ){
		function gdlr_split_date($from, $to){
			$ret = array();
			
			$from = new DateTime($from);
			$to = new DateTime($to);
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);
			
			foreach($periods as $period){
				$ret[$period->format('Y-m-d')] = false;
			}
			
			return $ret;
		}
	}
	
	if( !function_exists('gdlr_is_ss') ){
		function gdlr_is_ss($selected_date, $ss_price) {
			if( !empty($ss_price['date']) ){
				$date_set = array_map('trim', explode(',', $ss_price['date']));
				foreach($date_set as $date){
					// for date_range
					if( strpos($date, 'to') !== false ){
						if( strpos($date, '*') !== false ){
							$date = str_replace('-*-', substr($selected_date, 4, 4),$date);
							$date = str_replace('*-', substr($selected_date, 0, 5),$date);
							$date = str_replace('-*', substr($selected_date, 7, 3),$date);
						}
						$date_range = array_map('trim', explode('to', $date));
						
						if( strcmp($date_range[0], $selected_date) <= 0 && strcmp($selected_date, $date_range[1]) <= 0 ) return true;
					}else{
						if( strpos($date, '*') !== false ){
							$date = str_replace('-*-', substr($selected_date, 4, 4),$date);
							$date = str_replace('*-', substr($selected_date, 0, 5),$date);
							$date = str_replace('-*', substr($selected_date, 7, 3),$date);
						}
						if( $selected_date == $date ) return true;
					}	
				}
			}
			return false;
		}
	}

	if( !function_exists('gdlrs_get_booking_price') ){
		function gdlrs_get_booking_price($option){
			$ret = array(
				'weekday-night'=>0, 'weekend-night'=>0,
				'ss_weekday-night'=>0, 'ss_weekend-night'=>0,
			
				'base'=>0, 'base-weekend'=>0,
				'ss_base'=>0, 'ss_base-weekend'=>0,

				'cnd'=>0, 'cnd-night'=>0, 'cnd-discount'=>0,
				'sub-total'=>0, 

				'total'=>0
			);
			
			// get the loop (date) counter
			$from = new DateTime($option['data']['check-in']);
			$to = new DateTime($option['data']['check-out']);
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);
			
			// calculate the price
			$workingDays = array(1, 2, 3, 4, 5);
			$ss_prices = json_decode(gdlr_decode_preventslashes($option['special-season-pricing']), true);;
			foreach($periods as $period){
				$weekday = (in_array($period->format('N'), $workingDays))? true: false;
				
				$special_season = false;
				foreach($ss_prices as $ss_price){
					if( gdlr_is_ss($period->format('Y-m-d'), $ss_price) ){
						if($weekday){
							$ret['ss_base'] += floatval($ss_price['bpwd']);
							$ret['ss_weekday-night']++;
						}else{
							$ret['ss_base-weekend'] += floatval($ss_price['bpwe']);
							$ret['ss_weekend-night']++;
						}
						$special_season = true;
						break;
					}
				}
				
				if( !$special_season ){
					if($weekday){
						$ret['base'] += floatval($option['room-base-price']);
						$ret['weekday-night']++;
					}else{
						$ret['base-weekend'] += floatval($option['room-base-price-weekend']);
						$ret['weekend-night']++;
					}
				}
			}

			// calculate total price
			$ret['total']  = $ret['base'] + $ret['base-weekend'];
			$ret['total'] += $ret['ss_base'] + $ret['ss_base-weekend'];
			$ret['sub-total'] = $ret['total'];

			// cnd
			if( !empty($option['consecutive-night-discount']) ){
				$stay_period = intval($option['data']['gdlr-night']);
					
				$night_no = 0;
				$discount = 0;
				$cnds = json_decode($option['consecutive-night-discount'], true);
				foreach( $cnds as $cnd ){
					if( $stay_period >= $cnd['night'] ){
						if( $cnd['night'] > $night_no ){
							$night_no = $cnd['night'];
							$discount = $cnd['discount'];
						}
					}
				}

				if( !empty($discount) && !empty($night_no) ){
					$ret['cnd-night'] = $night_no;
					$ret['cnd-discount'] = $discount;

					$ret['cnd'] = ($ret['total'] * $discount) / 100;
					$ret['total'] -= $ret['cnd'];
				}
			}

			return $ret;
		}
	}
	
	// get price breakdown popup
	if( !function_exists('get_hostel_price_breakdown_popup') ){
		function get_hostel_price_breakdown_popup($post_option){
			$price = gdlrs_get_booking_price($post_option);
			
			$weekday_title  = '<span>x ' . $price['weekday-night'] . ' ' . __('Night', 'gdlr-hostel') . (($price['weekday-night']>1)? 's': '');
			$weekday_title .= ' ' . __('(Weekday)', 'gdlr-hostel') . '</span>';
			$weekend_title  = '<span>x ' . $price['weekend-night'] . ' ' . __('Night', 'gdlr-hostel') . (($price['weekday-night']>1)? 's': '');
			$weekend_title .= ' ' . __('(Weekend)', 'gdlr-hostel') . '</span>';				
			
			$ss_weekday_title  = '<span>x ' . $price['ss_weekday-night'] . ' ' . __('Night', 'gdlr-hostel') . (($price['ss_weekday-night']>1)? 's': '');
			$ss_weekday_title .= ' ' . __('(Weekday)', 'gdlr-hostel') . '</span>';
			$ss_weekend_title  = '<span>x ' . $price['ss_weekend-night'] . ' ' . __('Night', 'gdlr-hostel') . (($price['ss_weekday-night']>1)? 's': '');
			$ss_weekend_title .= ' ' . __('(Weekend)', 'gdlr-hostel') . '</span>';			
			
			$price_title = array(
				'base'=> __('Base Price', 'gdlr-hostel') . $weekday_title,
				'base-weekend'=> __('Base Price', 'gdlr-hostel') . $weekend_title,
				'ss_base'=> __('Special Season Base Price', 'gdlr-hostel') . $ss_weekday_title,
				'ss_base-weekend'=> __('Special Season Base Price', 'gdlr-hostel') . $ss_weekend_title
			);
			
			$ret  = '<div class="price-breakdown-wrapper">';
			$ret .= '<div class="price-breakdown-close"></div>';
			$ret .= '<div class="price-breakdown-content">';
			
			foreach( $price_title as $slug => $title ){
				if( !empty($price[$slug]) ){
					$ret .= '<div class="price-breakdown-info">';
					$ret .= '<span class="gdlr-head">' . $price_title[$slug] . '</span>';
					$ret .= '<span class="gdlr-tail">' . gdlr_hostel_money_format($price[$slug]) . '</span>';
					$ret .= '</div>';
				}
			}	

			if( !empty($price['cnd']) ){		
				$ret .= '<div class="price-breakdown-info">';
				$ret .= '<span class="gdlr-head">';
				$ret .= sprintf(__('%d Nights, %d%% Discount', 'gdlr-hostel'), $price['cnd-night'], $price['cnd-discount']);
				$ret .= '</span>';
				$ret .= '<span class="gdlr-tail">-' . gdlr_hotel_money_format($price['cnd']) . '</span>';
				$ret .= '</div>';		
			}		
			
			$ret .= '<div class="price-breakdown-total">';
			$ret .= '<span class="gdlr-head">' . __('Total', 'gdlr-hostel');
			$ret .= '<span class="">' . __('*vat is not included yet', 'gdlr-hostel') . '</span>';
			$ret .= '</span>';
			$ret .= '<span class="gdlr-tail">' . gdlr_hostel_money_format($price['total']) . '</span>';
			$ret .= '</div>';
			
			$ret .= '</div>'; // price-breakdown-content
			$ret .= '</div>'; // price-breakdown-wrapper
			
			return array('price-breakdown'=>$ret, 'total'=>$price['total']);
		}
	}	
	
	// get total price for one booking
	if( !function_exists('gdlrs_get_booking_total_price') ){
		function gdlrs_get_booking_total_price($data, $coupon = ''){	
			global $hostel_option;
			$total_price = 0;

			// group the customer by room
			$customer_rooms = array();
			for($i=0; $i<intval($data['gdlr-room-number']); $i++){
				$customer_rooms[$data['gdlr-room-id'][$i]] = empty($customer_rooms[$data['gdlr-room-id'][$i]])? 1: $customer_rooms[$data['gdlr-room-id'][$i]] + 1;
			}	
			
			foreach($customer_rooms as $room_id => $guest_num ){
				$post_option = json_decode(gdlr_decode_preventslashes(get_post_meta($room_id, 'post-option', true)), true);
				$post_option['data'] = array(
					'check-in'=> $data['gdlr-check-in'],
					'check-out'=> $data['gdlr-check-out'],
					'gdlr-night'=> $data['gdlr-night']
				);
				$price = gdlrs_get_booking_price($post_option);
				if( empty($post_option['room-type']) || $post_option['room-type'] == 'dorm' ){
					$price['total'] = $price['total'] * $guest_num;
				}
				$total_price += $price['total'];
			}
			
			// service
			if( !empty($data['service']) ){
				$services_price = gdlrs_calculate_service_price($data);
				$total_price += $services_price['total'];
			}
			
			// coupon discount
			$discount_price = gdlrs_get_coupon_discount($data, $coupon);
			$total_price -= $discount_price;
			if( $total_price < 0 ) $total_price = 0;
			
			if( !empty($hostel_option['booking-vat-amount']) ){
				$vat_amount = ($total_price * floatval($hostel_option['booking-vat-amount'])) / 100;
				$total_price += $vat_amount;
			}
			
			$pay_amount = $total_price;
			if( !empty($hostel_option['booking-deposit-amount']) && $data['pay_deposit'] == 'true' ){
				$pay_amount = ($total_price * floatval($hostel_option['booking-deposit-amount'])) / 100;
			}
			return array('total_price'=> $total_price, 'pay_amount'=> round($pay_amount, 2));	
		}
	}

	add_action('wp_ajax_gdlrs_hotel_coupon_check', 'gdlrs_get_coupon_status');
	add_action('wp_ajax_nopriv_gdlrs_hotel_coupon_check', 'gdlrs_get_coupon_status');
	if( !function_exists('gdlrs_get_coupon_status') ){
		function gdlrs_get_coupon_status(){
			if( !empty($_POST['data']) ){
				parse_str($_POST['data'], $data);
			}

			$coupon = $_POST['coupon_code'];
			if( empty($coupon) ){
				die(json_encode(array(
					'status' => 'success',
					'message' => ''
				)));
			}

			$posts = get_posts(array('post_type'=>'coupon', 'posts_per_page'=>1, 'meta_key'=>'gdlr-coupon-code', 'meta_value'=>$coupon));
			if( sizeOf($posts) <= 0 ){
				die(json_encode(array(
					'status' => 'success',
					'message' => esc_html__('This coupon is invalid.', 'gdlr-hostel')
				)));
			}

			$post_option = json_decode(gdlr_decode_preventslashes(get_post_meta($posts[0]->ID, 'post-option', true)), true);
			
			// expire date
			if( !empty($post_option['coupon-expiry']) && strtotime(date("Y-m-d")) > strtotime($post_option['coupon-expiry']) ){
				die(json_encode(array(
					'status' => 'success',
					'message' => esc_html__('This coupon has expired.', 'gdlr-hostel')
				)));
			}
			
			// available num
			$coupon_num = get_post_meta($posts[0]->ID, 'gdlr-coupon-num', true);
			$coupon_num = empty($coupon_num)? 0: intval($coupon_num);
			if( $post_option['coupon-amount'] != -1 && $coupon_num >= $post_option['coupon-amount'] ){
				die(json_encode(array(
					'status' => 'success',
					'message' => esc_html__('This coupon has already been used.', 'gdlr-hostel')
				)));
			}
			
			// number of 
			$message = '';
			if( $post_option['coupon-discount-type'] == 'amount' ){
				$message .= gdlr_hostel_money_format($post_option['coupon-discount-amount']) . ' ' . esc_html__('discount', 'gdlr-hostel');
			}else{
				$message .= $post_option['coupon-discount-amount'] . '% ' . esc_html__('discount', 'gdlr-hostel');
			}

			if( !empty($post_option['specify-room']) ){
				$message .= esc_html__('( on specified room )', 'gdlr-hostel');
			}
			$message .= '.';

			die(json_encode(array(
				'status' => 'success',
				'message' => $message
			)));

		}
	}	
	
	if( !function_exists('gdlrs_get_coupon_discount') ){
		function gdlrs_get_coupon_discount($data, $coupon = '', $deduct_coupon = true){
			if( empty($coupon) ) return 0;
			
			$posts = get_posts(array('post_type'=>'coupon', 'posts_per_page'=>1, 'meta_key'=>'gdlr-coupon-code', 'meta_value'=>$coupon));
			$post_option = json_decode(gdlr_decode_preventslashes(get_post_meta($posts[0]->ID, 'post-option', true)), true);
			
			// expire date
			if( !empty($post_option['coupon-expiry']) && strtotime(date("Y-m-d")) > strtotime($post_option['coupon-expiry']) ) return 0;
			
			// available num
			$coupon_num = get_post_meta($posts[0]->ID, 'gdlr-coupon-num', true);
			$coupon_num = empty($coupon_num)? 0: intval($coupon_num);
			if( $post_option['coupon-amount'] != -1 && $coupon_num >= $post_option['coupon-amount'] ) return 0;
			if( $deduct_coupon ){
				update_post_meta($posts[0]->ID, 'gdlr-coupon-num', $coupon_num + 1);
			}

			// specify room
			if( !empty($post_option['specify-room']) ){
				$room_specify = explode(',', $post_option['specify-room']);
				foreach($data['gdlr-room-id'] as $key => $room_id){
					if( !in_array($room_id, $room_specify) ){
						unset($data['gdlr-room-id'][$key]);
					}
				}
			}
			if( empty($data['gdlr-room-id']) ) return 0;
			
			// number of 
			if( $post_option['coupon-discount-type'] == 'amount' ){
				return $post_option['coupon-discount-amount'];
			}else{
				// percent
				$total_price = 0;
				
				for($i=0; $i<sizeOf($data['gdlr-room-id']); $i++){
					$room_option = json_decode(gdlr_decode_preventslashes(get_post_meta($data['gdlr-room-id'][$i], 'post-option', true)), true);
					$room_option['data'] = array(
						'check-in'=> $data['gdlr-check-in'],
						'check-out'=> $data['gdlr-check-out'],
						'gdlr-night'=> $data['gdlr-night']
					);
					$price = gdlrs_get_booking_price($room_option);
					$total_price += $price['total'];
				}		

				// service
				if( !empty($data['service']) ){
					$services_price = gdlrs_calculate_service_price($data);
					$total_price += $services_price['total'];
				}

				return $total_price * floatval($post_option['coupon-discount-amount']) / 100;
			}
		}
	}

?>