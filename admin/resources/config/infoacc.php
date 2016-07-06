<?
require 'config_security.php';
$errorMsg = "";
$success = '';
//Get Action.
//Call Class generate_form();
$action = getValue('action','str','POST','');
if($action == 'execute'){
	$old_pass = getValue('old_pass','str','POST','');
	if(md5($old_pass) != $_SESSION['password']){
		$errorMsg .= 'Bạn nhập password cũ không chính xác';
	}
	$new_pass = getValue('new_pass','str','POST','');
	$check_pass = getValue('check_pass','str','POST','');
	if($new_pass != $check_pass){
		$errorMsg .= 'Bạn nhập password mới không khớp';
	}else{
		$new_pass = md5($new_pass);
	}
	$myform = new generate_form(); 
	$myform->addTable('admin_users');
	$myform->add('adm_password','new_pass',0,1,'',1,'Bạn nhập password chưa chính xác');
	if($errorMsg == ''){
		$db_ex = new db_execute($myform->generate_update_SQL('adm_id',$admin_id));
		$success = 'Cập nhật thành công';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Add New</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="../css/bootstrap.css" rel="stylesheet"/>
<link href="../css/common.css" rel="stylesheet"/>
<link href="../css/template.css" rel="stylesheet"/>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <div id="wrapper">
    	<?=$errorMsg?>
    	<?=$success?>
        <a href="/admin/logout.php" style="padding: 8px;">Thoát đăng nhập</a>
    	<?php $form = new form();?>
    	<?=$form->form_open('add_new',$_SERVER['REQUEST_URI'])?>
        <?=$form->textnote(array('Thay đổi mật khẩu quản trị viên'))?>
        <?=$form->password(array('label'=>'Mật khẩu cũ','name'=>'old_pass','id'=>'old_pass','require'=>1,'errorMsg'=>'Bạn chưa nhập mật khẩu cũ'))?>
        <?=$form->password(array('label'=>'Mật khẩu mới','name'=>'new_pass','id'=>'new_pass','require'=>1,'errorMsg'=>'Bạn chưa nhập mật khẩu mới'))?>
        <?=$form->password(array('label'=>'Xác nhận mật khẩu ','name'=>'check_pass','id'=>'new_pass','require'=>1,'errorMsg'=>'Mật khẩu xác nhận không khớp'))?>
        <?=$form->form_action(array('label'=>array('Thêm mới','Nhập lại'),'type'=>array('submit','reset')))?>
        <?=$form->form_close()?>
    </div>
</body>
</html>
