<?php
/*
Plugin Name: Post Affiliate Pro
Plugin URI: http://www.qualityunit.com/#
Description: Plugin that enable user signup integration integration with Post Affiliate Pro
Author: QualityUnit
Version: 1.0.1
Author URI: http://www.qualityunit.com
License: GPL2
*/

function apiFileExists() {
	return file_exists(WP_PLUGIN_DIR . '/'.basename(dirname(__FILE__)).'/PapApi.class.php');
}

//images subdirectory
function getPluginImgUrl() {	
	$url = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/img/';
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
}
		

function pap_add_primary_config_menu() {
	 add_menu_page(__('Post Affiliate Pro','pap-menu'), __('PostAffiliatePro','pap-menu'), 'manage_options', 'pap-top-level-options-handle', 'pap_config_general_page', getPluginImgUrl() . '/menu-icon.png', 0);
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
            <td colspan="2">Exmaple: http://www.yoursite.com/affiliate/scritps</td>
        </tr>                
    </table>';
    echo '<table class="form-table">
        <tr valign="top">
        <th scope="row" valign="middle">Merchnat name</th>
        <td><input type="text" size="40" name="pap-merchant-name" value="'. get_option('pap-merchant-name').'" /></td>        
        </tr>               
    </table>';
    echo '<table class="form-table">
        <tr valign="top">
        <th scope="row" valign="middle">Merchnat password</th>
        <td><input type="password" size="20" name="pap-merchant-password" value="'. get_option('pap-merchant-password').'" /></td>        
        </tr>               
    </table>';
    
    echo '<p class="submit">
    <input type="submit" class="button-primary" value="'. _('Save Changes') .'" />					
    </p></form></div>';
}

function pap_config_signup_page() {
    echo "<h2>" . __( 'Signup options', 'signup-config' ) . "</h2>";
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

    echo  '<form method="post" action="options.php">';
    echo '<table class="form-table">					
            <tr valign="top">
            <th scope="row" valign="middle">Avaliable affiliates</th>
            <td>';
    settings_fields('pap_config_signup_page');						
    echo '<select name="pap-sugnup-default-parent">';
    $selectedOption = get_option('pap-sugnup-default-parent');
    echo '<option selected value="">none</option>';
    foreach($recordset as $rec) {
      if ($selectedOption == $rec->get('id'))  {
          echo '<option selected value="'.$rec->get('id').'">' . $rec->get('username') . '(' . $rec->get('firstname').' '.$rec->get('lastname').')</option>';
      } else {
        echo '<option value="'.$rec->get('id').'">'. $rec->get('username') . ' (' . $rec->get('firstname').' '.$rec->get('lastname').')</option>';
      }
      
    }
    echo '</td>        
            </tr>						
        </table>';                        
    echo '</select>';
    //TODO: ADD STATUS
    echo '<p class="submit">
            <input type="submit" class="button-primary" value="'. _('Save Changes') .'" />					
            </p>';	
    echo '</form>';
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
    if (get_option('pap-sugnup-default-parent')!==false && get_option('pap-sugnup-default-parent')!==null && get_option('pap-sugnup-default-parent')!='') {
        $affiliate->setParentUserId(get_option('pap-sugnup-default-parent'));
    }
    $affiliate->setData(1, $user->user_level);
    $affiliate->add();
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
    $affiliate->load();
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
