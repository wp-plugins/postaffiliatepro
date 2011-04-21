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

class postaffiliatepro_Util_TopAffiliatesHelper extends postaffiliatepro_Base {

    const COL_SALES_COUNT = 'salesCount';
    const COL_COMMISSIONS = 'commissions';
    const COL_RAW_CLICKS = 'clicksRaw';
    const COL_RAW_IMPS = 'impressionsRaw';
    
    /**
     * @var Gpf_Data_RecordSet
     */
    private static $affiliateList = null;
    
    /**
     * @return Gpf_Data_RecordSet;
     */    
    public function getTopAffiliatesList($orderBy, $orderAsc, $limit) {
        if (self::$affiliateList !== null) {
            return self::$affiliateList;
        }
        $session = $this->getApiSession();
        $request = new Gpf_Rpc_GridRequest("Pap_Merchants_User_TopAffiliatesGrid", "getRows", $session);
        $request->setLimit(0, $limit);
        $request->addParam('columns', new Gpf_Rpc_Array(array(array('id'), array('id'), array(self::COL_COMMISSIONS), array(self::COL_SALES_COUNT), array(self::COL_RAW_CLICKS), array(self::COL_RAW_IMPS))));
        
        $request->addParam('sort_col', $orderBy);
        if ($orderAsc == 'true') {
            $request->addParam('sort_asc', 'true');
        } else {
            $request->addParam('sort_asc', 'false');
        }
//        $filters = new Gpf_Rpc_Array();
//        $filters->add(new Gpf_Data_Filter('rstatus', 'NE', 'D'));
//        $filters->add(new Gpf_Data_Filter('rstatus', 'NE', 'S'));
//        $filters->add(new Gpf_Data_Filter('rstatus', 'NE', 'W'));        
//                
//        $request->addParam('filters', $filters);

        try {
            $request->sendNow();
        } catch(Exception $e) {
            $this->_log(__("Can not obtain campaign list:" . $e->getMessage()));
            return null;
        }
        $grid = $request->getGrid();
        self::$affiliateList = $grid->getRecordset();
        return self::$affiliateList;
    }

    public function getOrderOptions($orderBy) {        
        $out = '';
        $select = ($orderBy=='name')?"selected='selected'":"";
        $out.="<option $select value='name'>Name</option>"; //'.($orderBy=='name')?'selected':''.' 
        $select = ($orderBy==self::COL_SALES_COUNT)?"selected='selected'":"";
        $out.="<option $select value='". self::COL_SALES_COUNT."'>Sales count</option>";
        $select = ($orderBy==self::COL_RAW_CLICKS)?"selected='selected'":"";
        $out.="<option $select value='".self::COL_RAW_CLICKS."'>Raw clicks</option>";
        $select = ($orderBy==self::COL_COMMISSIONS)?"selected='selected'":"";
        $out.="<option $select value='".self::COL_COMMISSIONS."'>Commissions</option>";
        $select = ($orderBy==self::COL_RAW_IMPS)?"selected='selected'":"";
        $out.="<option $select value='".self::COL_RAW_IMPS."'>Raw impressions</option>";
        return $out;
    }
    
}
?>