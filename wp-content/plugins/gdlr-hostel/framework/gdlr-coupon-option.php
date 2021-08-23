<?php
	/*	
	*	Goodlayers Coupon Option file
	*	---------------------------------------------------------------------
	*	This file creates all coupon options and attached to the theme
	*	---------------------------------------------------------------------
	*/

	// add action to create coupon post type
	add_action( 'init', 'gdlr_create_coupon' );
	if( !function_exists('gdlr_create_coupon') ){
		function gdlr_create_coupon() {
			global $theme_option;
			
			register_post_type( 'coupon',
				array(
					'labels' => array(
						'name'               => __('Coupon', 'gdlr-hostel'),
						'singular_name'      => __('Coupon', 'gdlr-hostel'),
						'add_new'            => __('Add New', 'gdlr-hostel'),
						'add_new_item'       => __('Add New Coupon', 'gdlr-hostel'),
						'edit_item'          => __('Edit Coupon', 'gdlr-hostel'),
						'new_item'           => __('New Coupon', 'gdlr-hostel'),
						'all_items'          => __('All Coupon', 'gdlr-hostel'),
						'view_item'          => __('View Coupon', 'gdlr-hostel'),
						'search_items'       => __('Search Coupon', 'gdlr-hostel'),
						'not_found'          => __('No coupon found', 'gdlr-hostel'),
						'not_found_in_trash' => __('No coupon found in Trash', 'gdlr-hostel'),
						'parent_item_colon'  => '',
						'menu_name'          => __('Coupon', 'gdlr-hostel')
					),
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => false,
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 5,
					'supports'           => array( 'title', 'custom-fields' )
				)
			);
			
			// add filter to style single template
			add_filter('single_template', 'gdlr_register_coupon_template');
		}
	}	
	
	if( !function_exists('gdlr_register_coupon_template') ){
		function gdlr_register_coupon_template($template){
			if( get_post_type() == 'coupon' ){
				$template = GDLR_LOCAL_PATH . '/404.php';
			}
			return $template;
		}
	}
	
		// add a room option to room page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_coupon_options'); }
	if( !function_exists('gdlr_create_coupon_options') ){
		function gdlr_create_coupon_options(){

			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('coupon'),
					'meta_title' => __('Goodlayers Coupon Option', 'gdlr-hostel'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
				
				array(
					'page-layout' => array(
						'title' => __('Page Layout', 'gdlr-hostel'),
						'options' => array(
							'coupon-code' => array(
								'title' => __('Coupon Code' , 'gdlr-hostel'),
								'type' => 'text',
								'custom_field' => 'gdlr-coupon-code'
							),
							'coupon-amount' => array(
								'title' => __('Coupon Amount' , 'gdlr-hostel'),
								'type' => 'text',
								'default' => -1,
								'description' => __('Fill -1 for unlimited uses', 'gdlr-hostel')
							),	
							'coupon-expiry' => array(
								'title' => __('Coupon Expiry' , 'gdlr-hostel'),
								'type' => 'date-picker'
							),
							'coupon-discount-type' => array(
								'title' => __('Coupon Discount Type' , 'gdlr-hostel'),
								'type' => 'combobox',
								'options' => array(
									'percent' => __('Percent', 'gdlr-hostel'),
									'amount' => __('Amount', 'gdlr-hostel')
								)
							),
							'coupon-discount-amount' => array(
								'title' => __('Coupon Discount Amount' , 'gdlr-hostel'),
								'type' => 'text',
								'description' => __('Only number is allowed here', 'gdlr-hostel')
							),
							'specify-room' => array(
								'title' => __('Apply only to specific room ( room id separated by comma )' , 'gdlr-hostel'),
								'type' => 'textarea'
							),
						)
					),
				)
			);
		}
	}
	
?>