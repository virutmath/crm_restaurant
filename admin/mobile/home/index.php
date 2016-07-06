<?
require_once 'inc_security.php';
$rainTpl = new RainTPL();
add_more_css('style.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('listDesk', $listDesk);
$custom_script = file_get_contents('script_mobile.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mobile_admin');