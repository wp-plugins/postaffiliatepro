<?php
/*
Plugin Name: Post Affiliate Pro
Plugin URI: http://www.qualityunit.com/#
Description: Plugin that enable user signup integration integration with Post Affiliate Pro
Author: QualityUnit
Version: 1.0.11
Author URI: http://www.qualityunit.com
License: GPL2
*/

function apiFileExists() {
	return file_exists(WP_PLUGIN_DIR . '/postaffiliatepro/PapApi.class.php');
}

//images subdirectory
function getPluginImgUrl() {	
	$url = WP_PLUGIN_URL . '/postaffiliatepro/img/';
	return $url;
}

if (apiFileExists()) {
	require_once 'PapApi.class.php';
}

function init_admin() {			
	register_setting('pap_config_general_page', 'pap-url');
	register_setting('pap_config_general_page', 'pap-merchant-name');
	register_setting('pap_config_general_page', 'pap-merchant-password');
	register_setting('pap_config_signup_page', 'pap-sugnup-default-parent');
	register_setting('pap_config_signup_page', 'pap-sugnup-default-status');
	register_setting('pap_config_signup_page', 'pap-sugnup-sendconfiramtionemail');
}
		

function pap_add_primary_config_menu() {
	 add_menu_page(__('Post Affiliate Pro','pap-menu'), __('PostAffiliatePro','pap-menu'), 'manage_options', 'pap-top-level-options-handle', 'pap_config_general_page', getPluginImgUrl() . '/menu-icon.png');
	 add_submenu_page('pap-top-level-options-handle', __('Signup','signup-config'), __('Signup options','signup-config'), 'manage_options', 'signup-config-page', 'pap_config_signup_page');
}

function pap_config_general_page() {
    echo '<div class="wrap"><h2>PAP General options </h2>';
    if (!apiFileExists()) {
        echo 'API file does not exist! Upload it first.';
    }
	echo '<form method="post" action="options.php">';
	settings_fields('pap_config_general_page');
    echo '<table class="form-table">					
        <tr valign="top">
        <th scope="row" valign="middle">Post Affiliate Pro URL</th>
        <td><input type="text" size="100" name="pap-url" value="'. get_option('pap-url').'" /></td>        
        </tr>
        <tr>
            <td colspan="2">Example: http://www.yoursite.com/affiliate/scritps</td>
        </tr>                
    </table>';
    echo '<table class="form-table">
        <tr valign="top">
        <th scope="row" valign="middle">Merchant Name</th>
        <td><input type="text" size="40" name="pap-merchant-name" value="'. get_option('pap-merchant-name').'" /></td>        
        </tr>               
    </table>';
    echo '<table class="form-table">
        <tr valign="top">
        <th scope="row" valign="middle">Merchant Password</th>
        <td><input type="password" size="20" name="pap-merchant-password" value="'. get_option('pap-merchant-password').'" /></td>        
        </tr>               
    </table>';
    
    echo '<p class="submit">
    <input type="submit" class="button-primary" value="'. _('Save Changes') .'" />					
    </p></form></div>';
}

function print_option($value, $caption, $selectedValue = null, $return = false) {
	if ($selectedValue!=null && $value == $selectedValue) {
		$selected = 'selected';
	} else {
		$selected = '';
	}
	$out = '<option value="'.$value.'" '.$selected.'>'.$caption.'</option>'; 
	if  ($return) {
		return $out;
	}	
	echo $out;
}

function beginForm($formName) {
	echo  '<form method="post" action="options.php">';
    settings_fields('pap_config_signup_page');						
    echo '<table class="form-table">';				
}

function insertFormOption($caption, $description, $optionCode) {	
	echo '<tr valign="top">
            <th scope="row" valign="middle" style="width:400px;">'.$caption.'</th>
            <td style="padding-bottom:2px;">';    
    
    echo $optionCode;
    
    echo '</td>';        
    echo '<tr><td colspan="2" style="padding-top:0px;padding-bottom:15px">'.$description.'</td></tr>';
    echo '</tr>';
}

