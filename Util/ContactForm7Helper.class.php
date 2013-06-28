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

        if($wpdb->get_var("show tables like 'wp_contact_form_7'") != 'wp_contact_form_7') {
            return self::formsExistsNew();
        }

        $querystr = "SELECT count(*) as count 	from wp_contact_form_7";
        try {
            $row = $wpdb->get_results($querystr);
        } catch (Exception $e) {
            return 0;
        }
        if (!array_key_exists(0, $row))  {
            return 0;
        }
        return $row[0]->count;
    }

    public static function formsExistsNew() {
        global $wpdb;

        $querystr = "SELECT count(*) AS count FROM wp_posts WHERE `post_type` = 'wpcf7_contact_form'";
        try {
            $row = $wpdb->get_results($querystr);
        } catch (Exception $e) {
            return 0;
        }
        if (!array_key_exists(0, $row))  {
            return 0;
        }
        return $row[0]->count;
    }

    public static function getFormListNew() {
        global $wpdb;
        $querystr = "SELECT ID, post_title FROM wp_posts WHERE `post_type` = 'wpcf7_contact_form'";
        $rows = $wpdb->get_results($querystr);
        return $rows;
    }

    public static function getFormList() {
        global $wpdb;
        
        if($wpdb->get_var("show tables like 'wp_contact_form_7'") != 'wp_contact_form_7') {
            return self::getFormListNew();
        }

        $querystr = "SELECT cf7_unit_id, title from wp_contact_form_7";
        $rows = $wpdb->get_results($querystr);
        return $rows;
    }

}
