var ArchiveGlobal;
(function( $ ) {
	'use strict';
      ArchiveGlobal = (function() {
        
		return {  
		  
		     filter_ajax: function() {
  
                	$(document).on('click' , '.jws-post-letter-filter a , .jws-post-category-filter a , .jws-post-years-filter a , .page-numbers a' , function(e) {
                        
                        e.preventDefault();
                        var url = $(this).attr('href');
                      
                        $(document.body).trigger('archive_videos_filter_ajax', [url, $(this)]);
                        
                	});
                    
                    
                    
                    
                    $(document).on('submit', '.post-select-filter', function(e) {
                        e.preventDefault();
                        var url = $(this).attr('action') + '?' + $(this).serialize();
                    
                        $(document.body).trigger('archive_videos_filter_ajax', [url, $(this)]);
    
                    });
                    
                    
                    
                    
                    
                    $(document.body).on('archive_videos_filter_ajax', function(e, url, element) { 
                        
                        $('html,body').animate({
                            scrollTop: $(".content-area").offset().top - 120
                        }, 600);
                        
                        $('.jws-filter-modal').removeClass('open');
                        
                        var intervalID;
                        $('body').addClass('jws-animated-post');
                        $('.content-area').addClass('loading');
                        $('.post_content').append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');    
                                
                        if ('?' == url.slice(-1)) {
                            url = url.slice(0, -1);
                        }
            
                        url = url.replace(/%2C/g, ',');
            
                        window.history.pushState(null, "", url);
                        $(window).bind("popstate", function() {
                            window.location = location.href
                        });
                        
                        clearInterval(intervalID);
                        
                        $.get(url, function(res) {
                        
                       // $('.post_sidebar').replaceWith($(res).find('.post_sidebar'));
                       // $('.jws-title-bar-wrap').replaceWith($(res).find('.jws-title-bar-wrap'));
                       // $('.post_content').replaceWith($(res).find('.post_content'));
                        $('.site-content').replaceWith($(res).find('.site-content'));
                      
                        jwsThemeModule.movies_offset();
                        
                        $('select').select2({
        					dropdownAutoWidth: true,
                            minimumResultsForSearch: 10
        				});
                        
                        var iter = 0;
                        intervalID = setInterval(function() {
                                $('.post_content').find('.jws-post-item').eq(iter).addClass('jws-animated');
                                iter++;
                        }, 100);
                        
                    }, 'html');
                    });
                 
             },   
             
		}
        
            
        
      }());  
      jQuery(document).ready(function($) {

        ArchiveGlobal.filter_ajax();
  
        
    });

})( jQuery );
