<?php
/**
 * CF7 Skins Log Class
 * 
 * @package cf7skins
 * @author Neil Murray 
 * @since 0.5.0
 */

 
class CF7_Skins_Log {
	
	var $textdomain;
	
	/**
     * Class constructor
	 * 
     * @since 0.5.0
     */	
    function __construct() {
		$this->textdomain = CF7SKINS_TEXTDOMAIN;		
		add_filter( 'cf7skins_setting_tabs', array( &$this, 'log_tab' ), 1, 9999 );
		add_filter( 'cf7skins_setting_fields', array( &$this, 'setting_fields' ) );
		add_action( 'cf7skins_setting_info', array( &$this, 'get_version_log' ) );
		add_action( 'cf7skins_setting_info', array( &$this, 'activation_log' ) );
		add_action( 'cf7skins_setting_info', array( &$this, 'deactivation_log' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'cf7skins_settings_enqueue_script', array( $this, 'enqueue_script' ) );
    }
	
	
	/**
     * Add the log tab/section in the settings page
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $tabs (array) array of tabs
     * @since 0.5.0
     */
	function log_tab( $section ) {
		$section['logs'] = __( 'Logs', CF7SKINS_TEXTDOMAIN );
		return $section;		
    }
	
	
	/**
     * Add setting field to the log tab
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $fields (array) array of section fields
     * @since 0.5.0
     */
	function setting_fields( $fields ) {
		$fields['get_version'] = array(
			'label' => __( 'Get Version', CF7SKINS_TEXTDOMAIN ),
			'section' => 'logs',
			'type' => 'info'
		);
		
		$fields['activation'] = array(
			'label' => __( 'Activation', CF7SKINS_TEXTDOMAIN ),
			'section' => 'logs',
			'type' => 'info'
		);
		
		$fields['deactivation'] = array(
			'label' => __( 'Deactivation', CF7SKINS_TEXTDOMAIN ),
			'section' => 'logs',
			'type' => 'info'
		);
		
		return $fields;
    }
	
	
	/**
     * Add setting field to the log tab
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $fields (array) array of section fields
     * @since 0.5.0
     */
	function get_version_log( $args ) {
		if ( 'get_version' != $args['label_for'] )
			return;
		
		if( is_array( $logs = get_option( 'cf7skins_get_version' ) ) ) {

			$string = '';
			foreach( $logs as $key => $value ) {
				$string .= '### '. strtoupper( $key ) .' ###' . "\n\n";
				
				if( $value )
					foreach( $value as $k => $v )
						$string .= "$k: $v". "\n\n";
				
				$string .= "\n\n";
			}
			
			echo '<textarea readonly="readonly" class="cf7skins-log">'. print_r( $string, true ) .'</textarea>';
			
		} else {
			_e( 'Not available', $this->textdomain );
		}
    }
	
	
	/**
     * Add setting field to the log tab
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $fields (array) array of section fields
     * @since 0.5.0
     */
	function activation_log( $args ) {
		if ( 'activation' != $args['label_for'] )
			return;
		
		if( is_array( $logs = get_option( 'cf7skins_activation' ) ) ) {
			$string = '';
			foreach( $logs as $key => $value ) {
				echo '<input type="submit" value="Delete Status ('. $key .')" name="'. $key .'_delete_status" class="button" /> ';
				$string .= '### '. strtoupper( $key ) . ' ###'. "\n\n";
				$string .= print_r( $value, true ). "\n\n";									
			}

			echo '<br /><br /><textarea readonly="readonly" class="cf7skins-log">'. print_r( $string, true ) .'</textarea>';
			
		} else {
			_e( 'Not available', $this->textdomain );
		}
    }	
	
	
	/**
     * Delete plugin activation license status
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $fields (array) array of section fields
     * @since 0.5.0
     */
	function page_init() {
		$logs = get_option( 'cf7skins_activation' );
		
		foreach( $_POST as $key => $value ) {
			
			// Check if user push the delete status 
			if ( strpos( $key, '_delete_status' ) !== false ) {
				
				// Explode and get the first text
				$pieces = explode( '_', $key ); 
				$k = $pieces[0];
				
				unset( $logs[$k] );	// delete selected plugin log
				update_option( 'cf7skins_activation', $logs ); // update the activation log
				delete_option( $k.'_license_status' ); // delete the plugin license status
			}			
		}
    }
	
	
	/**
     * Add setting field to the log tab
	 * 
	 * @filter cf7skins_setting_tabs
	 * @param $fields (array) array of section fields
     * @since 0.5.0
     */
	function deactivation_log( $args ) {
		if ( 'deactivation' != $args['label_for'] )
			return;
		
		if( is_array( $logs = get_option( 'cf7skins_deactivation' ) ) ) {
			$string = '';
			foreach( $logs as $key => $value ) {
				$string .= '### '. strtoupper( $key ) .' ###'. "\n\n";
				$string .= print_r( $value, true ). "\n\n";				
			}
			
			echo '<textarea readonly="readonly" class="cf7skins-log">'. print_r( $string, true ) .'</textarea>';
		} else {
			_e( 'Not available', $this->textdomain );
		}
    }	
	
	
	/**
     * Custom script and style for the setting page panel
	 * 
	 * @param none
     * @since 1.1.1
     */
	function enqueue_script() {
		?>
		<style type="text/css">
		.cf7skins-log {
			background: transparent;
			display: block;
			font-family: Menlo,Monaco,monospace;
			height: 400px;
			overflow: auto;
			font-size: 11px;
			white-space: pre;
			width: 100%;"
		}
		</style>
		<?php
    }
			 		
} // End class

$option = get_option( CF7SKINS_OPTIONS );
if( isset( $option['display_log'] ) && $option['display_log'] )	
	new CF7_Skins_Log(); // Create new instance
