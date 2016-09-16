<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( function_exists( 'current_user_can' ) ) {
	if ( ! current_user_can( 'delete_pages' ) ) {
		die( 'Access Denied' );
	}
}
if ( ! function_exists( 'current_user_can' ) ) {
	die( 'Access Denied' );
}
function hugeit_slider_show_slider() {
	global $wpdb;

	session_start();
	if ( isset( $_REQUEST['csrf_token_hugeit_1752'] ) ) {
		$_REQUEST['csrf_token_hugeit_1752'] = esc_html( $_REQUEST['csrf_token_hugeit_1752'] );
		if ( $_SESSION['csrf_token_hugeit_1752'] == $_REQUEST['csrf_token_hugeit_1752'] ) {
			if ( isset( $_POST['search_events_by_title'] ) ) {
				$_POST['search_events_by_title'] = esc_html( stripslashes( $_POST['search_events_by_title'] ) );
			}
		}
	}
	if ( isset( $_POST['asc_or_desc'] ) ) {
		$_POST['asc_or_desc'] = sanitize_text_field( $_POST['asc_or_desc'] );
		$_POST['asc_or_desc'] = sanitize_text_field( $_POST['asc_or_desc'] );
	}
	if ( isset( $_POST['order_by'] ) ) {
		$_POST['order_by'] = sanitize_text_field( $_POST['order_by'] );
		$_POST['order_by'] = sanitize_text_field( $_POST['order_by'] );
	}
	$where                 = '';
	$sort["custom_style"]  = "manage-column column-autor sortable desc";
	$sort["default_style"] = "manage-column column-autor sortable desc";
	$sort["sortid_by"]     = 'id';
	$sort["1_or_2"]        = 1;
	$order                 = '';
	if ( isset( $_POST['page_number'] ) ) {
		$_POST['page_number'] = absint( $_POST['page_number'] );
		if ( $_POST['asc_or_desc'] ) {
			$sort["sortid_by"] = sanitize_text_field($_POST['order_by']);
			if ( $_POST['asc_or_desc'] == 1 ) {
				$sort["custom_style"] = "manage-column column-title sorted asc";
				$sort["1_or_2"]       = "2";
				$order                = "ORDER BY " . $sort["sortid_by"] . " ASC";
			} else {
				$sort["custom_style"] = "manage-column column-title sorted desc";
				$sort["1_or_2"]       = "1";
				$order                = "ORDER BY " . $sort["sortid_by"] . " DESC";
			}
		}
		if ( $_POST['page_number'] ) {
			$limit = ( $_POST['page_number'] - 1 ) * 20;
		} else {
			$limit = 0;
		}
	} else {
		$limit = 0;
	}
	if ( isset( $_POST['search_events_by_title'] ) ) {
		$_POST['search_events_by_title'] = esc_html( $_POST['search_events_by_title'] );
		$search_tag = esc_html( stripslashes( $_POST['search_events_by_title'] ) );
	} else {
		$search_tag = "";
	}
	if ( isset( $_GET["catid"] ) ) {
		$cat_id = absint( $_GET["catid"] );
	} else {
		if ( isset( $_POST['cat_search'] ) ) {
			$_POST['cat_search'] = sanitize_text_field( $_POST['cat_search'] );
			$cat_id = absint($_POST['cat_search']);
		} else {
			$cat_id = 0;
		}
	}
	if ( $search_tag ) {
		$where = " WHERE name LIKE '%" . $search_tag . "%' ";
	}
	if ( $where ) {
		if ( $cat_id ) {
			$where .= " AND sl_width=" . $cat_id;
		}
	} else {
		if ( $cat_id ) {
			$where .= " WHERE sl_width=" . $cat_id;
		}
	}
	$cat_row_query    = "SELECT id,name FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE sl_width=0";
	$cat_row          = $wpdb->get_results( $cat_row_query );
	$query            = "SELECT COUNT(*) FROM " . $wpdb->prefix . "huge_itslider_sliders" . $where;
	$total            = $wpdb->get_var( $query );
	$pageNav['total'] = $total;
	$pageNav['limit'] = $limit / 20 + 1;

	if ($cat_id) {
	$query ="SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itslider_sliders  AS a LEFT JOIN ".$wpdb->prefix."huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering as ordering,".$wpdb->prefix."huge_itslider_sliders.id AS id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itslider_sliders.name AS par_name,".$wpdb->prefix."huge_itslider_sliders.id FROM ".$wpdb->prefix."huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE  a.name LIKE '%".$search_tag."%' group by a.id ". $order . " LIMIT ".$limit.",20" ;
	 } else {
	 $query ="SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itslider_sliders  AS a LEFT JOIN ".$wpdb->prefix."huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering as ordering,".$wpdb->prefix."huge_itslider_sliders.id AS id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itslider_sliders.name AS par_name,".$wpdb->prefix."huge_itslider_sliders.id FROM ".$wpdb->prefix."huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%".$search_tag."%'  group by a.id ". $order ." "." LIMIT ".$limit.",20" ; 
}

$rows = $wpdb->get_results($query);
	global $glob_ordering_in_cat;
	if ( isset( $sort["sortid_by"] ) ) {
		$sort["sortid_by"] = esc_html( $sort["sortid_by"] );
		if ( $sort["sortid_by"] == 'ordering' ) {
			if ( $_POST['asc_or_desc'] == 1 ) {
				$glob_ordering_in_cat = " ORDER BY ordering ASC";
			} else {
				$glob_ordering_in_cat = " ORDER BY ordering DESC";
			}
		}
	}
	$rows      = hugeit_slider_open_cat_in_tree( $rows );
	$query     = "SELECT  " . $wpdb->prefix . "huge_itslider_sliders.ordering," . $wpdb->prefix . "huge_itslider_sliders.id, COUNT( " . $wpdb->prefix . "huge_itslider_images.slider_id ) AS prod_count
FROM " . $wpdb->prefix . "huge_itslider_images, " . $wpdb->prefix . "huge_itslider_sliders
WHERE " . $wpdb->prefix . "huge_itslider_images.slider_id = " . $wpdb->prefix . "huge_itslider_sliders.id
GROUP BY " . $wpdb->prefix . "huge_itslider_images.slider_id ";
	$prod_rows = $wpdb->get_results( $query );

	foreach ( $rows as $row ) {

		foreach ( $prod_rows as $row_1 ) {
			if ( $row->id == $row_1->id ) {
				$row->ordering   = $row_1->ordering;
				$row->prod_count = $row_1->prod_count;
			}
		}

	}

	$cat_row = hugeit_slider_open_cat_in_tree( $cat_row );
	hugeit_slider_html_show_sliders( $rows, $pageNav, $sort, $cat_row );
}

