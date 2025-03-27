<?php

/**
 * Fired during plugin activation
 *
 * @link       https://jwsuperthemes.com
 * @since      1.0.0
 *
 * @package    Jws_Streamvid
 * @subpackage Jws_Streamvid/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code for profile
 *
 * @since      1.0.0
 * @package    Jws_Streamvid
 * @subpackage Jws_Streamvid/includes
 * @author     Jws Theme <jwstheme@gmail.com>
 */
class Jws_Streamvid_Profile {
    
     public function _profile_is_owner(){

      
        $user_id = get_current_user_id();
        $current_id = get_queried_object_id();
        
        if(is_user_logged_in() && get_current_user_id() == get_queried_object_id()) { 
            
            return true;
            
        }
        
   
        return false;
    } 
    
    public function redirect_url_default(){  
    
        $is_logged_in = is_user_logged_in();
        
        if( !$is_logged_in ){
            return '';
        }

        // Set account page
        $account_page = get_option( 'pmpro_account_page_id' );

        if( $account_page && is_page( $account_page ) ){
            wp_redirect( $this->get_endpoint( get_current_user_id(), 'membership' , '' )   );
        }

        // Set billing page
        $billind_page = get_option( 'pmpro_billing_page_id' );

        if( $billind_page && is_page( $billind_page ) ){
            wp_redirect( $this->get_endpoint( get_current_user_id(), 'membership' , 'billing' ));
        }

        // Set invoices page
        $invoice_page = get_option( 'pmpro_invoice_page_id' );

        if( $invoice_page && is_page( $invoice_page ) ){
            wp_redirect( $this->get_endpoint( get_current_user_id(), 'membership' , 'invoices' ));
        } 
        
    }
    
    public function get_menu_menu_header_items(){  
 
       $items = array();
       
       $items[ 'favorites' ] 	= array(
			'title'			=>	esc_html__( 'Favorites', 'jws_streamvid' ),
			'icon'			=>	'jws-icon-thumbs-up',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/favorites.php' );
			},
			'priority'		=>	0
	   );
       
