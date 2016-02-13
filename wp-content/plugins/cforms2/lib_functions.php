<?php
/*
 * Copyright (c) 2006-2012 Oliver Seidel (email : oliver.seidel @ deliciousdays.com)
 * Copyright (c) 2014-2015 Bastian Germann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function cforms2_delete_db_and_deactivate () {
    if( !isset($_POST['cfdeleteall']))
        return;

    if( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        define( 'WP_UNINSTALL_PLUGIN', true );
        require_once(plugin_dir_path(__FILE__) . 'uninstall.php');

        ### deactivate cforms plugin
        $curPlugs = get_option('active_plugins');
        array_splice($curPlugs, array_search( 'cforms2', $curPlugs), 1 ); // Array-function!
        update_option('active_plugins', $curPlugs);
        header('Location: plugins.php?deactivate=true');
        die();
    }

}

### backup/download cforms settings
$buffer='';
function cforms2_download(){
	global $buffer, $cformsSettings;

	if( isset($_REQUEST['savecformsdata']) || isset($_REQUEST['saveallcformsdata']) ) {

		if( isset($_REQUEST['savecformsdata']) ){
	        $noDISP = '1'; $no='';
	        if( $_REQUEST['noSub']<>'1' )
	            $noDISP = $no = $_REQUEST['noSub'];

	    	$buffer .= cforms2_save_array($cformsSettings['form'.$no]);
			$filename = 'form-settings.txt';
		}else{
	    	$buffer .= cforms2_save_array($cformsSettings);
			$filename = 'all-cforms-settings.txt';
		}
        if (ob_get_contents())ob_end_clean();
		header('Pragma: public;');
		header('Expires: 0;');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0;');
		header('Content-Type: application/force-download;');
		header('Content-Type: application/octet-stream;');
		header('Content-Type: application/download;');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		header('Content-Transfer-Encoding: binary;');
        flush();
		print $buffer;
		exit(0);
	}
}

### backup/download cforms settings :: save the array
function cforms2_save_array($vArray){
	global $buffer;
    // Every array starts with chr(1)+"{"
    $buffer .=  "\0{";

    // Go through the given array
    reset($vArray);
	$i=0;
    while (true)
    {
        $Current = current($vArray);
        $MyKey = addslashes(strval( key($vArray) ));
        if (is_array($Current)) {
            $buffer .= $MyKey."\0";
            cforms2_save_array($Current);
            $buffer .= "\0";
        } else {
            $Current = addslashes($Current);
            $buffer .= "$MyKey\0$Current\0";
        }

        ++$i;

        while ( next($vArray)===false ) {
            if (++$i > count($vArray)) break;
        }

        if ($i > count($vArray)) break;
    }
    $buffer .= "\0}";
}



### check user access
function cforms2_check_access_priv($r='manage_cforms'){
	if( !current_user_can($r) ){
		$err = '<div class="wrap"><div id="icon-cforms-error" class="icon32"><br/></div><h2>'.__('cforms error','cforms2').'</h2><div class="updated fade" id="message"><p>'.__('You do not have the proper privileges to access this page.','cforms2').'</p></div></div>';
		die( $err );
    }
}



### add cforms menu
function cforms2_menu() {
	global $wpdb;

    $p = plugin_dir_path(plugin_basename(__FILE__));

	$tablesup = $wpdb->get_var("show tables like '$wpdb->cformssubmissions'") == $wpdb->cformssubmissions;

	$o = $p.'cforms-options.php';

	add_menu_page(__('cformsII', 'cforms2'), __('cformsII', 'cforms2'), 'manage_cforms', $o, '', plugin_dir_url(__FILE__).'images/cformsicon.png');

	add_submenu_page($o, __('Form Settings', 'cforms2'), __('Form Settings', 'cforms2'), 'manage_cforms', $o);
	add_submenu_page($o, __('Global Settings', 'cforms2'), __('Global Settings', 'cforms2'), 'manage_cforms', $p.'cforms-global-settings.php');
	if ( ($tablesup || isset($_REQUEST['cforms_database'])) && !isset($_REQUEST['deletetables']) )
		add_submenu_page($o, __('Tracking', 'cforms2'), __('Tracking', 'cforms2'), 'track_cforms', $p.'cforms-database.php');
	add_submenu_page($o, __('Styling', 'cforms2'), __('Styling', 'cforms2'), 'manage_cforms', $p.'cforms-css.php');
	add_submenu_page($o, __('Help!', 'cforms2'), __('Help!', 'cforms2'), 'manage_cforms', $p.'cforms-help.php');
}


### get current page
function cforms2_get_request_uri() {
	$request_uri = $_SERVER['REQUEST_URI'];
	if ( !isset($_SERVER['REQUEST_URI']) || (strpos($_SERVER['SERVER_SOFTWARE'],'IIS')!==false && strpos($_SERVER['REQUEST_URI'],'wp-admin')===false) ){
	    if(isset($_SERVER['SCRIPT_NAME']))
	        $request_uri = $_SERVER['SCRIPT_NAME'];
	    else
	        $request_uri = $_SERVER['PHP_SELF'];
	}
	return $request_uri;
}


function cforms2_enqueue_script_datepicker($localversion, $dateFormat) {
	global $wp_scripts;
	$suffix = SCRIPT_DEBUG ? '' : '.min';

    $cformsSettings = get_option('cforms_settings');
	$nav = $cformsSettings['global']['cforms_dp_nav'];

    wp_register_script('cforms-calendar', plugin_dir_url(__FILE__) . 'js/cforms.calendar.js', array('jquery', 'jquery-ui-datepicker'), $localversion);
    $day_names = explode( ',', stripslashes($cformsSettings['global']['cforms_dp_days']) );
    wp_localize_script('cforms-calendar', 'cforms2_cal', array(
        'buttonImageOnly'  => true,
        'showOn'           => 'both',
        'dateFormat'       => $dateFormat,
        'dayNamesMin'      => $day_names,
        'dayNamesShort'    => $day_names,
        'monthNames'       => explode( ',', stripslashes($cformsSettings['global']['cforms_dp_months']) ),
        'firstDay'         => stripslashes($cformsSettings['global']['cforms_dp_start']),
        'prevText'         => stripslashes($nav[1]),
        'nextText'         => stripslashes($nav[3]),
        'closeText'        => stripslashes($nav[4]),
        'buttonText'       => stripslashes($nav[5]),
        'changeYear'       => $nav[6]==1,
        'buttonImage'      => plugin_dir_url( __FILE__ ) . 'images/calendar.gif'
    ) );
    wp_enqueue_script('cforms-calendar');
    
	$jqui = $wp_scripts->query('jquery-ui-datepicker');
	$theme = $cformsSettings['global']['cforms_jqueryuitheme'];
	if (empty($theme)) {
		$theme = 'smoothness';
	}
    wp_register_style('jquery-ui-theme', "https://ajax.googleapis.com/ajax/libs/jqueryui/$jqui->ver/themes/$theme/jquery-ui$suffix.css", false, $jqui->ver );
    wp_enqueue_style('jquery-ui-theme');
}

function cforms2_enqueue_style_admin() {
    global $localversion;
    wp_register_style('cforms-admin', plugin_dir_url(__FILE__) . 'cforms-admin.css', false, $localversion );
	wp_enqueue_style('cforms-admin');
}

function cforms2_admin_enqueue_scripts() {
	global $localversion;

	$suffix = SCRIPT_DEBUG ? '' : '.min';
	$r=plugin_dir_url(__FILE__);

    wp_enqueue_style('wp-color-picker');
    
    wp_register_style('jquery-flexigrid', $r . 'js/css/flexigrid.css', false, '1.1' );
    wp_enqueue_style('jquery-flexigrid');

    wp_register_script('jquery-flexigrid',$r."js/jquery.flexigrid$suffix.js",array('jquery'),'1.1');
    wp_enqueue_script('jquery-flexigrid');

    wp_register_script('jquery-jqmodal',$r.'js/jquery.jqmodal.js',array('jquery'),'1.3.0');
    wp_register_script('cforms-admin',$r.'js/cforms.admin.js', array(
        'jquery', 'jquery-jqmodal', 'jquery-ui-draggable', 'jquery-ui-sortable', 'wp-color-picker'
    ), $localversion);
    wp_localize_script('cforms-admin', 'cforms2_nonces', array(
        'installpreset' => wp_create_nonce('cforms2_installpreset'),
        'reset_captcha' => wp_create_nonce('cforms2_reset_captcha'),

		'cforms2_field' => wp_create_nonce('cforms2_field'),

        'deleteentries' => wp_create_nonce('database_deleteentries'),
		'deleteentry'   => wp_create_nonce('database_deleteentry'),
		'dlentries'     => wp_create_nonce('database_dlentries'),
		'getentries'    => wp_create_nonce('database_getentries')
    ) );
    wp_enqueue_script('cforms-admin');

    cforms2_enqueue_style_admin();
    cforms2_enqueue_script_datepicker($localversion, 'dd/mm/yy');
}


### footer
function cforms2_footer() {
	global $localversion;
?>	<p style="padding-top:50px; font-size:11px; text-align:center;">
		<em>
			<?php echo sprintf(__('For more information and support, visit the <strong>cforms</strong> %s support forum %s. ', 'cforms2'),'<a href="http://wordpress.org/support/plugin/cforms2" title="cforms support forum">','</a>') ?>
			<?php _e('Translation provided by Oliver Seidel.', 'cforms2') ?>
		</em>
	</p>
	<p align="center">Version v<?php echo $localversion; ?></p>
<?php
}



### plugin uninstalled?
function cforms2_check_erased() {
	global $cformsSettings;
    if ( $cformsSettings['global']['cforms_formcount'] == '' ){
		?>
		<div class="wrap">
		<div id="icon-cforms-global" class="icon32"><br/></div><h2><?php _e('All cforms data has been erased!', 'cforms2') ?></h2>
	    <p class="ex" style="padding:5px 35px 10px 41px;"><?php _e('Please go to your <strong>Plugins</strong> tab and either disable the plugin, or toggle its status (disable/enable) to revive cforms!', 'cforms2') ?></p>
	    <p class="ex" style="padding:5px 35px 10px 41px;"><?php _e('In case disabling/enabling doesn\'t seem to properly set the plugin defaults, try login out and back in and <strong>don\'t select the checkbox for activation</strong> on the plugin page.', 'cforms2') ?></p>
	    </div>
		<?php
	    return true;
	}
	return false;
}

### add menu items to admin bar
function cforms2_add_admin_bar_root($admin_bar, $id, $ti){
	$arr = array(	'id' => $id, 
					'title' => $ti, 
					'href'  => false 
				);
	$admin_bar->add_node( $arr );
}

function cforms2_add_admin_bar_item($admin_bar, $id,$ti,$hi,$ev,$p = 'cforms-bar'){
	$arr = array(	'parent' => $p, 
					'id' => $id, 
					'title' => $ti, 
					'href'  => '#', 
					'meta'  => array(	'title'  => $hi, 
										'onclick'  => $ev )
				);
	
	$admin_bar->add_node( $arr );
}

function cforms2_get_boolean_from_request($index) {
	if (isset($_REQUEST[$index]) && $_REQUEST[$index])
		return '1';
	else
		return '0';
}

function cforms2_get_from_request($index) {
	if (isset($_REQUEST[$index]) && $_REQUEST[$index])
		return $_REQUEST[$index];
	else
		return '';
}
function cforms2_get_pluggable_captchas() {
	static $captchas = array();
	if (empty($captchas))
		// This filter is meant to add one element to the associative array per cforms2_captcha
		// implementation consisting of the classname as key and object as value
		$captchas = apply_filters('cforms2_add_captcha', $captchas);
	return $captchas;
}
function cforms2_check_pluggable_captchas_authn_users($field_type) {
	$captchas = cforms2_get_pluggable_captchas();
	return array_key_exists($field_type, $captchas) && is_user_logged_in()
	       && !$captchas[$field_type]->check_authn_users();
}

function cforms2_admin_date_format() {
	return __('dd', 'cforms2') .'/'. __('mm', 'cforms2') .'/'. __('yyyy', 'cforms2');
}
