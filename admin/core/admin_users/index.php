<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute'){
    switch($action_modal){
        case 'add_user_group':
            $myform = new generate_form();
            $myform->addTable('admin_users_groups');
            $myform->add('adu_group_name','adu_group_name',0,0,'',1,'Chưa có tên nhóm');
            $myform->add('adu_group_note','adu_group_note',0,0,'');
            if(!$myform->checkdata()){
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới nhóm tài khoản ID '.$last_id.' bảng admin_users_groups');
                redirect('index.php');
            }
            break;
        case 'edit_user_group':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable('admin_users_groups');
            $myform->add('adu_group_name','adu_group_name',0,0,'',1,'Chưa có tên nhóm');
            $myform->add('adu_group_note','adu_group_note',0,0,'');
            if(!$myform->checkdata()){
                $db_insert = new db_execute($myform->generate_update_SQL('adu_group_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa nhóm tài khoản ID '.$record_id.' bảng admin_users_groups');
                redirect('index.php');
            }
            break;
        case 'add_user':
            $adm_password = getValue('adm_password','str','POST','',3);
            $adm_password = md5($adm_password);
            $myform = new generate_form();
            $myform->addTable('admin_users');
            $myform->add('adm_loginname','adm_loginname',0,0,'',1,'Chưa có tên tài khoản',1,'Tài khoản đã tồn tại');
            $myform->add('adm_name','adm_name',0,0,'',1,'Chưa nhập tên hiển thị');
            $myform->add('adm_note','adm_note',0,0,'',0,'');
            $myform->add('adm_group_id','adm_group_id',1,0,0,1,'Bạn chưa chọn nhóm quản lý');
            $myform->add('adm_password','adm_password',0,1,'',1,'Chưa nhập mật khẩu');
            if(!$myform->checkdata()){
                //kiểm tra xem có fai nhóm quản lý không
                $admin_group = getValue('adm_group_id','int','POST',0);
                if($admin_group !== 1) {
                    //không phải nhóm quản lý admin mặc định
                    $myform->add('adm_user_config','admin_id',1,1,$admin_id);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới tài khoản ID '.$last_id.' bảng admin_users');
                redirect('index.php');
            }
            break;
        case 'edit_user':
            $record_id = getValue('record_id','int','POST',0);

            $myform = new generate_form();
            $myform->addTable('admin_users');
            $myform->add('adm_name','adm_name',0,0,'',1,'Chưa nhập tên hiển thị');
            $myform->add('adm_note','adm_note',0,0,'',0,'');
            $myform->add('adm_group_id','adm_group_id',1,0,0,1,'Bạn chưa chọn nhóm quản lý');
            $adm_password = getValue('adm_password','str','POST','',3);
            if($adm_password){
                $adm_password = md5($adm_password);
                $myform->add('adm_password','adm_password',0,1,'',1,'Chưa nhập mật khẩu');
            }
            if(!$myform->checkdata()){
                $db_insert = new db_execute($myform->generate_update_SQL('adm_id',$record_id));
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa tài khoản ID '.$record_id.' bảng admin_users');
                redirect('index.php');
            }
            break;
        case 'permission_group':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            //quyền sử dụng module
            $use_module = getValue('use_module','arr','POST',array());
            //xóa các permission cũ
            $db_del = new db_execute('DELETE FROM admin_group_role WHERE group_id = '.$record_id);
            unset($db_del);
            foreach($use_module as $module){
                //list các luật đặc biệt
                $custom_role_id = getValue('custom_role'.$module,'arr','POST',array());
                //join list này thành chuỗi để lưu vào csdl
                $custom_role_id = implode(',',$custom_role_id);
                //lấy quyền add, edit, delete, recovery theo từng module
                $role_add = getValue('role_add'.$module,'int','POST',0);
                $role_edit = getValue('role_edit'.$module,'int','POST',0);
                $role_delete = getValue('role_delete'.$module,'int','POST',0);
                $role_recovery = getValue('role_recovery'.$module,'int','POST',0);
                $role_trash = getValue('role_trash'.$module,'int','POST',0);
                $sql_insert = 'INSERT INTO
                                  admin_group_role(group_id,
                                                   module_id,
                                                   custom_role_id,
                                                   role_add,
                                                   role_edit,
                                                   role_delete,
                                                   role_trash,
                                                   role_recovery)
                               VALUES ('.$record_id.',
                                        '.$module.',
                                        "'.$custom_role_id.'",
                                        '.$role_add.',
                                        '.$role_edit.',
                                        '.$role_delete.',
                                        '.$role_trash.',
                                        '.$role_recovery.')
                               ON DUPLICATE KEY UPDATE
                                                custom_role_id = "'.$custom_role_id.'",
                                                role_add = '.$role_add.',
                                                role_edit = '.$role_edit.',
                                                role_delete = '.$role_delete.',
                                                role_trash = '.$role_trash.',
                                                role_recovery = '.$role_recovery.';';
                $db_execute = new db_execute($sql_insert);
                unset($db_execute);
            }
            //log action
            log_action(ACTION_LOG_EDIT,'Chỉnh sửa quyền cho nhóm tài khoản '.$record_id);
            redirect('index.php');
            break;
    }
}


//Phần hiển thị
//Khởi tạo
$left_control = '';
$right_control = '';
$left_column = '';
$right_column = '';
$context_menu = '';

//khối bên trái hiển thị nhóm quản lý : admin_users_groups
$left_control = list_admin_control_button(1,1,1,1);
//count all user
$db_count = new db_count('SELECT count(*) as count
                          FROM admin_users');
$left_count_all_user = $db_count->total;unset($db_count);
$array_group = array();
$db_query = new db_query('SELECT adu_group_id, adu_group_name
                          FROM admin_users_groups');
while($row = mysqli_fetch_assoc($db_query->result)){
    $db_select = new db_count('SELECT count(*) as count
                                FROM admin_users
                                WHERE adm_group_id = '.$row['adu_group_id']);
    $row['count'] = $db_select->total;unset($db_select);
    $array_group[] = $row;
}
ob_start();
?>
<ul class="list-unstyled list-vertical-crm">
    <li data-group-user="all">
        <label class="active user_group_name"><b><i class="fa fa-list fa-fw"></i> Tất cả (<?=$left_count_all_user?>)</b></label>
    </li>
    <? foreach($array_group as $group_item) {
        ?>
    <li class="list-vertical-item" data-group-user="<?=$group_item['adu_group_id']?>" data-count-user="<?=$group_item['count']?>">
        <label class="user_group_name"><?=$group_item['adu_group_name']?> (<?=$group_item['count']?>)</label>
    </li>
    <?
    }?>
    <li data-group-user="trash">
        <label class="user_group_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?=count_item_trash('admin_users')?>)</b></label>
    </li>
</ul>
<div id="btn-module-permission" class="abs deactivate" onclick="module_permission()">
    <span>Phân quyền nhóm tài khoản</span>
</div>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối bên phải, hiển thị list user tương ứng
$right_control = list_admin_control_button(1,1,1,1);
#Bắt đầu với datagrid
$list = new dataGrid($id_field,30);
$list->add('','Tài khoản');
$list->add('','Tên hiển thị');
$list->add('','Ghi chú');

//Biến group user
$group_user = getValue('group','int','POST',0);
if($group_user && $isAjaxRequest) {
    $sql_extra = ' AND adm_group_id = ' . $group_user . ' ';
}else{
    $sql_extra = '';
}

$db_count = new db_count('SELECT count(*) as count
                            FROM admin_users
                            WHERE 1 '.$list->sqlSearch(). $sql_extra . '
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM admin_users
                            WHERE 1 '.$list->sqlSearch(). $sql_extra .'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$right_column .= $list->showHeader($total_row);
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $right_column .= $list->start_tr($i,$row[$id_field],'class="context-menu-user" onclick="selectRow('.$row[$id_field].')" data-user-id="'.$row[$id_field].'"');
    $right_column .= '<td>'.$row['adm_loginname'].'</td>';
    $right_column .= '<td>'.$row['adm_name'].'</td>';
    $right_column .= '<td>'.$row['adm_note'].'</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();

//xử lý load danh sách kiểu ajax
if($isAjaxRequest) {
    $action = getValue('action','str','POST','');
    switch($action) {
        case 'getListUser' :
            echo $right_column;
            break;
    }
    //kết thúc ajax request;
    die();
}

$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);

$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('modal_2column');