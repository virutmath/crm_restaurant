<? require_once 'inc_security.php';
// request ajax 
$from_date    = getValue('from_date','str','POST','');
$to_date    = getValue('to_date','str','POST','');
$type_report    = getValue('type_report','int','POST',0);
$customer_report    = getValue('customer_report','int','POST',0);
$user_report    = getValue('user_report','int','POST',0);
$adm_user_report    = getValue('adm_user_report','int','POST',0);
$type_bill    = getValue('type_bill','int','POST',0);
//Khởi tạo
$footer_control     = 'THỐNG KÊ CHI PHÍ THEO HÓA ĐƠN BÁN HÀNG';
$right_column       = '';
$left_column        = '';
$total_report       = '';
$data_module        = 'revenue_bill';
//left column
$left_column        .= '<p class="select-title">Khách hàng:</p>';
$left_column        .= '<select name="customer" class="bill-date customer-report form-control">';
$left_column        .= '<option value="-1">--- Tất cả khách hàng</option>';
// lay ra list khach hang
$db_customer        = new db_query("SELECT cus_id, cus_name FROM customers");
while ( $data_cus   = mysqli_fetch_assoc($db_customer->result) )
{
    $left_column    .= '<option value="' . $data_cus['cus_id'] .'">' . $data_cus['cus_name'] . '</option>';
}unset($db_customer);
$left_column        .= '<option value="0">Khách lẻ</option>';
$left_column        .= '</select>';
$left_column        .= '<span class="search-report"><i class="fa fa-search"></i></span>';
$left_column        .= '<div class="clear"></div>';

$left_column        .= '<p class="select-title">Nhân viên:</p>';
$left_column        .= '<select name="type-report" class="bill-date user-report form-control">';
$left_column        .= '<option value="-1">--- Tất cả nhân viên</option>';
// lay ra list nhan vien
$db_users           = new db_query("SELECT use_id, use_name FROM users");
while ( $data_use   = mysqli_fetch_assoc($db_users->result) )
{
    $left_column    .= '<option value="' . $data_use['use_id'] . '">' . $data_use['use_name'] . '</option>';
}unset($db_users);
$left_column        .= '<option value="0">Mặc định không chọn</option>';
$left_column        .= '</select>';
$left_column        .= '<span class="search-report"><i class="fa fa-search"></i></span>';
$left_column        .= '<div class="clear"></div>';

$left_column        .= '<p class="select-title">Thu ngân:</p>';    
$left_column        .= '<select name="type-report" class="bill-date form-control adm-user-report">';    
$left_column        .= '<option value="-1">--- Tất cả thu ngân</option>'; 
// lay ra thu ngan 
$db_admin_users     = new db_query("SELECT adm_id, adm_name FROM admin_users");
while ( $data_adm_user = mysqli_fetch_assoc($db_admin_users->result) )
{
    $left_column        .= '<option value="' . $data_adm_user['adm_id'] . '">' . $data_adm_user['adm_name'] . '</option>';
}unset($db_admin_users);
$left_column        .= '</select>';
// right column
$id_field_i         = 'bii_id';
$bg_table_i         = 'bill_in';
$list = new dataGrid($id_field_i,3000);
$list->add('', 'Thời gian');
$list->add('', 'Số HĐ');
$list->add('', 'Doanh thu');
$list->add('', 'Trung bình');
$And    = '';
$array_return = array();
if ( $isAjaxRequest )
{
    if ( $type_bill == 0 ){
        $time_order_field = 'bii_start_time';
    }else{
        $time_order_field = 'bii_end_time';
    }
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
    if ( $customer_report >= 0 ) $And .= ' AND bii_customer_id = ' . $customer_report;
    if ( $user_report >= 0 ) $And .= ' AND bii_staff_id = ' . $user_report;
    if ( $adm_user_report >= 0 ) $And .= ' AND bii_admin_id = ' . $adm_user_report;
}else{
    $time_order_field = 'bii_start_time';
    $And = 'AND '.$time_order_field.' >= ' . ($today - 2592000) . ' AND '.$time_order_field.' <= ' . $today;
}

