<?php
/**
 * Template class
 * @author Flipper Code<hello@flippercode.com>
 * @version 4.1.1
 * @package Google Maps Plugin
 */

if ( ! class_exists( 'WPGMP_Template' ) ) {

	/**
	 * Controller class to display views.
	 * @author: Flipper Code<hello@flippercode.com>
	 * @version: 4.1.1
	 * @package: Google Maps Plugin
	 */

	class WPGMP_Template extends FlipperCode_HTML_Markup{


		function __construct($options = array()) {
			
			$premium_features = '<ul class="fc-pro-features">
			<li>'.esc_html__('Display beautiful listing of locations under map.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Fitlers markers & listing based on different criterias.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display any custom posts type data on map.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display html based dynamic data in info-window.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display multiple customizable routes on map.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Apply beautiful skins to map for UI enhancement. ','wpgmp_google_map').'</li>
			<li>'.esc_html__('Export/Import locations to & from csv.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display directions between places.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Enable marker clustering on map.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display locations using ACF google map field.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display polygons, circles & rectangles on map.','wpgmp_google_map').'</li>
			<li>'.esc_html__('Display html based infowindow content & more...','wpgmp_google_map').'</li>
			</ul>';
						
			$productInfo = array('productName' => esc_html__('WP Google Map Plugin','wpgmp_google_map'),
                        'productSlug' => 'wp-google-map-plugin',
                        'product_tag_line' => 'worlds most advanced google map plugin',
                        'productTextDomain' => 'wpgmp_google_map',
                        'productVersion' => WPGMP_VERSION,
                        'premium_features' => $premium_features,
                        'videoURL' => 'https://www.youtube.com/playlist?list=PLlCp-8jiD3p2PYJI1QCIvjhYALuRGBJ2A',
                        'docURL' => 'https://www.wpmapspro.com/tutorials/',
                        'demoURL' => 'https://www.wpmapspro.com',
                        'productSaleURL' => 'https://codecanyon.net/item/advanced-google-maps-plugin-for-wordpress/5211638/?utm_source=wordpress&utm_medium=link&utm_campaign=freemium',
                        'multisiteLicence' => 'http://codecanyon.net/item/advanced-google-maps-plugin-for-wordpress/5211638?license=extended&open_purchase_for_item_id=5211638&purchasable=source'
   			 );
			$productInfo = array_merge($productInfo, $options);
			parent::__construct($productInfo);

		}

	}
	
}
