<?
require_once 'inc_security.php';
//cửa hàng mặc định
$age_id = $configuration['con_default_agency'];
//Lấy ra các bàn đang mở để active
$desk_active_id = array();
$db_current_desk = new db_query('SELECT * FROM current_desk');
while($row = mysqli_fetch_assoc($db_current_desk->result)) {
    $desk_active_id[] = $row['cud_desk_id'];
}
//lấy ra danh sách khu vực bàn ăn
$list_desk = array();
$db_query = new db_query('SELECT *
                          FROM sections
                          LEFT JOIN service_desks ON sec_service_desk = sed_id
                          WHERE sed_agency_id = ' . $age_id);
while($row = mysqli_fetch_assoc($db_query->result)) {
    //select ra các bàn trong section này
    $db_desk = new db_query('SELECT * FROM desks WHERE des_sec_id = ' . $row['sec_id']);
    while($row_desk = mysqli_fetch_assoc($db_desk->result)) {
        $row_desk['full_name'] = $row['sec_name'] . ' - ' . $row_desk['des_name'];
        //Nếu bàn này có trong list active thì thêm active vào
        $row_desk['active'] = in_array($row_desk['des_id'],$desk_active_id);
        $row['list_desk'][] = $row_desk;
    }
    unset($db_desk);
    $row['count'] = isset($row['list_desk']) ? count($row['list_desk']) : 0;
    $list_desk[] = $row;
}


$rainTpl = new RainTPL();
add_more_css('style.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('bottom_control', $bottom_control);
$rainTpl->assign('list_desk',$list_desk);
$custom_script = file_get_contents('script_mobile.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mobile_list_desk');