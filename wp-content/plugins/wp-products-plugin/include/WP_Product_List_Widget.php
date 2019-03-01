<?php
class WP_Product_List_Widget extends WP_Widget {
    public function __construct() {
        $widget_options = array(
            'classname' => 'wp_product_list_widget',
            'description' => 'The widget lists the first 5 items of the "Product" post type sorted by the number of stars descending.',
        );
        parent::__construct( 'wp_product_list_widget', 'WP Product List', $widget_options );
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
      </p><?php 
    }
    
    public function widget( $args, $instance ) {
      $title = apply_filters( 'widget_title', $instance[ 'title' ] );
      echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>

      <?php 
      $requested_target_group = $_GET['target'];
      $default_target_group = get_option('default-target-group') ;
      $current_default_term = get_term( $default_target_group, 'target_groups' );
      $current_default_term_slug = $current_default_term->slug;             
      if(isset($default_target_group)&&!empty($default_target_group)&&term_exists( $current_default_term_slug, 'target_groups' )){
          if(isset($requested_target_group)&&!empty($requested_target_group)&&term_exists( $requested_target_group, 'target_groups' )){
              
             /*
              * In this case a target group is given in the url parameters and the given target group exists, we store
              * in a session the giver target group that will be reused when no target group is given or it doesn’t exist 
             */
              $_SESSION['target'] = $requested_target_group;
              /*
               * When the page is called with a target group parameter the widget only show product items which are connected to the given target group.
              */
              $loop = new WP_Query( array(
                  'post_type' => 'Product',
                  'tax_query' => array(
                      array (
                          'taxonomy' => 'target_groups',
                          'field' => 'slug',
                          'terms' => $requested_target_group,
                      )
                  ),
                  'posts_per_page' => 5,                         
                  'order' , 'DESC',                             
                  'orderby' => 'meta_value',                     
                  'meta_key' => '_wp_product_plugin_meta_key'
                  )
              );
          }else{
              /*
               * In this case no target parameter was passed or the target passed does not exist, first we check if there
               * is any target group in the session, if it exists we used the stored one in the session, if not we use
               * the default target group
               */
              $targetGroupDefaultOrStored = "";
              if(isset($_SESSION['target'])&&!empty($_SESSION['target'])){
                  $targetGroupDefaultOrStored = $_SESSION['target'];
              }else{
                  $targetGroupDefaultOrStored = $current_default_term_slug;
              }
              $loop = new WP_Query( array(
                  'post_type' => 'Product',
                  'tax_query' => array(
                      array (
                          'taxonomy' => 'target_groups',
                          'field' => 'slug',
                          'terms' => $targetGroupDefaultOrStored,
                      )
                  ),
                  'posts_per_page' => 5,                         
                  'order' , 'DESC',                              
                  'orderby' => 'meta_value',                   
                  'meta_key' => '_wp_product_plugin_meta_key'
              )
            );
          }
      }else{
          /*
           * When the plugin is activated and noone has selected a default target group in the setting page, the
           * default behaviour of the widget is to display the first five product items with the highest rating 
           */
          $loop = new WP_Query( array(
              'post_type' => 'Product',
              'posts_per_page' => 5,                         //  display only the first 5 posts
              'order' , 'DESC',                              //  set the descending order
              'orderby' => 'meta_value',                     //  the order is specified using the meta_value containing the number of stars
              'meta_key' => '_wp_product_plugin_meta_key'
            )
          );
      }
      display_post($loop);
      wp_reset_query();   
      echo $args['after_widget'];
    }
    

}

function display_post($loop){
    while ( $loop->have_posts() ) : $loop->the_post();
    $current_post_id = get_the_ID();
    $current_post_meta = get_post_meta($current_post_id);
    $current_product_stars = $current_post_meta['_wp_product_plugin_meta_key'][0];
    
    
    if ( has_post_thumbnail() ) { ?>
      <div class="wp-product-thumbnail">
         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
      </div>
      <?php } ?>
      <div class="wp-product-rating">
         <?php  
         echo("<h4>");
         for($i=0;$i<5;$i++){
             if($i<$current_product_stars){
                 echo "<i class='fas fa-star'></i>";
             }else{
                 echo "<i class='far fa-star'></i>";
             }
         }
         echo("</h4>");
         ?>
      </div>
      
      <div class="wp-product-title">
        <p><?php echo get_the_title(); ?></p>
      </div>
      <hr>
      <?php endwhile; 
}

function register_wp_product_list_widget() {
    register_widget( 'WP_Product_List_Widget' );
}
add_action( 'widgets_init', 'register_wp_product_list_widget' );

function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session');