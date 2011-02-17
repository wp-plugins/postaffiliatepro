<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

if (!class_exists('postaffiliatepro_Base')) {
    class postaffiliatepro_Base {
        const IMG_PATH = '/postaffiliatepro/img/';

        private static $session = null;
        private static $campaignHelper = null;
        
        protected function _log($message) {
            if( WP_DEBUG === true ){
                if( is_array( $message ) || is_object( $message ) ){
                    $message = print_r( $message, true );
                }
                $message = 'PostAffiliatPro Wordpress plugin log: ' . $message;
                error_log($message);
                echo $message;
            }
        }
        
        /**
         * @return postaffiliatepro_Util_CampaignHelper
         */
        protected function getCampaignHelper() {
            if (self::$campaignHelper) {
                return self::$campaignHelper;
            }
            self::$campaignHelper = new postaffiliatepro_Util_CampaignHelper();
            return self::$campaignHelper;
        }
        
        public function getAccountName() {
            if (get_option(postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME) == '') {
                return postaffiliatepro::DEFAULT_ACCOUNT_NAME;
            }
            return get_option(postaffiliatepro::CLICK_TRACKING_ACCOUNT_SETTING_NAME);
        }
        
        protected function getPapVersion () {            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, get_option(postaffiliatepro::PAP_URL_SETTING_NAME) . 'api/version.php');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $result = curl_exec($ch);               
            $xml = new SimpleXMLElement($result);
            return (string) $xml->applications->pap->versionNumber;                      
        }

        /**
         * @return Gpf_Api_Session
         */
        protected function getApiSession() {
            if (self::$session !== null) {
                return self::$session;
            }
            $session = new Gpf_Api_Session(get_option(postaffiliatepro::PAP_URL_SETTING_NAME) . "scripts/server.php");
            try {
                $login = $session->login(get_option(postaffiliatepro::PAP_MERCHANT_NAME_SETTING_NAME), get_option(postaffiliatepro::PAP_MERCHANT_PASSWORD_SETTING_NAME));
            } catch (Gpf_Api_IncompatibleVersionException $e) {
                $this->_log(__("Unable to login into PAP installation because of icompatible versions (probably your repote API file here in WP installation is older than your PAP installation)"));
                return null;
            }
            if($login == false) {                        
                $this->_log(__("Unable to login into PAP installation with given credentails: " . $session->getMessage()));                
                return null;
            }
            self::$session = $session;
            return $session;
        }

        protected function getImgUrl() {
            return WP_PLUGIN_URL . self::IMG_PATH;
        }
    }
}
?>