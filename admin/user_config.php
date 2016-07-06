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
if(!$isAdmin) {
    redirect('index.php');
}

//ajax
$isAjaxRequest          =   !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
if($isAjaxRequest) {
    $action = getValue('action','str','POST','');
    switch($action) {
        case 'changeAgency':
            $age_id = getValue('age_id','int','POST',0);
            $db_age = new db_query('SELECT sed_id, sed_name
                                    FROM service_desks
                                    WHERE sed_agency_id = ' . $age_id);
            $list_service_desk = $db_age->resultArray();
            foreach($list_service_desk as $k=>$v) {
                ?>
                <option value="<?=$v['sed_id']?>"><?=$v['sed_name']?></option>
            <?
            }
            break;
    }
    die();
}

//xử lý
$action = getValue('action','str','POST','');
if($action == 'execute') {
    $myform = new generate_form();
    $myform->add('con_restaurant_name','con_restaurant_name',0,0,'');
    $myform->add('con_restaurant_address','con_restaurant_address',0,0,'');
    $myform->add('con_restaurant_phone','con_restaurant_phone',0,0,'');
    $myform->add('con_default_svdesk','con_default_svdesk',1,0,'');
    $myform->add('con_default_agency','con_default_agency',1,0,'');
    $myform->add('con_default_store','con_default_store',1,0,'');
    $myform->addTable('configurations');

    if(!$myform->checkdata()) {
        //kiểm tra xem acc đã được config chưa
        $db_check = new db_query('SELECT * FROM configurations WHERE con_admin_id = ' . $admin_id);
        if(mysqli_num_rows($db_check->result) < 1) {
            $myform->add('con_admin_id','admin_id',1,1,0);
            $db_insert = new db_execute($myform->generate_insert_SQL());
            unset($db_insert);
        }else {
            $db_update = new db_execute($myform->generate_update_SQL('con_admin_id',$admin_id));
            unset($db_update);
        }
        //redirect ve index
        redirect('index.php');
    }
}


$db_config = new db_query('SELECT *
                           FROM configurations
                           WHERE con_admin_id = ' . $admin_id);
$config_data = mysqli_fetch_assoc($db_config->result);
unset($db_config);
$db_age = new db_query('SELECT age_id, age_name
                        FROM agencies');
$list_agencies = array();
while($row = mysqli_fetch_assoc($db_age->result)) {
    if($config_data && $row['age_id'] == $config_data['con_default_agency']) {
        $row['selected'] = 'selected';
    }else{
        $row['selected'] = '';
    }
    $list_agencies[] = $row;
}
unset($db_age);

if(!$config_data){
    $db_sed = new db_query('SELECT sed_id, sed_name, age_name
                            FROM service_desks
                            LEFT JOIN agencies ON age_id = sed_agency_id
                            WHERE sed_agency_id = ' . $list_agencies[0]['age_id']);
}else{
    $db_sed = new db_query('SELECT sed_id, sed_name, age_name
                            FROM service_desks
                            LEFT JOIN agencies ON age_id = sed_agency_id
                            WHERE sed_agency_id = ' . $config_data['con_default_agency']);
}

$list_service_desk = array();
while($row = mysqli_fetch_assoc($db_sed->result)) {
    $row['sed_name'] = $row['age_name'] . ' - ' . $row['sed_name'];
    if($config_data && $row['sed_id'] == $config_data['con_default_svdesk']) {
        $row['selected'] = 'selected';
    }else{
        $row['selected'] = '';
    }
    $list_service_desk[] = $row;
}
unset($db_sed);


$db_store = new db_query('SELECT cat_id, cat_name FROM categories_multi WHERE cat_type = "stores"');
$list_store = array();
while($row = mysqli_fetch_assoc($db_store->result)) {
    if($config_data && $row['cat_id'] == $config_data['con_default_store']) {
        $row['selected'] = 'selected';
    }else{
        $row['selected'] = '';
    }
    $list_store[] = $row;
}
unset($db_store);


RainTpl::configure("base_url", null );
RainTpl::configure("tpl_dir", "resources/templates/" );
RainTpl::configure("cache_dir", "resources/caches/" );
RainTPL::configure("path_replace_list",array());

$rainTpl = new RainTPL();
$rainTpl->assign('list_agencies',$list_agencies);
$rainTpl->assign('list_service_desk',$list_service_desk);
$rainTpl->assign('list_store', $list_store);
$rainTpl->assign('config_data', $config_data);

$rainTpl->draw('user_config');