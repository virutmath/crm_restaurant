<?php
require_once 'inc_security.php';

function getListEmployee()
{
	$sql = 'select * from users';
	$db = new db_query($sql);
	$list = [];
	while ($row = mysqli_fetch_assoc($db->result)) {
		$row['eating'] = true;
		$list[] = $row;
	}
	echo json_encode(['list' => $list]);
}

function checkin()
{
	$user_id = getValue('user_id', 'int', 'POST', 0);
	$timestamp = time();
	$time_in = date('Y-m-d H:i:s', $timestamp);
	$sql = 'INSERT INTO timechecking(user_id, time_in) VALUES(' . $user_id . ',"' . $time_in . '")';
	$db = new db_execute($sql);
	unset($db);
	echo json_encode(['success' => 1]);
}

function checkout()
{
	//lấy ra lần checkin gần nhất trong ca (chia theo 2 ca, ca 1 từ 10h-14h, ca 2 từ 17h-22h)
	$user_id = getValue('user_id', 'int', 'POST', 0);
	$timestamp = time();
	$time_out = date('Y-m-d H:i:s', $timestamp);
	//xác định xem đang là ca 1 hay ca 2
	$hour = date('H');
	if ($hour < 15) {
		//ca 1, cho phép quá 1h so với giờ của ca
		$sql_where = ' AND time_in < "' . date('Y-m-d 14:00:00') . '"';
	} else {
		//ca 2
		$sql_where = ' AND time_in > "' . date('Y-m-d 14:00:00') . '" AND time_in < "' . date('Y-m-d 22:00:00') . '"';
	}

	$sql = 'SELECT * 
			FROM timechecking 
			WHERE user_id = ' . $user_id . '
			' . $sql_where . '
			ORDER BY time_in DESC
			LIMIT 1';
	$db = new db_query($sql);
	$last_checkin = mysqli_fetch_assoc($db->result);
	//nếu chưa tồn tại lần checkin nào thì tạo checkin
	if (!$last_checkin) {
		$sql = 'INSERT INTO timechecking(user_id, time_in) VALUES(' . $user_id . ',"' . $time_out . '")';
		$db = new db_execute($sql);
		return;
	}
	$sql = 'UPDATE timechecking SET time_out = "' . $time_out . '" WHERE id = ' . $last_checkin['id'];
	$db = new db_execute($sql);
	unset($db);
	echo json_encode(['success' => 1]);
}

function eating() {
	$user_id = getValue('user_id', 'int', 'POST', 0);
	//lấy ra lần checkin gần nhất trong ca (chia theo 2 ca, ca 1 từ 10h-14h, ca 2 từ 17h-22h)
	//nếu chưa có dữ liệu checkin checkout thì đưa ra thông báo lỗi
	$hour = date('H');
	if ($hour < 15) {
		//ca 1, cho phép quá 1h so với giờ của ca
		$sql_where = ' AND time_in < "' . date('Y-m-d 14:00:00') . '"';
	} else {
		//ca 2
		$sql_where = ' AND time_in > "' . date('Y-m-d 14:00:00') . '" AND time_in < "' . date('Y-m-d 20:00:00') . '"';
	}

	$sql = 'SELECT * 
			FROM timechecking 
			WHERE user_id = ' . $user_id . '
			' . $sql_where . '
			ORDER BY time_in DESC
			LIMIT 1';
	$db = new db_query($sql);
	$last_checkin = mysqli_fetch_assoc($db->result);
	if(!$last_checkin) {
		http_response_code(403);
		echo json_encode(['error'=>'Bạn chưa chấm công, chưa thể cập nhật tình trạng ăn ca']);
		die();
	}else{
		$eating = getValue('eating','int','POST');
		//cập nhật vào last checkin
		$db = new db_execute('UPDATE timechecking SET eating = '.$eating.' WHERE id = ' . $last_checkin['id']);
		unset($db);
	}

}

function getLog()
{
	$from = getValue('from', 'str', 'GET', date('Y-m-d'));
	$to = getValue('to', 'str', 'GET', date('Y-m-d'));
	$sql = 'SELECT users.use_name, time_in, time_out 
			FROM timechecking 
			LEFT JOIN users ON users.use_id = timechecking.user_id 
			WHERE time_in >= "' . $from . ' 0:0:0" AND time_out <= "' . $to . ' 23:59:59"';
	$db = new db_query($sql);
	$list = $db->resultArray();
	echo json_encode(['list' => $list, 'sql' => $sql]);
}

function getTimeWork()
{
	//danh sách user
	$sql = 'SELECT * FROM users';
	$db = new db_query($sql);
	$list = [];
	while ($row = mysqli_fetch_assoc($db->result)) {
		//lấy ra thời gian làm việc ca 1
		$sql = 'SELECT TIME_TO_SEC(TIMEDIFF(time_out, time_in)) as time_work, eating 
				FROM timechecking
				WHERE time_in < "' . date('Y-m-d 14:00:00') . '"
				AND user_id = ' . $row['use_id'] . '
				AND time_out > 0
				ORDER BY time_in DESC
				LIMIT 1';
		$db_ca1 = new db_query($sql);
		$ca1 = mysqli_fetch_assoc($db_ca1->result);
		if(!$ca1) {
			$ca1 = ['time_work'=>0,'eating'=>0];
		}
		//lấy ra thời gian ca 2
		$sql = 'SELECT TIME_TO_SEC(TIMEDIFF(time_out, time_in)) as time_work, eating 
				FROM timechecking
				WHERE time_in > "' . date('Y-m-d 16:00:00') . '" AND time_in < "' . date('Y-m-d 20:00:00') . '"
				AND user_id = ' . $row['use_id'] . '
				AND time_out > 0
				ORDER BY time_in DESC
				LIMIT 1';
		$db_ca2 = new db_query($sql);
		$ca2 = mysqli_fetch_assoc($db_ca2->result);
		if(!$ca2) {
			$ca2 = ['time_work'=>0,'eating'=>0];
		}
		$tmp = [
			'use_name' => $row['use_name'],
			'ca1' => $ca1['time_work'],
			'lunch' => !!$ca1['eating'],
			'work1'=>$ca1['eating'] ? $ca1['time_work'] - 30 * 60 : $ca1['time_work'],
			'ca2' => $ca2['time_work'],
			'dinner' => !!$ca2['eating'],
			'work2'=>$ca2['eating'] ? $ca2['time_work'] - 30 * 60 : $ca2['time_work']
		];
		$tmp['total'] = $tmp['work1'] + $tmp['work2'];
		$list[] = $tmp;
	}
	echo json_encode(['list'=>$list]);
}

$action = getValue('action', 'str', 'REQUEST');
if ($action && is_callable($action)) {
	ob_clean();
	$action();
} else {
	var_dump($_REQUEST);
	http_response_code(404);
}