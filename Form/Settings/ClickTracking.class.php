<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_ClickTracking extends postaffiliatepro_Form_Base {
    public function __construct() {
        parent::__construct(postaffiliatepro::CLICK_TRACKING_SETTINGS_PAGE_NAME, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/ClickTrackingSettings.xtpl';
    }
    
    protected function getType() {
        return postaffiliatepro_Form_Base::TYPE_FORM;
    }

    protected function initForm() {
        $this->addCheckbox(postaffiliatepro::CLICK_TRACKING_ENABLED_SETTING_NAME);
        $this->addTextBox(postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME, 40);
        $this->addSubmit();
    }
    
    protected function getOption($name) {
        if ($name == postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME) {
            return $this->getAccountName();
        }
        return parent::getOption($name);
    }
}

?>