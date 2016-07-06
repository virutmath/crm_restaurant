<?
require_once 'inc_security.php';
//Phần này được tùy biến riêng
//Giao diện quản lý quỹ tiền sẽ gồm 2 danh sách, 2 phần left column và right column có kích thước bằng nhau
//file template fullwidth_half.html

//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
global $admin_id;
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_money_ticket_in' :
            checkPermission('add');
            //Thời gian tạo phiếu lấy từ thời gian hệ thống
            $fin_date = time();
            $fin_admin_id = $admin_id;
            $fin_agency_id = $configuration['con_default_agency'];
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_date', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_updated_time', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do thu');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $myform->add('fin_agency_id', 'fin_agency_id', 1, 1, 0, 1, 'Lỗi chi nhánh');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute_return();
                $last_id = $db->db_execute($myform->generate_insert_SQL());
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới phiếu thu ' . $last_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;

        case 'edit_money_ticket_in' :
            checkPermission('edit');
            //Không thay đổi thời gian tạo, cập nhật thêm thời gian updated
            $fin_updated = time();
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_updated_time', 'fin_updated_time', 1, 1, $fin_updated, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do thu');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute($myform->generate_update_SQL('fin_id', $record_id));
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Chỉnh sửa phiếu thu ' . $record_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;
        case 'add_money_ticket_out' :
            checkPermission('add');
            //Thời gian tạo phiếu lấy từ thời gian hệ thống
            $fin_date = time();
            $fin_admin_id = $admin_id;
            $fin_agency_id = $configuration['con_default_agency'];
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_date', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_updated_time', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do chi');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nhận tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nhận tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $myform->add('fin_agency_id', 'fin_agency_id', 1, 1, 0, 1, 'Chi nhánh không tồn tại');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute_return();
                $last_id = $db->db_execute($myform->generate_insert_SQL());
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới phiếu chi ' . $last_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;

        case 'edit_money_ticket_out' :
            checkPermission('edit');
            //Không thay đổi thời gian tạo, cập nhật thêm thời gian updated
            $fin_updated = time();
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_updated_time', 'fin_updated_time', 1, 1, $fin_updated, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do chi');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute($myform->generate_update_SQL('fin_id', $record_id));
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Chỉnh sửa phiếu chi ' . $record_id . ' bảng ' . $bg_table);
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
$right_column = '';
$left_column_title = 'Danh sách phiếu thu';
$right_column_title = 'Danh sách phiếu chi';
$context_menu = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);

//Thêm nút cài đặt và thùng rác bên phải của danh sách
$left_control .= '<div class="control-table-listing top_right_control pull-right">
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash" onclick="list_trash(\'in\')"><i class="fa fa-recycle"></i> Thùng rác</span>
</div>';
//Hiển thị danh sách phiếu thu bên trái
#Bắt đầu với datagird
$list = new dataGrid($id_field,3000);
$list->add('', 'Ngày thu');
$list->add('','Số phiếu');
$list->add('','Người nhận');
$list->add('','Diễn giải');
$list->add('','Số tiền');

//hiển thị cả phiếu thu hệ thống
$check_system_in = getValue('check_system_in','int','POST','');
$check_system_out = getValue('check_system_out','int','POST','');
if($check_system_in) {
    $sql_phieuthu = ' AND cat_type IN("money_in","money_system_in")';
}else{
    $sql_phieuthu = ' AND cat_type IN("money_in")';
}
if($check_system_out) {
    $sql_phieuchi = ' AND cat_type IN("money_out","money_system_out")';
}else{
    $sql_phieuchi = ' AND cat_type IN("money_out")';
}

//hiển thị các phiếu thu chi theo thời gian
$start_date_in = getValue('start_date_in','str','POST','');
$start_date_in = $start_date_in ? convertDateTime($start_date_in,'0:0:0') : $default_start_date_in;

$end_date_in = getValue('end_date_in','str','POST','');
$end_date_in = $end_date_in ? convertDateTime($end_date_in,'23:59:59') : $default_end_date_in;

$start_date_out = getValue('start_date_out','str','POST','');
$start_date_out = $start_date_out ? convertDateTime($start_date_out,'0:0:0') : $default_start_date_out;

$end_date_out = getValue('end_date_out','str','POST','');
$end_date_out = $end_date_out ? convertDateTime($end_date_out,'23:59:59') : $default_end_date_out;


$sql_date_in = '';
$sql_date_in .= ' AND fin_date >= ' . $start_date_in . ' AND fin_date <= ' . $end_date_in;
$sql_date_out = '';
$sql_date_out .= ' AND fin_date >= ' . $start_date_out . ' AND fin_date <= ' . $end_date_out;


$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table.'
                            LEFT JOIN categories_multi ON cat_id = fin_cat_id
                            WHERE 1 '.$list->sqlSearch(). $sql_phieuthu . $sql_date_in .'
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            LEFT JOIN categories_multi ON cat_id = fin_cat_id
                            WHERE 1 '.$list->sqlSearch(). $sql_phieuthu . $sql_date_in . '
                            ORDER BY '.$list->sqlSort().' '.$id_field.' DESC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
//Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
$left_column .= $list->showHeader($total_row, '', 'id="table-listing-left"');
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $left_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    //Ngày tạo
    $left_column .= '<td class="center" width="">' . date('d/m/Y H:i', $row['fin_date']) . '</td>';
    //Số phiếu - ID phiếu
    $left_column .= '<td class="center" width="">' . format_codenumber($row[$id_field], 6) . '</td>';
    //Người trả
    $left_column .= '<td width="120">'.$row['fin_username'].'</td>';
    //Mô tả
    $left_column .= '<td>'.$row['cat_name'].'</td>';
    //số tiền
    $left_column .= '<td class="text-right" width="80">' . format_number($row['fin_money']) . '</td>';
    $left_column .= $list->end_tr();
}
$left_column .= $list->showFooter();


