<?php

if( ! defined('ABSPATH' ) ){
    exit;
}
global $wp_query;
$request = get_query_var( 'dashboard' );
$request = explode( '/' , $request );


$shop_menu = jws_streamvid()->get()->profile->get_menu_dashboard_items();
$shop_menu =  $shop_menu['dashboard']['submenu']['shop']['submenu'];


if(isset($_GET['shop'])) {
    
    $current = $_GET['shop'];
    
} else {
    
    $current = 'orders';
    
}
echo '<div class="woocommerce row">';
if(isset($shop_menu)) {
   
    echo '<nav class="woocommerce-MyAccount-navigation"><ul>';
    
    $current = isset($request[1]) ? $request[1] : 'orders';
    
    foreach($shop_menu as $key => $menu) {

        $url = jws_streamvid()->get()->profile->get_url($key, 'dashboard/shop');
        
        $active = $current == $key ? 'is-active' : '';
        
        echo '<li class="'.$active.'"><a href="'.esc_url($url).'">'.$menu['title'].'</a></li>';
     
    }
    echo '</ul></nav>';
    
    echo '<div class="woocommerce-MyAccount-content">';
    jws_streamvid_load_template( 'user/profile/woocommerce/'.$current.'.php' );
    echo '</div>';
    
}
echo '</div>';
