<?php
require 'config_security.php';
$bg_errorMsg = '';
$bg_table = 'modules';
check_super_admin();
#Phần code xử lý
$myform = new generate_form();
$myform->addTable($bg_table);
$myform->add('mod_name','mod_name',0,0,'',1);
$myform->add('mod_directory','mod_directory',0,0,1);
$myform->add('mod_listname','mod_listname',0,0,'');
$myform->add('mod_listfile','mod_listfile',0,0,'');
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
    'label'=>'Tên module',
    'name'=>'mod_name',
    'id'=>'mod_name'
));
$html_page .= $form->text(array(
    'label'=>'Thư mục',
    'name'=>'mod_directory',
    'id'=>'mod_directory'
));
$html_page .= $form->text(array(
    'label'=>'Tên chức năng',
    'name'=>'mod_listname',
    'id'=>'mod_listname'
));
$html_page .= $form->text(array(
    'label'=>'Tên file tương ứng',
    'name'=>'mod_listfile',
    'id'=>'mod_listfile'
));

$html_page .= $form->form_redirect(array(
    'list'=>array('Thêm mới'=>'websetting.php')
));
$html_page .= $form->form_action(array(
    'label'=>array('Thêm mới','Nhập lại'),
    'type'=>array('submit','reset')
));
$html_page .= $form->form_close();
$rainTpl->assign('html_page',$html_page);
$rainTpl->draw('websetting');
