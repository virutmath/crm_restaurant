<?
require_once '../../resources/security/security.php';
$module_id	= 11;
$module_name = 'Quản lý nhân sự';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'users';
$id_field = 'use_id';
$cat_field = 'use_group_id';
$cat_type = 'users';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm mới nhóm nhân sự',
    'loadFormEditCategory'=>'Cập nhật nhóm nhân sự',
    'loadFormAddRecord'=>'Thêm mới nhân sự',
    'loadFormEditRecord'=>'Cập nhật nhân sự'
);