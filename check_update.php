<?php

defined( 'ABSPATH' ) || exit;


if( ! class_exists( 'JwsStreamvidUpdateChecker' ) ) {

	class JwsStreamvidUpdateChecker{

		public $plugin_slug;
		public $version;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {

			$this->plugin_slug = plugin_basename( __DIR__ );
			$this->cache_key = "$this->plugin_slug-cc";
			$this->cache_allowed = false;
  
            if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
     
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . "/$this->plugin_slug/$this->plugin_slug.php" , false , false);
            $this->version = $plugin_data['Version'];
            global $pagenow;
            
            if(get_option('jws_license') == 'good') {
    			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
    			add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
    			add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
            }
           
		}

		public function request(){
         
			$remote = get_transient( $this->cache_key );
            
           
        
			if( false === $remote) {

				$remote = wp_remote_get(
					"https://jwsuperthemes.com/plugins/streamvid/$this->plugin_slug.json",
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, HOUR_IN_SECONDS * 10 );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );
 
			return $remote;

		}


		function info( $res, $action, $args ) {

			// print_r( $action );
			// print_r( $args );

			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return false;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return false;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return false;
			}

			$res = new stdClass();

			$res->name = $remote->name;
			$res->slug = $remote->slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = $remote->requires_php;
			$res->last_updated = $remote->last_updated;

			$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog
			);
 
			if( ! empty( $remote->banners ) ) {
				$res->banners = array(
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			}

			return $res;

		}

		public function update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();

			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$res = new stdClass();
				$res->slug = $this->plugin_slug;
				$res->plugin = "$this->plugin_slug/$this->plugin_slug.php"; 
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;

				$transient->response[ $res->plugin ] = $res;

	    }

			return $transient;

		}

		public function purge(){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
				delete_transient( $this->cache_key );
			}

		}


	}

	new JwsStreamvidUpdateChecker();

}
