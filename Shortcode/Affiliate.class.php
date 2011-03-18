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
    public function getCode($atts, $content = null) {
        global $current_user;
        $affiliate = new Pap_Api_Affiliate($this->getApiSession());
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
                $session = new Gpf_Api_Session($this->getApiSessionUrl());
                $session->login($affiliate->getUsername(), $affiliate->getPassword(), Gpf_Api_Session::AFFILIATE);
                if ($content !== null && $content != '') {
                    return '<a href="'.$this->getLoginUrl($session).'" target="_blank">' . $content . '</a>';
                } else {
                    return '<a href="'.$this->getLoginUrl($session).'" target="_blank">Affiliate panel</a>';
                }
                
            }
            return $affiliate->getField($atts['item']);
        }
    }
    
    private function getLoginUrl(Gpf_Api_Session $session) {
        return get_option(postaffiliatepro::PAP_URL_SETTING_NAME) . 'affiliates/panel.php?S=' . $session->getSessionId();
    }
}