//Khối bên phải, hiển thị danh sách phiếu chi
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
//Thêm nút cài đặt và thùng rác bên phải của danh sách
$right_control .= '<div class="control-table-listing top_right_control pull-right">
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash" onclick="list_trash(\'out\')"><i class="fa fa-recycle"></i> Thùng rác</span>
</div>';
#Bắt đầu với datagrid
$list = new dataGrid($id_field,3000);
$list->add('','Ngày chi');
$list->add('','Số phiếu');
$list->add('','Người nhận');
$list->add('','Diễn giải');
$list->add('','Số tiền');


$db_count = new db_count('SELECT count(*) as count
                          FROM '.$bg_table.'
                          LEFT JOIN categories_multi ON cat_id = fin_cat_id
                          WHERE 1 '.$list->sqlSearch(). $sql_phieuchi . $sql_date_out.'
                          ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            LEFT JOIN categories_multi ON cat_id = fin_cat_id
                            WHERE 1 '.$list->sqlSearch(). $sql_phieuchi . $sql_date_out.'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' DESC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
//Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-right
$right_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $right_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    //Ngày tạo
    $right_column .= '<td class="center" width="">' . date('d/m/Y H:i', $row['fin_date']) . '</td>';
    //Số phiếu - ID phiếu
    $right_column .= '<td class="center" width="">' . format_codenumber($row[$id_field], 6) . '</td>';
    //Người nhận
    $right_column .= '<td width="120">'.$row['fin_username'].'</td>';
    //Mô tả
    $right_column .= '<td>'.$row['cat_name'].'</td>';
    //số tiền
    $right_column .= '<td class="text-right" width="80">' . format_number($row['fin_money']) . '</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();



//footer control
//Hiển thị phiếu thu - chi hệ thống
$footer_control .=
'<div class="filter-system-ticket col-xs-12">
    <div class="col-xs-6">
        <label>
            <input type="checkbox" id="check_system_in" name="check_system_in" value="1"/>
            Hiển thị phiếu thu hệ thống
        </label>
    </div>
    <div class="col-xs-6">
        <label>
            <input type="checkbox" id="check_system_out" name="check_system_out" value="1"/>
            Hiển thị phiếu chi hệ thống
        </label>
    </div>
</div>';
//Phần bộ lọc của phiếu thu
$footer_control .=
    '<form class="form-inline col-xs-5" action="index.php" method="post">
        <input type="hidden" value="filterMoneyIn" name="action" />
        <div class="form-group text-center col-xs-6">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" name="start_date_in" value="' . getValue('start_date_in', 'str', 'POST', date('d/m/Y', $default_start_date_in)) . '"/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" name="end_date_in" value="' . getValue('end_date_in', 'str', 'POST', date('d/m/Y', $default_end_date_in)) . '"/>
        </div>
        <div class="form-group col-xs-6 text-center">
            <button class="btn btn-success footer-submit"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
            <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất Excel</button>
        </div>
    </form>';


//Phần hiển thị tổng số tiền thu - chi
//tính tổng số tiền thu - chi
$db_total_money = new db_query('SELECT SUM(fin_money) as total_money_in
                                 FROM financial
                                 LEFT JOIN categories_multi ON fin_cat_id = cat_id
                                 WHERE cat_type IN("money_in","money_system_in")');
$total_money_in = mysqli_fetch_assoc($db_total_money->result);
$total_money_in = $total_money_in['total_money_in'];
$db_total_money =  new db_query('SELECT SUM(fin_money) as total_money_out
                                 FROM financial
                                 LEFT JOIN categories_multi ON fin_cat_id = cat_id
                                 WHERE cat_type IN("money_out","money_system_out")');
$total_money_out = mysqli_fetch_assoc($db_total_money->result);
$total_money_out = $total_money_out['total_money_out'];
$footer_control .=
    '<div class="col-xs-2 text-center" id="sum-fntext"><b id="sum-financial">'.format_number($total_money_in - $total_money_out). ' ' . DEFAULT_MONEY_UNIT .'</b></div>';


//Phần hiển thị bộ lọc của phiếu chi
$footer_control .=
    '<form class="form-inline col-xs-5" action="index.php" method="post">
        <input type="hidden" name="action" value="filterMoneyOut"/>
        <div class="form-group text-center col-xs-6">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" name="start_date_out" value="' . getValue('start_date_out', 'str', 'POST', date('d/m/Y', $default_start_date_out)) . '"/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" name="end_date_out" value="' . getValue('end_date_out', 'str', 'POST', date('d/m/Y', $default_end_date_out)) . '"/>
        </div>
        <div class="form-group col-xs-6 text-center">
            <button class="btn btn-success footer-submit"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
            <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất Excel</button>
        </div>
    </form>';

if($isAjaxRequest) {
    $action = getValue('action', 'str','POST','');
    if($action == 'filterMoneyIn') {
        //lọc phiếu thu
        echo $left_column;
        die();
    }
    if($action == 'filterMoneyOut') {
        echo $right_column;
        die();
    }
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
$custom_script = file_get_contents('script_half.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_half');