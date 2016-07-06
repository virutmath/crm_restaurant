<?
require_once '../../resources/security/security.php';
$module_id	= 12;
$module_name = '';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = 'financial';
$id_field = 'fin_id';
$cat_field = 'fin_cat_id';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddMoneyTicketIn' => 'Thêm mới phiếu thu',
    'loadFormEditMoneyTicketIn' => 'Chỉnh sửa phiếu thu',
    'loadFormAddMoneyTicketOut' => 'Thêm mới phiếu chi',
    'loadFormEditMoneyTicketOut' => 'Chỉnh sửa phiếu chi',
    'listRecordTrash' => 'Thùng rác',
    'viewTrashMoneyUser'=> 'Xem thông tin cập nhật'
);

#@Cấu hình cài đặt, hiển thị
//Số ngày mặc định hiển thị phiếu thu
$number_date_in = 30;
//Thời gian hiển thị mặc định phiếu thu
$default_end_date_in = time();
$default_start_date_in = $default_end_date_in;
//Số ngày mặc định hiển thị phiếu chi
$number_date_out = 30;
//Thời gian hiển thị mặc định phiếu thu
$default_end_date_out = time();
$default_start_date_out = $default_end_date_out;