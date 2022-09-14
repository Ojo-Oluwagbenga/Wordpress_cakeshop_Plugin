<?php
/*
Plugin Name: Cake Shop
Plugin URI: http://localhost:8080
Description: Manages the CRUD operations with the cake shop(CodeIgniter Endpoints) third party
Version: 1.0.0
Author: Ojo Oluwagbenga John
Author URI: http://localhost:8080
*/

// Exit if accessed directly
if(!defined('ABSPATH')){
  exit;
}

// Load Scripts

$myfile = fopen(plugin_dir_path(__FILE__) . "\includes\apikey.txt", "r") or die("Unable to open file!");
$key = fread($myfile,filesize(plugin_dir_path(__FILE__) . "\includes\apikey.txt"));
fclose($myfile);

$right = false;

$response = wp_remote_post('http://localhost:8080/users/validateapi', array(
    'method'      => 'POST',
    'blocking'    => true,
    'headers'     => array(),
    'body'        => array(
      'apikey' => $key,
    )
  ));
  
if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
} else {
    $data =  json_decode(wp_remote_retrieve_body($response));
    $key = $data->apikey;
    
    if ($data->message == 'ok'){
        $right = true;
        $key =  $data->apikey;
    }
}

if (!$right){
    $myfile = fopen(plugin_dir_path(__FILE__) . "\includes\apikey.txt", "w") or die("Unable to open file!");
    fwrite($myfile, '0');
    fclose($myfile);
}else{
    add_action('wp_enqueue_scripts', 'cks_add_scripts');
}



add_action( 'admin_menu', 'wporg_options_page' );
function wporg_options_page() {  
    add_menu_page(
        'Cake Shop',
        'Cake Owner Dashboard',
        'manage_options',
        plugin_dir_path(__FILE__) . 'panelview.php',
        null,
        'dashicons-admin-plugins',
        20
    );
}

function plugin_auth(){
  if (isset($_REQUEST)){
    $apikey='';

    if ($_REQUEST['type'] == 'api'){
      $response = wp_remote_post('http://localhost:8080/users/validateapi', array(
        'method'      => 'POST',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
          'apikey' => $_REQUEST['apikey'],
        )
      ));
      
      if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        die ("Something went wrong:". $error_message);
      } else {
        $data =  json_decode(wp_remote_retrieve_body($response));
        if ($data->message != 'ok'){
          die(json_encode(['error'=>'Could not authourize']));
        }
        $apikey = $data->apikey;
      }
      
    }

    if ($_REQUEST['type'] == 'cred'){
      $response = wp_remote_post('http://localhost:8080/users/validate', array(
        'method'      => 'POST',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
          'username' => $_REQUEST['username'],
          'password' => $_REQUEST['password'],
        )
      ));
      
      if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        die ("Something went wrong:". $error_message);
      } else {
        $data =  json_decode(wp_remote_retrieve_body($response));
        if ($data->message != 'ok'){
          die(json_encode(['error'=>'Could not authourize']));
        }
        $apikey = $data->apikey;
      }
    }

    $myfile = fopen(plugin_dir_path(__FILE__) . "\includes\apikey.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $apikey);
    fclose($myfile);
    add_action('wp_enqueue_scripts', 'cks_add_scripts');

    die('Key authenticated successfully');

  }
  exit();
  
}
add_action('wp_ajax_plugin_auth', 'plugin_auth');


function model_auth(){
  if (isset($_REQUEST)){
    $apikey='';

    if ($_REQUEST['method'] == 'get'){
      die(wp_remote_retrieve_body(wp_remote_get($_REQUEST['ref_url'])));
    }

    if ($_REQUEST['method'] == 'post'){
      $response = wp_remote_post($_REQUEST['ref_url'], array(
        'method'      => 'POST',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => $_REQUEST['data']
      ));
      
      if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        die ("Something went wrong:". $error_message);
      } else {
        die(wp_remote_retrieve_body($response));
      }

      
    }


  }
  exit();
  
}
add_action('wp_ajax_model_auth', 'model_auth');

function cks_add_scripts(){
  // Add Main CSS
  wp_enqueue_style('cks-main-style', plugins_url(). '/cake-shop/css/style.css');
  // Add Main JS
  wp_enqueue_script('cks-main-script', plugins_url(). '/cake-shop/js/model.js');
}
