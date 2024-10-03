(function($) { 
  	'use strict';

	$(".wrps_related_products").each(function() {
	    var t 			= $(this),
	    rtl 			= t.data("direction") ? !0 : !1,
	    items 			= t.data("items") ? parseInt(t.data("items")) : '',
	    desktopsmall 	= t.data("desktopsmall") ? parseInt(t.data("desktopsmall")) : '',
	    tablet 			= t.data("tablet") ? parseInt(t.data("tablet")) : '',
	    mobile 			= t.data("mobile") ? parseInt(t.data("mobile")) : '',
	    navTextLeft 	= t.data("direction") ? 'right' : 'left',
	    navTextRight 	= t.data("direction") ? 'left' : 'right';
	        
	    $(this).owlCarousel({
	        autoplay: true,
	        rtl: rtl,
	        items: items,
	        responsiveClass: true,
	        responsive:{
		    	0:{
		            items: mobile,
		        },
		        480:{
		            items: mobile,
		        },
		        768:{
		            items: tablet,
		        },
		        1170:{
		            1024: desktopsmall,
		        },
		        1200:{
		            items: items,
		        }
		    },
	        nav: true,
	        navText : ['<i class="wpb-icon-angle-'+navTextLeft+'" aria-hidden="true"></i>','<i class="wpb-icon-angle-'+navTextRight+'" aria-hidden="true"></i>'],
	        dots: true,
	        loop: false,
	        margin: 10,
	        autoHeight: true,
	    });
	});

} )(jQuery);  