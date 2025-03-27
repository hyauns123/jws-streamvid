<?php 

$post_id = get_the_ID();

$url_sourse = '';

if(!empty($args)) {
    
  $defaults  = array(
     'id' => '',
     'url' => '',
  );  
  $args = wp_parse_args( $args , $defaults );

  extract( $args );
    
  $post_id = !empty($id) ? $id :  $post_id; 
  $url_sourse = !empty($url) ? $url : '';
} 


$global_ratio = jws_theme_get_option('video_ratio');

$video_radio = get_post_meta($post_id,'video_ratio',true);

$is_affiliate = get_post_meta($post_id,'is_affiliate',true);


$video_radio = !empty($video_radio) ? $video_radio : $global_ratio;

$class_player = 'videos_player ratio_'.$video_radio;

$attr_player = '';

$bn_host_name = jws_theme_get_option('bn_host_name');

$cl_host_name = jws_theme_get_option('cl_host_name');

$advence_videos = jws_theme_get_option('video_advenced');

$autoplay = jws_theme_get_option('video_autoplay') ? true : false;

$muted = jws_theme_get_option('video_muted') ? true : false;

$logo_player = jws_theme_get_option('player_logo');

if(is_embed()) $class_player .= ' has-embed'; 


$post_type = get_post_type();

$poster_id = get_post_thumbnail_id();
$featured_image_two = get_post_meta( $post_id , 'featured_image_two', true );

if(!empty($featured_image_two)) {
    $poster_id  = $featured_image_two;
}

if($post_type == 'videos') {
  
  $live_data = get_post_meta( $post_id , 'live_data', true ); 
  
}


$image = jws_image_advanced(array('attach_id' => $poster_id, 'thumb_size' => 'full' , 'return_url' => true));



$videos_type = !empty($url_sourse) ? 'url' : get_post_meta($post_id, 'videos_type',true);

if($videos_type == 'url') {
    
    $video_url = !empty($url_sourse) ? $url_sourse : get_post_meta($post_id, 'videos_url',true);

    $type = "video/mp4";
    
    if(jws_is_youtube_url($video_url)) {
        $type = 'video/youtube';
        
    }

    if(jws_check_m3u8_video($video_url)) {
        $type = 'application/x-mpegURL';
    }
    
    $iframe = jws_has_iframe_in_text($video_url);
    
    if($iframe) {
        $type = "iframe";
    }
    
    if(jws_has_shortcode_video($video_url)) {
        
          $type = "shortcode";
          
    }


 
}else {
  
    $video_id = get_post_meta($post_id, 'videos_file',true);
    
    $file = get_post_meta($video_id , 'encode_url' , true);
    
    $type = get_post_mime_type( $video_id );
    
    $video_url = wp_get_attachment_url($video_id);
    
    $bunny_id = get_post_meta($video_id , 'bunny_id' , true);
    
    $cloudflare_id = get_post_meta($video_id , 'cloudflare_id' , true);
    
    
    if(!empty($file) && $advence_videos == 'encode') {
        $video_url = get_site_url().strstr($file, '/wp-content');
        $type = 'application/x-mpegURL';
    }
    
    if(!empty($bunny_id) && $advence_videos == 'bunny') {
        $video_url = "//$bn_host_name/$bunny_id/playlist.m3u8";
        $type = 'application/x-mpegURL';
    }  
    
    if(!empty($cloudflare_id) && $advence_videos == 'cloudflare') {
        $video_url = "//$cl_host_name/$cloudflare_id/manifest/video.m3u8";
        $type = 'application/x-mpegURL';
    } 

}

if(isset($live_data['uid'])) {
    
    $attr_player .= 'data-live-uid='.$live_data['uid'].'';

    $live_stream_url =  jws_streamvid()->get()->live_videos->get_live_stream_url($live_data['uid']);
 
    $video_url = $live_stream_url;
    
    $type = 'application/x-mpegURL';
    
}

if(empty($video_url)) {
 
 $default_video_type = jws_theme_get_option('video_player_default_type');   
 $default_video_url = jws_theme_get_option('video_player_default_url'); 
 
 if($default_video_type == 'm3u8') {
     $type = 'application/x-mpegURL';
 } elseif($default_video_type == 'youtube') {
     $type = 'video/youtube';
 } else {
     $type = "video/mp4";
 }
    
    
 
 $video_url = $default_video_url; 
}

$quality_lists = get_field( "quality_lists", $post_id );
$quality_array = array();
if(!empty($quality_lists) && $videos_type == 'many_quality') {
            
    foreach($quality_lists as $key => $quality) {
        $type_qua = "video/mp4";
    
        if(jws_is_youtube_url($quality['quality_url'])) {
            $type_qua = 'video/youtube';
            
        }
    
        if(jws_check_m3u8_video($quality['quality_url'])) {
            $type_qua = 'application/x-mpegURL';
        }
        $quality_array[] = array(
          'url' => $quality['quality_url'],
          'label' => $quality['label'],
          'type' => $type_qua
        );
    }
    
    if(empty($video_url)) $video_url = 'many_quality';
    
    
} 


