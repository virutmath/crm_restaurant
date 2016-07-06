<?
require_once 'config.php';
$array_return = array();
//Lấy ra configuration
$username = getValue('username','str','POST','',3);
$password = getValue('password','str','POST','',3);
$user_id = checkLogin($username,$password);
if(!$user_id) {
    $array_return = array('error'=>401,'msg'=>'Lỗi đăng nhập!');
    ob_clean();
    header("Access-Control-Allow-Origin: *");
    echo json_encode($array_return);
    die();
}
//select ra admin_user
$db_user = new db_query('SELECT adm_user_config, adu_group_admin
                         FROM admin_users
                         LEFT JOIN admin_users_groups ON adu_group_id = adm_group_id
                         WHERE adm_id = ' . $user_id . '
                         LIMIT 1');
$user_detail = mysqli_fetch_assoc($db_user->result);
$isAdmin = intval($user_detail['adu_group_admin']);
//Lấy ra config nhà hàng
if($isAdmin) {
    $sql_configuration = 'SELECT * FROM configurations WHERE con_admin_id = '.$user_id.' LIMIT 1';
}else{
    $sql_configuration = 'SELECT *
                          FROM configurations
                          LEFT JOIN admin_users ON adm_id
                          WHERE con_admin_id = '.$user_detail['adm_user_config'].' LIMIT 1';
}
$db_config = new db_query($sql_configuration);
$config = mysqli_fetch_assoc($db_config->result);unset($db_config);
$array_return['config_common'] = $config;

//lấy ra danh sách các bàn của chi nhánh này


//Lấy ra danh sách các menu
$array_return['list_menu'] = array();
$db_query = new db_query('SELECT men_name, men_price, men_price1, men_price2, uni_name AS men_unit
                          FROM menus
                          LEFT JOIN units ON men_unit_id = uni_id
                          LIMIT 30');
$stt = 1;
while($row = mysqli_fetch_assoc($db_query->result)) {
    $row['stt'] = $stt++;
    $array_return['list_menu'][] = $row;
}

//Lấy ra các bàn đang mở để active
$desk_active_id = array();
$db_current_desk = new db_query('SELECT * FROM current_desk');
while($row = mysqli_fetch_assoc($db_current_desk->result)) {
    $desk_active_id[] = $row['cud_desk_id'];
}
//lấy ra các thực đơn có trong các bàn đang mở
$menu_in_desk = array();
$db_menu = new db_query('SELECT * FROM current_desk_menu');

//lấy ra danh sách khu vực bàn ăn
$db_query = new db_query('SELECT sec_id,sec_name
                          FROM sections
                          LEFT JOIN service_desks ON sec_service_desk = sed_id
                          WHERE sed_agency_id = ' . $config['con_default_agency']);
while($row = mysqli_fetch_assoc($db_query->result)) {
    //select ra các bàn trong section này
    $db_desk = new db_query('SELECT des_id,des_name
                             FROM desks
                             WHERE des_sec_id = ' . $row['sec_id']);
    while($row_desk = mysqli_fetch_assoc($db_desk->result)) {
        $row_desk['full_name'] = $row['sec_name'] . ' - ' . $row_desk['des_name'];
        //Nếu bàn này có trong list active thì thêm active vào
        $row_desk['active'] = in_array($row_desk['des_id'],$desk_active_id);
        $row['list_desk'][] = $row_desk;
    }
    unset($db_desk);
    $row['count'] = count($row['list_desk']);
    $list_desk[] = $row;
}
$array_return['list_desk'] = $list_desk;





ob_clean();
header("Access-Control-Allow-Origin: *");
echo json_encode($array_return);