<?php 

if(isset($_POST['post_id']) && isset($_POST['post_type']) && isset($_POST['type'])) {
    $post_id = $_POST['post_id'];
    $post_type = $_POST['post_type'];
    $type = $_POST['type'];
    $user_id = get_current_user_id();
    if(!$user_id) {
        wp_send_json_success('errrorr');
    }
    
    $liked = get_post_meta($post_id, 'likes', true);
    $list_liked = get_user_meta($user_id, $post_type.'_liked', true);
    
    if(empty($list_liked)) {
        $list_liked = [];
    }
    
    $status = 'bad';
    $liked_number = $liked ?: '0';

    if($type == 'dislike' && in_array($post_id, $list_liked)) {
        $liked_number--;
        update_post_meta($post_id, 'likes', $liked_number);
        $key = array_search($post_id, $list_liked);
        if($key !== false) {
            unset($list_liked[$key]);
        } 
        update_user_meta($user_id, $post_type.'_liked', $list_liked);
        $message = sprintf(
                __('Unliked <strong>%s</strong>.','jws_streamvid'),
                get_the_title( $post_id )
        );
    }
    
    if($user_id && !in_array($post_id, $list_liked) && $type == 'like') {
        $list_liked[] = $post_id;  
        update_user_meta($user_id, $post_type.'_liked', $list_liked);
        $liked_number++;
        update_post_meta($post_id, 'likes', $liked_number);
        $status = 'good';
        $message =  sprintf(
                __('Liked <strong>%s</strong>.','jws_streamvid'),
                get_the_title( $post_id )
        );
    }
    
    $result = [
        'status' => $status,
        'count'  => $liked_number,
        'message' => $message
    ];
    
    wp_send_json_success($result);
}
