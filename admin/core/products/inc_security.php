<?
require_once '../../resources/security/security.php';
$module_id	= 7;
$module_name = 'Quản lý kho hàng';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'products';
$id_field = 'pro_id';
$cat_field = 'pro_cat_id';
$cat_type = 'products';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm mới nhóm mặt hàng',
    'loadFormEditCategory'=>'Cập nhật nhóm mặt hàng',
    'loadFormAddRecord'=>'Thêm mới mặt hàng',
    'loadFormEditRecord'=>'Cập nhật mặt hàng'
);

$listing_product_size = 15;