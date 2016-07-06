<?
require_once 'inc_security.php';
//Phần này được tùy biến riêng
//Giao diện quản lý quỹ tiền sẽ gồm 2 danh sách, 2 phần left column và right column có kích thước bằng nhau
//file template fullwidth_half.html

//Phần xử lý
$action_modal       = getValue('action_modal','str','POST','',2);
$action             = getValue('action','str','POST','',2);


//Phần hiển thị
//Khởi tạo
$left_control       = '';
$right_control      = '';
$footer_control     = '';
$left_column        = '';
$right_column       = '';
$left_column_title  = 'Danh sách phiếu kiểm kê kho hàng';
$right_column_title = 'Danh sách phiếu chuyển kho hàng';
$context_menu       = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//phần hiển thị chức năng left_control bên trái
$db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "inventory"');
$trash_count = $db_count->total;unset($db_count);
if($add_btn || $edit_btn || $trash_btn) {
    $left_control .=
        '<div class="modal-control">
            <span class="control-btn control-btn-add" onclick="addInventory()"><i class="fa fa-file-o"></i> Thêm</span>
            <span class="control-btn control-btn-trash deactivate"><i class="fa fa-trash"></i> Xóa</span>
            <span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In HĐ</span>
            <span class="control-btn control-btn-refresh"><i class="fa fa-refresh"></i> Làm mới</span>
            <span class="control-btn control-btn-recover" onclick="list_trash_inventory()"><i class="fa fa-recycle"></i> Thùng rác ('.$trash_count.')</span>
        </div>';
}

//Hiển thị danh sách phiếu thu bên trái
#Bắt đầu với datagird
$list = new dataGrid('inv_id',30);
$list->add('','Số phiếu');
$list->add('','Nhân viên kiểm kê');
$list->add('','Ngày kiểm kê');
$list->add('','Kho kiểm kê');

// lấy biên từ form tìm kiếm theo ngày và nhân viên
$start_date_in = getValue('start_date_in','str','POST',0);
$date_from = convertDateTime('d/m/Y',$start_date_in);

$end_date_in = getValue('end_date_in','str','POST',0);
$date_to = convertDateTime('d/m/Y',$end_date_in);

$staff_name = getValue('list_staff_id','int','POST',0);

// slect list danh sách phiếu kiểm kê
$db_count = new db_count('SELECT count(*) as count
                            FROM inventory
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM inventory
                            WHERE 1 '.$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' inv_id DESC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
// tạo mảng để hiện thị tên nhân viên
$staff_array = array();
$db_staff = new db_query('SELECT * FROM users');
while($row_user = mysqli_fetch_assoc($db_staff->result)){
    $staff_array[$row_user['use_id']] = $row_user['use_name'];
}
// tạo mảng để hiện thị kho hàng
$store_array = array();
$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores" ');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $store_array[$row_store['cat_id']] = $row_store['cat_name'];
}
//Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
$left_column .= $list->showHeader($total_row, '', 'id="table-listing-left"');
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $left_column .= $list->start_tr($i,$row['inv_id'],'class="menu-normal record-item" ondblclick="detail_inventory('.$row['inv_id'].')" onclick="active_record('.$row['inv_id'].')" data-record_id="'.$row['inv_id'].'"');
    /* code something */
    //Số phiếu - ID phiếu
    $left_column .= '<td class="center" width="">' . format_codenumber($row['inv_id'], 6) . '</td>';
    //Người trả
    $left_column .= '<td width="120" class="center">'.$staff_array[$row['inv_staff_id']].'</td>';

    //Mô tả
    $left_column .= '<td class="center">'. date('d/m/Y H:i', $row['inv_time']).'</td>';
    //số tiền
    $left_column .= '<td width="120" class="text-left">'.$store_array[$row['inv_store_id']].'</td>';
    $left_column .= $list->end_tr();
}
$left_column .= $list->showFooter();







//Thêm nút cài đặt và thùng rác bên phải của danh sách
$db_count_stock = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "stock_transfer"');
$trash_count_stock = $db_count_stock->total;unset($db_count_stock);

$right_control .= '<div class="modal-control">
            <span class="control-btn control-btn-add" onclick="addStockTransfer()"><i class="fa fa-file-o"></i> Thêm</span>
            <span class="control-btn control-btn-trash deactivate"><i class="fa fa-trash"></i> Xóa</span>
            <span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In HĐ</span>
            <span class="control-btn control-btn-refresh"><i class="fa fa-refresh"></i> Làm mới</span>
            <span class="control-btn control-btn-recover" onclick="list_trash_stock_trans()"><i class="fa fa-recycle"></i> Thùng rác ('.$trash_count_stock.')</span>
        </div>';
//Hiển thị danh sách phiếu thu bên trái
#Bắt đầu với datagird
$list = new dataGrid('sto_id',30);
$list->add('','Số phiếu');
$list->add('','Nhân viên chuyển');
$list->add('','Ngày chuyển');
$list->add('','Từ kho');
$list->add('','Đến kho');

