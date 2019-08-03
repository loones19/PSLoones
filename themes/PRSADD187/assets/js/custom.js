 
	
$(document).ready(function(){	  
	
	// tm_top link

		$('#links_block_top .title_block').click(function() {
		    $('#links_block_top .block_content').slideToggle("slow");
		    $('#links_block_top .title_block').toggleClass('active');
		  });
	

		// tm_vertical desk

	$('.tmvm-contener .block-title').click(function() {
	    $('#_desktop_top_menu #top-menu').slideToggle("slow");
	    $('.tmvm-contener .block-title').toggleClass('active');
  	});

	//breadcumb//
	$('h1.h1').prependTo('.breadcrumb .container');
	//breadcumb//


});

function additionalCarousel(sliderId){
	/*======  curosol For Additional ==== */
	 var tmadditional = $(sliderId);
      tmadditional.owlCarousel({
     	 items : 3, //10 items above 1000px browser width
     	 itemsDesktop : [1199,2], 
     	 itemsDesktopSmall : [991,2], 
     	 itemsTablet: [767,1], 
     	 itemsMobile : [320,1] 
      });
      // Custom Navigation Events
      $(".additional_next").click(function(){
        tmadditional.trigger('owl.next');
      })
      $(".additional_prev").click(function(){
        tmadditional.trigger('owl.prev');
      });
}

$(document).ready(function(){
	
								
	bindGrid();
	additionalCarousel("#main #additional-carousel");
	

	$('.cart_block .block_content').on('click', function (event) {
		event.stopPropagation();
	});
	
	
	
	
	// ---------------- start more menu setting ----------------------
	if ($(document).width() >= 992 && $(document).width() <= 1199){
		var max_elem = 8;	
	}


	else if($(document).width() >= 1200 && $(document).width() <= 1450 ){
		var max_elem = 9;	
	}
	else{
		var max_elem = 13;	
	}
	  
		var itemsleft = $('.header-top .menu ul#top-menu > li,#left-column .menu ul#top-menu > li');	
		
		if ( itemsleft.length > max_elem ) {
		
			$('.header-top .menu ul#top-menu, #left-column .menu ul#top-menu').append('<li><div class="more-wrap"><span class="more-view">More Categories<i class="material-icons">&#xE313;</i></span></div></li>');
		}

		$('.header-top .menu ul#top-menu .more-wrap,#left-column .menu ul#top-menu .more-wrap ').click(function() {
			if ($(this).hasClass('active')) {
				itemsleft.each(function(i) {
					if ( i >= max_elem ) {
						$(this).slideUp(200);
					}
				});
				$(this).removeClass('active');
				$('.more-wrap').html('<span class="more-view">More Categories<i class="material-icons">&#xE313;</i></span>');
			} else {
				itemsleft.each(function(i) {
					if ( i >= max_elem  ) {
						$(this).slideDown(200);
					}
				});
				$(this).addClass('active');
				$('.more-wrap').html('<span class="more-view">Less Categories<i class="material-icons">&#xE316;</i></span>');
			}
		});

		itemsleft.each(function(i) {
			if ( i >= max_elem ) { 
				$(this).css('display', 'none');
			}
		});


	

});


// Add/Remove acttive class on menu active in responsive  
	$('#menu-icon').on('click', function() {
		$(this).toggleClass('active');
	});

// Loading image before flex slider load
	$(window).load(function() { 
		$(".loadingdiv").removeClass("spinner"); 
	});

// Flex slider load
	$(window).load(function() {
		if($('.flexslider').length > 0){ 
			$('.flexslider').flexslider({		
				slideshowSpeed: $('.flexslider').data('interval'),
				pauseOnHover: $('.flexslider').data('pause'),
				animation: "fade"
			});
		}
	});		

// Scroll page bottom to top
	$(window).scroll(function() {
		if ($(this).scrollTop() > 500) {
			$('.top_button').fadeIn(500);
		} else {
			$('.top_button').fadeOut(500);
		}
	});							
	$('.top_button').click(function(event) {
		event.preventDefault();		
		$('html, body').animate({scrollTop: 0}, 800);
	});



/*======  Carousel Slider For Feature Product ==== */
	var tmfeature = $("#feature-carousel");
	
	tmfeature.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2]
	}); 



	// Custom Navigation Events
	$(".feature_next").click(function(){
		tmfeature.trigger('owl.next');
	})
	$(".feature_prev").click(function(){
		tmfeature.trigger('owl.prev');
	});