$db_count           = new db_count('SELECT count(*) as count
                                    FROM '.$bg_table_i.' 
                                    WHERE 1 '.$list->sqlSearch().$And.'
                                    ');
$total              = $db_count->total;unset($db_count);

$db_bill_in         = new db_query("SELECT * FROM " . $bg_table_i . "
                                    WHERE 1 " . $And . "
                                    ORDER BY " . $time_order_field . " ASC
                                    " . $list->limit($total) ." 
                                    ");
$total_bill_in      = mysqli_num_rows($db_bill_in->result);
$right_column       = $list->showHeader($total_bill_in);
$i                  = 0;
$total_fund         = 0;
$date_money         = array();
$aray               = array();
$data               = array();
while($data_bill_in = mysqli_fetch_assoc($db_bill_in->result) )
{
    $aray['id']     = $data_bill_in['bii_id'];
    $aray['date']   = $data_bill_in['bii_start_time'];  
    $aray['money']  = $data_bill_in['bii_true_money'];
    $date_money[]   = $aray;
    //
    $date           = gmdate("d-m-Y",$data_bill_in['bii_start_time']); // lay ra ngay tao hoa don
    $data[$date]    = $data_bill_in;
    $total_fund     += $data_bill_in['bii_true_money'];
}unset($db_bill_in);
//
$array_date             = array();
$array                  = array();
foreach ( $data as $key => $value)
{
    $soHD = 0;
    $doanhthu = 0;
    $bii_id = array();
    foreach ( $date_money as $val )
    {
        $date = gmdate("d-m-Y",$val['date']);
        if ( $date == $key )
        {
            $bii_id[] = $val['id'];
            $soHD += 1;
            $doanhthu += $val['money'];
        }
    }
    if ( count($bii_id) > 1)
    {
        $bii_id = implode('_', $bii_id);
    }else{
        $bii_id = implode('', $bii_id);
    }
    $i++;
    //
    $array_date['x'] = convertDateTime($key,'0:0:0');
    $array_date['y'] = intval($doanhthu);
    $array[] = $array_date;
    //
    $right_column .= $list->start_tr($i,$bii_id,'class="menu-normal record-item"');
    $right_column .= '<td class="center"> Trong ngày: ' . $key . '</td>';
    $right_column .= '<td class="center">' . $soHD . '</td>';
    $right_column .= '<td class="text-right"> ' . number_format($doanhthu) . '</td>';
    $right_column .= '<td class="text-right"> ' . number_format($doanhthu / $soHD) . '</td>';
    $right_column .= $list->end_tr();
}
$title['title'] = "Doanh thu theo hóa đơn";
$right_column        .= $list->showFooter();
$right_column   .='<div id="chartContainer"></div>';
// total report 
$total_report       .= '<p class="select-title">Lọc theo thời gian:</p>';
$total_report       .= '<p class="select-title">';
$total_report       .= '<span class="creat-bill">';
$total_report       .= '<input type="radio" name="bill" id="creat_bill" checked="checked"/>';
$total_report       .= '<label for="creat_bill"> Tạo HĐ (mặc định)</label>';
$total_report       .= '</span>';
$total_report       .= '<span class="pay-bill">';
$total_report       .= '<input type="radio" name="bill" id="pay_bill"/>';
$total_report       .= '<label for="pay_bill"> Thanh toán HĐ</label>';
$total_report       .= '</span>';
$total_report       .= '<div class="clear"></div>';
$total_report       .= '</p>';
$total_report       .= '<p class="select-title">Tổng doanh thu:</p>';
$total_report       .= '<p class="select-title total-cost"><strong>'.number_format($total_fund).'</strong></p>';
if ( $isAjaxRequest )
{
    $array_return['dt'] = $array;
    $array_return['table'] = $right_column; 
    $array_return['total_cost'] = $total_fund;
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