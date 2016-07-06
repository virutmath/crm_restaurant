<?
require_once '../../resources/security/security.php';
$module_id	= 7;
$module_name = 'Danh sách kho hàng';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$id_field = 'cat_id';
$bg_table = 'categories_multi';
$cat_type = 'stores';

$modal_title = array(
    'loadFormAddRecord'=>'Thêm mới kho hàng',
    'loadFormEditRecord'=>'Cập nhập kho hàng',
);