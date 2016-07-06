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
            $myform->add('age_name','age_name',0,0,'',1,'Bạn chưa nhập tên cửa hàng');
            $myform->add('age_address','age_address',0,0,'');
            $myform->add('age_phone','age_phone',0,0,'');
            $myform->add('age_note','age_note',0,0,'');

            if(!$myform->checkdata()){
                $age_image = getValue('age_image','str','POST','');
                if($age_image){
                    $myform->add('age_image','age_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($age_image);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới cửa hàng '.$last_id.' bảng agencies');
                redirect('index.php');
            }
            break;
        case 'edit_category':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('age_name','age_name',0,0,'',1,'Bạn chưa nhập tên cửa hàng');
            $myform->add('age_address','age_address',0,0,'');
            $myform->add('age_phone','age_phone',0,0,'');
            $myform->add('age_note','age_note',0,0,'');

            if(!$myform->checkdata()){
                $age_image = getValue('age_image','str','POST','');
                if($age_image){
                    $myform->add('age_image','age_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($age_image);
                }
                $db_insert = new db_execute($myform->generate_update_SQL('age_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa cửa hàng '.$record_id. ' bảng agencies');
                redirect('index.php');
            }
            break;
        case 'add_record':
            $myform = new generate_form();
            /* code something */
            $myform->addTable($bg_table);
            $myform->add('sed_name','sed_name',0,0,'',1,'Bạn chưa nhập tên quầy');
            $myform->add('sed_agency_id','sed_agency_id',1,0,'',1,'Bạn chưa chọn cửa hàng');
            $myform->add('sed_phone','sed_phone',0,0,'');
            $myform->add('sed_note','sed_note',0,0,'');

            if(!$myform->checkdata()){
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                if($last_id){
                    log_action(ACTION_LOG_ADD,'Thêm mới bản ghi '.$last_id.' bảng '.$bg_table);
                }
                redirect('index.php');
            }
            break;
        case 'edit_record':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('sed_name','sed_name',0,0,'',1,'Bạn chưa nhập tên quầy');
            $myform->add('sed_agency_id','sed_agency_id',1,0,'',1,'Bạn chưa chọn cửa hàng');
            $myform->add('sed_phone','sed_phone',0,0,'');
            $myform->add('sed_note','sed_note',0,0,'');

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
$right_control = '';
$left_column = '';
$right_column = '';
$context_menu = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$list_category = array();
$db_agencies = new db_query('SELECT * FROM agencies');
$list_category = $db_agencies->resultArray();unset($db_agencies);

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
            $db_count = new db_count('SELECT count(*) as count FROM '.$bg_table.' WHERE '.$cat_field .' = '. $cat['age_id']);
            $cat_count = $db_count->total;unset($db_count);
            ?>
        <li data-cat="<?=$cat['age_id']?>" class="list-vertical-item">
            <label class="cat_name"><?=$cat['age_name']?> (<?=$cat_count?>)</label>
        </li>
        <?
        }?>
        <li data-cat="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?=$trash_count?>)</b></label>
        </li>
    </ul>
    <div id="detail-agency">
        <? foreach($list_category as $cat):?>
        <div id="agency-detail-<?=$cat['age_id']?>" class="agency-item-dt hidden">
            <label>Chi tiết cửa hàng</label>
            <div>Địa chỉ</div>
            <span class="agency-address" title="<?=$cat['age_address']?>"><?=$cat['age_address']?></span>
            <div>Điện thoại</div>
            <span class="agency-phone" title="<?=$cat['age_phone']?>"><?=$cat['age_phone']?></span>
            <? if($cat['age_image']){
                ?>
                <img src="<?=get_picture_path($cat['age_image'])?>" />
            <?
            }?>
        </div>
        <? endforeach;?>
    </div>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối bên phải, hiển thị list cửa tương ứng
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
#Bắt đầu với datagrid
$list = new dataGrid($id_field,30);
$list->add('','Tên quầy');
$list->add('','Điện thoại');

$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$right_column .= $list->showHeader($total_row);
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $right_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    $right_column .= '<td>'.$row['sed_name'].'</td>';
    $right_column .= '<td class="right">'.$row['sed_phone'].'</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();


$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
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