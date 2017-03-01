<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'inc_constant.php';
require_once '../../../vendor/autoload.php';
if(DEVELOPER_ENVIRONMENT) {
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}
require_once('../../../classes/database.php');
require_once('../../../classes/generate_form.php');
require_once("../../../classes/simple_html_dom.php");
require_once('../../../classes/rain.tpl.class.php');
require_once('../../../classes/PHPExcel.php');
require_once('../../../classes/kint/Kint.class.php');
require_once('../../../classes/Patterns/autoload.php');
require_once('../../../functions/functions.php');
require_once('../../../functions/rewrite_functions.php');
require_once('../../../functions/form.php');
require_once('../../../functions/date_functions.php');
require_once("../../../functions/file_functions.php");

require_once 'eloquent.database.php';
require_once('functions.php');
require_once('grid.php');
require_once('AbstractAjax.php');
require_once('AjaxCommon.php');
require_once('functions_1.php');
require_once('inc_config_security.php');
RainTpl::configure("base_url", null );
RainTpl::configure("tpl_dir", "../../resources/templates/" );
RainTpl::configure("cache_dir", "../../resources/caches/" );
RainTPL::configure("path_replace_list",array());
RainTPL::configure('tpl_constants',array(
    'DEVELOPER_ENVIRONMENT' => DEVELOPER_ENVIRONMENT
));

$admin_id 				=   getValue("user_id","int","SESSION");
$user_config            =   getValue('user_config','int','SESSION');
$isAdmin	            =	getValue("isAdmin", "int", "SESSION", 0);
$isSuperAdmin           =   getValue('isSuperAdmin', 'int', 'SESSION', 0);
$isAjaxRequest          =   !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
//Lấy ra config nhà hàng
if($isAdmin) {
    $sql_configuration = 'SELECT * FROM configurations WHERE con_admin_id = '.$admin_id.' LIMIT 1';
}else{
    $sql_configuration = 'SELECT *
                          FROM configurations
                          LEFT JOIN admin_users ON adm_id
                          WHERE con_admin_id = '.$user_config.' LIMIT 1';
}
$db_con = new db_query($sql_configuration);
$configuration = mysqli_fetch_assoc($db_con->result);
unset($db_con);
//nếu chưa có configuration thì chuyển sang phần user_config
if(!$configuration) {
	redirect('/admin/user_config.php');
}
//lấy danh sách bàn trong cửa hàng hiện tại
$db_desk = new db_query('SELECT *
                         FROM desks
                         LEFT JOIN sections ON sec_id = des_sec_id
                         LEFT JOIN service_desks ON sed_id = sec_service_desk
                         WHERE sed_agency_id = ' . $configuration['con_default_agency']);
$_list_desk = $db_desk->resultArray();unset($db_desk);
//lấy danh sách khu vực trong cửa hàng hiện tại
$db_section = new db_query('SELECT *
                            FROM sections
                            LEFT JOIN service_desks ON sed_id = sec_service_desk
                            WHERE sed_agency_id = ' . $configuration['con_default_agency']);
$_list_section = $db_section->resultArray();unset($db_section);
$load_header = $css_global.$js_global;
$load_header .= '<title>Hệ thống quản lý CMS</title>';
?>
