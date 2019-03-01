<?php
/**
 * Plugin Name: WP Products Plugin
 * Description: This plugin manages the creation of a new custom post type "Product", the widget "Product List", the taxonomy "Target Groups" and the setting for "Default Target Group"
 * Author: Salvatore Marino
 * Version: 1.0.0
 */

include_once( dirname( __FILE__ ) . '/include/posttypes.php' );
include_once( dirname( __FILE__ ) . '/include/WP_Product_List_Widget.php' );
include_once( dirname( __FILE__ ) . '/include/settings-page.php' );

function wp_products_enque_fontawesome_style() {
    wp_enqueue_style( 'wp-products-fontawesome', plugins_url('/include/fontawesome/css/all.css', __FILE__));
}

add_action( 'wp_enqueue_scripts', 'wp_products_enque_fontawesome_style' );