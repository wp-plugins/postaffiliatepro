<?php
/*
 Plugin Name: Post Affiliate Pro
 Plugin URI: http://www.qualityunit.com/#
 Description: Plugin that enable user signup integration integration with Post Affiliate Pro
 Author: QualityUnit
 Version: 1.1.0
 Author URI: http://www.qualityunit.com
 License: GPL2
 */

class postaffiliatepro_Util_ContactForm7Helper extends postaffiliatepro_Base {

    public static function formsExists() {
        global $wpdb;
        
        $querystr = "SELECT count(*) as count 	from wp_contact_form_7";
        $count = $wpdb->get_results($querystr);      
        return $count[0]->count;
    }
    
    public static function getFormList() {
        global $wpdb;
        
        $querystr = "SELECT cf7_unit_id, title from wp_contact_form_7";
        $rows = $wpdb->get_results($querystr);      
        return $rows;
    }
    
}
