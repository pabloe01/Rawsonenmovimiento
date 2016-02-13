<?php
/**
 * CF7 Skins Template Class
 * 
 * @package cf7skins
 * @author Neil Murray
 * @version 0.0.1
 * @since 0.0.1
 */

 
class CF7_Skin_Template extends CF7_Skin {
 
	/**
     * Class constructor
	 * 
     * @since 0.0.1
     */	
    function __construct() {
		parent::__construct(); // Run parent class
		add_action( 'wp_ajax_load_template', array( &$this, 'load_template' ) );
    }
	
	
	/**
     * Load selected template and translate.
	 * 
     * @uses wpcf7_load_textdomain
     * @since 0.0.1
     */		
	function load_template() {
		// Check the nonce and if not valid, just die.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'cf7s' ) )
			die();
		
		// Get translation if locale is set and exists in the Contact Form 7 l10n
		if( isset( $_POST['locale'] ) 
			&& ! empty( $_POST['locale'] ) 
			&& array_key_exists( $_POST['locale'], wpcf7_l10n() ) )
				wpcf7_load_textdomain( $_POST['locale'] );
		
		// Get translation based on post ID
		if( isset( $_POST['post'] ) && ! empty( $_POST['post'] ) ) {
			$wpcf7 = wpcf7_contact_form( (int) $_POST['post'] );  // current CF7 form
			wpcf7_load_textdomain( $wpcf7->locale );
		}
		
		// Load selected template file
		$templates = $this->cf7s_get_template_list();		
		$template = $templates[$_POST['template']];
		require_once( $template['path'] . trailingslashit( $template['dir'] ) . $template['index'] );
		exit();
	}	
	
	
	/**
     * Get list of templates - sorted alphabetically [a-z]
	 * 
	 * @filter cf7skins_templates
	 * @return (array) array of templates
     * @since 0.0.1
     */
	public static function cf7s_get_template_list() {
		$templates = self::read_templates( CF7SKINS_TEMPLATES_PATH, CF7SKINS_TEMPLATES_URL );
		$templates = apply_filters( 'cf7skins_templates', $templates );	// add filter for other plugins				
		ksort( $templates ); // sort by array keys		
		return $templates;			
    }
	
	
	/**
	* Get list of templates from the templates directory
	* 
	* @param $path current plugin templates directory path
	* @param $url  current plugin templates directory url
	* @return (array) arrays of template information
	* @since 0.0.1
	*/	
	public static function read_templates( $path, $url ) {
		
		$templates = array();
		
		if ( $handle = opendir( $path ) ) {
		
			// Uses WP file system for reading description.txt and instruction.txt
			if( is_admin() ) WP_Filesystem();
			global $wp_filesystem;
			
			while (false !== ( $entry = readdir( $handle ) ) )
				if ( $entry != '.' && $entry != '..' ) {					
					
					// Add default instructions
					ob_start();
					include( CF7SKINS_PATH . 'includes/template-instructions.php' );
					$instructions = ob_get_contents();
					$instructions = str_replace( "\r", "<br />", $instructions ); // replace newline as HTML <br />
					ob_end_clean();
					
					// Step up default headers
					$default_headers = array(
						'Template Name' => 'Template Name',
						'Template URI' 	=> 'Template URI',
						'Author' 		=> 'Author',
						'Author URI' 	=> 'Author URI',
						'Description' 	=> 'Description',
						'Instructions' 	=> 'Instructions',
						'Version' 		=> 'Version',
						'Version Date'	=> 'Version Date',	// with format '2012-02-23 06:12:45'
						'License' 		=> 'License',
						'License URI' 	=> 'License URI',
						'Tags' 			=> 'Tags',
						'Text Domain' 	=> 'Text Domain'  // for external translation slug
					);
					
					// Start reading files
					$files = scandir( $path . $entry );
					$templates[$entry]['dir'] = $entry;
					$templates[$entry]['path'] = $path;
					$templates[$entry]['url'] = $url;					
					foreach( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$templates[$entry]['files'][] = $file;
							$file_path = $path . trailingslashit($entry) . $file;
							$file_data = get_file_data( $file_path, $default_headers );
							
							// Load description from description.txt if is existed.
							// Inline description will be overwrited if description.txt if is existed.
							$description_file = $path . trailingslashit($entry) . 'description.txt';
							if ( is_admin() && $wp_filesystem->is_file( $description_file ) ) {
								$_description = $wp_filesystem->get_contents( $description_file );
								$file_data['Description'] = str_replace( "\r", "<br />", $_description ); // replace newline as HTML <br />
							}
							
							// Use default instruction if there is no instruction
							$file_data['Instructions'] = $file_data['Instructions'] ? $file_data['Instructions'] : $instructions;
							
							// Load instruction from instruction.txt if is existed
							// Inline or default description will be overwrited if description.txt if is existed.
							$instructions_file = $path . trailingslashit($entry) . 'instruction.txt';
							if ( is_admin() && $wp_filesystem->is_file( $instructions_file ) ) {
								$_instructions = $wp_filesystem->get_contents( $instructions_file );
								$file_data['Instructions'] = str_replace( "\r", "<br />", $_instructions ); // replace newline as HTML <br />
							}
							
							if( $file_data['Template Name'] ) {
								$templates[$entry]['index'] = $file;
								$templates[$entry]['details'] = $file_data;
							}
						}
					}
					
					$templates[$entry]['tags'] = array_map('trim', explode( ',', $templates[$entry]['details']['Tags'] ) );
				}
			
			closedir( $handle );
		}
		
