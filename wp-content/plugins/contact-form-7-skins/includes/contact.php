<?php
/**
 * Contact Form Class
 * 
 * Implement functionality where CF7 Skins interacts with CF7
 * 
 * @package cf7skins
 * @author Neil Murray
 * @since 0.0.1
 */ 

 
class CF7_Skins_Contact {
	
	/**
     * Class constructor
	 * 
     * @uses http://codex.wordpress.org/Function_Reference/add_action
     * @uses http://codex.wordpress.org/Function_Reference/add_filter
     * @since 0.0.1
     */	
    function __construct() {
		add_filter( 'wpcf7_form_class_attr', array( &$this, 'form_class_attr' ) );	
		add_action( 'wpcf7_contact_form', array( &$this, 'contact_form' ), 1 );
		add_action( 'wp_head', array( $this, 'print_custom' ) );		
		add_action( 'cf7skins_enqueue_styles', array( $this, 'skins_style' ), 9, 1 );	// Priority set to 9	
    }
	
	
	/**
	 * Parse the CF7 shortcode ID in single or nested shortcodes
     * @return (array) of CF7 id(s)
	 * @since 1.0.2
     */			
	function parse_shortcode_id( $content ) {
		$tag = 'contact-form-7';
		
		// Return if there is no CF7 shortcode in post content
		if ( ! has_shortcode( $content , $tag ) )
			return false;
		
		// Get all the CF7 form shortcodes in the post content
		// Use similar approach as wp-includes\shortcodes.php has_shortcode() function
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		
		if ( empty( $matches ) )
			return false;

		// Loop through shortcodes, parse shortcode attributes and get the CF7 form ID
		foreach ( $matches as $shortcode ) {
			
			if ( $tag === $shortcode[2] ) {			
				$atts = shortcode_parse_atts( $shortcode[3] );
				$ids[] = $atts['id']; // Add the CF7 form ID
			
			// Nested shortcode
			} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) { // nested shortcodes
				$shortcode = $this->parse_shortcode_id( $shortcode[5] );
			}
			
		}

