<?
require_once 'inc_security.php';
// lấy id của bàn đã active
$record_id = getValue('desk_id','int','GET',0);
//select bàn ăn cần chuyển
$desk_name                  = '';
$db_query                   = new db_query('SELECT des_name,sec_name
                                            FROM desks
                                            LEFT  JOIN  sections ON des_sec_id = sec_id
                                            WHERE des_id = '.$record_id.'
                                            LIMIT 1');
$desk_name                  = mysqli_fetch_assoc($db_query->result);unset($db_query);
$desk_name                  = $desk_name['sec_name'] . ' - ' . $desk_name['des_name'];

//select danh sách bàn ăn có thể nhận - các bàn chưa được mở
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
                                    AND des_id NOT IN (SELECT cud_desk_id FROM current_desk)
                               ORDER BY des_id ASC');
while ($row_des = mysqli_fetch_assoc($db_query_des->result)) {
    $row_des['des_name']    = $row_des['sec_name'] . ' - ' . $row_des['des_name'];
    $list_desk[]            = $row_des;
}
unset($db_query_des);

// query các thực đơn có trong bàn ăn được chọn để tách
$left_column = '';
$left_column = '
<div id="mindow-listing-menu">';
// tạo mảng đơn vị tính
$array_unit         = array();
$db_query           = new db_query('SELECT * FROM units');

while ($row         = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']]     = $row['uni_name'];
}
//Danh sách mặt hàng
$listing_menu = '';
$list = new dataGrid('men_id',100,'.table-listing-bound');
$list->add('men_name', 'Tên thực đơn');
$list->add('', 'ĐVT');
$list->add('', 'SL');
// dem ban ghi trong menu
$db_count = new db_count('SELECT count(*) AS count
                          FROM menus
                          INNER JOIN current_desk_menu ON cdm_menu_id = men_id
                          WHERE cdm_desk_id = ' . $record_id);
$total = $db_count->total;
unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM menus
                            INNER JOIN current_desk_menu ON cdm_menu_id = men_id
                            WHERE cdm_desk_id = ' . $record_id.'
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$listing_menu .= $list->showHeader($total_row);

//tổng tiền của bàn
$from_desk_total = 0;
$json_menu = array();

$i = 0;
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $from_desk_total += $row['cdm_price'] * $row['cdm_number'];
    $listing_menu .= $list->start_tr($i, $row['men_id'], 'onclick="deskSplit.activeMenu('.$row['men_id'].',\'from\')" class="menu-normal record-item" data-record_id="' . $row['men_id'] . '"');
    /* code something */
    $listing_menu .= '<td class="center">' . $row['men_name'] . '</td>';
    $listing_menu .= '<td class="center">' . $array_unit[$row['men_unit_id']] . '</td>';
    $listing_menu .= '<td class="center">' . $row['cdm_number'] . '</td>';
    $listing_menu .= $list->end_tr();
    $json_menu[$row['men_id']] = array(
        'men_id'=>$row['men_id'],
        'men_name'=>$row['men_name'],
        'men_number'=>$row['cdm_number'],
        'men_price'=>$row['cdm_price'],
        'men_unit'=>$array_unit[$row['men_unit_id']]
    );
}
$listing_menu               .= $list->showFooter();
$left_column                .= $listing_menu;
$left_column                .= '</div>';


$footer_control = '';


$rainTpl = new RainTPL();
add_more_css('css/custom_desks.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$custom_script = file_get_contents('script_split_desk.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('desk_name',$desk_name);
$rainTpl->assign('json_menu',json_encode($json_menu));
$rainTpl->assign('from_desk_total',number_format($from_desk_total));
$rainTpl->assign('record_id',$record_id);
$rainTpl->assign('list_desk',$list_desk);
$rainTpl->draw('split_desk');
