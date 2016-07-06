<?
require_once 'inc_security.php';

global $isAdmin;
global $admin_id;

//Phần xử lý
if ($isAjaxRequest) {
    $action = getValue('action', 'str', 'POST', '', 2);
    switch ($action) {
        case 'setup':
            $array_return = array();
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('con_restaurant_name', 'res_name', 0, 0, '');
            $myform->add('con_restaurant_address', 'res_address', 0, 0, '');
            $myform->add('con_restaurant_phone', 'res_phone', 0, 0, '');
            // quay tinh tien
            $myform->add('con_default_svdesk', 'con_default_svdesk', 1, 0, 0);
            //kho hang
            $myform->add('con_default_store', 'con_default_store', 1, 0, 0);
            if (!$myform->checkdata()) {
                $con_picture = getValue('con_restaurant_image', 'str', 'POST', '');
                if ($con_picture) {
                    $myform->add('con_restaurant_image', 'con_restaurant_image', 0, 0, '');
                    //upload ảnh
                    module_upload_picture($con_picture);
                }
                $db_update = new db_execute($myform->generate_update_SQL('con_admin_id', $admin_id));
                unset($db_update);
                // log action
                log_action(ACTION_LOG_ADD, 'Cập nhật hệ thống cài đặt chung');

                $array_return['msg']        = "Cập nhập thành công";
                $array_return['success']    = 1;
                die(json_encode($array_return));
            }
            break;
        case 'setup-system':
            break;
        // cập nhập thực đơn mặc định khi bắt đầu
        case 'defaultMenus':
            global $configuration;
            //trước khi cập nhập sẽ xóa dữ liệu trong con_start_menu ở bảng configurations rồi sau đó sẽ thêm mới vào
            $db_delete = new db_execute('DELETE con_start_menu FROM configurations WHERE con_id = '.$configuration['con_id'].'');
            unset($db_delete);


            // tạo mảng
            $array_start_menu = array();
            // lấy mảng menu trả về
            $list_menu = getValue('menus','arr','POST','');
            if($list_menu != ''){
                foreach($list_menu as $menu){
                    $array_start_menu[$menu['men_id']] = $menu['men_value'];
                }
            }
            if($array_start_menu == null){
                $str_start_menu = null;
            } else {
                $str_start_menu = base64_encode(json_encode($array_start_menu));
            }


            $db_update = 'UPDATE configurations
                          SET con_start_menu = "'.$str_start_menu.'"
                          WHERE con_id = '.$configuration['con_id'].'';
            $db_excute = new db_execute($db_update);
            unset($db_excute);
            $array_return['msg']        = "Cập nhập thành công";
            $array_return['success']    = 1;
            die(json_encode($array_return));
    }
}

// đổ giữ liệu ra form
$array_option_desk      = '';
$con_restaurant_name    = '';
$con_restaurant_address = '';
$con_restaurant_phone   = '';
$con_restaurant_image   = '';
$array_store            = '';

// lay thông tin tu bang configurations
//Lấy ra config nhà hàng

if ($isAdmin) {
    $sql_configuration = 'SELECT * FROM configurations WHERE con_admin_id = ' . $admin_id . ' LIMIT 1';
} else {
    $sql_configuration = 'SELECT *
                          FROM configurations
                          LEFT JOIN admin_users ON adm_id
                          WHERE con_admin_id = ' . $user_config . ' LIMIT 1';
}

// lấy ra các trường trong bảng configuration
$db_query_con = new db_query($sql_configuration);
$row_con = mysqli_fetch_assoc($db_query_con->result);
// gán dữ liệu vào các biến
$con_restaurant_name    = $row_con['con_restaurant_name'];
$con_restaurant_address = $row_con['con_restaurant_address'];
$con_restaurant_phone   = $row_con['con_restaurant_phone'];
$con_restaurant_image   = get_picture_path($row_con['con_restaurant_image']);
$con_default_svdesk     = $row_con['con_default_svdesk'];
$con_default_store      = $row_con['con_default_store'];
$con_default_agency     = $row_con['con_default_agency'];

unset($db_query_con);

//lấy ra quầy phục vụ mà đã được cấu hình chọn và selected
$db_query = new db_query('SELECT *
                          FROM service_desks
                          LEFT JOIN agencies ON sed_agency_id = age_id WHERE sed_agency_id = ' . $con_default_agency . '');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    if ($con_default_svdesk == $row['sed_id']) {
        $selected_check = 'selected="selected"';
    } else {
        $selected_check = '';
    }
    $array_option_desk .= '<option value="' . $row['sed_id'] . '" ' . $selected_check . '>' . $row['age_name'] . ' - ' . $row['sed_name'] . '</option>';
}
unset($db_query);


// lay danh sach kho hang
$db_query_store = new db_query('SELECT *FROM categories_multi WHERE cat_type = "stores"');

while ($row_store = mysqli_fetch_assoc($db_query_store->result)) {
    if ($con_default_store == $row_store['cat_id']) {
        $check_store = 'selected="selected"';
    } else {
        $check_store = '';
    }
    $array_store .= '<option value="' . $row_store['cat_id'] . '" ' . $check_store . '>' . $row_store['cat_name'] . '</option>';
}

unset($db_query_store);



$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('module_name', $module_name);
$rainTpl->assign('error_msg', print_error_msg($bg_errorMsg));
$rainTpl->assign('array_option_desk', $array_option_desk);
$rainTpl->assign('array_store', $array_store);
$rainTpl->assign('con_restaurant_address', $con_restaurant_address);
$rainTpl->assign('con_restaurant_phone', $con_restaurant_phone);
$rainTpl->assign('con_restaurant_name', $con_restaurant_name);
$rainTpl->assign('con_restaurant_image', $con_restaurant_image);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script', $custom_script);
$rainTpl->draw('modal_setup');