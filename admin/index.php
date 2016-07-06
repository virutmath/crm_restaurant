<?php
session_start();
error_reporting(E_ALL);
require_once("../functions/functions.php");
require_once("../classes/database.php");
require_once("../classes/rain.tpl.class.php");
require_once('resources/security/inc_constant.php');
require_once("resources/security/functions.php");
require_once("resources/security/functions_1.php");

checkLogged('login.php');
$admin_id 				=   getValue("user_id","int","SESSION");
$isAdmin                =   getValue('isAdmin', 'int', 'SESSION', 0);
$user_config            =   getValue('user_config','int','SESSION');
$user_group_id          =   getValue('user_group_id', 'int', 'SESSION', 0);



RainTpl::configure("base_url", null );
RainTpl::configure("tpl_dir", "resources/templates/" );
RainTpl::configure("cache_dir", "resources/caches/" );
RainTPL::configure("path_replace_list",array());

$rainTpl = new RainTPL();


//Lấy ra config nhà hàng
if($isAdmin) {
    $sql_configuration = 'SELECT *
                          FROM configurations
                          LEFT JOIN agencies ON age_id = con_default_agency
                          LEFT JOIN service_desks ON sed_id = con_default_svdesk
                          LEFT JOIN categories_multi ON cat_id = con_default_store
                          WHERE con_admin_id = '.$admin_id.' LIMIT 1';
}else{
    $sql_configuration = 'SELECT *
                          FROM configurations
                          LEFT JOIN agencies ON age_id = con_default_agency
                          LEFT JOIN service_desks ON sed_id = con_default_svdesk
                          LEFT JOIN categories_multi ON cat_id = con_default_store
                          LEFT JOIN admin_users ON adm_id
                          WHERE con_admin_id = '.$user_config.' LIMIT 1';
}
$db_con = new db_query($sql_configuration);
$configuration = mysqli_fetch_assoc($db_con->result);
unset($db_con);
$rainTpl->assign('configuration',$configuration);

//menu navigate
if($isAdmin){
    $db_nav = new db_query('SELECT *
                            FROM navigate_admin
                            LEFT JOIN modules ON nav_module_id = mod_id
                            ORDER BY nav_order ASC');
}else{
    $db_nav = new db_query('SELECT *
                            FROM navigate_admin
                            LEFT JOIN modules ON nav_module_id = mod_id
                            LEFT JOIN admin_group_role ON mod_id = module_id
                            WHERE group_id = '.$user_group_id);
}
$array_navigate = array();
while($row = mysqli_fetch_assoc($db_nav->result)){
    $array_navigate[] = $row;
}
$rainTpl->assign('array_navigate',$array_navigate);


//các đường dẫn tới function
//Quản lý người dùng
$link_manager_user = link_module_function('admin_users','index.php');
//Quản lý khu vực bàn ân
$link_manager_desk = link_module_function('desks','index.php');
//Quản lý nhà cung cấp
$link_manager_supplier = link_module_function('suppliers','index.php');
//Quản lý chi nhánh, cửa hàng
$link_manager_agencies = link_module_function('agencies','index.php');
//Quản lý thực đơn
$link_manager_menus = link_module_function('menus','index.php');
//Quản lý khách hàng
$link_manager_customers = link_module_function('customers','index.php');
//@uản lý nhân sự
$link_manager_users = link_module_function('users','index.php');
//Quản lý sản phẩm ( quản lý kho hàng)
$link_manager_products = link_module_function('products','index.php');
// quản lý kiểm kê chuyển kho
$link_inventory_transfer = link_module_function('inventory_transfer','index.php');
// nhập hàng vào kho
$link_import_stores = link_module_function('products','import.php');
//quan ly danh sach kho
$link_listing_stores = link_module_function('stores','index.php');
// quan ly danh muc phieu thu chi
$link_cat_fins = link_module_function('categories_financial','index.php');
// quan ly tai chinh - danh sách phiếu thu chi (bao gồm thêm mới sửa xóa, in phiếu)
$link_manager_fins = link_module_function('financial','index.php');
$link_home = link_module_function('home','index.php');
$link_settings = link_module_function('settings','index.php');
//chiến dịch khuyến mãi
$link_promotions  = link_module_function('promotions','index.php');


$rainTpl->assign('link_manager_user',$link_manager_user);
$rainTpl->assign('link_manager_desk',$link_manager_desk);
$rainTpl->assign('link_manager_supplier',$link_manager_supplier);
$rainTpl->assign('link_manager_agencies',$link_manager_agencies);
$rainTpl->assign('link_manager_menus',$link_manager_menus);
$rainTpl->assign('link_manager_products',$link_manager_products);
$rainTpl->assign('link_inventory_transfer',$link_inventory_transfer);
$rainTpl->assign('link_import_stores',$link_import_stores);
$rainTpl->assign('link_manager_customers',$link_manager_customers);
$rainTpl->assign('link_manager_users',$link_manager_users);
$rainTpl->assign('link_listing_stores',$link_listing_stores);
$rainTpl->assign('link_cat_fins',$link_cat_fins);
$rainTpl->assign('link_manager_fins',$link_manager_fins);
$rainTpl->assign('link_home',$link_home);
$rainTpl->assign('link_settings',$link_settings);
$rainTpl->assign('link_promotions',$link_promotions);


//Thanh điều hướng - navigate admin
if($isAdmin){
    $db_nav = new db_query('SELECT *
                            FROM navigate_admin
                            LEFT JOIN modules ON nav_module_id = mod_id
                            ORDER BY nav_order ASC');
}else{
    $db_nav = new db_query('SELECT *
                            FROM navigate_admin
                            LEFT JOIN modules ON nav_module_id = mod_id
                            LEFT JOIN admin_group_role ON mod_id = module_id
                            WHERE group_id = '.$user_group_id);
}
$list_nav = array();
while ($row = mysqli_fetch_assoc($db_nav->result)) {
    if($row['mod_directory'] == 'home') {
        $row['active'] = 1;
    }else{
        $row['active'] = 0;
    }
    $row['link'] = link_module_function($row['mod_directory'], 'index.php');
    $row['label'] = $row['nav_name'];
    $list_nav[] = $row;
}
$rainTpl->assign('list_nav', $list_nav);

//thông tin người đăng nhập
$user_name = getValue('username','str','SESSION','bạn');
$user_note = getValue('user_note','str','SESSION','');
$rainTpl->assign('user_name',$user_name);
$rainTpl->assign('user_note',$user_note);
$rainTpl->assign('admin_id',$admin_id);

$rainTpl->draw('admin_index');
