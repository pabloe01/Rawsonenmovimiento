<?php
/**
 * CF7 Skins Style Class
 * 
 * @package cf7skins
 * @author Neil Murray 
 * @since 0.0.1
 */


class CF7_Skin_Style extends CF7_Skin {

	/**
     * Class constructor
	 * 
     * @since 0.0.1
     */	
    function __construct() {
		parent::__construct(); // Run parent class
    }
	
	
	/**
     * Get list of styles - sorted alphabetically [a-z]
	 * 
	 * @filter cf7skins_styles
	 * @return (array) array of styles
     * @since 0.0.1
     */
	public static function cf7s_get_style_list() {
		$styles = self::read_styles( CF7SKINS_STYLES_PATH, CF7SKINS_STYLES_URL );
		$styles = apply_filters( 'cf7skins_styles', $styles ); // add filter for other plugins	
		ksort( $styles ); // sort by array keys	
		return $styles;		
    }
	
		 		
	/**
	* Get list of styles from the styles directory
	* 
	* @param $path current plugin styles directory path
	* @param $url  current plugin styles directory url
	* @return (array) arrays of style information
	* @since 0.0.1
	*/
	public static function read_styles( $path, $url ) {
	
		$styles = array();
		
		if ( $handle = opendir( $path ) ) {
		
			// Uses WP file system for reading description.txt and instruction.txt
			if( is_admin() ) WP_Filesystem();
			global $wp_filesystem;		
			
			while (false !== ( $entry = readdir( $handle ) ) )
				if ( $entry != '.' && $entry != '..' ) {
				
					// Add default instructions
					ob_start();
					include( CF7SKINS_PATH . 'includes/style-instructions.php' );
					$instructions = ob_get_contents();
					$instructions = str_replace( "\r", "<br />", $instructions ); // replace newline as HTML <br />
					ob_end_clean();				
					
					// Step up default headers
					$default_headers = array(
						'Style Name' 	=> 'Style Name',
						'Style URI' 	=> 'Style URI',
						'Author' 		=> 'Author',
						'Author URI' 	=> 'Author URI',
						'Description' 	=> 'Description',
						'Instructions' 	=> 'Instructions',
						'Version' 		=> 'Version',
						'Version Date'	=> 'Version Date',	// with format '2012-02-23 06:12:45'
						'License' 		=> 'License',
						'License URI' 	=> 'License URI',
						'Tags' 			=> 'Tags'
					);				
					
					// Start reading files
					$files = scandir( $path . $entry );
					$styles[$entry]['dir'] = $entry;
					$styles[$entry]['path'] = $path;
					$styles[$entry]['url'] = $url;
					foreach( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$styles[$entry]['files'][] = $file;
							$file_path = $path . trailingslashit($entry) . $file;
							$file_data = get_file_data( $file_path, $default_headers, 'test' );
							
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
							
							if( $file_data['Style Name'] ) {
								$styles[$entry]['index'] = $file;
								$styles[$entry]['details'] = $file_data;
							}
						}
					}
					
					$styles[$entry]['tags'] = array_map('trim', explode( ',', $styles[$entry]['details']['Tags'] ) );
				}
			closedir($handle);
		}
		
