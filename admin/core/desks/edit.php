<?
require_once 'inc_security.php';
checkPermission('edit');
$record_id = getValue('record_id');

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
        $db_update = new db_execute($myform->generate_update_SQL($id_field,$record_id));
        unset($db_update);


        /**
         * something code here
         */


        //redirect
        redirect($form_redirect);
    }
}


//lấy dữ liệu record cần sửa đổi
$db_data 	= new db_query("SELECT * FROM " . $bg_table . " WHERE " . $id_field . " = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
    foreach($row as $key=>$value){
        $$key = $value;
    }
}else{
    exit();
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
    'label'=>array('Cập nhật','Nhập lại'),
    'type'=>array('submit','reset')
));
$html_page .= $form->form_close();
$rainTpl->assign('html_page',$html_page);
$rainTpl->draw('add');