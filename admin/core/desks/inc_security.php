<?
require_once '../../resources/security/security.php';
$module_id	= 2;
$module_name = 'Quản lý khu vực, bàn ăn';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'desks';
$id_field = 'des_id';
$modal_title = array(
    'loadFormAddSection'=>'Thêm mới khu vực bàn ăn',
    'loadFormEditSection'=>'Cập nhật khu vực bàn ăn',
    'loadFormAddDesk'=>'Thêm mới bàn ăn',
    'loadFormEditDesk'=>'Cập nhật bàn ăn'
);