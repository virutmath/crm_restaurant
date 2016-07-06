<?
require_once 'inc_security.php';

//Phần hiển thị
//Khởi tạo
$top_control    = '';
$content_column = '';
$footer_control = '';

$id_field = "bii_id";
$bg_table = "bill_in";

#Bắt đầu với datagrid
$list = new dataGrid($id_field, 30);
$list->add('', 'Thời gian');
$list->add('', 'Bàn');
$list->add('', 'Số HĐ');
$list->add('', 'Cộng trước giảm');
$list->add('', 'Giảm');
$list->add('', 'Phí DV');
$list->add('', 'VAT');
$list->add('', 'Tổng tiền');
$list->add('', 'Tiền mặt');
$list->add('', 'Thẻ');
$list->add('', 'Ghi nợ');

/* Lấy các thông tin bắn ajax về*/
$start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
$end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0') + 86400 -1;
$store_id       = getValue('store_id','int','POST',0);
$admin_id       = getValue('admin_id','int','POST',0);

$start_today = convertDateTime(time(),'0:0:0');// bắt đầu trong ngày từ 00
$end_today   = convertDateTime(time(),'0:0:0') + 86400 - 1; // kết thúc ngày

if( $isAjaxRequest ){

    if ($start_date){
        $sql_search .= ' AND bii_start_time >= ' . $start_date;
    }
    if ($end_date){
        $sql_search .= ' AND bii_start_time <= ' . $end_date;
    }
    if ($store_id != 0){
        $sql_search .= ' AND bii_store_id = ' . $store_id;
    }
    if ($admin_id != 0){
        $sql_search .= ' AND bii_admin_id = ' . $admin_id;
    }

}else{
    $sql_search = ' AND bii_start_time >= ' .$start_today.' AND bii_start_time <= '.$end_today.'';
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

/* Các biến tổng trong hóa đơn*/
$all_money_menus    = 0;
$all_money_default  = 0;
$all_money_real     = 0;
$all_money_discount = 0;
$all_money_vat      = 0;
$all_money_true     = 0;
$all_money_service  = 0;
$all_money_cash     = 0;
$all_money_debit    = 0;


$i = 0;
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    /* Hiển thị tên tên bàn*/
    $db_section = new db_query('SELECT * FROM desks WHERE des_id = '.$row['bii_desk_id'].'');
    $row_sec = mysqli_fetch_assoc($db_section->result);
    //query vi tri
    $db_location = new db_query('SELECT * FROM sections WHERE sec_id ='.$row_sec['des_sec_id'].'');
    $row_location = mysqli_fetch_assoc($db_location->result);
    $location = $row_sec['des_name'].' - '.$row_location['sec_name'];

    /* Số tiền tổng tiền*/
    $vat_value = $row['bii_vat']/100;
    $total_money_ext = $row['bii_true_money'] - ($row['bii_true_money']*$row['bii_discount']/100) + ($row['bii_true_money']*$row['bii_extra_fee']/100);
    $total_money_real = $total_money_ext * (1 + $vat_value);
    unset($db_location);unset($db_section);

    $money_real = round($total_money_real,-3) - $row['bii_money_debit'];

    $i++;
    $content_column .= $list->start_tr($i, $row[$id_field], 'class="menu-normal record-item" data-record_id="' . $row[$id_field] . '"');
    /* code something */
    $content_column .= '<td class="center">' . date('d/m/Y h:i',$row['bii_start_time']) .'</td>';
    $content_column .= '<td class="center">' . $location . '</td>';
    $content_column .= '<td class="center">' . format_codenumber($row['bii_id'],6,PREFIX_BILL_CODE)  . '</td>';
    $content_column .= '<td class="text-right">'.number_format($row['bii_true_money']).'</td>';// Số tiền tổng chưa giảm
    $content_column .= '<td class="text-right">'.number_format($row['bii_true_money']*$row['bii_discount']/100).'</td>';
    $content_column .= '<td class="text-right">'.number_format($row['bii_true_money']*$row['bii_extra_fee']/100).'</td>';
    $content_column .= '<td class="text-right">'.number_format($total_money_ext*$row['bii_vat']/100).'</td>';
    $content_column .= '<td class="text-right">'.number_format(round($total_money_real,-3)).'</td>';
    $content_column .= '<td class="text-right">'.number_format($row['bii_type'] == 0 ? $money_real : 0).'</td>';/* Tiền mặt*/
    $content_column .= '<td class="text-right">'.number_format($row['bii_type'] == 1 ? $money_real : 0).'</td>';
    $content_column .= '<td class="text-right">'.number_format($row['bii_money_debit']).'</td>';

    $content_column .= $list->end_tr();

    /* Tổng các số tiền dựa theo hóa đơn bán hàng*/
    $all_money_default      += $row['bii_true_money'];
    $all_money_discount     += $row['bii_true_money']*$row['bii_discount']/100;
    $all_money_real         += round($total_money_real,-3);/* Tổng tiền*/
    $all_money_vat          += $total_money_ext*$row['bii_vat']/100;
    $all_money_service      += $row['bii_true_money']*$row['bii_extra_fee']/100;
    $all_money_debit        += $row['bii_money_debit'];
    $all_money_true         += $row['bii_type'] == 0 ? $money_real : 0; /*Tiền mặt*/
    $all_money_cash         += $row['bii_type'] == 1 ? $money_real : 0; /*Tiền mặt*/
}
unset($db_listing);
$content_column .= $list->showFooter();

