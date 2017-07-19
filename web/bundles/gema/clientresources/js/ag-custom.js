jQuery(document).ready(function( $ ) {
	
//Site Switcher
  $('#top-site-switcher .current-site').on('click', function() {
	  $(this).toggleClass("open");
	  $('body').toggleClass("site-switcher-open");
    $('#top-site-switcher .sites-list').fadeToggle( "slow", "linear" );
  });
   
  var currentDomain = window.location.hostname;

  	/* USE WHEN ALL SITES ARE MULTISITE - does not work if URLs are external
 $( ".site-switcher li.menu-item a" ).each(function() {
	  

	var itemUrl = $(this).attr('href');
	var itemDomain = itemUrl.replace(/^http\:\/\//, '').replace(/\/+$/, '');  
	   
	if(itemDomain == currentDomain ){
		 $(this).parent('li').addClass('current-domain');
	}
  });*/
   
//Header Detect Phone/Email, make link

 $( ".top-info-line-1 span" ).each(function() {
	var string = $(this).text();
	console.log(string);
	icon = $(this).find('i.fa');
	
	//Email
	if( icon.hasClass('fa-inbox') || icon.hasClass('fa-envelope') || icon.hasClass('fa-envelope-o') ){
		$(this).wrap('<a href="mailto:'+string+'"></a>').addClass('email');
	}
	//Phone
	if( icon.hasClass('fa-mobile') || icon.hasClass('fa-phone') || icon.hasClass('fa-phone-square') ){
		$(this).wrap('<a href="tel:'+string+'"></a>').addClass('phone');
	}
	//Address
	if( icon.hasClass('fa-map-marker') || icon.hasClass('fa-location-arrow')){
		$(this).wrap('<a href="http://maps.google.com/?q='+string+'" target="_blank"></a>').addClass('address');
	}
  });




//Mobile Bull Search
	function findBSLinks() {
		
		var bullSearchLink = $('a[href*="bs."]');
		var bullSearchFormBtn = $('.bullsearch-form-btn');
		
			if($(window).width() <= 700) {
				bullSearchLink.addClass('bs-mobile-msg');
				bullSearchFormBtn.addClass('bs-mobile-msg');
			} else {
				bullSearchLink.removeClass('bs-mobile-msg');
				bullSearchFormBtn.removeClass('bs-mobile-msg')
			}
	}
	
	findBSLinks();

    $(window).resize(function() {
		findBSLinks();
    }).resize(); 
	
	var mobileMsgEl = $('#mobile-bs-el');
	
	$(document).on('click','a.bs-mobile-msg' ,function(e){
		var theLink = $(this).attr('href');
		e.preventDefault();
		mobileMsgEl.addClass('showme');
	});
	
	$(document).on('click','#mobile-bs-el #close-msg' ,function(){
		mobileMsgEl.removeClass('showme');
	});
	

// Remove Footer Social Icons Tooltips
	$('.footer-social-icons a .tooltip').unbind('mouseenter mouseleave hover');
	
	
// Fade Me
	 /* On load ... */
	fadeInFadeMe();
	/* ... and every time the window is scrolled. */
    jQuery(window).scroll( function(){
		fadeInFadeMe();
	 });
    

     function fadeInFadeMe() {

        jQuery('.fademe').each( function(i){
            
            var bottom_of_object = jQuery(this).offset().top + jQuery(this).outerHeight();
            var bottom_of_window = jQuery(window).scrollTop() + jQuery(window).height();
            
            if( bottom_of_window > bottom_of_object ){
                jQuery(this).animate({'opacity':'1'},1200);
            }
            
        }); 
    
	  }
	  
//Bull Search SC Form 
	$(document).on('click','.bullsearch-form-btn' ,function(e){
		e.preventDefault();
	
		var searchString = $(this).closest('.bull-search-container').find("#bullsearch_string").val();
		var localeString = $(this).closest('.bull-search-container').find("#ag-bullsearch-form").attr("search-redirect");
		var redirectURL = "http://bs.altagenetics.com/"+localeString+"/BS/_homeSearch?searchCriteria="+searchString;
		window.location.href = redirectURL;
	});
	//Search on Enter 
		$('#bullsearch_string').keypress(function(event){
		  if(event.keyCode == 13){
			$(this).siblings('#bullsearch-btn').click();
		  }
		});

});