// lấy biên từ form tìm kiếm theo ngày và nhân viên
$start_date_in  = getValue('start_date_in','str','POST',0);
$date_from      = convertDateTime('d/m/Y',$start_date_in);

$end_date_in    = getValue('end_date_in','str','POST',0);
$date_to        = convertDateTime('d/m/Y',$end_date_in);

$staff_name     = getValue('list_staff_id','int','POST',0);

// slect list danh sách phiếu kiểm kê
$db_count = new db_count('SELECT count(*) as count
                            FROM stock_transfer
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM stock_transfer
                            WHERE 1 '.$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' sto_id DESC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
// tạo mảng để hiện thị tên nhân viên
$staff_array = array();
$db_staff = new db_query('SELECT * FROM users');
while($row_user = mysqli_fetch_assoc($db_staff->result)){
    $staff_array[$row_user['use_id']] = $row_user['use_name'];
}
// tạo mảng để hiện thị kho hàng
$store_array = array();
$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores" ');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $store_array[$row_store['cat_id']] = $row_store['cat_name'];
}
//Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
$right_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
$i = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $right_column .= $list->start_tr($i,$row['sto_id'],'class="menu-normal record-item" ondblclick="detail_stock_transfer('.$row['sto_id'].')" onclick="active_record_r('.$row['sto_id'].')" data-record_id="'.$row['sto_id'].'"');
    /* code something */
    //Số phiếu - ID phiếu
    $right_column .= '<td class="center" width="">' . format_codenumber($row['sto_id'], 6) . '</td>';
    //Người trả
    $right_column .= '<td width="120" class="center">'.$staff_array[$row['sto_staff_id']].'</td>';

    //Mô tả
    $right_column .= '<td class="center">'. date('d/m/Y H:i', $row['sto_time']).'</td>';
    //số tiền
    $right_column .= '<td width="120" class="text-left">'.$store_array[$row['sto_from_storeid']].'</td>';
    $right_column .= '<td width="120" class="text-left">'.$store_array[$row['sto_to_storeid']].'</td>';
    $right_column .= $list->end_tr();
}
$right_column .= $list->showFooter();

//footer control
//Phần bộ lọc của phiếu thu
// lay ra list danh sách nhân viên kiểm kê
$list_staff ='';
$db_query_customer = new db_query('SELECT * FROM users');
$list_staff .= '<option value="0"> --- Tất cả -- </option>';
while($row = mysqli_fetch_assoc($db_query_customer->result)){
    $list_staff .= '<option value="' . $row['use_id'] . '">' . $row['use_name'] . '</option>';
}

$footer_control .=
    '<form class="form-inline col-xs-6 box-search" method="post" onsubmit="fill_data_Inventory()">
        <input type="hidden" value="fillerInventory" name="action" />
        <div class="form-group text-center col-xs-5">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" value="'.date('d/m/Y',time() - 86400*30).'" name="start_date_in" id="start_date_inventory"/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" value="'.date('d/m/Y').'" name="end_date_in" id="end_date_inventory"/>
        </div>
        <div class="form-group text-center col-xs-4">
            <label class="">Nhân viên:</label>
            <select class="form-control" name="staff_name" id="list_staff">
                '.$list_staff.'
            </select>
        </div>
        <div class="form-group col-xs-3 text-center">
            <button class="btn btn-success footer-submit" type="button" onclick="fill_data_Inventory()"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
        </div>
    </form>';



//Phần hiển thị bộ lọc của phiếu chi
$footer_control .=
    '<form class="form-inline col-xs-6 box-search" method="post">
        <input type="hidden" name="action" value="filterStockTransfer" onsubmit="fill_data_stock_transfer()"/>
        <div class="form-group text-center col-xs-5">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" value="'.date('d/m/Y',time() - 86400*30).'" name="start_date_in" id="start_date_stock_transfer"/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" value="'.date('d/m/Y').'" name="end_date_in" id="end_date_stock_transfer" />
        </div>
        <div class="form-group text-center col-xs-4">
            <label class="">Nhân viên:</label>
            <select class="form-control" name="staff_name" id="list_staff_stock">
                '.$list_staff.'
            </select>
        </div>
        <div class="form-group col-xs-3 text-center">
            <button class="btn btn-success footer-submit" type="button" onclick="fill_data_stock_transfer()"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
        </div>
    </form>';
$footer_control .='
    <div class="clearfix"></div>
    <div class="button_tab">
        <ul>
            <li><a href="../products/index.php"><i class="fa fa-list"></i> DANH SÁCH MẶT HÀNG</a></li>
            <li><a href="#" id="button_tab_active"><i class="fa fa-check"></i> KIỂM KÊ - CHUYỂN KHO</a></li>
        </ul>
    </div>
';
if($isAjaxRequest) {
    $action = getValue('action', 'str','POST','');
    if($action == 'fillerInventory') {
        //lọc phiếu thu
        echo $left_column;
        die();
    }
    if($action == 'filterStockTransfer') {
        //lọc phiếu thu
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