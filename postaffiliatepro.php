<?php
/*
 Plugin Name: Post Affiliate Pro
 Plugin URI: http://www.qualityunit.com/#
 Description: Plugin that enable user signup integration integration with Post Affiliate Pro
 Author: QualityUnit
 Version: 1.2.16
 Author URI: http://www.qualityunit.com
 License: GPL2
 */

include WP_PLUGIN_DIR . '/postaffiliatepro/Base.class.php';
include WP_PLUGIN_DIR . '/postaffiliatepro/lib/Initializer.class.php';

if (!class_exists('postaffiliatepro')) {
    class postaffiliatepro extends postaffiliatepro_Base {
        const API_FILE = '/postaffiliatepro/PapApi.class.php';

        //configuration pages and settings
        //general page
        const GENERAL_SETTINGS_PAGE_NAME = 'pap_config_general_page';

        const PAP_URL_SETTING_NAME = 'pap-url';
        const PAP_MERCHANT_NAME_SETTING_NAME = 'pap-merchant-name';
        const PAP_MERCHANT_PASSWORD_SETTING_NAME = 'pap-merchant-password';

        //signup options
        const SIGNUP_SETTINGS_PAGE_NAME = 'pap_config_signup_page';

        const SIGNUP_INTEGRATION_ENABLED_SETTING_NAME = 'pap-sugnup-integration-enabled';
        const SIGNUP_DEFAULT_PARENT_SETTING_NAME = 'pap-sugnup-default-parent';
        const SIGNUP_DEFAULT_STATUS_SETTING_NAME = 'pap-sugnup-default-status';
        const SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME = 'pap-sugnup-sendconfiramtionemail';
        const SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME = 'pap-sugnup-campaigns-settings';

        //click tracking integration page
        const CLICK_TRACKING_SETTINGS_PAGE_NAME = 'pap_config_click_tracking_page';

        const CLICK_TRACKING_ENABLED_SETTING_NAME = 'pap-click-tracking-enabled';
        const CLICK_TRACKING_ACCOUNT_SETTING_NAME = 'pap-click-tracking-account';

        const DEFAULT_ACCOUNT_NAME = 'default1';

        //top affiliates widget options
        const TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME = 'pap-top-affiliates-widget-settings-page';
        const TOP_AFFILAITES_REFRESHTIME = 'pap-top-affiliates-refresh-time';
        const TOP_AFFILAITES_REFRESHINTERVAL = 'pap-top-affiliates-refresh-interval';
        const TOP_AFFILAITES_CACHE = 'pap-top-affiliates-cache';
        const TOP_AFFILAITES_ORDER_BY = 'pap-top-affiliates-order-by';
        const TOP_AFFILAITES_ORDER_ASC = 'pap-top-affiliates-order-asc';
        const TOP_AFFILAITES_LIMIT = 'pap-top-affiliates-limit';
        const TOP_AFFILAITES_ROW_TEMPLATE = 'pap-top-affiliates-row-template';

        const SHORTCODES_SETTINGS_PAGE_NAME = 'shortcodes-settings-page';
        const AFFILAITE_SHORTCODE_CACHE = 'affiliate-shortcode_cache';

        //specail integrations page
        const INTEGRATIONS_SETTINGS_PAGE_NAME = 'pap-integrations-config-page';

        const CONTACT7_SIGNUP_COMMISSION_ENABLED = 'contact7-signup-commission-enabled';

        //contact form 7 integration page
        const CONTACT7_SIGNUP_COMMISSION_CONFIG_PAGE = 'contact7-signup-commission-config-page';
        const CONTACT7_CONTACT_COMMISSION_AMOUNT = 'contact7-contact-commission-amount';
        const CONTACT7_CONTACT_COMMISSION_CAMPAIGN = 'contact7-contact-commission-campaign';
        const CONTACT7_CONTACT_COMMISSION_FORM = 'contact7-contact-commission-form';
        const CONTACT7_CONTACT_COMMISSION_STORE_FORM = 'contact7-contact-commission-store-form';        

        public function __construct() {
            $init = new postaffiliatepro_lib_Initializer();
            try {
                $init->initLibraries();
            } catch (LibraryInitializationExceptiopn $e) {
                $this->_log(__('Error during loading library files: ' . $e->getMessage()));
                return;
            }
            if (!$this->apiFileExists()) {
                $this->_log(__('Error during loading PAP API file: ' . WP_PLUGIN_DIR . self::API_FILE));
                return;
            }
            $this->includePapApiFile();
            $this->initUtils();
            $this->initForms();
            $this->initWidgets();
            $this->initPlugin();
            $this->initShortcodes();
        }

        private function initWidgets() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Widget/TopAffiliates.class.php';
        }

        private function initUtils() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/CampaignHelper.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/TopAffiliatesHelper.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Util/ContactForm7Helper.class.php';
        }

        private function initForms() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Base.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/General.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/Signup.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/Campaigns.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/CampaignInfo.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/ClickTracking.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/Integrations.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Form/Settings/ContactForm7.class.php';
        }

        private function includePapApiFile() {
            require_once WP_PLUGIN_DIR . self::API_FILE;
        }

        private function apiFileExists() {
            return @file_exists(WP_PLUGIN_DIR . self::API_FILE);
        }

        private function getPapIconURL() {
            return $this->getImgUrl() . '/menu-icon.png';
        }

        private function initShortcodes() {
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Shortcode/Cache.class.php';
            require_once WP_PLUGIN_DIR . '/postaffiliatepro/Shortcode/Affiliate.class.php';

            add_shortcode('affiliate', array($this, 'getAffiliateShortCode'));
        }

        private function initPlugin() {
            add_action('admin_init', array($this, 'initSettings'));
            add_action('admin_menu', array($this, 'addPrimaryConfigMenu'));
            add_action('user_register', array($this, 'onNewUserRegistration'));
            //fix to work with magic members
            add_action('mgm_user_register', array($this, 'onNewUserRegistration'), 99);
            //fix end
            add_action('profile_update', array($this, 'onUpdateExistingUser'));
            //contact7
            add_action('wpcf7_mail_sent', array($this, 'addContactForm7ContactCommission'));

            add_filter ('wp_head', array($this, 'insertIntegrationCodeToHead'), 99);
            add_action('widgets_init', create_function('', 'return register_widget("postaffiliatepro_Widget_TopAffiliates");'));

        }

        private function getFormData($form) {
            if (count($form->posted_data) == 0) {
                return '';
            }
            $output = '';
            foreach ($form->posted_data as $key => $field) {
                $output .= $key . ': ' . $field . ', ';
            }
            return substr($output,0,-2);
        }
        
        private function commissionEnabledForForm($form) {
            if (get_option(self::CONTACT7_CONTACT_COMMISSION_FORM) == '0') {
                return true;
            }
            return get_option(self::CONTACT7_CONTACT_COMMISSION_FORM) == $form->id;
        }

        public function addContactForm7ContactCommission($form) {
            if (!$this->contactForm7ContactCommissionEnabled()) {
                $this->_log(__('Contact form 7 contact commission disabled. Skipping action.'));
                return $form;
            }
            if (!$this->commissionEnabledForForm($form)) {
                $this->_log(__('Contact form 7 contact commission not enabled for form ' . $form->unit_tag . '. Skipping action.'));
                return $form;
            }
            $saleTracker = new Pap_Api_SaleTracker($this->getApiSessionUrl());
            $sale1 = $saleTracker->createSale();
            $sale1->setTotalCost(get_option(self::CONTACT7_CONTACT_COMMISSION_AMOUNT));
            if ($this->contactForm7ContactCommissionStoreForm()) {
                $sale1->setData1($this->getFormData($form));
            }
            if (get_option(self::CONTACT7_CONTACT_COMMISSION_CAMPAIGN) != '') {
                $sale1->setCampaignId(get_option(self::CONTACT7_CONTACT_COMMISSION_CAMPAIGN));
            }
            try {
                $saleTracker->register();
            } catch (Exception $e) {
                $this->_log(__('Error during registering contact commission: ' . $e->getMessage()));
            }
        }

        public function getAffiliateShortCode($attr, $content = null) {
            $affiliate = new Shortcode_Affiliate();
            return $affiliate->getCode($attr, $content);
        }

        public function widgetTopAffiliates($args) {
            $widget = new postaffiliatepro_Widget_TopAffiliates($args);
            $widget->render();
        }

        private function parseServerPathForClickTrackingCode() {
            $url = str_replace ('https://', '', get_option(self::PAP_URL_SETTING_NAME));
            $url = str_replace ('http://', '', $url);
            return $url;
        }

        public function insertIntegrationCodeToHead($content) {
            if (get_option(self::CLICK_TRACKING_ENABLED_SETTING_NAME) != 'true') {
                return $content;
            }
            if(!is_feed()) {
                echo '<script type="text/javascript"><!--
					  document.write(unescape("%3Cscript id=\'pap_x2s6df8d\' src=\'" + (("https:" == document.location.protocol) ? "https://" : "http://") + 
					  "'.$this->parseServerPathForClickTrackingCode().'scripts/trackjs.js\' type=\'text/javascript\'%3E%3C/script%3E"));//-->
                      </script>
                      <script type="text/javascript"><!--
                      PostAffTracker.setAccountId(\''.$this->getAccountName().'\');
                      try {
                      PostAffTracker.track();
                      } catch (err) { }
                      //-->
                      </script>' . $content;
            }
        }

        private function resolveParentAffiliateFromCookie(Gpf_Api_Session $session, Pap_Api_Affiliate $affiliate) {
            $clickTracker = new Pap_Api_ClickTracker($session);
            try {
                $clickTracker->track();
            } catch (Exception $e) {
                $this->_log(__("Error running track:" . $e->getMessage()));
            }
            if ($clickTracker->getAffiliate() != null) {
                $affiliate->setParentUserId($clickTracker->getAffiliate()->getValue('userid'));
            } else {
                $this->_log(__("Parent affiliate not found from cookie"));
            }
        }

        private function resolveFirstAndLastName(WP_User $user, Pap_Api_Affiliate $affiliate) {
            if ($user->first_name=='' && $user->last_name=='') {
                $affiliate->setFirstname($user->nickname);
                $affiliate->setLastname(' ');
            } else {
                $affiliate->setFirstname(($user->first_name=='')?' ':$user->first_name);
                $affiliate->setLastname(($user->last_name=='')?' ':$user->last_name);
            }
        }

        /**
         * @return Pap_Api_Affiliate
         */
        private function initAffiliate(WP_User $user, Gpf_Api_Session $session) {
            $affiliate = new Pap_Api_Affiliate($session);
            $affiliate->setUsername($user->user_email);
            $this->resolveFirstAndLastName($user, $affiliate);
            $affiliate->setNotificationEmail($user->user_email);
            $affiliate->setData(1, __('User level: ') . $user->user_level);
            return $affiliate;
        }

        private function setParentToAffiliate(Pap_Api_Affiliate $affiliate, Gpf_Api_Session $session) {
            if (get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME)!==false &&
            get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME)!==null &&
            get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME)!='' &&
            get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME)!='from_cookie') {
                $affiliate->setParentUserId(get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME));
            }
            if (get_option(self::SIGNUP_DEFAULT_PARENT_SETTING_NAME)=='from_cookie') {
                $this->resolveParentAffiliateFromCookie($session, $affiliate);
            }
        }

        private function setStatusToAffiliate(Pap_Api_Affiliate $affiliate) {
            if (get_option(self::SIGNUP_DEFAULT_STATUS_SETTING_NAME)!==false &&
            get_option(self::SIGNUP_DEFAULT_STATUS_SETTING_NAME)!==null &&
            get_option(self::SIGNUP_DEFAULT_STATUS_SETTING_NAME)!='') {
                $affiliate->setStatus(get_option(self::SIGNUP_DEFAULT_STATUS_SETTING_NAME));
            }
        }

        private function signupIntegrationEnabled() {
            return get_option(self::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME) == 'true';
        }

        private function contactForm7ContactCommissionEnabled() {
            return postaffiliatepro_Util_ContactForm7Helper::formsExists() && get_option(self::CONTACT7_SIGNUP_COMMISSION_ENABLED) == 'true';
        }

        private function contactForm7ContactCommissionStoreForm() {
            return get_option(self::CONTACT7_CONTACT_COMMISSION_STORE_FORM) == 'true';
        }

        public function onNewUserRegistration($user_id) {
            if (!$this->signupIntegrationEnabled()) {
                $this->_log(__("Signup integratoin disabled - skipping new affiliate creation"));
                return;
            }
            $session = $this->getApiSession();
            if ($session===null) {
                $this->_log(__("We have no session to PAP installation! Registration of PAP user cancelled."));
                return;
            }
            $affiliate = $this->initAffiliate(new WP_User($user_id), $session);

            $this->setParentToAffiliate($affiliate, $session);

            $this->setStatusToAffiliate($affiliate);

            try {
                $affiliate->add();
            } catch (Exception $e) {
                $this->_log(__("Error adding affiliate" . $e->getMessage()));
                return;
            }

            if (get_option(self::SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME) == 'true') {
                try {
                    $affiliate->sendConfirmationEmail();
                } catch (Exception $e) {
                    $this->_log(__("Error on sending confirmation email"));
                    return;
                }
            }
            $this->processCampaigns($affiliate);
        }

        private function getCampaignOption($campaignId, $name) {
            $value = get_option(self::SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME);
            if (!is_array($value)) {
                return '';
            }
            if (!array_key_exists($name . '-' . $campaignId, $value)) {
                return '';
            }
            return $value[$name . '-' . $campaignId];
        }

        private function assignToCampaign(Pap_Api_Affiliate $affiliate, $campaignId, $sendNotification) {
            try {
                $affiliate->assignToPrivateCampaign($campaignId, ($sendNotification=='true')?true:false);
            } catch (Exception $e) {
                $this->_log('Unable to assign user to private camapign ' . $campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID) . ', problem: ' . $e->getMessage());
            }
        }

        private function processCampaigns(Pap_Api_Affiliate $affiliate) {
            $campaigns = $this->getCampaignHelper()->getCampaignsList();
            if ($campaigns === null) {
                return;
            }
            foreach ($campaigns as $campaign) {
                if ($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE) != postaffiliatepro_Util_CampaignHelper::CAMPAIGN_TYPE_PUBLIC) {
                    if ($this->getCampaignOption($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID), postaffiliatepro_Form_Settings_CampaignInfo::ADD_TO_CAMPAIGN) == 'true') {
                        $this->assignToCampaign($affiliate, $campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID),
                        $this->getCampaignOption($campaign->get(postaffiliatepro_Util_CampaignHelper::CAMPAIGN_ID), postaffiliatepro_Form_Settings_CampaignInfo::SEND_NOTIFICATION_EMAIL));
                    }
                }
            }
        }

        public function onUpdateExistingUser($user_id) {
            if (!$this->signupIntegrationEnabled()) {
                $this->_log(__("Signup integratoin disabled - skipping upating existing affiliate"));
                return;
            }
            $session = $this->getApiSession();
            if ($session === null) {
                $this->_log(__("We have no session to PAP installation! Updating of PAP user cancelled."));
                return;
            }
            $user = new WP_User($user_id);
            $affiliate = new Pap_Api_Affiliate($session);
            $affiliate->setUsername($user->user_email);
            try {
                $affiliate->load();
            } catch (Exception $e) {
                $this->_log(__("Unable to load affiliate from Post Affiliate Pro. Update of user " . $user->nickname . " cancelled"));
                return;
            }
            $this->resolveFirstAndLastName($user, $affiliate);
            $affiliate->setNotificationEmail($user->user_email);
            $affiliate->setData(1, $user->user_level);
            $affiliate->save();
        }

        public function initSettings() {
            register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_URL_SETTING_NAME);
            register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_MERCHANT_NAME_SETTING_NAME);
            register_setting(self::GENERAL_SETTINGS_PAGE_NAME, self::PAP_MERCHANT_PASSWORD_SETTING_NAME);
            register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_INTEGRATION_ENABLED_SETTING_NAME);
            register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_DEFAULT_PARENT_SETTING_NAME);
            register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_DEFAULT_STATUS_SETTING_NAME);
            register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_SEND_CONFIRMATION_EMAIL_SETTING_NAME);
            register_setting(self::SIGNUP_SETTINGS_PAGE_NAME, self::SIGNUP_CAMPAIGNS_SETTINGS_SETTING_NAME);
            register_setting(self::CLICK_TRACKING_SETTINGS_PAGE_NAME, self::CLICK_TRACKING_ENABLED_SETTING_NAME);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_REFRESHTIME);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_REFRESHINTERVAL);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_CACHE);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ORDER_BY);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ORDER_ASC);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_LIMIT);
            register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ROW_TEMPLATE);
            register_setting(self::SHORTCODES_SETTINGS_PAGE_NAME, self::AFFILAITE_SHORTCODE_CACHE);
            register_setting(self::INTEGRATIONS_SETTINGS_PAGE_NAME, self::CONTACT7_SIGNUP_COMMISSION_ENABLED);
            register_setting(self::CONTACT7_SIGNUP_COMMISSION_CONFIG_PAGE, self::CONTACT7_CONTACT_COMMISSION_AMOUNT);
            register_setting(self::CONTACT7_SIGNUP_COMMISSION_CONFIG_PAGE, self::CONTACT7_CONTACT_COMMISSION_CAMPAIGN);
            register_setting(self::CONTACT7_SIGNUP_COMMISSION_CONFIG_PAGE, self::CONTACT7_CONTACT_COMMISSION_FORM);
            register_setting(self::CONTACT7_SIGNUP_COMMISSION_CONFIG_PAGE, self::CONTACT7_CONTACT_COMMISSION_STORE_FORM);
        }

        public function addPrimaryConfigMenu() {
            add_menu_page(__('Post Affiliate Pro','pap-menu'), __('PostAffiliatePro','pap-menu'), 'manage_options', 'pap-top-level-options-handle', array($this, 'printGeneralConfigPage'), $this->getPapIconURL());
            add_submenu_page('pap-top-level-options-handle', __('Signup','signup-config'), __('Signup options','signup-config'), 'manage_options', 'signup-config-page', array($this, 'printSignupConfigPage'));
            add_submenu_page('pap-top-level-options-handle', __('Click tracking','click-tracking-config'), __('Click tracking','click-tracking-config'), 'manage_options', 'click-tracking-config-page', array($this, 'printClickTrackingConfigPage'));
            add_menu_page(__('Integrations'), __('Integrations'), 'manage_options', 'integrations-config-page-handle', array($this, 'printSpecialIntegrationsConfigPage'), $this->getPapIconURL());
            if (postaffiliatepro_Util_ContactForm7Helper::formsExists()) {
                add_submenu_page('integrations-config-page-handle', __('Contact form 7','contact-form-7-settings'), __('Contact form 7','contact-form-7-settings'), 'manage_options', 'contact-form-7-settings-page', array($this, 'printContactForm7ConfigPage'));
            }
        }

        public function printGeneralConfigPage() {
            $form = new postaffiliatepro_Form_Settings_General();
            $form->render();
        }

        public function printSignupConfigPage() {
            $form = new postaffiliatepro_Form_Settings_Signup();
            $form->render();
            return;
        }

        public function printClickTrackingConfigPage() {
            $form = new postaffiliatepro_Form_Settings_ClickTracking();
            $form->render();
            return;
        }

        public function printSpecialIntegrationsConfigPage() {
            $form = new postaffiliatepro_Form_Settings_Integrations();
            $form->render();
            return;
        }

        public function printContactForm7ConfigPage() {
            $form = new postaffiliatepro_Form_Settings_ContactForm7();
            $form->render();
            return;
        }
    }
}

$postaffiliatepro = new postaffiliatepro();
?>
