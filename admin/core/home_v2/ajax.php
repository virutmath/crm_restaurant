<?php
require_once 'inc_security.php';
use Illuminate\Database\Capsule\Manager as DB;

function getDesks()
{
	$arrayReturn = [];
	$sections = DB::table('sections')->get();
	$arrayReturn['sections'] = $sections->toArray();
	$desks = DB::table('desks')
		->join('sections', 'des_sec_id', '=', 'sec_id')
		->get();
	//xử lý group by
	$desks = $desks->groupBy('sec_id');
	$arrayReturn['desks'] = $desks->toArray();
	echo json_encode($arrayReturn);
	die();
}

function getDeskDetail()
{
	$arrayReturn = [];
	$id = getValue('id');
	$desk = DB::table('current_desk')->where('cud_desk_id', $id)->first();
	$arrayReturn['desk'] = $desk;
	$menus = DB::table('current_desk_menu')
		->join('menus', 'men_id','=','cdm_menu_id')
		->where('cdm_desk_id', $id)
		->get();
	echo json_encode($menus);
	die();
}


$action = getValue('action', 'str', 'REQUEST');
if ($action && is_callable($action)) {
	ob_clean();
	$action();
} else {
	var_dump($_REQUEST);
	http_response_code(404);
}