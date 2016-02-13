<?php
/**
 * Admin Class
 * 
 * Implement all functionality for CF7 Skins Admin on CF7 plugin page.
 * 
 * @package cf7skins
 * @author Neil Murray
 * @since 0.0.1
 */ 


class CF7_Skins_Admin {
	
	/**
     * Class constructor
	 * 
     * @since 0.0.1
     */	
    function __construct() {
	
		// Create the metabox for CF7 Skins
		add_action( 'wpcf7_add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		add_action( 'wpcf7_admin_footer', array( &$this, 'add_meta_boxes_42' ) );
		
		// Push the styles and scripts to the admin header
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
		
		// Action hooks to store selected template and style while updating or creating new CF7
		add_action( 'wpcf7_after_create', array( &$this, 'skin_update' ) );
		add_action( 'wpcf7_after_update', array( &$this, 'skin_update' ) );
	}			
	
	
	/**
     * Update current form post meta data with selected style and/or template
	 *
     * Get post id (contact form 7 form id) in $_POST['cf7s-template'] and $_POST['cf7s-style']
     * Post meta name 'cf7s_template' for template and 'cf7s_style' for style
	 * 
	 * @action cf7s_update
     * @param $cf7 is the contact form 7 object data
     * @since 0.0.1
     */	
	function skin_update( $cf7 ) {
		// Get the current contact form 7 id
		$form_id = CF7_Skins_Contact::get_form_id( $cf7 );
		
		// Update the post meta
		if( isset( $_POST['cf7s-template'] ) )
			update_post_meta( $form_id, 'cf7s_template', esc_attr( $_POST['cf7s-template'] ) );
		
		if( isset( $_POST['cf7s-style'] ) )
			update_post_meta( $form_id, 'cf7s_style', esc_attr( $_POST['cf7s-style'] ) );
		
		// Add action while saving the contact form 7
		do_action( 'cf7s_update', $cf7 );
	}
	
	
	/**
     * Enqueue CF7 Skins admin styles and scripts
	 *
	 * @action cf7s_admin_enqueue_scripts
     * @param $hook_suffix is the current page hook
     * @since 0.0.1
     */
	function admin_enqueue_scripts( $hook_suffix ) {
		if ( false === strpos( $hook_suffix, 'wpcf7' ) )
			return;
		
		wp_enqueue_style( 'tipsy',
			CF7SKINS_URL . 'css/tipsy.css',
			array( 'contact-form-7-admin' ), '1.0.0a', 'all' );
		
		wp_enqueue_style( 'cf7s-admin',
			CF7SKINS_URL . 'css/admin.css',
			array( 'contact-form-7-admin' ), CF7SKINS_VERSION, 'all' );
		
		wp_enqueue_script( 'tipsy',
			CF7SKINS_URL . 'js/jquery.tipsy.js',
			array( 'jquery' ), '1.0.0a', true );
		
		wp_enqueue_script( 'cf7s-admin',
			CF7SKINS_URL . 'js/jquery.admin.js',
			array( 'jquery', 'underscore' ), CF7SKINS_VERSION, true );
		
		wp_localize_script( 'cf7s-admin', 'cf7s', array(
			'nonce'		=> wp_create_nonce( 'cf7s' ),   // generate a nonce for security checking
			'load'		=> 'load_template',  			// post action for reading and loading selected template
			'sort'		=> 'cf7s_sort_skin',  			// post action for sorting skin		
			'l10n'		=> array(
				'loading'		=> __('Loading template...', CF7SKINS_TEXTDOMAIN ),
				'emptyfilter'	=> __('Empty filter, please select a filter.', CF7SKINS_TEXTDOMAIN ),
				'select' 		=> __('Select', CF7SKINS_TEXTDOMAIN ),
				'selected' 		=> __('Selected', CF7SKINS_TEXTDOMAIN ),
				'expanded'		=> __('Expanded View', CF7SKINS_TEXTDOMAIN ),
			)
		));
		
		do_action( 'cf7s_admin_enqueue_scripts', $hook_suffix );
	}
	
	
	/**
     * Create the skins metabox
	 *
	 * @action cf7s_add_meta_boxes
     * @param $post_id is the current post editing ID
     * @since 0.0.1
     */	
	function add_meta_boxes( $post_id ) {
		add_meta_box( 'cf7s', __( 'Skins', 'cf7skins' ),
			array( &$this, 'skins_meta_box' ), null, 'mail', 'core' );
			
		// Add action while creating the skins metabox
		do_action( 'cf7s_add_meta_boxes', $post_id );
	}
	
	
	/**
     * Custom skins dialog added in the CF7 Footer for version 4.2
	 *
	 * @action wpcf7_admin_footer
     * @param $post contact form object
     * @since 1.0.1
     */	
	function add_meta_boxes_42( $post ) {
		if (version_compare(WPCF7_VERSION, '4.2') >= 0) {
		
			// Create the container id for javascript pointer
			// This is added if using add_meta_box() function
			echo '<div class="wrap">';
				echo '<div id="cf7skins-42" class="postbox">';
				echo '<div title="'. __('Click to toggle', CF7SKINS_TEXTDOMAIN ) .'" class="handlediv"><br></div>';
					echo '<h3 class="hndle"><span>'. __('Skins', CF7SKINS_TEXTDOMAIN ) .'</span></h3>';
					echo '<div class="inside">';
						echo '<div id="cf7s" class="cf7-42">';
							$this->generate_tab( null, null );  // in tab.php	
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}
	
	
	/**
     * Display the skins metabox
	 * 
     * @param $post current post object
     * @param $box metabox arguments
     * @since 0.0.1
     */	
	function skins_meta_box( $post, $box ) {
		$this->generate_tab( $post, $box );  // in tab.php
	}
	
	
	/**
	 * Check if current admin page is contact form editing page
	 * @since 1.0.2
	 */
	public static function edit_page() {
		global $hook_suffix;
				
		// Don't show at the Contact Form 7 list wp-admin/admin.php?page=wpcf7
		if( 'toplevel_page_wpcf7' == $hook_suffix && isset( $_GET['post'] ) && ! empty( $_GET['post'] )  )
			return true;
		
		// Don't show in the add new page
		if( 'contact_page_wpcf7-new' == $hook_suffix && isset( $_GET['locale'] ) )
			return true;
		
		return false;
	}		
}