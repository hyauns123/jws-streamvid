<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
$user_id = absint(get_queried_object_id());
wp_enqueue_script('jws-youtube-api');
$post_watchlisted = get_user_meta($user_id, 'post_watchlist', true);

if(!empty($post_watchlisted)) {
 
$setting =  array(
    'image_size'    =>  '580x326',
); 
  
 
$args = array(
    'post_type' => array('videos', 'movies' , 'tv_shows'),
    'post__in' => $post_watchlisted,
    'orderby' => array( 'menu_order' => 'DESC' )
  
);

$querys = new WP_Query($args);    
    ?> 
    <div class="jws-videos-advanced-element profile-watchlist">
        <h5 class="profile-title"><?php echo esc_html__('My Watchlist','jws_streamvid'); ?></h5>
        <button class="select-all"><?php echo esc_html__('Select all','jws_streamvid'); ?></button>
        <div class="row videos-advanced-content layout1 row">
            <?php  
                $id_empty = array();
                if ($post_watchlisted) {
                    foreach(array_reverse($post_watchlisted) as $id) {
                        $status =  get_post_status($id);
                      
                        if(!$status) {
                            
                           $id_empty[]  = $id;
                            
                        }
                        
                        if($status != 'publish') continue;
                        $setting['id'] = $id;
                        echo '<div class="jws-post-item col-xl-2 col-lg-4 col-md-6 col-12">';
                            get_template_part( 'template-parts/content/watchlist/watchlist' , '' , $setting );
                        echo '</div>';
                    }
                } 
                if(!empty($id_empty)) {
                    
                   $watchlisted = array_diff($post_watchlisted, $id_empty);
                   update_user_meta($user_id, 'post_watchlist', $watchlisted); 
                    
                }
            ?>
        </div>
    </div>    
    <?php     
} else {
    
    return esc_html_e('No watchlist.','jws_streamvid');
    
}