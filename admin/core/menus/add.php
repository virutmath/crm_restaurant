<?
require_once 'inc_security.php';
checkPermission('add');

#Phần code xử lý
$myform = new generate_form();
$myform->addTable($bg_table);
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
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));

$html_page = '';
$form = new form();
$html_page .= $form->form_open();
$html_page .= $form->textnote('Các trường có dấu (<span class="form-asterick">*</span>) là bắt buộc nhập');


/**
 * something here
 */


$html_page .= $form->form_redirect();
$html_page .= $form->form_action(array(
    'label'=>array('Thêm mới','Nhập lại'),
    'type'=>array('submit','reset')
));
$html_page .= $form->form_close();
$rainTpl->assign('html_page',$html_page);
$rainTpl->draw('add');