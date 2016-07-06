<?
require_once 'inc_security.php';
//
//
// khai bao bien
$content_column = '';
$footer_control = '';

$ten_ma = '';
$ten_nhom = '';

$id   = '';
$name   = '';
$avata = '';
$avatar = '<i class="fa fa-camera-retro fa-2x ava-cus"></i><p>Không có hình</p>';
$address = '';
$phone  = '';
$email  = '';
$team_obj = '';
$note   = '';
$name_object = '';
//
if(isset($_GET['cus_id'])){
    $cus_id     = getValue('cus_id','int','GET',0);
    $ten_ma     = 'Mã KH';
    $ten_nhom   = 'Nhóm khách hàng';
    $name_object = 'Tên khách hàng';
    if($cus_id  == 0) {
        $name   = 'Khách lẻ';
    }
    if($cus_id  != 0){
        $db_custemor    = new db_query('SELECT * FROM customers 
                                        INNER JOIN customer_cat ON customers.cus_cat_id = customer_cat.cus_cat_id
                                        WHERE cus_id = ' . intval($cus_id));
        $data_cus       = mysqli_fetch_assoc($db_custemor->result);unset($db_custemor);
        
        $id             = format_codenumber($data_cus['cus_id'],6,'KH');
        $avata          = '<img src="'.get_picture_path($data_cus['cus_picture']).'"/>';
        $name           = $data_cus['cus_name'];
        $address        = $data_cus['cus_address'];
        $phone          = $data_cus['cus_phone'];
        $email          = $data_cus['cus_email'];
        $team_obj       = $data_cus['cus_cat_name'];
        $note           = $data_cus['cus_note'];  
    }
}
if(isset($_GET['user_id'])){
    $user_id        = getValue('user_id','int','GET',0);
    $ten_ma         = 'Mã NV';
    $ten_nhom       = 'Nhóm nhân viên';
    $name_object    = 'Tên nhà cung cấp';
    if($user_id == 0){
        $name       = 'Không chọn nhân viên';
    }
    if($user_id != 0){
        $db_user        = new db_query('SELECT * FROM users 
                                        INNER JOIN categories_multi ON use_group_id = cat_id
                                        WHERE use_id = ' . intval($user_id));
        $data_user      = mysqli_fetch_assoc($db_user->result);unset($db_user);
        $id             = format_codenumber($data_user['use_id'],6,'NV');
        $avata          = $data_user['use_image'];
        $name           = $data_user['use_name'];
        $address        = $data_user['use_address'];
        $phone          = $data_user['use_phone'];
        $note           = $data_user['use_note'];
        $team_obj       = $data_user['cat_name'];
    }
}

if($avata == ''){
    $img    = $avatar;
}else{
    $img  = $avata;
}

$content_column .= '<div class="detail_content">';
$content_column .= '<form action="" method="">';
$content_column .= '<table cellpadding="0" cellspacing="0" border="0" class="form-box">';
$content_column .= '<tr>';
$content_column .= '<td class="col-30">Mã hệ thống:</td>';
$content_column .= '<td><input class="inp1-2" readonly="readonly" value="'.$id.'"/> '.$ten_ma.': <input class="inp1-2" readonly="readonly"/></td>';
$content_column .= '<td rowspan="6" class="col-30" ><div class="box-ava-cus">'.$img.'</div></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>'.$name_object.':</td>';
$content_column .= '<td><input class="inp1" readonly="readonly" value="'.$name.'"/></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>Địa chỉ:</td>';
$content_column .= '<td><input class="inp1" readonly="readonly" value="'.$address.'"/></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>Điện thoại:</td>';
$content_column .= '<td><input class="inp1" readonly="readonly" value="'.$phone.'"/></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>Email:</td>';
$content_column .= '<td><input class="inp1" readonly="readonly" value="'.$email.'"/></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>'.$ten_nhom.':</td>';
$content_column .= '<td><select class="inp1 inp-sel" disabled="disabled"><option>'.$team_obj.'</option></select></td>';
$content_column .= '</tr>';
$content_column .= '<tr>';
$content_column .= '<td>Ghi chú:</td>';
$content_column .= '<td colspan="2"><textarea class="inp-tarea" readonly="readonly">'.$note.'</textarea></td>';
$content_column .= '</tr>';
$content_column .= '</table>';
$content_column .= '</form>';

$footer_control .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span>'; 

$rainTpl = new RainTPL();
add_more_css('detail_cus_user.css',$load_header);
$rainTpl->assign('load_header',$load_header);

$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
//$custom_script = file_get_contents('script_bill_detail.html');
$rainTpl->draw('detail_cus_user');