<?
require_once '../../resources/security/security.php';
$module_id	= 7;
$module_name = 'Quản lý kho hàng';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';

$modal_title = array(
    'listTrashInventory'=> 'Thùng rác'
);

$listing_product_size = 15;