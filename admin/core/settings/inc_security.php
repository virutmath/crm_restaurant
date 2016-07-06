<?
require_once '../../resources/security/security.php';
$module_id	= 1;
$module_name = 'Cài đặt hệ thống';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'configurations';
$id_field = 'con_id';
