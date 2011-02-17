<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_General extends postaffiliatepro_Form_Base {
    public function __construct() {
        parent::__construct(postaffiliatepro::GENERAL_SETTINGS_PAGE_NAME, 'options.php');
    }
    
    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/GeneralSettings.xtpl';
    }
    
    protected function getType() {
        return postaffiliatepro_Form_Base::TYPE_FORM;
    }
    
    protected function initForm() {
        $this->addTextBox(postaffiliatepro::PAP_URL_SETTING_NAME, 100);
        $this->addTextBox(postaffiliatepro::PAP_MERCHANT_NAME_SETTING_NAME, 40);
        $this->addPassword(postaffiliatepro::PAP_MERCHANT_PASSWORD_SETTING_NAME, 20);
        $this->checkCredentails();
        $this->addSubmit();
    }
    
    private function checkCredentails() {
        $session = $this->getApiSession();
        if ($session !== null) {
            $this->parseBlock('login_check_ok', array());
            $this->parseBlock('installation_info', array('pap-version' => $this->getPapVersion()));
        } else {
            $this->parseBlock('login_check_failed', array());
        }
    }
}

?>