<?php
/**
* Template Name: Fieldset - Multiple
* Template URI: http://
* Author: Neil Murray
* Author URI: http://cf7skins.com
* Description:
* Instructions:
* Version: 1.0
* Version Date: 2015-08-28
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tags: fieldset
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
</fieldset>
<p>Use paragraphs for text that is not a form field.</p>
<fieldset>
	<legend><?php _e( 'Your Requirements', 'cf7skins'); ?></legend>
	<ol>
		<li> <?php _e( 'Checkboxes', 'cf7skins'); ?> [checkbox cf7s-checkbox-01 "<?php _e( 'Option 1', 'cf7skins' ); ?>" "<?php _e( 'Option 2', 'cf7skins' ); ?>" "<?php _e( 'Option 3', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'Radio Buttons', 'cf7skins'); ?> [radio cf7s-radio-01 "<?php _e( 'Yes', 'cf7skins' ); ?>" "<?php _e( 'No', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'Dropdown Select', 'cf7skins'); ?> [select cf7s-select-01 "<?php _e( 'Item 1', 'cf7skins' ); ?>" "<?php _e( 'Item 2', 'cf7skins' ); ?>" "<?php _e( 'Item 3', 'cf7skins' ); ?>"] </li>
	</ol>
</fieldset>
[submit "<?php _e( 'Submit', 'cf7skins'); ?>"]
<p>* <?php _e( 'Required', 'cf7skins' ); ?></p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tempus pharetra vehicula. Aliquam pellentesque mi non scelerisque placerat.</p>