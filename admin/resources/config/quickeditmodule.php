<?php
require 'config_security.php';
$myform = new generate_form();
$record_id = getValue('record_id');
$myform->add("mod_name","mod_name",0,0,"",1,"Bạn chưa nhập tên module",0,"");
$myform->add("mod_path","mod_path",0,0,"",0,"",0,"");
$myform->add("mod_listname","mod_listname",0,0,"",0,"",0,"");
$myform->add("mod_listfile","mod_listfile",0,0,"",0,"",0,"");
$myform->add("mod_order","mod_order",1,0,0,0,"",0,"");
//Add table
$myform->addTable($fs_table);
$iQuick = getValue("action","str","POST","");
if ($iQuick == "execute"){
	$errorMsg='';
	$errorMsg .= $myform->checkdata();
	if($errorMsg == ""){
		//echo $myform->generate_insert_SQL();
		$db_ex = new db_execute($myform->generate_update_SQL($field_id,$record_id));
		unset($db_ex);
		//Hien thi loi
	}
	redirect('websetting.php');
}
?>