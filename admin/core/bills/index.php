<?
require_once 'inc_security.php';
// khai báo biến
$left_control = '';
$right_control = '';
$footer_control = '';
$left_column = '';
$right_column = '';
$context_menu = '';
$cus_name = '';
$left_column_title='danh sách hóa đơn bán hàng';
$right_column_title='danh sách hóa đơn nhập hàng';
$setting_column = '';
$add_btn = 0;
$edit_btn = 0;
$trash_btn = getPermissionValue('trash');
$list_quantity = array();

// phần hiển thị khi mới truy cập vào trang hóa đơn
$left_control .= '<span class="info control-btn deactivate control-detail"><i class="fa fa-list-alt"></i> Chi tiết</span>';
$left_control .= list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
// lấy ra tổng số hóa đơn bán trong bảng thùng rác (trash)
$sql            = new db_query("SELECT * FROM trash WHERE tra_table = 'bill_in'");
$count          = mysqli_num_rows($sql->result);unset($sql);

$left_control .= '<div class="control-table-listing top_right_control">
    <span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In HĐ</span>
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash"><i class="fa fa-recycle"></i> Thùng rác ('.$count.')</span>
</div>';

$list = new dataGrid($id_field_i,3000);
$list->add($id_field_i, 'Số HĐ','string',1);
$list->add('bii_desk_id', 'Bàn');
$list->add('bii_customer_id', 'Khách hàng');
$list->add('bii_end_time', 'Ngày bán');
$list->add('bio_total_money', 'Tổng tiền');
// khai bao
$and_bii_cus_id = '';
$and_bio_sup_id = '';
$type_bill_fillter = ' AND bii_start_time ';
// tồn tại $isRequestAjax
if($isAjaxRequest && isset($_POST['active'])){
    $id_customer    = getValue('id_customer','int','POST',0);
    $time_start     = convertDateTime(getValue('time_start','str','POST',''),'0:0:0'); 
    $time_end       = convertDateTime(getValue('time_end','str','POST',''),'23:59:59');
    $active         = getValue('active','int','POST',0);
    
    if ( $active ) $type_bill_fillter = ' AND bii_end_time '; // loc theo thoi gian thanh toan hoa don
    if ( $id_customer ) $and_bii_cus_id .= ' AND bii_customer_id = ' . intval($id_customer); // loc theo id khach
    if ( $time_start ) $and_bii_cus_id .= $type_bill_fillter .' >= ' . $time_start;
    if ( $time_end ) $and_bii_cus_id .= $type_bill_fillter . ' <= ' . $time_end;      
}
else
{
    $and_bii_cus_id = $type_bill_fillter . ' >= ' . (convertDateTime($today,'0:0:0') - 2592000) . $type_bill_fillter . ' <= ' . convertDateTime($today,'23:59:59');
}// end ton tai $isAjaxRequest
$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table_i.' 
                            WHERE 1 '.$list->sqlSearch().$and_bii_cus_id .'
                            ');
$total = $db_count->total;unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM '.$bg_table_i.'
                            WHERE 1 '.$list->sqlSearch().$and_bii_cus_id.'
                            ORDER BY '.$list->sqlSort().' ' . $id_field_i . ' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$left_column .= '<div id="table_bill_in">';
