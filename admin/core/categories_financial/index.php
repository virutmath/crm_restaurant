<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_record':
            checkPermission('add');
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên');
            $myform->add('cat_type','cat_type',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cat_picture = getValue('cat_picture','str','POST','');
                if($cat_picture){
                    $myform->add('cat_picture','cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới danh mục '.$last_id.' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'edit_record':
            checkPermission('edit');
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên');
            $myform->add('cat_type','cat_type',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');
            /* code something */
            if(!$myform->checkdata()){
                $db_insert = new db_execute($myform->generate_update_SQL($id_field, $record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Chỉnh sửa bản ghi '.$record_id.' bảng '.$bg_table);
                redirect('index.php');
            }
            break;
    }
}


//Phần hiển thị
//Khởi tạo
$left_control = '';
$left_column = '';
$left_column_title = 'DANH SÁCH PHIẾU THU CHI';



$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
//Hiển thị danh sách lý do thu chi
$list = new dataGrid('cat_id',30);
$list->add('','Mô tả');
$db_count = new db_count('SELECT count(*) as count
                          FROM '.$bg_table. '
                          WHERE cat_type = "' . $cat_type_in. '" OR cat_type = "' . $cat_type_out.'"');
$total = $db_count->total;unset($db_count);
$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            WHERE 1 AND cat_type = "' . $cat_type_in. '" OR cat_type = "' . $cat_type_out.'" '
                            .$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$left_column .= $list->showHeader($total_row);
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $left_column .= $list->start_tr($i,$row['cat_id'],'class="menu-normal record-item" onclick="active_record('.$row['cat_id'].')" data-record_id="'.$row['cat_id'].'"');
    $left_column .= '<td>'.$row['cat_name'].'</td>';
}
$left_column .= $list->showFooter();
$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('left_column',$left_column);

$custom_script = file_get_contents('script1column.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('modal_1column');