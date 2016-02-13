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

### Validating non Ajax form submission

$inpFieldArr = array(); // for var[] type input fields

$cflimit = '';
$filefield = 0;

$captchaopt = $cformsSettings['global']['cforms_captcha_def'];

###debug
cforms2_dbg("lib_validate.php: validating fields for form no. $no");
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&
	 empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0 ) {

	$all_valid = false;
	$err = 3;
	$fileerr = $cformsSettings['global']['cforms_upload_err3'];
}

cforms2_dbg("REQUEST:".print_r($_REQUEST,1));
cforms2_dbg("FILES:".print_r($_FILES,1));
$off = 0;

if ($all_valid) for ($i = 1; $i <= $field_count; $i++) {	
	if ( !$custom )
		$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_'.((int)$i+(int)$off)]);
	else
		$field_stat = explode('$#$', $customfields[((int)$i+(int)$off) - 1]);
	$field_stat[] = "";
	$field_stat[] = "";
	$field_stat[] = "";

	### filter non input fields
	if ( $field_stat[1] == 'fieldsetstart' || $field_stat[1] == 'fieldsetend' || $field_stat[1] == 'textonly' ) {
		$validations[$i+$off] = 1;   ### auto approved
		continue;
	} 

	while ( $field_stat[1] == 'fieldsetstart' || $field_stat[1] == 'fieldsetend' || $field_stat[1] == 'textonly' ) {
		$off++;

		if ( !$custom )
			$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ((int)$i+(int)$off)]);
		else
			$field_stat = explode('$#$', $customfields[((int)$i+(int)$off) - 1]);
		$field_stat[] = "";
		$field_stat[] = "";
		$field_stat[] = "";

		if( $field_stat[1] == '')
			break 2; ### all fields searched, break both while & for
	}


	### custom error set?
	$c_err = explode('|err:', $field_stat[0], 2);
	$c_err[] = "";
	$c_title = explode('|title:', $c_err[0], 2);

	$field_name = $c_title[0];
	$field_type = $field_stat[1];
	$field_required = $field_stat[2];
	$field_emailcheck = $field_stat[3];

	cforms2_dbg("\t ...validating field $field_name");

	### ommit certain fields; validation only!
	if( in_array($field_type,array('cauthor','url','email')) ){
		if ( $user->ID ){
			$validations[$i+$off] = 1;   ### auto approved
			continue;
		}
	}

	$captchas = cforms2_get_pluggable_captchas();

	### captcha not for logged in users
	$jump = ($field_stat[1] == 'captcha') && is_user_logged_in() && $captchaopt['fo']<>'1';
	$jump = $jump || cforms2_check_pluggable_captchas_authn_users($field_stat[1]);

	if ( $jump )
		continue;

	### input field names & label
	$custom_names = ($cformsSettings['form'.$no]['cforms'.$no.'_customnames']=='1')?true:false;
	$isFieldArray = false;

	if ( $custom_names ) {

		###preg_match('/^([^#\|]*).*/',$field_name,$input_name);
		$tmpName = $field_name; ###hardcoded for now

		###debug
		cforms2_dbg("\t\t ...custom names/id's...($tmpName)");

		if ( strpos($tmpName,'[id:')!==false ){

			$isFieldArray = strpos($tmpName,'[]');
			preg_match('/^([^\[]*)\[id:([^\|\]]+(\[\])?)\]([^\|]*).*/',$tmpName,$input_name);
			$field_name = $input_name[1].$input_name[4];
			$trackingID	= cforms2_sanitize_ids( $input_name[2] );

/* 
	First Name[id:firstname]yy||^[A-Za-z ]*$Array
	(
		[0] => First Name[id:firstname]yy||^[A-Za-z ]*$
		[1] => First Name
		[2] => firstname
		[3] => 
		[4] => yy
	)
*/
			if (!isset($_REQUEST[ $trackingID ])) 
				$_REQUEST[ $trackingID ]= "";
			if ( $isFieldArray ) {				

				if( !isset($inpFieldArr[$trackingID]) || !$inpFieldArr[$trackingID] || $inpFieldArr[$trackingID]=='' )
					$inpFieldArr[$trackingID]=0;

				$current_field	= $_REQUEST[ $trackingID ][$inpFieldArr[$trackingID]++];

			} else 
				$current_field	= $_REQUEST[ $trackingID ];

			cforms2_dbg("\t\t\t ...currentField field_name = \"$field_name\", current_field = $current_field, request-id = $trackingID");


		} else {
			if ( strpos($tmpName,'#')!==false && strpos($tmpName,'#')==0 )
				preg_match('/^#([^\|]*).*/',$field_name,$input_name); ###special case with checkboxes w/ right label only & no ID
			else
				preg_match('/^([^#\|]*).*/',$field_name,$input_name); ###just take front part
			$current_field = isset($_REQUEST[ cforms2_sanitize_ids($input_name[1]) ]) ? $_REQUEST[ cforms2_sanitize_ids($input_name[1]) ]:"" ;
		}

	}
	else
		$current_field = isset($_REQUEST['cf'.$no.'_field_' . ((int)$i+(int)$off)]) ? $_REQUEST['cf'.$no.'_field_' . ((int)$i+(int)$off)] : "";

	if( in_array($field_type,array('comment','url','email','cauthor')) )  ### WP comment field name exceptions
		$current_field = $_REQUEST[$field_type];

	$current_field = is_array($current_field) ? $current_field : stripslashes($current_field);

	if ( $field_emailcheck ) {  ### email field

		###debug
		cforms2_dbg("\t\t ...found email field ($current_field) is_email = ".cforms2_is_email( $current_field ));

		### special email field in WP Commente
		if ( $field_type=='email' )
			$validations[$i+$off] = cforms2_is_email( $_REQUEST['email']) || (!$field_required && $_REQUEST['email']=='');
		else
			$validations[$i+$off] = cforms2_is_email( $current_field ) || (!$field_required && $current_field=='');

		if ( !$validations[$i+$off] && $err==0 ) $err=1;

	}
	else if( array_key_exists($field_type, $captchas) ){  ### pluggable captcha

		$validations[$i+$off] = 1;
		if ( !$captchas[$field_type]->check_response($_REQUEST[$field_type . '/hint'], $_REQUEST[$field_type]) ) {
			$validations[$i+$off] = 0;
			$err = $err ? $err : 2;
		}

	}
	else if( $field_type == 'captcha' ){  ### captcha verification

		$validations[$i+$off] = 1;

		$a = explode('+',$_COOKIE['turing_string_'.$no]);

		$a = $a[1];
		$b = md5( ($captchaopt['i'] == 'i')?strtolower($_REQUEST['cforms_captcha'.$no]):$_REQUEST['cforms_captcha'.$no]);

		if ( $a <> $b ) {
			$validations[$i+$off] = 0;
			$err = !($err)?2:$err;
		}

	}
	else if( $field_required ) { ### just required

		###debug
		cforms2_dbg("\t\t ...is required! check: current_field=$current_field");

		if( in_array($field_type,array( 'html5color','html5date','html5datetime','html5datetime-local','html5email','html5month','html5number','html5range','html5search','html5tel','html5time','html5url','html5week',
										'cauthor','url','comment','pwfield','textfield','datepicker','textarea','yourname','youremail','friendsname','friendsemail')) ){

			$validations[$i+$off] = ($current_field=='')?false:true;

		} else if( $field_type=="checkbox" ) {

			$validations[$i+$off] = ($current_field=='')?false:true;

		} else if( $field_type=="selectbox" || $field_type=="emailtobox" ) {

			$validations[$i+$off] = !($current_field == '-' );

		} else if( $field_type=="multiselectbox" ) {

			### how many multiple selects ?
			$all_options = $current_field;
			if ( count($all_options) <= 1 && $all_options[0]=='' )
				$validations[$i+$off] = false;
			else
				$validations[$i+$off] = true;

		} else if( $field_type=="upload" ) {  ### prelim upload check

			$validations[$i+$off] = !($_FILES['cf_uploadfile'.$no]['name'][$filefield]=='');
			if ( !$validations[$i+$off] && $err==0 ) {
				$err=3;
				$fileerr = $cformsSettings['global']['cforms_upload_err2'];
			}
		} else if( in_array($field_type,array('cauthor','url','email','comment')) ) {

			$validations[$i+$off] = ($_REQUEST[$field_type]=='')?false:true;

		} else if( $field_type=="radiobuttons" ) {

			$validations[$i+$off] = ($current_field=='')?false:true;

		}

		if ( !$validations[$i+$off] && $err==0 )
			$err=1;

	}
	else {
		$validations[$i+$off] = 1;
	}

	if ( $field_type=="upload" && isset($_FILES['cf_uploadfile'.$no]['name'][$filefield]) ) {
		$filefield++;
	}

	### REGEXP now outside of 'is required'
	if( in_array($field_type,array('cauthor','url','comment','pwfield','textfield','datepicker','textarea','yourname','youremail','friendsname','friendsemail')) ){

		### regexp set for textfields?
		$obj = explode('|', $c_title[0], 3);
		$obj[] = "";
		$obj[] = "";

		if ( $obj[2] <> '') { ### check against other field!

			if (  isset($_REQUEST[$obj[2]]) && $_REQUEST[$obj[2]]<>'' ){

				if( $current_field <> $_REQUEST[$obj[2]] )
					$validations[$i+$off] = false;
			}
			else { ### classic regexp
				$reg_exp = str_replace('/','\/',stripslashes($obj[2]) );

				###debug
				cforms2_dbg("\t\t ...REGEXP check content: $current_field =? $reg_exp");

				### multi-line textarea regexp trick
				if( $field_type == 'textarea' )
					$valField = (string)str_replace(array("\r", "\r\n", "\n"), ' ', $current_field);
				else
					$valField = $current_field;

				if( $current_field<>'' && !preg_match('/'.$reg_exp.'/', $valField) ){
					$validations[$i+$off] = false;
				}
			}
		}
		if ( !$validations[$i+$off] && $err==0 ) $err=1;
	}



	$all_valid = $all_valid && $validations[$i+$off];

	if ( $c_err[1] <> '' && $validations[$i+$off] == false ){
		$c_errflag=4;

		if ( $cformsSettings['global']['cforms_liID']=='1' ){
			$custom_error .= '<li><a href="#li-'.$no.'-'.($i+$off).'">'.stripslashes($c_err[1]).' &raquo;</li></a>';
		} else
			$custom_error .= '<li>' . stripslashes($c_err[1]) . '</li>';

	}

}


