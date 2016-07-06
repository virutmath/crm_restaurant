<?
require_once '../../resources/security/security.php';
$module_id	= 12;
$module_name = 'Danh sách phiếu thu chi';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$id_field = 'cat_id';
$bg_table = 'categories_multi';
$cat_type_in = 'money_in';
$cat_type_out = 'money_out';
$modal_title = array(
    'loadFormAddRecord'=>'Thêm mới lý do thu chi',
    'loadFormEditRecord'=>'Sửa lý do thu chi',
);