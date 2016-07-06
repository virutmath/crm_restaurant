<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 9:51 AM
 */

namespace Patterns\Restaurant;

use Patterns\Common\DBTable;
use Patterns\Common\Image;
use Patterns\Common\RootObjectDB;
use Patterns\Common\Unit;

/**
 * Class Menu
 * @package Patterns\Restaurant
 * Class xử lý, truy xuất về thực đơn trong nhà hàng
 */
class Menu extends RootObjectDB
{
    var $id;
    var $name;
    var $price;
    var $price1;
    var $price2;
    var $priceEditable = false;
    var $unit;
    var $image;
    var $note;

    function __construct($id = 0, $tableData = array())
    {
        //config db table
        $dbTable = new DBTable('menus', 'men_id');
        parent::__construct($id, $dbTable, $tableData);
        $this->id = $this->tableData['men_id'];
        $this->name = $this->tableData['men_name'];
        $this->price = $this->tableData['men_price'];
        $this->price1 = $this->tableData['men_price1'];
        $this->price2 = $this->tableData['men_price2'];
        $this->priceEditable = boolval($this->tableData['men_editable']);
        $this->unit = new Unit($this->tableData['men_unit_id']);
        $this->image = new Image($this->tableData['men_image']);
    }

}