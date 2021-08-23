<?php 
	/////////////////////////////////
	/* init plugin for version 3.0 */
	/////////////////////////////////
	if( !function_exists('gdlrs_hotel_event_init') ){
		function gdlrs_hotel_event_init(){
			gdlrs_hotel_set_list_dates();

			gdlrs_hotel_set_block_dates();
			gdlrs_hotel_set_hotel_availability();
		}
	}

	if( !function_exists('gdlrs_hotel_set_list_dates') ){
		function gdlrs_hotel_set_list_dates(){
			global $wpdb;

			$sql = "SELECT id, start_date, end_date from {$wpdb->prefix}gdlr_hostel_booking WHERE date_list IS NULL";
			$results = $wpdb->get_results($sql);

			foreach( $results as $result ){
				$wpdb->update($wpdb->prefix . 'gdlr_hostel_booking',
					array('date_list' => implode(',', gdlr_hotel_list_dates($result->start_date, $result->end_date))), 
					array('id' => $result->id),
					array('%s'), 
					array('%d')
				);
			}
		}
	}
	if( !function_exists('gdlr_hotel_list_dates') ){
		function gdlr_hotel_list_dates( $checkin, $checkout ){
			$ret = array();

			$from = new DateTime($checkin);
			$to = new DateTime($checkout);
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);

			foreach($periods as $period){
				$ret[] = $period->format('Y-m-d');
			}

			return $ret;
		}
	}
	//////////////////////////////////////////
	//////////////////////////////////////////
	
	// check the hotel block dates
	add_action('gdlr_save_hostel_option', 'gdlrs_hotel_set_block_dates');
	if( !function_exists('gdlrs_hotel_set_block_dates') ){
		function gdlrs_hotel_set_block_dates(){
			$hostel_option = get_option('gdlr_hostel_option', array());

			// assign the block date first
			if( !empty($hostel_option['block-date']) ){
				$block_dates = gdlr_hotel_list_date_format($hostel_option['block-date']);	
				
				update_option('gdlrs-hotel-block-dates', $block_dates);

				gdlrs_hotel_set_hotel_availability('saved');
			}

			return array();

		} // gdlr_hotel_get_block_dates
	}

	// set all hotel availability 
	if( !function_exists('gdlrs_hotel_set_hotel_availability') ){
		function gdlrs_hotel_set_hotel_availability( $action = 'new' ){

			// query post list
			$args = array('post_type' => 'hostel_room', 'suppress_filters' => false);
			$args['posts_per_page'] = '999';
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = 1;		
			$query = new WP_Query( $args );

			$count = 1;
			$room_paid = array();
			$room_book = array();
			while( $query->have_posts() ){ $query->the_post();

				if( $action == 'new' ){
					$temp_room_paid = gdlrs_set_room_availability(get_the_ID(), 'paid');
					$temp_room_book = gdlrs_set_room_availability(get_the_ID(), 'booking');
				}else{
					$temp_room_paid = get_post_meta(get_the_ID(), 'hostel_paid_not_available', true);
					$temp_room_paid = is_array($temp_room_paid)? $temp_room_paid: array();
					$temp_room_book = get_post_meta(get_the_ID(), 'hostel_booking_not_available', true);
					$temp_room_book = is_array($temp_room_book)? $temp_room_book: array();
				}

				if( $count == 1 ){
					$room_paid = $temp_room_paid;
					$room_book = $temp_room_book;
				}else{
					$room_paid = array_intersect($room_paid, $temp_room_paid);
					$room_book = array_intersect($room_book, $temp_room_book);
				}
				
				$count ++;
			}
			wp_reset_postdata();

			$block_room = get_option('gdlrs-hotel-block-dates', array());
			$room_paid = array_merge($block_room, $room_paid);
			$room_paid = array_unique($room_paid);
			update_option('gdlrs-hotel-unavailable-room-paid', $room_paid);

			$room_book = array_merge($block_room, $room_book);
			$room_book = array_unique($room_book);
			update_option('gdlrs-hotel-unavailable-room-booking', $room_book);
		}
	}

	// list date from the date format ( * and to )
	if( !function_exists('gdlr_hotel_list_date_format') ){
		function gdlr_hotel_list_date_format( $date_set, $calculated_year = 2 ){
			$date_list = array();
			$current_date = date('Y-m-d');

			$date_set = array_map('trim', explode(',', $date_set));
			
			// split the "to" criteria 
			foreach( $date_set as $key => $date_format ){
				if( strpos($date_format, 'to') !== false ){
					unset($date_set[$key]);

					$date_array = array_map('trim', explode('to', $date_format));
					
					$from_date = explode('-', $date_array[0]);
					$to_date = explode('-', $date_array[1]);

					$date_set[] = "{$from_date[0]}-{$from_date[1]}-{$from_date[2]}";
					while( ($from_date[0] < $to_date[0]) || ($from_date[1] < $to_date[1]) || ($from_date[2] < $to_date[2]) ){	
						
						if( $from_date[2] == '*' || $from_date[2] == '31' ){
							$from_date[2] = ($from_date[2] == '31')? '01': $from_date[2];

							if( $from_date[1] == '*' || $from_date[1] == '12' ){
								$from_date[1] = ($from_date[1] == '12')? '01': $from_date[1];

								if( $from_date[0] != '*' ){
									$from_date[0] = sprintf("%04s", intval($from_date[0]) + 1);
								}
							}else{
								$from_date[1] = sprintf("%02s", intval($from_date[1]) + 1);
							}
						}else{
							$from_date[2] = sprintf("%02s", intval($from_date[2]) + 1);
						}

						$date_set[] = "{$from_date[0]}-{$from_date[1]}-{$from_date[2]}";
					}
				}
			}

			foreach( $date_set as $date_format ){

				// change * to date list
				if( strpos($date_format, '*') !== false ){

					$date_array = explode('-', $date_format);

					// list all years
					if( $date_array[0] == '*' ){
						$date_array[0] = array();
						$current_year = date('Y');
						for( $i = 0; $i<=$calculated_year; $i++ ){
							$date_array[0][] = $current_year + $i;
						}
					}else{
						$date_array[0] = array($date_array[0]);
					}

					// list all month
					if( $date_array[1] == '*' ){
						$date_array[1] = array();
						for( $i = 1; $i<=12; $i++ ){
							$date_array[1][] = $i;
						}
					}else{
						$date_array[1] = array($date_array[1]);
					}

					// list all dates
					if( $date_array[2] == '*' ){
						$date_array[2] = array();
						for( $i = 1; $i<=31; $i++ ){
							$date_array[2][] = $i;
						}
					}else{
						$date_array[2] = array($date_array[2]);
					}

					foreach( $date_array[0] as $year ){
						foreach( $date_array[1] as $month ){
							$month = sprintf("%02s", $month);
							foreach( $date_array[2] as $day ){
								$day = sprintf("%02s", $day);
								$temp_date =  "{$year}-{$month}-{$day}";
								if( $current_date <= $temp_date ){
									$date_list[] = $temp_date;
								} 
							}
						}
					}

				}else{
					if( $current_date <= $date_format ){
						$date_list[] = $date_format;
					}
				}

			}
			$date_list = array_unique($date_list);

			return $date_list;
		} // gdlr_hotel_list_date_format
	}

	// set the single room availability 
	if( !function_exists('gdlrs_set_room_availability') ){
		function gdlrs_set_room_availability( $room_id, $preserve_after = 'paid' ){
			global $wpdb, $hostel_option;

			$not_avail_dates = array();
			$current_date = date('Y-m-d');
			$room_amount = get_post_meta($room_id, 'gdlr_room_amount', true);

			// block dates
			$block_room = get_post_meta($room_id, 'gdlr_block_date', true);
			$not_avail_dates = gdlr_hotel_list_date_format($block_room);
			
			// get room id ( for multi lingual )
			$room_ids = '';
			if( function_exists('pll_get_post_translations') ){
				$pll_translations = pll_get_post_translations($room_id);
				foreach( $pll_translations as $translation ){
					$room_ids .= empty($room_ids)? '': ',';
					$room_ids .= '\'' . $translation . '\'';
				}
			}else if( !empty($sitepress) ){
				$trid = $sitepress->get_element_trid($room_id, 'post_hostel_room');
				$translations = $sitepress->get_element_translations($trid,'post_hostel_room');
				foreach( $translations as $translation ){
					$room_ids .= empty($room_ids)? '': ',';
					$room_ids .= '\'' . $translation->element_id . '\'';
				}
			}else{
				$room_ids = '\'' . $room_id . '\'';
			}

			// check the date which the room is full
			$sql  = "SELECT payment_table.id, booking_table.date_list ";
			$sql .= "FROM {$wpdb->prefix}gdlr_hostel_booking AS booking_table, {$wpdb->prefix}gdlr_hostel_payment AS payment_table WHERE ";
			$sql .= "payment_table.id = booking_table.payment_id AND ";
			if( $preserve_after == 'paid' ){ 
				$sql .= "payment_table.payment_status = 'paid' AND ";
			}else{
				$sql .= "payment_table.payment_status != 'pending' AND ";
			}
			$sql .= "booking_table.room_id IN ({$room_ids}) AND ";
			$sql .= "booking_table.end_date > '{$current_date}' ";

			$results = $wpdb->get_results($sql);

			$booked_room = array();
			foreach( $results as $result ){
				$date_list = explode(',', $result->date_list);
				foreach( $date_list as $booked_date ){
					$booked_room[$booked_date] = empty($booked_room[$booked_date])? 1: $booked_room[$booked_date]+1;

					if( $booked_room[$booked_date] >= $room_amount ){
						$not_avail_dates[] = $booked_date;
					} 
				}
			}
			$not_avail_dates = array_unique($not_avail_dates);

			update_post_meta($room_id, 'hostel_' . $preserve_after . '_not_available', $not_avail_dates);

			return $not_avail_dates;
		}
	}
	
	// update single room availability 
	add_action('gdlrs_update_room_availability', 'gdlrs_update_room_availability');
	if( !function_exists('gdlrs_update_room_availability') ){
		function gdlrs_update_room_availability( $room_id ){

			// query post list
			$args = array('post_type' => 'hostel_room', 'suppress_filters' => false);
			$args['posts_per_page'] = '999';
			$args['post__not_in'] = array($room_id);
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = 1;		
			$query = new WP_Query( $args );

			$room_paid = gdlrs_set_room_availability($room_id, 'paid');
			$room_book = gdlrs_set_room_availability($room_id, 'booking');
			while( $query->have_posts() ){ $query->the_post();
				$temp_paid = get_post_meta(get_the_ID(), 'hostel_paid_not_available', true);
				$temp_paid = is_array($temp_paid)? $temp_paid: array();
				$temp_book = get_post_meta(get_the_ID(), 'hostel_booking_not_available', true);
				$temp_book = is_array($temp_book)? $temp_book: array();

				$room_paid = array_intersect($room_paid, $temp_paid);
				$room_book = array_intersect($room_book, $temp_book);

				if( empty($room_paid) && empty($room_book) ){
					break;
				}
			}
			wp_reset_postdata();

			$block_room = get_option('gdlrs-hotel-block-dates', array());
			$room_paid = array_merge($block_room, $room_paid);
			$room_paid = array_unique($room_paid);
			update_option('gdlrs-hotel-unavailable-room-paid', $room_paid);

			$room_book = array_merge($block_room, $room_book);
			$room_book = array_unique($room_book);
			update_option('gdlrs-hotel-unavailable-room-booking', $room_book);

		}
	}
	add_action('save_post', 'gdlrs_hotel_save_room_option_event', 999);
	add_action('pre_post_update', 'gdlrs_hotel_save_room_option_event', 999);
	if( !function_exists('gdlrs_hotel_save_room_option_event') ){
		function gdlrs_hotel_save_room_option_event( $post_id ){
			$post_type = get_post_type($post_id);

			if( $post_type == 'hostel_room' ){
				gdlrs_update_room_availability($post_id);
			}
		}
	}	

	add_action('gdlrs_update_transaction_availability', 'gdlrs_update_transaction_availability', 10, 2);
	if( !function_exists('gdlrs_update_transaction_availability') ){
		function gdlrs_update_transaction_availability( $data, $type = 'tid' ){
			global $wpdb;
			
			if( $type == 'tid' ){
				$tid = $data;
				$sql  = "SELECT DISTINCT room_id ";
				$sql .= "FROM {$wpdb->prefix}gdlr_hostel_booking WHERE ";
				$sql .= "payment_id = {$tid}";

				$results = $wpdb->get_results($sql);
			}else{
				$results = $data;
			}

			if( !empty($results) ){
				$count = sizeof($results);
				foreach( $results as $result ){
					if( $count == 1 ){
						gdlrs_update_room_availability($result->room_id);
					}else{
						gdlrs_set_room_availability($result->room_id, 'paid');
						gdlrs_set_room_availability($result->room_id, 'booking');
					}

					$count--;
				}
			}

		}
	}
	