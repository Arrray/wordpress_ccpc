<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
function hugeit_slider_front_end_slider( $images, $paramssld, $slider ) {
ob_start();
if ( isset( $slider ) ) {
	$sliderID                       = $slider[0]->id;
	$slidertitle                    = $slider[0]->name;
	$sliderheight                   = $slider[0]->sl_height;
	$sliderwidth                    = $slider[0]->sl_width;
	$slidereffect                   = $slider[0]->slider_list_effects_s;
	$slidepausetime                 = ( $slider[0]->description + $slider[0]->param );
	$sliderpauseonhover             = $slider[0]->pause_on_hover;
	$sliderposition                 = $slider[0]->sl_position;
	$slidechangespeed               = $slider[0]->param;
	$sliderloadingicon              = $slider[0]->sl_loading_icon;
	$sliderthumbslider              = $slider[0]->show_thumb;
	$slidervideoautoplay            = $slider[0]->video_autoplay;
	$sliderrandom                   = $slider[0]->random_images;
	$slideshow_title_position       = explode( '-', trim( $paramssld['slider_title_position'] ) );
	$slideshow_description_position = explode( '-', trim( $paramssld['slider_description_position'] ) );
}
$hasyoutube = false;
$hasvimeo   = false;
foreach ( $images as $key => $image_row ) {
	if ( strpos( $image_row->image_url, 'youtube' ) !== false || strpos( $image_row->image_url, 'youtu.be' ) !== false ) {
		$hasyoutube = true;
	}
	if ( strpos( $image_row->image_url, 'vimeo' ) !== false ) {
		$hasvimeo = true;
	}
}
$GLOBALS['pause_time']      = $slidepausetime;
$GLOBALS['thumbnail_width'] = $sliderwidth;
$GLOBALS['changespeed']     = $slider[0]->param;
?>
<script>
    var pausehover="<?php echo $sliderpauseonhover;?>";
	var huge_video_playing={};
	var autoplayMatch={};
    var scrolling;
    var ifhasthumb ="<?php echo $sliderthumbslider; ?>";

    scrolling = pausehover != 'on';

    jQuery(window).load(function(){
        if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
        setTimeout(function(){
            jQuery('.huge_it_slide_container_<?php echo $sliderID; ?>').mouseleave();
        }, 10);
    });
</script>


<?php if ($hasvimeo){?>
<script src="<?php echo plugins_url( 'js/vimeo.lib.js' , __FILE__ ) ?>"></script>
<!--<script src="https://f.vimeocdn.com/js/froogaloop2.min.js "></script>-->

<?php }
if ($hasyoutube){?>

<script src="<?php echo plugins_url( 'js/youtube.lib.js' , __FILE__ ) ?>"></script>
<?php }?>
<!--  #######ADD YOUTUBE IFRAME TWICE######## -->
<script>
	<?php
	if ( ! function_exists( 'hugeit_slider_get_youtube_id_from_url' ) ) {
		function hugeit_slider_get_youtube_id_from_url( $url ) {
			if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
				return $match[1];
			}
		}
	}

	$i = 0;
	foreach ($images as $key => $image_row) :?>
	<?php
	if (strpos( $image_row->image_url, 'vimeo' ) !== false){ ?>
	HUGEIT_VIMEOS['iframe' + '<?php echo $image_row->id; ?>'] = {
		id: '<?php echo $image_row->id; ?>',
		parentId: '<?php echo $sliderID; ?>',
		//videoId:'<?php echo hugeit_slider_get_youtube_id_from_url( $image_row->image_url ); ?>',
		volume: parseFloat('<?php echo $image_row->description; ?>') / 100,
		color: '<?php echo $image_row->link_target;?>',
		state: '',
		autoplay: '<?php echo ( $slidervideoautoplay == 'off' ) ? '' : $slidervideoautoplay;?>'
	};
	<?php
	} else if($image_row->sl_type == "video" and strpos( $image_row->image_url, 'youtu' ) !== false){
	?>
	HUGEIT_YT_IFRAMES['iframe' + '<?php echo $image_row->id; ?>'] = {
		id: '<?php echo $image_row->id; ?>',
		parentId: '<?php echo $sliderID; ?>',
		videoId: '<?php echo hugeit_slider_get_youtube_id_from_url( $image_row->image_url ); ?>',
		volume: '<?php echo $image_row->description; ?>',
		controls: '<?php echo $image_row->sl_url; ?>',
		showinfo: '<?php echo $image_row->link_target;?>',
		quality: '<?php echo $image_row->name;?>', state: '',
		autoplay: '<?php echo ( $slidervideoautoplay == 'off' ) ? '' : $slidervideoautoplay;?>'
	};


	<?php
	} else {
		continue;
	}
	$i ++;
	endforeach;
	?>

	var YTdeferred = jQuery.Deferred();
	window.onYouTubeIframeAPIReady = function() {
		//resolve when youtube callback is called passing YT as a parameter
		YTdeferred.resolve(window.YT);

	};
	YTdeferred.done(function(YT) {

		jQuery('.huge-it-iframe').each(function(){
			
			var el_id = jQuery(this).attr('data-element-id');
			var volume = HUGEIT_YT_IFRAMES['iframe'+el_id]['volume'];
			
			window['onPlayerReady'+el_id] = function(event) {
				window['player_'+el_id]['setVolume'](volume);
			}
		});		

		jQuery('.huge-it-iframe').each(function(){
		
			var id = jQuery(this).attr('id');
			var el_id = jQuery(this).attr('data-element-id');
			var controls = HUGEIT_YT_IFRAMES['iframe'+el_id]['controls'];
			var showinfo = HUGEIT_YT_IFRAMES['iframe'+el_id]['showinfo'];
			var url = HUGEIT_YT_IFRAMES['iframe'+el_id]['videoId'];
			controls||(controls = 0);
			showinfo||(showinfo = 0);
			
			window['player_'+el_id] = new YT.Player(id, {
				height: '<?php echo $sliderheight; ?>',
				width: '<?php echo $sliderwidth; ?>',
				videoId: url,
				playerVars: {
					'controls': controls,
					'showinfo': showinfo,
					'rel':0
				},
				events: {
					'onError': window['onPlayerError'+el_id],
					'onReady': window['onPlayerReady'+el_id],
					'onStateChange': window['onPlayerStateChange'+el_id],
					'loop':1
				}
			});			
		})
	});
	
	jQuery(function(){

		jQuery('.huge-it-iframe').each(function(){
			
			var el_id = jQuery(this).attr('data-element-id');
			var quality = HUGEIT_YT_IFRAMES['iframe'+el_id]['quality'];	
		
			window['onPlayerStateChange'+el_id] = function(event) {
		
				if (event.data == YT.PlayerState.PLAYING) {
					HUGEIT_YT_IFRAMES['iframe'+el_id]['state']='played';
					event.target.setPlaybackQuality(quality);
				}
			}
		});
	
	});
	function stopYoutubeVideo(currentVideo,nextVideo) {

		if(currentVideo) {
			(HUGEIT_YT_IFRAMES['iframe'+currentVideo]['state'])?currentVideo&&(window['player_'+currentVideo]['pauseVideo']()):currentVideo&&(window['player_'+currentVideo]['stopVideo']());
		}
		nextVideo&&HUGEIT_YT_IFRAMES['iframe'+nextVideo]['autoplay']&&(window['player_'+nextVideo]['playVideo']());
	}	
	
	function stopVimeoVideo(currentVideo,nextVideo){
		jQuery(currentVideo).vimeo('pause');
		jQuery(nextVideo).vimeo('play');
	}
