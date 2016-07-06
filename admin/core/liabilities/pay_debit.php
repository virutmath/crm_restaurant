<?
require_once 'inc_security.php';
if(!isset($_GET['id']) || !isset($_GET['type'])){
    die;
}
$record_id  = getValue('id','int');
$type       = getValue('type','str');


$footer_control = '';
$name_object    = '';
$name           = '';
$total_money    = '';
$number_bill    = 0;
$id             = format_codenumber($record_id,6,'');
$avatars        = '<i class="fa fa-camera fa-2x"></i>';
$tabel          = '';
$left_join      = '';
$id_column      = '';
$status         = '';
$total_debit    = 0;


if(trim($type)  == 'customer'){
    $tabel          = 'bill_in';
    $left_join      = 'LEFT JOIN customers ON bii_customer_id = cus_id';
    $name_object    = 'KH';
    $id_column      = 'bii_customer_id = ' . $record_id;
    $status         = 'bii_status';
    $name_key       = 'cus_name';
    $totalmoney    = 'bii_money_debit';
    $image         = 'cus_picture';
}
if(trim($type) == 'supplier'){
    $tabel          = 'bill_out';
    $left_join      = 'LEFT JOIN suppliers ON bio_supplier_id = sup_id';
    $name_object    = 'NCC';
    $id_column      = 'bio_supplier_id = ' . $record_id;
    $status         = 'bio_status';
    $name_key       = 'sup_name';
    $totalmoney     = 'bio_money_debit';
    $image          = 'sup_image';
}

$db_bill_debit  = new db_query('SELECT * FROM '.$tabel.' '.$left_join.'
                                WHERE '.$id_column.' 
                                AND '.$status.' = 0');
$number_bill    = mysqli_num_rows($db_bill_debit->result);
while($data_bill_debit = mysqli_fetch_assoc($db_bill_debit->result)){
    $name           = $data_bill_debit[$name_key];
    $total_debit    += $data_bill_debit[$totalmoney];
    if($data_bill_debit[$image] == ''){
        $avatar     = $avatars;
    }else{
        $avatar     = '<img src="'.get_picture_path($data_bill_debit[$image]).'"/>';
    }
}
$total_money = '<span class="total_money" data-total_money="'.$total_debit.'">'.number_format($total_debit).'</span>';

$rainTpl = new RainTPL();
add_more_css('pay_debit.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('name_object', $name_object);
$rainTpl->assign('name', $name);
$rainTpl->assign('id', $id);
$rainTpl->assign('record_id', $record_id);
$rainTpl->assign('type', $type);
$rainTpl->assign('avatar', $avatar);
$rainTpl->assign('number_bill', $number_bill);
$rainTpl->assign('total_debit', $total_debit);
$rainTpl->assign('total_money', $total_money);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('link_custom_script','<script type="text/javascript" src="script_debit.js"></script>');
$rainTpl->draw('content_pay_debit');
?>