<?
require_once '../../resources/security/security.php';
$module_id	= 6;
$module_name = 'bills';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table_o = 'bill_out';
$id_field_o = 'bio_id';
$bio_ss     = 'bio_start_time';

$bg_table_i = 'bill_in';
$id_field_i = 'bii_id';

$today = time();
$formDate = gmdate("d/m/Y", $today - 2592000);
$toDate = gmdate("d/m/Y", $today);

//$cat_field = '';
//$cat_type = 'supplier';
$cat_table = 'categories_multi';

$modal_title = array(
    'loadFormAddCategory'=>'',
    'loadFormEditCategory'=>'',
    'loadFormAddRecord'=>'',
    'loadFormEditRecord'=>''
);
