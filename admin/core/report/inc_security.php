<?
require_once '../../resources/security/security.php';
$module_id	= 15;
$module_name = '';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$cat_table = 'categories_multi';
$today = time();
$formDate = gmdate("d/m/Y", $today - 2592000);
$toDate = gmdate("d/m/Y", $today);
