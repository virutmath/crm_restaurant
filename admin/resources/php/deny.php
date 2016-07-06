<? session_start();
if(isset($_SESSION['userlogin'])){
    unset($_SESSION['userlogin']);
}else{
    echo '<script>window.top.location.href="../../login.php";</script>';
    die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <center>
        <h2>Bạn không có quyền thực thi</h2>
        <h5>Vui lòng liên hệ user admin!</h5>
    </center>
</body>