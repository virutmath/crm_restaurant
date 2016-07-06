<?
require_once 'inc_security.php';

//Phần hiển thị
//Khởi tạo
$top_control    = '';
$content_column = '';
$footer_control = '';

$id_field = "log_id";
$bg_table = "logs_session";

#Bắt đầu với datagrid
$list = new dataGrid($id_field, 30);
$list->add('', 'Người dùng');
$list->add('', 'Giờ đăng nhập');
$list->add('', 'Tổng thu');
$list->add('', 'Tiền mặt');
$list->add('', 'Thẻ');
$list->add('', 'Tổng chi');
$list->add('', 'Tiền mặt');
$list->add('', 'Thẻ');

/* Lấy các thông tin bắn ajax về*/
$start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
$end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0') + 86400 -1;
$admin_id       = getValue('admin_id','int','POST',0);


$start_today = convertDateTime(time(),'0:0:0');// bắt đầu trong ngày từ 00
$end_today   = convertDateTime(time(),'0:0:0') + 86400 - 1; // kết thúc ngày

if( $isAjaxRequest ){

    if ($start_date){
        $sql_search .= ' AND log_time_in >= ' . $start_date;
    }
    if ($end_date){
        $sql_search .= ' AND log_time_in <= ' . $end_date;
    }
    if ($admin_id != 0){
        $sql_search .= ' AND log_admin_id = ' . $admin_id;
    }

}else{
    $sql_search = ' AND log_time_in >= ' .$start_today.' AND log_time_in <= '.$end_today.'';
}