/*** <Vimeo> initialize And youtube autopay ***/ 

	jQuery( window ).load(function() {	

		(function VimeoOptionsReady(){ //calling funtion once
				jQuery('.huge-it-iframe-vimeo').each(function(){
					var el_id = jQuery(this).attr('data-element-id');
					jQuery(this).vimeo('setColor',HUGEIT_VIMEOS['iframe'+el_id]['color']);
					jQuery(this).vimeo('setVolume',HUGEIT_VIMEOS['iframe'+el_id]['volume']);
				})
		}());
		
		if('<?php echo $slidervideoautoplay;?>' == 'on') {
							

			if(jQuery('.huge-it-iframe-vimeo.autoplay').length) {
								
				(function AutoplayVimeo() {
					var setplayVideo = setInterval(hugeitframe, 100);
					function hugeitframe() {
						var vimeo1 = jQuery('.huge_it_slider_<?php echo $sliderID; ?> li').first().find('.autoplay')
							if(vimeo1.length) {
								vimeo1.vimeo('play');
								clearInterval(setplayVideo);
							}
					}
				}());
			}
			
			if(jQuery('.huge-it-iframe.autoplay').length) {
									
				(function AutoplayYoutube() {
					var setplayVideo = setInterval(hugeitframe, 100);
					function hugeitframe() {
						jQuery('.huge-it-iframe.autoplay').each(function(){
							
						var el_id = jQuery(this).attr('data-element-id');
						
						if(window['player_'+el_id]) {
							window['player_'+el_id]['playVideo']();
							clearInterval(setplayVideo);
						}
						});	
					}
				}());
			}
		}
	});

/*** </Vimeo> initialize***/ 

</script>
<!--  </#######ADD YOUTUBE IFRAME TWICE END########>-->
		
	
<script>

	jQuery(document).ready(function() {

		jQuery('.thumb_wrapper').on('click', function(ev) {
			var myid = jQuery(this).data('rowid');
			myid = parseInt(myid);
			eval('player_' + myid + '.playVideo()');
			ev.preventDefault();

		});
	});

	if(typeof sliderID_array =="undefined") {
		var sliderID_array=[];
	}

	var data_<?php echo $sliderID; ?> = [];
	var event_stack_<?php echo $sliderID; ?> = [];
	<?php
	$args = array(
		'numberposts'      => 10,
		'offset'           => 0,
		'category'         => 0,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'post',
		'post_status'      => 'draft, publish, future, pending, private',
		'suppress_filters' => true,
	);
	$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

	$i = 0;

	foreach ( $images as $image ) {
		$imagerowstype = $image->sl_type;
		if ( $image->sl_type == '' ) {
			$imagerowstype = 'image';
		}
		switch ( $imagerowstype ) {

			case 'image':
				echo 'data_' . $sliderID . '["' . $i . '"]=[];';
				echo 'data_' . $sliderID . '["' . $i . '"]["id"]="' . $i . '";';
				echo 'data_' . $sliderID . '["' . $i . '"]["image_url"]="' . $image->image_url . '";';


				$strdesription = str_replace( '"', "'", $image->description );
				$strdesription = preg_replace( "/\r|\n/", " ", $strdesription );
				echo 'data_' . $sliderID . '["' . $i . '"]["description"]="' . $strdesription . '";';


				$stralt = str_replace( '"', "'", $image->name );
				$stralt = preg_replace( "/\r|\n/", " ", $stralt );
				echo 'data_' . $sliderID . '["' . $i . '"]["alt"]="' . $stralt . '";';
				$i ++;
				break;

			case 'video':
				echo 'data_' . $sliderID . '["' . $i . '"]=[];';
				echo 'data_' . $sliderID . '["' . $i . '"]["id"]="' . $i . '";';
				echo 'data_' . $sliderID . '["' . $i . '"]["image_url"]="' . $image->image_url . '";';


				$strdesription = str_replace( '"', "'", $image->description );
				$strdesription = preg_replace( "/\r|\n/", " ", $strdesription );
				echo 'data_' . $sliderID . '["' . $i . '"]["description"]="' . $strdesription . '";';


				$stralt = str_replace( '"', "'", $image->name );
				$stralt = preg_replace( "/\r|\n/", " ", $stralt );
				echo 'data_' . $sliderID . '["' . $i . '"]["alt"]="' . $stralt . '";';
				$i ++;
				break;


			case 'last_posts':

				$keyForStoping = 0;
				foreach ( $recent_posts as $keyl => $recentimage ) {
					if ( $image->name == "0" ) {
						if ( get_the_post_thumbnail( $recentimage["ID"], 'thumbnail' ) != '' ) {
							if ( $keyl < $image->sl_url ) {
								echo 'data_' . $sliderID . '["' . $i . '"]=[];';
								echo 'data_' . $sliderID . '["' . $i . '"]["id"]="' . $i . '";';
								echo 'data_' . $sliderID . '["' . $i . '"]["image_url"]="' . $recentimage['guid'] . '";';


								$strdesription = str_replace( '"', "'", $recentimage['post_content'] );
								$strdesription = preg_replace( "/\r|\n/", " ", $strdesription );
								$strdesription = substr_replace( $strdesription, "", $image->description );
								echo 'data_' . $sliderID . '["' . $i . '"]["description"]="' . $strdesription . '";';


								$stralt = str_replace( '"', "'", $recentimage['post_title'] );
								$stralt = preg_replace( "/\r|\n/", " ", $stralt );
								echo 'data_' . $sliderID . '["' . $i . '"]["alt"]="' . $stralt . '";';
								$i ++;
							}
						}
					} else {
						$category_id = get_cat_ID( $image->name );                            //       USER CHOOSED CATEGORY
						$category_id_from_posts = wp_get_post_categories( $recentimage['ID'] );    //       GETTING ALL CATEGORIES FOR THIS POST
						if ( $keyForStoping < $image->sl_url ) {
							if ( in_array( $category_id, $category_id_from_posts ) ) {
								if ( get_the_post_thumbnail( $recentimage["ID"], 'thumbnail' ) != '' ) {
									$keyForStoping ++;
									//                                                        if($keyl < $image->sl_url){
									echo 'data_' . $sliderID . '["' . $i . '"]=[];';
									echo 'data_' . $sliderID . '["' . $i . '"]["id"]="' . $i . '";';
									echo 'data_' . $sliderID . '["' . $i . '"]["image_url"]="' . $recentimage['guid'] . '";';


									$strdesription = str_replace( '"', "'", $recentimage['post_content'] );
									$strdesription = preg_replace( "/\r|\n/", " ", $strdesription );
									$strdesription = substr_replace( $strdesription, "", $image->description );
									echo 'data_' . $sliderID . '["' . $i . '"]["description"]="' . $strdesription . '";';


									$stralt = str_replace( '"', "'", $recentimage['post_title'] );
									$stralt = preg_replace( "/\r|\n/", " ", $stralt );
									echo 'data_' . $sliderID . '["' . $i . '"]["alt"]="' . $stralt . '";';
									$i ++;
									//                                                        }
								}
							}
						}
					}

				}
				break;
		}
	}
	?>
	var huge_it_trans_in_progress_<?php echo $sliderID; ?> = false;
	var huge_it_transition_duration_<?php echo $sliderID; ?> = <?php echo $slidechangespeed;?>;
	var huge_interval = {};
	var id_array_index = sliderID_array.length;

	<?php
	$huge_sliderId = '';
	if ( !isset( $huge_sliderId ) ) {
		$huge_sliderId = '';
	}
	if ( $huge_sliderId == ';' ) {
		$huge_sliderId = '';
	}
	if ( $slider[0]->show_thumb == 'thumbnails' ) {
		$huge_sliderId = $slider[0]->id;
	}
