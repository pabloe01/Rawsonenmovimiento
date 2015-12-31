<?php

/*
  Plugin Name: URL Redirect
  Plugin URI: http://www.ninjapress.net/url-redirect/
  Description: Url Redirect permits to create unlimited redirections.
  Version: 1.3
  Author: Ninja Press
  Author URI: http://www.ninjapress.net
  License: GPL2
 * 
 */

if (!class_exists('URL_Redirect')) {

   class URL_Redirect {

      /**
       * Construct the plugin object
       */
      public function __construct() {
         // register actions
         add_action('admin_menu', array(&$this, 'add_menu'));
         add_action('admin_init', array(&$this, 'admin_init'));

         add_action('init', array(&$this, 'url_redirect_init'));
      }

      /**
       * Activate the plugin
       */
      public static function activate() {
         add_option('url_redirect_map', array());
      }

      /**
       * Deactivate the plugin
       */
      public static function deactivate() {
         // Do nothing
      }

      /**
       * hook into WP's admin_init action hook
       */
      public function admin_init() {
         // Set up the settings for this plugin
         // register the settings for this plugin
         register_setting('url_redirect_option', 'url_redirect_map');

         if (isset($_POST['url_redirect_delete'])) {
            $map = get_option('url_redirect_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $_POST['url_redirect_delete']) {
                  unset($map[$key]);
               }
            }

            update_option('url_redirect_map', $map);
         }

         if (isset($_POST['url_redirect_reset'])) {
            $map = get_option('url_redirect_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $_POST['url_redirect_reset']) {
                  $value['click'] = 0;

                  $map[$key] = $value;
               }
            }

            update_option('url_redirect_map', $map);
         }

         if (
                 isset($_POST['url_redirect_name']) and
                 isset($_POST['url_redirect_link']) and
                 $_POST['url_redirect_name'] != '' and
                 $_POST['url_redirect_link'] != ''
         ) {

            $name = $_POST['url_redirect_name'];
            $link = esc_url_raw($_POST['url_redirect_link'], 'http');
            $save = TRUE;

            $map = get_option('url_redirect_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $name) {
                  $value['link'] = $link;

                  $map[$key] = $value;

                  $save = FALSE;
               }
            }

            if ($save) {
               $map[] = array(
                   'name' => $name,
                   'link' => $link,
                   'click' => 0
               );
            }

            update_option('url_redirect_map', $map);
         }
      }

      /**
       * add a menu
       */
      public function add_menu() {
         add_management_page("URL Redirect", "URL Redirect", "manage_categories", 'wp_url_redirect', array(&$this, 'url_redirect_settings_page'));
      }

      public
              function url_redirect_settings_page() {
         if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
         }

         $map = get_option('url_redirect_map', array());

         wp_enqueue_script('', plugins_url('js/admin.js', __FILE__), array('jquery'), time(), true);

         // Render the settings template
         include(sprintf("%s/templates/tools.php", dirname(__FILE__)));
      }

      function url_redirect_init() {
         $name = substr($_SERVER["REQUEST_URI"], 1);

         $map = get_option('url_redirect_map', array());

         foreach ($map as $key => $value) {
            if ($value['name'] == $name) {
               $value['click'] ++;
               $map[$key] = $value;

               update_option('url_redirect_map', $map);

               wp_redirect($value['link']);
               exit;
            }
         }
      }

   }

}

if (class_exists('URL_Redirect')) {
   // Installation and uninstallation hooks
   register_activation_hook(__FILE__, array('URL_Redirect', 'activate'));
   register_deactivation_hook(__FILE__, array('URL_Redirect', 'deactivate'));

   // instantiate the plugin class
   $wp_footer_pop_up_banner = new URL_Redirect();

   if (isset($wp_footer_pop_up_banner)) {

      // Add the settings link to the plugins page
      function url_redirect_settings_link($links) {
         $settings_link = '<a href="tools.php?page=wp_url_redirect">Settings</a>';
         array_unshift($links, $settings_link);
         return $links;
      }

      $plugin = plugin_basename(__FILE__);
      add_filter("plugin_action_links_$plugin", 'url_redirect_settings_link');
   }
}   