###
### have to upload a file?
###

global $file;
$file=array();

if( isset($_FILES['cf_uploadfile'.$no]) && $all_valid){

	for ($i=0; $i<$filefield; $i++) {
		$file['name'][] = $_FILES['cf_uploadfile'.$no]['name'][$i];
		$file['type'][] = $_FILES['cf_uploadfile'.$no]['type'][$i];
		$file['tmp_name'][] = $_FILES['cf_uploadfile'.$no]['tmp_name'][$i];
		$file['error'][] = $_FILES['cf_uploadfile'.$no]['error'][$i];
		$file['size'][] = $_FILES['cf_uploadfile'.$no]['size'][$i];
	}

	$i=0;
	foreach( $file['name'] as $value ) {

		if(!empty($value)){   ### this will check if any blank field is entered
			

			if ( function_exists('my_cforms_logic') )
                $file['name'][$i] = my_cforms_logic($_REQUEST,$_FILES['cf_uploadfile'.$no]['name'][$i],"filename");

            $fileerr = '';
			### A successful upload will pass this test. It makes no sense to override this one.
			if ( $file['error'][$i] > 0 )
					$fileerr = $cformsSettings['global']['cforms_upload_err1'];

			### A successful upload will pass this test. It makes no sense to override this one.
			$fileext[$i] = strtolower( substr($value,strrpos($value, '.')+1,strlen($value)) );
			$allextensions = explode(',' ,  preg_replace('/\s/', '', strtolower($cformsSettings['form'.$no]['cforms'.$no.'_upload_ext'])) );

			if ( !in_array($fileext[$i], $allextensions) && $allextensions[0] !== "*")
				$fileerr = $cformsSettings['global']['cforms_upload_err5'];

			### A non-empty file will pass this test.
			if ( !( $file['size'][$i] > 0 ) )
				$fileerr = $cformsSettings['global']['cforms_upload_err2'];

				### A non-empty file will pass this test.
				if ( (int)$cformsSettings['form'.$no]['cforms'.$no.'_upload_size'] > 0 ) {
					if ( $file['size'][$i] >= (int)$cformsSettings['form'.$no]['cforms'.$no.'_upload_size'] * 1024 )
						$fileerr = $cformsSettings['global']['cforms_upload_err3'];
				}

			### A properly uploaded file will pass this test. There should be no reason to override this one.
			if (! is_uploaded_file( $file['tmp_name'][$i] ) )
				$fileerr = $cformsSettings['global']['cforms_upload_err4'];

			if ( $fileerr <> '' ){

				$err = 3;
				$all_valid = false;

			} ### file uploaded

        } ### if !empty
		$i++;

    } ### while all file

} ### no file upload triggered
###
### what kind of error message?
###
switch($err){
	case 0: break;
	case 1:
		$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), '<span>'.$cformsSettings['form'.$no]['cforms'.$no.'_failure'].'</span>' );
		break;
	case 2:
		$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), '<span>'.$cformsSettings['global']['cforms_codeerr'].'</span>' );
		break;
	case 3:
		$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), '<span>'.$fileerr.'</span>');
		break;
	case 4:
		$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), '<span>'.$cformsSettings['form'.$no]['cforms'.$no.'_failure'].'</span>' );
		break;

}
if ( $err<>0 && $c_errflag )
	$usermessage_text .= '<ol>'.$custom_error.'</ol>';

### proxy functions
function cforms2_is_email($string){
	return preg_match("/^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,63})$/i", $string);
}