//todo:  maybe bug
	?>
	  sliderID_array[id_array_index] = <?php echo $huge_sliderId; ?>
      // Stop autoplay.
      window.clearInterval(huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>]);
	  
     var huge_it_current_key_<?php echo $sliderID; ?> = '<?php echo (isset($current_key) ? $current_key : ''); ?>';
	 function huge_it_move_dots_<?php echo $sliderID; ?>() {
        var image_left = jQuery(".huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").position().left;
        var image_right = jQuery(".huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").position().left + jQuery(".huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").outerWidth(true);
       
      }
      function huge_it_testBrowser_cssTransitions_<?php echo $sliderID; ?>() {
        return huge_it_testDom_<?php echo $sliderID; ?>('Transition');
      }
      function huge_it_testBrowser_cssTransforms3d_<?php echo $sliderID; ?>() {
        return huge_it_testDom_<?php echo $sliderID; ?>('Perspective');
      }
      function huge_it_testDom_<?php echo $sliderID; ?>(prop) {
        // Browser vendor CSS prefixes.
        var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
        // Browser vendor DOM prefixes.
        var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
        var i = domPrefixes.length;
        while (i--) {
          if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
          }
        }
        return false;
      }
		function huge_it_cube_<?php echo $sliderID; ?>(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
		
        /* If browser does not support 3d transforms/CSS transitions.*/
        if (!huge_it_testBrowser_cssTransitions_<?php echo $sliderID; ?>()) {
			jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
          return huge_it_fallback_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
        }
        if (!huge_it_testBrowser_cssTransforms3d_<?php echo $sliderID; ?>()) {
          return huge_it_fallback3d_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
        }
		  jQuery(current_image_class).css({'z-index': 'none'});
          jQuery(next_image_class).css({'z-index' : 2});
        huge_it_trans_in_progress_<?php echo $sliderID; ?> = true;
        /* Set active thumbnail.*/
		jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");  
		jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
        jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>").css('perspective', 1000);
        jQuery(current_image_class).css({
          transform : 'translateZ(' + tz + 'px)',
          backfaceVisibility : 'hidden'
        });
		
		 jQuery(".huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>,.huge_it_slide_bg_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item_<?php echo $sliderID; ?>,.huge_it_slideshow_image_second_item_<?php echo $sliderID; ?> ").css('overflow', 'visible');
		
        jQuery(next_image_class).css({
          opacity : 1,
          filter: 'Alpha(opacity=100)',
          backfaceVisibility : 'hidden',
          transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
        });
        jQuery(".huge_it_slider_<?php echo $sliderID; ?>").css({
          transform: 'translateZ(-' + tz + 'px)',
          transformStyle: 'preserve-3d'
        });
        /* Execution steps.*/
        setTimeout(function () {
          jQuery(".huge_it_slider_<?php echo $sliderID; ?>").css({
            transition: 'all ' + huge_it_transition_duration_<?php echo $sliderID; ?> + 'ms ease-in-out',
            transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
          });
        }, 20);
        /* After transition.*/
        jQuery(".huge_it_slider_<?php echo $sliderID; ?>").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(huge_it_after_trans));
        function huge_it_after_trans() {
		  jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item_<?php echo $sliderID; ?>,.huge_it_slideshow_image_second_item_<?php echo $sliderID; ?> ").css('overflow', 'hidden');
		  jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>").removeAttr('style');
          jQuery(current_image_class).removeAttr('style');
          jQuery(next_image_class).removeAttr('style');
          jQuery(".huge_it_slider_<?php echo $sliderID; ?>").removeAttr('style');
		 // alert(current_image_class+' '+next_image_class);
          jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});
         // huge_it_change_watermark_container_<?php echo $sliderID; ?>();
          huge_it_trans_in_progress_<?php echo $sliderID; ?> = false;
          if (typeof event_stack_<?php echo $sliderID; ?> !== 'undefined' && event_stack_<?php echo $sliderID; ?>.length > 0) {
            key = event_stack_<?php echo $sliderID; ?>[0].split("-");
            event_stack_<?php echo $sliderID; ?>.shift();
            huge_it_change_image_<?php echo $sliderID; ?>(key[0], key[1], data_<?php echo $sliderID; ?>, true,false);
          }
        }
      }
      function huge_it_cubeH_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image width.*/
        var dimension = jQuery(current_image_class).width() / 2;
        if (direction == 'right') {
          huge_it_cube_<?php echo $sliderID; ?>(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          huge_it_cube_<?php echo $sliderID; ?>(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
        }
      }
      function huge_it_cubeV_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image height.*/
        var dimension = jQuery(current_image_class).height() / 2;
        /* If next slide.*/
        if (direction == 'right') {
          huge_it_cube_<?php echo $sliderID; ?>(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          huge_it_cube_<?php echo $sliderID; ?>(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
        }
      }
      /* For browsers that does not support transitions.*/
      function huge_it_fallback_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_fade_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function huge_it_fallback3d_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_sliceV_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
      }
      function huge_it_none_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});

        /* Set active thumbnail.*/
        jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
      }
      function huge_it_fade_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
		if (huge_it_testBrowser_cssTransitions_<?php echo $sliderID; ?>()) {
			
          jQuery(next_image_class).css('transition', 'opacity ' + huge_it_transition_duration_<?php echo $sliderID; ?> + 'ms linear');
		  jQuery(current_image_class).css('transition', 'opacity ' + huge_it_transition_duration_<?php echo $sliderID; ?> + 'ms linear');
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        }
        else {
		
          jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, huge_it_transition_duration_<?php echo $sliderID; ?>);
          jQuery(next_image_class).animate({
              'opacity' : 1,
              'z-index': 2
            }, {
              duration: huge_it_transition_duration_<?php echo $sliderID; ?>,
              complete: function () {return false;}
            });
          // For IE.
          jQuery(current_image_class).fadeTo(huge_it_transition_duration_<?php echo $sliderID; ?>, 0);
          jQuery(next_image_class).fadeTo(huge_it_transition_duration_<?php echo $sliderID; ?>, 1);
        }

		jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");
		jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
      }
      function huge_it_grid_<?php echo $sliderID; ?>(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
        /* If browser does not support CSS transitions.*/
        if (!huge_it_testBrowser_cssTransitions_<?php echo $sliderID; ?>()) {
			jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
          return huge_it_fallback_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
		  
        }
        huge_it_trans_in_progress_<?php echo $sliderID; ?> = true;
        /* Set active thumbnail.*/
		jQuery(".huge_it_slideshow_dots_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_<?php echo $sliderID; ?> + "_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_<?php echo $sliderID; ?>");
        /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
        var count = (huge_it_transition_duration_<?php echo $sliderID; ?>) / (cols + rows);
        /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
        function huge_it_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
          var delay = (c + r) * count;
          /* Return a gridlet elem with styles for specific transition.*/
          return jQuery('<div class="huge_it_gridlet_<?php echo $sliderID; ?>" />').css({
            width : width,
            height : height,
            top : top,
            left : left,
            backgroundImage : 'url("' + src + '")',
            backgroundColor: jQuery(".huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>").css("background-color"),
            /*backgroundColor: rgba(0, 0, 0, 0),*/
            backgroundRepeat: 'no-repeat',
            backgroundPosition : img_left + 'px ' + img_top + 'px',
            backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
            transition : 'all ' + huge_it_transition_duration_<?php echo $sliderID; ?> + 'ms ease-in-out ' + delay + 'ms',
            transform : 'none'
          });
        }
        /* Get the current slide's image.*/
        var cur_img = jQuery(current_image_class).find('img');
        /* Create a grid to hold the gridlets.*/
        var grid = jQuery('<div />').addClass('huge_it_grid_<?php echo $sliderID; ?>');
        /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
        jQuery(current_image_class).prepend(grid);
        /* vars to calculate positioning/size of gridlets*/
        var cont = jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>");
        var imgWidth = cur_img.width();
        var imgHeight = cur_img.height();
        var contWidth = cont.width(),
            contHeight = cont.height(),
            imgSrc = cur_img.attr('src'),/*.replace('/thumb', ''),*/
            colWidth = Math.floor(contWidth / cols),
            rowHeight = Math.floor(contHeight / rows),
            colRemainder = contWidth - (cols * colWidth),
            colAdd = Math.ceil(colRemainder / cols),
            rowRemainder = contHeight - (rows * rowHeight),
            rowAdd = Math.ceil(rowRemainder / rows),
            leftDist = 0,
            img_leftDist = (jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>").width() - cur_img.width()) / 2;
        /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
        tx = tx === 'auto' ? contWidth : tx;
        tx = tx === 'min-auto' ? - contWidth : tx;
        ty = ty === 'auto' ? contHeight : ty;
        ty = ty === 'min-auto' ? - contHeight : ty;
        /* Loop through cols*/
        for (var i = 0; i < cols; i++) {
          var topDist = 0,
              img_topDst = (jQuery(".huge_it_slide_bg_<?php echo $sliderID; ?>").height() - cur_img.height()) / 2,
              newColWidth = colWidth;
          /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
          if (colRemainder > 0) {
            var add = colRemainder >= colAdd ? colAdd : colRemainder;
            newColWidth += add;
            colRemainder -= add;
          }
          /* Nested loop to create row gridlets for each col.*/
          for (var j = 0; j < rows; j++)  {
            var newRowHeight = rowHeight,
                newRowRemainder = rowRemainder;
            /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
            if (newRowRemainder > 0) {
              add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
              newRowHeight += add;
              newRowRemainder -= add;
            }
            /* Create & append gridlet to grid.*/
            grid.append(huge_it_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
            topDist += newRowHeight;
            img_topDst -= newRowHeight;
          }
          img_leftDist -= newColWidth;
          leftDist += newColWidth;
        }
        /* Set event listener on last gridlet to finish transitioning.*/
        var last_gridlet = grid.children().last();
        /* Show grid & hide the image it replaces.*/
        grid.show();
        cur_img.css('opacity', 0);
        /* Add identifying classes to corner gridlets (useful if applying border radius).*/
        grid.children().first().addClass('rs-top-left');
        grid.children().last().addClass('rs-bottom-right');
        grid.children().eq(rows - 1).addClass('rs-bottom-left');
        grid.children().eq(- rows).addClass('rs-top-right');
        /* Execution steps.*/
        setTimeout(function () {
          grid.children().css({
            opacity: op,
            transform: 'rotate('+ ro +'deg) translateX('+ tx +'px) translateY('+ ty +'px) scale('+ sc +')'
          });
        }, 1);
        jQuery(next_image_class).css('opacity', 1);
        /* After transition.*/
        jQuery(last_gridlet).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(huge_it_after_trans));
        function huge_it_after_trans() {
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          cur_img.css('opacity', 1);
          grid.remove();
          huge_it_trans_in_progress_<?php echo $sliderID; ?> = false;
          if (typeof event_stack_<?php echo $sliderID; ?> !== 'undefined' && event_stack_<?php echo $sliderID; ?>.length > 0) {
            key = event_stack_<?php echo $sliderID; ?>[0].split("-");
            event_stack_<?php echo $sliderID; ?>.shift();
            huge_it_change_image_<?php echo $sliderID; ?>(key[0], key[1], data_<?php echo $sliderID; ?>, true,false);
          }
        }
      }
      function huge_it_sliceH_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        huge_it_grid_<?php echo $sliderID; ?>(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_sliceV_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'min-auto';
        }
        else if (direction == 'left') {
          var translateY = 'auto';
        }
        huge_it_grid_<?php echo $sliderID; ?>(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_slideV_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'auto';
        }
        else if (direction == 'left') {
          var translateY = 'min-auto';
        }
        huge_it_grid_<?php echo $sliderID; ?>(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
      }
      function huge_it_slideH_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        huge_it_grid_<?php echo $sliderID; ?>(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
      }
      function huge_it_scaleOut_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_scaleIn_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_blockScale_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_kaleidoscope_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_fan_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var rotate = 45;
          var translateX = 100;
        }
        else if (direction == 'left') {
          var rotate = -45;
          var translateX = -100;
        }
        huge_it_grid_<?php echo $sliderID; ?>(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_blindV_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function huge_it_blindH_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_<?php echo $sliderID; ?>(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function huge_it_random_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
        /* Pick a random transition from the anims array.*/
        this["huge_it_" + anims[Math.floor(Math.random() * anims.length)] + "_<?php echo $sliderID; ?>"](current_image_class, next_image_class, direction);
      }
      
      function iterator_<?php echo $sliderID; ?>() {
	      return 1;
     }
	 
     function huge_it_change_image_<?php echo $sliderID; ?>(current_key, key, data_<?php echo $sliderID; ?>, from_effect, clicked) {

	     if (data_<?php echo $sliderID; ?>[key]) {

		     if (!from_effect) {
			     // Change image key.
			     jQuery("#huge_it_current_image_key_<?php echo $sliderID; ?>").val(key);
			     current_key = jQuery(".huge_it_slideshow_dots_active_<?php echo $sliderID; ?>").attr("data-image_key");
		     }

		     if (huge_it_trans_in_progress_<?php echo $sliderID; ?>) {
			     event_stack_<?php echo $sliderID; ?>.push(current_key + '-' + key);
			     return;
		     }

		     var direction = 'right';
		     if (huge_it_current_key_<?php echo $sliderID; ?> > key) {
			     direction = 'left';
		     }
		     else if (huge_it_current_key_<?php echo $sliderID; ?> == key) {
			     return false;
		     }

		     // Set active thumbnail position.

		     huge_it_current_key_<?php echo $sliderID; ?> = key;
		     jQuery("#huge_it_slideshow_image_<?php echo $sliderID; ?>").attr('data-image_id', data_<?php echo $sliderID; ?>[key]["id"]);


		     jQuery(".huge_it_slideshow_title_text_<?php echo $sliderID; ?>").html(data_<?php echo $sliderID; ?>[key]["alt"]);
		     jQuery(".huge_it_slideshow_description_text_<?php echo $sliderID; ?>").html(data_<?php echo $sliderID; ?>[key]["description"]);

		     var current_image_class = "#image_id_<?php echo $sliderID; ?>_" + data_<?php echo $sliderID; ?>[current_key]["id"];
		     var next_image_class = "#image_id_<?php echo $sliderID; ?>_" + data_<?php echo $sliderID; ?>[key]["id"];


		     if (jQuery(current_image_class).find('.huge_it_video_frame_<?php echo $sliderID; ?>').length > 0) {
			     var streffect = '<?php echo $slidereffect; ?>';
			     if (streffect == "cubeV" || streffect == "cubeH" || streffect == "none" || streffect == "fade") {
				     huge_it_<?php echo $slidereffect; ?>_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
			     } else {
				     huge_it_fade_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
			     }
		     } else {
			     huge_it_<?php echo $slidereffect; ?>_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
		     }


		     jQuery('.huge_it_slideshow_title_text_<?php echo $sliderID; ?>').removeClass('none');
		     if (jQuery('.huge_it_slideshow_title_text_<?php echo $sliderID; ?>').html() == "") {
			     jQuery('.huge_it_slideshow_title_text_<?php echo $sliderID; ?>').addClass('none');
		     }

		     jQuery('.huge_it_slideshow_description_text_<?php echo $sliderID; ?>').removeClass('none');
		     if (jQuery('.huge_it_slideshow_description_text_<?php echo $sliderID; ?>').html() == "") {
			     jQuery('.huge_it_slideshow_description_text_<?php echo $sliderID; ?>').addClass('none');
		     }


		     jQuery(current_image_class).find('.huge_it_slideshow_title_text_<?php echo $sliderID; ?>').addClass('none');
		     jQuery(current_image_class).find('.huge_it_slideshow_description_text_<?php echo $sliderID; ?>').addClass('none');
		     huge_it_move_dots_<?php echo $sliderID; ?>();
		     <?php foreach ($images as $key => $image_row) {if($image_row->sl_type == "video" and strpos( $image_row->image_url, 'youtu' ) !== false){    ?>
		     <?php } } ?>
		     /*** <vy api>  for vimeo and youtube stop pause play etc... ***/
		     var nextVideo = jQuery('#video_id_<?php echo $sliderID; ?>_' + key).attr('data-element-id');
		     var currentVideo = jQuery('#video_id_<?php echo $sliderID; ?>_' + current_key).attr('data-element-id');
		     /*** add ***/
		     (currentVideo || nextVideo) && stopYoutubeVideo(currentVideo, nextVideo);
		     if ((jQuery('#player_<?php echo $sliderID; ?>_' + current_key)).hasClass('huge-it-iframe-vimeo')) {
			     jQuery('#player_<?php echo $sliderID; ?>_' + current_key).vimeo('pause');

		     }
		     if ((jQuery('#player_<?php echo $sliderID; ?>_' + key)).hasClass('huge-it-iframe-vimeo') && ('<?php echo $slidervideoautoplay;?>' == 'on')) {
			     jQuery('#player_<?php echo $sliderID; ?>_' + key).vimeo('play');
		     }
		     /*** </vy api>***/
		     window.clearInterval(huge_interval['huge_it_playInterval_' +<?php echo $sliderID; ?>]);
		     play_<?php echo $sliderID; ?>();
	     }

     }
	var staticthumbWidth;
	jQuery(window).load(function() {
		staticthumbWidth = jQuery('#huge_it_thumb_slider>li').width();

	});
     function huge_it_popup_resize_<?php echo $sliderID; ?>() {

	     var staticsliderwidth =<?php echo $sliderwidth;?>;
	     var sliderwidth =<?php echo $sliderwidth;?>;
	     var bodyWidth = jQuery(window).width();
	     var parentWidth = jQuery(".huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>").parent().width();

	     if (sliderwidth > parentWidth) {
		     sliderwidth = parentWidth;
	     }
	     if (sliderwidth > bodyWidth) {
		     sliderwidth = bodyWidth;
	     }
		var str=<?php echo $sliderheight;?>/(staticsliderwidth);
                var str2 =<?php echo $paramssld['slider_thumb_height']; ?>/<?php echo ($sliderwidth);?>;
                var sliderDynamicImageHeight = sliderwidth*str;
                <?php if($slider[0]->show_thumb == 'thumbnails') {?>
                    var sliderDynamicThumbHeight = sliderwidth*str2;
                    var diff = sliderDynamicThumbHeight/2;
                    jQuery('#huge_it_slideshow_left_<?php echo $sliderID; ?>,#huge_it_slideshow_right_<?php echo $sliderID; ?>').css({
                        'top':'calc(50% - '+diff+'px)'
                    });
                <?php }else {?>
                    var sliderDynamicThumbHeight = 0;
                <?php }?>

                jQuery('.huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>').css({'max-height':sliderDynamicImageHeight+sliderDynamicThumbHeight});
                jQuery('.huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>').css({'max-height':sliderDynamicImageHeight});
                jQuery('.huge_it_slideshow_thumbs_<?php echo $sliderID; ?> li').css({'max-height':sliderDynamicThumbHeight});

                jQuery("#huge_it_loading_image_<?php echo $sliderID; ?>").css({display: "none"});
                jQuery(".huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>").css({opacity: 1});
				jQuery(".huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>").removeClass("nocolor");



		if ("<?php echo $paramssld['slider_crop_image']; ?>"=="crop"){
                        jQuery(".huge_it_slider_ul li img").each(function(){
                            if(jQuery(this).prop('naturalWidth')>jQuery(this).prop('naturalHeight'))
                                jQuery(this).css({'width':'100%','height':'auto'/*,'top':'50%','left':'50%','transform':'translate( -50%, -50% )'*/});
                            else{
                                jQuery(this).css({'height':'100%','width':'auto'/*,'top':'50%','left':'50%','transform':'translate( -50%, -50% )'*/});
                            }
                        });
		}
                <?php if($sliderloadingicon == 'off') {?>
                    jQuery(".huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>").css({opacity:1});
                <?php }?>
      }
      
      jQuery(window).load(function () {
		jQuery(window).resize(function() {
			huge_it_popup_resize_<?php echo $sliderID; ?>();
		});
		
		jQuery('#huge_it_slideshow_left_<?php echo $sliderID; ?>').on('click',function(){
			huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()) - iterator_<?php echo $sliderID; ?>()) >= 0 ? (parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()) - iterator_<?php echo $sliderID; ?>()) % data_<?php echo $sliderID; ?>.length : data_<?php echo $sliderID; ?>.length - 1, data_<?php echo $sliderID; ?>,false,true);
			return false;
		});
		
		jQuery('#huge_it_slideshow_right_<?php echo $sliderID; ?>').on('click',function(){
			huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()) + iterator_<?php echo $sliderID; ?>()) % data_<?php echo $sliderID; ?>.length, data_<?php echo $sliderID; ?>,false,true);
			return false;
		});
	

		
	
		
		huge_it_popup_resize_<?php echo $sliderID; ?>();
        /* Disable right click.*/
        jQuery('div[id^="huge_it_container"]').bind("contextmenu", function () {
          return false;
        });
        			
		/*HOVER SLIDESHOW*/
		jQuery("#huge_it_slideshow_image_container_<?php echo $sliderID; ?>, .huge_it_slideshow_image_container_<?php echo $sliderID; ?>, .huge_it_slideshow_dots_container_<?php echo $sliderID; ?>,#huge_it_slideshow_right_<?php echo $sliderID; ?>,#huge_it_slideshow_left_<?php echo $sliderID; ?>").hover(function(){
			jQuery("#huge_it_slideshow_right_<?php echo $sliderID; ?>").css({'display':'inline'});
			jQuery("#huge_it_slideshow_left_<?php echo $sliderID; ?>").css({'display':'inline'});
		},function(){
			jQuery("#huge_it_slideshow_right_<?php echo $sliderID; ?>").css({'display':'none'});
			jQuery("#huge_it_slideshow_left_<?php echo $sliderID; ?>").css({'display':'none'});
		});
		
		if(pausehover=="on" && !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
			jQuery("#huge_it_slideshow_image_container_<?php echo $sliderID; ?>, .huge_it_slideshow_image_container_<?php echo $sliderID; ?>").hover(function(){
				window.clearInterval(huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>]);
                scrolling = false;
			},function(){
				window.clearInterval(huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>]);
                scrolling = true;                                   
				play_<?php echo $sliderID; ?>();
			});		
		}	
          play_<?php echo $sliderID; ?>();        
      });
        function play_<?php echo $sliderID; ?>(){
            if(scrolling ){
                huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>] = setInterval(function () {
                  var iterator = 1;
                  huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()) + iterator) % data_<?php echo $sliderID; ?>.length, data_<?php echo $sliderID; ?>,false,false);
                }, '<?php echo $slidepausetime; ?>');
            }                              
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                    huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>] = setInterval(function () {
                        var iterator = 1;
                        huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()) + iterator) % data_<?php echo $sliderID; ?>.length, data_<?php echo $sliderID; ?>,false,false);
                    }, '<?php echo $slidepausetime; ?>');
            }
      
        }
      jQuery(window).focus(function() {
        var i_<?php echo $sliderID; ?> = 0;
        jQuery(".huge_it_slider_<?php echo $sliderID; ?>").children("div").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#huge_it_current_image_key_<?php echo $sliderID; ?>").val(i_<?php echo $sliderID; ?>);
          }
          i_<?php echo $sliderID; ?>++;
        });
      });
      jQuery(window).blur(function() {
	console.log(event_stack_<?php echo $sliderID; ?>);
        window.clearInterval(huge_interval['huge_it_playInterval_'+<?php echo $sliderID; ?>]);
      });  
      jQuery(window).focus(function() {
		play_<?php echo $sliderID; ?>()
	});     
    </script>