/*======  Carousel Slider For New  Product ==== */
	var tmnewproduct = $("#newproduct-carousel");

	tmnewproduct.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".newproduct_next").click(function(){
		tmnewproduct.trigger('owl.next');
	})
	$(".newproduct_prev").click(function(){
		tmnewproduct.trigger('owl.prev');
	});



/*======  Carousel Slider For Bestseller Product ==== */

	var tmbestseller = $("#bestseller-carousel");

	tmbestseller.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".bestseller_next").click(function(){
		tmbestseller.trigger('owl.next');
	})
	$(".bestseller_prev").click(function(){
		tmbestseller.trigger('owl.prev');
	});


/*======  Carousel Slider For Special Product ==== */
		var tmspecial = $("#special-carousel");
		tmspecial.owlCarousel({
		items : 1, //10 items above 1000px browser width
		itemsDesktop : [1199,1], 
		itemsDesktopSmall : [991,1], 
		itemsTablet: [767,1], 
		itemsMobile : [480,1],
		afterAction: function(el){
	   		this
	   		.$owlItems
	   		.removeClass('active')

	   		this
		   .$owlItems 
		   .eq(this.currentItem)
		   .addClass('active')
		   var y =  this.currentItem;
	    }  
	});
	// Custom Navigation Events
	$(".special_next").click(function(){
		tmspecial.trigger('owl.next');
	})
	$(".special_prev").click(function(){
		tmspecial.trigger('owl.prev');
	});

/*======  Carousel Slider For Accessories Product ==== */

	var tmaccessories = $("#accessories-carousel");
	tmaccessories.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".accessories_next").click(function(){
		tmaccessories.trigger('owl.next');
	})
	$(".accessories_prev").click(function(){
		tmaccessories.trigger('owl.prev');
	});


/*======  Carousel Slider For Category Product ==== */

	var tmproductscategory = $("#productscategory-carousel");
	tmproductscategory.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".productscategory_next").click(function(){
		tmproductscategory.trigger('owl.next');
	})
	$(".productscategory_prev").click(function(){
		tmproductscategory.trigger('owl.prev');
	});


/*======  Carousel Slider For Viewed Product ==== */

	var tmviewed = $("#viewed-carousel");
	tmviewed.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".viewed_next").click(function(){
		tmviewed.trigger('owl.next');
	})
	$(".viewed_prev").click(function(){
		tmviewed.trigger('owl.prev');
	});

/*======  Carousel Slider For Crosssell Product ==== */

	var tmcrosssell = $("#crosssell-carousel");
	tmcrosssell.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [767,2], 
		itemsMobile : [480,2] 
	});
	// Custom Navigation Events
	$(".crosssell_next").click(function(){
		tmcrosssell.trigger('owl.next');
	})
	$(".crosssell_prev").click(function(){
		tmcrosssell.trigger('owl.prev');
	});


/*======  Carousel Slider For blog  ==== */
	
	var tmblog = $("#blog-carousel");
	tmblog.owlCarousel({
		items : 2, //10 items above 1000px browser width
		itemsDesktop : [1199,2], 
		itemsDesktopSmall : [991,1], 
		itemsTablet: [767,1], 
		itemsMobile : [479,1] 
	});

	$(".blog_next").click(function(){
		tmblog.trigger('owl.next');
	})
	$(".blog_prev").click(function(){
		tmblog.trigger('owl.prev');
	});
	

/*======  Carousel Slider For categorylist ==== */

		var tmcat = $("#tmcategorylist-carousel");
		tmcat.owlCarousel({
			items : 4, //10 items above 1000px browser width
			autoPlay: true,
			itemsDesktop : [1199,3], 
			itemsDesktopSmall : [991,3], 
			itemsTablet: [767,2], 
			itemsMobile : [479,1]


		});
		// Custom Navigation Events
		$(".cat_next").click(function(){
		tmcat.trigger('owl.next');
		})
		$(".cat_prev").click(function(){
		tmcat.trigger('owl.prev');
		});

/*======  Carousel Slider For For Tesimonial ==== */

	var tmtestimonial = $("#testimonial-carousel");
	tmtestimonial.owlCarousel({
     	 items : 1, //10 items above 1000px browser width
     	 itemsDesktop : [1199,1], 
     	 itemsDesktopSmall : [991,1],
     	 itemsTablet: [767,1], 
     	 itemsMobile : [480,1] 
      });
      // Custom Navigation Events
      $(".tmtestimonial_next").click(function(){
        tmtestimonial.trigger('owl.next');
      })
      $(".tmtestimonial_prev").click(function(){
        tmtestimonial.trigger('owl.prev');
      });



