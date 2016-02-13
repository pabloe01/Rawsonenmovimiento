<?php
/**
 * Uninstall function
 * 
 * @package cf7Skins
 * @author Neil Murray
 * @since 0.0.1
 */

 
// Exit if this file is not called from WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// Check if user wants to retain plugin data
$option = get_option( 'cf7skins' );

if ( isset( $option['delete_data'] ) && $option['delete_data'] ) {

	// Delete plugin option
	delete_option('cf7skins');
	delete_option('cf7skins_version_installed');
	delete_option('cf7skins_activated');
	delete_option('cf7skins_get_version');
	delete_option('cf7skins_activation');

	// Delete all post meta by key.
	// This plugin uses post meta cf7s_style for selected style
	// and cf7s_template for selected template.
	delete_post_meta_by_key( 'cf7s_style' );
	delete_post_meta_by_key( 'cf7s_template' );
}
?>