<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 5:09 PM
 */

namespace Patterns\Common;


class DBTable
{
    private $table_name;
    private $table_id_field;
    private $results;
    private $sql_builder;
    private $where_conditions;
    private $select_params = array();

    public function __construct($table_name, $table_id_field)
    {
        $this->table_name = $table_name;
        $this->table_id_field = $table_id_field;
    }


    public function getRowById($id)
    {
        $this->select_params['id'] = $id;
        $this->addWhere($this->table_id_field . '=' . $this->select_params['id']);
        return $this;
    }

    public function addWhere($string_condition) {
        $this->where_conditions[] = $this->addBlankSpace(trim($string_condition));
        return $this;
    }

    public function selectField($string = '*')
    {
        if (is_string($string)) {
            $this->select_params['field'] = $string;
        }
        if (is_array($string)) {
            $this->select_params['field'] = implode(',', $string);
        }
        return $this;
    }
    public function result() {
        $this->queryBuilder();
        $db = new DB();
        $this->results = $db->query($this->sql_builder)->fetch_assoc()->result;
        return $this->results;
    }
    public function results() {
        $this->queryBuilder();
        $db = new DB();
        $this->results = $db->query($this->sql_builder)->fetch_all()->resultArray;
        return $this->results;
    }

    private function queryBuilder()
    {
        $where_string = '';
        if (!isset($this->select_params['field'])) {
            $this->select_params['field'] = '*';
        }
        if($this->where_conditions) {
            $where_string = 'WHERE ' . implode('AND',$this->where_conditions);
        }
        $this->sql_builder = 'SELECT ' . $this->addBlankSpace($this->select_params['field']) .
                            'FROM ' . $this->addBlankSpace($this->table_name) .
                            $where_string;

    }

    private function addBlankSpace($string, $position = 'both')
    {
        switch (strtolower($position)) {
            case 'l' :
            case 'left' :
                $string = ' ' . $string;
                break;
            case 'r' :
            case 'right' :
                $string = $string . ' ';
                break;
            case 'b':
            case 'both' :
            default :
                $string = ' ' . $string . ' ';
                break;
        }
        return $string;
    }

}