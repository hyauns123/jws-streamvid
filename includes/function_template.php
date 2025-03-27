<?php

if( ! defined('ABSPATH' ) ){
    exit;
}

/**
*
* load the public template file
* 
* @param  string $file
* @return string file path
*
* @since  1.0.0
* 
*/


function jws_streamvid_check_owner() {
    
   $author_id = absint(get_queried_object_id());
   $current_user_id = get_current_user_id();   
   
   if($author_id != $current_user_id) {
      return false;
   }else {
      return true;
   }

}


function jws_streamvid_options($key) {
    
    $data = '';
    
    if(function_exists('jws_theme_get_option')) {
        
        $data = jws_theme_get_option($key);
        
    }
    
    
    return $data;
    

}


function jws_streamvid_load_template( $file, $require_once = true, $args = array()  ){

	$_file = trailingslashit(JWS_STREAMVID_PATH_PUBLIC).$file;

	if( file_exists( $_file ) ){
		load_template( $_file, $require_once, $args  );	
	}
}

function jws_get_max_upload_image_size(){

    $max_size 		= (int)jws_streamvid_options( 'max_upload_size', 2 ) * 1024 * 1024;

    $size = $max_size;

    return apply_filters( 'streamvid_get_max_upload_image_size', $size );
}

function jws_get_gender(){ 
        
   return array(
        'male'        =>  esc_html__( 'Male', 'jws_streamvid' ),
        'female'       =>  esc_html__( 'Female', 'jws_streamvid' ),
        'other'       =>  esc_html__( 'Other', 'jws_streamvid' ),
    ); 
        
}

/*
* Function ajax filter
*/
if (!function_exists('jws_load_season')) {
    function jws_load_season()
    {
   
        ob_start(); 
        $image_size = jws_streamvid_options('tv_shows_imagesize');  
        $tv_shows_seasons = get_field('tv_shows_seasons',$_POST['id']);
        if(isset($tv_shows_seasons[$_POST['season']]['episodes']) && !empty($tv_shows_seasons[$_POST['season']]['episodes'])) :  
        $episodes = $tv_shows_seasons[$_POST['season']]['episodes'];
        $display = 'slider';
        if(isset($_POST['display'])) $display = $_POST['display'];
        $column = "jws-post-item jws-pisodes_advanced-item";
        if($display == 'grid') {
          $column .= " col-xl-2 col-lg-3 col-6";   
        } else {
          $column .= " slider-item"; 
        }   
         
        foreach($episodes as $episodes_value) {
            $args =  array(
                'image_size'    =>  $image_size,
                'post_id' => $episodes_value,
            ) ;
           ?>
           
            
            <div class="<?php echo esc_attr($column); ?>">
               <?php 
                    get_template_part( 'template-parts/content/episodes/layout/layout4' , '' , $args ); 
                ?>
            </div>
           
           
           <?php 
        }
        endif;    
    
        $output = ob_get_clean();
       
        
   
        $result = array(
           'content' => $output,
           'status' => $_POST,
        );
        wp_send_json_success( $result );
    }

    add_action('wp_ajax_jws_load_season', 'jws_load_season');
    add_action('wp_ajax_nopriv_jws_load_season', 'jws_load_season');
}

if(!function_exists('jws_custom_post_type_endpoint')) {
    
  
    
    function jws_custom_post_type_endpoint() {
        
        $episodes_slug = jws_streamvid_options('episodes_slug');   
        $episodes_slug = !empty($episodes_slug) ? $episodes_slug : 'episodes';
    
         add_rewrite_endpoint( $episodes_slug , EP_PERMALINK);
    }
    add_action( 'init', 'jws_custom_post_type_endpoint' );  
    
}



if(!function_exists('jws_check_play_tv_shows')) {

    function jws_check_play_tv_shows($tv_shows_seasons) {
   
        if(isset($tv_shows_seasons[0]['episodes'][0])) {
            return get_the_permalink($tv_shows_seasons[0]['episodes'][0]);
        }
  
    }

}

