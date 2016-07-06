<?
require_once '../../resources/security/security.php';
$module_id	= 14;
$module_name = 'Quản lý tài khoản người dùng';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'admin_users';
$id_field = 'adm_id';