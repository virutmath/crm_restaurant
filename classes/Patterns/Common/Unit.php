<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 2:33 PM
 */

namespace Patterns\Common;


class Unit extends RootObjectDB
{
    var $name;
    var $id;
    function __construct($id = 0, $tableData = array()) {
        $dbTable = new DBTable('units','uni_id');
        parent::__construct($id,$dbTable,$tableData);
        $this->name = $this->tableData['uni_name'];
        $this->id = $this->tableData['uni_id'];
        return $this;
    }
}