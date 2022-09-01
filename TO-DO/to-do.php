<?php
/*
  Plugin Name: TO DO
  Description: Display TO DO list.
  Version: 1
  Author: 
  Author URI: 
 */

if (!defined('ABSPATH')) {
    exit(); 
}

register_activation_hook(__FILE__, 'todo_install_table');

function todo_install_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . '_todo_list';
    $sql = "CREATE TABLE $table_name (
		      `id` int NOT NULL AUTO_INCREMENT,
		      `title` varchar(500) NOT NULL,
        `description` text NOT NULL,
        `user_id` int NOT NULL,
        `status` int NOT NULL,
		PRIMARY KEY  (`id`)
	);";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here
add_action( 'wp_footer', 'my_action_javascript' );
add_action( 'wp_ajax_remove_todo_item', 'remove_todo_item' );
add_action( 'wp_ajax_nopriv_change_status_todo', 'change_status_todo' );

function my_action_javascript(){
  wp_enqueue_script( 'jquery' );
  wp_register_script( 'to-do-custom', plugin_dir_url( __FILE__ ).'assets/js/to-do.js' );
  wp_enqueue_script( 'to-do-custom' );  
}

function remove_todo_item(){
 global $wpdb;
 $table_name = $wpdb->prefix . '_todo_list';
 if(isset($_POST['todo_id'])){
  $wpdb->delete($table_name,array('id'=>$_POST['todo_id']));
  die();
 }
}

function change_status_todo(){
 global $wpdb;
 $table_name = $wpdb->prefix . '_todo_list';
 if(isset($_POST['todo_id'])){
  $wpdb->update($table_name,
   array('status'=>1),
   array('id'=>$_POST['todo_id'])
  );
  die();
 }
}

include( plugin_dir_path( __FILE__ ) . 'includes/admin.php');
include( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php');
