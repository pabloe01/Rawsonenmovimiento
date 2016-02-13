<?php
/**
* Template Name: Suggestion
* Template URI: http://
* Author: Neil Murray
* Author URI: http://cf7skins.com
* Description:
* Instructions:
* Version: 1.0
* Version Date: 2015-08-28
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tags: 
* Text Domain:  
**/
?>
<fieldset>
	<legend><?php _e( 'Suggestion Form', 'cf7skins'); ?></legend>
	<p><strong><?php _e( 'Please let us know what you think.', 'cf7skins'); ?></strong></p>
	<ol>
		<li> <?php _e( 'In which of the following areas do you have a suggestion?', 'cf7skins'); ?> [select cf7s-select1 multiple"<?php _e( 'Area 1', 'cf7skins' ); ?>" "<?php _e( 'Area 2', 'cf7skins' ); ?>" "<?php _e( 'Area 3', 'cf7skins' ); ?>" "<?php _e( 'Area 4', 'cf7skins' ); ?>"] </li>
		<p><?php _e( 'Note: You can select multiple items (Use Shift or Ctrl/Cmd + Click)', 'cf7skins'); ?></p>
		<li> <?php _e( 'Suggestion', 'cf7skins'); ?> [text cf7s-suggestion] </li>
		<li> <?php _e( 'Details', 'cf7skins'); ?> [textarea cf7s-details] </li>
		<li> <?php _e( 'Your Email - please enter your email if you would like us to follow up with you.', 'cf7skins'); ?> [email cf7s-email] </li>
		<li> <?php _e( 'Radio buttons', 'cf7skins'); ?> [radio cf7s-radio1 "<?php _e( 'Option 1', 'cf7skins' ); ?>" "<?php _e( 'Option 2', 'cf7skins' ); ?>" "<?php _e( 'Option 3', 'cf7skins' ); ?>"] </li>
		<li> <?php _e( 'Checkboxes', 'cf7skins'); ?> [checkbox cf7s-checkbox1 "<?php _e( 'Option 1', 'cf7skins' ); ?>" "<?php _e( 'Option 2', 'cf7skins' ); ?>" "<?php _e( 'Option 3', 'cf7skins' ); ?>"] </li>
	</ol>
	[submit "<?php _e( 'Submit', 'cf7skins'); ?>"]
</fieldset>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tempus pharetra vehicula. Aliquam pellentesque mi non scelerisque placerat.</p>