if(!function_exists('jws_check_trailer')) {

    function jws_check_trailer($post_id) {
        $url = '';
        $trailer_type = get_post_meta($post_id , 'videos_trailer_type' , true);

        if($trailer_type == 'url') {
            $url =  get_post_meta($post_id , 'videos_trailer_url' , true);
            
          
        } else {
            $video_id =  get_post_meta($post_id , 'videos_trailer_file' , true);
            $url = wp_get_attachment_url($video_id);
        }
  
        return $url;
        
    }

}


if(!function_exists('jws_episodes_check_type')) {
    function jws_episodes_check_type( $id ) { 
         
            $args = array(
                'post_type' => 'tv_shows',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'orderby' => 'modified',
                'meta_query' => array(
                'relation'      => 'OR',
                    array(
                        'key' => 'tv_shows_seasons_$_episodes',
                        'value' => $id,
                        'compare' => 'LIKE'
                    )
                )
            );
            
            
            $cast = new WP_Query($args);
            $cast = $cast->posts;
            if(!empty($cast)) {
                return $cast[0];
            }
           
    
        
    }  
  
} 

if(!function_exists('jws_episodes_check_season')) {
    function jws_episodes_check_season( $args ) { 
        
    $args = wp_parse_args( $args, array(
        'id_tv'   =>  '',
        'id'   =>  get_the_ID(),
    ) );
    extract( $args );

    $tv_shows_seasons = get_field('tv_shows_seasons',$id_tv);
 
    if(empty($tv_shows_seasons)) return false;
    
    foreach($tv_shows_seasons as $season => $episodes) {
    
       foreach($episodes['episodes'] as $episode) {
       
         if($episode == $id) {
            
            return $season + 1;
            
        }
       }    
    }    
  }  
  
} 



if(!function_exists('jws_share_button')) { 
    function jws_share_button() {
        if(!jws_streamvid_options('videos_share')) return false;
        ?>
        
        <div class="jws-share">
            <a href="#" data-modal-jws="#share-videos">
                <i class="jws-icon-share-network"></i>
                <span><?php echo esc_html__('Share','jws_streamvid'); ?></span>
            </a>
        </div>
        
        <?php
   } 
}

if(!function_exists('jws_like_button')) {
    function jws_like_button($post_type, $id = '') {
        if(!jws_streamvid_options('videos_like')) return false;
        $post_id = $id ? absint($id) : get_the_ID();
        $liked = get_post_meta($post_id, 'likes', true);
        $liked_number = $liked > 0 ? $liked : '0';
        $user_id = absint(get_current_user_id());
        $list_liked = get_user_meta($user_id, $post_type.'_liked', true);
        $class = 'like-button';
        if(is_array($list_liked) && in_array($post_id, $list_liked)) {
            $class .= ' liked';
        }
        ?>
        <div class="jws-likes">
            <a href="#" class="<?php echo esc_attr($class); ?>" data-type="<?php echo esc_attr($post_type); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
                <i class="jws-icon-thumbs-up"></i>
                <?php printf( _n( '%s <span>like</span>', '%s <span>likes</span>', $liked_number, 'jws_streamvid' ), '<span class="likes-count">' . esc_html($liked_number) . '</span>'); ?>
            
            </a>
        </div>
        <?php
    }
}

if(!function_exists('jws_watchlist_check')) { 
    
     function jws_watchlist_check($post_id) {
        $user_id = absint(get_current_user_id());
        $watchlist = get_user_meta($user_id, 'post_watchlist', true);
        if(is_array($watchlist) && in_array($post_id, $watchlist)) {
            return ' watchlisted';
        }
        return '';
    } 
    
}

