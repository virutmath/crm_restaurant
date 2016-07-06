<?
require_once 'inc_security.php';
$record_id      = getValue('record_id','int','POST',0);
$position       = getValue('position','str','POST','');
$limit          = getValue('limit','int','POST',0);
//Phần xử lý
$left_control   = '';
$right_control  = '';
$footer_control = '';

$bottom_left_control = '';
$bottom_right_control = '';

$left_column = '';
$right_column = '';

$bottom_left_column = '';
$bottom_right_column = '';

$context_menu = '';
$cus_name = '';

$total_left_top = '';
$total_right_top = '';
$total_bottom_left = '';
$total_bottom_right = '';
$join_table = '';
$And_id = '';

$left_column_title='công nợ khách hàng';
$right_column_title='công nợ nhà cung cấp';

$add_btn = '';$edit_btn = ''; $trash_btn = '';

$left_control .= '<span class="control-btn deactivate payments_liability"><i class="fa fa-check-square"></i> Thanh toán công nợ </span>';
$left_control .= '<span class="control-btn deactivate bill_detail"><i class="fa fa-list-alt"></i> Chi tiết hóa đơn </span>';
$left_control .= list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);

$list = new dataGrid($id_field_i,10);
$list->add('cus_name', 'Khách hàng','string',1,0,'Khách hàng');
$list->add('', 'Số HĐ chưa TT');
$list->add('', 'Tổng tiền chưa TT');
// lọc tổng số khách hàng còn ghi nợ
$db_count_cus = new db_query('SELECT *
                            FROM '.$bg_table_i.'
                            LEFT JOIN '.$cus_table.' ON cus_id = bii_customer_id
                            WHERE 1 ' . $list->sqlSearch() .'AND bii_status = 0
                            GROUP BY bii_customer_id');
$total = mysqli_num_rows($db_count_cus->result);unset($db_count_cus);
// lọc ra thông tin công nợ khách hàng
$db_count_cus = new db_query('SELECT cus_id, cus_name, COUNT(bii_money_debit) as hd_ctt, SUM(bii_money_debit) as total_debit
                            FROM '.$bg_table_i.'
                            LEFT JOIN '.$cus_table.' ON cus_id = bii_customer_id
                            WHERE 1 ' . $list->sqlSearch() .' AND bii_status = 0
                            GROUP BY bii_customer_id
                            ORDER BY ' . $list->sqlSort() . ' cus_id
                            ASC ' . $list->limit($total));
$total_row = mysqli_num_rows($db_count_cus->result);                          
$left_column .= $list->showHeader($total_row,'','id="table-listing-left"');
$i = 0;
$total_all_billin_debit = 0;                              

while($data_cus_debit = mysqli_fetch_assoc($db_count_cus->result)){
    $i++;
    $left_column .= $list->start_tr($i,$data_cus_debit['cus_id'],'class="menu-normal record-item" ondblclick="show_detail(\'left\')" onclick="active_record('.$data_cus_debit['cus_id'].',\'left\')" data-record_id="'.$data_cus_debit['cus_id'].'" data-cus_name="'.$data_cus_debit['cus_name'].'"');
    $left_column .= '<td>'.$data_cus_debit['cus_name'].'</td>';
    $left_column .= '<td class="center" width="100">'.$data_cus_debit['hd_ctt'].'</td>';
    $left_column .= '<td class="text-right" width="170">'.number_format($data_cus_debit['total_debit']).'</td>';
    $left_column .= $list->end_tr();
    $total_all_billin_debit += $data_cus_debit['total_debit'];
}unset($db_count_cus);
$left_column        .= $list->showFooter();
$total_left_top  .=
    '<div class="total-pos">
        <div class="col-xs-6-lft col-xs-6-lft-l">
            <span>Tổng Cộng:</span><input class="ttl-hd" value="" readonly="readonly"/>
        </div>
        <div class="col-xs-6-rgh col-xs-6-lft-r">
            <span>'.$i.'</span><input class="ttl-hd" value="'.number_format($total_all_billin_debit) . ' ' . DEFAULT_MONEY_UNIT .'" readonly="readonly"/>
        </div>
        <div class="clear"></div>
    </div>';
// end left
$right_control .= '<span class="control-btn deactivate payments_liability"><i class="fa fa-check-square"></i> Thanh toán công nợ </span>';
$right_control .= '<span class="control-btn deactivate bill_detail"><i class="fa fa-list-alt"></i> Chi tiết hóa đơn </span>';
$right_control .= list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);

$list = new dataGrid($id_field_o,10);
$list->add('sup_name', 'Nhà cung cấp','string',1);
$list->add('', 'Số HĐ chưa TT');
$list->add('', 'Tổng tiền chưa TT');

// lấy ra tổng số nhà công nợ hóa đơn nhập 
$db_bill_out = new db_query('SELECT *
                            FROM '.$bg_table_o.'
                            LEFT JOIN suppliers ON sup_id = bio_supplier_id
                            WHERE 1 ' . $list->sqlSearch() .' AND bio_status = 0
                            GROUP BY bio_supplier_id');
$total = mysqli_num_rows($db_bill_out->result); unset($db_bill_out);
// danh sách nhà cung cấp còn nợ
$db_bill_out = new db_query('SELECT sup_id,sup_name, COUNT(bio_money_debit) as hd_ctt, SUM(bio_money_debit) as total_debit
                            FROM '.$bg_table_o.'
                            LEFT JOIN '.$sup_table.' ON sup_id = bio_supplier_id
                            WHERE 1 ' . $list->sqlSearch() .' AND bio_status = 0
                            GROUP BY bio_supplier_id 
                            ORDER BY ' . $list->sqlSort() . ' sup_id
                            ASC ' . $list->limit($total)
                            );
$total_row = mysqli_num_rows($db_bill_out->result);                          
$right_column .= $list->showHeader($total_row,'','id="table-listing-right"');
$i = 0;
$total_all_billout_debit = 0;                            
while($data_bill_out_debit = mysqli_fetch_assoc($db_bill_out->result)){
    $i++;
    $right_column .= $list->start_tr($i,$data_bill_out_debit['sup_id'],'class="menu-normal record-item" ondblclick="show_detail(\'right\')" onclick="active_record('.$data_bill_out_debit['sup_id'].',\'right\')" data-record_id="'.$data_bill_out_debit['sup_id'].'" data-sup_name="'.$data_bill_out_debit['sup_name'].'"');
    $right_column .= '<td>'. $data_bill_out_debit['sup_name'] .'</td>';
    $right_column .= '<td class="center" width="100">'. $data_bill_out_debit['hd_ctt'] .'</td>';
    $right_column .= '<td  class="text-right" width="170">'. number_format($data_bill_out_debit['total_debit']) .'</td>';
    $right_column .= $list->end_tr();
    $total_all_billout_debit += $data_bill_out_debit['total_debit'];
}unset($db_bill_out);                          
    $right_column           .= $list->showFooter();
$total_right_top           .=
    '<div class="total-pos">
        <div class="col-xs-6-lft col-xs-6-lft-l">
            <span>Tổng Cộng:</span><input class="ttl-hd" value="" readonly="readonly"/>
        </div>
        <div class="col-xs-6-rgh col-xs-6-lft-r">
            <span>'.$i.'</span><input class="ttl-hd" value="'.number_format($total_all_billout_debit).' ' . DEFAULT_MONEY_UNIT . '" readonly="readonly"/>
        </div>
        <div class="clear"></div>
    </div>';
//end right

$bottom_left_control .= '<span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In phiếu thanh toán</span>';
$bottom_left_control .= 
'<div class="show_number_bill">
    <span>Hiển thị: </span>
    <select class="form-control">
        <option value="5">5 lần thanh toán gần đây</option>
        <option value="10">10 lần thanh toán gần đây</option>
        <option value="15">15 lần thanh toán gần đây</option>
    </select>
</div>';
// kiểm tra có tồn tại request ajax biến limit
$limit_financies = 5;
if($isAjaxRequest && $limit != 0){
    $limit_financies = $limit;
}
$list = new dataGrid('fin_id',$limit_financies);
$list->add('fin_id', 'Số phiếu','string',1);
$list->add('fin_updated_time', 'Ngày thanh toán');
$list->add('fin_money', 'Số tiền');
$list->add('fin_pay_type', 'Trả bằng');
$list->add('fin_note', 'Ghi chú');

if($isAjaxRequest){
    if($record_id && trim($position) == 'left'){
        $join_table = ' LEFT JOIN customers ON fin_username = cus_name ';
        $And_id     = ' AND cus_id = ' . $record_id;
    }
    if($record_id && trim($position) == 'right'){
        $join_table = ' LEFT JOIN suppliers ON fin_username = sup_name ';
        $And_id     = ' AND sup_id = ' . $record_id;
    }
}else{
    $join_table = '';
    $And_id     = ' AND fin_id = 0';
}
// lấy ra tổng số hóa đơn bán đã được trả 
$db_financies_bill_in   = new db_query('SELECT *
                                        FROM '.$financies.$join_table.'
                                        WHERE 1 ' . $list->sqlSearch() .' 
                                        AND fin_cat_id = 33'.$And_id);
$total = mysqli_num_rows($db_financies_bill_in->result); unset($db_financies_bill_in);
// list danh sách hóa đơn
$db_financies_bill_in   = new db_query('SELECT *
                                        FROM '.$financies.$join_table.'
                                        WHERE 1 ' . $list->sqlSearch() .' 
                                        AND fin_cat_id = 33'.$And_id.'
                                        ORDER BY ' . $list->sqlSort() . ' 
                                        fin_id ASC ' . $list->limit($total)
                                        );
// tim kiem theo so phieu
if($isAjaxRequest && isset($_GET['fin_id']) && isset($_GET['cus_name'])){
    $fin_id = getValue('fin_id','int','GET',0);
    $cus_name = getValue('cus_name','str','GET','');
    $db_financies_bill_in   = new db_query('SELECT *
                                            FROM '.$financies.' 
                                            WHERE 1 
                                            AND fin_id = ' . $fin_id . ' 
                                            AND fin_username = \''.trim($cus_name).'\' 
                                            AND fin_reason_other = \''.trim('Công nợ khách hàng').'\' 
                                            LIMIT 1'
                                            );
}
$total_row = mysqli_num_rows($db_financies_bill_in->result);  
$bottom_left_column = '<div class="bottom_conten_left_column">';
$bottom_conten_left_column = '';
$bottom_conten_left_column .= $list->showHeader($total_row,'','id="table-listing-bot-left"');
$i = 0;
$total_all_pay_bill_in = 0;
while($data_financies_bill_in = mysqli_fetch_assoc($db_financies_bill_in->result)){
    $i++;
    if($data_financies_bill_in['fin_pay_type'] == PAY_TYPE_CASH){
        $fin_pay_type = 'Tiền mặt';
    }
    if($data_financies_bill_in['fin_pay_type'] == PAY_TYPE_CARD){
        $fin_pay_type = 'Thẻ';
    }
    $bottom_conten_left_column .= $list->start_tr($i,$data_financies_bill_in['fin_id'],'class="menu-normal record-item" ondblclick="detail_financies('.$data_financies_bill_in['fin_id'].')" data-record_id="'.$data_financies_bill_in['fin_id'].'"');
    $bottom_conten_left_column .= '<td class="center" width="100">'. format_codenumber($data_financies_bill_in['fin_id'],6,'') .'</td>';
    $bottom_conten_left_column .= '<td class="center" width="100">'. date('d/m/Y',$data_financies_bill_in['fin_updated_time']) .'</td>';
    $bottom_conten_left_column .= '<td class="text-right" width="100">'. number_format($data_financies_bill_in['fin_money']) .'</td>';
    $bottom_conten_left_column .= '<td class="center" width="100">'. $fin_pay_type .'</td>';
    $bottom_conten_left_column .= '<td>'. $data_financies_bill_in['fin_note'] .'</td>';
    $bottom_conten_left_column .= $list->end_tr();
    $total_all_pay_bill_in += $data_financies_bill_in['fin_money'];
}unset($db_financies_bill_in);
$bottom_conten_left_column     .= $list->showFooter();
$bottom_conten_left_column     .=
'<div class="total-pos">
    <div class="col-xs-6-lft col-xs-6-lft-l">
        <span>Tổng Cộng:</span><input class="ttl-hd" value="" readonly="readonly"/>
    </div>
    <div class="col-xs-6-rgh col-xs-6-lft-r">
        <input class="ttl-hd" value="'.number_format($total_all_pay_bill_in).' ' . DEFAULT_MONEY_UNIT . '" readonly="readonly"/>
    </div>
    <div class="clear"></div>
</div>';
$bottom_left_column     .= $bottom_conten_left_column;
$bottom_left_column     .= '</div>';


//end bottom left
$bottom_right_control .= '<span class="control-btn deactivate control-bill-in"><i class="fa fa-print"></i> In phiếu thanh toán</span>';
$bottom_right_control .= 
'<div class="show_number_bill">
    <span>Hiển thị: </span>
    <select class="form-control">
        <option value="5">5 lần thanh toán gần đây</option>
        <option value="10">10 lần thanh toán gần đây</option>
        <option value="15">15 lần thanh toán gần đây</option>
    </select>
</div>';

$list = new dataGrid('fin_id',$limit_financies);
$list->add('fin_id', 'Số phiếu','string',1);
$list->add('fin_updated_time', 'Ngày thanh toán');
$list->add('fin_money', 'Số tiền');
$list->add('fin_pay_type', 'Trả bằng');
$list->add('fin_note', 'Ghi chú');

// lấy ra tổng số hóa đơn nhap đã được trả 
$cout_financies_bill_out   = new db_query('SELECT *
                                        FROM '.$financies.$join_table.'
                                        WHERE 1 ' . $list->sqlSearch() .' AND fin_cat_id = 32'.$And_id);
$total = mysqli_num_rows($cout_financies_bill_out->result); unset($cout_financies_bill_out);
// kiểm tra có tồn tại request ajax biến limit
if($isAjaxRequest && $limit != 0){
    $total = $limit;
}
// list danh sách hóa đơn
$db_financies_bill_out   = new db_query('SELECT *
                                        FROM '.$financies.$join_table.'
                                        WHERE 1 ' . $list->sqlSearch() .' AND fin_cat_id = 32'.$And_id.'
                                        ORDER BY ' . $list->sqlSort() . ' fin_id
                                        ASC ' . $list->limit($total)
                                        );
if($isAjaxRequest && isset($_GET['fin_id']) && isset($_GET['sup_name'])){
    $fin_id = getValue('fin_id','int','GET',0);
    $sup_name = getValue('sup_name','str','GET','');
    $db_financies_bill_out   = new db_query('SELECT *
                                            FROM '.$financies.' 
                                            WHERE 1 
                                            AND fin_id = ' . $fin_id . ' 
                                            AND fin_username = \''.trim($sup_name).'\' 
                                            AND fin_reason_other = \''.trim('Công nợ nhà cung cấp').'\' 
                                            LIMIT 1'
                                            );
}
$total_row = mysqli_num_rows($db_financies_bill_out->result);  
$bottom_right_column            = '<div class="bottom_conten_right_column">';
$bottom_conten_right_column     = '';
$bottom_conten_right_column     .= $list->showHeader($total_row,'','id="table-listing-bot-right"');
$i = 0;
$total_all_pay_bill_out = 0;
while($data_financies_bill_out = mysqli_fetch_assoc($db_financies_bill_out->result)){
    $i++;
    if($data_financies_bill_out['fin_pay_type'] == PAY_TYPE_CASH){
        $fin_pay_type = 'Tiền mặt';
    }
    if($data_financies_bill_out['fin_pay_type'] == PAY_TYPE_CARD){
        $fin_pay_type = 'Thẻ';
    }
    $bottom_conten_right_column .= $list->start_tr($i,$data_financies_bill_out['fin_id'],'class="menu-normal record-item" ondblclick="detail_financies('.$data_financies_bill_out['fin_id'].')" data-record_id="'.$data_financies_bill_out['fin_id'].'"');
    $bottom_conten_right_column .= '<td class="center" width="100">'. format_codenumber($data_financies_bill_out['fin_id'],6,'') .'</td>';
    $bottom_conten_right_column .= '<td class="center" width="100">'. date('d/m/Y',$data_financies_bill_out['fin_updated_time']) .'</td>';
    $bottom_conten_right_column .= '<td class="text-right" width="100">'. number_format($data_financies_bill_out['fin_money']) .'</td>';
    $bottom_conten_right_column .= '<td class="center" width="100">'. $fin_pay_type .'</td>';
    $bottom_conten_right_column .= '<td>'. $data_financies_bill_out['fin_note'] .'</td>';
    $bottom_conten_right_column .= $list->end_tr();
    $total_all_pay_bill_out += $data_financies_bill_out['fin_money'];
}unset($db_financies_bill_out);
$bottom_conten_right_column     .= $list->showFooter();
$bottom_conten_right_column     .=
'<div class="total-pos">
    <div class="col-xs-6-lft col-xs-6-lft-l">
        <span>Tổng Cộng:</span><input class="ttl-hd" value="" readonly="readonly"/>
    </div>
    <div class="col-xs-6-rgh col-xs-6-lft-r">
        <input class="ttl-hd" value="'.number_format($total_all_pay_bill_out).' ' . DEFAULT_MONEY_UNIT . '" readonly="readonly"/>
    </div>
    <div class="clear"></div>
</div>';
$bottom_right_column .= $bottom_conten_right_column;
$bottom_right_column .= '</div>';


//end bottom right
if($isAjaxRequest){
    if(trim($position) == 'left'){
        echo $bottom_conten_left_column;
    }
    if(trim($position) == 'right'){
        echo $bottom_conten_right_column;
    }
    if(isset($_GET['fin_id']) && isset($_GET['cus_name'])){
        echo $bottom_conten_left_column;
    }
    if(isset($_GET['fin_id']) && isset($_GET['sup_name'])){
        echo $bottom_conten_right_column;
    }
    die;
}
$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);

$rainTpl->assign('bottom_left_control',$bottom_left_control);
$rainTpl->assign('bottom_right_control',$bottom_right_control);
$rainTpl->assign('bottom_left_column',$bottom_left_column);
$rainTpl->assign('bottom_right_column',$bottom_right_column);

$rainTpl->assign('total_left_top',$total_left_top);
$rainTpl->assign('total_right_top',$total_right_top);
$rainTpl->assign('total_bottom_left',$total_bottom_left);
$rainTpl->assign('total_bottom_right',$total_bottom_right);

$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);
$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('right_column_title',$right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('pay_libility');
?>
