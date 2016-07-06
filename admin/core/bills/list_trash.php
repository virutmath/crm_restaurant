<?
require_once 'inc_security.php';
if(!isset($_GET['type_bill'])){
    die;
}
$type_bill          = getValue('type_bill','int','GET',0);
$content_column = '';
$footer_control = '';
// khai báo
$table  = '';
$type   = '';
$bill_id = '';
$day_creat = '';
$total_money = '';
$date = '';
$id_object = '';
$table_object = '';
$name_object = '';
$name_obj = '';
// list hoa don ban trong thung rac
if(intval($type_bill)       == 0){
    $table          = 'bill_in';
    $type           = 'Khách hàng';
    $bill_id        = 'bii_id';
    $day_creat      = 'bii_end_time';
    $total_money    = 'bii_true_money';
    $date           = 'Ngày bán';
    $id             = 'cus_id';
    $id_object      = 'bii_customer_id';
    $table_object   = 'customers';
    $name_object    = 'cus_name';
    $name_obj           = 'Khách lẻ';
}
if(intval($type_bill)       == 1){
    $table          = 'bill_out';
    $type           = 'Nhà cung cấp';
    $bill_id        = 'bio_id';
    $day_creat      = 'bio_start_time';
    $total_money    = 'bio_total_money';
    $date           = 'Ngày tạo';
    $id             = 'sup_id';
    $id_object      = 'bio_supplier_id';
    $table_object   = 'suppliers';
    $name_object    = 'sup_name';
    $name_obj           = 'Nhà cung cấp ngoài';
}

$list = new dataGrid($bill_id,10);
$list->add($bill_id, 'Số HĐ');
$list->add('', $type);
$list->add('', $date);
$list->add('', 'Tổng tiền');
$list->add('', 'Ghi chú');
// tổng số hóa đơn trong thùng rác
$count_bill_trash   = new db_count('SELECT count(*) as count
                                    FROM trash
                                    WHERE 1 '.$list->sqlSearch().' 
                                    AND tra_table = "' . $table . '"
                                    ');
$total = $count_bill_trash->total;unset($count_bill_trash);

$db_list_bill_trash     = new db_query('SELECT * FROM trash
                                        WHERE tra_table = "' . $table . '" '
                                        . $list->limit($total));
$total                  = mysqli_num_rows($db_list_bill_trash->result); unset($db_list_bill_trash);
// lọc ra thông tin hóa đơn trong thùng rác
$array_bill = trash_list($table,$total,0);
$i = 0;
$content_column        .= '<div class="section-content">';
$content_column        .= $list->showHeader($total,'','id="list_trash"');
foreach($array_bill as $row){
    $i++;
    if($row[$id_object] == 0){
        $name               = $name_obj;
    }else{
        $db_list_bill       = new db_query('SELECT ' . $name_object . ' FROM ' . $table_object . ' WHERE ' . $id . ' = ' . $row[$id_object]);
        $data_list_bill     = mysqli_fetch_assoc($db_list_bill->result);unset($db_list_bill);
        $name               = $data_list_bill[$name_object];
    }
    $content_column    .= $list->start_tr($i,$row[$bill_id],'class="menu-normal record-item" onclick="active_record('.$row[$bill_id].')" data-table="'.$table.'" ondblclick="show_bill_detai()" data-record_id="'.$row[$bill_id].'"'); 
    $content_column    .= '<td>' . format_codenumber($row[$bill_id],6,PREFIX_BILL_CODE) . '</td>';
    $content_column    .= '<td>' . $name . '</td>';
    $content_column    .= '<td class="center">' . date('d-m-Y',$row[$day_creat]) . '</td>';
    $content_column    .= '<td class="text-right">'.number_format($row[$total_money]).'</td>';
    $content_column    .= '<td></td>';
    $content_column    .= $list->end_tr();
}
$content_column        .= $list->showFooter();
$content_column        .= '</div>';
// footer
$footer_control .= '<div class="print-close">';
$footer_control .= '<span class="control-btn restore deactivate"><i class="fa fa-retweet"></i> Khôi phục</span>';
$footer_control .= '<span class="control-btn del_permanently deactivate"><i class="fa fa-file-excel-o"> Xóa vĩnh viễn</i></span> ';
$footer_control .= '<span class="control-btn deactivate view_detail"><i class="fa fa-list"></i> Xem chi tiết</span>';
$footer_control .= '<span class="control-btn deactivate information"><i class="fa fa-user"></i> Xem thông tin</span>';
$footer_control .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span>';
$footer_control .= '</div>';

$rainTpl = new RainTPL();
add_more_css('detail_cus_user.css',$load_header);
$rainTpl->assign('load_header',$load_header);

$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
$custom_script = file_get_contents('script_list_bill_trash.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mindow_iframe_1column');
?>