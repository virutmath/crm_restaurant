<?
require_once('../../classes/database.php');
require_once('../../classes/rain.tpl.class.php');
require_once('../../functions/functions.php');
require_once('../../functions/rewrite_functions.php');
require_once('../resources/security/AbstractAjax.php');
require_once('../resources/security/AjaxCommon.php');
require_once('../../classes/generate_form.php');
require_once("../../classes/simple_html_dom.php");
require_once('../../classes/rain.tpl.class.php');
require_once('../../classes/PHPExcel.php');
require_once('../../functions/form.php');
require_once '../resources/security/inc_constant.php';

require_once('../resources/security/grid.php');
check_authen();
//checkLogged();
require_once('../resources/security/functions_1.php');
require_once('../resources/security/inc_config_security.php');
RainTpl::configure("base_url", null );
RainTpl::configure("tpl_dir", "../../resources/templates/" );
RainTpl::configure("cache_dir", "../../resources/caches/" );
RainTPL::configure("path_replace_list",array());
$module_id	= 16;
$module_name = 'Thiết lập mặc định CRM';
$bg_errorMsg = '';
