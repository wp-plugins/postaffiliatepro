<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class postaffiliatepro_Form_Settings_Campaigns extends postaffiliatepro_Form_Base {
    /**
     * 
     * @var postaffiliatepro_Util_CampaignHelper
     */
    private $campaignHelper;
    
    public function __construct(postaffiliatepro_Util_CampaignHelper $campaignHelper) {
        $this->campaignHelper = $campaignHelper;
        parent::__construct();
    }

    protected function getTemplateFile() {
        return WP_PLUGIN_DIR . '/postaffiliatepro/Template/CampaignsSettings.xtpl';
    }
    
    protected function getType() {
        return postaffiliatepro_Form_Base::TYPE_TEMPLATE;
    }

    protected function initForm() {
        $this->addHtml('campaigns-count', (string) $this->campaignHelper->getCampaignsCount());
        $this->addHtml('public-campaigns-count', (string) $this->campaignHelper->getCampaignsCount(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE_PUBLIC));
        $this->addHtml('public-campaigns-manual-count', (string) $this->campaignHelper->getCampaignsCount(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE_PUBLIC_MANUAL));
        $this->addHtml('private-campaigns-count', (string) $this->campaignHelper->getCampaignsCount(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE_PRIVATE));
        
        $content = '';
        $campaigns = $this->campaignHelper->getCampaignsList();
        if ($campaigns === null) {
            return;
        }
        foreach ($campaigns as $campaign) {
            $form = new postaffiliatepro_Form_Settings_CampaignInfo($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID), $this->campaignHelper);
            $content .= $form->render(); 
        }
        $this->addHtml('campaigns-list', $content);
    }

    public function render() {        
        return parent::render(true);
    }
}

?>