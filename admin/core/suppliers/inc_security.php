<?
require_once '../../resources/security/security.php';
$module_id	= 10;
$module_name = 'Quản lý nhà cung cấp';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'suppliers';
$id_field = 'sup_id';
$cat_field = 'sup_cat_id';
$cat_type = 'supplier';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm mới nhóm nhà cung cấp',
    'loadFormEditCategory'=>'Chỉnh sửa nhóm nhà cung cấp',
    'loadFormAddRecord'=>'Thêm mới nhà cung cấp',
    'loadFormEditRecord'=>'Thay đổi thông tin nhà cung cấp'
);