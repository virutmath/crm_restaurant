<?
require_once '../../resources/security/security.php';
$module_id	= 4;
$module_name = 'Quản Lý Công Nợ';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$cat_field = '';


$bg_table_i = 'bill_in';
$id_field_i = 'bii_id';
$cus_table = 'customers';
$sup_table = 'suppliers';
$bg_table_o = 'bill_out';
$id_field_o = 'bio_id';
$financies = 'financial';


$modal_title = array(
    'loadFormAddCategory'=>'',
    'loadFormEditCategory'=>'',
    'loadFormAddRecord'=>'',
    'loadFormEditRecord'=>''
);