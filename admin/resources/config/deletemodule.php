<?php
require 'config_security.php';
$record_id = getValue('id');
$returnurl = getValue('returnURL','str','GET',base64_encode('websetting.php'));
$returnurl = base64_decode($returnurl);
$db = new db_execute('DELETE FROM modules WHERE mod_id = '.$record_id);
redirect($returnurl);
?>