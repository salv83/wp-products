<?php

add_action('admin_menu', 'wp_product_settings_menu');

function wp_product_settings_menu() {
    add_menu_page('WP Product Settings', 'WP Product Settings', 'administrator', 'wpproductsettingpage', 'wp_product_settings_page' , 'dashicons-feedback');
    add_action( 'admin_init', 'register_wp_product_settings' );
}

function register_wp_product_settings() {
    register_setting( 'wp_product_settings-group', 'default-target-group' );

}

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