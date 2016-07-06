<? require_once 'inc_security.php';
//Phần hiển thị
//Khởi tạo
$footer_control     = 'THỐNG KÊ DOANH THU THEO QUỸ TIỀN (SỐ TIỀN THU THỰC TẾ)';
$right_column       = '';
$left_column        = '';
$total_report       = '';
$data_module        = 'revenue_fund';
//left column

$left_column        .= '<p class="select-title">Thu ngân:</p>';    
$left_column        .= '<select name="type-report" class="bill-date form-control adm-user-report">';    
$left_column        .= '<option value="-1">--- Tất cả thu ngân</option>';    
// lay ra list thu ngan
$db_adm_users           = new db_query("SELECT adm_id, adm_name FROM admin_users");
while ( $data_adm_use   = mysqli_fetch_assoc($db_adm_users->result) )
{
    $left_column    .= '<option value="' . $data_adm_use['adm_id'] . '">' . $data_adm_use['adm_name'] . '</option>';
}unset($db_users);
$left_column        .= '</select>';
// right column
$id_financial         = 'fin_id';
$bg_financial         = 'financial';
$list = new dataGrid($id_financial,3000);
$list->add('', 'Thời gian');
$list->add('', 'Số HĐ');
$list->add('', 'Doanh thu');
$list->add('', 'Tiền mặt');
$list->add('', 'Thẻ');
// request 
$from_date = getValue('from_date','str','POST','');
$to_date = getValue('to_date','str','POST','');
$adm_user_report = getValue('adm_user_report','int','POST',0);
$And = '';
$array_return = array();
if ( $isAjaxRequest )
{
    $time_order_field = 'fin_updated_time';
    if ( $from_date != '' )
    {
        $from_date = convertDateTime($from_date,'0:0:0');
        $And .= ' AND '.$time_order_field.' >= ' . $from_date;
    }
    if ( $to_date != '' )
    {
        $to_date = convertDateTime($to_date,'0:0:0') + 86400 - 1;
        $And .= ' AND '.$time_order_field.' <= ' . $to_date;
    }
    if ( $adm_user_report > 0 )
    {
        $And .= ' AND fin_admin_id = ' . $adm_user_report;
    }
}else{
    $time_order_field = 'fin_updated_time';
    $And = 'AND '.$time_order_field.' >= ' . ($today - 2592000) . ' AND '.$time_order_field.' <= ' . $today;
    
}

$db_count           = new db_count('SELECT count(*) as count
                                    FROM '.$bg_financial.' 
                                    WHERE 1 '.$list->sqlSearch(). $And . ' 
                                    AND fin_cat_id IN ('. FINANCIAL_CAT_BAN_HANG .', '. FINANCIAL_CAT_CONG_NO_BAN_HANG .')');
$total              = $db_count->total;unset($db_count);

$db_financial_bill_in         = new db_query("SELECT * FROM " . $bg_financial . "
                                            WHERE 1 ".$list->sqlSearch(). $And . " 
                                            AND fin_cat_id IN (". FINANCIAL_CAT_BAN_HANG .", ". FINANCIAL_CAT_CONG_NO_BAN_HANG .")
                                            ORDER BY " . $time_order_field . " ASC
                                            " . $list->limit($total) ." 
                                            ");
$total_financial_bill_in      = mysqli_num_rows($db_financial_bill_in->result);
$right_column       = $list->showHeader($total_financial_bill_in);
$i                  = 0;
$total_fund         = 0;
$data_financial_bill_in = array();
$compare_dates          = array();
$arrDate                = array();

while ( $data_financial = mysqli_fetch_assoc($db_financial_bill_in->result) )
{
    $arrDate['cash']        = 0;
    $arrDate['card']        = 0;
    $date               = gmdate("d/m/Y", $data_financial['fin_updated_time']);
    $data_financial_bill_in[$date] = $data_financial;
    $arrDate['id_fin']  = $data_financial['fin_id'];
    $arrDate['date']    = $data_financial['fin_updated_time'];
    $arrDate['money']   = $data_financial['fin_money'];
    // kiem tra xem tra bằng tiền mặt hay qua the
    if ( $data_financial['fin_pay_type'] == PAY_TYPE_CASH )
    {
        $arrDate['cash'] = $data_financial['fin_money'];
    }
    else 
    {
        $arrDate['card'] = $data_financial['fin_money'];
    }
    $compare_dates[]    = $arrDate;
    $total_fund         += $data_financial['fin_money'];
    
}unset($db_financial_bill_in);
//
$array_date             = array();
$array                  = array();
foreach ($data_financial_bill_in as $date => $data_bill_in)
{
    $i++;
    $soHD               = 0;
    $doanhthu           = 0;
    $fin_id             = array();
    $pay_Cash           = 0;
    $pay_Card           = 0;
    foreach ( $compare_dates as $same_day )
    {
        if ( gmdate( "d/m/Y", $same_day['date'] ) == $date)
        {
            $soHD += 1;
            $doanhthu += $same_day['money'];
            $fin_id[] = $same_day['id_fin'];
            $pay_Cash += $same_day['cash'];
            $pay_Card += $same_day['card'];
        }
    }
    if ( count ( $fin_id ) > 1 )
    {
        $fin_id = implode('_', $fin_id);
    }else{
        $fin_id = implode('', $fin_id);
    }
    //
    $array_date['x'] = convertDateTime($date,'0:0:0');
    $array_date['y'] = intval($doanhthu);
    $array[] = $array_date;
    //
    $right_column .= $list->start_tr($i,$fin_id,'class="menu-normal record-item"');
    $right_column .= '<td class="center"> Trong ngày: ' . $date . '</td>';
    $right_column .= '<td class="center">' . $soHD . '</td>';
    $right_column .= '<td class="text-right">' . number_format($doanhthu) . '</td>';
    $right_column .= '<td class="text-right">' . number_format($pay_Cash) . '</td>';
    $right_column .= '<td class="text-right">' . number_format($pay_Card) . '</td>';
    $right_column .= $list->end_tr();
}
$right_column        .= $list->showFooter();
$right_column   .='<div id="chartContainer"></div>';
$title['title'] = "Doanh thu theo quỹ tiền";
// total report 
$total_report       .= '<p class="select-title">Tổng doanh thu:</p>';
$total_report       .= '<p class="select-title total-cost"><strong>'.number_format($total_fund).'</strong></p>';
// return ajax
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
$rainTpl->assign('data_module', $data_module);
$rainTpl->assign('formDate', $formDate);
$rainTpl->assign('title', json_encode($title));
$rainTpl->assign('array', json_encode($array));
$rainTpl->assign('toDate', $toDate);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('total_report', $total_report);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('right_column',$right_column);
$custom_script = file_get_contents('script_report.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column_report_bill');