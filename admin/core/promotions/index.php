<?
require_once 'inc_security.php';

//Phần hiển thị
//Khởi tạo
$left_control       = '';
$right_control      = '';
$footer_control     = '';
$left_column        = '';
$left_column_title  = 'Danh sách chiến dịch khuyến mãi';
$right_column       = '';
$right_column_title = 'Chi tiết khuyến mãi';
$context_menu       = '';
$list_menu_promo    = '';


// lay gia tri ajax  de hien thi right_column
$record_id  = getValue('record_id', 'int', 'POST', 0);
$db_query   = new db_query('SELECT *FROM promotions WHERE pms_id = "' . $record_id . '"');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $right_column = '';
    $right_column .= '<div class="info_promo">
                <div class="row promo_pad">
                    <div class="col-xs-12">
                        <label class="pull_left">Tên chiến dịch:</label>
                        <div class="promotion_custom">
                            <span class="promo_span pull_left">' . $row['pms_name'] . ' </span>
                        </div>
                    </div>
                </div>
                <div class="row promo_pad">
                    <div class="col-xs-12">
                        <label class="pull_left">Thời gian từ</label>
                        <div class="promotion_custom">
                            <span class="promo_time pull_left">' . date('d/m/Y H:i', $row['pms_start_time']) . ' </span>
                            <div class="arrow-pull"><i class="fa fa-arrow-right "></i></div>
                            <span class="promo_time pull_left"> ' . date('d/m/Y H:i', $row['pms_end_time']) . '</span>
                        </div>
                    </div>
                </div>
                <div class="row promo_pad">
                    <div class="col-xs-12">
                        <label class="pull_left">Giảm giá/Hóa đơn</label>
                        <div class="promotion_custom">
                            <span class="promo_time pull_left"> ' . $row['pms_value_sale'] . '</span>
                            <div class="arrow-pull"><label>Điều kiện:</label></div>
                            <span class="promo_time pull_left">Tổng tiền > ' . number_format($row['pms_condition']) . '  </span>
                        </div>
                    </div>
                </div>
                <div class="row promo_pad">
                    <div class="col-xs-12">
                        <label class="pull_left">Áp dụng tại:</label>
                        <div class="promotion_custom">
                            <span class="promo_span pull_left"> Toàn hệ thống</span>
                        </div>
                    </div>
                </div>
                <div class="row promo_pad">
                    <div class="col-xs-12">
                        <label class="pull_left">Ghi chú:</label>
                        <div class="promotion_custom">
                            <span class="promo_span pull_left"> ' . $row['pms_note'] . '</span>
                        </div>
                    </div>
                </div>
            </div>';
}
unset($db_query);

// hien thi danh sach menu duoc giam gia

$right_column .= '
            <div class="pro_title">Danh sách mặt hàng giảm giá</div>
            <div class="list-table-menu">';

#Bắt đầu với datagird
$list = new dataGrid('men_id', 30);
$list->add('', 'Tên mặt hàng');
$list->add('', 'Giảm giá');
$list->add('', 'Giảm theo tiền');


// select danh sach menu
$array_menu = array();
$db_menu = new db_query('SELECT * FROM menus');
while ($row = mysqli_fetch_assoc($db_menu->result)) {
    $array_menu[$row['men_id']] = $row['men_name'];
}
unset($db_menu);

