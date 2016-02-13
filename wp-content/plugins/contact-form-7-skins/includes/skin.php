<?php
/**
 * CF7 Skins - Skins Class
 * 
 * Base class for templates and styles
 *
 * @package cf7skins
 * @author Neil Murray
 * @since 0.0.1
 */
 
 
/**
 * Return if class already exists
 * 
 * @since 0.2.0
 */	
if ( class_exists( 'CF7_Skin' ) )
	return;

 
class CF7_Skin {
	
	// Class variables
	var $name, $version, $textdomain;	
	
	/**
     * Class constructor
	 * 
     * @since 0.0.1
     */	
    function __construct() {
		// Set class variables
		$this->name 	  = CF7SKINS_OPTIONS;
		$this->version 	  = CF7SKINS_VERSION;
		$this->textdomain = CF7SKINS_TEXTDOMAIN;
    }
	
	
	/**
     * Get post id in post edit screen
     * 
     * @since 0.0.1
     */	
	function get_id() {
		$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
		return $post_id;
    }
	
	
	/**
     * Get slug name for template or style based on directory name
	 * 
     * @param $skin current processed template or style reading data
     * @since 0.0.1
     */	
	function get_slug_name( $skin ) {
		echo str_replace(' ', '-', $skin['dir'] );
    }
	
	
	/**
     * Return the skin thumbnail image url
	 * 
     * @param $skin current processed template or style reading data
     * @since 0.1.0
     */	
	function get_skin_thumbnail( $skin ) {
		$imgpath = $skin['path'] . $skin['dir'] . '/thumbnail.png';
		
		// Check if thumbnail.png exists or load default thumbnail
		if( file_exists( $imgpath ) )
			$imgurl = $skin['url'] . $skin['dir'] . '/thumbnail.png';
		else
			$imgurl = CF7SKINS_URL . 'images/no-preview.png';
		
		return $imgurl;
    }
	
	
	/**
     * Return the skin modal image url, if does not exist thumbnail.png will be returned
	 * 
     * @parameter $skin is the current processed skin reading data
     * @since 0.1.0
     */	
	function get_skin_modal( $skin ) {
		$imgpath = $skin['path'] . $skin['dir'] . '/modal.png';
		
		// Check if modal.png exists
		if( file_exists( $imgpath ) )
			$imgurl = $skin['url'] . $skin['dir'] . '/modal.png';
		else
			$imgurl = $this->get_skin_thumbnail( $skin );
		
		return $imgurl;
    }

} // End class
