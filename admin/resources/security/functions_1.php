<?php
function notifydie($str){
    $noti = $str ? $str : 'Loi truy van CSDL';
    session_unset();
    session_destroy();
    die($noti);
}

function str_debase($encodedStr=""){
    $returnStr = "";
    if(!empty($encodedStr)) {
        $dec = str_rot13($encodedStr);
        $dec = base64_decode($dec);
        $returnStr = $dec;
    }
    return $returnStr;
}
function encode_combine($str) {
    $db = new db_query('SELECT "5529e6b0760d73d38d3d3a5bb33e3eaf" as kdm_hash1, kdims.* FROM kdims LIMIT 1');
    $hash = mysqli_fetch_assoc($db->result);unset($db);
    $string = str_rot13(str_rot13($hash['kdm_hash1']) . base64_url_encode($str));
    return base64_encode($string);
}
function decode_combine($str) {
    $str = base64_decode($str);
    $db = new db_query('SELECT "5529e6b0760d73d38d3d3a5bb33e3eaf" as kdm_hash1, kdims.* FROM kdims LIMIT 1');
    $hash = mysqli_fetch_assoc($db->result);unset($db);
    $decode_step1 = str_rot13($str);
    $decode_hash = str_rot13($hash['kdm_hash1']);
    $decode_step2 = str_replace($decode_hash,'',$decode_step1);
    return base64_url_decode($decode_step2);
}
function print_error_msg($errorMsg){
    if($errorMsg) return '<div class="alert alert-danger"><span class="close">&times;</span>'.$errorMsg.'</div>';
    else return '';
}
function add_error_msg ($error) {
    global $bg_errorMsg;
    $bg_errorMsg .= '&bull; '.$error.'<br>';
}
function mini_modal_open($label, $extra_html = ''){
    $str = '<div class="modal-mini">
            <div class="modal-header">
                <label>'.$label.'</label>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-mini-content" '.$extra_html.'>';
    return $str;
}
function mini_modal_close($js_addon = ''){
    if(!$js_addon)
    return '</div>
        </div>';
    else
        return '</div>
        </div>' . '<script>'.$js_addon.'</script>';
}
function add_more_css($css_path,&$load_header, $media = 'screen'){
    $load_header .= '<link href="'.$css_path.'" type="text/css" rel="stylesheet" media="'.$media.'"/>';
}
function module_upload_picture($picture_name){
    //tạo thư mục khi upload
    generate_dir_upload($picture_name,'organic');
    $path_upload = '../../..'.get_picture_dir($picture_name).'/'.$picture_name;
    return rename('../../../temp/'.$picture_name,$path_upload);
}

/**
 * Hàm định dạng mã phiếu, mã hóa đơn từ ID bản ghi
 * @param $id_value : id bản ghi
 * @param int $length : tổng độ dài của mã - tự động thêm số 0 vào trước cho đủ độ dài
 * @param string $prefix : tiền tố của mã - ví dụ hóa đơn thì thêm tiền tố HĐ
 * @return string
 */
function format_codenumber($id_value, $length = 0 ,$prefix = '') {
    $id_length = strlen($id_value);
    if($length <= $id_length) {
        return $prefix . $id_value;
    }
    for($i = 0; $i < $length - $id_length; $i++) {
        $id_value = '0' . $id_value;
    }
    return $prefix . $id_value;
}
/**
 * Hàm get ID từ mã code
 * @param string $code
 * @param string $prefix
 * @return int
 */
function codenumber_get_id($code, $prefix = '') {
    if($prefix) {
        return intval(str_replace($prefix,'',$code));
    }else{
        return intval($code);
    }
}
