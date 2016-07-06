<?
require_once '../../resources/security/security.php';
$module_id	= 10;
$module_name = 'Quản lý tiền mặt';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'financial';
$id_field = 'fin_id';


$modal_title = array(

);