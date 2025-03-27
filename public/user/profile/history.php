<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

   $user_id = get_current_user_id();
        
   $video_progress_data = get_user_meta($user_id, 'video_progress_data',true);
   $valid_post_types = ['movies', 'tv_shows', 'episodes', 'videos']; 
?> 
<div class="profile-history">
    <h5 class="profile-title"><?php echo esc_html__('My History','jws_streamvid'); ?></h5>
    <button class="select-all"><?php echo esc_html__('Select all','jws_streamvid'); ?></button>
    <div class="row">
        <?php  
           
            if(!empty($video_progress_data)) { $video_progress_data = array_reverse($video_progress_data, true);
              foreach($video_progress_data as $id => $history) { 
                     
                    $post_type = get_post_type($id); 
                    if(!in_array($post_type, $valid_post_types)) {  
        
                           continue;
        
                    }
                    $setting = array(
                     
                     'id' => $id,
                     'history' => $history
                    
                    );
                    
                    echo '<div class="jws-post-item col-xl-2 col-lg-4 col-md-6 col-12">';
                        get_template_part( 'template-parts/content/content-history' , '' , $setting );
                    echo '</div>';
                }
            } else {
                echo '<div class="jws-post-item col-xl-2 col-lg-4 col-md-6 col-12">';
                    echo esc_html__('Not Found','jws_streamvid');
                echo '</div>';
            }
          
        ?>
    </div>
</div>    
<?php     