function hugeit_slider_open_cat_in_tree( $catt, $tree_problem = '', $hihiih = 1 ) {

	global $wpdb;
	global $glob_ordering_in_cat;
	static $trr_cat = array();
	if ( ! isset( $search_tag ) ) {
		$search_tag = '';
	}
	if ( $hihiih ) {
		$trr_cat = array();
	}
	foreach ( $catt as $local_cat ) {
		$local_cat->name = $tree_problem . $local_cat->name;
		array_push( $trr_cat, $local_cat );
		$new_cat_query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "huge_itslider_sliders  AS a LEFT JOIN " . $wpdb->prefix . "huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  " . $wpdb->prefix . "huge_itslider_sliders.ordering AS ordering," . $wpdb->prefix . "huge_itslider_sliders.id AS id, COUNT( " . $wpdb->prefix . "huge_itslider_images.slider_id ) AS prod_count
FROM " . $wpdb->prefix . "huge_itslider_images, " . $wpdb->prefix . "huge_itslider_sliders
WHERE " . $wpdb->prefix . "huge_itslider_images.slider_id = " . $wpdb->prefix . "huge_itslider_sliders.id
GROUP BY " . $wpdb->prefix . "huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "huge_itslider_sliders.name AS par_name," . $wpdb->prefix . "huge_itslider_sliders.id FROM " . $wpdb->prefix . "huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%" . $search_tag . "%' AND a.sl_width=" . $local_cat->id . " GROUP BY a.id  " . $glob_ordering_in_cat;
		$new_cat       = $wpdb->get_results( $new_cat_query );
		hugeit_slider_open_cat_in_tree( $new_cat, $tree_problem . "— ", 0 );
	}

	return $trr_cat;

}

