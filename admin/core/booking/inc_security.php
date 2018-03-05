<?
require_once '../../resources/security/security.php';
$module_id	= 17;
$module_name = 'Đặt bàn';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'booking';
$id_field = 'booking_id';
$cat_field = 'partner_id';
$cat_type = 'booking_partner';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'',
    'loadFormEditCategory'=>'',
    'loadFormAddRecord'=>'',
    'loadFormEditRecord'=>''
);