<?
require_once '../../resources/security/security.php';
$module_id	= 8;
$module_name = 'Quản lý khuyến mãi';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'promotions';
$id_field = 'pms_id';

$modal_title = array(
    'loadFormAddRecord'=>'Thêm mới chiến dịch khuyễn mãi',
    'loadFormEditRecord'=>'Cập nhật chiến dịch khuyến mãi',
    'listRecordTrash' => 'Thùng rác',
);

$listing_menu_size = 15;