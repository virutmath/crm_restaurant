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
            $myform->add('cus_cat_name','cus_cat_name',0,0,'',1,'Bạn chưa nhập nhóm nhân viên');
            $myform->add('cus_cat_sale','cus_cat_sale',1,0,'');
            $myform->add('cus_cat_note','cus_cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cus_cat_picture = getValue('cus_cat_picture','str','POST','');
                if($cus_cat_picture){
                    $myform->add('cus_cat_picture','cus_cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cus_cat_picture);
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
            $myform->add('cus_cat_name','cus_cat_name',0,0,'',1,'Bạn chưa nhập nhóm nhân viên');
            $myform->add('cus_cat_sale','cus_cat_sale',1,0,'');
            $myform->add('cus_cat_note','cus_cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cus_cat_picture = getValue('cus_cat_picture','str','POST','');
                if($cus_cat_picture){
                    $myform->add('cus_cat_picture','cus_cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cus_cat_picture);
                }
                $db_insert = new db_execute($myform->generate_update_SQL('cus_cat_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa danh mục '.$record_id. ' bảng cus_cat_customer');
                redirect('index.php');
            }
            break;
        case 'add_record':
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('cus_name','cus_name',0,0,'',1,'Bạn chưa nhập tên khách hàng');
            $myform->add('cus_address','cus_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ');
            $myform->add('cus_phone', 'cus_phone', 0, 0, '', 0, '');
            $myform->add('cus_email','cus_email',0,0,'',1,'');
            $myform->add('cus_cat_id','cus_cat_id',1,0,'',0,'');
            $myform->add('cus_code','cus_code',0,0,'',0,'');
            $myform->add('cus_note','cus_note',0,0,'',0,'');
            $bg_errorMsg .= $myform->checkdata();
            if(!$myform->checkdata()){
                $cus_picture = getValue('cus_picture','str','POST','');
                if($cus_picture){
                    module_upload_picture($cus_picture);
                    $myform->add('cus_picture','cus_picture',0,0,'');
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
            $myform->add('cus_name','cus_name',0,0,'',1,'Bạn chưa nhập tên khách hàng');
            $myform->add('cus_address', 'cus_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ');
            $myform->add('cus_phone','cus_phone',0,0,'',0,'');
            $myform->add('cus_email','cus_email',0,0,'',1,'');
            $myform->add('cus_cat_id','cus_cat_id',1,0,'',0,'');
            $myform->add('cus_code','cus_code',0,0,'',0,'');
            $myform->add('cus_note','cus_note',0,0,'',0,'');
            $bg_errorMsg .= $myform->checkdata();
            if(!$myform->checkdata()){
                $cus_picture = getValue('cus_picture','str','POST','');
                if($cus_picture){
                    $myform->add('cus_picture','cus_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cus_picture);
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
$left_column_title = 'Nhóm khách hàng';
$right_column_title = 'Danh sách khách hàng';
$left_column = '';
$right_column = '';
$context_menu = '';

$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$db_cus_cat = "SELECT *FROM customer_cat ORDER BY cus_cat_id";
$rs_cus_cat = new db_query($db_cus_cat);
$list_category = $rs_cus_cat->resultArray();

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
            $db_count = new db_count('SELECT count(*) as count FROM '.$bg_table.' WHERE '.$cat_field .' = '. $cat['cus_cat_id']);
            $cat_count = $db_count->total;unset($db_count);
            ?>
            <li data-cat="<?=$cat['cus_cat_id']?>" class="list-vertical-item">
                <label class="cat_name"><?=$cat['cus_cat_name']?> (<?=$cat_count?>)</label>
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

//Khối bên phải, hiển thị list customer tương ứng
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
#Bắt đầu với datagrid
$list = new dataGrid($id_field,30);
$list->add('cus_id','Mã hệ thống');
$list->add('cus_code','Mã có sẵn');
$list->add('cus_name','Tên khách hàng','string',1,1);
$list->add('cus_address','Địa chỉ');
$list->add('cus_phone','Điện thoại');

$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);
// request ajax load categories customer name
$cat_id = getValue('cat_id','int','POST',0);
if($cat_id && $isAjaxRequest){
    $db_extra = 'AND ' . $bg_table . '.' . $cat_field . ' = ' . $cat_id . ' ';
}else{
    $db_extra = '';
}

$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            LEFT JOIN customer_cat ON customers.cus_cat_id = customer_cat.cus_cat_id
                            WHERE 1 '.$list->sqlSearch(). $db_extra . '
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$right_column .= $list->showHeader($total_row);
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    if(!$row['cus_code']){
        $customer_code = format_codenumber($row['cus_id'],6,PREFIX_CUSTOMER_CODE);
    }else {
        $customer_code = $row['cus_code'];
    }
    $right_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" data-customer_code="'.$customer_code.'" data-customer_name="'.$row['cus_name'].'" data-customer_discount="'.$row['cus_cat_discount'].'" ondblclick="addToParentWindow('.$row[$id_field].')" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    $right_column .= '<td class="center">'.format_codenumber($row['cus_id'],6,PREFIX_CUSTOMER_CODE).'</td>';
    $right_column .= '<td class="center">'.$row['cus_code'].'</td>';
    $right_column .= '<td class="center">'.$row['cus_name'].'</td>';
    $right_column .= '<td class="center">'.$row['cus_address'].'</td>';
    $right_column .= '<td class="center">'.$row['cus_phone'].'</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();
//Phần option lựa chọn customer
$right_column .=
'<div class="clearfix"></div>
<div class="col-xs-12">
    <span class="col-xs-4"></span>
    <span class="col-xs-8 text-right"><i>Bấm đúp chuột vào một nhân viên để chọn</i>&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-bell-o"></i> Chọn</span>
</div>';

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
add_more_css('custom_modal.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);

$custom_script = file_get_contents('script_modal.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('modal_2column');