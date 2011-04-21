<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_Integrations extends postaffiliatepro_Form_Base {
    public function __construct() {
        parent::__construct(postaffiliatepro::INTEGRATIONS_SETTINGS_PAGE_NAME, 'options.php');
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/IntegrationsConfig.xtpl';
    }
    
    protected function getType() {
        return postaffiliatepro_Form_Base::TYPE_FORM;
    }

    protected function initForm() {
        if (!postaffiliatepro_Util_ContactForm7Helper::formsExists()) {
            $this->addCheckbox(postaffiliatepro::CONTACT7_SIGNUP_COMMISSION_ENABLED, null, ' disabled');
            $this->addHtml('contact7-signup-note', '<tr><td colspan="2" style="padding-top:0px;padding-bottom:15px;color:#750808;">No forms exists!</td></tr>');
        } else {
            $this->addCheckbox(postaffiliatepro::CONTACT7_SIGNUP_COMMISSION_ENABLED);
        }
        $this->addSubmit();
    }
}

?>