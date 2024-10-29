<?php
   /*
   Plugin Name: AB Post View Counter
   Plugin URI: http://aleksandar.bjelosevic.info/abpvc
   Description: Plugin that count post/page views 
   Version: 1.14
   Author: Aleksandar Bjelosevic
   Author URI: http://aleksandar.bjelosevic.info
   License: GPL2
   */

function ab_postview_counter($content)
{

  global $post;
   $current_views = get_post_meta($post->ID, "ab_postview_views", true);
   if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
      $current_views = 0;
      if (get_option('ab-post-radio')==1)
      {
      $content .= "Read count: ".$current_views;
      }
     
   }
   else
   {
    if (get_option('ab-post-radio')==1)
      {
      $content .= "Read count: ".$current_views;
      }
   }

return $content;


}

function abpost_add_view() {
      global $post;
      $current_views = get_post_meta($post->ID, "ab_postview_views", true);
      if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
         $current_views = 0;
      }
      $new_views = $current_views + 1;
      update_post_meta($post->ID, "ab_postview_views", $new_views);
      return $new_views;

}

//Post counter in all post
// ADD NEW COLUMN
function ABPostCount_columns_head($defaults) {
    $defaults['abpost_view_counter'] = 'Post View Counter';
    return $defaults;
}


// SHOW THE POST COUNTER ON POST 
function ABPostCount_columns_content($column_name, $post_ID) {
global $post;
    if ($column_name == 'abpost_view_counter') {
        
        $current_views = get_post_meta($post->ID, "ab_postview_views", true);
        if($current_views =="") {$current_views=0;}
        echo $current_views;
        
    }
}

//end post counter

function abpostview_settings()
{
    add_settings_section("section", "Settings", null, "abpvc");
    add_settings_field("ab-post-radio", "Show counter after post?", "abpostview_radio_display", "abpvc", "section");  
    register_setting("section", "ab-post-radio");
}

function abpostview_radio_display()
{
   ?>
        <input type="radio" name="ab-post-radio" value="1" <?php checked(1, get_option('ab-post-radio'), true); ?>>Yes
        <input type="radio" name="ab-post-radio" value="2" <?php checked(2, get_option('ab-post-radio'), true); ?>>No
   <?php
}



function abpostview_page()
{
  ?>
      <div class="wrap">
         <h1>Post View Counter</h1>
  
         <form method="post" action="options.php">
            <?php
               settings_fields("section");
  
               do_settings_sections("abpvc");
                 
               submit_button(); 
            ?>
         </form>
      </div>
   <?php
}

function abpostview_menu_item()
{
  add_submenu_page("options-general.php", "AB Post View Counter", "AB Post View Counter", "manage_options", "abpvc", "abpostview_page"); 
}


add_action("admin_init", "abpostview_settings");
add_action("admin_menu", "abpostview_menu_item");
add_action("wp_head", "abpost_add_view");
add_filter("the_content", "ab_postview_counter" );
add_filter('manage_pages_columns', 'ABPostCount_columns_head');
add_filter('manage_posts_columns', 'ABPostCount_columns_head');
add_action('manage_posts_custom_column', 'ABPostCount_columns_content', 10, 2);
add_action('manage_pages_custom_column', 'ABPostCount_columns_content', 10, 2);
?>