       $items[ 'history' ] 	= array(
			'title'			=>	esc_html__( 'History', 'jws_streamvid' ),
			'icon'			=>	'jws-icon-eye',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/history.php' );
			},
			'priority'		=>	5
	   );
      
       if(function_exists( 'pmpro_page_meta' ) ){ 
           $items[ 'subscriptions' ] 	= array(
    			'title'			=>	esc_html__( 'Subscription', 'jws_streamvid' ),
    			'icon'			=>	'jws-icon-crown-simple',
    			'callback'		=>	function(){
    				jws_streamvid_load_template( 'user/profile/subscriptions.php' );
    			},
                'parent'    =>  'membership', 
    			'priority'		=>	10
    	   );
       }
       if(jws_theme_get_option('video_upload')) {
            $items[ 'upload' ] 	= array(
    			'title'			=>	esc_html__( 'Upload', 'jws_streamvid' ),
    			'icon'			=>	'jws-icon-upload',
    			'callback'		=>	'',
                'popup'         =>  '#upload-videos',
    			'priority'		=>	20
    	   );
       }
       if(jws_theme_get_option('video_live')) { 
            $items[ 'live' ] 	= array(
    			'title'			=>	esc_html__( 'Go live', 'jws_streamvid' ),
    			'icon'			=>	'jws-icon-video-camera',
    			'callback'		=>	'',
                'popup'         =>  '#upload-videos-live',
    			'priority'		=>	30
    	   );
       }
       
       $items[ 'author' ] 	= array(
			'title'			=>	esc_html__( 'My Profile', 'jws_streamvid' ),
			'icon'			=>	'jws-icon-user-circle',
			'callback'		=>	'',
            'url'       =>  get_author_posts_url( get_current_user_id() ),
			'priority'		=>	40
	   );
       $items[ 'profile' ] 	= array(
			'title'			=>	esc_html__( 'Setting', 'jws_streamvid' ),
			'icon'			=>	'jws-icon-gear',
			'callback'		=>	'',
            'parent'    =>  'dashboard', 
			'priority'		=>	50
	   );
       $items[ 'logout' ] 	= array(
			'title'			=>	esc_html__( 'Logout', 'jws_streamvid' ),
			'icon'			=>	'jws-icon-sign-out',
			'callback'		=>	'',
            'url'       =>  esc_url( wp_logout_url( home_url( '/' ) ) ),
			'priority'		=>	60
	   );
       return apply_filters( 'streamvid_profire_menu_header_items', $items );
        
    }
    
    
    public function get_menu_membership_items(){ 
       if( ! function_exists( 'pmpro_page_meta' ) ){
            return array();
       }
       
       $items[ 'membership' ] 	= array(
			'title'			=>	esc_html__( 'Membership', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/dashboard.php' );
			},
			'priority'		=>	0,
            'submenu'	=>	array(
				'subscriptions'	=>	array(
					'title'		=>	esc_html__( 'Your Subscriptions', 'jws_streamvid' ),
					'icon'		=>	'icon-edit',
					'callback'	=>	function(){
        				jws_streamvid_load_template( 'user/profile/subscriptions.php' );
        			},
					'priority'	=>	10,
                    'parent'    =>  'membership'     
				),
                'billing'	=>	array(
					'title'		=>	esc_html__( 'Billing', 'jws_streamvid' ),
					'icon'		=>	'icon-edit',
					'callback'	=>	function(){
        				jws_streamvid_load_template( 'user/profile/billing.php' );
        			},
					'priority'	=>	20,
                    'parent'    =>  'membership'   
				),
                'invoices'	=>	array(
					'title'		=>	esc_html__( 'Invoices', 'jws_streamvid' ),
					'icon'		=>	'icon-edit',
					'callback'	=>	function(){
        				jws_streamvid_load_template( 'user/profile/invoices.php' );
        			},
					'priority'	=>	30,
                    'parent'    =>  'membership'   
				)
           )     
	   );
       
       return apply_filters( 'streamvid_profire_menu_membership_items', $items );
    
        
        
    }
    
    public function get_menu_dashboard_items(){ 

       
       $items[ 'dashboard' ] 	= array(
			'title'			=>	esc_html__( 'Dashboard', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/dashboard.php' );
			},
			'priority'		=>	10,
            'submenu'	=>	array(
				'profile'	=>	array(
					'title'		=>	esc_html__( 'My Profile', 'jws_streamvid' ),
					'icon'		=>	'',
					'callback'		=>	function(){
        				jws_streamvid_load_template( 'user/profile/profile.php' );
        			},
        			'priority'	=>	20,
                    'parent'    =>  'dashboard'
				),

           )     
	   );
       
 
		if( function_exists( 'WC' )  ){
			$items[ 'dashboard' ]['submenu']['shop'] = array(
				'title'		=>	esc_html__( 'Shopping', 'streamtube-core' ),
				'icon'		=>	'',
				'callback'	=>	function(){
				    jws_streamvid_load_template( 'user/profile/woocommerce/shop.php' );
				},
                'priority'	=>	30,
				'parent'	=>	'dashboard',
				'submenu'	=>	array(
					'orders'	=>	array(
						'title'		=>	esc_html__( 'Orders', 'streamtube-core' ),
						'priority'	=>	10
					),
					'downloads'	=>	array(
						'title'		=>	esc_html__( 'Downloads', 'streamtube-core' ),
						'priority'	=>	20
					),
					'edit-address'		=>	array(
						'title'		=>	esc_html__( 'Addresses', 'streamtube-core' ),
						'priority'	=>	30
					)
				),
				'cap'		=>	'read',
			
			);			
		}
        
       return apply_filters( 'streamvid_profire_menu_woocommerce_items', $items );
  
        
        
    }

	public function get_menu_items(){
	   
	   $items = array();
      
       $items[ 'favorites' ] 	= array(
			'title'			=>	esc_html__( 'Favorites', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/favorites.php' );
			},
			'priority'		=>	0
	   );
       
        $items[ 'history' ] 	= array(
			'title'			=>	esc_html__( 'History', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/history.php' );
			},
			'priority'		=>	5
	   );
       
       $items[ 'playlist' ] 	= array(
			'title'			=>	esc_html__( 'Playlist', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/playlist.php' );
			},
			'priority'		=>	10
	   );
       $items[ 'watchlist' ] 	= array(
			'title'			=>	esc_html__( 'Watchlist', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/watchlist.php' );
			},
			'priority'		=>	20
	   );
        $items[ 'video' ] 	= array(
			'title'			=>	esc_html__( 'Videos', 'jws_streamvid' ),
			'icon'			=>	'',
			'callback'		=>	function(){
				jws_streamvid_load_template( 'user/profile/videos.php' );
			},
			'priority'		=>	30
	   );
       
     
       
       return apply_filters( 'streamvid_profire_menu_items', $items );


	}
    
    private function get_request_endpoint($var){
		global $wp_query;
 
		$menu_items = $this->pre_get_menu_items();
        
        if($var == 'dashboard') {
            
            $menu_items = $this->get_menu_dashboard_items(); 
        }
        
        if($var == 'membership') {
            
            $menu_items = $this->get_menu_membership_items();; 
        }
        $endpoint = isset($wp_query->query_vars[$var]) ? $wp_query->query_vars[$var] : '';
      
        
        if(!empty($endpoint)){
                return explode( '/' , $endpoint );
                
            
        } elseif(isset($wp_query->query_vars[$var])) {
        
             return $var; 
        }

		
	}
    
    public function add_endpoints(){
        
		$menu_items = array_keys($this->get_menu_items());        
        $menu_dashboard_items = array_keys($this->get_menu_dashboard_items());
        $menu_membership_items = array_keys($this->get_menu_membership_items());
       
        $menu_items = array_merge($menu_items, $menu_dashboard_items , $menu_membership_items);
 
		for ( $i=0; $i < count( $menu_items ); $i++) { 
			add_rewrite_endpoint( $menu_items[$i], EP_AUTHORS );
		}

	}
    
    
    public function get_endpoint( $user_id = 0, $parent_endpoint = '' , $endpoint = '' ){

		if( ! $user_id ){
			return;
		}

		$url = get_author_posts_url( $user_id);
    
        if( ! get_option( 'permalink_structure' ) ){ 
			return add_query_arg( array(
				$parent_endpoint	=>	$endpoint
			), $url );
           
		}
	
		return trailingslashit( $url ) . $parent_endpoint . '/' . $endpoint;
	}
    
    public static function get_url( $endpoint = '', $parent = '' ){
        
        $current_user = get_current_user_id();
        
        $url = is_author() ? get_author_posts_url( get_queried_object_id() ) : get_author_posts_url($current_user);
 
		if( ! $endpoint ){
			return $url;
		}

		if( ! get_option( 'permalink_structure' ) ){
			if( ! $parent ){
				$url = add_query_arg( array(
					$endpoint 	=>	1
				), $url );
			}
			else{
				$url = add_query_arg( array(
					$parent 	=>	$endpoint
				), $url );				
			}
		}
		else{

			$path = $endpoint;

			if( $parent ){
				$path = $parent . '/' . $endpoint;	
			}

			$url = trailingslashit( $url ) . $path;
		}

		return $url;
	}
    
    
    public function pre_get_menu_items(){
        
		$menu_items = $this->get_menu_items();
        $menu_dashboard_items = $this->get_menu_dashboard_items();
        $menu_membership_items = $this->get_menu_membership_items();
        $menu_items = array_merge($menu_items, $menu_dashboard_items , $menu_membership_items);
        

		return $menu_items;	
	}

    
    public function get_currents_menu(){

		$current = '';
       
		$menu_items = $this->pre_get_menu_items();
        $menu_layout = $this->the_menu_items_position();
        
        
        
          $endpoint_member = $this->get_request_endpoint('membership');
          $endpoint_dashboard = $this->get_request_endpoint('dashboard');
      
          if($endpoint_member == 'membership') {
            return array_keys($menu_layout)[0];
          }
          

          if($endpoint_dashboard == 'dashboard') {
        
            return array_keys($menu_layout)[0];
            
          }
  
          
          if(isset($endpoint_member[0]) && !empty($endpoint_member[0])) {
            
            $menu_items = $endpoint_member;
             
             return $menu_items[0];
            
          }
               
  
          if(isset($endpoint_dashboard[0]) && !empty($endpoint_dashboard[0])) {
            
           
            $menu_items = $endpoint_dashboard;
  
             return $menu_items[0];
            
          }
     
        
 
		if( count( $menu_items ) == 0 ){
			return false;
		}

		foreach ( $menu_items as $menu_id => $menu ) {

			$menu = wp_parse_args( $menu, array(
				'cap'	=>	'read'
			) );

			if( ! user_can( get_queried_object_id(), $menu['cap'] ) ){
				unset( $menu_items[ $menu_id ] );
			}

			if( isset( $GLOBALS['wp_query']->query_vars[$menu_id] ) ){
				$current = $menu_id;
               
			}
		}

        $return = isset(array_keys( $menu_items )[0]) ? array_keys( $menu_items )[0] : '';

		return $current ? $current : $return;
	}
    
    
    protected function uasort( &$items ){
		uasort( $items, function( $item1, $item2 ){
			return $item1['priority'] <=> $item2['priority'];
		} );
	}
    
    
    
    public  function the_main() {
        if( is_author() ){
			 jws_streamvid_load_template("user/profile/main.php", false);
			exit;
		}    
    }
    
    
    
    public  function the_menu_items_position() { 
        
            $menu_items = $this->get_menu_items();
            
            $endpoint_member = $this->get_request_endpoint('membership');
            $endpoint_dashboard = $this->get_request_endpoint('dashboard');
  
            if(isset($endpoint_dashboard[0]) && !empty($endpoint_dashboard[0])) {
                $menu_items = $this->get_menu_dashboard_items();
                $menu_items = $menu_items['dashboard']['submenu'];
            }
            
            if(isset($endpoint_member[0]) && !empty($endpoint_member[0])) {
                $menu_items = $this->get_menu_membership_items();
                $menu_items = $menu_items['membership']['submenu'];
            }
            
            return $menu_items;
        
    }
    
    
    
    public  function the_content() {
 
       	$current = $this->get_currents_menu();
       
		$menu_items = $this->the_menu_items_position();
        
        $is_owner = jws_streamvid_check_owner();
        
        if(!$is_owner && $current != 'video') {
            echo esc_html__('You do not have access.','jws_streamvid');
        }else {
           if( count( $menu_items ) == 0 
			|| ! array_key_exists( $current , $menu_items ) 
			|| ! array_key_exists( 'callback' , $menu_items[ $current ] ) 
			|| ! is_callable( $menu_items[ $current ]['callback'] ) ){
			// If no menu items found, we load videos template instead of empty space.
       
			return jws_streamvid_load_template( 'user/profile/favorites.php' );
		}
        
 
  
		return call_user_func( $menu_items[ $current ]['callback'] );	  
        }
 
		 
    }
    
    public  function the_header() {
            
           jws_streamvid_load_template("user/profile/header.php", false);
        
    }
    
    
   
    
    
    public  function the_menu($header) {
  
            $current = $this->get_currents_menu();
            
            $menu_items = $this->the_menu_items_position();
          
            if($header) {
                
                $menu_items = $this->get_menu_menu_header_items();
                
                
            }
         
            $this->uasort( $menu_items );
     
           
            ?>
            
            <ul class="<?php echo esc_attr('nav');?>">
                    
                    
                    <?php 
                        $menu_li = '';
                        
                        foreach($menu_items as $id => $menu) {
                            
                        
            				$menu = wp_parse_args( $menu, array(
            					'url'		=>	'',
                                'parent'	=>	'',
            					'private'	=>	false
            				) );
                     
                            $menu_li .= sprintf(
    							'<li class="nav-item nav-%s">',
    							sanitize_html_class( $id )
						    );
                            
                            $menu_li .= sprintf(
								'<a class="item-%s %s"  href="%s" %s>',
								esc_attr($id),
								$current == $id ? 'active' : '',
								$menu['url'] ? esc_url( $menu['url'] ) : esc_url( $this->get_url( $id, $menu['parent'] ) ),
                                !empty($menu['popup']) ? "data-modal-jws='".$menu['popup']."'" : ''
							);
                            
                            if(!empty($menu['icon'])) {
                                
                            $menu_li .= sprintf(
								' <i class="%s"></i>',
								$menu['icon'],
							);
                                
                            }
                            
                            $menu_li .= sprintf(
								' <span class="menu-text">%s</span>',
								$menu['title'],
							);
                            
                            $menu_li .= '</a>';
                            
                            $menu_li .= '</li>';
                            
                        }
                    echo $menu_li;
                    ?>
            
            </ul>
            
            <?php
        
    }
  
}

  