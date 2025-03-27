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
 * This class defines all code for post type
 *
 * @since      1.0.0
 * @package    Jws_Streamvid
 * @subpackage Jws_Streamvid/includes
 * @author     Jws Theme <jwstheme@gmail.com>
 */
class Jws_Streamvid_Post {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function create_custom_posttype() {
	   
        $labels = array(
			'name' 									=> esc_html__( 'Movies', 'jws_streamvid' ),
			'singular_name' 						=> esc_html__( 'Movie', 'jws_streamvid' )	
		);
        
        $movies_slug = jws_streamvid_options('movies_slug');
        

		$args = array(
			'label' 								=> esc_html__( 'Movies', 'jws_streamvid' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> false,
			'rest_base' 							=> '',
			'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> true,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=>	!empty($movies_slug) ? $movies_slug : 'movie', 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'trackbacks', 
				'custom-fields', 
				'comments', 
				'author' 
			),
            'menu_icon' => 'dashicons-video-alt3', 
		);
        register_post_type( 'movies', $args );
        
        $labels = array(
			'name'					=> _x( 'Movies Categories', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Movies Category', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Categories', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Movies Categories', 'zahar' ),
			'all_items'				=> esc_html__( 'All Movies Categories', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Category', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Category', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Category', 'zahar' ),
		);
	    $movies_cat_slug = jws_streamvid_options('movies_cat_slug');
      
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => !empty($movies_cat_slug) ? $movies_cat_slug : 'movies_cat' , 'with_front' =>	true  ),
		);
        

        
       register_taxonomy( 'movies_cat', array( 'movies' ), $args  );
       
       
       
       $labels = array(
            'name' => esc_html__( 'Tags', 'zahar' ),
            'singular_name' => esc_html__( 'Tag',  'zahar'  ),
            'search_items' =>  esc_html__( 'Search Tags' , 'zahar' ),
            'popular_items' => esc_html__( 'Popular Tags' , 'zahar' ),
            'all_items' => esc_html__( 'All Tags' , 'zahar' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__( 'Edit Tag' , 'zahar' ), 
            'update_item' => esc_html__( 'Update Tag' , 'zahar' ),
            'add_new_item' => esc_html__( 'Add New Tag' , 'zahar' ),
            'new_item_name' => esc_html__( 'New Tag Name' , 'zahar' ),
            'separate_items_with_commas' => esc_html__( 'Separate tags with commas' , 'zahar' ),
            'add_or_remove_items' => esc_html__( 'Add or remove tags' , 'zahar' ),
            'choose_from_most_used' => esc_html__( 'Choose from the most used tags' , 'zahar' ),
            'menu_name' => esc_html__( 'Tags','zahar'),
        ); 
    
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'movies_tag' ),
        );
        
        register_taxonomy( 'movies_tag', array( 'movies' ), $args  );
        
