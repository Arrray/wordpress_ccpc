<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( function_exists( 'current_user_can' ) ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( 'Access Denied' );
	}
}
if ( ! function_exists( 'current_user_can' ) ) {
	die( 'Access Denied' );
}
function hugeit_slider_show_styles( $op_type = "0" ) {
	global $wpdb;
	$query        = "SELECT *  FROM " . $wpdb->prefix . "huge_itslider_params ";
	$rows         = $wpdb->get_results( $query );
	$param_values = array();
	foreach ( $rows as $row ) {
		$key                  = $row->name;
		$value                = $row->value;
		$param_values[ $key ] = $value;
	}
	hugeit_slider_html_show_styles( $param_values, $op_type );
}