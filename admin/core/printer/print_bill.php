<?
require_once 'inc_security.php';
//lấy desk_id để in
$action = getValue('action','str','POST','');
//if($action != 'PRINT_SUCCESS_BILL') {
//    die();
//}
$bill_id = getValue('billID', 'int', 'GET', 0);

//khai báo các biến hiển thị


$pay_money      = 0;
$total          = 0;
$admin_name     = '';

// lấy thông tin để in từ bảng bill_in
$db_bill_in = new db_query('SELECT * FROM bill_in WHERE bii_id = '.$bill_id.'');
$row_bill_in = mysqli_fetch_assoc($db_bill_in->result);
$desk_vat       = $row_bill_in['bii_vat'];
$customer_id    = $row_bill_in['bii_customer_id'];
$desk_extra     = $row_bill_in['bii_extra_fee'];
$desk_discount  = $row_bill_in['bii_discount'];
$bill_cus_id    = $row_bill_in['bii_customer_id'];
$start_time     = date(' h:i d/m/Y',$row_bill_in['bii_start_time']);
unset($db_bill_in);

// lấy ra thông tin vị trí bàn ăn
$db_section = new db_query('SELECT * FROM desks WHERE des_id = '.$row_bill_in['bii_desk_id'].'');
$row_sec = mysqli_fetch_assoc($db_section->result);

$db_location = new db_query('SELECT * FROM sections WHERE sec_id ='.$row_sec['des_sec_id'].'');
$row_location = mysqli_fetch_assoc($db_location->result);
$location = $row_sec['des_name'].' - '.$row_location['sec_name'];
unset($db_location); unset($db_section);


// tạo mảng hiển thị tên menu
$array_menu = '';
$db_menu = new db_query('SELECT * FROM menus');
while($row = mysqli_fetch_assoc($db_menu->result)){
    $array_menu[$row['men_id']] = $row['men_name'];
}unset($db_menu);


/* Danh sách menu trong hóa đơn*/
    $list_menu = '';
    $array_total = array();
    $db_query_menu = new db_query('SELECT * FROM bill_in_detail WHERE bid_bill_id = '.$bill_id.'');
    $i=0;
    while($row_menu = mysqli_fetch_assoc($db_query_menu->result)){
        $i++;
        // giảm giá theo thực đơn
        $discount_menu = $row_menu['bid_menu_discount']; /* kiểm tra giá trị giảm giá theo thực đơn*/
        if($discount_menu == 0){
            $price_menu = $row_menu['bid_menu_price'];
        } else {
            $price_menu = $row_menu['bid_menu_price'] - ($discount_menu * $row_menu['bid_menu_price'])/100;
        }
        $list_menu .= '<tbody>
                        <tr class="menu-normal record-item">
                            <td width="15" class="center">
                                <span style="color:#142E62; font-weight:bold">'.$i.'</span>
                            </td>
                            <td class="text-left">'.$array_menu[$row_menu['bid_menu_id']].'</td>
                            <td class="center">'.number_format($row_menu['bid_menu_number']).'</td>
                            <td class="text-right">'.number_format($price_menu).'</td>
                            <td class="text-right">'.number_format($price_menu * $row_menu['bid_menu_number']).'</td>
                        </tr>
                    </tbody>';
        $array_total[$i] = ($row_menu['bid_menu_number'] * $price_menu);

    }unset($db_query_menu);
    $total = array_sum($array_total);

// số tiền cần phải thanh toán
$vat_value      = $desk_vat/100;
$pay_money = ($total - ($total * $desk_discount / 100) + ($total * $desk_extra/100)) * (1 + $vat_value);
// phi phí theo giá trị tiền
$extra_fee = $total* $desk_extra/100;
// giảm giá theo giá trị tiền
$discount_money = $total * $desk_discount/100;
// VAT theo giá trị tiền
$vat_money = $total * $desk_vat/100;


global $admin_id;
// query admin
$db_admin = new db_query('SELECT * FROM admin_users WHERE adm_id = '.$admin_id.'');
$row_admin = mysqli_fetch_assoc($db_admin->result);
    $admin_name = $row_admin['adm_name'];
 unset($db_admin);

// lấy tên khách hàng
$db_customer = new db_query('SELECT * FROM customers WHERE cus_id = '.$bill_cus_id.'');
$row_customer = mysqli_fetch_assoc($db_customer->result);
    $customer_name = $row_customer['cus_name'];
 unset($db_customer);

if($bill_cus_id == 0){
    $customer_name  = 'Khách lẻ';
}

// thong tin nha hang
$db_query_res   = new db_query('SELECT *FROM configurations WHERE con_id = 1');
$row_con  = mysqli_fetch_assoc($db_query_res->result);
$res_name       = $row_con['con_restaurant_name'];
$res_address    = $row_con['con_restaurant_address'];
$res_logo       = get_picture_path($row_con['con_restaurant_image']);
$res_phone      = $row_con['con_restaurant_phone'];
unset($db_query_res);



$rainTpl = new RainTPL();
add_more_css('css/custom.css',$load_header);
add_more_css('css/custom.css',$load_header,'print');
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('res_name',$res_name);
$rainTpl->assign('res_address',$res_address);
$rainTpl->assign('res_logo',$res_logo);
$rainTpl->assign('res_phone',$res_phone);
$rainTpl->assign('list_menu',$list_menu);
$rainTpl->assign('start_time',$start_time);
$rainTpl->assign('location',$location);
$rainTpl->assign('admin_name',$admin_name);
$rainTpl->assign('customer_name',$customer_name);
$rainTpl->assign('total',number_format($total));
$rainTpl->assign('desk_discount',$desk_discount);
$rainTpl->assign('desk_vat',$desk_vat);
$rainTpl->assign('desk_extra',$desk_extra);
$rainTpl->assign('extra_fee',number_format($extra_fee));
$rainTpl->assign('discount_money',number_format($discount_money));
$rainTpl->assign('vat_money',number_format($vat_money));
$rainTpl->assign('bill_id',format_codenumber($bill_id,6,PREFIX_BILL_CODE));
$rainTpl->assign('pay_money',number_format($pay_money));
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));



$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('print');