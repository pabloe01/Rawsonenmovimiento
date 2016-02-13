<?php
/**
 *
 * This program is a free software; you can use it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   	Customizr
 * @subpackage 	functions
 * @since     	1.0
 * @author    	Nicolas GUILLAUME <nicolas@presscustomizr.com>
 * @copyright 	Copyright (c) 2013-2015, Nicolas GUILLAUME
 * @link      	http://presscustomizr.com/customizr
 * @license   	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
/**
 * This is where Customizr starts. This file defines and loads the theme's components :
 * => Constants : CUSTOMIZR_VER, TC_BASE, TC_BASE_CHILD, TC_BASE_URL, TC_BASE_URL_CHILD, THEMENAME, TC_WEBSITE
 * => Default filtered values : images sizes, skins, featured pages, social networks, widgets, post list layout
 * => Text Domain
 * => Theme supports : editor style, automatic-feed-links, post formats, navigation menu, post-thumbnails, retina support
 * => Plugins compatibility : JetPack, bbPress, qTranslate, WooCommerce and more to come
 * => Default filtered options for the customizer
 * => Customizr theme's hooks API : front end components are rendered with action and filter hooks
 *
 * The method TC__::tc__() loads the php files and instanciates all theme's classes.
 * All classes files (except the class__.php file which loads the other) are named with the following convention : class-[group]-[class_name].php
 *
 * The theme is entirely built on an extensible filter and action hooks API, which makes customizations easy and safe, without ever needing to modify the core structure.
 * Customizr's code acts like a collection of plugins that can be enabled, disabled or extended.
 *
 * If you're not familiar with the WordPress hooks concept, you might want to read those guides :
 * http://docs.presscustomizr.com/article/26-wordpress-actions-filters-and-hooks-a-guide-for-non-developers
 * https://codex.wordpress.org/Plugin_API
 */
//Fire Customizr
require_once( get_template_directory() . '/inc/init.php' );

/**
 * THE BEST AND SAFEST WAY TO EXTEND THE CUSTOMIZR THEME WITH YOUR OWN CUSTOM CODE IS TO CREATE A CHILD THEME.
 * You can add code here but it will be lost on upgrade. If you use a child theme, you are safe!
 *
 * Don't know what a child theme is ? Then you really want to spend 5 minutes learning how to use child themes in WordPress, you won't regret it :) !
 * https://codex.wordpress.org/Child_Themes
 *
 * More informations about how to create a child theme with Customizr : http://docs.presscustomizr.com/article/24-creating-a-child-theme-for-customizr/
 * A good starting point to customize the Customizr theme : http://docs.presscustomizr.com/article/35-how-to-customize-the-customizr-wordpress-theme/
 */
add_action('wp_enqueue_scripts', 'my_google_font');

function my_google_font() {
    wp_enqueue_style($handle = 'my-google-font', $src = 'https://fonts.googleapis.com/css?family=Andada', $deps = array(), $ver = null, $media = null);
}

// Add some text after the header
add_action('__navbar', 'add_promotional_text');

function add_promotional_text() {
    // If we're not on the home page, do nothing
//    echo "<h2 class='span7 inside site-description'>"
//    . get_bloginfo('description')
//    . "</h2>";
    // Echo the html
    //echo "<div class='span7 inside site-description'>Rawson en movimiento es un espacio destinando a la presentación y ejecución de proyectos de y para el pueblo a través de una Pag. Web</div>";
}

add_action('__after_content', 'my_script_imageMapResizer');

function my_script_imageMapResizer() {
    ?>
    <div id="idialog" title="" style="display: none;">
        <img id="image_popup" src=""/>
    </div> 
    <div id="info"> 
    </div>
    <?php
    wp_enqueue_script('Script_imageMapResizer');
    wp_enqueue_script('Script_ZRawsMov');

    wp_localize_script(
            'Script_ZRawsMov', 'ajax_script', array('ajaxurl' => admin_url('admin-ajax.php'), 'url' => get_bloginfo('siteurl', 'display')));
}

wp_register_script('Script_imageMapResizer', '/wp-content/themes/RawsonMovimiento/inc/assets/js/imageMapResizer.min.js', '', '1.0', false);
wp_register_script('Script_ZRawsMov', '/wp-content/themes/RawsonMovimiento/inc/assets/js/zrmovjs.js', '', '1.168', true);

wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_style('plugin_name-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, PLUGIN_VERSION, false);


// CArgar post en Div
add_action('wp_ajax_cargaContenido', 'cargaContenido');
add_action('wp_ajax_nopriv_cargaContenido', 'cargaContenido');

function cargaContenido() {
    global $post;
    if (isset($_GET["category"])) {

        $id_category = get_term_by('name', $_GET["category"], 'category');
        $args = array(
            'category' => $id_category->term_id,
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $my_posts = get_posts($args);
        if ($my_posts) :
            foreach ($my_posts as $post) :
                setup_postdata($post);
                //the_title();
                the_content();
            endforeach;
            wp_reset_postdata();
        endif;
        //
    }
    die();
}