$table_left_column = '';
$table_left_column .= $list->showHeader($total_row,'', 'id="table-listing-left"');
$i = 0;
$totalAll  = 0;
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $backgroud  = '';
    if($row['bii_status'] == 0 && $row['bii_money_debit'] != 0){
        $backgroud = 'style="background:#B3F8FF;"';
    }
    $table_left_column .= $list->start_tr($i,$row[$id_field_i],' ' . $backgroud . ' class="menu-normal record-item" onclick="active_record('.$row[$id_field_i].',\'left\')" data-debit="'.$row['bii_money_debit'].'" data-record_id="'.$row[$id_field_i].'" ondblclick="showDetail(\'left\')"');
    /* code something */
    $table_left_column .= '<td class="center" width="80">' . format_codenumber($row[$id_field_i],6,PREFIX_BILL_CODE) . '</td>';
    // l?y v? tri ban va s? ban
    $supplier      = new db_query('SELECT des_name, sec_name 
                                   FROM desks INNER JOIN sections
                                   ON des_sec_id = sec_id 
                                   WHERE des_id = ' . $row['bii_desk_id']);
    $row_          = mysqli_fetch_assoc($supplier->result);unset($supplier);
    $table_left_column .= '<td class="center" width="110">' . $row_['sec_name'] . ' - ' . $row_['des_name'] . '</td>';
    // l?y ten khach hang
    $supplier     = new db_query('SELECT cus_id, cus_name
                                   FROM customers
                                   WHERE cus_id = ' . $row['bii_customer_id']);
    $row_         = mysqli_fetch_assoc($supplier->result);unset($supplier);
    if($row_['cus_id'] == 0){
        $cus_name = 'Khách lẻ';
    }else{
        $cus_name = $row_['cus_name'];
    }
    $table_left_column .= '<td>' . $cus_name . '</td>';
    $table_left_column .= '<td class="center" width="120">' . date('d-m-Y H:i:s',$row['bii_end_time']) . '</td>';
    // tính lại tổng tiền của 1 hóa đơn theo số lượng món * giá
    $data_bill_detail = new db_query('SELECT bid_menu_number, bid_menu_price
                                        FROM bill_in_detail
                                        WHERE bid_bill_id = ' . $row[$id_field_i] . '');
    $total_bii_true_money = 0;
    while ( $data_bill_in_detail = mysqli_fetch_assoc($data_bill_detail->result) )
    {
        $bii_true_money = $data_bill_in_detail['bid_menu_number'] * $data_bill_in_detail['bid_menu_price'];
        $total_bii_true_money += $bii_true_money;
    }unset($data_bill_detail);
    //
    $table_left_column .= '<td class="text-right" width="100">' . number_format($total_bii_true_money) .' '. DEFAULT_MONEY_UNIT . '</td>';
    $table_left_column .= $list->end_tr();
    $totalAll += $row['bii_true_money'];
}
$table_left_column        .= $list->showFooter();
// lấy ra tổng số hóa đơn và tổng tiền
$table_left_column     .= '
    <div class="total-pos">
        <div class="col-xs-6-lft">
            <span>Tổng HĐ:</span><input class="ttl-hd" value="'.$i.'" readonly="readonly">
        </div>
        <div class="col-xs-6-rgh">
            <span>Tổng tiền:</span><input class="ttl-hd" value="'.number_format($totalAll) . ' ' . DEFAULT_MONEY_UNIT . '" readonly="readonly">
        </div>
    </div>';
$left_column .= $table_left_column;
$left_column .= '</div>';

//phần hóa đơn nhập  onclick="showDetail({\'obj\':\'.content-bill-detail\',\'position\':\'right\'})"
$right_control .= '<span class="info control-btn deactivate control-detail"><i class="fa fa-list-alt"></i> Chi tiết</span>';
// ra các nút điều khiển thêm sửa xóa cài đặt làm mới....
$right_control .= list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
// lấy ra tổng số hóa đơn nhập hàng trong bảng thùng rác (trash)
$sql            = new db_query("SELECT * FROM trash WHERE tra_table = 'bill_out'");
$count          = mysqli_num_rows($sql->result);unset($sql);

$right_control .= '<div class="control-table-listing top_right_control">
    <span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In HĐ</span>
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash"><i class="fa fa-recycle"></i> Thùng rác ('.$count.')</span>
</div>';

$list_right = new dataGrid($id_field_o,3000);
$list_right->add($id_field_o, 'Số HĐ', 'string',1);
$list_right->add('bio_supplier_id', 'Nhà cung cấp');
$list_right->add('bio_start_time', 'Ngày nhập');
$list_right->add('bio_total_money', 'Tổng tiền');
// ton tai request ajax loc hoa don nhap
$type_bill_fillter = ' AND bio_start_time ';
if($isAjaxRequest && isset($_POST['id_brand'])){
    $id_brand   = getValue('id_brand','int','POST',0);
    $time_start = convertDateTime(getValue('time_start','str','POST',''),'0:0:0');
    $time_end   = convertDateTime(getValue('time_end','str','POST',''),'23:59:59'); 
    if ( $id_brand ) $and_bio_sup_id .= ' AND bio_supplier_id = ' . intval( $id_brand ); // loc theo id nha cung cap
    if ( $time_start ) $and_bio_sup_id .= $type_bill_fillter . ' >= ' . $time_start;
    if ( $time_end ) $and_bio_sup_id .= $type_bill_fillter . ' <= ' . $time_end;
}else{
    $and_bio_sup_id = $type_bill_fillter . ' >= ' . ( convertDateTime($today,'0:0:0') - 2592000 ) . $type_bill_fillter . ' <= ' . convertDateTime($today,'23:59:59');
}
$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table_o.'
                            WHERE 1 '.$list_right->sqlSearch(). $and_bio_sup_id.'
                            ');
$total_ = $db_count->total;unset($db_count);
$db_listing = new db_query('SELECT *
                            FROM '.$bg_table_o.'
                            WHERE 1 '.$list_right->sqlSearch().$and_bio_sup_id.'
                            ORDER BY '.$list_right->sqlSort().' ' . $id_field_o . ' ASC
                            '.$list_right->limit($total_));
$total_row = mysqli_num_rows($db_listing->result);
$right_column .= '<div id="table_bill_out">';
$table_right_column = '';
$table_right_column .= $list_right->showHeader($total_row,'','id="table-listing-right"');
$i = 0;
$totalAll_ = 0;

while($row = mysqli_fetch_assoc($db_listing->result)){
    $backgrouds = '';
    if($row['bio_status'] == BILL_STATUS_DEBIT && $row['bio_money_debit'] != 0){
        $backgrouds = 'style="background:#B3F8FF;"';
    }
    $supplier      = new db_query('SELECT sup_name
                                   FROM suppliers
                                   WHERE sup_id = ' . $row['bio_supplier_id']);
    $row_          = mysqli_fetch_assoc($supplier->result);unset($supplier);
    $i++;
    $table_right_column .= $list_right->start_tr($i,$row[$id_field_o],''.$backgrouds.' class="menu-normal record-item" onclick="active_record('.$row[$id_field_o].',\'right\')" data-debit="'.$row['bio_money_debit'].'" data-record_id="'.$row[$id_field_o].'" ondblclick="showDetail(\'right\')"');
    $table_right_column .= '<td class="center" width="80">' . format_codenumber($row[$id_field_o],6,PREFIX_BILL_CODE) . '</td>';
    $table_right_column .= '<td>' . $row_['sup_name'] . '</td>';
    $table_right_column .= '<td class="center" width="130">' . date('d-m-Y H:i:s',$row['bio_start_time']) . '</td>';
    $table_right_column .= '<td class="text-right" width="100">' . number_format($row['bio_total_money']) .' '. DEFAULT_MONEY_UNIT . '</td>';
    $table_right_column .= $list_right->end_tr();
    $totalAll_ += $row['bio_total_money'];
}
$table_right_column        .= $list_right->showFooter();
// lấy ra tổng tiền và tổng số hóa đơn
$table_right_column     .=
'<div class="total-pos">
    <div class="col-xs-6-lft col-xs-6-lft-l">
        <span>Tổng HĐ:</span><input class="ttl-hd" value="' . $total_ . '" readonly="readonly"/>
    </div>
    <div class="col-xs-6-rgh col-xs-6-lft-r">
        <span>Tổng tiền:</span><input class="ttl-hd" value="'.number_format($totalAll_) . ' ' . DEFAULT_MONEY_UNIT . '" readonly="readonly"/>
    </div>
</div>';
$right_column .= $table_right_column;
$right_column .= '</div>';
//hiển thị phần footer , lọc hóa đơn BÁN theo thời gian, theo tên
$footer_control   .= '<div class="filter-system-ticket col-xs-12">';
$footer_control   .= '<form action="" method="">';
$footer_control   .= '<div class="col-xs-6-both">';
$footer_control   .= '<div class="col-xs-6-bth-lft">';
$footer_control   .= '<div class="col-xs-6-bth-frm-lft">';
$footer_control   .= '<div class="frm-lft-left">';
$footer_control   .= '<input class="datetime-local frm-dt-lft lft" name="" value="'.$formDate.'"/>';
$footer_control   .= '<span class="frm-ic-ct"><i class="fa fa-arrow-right"></i></span>';
$footer_control   .= '<input class="datetime-local frm-dt-rgh lft" name="" value="'.$toDate.'"/></br>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '<select class="frm-sel-lft" id="select-customer" name="">';
$footer_control   .= '<option value="0">--- Tất cả khách hàng</option>';
$sql = new db_query('SELECT * FROM customers');
while ($row = mysqli_fetch_assoc($sql->result))
$footer_control   .= '<option value="' . $row['cus_id'] . '">' . $row['cus_name'] . '</option>';unset($sql);
$footer_control   .= '</select>';
$footer_control   .= '<span class="frm-ic-seach" id="find-customer"><i class="fa fa-search"></i></span>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>';
$footer_control   .= '<div class="frm-lft-right">';
$footer_control   .= '<p class="exc-out"><i class="fa fa-file-excel-o"></i> Xuất Excel</p>';
$footer_control   .= '<p class="filters"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu</p>';
$footer_control   .= '</div>'; 
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>'; 
$footer_control   .= '<div class="col-xs-6-bth-frm-rgh">';
$footer_control   .= '<ul>'; 
$footer_control   .= '<li>Lọc theo thời gian</li>';
$footer_control   .= '<li><label for="creat-bill"><input type="radio" name="filter" value="0" checked="checked" id="creat-bill"/> Tạo Hóa Đơn (mặc định)</label></li>';
$footer_control   .= '<li><label for="pay-bill"><input type="radio" name="filter" value="1" id="pay-bill"/> Thanh toán Hóa Đơn</label></li>';
$footer_control   .= '</ul>';
$footer_control   .= '</div>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>';
//hiển thị phần footer , lọc hóa đơn NHẬP theo thời gian, theo tên
$footer_control   .= '<div class="col-xs-6-bth-rgh">';
$footer_control   .= '<div class="col-xs-6-bth-frm-rgh-lft">';
$footer_control   .= '<div class="frm-lft-left">';
$footer_control   .= '<input name="" class="datetime-local frm-dt-lft rgh" value="'.$formDate.'"/>';
$footer_control   .= '<span class="frm-ic-ct"><i class="fa fa-arrow-right"></i></span>';
$footer_control   .= '<input name="" class="datetime-local frm-dt-rgh rgh" value="'.$toDate.'"/></br>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '<select class="frm-sel-lft frm-sel-rgh" id="select-brand" name="">';
$footer_control   .= '<option value="0">--- Tất cả nhà cung cấp</option>';
$sql = new db_query('SELECT * FROM suppliers');
while ($row = mysqli_fetch_assoc($sql->result))
$footer_control   .= '<option value="' . $row['sup_id'] . '">' . $row['sup_name'] . '</option>';unset($sql);
$footer_control   .= '</select>';
$footer_control   .= '<span class="frm-ic-seach" id="find-brand"><i class="fa fa-search"></i></span>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>';
$footer_control   .= '<div class="frm-lft-right">';
$footer_control   .= '<p class="exc-out"><i class="fa fa-file-excel-o"></i> Xuất Excel</p>';
$footer_control   .= '<p class="filters"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu</p>';
$footer_control   .= '</div>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>';
$footer_control   .= '</div>';
$footer_control   .= '<div class="clear_"></div>';
$footer_control   .= '</div>';
$footer_control   .= '</form>';
$footer_control   .= '</div>';
$footer_control   .= '<div id="bill_detail" class="dpl-none">';
$footer_control   .= '<div class="content-bill-detail dpl-none"></div>';
$footer_control   .= '</div>';
$footer_control   .= '<div id="cus-form" class="dpl-none">';
$footer_control   .= '<div class="content-form-cus dpl-none"></div>';
$footer_control   .= '</div>';
// return ajax
if($isAjaxRequest){
    if(isset($id_customer)){
        echo $table_left_column;
        die;
    } 
    if(isset($id_brand)){
        echo $table_right_column;
        die;
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
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_half');