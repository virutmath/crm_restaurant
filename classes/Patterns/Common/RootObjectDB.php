<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 10/13/2015
 * Time: 8:47 AM
 */

namespace Patterns\Common;

/**
 * Class RootObjectDB
 * @package Patterns\Common
 * Lớp ảo - thể hiện ảo của tất cả các đối tượng được chuyển từ databse sang object
 */
abstract class RootObjectDB
{
    protected $id;
    protected $dbTable;
    protected $tableData;

    /**
     * @param int $id
     * @param DBTable|null $dbTable
     * @param array $tableData
     */
    protected function __construct($id = 0, DBTable $dbTable = null, $tableData = array()) {
        if($id) {
            $this->id = $id;
        }
        if($dbTable) {
            $this->dbTable = $dbTable;
        }
        if($tableData) {
            $this->tableData = $tableData;
        }else{
            $this->tableData = $this->dbTable->getRowById($this->id)->selectField('*')->result();
        }

    }
}