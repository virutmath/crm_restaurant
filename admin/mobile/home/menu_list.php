<?
require_once 'inc_security.php';
$desk_id = getValue('desk_id','int');
$numberMenu = getValue('numberMenu','int','POST',0);
$priceMenu  = getValue('priceMenu','str','POST',0);
$menuId = getValue('menuId','int','POST',0);
//change number menu
if($isAjaxRequest && $menuId != 0 && $numberMenu != 0 && $priceMenu != ''){
    $db_price_menu  = new db_query("SELECT * FROM menus WHERE men_id = " . intval($menuId));
    $data_price = mysqli_fetch_assoc($db_price_menu->result);unset($db_price_menu);
    echo number_format(intval($numberMenu) * intval($data_price[$priceMenu])) . ' ' . DEFAULT_MONEY_UNIT;
    die;
}
check_desk_exist($desk_id);
if (!$desk_id)
{
    return;
}
// kiem tra xem id ban co ton tai hay khong
$db_count_desk = new db_count("SELECT count(*) as count FROM current_desk
                                WHERE cud_desk_id = " . intval($desk_id));
if ( $db_count_desk->total < 1 )
{
    return;
}unset($db_count_desk);
// lay ra vi tri ban 
$db_position_section = new db_query("SELECT * FROM desks
                                    LEFT JOIN sections ON des_sec_id = sec_id
                                    WHERE des_id = " . intval($desk_id));
$data_pos_sec       = mysqli_fetch_assoc($db_position_section->result);unset($db_position_section);
if(!$data_pos_sec) 
{
    return;   
}
$desk_name = $data_pos_sec['des_name'] . ' - ' . $data_pos_sec['sec_name'];
// lay  ra danh sach khach hang
$db_customer = new db_query('SELECT * FROM customers ORDER BY cus_id ASC');
$list_cus = $db_customer->resultArray();
unset($db_customer);
// lay ra danh sach nhan vien
$db_user = new db_query('SELECT * FROM users ORDER BY use_id ASC');
$list_use = $db_user->resultArray();
unset($db_user);
//lay ra gio vao ban
$db_current_desk = new db_query("SELECT cud_start_time FROM current_desk
                                WHERE cud_desk_id = " . intval($desk_id));
$data_current_desk = mysqli_fetch_assoc($db_current_desk->result);unset($db_current_desk);
$start_time = date('d/m/Y h:i',$data_current_desk['cud_start_time']);
//danh sach thuc don
$db_categories_menus = new db_query('SELECT * FROM categories_multi 
                                    WHERE cat_type = "' . MENU_CAT_TYPE .'"');
$list_menu = '';
while($data_cat_menu = mysqli_fetch_assoc($db_categories_menus->result)){ 
    // danh sach cat_menu
    $list_menu .= 
    '<li id="cat-menu-'.$data_cat_menu['cat_id'].'">
        <div class="name-price cat-menu">
            <div class="menu-list-name">'.$data_cat_menu['cat_name'].'</div>
            <i class="fa fa-plus"></i>
            <div class="clear"></div>
        </div>
        <ul class="child-menu">';
        $db_menus   = new db_query('SELECT * FROM menus 
                                    WHERE men_cat_id = ' . $data_cat_menu['cat_id'] .'');
        while ( $data_menu = mysqli_fetch_assoc ( $db_menus->result ) )
        {
            $list_menu .=
            '<li id="menu-'.$data_menu['men_id'].'">
                <div class="name-price">
                    <div class="menu-list-name" data-menu_id="'.$data_menu['men_id'].'">'.$data_menu['men_name'].'</div>
                    <i class="fa fa-angle-right"></i>
                    <span class="menu-list-price" data-price_menu="'.$data_menu['men_price'].'">'.number_format($data_menu['men_price']).'</span>
                    <div class="clear"></div>
                </div>
                <div class="number-total" id="box-menu-'.$data_menu['men_id'].'">
                    <form action="" method="">
                        <div class="bill-of-sale">
                            Số lượng: 
                            <select class="number select text-center">';
                                for($i=1; $i <= 100; $i++){$list_menu .= '<option value="'.$i.'">'.$i.'</option>';}
            $list_menu .= '</select>
                        </div>
                        <div class="bill-of-sale">
                            Chọn giá: 
                            <select class="price-type select text-center">
                                <option value="men_price">'.number_format($data_menu['men_price']).'</option>
                                <option value="men_price1">'.number_format($data_menu['men_price1']).'</option>
                                <option value="men_price2">'.number_format($data_menu['men_price2']).'</option>
                            </select>
                        </div>
                        <div class="clear save-cancel">
                            <span class="total-price">Tổng giá: <span>'.number_format($data_menu['men_price']).' '.DEFAULT_MONEY_UNIT.'</span></span>
                            <div class="bill-of-sale">
                                <span class="add" data-menu_id="'.$data_menu['men_id'].'">Thêm vào hóa đơn</span>
                            </div>
                            <div class="bill-of-sale">
                                <span class="cancel">Cancel</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </li>';
        }unset($db_menus);
    $list_menu .= 
        '</ul>
    </li>';
}unset($db_categories_menus);
//
$rainTpl = new RainTPL();
add_more_css('style.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('desk_id',$desk_id);
$rainTpl->assign('desk_name',$desk_name);
$rainTpl->assign('list_cus',$list_cus);
$rainTpl->assign('start_time',$start_time);
$rainTpl->assign('list_use',$list_use);
$rainTpl->assign('list_menu',$list_menu);
$custom_script = file_get_contents('script_list_menu.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mobile_list_menu'); 