<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
function hugeit_slider_show_published_images_1( $id ) {
	global $wpdb;
	$query  = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id = '%d' ORDER BY id ASC", $id );
	$slider = $wpdb->get_results( $query );
	$query  = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = '%d' ORDER BY ordering ASC", $id );
	$images = $wpdb->get_results( $query );
	if ( $slider[0]->random_images == "on" ) {
		shuffle( $images );
	}
	$query     = "SELECT * FROM " . $wpdb->prefix . "huge_itslider_params";
	$rowspar   = $wpdb->get_results( $query );
	$paramssld = array();
	foreach ( $rowspar as $rowpar ) {
		$key               = $rowpar->name;
		$value             = $rowpar->value;
		$paramssld[ $key ] = $value;
	}

	return hugeit_slider_front_end_slider( $images, $paramssld, $slider );
}
