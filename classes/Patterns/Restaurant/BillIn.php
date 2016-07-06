<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 10/13/2015
 * Time: 11:07 AM
 */

namespace Patterns\Restaurant;


use Patterns\Common\DBTable;

class BillIn extends Bill
{
    var $menus;
    var $bill_detail;
    function __construct($id = 0, $tableData = array()) {
        $dbTable = new DBTable('bill_in','bii_id');
        parent::__construct($id,$dbTable,$tableData);
        $dbTable = new DBTable('bill_in_detail','bid_bill_id');
        $this->bill_detail = $dbTable->getRowById($this->id)->selectField('*')->results();
    }
    function getListMenus() {
        foreach($this->bill_detail as $menu_bill) {
            $this->menus[] = new MenuInBill($menu_bill['bid_menu_id'],array(),$this);
        }
    }
    function findBillMenuDetail($menu_id){
        foreach($this->bill_detail as $detail) {
            if($detail['bid_menu_id'] == $menu_id){
                return $detail;
            }
        }
        //không tìm thấy
        return false;
    }
}