if(!function_exists('jws_watchlist_button')) {
    function jws_watchlist_button($id = '') {
        if(!jws_streamvid_options('videos_watchlist')) return false;
        $post_id = $id ? absint($id) : get_the_ID();
        $watchlisted = jws_watchlist_check($post_id); 
        $class = 'watchlist-add'.$watchlisted
        ?>
        <div class="jws-watchlist">
            <a class="<?php echo esc_attr($class); ?>" href="<?php echo get_the_permalink($post_id); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
                <i class="jws-icon-plus"></i>
                <span><?php echo esc_html__('Watchlist', 'jws_streamvid'); ?></span>
                <span class="added"><?php echo esc_html__('Watchlisted', 'jws_streamvid'); ?></span>
            </a>
        </div>
        <?php
    }
}


if(!function_exists('jws_download_button')) {
    function jws_download_button($id = '') {
        $post_id = $id ? absint($id) : get_the_ID();
        $enable = get_post_meta($post_id, 'download', true );
        $download_list = get_field('download_list' , $post_id);
        
        $pmpro_id = is_singular( 'episodes' ) ? jws_episodes_check_type($post_id) : $post_id;

        if(function_exists('pmpro_has_membership_access') && !pmpro_has_membership_access( $pmpro_id, get_current_user_id() ) && !isset($_GET['action']) && !isset($_GET['post'])){
            return false;
        }

        if(!$enable || empty($download_list)) return false;
        $text = esc_html__('Download Videos','jws_streamvid');
        echo "<a href='#' class='jws-download-videos fw-700' data-id='$post_id'><span class='text'>$text</span><i class='jws-icon-arrow-line-down'></i></a>";
        
        if(!empty($download_list)) {
            
            echo '<ul class="jws-download-list">';
            
            foreach($download_list as $download) {
                
                echo '<li><a  href="#" data-url="'.$download['download_url'].'">'.$download['download_name'].'</a></li>';
                
            }
            
            echo '</ul>';
    
        }
        
    
    }
}

function person_register_meta_boxes() {
    
	add_meta_box( 'person', __( 'Person data', 'textdomain' ), 'jws_person_data', 'person' );
    
    
    
    if(function_exists( 'pmpro_page_meta' ) ){
          $cpts = array('movies','tv_shows','videos','episodes');
          if(!empty($cpts)) {
            foreach($cpts as $cpt) {
              add_meta_box('pmpro_page_meta', 'Require Membership', 'pmpro_page_meta', $cpt, 'side', 'high');
            }
          } 
    }

    
}
add_action( 'add_meta_boxes', 'person_register_meta_boxes' );


function jws_person_data( $post ) {
   
    $live_data = get_post_meta( $post->ID, 'person_data', true );
    $live_data2 = get_post_meta( $post->ID, 'person_data_crew', true );
    
  
}


function jws_custom_video_fields($form_fields, $post) {
    
    $form_fields['cloudflare_id'] = array(
        'label' => 'Cloudflare ID',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'cloudflare_id', true),
        'helps' => 'This is cloudflare id'
    );
    
    $form_fields['bunny_id'] = array(
        'label' => 'Bunny ID',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'bunny_id', true),
        'helps' => 'This is bunny id'
    );
    
    return $form_fields;
    
}


add_filter('attachment_fields_to_edit', 'jws_custom_video_fields', 10, 2);


function jws_save_custom_video_fields($post, $attachment) {
    
    if (isset($attachment['bunny_id'])) {
        update_post_meta($post['ID'], 'bunny_id', $attachment['bunny_id']);
    }
    if (isset($attachment['cloudflare_id'])) {
        update_post_meta($post['ID'], 'cloudflare_id', $attachment['cloudflare_id']);
    }
    
    return $post;
}
add_filter('attachment_fields_to_save', 'jws_save_custom_video_fields', 10, 2);


