<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

class Shortcode_Affiliate extends postaffiliatepro_Base {
    /**
     * 
     * @var Shortcode_Cache
     */
    private static $cache = null; 
    
    public function __construct() {
        if (self::$cache === null) {
            self::$cache = new Shortcode_Cache();
        }
    }
    
    public function getCode($atts, $content = null) {        
        global $current_user;
        if ($current_user->ID == 0) {
            return;
        }
        $session = $this->getApiSession();
        if ($session == null) {
            $this->_log('Error getting session for login to PAP. Check WP logs for details.');
            return;
        }        
        $affiliate = new Pap_Api_Affiliate($session);
        $affiliate->setUsername($current_user->user_email);
        try {
            $affiliate->load();
        } catch (Exception $e) {
            $this->_log('Error getting affiliate: ' . $e->getMessage());
            return;
        }
        if (array_key_exists('item', $atts)) {       
            if ($atts['item'] == 'name') {
                return $affiliate->getFirstname() . ' ' . $affiliate->getLastname();
            }            
            if ($atts['item'] == 'loginurl') {                
                $session = $this->getSessionId($affiliate);
                if (array_key_exists('caption', $atts)) {
                    return '<a href="'.$this->getLoginUrl($session).'" target="_blank">' . $atts['caption'] . '</a>';
                } else {
                    return '<a href="'.$this->getLoginUrl($session).'" target="_blank">Affiliate panel</a>';
                }
                
            }
            return $affiliate->getField($atts['item']);
        }
    }
    
    private function getLoginUrl($sessionId) {
        return get_option(postaffiliatepro::PAP_URL_SETTING_NAME) . 'affiliates/panel.php?S=' . $sessionId;
    }
    
    private function getNewSessionId(Pap_Api_Affiliate $affiliate) {
        $session = new Gpf_Api_Session($this->getApiSessionUrl());
        $session->login($affiliate->getUsername(), $affiliate->getPassword(), Gpf_Api_Session::AFFILIATE);
        return $session->getSessionId();
    }
    
    private function getSessionId(Pap_Api_Affiliate $affiliate) {        
        $id = self::$cache->getSessionId($affiliate->getUsername());
        if ($id !== null) {
            return $id;
        }
        $newSessionId = $this->getnewSessionId($affiliate);
        self::$cache->update($affiliate->getUsername(), $newSessionId);
        return $newSessionId;
    }
}