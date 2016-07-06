<?
require_once '../../resources/security/security.php';
$module_id	= 13;
$module_name = 'Quản lý thực đơn';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'menus';
$id_field = 'men_id';
$cat_field = 'men_cat_id';
$cat_type = 'menus';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm mới danh mục thực đơn',
    'loadFormEditCategory'=>'Cập nhật danh mục thực đơn',
    'loadFormAddRecord'=>'Thêm mới thực đơn',
    'loadFormEditRecord'=>'Cập nhật thực đơn',
    'loadFormAddMenuProduct'=>'Bổ sung nguyên liệu vào thực đơn',
    'loadFormEditMenuProduct'=>'Thay đổi số lượng nguyên liệu trong thực đơn'
);