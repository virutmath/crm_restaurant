<?php
//Check logged
function checkLogged($denypath = "")
{
    checkloged();
    $denypath = $denypath ? $denypath : '../../resources/php/deny.php';
    //Check login
    $username = getValue('userlogin', 'str', 'SESSION', '');
    $password = getValue('password', 'str', 'SESSION', '');
    $admin_id = getValue("user_id", "int", "SESSION");
    $isAdmin = getValue("isAdmin", "int", "SESSION", 0);
    $db_check = new db_query("SELECT adm_id
								 FROM admin_users
								 LEFT JOIN admin_users_groups ON adu_group_id = adm_group_id
								 WHERE adm_loginname = '" . $username . "' AND adm_password = '" . $password . "'");
    if (mysqli_num_rows($db_check->result) > 0) {
        $check = mysqli_fetch_array($db_check->result);
        $adm_id = $check["adm_id"];
        $db_check->close();
        unset($db_check);
        if ($adm_id != $admin_id) {
            redirect($denypath);
        }
    } else {
        redirect($denypath);
    }
}

function checkLogin($username, $password)
{
    $username = replaceMQ($username);
    $password = replaceMQ($password);
    $db_check = new db_query("SELECT adm_id
                                FROM admin_users
                                WHERE adm_loginname = '" . $username . "' AND adm_password = '" . md5($password) . "'");
    if (mysqli_num_rows($db_check->result) > 0) {
        $check = mysqli_fetch_array($db_check->result);
        $adm_id = $check["adm_id"];
        $db_check->close();
        unset($db_check);
        return $adm_id;
    } else {
        $db_check->close();
        unset($db_check);
        return 0;
    }
}

//check access module
/**
 * @param $module_id
 * @return bool
 */
function checkAccessModule($module_id)
{
    global $load_header;
    if (!$module_id || !check_module_exist($module_id)) {
        trigger_error('Chưa khởi tạo module. Kiểm tra lại module id ', E_USER_ERROR);
    }
    $isAdmin = getValue("isAdmin", "int", "SESSION", 0);
    $isSuperAdmin = getValue('isSuperAdmin', 'int', 'SESSION', 0);
    if ($isAdmin || $isSuperAdmin) {
        //nếu có quyền truy cập module thì khởi tạo biến javascript global moduleID
        $load_header .= '<script>globalParams.moduleID = ' . $module_id . ';</script>';
        return true;
    }
    $group_id = getValue('user_group_id', 'int', 'SESSION', 0);
    $db = new db_query('SELECT * FROM admin_group_role WHERE group_id = ' . $group_id . ' AND module_id = ' . $module_id);
    if (mysqli_num_rows($db->result)) {
        unset($db);
        //nếu có quyền truy cập module thì khởi tạo biến javascript global moduleID
        $load_header .= '<script>globalParams.moduleID = ' . $module_id . ';</script>';
        return true;
    } else {
        redirect('../../resources/php/deny.php');
        return false;
    }
}

function check_module_exist($module_id)
{
    $db_query = new db_query('SELECT mod_id FROM modules WHERE mod_id = '.$module_id.' LIMIT 1');
    if(mysqli_fetch_assoc($db_query->result)){
        return true;
    }else{
        return false;
    }
}

//Check loged
function checkloged()
{
    $dm = $_SERVER["SERVER_NAME"];
    $dm = str_replace("www.", "", $dm);
    $db_select = new db_query("SELECT * FROM kdims WHERE kdm_domain = '" . md5($dm) . "' LIMIT 1");
    if ($row = mysqli_fetch_assoc($db_select->result)) {

        $array = str_debase($row["kdm_key"]);
        $row1 = json_decode($array, true);
        if ($row1 != null) {
            if (md5($row["kdm_key"] . "|" . $row1["pass"]) != $row["kdm_hash"]) {
                notifydie("Dang ky chua dung key");
            } else {
                return $row1;
            }
        } else {
            notifydie("Dang ky chua dung key");
        }

    } else {
        notifydie("Chua dang ky domain");
    }
}

function checkPermission($action)
{
    global $module_id;
    $group_id = getValue("user_group_id", "int", "SESSION");
    $isAdmin = getValue("isAdmin", "int", "SESSION", 0);
    $isSuperAdmin = getValue('isSuperAdmin', 'int', 'SESSION', 0);
    if ($isAdmin || $isSuperAdmin) {
        return true;
    }

    $db = new db_query('SELECT role_' . $action . '
						FROM admin_group_role
						WHERE group_id = ' . $group_id . ' AND module_id = ' . $module_id);
    $result = mysqli_fetch_assoc($db->result);
    if ($result['role_' . $action] == 1) {
        return true;
    } else {
        die('<div style="margin: 25px auto;padding: 10px;text-align: center"><h3>Bạn không có quyền truy cập chức năng này</h3></div>');
    }
}

//Kiểm tra quyền đặc biệt
function checkCustomPermission($permission = '')
{
    global $module_id;
    $group_id = getValue("user_group_id", "int", "SESSION");
    $isAdmin = getValue("isAdmin", "int", "SESSION", 0);
    $isSuperAdmin = getValue('isSuperAdmin', 'int', 'SESSION', 0);
    if ($isAdmin || $isSuperAdmin) {
        return true;
    }
    $db = new db_query('SELECT custom_role_id
                        FROM admin_group_role
                        WHERE group_id = ' . $group_id . '
                        AND module_id = ' . $module_id . '
                        LIMIT 1');
    $list_crole = mysqli_fetch_assoc($db->result);
    unset($db);
    if (!$list_crole) {
        die('<div style="margin: 25px auto;padding: 10px;text-align: center"><h3>Bạn không có quyền truy cập chức năng này</h3></div>');
    }
    $list_crole = $list_crole['custom_role_id'];
    $list_crole = explode(',', $list_crole);
    $db_crole = new db_query('SELECT *
                              FROM custom_roles
                              WHERE rol_module_id = ' . $module_id . ' AND rol_unique_tag = "' . $permission . '"
                              LIMIT 1');
    $crole = mysqli_fetch_assoc($db_crole->result);
    unset($db_crole);
    if (!$crole || !in_array($crole['rol_id'], $list_crole)) {
        die('<div style="margin: 25px auto;padding: 10px;text-align: center"><h3>Bạn không có quyền truy cập chức năng này</h3></div>');
    } else {
        return true;
    }
}

function check_desk_exist($desk_id)
{
    global $_list_desk;
    foreach ($_list_desk as $item) {
        if ($item['des_id'] == $desk_id)
            return true;
    }
    exit('Bàn không tồn tại trong chi nhánh hiện tại');
}

function getPermissionValue($action)
{
    global $module_id;
    $group_id = getValue("user_group_id", "int", "SESSION");
    $isAdmin = getValue("isAdmin", "int", "SESSION", 0);
    $isSuperAdmin = getValue('isSuperAdmin', 'int', 'SESSION', 0);
    if ($isAdmin || $isSuperAdmin) {
        return true;
    }
    if ($action == 'add' || $action == 'edit' || $action == 'delete' || $action == 'trash' || $action == 'recovery') {
        $db = new db_query('SELECT role_' . $action . '
						FROM admin_group_role
						WHERE group_id = ' . $group_id . ' AND module_id = ' . $module_id);
        $result = mysqli_fetch_assoc($db->result);
        if ($result['role_' . $action] == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        //quyền đặc biệt
        $db_crole = new db_query('SELECT *
                                  FROM custom_roles
                                  WHERE rol_module_id = ' . $module_id . ' AND rol_unique_tag = "' . $action . '"
                                  LIMIT 1');
        $crole = mysqli_fetch_assoc($db_crole->result);
        unset($db_crole);

        $db_check = new db_query('SELECT *
                                  FROM admin_group_role
                                  WHERE group_id = ' . $group_id . '
                                        AND module_id = ' . $module_id . '
                                  LIMIT 1');
        $list_crole = mysqli_fetch_assoc($db_check->result);
        unset($db_check);
        $list_crole = $list_crole['custom_role_id'];
        $list_crole = explode(',', $list_crole);
        return $crole && in_array($crole['rol_id'], $list_crole);
    }
}


function list_admin_control_button($btn_add = 0, $btn_edit = 0, $btn_delete = 0, $btn_refresh = 1)
{
    $html = '<div class="modal-control">';
    if ($btn_add) {
        $html .= '<span class="control-btn control-btn-add"><i class="fa fa-file-o"></i> Thêm</span>';
    }
    if ($btn_edit) {
        $html .= '<span class="control-btn deactivate control-btn-edit"><i class="fa fa-edit"></i> Sửa</span>';
    }
    if ($btn_delete) {
        $html .= '<span class="control-btn deactivate control-btn-trash"><i class="fa fa-trash"></i> Xóa</span>';
    }
    if ($btn_refresh) {
        $html .= '<span class="control-btn control-btn-refresh"><i class="fa fa-refresh"></i> Làm mới</span>';
    }
    $html .= '</div>';
    return $html;
}


function count_item_trash($table_name, $option_filter = '')
{
    if (!$option_filter)
        $db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "' . $table_name . '"');
    else
        $db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "' . $table_name . '" AND tra_option_filter = "' . $option_filter . '"');
    return $db_count->total;
}

function call_module_file($module_name, $action)
{
    if (file_exists('../' . $module_name . '/' . $action . '.php')) {
        return '../' . $module_name . '/' . $action . '.php';
    } else {
        return '../../core/' . $module_name . '/' . $action . '.php';
    }
}

function link_module_function($module_name, $file)
{
    return '/admin/core/' . $module_name . '/' . $file;
}

function check_super_admin()
{
    checkloged();
    $isSuperAdmin = getValue('isSuperAdmin', 'int', 'SESSION', 0);
    $denypath = '../../resources/php/deny.php';
    if (!$isSuperAdmin) {
        redirect($denypath);
    } else {
        return true;
    }
}

function move2trash($id_field, $record_id, $table, $array_data = array('field' => 'value'), $option_filter = '')
{
    $tra_data = base64_encode(json_encode($array_data));
    $tra_date = time();
    $db_insert = new db_execute('INSERT INTO trash (tra_record_id,tra_table,tra_date,tra_data,tra_option_filter)
								 VALUES("' . $record_id . '","' . $table . '",' . $tra_date . ',"' . $tra_data . '","' . $option_filter . '")');
    unset($db_insert);
    //delete trong bảng gốc
    $db_delete = new db_execute('DELETE FROM ' . $table . ' WHERE ' . $id_field . '=' . $record_id);
    unset($db_delete);
    //log action
    log_action(ACTION_LOG_TRASH, 'Xóa bản ghi ' . $record_id . ' từ bảng ' . $table);
}

function trash_recovery($record_id, $table)
{
    $trash_record = new db_query('SELECT * FROM trash WHERE tra_record_id = ' . $record_id . ' AND tra_table ="' . $table . '" LIMIT 1');
    $trash_record = mysqli_fetch_assoc($trash_record->result);
    $table_field = '';
    $table_value = '';
    $check_query = '';
    $table_data = json_decode(base64_decode($trash_record['tra_data']), 1);
    //Kiểm tra các field trong bảng gốc, nếu có field trong bảng cần khôi phục thì mới add vào câu insert
    $db_field = new db_query('SHOW FIELDS FROM ' . $table);
    $array_field = array();
    while ($row = mysqli_fetch_assoc($db_field->result)) {
        $array_field[] = $row['Field'];
    }
    foreach ($table_data as $key => $value) {
        //Nếu field không tồn tại trong bảng thì next
        if (!in_array($key, $array_field)) {
            continue;
        }
        $table_field .= $key . ',';
        $table_value .= '"' . $value . '",';
        $check_query .= $key . '= "' . $value . '" AND ';
    }
    $table_field = rtrim($table_field, ',');
    $table_value = rtrim($table_value, ',');

    $recovery_sql = 'INSERT INTO ' . $trash_record['tra_table'] . '(' . $table_field . ') VALUES (' . $table_value . ')';
    //echo $recovery_sql . '<br>';
    $db_insert = new db_execute($recovery_sql);
    unset($db_insert);
    //kiểm tra lại xem đã khôi phục được chưa
    $check_query = rtrim(trim($check_query), 'AND');
    $db_count = new db_count('SELECT count(*) as count FROM ' . $table . ' WHERE ' . $check_query);
    //echo 'SELECT count(*) as count FROM '.$table.' WHERE '.$check_key.' = "'.$check_value.'"';
    if ($db_count->total == 1) {
        //xóa bản ghi này trong thùng rác
        $db_del = new db_execute('DELETE FROM trash WHERE tra_id = ' . $trash_record['tra_id']);

        unset($db_del);
        //log lại
        log_action(ACTION_LOG_RECOVERY, 'Khôi phục bản ghi ' . $trash_record['tra_record_id'] . ' tới bảng ' . $trash_record['tra_table']);
        return true;
    } else {
        return false;
    }
}

function trash_list($table, $limit = 10, $start = 0, $sql_option = '')
{
    $start = (int)$start;
    $limit = (int)$limit;
    $db_query = new db_query('SELECT *
							  FROM trash
							  WHERE tra_table = "' . $table . '" ' . $sql_option . '
							  ORDER BY tra_record_id ASC
							  LIMIT ' . $start . ',' . $limit);
    $list_data = array();
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $row['tra_data'] = json_decode(base64_decode($row['tra_data']), 1);
        $list_data[] = $row['tra_data'];
    }
    return $list_data;
}

function terminal_delete($record_id, $table)
{
    //kiểm tra xem có trong thùng rác không
    $db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_record_id = ' . $record_id . ' AND tra_table = "' . $table . '"');
    if ($db_count->total) {
        $db_ex = new db_execute('DELETE FROM trash WHERE tra_record_id = ' . $record_id . ' AND tra_table = "' . $table . '"');
        unset($db_ex);
        log_action(ACTION_LOG_DELETE, 'Xóa hoàn toàn bản ghi ' . $record_id . ' của bảng ' . $table);
    }
}

function log_action($action_type, $action_message = '')
{
    $alo_admin_id = getValue('user_id', 'int', 'SESSION', 0);
    $alo_action_time = time();
    $db_insert = new db_execute('INSERT INTO admin_logs(alo_admin_id,alo_action_type,alo_action_time,alo_message)
								 VALUES(' . $alo_admin_id . ',"' . $action_type . '",' . $alo_action_time . ',"' . $action_message . '")');
    unset($db_insert);
}

function category_type($type, $parent_id = null)
{
    if ($parent_id !== null) {
        $parent_id = (int)$parent_id;
        $db_query = new db_query('SELECT * FROM categories_multi WHERE cat_type ="' . $type . '" AND cat_parent_id = ' . $parent_id);
    } else {
        $db_query = new db_query('SELECT * FROM categories_multi WHERE cat_type ="' . $type . '"');
    }
    $result = $db_query->resultArray();
    return $result;
}
