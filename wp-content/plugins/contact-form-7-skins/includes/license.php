<?php
/**
 * License Class
 * 
 * Implements EDD Licensing & Updates
 * 
 * @package cf7skins
 * @author Neil Murray
 * @version 0.0.1
 * @since 0.2.0
 */

class CF7_Skins_License {
	
	
	/**
     * Class constructor
     * @uses http://codex.wordpress.org/Function_Reference/add_action
     * @since 0.2.0
     */		
	function __construct() {

		add_filter( 'cf7skins_setting_tabs', array( &$this, 'license_tab' ), 1, 1 );
		add_action( 'cf7skins_section_license', array( &$this, 'active_sites' ) );
	}


	/**
     * Enable license tab if other plugins need activation
	 * 
	 * @param $tabs
	 * @since 0.5.0
     */
    function license_tab( $tabs ) {		
		$tabs['license'] = __( 'Licenses', CF7SKINS_TEXTDOMAIN );
		return $tabs;
    }
	
	
	/**
	 * Add active sites based on license key
	 * @since 0.2.0
	 */
	function active_sites() {
		echo '
		<br /><hr />
		<h3>Active Licenses</h3>
		<table class="wp-list-table widefat fixed tags" style="background-color: transparent;">
		<tbody>
			<tr>
				<th scope="row"><b>'. __( 'Plugin Name', CF7SKINS_TEXTDOMAIN ) .'</b></th>
				<th scope="row"><b>'. __( 'License Key', CF7SKINS_TEXTDOMAIN ) .'</b></th>
				<th scope="row"><b>'. __( 'Site URL', CF7SKINS_TEXTDOMAIN ) .'</b></th>
			</tr>';

	
			if( is_array( $logs = get_option( 'cf7skins_activation' ) ) ) {
				foreach( $logs as $slug => $log ) {
					echo '<tr>';
						echo '<td>'. $log->item_name .'</td>';
						echo '<td>'. $log->license_key .'</td>';
						
						echo '<td>';
						$sites = json_decode( $log->sites );					
						foreach( $sites as $site ) 
							echo $site . '<br />';
						echo '</td>';
					echo '</tr>';
				}
			}
		
		echo '
		</tbody>
		</table>';		
	}

} new CF7_Skins_License();