<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_category':
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type','cat_type',0,1,'');
            $myform->add('cat_desc','cat_desc',0,0,'');
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
        case 'edit_category':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type','cat_type',0,1,'');
            $myform->add('cat_desc','cat_desc',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cat_picture = getValue('cat_picture','str','POST','');
                if($cat_picture){
                    $myform->add('cat_picture','cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute($myform->generate_update_SQL('cat_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa danh mục '.$record_id. ' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'add_record':
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('use_name','use_name',0,0,'',1,'Bạn chưa nhập tên nhân viên');
            $myform->add('use_address', 'use_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ nhân viên');
            $myform->add('use_phone','use_phone',0,0,'',0,'');
            $myform->add('use_pay','use_pay',1,0,'',0,'');
            $myform->add('use_discount','use_discount',1,0,'',0,'');
            $myform->add('use_group_id','use_group_id',1,0,'',0,'');
            $myform->add('use_code','use_code',0,0,'',0,'');
            $myform->add('use_note','use_note',0,0,'',0,'');
            $bg_errorMsg .= $myform->checkdata();
            if(!$myform->checkdata()){
                $use_image = getValue('use_image','str','POST','');
                if($use_image){
                    $myform->add('use_image','use_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($use_image);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới bản ghi '.$last_id.' bảng '.$bg_table);
                redirect('index.php');
            }
            break;
        case 'edit_record':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('use_name','use_name',0,0,'',1,'Bạn chưa nhập tên nhân viên');
            $myform->add('use_address', 'use_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ nhân viên');
            $myform->add('use_phone','use_phone',0,0,'',0,'');
            $myform->add('use_pay','use_pay',1,0,'',0,'');
            $myform->add('use_discount','use_discount',1,0,'',0,'');
            $myform->add('use_group_id','use_group_id',1,0,'',0,'');
            $myform->add('use_code','use_code',0,0,'',0,'');
            $myform->add('use_note','use_note',0,0,'',0,'');
            $bg_errorMsg .= $myform->checkdata();
//            add_error_msg('Chưa nhập tên nv');
            if(!$myform->checkdata()){
                $use_image = getValue('use_image','str','POST','');
                if($use_image){
                    $myform->add('use_image','use_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($use_image);
                }
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
$right_control = '';
$footer_control = '';
$left_column = '';
$left_column_title = 'NHÓM NHÂN VIÊN';
$right_column = '';
$right_column_title = 'DANH SÁCH NHÂN VIÊN';
$context_menu = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$list_category = category_type($cat_type);

$db_count = new db_count('SELECT count(*) as count FROM '.$bg_table);
$all_count = $db_count->total;unset($db_count);
$db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "'.$bg_table.'"');
$trash_count = $db_count->total;unset($db_count);
ob_start();
?>
    <ul class="list-unstyled list-vertical-crm">
        <li data-cat="all">
            <label class="active cat_name"><b><i class="fa fa-list fa-fw"></i> Tất cả (<?=$all_count?>)</b></label>
        </li>
        <? foreach($list_category as $cat){
            //đếm số bản ghi trong mỗi cat
            $db_count = new db_count('SELECT count(*) as count FROM '.$bg_table.' WHERE '.$cat_field .' = '. $cat['cat_id']);
            $cat_count = $db_count->total;unset($db_count);
            ?>
        <li data-cat="<?=$cat['cat_id']?>" class="list-vertical-item">
            <label class="cat_name"><?=$cat['cat_name']?> (<?=$cat_count?>)</label>
        </li>
        <?
        }?>
        <li data-cat="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?=$trash_count?>)</b></label>
        </li>
    </ul>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối bên phải, hiển thị list user tương ứng
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
#Bắt đầu với datagrid
$list = new dataGrid($id_field,30);
$list->add('use_id','Mã hệ thống');
$list->add('use_code','Mã có sẵn');
$list->add('use_name','Tên nhân viên','string',1,1);
$list->add('use_address','Địa chỉ');
$list->add('use_phone','Điện thoại');
$list->add('use_note','Ghi chú');

$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);
$cat_id = getValue('cat_id','int','POST',0);
if($cat_id && $isAjaxRequest){
    $db_extra_left_join = ' LEFT JOIN ' . $cat_table . ' ON ' . $cat_field . ' = cat_id '; 
    $db_extra_and   = 'AND ' . $cat_field . ' = ' . $cat_id;
}else{
    $db_extra_left_join = '';
    $db_extra_and = ''; 
}


$db_listing = new db_query('SELECT *
                            FROM '.$bg_table. $db_extra_left_join .'
                            WHERE 1 '.$list->sqlSearch().$db_extra_and.'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$right_column .= $list->showHeader($total_row);
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $right_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" ondblclick="detailRecord()" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    $right_column .= '<td class="center">'.format_codenumber($row['use_id'],6,PREFIX_STAFF_CODE).'</td>';
    $right_column .= '<td class="center">'.$row['use_code'].'</td>';
    $right_column .= '<td class="center">'.$row['use_name'].'</td>';
    $right_column .= '<td class="center">'.$row['use_address'].'</td>';
    $right_column .= '<td class="center">'.$row['use_phone'].'</td>';
    $right_column .= '<td class="center">'.$row['use_note'].'</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();

if($isAjaxRequest){
    $action = getValue('action','str','POST','');
    switch($action){
        case 'listRecord':
        echo $right_column;
        break;
    }
    die;
}

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);
$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('right_column_title',$right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column');