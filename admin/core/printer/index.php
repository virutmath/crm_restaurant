<?
require_once 'inc_security.php';
//lấy desk_id để in
$desk_id        = getValue('desk_id','int','GET',0);
// lấy về action để thực hiện nếu là action = PRINT_BEFORE thì lấy các thông tin gửi từ home
$desk_vat       = getValue('VAT_value','int','GET',0);
$desk_extra     = getValue('extra_fee','int','GET',0);
$desk_discount  = getValue('discount_value','int','GET',0);
$action         = getValue('action','str','GET','');

//khai báo các biến hiển thị
$res_name       = '';
$res_address    = '';
$res_phone      = '';
$start_time     = '';
$location       = '';
$admin_name     = '';
$pay_money      = 0;
$total          = 0;
$res_logo       = '';
$bill_id        = '';
$customer_name = 'Khách lẻ';

// thong tin nha hang

$res_name       = $configuration['con_restaurant_name'];
$res_address    = $configuration['con_restaurant_address'];
$res_logo       = get_picture_path($configuration['con_restaurant_image']);
$res_phone      = $configuration['con_restaurant_phone'];
unset($db_query_res);


// query thông tin bàn ăn
$db_query_desk  = new db_query('SELECT * FROM current_desk WHERE cud_desk_id = '.$desk_id.'');
$row_desk = mysqli_fetch_assoc($db_query_desk->result);

$start_time     = date(' h:i d/m/Y',$row_desk['cud_start_time']);
$desk_discount  = $row_desk['cud_customer_discount'];
$desk_vat       = $row_desk['cud_vat'];
$desk_extra     = $row_desk['cud_extra_fee'];
$customer_id    = $row_desk['cud_customer_id'];
unset($db_query_desk);

//lấy ra tên khách hàng
$db_customer = new db_query('SELECT cus_name FROM customers WHERE cus_id = '.$customer_id.'');
$row_customer = mysqli_fetch_assoc($db_customer->result);
$customer_name = $row_customer['cus_name'];

// tao mảng hiển thị tên menu
$array_menu = '';
$db_menu = new db_query('SELECT * FROM menus');
while($row = mysqli_fetch_assoc($db_menu->result)){
    $array_menu[$row['men_id']] = $row['men_name'];
}unset($db_menu);


// query bàn ăn
$db_section = new db_query('SELECT * FROM desks WHERE des_id = '.$desk_id.'');
$row_sec = mysqli_fetch_assoc($db_section->result);
//query vi tri
$db_location = new db_query('SELECT * FROM sections WHERE sec_id ='.$row_sec['des_sec_id'].'');
$row_location = mysqli_fetch_assoc($db_location->result);

$location = $row_sec['des_name'].' - '.$row_location['sec_name'];

 unset($db_location);unset($db_section);



$list_menu = '';
$array_total = array();
$db_query_menu = new db_query('SELECT * FROM current_desk_menu WHERE cdm_desk_id = '.$desk_id.'');
$i=0;
while($row_menu = mysqli_fetch_assoc($db_query_menu->result)){
    $i++;
    // giảm giá theo thực đơn
    $discount_menu = $row_menu['cdm_menu_discount']; /* kiểm tra giá trị giảm giá theo thực đơn*/
    if($discount_menu == 0){
         $price_menu = $row_menu['cdm_price'];
    } else {
         $price_menu = $row_menu['cdm_price'] - ($discount_menu * $row_menu['cdm_price'])/100;
    }
    $list_menu .= '<tbody>
                        <tr class="menu-normal record-item">
                            <td width="15" class="center">
                                <span style="color:#142E62; font-weight:bold">'.$i.'</span>
                            </td>
                            <td class="text-left">'.$array_menu[$row_menu['cdm_menu_id']].'</td>
                            <td class="center">'.number_format($row_menu['cdm_number']).'</td>
                            <td class="text-right">'.number_format($price_menu).'</td>
                            <td class="text-right">'.number_format($price_menu * $row_menu['cdm_number']).'</td>
                        </tr>
                    </tbody>';
    $array_total[$i] = ($row_menu['cdm_number'] * $price_menu);

}unset($db_query_menu);
$total = array_sum($array_total);


global $admin_id;
// query admin
$db_admin = new db_query('SELECT * FROM admin_users WHERE adm_id = '.$admin_id.'');
$row_admin = mysqli_fetch_assoc($db_admin->result);
$admin_name = $row_admin['adm_name'];
unset($db_admin);
// số tiền cần phải thanh toán

$vat_value      = $desk_vat/100;
$pay_money = ($total - ($total * $desk_discount / 100) + ($total * $desk_extra/100)) * (1 + $vat_value);
// phi phí theo giá trị tiền
$extra_fee = $total* $desk_extra/100;
// giảm giá theo giá trị tiền
$discount_money = $total * $desk_discount/100;
// VAT theo giá trị tiền
$vat_money = $total * $desk_vat/100;
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
$rainTpl->assign('bill_id',$bill_id);
$rainTpl->assign('total',number_format($total));
$rainTpl->assign('desk_discount',$desk_discount);
$rainTpl->assign('desk_vat',$desk_vat);
$rainTpl->assign('desk_extra',$desk_extra);
$rainTpl->assign('extra_fee',number_format($extra_fee));
$rainTpl->assign('extra_fee',number_format($extra_fee));
$rainTpl->assign('discount_money',number_format($discount_money));
$rainTpl->assign('vat_money',number_format($vat_money));
$rainTpl->assign('pay_money',number_format($pay_money));
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));



$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('print');