<?php
/**
 * Class: WPGMP_Model_Settings
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Maps
 */

if ( ! class_exists( 'WPGMP_Model_Settings' ) ) {

	/**
	 * Setting model for Plugin Options.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WPGMP_Model_Settings extends FlipperCode_Model_Base {
		/**
		 * Intialize Backup object.
		 */
		function __construct() {
		}
		/**
		 * Admin menu for Settings Operation
		 * @return array Admin menu navigation(s).
		 */
		function navigation() {
			return array(
				'wpgmp_manage_settings' => esc_html__( 'Settings', 'wpgmp_google_map' ),
			);
		}
		/**
		 * Add or Edit Operation.
		 */
		function save() {
			
			global $_POST;
			
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

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}
			$extra_fields = array();
			if ( isset( $_POST['location_extrafields'] ) ) {
				foreach ( $_POST['location_extrafields'] as $index => $label ) {
					if ( $label != '' ) {
						$extra_fields[$index] = sanitize_text_field( wp_unslash( $label ) );
					}
				}
			}

			$meta_hide = array();
			if ( isset( $_POST['wpgmp_allow_meta'] ) ) {
				foreach ( $_POST['wpgmp_allow_meta'] as $index => $label ) {
					if ( $label != '' ) {
						$meta_hide[$index] = sanitize_text_field( wp_unslash( $label ) );
					}
				}
			}
			update_option( 'wpgmp_language',sanitize_text_field( wp_unslash( $_POST['wpgmp_language'] ) ) );
			update_option( 'wpgmp_api_key',sanitize_text_field( wp_unslash( $_POST['wpgmp_api_key'] ) ) );
			update_option( 'wpgmp_scripts_place',sanitize_text_field( wp_unslash( $_POST['wpgmp_scripts_place'] ) ) );
			update_option( 'wpgmp_location_extrafields', serialize(  $extra_fields  ) );
			update_option( 'wpgmp_allow_meta', serialize(  $meta_hide  ));
			$response['success'] = esc_html__( 'Setting(s) saved successfully.','wpgmp_google_map' );
			return $response;

		}
	}
}
