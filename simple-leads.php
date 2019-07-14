<?php
/*
Plugin Name: Simple Leads
Description: Плагин для приема заявок с сайта
Plugin URI: https://max-landing.ru
Author: Shteklein Maxim
Author URI: https://max-landing.ru
Version: 1.0
*/


define('SL_DIR', plugin_dir_path(__FILE__));

/*
// count table rows
add_filter( 'set-screen-option', function( $status, $option, $value ){
	return ( $option == 'sms_ru_newsletter_per_page' ) ? (int) $value : $status;
}, 10, 3 );
*/

/*
add_action('admin_menu', 'sms_ru_add_admin_pages');
function sms_ru_add_admin_pages()
{ 
    add_menu_page('Настройка', 'SMS.RU', 'manage_options', 'sms-ru-options', 'sms_ru_options_menu_point', 'dashicons-email-alt');
    add_submenu_page( 'sms-ru-options', 'Настройка', 'Настройка', 'manage_options', 'sms-ru-options', 'sms_ru_options_menu_point');
    add_submenu_page( 'sms-ru-options', 'СМС клиента', 'СМС клиента', 'manage_options', 'client-page', 'sms_ru_client_menu_point');
    $hook = add_submenu_page( 'sms-ru-options', 'Рассылка', 'Рассылка', 'manage_options', 'newsletter-page', 'sms_ru_newsletter_menu_point');
    add_action( "load-$hook", function () {
		add_screen_option('per_page', array('label' => 'Элементов на странице',	'default' => 20, 'option' => 'sms_ru_newsletter_per_page'));
	});

}
*/
/*

// activation plugin
register_activation_hook(__FILE__, 'sms_ru_activation');
function sms_ru_activation() {
    add_option('sms_ru_login', '');
	add_option('sms_ru_api_id', '');
	add_option('sms_ru_name_sendler', '');
	add_option('sms_ru_admin_massage', '');
	add_option('sms_ru_massage_tamplate', 'Новый заказ');
	add_option('sms_ru_client_massages', '');
}

// deactivation plugin
register_deactivation_hook(__FILE__, 'sms_ru_deactivation');
function sms_ru_deactivation() {
	delete_option('sms_ru_login');
	delete_option('sms_ru_api_id');
	delete_option('sms_ru_admin_massage');
	delete_option('sms_ru_massage_tamplate');
	delete_option('sms_ru_client_massages');
	delete_option('sms_ru_name_sendler');
}
*/

// all functions
include_once( SL_DIR . 'functions/functions.php');
include_once( SL_DIR . 'functions/leads.php');
include_once( SL_DIR . 'functions/reviews.php');

/*
// Page "settings"
if(is_admin()) {
	include_once( SMS_RU_DIR . 'pages/sms_ru_settings.php');
}

// Page "SMS client"
include_once( SMS_RU_DIR . 'pages/sms_ru_client.php');

 // Page "Newsletter"
include_once( SMS_RU_DIR . 'pages/sms_ru_newsletter.php');

*/
