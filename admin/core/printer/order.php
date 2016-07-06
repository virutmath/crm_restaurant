<?
require_once 'inc_security.php';
checkCustomPermission('IN_CHE_BIEN');
if(!$_list_desk) {
    exit('Chi nhánh hiện tại chưa tạo bàn nào');
}
$desk_id = getValue('desk_id','int','GET',0);
$list_desk = array();
//lấy ra chi tiết bàn
$desk_detail = array();
foreach($_list_desk as $desk) {
    $list_desk[] = $desk['des_id'];
    if($desk['des_id'] == $desk_id) {
        $desk['desk_name'] = $desk['sec_name'] . ' - ' . $desk['des_name'];
        $desk_detail = $desk;
    }
}
if(!$desk_detail) {
    exit('Bàn chưa được mở hoặc không khả dụng');
}

$list_desk = implode(',',$list_desk);
//kiểm tra xem bàn có mở hay không
$sql_check_table = 'SELECT cdm_desk_id
                    FROM current_desk_menu
                    WHERE cdm_desk_id = ' . $desk_id.'
                    LIMIT 1';
$db_check_table = new db_query($sql_check_table);
if(!mysqli_num_rows($db_check_table->result)) {
    exit('Không thể in thực đơn cho bàn trống');
}
$db_unit = new db_query('SELECT *
                         FROM units');
$array_unit = array();
while($row = mysqli_fetch_assoc($db_unit->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
}

$db_list_menu = new db_query('SELECT men_id,men_name,cdm_number,cdm_printed_number,men_unit_id
                              FROM menus
                              LEFT JOIN current_desk_menu ON cdm_menu_id = men_id
                              WHERE cdm_desk_id = ' . $desk_id. '
                              AND cdm_printed_number < cdm_number');
$list_menu = array();
$stt = 1;
while($row = mysqli_fetch_assoc($db_list_menu->result)) {
    $row['stt'] = $stt++;
    $row['uni_name'] = $array_unit[$row['men_unit_id']];
    $row['print_number'] = $row['cdm_number'] - $row['cdm_printed_number'];
    $list_menu[] = $row;
}
add_more_css('css/print_order.css',$load_header);
add_more_css('css/print_order.css',$load_header,'print');
$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('configuration',$configuration);
$rainTpl->assign('print_date',date('d/m/Y H:i:s',time()));
$rainTpl->assign('list_menu',$list_menu);
$rainTpl->assign('list_menu_json',json_encode($list_menu));
$rainTpl->assign('desk_detail',$desk_detail);
$rainTpl->draw('v2/printer/order');