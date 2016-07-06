<?
require_once '../../resources/security/security.php';
$module_id	= 9;
$module_name = 'Quản lý khách hàng';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'customers';
$id_field = 'cus_id';
$cat_field = 'cus_cat_id';
$cat_table = 'customer_cat';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm nhóm khách hàng',
    'loadFormEditCategory'=>'Cập nhật nhóm khách hàng',
    'loadFormAddRecord'=>'Thêm khách hàng',
    'loadFormEditRecord'=>'Cập nhật khách hàng'
);