<?php
/*
Plugin Name: Simple Leads
Description: Плагин для приема заявок с сайта
Plugin URI: https://github.com/shtekleinmax/simple-leads
Author: Shteklein Maxim
Author URI: https://github.com/shtekleinmax/simple-leads
Version: 1.0.1
*/

define('SL_DIR', plugin_dir_path(__FILE__));

// all functions
include_once( SL_DIR . 'functions/functions.php');
include_once( SL_DIR . 'functions/leads.php');
