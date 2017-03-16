<?
require_once '../../resources/security/security.php';
$module_id	= 3;
$module_name = 'Quản lý bán hàng';
checkLogged('/admin/login.php?redirect='.urlencode(getURL()));
checkAccessModule($module_id);
$bg_errorMsg = '';
$bg_table = 'bills';
$id_field = 'bil_id';

$listing_menu_size = 20;//số bản ghi load trong 1 trang của listing menu
$desk_menu_size = 20;//số bản ghi load trong 1 trang của listing menu desk
