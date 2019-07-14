<?php
/**
 * Functions of plugine
 *
 * @package SimpleLeads
 * @since 1.0.0
 */

/*===========================================================================================================================*/


/**
 * Simple lead validation
 */
function simple_lead_validation( $fields ) {
    $error = '';
    if ( empty( $fields['name'] ) ) {
        $error .= '&entryname=Пожалуйста, введите Ваше имя';
    }

    if ( empty( $fields['phone'] ) ) {
        $error .= '&entryphone=Пожалуйста, введите Ваш телефон';
    }

    if ( !empty( $error ) ) {
        header( 'Location: '.site_url( $fields['_wp_http_referer'] ).'?'.$error );
        exit;
    }
}


/**
 * Removing parentheses, spaces, hyphens from a phone number
 *
 * @param string telephone number, exempe: +7 (777) 165-51-28
 * @return string, exemple: 77771655128
 */
function simple_lead_phone_processing( $phone ) {
	$phone = str_replace( [' ', '(', ')', '-'], '', $phone );
	return $phone;
}