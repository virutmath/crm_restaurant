<?
require_once 'inc_security.php';
$desk_id = getValue('desk_id','int','POST',0);
$array_return = array();
if($desk_id == 0){
    exit();
}
$start_time = '';
$cus_id = 0;
$cus_name = '';
$use_id = 0;
$use_name = '';
$list_menu = '';
$cud_note = '';
$start_time_int = 0;
// lay ra vi tri ban
$db_desk = new db_query("SELECT * FROM desks
                        LEFT JOIN sections ON des_sec_id = sec_id
                        WHERE des_id = " . intval($desk_id));
$data_pos_desk = mysqli_fetch_assoc($db_desk->result);unset($db_desk);
$desk_name = $data_pos_desk['des_name'] . ' - ' . $data_pos_desk['sec_name'];
$db_current_desk = new db_query("SELECT * FROM current_desk WHERE cud_desk_id = " . intval($desk_id));
$data_current_desk = mysqli_fetch_assoc($db_current_desk->result);unset($db_current_desk);
// thoi gian khoi tao ban
$start_time_int = $data_current_desk['cud_start_time'];
$start_time = date("d/m/Y h:i", $start_time_int);
// ghi chu
$cud_note = $data_current_desk['cud_note'];
// customer id
$cus_id = $data_current_desk['cud_customer_id'];
$db_customer = new db_query("SELECT * FROM customers WHERE cus_id = " . intval($cus_id));
$data_customer = mysqli_fetch_assoc($db_customer->result);unset($db_customer);
// customer name
$cus_name = $data_customer['cus_name'];
// nhan vien id
$use_id = $data_current_desk['cud_staff_id'];
$db_users = new db_query("SELECT * FROM users WHERE use_id = " . intval($use_id));
$data_users = mysqli_fetch_assoc($db_users->result);unset($db_users);
// ten nhan vien
$use_name = $data_users['use_name'];
// lay ra cac mon an da goi
$db_current_desk_menu = new db_query("SELECT * FROM current_desk_menu 
                                    LEFT JOIN menus ON cdm_menu_id = men_id
                                    WHERE cdm_desk_id = " . intval($desk_id));
$total_price    = 0;
while($data_current_desk_menu = mysqli_fetch_assoc($db_current_desk_menu->result)){
    $list_menu .= 
        '<li id="menu_'.$data_current_desk_menu['cdm_menu_id'].'">
            <div class="name-price menu-active" onclick=deleteCancelMenu(this);>
                <div class="menu-list-name" data-menu_id="'.$data_current_desk_menu['cdm_menu_id'].'">'.$data_current_desk_menu['men_name'].'</div>
                <span class="menu-list-price" data-price_menu="'.$data_current_desk_menu['cdm_price'].'">'.number_format($data_current_desk_menu['cdm_price']).'</span>
                <span class="number-menu" data-number_menu="'.$data_current_desk_menu['cdm_number'].'">'.$data_current_desk_menu['cdm_number'].'</span>
                <div class="clear"></div>
            </div>
            <div class="number-total" id="box-menu-'.$data_current_desk_menu['cdm_menu_id'].'" style="display: none;">
                <div class="total-price">
                     <div class="bill-of-sale" onclick="delMenu(this);">
                        <span class="delete">Xóa món</span>
                    </div>
                    <div class="bill-of-sale" onclick="cancelDel(this);">
                        <span class="cancel">Cancel</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </li>';
    $total_price += $data_current_desk_menu['cdm_price'] * $data_current_desk_menu['cdm_number']; 
}
if($total_price != 0){
    $list_menu .= 
    '<li class="total-price-bill">
        <div class="name-price">
            <div class="menu-list-name">Tổng hóa đơn : </div>
            <span class="menu-list-price">'.number_format($total_price) . '</span>
            <div class="clear"></div>
        </div>
    </li>';
}
unset($db_current_desk_menu);

$rainTpl = new RainTPL();
$rainTpl->assign('desk_id',$desk_id);
$rainTpl->assign('desk_name',$desk_name);
$rainTpl->assign('start_time_int',$start_time_int);
$rainTpl->assign('cus_id',$cus_id);
$rainTpl->assign('cud_note',$cud_note);
$rainTpl->assign('start_time',$start_time);
$rainTpl->assign('cus_name',$cus_name);
$rainTpl->assign('use_id',$use_id);
$rainTpl->assign('use_name',$use_name);
$rainTpl->assign('list_menu',$list_menu);
$rainTpl->draw('mobile_detail_desk');