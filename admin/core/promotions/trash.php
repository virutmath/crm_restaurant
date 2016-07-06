<?
require_once 'inc_security.php';
    // khai vao bien de hien thi
    $content_column = '';
    $footer_control = '';
$content_column .= '<div id="mindow-listing-promotions">';
    $class_context_menu = 'menu-trash';
    $list = new dataGrid('pms_id',100, '#mindow-listing-promotions');
    $list->add('','Cửa hàng');
    $list->add('','Tên chiến dịch');
    $list->add('','Ngày bắt đầu');
    $list->add('','Ngày kết thúc');
    $list->add('','Giảm');
    $list->add('','Kiểu KM');
    $list->add('','Điều kiện HĐ');

    $db_count = new db_count('SELECT count(*) as count
                                  FROM trash
                                  WHERE tra_table = "'.$bg_table.'"
                                        ');
    $total = $db_count->total;unset($db_count);
    $array_row = trash_list($bg_table,30,0);
    $list->limit($total);
    $total_row = count($array_row);
    $content_column .= $list->showHeader($total_row,'','id="table-trash"');
    $i = 0;
    //Lấy ra list cửa hàng
    $list_agencies = array();
    $db_query_agencies = new db_query("SELECT * FROM agencies");
    $list_agencies = array();
    while($row = mysqli_fetch_assoc($db_query_agencies->result)) {
        $list_agencies[$row['age_id']] = $row['age_name'];
    }unset($db_query_agencies);
    // select các bản ghi trong thung rác
    foreach($array_row as $row){
        $i++;
        $content_column .= $list->start_tr($i,$row[$id_field],'class="'.$class_context_menu.' record-item" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
        //cửa hàng
        $content_column .='<td class="text-left">'.$list_agencies[$row['pms_agency_id']].'</td>';
        //tên chiến dịch
        $content_column .='<td class="text-left">'.$row['pms_name'].'</td>';
        //Ngày bắt đầu
        $content_column .= '<td class="center">' . date('d/m/Y H:i', $row['pms_start_time']) . '</td>';
        //Ngày kết thúc
        $content_column .= '<td class="center">' . date('d/m/Y H:i', $row['pms_end_time']) . '</td>';
        // giảm giá
        $content_column .= '<td class="center">' .  $row['pms_value_sale'] . '</td>';
        //kiểu khuyến mãi
        $content_column .= '<td class="center">' .  ($row['pms_type_sale']?'% phần trăm':'$ tiền mặt') . '</td>';
        //số tiền
        $content_column .= '<td class="text-right">' . number_format($row['pms_condition']) . '</td>';
        $content_column .= $list->end_tr();
    }
$content_column .= $list->showFooter();
$content_column .= '</div>';
$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$custom_script = file_get_contents('script_trash.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
$rainTpl->draw('mindow_iframe_1column');
?>