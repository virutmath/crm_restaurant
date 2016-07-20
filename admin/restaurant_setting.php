<?php
session_start();
error_reporting(E_ALL);
require_once("../functions/functions.php");
require_once("../classes/generate_form.php");
require_once("../classes/database.php");
require_once("../classes/rain.tpl.class.php");
require_once('resources/security/inc_constant.php');
require_once("resources/security/functions.php");
require_once("resources/security/functions_1.php");

checkLogged('login.php');
$admin_id 				=   getValue("user_id","int","SESSION");
$isAdmin	            =	getValue("isAdmin", "int", "SESSION", 0);
$isSuperAdmin           =   getValue('isSuperAdmin', 'int', 'SESSION', 0);
if(!$isSuperAdmin) {
	//neu khong fai tai khoan admin lan dau dang nhap thi cho ve trang login
	redirect('login.php');
}

$action = getValue('action','str','POST','');
if($action == 'execute') {
	//cai dat tai khoan quan tri va chi nhanh
	$new_password = getValue('change_password','str','POST','');
	if(!$new_password) {
		redirect('restaurant_setting.php');
	}
	//update password
	$update_pass_sql = 'UPDATE admin_users SET adm_password = "'.md5($new_password).'" WHERE adm_id = ' . $admin_id;
	$db_update = new db_execute($update_pass_sql);
	unset($db_update);
	//insert agency for initialize
	$age_name = getValue('agency_name','str','POST','');
	$age_address = getValue('agency_address','str','POST','');
	$age_phone = getValue('agency_phone','str','POST','');
	if(!($age_name&&$age_address&&$age_phone)) {
		redirect('restaurant_setting.php');
	}
	$insert_agency_sql = 'INSERT INTO agencies (age_name,age_address,age_phone) 
						  VALUES("'.$age_name.'","'.$age_address.'","'.$age_phone.'")';
	$db_insert = new db_execute_return();
	$agency_id = $db_insert->db_execute($insert_agency_sql);unset($db_insert);
	//insert service desk
	$svd_name = getValue('svd_name','str','POST','');
	if(!$svd_name) $svd_name = 'Quầy thu ngân';
	$sql_insert_svd = 'INSERT INTO service_desks(sed_name,sed_agency_id)
						VALUES("'.$svd_name.'",'.$agency_id.')';
	$db_insert = new db_execute($sql_insert_svd);unset($db_insert);
	if($agency_id) {
		redirect('user_config.php');
	}else{
		echo 'Đã có lỗi xảy ra, vui lòng thử lại sau';
	}
}



RainTpl::configure("base_url", null );
RainTpl::configure("tpl_dir", "resources/templates/" );
RainTpl::configure("cache_dir", "resources/caches/" );
RainTPL::configure("path_replace_list",array());

$rainTpl = new RainTPL();

$rainTpl->draw('restaurant_setting');