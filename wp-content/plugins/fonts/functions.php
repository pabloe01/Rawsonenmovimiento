<?php 
/*
Plugin Name: Fonts
Plugin URI: http://wpsites.net/plugins/fonts/
Description: <a href="http://wpsites.net/wordpress-admin/add-google-web-fonts-to-your-wordpress-editor/">Add Google Fonts</a> | <a href="http://wpsites.net/wordpress-themes/add-custom-fonts-to-the-wordpress-editor/">Add custom fonts</a>
Version: 2.1
Author: Brad Dalton - wpsites
Author URI: http://wpsites.net/wordpress-admin/add-google-web-fonts-to-your-wordpress-editor/

License: GPL2
*/

function add_more_buttons($buttons) {
$buttons[] = 'fontselect';
$buttons[] = 'fontsizeselect';
return $buttons;
}

add_filter("mce_buttons_3", "add_more_buttons");

add_action('admin_notices', 'example_admin_notice');
function example_admin_notice() {
    global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
    if ( ! get_user_meta($user_id, 'example_ignore_notice') ) {
        echo '<div class="updated"><p>'; 
        printf(__('New to fonts plugin - Add your own unique selection of fonts!  <a href="http://wpsites.net/wordpress-admin/add-google-web-fonts-to-your-wordpress-editor/">Add Google Fonts</a> | <a href="http://wpsites.net/wordpress-themes/add-custom-fonts-to-the-wordpress-editor/">Add custom fonts</a> | <a href="%1$s">Hide Notice</a>'), '?example_nag_ignore=0');
        echo "</p></div>";
    }
}

add_action('admin_init', 'example_nag_ignore');
function example_nag_ignore() {
    global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['example_nag_ignore']) && '0' == $_GET['example_nag_ignore'] ) {
             add_user_meta($user_id, 'example_ignore_notice', 'true', true);
    }
}