function hugeit_slider_edit_slider( $id ) {

	global $wpdb;

	if ( isset( $_GET["removeslide"] ) ) {
		$getremoveslide = absint( $_GET["removeslide"] );
		if ( $getremoveslide != 0 ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "huge_itslider_images  WHERE id = %d ", $getremoveslide ) );
		}
	}
	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id= %d", $id );
	$row   = $wpdb->get_row( $query );
	if ( ! isset( $row->slider_list_effects_s ) ) {
		return 'id not found';
	}
	$images    = explode( ";;;", $row->slider_list_effects_s );
	$par       = explode( '	', $row->param );
	$count_ord = count( $images );
	$cat_row   = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id!=" . $id . " AND sl_width=0" );
	$cat_row   = hugeit_slider_open_cat_in_tree( $cat_row );
	$query     = $wpdb->prepare( "SELECT name,ordering FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE sl_width=%d  ORDER BY `ordering` ", $row->sl_width );
	$ord_elem  = $wpdb->get_results( $query );

	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY ordering ASC  ", $row->id );
	$rowim = $wpdb->get_results( $query );
	$query  = "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders ORDER BY id ASC";
	$rowsld = $wpdb->get_results( $query );

	$query = "SELECT *  FROM " . $wpdb->prefix . "huge_itslider_params ";

	$rowspar = $wpdb->get_results( $query );

	$paramssld = array();
	foreach ( $rowspar as $rowpar ) {
		$key               = $rowpar->name;
		$value             = $rowpar->value;
		$paramssld[ $key ] = $value;
	}

	$query      = "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY id ASC";
	$rowsposts  = $wpdb->get_results( $query );
	$rowsposts8 = '';
	$postsbycat = '';
	if ( isset( $_POST["iframecatid"] ) ) {
		$iframecatid = absint( $_POST["iframecatid"] );
		$query       = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "term_relationships WHERE term_taxonomy_id = %d ORDER BY object_id ASC", $iframecatid );
		$rowsposts8  = $wpdb->get_results( $query );

		foreach ( $rowsposts8 as $rowsposts13 ) {
			$query      = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'post' AND post_status = 'publish' AND ID = %d  ORDER BY ID ASC", $rowsposts13->object_id );
			$rowsposts1 = $wpdb->get_results( $query );
			$postsbycat = $rowsposts1;
		}
	}
	hugeit_slider_html_edit_slider( $ord_elem, $count_ord, $images, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat );
}

function hugeit_slider_add_slider() {
	global $wpdb;

	$wpdb->insert(
		$wpdb->prefix . "huge_itslider_sliders",
		array(
			'name' => 'New slider',
			'sl_height' => '375',
			'sl_width' => '600',
			'pause_on_hover' => 'on',
			'slider_list_effects_s' => 'cubeH',
			'description' => '4000',
			'param' => '1000',
			'sl_position' => 'center',
			'ordering' => '1',
			'published' => '300',
			'sl_loading_icon' => 'off',
		)
	);

	$save_link = html_entity_decode(wp_nonce_url('admin.php?page=sliders_huge_it_slider&id=' . $wpdb->insert_id . '&task=apply', 'apply_slider_' . $wpdb->insert_id, 'hugeit_slider_apply_slider'));
	header( 'Location: ' . $save_link );
}