<style>

.imgc_slider #slider77 {
	border-color: aqua;
    border-width: 3px;
	text-transform: uppercase;
	list-style: none;
	outline: none;
}

	.thumb_image{
		  position: absolute;
		  width: 100%;
		  height: 100%;
		  top: 0;
		  left:0;
	}
	.entry-content a{
		border-bottom: none !important;
	}
	.play-button-slider{
		top: 0;
    left: 0;
    width: 100%;
    height: 100%;
	}
	.youtube-icon { position: absolute;
    
    background:url(<?php echo plugin_dir_url( __FILE__ ); ?>images/play.youtube.png) center center no-repeat;background-size:14%;}
</style>
	<?php
    $args = array(
    'numberposts' => 10,
    'offset' => 0,
    'category' => 0,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_type' => 'post',
    'post_status' => 'draft, publish, future, pending, private',
    'suppress_filters' => true );

    $recent_posts = wp_get_recent_posts( $args, ARRAY_A );
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( 1 ), 'thumbnail' );
	?>

<?php if($sliderloadingicon == "on") { ?>
<div class="huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> nocolor">
<?php } else { ?>
<div class="huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> ">
<?php } ?>
	<?php if($sliderloadingicon == "on") { ?>
		<div id="huge_it_loading_image_<?php echo $sliderID;  ?>" class="display" ><img  src="<?php echo plugins_url('', __FILE__).'/Front_images/loading/loading'.$paramssld["loading_icon_type"].'.gif'; ?>" alt="" /> </div>
		<div class="huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?> nodisplay">
	<?php } else { ?>
		<div id="huge_it_loading_image_<?php echo $sliderID; ?>" class="nodisplay"> <img src="<?php echo plugins_url('', __FILE__).'/Front_images/loading/loading'.$paramssld["loading_icon_type"].'.gif'; ?>"  alt="" width="100" height="100" style=" margin: 0 auto;" /> </div>
		<div class="huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>" class="display">
	<?php } ?>
      <?php
      $current_pos = 0;
      ?>
      
		<!-- ##########################DOTS######################### -->
        <div class="huge_it_slideshow_dots_container_<?php echo $sliderID; ?>">
			  <div class="huge_it_slideshow_dots_thumbnails_<?php echo $sliderID; ?>">
				  <?php
				  $current_image_id = 0;
				  $current_pos      = 0;
				  $current_key      = 0;
				  $stri             = 0;
				  foreach ( $images as $key => $image_row ) {
					  $imagerowstype = $image_row->sl_type;
					  if ( $image_row->sl_type == '' ) {
						  $imagerowstype = 'image';
					  }
					  switch ( $imagerowstype ) {

						  case 'image':

							  if ( $image_row->id == $current_image_id ) {
								  $current_pos = $stri;
								  $current_key = $stri;
							  }

							  ?>
							  <div id="huge_it_dots_<?php echo $stri; ?>_<?php echo $sliderID; ?>"
							       class="huge_it_slideshow_dots_<?php echo $sliderID; ?> <?php echo( ( $key == $current_image_id ) ? 'huge_it_slideshow_dots_active_' . $sliderID : 'huge_it_slideshow_dots_deactive_' . $sliderID ); ?>"
							       onclick="if(jQuery(this).hasClass('huge_it_slideshow_dots_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
							       data-image_id="<?php echo $image_row->id; ?>"
							       data-image_key="<?php echo $stri; ?>"></div>
							  <?php
							  $stri ++;
							  break;
						  case 'video':

							  if ( $image_row->id == $current_image_id ) {
								  $current_pos = $stri;
								  $current_key = $stri;
							  }

							  ?>
							  <div id="huge_it_dots_<?php echo $stri; ?>_<?php echo $sliderID; ?>"
							       class="huge_it_slideshow_dots_<?php echo $sliderID; ?> <?php echo( ( $key == $current_image_id ) ? 'huge_it_slideshow_dots_active_' . $sliderID : 'huge_it_slideshow_dots_deactive_' . $sliderID ); ?>"
							       onclick="if(jQuery(this).hasClass('huge_it_slideshow_dots_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
							       data-image_id="<?php echo $image_row->id; ?>"
							       data-image_key="<?php echo $stri; ?>"></div>
							  <?php
							  $stri ++;
							  break;
						  case 'last_posts':

							  $keyForStoping = 0;
							  foreach ( $recent_posts as $lkeys => $last_posts ) {
								  if ( $image_row->name == "0" ) {
									  if ( $lkeys < $image_row->sl_url ) {
										  if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
											  $imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );

											  if ( $image_row->id == $current_image_id ) {
												  $current_pos = $stri;
												  $current_key = $stri;
											  }
											  ?>
											  <div id="huge_it_dots_<?php echo $stri; ?>_<?php echo $sliderID; ?>"
											       class="huge_it_slideshow_dots_<?php echo $sliderID; ?> <?php echo( ( $stri == $current_image_id ) ? 'huge_it_slideshow_dots_active_' . $sliderID : 'huge_it_slideshow_dots_deactive_' . $sliderID ); ?>"
											       onclick="if(jQuery(this).hasClass('huge_it_slideshow_dots_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
											       data-image_id="<?php echo $image_row->id; ?>"
											       data-image_key="<?php echo $stri; ?>"></div>
											  <?php
											  $stri ++;
										  }
									  }
								  } else {
									  $category_id            = get_cat_ID( $image_row->name );                            //       USER CHOOSED CATEGORY
									  $category_id_from_posts = wp_get_post_categories( $last_posts['ID'] );    //       GETTING ALL CATEGORIES FOR THIS POST
									  if ( $keyForStoping < $image_row->sl_url ) {
										  if ( in_array( $category_id, $category_id_from_posts ) ) {
											  if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
												  $keyForStoping ++;
												  $imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );

												  if ( $image_row->id == $current_image_id ) {
													  $current_pos = $stri;
													  $current_key = $stri;
												  }
												  ?>
												  <div id="huge_it_dots_<?php echo $stri; ?>_<?php echo $sliderID; ?>"
												       class="huge_it_slideshow_dots_<?php echo $sliderID; ?> <?php echo( ( $stri == $current_image_id ) ? 'huge_it_slideshow_dots_active_' . $sliderID : 'huge_it_slideshow_dots_deactive_' . $sliderID ); ?>"
												       onclick="if(jQuery(this).hasClass('huge_it_slideshow_dots_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
												       data-image_id="<?php echo $image_row->id; ?>"
												       data-image_key="<?php echo $stri; ?>"></div>
												  <?php
												  $stri ++;
											  }
										  }
									  }
								  }

							  }
							  break;
					  }
				  }
				  ?>
			  </div>
			
			<?php
			   if ($paramssld['slider_show_arrows']=="on") {
			 ?>
				<a id="huge_it_slideshow_left_<?php echo $sliderID; ?>" href="#">
					<div id="huge_it_slideshow_left-ico_<?php echo $sliderID; ?>">
					<div><i class="huge_it_slideshow_prev_btn_<?php echo $sliderID; ?> fa"></i></div></div>
				</a>
				
				<a id="huge_it_slideshow_right_<?php echo $sliderID; ?>" href="#">
					<div id="huge_it_slideshow_right-ico_<?php echo $sliderID;?>">
					<div><i class="huge_it_slideshow_next_btn_<?php echo $sliderID; ?> fa"></i></div></div>
				</a>
			<?php
			}
			?>
		</div>
	  <!-- ##########################IMAGES######################### -->

      <div id="huge_it_slideshow_image_container_<?php echo $sliderID; ?>" class="huge_it_slideshow_image_container_<?php echo $sliderID; ?>">        
        <div class="huge_it_slide_container_<?php echo $sliderID; ?>">
          <div class="huge_it_slide_bg_<?php echo $sliderID; ?>">
            <ul class="huge_it_slider_<?php echo $sliderID; ?>">
			  <?php
			  $i=0;
			  foreach ($images as $key => $image_row) {
			  	$imagerowstype=$image_row->sl_type;
				if($image_row->sl_type == ''){
				$imagerowstype='image';
				}
				  switch ( $imagerowstype ) {
					  case 'image':
						  $target = "";
						  /************Alt tag functions*********************/
						  if ( ! function_exists( 'pippin_get_image_id' ) ) {
							  function pippin_get_image_id( $image_url ) {
								  global $wpdb;
								  $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
								  if ( $attachment ) {
									  return $attachment[0];
								  }
							  }
						  }
						  if ( ! function_exists( 'wp_get_attachment' ) ) {
							  function wp_get_attachment( $attachment_id ) {
								  $attachment = get_post( $attachment_id );

								  return array(
									  'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
									  'caption'     => $attachment->post_excerpt,
									  'description' => $attachment->post_content,
									  'href'        => get_permalink( $attachment->ID ),
									  'src'         => $attachment->guid,
									  'title'       => $attachment->post_title,
								  );
							  }
						  }
						  /**************************************************/
						  ?>
					  <li class="huge_it_slideshow_image<?php if ($i != $current_image_id) {$current_key = $key; echo '_second';} ?>_item_<?php echo $sliderID; ?>" id="image_id_<?php echo $sliderID.'_'.$i ?>">      
						<?php if($image_row->sl_url!=""){ 
							if ($image_row->link_target=="on"){$target='target="_blank"';}
							echo '<a href="'.$image_row->sl_url.'" '.$target.'>';
						} ?>
                                                <?php 
                                                    $idofatt=pippin_get_image_id($image_row->image_url);
                                                    $somearray=wp_get_attachment($idofatt);
                                                ?>
						<img id="huge_it_slideshow_image_<?php echo $sliderID; ?>_<?php echo $key ;?>" class="huge_it_slideshow_image_<?php echo $sliderID; ?>" src="<?php echo $image_row->image_url; ?>"  alt="<?php if($image_row->name == "") echo $somearray['alt'];else echo $image_row->name; ?>" data-image_id="<?php echo $image_row->id; ?>" />
						<?php if($image_row->sl_url!=""){ echo '</a>'; }?>		
						<div class="huge_it_slideshow_title_text_<?php echo $sliderID; ?> <?php if(trim($image_row->name)=="") echo "none"; ?>">
							<?php echo $image_row->name; ?>
						</div>
						<div class="huge_it_slideshow_description_text_<?php echo $sliderID; ?> <?php if(trim($image_row->description)=="") echo "none"; ?>">
							<?php echo $image_row->description; ?>
						</div>
					  </li>
					  <?php
					$i++;
					break;

					  case 'last_posts':

						  $keyForStoping = 0;
						  foreach ( $recent_posts as $lkeys => $last_posts ) {
							  if ( $image_row->name == "0" ) {
								  if ( $lkeys < $image_row->sl_url ) {
									  $imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );

									  if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
										  $target = "";
										  ?>
										  <li class="huge_it_slideshow_image<?php if ( $i != $current_image_id ) {
											  $current_key = $key;
											  echo '_second';
										  } ?>_item_<?php echo $sliderID; ?>"
										      id="image_id_<?php echo $sliderID . '_' . $i ?>">
											  <?php if ( $image_row->sl_postlink == "1" ) {
												  if ( $image_row->link_target == "on" ) {
													  $target = 'target="_blank"';
												  }
												  echo '<a href="' . $last_posts["guid"] . '" ' . $target . '>';
											  } ?>
											  <img
												  id="huge_it_slideshow_image_<?php echo $sliderID; ?>_<?php echo $key; ?>"
												  class="huge_it_slideshow_image_<?php echo $sliderID; ?>"
												  src="<?php echo $imagethumb[0]; ?>"
												  alt=" <?php echo $last_posts["post_title"]; ?>"
												  data-image_id="<?php echo $image_row->id; ?>"/>
											  <?php if ( $image_row->sl_postlink == "1" ) {
												  echo '</a>';
											  } ?>
											  <div
												  class="huge_it_slideshow_title_text_<?php echo $sliderID; ?> <?php if ( trim( $last_posts["post_title"] ) == "" ) {
													  echo "none";
												  }
												  if ( $image_row->sl_stitle != "1" ) {
													  echo " hidden";
												  } ?>">
												  <?php echo $last_posts["post_title"]; ?>
											  </div>
											  <div
												  class="huge_it_slideshow_description_text_<?php echo $sliderID; ?> <?php if ( trim( $last_posts["post_content"] ) == "" ) {
													  echo "none";
												  }
												  if ( $image_row->sl_sdesc != "1" ) {
													  echo " hidden";
												  } ?>">
												  <?php
												  echo substr_replace( $last_posts["post_content"], "", $image_row->description ); ?>
											  </div>
										  </li>
										  <?php
										  $i ++;
									  }
								  }
							  } else {
								  $category_id            = get_cat_ID( $image_row->name );                            //       USER CHOOSED CATEGORY
								  $category_id_from_posts = wp_get_post_categories( $last_posts['ID'] );    //       GETTING ALL CATEGORIES FOR THIS POST
								  if ( $keyForStoping < $image_row->sl_url ) {
									  if ( in_array( $category_id, $category_id_from_posts ) ) {
										  $imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );
										  if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
											  $keyForStoping ++;
											  $target = "";
											  ?>
											  <li class="huge_it_slideshow_image<?php if ( $i != $current_image_id ) {
												  $current_key = $key;
												  echo '_second';
											  } ?>_item_<?php echo $sliderID; ?>"
											      id="image_id_<?php echo $sliderID . '_' . $i ?>">
												  <?php if ( $image_row->sl_postlink == "1" ) {
													  if ( $image_row->link_target == "on" ) {
														  $target = 'target="_blank"';
													  }
													  echo '<a href="' . $last_posts["guid"] . '" ' . $target . '>';
												  } ?>
												  <img id="huge_it_slideshow_image_<?php echo $sliderID; ?>"
												       class="huge_it_slideshow_image_<?php echo $sliderID; ?>"
												       src="<?php echo $imagethumb[0]; ?>"
												       alt="<?php echo $last_posts["post_title"]; ?>"
												       data-image_id="<?php echo $image_row->id; ?>"/>
												  <?php if ( $image_row->sl_postlink == "1" ) {
													  echo '</a>';
												  } ?>
												  <div
													  class="huge_it_slideshow_title_text_<?php echo $sliderID; ?> <?php if ( trim( $last_posts["post_title"] ) == "" ) {
														  echo "none";
													  }
													  if ( $image_row->sl_stitle != "1" ) {
														  echo " hidden";
													  } ?>">
													  <?php echo $last_posts["post_title"]; ?>
												  </div>
												  <div
													  class="huge_it_slideshow_description_text_<?php echo $sliderID; ?> <?php if ( trim( $last_posts["post_content"] ) == "" ) {
														  echo "none";
													  }
													  if ( $image_row->sl_sdesc != "1" ) {
														  echo " hidden";
													  } ?>">
													  <?php
													  echo substr_replace( $last_posts["post_content"], "", $image_row->description ); ?>
												  </div>
											  </li>
											  <?php
											  $i ++;
										  }
									  }
								  }
							  }
						  }
						  break;
					case 'video':

						?>
						<li class="huge_it_slideshow_image<?php if ($i != $current_image_id) {$current_key = $key; echo '_second';} ?>_item_<?php echo $sliderID; ?>" id="image_id_<?php echo $sliderID.'_'.$i ?>">
							<?php 
								if(strpos($image_row->image_url,'youtube') !== false || strpos($image_row->image_url,'youtu') !== false){
									$video_thumb_url=hugeit_slider_get_youtube_id_from_url($image_row->image_url);
								?>
									
								<div id="video_id_<?php echo $sliderID;?>_<?php echo $key ;?>"  data-element-id="<?php echo $image_row->id;?>" class="huge_it_video_frame_<?php echo $sliderID; ?> huge-it-iframe <?php if ($i == $current_image_id) {$current_key = $key; echo ($slidervideoautoplay=='off')?'':'autoplay';} ?>"></div>
							<?php }else {
								$vimeo = $image_row->image_url;
								$imgid = explode( "/", $vimeo );
								$imgid =  end($imgid);
							?>
								<iframe id="player_<?php echo $sliderID ;?>_<?php echo $key ;?>" data-element-id="<?php echo $image_row->id;?>"  class="huge_it_video_frame_<?php echo $sliderID; ?>  huge-it-iframe-vimeo <?php if ($i == $current_image_id) {$current_key = $key; echo ($slidervideoautoplay=='off')?'':'autoplay';} ?>" src="//player.vimeo.com/video/<?php echo $imgid; ?>?api=1&player_id=player_<?php echo $key ;?>&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>
							<?php } ?>
						</li>
					<?php
					$i++;
					break;
				} 
			 } 
			  ?>
            </ul>
          </div>
		  <input  type="hidden" id="huge_it_current_image_key_<?php echo $sliderID; ?>" value="0" />
        </div>
      </div>
	</div>
