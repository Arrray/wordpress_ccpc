<?php

class WDSModelSlider {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function get_slide_rows_data($id) {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE published=1 AND slider_id="%d" AND image_url<>"" ORDER BY `order` asc', $id));
    foreach ($rows as $row) {
      $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
      $row->thumb_url = str_replace('{site_url}', site_url(), $row->thumb_url);
    }
    return $rows;
  }
  
  public function get_slider_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
    if ($row) {
      $row->music_url = str_replace('{site_url}', site_url(), $row->music_url);
      $row->right_butt_url = str_replace('{site_url}', site_url(), $row->right_butt_url);
      $row->left_butt_url = str_replace('{site_url}', site_url(), $row->left_butt_url);
      $row->right_butt_hov_url = str_replace('{site_url}', site_url(), $row->right_butt_hov_url);
      $row->left_butt_hov_url = str_replace('{site_url}', site_url(), $row->left_butt_hov_url);
      $row->bullets_img_main_url = str_replace('{site_url}', site_url(), $row->bullets_img_main_url);
      $row->bullets_img_hov_url = str_replace('{site_url}', site_url(), $row->bullets_img_hov_url);
      $row->play_butt_url = str_replace('{site_url}', site_url(), $row->play_butt_url);
      $row->play_butt_hov_url = str_replace('{site_url}', site_url(), $row->play_butt_hov_url);
      $row->paus_butt_url = str_replace('{site_url}', site_url(), $row->paus_butt_url);
      $row->paus_butt_hov_url = str_replace('{site_url}', site_url(), $row->paus_butt_hov_url);
    }
    return $row;
  }

  public function get_layers_row_data($slide_id) {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wdslayer WHERE slide_id='%d' ORDER BY `depth` ASC", $slide_id));
    foreach ($rows as $row) {
      $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
    }
    return $rows;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}