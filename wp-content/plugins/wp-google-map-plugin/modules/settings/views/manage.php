<?php
/**
 * This class used to manage settings page in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */

$form  = new WPGMP_Template();
$form->form_action = esc_url ( add_query_arg( 'page', 'wpgmp_manage_settings', admin_url ('admin.php') )  );
$form->set_header( esc_html__( 'General Setting(s)', 'wpgmp_google_map' ), $response );
$link = '<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">'.esc_html__("create google maps api key","wpgmp_google_map").'</a>';

$form->add_element('text','wpgmp_api_key',array(
	'lable' => esc_html__( 'Google Maps API Key','wpgmp_google_map' ),
	'value' => get_option( 'wpgmp_api_key' ),
	'desc'  => sprintf( esc_html__( 'Get here %1$s and insert here.', 'wpgmp_google_map' ), $link ),
	));

$language = array(
'en' => esc_html__( 'ENGLISH', 'wpgmp_google_map' ),
'ar' => esc_html__( 'ARABIC', 'wpgmp_google_map' ),
'eu' => esc_html__( 'BASQUE', 'wpgmp_google_map' ),
'bg' => esc_html__( 'BULGARIAN', 'wpgmp_google_map' ),
'bn' => esc_html__( 'BENGALI', 'wpgmp_google_map' ),
'ca' => esc_html__( 'CATALAN', 'wpgmp_google_map' ),
'cs' => esc_html__( 'CZECH', 'wpgmp_google_map' ),
'da' => esc_html__( 'DANISH', 'wpgmp_google_map' ),
'de' => esc_html__( 'GERMAN', 'wpgmp_google_map' ),
'el' => esc_html__( 'GREEK', 'wpgmp_google_map' ),
'en-AU' => esc_html__( 'ENGLISH (AUSTRALIAN)', 'wpgmp_google_map' ),
'en-GB' => esc_html__( 'ENGLISH (GREAT BRITAIN)', 'wpgmp_google_map' ),
'es' => esc_html__( 'SPANISH', 'wpgmp_google_map' ),
'fa' => esc_html__( 'FARSI', 'wpgmp_google_map' ),
'fi' => esc_html__( 'FINNISH', 'wpgmp_google_map' ),
'fil' => esc_html__( 'FILIPINO', 'wpgmp_google_map' ),
'fr' => esc_html__( 'FRENCH', 'wpgmp_google_map' ),
'gl' => esc_html__( 'GALICIAN', 'wpgmp_google_map' ),
'gu' => esc_html__( 'GUJARATI', 'wpgmp_google_map' ),
'hi' => esc_html__( 'HINDI', 'wpgmp_google_map' ),
'hr' => esc_html__( 'CROATIAN', 'wpgmp_google_map' ),
'hu' => esc_html__( 'HUNGARIAN', 'wpgmp_google_map' ),
'id' => esc_html__( 'INDONESIAN', 'wpgmp_google_map' ),
'it' => esc_html__( 'ITALIAN', 'wpgmp_google_map' ),
'iw' => esc_html__( 'HEBREW', 'wpgmp_google_map' ),
'ja' => esc_html__( 'JAPANESE', 'wpgmp_google_map' ),
'kn' => esc_html__( 'KANNADA', 'wpgmp_google_map' ),
'ko' => esc_html__( 'KOREAN', 'wpgmp_google_map' ),
'lt' => esc_html__( 'LITHUANIAN', 'wpgmp_google_map' ),
'lv' => esc_html__( 'LATVIAN', 'wpgmp_google_map' ),
'ml' => esc_html__( 'MALAYALAM', 'wpgmp_google_map' ),
'it' => esc_html__( 'ITALIAN', 'wpgmp_google_map' ),
'mr' => esc_html__( 'MARATHI', 'wpgmp_google_map' ),
'nl' => esc_html__( 'DUTCH', 'wpgmp_google_map' ),
'no' => esc_html__( 'NORWEGIAN', 'wpgmp_google_map' ),
'pl' => esc_html__( 'POLISH', 'wpgmp_google_map' ),
'pt' => esc_html__( 'PORTUGUESE', 'wpgmp_google_map' ),
'pt-BR' => esc_html__( 'PORTUGUESE (BRAZIL)', 'wpgmp_google_map' ),
'pt-PT' => esc_html__( 'PORTUGUESE (PORTUGAL)', 'wpgmp_google_map' ),
'ro' => esc_html__( 'ROMANIAN', 'wpgmp_google_map' ),
'ru' => esc_html__( 'RUSSIAN', 'wpgmp_google_map' ),
'sk' => esc_html__( 'SLOVAK', 'wpgmp_google_map' ),
'sl' => esc_html__( 'SLOVENIAN', 'wpgmp_google_map' ),
'sr' => esc_html__( 'SERBIAN', 'wpgmp_google_map' ),
'sv' => esc_html__( 'SWEDISH', 'wpgmp_google_map' ),
'tl' => esc_html__( 'TAGALOG', 'wpgmp_google_map' ),
'ta' => esc_html__( 'TAMIL', 'wpgmp_google_map' ),
'te' => esc_html__( 'TELUGU', 'wpgmp_google_map' ),
'th' => esc_html__( 'THAI', 'wpgmp_google_map' ),
'tr' => esc_html__( 'TURKISH', 'wpgmp_google_map' ),
'uk' => esc_html__( 'UKRAINIAN', 'wpgmp_google_map' ),
'vi' => esc_html__( 'VIETNAMESE', 'wpgmp_google_map' ),
'zh-CN' => esc_html__( 'CHINESE (SIMPLIFIED)', 'wpgmp_google_map' ),
'zh-TW' => esc_html__( 'CHINESE (TRADITIONAL)', 'wpgmp_google_map' ),
);

$form->add_element( 'select', 'wpgmp_language', array(
	'lable' => esc_html__( 'Map Language', 'wpgmp_google_map' ),
	'current' => get_option( 'wpgmp_language' ),
	'desc' => esc_html__( 'Choose your language for map. Default is English.', 'wpgmp_google_map' ),
	'options' => $language,
	'before' => '<div class="fc-4">',
	'after' => '</div>',
));

$form->add_element( 'radio', 'wpgmp_scripts_place', array(
	'lable' => esc_html__( 'Include Scripts in ', 'wpgmp_google_map' ),
	'radio-val-label' => array( 'header' => esc_html__( 'Header','wpgmp_google_map' ),'footer' => esc_html__( 'Footer (Recommended)','wpgmp_google_map' ) ),
	'current' => get_option( 'wpgmp_scripts_place' ),
	'class' => 'chkbox_class',
	'default_value' => 'footer',
));

$form->add_element('submit','wpgmp_save_settings',array(
	'value' => esc_html__( 'Save Setting','wpgmp_google_map' ),
	));
$form->add_element('hidden','operation',array(
	'value' => 'save',
	));
$form->add_element('hidden','page_options',array(
	'value' => 'wpgmp_api_key,wpgmp_scripts_place',
	));
$form->render();
