<?php
/**
* Template Name: Event
* Template URI: http://
* Author: Neil Murray
* Author URI: http://cf7skins.com
* Description:
* Instructions:
* Version: 1.0
* Version Date: 2015-08-28
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tags: featured, event
* Text Domain:  
**/
?>
<fieldset>
	<legend><?php _e( 'Event', 'cf7skins'); ?></legend>
	<ol>
		<li> <?php _e( 'Name', 'cf7skins'); ?> [text cf7s-name] </li>
		<li> <?php _e( 'Phone Number', 'cf7skins'); ?> [tel cf7s-phone] </li>
		<li> <?php _e( 'Email', 'cf7skins'); ?> [email* cf7s-email] </li>
		<li> <?php _e( 'Which workshops will you be attending?', 'cf7skins' ); ?> [checkbox cf7s-checkbox1 "<?php _e( 'Option 1', 'cf7skins' ); ?>" "<?php _e( 'Option 2', 'cf7skins' ); ?>" "<?php _e( 'Option 3', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'Are you an existing customer?', 'cf7skins' ); ?> [radio cf7s-radio1 "<?php _e( 'Yes', 'cf7skins' ); ?>" "<?php _e( 'No', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'How did find out about this event?', 'cf7skins'); ?> [select cf7s-select1 "<?php _e( 'Option 1', 'cf7skins' ); ?>" "<?php _e( 'Option 2', 'cf7skins' ); ?>" "<?php _e( 'Option 3', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'Comments or Questions', 'cf7skins'); ?> [textarea cf7s-comments] </li>
	</ol>
	[submit "<?php _e( 'Submit', 'cf7skins'); ?>"]
	<p>* <?php _e( 'Required', 'cf7skins' ); ?></p>
</fieldset>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tempus pharetra vehicula. Aliquam pellentesque mi non scelerisque placerat.</p>