jQuery(document).ready(function () {
	jQuery('#arrows-type input[name="params[slider_navigation_type]"]').change(function(){
		jQuery(this).parents('ul').find('li.active').removeClass('active');
		jQuery(this).parents('li').addClass('active');
	});
	jQuery('#slider-loading-icon li').click(function(){ //alert(jQuery(this).find("input:checked").val());
		jQuery(this).parents('ul').find('li.act').removeClass('act');
		jQuery(this).addClass('act');
	});	
	jQuery('.slider-options .save-slider-options').click(function(){
		alert("General Settings are disabled in free version. If you need those functionalityes, you need to buy the commercial version.");
		return false;
	});	
		
	jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
		 jQuery(this).parent().find('span').html(parseInt(data.value)+"%");
		 jQuery(this).val(parseInt(data.value));
	});
		
	jQuery('.help').hover(function(){
           jQuery(this).parent().find('.help-block').removeClass('active');
           var width=jQuery(this).parent().find('.help-block').outerWidth();
            jQuery(this).parent().find('.help-block').addClass('active').css({'left':-((width /2)-10)});
        },function() {
			jQuery(this).parent().find('.help-block').removeClass('active');
	});

	jQuery(".close_free_banner").on("click",function(){
		jQuery(".free_version_banner").css("display","none");
		hgSliderSetCookie( 'hgSliderFreeBannerShow', 'no', {expires:3600} );
	});

	jQuery('.hugeit_slider_delete_slide').on('click', function() {
		var c = confirm('Are you sure you want to delete this slider ?');

		if (!c) {
			return false;
		}
	});
});

  jQuery(function() {
    jQuery( "#images-list" ).sortable({
      stop: function() {
			jQuery("#images-list li").removeClass('has-background');
			count=jQuery("#images-list li").length;
			for(var i=0;i<=count;i+=2){
					jQuery("#images-list li").eq(i).addClass("has-background");
			}
			jQuery("#images-list li").each(function(){
				jQuery(this).find('.order_by').val(jQuery(this).index());
			});
      },
      revert: true
    });
   // jQuery( "ul, li" ).disableSelection();
  });

/* Cookies */
function hgSliderGetCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function hgSliderSetCookie(name, value, options) {
	options = options || {};

	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}


	if(typeof value == "object"){
		value = JSON.stringify(value);
	}
	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;

	for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}

function hgSliderDeleteCookie(name) {
	setCookie(name, "", {
		expires: -1
	})
}