$setup = array(
	'controls'			=>	true,
	'muted'				=>	$muted,
	'autoplay'			=>	$autoplay,
	'preload'			=>	'auto',
    'playbackRates' => array(0.5, 1, 1.5, 2),
    'logo' => array('url'=>''),
	'sources'			=>	array(
		array(         
			'src'		=>	base64_encode('video hidden'),
			'type'		=>	$type
		)
	),
);

if(!empty($image)) {
    $setup['poster'] = $image;
}

if(isset($logo_player['url']) && !empty($logo_player['url'])) {
    
    $setup['logo']['url'] = $logo_player['url'];
    
}



/* Check Current Time */
$current_time = '';
if( is_user_logged_in() ){ 
          
        $user_id = get_current_user_id();
        
        $video_progress_data = get_user_meta($user_id, 'video_progress_data',true);
        
        $time_id = is_singular( 'episodes' ) ? jws_episodes_check_type($post_id) : $post_id;
        
        if(is_singular( 'episodes' ) && isset($video_progress_data[$time_id]['episodes']) && $video_progress_data[$time_id]['episodes'] == $post_id ) {
            
            $current_time = $video_progress_data[$time_id]['time'];
         
        } elseif(!is_singular( 'episodes' )&&  !empty($video_progress_data) && isset($video_progress_data[$time_id])) {
            
            $current_time = $video_progress_data[$time_id]['time'];
            
        } else {
            
            $current_time = '';
            
        }
    
} 
    
$setup['current_time'] = $current_time;


$pmpro_id = $post_id;

$pmpro_id = jws_check_episodes_membership_access($pmpro_id);

if(function_exists('pmpro_has_membership_access') && !pmpro_has_membership_access( $pmpro_id, get_current_user_id() ) && !isset($_GET['action']) && !isset($_GET['post'])){
 
 $type = 'blocked';

}



$setup = apply_filters( 'streamvid/player/setup', $setup , $post_id );



if(is_admin()) {
    $setup['autoplay'] = false;
}

$class_player .= ' '.$type;

?>
<div class="<?php echo esc_attr($class_player); ?> vjs-waiting" <?php echo esc_attr($attr_player); ?> data-playerid="<?php echo esc_attr($post_id); ?>">

<?php  

if(isset($live_data['uid'])) { 
    
    ?>
        <div class="player-overlay">
        
            <div class="overlay-inner">
                  <div class="spinner">
                        <?php 
                            for ($i = 1; $i <= 8; $i++) {
                                echo '<div class="spinner-blade"></div>';
                            }
                        ?>
                 </div> 
                 <div class="message">
                    <?php 

                       echo esc_html__('Stream is starting soon.','jws_streamvid'); 

                    ?>
                </div>
                
            </div>
           
        </div>
        
    <?php    
    
}


$subtitles = get_field( "sub_titles", $post_id );


if($type == 'blocked'){

 $promp = '<div style="background:url('.$image.'),#000000" class="videos-message">';
 
  $post = get_post($pmpro_id); 
  setup_postdata($post); 
  $promp .= jws_pmpro_no_access_message_html('', $pmpro_id);
  wp_reset_postdata(); 

 $promp .= '</div>'; 
   
 echo $promp;
 
} elseif($type == 'iframe' || $type == 'shortcode') {
    
    echo do_shortcode($video_url);
      
} elseif($is_affiliate) {
    
     $affiliate = '<div style="background:url('.$image.'),#000000" class="videos-affiliate">';
     $affiliate .= '<a target="_blank" href="'.$video_url.'"><i class="jws-icon-play-circle"></i></a>';   
     $affiliate .= '</div>'; 
       
     echo $affiliate;
    
} else {
    ?>
    
    <video id="videos_player"
            class="jws_player video-js vjs-default-skin"
            data-playerid="<?php echo esc_attr($post_id); ?>"
            data-player='<?php echo json_encode( $setup ); ?>'
            data-quality='<?php echo json_encode( $quality_array ); ?>'
            poster="<?php if(isset($setup['poster'])) echo $setup['poster']; ?>"
        >
       <?php
         
         if(!empty($subtitles)) {
            
            foreach($subtitles as $key => $subtitle) {
                 $default = $key == '0' ? 'default' : ''; 
                 $url = isset($subtitle['vtt_file']['url']) ? $subtitle['vtt_file']['url'] : '';
                 if(!empty($subtitle['vtt_url'])) $url = $subtitle['vtt_url'];
                 
                 echo !empty($url) ? '<track label="'.$subtitle['language'].'" kind="subtitles" srclang="'.$subtitle['language'].'" src="'.$url.'" '.$default.' />' : '';
            }
            
         }        
        ?>
    </video>
    <div class="vjs-loading-spinner"></div>
    
    <?php
} ?>


</div>