        $labels = array(
			'name'					=> _x( 'Playlist', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Playlist', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Playlist', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Movies Playlist', 'zahar' ),
			'all_items'				=> esc_html__( 'All Movies Playlist', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Playlist', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Playlist', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Playlist', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Playlist', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Playlist', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Playlist', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Playlist', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Playlist', 'zahar' ),
		);
	
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'movies_playlist' ),
		);
        

        
       register_taxonomy( 'movies_playlist', array( 'movies' ), $args  );
        
        
        
        /* ------------------------------ */
       
       
       
       
        $labels = array(
			'name' 									=> esc_html__( 'Episodes', 'jws_streamvid' ),
			'singular_name' 						=> esc_html__( 'Episodes', 'jws_streamvid' )	
		);
        
        $episodes_slug = jws_streamvid_options('episodes_slug');

		$args = array(
			'label' 								=> esc_html__( 'Episodes', 'jws_streamvid' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> false,
			'rest_base' 							=> '',
			'has_archive' 							=> true,
			'show_in_menu'		  => 'edit.php?post_type=tv_shows',
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=> !empty($episodes_slug) ? $episodes_slug : 'episodes', 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt',  
			),
			'menu_icon'								=>	'dashicons-video-alt3'
		);
        register_post_type( 'episodes', $args );
        
        
        $labels = array(
			'name'					=> _x( 'Playlist', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Playlist', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Playlist', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Playlist', 'zahar' ),
			'all_items'				=> esc_html__( 'All Playlist', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Playlist', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Playlist', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Playlist', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Playlist', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Playlist', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Playlist', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Playlist', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Playlist', 'zahar' ),
		);
	
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'episodes_playlist' ),
		);
        

        
       register_taxonomy( 'episodes_playlist', array( 'episodes' ), $args  );
        
        
        $labels = array(
			'name' 									=> esc_html__( 'Tv Shows', 'jws_streamvid' ),
			'singular_name' 						=> esc_html__( 'Tv Shows', 'jws_streamvid' )	
		);
        
        $tv_shows_slug = jws_streamvid_options('tv_shows_slug');

		$args = array(
			'label' 								=> esc_html__( 'Tv Shows', 'jws_streamvid' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> false,
			'rest_base' 							=> '',
			'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> true,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=>	!empty($tv_shows_slug) ? $tv_shows_slug : 'tv_shows', 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'trackbacks', 
				'custom-fields', 
				'comments', 
				'author' 
			),
			'menu_icon'			=>	'dashicons-video-alt3'
		);
        register_post_type( 'tv_shows', $args );
        
        $labels = array(
			'name'					=> _x( 'Tv Shows Categories', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Tv Shows Category', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Categories', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Tv Shows Categories', 'zahar' ),
			'all_items'				=> esc_html__( 'All Tv Shows Categories', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Category', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Category', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Category', 'zahar' ),
		);
	    $tv_shows_cat_slug = jws_streamvid_options('tv_shows_cat_slug');
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => !empty($tv_shows_cat_slug) ? $tv_shows_cat_slug : 'tv_shows_cat' ),
		);

       register_taxonomy( 'tv_shows_cat', array( 'tv_shows' ), $args  );
       
       
       $labels = array(
            'name' => esc_html__( 'Tags', 'zahar' ),
            'singular_name' => esc_html__( 'Tag',  'zahar'  ),
            'search_items' =>  esc_html__( 'Search Tags' , 'zahar' ),
            'popular_items' => esc_html__( 'Popular Tags' , 'zahar' ),
            'all_items' => esc_html__( 'All Tags' , 'zahar' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__( 'Edit Tag' , 'zahar' ), 
            'update_item' => esc_html__( 'Update Tag' , 'zahar' ),
            'add_new_item' => esc_html__( 'Add New Tag' , 'zahar' ),
            'new_item_name' => esc_html__( 'New Tag Name' , 'zahar' ),
            'separate_items_with_commas' => esc_html__( 'Separate tags with commas' , 'zahar' ),
            'add_or_remove_items' => esc_html__( 'Add or remove tags' , 'zahar' ),
            'choose_from_most_used' => esc_html__( 'Choose from the most used tags' , 'zahar' ),
            'menu_name' => esc_html__( 'Tags','zahar'),
        ); 
    
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'tv_shows_tag' ),
        );
        
        register_taxonomy( 'tv_shows_tag', array( 'tv_shows' ), $args  );
       
       
       
       $labels = array(
			'name' 									=> esc_html__( 'Videos', 'jws_streamvid' ),
			'singular_name' 						=> esc_html__( 'Videos', 'jws_streamvid' )	
		);
        
        $videos_slug = jws_streamvid_options('videos_slug');

		$args = array(
			'label' 								=> esc_html__( 'Videos', 'jws_streamvid' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> false,
			'rest_base' 							=> '',
			'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> true,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=>	!empty($videos_slug) ? $videos_slug : 'videos', 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'trackbacks', 
				'custom-fields', 
				'comments', 
				'author' 
			),
			'menu_icon'								=>	'dashicons-video-alt3'
		);
        register_post_type( 'videos', $args );
        
        $labels = array(
			'name'					=> _x( 'Videos Categories', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Videos Category', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Categories', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Videos Categories', 'zahar' ),
			'all_items'				=> esc_html__( 'All Videos Categories', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Category', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Category', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Category', 'zahar' ),
		);
	    $videos_cat_slug = jws_streamvid_options('videos_cat_slug');
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => !empty($videos_cat_slug) ? $videos_cat_slug : 'videos_cat' ),
		);
        

        
       register_taxonomy( 'videos_cat', array( 'videos' ), $args  );
       
       
       $labels = array(
			'name'					=> _x( 'Playlist', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Playlist', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Playlist', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Videos Playlist', 'zahar' ),
			'all_items'				=> esc_html__( 'All Videos Playlist', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Playlist', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Playlist', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Playlist', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Playlist', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Playlist', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Playlist', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Playlist', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Playlist', 'zahar' ),
		);
	
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'videos_playlist' ),
		);
        

        
       register_taxonomy( 'videos_playlist', array( 'videos' ), $args  );
       
       $labels = array(
            'name' => esc_html__( 'Tags', 'zahar' ),
            'singular_name' => esc_html__( 'Tag',  'zahar'  ),
            'search_items' =>  esc_html__( 'Search Tags' , 'zahar' ),
            'popular_items' => esc_html__( 'Popular Tags' , 'zahar' ),
            'all_items' => esc_html__( 'All Tags' , 'zahar' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__( 'Edit Tag' , 'zahar' ), 
            'update_item' => esc_html__( 'Update Tag' , 'zahar' ),
            'add_new_item' => esc_html__( 'Add New Tag' , 'zahar' ),
            'new_item_name' => esc_html__( 'New Tag Name' , 'zahar' ),
            'separate_items_with_commas' => esc_html__( 'Separate tags with commas' , 'zahar' ),
            'add_or_remove_items' => esc_html__( 'Add or remove tags' , 'zahar' ),
            'choose_from_most_used' => esc_html__( 'Choose from the most used tags' , 'zahar' ),
            'menu_name' => esc_html__( 'Tags','zahar'),
        ); 
    
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'videos_tag' ),
        );
        
        register_taxonomy( 'videos_tag', array( 'videos' ), $args  );
       
       
        $labels = array(
			'name' 									=> esc_html__( 'Person', 'jws_streamvid' ),
			'singular_name' 						=> esc_html__( 'Person', 'jws_streamvid' )	
		);
        
        $person_slug = jws_streamvid_options('person_slug');

		$args = array(
			'label' 								=> esc_html__( 'Person', 'jws_streamvid' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> false,
			'rest_base' 							=> '',
			'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> true,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=>	!empty($person_slug) ? $person_slug :  'person', 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'trackbacks', 
				'custom-fields', 
				'comments', 
				'author' 
			),
			'menu_icon'								=>	'dashicons-admin-users'
		);
        register_post_type( 'person', $args );
       
        $labels = array(
			'name'					=> _x( 'Person Categories', 'Taxonomy plural name', 'zahar' ),
			'singular_name'			=> _x( 'Person Category', 'Taxonomy singular name', 'zahar' ),
			'search_items'			=> esc_html__( 'Search Categories', 'zahar' ),
			'popular_items'			=> esc_html__( 'Popular Person Categories', 'zahar' ),
			'all_items'				=> esc_html__( 'All Person Categories', 'zahar' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'zahar' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'zahar' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'zahar' ),
			'update_item'			=> esc_html__( 'Update Category', 'zahar' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'zahar' ),
			'new_item_name'			=> esc_html__( 'New Category', 'zahar' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'zahar' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'zahar' ),
			'menu_name'				=> esc_html__( 'Category', 'zahar' ),
		);
	    $person_cat_slug = jws_streamvid_options('person_cat_slug');
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => !empty($person_cat_slug) ? $person_cat_slug :  'person_cat' ),
		);
        

        
       register_taxonomy( 'person_cat', array( 'person' ), $args  );
       
       $labels = array(
                'name' => _x('Advertising', 'Post Type General Name', 'jws_streamvid'),
                'singular_name' => _x('Advertising', 'Post Type Singular Name', 'jws_streamvid'),
                'menu_name' => __('Advertising', 'jws_streamvid'),
                'parent_item_colon' => __('Parent discussion', 'jws_streamvid'),
                'all_items' => __('Advertising', 'jws_streamvid'),
                'view_item' => __('View discussions', 'jws_streamvid'),
                'add_new_item' => __('Add new question', 'jws_streamvid'),
                'add_new' => __('Add new', 'jws_streamvid'),
                'edit_item' => __('Edit discussion', 'jws_streamvid'),
                'update_item' => __('Update discussion', 'jws_streamvid'),
                'search_items' => __('Search discussion', 'jws_streamvid'),
                'not_found' => __('Not found', 'jws_streamvid'),
                'not_found_in_trash' => __('Not found in the bin', 'jws_streamvid'),
         );

         // Set other options for Custom Post Type

         $args = array(
                'label' => __('Advertising', 'jws_streamvid'),
                'description' => __('Advertising', 'jws_streamvid'),
                'labels' => $labels,
                // Features this CPT supports in Post Editor
                'supports' => array(
                    'title',
                    //'editor',
                    //'author',
                ),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu'	 => true,
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'menu_position' => 9,
                'can_export' => false,
                'has_archive' => false,
                'exclude_from_search' => true,
                'menu_icon' => 'dashicons-clipboard',
                'query_var' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
        );
        
        register_post_type( 'advertising', $args );
        
        $labels = array(
                'name' => _x('Ads VMAP', 'Post Type General Name', 'jws_streamvid'),
                'singular_name' => _x('Ads VMAP', 'Post Type Singular Name', 'jws_streamvid'),
                'menu_name' => __('Ads VMAP', 'jws_streamvid'),
                'parent_item_colon' => __('Parent discussion', 'jws_streamvid'),
                'all_items' => __('Ads VMAP', 'jws_streamvid'),
                'view_item' => __('View discussions', 'jws_streamvid'),
                'add_new_item' => __('Add new question', 'jws_streamvid'),
                'add_new' => __('Add new', 'jws_streamvid'),
                'edit_item' => __('Edit discussion', 'jws_streamvid'),
                'update_item' => __('Update discussion', 'jws_streamvid'),
                'search_items' => __('Search discussion', 'jws_streamvid'),
                'not_found' => __('Not found', 'jws_streamvid'),
                'not_found_in_trash' => __('Not found in the bin', 'jws_streamvid'),
         );

         // Set other options for Custom Post Type

         $args = array(
                'label' => __('Ads VMAP', 'jws_streamvid'),
                'description' => __('Ads VMAP', 'jws_streamvid'),
                'labels' => $labels,
                // Features this CPT supports in Post Editor
                'supports' => array(
                    'title',
                    //'editor',
                    //'author',
                ),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu'	 => 'edit.php?post_type=advertising',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'menu_position' => 9,
                'can_export' => false,
                'has_archive' => false,
                'exclude_from_search' => true,
                'menu_icon' => 'dashicons-clipboard',
                'query_var' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
        );
        
        register_post_type( 'adsvmap', $args );
        
	}
    
    public function add_filter_column_videos($defaults) {

        $defaults['featured_image'] = esc_html__('Featured Image','seatevent');
        
        return $defaults;
        
    }
    
    public function show_filter_column_videos($column_name, $post_id) {

        if ($column_name == 'featured_image') {
            echo get_the_post_thumbnail($post_id, 'thumbnail'); 
        }

    }
    
    public function add_filter_column_movies($defaults) {

        $defaults['featured_image'] = esc_html__('Featured Image','seatevent');
        
        return $defaults;
        
    }
    
    public function show_filter_column_movies($column_name, $post_id) {

        if ($column_name == 'featured_image') {
            echo get_the_post_thumbnail($post_id, 'thumbnail'); 
        }

    }
    
    public function add_filter_column_tv_shows($defaults) {

        $defaults['featured_image'] = esc_html__('Featured Image','seatevent');
        
        return $defaults;
        
    }
    
    public function show_filter_column_tv_shows($column_name, $post_id) {

        if ($column_name == 'featured_image') {
            echo get_the_post_thumbnail($post_id, 'thumbnail'); 
        }

    }
    
    public function add_filter_column_episodes($defaults) {

        $defaults['featured_image'] = esc_html__('Featured Image','seatevent');
        
        return $defaults;
        
    }
    
    public function show_filter_column_episodes($column_name, $post_id) {

        if ($column_name == 'featured_image') {
            echo get_the_post_thumbnail($post_id, 'thumbnail'); 
        }

    }
    
    public function add_filter_column_person($defaults) {

        $defaults['featured_image'] = esc_html__('Featured Image','seatevent');
        
        return $defaults;
        
    }
    
    public function show_filter_column_person($column_name, $post_id) {

        if ($column_name == 'featured_image') {
            echo get_the_post_thumbnail($post_id, 'thumbnail'); 
        }

    }
}


add_action('admin_menu', 'jws_custom_episodes_playlist');

function jws_custom_episodes_playlist() {
    
     add_submenu_page(
        'edit.php?post_type=tv_shows',
        'Playlist',  
        'Playlist',     
        'manage_categories',   
        'edit-tags.php?taxonomy=episodes_playlist&post_type=tv_shows' 
    );
    
}