		return $templates;
    }
	
	
	/**
	* Returns a list of template filter tags  // move to template-feature.php?
	*
	* Uses the same method as get_theme_feature_list() function
	* @see http://codex.wordpress.org/Function_Reference/get_theme_feature_list
	* @filter template_filter_tags
	* @return (array)
	* @since 0.1.0
	*/		
	function filter_tags() {
		$filter_tags = array(
		
			'Layout' => array (
				'fixed-layout' 			=> __( 'Fixed Layout', $this->textdomain ),
				'fluid-layout' 			=> __( 'Fluid Layout', $this->textdomain ),
				'responsive-layout' 	=> __( 'Responsive Layout', $this->textdomain ),
				'one-column' 			=> __( 'One Column', $this->textdomain ),
				'one-two-columns'		=> __( 'One or Two Column', $this->textdomain ),
				'one-two-three-columns'	=> __( 'One, Two or Three Column', $this->textdomain ),
			),
			
			'Features' => array (
				'fieldsets'		=> __( 'Fieldsets', $this->textdomain ),
				'background'	=> __( 'Background', $this->textdomain ),
				'gradients' 	=> __( 'Gradients', $this->textdomain ),
			),
			
			'Subject' => array (
				'business'		=> __( 'Business', $this->textdomain ),
				'event'			=> __( 'Event', $this->textdomain ),
				'holiday' 		=> __( 'Holiday', $this->textdomain ),
				'individual' 	=> __( 'Individual', $this->textdomain ),
				'seasonal' 		=> __( 'Seasonal', $this->textdomain ),
			)
		);
		
		return apply_filters( 'template_filter_tags', $filter_tags );
	}
	
	
	/**
     * Output template filter tags for backend  // move to template-filter.php?
	 * 
	 * @compat WP3.9
	 * @output (HTML)
     * @since 0.0.1
     */
	function cf7s_show_template_list_39() {
		$val = get_post_meta( $this->get_id(), 'cf7s_template', true ); // Get current post selected template
		?>
		<div class="theme-navigation theme-install-php">
			<span class="theme-count"><?php echo count( $this->cf7s_get_template_list() ); ?></span>
			<a class="theme-section skin-sort balloon current" title="<?php _e( 'All available Templates',  $this->textdomain ); ?>" href="#" data-sort="all"><?php _e('All', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" title="<?php _e( 'Selected by CF7 Skins Team',  $this->textdomain ); ?>" href="#" data-sort="featured"><?php _e('Featured', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" title="<?php _e( 'Commonly used',  $this->textdomain ); ?>" href="#" data-sort="popular"><?php _e('Popular', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" title="<?php _e( 'Recently added',  $this->textdomain ); ?>" href="#" data-sort="new"><?php _e('Latest', $this->textdomain); ?></a>
			
			<div class="theme-top-filters">
				<!-- <span class="theme-filter" data-filter="photoblogging">Photography</span>
				<span class="theme-filter" data-filter="responsive-layout">Responsive</span> -->
				<?php if( CF7SKINS_FEATURE_FILTER ) : ?>
					<a class="more-filters balloon" title="<?php _e( 'Narrow your choices based on your specific requirements',  $this->textdomain ); ?>" href="#"><?php _e('Feature Filter', $this->textdomain); ?></a>
				<?php endif; ?>
			</div>
			
			<div class="more-filters-container">
				<a class="apply-filters button button-secondary" href="#"><?php _e('Apply Filters', $this->textdomain); ?><span></span></a>
				<a class="clear-filters button button-secondary" href="#"><?php _e('Clear', $this->textdomain); ?></a>
				<br class="clear">
				<?php
				$feature_list = $this->filter_tags();
				
				foreach ( $feature_list as $feature_name => $features ) {
				
					echo '<div class="filters-group">';
					
					$feature_name = esc_html( $feature_name );
					echo '<h4 class="feature-name">' . $feature_name . '</h4>';
					echo '<ol class="feature-group">';
					foreach ( $features as $feature => $feature_name ) {
						$feature = esc_attr( $feature );
						echo '<li><input type="checkbox" id="tab-template-' . $feature . '" value="' . $feature . '" /> ';
						echo '<label for="tab-template-' . $feature . '">' . $feature_name . '</label></li>';
					}
					echo '</ol>';
					echo '</div>';
				}
				?>
				
				<div class="filtering-by filtered-by">
					<span><?php _e('Filtering by:', $this->textdomain); ?></span>
					<div class="tags"></div>
					<a href="#"><?php _e('Edit', $this->textdomain); ?></a>
				</div>
			</div>
			
			<div class="skins-sort">
				<label class="balloon" title="<?php _e( 'Sort by Name, Date and License (free or pro) – use arrow to reverse sort order', $this->textdomain ); ?>" for="skins-sort"><?php _e('Sort by', $this->textdomain); ?></label>
				<select class="balloon sort-by" name="sort-by" title="" autocomplete="off">
					<option value="name" selected="selected"><?php _e( 'Name', $this->textdomain ); ?></option>
					<option value="date"><?php _e( 'Date', $this->textdomain ); ?></option>
					<option value="license"><?php _e( 'License', $this->textdomain ); ?></option>
				</select>
				<a href="javascript:void(0)" class="dashicons dashicons-arrow-down-alt"></a>
			</div>
			<label class="screen-reader-text" for="theme-search-input"><?php _e('Search Templates', $this->textdomain); ?></label>
			<input type="search" class="theme-search skins-search" id="theme-search-input" placeholder="<?php _e('Search templates...', $this->textdomain); ?>" />
		</div>
		
		<div class="skin-list clearfix">
			<span class="spinner"></span>
			<?php $this->templates_list() ?>
		</div>
		
		<div class="skin-details clearfix hidden">
			<?php foreach( $this->cf7s_get_template_list() as $template ) 		
				$this->cf7s_details_view( $template ); ?>
		</div>
		<input type="hidden" value="<?php echo $val; ?>" name="cf7s-template" id="cf7s-template" />
		<?php
    }
	 
	 
	 /**
     * Output template filter tags for backend  // move to template-filter.php?
	 * 
	 * @output (HTML)
     * @since 0.0.1
     */		 
	function cf7s_show_template_list() {
		$val = get_post_meta( $this->get_id(), 'cf7s_template', true ); // Get current post selected template
		?>
		<div class="wp-filter">
			<div class="filter-count"><span class="count"><?php echo count( $this->cf7s_get_template_list() ); ?></span></div>
			
			<ul class="filter-links">
				<li><a class="theme-section skin-sort balloon current" title="<?php _e( 'All available Templates',  $this->textdomain ); ?>" href="#" data-sort="all"><?php _e('All', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" title="<?php _e( 'Selected by CF7 Skins Team',  $this->textdomain ); ?>" href="#" data-sort="featured"><?php _e('Featured', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" title="<?php _e( 'Commonly used',  $this->textdomain ); ?>" href="#" data-sort="popular"><?php _e('Popular', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" title="<?php _e( 'Recently added',  $this->textdomain ); ?>" href="#" data-sort="new"><?php _e('Latest', $this->textdomain); ?></a></li>
			</ul>
			
			<?php if( CF7SKINS_FEATURE_FILTER ) : ?>
				<a class="drawer-toggle balloon" title="<?php _e( 'Narrow your choices based on your specific requirements',  $this->textdomain ); ?>" href="#"><?php _e('Feature Filter', $this->textdomain); ?></a>
			<?php endif; ?>
			
			<div class="search-form">
				<label class="screen-reader-text" for="theme-search-input"><?php _e('Search Templates', $this->textdomain); ?></label>
				<input type="search" class="theme-search skins-search" id="theme-search-input" placeholder="<?php _e('Search templates...', $this->textdomain); ?>" />
			</div>	
			
			<div class="filter-drawer">
				<div class="buttons">
					<a class="apply-filters button button-secondary" href="#"><?php _e('Apply Filters', $this->textdomain); ?><span></span></a>
					<a class="clear-filters button button-secondary" href="#"><?php _e('Clear', $this->textdomain); ?></a>
				</div>
						
				<?php
				$feature_list = $this->filter_tags();
				foreach ( $feature_list as $feature_name => $features ) {
					echo '<div class="filter-group">';
					$feature_name = esc_html( $feature_name );
					echo '<h4 class="feature-name">' . $feature_name . '</h4>';
					echo '<ol class="feature-group">';
					foreach ( $features as $feature => $feature_name ) {
						$feature = esc_attr( $feature );
						echo '<li><input type="checkbox" id="tab-template-' . $feature . '" value="' . $feature . '" /> ';
						echo '<label for="tab-template-' . $feature . '">' . $feature_name . '</label></li>';
					}
					echo '</ol>';
					echo '</div>';
				}
				?>
				<div class="filtered-by">
					<span><?php _e('Filtering by:', $this->textdomain); ?></span>
					<div class="tags"></div>
					<a href="#"><?php _e('Edit', $this->textdomain); ?></a>
				</div>
			</div>
			<div class="skins-sort">
				<label class="balloon" title="<?php _e( 'Sort by Name, Date and License (free or pro) – use arrow to reverse sort order', $this->textdomain ); ?>" for="skins-sort"><?php _e('Sort by', $this->textdomain); ?></label>
				<select class="balloon sort-by" name="sort-by" title="" autocomplete="off">
					<option value="name" selected="selected"><?php _e( 'Name', $this->textdomain ); ?></option>
					<option value="date"><?php _e( 'Date', $this->textdomain ); ?></option>
					<option value="license"><?php _e( 'License', $this->textdomain ); ?></option>
				</select>
				<a href="javascript:void(0)" class="dashicons dashicons-arrow-down-alt"></a>
			</div>			
		</div>		
		
		<div class="skin-list clearfix">
			<span class="spinner"></span>
			<?php $this->templates_list() ?>
		</div>
		<div class="skin-details clearfix hidden">
			<?php foreach( $this->cf7s_get_template_list() as $template ) 		
				$this->cf7s_details_view( $template ); ?>
		</div>
		
		<input type="hidden" value="<?php echo $val; ?>" name="cf7s-template" id="cf7s-template" />
		<?php
    }

			 
	/**
	 * Output each template in the template tab
	 * 
	 * @deprecated cf7s_show_template_inlist
	 * @param $templates (array) of all the templates
	 * @output (HTML)
	 * @since 0.1.0
	 */	
	function templates_list( $templates = array() ) {
		if( ! $templates )
			$templates = $this->cf7s_get_template_list();
		//print_r( $templates );
		
		// Get the current contact form ID, check if request comes from AJAX
		$id = isset( $_POST['id'] ) ? $_POST['id'] : $this->get_id();		
		
		foreach( $templates as $key => $template ) {
			$class = $template['dir'] == get_post_meta( $id, 'cf7s_template', true ) ? ' selected' : '';			
			$select_text = $template['dir'] == get_post_meta( $id, 'cf7s_template', true ) ? __('Selected', $this->textdomain ) : __('Select', $this->textdomain );
			$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : '';
			$post = isset( $_GET['post'] ) ? (int) $_GET['post'] : '';
			
			$skin_class = $template['dir'] == get_post_meta( $id, 'cf7s_template', true ) ? 'skin skin-selected' : 'skin';
			$date = mysql2date( 'U', $template['details']['Version Date'] );
			$license = strpos( $template['path'], 'cf7skins-pro' ) !== false ? 'pro' : 'free';
			?>
			<div class="<?php echo $skin_class; ?>" data-name="<?php echo $key; ?>" data-date="<?php echo $date; ?>" data-license="<?php echo $license; ?>">
				<div class="wrapper">
					<h4 class="skin-name"><?php echo $template['details']['Template Name']; ?></h4>
					<div class="thumbnail">
						<?php $imgpath = $template['path'] . $template['dir'] . '/thumbnail.png'; ?>
						<?php $imgurl = $template['url'] . $template['dir'] . '/thumbnail.png'; ?>
						<img src="<?php echo file_exists( $imgpath ) ? $imgurl : CF7SKINS_URL . 'images/no-preview.png'; ?>" />
					</div>
					<ul class="clearfix skin-action">
						<li><a class="select<?php echo $class; ?> balloon" title="<?php _e( 'Select to apply the Template to your form - appears in the form editing area, where you can edit your requirements.',$this->textdomain ); ?>" data-post="<?php echo $post; ?>" data-locale="<?php echo $locale; ?>" data-value="<?php $this->get_slug_name( $template ); ?>" href="#cf7s-template"><?php echo $select_text; ?></a></li>
						<li><a class="detail balloon" title="<?php _e( 'Show detailed information about this Template, with layout, description and usage details.' ,$this->textdomain ); ?>" href="#tpl-<?php $this->get_slug_name( $template ); ?>-detail"><?php _e('Details', $this->textdomain ); ?></a></li>
					</ul>				
				</div>		
			</div>
			<?php
		}
    }
	
	
    /**
     * Output expanded and details view of selected template
	 *
	 * @TODO Display in pop-over window
	 * @param $template current processed template
	 * @output (HTML)
     * @since 0.0.1
     */ 
	function cf7s_details_view( $template ) {
		
		$class = $template['dir'] == get_post_meta( $this->get_id(), 'cf7s_template', true ) ? ' selected' : '';	// set link class
		$select_text = $template['dir'] == get_post_meta( $this->get_id(), 'cf7s_template', true ) ? __('Selected', $this->textdomain) : __('Select', $this->textdomain);
		?>
		<div id="tpl-<?php $this->get_slug_name( $template ); ?>-detail" class="details hidden">
			<div class="details-view">
				<div class="block-thumbnail">
					<img src="<?php echo $template['url'] . $template['dir'] . '/thumbnail.png'; ?>" />
				</div>			
				<div class="block-details"><div>
					<ul class="clearfix skin-action">
						<li><a class="balloon view" data-value="<?php $this->get_slug_name( $template ); ?>" href="#cf7s-template" title="<?php _e( 'Use Expanded View to view Template features - shows layout, description & usage details.', $this->textdomain ); ?>"><?php _e('Expanded View', $this->textdomain ); ?></a></li>
						<li><a class="balloon select<?php echo $class; ?>" data-value="<?php $this->get_slug_name( $template ); ?>" href="#cf7s-template" title="<?php _e( 'Select to apply the Template to your form - appears in the Form Editing area, where you can edit to your requirements.', $this->textdomain ); ?>"><?php echo $select_text; ?></a></li>
						<li><a class="balloon close" href="#" title="<?php _e( 'Return to Template Gallery/Grid view.', $this->textdomain ); ?>"><?php _e('Close', $this->textdomain ); ?></a></li>
					</ul>
					<?php // print_r( $template ); ?>
					<h1><?php echo $template['details']['Template Name']; ?></h1>
					
					<h4><strong><?php _e('Description', $this->textdomain ); ?></strong></h4>
					<p class="description"><?php echo $template['details']['Description']; ?></p>
					
					<h4><strong><?php _e('Instructions', $this->textdomain ); ?></strong></h4>
					<p class="description"><?php echo $template['details']['Instructions']; ?></p>
				</div></div>
			</div>
			<div class="expanded-view">
				<ul class="clearfix skin-action">
					<li><a class="balloon view" data-value="<?php $this->get_slug_name( $template ); ?>" href="#cf7s-template" title="<?php _e( 'Return to Details View', $this->textdomain ); ?>"><?php _e('Details View', $this->textdomain ); ?></a></li>
					<li><a class="balloon select<?php echo $class; ?>" data-value="<?php $this->get_slug_name( $template ); ?>" href="#cf7s-template" title="<?php _e( 'Select to apply the Template to your form - appears in the Form editing area, where you can edit to your requirements.', $this->textdomain ); ?>"><?php echo $select_text; ?></a></li>
					<li><a class="balloon close" href="#" title="<?php _e( 'Return to Template Gallery/ Grid View', $this->textdomain ); ?>"><?php _e('Close', $this->textdomain ); ?></a></li>
				</ul>	
				
				<h1><?php echo $template['details']['Template Name']; ?></h1>
				
				<div class="large-thumbnail">
					<img src="<?php echo $this->get_skin_modal( $template ); ?>" />
				</div>
				<h4><strong><?php _e('Description', $this->textdomain ); ?></strong></h4>
				<p class="description"><?php echo $template['details']['Description']; ?></p>
				
				<h4><strong><?php _e('Instructions', $this->textdomain ); ?></strong></h4>
				<p class="description"><?php echo $template['details']['Instructions']; ?></p>				
			</div>
		</div>
		<?php
    }

} // End class