$db_count = new db_count('SELECT count(*) as count
                            FROM ' . $bg_table . '
                            WHERE 1 ' . $list->sqlSearch() . $sql_search. '
                            ');
$total = $db_count->total;
unset($db_count);


$db_listing = new db_query('SELECT *
                            FROM ' . $bg_table . '
                            WHERE 1 ' . $list->sqlSearch() . $sql_search .'
                            ORDER BY ' . $list->sqlSort() . ' ' . $id_field . ' DESC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$content_column .= $list->showHeader($total_row);

/* Tạo mảng array_admin để hiển thị tên */
$array_admin = array();
$db_admin = new db_query('SELECT * FROM admin_users');
while($row_admin = mysqli_fetch_assoc($db_admin->result)){
    $array_admin[$row_admin['adm_id']] = $row_admin['adm_name'];
}unset($db_admin);

$i = 0;
$all_money_default = 0; /* Tổng tiền trước khi giảm*/
$array_data = array();/* Mảng dữ liệu cần lấy*/
$array_day   = array();/* Dữ liệu thời gian*/
$array_date  = array();
while ($row = mysqli_fetch_assoc($db_listing->result)) {

    /* Tạo mảng dữ liệu để hiển thị biểu đồ JS*/
    $start_day = gmdate('d/m/Y',$row['log_time_in']);
    $array_day[$start_day] = $row;

    /* Lấy mảng dữ liệu*/

    $db_query_admin = new db_query('SELECT * FROM logs_session');
    $row_log = mysqli_fetch_assoc($db_query_admin->result); unset($db_query_admin);

    /* Thời gian admin log sẽ chỉ tính trong ngày hôm đó*/
    $start_day  = convertDateTime(date('d/m/Y',$row['log_time_in']),'0:0:0');
    $end_day    = $start_day + 86400 - 1;

    /* Tổng thu tổng chi của admin được lấy từ bill_in bảng hóa đơn bán hàng */
    $total_money_real   = 0;
    $money_real_in      = 0; /* Tiền mặt thu*/
    $money_cash_in      = 0; /* Tiền thẻ thu*/
    $db_bill = new db_query('SELECT * FROM bill_in
                             WHERE bii_admin_id = '.$row['log_admin_id'].'
                             AND bii_start_time >= '.$start_day.'
                             AND bii_start_time <= '.$end_day.'
                               ');
    while($row_bill_money = mysqli_fetch_assoc($db_bill->result)) {
        /* Hiển thị số tiền nếu hóa đơn ghi nợ*/
        $db_fina = new db_query('SELECT fin_money,fin_pay_type FROM financial WHERE fin_billcode = '.$row_bill_money['bii_id'].'');
        $row_fin = mysqli_fetch_assoc($db_fina->result); unset($db_fina);
        if($row['bii_type'] == 0){
            $money_real = $row_fin['fin_money'];
        } else{
            $money_real = 0;
        }
        if($row['bii_type'] == 1){
            $money_cash = $row_fin['fin_money'];
        } else{
            $money_cash = 0;
        }
        /* Tính giá trị tiền khi đã giảm giá phụ phí VAT */
        $vat_value = $row_bill_money['bii_vat'] / 100;
        $total_money_ext    = $row_bill_money['bii_true_money'] - ($row_bill_money['bii_true_money'] * $row['bii_discount'] / 100) + ($row_bill_money['bii_true_money'] * $row_bill_money['bii_extra_fee'] / 100);
        $total_money_real  += $total_money_ext * (1 + $vat_value);/*Tổng tiền khi đã tính toán giảm */
        $money_real_in     += $money_real; /* Tiền mặt*/
        $money_cash_in     += $money_cash; /* Tiền thẻ*/
    }unset($db_bill);


    /* Tổng thu tổng chi của admin được lấy từ bill_out bảng hóa đơn nhập hàng */
    $total_money_out     = 0;
    $money_real_out      = 0; /* Tiền mặt chi*/
    $money_cash_out      = 0; /* Tiền thẻ chi*/
    $db_bill_out = new db_query('SELECT * FROM bill_out
                             WHERE bio_admin_id = '.$row['log_admin_id'].'
                             AND bio_start_time >= '.$start_day.'
                             AND bio_start_time <= '.$end_day.'
                               ');
    while($row_bio_money = mysqli_fetch_assoc($db_bill_out->result)) {
        /* Hiển thị số tiền nếu hóa đơn ghi nợ*/
        $db_fina = new db_query('SELECT fin_money,fin_pay_type FROM financial WHERE fin_billcode = '.$row_bio_money['bio_id'].'');
        $row_fin = mysqli_fetch_assoc($db_fina->result); unset($db_fina);
        if($row['bio_type'] == 0){
            $money_real = $row_fin['fin_money'];
        } else{
            $money_real = 0;
        }
        if($row['bio_type'] == 1){
            $money_cash = $row_fin['fin_money'];
        } else{
            $money_cash = 0;
        }
        /* Tính tổng tiền nhập hàng */
        $total_money_out     += $row_bio_money['bio_total_money'];/* Tiền nhập hàng*/
        $money_real_out      += $money_real; /* Tiền mặt*/
        $money_cash_out      += $money_cash; /* Tiền thẻ*/
    }unset($db_bill_out);

    $i++;
    $content_column .= $list->start_tr($i, $row[$id_field], 'class="menu-normal record-item" data-record_id="' . $row[$id_field] . '"');
    /* code something */
    $content_column .= '<td class="center">' . $array_admin[$row['log_admin_id']].'</td>';

    $content_column .= '<td class="center">'.date('d/m/Y h:i:s',$row['log_time_in']).'</td>';
    $content_column .= '<td class="text-right">'.number_format($total_money_real).'</td>';
    $content_column .= '<td class="text-right">'.number_format($money_real_in).'</td>';
    $content_column .= '<td class="text-right">'.number_format($money_cash_in).'</td>';
    $content_column .= '<td class="text-right">'.number_format($total_money_out).'</td>';
    $content_column .= '<td class="text-right">'.number_format($money_real_out).'</td>';
    $content_column .= '<td class="text-right">'.number_format($money_cash_out).'</td>';

    $content_column .= $list->end_tr();

}unset($db_listing);
$content_column .= $list->showFooter();

$content_column .= '<div id="chartContainer"></div>';
$title['title'] = "Thu chi theo phiên đăng nhập";

/* Danh sách các quản lý thu ngân*/
$list_user = '<option value="0"> Tất cả</option>';
$db_user = new db_query('SELECT * FROM admin_users');
while($row_user = mysqli_fetch_assoc($db_user->result)){

    $list_user .= '<option value="'.$row_user['adm_id'].'">'.$row_user['adm_name'].'</option>';
}unset($db_user);

$top_control .='
    <div class="control_right">
        <span class="fl pull_span"> Thời gian:</span>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y',time()).'" id="start_date" type="text">
        <i class="fa fa-arrow-right fl pull_span"></i>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y',time()).'" id="end_date" type="text">
        <span class="fl pull_span"> Quản lý:</span>

        <label><select class="form-control list_store" id="admin_id" >
                    '.$list_user.'
                </select>
        </label>
        <button class="btn btn-success" onclick="fillBillAdmin()"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu </button>
        <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất excel </button>
    </div>
';

$footer_control = '
<div class="total_money">
    <label>Σ Trước giảm: <span id="money_default" class="number_return"> '.number_format($all_money_default).' </span></label>

</div>';


/* Phân trang ajax*/
if($isAjaxRequest){
    $action = getValue('action','str','POST','');
    switch($action){
        case 'listRecord':
            $array_return['content']        = $content_column;
            echo json_encode($array_return);
            break;
    }
    die;
}
$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('title',json_encode($title));
$rainTpl->assign('top_control', $top_control);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('report_1column');