function hugeit_slider_popup_posts( $id ) {
	global $wpdb;

	if ( isset( $_GET["removeslide"] ) ) {
		$getremove = absint( $_GET["removeslide"] );
		if ( $getremove != 0 ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "huge_itslider_images  WHERE id = %d ", $getremove ) );
		}
	}

	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id= %d", $id );
	$row   = $wpdb->get_row( $query );
	if ( ! isset( $row->slider_list_effects_s ) ) {
		return 'id not found';
	}
	$images    = explode( ";;;", $row->slider_list_effects_s );
	$par       = explode( '	', $row->param );
	$count_ord = count( $images );
	$cat_row   = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id!=" . $id . " AND sl_width=0" );
	$cat_row   = hugeit_slider_open_cat_in_tree( $cat_row );
	$query     = $wpdb->prepare( "SELECT name,ordering FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE sl_width=%d  ORDER BY `ordering` ", $row->sl_width );
	$ord_elem  = $wpdb->get_results( $query );

	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY id ASC  ", $row->id );
	$rowim = $wpdb->get_results( $query );

	if ( isset( $_GET["addslide"] ) ) {
		$getaddslide = intval( $_GET["addslide"] );
	}

	$query  = "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders ORDER BY id ASC";
	$rowsld = $wpdb->get_results( $query );

	$query = "SELECT *  FROM " . $wpdb->prefix . "huge_itslider_params ";

	$rowspar = $wpdb->get_results( $query );

	$paramssld = array();
	foreach ( $rowspar as $rowpar ) {
		$key               = $rowpar->name;
		$value             = $rowpar->value;
		$paramssld[ $key ] = $value;
	}

	$query     = "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY id ASC";
	$rowsposts = $wpdb->get_results( $query );

	$categories = get_categories();
	if ( isset( $_POST["iframecatid"] ) ) {
		$iframecatid = absint( $_POST["iframecatid"] );
	} else {
		if ( isset( $categories[0]->cat_ID ) ) {
			$iframecatid = $categories[0]->cat_ID;
		} else {
			$iframecatid = '';
		}
	}

	$query      = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "term_relationships WHERE term_taxonomy_id = %d ORDER BY object_id ASC", $iframecatid );
	$rowsposts8 = $wpdb->get_results( $query );

	foreach ( $rowsposts8 as $rowsposts13 ) {
		$query      = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'post' AND post_status = 'publish' AND ID = %d  ORDER BY ID ASC", $rowsposts13->object_id );
		$rowsposts1 = $wpdb->get_results( $query );

		$postsbycat = $rowsposts1;

	}
	global $wpdb;

	if ( isset( $_GET["closepop"] ) ) {
		$getclosepopup = absint( $_GET["closepop"] );
		if ( $getclosepopup == 1 ) {

			if ( isset( $_POST["popupposts"] ) ) {
				$postpopupposts = esc_html( $_POST["popupposts"] );
				if ( $postpopupposts != 'none' and $postpopupposts != '' ) {
					$popuppostsposts = explode( ";", $_POST["popupposts"] );
					array_pop( $popuppostsposts );

					foreach ( $popuppostsposts as $popuppostsposts1 ) {
						$my_id = $popuppostsposts1;

						$post_id_1 = get_post( $my_id );

						$post_image = wp_get_attachment_url( get_post_thumbnail_id( $popuppostsposts1 ) );
						$posturl    = get_permalink( $popuppostsposts1 );
						$table_name = $wpdb->prefix . "huge_itslider_images";

						$descnohtmlnoq  = strip_tags( $post_id_1->post_content );
						$descnohtmlnoq1 = html_entity_decode( $descnohtmlnoq );
						$descnohtmlnoq1 = htmlentities( $descnohtmlnoq1, ENT_QUOTES, "UTF-8" );

						$query        = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY id ASC", $row->id );
						$rowplusorder = $wpdb->get_results( $query );

						foreach ( $rowplusorder as $key => $rowplusorders ) {
							$rowplusorderspl = $rowplusorders->ordering + 1;
							$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET ordering = '" . $rowplusorderspl . "' WHERE id = %d ", $rowplusorders->id ) );

						}
					}

				}
			}
			if ( ! isset( $_POST["lastposts"] ) ) {
				if ( isset( $_POST["posthuge-it-description-length"] ) ) {
					$POST_hugeit_description_length = intval( $_POST["posthuge-it-description-length"] );
					$table_name                     = $wpdb->prefix . "huge_itslider_sliders";
					$wpdb->query( $wpdb->prepare( "UPDATE %s huge_itslider_sliders SET published = %d WHERE id = ", $table_name, absint( $_GET['id'] ) ) );
				}
			}
		}
	}

	if ( isset( $_POST["lastposts"] ) ) {
		$_POST["lastposts"] = esc_html( $_POST["lastposts"] );
		$query              = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY id DESC LIMIT 0, " . absint($_POST["lastposts"]) );
		$rowspostslast      = $wpdb->get_results( $query );
		foreach ( $rowspostslast as $rowspostslastfor ) {

			$my_id     = $rowspostslastfor;
			$post_id_1 = get_post( $my_id );

			$post_image     = wp_get_attachment_url( get_post_thumbnail_id( $rowspostslastfor ) );
			$posturl        = get_permalink( $rowspostslastfor );
			$table_name     = $wpdb->prefix . "huge_itslider_images";
			$descnohtmlno   = strip_tags( $post_id_1->post_content );
			$descnohtmlno1  = html_entity_decode( $descnohtmlno );
			$lengthtextpost = '300';
			$descnohtmlno2  = substr_replace( $descnohtmlno1, "", $lengthtextpost );
			$descnohtmlno3  = htmlentities( $descnohtmlno2, ENT_QUOTES, "UTF-8" );
			$posttitle      = htmlentities( $post_id_1->post_title, ENT_QUOTES, "UTF-8" );
			$posturl2       = htmlentities( $posturl, ENT_QUOTES, "UTF-8" );

			$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` ( `name`, `slider_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width` ) VALUES ( '%s', '%s', '%s', '%s', '%s', '0', 2, '1' )", $posttitle, $row->id, $descnohtmlno3, $post_image, $posturl ) );
		}
	}
	if ( isset( $_POST["addlastposts"] ) ) {
		$_POST["addlastposts"] = esc_html( $_POST["addlastposts"] );
		if ( $_POST["addlastposts"] == 'addlastposts' ) {
			$query        = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY id ASC", $row->id );
			$rowplusorder = $wpdb->get_results( $query );

			foreach ( $rowplusorder as $key => $rowplusorders ) {
				$rowplusorderspl = $rowplusorders->ordering + 1;
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET ordering = '" . $rowplusorderspl . "' WHERE id = %d ", $rowplusorders->id ) );
			}


			$table_name       = $wpdb->prefix . "huge_itslider_images";
			$sql_addlastposts = $wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` ( `name`, `slider_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `sl_stitle`, `sl_sdesc`, `sl_postlink`, `ordering`, `published`, `published_in_sl_width` ) VALUES" . "( '%s', '%d', '%s', '', '%s', 'last_posts', '%s', '%s', '%s', '%s', '0', '2', '1' )", sanitize_text_field($_POST["titleimage"]), absint($row->id), sanitize_text_field($_POST["im_description"]), esc_url($_POST["sl_url"]), sanitize_text_field($_POST["sl_link_target"]), sanitize_text_field($_POST["sl_stitle"]), sanitize_text_field($_POST["sl_sdesc"]), sanitize_text_field($_POST["sl_postlink"]) ) );
		}
	}

	if ( ! isset( $postsbycat ) ) {
		$postsbycat = '';
	}
	hugeit_slider_html_popup_posts( $ord_elem, $count_ord, $images, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat );
}

function hugeit_slider_popup_video( $id ) {
	hugeit_slider_html_popup_video();
}

function hugeit_slider_remove_slider( $id ) {

	global $wpdb;
	$sql_remov_tag = $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id = %d", $id );
	if ( ! $wpdb->query( $sql_remov_tag ) ) {
		?>
		<div id="message" class="error"><p>Slider Deleted</p></div>
		<?php

	} else {
		?>
		<div class="updated"><p><strong><?php _e( 'Item Deleted.' ); ?></strong></p></div>
		<?php
	}
}

function hugeit_slider_apply_cat( $id ) {
	global $wpdb;
	if ( ! is_numeric( $id ) ) {
		echo 'Insert numeric id';

		return '';
	}
	if ( ! ( isset( $_POST['sl_width'] ) && isset( $_POST["name"] ) ) ) {
		return '';
	}
	$cat_row    = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id != %d ", $id ) );
	$corent_ord = $wpdb->get_var( $wpdb->prepare( 'SELECT `ordering` FROM ' . $wpdb->prefix . 'huge_itslider_sliders WHERE id = %d AND sl_width=%d', $id, $_POST['sl_width'] ) );
	$max_ord    = $wpdb->get_var( 'SELECT MAX(ordering) FROM ' . $wpdb->prefix . 'huge_itslider_sliders' );

	$query  = $wpdb->prepare( "SELECT sl_width FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id = %d", $id );
	$id_bef = $wpdb->get_var( $query );

	if ( isset( $_POST["content"] ) ) {
		$_POST["content"] = esc_html( $_POST["content"] );
		$script_cat       = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', stripslashes( $_POST["content"] ) );
	}

	$post_slname = esc_html( $_POST["name"] );
	$post_sl_width = esc_html( $_POST["sl_width"] );
	$post_sl_height = esc_html( $_POST["sl_height"] );
	$post_pause_on_hover = esc_html( $_POST["pause_on_hover"] );
	$post_slider_effects_list = esc_html( $_POST["slider_effects_list"] );
	$post_sl_pausetime = esc_html( $_POST["sl_pausetime"] );
	$post_sl_changespeed = esc_html( $_POST["sl_changespeed"] );
	$post_sl_position = esc_html( $_POST["sl_position"] );
	$post_sl_loading_icon = esc_html( $_POST["sl_loading_icon"] );
	$post_show_thumb = esc_html( $_POST["show_thumb"] );
	$post_show_video_autoplay = esc_html( $_POST["video_autoplay"] );

	$wpdb->update(
		$wpdb->prefix . "huge_itslider_sliders",
		array(
			'name' => $post_slname,
			'sl_width' => $post_sl_width,
			'sl_height' => $post_sl_height,
			'pause_on_hover' => $post_pause_on_hover,
			'slider_list_effects_s' => $post_slider_effects_list,
			'description' => $post_sl_pausetime,
			'param' => $post_sl_changespeed,
			'ordering' => 1,
			'sl_position' => $post_sl_position,
			'sl_loading_icon' => $post_sl_loading_icon,
			'show_thumb' => $post_show_thumb,
			'video_autoplay' => $post_show_video_autoplay,
			'random_images' => $_POST["random_images"],
		),
		array('id' => absint($id)),
		array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s')
	);

	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id = %d", $id );
	$row   = $wpdb->get_row( $query );

	$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY id ASC", $row->id );
	$rowim = $wpdb->get_results( $query );

	foreach ( $rowim as $key => $rowimages ) {
		$formattedDescription = wp_unslash(esc_html($_POST["im_description" . $rowimages->id]));
		$formattedTitle = wp_unslash(esc_html($_POST["titleimage" . $rowimages->id]));

		$description = substr($formattedDescription, -1) === '\\' ? $formattedDescription . ' ' : $formattedDescription;
		$title = substr($formattedTitle, -1) === '\\' ? $formattedTitle . ' ' : $formattedTitle;

		$wpdb->update(
			$wpdb->prefix . "huge_itslider_images",
			array(
				'ordering' => sanitize_text_field($_POST[ "order_by_" . $rowimages->id ]),
				'link_target' => sanitize_text_field($_POST[ "sl_link_target" . $rowimages->id ]),
				'sl_url' => esc_url($_POST[ "sl_url" . $rowimages->id ]),
				'name' => $title,
				'description' => $description,
			),
			array('id' => absint($rowimages->id))
		);

		if ( isset( $_POST[ "imagess" . $rowimages->id . "" ] ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET  image_url = '" . esc_url($_POST[ "imagess" . $rowimages->id . "" ]) . "'  WHERE ID = %d ", $rowimages->id ) );
		}
/////////////////update///////////////////////////
		if ( isset( $_POST[ "sl_stitle" . $rowimages->id . "" ] ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET  sl_stitle = '" . sanitize_text_field($_POST[ "sl_stitle" . $rowimages->id . "" ]) . "'  WHERE ID = %d ", $rowimages->id ) );
		}
		if ( isset( $_POST[ "sl_sdesc" . $rowimages->id . "" ] ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET  sl_sdesc = '" . sanitize_text_field($_POST[ "sl_sdesc" . $rowimages->id . "" ]) . "'  WHERE ID = %d ", $rowimages->id ) );
		}
		if ( isset( $_POST[ "sl_postlink" . $rowimages->id . "" ] ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET  sl_postlink = '" . $_POST[ "sl_postlink" . $rowimages->id . "" ] . "'  WHERE ID = %d ", $rowimages->id ) );
		}
////////////////update///////////////////////////

	}

	if ( isset( $_POST['params'] ) ) {
		$params = $_POST['params'];
		foreach ( $params as $key => $value ) {
			$value = sanitize_text_field($value);
			$wpdb->update( $wpdb->prefix . 'huge_itslider_params', array( 'value' => $value ), array( 'name' => $key ), array( '%s' ) );
		}
	}

	if ( trim($_POST["imagess"]) != '' ) {
		$query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = %d ORDER BY id ASC", $row->id );
		$rowim = $wpdb->get_results( $query );
		foreach ( $rowim as $key => $rowimages ) {
			$orderingplus = $rowimages->ordering + 1;
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_images SET  ordering = %d  WHERE ID = %d ", $orderingplus, $rowimages->id ) );
		}
		$table_name        = $wpdb->prefix . "huge_itslider_images";
		$imagesnewuploader = explode( ";;;", $_POST["imagess"] );
		array_pop( $imagesnewuploader );
		foreach ( $imagesnewuploader as $imagesnewupload ) {
			$sql_2 = $wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` ( `name`, `slider_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width` )" . "VALUES ( '', '%d', '', '%s', '', 'par_TV', '2', '1' )", $row->id, $imagesnewupload ) );
		}
	}

	if ( isset( $_POST["posthuge-it-description-length"] ) ) {
		$post_huge_it_description = absint( $_POST["posthuge-it-description-length"] );
		$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  published = %d WHERE id = %d ", $post_huge_it_description, absint( $_GET['id'] ) ) );
	}
	?>
	<div class="updated"><p><strong><?php _e( 'Item Saved' ); ?></strong></p></div>
	<?php

	return true;
}