<!-- slider thumbs  -->

<script>
	jQuery(document).ready(function(jQuery) {
		setInterval(function() {
			jQuery('.huge_it_slider_<?php echo $sliderID; ?>').find("li").each(function() {

				if (jQuery(this).css("opacity") == "1") {

					var img_id = jQuery(this).attr('id');
					jQuery('.huge_it_slideshow_thumbs_<?php echo $sliderID; ?>').each(function() {
						var allListElements = jQuery('li[id=' + img_id + ']');

						jQuery(this).find(allListElements).not(".bx-clone").each(function() {
							jQuery('.huge_it_slideshow_thumbs_<?php echo $sliderID; ?> li').find(".trans_back").css('background', 'rgba(255,255,255,0.3)');
							jQuery(this).find('.trans_back').css('background', 'none');
						})
					})
				}
			})
		}, 100)
	})
</script>

	

<?php if($sliderthumbslider=='thumbnails'){?>
	<div class="huge_it_slideshow_thumbs_container_<?php echo $sliderID; ?>">
		<ul id="huge_it_thumb_slider" class="huge_it_slideshow_thumbs_<?php echo $sliderID; ?>">
			<?php
			$i                = 0;
			$current_image_id = 0;
			$current_pos      = 0;
			$current_key      = 0;
			$stri             = 0;
			foreach ( $images as $key => $image_row ) {
				$imagerowstype = $image_row->sl_type;

				if ( $image_row->sl_type == '' ) {
					$imagerowstype = 'image';
				}
				switch ( $imagerowstype ) {

					case 'image':

						if ( $image_row->id == $current_image_id ) {
							$current_pos = $stri;
							$current_key = $stri;
						}
						?>
						<li id="image_id_<?php echo $sliderID . '_' . $i ?>"
						    class="huge_it_slideshow_thumbnails_<?php echo $sliderID; ?>"
						    onclick="if(jQuery(this).hasClass('huge_it_slideshow_thumbnails_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
						    data-image_id="<?php echo $image_row->id; ?>" data-image_key="<?php echo $stri; ?>">
							<img class="sl_thumb_img" src="<?php echo $image_row->image_url; ?>" />
							<div class="trans_back"></div>
							<input type="hidden" id="time" name="time" value="<?php echo $slidepausetime; ?>">

						</li>
						<?php
						$stri ++;
						$i ++;
						break;
					case 'video':
						$url = $image_row->image_url;
						if ( ! function_exists( 'hugeit_slider_get_youtube_id_from_url_slider_thumb' ) ) {
							function hugeit_slider_get_youtube_id_from_url_slider_thumb( $url ) {
								if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
									return $match[1];
								}
							}
						}
						if ( strpos( $image_row->image_url, 'youtube' ) !== false || strpos( $image_row->image_url, 'youtu' ) !== false ) {
							$video_thumb_url = hugeit_slider_get_youtube_id_from_url_slider_thumb( $image_row->image_url );
							$thumburl        = '<img class="sl_thumb_img" src="http://img.youtube.com/vi/' . $video_thumb_url . '/mqdefault.jpg" alt="" />';
							$liclass         = "youtube";
						} else if ( strpos( $image_row->image_url, 'vimeo' ) !== false ) {
							$liclass  = "vimeo";
							$vimeo    = $image_row->image_url;
							$imgid    = explode( "/", $vimeo );
							$imgid    = end( $imgid );
							$hash     = unserialize( file_get_contents( "http://vimeo.com/api/v2/video/" . $imgid . ".php" ) );
							$imgsrc   = $hash[0]['thumbnail_large'];
							$thumburl = '<img src="' . $imgsrc . '" alt="" />';
						}

						if ( $image_row->id == $current_image_id ) {
							$current_pos = $stri;
							$current_key = $stri;
						}
						?>
						<li id="image_id_<?php echo $sliderID . '_' . $i ?>"
						    class="huge_it_slideshow_thumbnails_<?php echo $sliderID; ?>"
						    onclick="if(jQuery(this).hasClass('huge_it_slideshow_thumbnails_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
						    data-image_id="<?php echo $image_row->id; ?>" data-image_key="<?php echo $stri; ?>">
							<?php echo $thumburl; ?>
							<div class="play-icon <?= $liclass; ?>"></div>
							<div class="trans_back"></div>
							<input type="hidden" id="time" name="time" value="<?php echo $slidepausetime; ?>" slide="<?php echo $sliderID; ?>">
						</li>
						<?php
						$stri ++;
						$i++;
						break;

					case 'last_posts':
						$keyForStoping = 0;
						foreach ( $recent_posts as $lkeys => $last_posts ) {
							if ( $image_row->name == "0" ) {
								if ( $lkeys < $image_row->sl_url ) {
									$imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );

									if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
										$target = "";
										?>
										<li id="image_id_<?php echo $sliderID . '_' . $i ?>"
										    class="huge_it_slideshow_thumbnails_<?php echo $sliderID; ?>"
										    onclick="if(jQuery(this).hasClass('huge_it_slideshow_thumbnails_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
										    data-image_id="<?php echo $image_row->id; ?>"
										    data-image_key="<?php echo $stri; ?>">

											<img class="sl_thumb_img" src="<?php echo $imagethumb[0]; ?>"/>
											<div class="trans_back"></div>
											<input type="hidden" id="time" name="time"
											       value="<?php echo $slidepausetime; ?>">

										</li>
										<?php
										$i ++;
									}
								}
							} else {
								$category_id = get_cat_ID( $image_row->name );                            //       USER CHOOSED CATEGORY
								$category_id_from_posts = wp_get_post_categories( $last_posts['ID'] );    //       GETTING ALL CATEGORIES FOR THIS POST
								if ( $keyForStoping < $image_row->sl_url ) {
									if ( in_array( $category_id, $category_id_from_posts ) ) {
										$imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $last_posts["ID"] ), 'thumbnail-size', true );
										if ( get_the_post_thumbnail( $last_posts["ID"], 'thumbnail' ) != '' ) {
											$keyForStoping ++;
											$target = "";
											?>
											<li id="image_id_<?php echo $sliderID . '_' . $i ?>"
											    class="huge_it_slideshow_thumbnails_<?php echo $sliderID; ?>"
											    onclick="if(jQuery(this).hasClass('huge_it_slideshow_thumbnails_active_<?php echo $sliderID; ?>')) { return false; } huge_it_change_image_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_<?php echo $sliderID; ?>,false,true);return false;"
											    data-image_id="<?php echo $image_row->id; ?>"
											    data-image_key="<?php echo $stri; ?>">

												<img id="huge_it_slideshow_image_<?php echo $sliderID; ?>"
												     class="huge_it_slideshow_image_<?php echo $sliderID; ?>"
												     src="<?php echo $imagethumb[0]; ?>"/>
												<div class="trans_back"></div>
												<input type="hidden" id="time" name="time"
												       value="<?php echo $slidepausetime; ?>">
											</li>
											<?php
											$i++;
										}
									}
								}
							}
						}
						break;
				}
			}
			?>
		</ul>
	</div>
<?php } ?>
		</div>
	<?php
	return ob_get_clean();
}  
