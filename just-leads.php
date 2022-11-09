<?php
/*
Plugin Name: Just Leads
Description: Плагин для приема и обработки лидов с сайта
Plugin URI:  https://github.com/shtekleinmax/just-leads
Author:      Shteklein Maxim
Author URI:  https://maxweb.kz/
Version:     1.0.1
*/

define('SL_DIR', plugin_dir_path(__FILE__));

// all functions
include_once( SL_DIR . 'functions/functions.php');
include_once( SL_DIR . 'functions/leads.php');
