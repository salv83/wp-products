<?php
/**
 * This is the section responsible to create the setting page where will be possible to specify the default target group
 * that the widget will display
 */
add_action('admin_menu', 'wp_product_settings_menu');


/**
 * With this function we specify that we want to create a new administrator menu item that will link to our setting page
 */
function wp_product_settings_menu() {
    add_menu_page('WP Product Settings', 'WP Product Settings', 'administrator', 'wpproductsettingpage', 'wp_product_settings_page' , 'dashicons-feedback');
    add_action( 'admin_init', 'register_wp_product_settings' );
}

/**
 * Registration of the group that will contain the options of this setting page
 */
function register_wp_product_settings() {
    register_setting( 'wp_product_settings-group', 'default-target-group' );

}

/**
 * This function builds the setting page. As first thing it try to get all the terms for the taxonomy Target Group,
 * then it build a select whose options will be the name of the term and as value the id of the term  
 */
function wp_product_settings_page() {
    ?>
<div class="wrap">
<h1>WP Product Settings</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wp_product_settings-group' ); ?>
    <?php do_settings_sections( 'wp_product_settings-group' );
    
    $default_target_group = get_option('default-target-group') ;
    

    $terms = get_terms([
        'taxonomy' => 'target_groups',
        'hide_empty' => false,
    ]);

    ?>
    <table class="form-table">
              <tr valign="top">
				<th scope="row">Select the default "Target Group" from the items available in that taxonomy.</th>
				<td>
				<select name="default-target-group">
                            <?php
                                foreach($terms as $term){
                                    echo '<option value="' . $term->term_id . '" ' . selected( $term->term_id, $default_target_group ) . '>' . $term->name . '</option>';
                                }
                            ?>
                </select>
				</td>
			</tr>		
	</table>    
	
    <?php submit_button(); ?>

</form>
</div>
<?php }  