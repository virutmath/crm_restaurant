<?
require_once 'inc_security.php';
if(!isset($_GET['data_record_id']) && !isset($_GET['position'])){
    die;
}
$data_record_id         = getValue('data_record_id','int','GET',0);
$position               = getValue('position','str','GET','');
$content_column         = '';
$footer_control         = '';
$custom_script          = '';

if($data_record_id      != 0){
    if(trim($position)  == 'bill_in'){
        //$id_bill        = 'bii_id = ' . $data_record_id;
        $start_time     = 'bii_start_time';
        $adm_id         = 'bii_admin_id';
    }
    if(trim($position)  == 'bill_out'){
        //$id_bill        = 'bio_id = ' . $data_record_id;
        $start_time     = 'bio_start_time';
        $adm_id         = 'bio_admin_id';
    }
    $db_bill            = new db_query('SELECT * FROM trash
                                        WHERE tra_record_id = ' . $data_record_id .' 
                                        AND tra_table = \'' . $position . '\'');
    $data_bill          = mysqli_fetch_assoc($db_bill->result);unset($db_bill);
    $data_bill          = json_decode(base64_decode($data_bill['tra_data']),1);
    // ngay tao
    $bill_start_time    = $data_bill[$start_time];
    // ten nguoi tao
    $db_admin           = new db_query('SELECT adm_name FROM admin_users WHERE adm_id = ' . $data_bill[$adm_id]);
    $data_admin         = mysqli_fetch_assoc($db_admin->result);unset($db_admin);
    $creat_people       = $data_admin['adm_name'];
    // lay ra financies id
    $list_fin_id        = $data_bill['arr_fin_id'];
    $list_fin_id        = explode(',',$list_fin_id);
    foreach($list_fin_id as $fin_id){
        // ngay cap nhat cuoi
        // nguoi cap nhat cuoi
        $db_fin             = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . $fin_id . ' 
                                            AND tra_table = \'financial\'');
        while($data_fin     = mysqli_fetch_assoc($db_fin->result)){
            $data_fin       = json_decode(base64_decode($data_fin['tra_data']),1);
            $last_time[]    = $data_fin['fin_updated_time'];
            $update_people  = $data_fin['fin_admin_id'];
        }unset($db_fin);
        // ngày cập nhật cuối
        $last_times = max($last_time);
        // người cập nhật
        $db_admin           = new db_query('SELECT adm_name FROM admin_users WHERE adm_id = ' . $update_people);
        $data_admin         = mysqli_fetch_assoc($db_admin->result);unset($db_admin);
        $update_people_last = $data_admin['adm_name'];
    }
}

$content_column .= 
'<div class="col-xs-12">
    <table class="col-xs-12 infor_bill_trash" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>Thông tin về: </td>
            <td><strong>Số HĐ: '.format_codenumber($data_record_id,6,'').'</strong></td>
        </tr>
        <tr>
            <td>Ngày tạo: </td>
            <td><strong>'.date("d/m/Y h:i",$bill_start_time).'</strong></td>
        </tr>
        <tr>
            <td>Người tạo: </td>
            <td><strong>'.$creat_people.'</strong></td>
        </tr>
        <tr>
            <td>Cập nhật lần cuối: </td>
            <td><strong>'.date("d/m/Y h:i",$last_times).'</strong></td>
        </tr>
        <tr>
            <td>Người cập nhật: </td>
            <td><strong>'.$update_people_last.'</strong></td>
        </tr>
        <tr>
            <td>Trạng thái: </td>
            <td><strong>Đang trong thùng rác</strong></td>
        </tr>
    </table>
</div>';
$footer_control .= '<div class="print-close">';
$footer_control .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span>';
$footer_control .= '</div>';

$custom_script = 
'<script>
    var bill_close = $(\'\.bill-close\');
    bill_close.click(function(){
        window.parent.communicateParentWindow(\'close_detail\');
    });
</script>';
$rainTpl = new RainTPL();
add_more_css('info_cus_user.css',$load_header);
$rainTpl->assign('load_header',$load_header);

$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
//$custom_script = file_get_contents('script_list_bill_trash.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mindow_iframe_1column');
?>