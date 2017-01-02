<?php
require_once 'inc_security.php';

function getListEmployee() {
	$sql = 'select * from users';
	$db = new db_query($sql);
	$list = $db->resultArray();
	echo json_encode(['list'=>$list]);
}

$action = getValue('action','str','REQUEST');
if($action && is_callable($action)) {
	$action();
}else{
	http_response_code(404);
}