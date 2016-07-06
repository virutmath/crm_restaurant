<?php
session_start();
error_reporting(0);
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
        $db_isadmin = new db_query("SELECT adu_group_admin, adm_isadmin, adm_name, adm_note, adm_group_id, adm_user_config
                                    FROM admin_users
                                    LEFT JOIN admin_users_groups ON adm_group_id = adu_group_id
                                    WHERE adm_id = " . $user_id);

        $row = mysqli_fetch_array($db_isadmin->result);
        if ($row["adu_group_admin"] != 0) $isAdmin = 1;
        if ($row['adm_isadmin'] != 0) $isSuperAdmin = 1;

        //Set SESSION
        $_SESSION["logged"]         =   1;
        $_SESSION["user_id"]        =   $user_id;
        $_SESSION['user_group_id']  =   $row['adm_group_id'];
        $_SESSION["userlogin"]      =   $username;
        $_SESSION["username"]       =    $row['adm_name'];
        $_SESSION["user_note"]      =    $row['adm_note'];
        $_SESSION["password"]       =   md5($password);
        $_SESSION["isAdmin"]        =   $isAdmin;
        $_SESSION['isSuperAdmin']   =   $isSuperAdmin;
        unset($db_isadmin);

        $db_query_log = new db_query('SELECT * FROM logs_session WHERE log_admin_id ='.$user_id .'
                                     ORDER BY log_time_in DESC LIMIT 1');

        $row_log = mysqli_fetch_assoc($db_query_log->result); unset($db_query_log);
        $time_log_start = convertDateTime(date('d/m/Y',$row_log['log_time_in']),'0:0:0');// reset thời gian về đầu ngày
        $time_log       = convertDateTime(date('d/m/Y',time()),'0:0:0');
        if($time_log    != $time_log_start ){
            /* Khi đăng nhập thì lưu lại log admin đăng nhập vào khoảng thời gian nào*/
            $db_admin_log = 'INSERT INTO logs_session (log_admin_id,log_time_in) VALUES ('.$user_id.','.time().')';
            $db_exc_logs = new db_execute($db_admin_log); unset($db_exc_logs);
        }


        if(!$isAdmin && !$isSuperAdmin) {
            $_SESSION['user_config'] = $row['adm_user_config'];
            redirect('index.php');
        }else{
            //kiểm tra xem acc đã được config chưa
            $db_check = new db_query('SELECT * FROM configurations WHERE con_admin_id = ' . $user_id);
            if(mysqli_num_rows($db_check->result) < 1) {
                //chưa có config thì redirect đến file config
                redirect('user_config.php');
            }else {
                require_once '../classes/Mobile_Detect.php';
                $detect_mobile = new Mobile_Detect();
                if($detect_mobile->isMobile()) {
                    redirect('mobile/home/');
                }else{
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
                    <div class="title_login"><img src="../pictures/logo.png">CRM Restaurant</div>
                    <div class="errorMsg"></div>
                        <span class="input-icon">
                            <input type="text" name="username" class="form-control" placeholder="Tài khoản" autocomplete="off"/>
                            <i class="fa fa-user"></i>
                        </span>
                        <span class="input-icon">
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" autocomplete="off"/>
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