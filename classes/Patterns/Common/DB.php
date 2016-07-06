<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 2:44 PM
 */

namespace Patterns\Common;


class DB
{
    private $db_query;
    private $db_execute;
    public $resultArray;
    public $result;

    public function __construct()
    {
        return $this;
    }

    public function query($sql_query)
    {
        $this->db_query = new \db_query($sql_query);
        return $this;
    }

    public function fetch_assoc()
    {
        $this->result = mysqli_fetch_assoc($this->db_query->result);
        return $this;
    }

    public function fetch_all()
    {
        while ($row = mysqli_fetch_assoc($this->db_query->result)) {
            $this->resultArray[] = $row;
        }
        return $this;
    }

    public function execute($sql_execute)
    {
        $this->db_execute = new \db_execute($sql_execute);
    }
}