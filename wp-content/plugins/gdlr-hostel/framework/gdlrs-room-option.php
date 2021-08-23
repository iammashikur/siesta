<?php
	/*	
	*	Goodlayers Hostel Room Option file
	*	---------------------------------------------------------------------
	*	This file creates all hotel options and attached to the theme
	*	---------------------------------------------------------------------
	*/
	
	// add action to create room post type
	add_action( 'init', 'gdlr_create_hostel_room' );
	if( !function_exists('gdlr_create_hostel_room') ){
		function gdlr_create_hostel_room() {
			global $theme_option;
			
			if( !empty($theme_option['hostel-room-slug']) ){
				$room_slug = $theme_option['hostel-room-slug'];
				$room_category_slug = $theme_option['hostel-room-category-slug'];
				$room_tag_slug = $theme_option['hostel-room-tag-slug'];
			}else{
				$room_slug = 'hostel_room';
				$room_category_slug = 'hostel_room_category';
				$room_tag_slug = 'hostel_room_tag';
			}
			
			register_post_type( 'hostel_room',
				array(
					'labels' => array(
						'name'               => __('Rooms', 'gdlr-hostel'),
						'singular_name'      => __('Room', 'gdlr-hostel'),
						'add_new'            => __('Add New', 'gdlr-hostel'),
						'add_new_item'       => __('Add New Room', 'gdlr-hostel'),
						'edit_item'          => __('Edit Room', 'gdlr-hostel'),
						'new_item'           => __('New Room', 'gdlr-hostel'),
						'all_items'          => __('All Rooms', 'gdlr-hostel'),
						'view_item'          => __('View Room', 'gdlr-hostel'),
						'search_items'       => __('Search Room', 'gdlr-hostel'),
						'not_found'          => __('No rooms found', 'gdlr-hostel'),
						'not_found_in_trash' => __('No rooms found in Trash', 'gdlr-hostel'),
						'parent_item_colon'  => '',
						'menu_name'          => __('Rooms (Hostel)', 'gdlr-hostel')
					),
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => $room_slug  ),
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 8,
					'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
				)
			);
			
			// create room categories
			register_taxonomy(
				'hostel_room_category', array("hostel_room"), array(
					'hierarchical' => true,
					'show_admin_column' => true,
					'label' => __('Room Categories', 'gdlr-hostel'), 
					'singular_label' => __('Room Category', 'gdlr-hostel'), 
					'rewrite' => array( 'slug' => $room_category_slug  )));
			register_taxonomy_for_object_type('hostel_room_category', 'hostel_room');
			
			// create custom taxonomy for room category
			if( is_admin() && class_exists('gdlr_tax_meta') ){
				global $gdlr_sidebar_controller;
				
				new gdlr_tax_meta( 
					array(
						'taxonomy'=>'hostel_room_category',
						'slug'=>'gdlr_hostel_branch'
					),
					array(
						'upload' => array(
							'title'=> __('Hostel Thumbnail', 'gdlr-song'),
							'type'=> 'upload'
						),
						'content' => array(
							'title'=> __('Hostel Location', 'gdlr-song'),
							'type'=> 'textarea'
						),
						'paypal-recipient-email' => array(
							'title'=> __('Paypal Recipient Email ( If the branches is enabled )', 'gdlr-song'),
							'type'=> 'text'
						)
					)
				);
			}
			
			
			// create room tag
			register_taxonomy(
				'hostel_room_tag', array('hostel_room'), array(
					'hierarchical' => false, 
					'show_admin_column' => true,
					'label' => __('Room Tags', 'gdlr-hostel'), 
					'singular_label' => __('Room Tag', 'gdlr-hostel'),  
					'rewrite' => array( 'slug' => $room_tag_slug  )));
			register_taxonomy_for_object_type('hostel_room_tag', 'hostel_room');	

			// add filter to style single template
			add_filter('single_template', 'gdlr_register_hostel_room_template');
		}
	}

	if( !function_exists('gdlr_register_hostel_room_template') ){
		function gdlr_register_hostel_room_template($single_template) {
			global $post;

			if ($post->post_type == 'hostel_room') {
				$single_template = dirname(dirname( __FILE__ )) . '/single-room.php';
			}
			return $single_template;	
		}
	}
	
	// add a room option to room page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_hostel_room_options'); }
	if( !function_exists('gdlr_create_hostel_room_options') ){
		function gdlr_create_hostel_room_options(){

			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('hostel_room'),
					'meta_title' => __('Goodlayers Room Option', 'gdlr-hostel'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => __('Page Layout', 'gdlr-hostel'),
						'options' => array(
							'page-title' => array(
								'title' => __('Page Title' , 'gdlr-hostel'),
								'type' => 'text'
							),
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-hostel'),
								'type' => 'textarea'
							),							
							'header-background' => array(
								'title' => __('Header Background Image' , 'gdlr-hostel'),
								'button' => __('Upload', 'gdlr-hostel'),
								'type' => 'upload',
							),					
						)
					),
					
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-hostel'),
						'options' => array(
							'room-amount' => array(
								'title' => __('Bed Amount' , 'gdlr-hostel'),
								'type' => 'text',
								'default' => 1,
								'custom_field' => 'gdlr_room_amount',
								'description' => __('Bed Amount for the room', 'gdlr-hostel')
							),
							'room-type' => array(
								'title' => __('Room Type' , 'gdlr-hostel'),
								'type' => 'combobox',
								'options' => array(
									'dorm' => __('Dorm' , 'gdlr-hostel'),
									'private' => __('Private' , 'gdlr-hostel'),
								),
								'custom_field' => 'gdlr_room_type',
								'description' => __('*For private room, guest has to pay for entire room. For example 3 persons booking a 5 private bedroom will need to pay for 5 persons.', 'gdlr-hostel')
							),
							'block-room-date' => array(
								'title' => __('Block room on specific date' , 'gdlr-hotel'),
								'type' => 'textarea',
								'custom_field' => 'gdlr_block_date',
								'description' => __('Fill the date in yyyy-mm-dd format. Use * for recurring date, separated each date using comma, use the word \'to\' for date range. Ex. *-12-25 to *-12-31 means special season is running every Christmas to New Year\'s Eve every year.', 'gdlr-hotel'),
							),
							
							// room price
							'row-bp' => array( 'type' => 'row' ),
							'room-price-title' => array( 
								'title' => __('Room Price' , 'gdlr-hostel'), 
								'type' => 'title', 
								'wrapper-class' => 'gdlr-top-divider gdlr-title-text' 
							),
							'room-base-price-weekend' => array(
								'title' => __('Base Price Weekend' , 'gdlr-hostel'),
								'type' => 'text',
								'default' => 0,
								'wrapper-class' => 'six columns'
							),	
							'room-base-price' => array(
								'title' => __('Base Price Weekday' , 'gdlr-hostel'),
								'type' => 'text',
								'default' => 0,
								'wrapper-class' => 'six columns'
							),
							'row-bp-2' => array( 'type' => 'close-row' ),
							'base-price-description' => array(
								'type' => 'description',
								'description' => __('*Base Price field accepts only number. Don’t fill currency sign nor commas. For dorm, put base price for a person. For private room, put base price for whole room.', 'gdlr-hostel')
							),

							// special season price
							'special-season-title' => array(
								'title' => __('Seasonal Room Price' , 'gdlr-hostel'),
								'type' => 'title',
								'wrapper-class' => 'gdlr-top-divider gdlr-title-text'
							),
							'special-season-pricing' => array(
								'title' => __('Add Seasonal Prices', 'gdlr-hostel'),
								'type' => 'ssp',
								'room-type' => 'hostel',
								'wrapper-class' => 'gdlr-ssp-option-wrapper'
							),
							'consecutive-night-discount' => array(
								'title' => __('Discount For Consecutive Nights', 'gdlr-hotel'),
								'type' => 'cnd',
								'wrapper-class' => 'gdlr-cnd-option-wrapper gdlr-top-divider'
							),
							'facilities-and-services' => array(
								'title' => __('Add Facilities and Services', 'gdlr-hostel'),
								'type' => 'fas',
								'data-type' => 'hostel',
								'wrapper-class' => 'gdlr-fas-option-wrapper gdlr-top-divider'
							),
							'thumbnail-type' => array(
								'title' => __('Thumbnail Type' , 'gdlr-hostel'),
								'type' => 'combobox',
								'options' => array(
									'feature-image'=> __('Feature Image', 'gdlr-hostel'),
									'video'=> __('Video', 'gdlr-hostel'),
									'slider'=> __('Slider', 'gdlr-hostel')
								),
								'wrapper-class' => 'gdlr-top-divider'
							),						
							'thumbnail-video' => array(
								'title' => __('Video Url' , 'gdlr-hostel'),
								'type' => 'text',
								'wrapper-class' => 'video-wrapper thumbnail-type-wrapper'
							),		
							'thumbnail-slider' => array(
								'title' => __('Slider' , 'gdlr-hostel'),
								'type' => 'slider',
								'wrapper-class' => 'slider-wrapper thumbnail-type-wrapper'
							),								
							'inside-thumbnail-type' => array(
								'title' => __('Inside Room Thumbnail Type' , 'gdlr-hostel'),
								'type' => 'combobox',
								'options' => array(
									'thumbnail-type'=> __('Same As Thumbnail Type', 'gdlr-hostel'),
									'image'=> __('Image', 'gdlr-hostel'),
									'video'=> __('Video', 'gdlr-hostel'),
									'slider'=> __('Slider', 'gdlr-hostel'),
									'stack-image'=> __('Stack Images', 'gdlr-hostel')
								),
								'wrapper-class' => 'gdlr-top-divider'
							),		
							'inside-thumbnail-image' => array(
								'title' => __('Image Url' , 'gdlr-hostel'),
								'type' => 'upload',
								'wrapper-class' => 'image-wrapper inside-thumbnail-type-wrapper'
							),							
							'inside-thumbnail-video' => array(
								'title' => __('Video Url' , 'gdlr-hostel'),
								'type' => 'text',
								'wrapper-class' => 'video-wrapper inside-thumbnail-type-wrapper'
							),		
							'inside-thumbnail-slider' => array(
								'title' => __('Slider' , 'gdlr-hostel'),
								'type' => 'slider',
								'wrapper-class' => 'stack-image-wrapper slider-wrapper inside-thumbnail-type-wrapper'
							),								
						)
					),

				)
			);
		}
	}	
	
	// add room in page builder area
	add_filter('gdlr_page_builder_option', 'gdlrs_register_room_item');
	if( !function_exists('gdlrs_register_room_item') ){
		function gdlrs_register_room_item( $page_builder = array() ){
			global $gdlr_spaces;
		
			$page_builder['content-item']['options']['hostel-room'] = array(
				'title'=> __('Hostel Room', 'gdlr-hostel'), 
				'type'=>'item',
				'options'=>array_merge(gdlr_page_builder_title_option(__('View all rooms', 'gdlr-hostel')), array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-hostel'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('hostel_room_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-hostel')
					),	
					'tag'=> array(
						'title'=> __('Tag' ,'gdlr-hostel'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('hostel_room_tag'),
						'description'=> __('Will be ignored when the room filter option is enabled.', 'gdlr-hostel')
					),					
					'room-style'=> array(
						'title'=> __('Room Style' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> array(
							'classic' => __('Classic', 'gdlr-hostel'),
							'classic-no-space' => __('Classic No Space', 'gdlr-hostel'),
							'modern' => __('Modern', 'gdlr-hostel'),
							'modern-no-space' => __('Modern No Space', 'gdlr-hostel'),
							'modern-new' => __('Modern New', 'gdlr-hostel'),
							'modern-new-no-space' => __('Modern New No Space', 'gdlr-hostel'),
							'medium' => __('Medium Thumbnail', 'gdlr-hostel'),
							'medium-new' => __('New Medium Thumbnail', 'gdlr-hostel'),
						),
					),
					'enable-carousel'=> array(
						'title'=> __('Room Carousel' ,'gdlr-hostel'),
						'type'=> 'checkbox',
						'wrapper-class'=> 'room-style-wrapper modern-wrapper modern-new-wrapper modern-no-space-wrapper modern-new-no-space-wrapper classic-wrapper classic-no-space-wrapper medium-wrapper medium-new-wrapper'
					),
					'room-size'=> array(
						'title'=> __('Room Column Size' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4'),
						'default'=> 3,
						'wrapper-class'=>'room-style-wrapper classic-wrapper classic-no-space-wrapper modern-wrapper modern-new-wrapper modern-no-space-wrapper modern-new-no-space-wrapper'
					),					
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-hostel'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of rooms you want to pull out.', 'gdlr-hostel')
					),	
					'num-excerpt'=> array(
						'title'=> __('Num Excerpt' ,'gdlr-hostel'),
						'type'=> 'text',	
						'default'=> '20',
						'wrapper-class'=>'room-style-wrapper medium-wrapper medium-new-wrapper'
					),				
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> gdlr_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-hostel')
					),	
					'orderby'=> array(
						'title'=> __('Order By' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> array(
							'date' => __('Publish Date', 'gdlr-hostel'), 
							'title' => __('Title', 'gdlr-hostel'), 
							'rand' => __('Random', 'gdlr-hostel'), 
						)
					),
					'order'=> array(
						'title'=> __('Order' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> array(
							'desc'=>__('Descending Order', 'gdlr-hostel'), 
							'asc'=> __('Ascending Order', 'gdlr-hostel'), 
						)
					),			
					'pagination'=> array(
						'title'=> __('Enable Pagination' ,'gdlr-hostel'),
						'type'=> 'checkbox'
					),					
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-hostel'),
						'type' => 'text',
						'default' => $gdlr_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-hostel')
					),				
				))
			);
			
			$page_builder['content-item']['options']['hostel-room-category'] = array(
				'title'=> __('Hostel Room Category / Branches', 'gdlr-hostel'), 
				'type'=>'item',
				'options'=>array_merge(gdlr_page_builder_title_option(__('View All Branches', 'gdlr-hostel')), array(									
					'category'=> array(
						'title'=> __('Select Category to Display' ,'gdlr-hostel'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_id_list('hostel_room_category'),
						'description'=> __('Will be ignored when the room filter option is enabled.', 'gdlr-hostel')
					),
					'item-size'=> array(
						'title'=> __('Item Column Size' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4'),
						'default'=> 3,
						'wrapper-class'=>'room-style-wrapper classic-wrapper modern-wrapper'
					),				
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-hostel'),
						'type'=> 'combobox',
						'options'=> gdlr_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-hostel')
					),				
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-hostel'),
						'type' => 'text',
						'default' => $gdlr_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-hostel')
					),				
				))
			);
			return $page_builder;
		}
	}
	
?>