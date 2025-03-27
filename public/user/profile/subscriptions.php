<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
if(!jws_streamvid()->get()->profile->_profile_is_owner()) return false;

get_template_part( 'paid-memberships-pro/shortcodes/pmpro_account' );

echo my_pmpro_shortcode_account( array(
            'sections'  =>  'membership'
) );
