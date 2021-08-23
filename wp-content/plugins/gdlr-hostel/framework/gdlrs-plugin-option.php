<?php
	/*	
	*	Goodlayers Plugin Option File
	*/
	
	// create admin menu
	add_action('admin_menu', 'gdlr_hostel_add_admin_menu', 100);
	if( !function_exists('gdlr_hostel_add_admin_menu') ){
		function gdlr_hostel_add_admin_menu(){
			$page = add_submenu_page('hostel_option', __('Transaction', 'gdlr-hostel'), __('Transaction', 'gdlr-hostel'), 
				'manage_hostel', 'hostel-transaction' , 'gdlr_hostel_transaction_option');			
			add_action('admin_print_styles-' . $page, 'gdlrs_transaction_option_style');	
			add_action('admin_print_scripts-' . $page, 'gdlrs_transaction_option_script');	

			$page = add_submenu_page('hostel_option', __('Summary Report', 'gdlr-hostel'), __('Summary Report', 'gdlr-hostel'), 
				'manage_hostel', 'hostel-summary-report' , 'gdlr_hostel_summary_report');			
			add_action('admin_print_styles-' . $page, 'gdlrs_transaction_option_style');	
			add_action('admin_print_scripts-' . $page, 'gdlrs_transaction_option_style');				
		}	
	}
	if( !function_exists('gdlrs_transaction_option_style') ){
		function gdlrs_transaction_option_style(){
			wp_enqueue_style('gdlr-alert-box', plugins_url('transaction-style.css', __FILE__));		
			wp_enqueue_style('font-awesome', GDLR_PATH . '/plugins/font-awesome-new/css/font-awesome.min.css');		
		}
	}
	if( !function_exists('gdlrs_transaction_option_script') ){
		function gdlrs_transaction_option_script(){
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('gdlr-alert-box', plugins_url('transaction-script.js', __FILE__));
		}
	}
	
	add_action('init', 'gdlr_create_hostel_admin_option', 99);
	if( !function_exists('gdlr_create_hostel_admin_option') ){
		function gdlr_create_hostel_admin_option(){
			global $hostel_option, $gdlr_sidebar_controller;
		
			new gdlr_admin_option( 
				
				// admin option attribute
				array(
					'page_title' => __('Hostel Option', 'gdlr-hostel'),
					'menu_title' => __('Hostel Option', 'gdlr-hostel'),
					'menu_slug' => 'hostel_option',
					'save_option' => 'gdlr_hostel_option',
					'role' => 'edit_theme_options',
					'position' => 84,
				),
					  
				// admin option setting
				array(
					// general menu
					'general' => array(
						'title' => __('General', 'gdlr-hostel'),
						'icon' => GDLR_PATH . '/include/images/icon-general.png',
						'options' => array(
							
							'general-option' => array(
								'title' => __('General Option', 'gdlr-hostel'),
								'options' => array(
									'booking-money-format' => array(
										'title' => __('Money Display Format', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '$NUMBER',
									),
									'enable-hotel-branch' => array(
										'title' => __('Enable Hotel Branch ( Using Category )', 'gdlr-hostel'),
										'type' => 'checkbox',	
										'default' => 'disable'
									),
									'preserve-booking-room' => array(
										'title' => __('Preserve The Room After', 'gdlr-hostel'),
										'type' => 'combobox',	
										'options' => array(
											'paid' => __('Paid for room', 'gdlr-hostel'),
											'booking' => __('Booking for room', 'gdlr-hostel')
										)
									),
									'booking-price-display' => array(
										'title' => __('Booking Price Display', 'gdlr-hostel'),
										'type' => 'combobox',	
										'options' => array(
											'start-from' => __('Start From', 'gdlr-hostel'),
											'full-price' => __('Full Price', 'gdlr-hostel')
										)
									),
									'booking-vat-amount' => array(
										'title' => __('Vat Amount', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '8',
										'description' => __('Input only number ( as percent )', 'gdlr-hostel') . 
											__('Filling 0 to disable this option out.', 'gdlr-hostel'),
									),
									'block-date' => array(
										'title' => __('Block Date', 'gdlr-hostel'),
										'type' => 'textarea',	
										'default' => '',
										'description' => __('Fill the date in yyyy-mm-dd format. Use * for recurring date, separated each date using comma, use the word \'to\' for date range. Ex. *-12-25 to *-12-31 means special season is running every Christmas to New Year\'s Eve every year.', 'gdlr-hostel')
									),
									'booking-deposit-amount' => array(
										'title' => __('Deposit Amount', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '20',
										'description' => __('Allow customer to pay part of price for booking the room ( as percent ).', 'gdlr-hostel') . 
											__('Filling 0 to disable this option out.', 'gdlr-hostel'),
									),
									'payment-method' => array(
										'title' => __('Payment Method', 'gdlr-hostel'),
										'type' => 'combobox',	
										'options' => array(
											'contact' =>  __('Only Contact Form', 'gdlr-hostel'),
											'instant' =>  __('Include Instant Payment', 'gdlr-hostel'),
										)
									),
									'instant-payment-method' => array(
										'title' => __('Instant Payment Method', 'gdlr-hostel'),
										'type' => 'multi-combobox',	
										'options' => array(
											'paypal' =>  __('Paypal', 'gdlr-hostel'),
											'stripe' =>  __('Stripe', 'gdlr-hostel'),
											'paymill' =>  __('Paymill', 'gdlr-hostel'),
											'authorize' =>  __('Authorize.Net', 'gdlr-hostel'),
										),
										'wrapper-class' => 'payment-method-wrapper instant-wrapper',
										'description' => __('Leaving this field blank will display all available payment method.', 'gdlr-hostel')
									),
								)
							),
							'booking-settings' => array(
								'title' => __('Booking Page Settings', 'gdlr-hostel'),
								'options' => array(
									'transaction-per-page' => array(
										'title' => __('Transaction Per Page', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '30',
									),
									'booking-slug' => array(
										'title' => __('Booking Page Slug', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => 'booking',
										'description' => __('Please only fill lower case character with no special character here.', 'gdlr-hostel')
									),
									'enable-checkin-time' => array(
										'title' => __('Enable Checkin Time', 'gdlr-hostel'),
										'type' => 'combobox',
										'options' => array(
											'yes' => __('Yes', 'gdlr-hostel'), 
											'no' => __('No', 'gdlr-hostel') 
										),	
										'default' => 'no',
									),
									'checkin-time-hour' => array(
										'title' => __('Start Checkin Time ( HOURS )', 'gdlr-hostel'),
										'type' => 'combobox',
										'options' => apply_filters('gdlr-hotel-reservation-hours', array(
											'01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', 
											'06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', 
											'11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', 
											'16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', 
											'21' => '21', '22' => '22', '23' => '23', '24' => '24', 
										)),
										'default' => '15',
										'wrapper-class' => 'enable-checkin-time-wrapper yes-wrapper'
									),
									// 'checkin-time-min' => array(
									// 	'title' => __('Checkin Time ( MINS )', 'gdlr-hostel'),
									// 	'type' => 'combobox',	
									// 	'options' => apply_filters('gdlr-hotel-reservation-mins', array(
									// 		'00' => '00', '15' => '15', '30' => '30', '45' => '45',
									// 	)),
									// 	'default' => '00',
									// 	'wrapper-class' => 'enable-checkin-time-wrapper yes-wrapper'
									// ),
									'enable-booking-term-and-condition' => array(
										'title' => __('Enable Booking Term And Condition', 'gdlr-hostel'),
										'type'=> 'combobox',
										'options'=> array(
											'yes' => __('Yes', 'gdlr-hostel'),
											'no' => __('No', 'gdlr-hostel'),
										),
										'default' => 'no'
									),
									'booking-term-and-condition' => array(
										'title' => __('Booking Term And Condition Page', 'gdlr-hostel'),
										'type'=> 'combobox',
										'options'=> gdlr_hotel_get_post_list('page'),
										'wrapper-class' => 'enable-booking-term-and-condition-wrapper yes-wrapper'
									),
									'booking-item-style' => array(
										'title' => __('Booking Item Style', 'gdlr-hostel'),
										'type'=> 'combobox',
										'options'=> array(
											'medium' => esc_html('Medium Thumbnail', 'gdlr-hostel'),
											'medium-new' => esc_html('New Medium Thumbnail', 'gdlr-hostel'),
										),
										'default'=> 'medium'
									),	
									'booking-thumbnail-size' => array(
										'title' => __('Booking Thumbnail Size', 'gdlr-hostel'),
										'type'=> 'combobox',
										'options'=> gdlr_get_thumbnail_list(),
										'default'=> 'small-grid-size'
									),
									'booking-num-fetch' => array(
										'title' => __('Booking Num Fetch', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '5',
									),
									'booking-num-excerpt' => array(
										'title' => __('Booking Num Excerpt', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => '34',
									),
								)
							),
							'booking-mail' => array(
								'title' => __('Booking Mail', 'gdlr-hostel'),
								'options' => array(
									'recipient-name' => array(
										'title' => __('Recipient Name', 'gdlr-hostel'),
										'type' => 'text'
									),
									'recipient-mail' => array(
										'title' => __('Recipient Email', 'gdlr-hostel'),
										'type' => 'text'
									),
									'booking-complete-contact' => array(
										'title' => __('Booking Complete Contact', 'gdlr-hostel'),
										'type' => 'textarea'
									),
									'booking-code-prefix' => array(
										'title' => __('Booking Code Prefix', 'gdlr-hostel'),
										'type' => 'text',
										'default' => 'GDLR'
									),
								)
							),
								
							'room-style' => array(
								'title' => __('Room Style', 'gdlr-hostel'),
								'options' => array(		
									'minimum-night' => array(
										'title' => __('Minimum Night to Stay', 'gdlr-hostel'),
										'type'=> 'text',
										'default'=> '1'
									),
									'room-thumbnail-size' => array(
										'title' => __('Single Room Thumbnail Size', 'gdlr-hostel'),
										'type'=> 'combobox',
										'options'=> gdlr_get_thumbnail_list(),
										'default'=> 'post-thumbnail-size'
									),
								)
							),
							
							'paypal-payment-info' => array(
								'title' => __('Paypal Info', 'gdlr-hostel'),
								'options' => array(	
									'paypal-recipient-email' => array(
										'title' => __('Paypal Recipient Email', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => 'testmail@test.com'
									),
									'paypal-action-url' => array(
										'title' => __('Paypal Action URL', 'gdlr-hostel'),
										'type' => 'text',
										'default' => 'https://www.paypal.com/cgi-bin/webscr'
									),
									'paypal-currency-code' => array(
										'title' => __('Paypal Currency Code', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => 'USD'
									),						
								)
							),
							
							'stripe-payment-info' => array(
								'title' => __('Stripe Info', 'gdlr-hostel'),
								'options' => array(	
									'stripe-secret-key' => array(
										'title' => __('Stripe Secret Key', 'gdlr-hostel'),
										'type' => 'text'
									),
									'stripe-publishable-key' => array(
										'title' => __('Stripe Publishable Key', 'gdlr-hostel'),
										'type' => 'text'
									),	
									'stripe-currency-code' => array(
										'title' => __('Stripe Currency Code', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => 'usd'
									),	
								)
							),
							
							'paymill-payment-info' => array(
								'title' => __('Paymill Info', 'gdlr-hostel'),
								'options' => array(	
									'paymill-private-key' => array(
										'title' => __('Paymill Private Key', 'gdlr-hostel'),
										'type' => 'text'
									),
									'paymill-public-key' => array(
										'title' => __('Paymill Public Key', 'gdlr-hostel'),
										'type' => 'text'
									),	
									'paymill-currency-code' => array(
										'title' => __('Paymill Currency Code', 'gdlr-hostel'),
										'type' => 'text',	
										'default' => 'usd'
									),
								)
							),
							
							'authorize-payment-info' => array(
								'title' => __('Authorize Info', 'gdlr-hostel'),
								'options' => array(	
									'authorize-live-mode' => array(
										'title' => __('Live Mode ', 'gdlr-hostel'),
										'type' => 'checkbox',
										'default' => 'disable',
										'description' => __('Please turn this option off when you\'re on test mode.','gdlr-hostel')
									),
									'authorize-api-id' => array(
										'title' => __('Authorize API Login ID ', 'gdlr-hostel'),
										'type' => 'text'
									),
									'authorize-transaction-key' => array(
										'title' => __('Authorize Transaction Key', 'gdlr-hostel'),
										'type' => 'text'
									),
									'authorize-md5-hash' => array(
										'title' => __('Authorize MD5 Hash', 'gdlr-hostel'),
										'type' => 'text'
									),
								)
							),					
						)
					)
				),
				
				$hostel_option
			);
		}
	}
?>