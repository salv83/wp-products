<?php
/**
 * This is the section responsible to create a new Post Type called, a taxonomy called Target Groups and a metabox "Stars" that
 * will be a filed of the new Post type
 */

add_action( 'init', 'wp_product_plugin_create_product' );

/**
 * register a new custom post type called "Product" with a taxonomy called Target Groups
 */
function wp_product_plugin_create_product() {
    register_post_type(
        'product',
        array(
            'labels' => array(
                'name'               => __('Product', 'wp-product'),
                'singular_name'      => __('Product', 'wp-product'),
                'add_new'            => __('Add New', 'wp-product'),
                'add_new_item'       => __('Add New product', 'wp-product'),
                'edit_item'          => __('Edit product', 'wp-product'),
                'new_item'           => __('New product', 'wp-product'),
                'all_items'          => __('All product', 'wp-product'),
                'view_item'          => __('View product', 'wp-product'),
                'search_items'       => __('Search product', 'wp-product'),
                'not_found'          => __('No product found', 'wp-product'),
                'not_found_in_trash' => __('No product found in Trash', 'wp-product'),
                'parent_item_colon'  => '',
                'menu_name'          => __('Product', 'wp-product')
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => array( 'title', 'thumbnail' )
        )
        );
    
        // create target groups
        register_taxonomy(
            'target_groups', array("product"), array(
                'hierarchical' => true,
                'show_admin_column' => true,
                'label' => __('Target Groups', 'wp-product'),
                'singular_label' => __('Target Groups', 'wp-product'),
                'rewrite' => array( 'slug' => 'target_groups'  )));
        register_taxonomy_for_object_type('target_groups', 'product');
}

/**
 * add a new metabox for the custom post type "Product" 
 */
function wp_product_plugin_add_box()
{
    add_meta_box(
        'wp_product_plugin_box_id',           // Unique ID
        'Stars',                              // Box title
        'wp_product_plugin_box_html',         // Content callback, must be of type callable
        'product',                            // Post type
        'normal',                             // The context within the screen where the boxes should display.
        'high'                                // The priority within the context where the boxes should show
        );
}
add_action('add_meta_boxes', 'wp_product_plugin_add_box');


/**
 * the metabox will be a select with 5 integer values, those integer values represents the rating of the products
 */
function wp_product_plugin_box_html($post)
{
    /* Check if there is any meta value stored in the database in order to set the selected option using the function selected() */
    $stars_field_value  = get_post_meta( $post->ID);
    $selected_element = $stars_field_value['_wp_product_plugin_meta_key'][0];
    
    ?>
    <label for="wp_product_plugin_field">Select the number of stars that will be used to sort the Products</label><br/><br/>
    <select name="wp_product_plugin_field" id="wp_product_plugin_field" class="postbox">
        <option value="" selected disabled hidden>Choose here</option>
        <option value="1" <?php selected($selected_element, '1'); ?>>1</option>
        <option value="2" <?php selected($selected_element, '2'); ?>>2</option>
        <option value="3" <?php selected($selected_element, '3'); ?>>3</option>
        <option value="4" <?php selected($selected_element, '4'); ?>>4</option>
        <option value="5" <?php selected($selected_element, '5'); ?>>5</option>
    </select>
    <?php
}

/**
 * this function store the values choosed by the user with the select to the database
 */
function wp_product_plugin_save_postdata($post_id)
{
    if (array_key_exists('wp_product_plugin_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_wp_product_plugin_meta_key',
            $_POST['wp_product_plugin_field']
            );
    }
}
add_action('save_post', 'wp_product_plugin_save_postdata');