//lấy ra tất cả kho hàng
$list_store = '';
$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores"');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $list_store .= '<option value="'.$row_store['cat_id'].'">'.$row_store['cat_name'].'</option>';
}unset($db_store);
/* Danh sách các quản lý thu ngân*/
$list_user = '';
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
        <span class="fl pull_span"> Kho hàng:</span>
        <label><select class="form-control list_store" id="store_id" >
                    '.$list_store.'
                </select>
        </label>
        <label><select class="form-control list_store" id="admin_id" >
                    '.$list_user.'
                </select>
        </label>
        <button class="btn btn-success" onclick="fillBillDetail()"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu </button>
        <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất excel </button>
    </div>
';

$footer_control = '
<div class="total_money">
    <label>Σ Trước giảm: <span id="money_default" class="number_return"> '.number_format($all_money_default).' </span></label>
    &nbsp;
    <label>Σ Giảm: <span id="money_discount" class="number_return"> '.number_format($all_money_discount).' </span></label>
    &nbsp;
    <label>Σ DV: <span id="money_service" class="number_return"> '.number_format($all_money_service).' </span></label>
     &nbsp;
    <label>Σ VAT: <span id="money_vat" class="number_return"> '.number_format($all_money_vat).' </span></label>
     &nbsp;
    <label>Σ Doanh thu: <span id="money_real" class="number_return"> '.number_format($all_money_real).' </span></label>
     &nbsp;
    <label>Σ Tiền mặt: <span id="money_true" class="number_return"> '.number_format($all_money_true).' </span></label>
     &nbsp;
    <label>Σ Tiền thẻ: <span id="money_cash" class="number_return"> '.number_format($all_money_cash).' </span></label>
    &nbsp;
    <label>Σ Ghi nợ: <span id="money_debit" class="number_return"> '.number_format($all_money_debit).' </span></label>
</div>';


/* Phân trang ajax*/
if($isAjaxRequest){
    $action = getValue('action','str','POST','');
    switch($action){
        case 'listRecord':
            $array_return['content']        = $content_column;
            $array_return['money_default']  = number_format($all_money_default);
            $array_return['money_discount'] = number_format($all_money_discount);
            $array_return['money_service']  = number_format($all_money_service);
            $array_return['money_vat']      = number_format($all_money_vat);
            $array_return['money_real']     = number_format($all_money_real);
            $array_return['money_true']     = number_format($all_money_true);
            $array_return['money_cash']     = number_format($all_money_cash);
            $array_return['money_debit']    = number_format($all_money_debit);
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
$rainTpl->assign('top_control', $top_control);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('report_1column');