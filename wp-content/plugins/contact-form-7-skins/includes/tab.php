<?php
/**
 * Admin Tab Class
 * 
 * Implement Tab functionality on CF7 Skins Admin 
 * 
 * @package cf7skins
 * @author Neil Murray
 * @since 0.0.1
 */

 
class CF7_Skins_Admin_Tab extends CF7_Skins_Admin {
	
	// Class variables
    var $tabs, $template, $style;
	
	/**
	 * Class constructor
	 *
	 * @since 0.0.1
	 */	
	function __construct() {
		parent::__construct(); // run parent class
		
		$this->template = new CF7_Skin_Template();
		$this->style = new CF7_Skin_Style();
		
		$this->tabs = array(
			'template' 	=> __( 'Template', 'cf7skins' ),
			'style'		=> __( 'Style', 'cf7skins' )
		);
		
		add_action( 'wp_ajax_cf7s_sort_skin', array( &$this, 'sort_skin' ) );
		add_action( 'wp_ajax_nopriv_cf7s_sort_skin', array( &$this, 'sort_skin' ) );		
	}
	
	
	/**
	 * Sort styles/templates based on selected filter using AJAX
	 * 
	 * @options all, new, search, tag
	 * @output HTML
	 * @since 0.0.1
	 */	
	function sort_skin() {
		// Check the nonce and if not isset the id, just die.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'cf7s' ) || ! isset( $_POST['tab'] ) || ! isset( $_POST['sort'] ) )
			die();
		
		if( strpos( $_POST['tab'], 'template' ) ) {	// templates
			
			$templates = $this->template->cf7s_get_template_list();
			$dates = $new_templates = array();
			
			switch ( $_POST['sort'] ) :
				
				case 'all': // display all			
					$new_templates = $templates;
					break;
					
				case 'new':	// sort by date										
					foreach( $templates as $key => $template ) {
						if( $template['details']['Version Date'] ) {
							$template_date = explode( "//", $template['details']['Version Date'] );
							$template_date = $template_date[0];							
							$dates[$key] = mysql2date( 'U', $template_date );
						}
					}
					
					arsort( $dates );
					$dates = array_slice( $dates, 0, 10, true ); // get only the first 10
					
					foreach( $dates as $key => $date )
						$new_templates[$key] = $templates[$key];						
					break;
					
				case 'search':
					if( ! isset( $_POST['keyword'] ) && empty( $_POST['keyword'] ) ) 
						return;
								
					$keyword = esc_attr( $_POST['keyword'] );
					foreach( $templates as $key => $template ) {
						$match = false;
						foreach( $template['details'] as $details )
							if( stristr( $details, $keyword ) !== false )
								$match = true;
						
						if( $match )
							$new_templates[$key] =  $template;
					}
					break;
									
				case 'tag':
					if( ! isset( $_POST['tags'] ) && empty( $_POST['tags'] ) ) 
						return;
					
					foreach( $templates as $key => $template ) {
						if( empty( $template['details']['Tags'] ) ) // bail early if empty
							continue;
												
						$stripped_tags = str_replace( ' ', '', $template['details']['Tags'] ); // strip all spaces before exploding
						$template_tags = explode( ',', $stripped_tags );
						$match = false;
						
						foreach( $_POST['tags'] as $tag )						
							if( in_array( esc_attr( $tag ), $template_tags ) )
								$match = true;
								
						if( $match )
							$new_templates[$key] = $template;
					}			
					break;						
					
				default:
					foreach( $templates as $key => $template )
						if( in_array( $_POST['sort'], $template['tags'] ) )
							$new_templates[$key] = $template;					
					break;
			
			endswitch;
			
			if( $new_templates )
				$this->template->templates_list( $new_templates );
			else
				echo '<p class="no-themes no-skins">'. __( 'No templates found. Try a different search.', 'cf7skins' ) . '</p>';
		
		} elseif( strpos( $_POST['tab'], 'style' ) ) {	// styles
			
			$styles = $this->style->cf7s_get_style_list();
			$dates = $new_styles = array();
			
			switch ( $_POST['sort'] ) :
				
				case 'all':	// display all
					$new_styles = $styles;
					break;
				
				case 'new':	// sort by date					
					foreach( $styles as $key => $style ) {
						if( $style['details']['Version Date'] ) {
							$skin_date = explode( "//", $style['details']['Version Date'] );
							$skin_date = $skin_date[0];
							$dates[$key] = mysql2date( 'U', $skin_date );					
						}
					}
					
					arsort( $dates );
					$dates = array_slice( $dates, 0, 10, true ); // get only the first 10
					foreach( $dates as $key => $date )
						$new_styles[$key] = $styles[$key];
					break;
					
				case 'search':
					if( ! isset( $_POST['keyword'] ) && empty( $_POST['keyword'] ) ) 
						return;
					
					$keyword = esc_attr( $_POST['keyword'] );
					foreach( $styles as $key => $style ) {
						$match = false;
						foreach( $style['details'] as $details )
							if( stristr( $details, $keyword ) !== false )
								$match = true;
								
						if( $match )	
							$new_styles[$key] = $style;
					}
					break;
					
				case 'tag':
					if( ! isset( $_POST['tags'] ) && empty( $_POST['tags'] ) ) 
						return;
					
					foreach( $styles as $key => $style ) {
						if( empty( $style['details']['Tags'] ) ) // bail early if empty
							continue;
							
						$stripped_tags = str_replace( ' ', '', $style['details']['Tags'] ); // strip all spaces before exploding
						$style_tags = explode( ',', $stripped_tags );
						$match = false;
						
						foreach( $_POST['tags'] as $tag )						
							if( in_array( esc_attr( $tag ), $style_tags ) )
								$match = true;
								
						if( $match )
							$new_styles[$key] = $style;							
					}
					break;						
					
				default:
					foreach( $styles as $key => $style )
						if( in_array( $_POST['sort'], $style['tags'] ) )
							$new_styles[$key] = $style;	
					break;
			
			endswitch;
			
			if( $new_styles )
				$this->style->styles_list( $new_styles );
			else
				echo '<p class="no-themes no-skins">'. __( 'No styles found. Try a different search.', 'cf7skins' ) . '</p>';				
		}
		
		exit();
	}
	
	
	/**
	 * Create tabs for styles or templates
	 * 
	 * Function called in admin.php
	 * 
	 * @output HTML
     * @param $post current post object
     * @param $box metabox arguments
	 * @since 0.0.1
	 */		
	function generate_tab( $post, $box ) {
		$option = get_option( CF7SKINS_OPTIONS );
		$color_scheme = isset( $option['color_scheme'] ) ? $option['color_scheme'] : '';
		
		// Template tooltip text
		// @since 0.2.0
		$this->tabs['template'] = array(
			'label' => __( 'Template', 'cf7skins'),
			'note' => __( 'Each Template acts as an easy to follow guide, which can be adapted to your requirements', 'cf7skins'),
			'help' => __( 'Choose a Template for your form – then you can add, copy or remove fields to match your requirements.', 'cf7skins'),
		);
		
		// Style tooltip text
		// @since 0.2.0
		$this->tabs['style'] = array(
			'label' => __( 'Style', 'cf7skins'),
			'note' => __( 'Each Style covers the full range of standard form elements available within Contact Form 7', 'cf7skins'),
			'help' => __( 'You can change the Style applied to your form by simply selecting a different Style', 'cf7skins')
		);
		?>
		<h2 class="nav-tab-wrapper <?php echo $color_scheme; ?>">
			<?php foreach( $this->tabs as $key => $value ) : ?>				
				<a class="nav-tab nav-tab-<?php echo $key; ?> <?php echo $key == 'template'? 'nav-tab-active' : ''; ?>" href="#tab-<?php echo $key; ?>">
					<?php echo $value['label']; ?>
					<span class="help balloon-hover balloon" title="<?php echo $value['note']; ?>"><?php _e('!', 'cf7skins'); ?></span>
					<span class="help balloon-hover balloon" title="<?php echo $value['help']; ?>"><?php _e('?', 'cf7skins'); ?></span>
				</a>
			<?php endforeach; ?>
			<span class="ext-link">
				<?php $this->links1(); ?>
				<?php $this->links2(); ?>
				<?php $this->links3(); ?>
			</span>
		</h2>
		
		<div class="nav-tab-content <?php echo $color_scheme; ?>">
			<div id="tab-template" class="tab-content clearfix">
				<?php 
					if ( version_compare( get_bloginfo( 'version' ), '4', '<' ) )
						$this->template->cf7s_show_template_list_39();
					else
						$this->template->cf7s_show_template_list();				
				?>
			</div>
			<div id="tab-style" class="tab-content clearfix hidden">
				<?php 
					if ( version_compare( get_bloginfo( 'version' ), '4', '<' ) )
						$this->style->cf7s_show_style_list_39();
					else
						$this->style->cf7s_show_style_list();
				?>
			</div>
		</div>
		<?php
	}
	
	
	/**
	 * Custom link 1
	 * 
	 * @output HTML
	 * @since 0.0.1
	 */		
	function links1() { ?>
		<a href="http://docs.cf7skins.com/"><?php _e('Documentation', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<a href="http://kb.cf7skins.com/faq/"><?php _e('FAQ', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<a href="http://kb.cf7skins.com/category/tutorials/"><?php _e('Tutorials', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<a href="http://kb.cf7skins.com/"><?php _e('Knowledge Base', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<?php
	}
	
	
	/**
	 * Custom link 2
	 * 
	 * @output HTML
	 * @since 0.0.1
	 */		
	function links2() { ?>
		<a href="http://cf7skins.com/support/"><?php _e('Support', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<a href="http://cf7skins.com/blog/"><?php _e('Blog', 'cf7skins'); ?></a>&nbsp;|&nbsp;
		<a href="http://cf7skins.com/pro-version/"><strong><?php _e('Pro Version', 'cf7skins'); ?></strong></a>
		<?php
	}
	
	
	/**
	 * Custom link 3
	 * @output HTML
	 * @since 0.0.1
	 */		
	function links3() { ?>
		<a class="help" href="http://cf7skins.com/" title="Click a link to learn more"><?php _e('?', 'cf7skins'); ?></a>
		<?php
	}

} new CF7_Skins_Admin_Tab();