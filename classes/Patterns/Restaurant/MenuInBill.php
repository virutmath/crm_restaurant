<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 10/13/2015
 * Time: 8:26 AM
 */

namespace Patterns\Restaurant;


use Patterns\Common\DBTable;

class MenuInBill extends Menu
{
    var $number;
    var $price;
    var $discount;
    var $bill;

    function __construct($id, $tableData, BillIn $bill) {
        $this->bill = $bill;
        if($this->bill->findBillMenuDetail($id)) {
            parent::__construct($id,$tableData);
            $billDt = $this->bill->findBillMenuDetail($this->id);
            $this->number = $billDt['bid_menu_number'];
            $this->price = $billDt['bid_menu_price'];
            $this->discount = $billDt['bid_menu_discount'];
        }else{
            throw new \Exception('Không tồn tại thực đơn trong hóa đơn');
        }
    }
    function calculateMenuMoney() {
         return floatval($this->number * (1 - $this->discount/100) * $this->price);
    }
}