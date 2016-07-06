<? require_once 'inc_security.php';
//Phần hiển thị
//Khởi tạo
$footer_control     = 'THỐNG KÊ CHI PHÍ THEO HÓA ĐƠN NHẬP HÀNG';
$right_column       = '';
$left_column        = '';
$total_report       = '';
$data_module        = 'report_bill';
//left column
$left_column        .= '<p class="select-title">Nhà cung cấp:</p>';
$left_column        .= '<select name="type-report" class="bill-date brand-report form-control">';
// lay ra nha cung cap
$db_suppliers        = new db_query("SELECT sup_id, sup_name FROM suppliers");
while ( $data_sup    = mysqli_fetch_assoc($db_suppliers->result) )
{
    $left_column    .= '<option value="' . $data_sup['sup_id'] .'">' . $data_sup['sup_name'] . '</option>';
}unset($db_suppliers);
$left_column        .= '</select>';
$left_column        .= '<span class="search-report"><i class="fa fa-search"></i></span>';
$left_column        .= '<div class="clear"></div>';
$left_column        .= '<p class="select-title">Kho hàng:</p>';
$left_column        .= '<select name="type-report" class="bill-date store-report form-control">';
$left_column        .= '<option value="0">--- Tất cả kho hàng</option>';
// lay ra kho hang
$db_bill_out         = new db_query("SELECT bio_store_id FROM bill_out");
$store_id            = array();
while ( $data_bill_out  = mysqli_fetch_assoc($db_bill_out->result) )
{
    $store_id[$data_bill_out['bio_store_id']] = $data_bill_out['bio_store_id'];
}unset($db_bill_out);
foreach ( $store_id as $id_store => $value) 
{
    $db_store       = new db_query("SELECT cat_name FROM categories_multi WHERE cat_id = " . $id_store);
    $data_store     = mysqli_fetch_assoc($db_store->result); unset($db_store);
    $left_column    .= '<option value="' . $id_store .'">' . $data_store['cat_name'] . '</option>';
}
$left_column        .= '</select>';
$left_column        .= '<p class="select-title">Thu ngân nhập kho:</p>';    
$left_column        .= '<select name="type-report" class="bill-date form-control adm-user-report">';    
$left_column        .= '<option value="0">--- Tất cả thu ngân</option>';    
// lay ra thu ngan
$db_admin_users     = new db_query("SELECT adm_id, adm_name FROM admin_users");
while ( $data_adm_user = mysqli_fetch_assoc($db_admin_users->result) )
{
    $left_column        .= '<option value="' . $data_adm_user['adm_id'] . '">' . $data_adm_user['adm_name'] . '</option>';
}unset($db_admin_users);
$left_column        .= '</select>';
// right column
$id_field_o         = 'bio_id';
$bg_table_o         = 'bill_out';
$list = new dataGrid($id_field_o,3000);
$list->add('', 'Thời gian');
$list->add('', 'Số HĐ');
$list->add('', 'Chi phí');
$list->add('', 'Trung bình');
// request 
$from_date =  getValue('from_date','str','POST','');
$to_date = getValue('to_date','str','POST','');
$adm_user_report = getValue('adm_user_report','int','POST',0);
$brand_report = getValue('brand_report','int','POST',0);
$store_report = getValue('store_report','int','POST',0);
$And = '';
$array_return = array();
$time_order_field = 'bio_start_time';
if ( $isAjaxRequest )
{
    if ( $from_date != '' ) 
    {
        $from_date  = convertDateTime($from_date,'0:0:0');
        $And .= ' AND '.$time_order_field.' >= ' . $from_date;
    }
    if ( $to_date != '' )
    {
        $to_date  = convertDateTime($to_date,'0:0:0') + 86400 - 1;
        $And .= ' AND '.$time_order_field.' <= ' . $to_date;
    }
    if ( $adm_user_report > 0 )
    {
        $And .= ' AND bio_admin_id = ' . $adm_user_report;
    }
    if ( $brand_report > 0 )
    {
        $And .= ' AND bio_supplier_id = ' . $brand_report;
    }
    if ( $store_report > 0 )
    {
        $And .= ' AND bio_store_id = ' . $store_report;
    }
}else{
    $And = 'AND '.$time_order_field.' >= ' . ($today - 2592000) . ' AND '.$time_order_field.' <= ' . $today;
}
$db_count           = new db_count('SELECT count(*) as count
                                    FROM '.$bg_table_o.' 
                                    WHERE 1 '.$list->sqlSearch(). $And . '
                                    ');
$total              = $db_count->total;unset($db_count);

$db_bill_out         = new db_query("SELECT * FROM " . $bg_table_o . "
                                    WHERE 1 " . $And . "
                                    ORDER BY " . $time_order_field . " ASC
                                    " . $list->limit($total) ." 
                                    ");
$total_bill_out      = mysqli_num_rows($db_bill_out->result);
$right_column       = $list->showHeader($total_bill_out);
$i                  = 0;
$total_bill         = 0;
$date_money         = array();
$aray               = array();
$data               = array();
while($data_bill_out = mysqli_fetch_assoc($db_bill_out->result) )
{
    $aray['id']     = $data_bill_out['bio_id'];
    $aray['date']   = $data_bill_out['bio_start_time'];  
    $aray['money']  = $data_bill_out['bio_total_money'];
    $date_money[]   = $aray;
    //
    $date           = gmdate("d-m-Y",$data_bill_out['bio_start_time']); // lay ra ngay tao hoa don
    $data[$date]    = $data_bill_out;
    $total_bill     += $data_bill_out['bio_total_money'];
}unset($db_bill_in);
//
$array_date             = array();
$array                  = array();
foreach ( $data as $key => $value)
{
    $soHD = 0;
    $chiphi = 0;
    $bio_id = array();
    foreach ( $date_money as $val )
    {
        $date = gmdate("d-m-Y",$val['date']);
        if ( $date == $key )
        {
            $bio_id[] = $val['id'];
            $soHD += 1;
            $chiphi += $val['money'];
        }
    }
    if ( count($bio_id) > 1)
    {
        $bio_id = implode('_', $bio_id);
    }else{
        $bio_id = implode('', $bio_id);
    }
    //
    $array_date['x'] = convertDateTime($key,'0:0:0');
    $array_date['y'] = intval($chiphi);
    $array[] = $array_date;
    //
    $i++;
    $right_column .= $list->start_tr($i,$bio_id,'class="menu-normal record-item"');
    $right_column .= '<td class="center"> Trong ngày: ' . $key . '</td>';
    $right_column .= '<td class="center">' . $soHD . '</td>';
    $right_column .= '<td class="text-right"> ' . number_format($chiphi) . '</td>';
    $right_column .= '<td class="text-right"> ' . number_format($chiphi / $soHD) . '</td>';
    $right_column .= $list->end_tr();
}
$right_column        .= $list->showFooter();
$right_column   .='<div id="chartContainer"></div>';
$title['title'] = "Chi phí theo hóa đơn";
// total report 
$total_report       .= '<p class="select-title">Tổng chi phí:</p>';
$total_report       .= '<p class="select-title total-cost"><strong>'.number_format($total_bill).'</strong></p>';
// return ajax
if  ( $isAjaxRequest )
{
    $array_return['dt'] = $array;
    $array_return['table'] = $right_column;
    $array_return['total_cost'] = $total_bill;
    echo json_encode($array_return);
    die;
}

$rainTpl = new RainTPL();
add_more_css('css_report_bill.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('formDate', $formDate);
$rainTpl->assign('toDate', $toDate);
$rainTpl->assign('title', json_encode($title));
$rainTpl->assign('array', json_encode($array));
$rainTpl->assign('data_module', $data_module);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('total_report', $total_report);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('right_column',$right_column);
$custom_script = file_get_contents('script_report.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column_report_bill');