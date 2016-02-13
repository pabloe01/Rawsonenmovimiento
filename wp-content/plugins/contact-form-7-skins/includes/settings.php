<?php
/**
 * Settings Class
 * 
 * Implement all functionality on CF7 Skins Settings page.
 * 
 * @package cf7skins
 * @author Neil Murray
 * @since 0.1.0
 */
 
 
class CF7_Skins_Settings {
	
    // Holds the values to be used in the fields callbacks
    private $options;
	
	// Define class variables
	var $tabs, $section, $fields, $slug, $textdomain;
	
    /**
     * Class constructor
	 * 
     * @uses http://codex.wordpress.org/Function_Reference/add_action
     * @uses http://codex.wordpress.org/Function_Reference/add_filter
	 * @filter cf7skins_setting_tabs
	 * @since 0.1.0
     */
    function __construct() {
		$this->slug = CF7SKINS_OPTIONS;
		$this->textdomain = CF7SKINS_TEXTDOMAIN;			
		
		$this->section = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$this->options = get_option( $this->slug );
		
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

	
    /**
     * Add CF7 Skins Settings page as submenu under Contact Form 7 plugin menu item
	 * 
	 * @see add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );	 
	 * @since 0.1.0
     */
    function add_menu_page() {
		$this->tabs = apply_filters( 'cf7skins_setting_tabs', array( 
			'general'	=> __( 'General', $this->textdomain ),
			'advanced'	=> __( 'Advanced', $this->textdomain )			
		));	
		
        $page = add_submenu_page( 'wpcf7', 'CF7 Skins Settings', 'CF7 Skins', 'manage_options', 'cf7skins', array( $this, 'settings_fields' ) );
		
		add_action( 'admin_print_scripts-' . $page, array( &$this, 'enqueue_script' ) );
    }		
	
	
	/**
     * Add Settings jQuery
	 * 
	 * @since 0.1.0
     */
    function enqueue_script() {
		wp_enqueue_script( $this->slug, CF7SKINS_URL . 'js/jquery.settings.js', array( 'jquery', 'jquery-ui-sortable' ), CF7SKINS_VERSION );
		
		// Filter action @since 1.1.1
		do_action( 'cf7skins_settings_enqueue_script' );
    }
	
	
    /**
     * Display CF7 Skins Settings page in Tabs
	 * 
	 * Output nonce, action, and option_page fields for a settings page settings_fields( $option_group )
	 * @see settings_fields ( string $option_group = null )
	 * Print out the settings fields for a particular settings section
	 * @see do_settings_fields ( string $page = null, section $section = null )
     * @since 0.1.0
     */
    function settings_fields() {
		?>
		<div class="wrap">
			<?php //echo '<pre style="font-size:10px;line-height:10px;">'. print_r( $this->options, true ) .'</pre>'; ?>
			<h2><?php _e( 'Contact Form 7 Skins Settings', $this->textdomain ); ?></h2><br />
			<h2 class="nav-tab-wrapper">
				<?php	
				foreach( $this->tabs as $tab => $name ) {
					$class = ( $tab == $this->section ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab$class' href='?page=". $this->slug ."&tab=$tab'>$name</a>";
				}
				?>
			</h2>     
            <form method="post" action="options.php">
            <?php		
				settings_fields( $this->slug );
				echo "<input name='{$this->slug}[section]' value='{$this->section}' type='hidden' />";	// handle section
				echo '<table class="form-table">';
				do_settings_fields( $this->slug, $this->section );
				echo '</table>';
				do_action( "cf7skins_section_{$this->section}" ); 
				submit_button( __( 'Save Changes', $this->textdomain ) );
            ?>
            </form>
        </div>
        <?php
    }
	
	
    /**
	 * Register and add settings
	 * 
	 * @see register_setting( $option_group, $option_name, $sanitize_callback );
	 * @see add_settings_section( $id, $title, $callback, $page );
	 * @see add_settings_field( $id, $title, $callback, $page, $section, $args );
	 * @since 0.1.0
     */
    function page_init() {
		if( ! isset( $this->tabs ) )
			return;
		
        register_setting( $this->slug, $this->slug, array( $this, 'sanitize_callback' ) );		
		
		// Add section for each tab on Settings page
		foreach( $this->tabs as $tab => $name ) {
			add_settings_section( $tab, '',  '', $this->slug );
		}
		
		/*
		// Get styles list for the custom enqueue styles
		$styles = array();
		$get_styles = CF7_Skin_Style::cf7s_get_style_list();
		foreach( $get_styles as $k => $v )
			$styles[$k] = $v['details']['Style Name'];
		*/

		/* Add Initial Fields
		 * 
		 * Licenses are added via apply_filters () in license.php 
		 * 
		 * @filter cf7skins_setting_fields
		 * @since 0.2.0. 
		 */
		$fields = apply_filters( 'cf7skins_setting_fields', array(
			'color_scheme' => array( 
				'section' => 'general',
				'label' => __( 'Color Scheme', $this->textdomain ),
				'type' => 'color-scheme',
				'default' => 'default',
				'description' => __( 'Select color scheme for CF7 Skins interface.', $this->textdomain ),
			),
			/*'custom' => array( 
				'section' => 'advanced',
				'label' => __( 'Custom Styles & Scripts', $this->textdomain ),
				'type' => 'textarea',
				'description' => __( 'Print your custom scripts or styles with the tag to push to the wp_head().', $this->textdomain ),
			),
			'enqueue_styles' => array( 
				'section' => 'advanced',
				'label' => __( 'Enqueue Styles', $this->textdomain ),
				'type' => 'checkbox',
				'default' => array(),
				'detail' => $styles,
				'description' => __( 'Enqueue selected styles for whole site pages header.', $this->textdomain ),
			),
			*/
			'display_log' => array( 
				'section' => 'advanced',
				'label' => __( 'Display Log', $this->textdomain ),
				'type' => 'checkbox',
				'default' => false,
				'detail' => __( 'Displays plugin log tab.', $this->textdomain ),
			),
			'delete_data' => array( 
				'section' => 'advanced',
				'label' => __( 'Delete Settings', $this->textdomain ),
				'type' => 'checkbox',
				'default' => false,
				'detail' => __( 'Remove all plugin data on plugin deletion.', $this->textdomain ),
			),			
		));		
		
		$this->fields = $fields; // @since 0.5.0 set class object
		
		// add_settings_field( 'color_scheme', __('Color Scheme', $this->textdomain), array( $this, 'setting_field' ), $this->slug, 'general', array( 'label_for' => 'color_scheme', 'type' => 'color-scheme', 'default' => true, 'detail' => __('Color Scheme', $this->textdomain) ) );
		
		// Set function setting_field () as callback for each field
		foreach( $fields as $key => $field ) {
			$field['label_for'] = $key;
			add_settings_field( $key, $field['label'], array( $this, 'setting_field' ), $this->slug, $field['section'], $field );		
		}
		
		// Create initialize settings if this is the first install
		if( ! get_option( $this->slug ) ) {
			global $wp_settings_fields;
			$sections = $wp_settings_fields[$this->slug];
			$array = array();
			foreach( $sections as $fields ) {
				foreach( $fields as $k => $field ) {
					$array[$k] = isset( $field['args']['default'] ) ? $field['args']['default'] : '';
				}
			}
			update_option( $this->slug, $array );
		}
    }
	
	
    /**
     * Sanitize each setting field as needed
	 * 
     * @param array $input Contains all settings fields as array keys
     * @since 0.1.0
     */
    function sanitize_callback( $inputs ) {
		// return if inputs are empty
		if( ! isset( $inputs['section'] ) )
			return $inputs;
		
		global $wp_settings_fields;
		$section = $wp_settings_fields[$this->slug][$inputs['section']];
		$old_option = $this->options;
		
		foreach( $inputs as $k => $input ) {
			$type = $section[$k]['args']['type'];
			
			if( 'text' == $type ) {
				$this->options[$k] = sanitize_text_field( $input );
			} elseif( 'number' == $type ) {
				$this->options[$k] = absint( $input );
			} elseif( 'url' == $type ) {
				$this->options[$k] = esc_url( $input );
			} else {
				$this->options[$k] = $input;
			}
		}
		
		// Special case for checkbox, we need to loop through setting fields
		foreach( $section as $k => $field )
			if( 'checkbox' == $field['args']['type'] )
				if( ! isset( $inputs[$k] ) )
					$this->options[$k] = false;			
		
		/* $this->options is the new and $inputs is old
		 * 
		 * Sanitized Licenses are added via apply_filters () in license.php 
		 * 
		 * @filter cf7skins_setting_sanitize
		 * @since 0.2.0
		 */
        return apply_filters( 'cf7skins_setting_sanitize', $this->options, $old_option, $inputs );
    }

	
    /**
     * Print the option field in the section
	 * 
     * @params $args
     * @since 0.1.0
     */	
    public function setting_field( $args ) {
		// echo '<pre style="font-size:10px;line-height:10px;">'. print_r( $this->options, true ) .'</pre>';
		// echo '<pre style="font-size:10px;line-height:10px;">'. print_r( $args, true ) .'</pre>';
		extract( $args );
		$id = isset( $label_for ) ? $label_for : '';  // Use label_for arg as id if set
		switch ( $type ) {
			case 'textarea':
				printf( '<textarea id="%1$s" name="'.$this->slug.'[%1$s]" cols="50" rows="5" class="large-text">%2$s</textarea>',
					$id, isset( $this->options[$id] ) ? $this->options[$id] : '' );
				break;
				
			case 'checkbox':
				if ( is_array( $detail ) ) {
					$value = isset( $this->options[$id] ) ? $this->options[$id] : array();
					foreach( $detail as $k => $v )
						printf( '<label><input id="%1$s" name="'.$this->slug.'[%1$s][%2$s]" type="checkbox" value="1" %3$s />%4$s</label><br />',
							$id, $k, isset( $value[$k] ) ? 'checked="checked"' : '', $v );

				} else {				
					$value = isset( $this->options[$id] ) ? $this->options[$id] : false;
					printf( '<label><input id="%1$s" name="'.$this->slug.'[%1$s]" type="checkbox" value="1" %2$s />%3$s</label>',
						$id, $value ? 'checked="checked"' : '', $detail );
					}
				break;
				
			case 'color-scheme':
				foreach ( $this->color_scheme() as $color => $color_info ) {
					$selected = $this->options[$id] == $color ? ' selected' : '';
					echo'
					<div class="color-option'.$selected.'">
						<input type="radio" '.checked( $this->options[$id], $color, false ).' class="tog" value="'. $color .'" name="'.$this->slug.'[color_scheme]" />
						<input type="hidden" value="'. $color_info->url .'" class="css_url" />					
						<label for="admin_color_fresh">'.$color_info->name.'</label>
						<table class="color-palette">
							<tbody>
								<tr>';
									foreach( $color_info->colors as $bgcolor )									
										echo '<td style="background-color: '.$bgcolor.'">&nbsp;</td>';
								
								echo'
								</tr>
							</tbody>
						</table>
					</div>';
				}
				break;
				
			case 'license':
				$disable = ( $status !== false && $status == 'valid' ) ? ' disabled' : '';
				printf( '<input id="%1$s" name="'.$this->slug.'[%1$s]" value="%2$s" class="regular-text license-key" type="text"'. $disable .'/>',
					$id, isset( $this->options[$id] ) ? $this->options[$id] : '', $type );
				if( $status !== false && $status == 'valid' ) {
					echo '<span style="color:green;padding:0 10px;font-size:12px">'. __( 'active', CF7SKINS_TEXTDOMAIN ) .'</span>';
					echo '<input type="submit" class="button" name="'. "{$this->slug}[$id" .'_deactivate]" value="'. __('Deactivate License',CF7SKINS_TEXTDOMAIN) .'"/>';
				} else {
					if( $status == 'invalid' )
						echo '<span style="color:red;padding:0 10px;font-size:12px">'. __( 'invalid', CF7SKINS_TEXTDOMAIN ) .'</span>';
					
					echo '&nbsp;<input type="submit" class="button" name="'. "{$this->slug}[$id" .'_activate]" value="'. __('Activate License',CF7SKINS_TEXTDOMAIN) .'"/>';
				}					
				break;				
				
			case 'info':
				do_action( 'cf7skins_setting_info', $args );
				break;
				
			case 'text':
			case 'number':
			case 'url':
			default:
				printf( '<input id="%1$s" name="'.$this->slug.'[%1$s]" value="%2$s" class="regular-text" type="%3$s" />',
					$id, isset( $this->options[$id] ) ? $this->options[$id] : '', $type );
				break;
		}
		
		if( isset( $description ) ){
			switch ( $type ) {
				case 'license':  // Don't display activation instructions if valid license
					if( $status !== false && $status == 'valid' ) {
					break;
					}
				default:
					echo '<p class="description">'. $description .'</p>';
					break;
			}
		}
    }
	
	
	/**
     * Custom option for the color scheme
	 * 
	 * @filter cf7skins_color_scheme
     * @since 0.1.0
     */	
	function color_scheme() {
		$colors = array();
		
		$color = new stdClass();
		$color->name   = __('Default', $this->textdomain);
		$color->url    = CF7SKINS_URL . 'css/admin.css';
		$color->colors = array( '#94B2CE', '#C4D9EE', '#70A74A', '#C9F4B0' );
		$colors['default'] = $color;
		
		$color = new stdClass();
		$color->name   = __('Wheat', $this->textdomain);
		$color->url    = CF7SKINS_URL . 'css/admin.css';
		$color->colors = array( '#EEEEEE', '#E5E5E5', '#E5EAA8', '#DAE193' );
		$colors['wheat'] = $color;
		
		$color = new stdClass();
		$color->name   = __('Ocean', $this->textdomain);
		$color->url    = CF7SKINS_URL . 'css/admin.css';
		$color->colors = array( '#ECF7FB', '#CDE8F1', '#D6F9C1', '#C2F0A5' );
		$colors['ocean'] = $color;		
		
		return apply_filters( 'cf7skins_color_scheme', $colors );
	}
	
} new CF7_Skins_Settings();