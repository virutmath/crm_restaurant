<?
require_once '../../resources/security/security.php';
$module_id	    = 5;
$module_name    = 'Quản lý quầy dịch vụ - cửa hàng';
//Phần này hơi đặc biệt - quầy dịch vụ đóng vai trò là record, cửa hàng đóng vai trò là category
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg    = '';
$bg_table       = 'service_desks';
$id_field       = 'sed_id';
$cat_field      = 'sed_agency_id';
$cat_table      = 'agencies';

$modal_title = array(
    'loadFormAddCategory'=>'Thêm mới cửa hàng',
    'loadFormEditCategory'=>'Sửa thông tin cửa hàng',
    'loadFormAddRecord'=>'Thêm mới quầy phục vụ',
    'loadFormEditRecord'=>'Sửa thông tin quầy phục vụ'
);