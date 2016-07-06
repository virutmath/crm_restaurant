<? require_once 'inc_security.php';
//Phần hiển thị
//Khởi tạo
$footer_control     = 'THỐNG KÊ THU CHI THEO HÓA ĐƠN';
$right_column       = '';
$left_column        = '';
$total_report       = '';
$data_module        = 'expenditures_bill';
//left column
$left_column        .= '<p class="select-title">Thu ngân:</p>';    
$left_column        .= '<select name="type-report" class="bill-date form-control adm-user-report">';    
$left_column        .= '<option value="0">--- Tất cả thu ngân</option>';    
// lay ra list thu ngan
$db_adm_users           = new db_query("SELECT adm_id, adm_name FROM admin_users");
while ( $data_adm_use   = mysqli_fetch_assoc($db_adm_users->result) )
{
    $left_column    .= '<option value="' . $data_adm_use['adm_id'] . '">' . $data_adm_use['adm_name'] . '</option>';
}unset($db_users);
$left_column        .= '</select>';
// right column
$id_financial   = 'fin_id';
$id_bill        = 'fin_billcode';
$bg_table_fin   = 'financial';
$list = new dataGrid($id_bill,3000);
$list->add('', 'Thời gian');
$list->add('', 'Doanh thu');
$list->add('', 'Chi phí');
$list->add('', 'Tiền lãi');
// request
$from_date = getValue('from_date','str','POST','');
$to_date = getValue('to_date','str','POST','');
$adm_user_report = getValue('adm_user_report','int','POST',0);
$And = '';
$array_return = array();
$time_order_field = 'fin_updated_time';
if ( $isAjaxRequest )
{ 
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
    $And = 'AND '.$time_order_field.' >= ' . ($today - 2592000) . ' AND '.$time_order_field.' <= ' . $today;
}
$db_count               = new db_count('SELECT count(*) as count
                                        FROM '.$bg_table_fin.' 
                                        WHERE 1 '.$list->sqlSearch(). $And . '
                                        AND fin_cat_id IN(' . FINANCIAL_CAT_BAN_HANG . ', ' . FINANCIAL_CAT_NHAP_HANG . ')
                                        ');
$total                  = $db_count->total;unset($db_count);
//
$db_financial           = new db_query("SELECT * FROM " . $bg_table_fin . "
                                        WHERE 1 ".$list->sqlSearch(). $And . " 
                                        AND fin_cat_id IN(". FINANCIAL_CAT_BAN_HANG .", ". FINANCIAL_CAT_NHAP_HANG .")
                                        ORDER BY " . $time_order_field . " ASC
                                        " . $list->limit($total) ." 
                                        ");
$total_financial        = mysqli_num_rows($db_financial->result);
$right_column           = $list->showHeader($total_financial);
$i                      = 0;
$array_data_result      = array();
$money                  = array();
$array_money            = array();
$total_thu              = 0;
$total_chi              = 0;
while( $data_fin        = mysqli_fetch_assoc($db_financial->result) )
{
    $date               = gmdate ("d-m-Y",$data_fin['fin_updated_time']);
    $money['bii_money'] = 0;
    $money['bio_money'] = 0;
    $money['fin_id']    = $data_fin[$id_financial];
    $money['date']      = $data_fin['fin_updated_time'];
    // neu fin_cat_id = 30 thi lay gia trong bang hoa don ban
    if ( $data_fin['fin_cat_id'] == FINANCIAL_CAT_BAN_HANG )
    {
        $db_bill_in     = new db_query("SELECT bii_true_money FROM bill_in 
                                        WHERE bii_id = " . $data_fin[$id_bill]);
        if ( mysqli_num_rows($db_bill_in->result) >= 1 )
        {
            $data_bill_in   = mysqli_fetch_assoc($db_bill_in->result);unset($db_bill_in);
            $money['bii_money'] = $data_bill_in['bii_true_money'];
            $total_thu += $data_bill_in['bii_true_money'];
        }
    }
    if ( $data_fin['fin_cat_id'] == FINANCIAL_CAT_NHAP_HANG )
    {
        $db_bill_out     = new db_query("SELECT bio_total_money FROM bill_out 
                                        WHERE bio_id = " . $data_fin[$id_bill]);
        if ( mysqli_num_rows($db_bill_out->result) >= 1 )
        {
            $data_bill_out   = mysqli_fetch_assoc($db_bill_out->result);
            $money['bio_money'] = $data_bill_out['bio_total_money'];
            $total_chi += $data_bill_out['bio_total_money'];
        }
    }
    $array_money[]       = $money;
    $array_data_result[$date] = $date;
}unset($db_financial);
//
$array_date             = array();
$array                  = array();
foreach ( $array_data_result as $date => $value )
{
    $i++;
    $doanh_thu  = 0;
    $chi_phi    = 0;
    $arr_fin_id = array();
    foreach ( $array_money as $result )
    {
        if ( gmdate ("d-m-Y",$result['date']) == $date )
        {
            $doanh_thu += $result['bii_money'];
            $chi_phi += $result['bio_money'];
            $arr_fin_id[] = $result[$id_financial];
        }
    }
    if ( count ($arr_fin_id) >1 )
    {
        $arr_fin_id = implode('_',$arr_fin_id);
    }
    else
    {
        $arr_fin_id = implode('',$arr_fin_id);
    }
    $array_date['x']   = convertDateTime($date,'0:0:0');
    $array_date['thu'] = intval($doanh_thu);
    $array_date['chi'] = intval($chi_phi);
    $array[] = $array_date;
    //
    $right_column .= $list->start_tr($i,$arr_fin_id,'class="menu-normal record-item"');
    $right_column .= '<td class="center"> Trong ngày: ' . $date . '</td>';
    $right_column .= '<td class="text-right">' . number_format($doanh_thu) . '</td>';
    $right_column .= '<td class="text-right">' . number_format($chi_phi) . '</td>';
    $right_column .= '<td class="text-right">' . number_format($doanh_thu - $chi_phi) . '</td>';
    $right_column .= $list->end_tr();
}
$right_column   .= $list->showFooter();
$right_column   .='<div id="chartContainer"></div>';
$title['title'] = "Thống kê thu chi";
$title['all'] = 1;
// total report 
$total_report       .= '<p class="select-title">';
$total_report       .= '<span class="total">Tổng doanh thu:</span>';
$total_report       .= '<span class="total price bill"><strong>' . number_format($total_thu) . '</strong></span>';
$total_report       .= '</p>';
$total_report       .= '<p class="select-title">';
$total_report       .= '<span class="total">Tổng chi phí:</span>';
$total_report       .= '<span class="total price fund"><strong>' . number_format($total_chi) . '</strong></span>';
$total_report       .= '</p>';
$total_report       .= '<p class="select-title">Tổng tiền lãi:</p>';
$total_report       .= '<p class="select-title total-cost"><strong>' . number_format($total_thu - $total_chi) . '</strong></p>';
// return ajax
if ( $isAjaxRequest )
{
    $array_return['type_report'] = $data_module;
    $array_return['dt'] = $array;
    $array_return['bill'] = $total_thu;
    $array_return['fund'] = $total_chi;
    $array_return['table'] = $right_column;
    $array_return['total_cost'] = $total_thu - $total_chi;
    echo json_encode($array_return);
    die;
}
$rainTpl = new RainTPL();
add_more_css('css_report_bill.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('data_module', $data_module);
$rainTpl->assign('formDate', $formDate);
$rainTpl->assign('toDate', $toDate);
$rainTpl->assign('title', json_encode($title));
$rainTpl->assign('array', json_encode($array));
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('total_report', $total_report);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('right_column',$right_column);
$custom_script = file_get_contents('script_report.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column_report_bill');