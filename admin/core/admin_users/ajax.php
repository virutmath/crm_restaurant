<?
require_once 'inc_security.php';
$action = getValue('action','str','POST','',3);
switch($action){
    case 'loadFormAddGroupUser':
        $form = new form();
        $html = '';
        ?>
        <div class="modal-mini">
            <div class="modal-header">
                <label>Thêm mới nhóm tài khoản đăng nhập</label>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-mini-content">
                <?
                $html .= $form->form_open();
                $html .= $form->text(array(
                    'label'=>'Nhập tên',
                    'name'=>'adu_group_name',
                    'id'=>'adu_group_name',
                    'require'=>1,
                    'errorMSg'=>'Bạn chưa nhập tên nhóm'
                ));
                $html .= $form->textarea(array(
                    'label'=>'Ghi chú',
                    'name'=>'adu_group_note',
                    'id'=>'adu_group_note',
                    'extra'=>'style="height : 120px;"'
                ));
                $html .= $form->form_action(array(
                    'label'=>array('Lưu lại'),
                    'type'=>array('submit','reset'),
                    'extra'=>array('','modal-control="modal-close"')
                ));
                $html .= $form->hidden(array(
                    'name'=>'action_modal',
                    'id'=>'action_modal',
                    'value'=>'add_user_group'
                ));
                $html .= $form->form_close();
                echo $html;?>
            </div>
        </div>
        <?
        break;
    case 'getListUser':
        $group = getValue('group','str','POST','',3);
        $right_column = '';
        switch($group){
            case 'all':
                #Bắt đầu với datagrid
                $list = new dataGrid($id_field,30);
                $list->add('','Tài khoản');
                $list->add('','Tên hiển thị');
                $list->add('','Ghi chú');

                $db_count = new db_count('SELECT count(*) as count
                            FROM admin_users
                            WHERE 1 '.$list->sqlSearch().'
                            ');
                $total = $db_count->total;unset($db_count);

                $db_listing = new db_query('SELECT *
                            FROM admin_users
                            WHERE 1 '.$list->sqlSearch().'
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
                echo $right_column;
                break;
            case 'trash':
                //list user ở trong thùng rác
                $array_row = trash_list('admin_users');
                #Bắt đầu với datagrid
                $list = new dataGrid($id_field,10);
                $list->add('','Tài khoản');
                $list->add('','Tên hiển thị');
                $list->add('','Ghi chú');
                $db_count = new db_count('SELECT count(*) as count
                            FROM trash
                            WHERE tra_table = "admin_users"');
                $total = $db_count->total;unset($db_count);
                $list->limit($total);
                $total_row = count($array_row);
                $right_column .= $list->showHeader($total_row);
                $i=0;
                foreach($array_row as $row){
                    $i++;
                    $right_column .= $list->start_tr($i,$row['adm_id'],'class="context-menu-trash" data-user-id="'.$row['adm_id'].'"');
                    $right_column .= '<td>'.$row['adm_loginname'].'</td>';
                    $right_column .= '<td>'.$row['adm_name'].'</td>';
                    $right_column .= '<td>'.$row['adm_note'].'</td>';
                    $right_column .= $list->end_tr();
                }
                $right_column .= $list->showFooter();
                echo $right_column;
                break;
            default :
                $group = (int)$group;
                if($group){
                    #Bắt đầu với datagrid
                    $list = new dataGrid($id_field,30);
                    $list->add('','Tài khoản');
                    $list->add('','Tên hiển thị');
                    $list->add('','Ghi chú');

                    $db_count = new db_count('SELECT count(*) as count
                            FROM admin_users
                            WHERE adm_group_id = '.$group.' '.$list->sqlSearch().'
                            ');
                    $total = $db_count->total;unset($db_count);

                    $db_listing = new db_query('SELECT *
                            FROM admin_users
                            WHERE adm_group_id = '.$group.'  '.$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' DESC
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

                    echo $right_column;
                }
                break;
        }
        break;
    //phục hồi dữ liệu
    case 'recycleRow':
        $user_id = getValue('user','int','POST',0);
        $array_return = array();
        //phục hồi dữ liệu
        $result = trash_recovery($user_id,'admin_users');
        if($result){
            $array_return = array('success'=>1);
        }else{
            $array_return = array('success'=>0,'error'=>'Khôi phục không thành công');
        }
        die(json_encode($array_return));
        break;
    case 'loadFormAddUser':
        //load form thêm mới tài khoản đăng nhập
        $form = new form();
        $html = '';
        $array_group_user = array(0=>' - Chọn nhóm quản lý - ');
        $db_query = new db_query('SELECT * FROM admin_users_groups');
        while($row = mysqli_fetch_assoc($db_query->result)){
            $array_group_user[$row['adu_group_id']] = $row['adu_group_name'];
        }
        ?>
        <div class="modal-mini">
            <div class="modal-header">
                <label>Thêm mới tài khoản đăng nhập</label>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-mini-content">
                <?
                $html .= $form->form_open();
                $html .= $form->textnote('Các tài khoản không thuộc nhóm quản lý sẽ được sao chép cấu hình thông tin nhà hàng của tài khoản quản lý đang đăng nhập');
                $html .= $form->text(array(
                    'label'=>'Tài khoản',
                    'name'=>'adm_loginname',
                    'id'=>'adm_loginname',
                    'require'=>1,
                    'errorMSg'=>'Bạn chưa nhập tên tài khoản'
                ));
                $html .= $form->password(array(
                    'label'=>'Mật khẩu',
                    'name'=>'adm_password',
                    'id'=>'adm_password',
                    'require'=>1,
                    'errorMsg'=>'Bạn chưa nhập mật khẩu'
                ));
                $html .= $form->text(array(
                    'label'=>'Tên hiển thị',
                    'name'=>'adm_name',
                    'id'=>'adm_name',
                    'require'=>1,
                    'errorMsg'=>'Bạn chưa nhập tên hiển thị'
                ));
                $html .= $form->select(array(
                    'label'=>'Chọn nhóm quản lý',
                    'name'=>'adm_group_id',
                    'id'=>'adm_group_id',
                    'require'=>1,
                    'errorMsg'=>'Bạn chưa chọn nhóm quản lý',
                    'selected'=>0,
                    'option'=>$array_group_user
                ));
                $html .= $form->textarea(array(
                    'label'=>'Ghi chú',
                    'name'=>'adm_note',
                    'id'=>'adm_note',
                    'extra'=>'style="height : 90px;"'
                ));
                $html .= $form->form_action(array(
                    'label'=>array('Lưu lại'),
                    'type'=>array('submit','reset'),
                    'extra'=>array('','modal-control="modal-close"')
                ));
                $html .= $form->hidden(array(
                    'name'=>'action_modal',
                    'id'=>'action_modal',
                    'value'=>'add_user'
                ));
                $html .= $form->form_close();
                echo $html;?>
            </div>
        </div>
        <?
        break;
    case 'loadFormEditUser':
        //load form sửa tài khoản đăng nhập
        $form = new form();
        $html = '';
        $array_group_user = array(0=>' - Chọn nhóm quản lý - ');
        $db_query = new db_query('SELECT * FROM admin_users_groups');
        while($row = mysqli_fetch_assoc($db_query->result)){
            $array_group_user[$row['adu_group_id']] = $row['adu_group_name'];
        }
        $user_id = getValue('user','int','POST',0);
        //load data record cần sửa đổi
        $db_data 	= new db_query("SELECT * FROM admin_users WHERE adm_id = " . $user_id);
        if($row 		= mysqli_fetch_assoc($db_data->result)){
            foreach($row as $key=>$value){
                $$key = $value;
            }
        }else{
            exit();
        }
        ?>
        <div class="modal-mini">
            <div class="modal-header">
                <label>Sửa tài khoản đăng nhập</label>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-mini-content">
                <?
                $html .= $form->form_open();
                $html .= $form->text(array(
                    'label'=>'Tài khoản',
                    'name'=>'adm_loginname',
                    'id'=>'adm_loginname',
                    'require'=>1,
                    'value'=>$adm_loginname,
                    'errorMSg'=>'Bạn chưa nhập tên tài khoản',
                    'disabled'=>'disabled'
                ));
                $html .= $form->password(array(
                    'label'=>'Mật khẩu',
                    'name'=>'adm_password',
                    'id'=>'adm_password',
                    'require'=>'',
                    'placeholder'=>'Nếu không nhập, hệ thống sẽ dùng mật khẩu cũ'
                ));
                $html .= $form->text(array(
                    'label'=>'Tên hiển thị',
                    'name'=>'adm_name',
                    'id'=>'adm_name',
                    'require'=>1,
                    'value'=>$adm_name,
                    'errorMsg'=>'Bạn chưa nhập tên hiển thị'
                ));
                $html .= $form->select(array(
                    'label'=>'Chọn nhóm quản lý',
                    'name'=>'adm_group_id',
                    'id'=>'adm_group_id',
                    'require'=>1,
                    'errorMsg'=>'Bạn chưa chọn nhóm quản lý',
                    'selected'=>$adm_group_id,
                    'option'=>$array_group_user
                ));
                $html .= $form->textarea(array(
                    'label'=>'Ghi chú',
                    'name'=>'adm_note',
                    'id'=>'adm_note',
                    'value'=>$adm_note,
                    'extra'=>'style="height : 90px;"'
                ));
                $html .= $form->form_action(array(
                    'label'=>array('Lưu lại'),
                    'type'=>array('submit','reset'),
                    'extra'=>array('','modal-control="modal-close"')
                ));
                $html .= $form->hidden(array(
                    'name'=>'action_modal',
                    'id'=>'action_modal',
                    'value'=>'edit_user'
                ));
                $html .= $form->hidden(array(
                    'name'=>'record_id',
                    'id'=>'record_id',
                    'value'=>$user_id
                ));
                $html .= $form->form_close();
                echo $html;?>
            </div>
        </div>
        <?
        break;
    case 'loadFormEditGroup':
        //load form sửa nhóm tài khoản
        $form = new form();
        $html = '';
        $group_id = getValue('group','int','POST',0);
        //load data record cần sửa đổi
        $db_data 	= new db_query("SELECT * FROM admin_users_groups WHERE adu_group_id = " . $group_id);
        if($row 		= mysqli_fetch_assoc($db_data->result)){
            foreach($row as $key=>$value){
                $$key = $value;
            }
        }else{
            exit();
        }
        ?>
        <div class="modal-mini">
            <div class="modal-header">
                <label>Sửa nhóm tài khoản đăng nhập</label>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-mini-content">
                <?
                $html .= $form->form_open();
                $html .= $form->text(array(
                    'label'=>'Nhập tên',
                    'name'=>'adu_group_name',
                    'id'=>'adu_group_name',
                    'value'=>$adu_group_name,
                    'require'=>1,
                    'errorMsg'=>'Bạn chưa nhập tên nhóm tài khoản'
                ));
                $html .= $form->textarea(array(
                    'label'=>'Ghi chú',
                    'name'=>'adu_group_note',
                    'id'=>'adu_group_note',
                    'value'=>$adu_group_note,
                    'extra'=>'style="height : 160px;"'
                ));
                $html .= $form->form_action(array(
                    'label'=>array('Đồng ý','Hủy bỏ'),
                    'type'=>array('submit','reset'),
                    'extra'=>array('','modal-control="modal-close"')
                ));
                $html .= $form->hidden(array(
                    'name'=>'record_id',
                    'id'=>'record_id',
                    'value'=>$group_id
                ));
                $html .= $form->hidden(array(
                    'name'=>'action_modal',
                    'id'=>'action_modal',
                    'value'=>'edit_user_group'
                ));
                $html .= $form->form_close();
                echo $html;
                ?>
            </div>
        </div>
        <?
        break;
    case 'loadFormPermission':
        $group_id = getValue('group','int','POST',0);
        if($group_id === 1){
            echo 'Bạn không thể chỉnh sửa quyền của nhóm quản lý mặc định của hệ thống';
            exit();
        }
        $html = '';
        $form = new form();
        $html .= mini_modal_open('Phân quyền nhóm tài khoản', 'style="height:236px;width:480px;"');
        $html .= $form->form_open();
        $html .= $form->textnote('Chú ý : Nếu trong 1 chức năng, không tick vào quyền sử dụng thì các quyền khác không có hiệu lực');
        //lấy ra list các module, ứng với mỗi module lấy ra các quyền tương ứng
        $db_query = new db_query('SELECT * FROM modules');
        $list_module = array();
        while($row = mysqli_fetch_assoc($db_query->result)){
            //kiểm tra quyền của group trong mỗi module
            $db_role = new db_query('SELECT * FROM admin_group_role WHERE module_id = '.$row['mod_id'].' AND group_id = '.$group_id.' LIMIT 1');
            $true_roles = mysqli_fetch_assoc($db_role->result); unset($db_role);
            //Tạo các checkbox
            $list_checkbox = array();
            //Checkbox quyền sử dụng - được tick khi mod_id match với true_role[module_id]
            $list_checkbox[] = array(
                'name'=>'use_module[]',
                'id'=>'use_module'.$row['mod_id'],
                'value'=>$row['mod_id'],
                'label'=>'Sử dụng',
                'is_check'=>($true_roles ? TRUE : FALSE)
            );

            //lấy ra các quyền đặc biệt của module
            $db_custom_role = new db_query('SELECT * FROM custom_roles WHERE rol_module_id = '.$row['mod_id']);
            $i = 0;
            $true_custom_role = explode(',',$true_roles['custom_role_id']);
            while($row_custom = mysqli_fetch_assoc($db_custom_role->result)){
                $i++;
                $list_checkbox[] = array(
                    'name'=>'custom_role'.$row['mod_id'].'[]',
                    'id'=>'custom_role'.$row['mod_id'].$i,
                    'value'=>$row_custom['rol_id'],
                    'label'=>$row_custom['rol_name'],
                    'is_check'=>in_array($row_custom['rol_id'],$true_custom_role)
                );
            }
            $list_checkbox[] = array(
                'name'=>'role_add'.$row['mod_id'],
                'id'=>'role_add'.$row['mod_id'],
                'value'=>1,
                'label'=>'Thêm mới',
                'is_check'=>$true_roles['role_add']
            );
            $list_checkbox[] = array(
                'name'=>'role_edit'.$row['mod_id'],
                'id'=>'role_edit'.$row['mod_id'],
                'value'=>1,
                'label'=>'Sửa',
                'is_check'=>$true_roles['role_edit']
            );
            $list_checkbox[] = array(
                'name'=>'role_trash'.$row['mod_id'],
                'id'=>'role_trash'.$row['mod_id'],
                'value'=>1,
                'label'=>'Xóa',
                'is_check'=>$true_roles['role_trash']
            );
            $list_checkbox[] = array(
                'name'=>'role_delete'.$row['mod_id'],
                'id'=>'role_delete'.$row['mod_id'],
                'value'=>1,
                'label'=>'Xóa vĩnh viễn',
                'is_check'=>$true_roles['role_delete']
            );
            $list_checkbox[] = array(
                'name'=>'role_recovery'.$row['mod_id'],
                'id'=>'role_recovery'.$row['mod_id'],
                'value'=>1,
                'label'=>'Khôi phục',
                'is_check'=>$true_roles['role_recovery']
            );
            $html .= $form->list_checkbox(array(
                'label'=>$row['mod_name'],
                'list'=>$list_checkbox,
                'column'=>2
            ));
            $html .= $form->form_divider();
        }
        $html .= $form->form_action(array(
            'label'=>array('Đồng ý','Hủy bỏ'),
            'type'=>array('submit','reset'),
            'extra'=>array('','modal-control="modal-close"')
        ));
        $html .= $form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>'permission_group'
        ));
        $html .= $form->hidden(array(
            'name'=>'record_id',
            'id'=>'record_id',
            'value'=>$group_id
        ));
        $html .= $form->form_close();
        $html .= mini_modal_close();
        echo $html;
        break;
    case 'deleteGroupUser':
        //hàm xóa 1 nhóm tài khoản đăng nhập - trả về json
        $group_id = getValue('group','int','POST',0);
        $array_return = array();
        //kiểm tra xem group này còn user nào ko, nếu còn thì ko xóa được
        $check_user = new db_count('SELECT count(*) as count FROM admin_users WHERE adm_group_id = '.$group_id);
        if($check_user->total){
            $array_return = array('success'=>0,'error'=>'Bạn không thể xóa nhóm này, vì nó vẫn còn chứa các tài khoản. Vui lòng xóa các tài khoản đăng nhập trước');
            unset($db_count);
            die(json_encode($array_return));
            break;
        }
        //xóa nhóm tài khoản vào thùng rác
        $db_query = new db_query('SELECT * FROM admin_users_groups WHERE adu_group_id = '.$group_id .' LIMIT 1');
        $group_data = mysqli_fetch_assoc($db_query->result);unset($db_query);
        move2trash('adu_group_id',$group_id,'admin_users_groups',$group_data);
        $array_return = array('success'=>1,'data'=>$group_data);
        die(json_encode($array_return));
        break;
    case 'deleteUser':
        //hàm xóa 1 tài khoản đăng nhập - trả về json
        $user_id = getValue('user','int','POST',0);
        $array_return = array();
        //nếu user_id = 1 - tài khoản mặc định của hệ thống, không thể xóa
        if($user_id == 1){
            $array_return = array('success'=>0,'error'=>'Đây là tài khoản quản trị mặc định của hệ thống! Bạn không thể xóa tài khoản này');
            die(json_encode($array_return));
        }
        //xóa user vào thùng rác
        $db_query = new db_query('SELECT * FROM admin_users WHERE adm_id = '.$user_id .' LIMIT 1');
        $user_data = mysqli_fetch_assoc($db_query->result);unset($db_query);
        move2trash('adm_id',$user_id,'admin_users',$user_data);
        $array_return = array('success'=>1);
        die(json_encode($array_return));
        break;
    case 'terminalDeleteUser':
        //check quyền
        checkPermission('delete');
        //hàm xóa hoàn toàn bản ghi ra khỏi thùng rác
        $user_id = getValue('user','int','POST',0);
        terminal_delete($user_id,'admin_users');
        $array_return = array('success'=>1);
        die(json_encode($array_return));
        break;
}