		// Return all the CF7 form ID
		if ( isset( $ids ) )
			return $ids;
	}
	
	
	/**
     * Get contact form 7 id
	 * 
     * Back compat for CF7 3.9 
	 * @see http://contactform7.com/2014/07/02/contact-form-7-39-beta/
	 * 
     * @param $cf7 Contact Form 7 object
     * @since 0.1.0
     */		
	function get_form_id( $cf7 ) {
		if ( version_compare( WPCF7_VERSION, '3.9-alpha', '>' ) ) {
			return $cf7->id();
		}
		return $cf7->id;
	}
	
	
	/**
     * Get CF7 form content 
	 * 
     * Back compat for CF7 3.9
	 * 
     * @param $cf7 Contact Form 7 object
     * @since 0.1.0
     */		
	function get_form_content( $cf7 ) {
		if ( version_compare( WPCF7_VERSION, '3.9-alpha', '>' ) ) {
			return $cf7->prop( 'form' );
		}
		return $cf7->form;
	}
	
	
	/**
	 * Enqueue cf7s-framework + selected skin style .css file to the frontend. 
	 * 	 
	 * @var $style - The CF7 Skins style slug name applied to current CF7 form
     * @since 1.1
     */		
	function enqueue_style( $style ) {		
		wp_enqueue_style( "cf7s-framework-normalize", 
			CF7SKINS_URL . 'css/framework/cf7s-normalize.css', 
			array('contact-form-7'), CF7SKINS_VERSION, 'all' );
		
		wp_enqueue_style( "cf7s-framework-default", 
			CF7SKINS_URL . 'css/framework/cf7s-default.css', 
			array('contact-form-7'), CF7SKINS_VERSION, 'all' );
			
		/**
		 * Filter hook action enqueue styles after CF7 Skins Framework
		 * or before selected skin style
		 * @since 1.1.1
		 */				
		do_action( 'cf7skins_enqueue_styles', $style );
	}	
	
	
	/**
	 * Enqueue cf7s-framework + selected skin style .css file to the frontend. 
	 * 	 
	 * @var $style - cf7skins style slug name applied to current CF7 form
     * @since 1.1
     */		
	function skins_style( $style ) {		
		$skins = CF7_Skin_Style::cf7s_get_style_list(); // get all skins

		if ( isset( $skins[$style] ) ) // check if skin is exists
			wp_enqueue_style( "cf7s-$style", $skins[$style]['url'] . trailingslashit( $skins[$style]['dir'] ) . $skins[$style]['index'], 
				array('contact-form-7'), CF7SKINS_VERSION, 'all' );
	}
	
	
	/**
     * Add cf7skins classes to the CF7 HTML form class
	 * 
	 * Based on selected template & style
	 * eg. class="wpcf7-form cf7t-fieldset cf7s-wild-west"
	 * 
	 * @uses 'wpcf7_form_class_attr' filter in WPCF7_ContactForm->form_html()
	 * @uses wpcf7_get_current_contact_form()
	 * @file wp-content\plugins\contact-form-7\includes\contact-form.php
	 * 
     * @param $class is the CF7 HTML form class
     * @since 0.0.1
     */		
	function form_class_attr( $class ) {
		
		// Get the current CF7 form ID
		$cf7 = wpcf7_get_current_contact_form();  // Current contact form 7 object
		$form_id = $this->get_form_id( $cf7 );		
		
		// Get current CF7 form template and style from post meta
		$template_class = get_post_meta( $form_id, 'cf7s_template', true ) ? ' cf7t-' . get_post_meta( $form_id, 'cf7s_template', true ) : '';
		$skin_class = get_post_meta( $form_id, 'cf7s_style', true ) ? ' cf7s-'. get_post_meta( $form_id, 'cf7s_style', true ) : '';
		
		// CF7 Skins default class
		$cf7skins_class = ( $template_class || $skin_class ) ? ' cf7skins' : '';
		$cf7skins_classes = apply_filters( 'cf7skins_form_classes', $cf7skins_class );
		
		// Return the modified class
		return $class . $cf7skins_classes . $template_class . $skin_class;
	}
	
	
	/**
     * Modify the CF7 form content
	 * 
     * Back compat for CF7 3.9 
	 * @see http://contactform7.com/2014/07/02/contact-form-7-39-beta/
	 * 
	 * @uses 'wpcf7_contact_form' action in WPCF7_ContactForm__construct()
	 * @uses WPCF7_ContactForm->set_properties() - CF7 3.9 & after
	 * @uses WPCF7_ContactForm->form - before CF7 3.9
	 * @file wp-content\plugins\contact-form-7\includes\contact-form.php
	 *
     * @param $cf7 Contact Form 7 object
     * @since 0.0.1
     */		
	function contact_form( $cf7 ) {
		if( ! is_admin() ) {
			
			// Return if no cf7skins template or style was selected
			if ( ! get_post_meta( $cf7->id(), 'cf7s_template', true ) && ! get_post_meta( $cf7->id(), 'cf7s_style', true ) )
				return $cf7;
			
			// Enqueue CF7 Skins styles
			// @since 1.1
			if( $style = get_post_meta( $cf7->id(), 'cf7s_style', true ) )
				$this->enqueue_style( $style );			
			
			$form = $this->get_form_content( $cf7 );
			
			if ( version_compare( WPCF7_VERSION, '3.9-alpha', '>' ) ) {
				// uses WPCF7_ContactForm->set_properties() - CF7 3.9 & after
				$cf7->set_properties( array( 'form' => $this->modify_form( $form ) ) );
				}
			else {
				// uses WPCF7_ContactForm->form - before CF7 3.9
				$cf7->form = $this->modify_form( $form );
				}
		}
		
		return $cf7;
	}
	
	
	/**
     * Add label and require <em> to CF7 form
	 * 
     * e.g. <li>Name [text* cf7s-name]</li> CHANGED TO
     * <li><label for="cf7s-name">Name <em class="cf7s-reqd">*</em></label> [text* cf7s-name]</li>
	 * 
     * @param $form Current CF7 form content
     * @since 0.0.1
     */			
	function modify_form( $form ) {
		// Get all current shortcode
		$manager = WPCF7_ShortcodeManager::get_instance();
		$scanned = $manager->scan_shortcode( $form );		
		
		// Get all shortcodes id with tag name as the index
		$ids = array();		
		foreach ( $scanned as $tag ) {
			$tag = new WPCF7_Shortcode( $tag );
			$ids[$tag->name] = $tag->get_id_option() ? $tag->get_id_option() : $tag->name;
		}
		
		// Patterns for searching all list tag
		$pattern = "/<li ?.*>(.*)<\/li>/";
		preg_match_all( $pattern, $form, $matches );
		
		if ( $matches[0] ) {
		
			// Loop trought each match
			foreach( $matches[0] as $list ) {
				
				// Process only if the list have a shortcode
				if( preg_match( "/\[.*?\]/", $list, $shortcode ) ) {
					
					// Explode shortcode by spaces to get the shortcode name
					$explode = explode( ' ', str_replace( array('[', ']'), '', $shortcode[0] ) );
					$name = $explode[1];
					$id = isset( $ids[$name] ) ? $ids[$name] : $name; 
					
					// Add opening label tag
					$new_list = preg_replace( "/(<li.*?>)(.*)(<\/li>)/", "$1<label for='$id'>$2$3", $list );
					
					// Closing label tag with * required em tag
					if ( strpos( $shortcode[0], '*' ) !== false ) {
						$new_list = str_replace( '[', '<em class="cf7s-reqd">*</em> </label>[', $new_list );
					} else {
						$new_list = str_replace( '[', '</label>[', $new_list );
					}
					
					// Add label and em by replacing it.			
					$form = str_replace( $list, $new_list, $form );
				}
			}
		}
		
		return $form;
	}
	
	
	/**
     * Print selected enqueue styles from the settings page
	 * 
     * @parameter none
     * @since 1.1
	 * @author Neil Murray
     */		
	function custom_enqueue_style() {
		$option = get_option( CF7SKINS_OPTIONS );
		if( isset( $option['enqueue_styles'] ) && is_array( $option['enqueue_styles'] ) )
			foreach( $option['enqueue_styles'] as $k => $val )
				$this->enqueue_style($k);
	}	
	
	
	/**
     * Print custom scripts/styles from the settings page
	 * 
     * @parameter none
     * @since 0.1.0
	 * @author Neil Murray
     */		
	function print_custom() {
		$option = get_option( CF7SKINS_OPTIONS );
		if( isset( $option['custom'] ) && $option['custom'] )
			echo $option['custom'];
	}	

} new CF7_Skins_Contact();
