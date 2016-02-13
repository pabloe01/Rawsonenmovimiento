<?php
/**
* Template Name: Fieldset - Basic 
* Template URI: http://
* Author: Neil Murray
* Author URI: http://cf7skins.com
* Description:
* Instructions:
* Version: 1.0
* Version Date: 2015-08-28
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tags: popular, featured, fieldset
* Text Domain:  
**/
?>
<fieldset>
	<legend><?php _e( 'Your Details', 'cf7skins'); ?></legend>
	<ol>
		<li> <?php _e( 'Name', 'cf7skins'); ?> [text cf7s-name] </li>
		<li> <?php _e( 'Email', 'cf7skins'); ?> [email* cf7s-email] </li>
		<li> <?php _e( 'Phone', 'cf7skins'); ?> [text cf7s-phone] </li>
		<li> <?php _e( 'Message', 'cf7skins' ); ?> [textarea cf7s-message] </li>
	</ol>
	[submit "<?php _e( 'Submit', 'cf7skins'); ?>"]
	<p>* <?php _e( 'Required', 'cf7skins' ); ?></p>
</fieldset>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tempus pharetra vehicula. Aliquam pellentesque mi non scelerisque placerat.</p>