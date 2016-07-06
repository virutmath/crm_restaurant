<?
require_once '../../resources/security/security.php';
$module_id	= 3;
$module_name = 'Quản lý thực đơn';
checkAccessModule($module_id);
checkLogged();

$partICON       = '../themes/images/';
$home       = 'index.php';
$listDesk   = 'list_desk.php';
$menuList   = 'menu_list.php';

$bottom_control = '
                    <div class="control">
                        <a class="back"><i class="fa fa-home fa-2x"></i> Home</a>
                    </div>
                  ';



