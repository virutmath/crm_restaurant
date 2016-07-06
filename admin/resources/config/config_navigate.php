<?php
require 'config_security.php';
$bg_errorMsg = '';
$bg_table = 'navigate_admin';
//array module
$array_module = array();
$db_query = new db_query('SELECT mod_id, mod_name FROM modules ORDER BY mod_id ASC');
while($row = mysqli_fetch_assoc($db_query->result)){
    $array_module[$row['mod_id']] = $row['mod_name'];
}
check_super_admin();
#Phần code xử lý
$myform = new generate_form();
$myform->addTable($bg_table);
$myform->add('nav_name','nav_name',0,0,'',1);
$myform->add('nav_module_id','nav_module_id',1,0,1);
$myform->add('nav_order','nav_order',1,0,1);
/**
 * Something here ...
 * insert, update...
 */
$form_redirect = getValue('form_redirect','str','POST','');
$action = getValue('action','str','POST','');
if($action == 'execute'){
    $bg_errorMsg = $myform->checkdata();
    /**
     * something code here
     */
    if(!$bg_errorMsg){
        $db_insert = new db_execute_return();
        $last_id = $db_insert->db_execute($myform->generate_insert_SQL());unset($db_insert);

        /**
         * something code here
         */


        //redirect
        if($last_id){
            redirect($form_redirect);
        }
    }
}

#Phần hiển thị
$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));

$html_page = '';
$form = new form();
$html_page .= $form->form_open();
$html_page .= $form->textnote('Các trường có dấu (<span class="form-asterick">*</span>) là bắt buộc nhập');

$html_page .= $form->text(array(
    'label'=>'Điều hướng',
    'name'=>'nav_name',
    'id'=>'nav_name'
));
$html_page .= $form->select(array(
    'label'=>'Chọn modules',
    'name'=>'nav_module_id',
    'id'=>'nav_module_id',
    'option'=>$array_module,
    'selected'=>getValue('nav_module_id','int','POST',0)
));
$html_page .= $form->text(array(
    'label'=>'Thứ tự',
    'name'=>'nav_order',
    'id'=>'nav_order',
    'value'=>0
));

$html_page .= $form->form_redirect(array(
    'list'=>array('Thêm mới'=>'config_navigate.php')
));
$html_page .= $form->form_action(array(
    'label'=>array('Thêm mới','Nhập lại'),
    'type'=>array('submit','reset')
));
$html_page .= $form->form_close();
$rainTpl->assign('html_page',$html_page);
$rainTpl->draw('config_navigate');
