<?php
require_once 'inc_security.php';
use Illuminate\Database\Capsule\Manager as DB;
class Home_v2 {
	protected function ajaxResponse($content) {
		if(is_string($content)) {
			echo $content;die();
		}else{
			echo json_encode($content);
			die();
		}
	}

	protected function errorResponse($msg = null,$error_code = null, $error = null) {
		http_response_code($error_code ?: 400);
		$msg = $msg ?: 'Dữ liệu không hợp lệ';
		$error = $error ?: 1;
		echo json_encode(['error' => $error, 'msg'=>$msg]);
		die();
	}
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
		$this->ajaxResponse($arrayReturn);
	}

	function getDeskDetail()
	{
		$id = getValue('id');
		$menus = Current_Desk_Menu::getDetail($id);
		$this->ajaxResponse($menus->toJson());
	}

	function getMenus()
	{
		$menus = DB::table('menus')->get();
		foreach ($menus as $menu) {
			$menu->image_url = $menu->men_image ? get_picture_path($menu->men_image) : '/admin/resources/img/food-trays.png';
		}
		$this->ajaxResponse($menus->toJson());
	}

	function addDish()
	{
		$desk_id = getValue('desk', 'int', 'POST', 0);
		$menu_id = getValue('menu', 'int', 'POST', 0);
		$number = getValue('number','int','POST',1);
		if (!$desk_id || !$menu_id) {
			$this->errorResponse('Dữ liệu bàn và thực đơn không hợp lệ');
		}
		//TODO check data xem thực đơn và bàn có hợp lệ ko?
		//check xem bàn đã được mở chưa => nếu chưa mở thì mở bàn
		$detail = Current_Desk::getDetail($desk_id);
		if (!$detail) {
			//mở bàn
			$open = Current_Desk::open($desk_id);
			if (!$open) {
				//lỗi
				$this->errorResponse('Đã có lỗi xảy ra, không thể mở bàn này');
			}
		}
		//lấy ra thông tin của menu
		$menu = Menu::getDetail($menu_id);
		//add menu vào bàn
		//TODO làm thêm khai báo giá tự nhập + tính năng lên thực đơn setup có sẵn
		//nếu chưa có thực đơn này trong bàn thì add vào
		$check_exists = Current_Desk_Menu::checkMenuExist($desk_id, $menu->men_id);
		if (!$check_exists) {
			$result = Current_Desk_Menu::addMenu($desk_id, $menu, $number);
		} else {
			$result = Current_Desk_Menu::increaseDish($desk_id, $menu->men_id,$number);
		}
		if ($result) {
			//lấy ra chi tiết bàn để trả về view
			$menu = Current_Desk_Menu::getDetail($desk_id);
			$this->ajaxResponse($menu->toJson());
		} else {
			$this->errorResponse('Lỗi cập nhật dữ liệu');
		}
	}

	function deleteMenu()
	{
		$desk_id = getValue('desk', 'int', 'POST', 0);
		$menu_id = getValue('menu', 'int', 'POST', 0);
		if (!$desk_id || !$menu_id) {
			$this->errorResponse('Dữ liệu bàn và thực đơn không hợp lệ');
		}
		//TODO check data xem thực đơn và bàn có hợp lệ ko?
		//check xem bàn đã được mở chưa
		$detail = Current_Desk::getDetail($desk_id);
		if (!$detail) {
			//bàn chưa mở thì ko xóa đc
			$this->errorResponse('Bàn chưa được mở');
		}
		//lấy ra thông tin của menu
		$menu = Menu::getDetail($menu_id);
		//remove thực đơn khỏi bàn
		$check_exists = Current_Desk_Menu::checkMenuExist($desk_id, $menu->men_id);
		if (!$check_exists) {
			$result = false;
			$this->errorResponse('Thực đơn chưa được thêm vào bàn');
		} else {
			$result = Current_Desk_Menu::deleteMenu($desk_id, $menu->men_id);
		}
		if ($result) {
			//lấy ra chi tiết bàn để trả về view
			$menu = Current_Desk_Menu::getDetail($desk_id);
			$this->ajaxResponse($menu->toJson());
		} else {
			$this->errorResponse('Lỗi cập nhật dữ liệu');
		}
	}

	function incMenu() {
		$desk_id = getValue('desk', 'int', 'POST', 0);
		$menu_id = getValue('menu', 'int', 'POST', 0);
		if (!$desk_id || !$menu_id) {
			$this->errorResponse('Dữ liệu bàn và thực đơn không hợp lệ');
		}
		//TODO check data xem thực đơn và bàn có hợp lệ ko?
		//check xem bàn đã được mở chưa
		$detail = Current_Desk::getDetail($desk_id);
		if (!$detail) {
			//bàn chưa mở thì ko thêm đc
			$this->errorResponse('Bàn chưa được mở');
		}
		//lấy ra thông tin của menu
		$menu = Menu::getDetail($menu_id);
		//tăng số lượng của thực đơn
		$check_exists = Current_Desk_Menu::checkMenuExist($desk_id, $menu->men_id);
		if (!$check_exists) {
			$result = false;
			$this->errorResponse('Thực đơn chưa được thêm vào bàn');
		} else {
			$result = Current_Desk_Menu::increaseDish($desk_id, $menu->men_id);
		}
		if ($result) {
			//lấy ra chi tiết bàn để trả về view
			$menu = Current_Desk_Menu::getDetail($desk_id);
			$this->ajaxResponse($menu->toJson());
		} else {
			$this->errorResponse('Lỗi cập nhật dữ liệu');
		}
	}

	function decMenu() {
		$desk_id = getValue('desk', 'int', 'POST', 0);
		$menu_id = getValue('menu', 'int', 'POST', 0);
		if (!$desk_id || !$menu_id) {
			$this->errorResponse('Dữ liệu bàn và thực đơn không hợp lệ');
		}
		//TODO check data xem thực đơn và bàn có hợp lệ ko?
		//check xem bàn đã được mở chưa
		$detail = Current_Desk::getDetail($desk_id);
		if (!$detail) {
			//bàn chưa mở thì ko thêm đc
			$this->errorResponse('Bàn chưa được mở');
		}
		//lấy ra thông tin của menu
		$menu = Menu::getDetail($menu_id);
		//giảm số lượng của thực đơn
		$check_exists = Current_Desk_Menu::checkMenuExist($desk_id, $menu->men_id);
		if (!$check_exists) {
			$result = false;
			$this->errorResponse('Thực đơn chưa được thêm vào bàn');
		} else {
			$result = Current_Desk_Menu::decreaseDish($desk_id, $menu->men_id);
		}
		if ($result) {
			//lấy ra chi tiết bàn để trả về view
			$menu = Current_Desk_Menu::getDetail($desk_id);
			$this->ajaxResponse($menu->toJson());
		} else {
			$this->errorResponse('Lỗi cập nhật dữ liệu');
		}
	}
}

$class = new Home_v2();
$action = getValue('action', 'str', 'REQUEST');
if ($action && is_callable($class->$action())) {
	ob_clean();
	$class->$action();
	die();
} else {
	var_dump($_REQUEST);
	http_response_code(404);
}