		return $styles;		
    }
	
	
	/**
	* Returns a list of style filter tags  // move to style-feature.php?
	* 
	* Uses the same method as get_theme_feature_list() function
	* @see http://codex.wordpress.org/Function_Reference/get_theme_feature_list
	* @filter style_filter_tags
	* @return (array)
	* @since 0.1.0
	*/	
	function filter_tags() {
		$filter_tags = array(
		
			__( 'Colors', $this->textdomain ) => array (
				'black'		=> __( 'Black', $this->textdomain ),
				'brown'		=> __( 'Brown', $this->textdomain ),
				'gray'		=> __( 'Gray', $this->textdomain ),
				'green'		=> __( 'Green', $this->textdomain ),
				'orange'	=> __( 'Orange', $this->textdomain ),
				'pink'		=> __( 'Pink', $this->textdomain ),
				'purple'	=> __( 'Purple', $this->textdomain ),
				'red'		=> __( 'Red', $this->textdomain ),
				'silver'	=> __( 'Silver', $this->textdomain ),
				'tan'		=> __( 'Tan', $this->textdomain ),
				'white'		=> __( 'White', $this->textdomain ),
				'yellow'	=> __( 'Yellow', $this->textdomain ),
				'dark'		=> __( 'Dark', $this->textdomain ),
				'light'		=> __( 'Light', $this->textdomain ),
			),
			
			__( 'Layout', $this->textdomain ) => array (
				'fixed-layout' 			=> __( 'Fixed Layout', $this->textdomain ),
				'fluid-layout' 			=> __( 'Fluid Layout', $this->textdomain ),
				'responsive-layout' 	=> __( 'Responsive Layout', $this->textdomain ),
				'one-column' 			=> __( 'One Column', $this->textdomain ),
				'one-two-columns'		=> __( 'One or Two Column', $this->textdomain ),
				'one-two-three-columns'	=> __( 'One, Two or Three Column', $this->textdomain ),
			),
			
			__( 'Features', $this->textdomain ) => array (
				'Fieldsets'		=> __( 'Fieldsets', $this->textdomain ),
				'Background'	=> __( 'Background', $this->textdomain ),
				'Gradients' 	=> __( 'Gradients', $this->textdomain ),
			),
			
			__( 'Subject', $this->textdomain ) => array (
				'business'		=> __( 'Business', $this->textdomain ),
				'event'			=> __( 'Event', $this->textdomain ),
				'holiday' 		=> __( 'Holiday', $this->textdomain ),
				'individual' 	=> __( 'Individual', $this->textdomain ),
				'seasonal' 		=> __( 'Seasonal', $this->textdomain ),
			)
		);
		
		return apply_filters( 'style_filter_tags', $filter_tags );
	}	
	
	
	/**
     * Output style filter tags for backend  // move to style-feature.php?
	 * 
	 * @compat WP3.9
	 * @output (HTML)
     * @since 0.0.1
     */	
	
	function cf7s_show_style_list_39() {		
		$val = get_post_meta( $this->get_id(), 'cf7s_style', true ); // Get current post selected style
		?>
		<div class="theme-navigation theme-install-php">
			<span class="theme-count"><?php echo count( $this->cf7s_get_style_list() ); ?></span>
			<a class="theme-section skin-sort current balloon" href="#" data-sort="all" title="<?php _e( 'All available Styles', $this->textdomain ); ?>"><?php _e('All', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" href="#" data-sort="featured" title="<?php _e( 'Selected by the CF7 Skins team', $this->textdomain ); ?>"><?php _e('Featured', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" href="#" data-sort="popular" title="<?php _e( 'Commonly used', $this->textdomain ); ?>"><?php _e('Popular', $this->textdomain); ?></a>
			<a class="theme-section skin-sort balloon" href="#" data-sort="new" title="<?php _e( 'Recently added', $this->textdomain ); ?>"><?php _e('Latest', $this->textdomain); ?></a>

			<div class="theme-top-filters">
				<!-- <span class="theme-filter" data-filter="photoblogging">Photography</span>
				<span class="theme-filter" data-filter="responsive-layout">Responsive</span> -->
				<?php if( CF7SKINS_FEATURE_FILTER ) : ?>
					<a class="more-filters balloon" title="<?php _e( 'Narrow your choices based on your specific requirements',  $this->textdomain ); ?>" href="#">Feature Filter</a>
				<?php endif; ?>
			</div>
			
			<div class="more-filters-container">
				<a class="apply-filters button button-secondary balloon" href="#" title="<?php _e('Check all the boxes that meet your specific requirements and then click apply filters.', $this->textdomain); ?>"><?php _e('Apply Filters', $this->textdomain); ?><span></span></a>
				<a class="clear-filters button button-secondary balloon" href="#"><?php _e('Clear', $this->textdomain); ?></a>
				<br class="clear">

				<?php
				$feature_list = $this->filter_tags();
				
				foreach ( $feature_list as $feature_name => $features ) {
				
					if ( $feature_name === 'Colors' || $feature_name === __( 'Colors' ) ) { // hack hack hack
						echo '<div class="filters-group wide-filters-group">';
					} else {
						echo '<div class="filters-group">';
					}

					$feature_name = esc_html( $feature_name );
					echo '<h4 class="feature-name">' . $feature_name . '</h4>';
					echo '<ol class="feature-group">';
					foreach ( $features as $feature => $feature_name ) {
						$feature = esc_attr( $feature );
						echo '<li><input type="checkbox" id="tab-style-' . $feature . '" value="' . $feature . '" /> ';
						echo '<label for="tab-style-' . $feature . '">' . $feature_name . '</label></li>';
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
				<label class="balloon" for="skins-sort" title="<?php _e( 'Sort by Name, Date and License (free or pro) – use arrow to reverse sort order', $this->textdomain ); ?>"><?php _e('Sort by', $this->textdomain); ?></label>
				<select class="sort-by balloon" name="sort-by" title="" autocomplete="off">
					<option value="name" selected="selected"><?php _e( 'Name', $this->textdomain ); ?></option>
					<option value="date"><?php _e( 'Date', $this->textdomain ); ?></option>
					<option value="license"><?php _e( 'License', $this->textdomain ); ?></option>
				</select>
				<a href="javascript:void(0)" class="dashicons dashicons-arrow-down-alt"></a>
			</div>				
			<label class="screen-reader-text" for="theme-search-input"><?php _e('Search Styles', $this->textdomain); ?></label>
			<input type="search" class="theme-search skins-search" id="theme-search-input" placeholder="<?php _e('Search styles...', $this->textdomain); ?>" />
		</div>	

		<div class="skin-list clearfix">
			<span class="spinner"></span>
			<?php $this->styles_list(); ?>
		</div>

		<div class="skin-details clearfix hidden">
		<?php foreach( $this->cf7s_get_style_list() as $style ) 		
			$this->cf7s_details_view( $style ); ?>
		</div>
		<input type="hidden" value="<?php echo $val; ?>" name="cf7s-style" id="cf7s-style" />
		<?php
    }
	 
	 
	/**
     * Output style filter tags for backend  // move to style-feature.php?
	 * 
	 * @output (HTML)
     * @since 0.0.1
     */	
	function cf7s_show_style_list() {		
		$val = get_post_meta( $this->get_id(), 'cf7s_style', true ); // Get current post selected style
		?>
		<div class="wp-filter">
			<div class="filter-count"><span class="count"><?php echo count( $this->cf7s_get_style_list() ); ?></span></div>
			
			<ul class="filter-links">
				<li><a class="theme-section skin-sort current balloon" href="#" data-sort="all" title="<?php _e( 'All available Styles', $this->textdomain ); ?>"><?php _e('All', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" href="#" data-sort="featured" title="<?php _e( 'Selected by the CF7 Skins team', $this->textdomain ); ?>"><?php _e('Featured', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" href="#" data-sort="popular" title="<?php _e( 'Commonly used', $this->textdomain ); ?>"><?php _e('Popular', $this->textdomain); ?></a></li>
				<li><a class="theme-section skin-sort balloon" href="#" data-sort="new" title="<?php _e( 'Recently added', $this->textdomain ); ?>"><?php _e('Latest', $this->textdomain); ?></a></li>
			</ul>
			
			<?php if( CF7SKINS_FEATURE_FILTER ) : ?>
				<a class="drawer-toggle balloon" title="<?php _e( 'Narrow your choices based on your specific requirements',  $this->textdomain ); ?>" href="#">Feature Filter</a>
			<?php endif; ?>
			
			<div class="search-form">
				<label class="screen-reader-text" for="theme-search-input"><?php _e('Search Styles', $this->textdomain); ?></label>
				<input type="search" class="theme-search skins-search" id="theme-search-input" placeholder="<?php _e('Search styles...', $this->textdomain); ?>" />
			</div>			
			
			<div class="filter-drawer">
				<div class="buttons">
					<a class="apply-filters button button-secondary balloon" href="#" title="<?php _e('Check all the boxes that meet your specific requirements and then click apply filters.', $this->textdomain); ?>"><?php _e('Apply Filters', $this->textdomain); ?><span></span></a>
					<a class="clear-filters button button-secondary balloon" href="#"><?php _e('Clear', $this->textdomain); ?></a>
				</div>
								
				<?php
				$feature_list = $this->filter_tags();
				foreach ( $feature_list as $feature_name => $features ) {
					if ( $feature_name === 'Colors' || $feature_name === __( 'Colors' ) ) { // hack hack hack
						echo '<div class="filter-group wide-filters-group">';
					} else {
						echo '<div class="filter-group">';
					}
					$feature_name = esc_html( $feature_name );
					echo '<h4 class="feature-name">' . $feature_name . '</h4>';
					echo '<ol class="feature-group">';
					foreach ( $features as $feature => $feature_name ) {
						$feature = esc_attr( $feature );
						echo '<li><input type="checkbox" id="tab-style-' . $feature . '" value="' . $feature . '" /> ';
						echo '<label for="tab-style-' . $feature . '">' . $feature_name . '</label></li>';
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
				<label class="balloon" for="skins-sort" title="<?php _e( 'Sort by Name, Date and License (free or pro) – use arrow to reverse sort order', $this->textdomain ); ?>"><?php _e('Sort by', $this->textdomain); ?></label>
				<select class="sort-by balloon" name="sort-by" title="" autocomplete="off">
					<option value="name" selected="selected"><?php _e( 'Name', $this->textdomain ); ?></option>
					<option value="date"><?php _e( 'Date', $this->textdomain ); ?></option>
					<option value="license"><?php _e( 'License', $this->textdomain ); ?></option>
				</select>
				<a href="javascript:void(0)" class="dashicons dashicons-arrow-down-alt"></a>
			</div>			
		</div>	
		
		<div class="skin-list clearfix">
			<span class="spinner"></span>
			<?php $this->styles_list(); ?>
		</div>
		<div class="skin-details clearfix hidden">
		<?php foreach( $this->cf7s_get_style_list() as $style ) 		
			$this->cf7s_details_view( $style ); ?>
		</div>
		<input type="hidden" value="<?php echo $val; ?>" name="cf7s-style" id="cf7s-style" />
		<?php
    }
	
	
	/**
	 * Output each style in the style tab
	 * 
	 * @deprecated cf7s_show_style_inlist
	 * @param $styles (array) of all the styles
	 * @output (HTML)
	 * @since 0.1.0
	 */
	function styles_list( $styles = array() ) {
		if( ! $styles )
			$styles = $this->cf7s_get_style_list();
		//print_r( $styles );
		
		// Get the current contact form ID, check if request comes from AJAX
		$id = isset( $_POST['id'] ) ? $_POST['id'] : $this->get_id();
		
		foreach( $styles as $key => $style ) {
			$class = $style['dir'] == get_post_meta( $id, 'cf7s_style', true ) ? ' selected' : '';			
			$select_text = $style['dir'] == get_post_meta( $id, 'cf7s_style', true ) ? __( 'Selected', $this->textdomain ) : __( 'Select', $this->textdomain );
			
			$skin_class = $style['dir'] == get_post_meta( $id, 'cf7s_style', true ) ? 'skin skin-selected' : 'skin';
			$style_date = explode( "//", $style['details']['Version Date'] );
			$date = mysql2date( 'U', $style_date[0] );						
			$license = strpos( $style['path'], 'cf7skins-pro' ) !== false ? 'pro' : 'free';	
			?>
			<div class="<?php echo $skin_class; ?>" data-name="<?php echo $key; ?>" data-date="<?php echo $date; ?>" data-license="<?php echo $license; ?>">
				<div class="wrapper">
					<h4 class="skin-name"><?php echo $style['details']['Style Name']; ?></h4>
					<div class="thumbnail">
						<?php $imgpath = $style['path'] . $style['dir'] . '/thumbnail.png'; ?>
						<?php $imgurl = $style['url'] . $style['dir'] . '/thumbnail.png'; ?>
						<img src="<?php echo file_exists( $imgpath ) ? $imgurl : CF7SKINS_URL . 'images/no-preview.png'; ?>" />
					</div>
					<ul class="clearfix skin-action">
						<li><a class="select<?php echo $class; ?> balloon" title="<?php _e( 'Select to apply the Style to your form - is applied to your form once you Save.',$this->textdomain ); ?>" data-value="<?php $this->get_slug_name( $style ); ?>" href="#cf7s-style"><?php echo $select_text; ?></a></li>
						<li><a class="detail balloon" title="<?php _e( 'Show detailed information about this Style - overview of the appearance and layout with description and usage details.' ,$this->textdomain ); ?>" href="#<?php $this->get_slug_name( $style ); ?>"><?php _e('Details', $this->textdomain ); ?></a></li>
					</ul>
				</div>		
			</div>		
			<?php
		}
    }
	
	
    /**
     * Output expanded and details view of selected style
	 * 
	 * @TODO Display in pop-over window
	 * @param $style current processed style
	 * @output (HTML)
     * @since 0.0.1
     */		 
	 function cf7s_details_view( $style ) {
	 
		$class = $style['dir'] == get_post_meta( $this->get_id(), 'cf7s_style', true ) ? ' selected' : ''; // set link class
		$select_text = $style['dir'] == get_post_meta( $this->get_id(), 'cf7s_style', true ) ? __('Selected', $this->textdomain) : __('Select', $this->textdomain);
		?>
		<div id="<?php $this->get_slug_name( $style ); ?>" class="details hidden">
			<div class="details-view">
				<div class="block-thumbnail">
					<img src="<?php echo $style['url'] . $style['dir'] . '/thumbnail.png'; ?>" />
				</div>
					
				<div class="block-details"><div>
					<ul class="clearfix skin-action">
						<li><a class="balloon view" data-value="<?php $this->get_slug_name( $style ); ?>" href="#cf7s-style" title="<?php _e( 'Use Expanded View to view Styles features - shows all form fields available in Contact Form 7.', $this->textdomain ); ?>"><?php _e('Expanded View', $this->textdomain ); ?></a></li>
						<li><a class="balloon select<?php echo $class; ?>" data-value="<?php $this->get_slug_name( $style ); ?>" href="#cf7s-style" title="<?php _e( 'Select to apply the Style to your form - is applied to your form once you Save.', $this->textdomain ); ?>"><?php echo $select_text; ?></a></li>
						<li><a class="balloon close" href="#" title="<?php _e( 'Return to Style Gallery/Grid view.', $this->textdomain ); ?>"><?php _e('Close', $this->textdomain ); ?></a></li>
					</ul>			
					<?php // print_r( $style ); ?>
					<h1><?php echo $style['details']['Style Name']; ?></h1>

					<h4><strong><?php _e('Description', $this->textdomain ); ?></strong></h4>			
					<p class="description"><?php echo $style['details']['Description']; ?></p>
					
					<h4><strong><?php _e('Instructions', $this->textdomain ); ?></strong></h4>
					<p class="description"><?php echo $style['details']['Instructions']; ?></p>
				</div></div>
			</div>
			
			<div class="expanded-view">
				<ul class="clearfix skin-action">
					<li><a class="balloon view" data-value="<?php $this->get_slug_name( $style ); ?>" href="#cf7s-style" title="<?php _e( 'Return to Details View', $this->textdomain ); ?>"><?php _e('Details View', $this->textdomain ); ?></a></li>
					<li><a class="balloon select<?php echo $class; ?>" data-value="<?php $this->get_slug_name( $style ); ?>" href="#cf7s-style" title="<?php _e( 'Select to apply the Style to your form - is applied to your form once you Save', $this->textdomain ); ?>"><?php echo $select_text; ?></a></li>
					<li><a class="balloon close" href="#" title="<?php _e( 'Return to Style Gallery/Grid View', $this->textdomain ); ?>"><?php _e('Close', $this->textdomain ); ?></a></li>
				</ul>
				
				<h1><?php echo $style['details']['Style Name']; ?></h1>
			
				<div class="large-thumbnail">
					<img src="<?php echo $this->get_skin_modal( $style ); ?>" />
				</div>
				<h4><strong><?php _e('Description', $this->textdomain ); ?></strong></h4>			
				<p class="description"><?php echo $style['details']['Description']; ?></p>
				
				<h4><strong><?php _e('Instructions', $this->textdomain ); ?></strong></h4>
				<p class="description"><?php echo $style['details']['Instructions']; ?></p>		
			</div>
		</div>		
		<?php
    }

} // End class
