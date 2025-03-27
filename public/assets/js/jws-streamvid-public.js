(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
     
  $(document).ready(function() {

      
        
        function video_filter() {  
            
    
            
             $(document).on('change' , '.post-select-filter select' , function() {
                
                if($(this).hasClass('cat_change')) {
               
                 var action = $(this).find(':selected').data('url');
            
                $(this).parents('form').attr('action',action);
                    
                }
            
                $(this).parents('form').submit();
                
              }); 
            
        }
        
        video_filter();
   	       
        
        function check_live_stream_status() {
            
               if($('[data-live-uid]').length) {
               var id = $('[data-live-uid]').data('live-uid');
               var live_status = 'not_live';
               var message = '';
               setInterval( function(){
                  $.ajax({
    					url: jws_script.ajax_url,
    					data: {
    						action: 'check_live_stream_status',
                            id: id,
    					},
    					dataType: 'json',
    					method: 'POST',
    					success: function(response) {
    			
                            if(response.success) {
                                
                                
                                
                                if(response.data.status == 'ready' && live_status != 'live') {
      
                                   live_status = 'live_2';
        
                                }
                                
                                
                                if(response.data.status == 'initializing') {
      
                                    live_status = 'live';
        
                                }
                                
                              
                                message = response.data.message;
                                
                                if(live_status == 'live_2') {
                                    
                                    if(response.data.status == 'disconnected') {
                                            $('.player-overlay').fadeIn( "fast" );
                                            $('.player-overlay .message').html(message); 
                                            window.location.reload();
                                       
                                    }
                                        
                                }
                              
                                
                                if(live_status == 'live') {
                                    
                                        $('.player-overlay').fadeIn( "fast" );
                                        $('.player-overlay .message').html(message); 
                                         
                                        if(response.data.status != 'initializing') {
                                    
                                            window.location.reload();
                                       
                                        }
                                        
                                } 	
                            }else {
                              
                            }
        
    					     
    					},
    					error: function() {
    						console.log('We cant remove product wishlist. Something wrong with AJAX response. Probably some PHP conflict.');
    					},
    					complete: function() {
    					
    					},
    			});
                    
                }, 3500 );
                
               } 
            
        }

        check_live_stream_status(); 
        
        
        
        $(document).on('click','[data-modal-jws]' , function(e) {
                   e.preventDefault();
                   var $buttton = $(this).data('modal-jws');
               
                    $.magnificPopup.open({
                        items: {
                            src: $buttton,
                            type: 'inline'
                        },
                        removalDelay: 360,
                        tClose: 'close',    
                        callbacks: {
                            beforeOpen: function() {
                                this.st.mainClass = 'user-popup animation-popup';
                            },
                            open: function() {
                                
        
                            }
                        },
                    });
           
          });
          $('.cancel-modal').on('click' , function(e) { 
                    
             $.magnificPopup.close();
 
          }); 
            
        
      
 });

})( jQuery );
