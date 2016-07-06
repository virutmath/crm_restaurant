<?
require_once '../../resources/security/security.php';
$module_id	= 3;
$module_name = 'Quản lý thực đơn';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