function bindGrid()
{
	var view = $.totalStorage("display");

	if (view && view != 'grid')
		display(view);
	else
		$('.display').find('li#grid').addClass('selected');

	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '#list', function(e){
		e.preventDefault();
		display('list');		
	});	
}

function display(view)
{
	if (view == 'list')
	{
		$('#products ul.product_list').removeClass('grid').addClass('list');
		$('#products .product_list > li').removeClass('col-xs-12 col-sm-6 col-md-6 col-lg-3').addClass('col-xs-12');
		
		
		$('#products .product_list > li').each(function(index, element) {
			var html = '';
			html = '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product"><div class="row">';
				html += '<div class="thumbnail-container col-xs-4 col-xs-5 col-md-3">' + $(element).find('.thumbnail-container').html() + '</div>';
				
				html += '<div class="product-description center-block col-xs-4 col-xs-7 col-md-9">';
					html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() + '</h3>';
					html += '<div class="comments_note">'+ $(element).find('.comments_note').html() +'</div>';
					var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
					if (price != null) {
						html += '<div class="product-price-and-shipping">'+ price + '</div>';
					}
					
					html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
					
					var colorList = $(element).find('.highlighted-informations').html();
					if (colorList != null) {
						html += '<div class="highlighted-informations">'+ colorList +'</div>';
					}
					
					html += '<div class="product-actions-main">'+ $(element).find('.product-actions-main').html() +'</div>';
					html += '<div class="quick-view">'+ $(element).find('.quick-view').html() +'</div>';
					
				html += '</div>';
			html += '</div></div>';
		$(element).html(html);
		});
		$('.display').find('li#list').addClass('selected');
		$('.display').find('li#grid').removeAttr('class');
		$.totalStorage('display', 'list');
	}
	else
	{
		$('#products ul.product_list').removeClass('list').addClass('grid');
		$('#products .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-6 col-md-6 col-lg-3');
		$('#products .product_list > li').each(function(index, element) {
		var html = '';
		html += '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product">';
			html += '<div class="thumbnail-container">' + $(element).find('.thumbnail-container').html() +'</div>';
			
			
			html += '<div class="product-description">';
				html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() +'</h3>';
			
				var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
				if (price != null) {
					html += '<div class="product-price-and-shipping">'+ price + '</div>';
				}
				
				html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
				
				
				
				var colorList = $(element).find('.highlighted-informations').html();
				if (colorList != null) {
					html += '<div class="highlighted-informations">'+ colorList +'</div>';
				}
				
			html += '</div>';
		html += '</div>';
		$(element).html(html);
		});
		$('.display').find('li#grid').addClass('selected');
		$('.display').find('li#list').removeAttr('class');
		$.totalStorage('display', 'grid');
	}
}


function responsivecolumn(){
	
	if ($(document).width() <= 991){
				
		// ---------------- Fixed header responsive ----------------------
		$(window).bind('scroll', function () {
			if ($(window).scrollTop() > 0) {
				$('.header-nav').addClass('fixed');
			} else {
				$('.header-nav').removeClass('fixed');
			}
		});
	}
	
	
	if ($(document).width() <= 991)
	{
		$('.container #columns_inner #left-column').appendTo('.container #columns_inner');
	}
	if ($(document).width() >= 992){

				$('.container #columns_inner #left-column').prependTo('.container #columns_inner');

		
		
	}
}
$(document).ready(function(){responsivecolumn();});
$(window).resize(function(){responsivecolumn();});


function searchtoggle() {
 		
	if($(window).width() <= 991 ){
		$('#search_widget').detach().insertAfter('.header-nav #_mobile_user_info');
		$('.header-top #links_block_top').detach().insertAfter('.header-nav #search_widget');
		$('.search_button').click(function(event){
			$(this).toggleClass('active');
			$('#search_widget').toggleClass('active');
			event.stopImmediatePropagation();
			$(".searchtoggle").slideToggle("fast");
			$('.search-widget form input[type="text"]').focus();
		});
		
		$(".searchtoggle").on("click", function (event) {
			event.stopImmediatePropagation();
		});
	}else{
		$('.search_button,.searchtoggle').unbind();
		$('#search_widget').unbind();
		$(".searchtoggle").show();
		$('#search_widget').detach().insertAfter('.header-top #tmcmssliderbottomblock');
		$('.header-nav #links_block_top').detach().insertBefore('.header-top #_desktop_cart');
	}

}

