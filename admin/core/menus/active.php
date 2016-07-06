<?php
require_once 'inc_security.php';
checkPermission('edit');
$field = getValue('field','str','POST','');
$record_id = getValue('id','int','POST',0);
//Lấy ra trạng thái hiện tại
$db_active = new db_query('SELECT '.$field.' as field FROM '.$bg_table. ' WHERE '.$id_field. '='. $record_id);
$value = mysqli_fetch_assoc($db_active->result);unset($db_active);
$value = abs($value['field'] - 1);
//Update field
$db_update = new db_execute('UPDATE '.$bg_table.' SET '.$field.' = '.$value . ' WHERE '.$id_field . '=' . $record_id);
echo form_checkbox($field,1,$value,'onclick="Grid.update_active(\''.$field.'\','.$record_id.')" id="'.$field.'_'.$record_id.'" ');
?>