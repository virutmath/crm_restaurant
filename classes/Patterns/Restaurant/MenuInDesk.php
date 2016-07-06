<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 10/13/2015
 * Time: 4:26 PM
 */

namespace Patterns\Restaurant;


class MenuInDesk extends Menu
{
    var $desk;
    function __construct($id = 0, $tableData = array(), Desk $desk) {
        parent::__construct($id, $tableData);
        $this->desk = $desk;
    }

}