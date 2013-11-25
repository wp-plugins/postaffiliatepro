<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_Signup extends postaffiliatepro_Form_Base {
    private $campaignHelper = null;
    
    public function __construct() {
        parent::__construct(postaffiliatepro::SIGNUP_SETTINGS_PAGE_NAME, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/SignupSettings.xtpl';
    }
    
    protected function getType() {
        return postaffiliatepro_Form_Base::TYPE_FORM;
    }

    private function getAffiliatesSelectData() {
        $session = $this->getApiSession();
        if ($session === null) {
            $this->_log(__('Unable to obtain affiliate list from Post Affiliate Pro installation'));
            return array();
        }
        $request = new Pap_Api_AffiliatesGrid($this->getApiSession());
        $request->addParam('columns', new Gpf_Rpc_Array(array(array('id'), array('username'), array('firstname'), array('lastname'))));
        $request->setLimit(0, 5000);
        try {
            $request->sendNow();
        } catch(Exception $e) {
            $this->_log(__("API call error: " . $e->getMessage()));
        }
        $grid = $request->getGrid();
        $recordset = $grid->getRecordset();
        $data = array();
        $data['from_cookie'] = 'resolved from cookie';
        foreach($recordset as $rec) {
            $data[$rec->get('id')] = $rec->get('username') . '(' . $rec->get('firstname').' '.$rec->get('lastname').')';
        }
        return $data;
    }

    protected function initForm() {
        $this->addSelect(postaffiliatepro::SIGNUP_DEFAULT_STATUS_SETTING_NAME, array('' => 'default', 'A' => 'Approved', 'P' => 'Pending', 'D'=>'Declined'));
        $this->addSelect(postaffiliatepro::SIGNUP_DEFAULT_PARENT_SETTING_NAME, $this->getAffiliatesSelectData());
        $this->addCheckbox(postaffiliatepro::SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME);
        $this->addCheckbox(postaffiliatepro::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME);
        $this->addSubmit();
        
        $campaignsForm = new postaffiliatepro_Form_Settings_Campaigns($this->getCampaignHelper());
        $this->parseBlock('campaigns', array('campaigns-content' => $campaignsForm->render()));
    }

    public function render() {        
        if ($this->getApiSession() !== null) {
            parent::render();
            return;
        }                
        $this->renderTemplate(WP_PLUGIN_DIR . '/postaffiliatepro/Template/SignupSettingsNoSession.xtpl');
    }
}

?>