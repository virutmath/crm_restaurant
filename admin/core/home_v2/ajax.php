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
	$id = getValue('id');
	$menus = Current_Desk_Menu::getDetail($id);
	echo $menus->toJson();
	die();
}

function getMenus()
{
	$menus = DB::table('menus')->get();
	foreach ($menus as $menu) {
		$menu->image_url = $menu->men_image ? get_picture_path($menu->men_image) : '/admin/resources/img/food-trays.png';
	}
	echo $menus->toJson();
}

function addDish()
{
	$desk_id = getValue('desk','int','POST',0);
	$menu_id = getValue('menu','int','POST',0);
	if(!$desk_id || !$menu_id) {
		http_response_code(400);
		echo json_encode(['error'=>1,'Dữ liệu bàn và thực đơn không hợp lệ']);
		die();
	}
	//TODO check data xem thực đơn và bàn có hợp lệ ko?
	//check xem bàn đã được mở chưa => nếu chưa mở thì mở bàn
	$detail = Current_Desk::getDetail($desk_id);
	if(!$detail) {
		//mở bàn
		$open = Current_Desk::open($desk_id);
		if(!$open) {
			//lỗi
		}
	}
	//lấy ra thông tin của menu
	$menu = Menu::getDetail($menu_id);
	//add menu vào bàn
	//TODO làm thêm khai báo giá tự nhập + tính năng lên thực đơn setup có sẵn
	//nếu chưa có thực đơn này trong bàn thì add vào
	$check_exists = Current_Desk_Menu::checkMenuExist($desk_id,$menu->men_id);
	if(!$check_exists) {
		$result = Current_Desk_Menu::addMenu($desk_id,$menu,1);
	}else{
		$result = Current_Desk_Menu::increaseDish($desk_id,$menu->men_id);
	}
	if($result) {
		//lấy ra chi tiết bàn để trả về view
		$menu = Current_Desk_Menu::getDetail($desk_id);
		echo $menu->toJson();
		die();
	}else {
		http_response_code(400);
		echo json_encode(['error'=>1,'msg'=>'Lỗi cập nhật dữ liệu']);
		die();
	}
}

$action = getValue('action', 'str', 'REQUEST');
if ($action && is_callable($action)) {
	ob_clean();
	$action();
} else {
	var_dump($_REQUEST);
	http_response_code(404);
}