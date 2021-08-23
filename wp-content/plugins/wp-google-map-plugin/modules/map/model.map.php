<?php
/**
 * Class: WPGMP_Model_Map
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Maps
 */

if ( ! class_exists( 'WPGMP_Model_Map' ) ) {

	/**
	 * Map model for CRUD operation.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WPGMP_Model_Map extends FlipperCode_Model_Base {
		/**
		 * Validations on route properies.
		 * @var array
		 */
		protected $validations = array(
		'map_title'	 => array( 'req' => 'Please enter map title.' ),
		'map_height' => array( 'req' => 'Please enter map height.' ),
		);
		/**
		 * Intialize map object.
		 */
		function __construct() {

			$this->table = TBL_MAP;
			$this->unique = 'map_id';
		}
		/**
		 * Admin menu for CRUD Operation
		 * @return array Admin menu navigation(s).
		 */
		function navigation() {
			return array(
			'wpgmp_form_map' => esc_html__( 'Add Map', 'wpgmp_google_map' ),
			'wpgmp_manage_map' => esc_html__( 'Manage Maps', 'wpgmp_google_map' ),
			);

		}
		/**
		 * Install table associated with map entity.
		 * @return string SQL query to install create_map table.
		 */
		function install() {
			global $wpdb;
			$create_map = 'CREATE TABLE '.$wpdb->prefix.'create_map (
			map_id int(11) NOT NULL AUTO_INCREMENT,
			map_title varchar(255) DEFAULT NULL,
			map_width varchar(255) DEFAULT NULL,
			map_height varchar(255) DEFAULT NULL,
			map_zoom_level varchar(255) DEFAULT NULL,
			map_type varchar(255) DEFAULT NULL,
			map_scrolling_wheel varchar(255) DEFAULT NULL,
			map_visual_refresh varchar(255) DEFAULT NULL,
			map_45imagery varchar(255) DEFAULT NULL,
			map_street_view_setting text DEFAULT NULL,
			map_route_direction_setting text DEFAULT NULL,
			map_all_control text DEFAULT NULL,
			map_info_window_setting text DEFAULT NULL,
			style_google_map text DEFAULT NULL,
			map_locations longtext DEFAULT NULL,
			map_layer_setting text DEFAULT NULL,
			map_polygon_setting longtext DEFAULT NULL,
			map_polyline_setting longtext DEFAULT NULL,
			map_cluster_setting text DEFAULT NULL,
			map_overlay_setting text DEFAULT NULL,
			map_geotags text DEFAULT NULL,
			map_infowindow_setting text DEFAULT NULL,
			PRIMARY KEY  (map_id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			return $create_map;
		}
		/**
		 * Get Map(s)
		 * @param  array $where  Conditional statement.
		 * @return array         Array of Map object(s).
		 */
		public function fetch($where = array()) {
			$objects = $this->get( $this->table, $where );
			
			if ( isset( $objects ) ) {
				return $objects;
			}
		}
		/**
		 * Add or Edit Operation.
		 */
		function save() {
			
			global $_POST;
			$data = array();
			$entityID = '';

			//Permission Verification
			if ( ! current_user_can('administrator') )
			die( 'You are not allowed to save changes!' );
			
			//Nonce Verification
			if( !isset( $_REQUEST['_wpnonce'] ) || ( isset( $_REQUEST['_wpnonce'] ) && empty($_REQUEST['_wpnonce']) ) )
			die( 'You are not allowed to save changes!' );
			if ( isset( $_REQUEST['_wpnonce'] ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpgmp-nonce' ) )
			die( 'You are not allowed to save changes!' );
	
			//Check Validations
			$this->verify( $_POST );
			
			if(!empty($_POST['map_street_view_setting']['pov_heading']) && empty($_POST['map_street_view_setting']['pov_pitch'])){
				$this->errors[] = esc_html__( 'Please enter integer value for POV pitch.','wpgmp_google_map' );
			}
			if(empty($_POST['map_street_view_setting']['pov_heading']) && !empty($_POST['map_street_view_setting']['pov_pitch'])){
				$this->errors[] = esc_html__( 'Please enter integer value for POV heading.','wpgmp_google_map' );
			}
			if(!empty($_POST['map_street_view_setting']['pov_heading']) && !empty($_POST['map_street_view_setting']['pov_pitch'])){
				
				$a = $_POST['map_street_view_setting']['pov_heading'];
				$b = $_POST['map_street_view_setting']['pov_pitch'];
				
				if (!(int) $a == $a || !(int) $b == $b) {
					$this->errors[] = esc_html__( 'Please enter integer values for both POV heading & Pov pitch.','wpgmp_google_map' );
				}
				
			}
			
			if ( is_array( $this->errors ) && ! empty( $this->errors ) ) {
				$this->throw_errors();
			}

			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
			}
			
			if ( !is_array($_POST['map_locations']) && '' != sanitize_text_field( $_POST['map_locations'] ) ) {
				$map_locations = explode( ',', sanitize_text_field( $_POST['map_locations'] ) );
			} else if( is_array($_POST['map_locations']) && !empty($_POST['map_locations'])) {
				$map_locations = $_POST['map_locations'];
			} 
			else { $map_locations = array(); }

			if ( isset( $_POST['extensions_fields'] ) ) {
				$_POST['map_all_control']['extensions_fields'] = $_POST['extensions_fields'];
			}

			if ( isset( $_POST['map_all_control']['map_control_settings'] ) ) {
				$arr = array();$i=0;
				foreach($_POST['map_all_control']['map_control_settings'] as $key=>$val){
					if($val['html']!=''){
						$arr[$i]['html'] = $val['html'];
						$arr[$i]['position'] = $val['position'];
						$i++;
					}
				}
				$_POST['map_all_control']['map_control_settings'] = $arr;
			}

			if ( isset( $_POST['map_all_control']['custom_filters'] ) ) {
				foreach($_POST['map_all_control']['custom_filters'] as $k=>$val){
					if($val['slug']=='')
						unset($_POST['map_all_control']['custom_filters'][$k]);
				}
			}

			$data['map_title'] = sanitize_text_field( wp_unslash( $_POST['map_title'] ) );
			if(isset($_POST['map_width']))
			$data['map_width'] = str_replace( 'px','',sanitize_text_field( wp_unslash( $_POST['map_width'] ) ) );
			$data['map_height'] = str_replace( 'px','',sanitize_text_field( wp_unslash( $_POST['map_height'] ) ) );
			$data['map_zoom_level'] = intval( wp_unslash( $_POST['map_zoom_level'] ) );
			$data['map_type'] = sanitize_text_field( wp_unslash( $_POST['map_type'] ) );
			if(isset($_POST['map_scrolling_wheel']))
			$data['map_scrolling_wheel'] = sanitize_text_field( wp_unslash( $_POST['map_scrolling_wheel'] ) );
			else
			$data['map_scrolling_wheel'] = '';
			
			if(isset($_POST['map_45imagery']))
			$data['map_45imagery'] = sanitize_text_field( wp_unslash( $_POST['map_45imagery'] ) );
			else
			$data['map_45imagery'] = '';
			
			if(isset($_POST['map_street_view_setting']))
			$data['map_street_view_setting'] = serialize( wp_unslash( $_POST['map_street_view_setting'] ) );
			if(isset($_POST['map_route_direction_setting']))
			$data['map_route_direction_setting'] = serialize( wp_unslash( $_POST['map_route_direction_setting'] ) );
			if(isset($_POST['map_all_control']))
			$data['map_all_control'] = serialize( wp_unslash( $_POST['map_all_control'] ) );
			if(isset($_POST['map_info_window_setting']))
			$data['map_info_window_setting'] = serialize( wp_unslash( $_POST['map_info_window_setting'] ) );
			if(isset($_POST['style_google_map']))
			$data['style_google_map'] = serialize( wp_unslash( $_POST['style_google_map'] ) );
			if(isset($map_locations))
			$data['map_locations'] = serialize( wp_unslash( $map_locations ) );
			if(isset($_POST['map_layer_setting']))
			$data['map_layer_setting'] = serialize( wp_unslash( $_POST['map_layer_setting'] ) );
			else
			$data['map_layer_setting'] = '';
			
			if(isset($_POST['map_polygon_setting']))
			$data['map_polygon_setting'] = serialize( wp_unslash( $_POST['map_polygon_setting'] ) );
			if(isset($_POST['map_cluster_setting']))
			$data['map_cluster_setting'] = serialize( wp_unslash( $_POST['map_cluster_setting'] ) );
			if(isset($_POST['map_overlay_setting']))
			$data['map_overlay_setting'] = serialize( wp_unslash( $_POST['map_overlay_setting'] ) );
			if(isset($_POST['map_infowindow_setting']))
			$data['map_infowindow_setting'] = serialize( wp_unslash( $_POST['map_infowindow_setting'] ) );
			if(isset($_POST['map_geotags']))
			$data['map_geotags'] = serialize( wp_unslash( $_POST['map_geotags'] ) );
			if ( $entityID > 0 ) {
				$where[ $this->unique ] = $entityID;
			} else {
				$where = '';
			}
			// Hook to insert/update extension data.

			if( isset($_POST['fc_entity_type']) ) {

				$extension_name = strtolower(trim(sanitize_text_field( wp_unslash( $_POST['fc_entity_type'] ) )));
				if( $extension_name !='' ) {
					$data = apply_filters($extension_name.'_save',$data,$this->table,$where);
				}
				
			}
			
			$result = FlipperCode_Database::insert_or_update( $this->table, $data, $where );
			
			if ( false === $result ) {
				$response['error'] = esc_html__( 'Something went wrong. Please try again.','wpgmp_google_map' );
			} elseif ( $entityID > 0 ) {
				$response['success'] = esc_html__( 'Map updated successfully.','wpgmp_google_map' );
			} else {
			    $response['success'] = esc_html__( 'Map added successfully.','wpgmp_google_map' );
			}
			return $response;
			
		}
		/**
		 * Delete map object by id.
		 */
		function delete() {
			if ( isset( $_GET['map_id'] ) ) {
				$id = intval( wp_unslash( $_GET['map_id'] ) );
				$connection = FlipperCode_Database::connect();
				$this->query = $connection->prepare( "DELETE FROM $this->table WHERE $this->unique='%d'", $id );
				return FlipperCode_Database::non_query( $this->query, $connection );
			}
		}
		/**
		 * Clone map object by id.
		 */
		function copy($map_id) {
			if ( isset( $map_id ) ) {
				$id = intval( wp_unslash( $map_id ) );
				$map = $this->get( $this->table,array( array( 'map_id', '=', $id ) ) );
				$data = array();
				foreach ( $map[0] as $column => $value ) {

					if ( $column == 'map_id' ) {
						continue; } else if ( $column == 'map_title' ) {
						$data[$column] = $value.' '.esc_html__( 'Copy','wpgmp_google_map' );
						} else { 					$data[$column] = $value; }
				}

				$result = FlipperCode_Database::insert_or_update( $this->table, $data );
			}
		}

	}
}
