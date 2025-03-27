<?php 

    $errors = new WP_Error();
    
    if(!isset($_POST['post_id'])) {
        
        $errors->add(
                'video_empty',
                esc_html__( 'No video selected yet.', 'jws_streamvid' )
        );
        
    } 
    
    if( $errors->get_error_code() ){
             wp_send_json_error( $errors );
    } 
    
    $post_id = $_POST['post_id'];
    
    $type = $_POST['type'];
    
    $user_id = get_current_user_id();

    $watchlisted = get_user_meta($user_id, 'post_watchlist', true);
    
    if(empty($watchlisted)) {
        $watchlisted = [];
    }
    
    $status = 'bad';
 
    if($type == 'watchlisted' && in_array($post_id, $watchlisted)) {
        $key = array_search($post_id, $watchlisted);
        if($key !== false) {
            unset($watchlisted[$key]);
        } 
        $output = update_user_meta($user_id, 'post_watchlist', $watchlisted);
        $message = esc_html__('Removed from watchlist.','jws_streamvid');
    }
    
    if($user_id && !in_array($post_id, $watchlisted) && $type == 'watchlist') {
        $watchlisted[] = $post_id;  
        $output = update_user_meta($user_id, 'post_watchlist', $watchlisted);
        $status = 'good';
        $message =  sprintf(
            __('Added <strong>%s</strong> to watchlist.','jws_streamvid'),
            get_the_title( $post_id )
        );
    }
    
    if($user_id && $type == 'watchlist_many') {
        if(!in_array($post_id, $watchlisted)) {
            $watchlisted = array_diff($watchlisted, $post_id);
            $output = update_user_meta($user_id, 'post_watchlist', $watchlisted);
            $status = 'good';
            $message = esc_html__('Removed from watchlist.','jws_streamvid'); 
        } else {
           wp_send_json_error(  esc_html__('No video selected yet.','jws_streamvid')  );
        }
        
    }
   
    $result = [
        'status' => $status,
        'message' => $message
    ];
    
    wp_send_json_success($result);