function pap_config_signup_page() {
    echo "<h2>" . __( 'Signup Options', 'signup-config' ) . "</h2>";
	if (!apiFileExists()) {
        echo 'API file does not exist! Upload it first.';
        return;
    }
    $session = new Gpf_Api_Session(get_option('pap-url') . "/server.php");
			$login = $session->login(get_option('pap-merchant-name'), get_option('pap-merchant-password'));						
			if($login == false) {				
				return;
			}	
	$request = new Pap_Api_AffiliatesGrid($session);
	$request->setLimit(0, 5000);
	
	try {
    $request->sendNow();
    } catch(Exception $e) {
      echo "API call error: ".$e->getMessage();
    }

    $grid = $request->getGrid();

    $recordset = $grid->getRecordset();
    
    echo 'These options will apply when a new affiliate is automatically created with Post Affiliate Pro.';

	beginForm('pap_config_signup_page');
	
	$selectedOption = get_option('pap-sugnup-default-parent');
	$papsugnupdefaultparent = '<select name="pap-sugnup-default-parent">';
	if ($selectedOption== '') {
		$papsugnupdefaultparent .= '<option selected value="">none</option>';
	} else {
		$papsugnupdefaultparent .= '<option value="">none</option>';
	}
	if ($selectedOption=='cookie') {
		$papsugnupdefaultparent .= '<option selected value="cookie">resolved from cookie</option>';
	} else {
		$papsugnupdefaultparent .= '<option value="cookie">resolved from cookie</option>';
	}
    foreach($recordset as $rec) {
      if ($selectedOption == $rec->get('id'))  {
          $papsugnupdefaultparent .= '<option selected value="'.$rec->get('id').'">' . $rec->get('username') . '(' . $rec->get('firstname').' '.$rec->get('lastname').')</option>';
      } else {
        $papsugnupdefaultparent .= '<option value="'.$rec->get('id').'">'. $rec->get('username') . ' (' . $rec->get('firstname').' '.$rec->get('lastname').')</option>';
      }
      
    }
    $papsugnupdefaultparent .= '</select>';
	
	insertFormOption('Default Parent Affiliate (should be none by default)', 'Every new affiliate account created with Post Affiliate Pro through WP, will have this selected affiliate as his parent.', $papsugnupdefaultparent);
    
    $selectedStatus = get_option('pap-sugnup-default-status');
    $papsugnupdefaultstatus = '<select name="pap-sugnup-default-status">';    
    if ($selectedStatus=='') {$selectedStatus = 'P';}
          
    $papsugnupdefaultstatus .= print_option('A', 'Approved', $selectedStatus, true);
    $papsugnupdefaultstatus .= print_option('P', 'Pending', $selectedStatus, true);
    $papsugnupdefaultstatus .= print_option('D', 'Declined', $selectedStatus, true);
    $papsugnupdefaultstatus .= print_option('', 'defaut', $selectedStatus, true);
    $papsugnupdefaultstatus .= '</select>';
    
    insertFormOption('Default signup status', 'Every new affiliate which will be insertet to Post Affiliate Pro through WP, will have this status. If you set "default", Post Affiliate Pro will handle status by its predefined settings.', $papsugnupdefaultstatus);                                                                   
    
    $selectedStatus = get_option('pap-sugnup-sendconfiramtionemail');
    if ($selectedStatus == 'true') {
    	$checked = 'checked';
    } else {
    	$checked;
    }
    $papmailconfirmsend = '<input type="checkbox" name="pap-sugnup-sendconfiramtionemail" value="true" '.$checked.'></input>';
    
	insertFormOption('Send confiramtion email when new user sign-up', 'When checked, Post Affiliate Pro will notify about new sign-up with an email.', $papmailconfirmsend); 
    
    echo '</table>';
    
    echo '<p class="submit">
            <input type="submit" class="button-primary" value="'. _('Save Changes') .'" />					
            </p>';	
    echo '</form>';
}

function resolve_parent_from_cookie(Gpf_Api_Session $session, Pap_Api_Affiliate $affiliate) {
	$clickTracker = new Pap_Api_ClickTracker($session);
    try {  
        $clickTracker->track();        
    } catch (Exception $e) {
    }
    if ($clickTracker->getAffiliate() != null) {
    	$affiliate->setParentUserId($clickTracker->getAffiliate()->getValue('userid'));
    }
}

function affiliate_new_user($user_id) {
    $session = new Gpf_Api_Session(get_option('pap-url') . "/server.php");
    $login = $session->login(get_option('pap-merchant-name'), get_option('pap-merchant-password'));						
    if($login == false) {				
        return;
    }
    $user = new WP_User($user_id);    
    $affiliate = new Pap_Api_Affiliate($session);
    $affiliate->setUsername($user->user_email);
    $affiliate->setFirstname(($user->first_name=='')?' ':$user->first_name);
    $affiliate->setLastname(($user->last_name=='')?' ':$user->last_name);
    $affiliate->setNotificationEmail($user->user_email);
    if (get_option('pap-sugnup-default-parent')!==false && get_option('pap-sugnup-default-parent')!==null && get_option('pap-sugnup-default-parent')!='' && get_option('pap-sugnup-default-parent')!='cookie') {
        $affiliate->setParentUserId(get_option('pap-sugnup-default-parent'));
    }
    if (get_option('pap-sugnup-default-parent')=='cookie') {
    	resolve_parent_from_cookie($session, $affiliate);
    }    
    if (get_option('pap-sugnup-default-status')!==false && get_option('pap-sugnup-default-status')!==null && get_option('pap-sugnup-default-status')!='') {
        $affiliate->setStatus(get_option('pap-sugnup-default-status'));
    }
    $affiliate->setData(1, $user->user_level);
    $affiliate->add();
    
    if (get_option('pap-sugnup-sendconfiramtionemail' == 'true')) {
    	try {
    		$affiliate->sendConfirmationEmail();
    	} catch (Exception $e) {
    		return;
    	}
    }
}
		
function affiliate_update_user($user_id) {
    $session = new Gpf_Api_Session(get_option('pap-url') . "/server.php");
    $login = $session->login(get_option('pap-merchant-name'), get_option('pap-merchant-password'));
    if($login == false) {
        return;
    }
    $user = new WP_User($user_id);
    
    $affiliate = new Pap_Api_Affiliate($session);
    $affiliate->setUsername($user->user_email);
    try {
    	$affiliate->load();
    } catch (Exception $e) {
    	return;
    }
    $affiliate->setFirstname(($user->first_name=='')?' ':$user->first_name);
    $affiliate->setLastname(($user->last_name=='')?' ':$user->last_name);
    $affiliate->setNotificationEmail($user->user_email);
    $affiliate->setData(1, $user->user_level);
    $affiliate->save();
}

add_action('admin_menu', 'pap_add_primary_config_menu');
add_action('admin_init', 'init_admin');
add_action( 'user_register', 'affiliate_new_user');
add_action( 'profile_update', 'affiliate_update_user');

?>
