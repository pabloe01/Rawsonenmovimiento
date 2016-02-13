<?php
/**
* Template Name: Default CF7 Form
* Template URI: http://
* Author: Neil Murray
* Author URI: http://cf7skins.com
* Description: The default Contact Form 7 form is a minimalistic contact form without any of the CF7 Skins structure applied. It is simple, straightforward, and will blend in with most plain pages.  <br><br> <strong>Note:</strong> This template is based around paragraph &lt;p&gt; elements as used in the default Contact Form 7 form. Other CF7 Skins templates use <a href="http://kb.cf7skins.com/why-we-use-fieldset-legend-lists-in-cf7-skins/">Fieldset, Legend & Lists</a>. <br><br> Included in this template are the following fields: <br>•	Name  <br>•	Email address <br>•	Subject <br>•	Message area that can be expanded for long messages<br>•	Submit button  <br><br> You can copy, add, remove and alter fields, text, titles and fieldsets and more to customize the form to suit your needs.
* Instructions:	

* Version: 1.0
* Version Date: 2015-08-28
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tags: 
* Text Domain:  
**/
?>
<p><?php _e( 'Your Name (required)', 'cf7skins'); ?><br />
    [text* your-name] </p>

<p><?php _e( 'Your Email (required)','cf7skins'); ?><br />
    [email* your-email] </p>

<p><?php _e( 'Subject', 'cf7skins'); ?><br />
    [text your-subject] </p>

<p><?php _e( 'Your Message','cf7skins'); ?><br />
    [textarea your-message] </p>

<p>[submit "<?php _e( 'Send','cf7skins'); ?>"]</p>