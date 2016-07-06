<?
require_once 'inc_security.php';
if(!isset($_GET['id']) || !isset($_GET['type'])){
    die;
}
$record_id  = getValue('id','int');
$type       = getValue('type','str');
//Phần xử lý
$top_control = '';
$footer_control = '';
$content_column = '';
$tabel          = '';
$bill_id        = '';
$type_bill_id   = ''; // loại hóa đơn
$bill_status    = '';
$type_table     = '';
$id_obj         = ''; // id của đối tượng
$name           = '';
$date           = '';
$debit          = '';
$name_obj       = '';
$date_type      = '';
$And            = '';
$obj            = '';

if($isAjaxRequest){
    if(!isset($_GET['time_start']) || !isset($_GET['time_end'])){
        die;
    }
    $t_start = getValue('time_start','str');
    $t_start = convertDateTime($t_start,'0:0:0');
    $t_end   = getValue('time_end','str');
    $t_end   = convertDateTime($t_end,'0:0:0') + 86400;
    $today   = convertDateTime(time(),'0:0:0');
    //
    if($t_start != $today && $type == 'customer'){
        $And = 'AND bii_end_time >= ' .$t_start;
    }
    if($t_end != ($today + 86400) && $type == 'customer'){
        $And   = 'AND bii_end_time <= ' .$t_end;
    }
    if($t_start != $today && $t_end != ($today + 86400) && $type == 'customer'){
        $And   = 'AND bii_end_time <= ' .$t_end .' AND bii_end_time >= ' .$t_start;
    }
    if($t_start == $today && $t_end == ($today + 86400) && $type == 'customer'){
        $And    = '';
    }
    //
    if($t_start != $today && $type == 'supplier'){
        $And = 'AND bio_start_time >= ' .$t_start;
    }
    if($t_end != ($today + 86400) && $type == 'supplier'){
        $And   = 'AND bio_start_time <= ' .$t_end;
    }
    if($t_start != $today && $t_end != ($today + 86400) && $type == 'supplier'){
        $And   = 'AND bio_start_time <= ' .$t_end .' AND bio_start_time >= ' .$t_start;
    }
    if($t_start == $today && $t_end == ($today + 86400) && $type == 'supplier'){
        $And    = '';
    }
}

if($type            == 'customer'){
    $tabel          = 'bill_in';
    $bill_status    = 'bii_status';
    $type_bill_id   = 'bii_customer_id';
    $bill_id        = 'bii_id';
    $type_table     = 'customers';
    $id_obj         = 'cus_id';
    $name           = 'cus_name';
    $date           = 'bii_end_time';
    $date_type      = 'Ngày bán';
    $debit          = 'bii_money_debit';
    $name_obj       = 'Khách Hàng';
}
if($type            == 'supplier'){
    $tabel          = 'bill_out';
    $bill_status    = 'bio_status';
    $type_bill_id   = 'bio_supplier_id';
    $bill_id        = 'bio_id';
    $type_table     = 'suppliers';
    $id_obj         = 'sup_id';
    $name           = 'sup_name';
    $date           = 'bio_start_time';
    $date_type      = 'Ngày nhập';
    $debit          = 'bio_money_debit';
    $name_obj       = 'Nhà Cung Cấp';
}
$top_control .= 
'<div class="filter_bill_datetime">
    <div class="mini_filter_bill_datetime">
        <form action="" >
            <input id="check-all" type="checkbox"/>
            <label class="control-btn" for="check-all"> Tất cả</label>
            <input class="datetime-local frm-dt-lft lft" name="" value=""/>
            <span class="frm-ic-ct"><i class="fa fa-arrow-right"></i></span>
            <input class="datetime-local frm-dt-rgh lft" name="" value=""/>
            <span class="filters '.$type.'" data-id="'.$record_id.'"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu</span>
            <span class="exc-out"><i class="fa fa-file-excel-o"></i> Xuất Excel</span>
        </form>
    </div>
</div>';
$list = new dataGrid($bill_id,10);
$list->add($bill_id, 'Mã HĐ');
$list->add($date, $date_type);
$list->add($debit, 'Ghi nợ');
$list->add('', 'Còn lại');
$list->add($bill_status, 'Trạng thái');

$db_count           = new db_query('SELECT *
                                    FROM '.$tabel.'
                                    WHERE '.$bill_status.' = 0 ' . $And . '
                                    AND '.$type_bill_id.' = ' . $record_id . '
                                    ');
$total              = mysqli_num_rows($db_count->result);unset($db_count);
$db_list_bill       = new db_query('SELECT *
                                    FROM '.$tabel.' INNER JOIN '.$type_table.' ON '.$type_bill_id.' = '.$id_obj.'
                                    WHERE '.$bill_status.' = 0 ' . $And . '
                                    AND '.$type_bill_id.' = ' . $record_id . '
                                    ORDER BY ' . $list->sqlSort() . $bill_id. '
                                    ASC ' . $list->limit($total)
                                    );
$total_row          = mysqli_num_rows($db_list_bill->result);
$content_column    .= '<div id="table_result">';
$table_result       = '';                    
$table_result     .= $list->showHeader($total_row);

$i                  = 0;
while($data_bill    = mysqli_fetch_assoc($db_list_bill->result)){
    $i++;
    $table_result       .= $list->start_tr($i,$data_bill[$bill_id],'class="menu-normal record-item" onclick="active_record('.$data_bill[$bill_id].')"');
    $table_result       .= '<td class="center">'.format_codenumber($data_bill[$bill_id],6,'').'</td>';
    $table_result       .= '<td class="center"  width="170">'.date("d-m-Y h:i",$data_bill[$date]).'</td>';
    $table_result       .= '<td class="text-right" width="100">'.number_format($data_bill[$debit]).'</td>';
    $table_result       .= '<td class="text-right"> </td>';
    $table_result       .= '<td class="center">Chưa Thanh Toán</td>';
    $obj                = $name_obj . ': <b>'.$data_bill[$name].'</b>';
    $table_result       .= $list->end_tr();
}
$table_result           .= $list->showFooter();
$content_column         .= $table_result;
$content_column         .= '</div>';

$footer_control         .= '<div class="name_obj">'.$obj.'</div>';
$footer_control         .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span>';

if($isAjaxRequest){
    echo $table_result;
    die;
}

$rainTpl = new RainTPL();
add_more_css('css_detail.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('top_control', $top_control);

$rainTpl->assign('content_column', $content_column);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script_detail.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('detail_libility');

?>