if (!function_exists('jws_ajax_sources')) {
    function jws_ajax_sources()
    {
        if(isset($_POST['id'])) {
            
            ob_start(); 
        
            $data = array();
            
            $data['id'] = $_POST['id'];
            
            if($_POST['index'] != 'main') {
     
                $sources = get_field('sources',$_POST['id']);
                $url = isset($sources[$_POST['index']]['url']) ? $sources[$_POST['index']]['url'] : '';
                $data['url'] = $url;
            }
            
            do_action('streamvid/movies/player',$data); 
        
            
            $output = ob_get_clean();
            $result = array(
               'content' => $output,
               'status' => $_POST['index'],
            );
            wp_send_json_success( $result );
        }
        
    }

    add_action('wp_ajax_jws_ajax_sources', 'jws_ajax_sources');
    add_action('wp_ajax_nopriv_jws_ajax_sources', 'jws_ajax_sources');
}

function jws_is_youtube_url($url) {
  $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/';
  return preg_match($pattern, $url);
}

function jws_check_m3u8_video($video_url) {
    $response = wp_remote_get($video_url);

    if (is_wp_error($response)) {
    
        return false;
    }

    $body = wp_remote_retrieve_body($response);

    if (strpos($body, '#EXTM3U') !== false) {
        return true; 
    } else {
        return false; 
    }
  
}

function jws_has_iframe_in_text($text) {
  if(empty($text)) return false;  
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($text);
  $iframes = $dom->getElementsByTagName('iframe');
  
  return $iframes->length > 0;
}

function jws_has_shortcode_video($text) { 
 
    $first_position = strpos($text, "[");
    $last_position = strrpos($text, "]");
    
    if ($first_position !== false && $last_position !== false) { 
        
        return true;
            
    }
    
}

function jws_check_episodes_membership_access($pmpro_id) { 
  

 if(isset($_GET['package_single'])) {
    return $pmpro_id;
 }
  
 if(jws_theme_get_option('tv_shows_package') && is_singular( 'episodes' )) {
    
   $pmpro_id = jws_episodes_check_type($pmpro_id);

 }
 
 return $pmpro_id;
  
}

function jws_premium_videos($post_id) {
  
   if(function_exists('pmpro_has_membership_access') && !pmpro_has_membership_access( $post_id, get_current_user_id() ))  {
      
    return '<span class="jws-premium jws-icon-crown-1"></span>';
    
   } 
    
}

if(!function_exists('jws_return_data_demo')) {
    
    function jws_return_data_demo() {
        
        if(jws_theme_get_option('block_user_function')) {
              wp_send_json_error(
                new WP_Error(
                    'error',
                    esc_html__( 'Demo version will limit media upload, You can turn this limit off in theme settings.', 'jws_streamvid' )
                )
             ); 
        }
         

    }
    
}

add_action('wp_ajax_jws_video_check', 'jws_video_check');    
add_action( 'wp_ajax_nopriv_jws_video_check', 'jws_video_check' );

function jws_video_check() {

    $errors = new WP_Error(); 

     if( $errors->get_error_code() ){
      
        wp_send_json_error( $errors );
           
     } else {
             $post_id = $_POST['id'];

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
                      'url' => base64_encode($quality['quality_url']),
                      'label' => base64_encode($quality['label']),
                      'type' => base64_encode($type_qua)
                    );
                }
                
                if(empty($video_url)) $video_url = 'many_quality';
                
                
            } 
            
            
            $setup = array(
            	'token'		=>	array(
            		array(         
            			'item'		=>	base64_encode($video_url),
            			'item_type'		=>	base64_encode($type),
                        'item_quality'	 => $quality_array,
            		)
            	),
            );
             wp_send_json_success($setup);
     }
    
    
}


function jws_pmpro_lifter_membership_content_filter( $filtered_content, $original_content ) {	
	// Bail if the streamline option is not enabled.
    return $original_content;
}
add_filter( 'pmpro_membership_content_filter', 'jws_pmpro_lifter_membership_content_filter', 10, 2 );