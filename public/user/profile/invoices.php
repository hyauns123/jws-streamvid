<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
if(!jws_streamvid()->get()->profile->_profile_is_owner()) return false;
 
require_once( PMPRO_DIR . '/preheaders/invoice.php' );

ob_start();

get_template_part( 'paid-memberships-pro/pages/invoice' );

$output = ob_get_clean();


echo $output;