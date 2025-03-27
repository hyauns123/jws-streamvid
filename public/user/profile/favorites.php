<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?> <div class="favorites-page">
<?php
$user_id = absint(get_queried_object_id());
$movies_liked = get_user_meta($user_id, 'movies_liked', true);
$tv_shows_liked = get_user_meta($user_id, 'tv_shows_liked', true);
$videos_liked = get_user_meta($user_id, 'videos_liked', true);


if(empty($movies_liked) && empty($tv_shows_liked) && empty($videos_liked)) {
    
    
    return esc_html_e('You have not liked any posts yet.','jws_streamvid');
    
    
    
}


if(!empty($movies_liked)) {
 
$setting =  array(
    'image_size'    =>  jws_theme_get_option('movies_imagesize')
); 
  
$data_slick = 'data-owl-option=\'{
"autoplay":false,
"nav":true, 
"loop":false,
"dots":false, 
"autoWidth":true,
"smartSpeed":500, 
"responsive":{
    "1024":{"items":5,"slideBy":5},
    "768":{"items":1,"slideBy":1},
    "0":{"items":1,"slideBy":1}
}}\'';   
    
$args = array(
    'post_type' => 'movies',
    'post__in' => $movies_liked,
);

$movies_query = new WP_Query($args);   
if ($movies_query->have_posts()) { 
    ?> 
    <div class="jws-movies_advanced-element">
        <h5 class="title-post-type"><?php echo esc_html__('Favorite Movies','jws_streamvid'); ?></h5>
        <div class="row movies_advanced_content jws_movies_advanced_slider owl-carousel layout4" <?php echo $data_slick; ?>>
            <?php  
                
                    while ($movies_query->have_posts()) {
                        $movies_query->the_post();
                        echo '<div class="jws-post-item slider-item">';
                            get_template_part( 'template-parts/content/movies/layout/layout4' , '' , $setting );
                        echo '</div>';
                    }
                
            ?>
        </div>
    </div>    
    <?php 
    }     
}



if(!empty($tv_shows_liked)) {
 
$setting =  array(
    'image_size'    => jws_theme_get_option('tv_shows_imagesize')
); 

$args = array(
    'post_type' => 'tv_shows',
    'post__in' => $tv_shows_liked,
);

$tv_shows_query = new WP_Query($args);   
   if ($tv_shows_query->have_posts()) {
    ?> 
    <div class="jws-tv-shows-advanced-element">
        <h5 class="title-post-type"><?php echo esc_html__('Favorite Tv Shows','jws_streamvid'); ?></h5>
        <div class="row tv-shows-advanced-content layout2 owl-carousel jws-tv-shows-advanced-slider" <?php echo $data_slick; ?>>
            <?php  
                
                    while ($tv_shows_query->have_posts()) {
                        $tv_shows_query->the_post();
                        echo '<div class="jws-post-item slider-item">';
                            get_template_part( 'template-parts/content/tv_shows/layout/layout2' , '' , $setting );
                        echo '</div>';
                    }
               
            ?>
        </div>
    </div>    
    <?php  
     }   
}


if(!empty($videos_liked)) {
 
$setting =  array(
    'image_size'    =>  jws_theme_get_option('videos_imagesize')
); 

$args = array(
    'post_type' => 'videos',
    'post__in' => $videos_liked,
);

$videos_query = new WP_Query($args);    
    ?> 
    <div class="jws-videos-advanced-element">
        <h5 class="title-post-type"><?php echo esc_html__('Video I Liked','jws_streamvid'); ?></h5>
        <div class="row videos-advanced-content layout1">
            <?php  
                if ($videos_query->have_posts()) {
                    while ($videos_query->have_posts()) {
                        $videos_query->the_post();
                        echo '<div class="jws-post-item col-xl-2 col-lg-4 col-md-6">';
                            get_template_part( 'template-parts/content/videos/layout/layout1' , '' , $setting );
                        echo '</div>';
                    }
                } 
            ?>
        </div>
    </div>    
    <?php     
}
?>
</div>