// slect list danh sách mặt hàng giảm giá trong chiến dịch
$db_count = new db_count('SELECT count(*) as count
                            FROM promotions_menu
                            WHERE pms_id = ' . $record_id . '
                            ');
$total = $db_count->total;
unset($db_count);

// select cac mat hang co trong khuyen mai
$db_menu_list = new db_query('SELECT *FROM promotions_menu WHERE pms_id = "' . $record_id . '"' . $list->limit($total));
$total_row = mysqli_num_rows($db_menu_list->result);

$right_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
$i = 0;
while ($row_menu = mysqli_fetch_assoc($db_menu_list->result)) {
    $i++;
    $right_column .= $list->start_tr($i, $row['men_id'], 'class="menu-normal record-item"  dblclick="detail_record(' . $row['men_id'] . ')" onclick="active_record(' . $row['men_id'] . ')" data-record_id="' . $row['men_id'] . '"');
    /* code something */
    $right_column .= '<td class="center" width="">' . $array_menu[$row_menu['pms_menu_id']] . '</td>';

    $right_column .= '<td width="120" class="center">' . number_format($row_menu['pms_menu_value']) . '</td>';

    $right_column .= '<td class="center">' . ($row_menu['pms_menu_type'] ? '<input type="checkbox" disabled checked>' : '<input type="checkbox" disabled>') . '</td>';

    $right_column .= $list->end_tr();
}
unset($db_menu_list);
$right_column .= $list->showFooter();
$right_column .= '</div>';
// hien thi kieu ajax
if ($isAjaxRequest) {
    $action = getValue('action', 'str', 'POST', '');
    if ($action == 'showRecord') {
        echo $right_column;
        die();
    }
}


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
// dem ban ghi trong thung rac
$db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "' . $bg_table . '"');
$trash_count = $db_count->total;
unset($db_count);
if ($add_btn || $edit_btn || $trash_btn) {
    $left_control .=
        '<div class="modal-control">
            <span class="control-btn control-btn-add" onclick="addPromo()"><i class="fa fa-file-o"></i> Thêm mới</span>
            <span class="control-btn control-btn-edit deactivate"><i class="fa fa-edit"></i> Sửa</span>
            <span class="control-btn control-btn-trash deactivate"><i class="fa fa-trash"></i> Xóa</span>
            <span class="control-btn control-btn-recover" onclick="list_trash(\'out\')"><i class="fa fa-recycle"></i> Thùng rác (' . $trash_count . ')</span>
        </div>';
}


$right_control = list_admin_control_button(0, 0, 0, 1);
#Bắt đầu với datagrid
$list = new dataGrid($id_field, 30);
$list->add('pms_agency_id', 'Cửa hàng');
$list->add('pms_name', 'Tên chiến dịch');
$list->add('pms_start_time', 'Thòi gian bắt đầu');
$list->add('pms_end_time', 'Thời gian kết thúc');

$db_count = new db_count('SELECT count(*) as count
                            FROM ' . $bg_table . '
                            WHERE 1 ' . $list->sqlSearch() . '
                            ');
$total = $db_count->total;
unset($db_count);
// select agencies
$array_agencies = array();
$db_query_agen = new db_query('SELECT * FROM agencies');
while ($row = mysqli_fetch_assoc($db_query_agen->result)) {
    $array_agencies[$row['age_id']] = $row['age_name'];
}
unset($db_query_agen);

$db_listing = new db_query('SELECT *
                            FROM ' . $bg_table . '
                            WHERE 1 ' . $list->sqlSearch() . '
                            ORDER BY ' . $list->sqlSort() . ' ' . $id_field . ' ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$left_column .= $list->showHeader($total_row);
$i = 0;
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $left_column .= $list->start_tr($i, $row[$id_field], 'class="menu-normal record-item" onclick="active_record(' . $row[$id_field] . ')" data-record_id="' . $row[$id_field] . '"');
    /* code something */
    $left_column .= '<td class="text-left" width="">' . $array_agencies[$row['pms_agency_id']] . '</td>';
    $left_column .= '<td class="text-left" width="">' . $row['pms_name'] . '</td>';
    $left_column .= '<td class="center" width="">' . date('d/m/Y H:i', $row['pms_start_time']) . '</td>';
    $left_column .= '<td class="center" width="">' . date('d/m/Y H:i', $row['pms_end_time']) . '</td>';
    $left_column .= $list->end_tr();
}
$left_column .= $list->showFooter();
$footer_control .= '
    <div class="clearfix"></div>
    <div class="button_tab">
        <ul>
             <li><a href="../customers/index.php" > <i class="fa fa-list"></i> DANH SÁCH KHÁCH HÀNG</a></li>
            <li><a href="#" id="button_tab_active"><i class="fa fa-bullhorn" ></i> CHIẾN DỊCH KHUYẾN MÃI</a></li>
        </ul>
    </div>
';

$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('module_name', $module_name);
$rainTpl->assign('error_msg', print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control', $left_control);
$rainTpl->assign('right_control', $right_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('left_column_title', $left_column_title);
$rainTpl->assign('right_column_title', $right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script', $custom_script);
$rainTpl->draw('fullwidth_2column_promo');