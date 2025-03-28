(function( $ ) {
	'use strict';

  $(document).ready(function() {
    
  
        $('.jws-download-videos').on('click', function(e) { 
            
            e.preventDefault();
         
            var button = $(this); 
            
            button.next('.jws-download-list').slideToggle();
            
        });

        $('.jws-download-list a').on('click', function(e) {
           
            e.preventDefault();
         
            var button = $(this);
            var download_url = button.data('url');
         
        
        
            button.addClass('loading');
            if(!button.find('.loader').length) {   
                    button.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');
            }
            console.log(download_url);
            $.ajax({
                type: 'POST',
                url: jws_script.ajax_url,
                data: {
                    action: 'download_post',
                    download_url: download_url,
                },
                success: function(response) {
                    
                    console.log(response);
                    
                   
                    if(response.data.content != "no_file") {
                        
                        var link = document.createElement('a');
                        link.href = response.data.content;
                        link.download = 'video.mp4'; // Set the filename for the downloaded video
                
                        // Append the link to the document and trigger the click event
                        $(document.body).append(link);
                        link.click();
                
                        // Remove the link from the document
                        $(link).remove();
                        
                    } else {
                        
                  
                        jwsThemeModule.show_notification('Video not found.','error');
                        
                    }
                    
                    button.removeClass('loading');
                   
                    
                }
            });
        }); 
        
        
        $(document).on('click', '.like-button' , function(e) {    
            e.preventDefault();
            
            if($('body').hasClass('user-not-logged-in')) {
                 $('.jws-form-login-popup').addClass('open');
                 return false;
            }
           
            var button = $(this);
            var post_id = button.data('post-id');
            var post_type = button.data('type');
            var likes_count = button.find('.likes-count');
            var $type = 'like';
            if(button.hasClass('liked')) {
                $type = 'dislike';
            }
            button.addClass('loading');
            if(!button.find('.loader').length) {   
                    button.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');
            }
            $.ajax({
                type: 'POST',
                url: jws_script.ajax_url,
                data: {
                    action: 'like_post',
                    post_id: post_id,
                    post_type:post_type,
                    type: $type,
                },
                success: function(response) {
                    button.removeClass('loading');
                    if(response.data.status == 'good') {
                      likes_count.text(response.data.count);
                      button.addClass('liked');  
                    }else {
                      button.removeClass('liked'); 
                      likes_count.text(response.data.count);
                    }
                    jwsThemeModule.show_notification(response.data.message,'success');   
                }
            });
        }); 
        
        
        
        
        /* Code function watch list */
        
            function edit_watchlist() {
           
             $(document).on('click','.watchlist-edit', function(e) {  
                        
                      e.preventDefault();
                       var button = $(this); 
                      $('.watchlist-button').addClass('editor');
                      $('.profile-main').addClass('editor');  
             });
             $(document).on('click','.watchlist-cancel', function(e) {  
                        
                      e.preventDefault();
                       var button = $(this); 
                      $('.watchlist-button').removeClass('editor');
                      $('.profile-main').removeClass('editor');   
             });
             
             $(document).on('click','.select-all', function(e) {  
                        
                      e.preventDefault();
                       
                       var button = $(this); 
                       button.toggleClass('active');
                       if (button.hasClass('active')) {
                          $('input[name="watchlisted[]"]').prop('checked', true);
                        } else {
                          $('input[name="watchlisted[]"]').prop('checked', false);
                        }
                 
             });
            
             $(document).on('click','.watchlist-delete', function(e) {  
                        
                      e.preventDefault();
                      var button = $(this); 
                      var selectedValues = []; 
                      var $type = 'watchlist_many';
                    $('input[name="watchlisted[]"]:checked').each(function() {
                      selectedValues.push($(this).val());
                    });
                    
                    if($('.profile-watchlist').length) {
                        watchlist_item(selectedValues,$type,button);
                    }else {
                        history_delete(selectedValues,button);
                    }
                
                    

                      
             });
             
             function history_delete($id,button) { 
                button.addClass('loading');
                if(!button.find('.loader').length) {   
                    button.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');
                }
                $.ajax({
                    type: 'POST',
                    url: jws_script.ajax_url,
                    data: {
                        action: 'history_delete',
                        post_id: $id,
                    },
                    success: function(response) {
                        button.removeClass('loading'); 
                    
                        if(response.success) { 
                            
                          
                            $('input[name="watchlisted[]"]:checked').each(function() {
                              $(this).parents('.jws-post-item').remove();
                            });
                          
                            jwsThemeModule.show_notification(response.data.message,'success');  
  
                        }else {
                            
                            jwsThemeModule.show_notification(response.data[0].message,'error');
                            
                        }
                        
                    }
                });
                
                
             }
             
             
             function watchlist_item($id,$type,button) {
                button.addClass('loading');
                let button_global;
                if(button.hasClass('watchlist-delete')) {
                    button_global = button;
                } else {
                    button_global = $(".watchlist-add[data-post-id="+$id+"]");
                }
                if(!button.find('.loader').length) {   
                    button.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');
                }
                $.ajax({
                    type: 'POST',
                    url: jws_script.ajax_url,
                    data: {
                        action: 'watchlist_post',
                        post_id: $id,
                        type: $type,
                    },
                    success: function(response) {
                        button.removeClass('loading'); 
                      
                        if(response.success) { 
                            
                           if($type == 'watchlist_many') {
                                $('input[name="watchlisted[]"]:checked').each(function() {
                                  $(this).parents('.jws-post-item').remove();
                                });
                            } else {
                               if(response.data.status == 'good') {
                                  button_global.addClass('watchlisted');  
                                }else {
                                  button_global.removeClass('watchlisted'); 
                                } 
                            } 
                            jwsThemeModule.show_notification(response.data.message,'success');  
  
                        }else {
                            
                            jwsThemeModule.show_notification(response.data[0].message,'error');
                            
                        }
                        
                    }
                });
                
             }
             
                   
            $(document).on('click', '.watchlist-add' , function(e) {
                e.preventDefault();
                
                if($('body').hasClass('user-not-logged-in')) {
                   $('.jws-form-login-popup').addClass('open');
                   return false;
                }

                var button = $(this);
                var post_id = button.data('post-id');
                var $type = 'watchlist';
                if(button.hasClass('watchlisted')) {
                    $type = 'watchlisted';
                }
                watchlist_item(post_id,$type,button);
            }); 
       
            
        }  
        edit_watchlist(); 
  

	 });

})( jQuery );
