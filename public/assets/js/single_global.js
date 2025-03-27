


var jwsSingleGlobal;
(function( $ ) {
	'use strict';
      jwsSingleGlobal = (function() {
        
		return {  
		     episodes_carousel: function() {
  
                    var episodes = $('.global-episodes .jws-pisodes_advanced-slider');
                    episodes.owlCarousel('destroy');
                    jwsThemeModule.owl_caousel_init(episodes);
           
             }, 
             cast_carousel: function() {
                
                    var cast = $('.global-cast .jws-person-advanced-slider');
                    cast.owlCarousel('destroy');
                    jwsThemeModule.owl_caousel_init(cast);
                    
                    var videos = $('.global-video .jws-videos-advanced-slider');
                    videos.owlCarousel('destroy');
                    jwsThemeModule.owl_caousel_init(videos);
                    
                    var movies = $('.global-movies .jws_movies_advanced_slider');
                    movies.owlCarousel('destroy');
                    jwsThemeModule.owl_caousel_init(movies);
       
             },
              
		}
        
            
        
      }());  
      jQuery(document).ready(function($) {

        jwsSingleGlobal.cast_carousel();
        jwsSingleGlobal.episodes_carousel();
  
        function jws_scroll_to_episodes() {
            
            
             if($('.single-episodes .jws-episodes_advanced-element .jws-scrollbar').length) {
                    var ep_list =  $('.single-episodes .videos_player').data('playerid');  
                    
                    var playlist = jQuery( '.single-episodes .jws-episodes_advanced-element .jws-scrollbar' );
    
                    var position = jQuery('#episodes-item-' +  ep_list).position().top;
            
                    playlist.animate({scrollTop: position },{ duration: 0 });
            }
 
        }
        
        jws_scroll_to_episodes();
        
        function adjustEpisodesHeight() {
            var tvShowsInfoHeight = $('.tv-shows-info').outerHeight(); 
            $('.sidebar-list .jws-episodes_advanced-element').css('--top-hright', tvShowsInfoHeight + 'px');
        }
        
        adjustEpisodesHeight();
        $(window).resize(adjustEpisodesHeight);
        
        if($('.single-videos .playlist-list').length) {
             var playlistid =  $('.single-videos .videos_player').data('playerid');  
             document.getElementById( 'playlist-item-' +  playlistid ).scrollIntoView({
    			behavior 	: 'smooth',
    			block 		: 'center',
    			inline 		: 'start' 
    		 }); 
        } 
        
     
        
        $('.jws-list-top .change-layout').click(function(e) { 
                var sidebar = $('.sidebar-list');
                sidebar.toggleClass('list grid'); // Toggle classes efficiently
                jws_scroll_to_episodes(); // Re-run scroll logic after layout change
            
        });

        
         $('.nav-tabs > li > a').click(function(e) {
            e.preventDefault();
            // Get the tab name from the data attribute
            var tabName = $(this).attr('href');
        
        
            // Remove the active class from all tab contents
            $('.tabs-content > div').removeClass('active');
        
            // Add the active class to the clicked tab content
            $(tabName).addClass('active');
        
            // Remove the active class from all tab links
            $('.nav-tabs > li > a').removeClass('active');
        
            // Add the active class to the clicked tab link
            $(this).addClass('active');
            
            jwsSingleGlobal.cast_carousel();
            jws_js_content_check();
        });
        
        $('#comment_rating_stars i').on('mouseover', function() {
            var rating = $(this).data('rating');
            $('#comment_rating_stars i').removeClass('active');
            $('#comment_rating_stars i').slice(0, rating).addClass('active');
        });
        $('#comment_rating_stars i').on('mouseout', function() {
            var rating = $('#comment_rating').val();
            $('#comment_rating_stars i').removeClass('active');
            $('#comment_rating_stars i').slice(0, rating).addClass('active');
        });
        $('#comment_rating_stars i').on('click', function() {
            var rating = $(this).data('rating');
            $('#comment_rating').val(rating);
        });
        
     
       function jws_js_content_check() { 
             if ($(window).width() < 767) {
                if($('.js-content').height() > 60) {
                    $('.view-more-content').show();
                    $('.js-content').addClass('js-more');
                }
            } else {
                if($('.js-content').height() > 72) {
                    $('.view-more-content').show();
                    $('.js-content').addClass('js-more');
                }
            }

        };
        
        function jws_js_content() {
            jws_js_content_check();
            $(document).on('click', '.view-more-content'  , function() {
                $('.js-content-container').toggleClass('open');
                 $('.js-content').toggleClass('js-more');
            });
        
        }
        jws_js_content();
    
        function saveVideoProgress(video_current_time) {
          
            var data = {};
            data.action = 'history_post';
            data.progress = JSON.parse(video_current_time);
            
            if(streamvid_script.is_episodes) {
  
                data.tv_shows = streamvid_script.episodes_tv_shows;
                
            }
         
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: jws_script.ajax_url,
                data:data,
                success: function(response) {
              
                }
            });
        }
        
        
        function custom_logo_player() {
  
        var componentButton = videojs.getComponent('Button');
        
        var controlBarLogo = videojs.extend( componentButton, {
        	constructor: function( player, options ) {
        
        		var defaults	=	{
        			id 			:	'',
        			logo		:	'',
        			href		:	'#',
        		}
        
        		options = videojs.mergeOptions( defaults, options );
        
        		componentButton.call( this, player, options );
        
        		if ( options.logo ) {
                this.update( options );
              }
        	},
        	createEl: function() {
        	    return videojs.dom.createEl( 'button', {
        			 className: 'vjs-control vjs-logo-button'
        		 });
        	},
        	update: function( options ){
        
        		var img = document.createElement( 'img' );
        
        		if( options.logo ){
        			img.src = options.logo;	
        		}
        
        		if( options.alt ){
        			img.alt = options.alt;
        		}
        
        		if( options.href ){
        			img.setAttribute( 'data-href',options.href );
        			if( options.href != '#' ){
        				img.addEventListener("click", function () {
        					window.open( options.href, '_blank' );
        				});
        			}
        		}
        
        		this.el().appendChild( img );
        	},
        });
        videojs.registerComponent( 'controlBarLogo', controlBarLogo );
             
        }
  
        var playerjs = false;  
        function start_player($player_wap,$reload) {
              var $player = $player_wap.find('.jws_player'),  
              option = $player.data( 'player' ),
              quality_lists = false,
              player_start = $player.attr( 'id' ),
              player_id_post = $player_wap.find('[data-playerid]').attr( 'data-playerid' );
              
              var isVideoPlayed = false;
              var hideTimer;
     
              if($player.length) {
                
                    custom_logo_player(); 
                    
                 
                    if(typeof videojs.getPlugin('chromecast') != 'undefined') {
                       
                            option.techOrder = [ 'chromecast', 'html5'];
                           
                            option.chromecast = {
                          
                              modifyLoadRequestFn: function (loadRequest) { // HLS support
                                 loadRequest.media.hlsSegmentFormat = 'ts';
                                 loadRequest.media.hlsVideoSegmentFormat = 'ts';
                                 return loadRequest;
                              }
                           },
                           option.plugins = {
                              chromecast: {},
                              airplayButton: {},
                           }
                    };
                    
                    if(option.sources[0].type == 'video/youtube') {
                         option.muted = true;
                         option.techOrder = [ 'chromecast', 'html5' , 'youtube'];
                    }
                    
                   
                   
                    if($reload) {
                        videojs(player_start).dispose();
                    }
                    
                    if(!playerjs || $reload) { 
                        
                           var data = data = {}; 
                           data.action = 'jws_video_check';
                           data.id = player_id_post;
                           
                           $.ajax({
                                   url: jws_script.ajax_url,
                    					data: data,
                    					type: 'POST',
                                        dataType: 'json',
                                        success: function(response) {
                                
                                      
                                        if(response.success) {
                                            
                                            $('.videos_player').removeClass('vjs-waiting');
                                                             
                                            option.sources = [{
                                                src: atob(response.data.token[0].item),
                                                type:atob(response.data.token[0].item_type)
                                            }];     
                                            
                                            quality_lists =  response.data.token[0].item_quality;                  
                                                                                        
                                            
                                            playerjs = videojs(player_start , option);
                                            
                                        
                                            if(option.ads_tag_url && typeof playerjs.ima != "undefined") {
                                           
                                               playerjs.ima({
                                    				adTagUrl : option.ads_tag_url,
                                    		   });
                                            }
                                            
                                            if(typeof playerjs.seekButtons != "undefined") {
                                                
                                                playerjs.seekButtons({
                                                    forward: 10,
                                                    back: 10
                                                });
                                            
                                                
                                            }
                        
                                            playerjs.ready(function() {
                                         
                                            if(option.logo.url) {
                                                var logo_option	=	{
                                        			id 			:	'',
                                        			logo		:	option.logo.url,
                                        			href		:	'#',
                                        	   }
                                               playerjs.getChild( 'controlBar' ).addChild( 'controlBarLogo', logo_option );
                                            }    
                                            
                                      
                                          
                                            if(typeof option.current_time != "undefined") { 
                                              
                                               playerjs.on('loadedmetadata', function() {
                                               
                                                if(option.current_time <= playerjs.duration()) {
                                                    
                                                     playerjs.currentTime(option.current_time);
                                                    
                                                }
                                          
                                              });
                                    
                                            }
                                            
                                            if(quality_lists.length > 0) {
                                       
                                              playerjs.controlBar.addChild('QualitySelector');
                                            
                                              const sources = quality_lists.map((quality, index) => ({
                                                    src: atob(quality.url),
                                                    type: atob(quality.type),
                                                    label: atob(quality.label),
                                                    selected: index === 0 
                                              }));
                                            
                                               
                                              playerjs.src(sources);
                                            
                                            } else {
                                                
                                                if(option.sources[0].type == 'application/x-mpegURL') {
                                                  this.hlsQualitySelector({
                                                      displayCurrentQuality: true,
                                                    }); 
                                                } 
                                          
                                                
                                            }
                                                  
                                                          
                                            playerjs.hotkeys({
                                              volumeStep: 0.1,
                                              seekStep: 5,
                                              enableVolumeScroll: false
                                            });   
                                            
                                     
                                            playerjs.on('play', function() {
                                               
                                                isVideoPlayed = true;
                                                
                                                
                                            });
                                            
                                          
                                             function jws_save_history_action() {
                        					
                        						if (isVideoPlayed && !playerjs.isDisposed()) { 
                        						  
                                                
                                                    let currentTime = playerjs.currentTime();
                                                    let lengthOfVideo = playerjs.duration();
                                               
                                                    const currentTime_id = {
                                                      id: player_id_post,
                                                      time: currentTime,
                                                      endtime: lengthOfVideo
                                                    };
                        							if($('body').hasClass('logged-in')) {
                                                        saveVideoProgress(JSON.stringify(currentTime_id)); 
                        							}
                        							
                                                }
                        						
                        						
                        					};	
                                            
                        						
                        				    hideTimer = setInterval(jws_save_history_action,5000);	
                                
                                            
                                            playerjs.on('ended', function() {
                                    
                                                  var episode_list = streamvid_script.episodes_list;
                                        
                                                  if(typeof episode_list != "undefined") {
                                                  
                                                          let index = episode_list.findIndex(item => item.id == player_id_post);
                                                          
                                                          if (index !== -1 && index < episode_list.length - 1) {
                                                               
                                                                var nextItem = episode_list[index + 1];
                                                     
                                                                jwsThemeModule.show_notification(streamvid_script.next_episodes,'success');
                                                                setTimeout(function(){ window.location.href = nextItem['link'];  }, 2000);  
                                                        
                                                          }
                                                    
                                                  }
                                           
                                              
                                            });
                                            
                                              
                                            setTimeout(function(){ $player.removeAttr('data-player');  $('#videos_player').removeAttr('data-player');  }, 500);
                                            
                                           if(streamvid_script.block_devtool == 'yes') {
                                                console.log(Object.defineProperties(new Error, {
                                                    toString: {
                                                        value() {
                                                            (new Error).stack.includes('toString@') && alert('Safari devtools');
                                                        }
                                                    },
                                                    message: {
                                                        get() {
                                                            alert(streamvid_script.security_text);
                                                            $('.jws_player ').empty();
                                                            location.reload();
                                                        }
                                                    },
                                                }));
                                           }
                                              
                                                            
                                               
                                          });
                                            
                                            
                                            
                                  
                                        } else {
                                            
                                        }
                                    
                				
                                    },
                                    error: function() {
                                        console.log('error');
                                    },
                                    complete: function() {},
                           });    
                            
                        }
           
          
                }
                
               
             $(window).on("beforeunload", function() { 
                    let currentTime = "";
                    let lengthOfVideo = "";
                    if (isVideoPlayed) { 
                         currentTime = playerjs.currentTime();
                         lengthOfVideo = playerjs.duration();
                    }    
                        const currentTime_id = {
                          id: player_id_post,
                          time: currentTime,
                          endtime: lengthOfVideo
                        };
                        localStorage.setItem('video_current_time', JSON.stringify(currentTime_id));
                      
                        const video_current_time = localStorage.getItem("video_current_time");
          
                        if($('body').hasClass('logged-in')) {
                           saveVideoProgress(video_current_time); 
                        }  
                           
                        localStorage.removeItem("video_current_time");
                        
                        return; 
                    
               });
               
                 
            
        }
        
        
        
        function player_action() {
            
        var player;   
      
        if ( typeof videojs == 'function') {
            
              
                $('.videos_player').each(function(){
                    
                    var $this = $(this);
                    
                    if(!$('.single-movies .site-main').hasClass('version-v3')) {
                        player = start_player($this);
                    }

                    
                });
                
                  $('.single-movies .version-v3 .video-play .jws-play').magnificPopup({
                          type: 'inline',
                          midClick: true,
                          mainClass: 'mfp-fade',
                          callbacks: {
                		    beforeOpen: function() {
                		    
                		        if(!playerjs) {
                		          start_player($('.videos_player')); 
                		        } else {
                		          playerjs.play();
                		        } 
                		        
                                this.st.mainClass = 'videojs-popup animation-popup';
                            },  
                            beforeClose: function() {
                                if(playerjs) { 
                		            playerjs.pause();
                                    playerjs.disablePictureInPicture();
                		        } 
                          
                            }
                    	  },
                    });
       
        }
            
               
        $(document).on('click' , '.sources-videos button' , function(e) {
            var data = {};
            var button = $(this);
            var container = button.parents('.sources-videos');
            var post_id = container.data('id');
            e.preventDefault(); 
            
            
            $('.videos_player').addClass('loading');
            
            if(container.hasClass('sources-table')) {
                $('.jws-play').trigger('click');  
                
            } else {
                
                container.find('li').removeClass('active');
                button.parent().addClass('active');
            }
            
            $('.videos_player').empty();
            $('.videos_player').append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>').addClass('loading');    
              
            
            data.action = 'jws_ajax_sources';
            data.id = post_id;
            data.index = button.data('index');
            
            if(button.hasClass('main')) {
               data.index = 'main'; 
            }
            
            $.ajax({
				url: jws_script.ajax_url,
				data: data,
				type: 'POST',
                dataType: 'json',
			}).success(function(response) {
                
                  $('.videos_player').replaceWith(response.data.content);   
                  player = start_player($('.videos_player'),true);
              

		
			}).complete(function( ){
                $('.videos_player').removeClass('loading');
	        }).error(function(ex) {
				console.log(ex);
			});
    	});
            
            
        }
        
        player_action();
  
      
        // load more button click event
    	$(document).on('click' , '#comments .page-numbers a' , function(e) {
            
            e.preventDefault();
            var url = $(this).attr('href');
          
            $(document.body).trigger('jws_comments_filter_ajax', [url, $(this)]);
            
    	});
        
        $(document.body).on('jws_comments_filter_ajax', function(e, url, element) { 
            
            $('#comments').append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>').addClass('loading');    
                    
            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }

            url = url.replace(/%2C/g, ',');

            window.history.pushState(null, "", url);
            $(window).bind("popstate", function() {
                window.location = location.href
            });
            
            $.get(url, function(res) {
            $('#comments').replaceWith($(res).find('#comments'));
         

        }, 'html');
        });
        
     
        
          
        
    });

})( jQuery );
