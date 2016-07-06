<?
require_once '../../resources/security/security.php';
$module_id	= 0;
$module_name = '';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = '';
$id_field = '';
$cat_field = '';
$cat_type = 'supplier';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'',
    'loadFormEditCategory'=>'',
    'loadFormAddRecord'=>'',
    'loadFormEditRecord'=>''
);