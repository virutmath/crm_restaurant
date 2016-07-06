<?
require_once 'inc_security.php';
// lấy id của bàn đã active
$record_id = getValue('desk_id','int','GET',0);


//select bàn ăn cần chuyển
$array_desk = array();
$sec_name = '';
    $db_query = new db_query('SELECT d.des_id,d.des_name,s.sec_name FROM desks d LEFT  JOIN  sections s ON d.des_sec_id = s.sec_id WHERE d.des_id = '.$record_id.'');
while($row = mysqli_fetch_assoc($db_query->result)){
    $array_desk[$row['des_id']] = $row['des_name'];
    $sec_name = $row['sec_name'];
}unset($db_query);


// slect cac bàn ăn đang active
$current_desk = '';
$db_desk_active = new db_query('SELECT * FROM current_desk');
while($row_des_active = mysqli_fetch_assoc($db_desk_active->result)) {
    $current_desk .= $row_des_active['cud_desk_id'].',';
}
//select bàn ăn cần chuyển
$list_desk = '';
$db_query_des = new db_query('SELECT
                                    des_id,
                                    des_name,
                                    sec_name
                                FROM
                                    desks
                                LEFT JOIN sections ON des_sec_id = sec_id
                                LEFT JOIN service_desks ON sec_service_desk = sed_id
                                WHERE
                                    sed_agency_id = '.$configuration['con_default_agency'].'
                                    AND des_id NOT IN(' . rtrim($current_desk,',') . ')
                                ORDER BY des_id ASC');
while ($row_des = mysqli_fetch_assoc($db_query_des->result)) {
    $list_desk .= '<option value="' . $row_des['des_id'] . '">' . $row_des['sec_name'] . ' - ' . $row_des['des_name'] . '</option>';
}
unset($db_query_des);

$content_column = '
<form name="move_desk" action="" method="post">
<div class="content_column">
    <div class="row">
        <div class="col-xs-4 text-right">Chuyển từ bàn</div>
        <div class="col-xs-6">
        <label>'.$sec_name.' - '.$array_desk[$record_id].'<input type="hidden" value="'.$record_id.'" id="from_desk"></label>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-4 text-right">Chuyển đến bàn</div>
        <div class="col-xs-6">
        <select class="form-control" id="to_desk">
            '.$list_desk.'
        </select>
        </div>
    </div>

</div></form>';
$footer_control = '<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="moveDesk()">
        <i class="fa fa-save"></i>
        Lưu lại
    </label>
</div>';


$rainTpl = new RainTPL();
add_more_css('css/custom_mindow.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$custom_script = file_get_contents('script_mindow.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
$rainTpl->draw('mindow_iframe_1column');
?>