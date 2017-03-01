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


$action = getValue('action', 'str', 'REQUEST');
if ($action && is_callable($action)) {
	ob_clean();
	$action();
} else {
	var_dump($_REQUEST);
	http_response_code(404);
}