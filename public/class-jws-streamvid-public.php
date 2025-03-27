<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jwsuperthemes.com
 * @since      1.0.0
 *
 * @package    Jws_Streamvid
 * @subpackage Jws_Streamvid/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Jws_Streamvid
 * @subpackage Jws_Streamvid/public
 * @author     Jws Theme <jwstheme@gmail.com>
 */
class Jws_Streamvid_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jws_Streamvid_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jws_Streamvid_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if(is_singular( array( 'movies') ) || is_singular( array( 'episodes') ) || is_singular( array( 'videos') )) {
            
        wp_enqueue_style( 'videojs', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/css/videojs.css', array(), $this->version, 'all' );
        
        wp_enqueue_style( 'videojs-ima', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/css/videojs.ima.css', array(), $this->version, 'all' );
     
        wp_enqueue_style( 'videojs-chromcast', 'https://cdn.jsdelivr.net/npm/@silvermine/videojs-chromecast@1.5.0/dist/silvermine-videojs-chromecast.min.css', array(), $this->version, 'all' );
        
        wp_enqueue_style( 'videojs-seek', 'https://cdn.jsdelivr.net/npm/videojs-seek-buttons@3.0.1/dist/videojs-seek-buttons.min.css', array(), $this->version, 'all' );
        
        wp_enqueue_style( 'videojs-airplay', 'https://cdn.jsdelivr.net/npm/videojs-airplay@1.1.1/dist/videojs.airplay.min.css', array(), $this->version, 'all' );
        
        wp_enqueue_style( 'videojs-quality-selector', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/css/quality-selector.css', array(), $this->version, 'all' );
         
       
        }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jws_Streamvid_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jws_Streamvid_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
         
         
        
        $video_chromcast = function_exists('jws_theme_get_option') && jws_theme_get_option('video_chromcast') ? true : false;
        $video_player_ads = function_exists('jws_theme_get_option') && jws_theme_get_option('video_player_ads') ? true : false;
        $video_seek_button = function_exists('jws_theme_get_option') && jws_theme_get_option('video_seek_button') ? true : false;
        


        wp_enqueue_script( $this->plugin_name, JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/jws-streamvid-public.js', array( 'jquery' ), $this->version, true );
        
        wp_enqueue_script( 'jws-tool', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/tool/tool.js', array( 'jquery' ), $this->version, true );
        
        wp_register_script( 'jws-youtube-api', '//www.youtube.com/iframe_api', array( 'jquery' ), $this->version, true );
      
        
        if(is_archive()) { 
    
             wp_enqueue_script( 'jws-archive-global', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/archive_global.js', array( 'jquery' ), $this->version, true );
            
            
        }
        
        wp_register_script( 'jws-single-global', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/single_global.js', array( 'jquery' ), $this->version, true );
            
        if(is_singular( array( 'movies') ) || is_singular( array( 'episodes') ) || is_singular( array( 'videos') ) || is_singular( array( 'tv_shows')) || is_singular( array( 'person'))) { 
            
             wp_enqueue_script( 'jws-single-global');
            
            
        }
      
        if(is_singular( array( 'movies') ) || is_singular( array( 'episodes') ) || is_singular( array( 'videos') )) {
            
            
            wp_enqueue_script( 'videojs', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs.min.js', array( 'jquery' ), $this->version, true );
            
            wp_enqueue_script( 'videojs-youtube', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/Youtube.min.js', array( 'videojs' ), $this->version, true );
            
            wp_enqueue_script( 'videojs-http-streaming', JWS_STREAMVID_URL_PUBLIC_ASSETS. '/js/videojs-http-streaming.js', array( 'videojs' ), $this->version, true );
            
            wp_enqueue_script( 'videojs-contrib-quality-levels', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs-contrib-quality-levels.min.js', array( 'videojs' ), $this->version, true );
            
            wp_enqueue_script( 'videojs-hls-quality-selector', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs-hls-quality-selector.min.js', array( 'videojs' ), $this->version, true );
            
            wp_enqueue_script( 'silvermine-videojs-quality-selector', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/silvermine-videojs-quality-selector.min.js', array( 'videojs' ), $this->version, true );
      
            if($video_player_ads) {
               
                wp_enqueue_script( 'videojs-ima', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs.ima.min.js', array( 'videojs' ), $this->version, true );
            
                wp_enqueue_script( 'videojs-contrib-ads', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs-contrib-ads.js', array( 'videojs' ), $this->version, true );
                
                wp_enqueue_script( 'googleapis-imasdk', '//imasdk.googleapis.com/js/sdkloader/ima3.js', array( 'videojs' ), $this->version, true ); 
                    
            }

            /* Chromcast */

            if($video_chromcast) {
                
                wp_enqueue_script( 'videojs-chromecast', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs-chromecast.min.js', array( 'videojs' ), $this->version, true );
                wp_enqueue_script( 'cast_sender', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs.cast_sender.js?loadCastFramework=1', array( 'videojs' ), $this->version, false );
                wp_enqueue_script( 'cast_fenny', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs.cast.min.js', array( 'videojs' ), $this->version, true ); 
                wp_enqueue_script( 'videojs-airplay', 'https://cdn.jsdelivr.net/npm/videojs-airplay@1.1.1/dist/videojs.airplay.min.js', array( 'videojs' ), $this->version, true ); 
                
            }
            
            if($video_seek_button) {
               
                wp_enqueue_script( 'seek-buttons', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs-seek-buttons.js', array( 'videojs' ), $this->version, true );
             
                
            }
 
           
            
            wp_enqueue_script( 'videojs-hotkeys', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/videojs.hotkeys.min.js', array( 'videojs' ), $this->version, true );
            
     
            
        }
        
        if(is_singular( array( 'movies') )) {
          
            wp_enqueue_script( 'jws-single-movies', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/single_movies.js', array( 'jquery' ), $this->version, true );
          
        }
        
        wp_register_script( 'jws-single-tv-shows', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/single_tv_shows.js', array( 'jquery' ), $this->version, true );
          
        if(is_singular( array( 'tv_shows') )) {
          wp_enqueue_script( 'jws-single-tv-shows');
        }
        
        
        if(is_singular( array( 'person') )) {
          
            wp_enqueue_script( 'jws-single-person', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/single_person.js', array( 'jquery' ), $this->version, true );
          
        }
        
        if(is_author()) {
          
            wp_enqueue_script( 'jws-profile', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/profile.js', array( 'jquery' ), $this->version, true );
           
          
        }
        
        if( is_user_logged_in() ){
            
            wp_enqueue_script( 'jws-upload-videos', JWS_STREAMVID_URL_PUBLIC_ASSETS . '/js/upload_videos.js', array( 'jquery' ), $this->version, true );
       
        } 
        $max_size = jws_get_max_upload_image_size();
       
        $fr_varjs = array( 
         
            'max_file_size'	=>	sprintf(
                '%s %smb.',
                esc_html( 'Support *.png, *.jpeg, *.gif, *.jpg. Maximun upload file size:' , 'jws_streamvid' ),
                $max_size
            ),
            'next_episodes' => esc_html__( 'Moving on to the next episode' , 'jws_streamvid' ),
            'security_text' => jws_theme_get_option('block_devtool_text','You are currently using DevTools. Please disable it to continue watching the video.' ),
            'block_devtool' => jws_theme_get_option('block_devtool') ? 'yes' : 'no',
            
        );     
    
        if(is_singular( array( 'episodes') ) ) {
            $fr_varjs['is_episodes'] = true;
            $fr_varjs['episodes_tv_shows'] = jws_episodes_check_type(get_the_ID());
            $fr_varjs['episodes_list'] = array();
            $tv_shows = jws_episodes_check_type(get_the_ID());
            $tv_shows_seasons = get_field('tv_shows_seasons',$tv_shows);
            $seasion = jws_episodes_check_season( array('id_tv' => $tv_shows) );
            $auto_episodes = jws_theme_get_option('next_episodes') ? true : false;
            $seasion = $seasion - 1;
            if(isset($_GET['playlist'])) {
                
                $term = get_term( $_GET['playlist'] , 'episodes_playlist' );
                $post_ids = get_term_meta($_GET['playlist'], 'playlist_order', true);
                $args =  array(
                    'post_type'         =>  'episodes',
                    'post_status'       =>  array( 'publish' ),
                    'posts_per_page'    =>  -1,
                    'post__in'  => $post_ids, 
                    'orderby'   => 'post__in', 
                    'order'             =>  'ASC',
                     'fields'          => 'ids', // Only get post IDs
                    'tax_query'         =>  array(
                        array(
                            'taxonomy'  =>  $term->taxonomy,
                            'field'     =>  'term_id',
                            'terms'     =>  $_GET['playlist']
                        )
                    )
                ) ; 
                
                $episodes = get_posts( $args );
                
                foreach($episodes as $episodes_value) { 
                    
                     $link = get_the_permalink($episodes_value);
                     
                     if(!empty($_GET['playlist'])) { $link = add_query_arg( 'playlist', $_GET['playlist'] , $link ); } 
                     
                     $fr_varjs['episodes_list'][] = array(
                            
                        'id' => $episodes_value,
                        'link' => $link
                      
                      );
                    
                }
                
            }  else {
                
                   if(isset($tv_shows_seasons[$seasion]['episodes']) && !empty($tv_shows_seasons[$seasion]['episodes']) && $auto_episodes) : 
            
                       $episodes = $tv_shows_seasons[$seasion]['episodes'];
                 
                       foreach($episodes as $episodes_value) { 
                          $link = get_the_permalink($episodes_value); 
                          $fr_varjs['episodes_list'][] = array(
                            
                            'id' => $episodes_value,
                            'link' => $link
                          
                          );
                       }
                    
                    
                    endif;
                
            }
         
            
        }
  

		$fr_varjs = apply_filters( 'streamvid_frontend_localize', $fr_varjs );

		wp_localize_script( $this->plugin_name, 'streamvid_script', $fr_varjs );
        
        
        
        
        
	}
    
    public function movies_player($args) { 
        jws_streamvid_load_template("movies/player.php", false , $args);
    }
    public function likes() { 
        
        jws_streamvid_load_template("tool/like/likes.php", false);

    } 
    
    public function watchlist() { 
        
        jws_streamvid_load_template("tool/watchlist/watchlist.php", false);

    }
    
    public function download() { 
        
        jws_streamvid_load_template("tool/download/download.php", false);

    }
    
    public function history_delete() {  
        
      
        $errors = new WP_Error();   
      
        $history_delete = isset($_POST['post_id']) ?  $_POST['post_id'] : '';
        
        $user_id = get_current_user_id();
            
        $video_progress_data = get_user_meta($user_id, 'video_progress_data',true);
     
        if(!empty($history_delete)) {
            
            foreach($history_delete as $delete) {
                
                if(isset($video_progress_data[$delete])) {
                  
                  unset($video_progress_data[$delete]);
                
                
                }
   
            }
            
            update_user_meta($user_id, 'video_progress_data', $video_progress_data);
            
           $message = esc_html__('Removed from history.','jws_streamvid');
            
        } else {
             $errors->add(
                'video_empty',
                esc_html__( 'No video selected yet.', 'jws_streamvid' )
            );
        }
        $result = [
            'message' => $message
        ];
   
        if( $errors->get_error_code() ){
             wp_send_json_error( $errors );
        } 
        
            
        wp_send_json_success($result);
  
    }
    
       public function history() { 
            
   
        $args = wp_parse_args( $_POST, array(
            'progress' => array(),
            'tv_shows' => ''
        ) );

        extract( $args );
        
        $errors = new WP_Error();   
       
        
        if( $errors->get_error_code() ){
             wp_send_json_error( $errors );
        } 
        
        if(is_user_logged_in()) {
            
            $user_id = get_current_user_id();
            
            $video_progress_data = get_user_meta($user_id, 'video_progress_data',true);
            
            if(empty($video_progress_data)) {
                
                $video_progress_data = array();
                
            }
             
            if(!empty($progress)) {
                
                $id = $progress['id'];
                 
                if(!empty($tv_shows)) {
                   $id_tv_show = $tv_shows;
                }
              
                if(isset($video_progress_data[$id])) {
                  
                  unset($video_progress_data[$id]);
                
                }
              
      
                 $video_progress_data[$id] = array(
                   'time' => $progress['time'],
                   'endtime' => $progress['endtime']
                 );
                 
                if(!empty($tv_shows)) {
                    if(isset($video_progress_data[$id_tv_show])) {
                  
                      unset($video_progress_data[$id_tv_show]);
                    
                    }
                    
                 $video_progress_data[$id_tv_show] = array(
                   'time' => $progress['time'],
                   'endtime' => $progress['endtime']
                 );
                   $video_progress_data[$id_tv_show]['episodes'] =  $progress['id'];
             
                }
            
                 update_user_meta($user_id, 'video_progress_data', $video_progress_data);
                
            }
      
        }
       
       
        
    }
    
    
    public function search_page($search_template) { 
        
        if( is_search() && !isset($_GET['post_type'])){
			$search_template = plugin_dir_path( __FILE__ ) . 'page/search.php';
		}
        return $search_template;

    }

    
     public function global_modal() {
        global $wp_query; 
         if(is_single()){
		  jws_streamvid_load_template("tool/share/share.php", false);
		}
        
        
        if( is_user_logged_in() ){
            jws_streamvid_load_template("playlist/create-playlist.php", false);
            if(isset($_GET['playlist']) && !empty($_GET['playlist'])) { 
              jws_streamvid_load_template("playlist/edit-playlist.php", false);  
              jws_streamvid_load_template("playlist/delete-playlist.php" , false , array('term_id'=>$_GET['playlist']));
              jws_streamvid_load_template("playlist/other-playlist.php" , false , array('term_id'=>$_GET['playlist']));
              jws_streamvid_load_template("playlist/search-item.php", false ,  array('term_id'=>$_GET['playlist']));
            }
          
            do_action( 'streamvid/videos/form', array('type'=>'create') );
            do_action( 'streamvid/videos/form', array('type'=>'edit') );
            
            if(isset($wp_query->query_vars['dashboard'])) {
                jws_streamvid()->get()->dashboard->form_profile();
                jws_streamvid()->get()->dashboard->form_personal();     
            }
            
            jws_streamvid()->get()->live_videos->form_upload(); 
          
           
        }  
    }     
}
