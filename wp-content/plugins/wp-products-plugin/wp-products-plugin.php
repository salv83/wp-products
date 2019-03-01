<?php
/**
 * Plugin Name: WP Products Plugin
 * Description: This plugin manages the creation of a new custom post type "Product", the widget "Product List", the taxonomy "Target Groups" and the setting for "Default Target Group"
 * Author: Salvatore Marino
 * Version: 1.0.0
 */

/**
 * The main logic of the plugin is divided in 3 filed, posttypes.php will manage the new post type with its fields and taxonomy
 * WP_Product_List_Widget.php is the class implements the widget that displays the products, and settings-page.php manage the custom
 * wordpress setting page where will be possible to set the default target group
 */
include_once( dirname( __FILE__ ) . '/include/posttypes.php' );
include_once( dirname( __FILE__ ) . '/include/WP_Product_List_Widget.php' );
include_once( dirname( __FILE__ ) . '/include/settings-page.php' );


/**
 * This is function will enqueue the Fontawesome framework, that will be used to style the display of the rating stars under the product
 */
function wp_products_enque_fontawesome_style() {
    wp_enqueue_style( 'wp-products-fontawesome', plugins_url('/include/fontawesome/css/all.css', __FILE__));
}

add_action( 'wp_enqueue_scripts', 'wp_products_enque_fontawesome_style' );