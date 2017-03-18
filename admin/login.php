<?php
session_start();
error_reporting(0);
require_once 'resources/security/eloquent.database.php';
require_once 'resources/models/admin_users.php';
require_once 'resources/models/logs_session.php';
require_once('../classes/rain.tpl.class.php');
require_once("../functions/functions.php");
require_once("../functions/date_functions.php");
require_once('resources/security/inc_constant.php');
require_once("../classes/database.php");
require_once("resources/security/functions.php");
require_once("resources/security/functions_1.php");
$username = getValue("username", "str", "POST", "", 1);
$password = getValue("password", "str", "POST", "", 1);
$action = getValue("action", "str", "POST", "");

if ($action == "login") {
	$user_id = 0;
	$user_id = checkLogin($username, $password);
	if ($user_id != 0) {
		$isAdmin = 0;
		$isSuperAdmin = 0;

		$check_isAdmin = Admin_User::where('admin_id', $user_id)
			->join('admin_users_groups', 'adm_group_id', '=', 'adu_group_id')
			->first();

		if ($check_isAdmin->adu_group_admin != 0) $isAdmin = 1;
		if ($check_isAdmin->adm_isadmin != 0) $isSuperAdmin = 1;

		//Set SESSION
		$_SESSION["logged"] = 1;
		$_SESSION["user_id"] = $user_id;
		$_SESSION['user_group_id'] = $check_isAdmin->adm_group_id;
		$_SESSION["userlogin"] = $username;
		$_SESSION["username"] = $check_isAdmin->adm_name;
		$_SESSION["user_note"] = $check_isAdmin->adm_note;
		$_SESSION["password"] = md5($password);
		$_SESSION["isAdmin"] = $isAdmin;
		$_SESSION['isSuperAdmin'] = $isSuperAdmin;

		$row_log = Logs_Session::where('log_admin_id',$user_id)->orderBy('log_time_in','desc')->first()->toArray();
		$time_log_start = convertDateTime(date('d/m/Y', $row_log['log_time_in']), '0:0:0');// reset thời gian về đầu ngày
		$time_log = convertDateTime(date('d/m/Y', time()), '0:0:0');
		if ($time_log != $time_log_start) {
			/* Khi đăng nhập thì lưu lại log admin đăng nhập vào khoảng thời gian nào*/
			Logs_Session::insert(['log_admin_id'=>$user_id,'log_time_in'=>time()]);
		}


		if (!$isAdmin && !$isSuperAdmin) {
			$_SESSION['user_config'] = $check_isAdmin->adm_user_config;
			redirect('index.php');
		} else {
			//kiểm tra xem acc đã được config chưa
			$db_check = new db_query('SELECT * FROM configurations WHERE con_admin_id = ' . $user_id);
			if (mysqli_num_rows($db_check->result) < 1) {
				//chưa có config thì redirect đến file config
				redirect('user_config.php');
			} else {
				require_once '../classes/Mobile_Detect.php';
				$detect_mobile = new Mobile_Detect();
				if ($detect_mobile->isMobile()) {
					redirect('mobile/home/');
				} else {
					redirect('index.php');
				}

			}
		}
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.0, minimum-scale=1.0">
    <title>Administrator Managerment</title>
    <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/home.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/css_login.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/font-awesome.min.css"/>
    <script src="resources/js/jquery.js" type="text/javascript"></script>
</head>
<body>
<div class="content_center">
    <div class="top_head">

    </div>
    <div class="left_content">
        <p>Phần mềm quản lý nhà hàng chuyên nghiệp</p>
        <img src="../pictures/banner.jpg" alt="image">

    </div>
    <div class="right_content">
        <form method="post" action="" class="form-horizontal" autocomplete="off">
            <div class="login_container">
                <div class="title_login">PANDAIN POS</div>
                <div class="errorMsg"></div>
                <span class="input-icon">
                            <input type="text" name="username" class="form-control" placeholder="Tài khoản"
                                   autocomplete="off"/>
                            <i class="fa fa-user"></i>
                        </span>
                <span class="input-icon">
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu"
                                   autocomplete="off"/>
                            <i class="fa fa-lock"></i>
                            <a href="#">Quên mật khẩu?</a>
                        </span>
                <div class="group-button">
                    <button type="submit" class="button-control">Đăng nhập</button>
                    <input type="hidden" name="action" value="login"/>
                </div>
                <div class="link_help">
                    <p><a href="#"><i class="fa fa-info-circle"></i> Liên hệ</a></p>
                    <p><a href="#"><i class="fa fa-question-circle"></i> Hướng dẫn sử dụng</a></p>
                </div>

            </div>
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="footer">

    </div>
</div>

</body>
</html>