jQuery(document).ready(function() {searchtoggle();});
$(window).resize(function(){searchtoggle();});

// JS for calling loadMore
$(document).ready(function () {

	"use strict";							 
  	var size_li_feat = $("#index #featureProduct .featured_grid li.product_item").size();
	var size_li_new = $("#index #newProduct .newproduct_grid li.product_item").size();
	var size_li_best = $("#index #bestseller .bestseller_grid li.product_item").size();
	var size_li_special = $("#index .special-products #special-grid li.product_item").size();

	var x= 128;
	var y= 128;
	var z= 128;
	var s= 128;
		
	$('#index #featureProduct .featured_grid li.product_item:lt('+x+')').fadeIn('slow');
	$('#index #newProduct .newproduct_grid li.product_item:lt('+y+')').fadeIn('slow');
	$('#index #bestseller .bestseller_grid li.product_item:lt('+z+')').fadeIn('slow');
	$('#index .special-products #special-grid li.product_item:lt('+s+')').fadeIn('slow');

    $('.featured_grid .gridcount').click(function () {
	if(x==size_li_feat){									 			
			 $('.featured_grid .gridcount').hide();
			 $('.featured_grid .tm-message').show();
	}else{
		x= (x+4 <= size_li_feat) ? x+4 : size_li_feat;	
        $('#index #featureProduct .featured_grid li.product_item:lt('+x+')').fadeIn(1000);			
	}
    });		
	
	$('.newproduct_grid .gridcount').click(function () {
	if(y==size_li_new){									 
			$('.newproduct_grid .gridcount').hide();
			$('.newproduct_grid .tm-message').show();
	}else{
		y= (y+4 <= size_li_new) ? y+4 : size_li_new;
        $('#index #newProduct .newproduct_grid li.product_item:lt('+y+')').fadeIn('slow');
	}
    });	   
	
	$('.bestseller_grid .gridcount').click(function () {
	if(z==size_li_best){									 
			$('.bestseller_grid .gridcount').hide();
			$('.bestseller_grid .tm-message').show();
	}else{
		z= (z+4 <= size_li_best) ? z+4 : size_li_best;
        $('#index #bestseller .bestseller_grid li.product_item:lt('+z+')').fadeIn('slow');
	}
    });
			
	$('#special-grid .gridcount').click(function () {
	if(s==size_li_special){
	
			$('#special-grid .gridcount').hide();
			$('#special-grid .tm-message').show();
	}else{
		s= (s+4 <= size_li_special) ? s+4 : size_li_special;
        $('#index .special-products #special-grid li.product_item:lt('+s+')').fadeIn('slow');
	}
    });
		
		
});
		
//sign in toggle
$(document).ready(function(){
	
	 $('.tm_userinfotitle').click(function(event){
		  $(this).toggleClass('active');
		  event.stopPropagation();
		  $(".user-info").slideToggle("fast");
		});
		$(".user-info").on("click", function (event) {
		  event.stopPropagation();
		});
		$('#product #productCommentsBlock').appendTo('#product #tab-content #rating');
});




function headertoggle() {	
	//LOONES
//	$('#currencies-block-top').css('display','block');
//	$('#header_links').css('display','block');
//	$('.language-selector-wrapper').css('display','block');
//	$('.language-selector-wrapper').appendTo('.user-info');
//	$('.currency-selector').appendTo('.user-info');
//	$('.currency-selector').css('display','block');
}
$(document).ready(function() {headertoggle();});
$(window).resize(function() {headertoggle();});

function cmsbanner() {
"use strict";

// Set the date we're counting down to
var countDownDate = new Date("july 18, 2020 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById('demoday').innerHTML = days + "</br><span>day</span>" ;
    document.getElementById("demohour").innerHTML = hours + "</br><span>hour<span>";
    document.getElementById("demominute").innerHTML = minutes + "</br><span>min</span>";
    document.getElementById("demosec").innerHTML = seconds + "</br><span>sec</span>";
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
       document.getElementById("demoday").innerHTML = "EXPIRED";
        document.getElementById("demohour").innerHTML = "EXPIRED";
         document.getElementById("demominute").innerHTML = "EXPIRED";
          document.getElementById("demosec").innerHTML = "EXPIRED";
    }
}, 1000);

}

//Tm AboutUs

$('#tmaboutcmsblock .block_title').click(function() {
    $('#tmaboutcmsblock #cmsappblock').slideToggle("slow");
    $('#tmaboutcmsblock .block_title').toggleClass('active');
  	});
