<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class SampleAjax extends AjaxCommon {
    function _loadFormAddCategory()
    {
        parent::_loadFormAddCategory(); // TODO: Change the autogenerated stub
    }

    function _loadFormEditCategory()
    {
        parent::_loadFormEditCategory(); // TODO: Change the autogenerated stub
    }

    function _loadFormAddRecord()
    {
        parent::_loadFormAddRecord(); // TODO: Change the autogenerated stub
    }

    function _loadFormEditRecord()
    {
        parent::_loadFormEditRecord(); // TODO: Change the autogenerated stub
    }
}
$ajax = new SampleAjax();
$ajax->execute();
