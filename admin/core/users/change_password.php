<?
/**
 * Created by PhpStorm.
 * User: congn
 * Date: 25/09/2015
 * Time: 10:23 SA
 */
require_once 'inc_security.php';
global $admin_id;
//Phần xử lý
$action = getValue('action','str','POST','',2);
if($action) {
    switch ($action) {
        case 'changePassword':

            break;
    }
}
//Phần hiển thị
//Khởi tạo
$footer_control = '';
$content_column = '
    <table class="box_form" cellpadding="5">
        <tr>
            <td>Mật khẩu cũ: </td>
            <td> <input class="form-control" type="password" id="pass_old"></td>
        </tr>
        <tr>
            <td>Mật khẩu mới: </td>
            <td> <input class="form-control" type="password" id="pass_new"></td>
        </tr>
        <tr>
            <td>Nhập lại mật khẩu mới: </td>
            <td> <input class="form-control" type="password" id="repass_new"></td>
        </tr>
    </table>
';

$footer_control = '
<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="changePassword('.$admin_id.')">
        <i class="fa fa-save"></i>
        Lưu lại
    </label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('content_column',$content_column);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mindow_iframe_1column');

?>