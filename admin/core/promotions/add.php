<?
require_once 'inc_security.php';
//kiểm tra quyền nhập hàng
checkCustomPermission('add');
//Đếm tất cả số thực đơn đang có
$db_count = new db_count('SELECT count(*) AS count FROM menus');
$all_count = $db_count->total;
unset($db_count);
//Lấy danh mục thực đơn
$men_cat_id = array('' => 'Tất cả (' . $all_count . ')');
$db_query = new db_query('SELECT * FROM categories_multi WHERE cat_type = "menus"');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    //đếm số thực đơn trong cate này
    $db_count = new db_count('SELECT count(*) AS count FROM menus WHERE men_cat_id = ' . $row['cat_id']);
    $men_count = $db_count->total;
    unset($db_count);
    $men_cat_id[$row['cat_id']] = $row['cat_name'] . ' (' . $men_count . ')';
}

$mindow_title = 'Thêm mới chiến dịch khuyến mãi';
$left_column = '';
$right_column = '';

$left_column .= '
<div class="text-center section-title">Danh sách thực đơn</div>
<div id="mindow-listing-menu">';

//Danh sách mặt hàng
$listing_menu = '';
$list = new dataGrid('men_id',100, '#mindow-listing-menu');
$list->add('men_name', 'Tên thực đơn', 'string', 1, 0);
$list->add('', 'ĐVT');
$list->addSearch('', 'men_cat_id', 'array', $men_cat_id, getValue('men_cat_id'));
$sql_search = '';
$search_cat_id = getValue('men_cat_id','int','GET',0);
if ($search_cat_id) {
    $sql_search .= ' AND men_cat_id = ' . $search_cat_id . ' ';
}
// dem ban ghi trong menu
$db_count = new db_count('SELECT count(*) AS count
                          FROM menus
                          WHERE 1 ' . $list->sqlSearch() . $sql_search);
$total = $db_count->total;
unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM menus
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' men_id ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);

$listing_menu .= $list->showHeader($total_row);

$i = 0;
// tạo mảng đơn vị tính
$array_unit = array();
$db_query = new db_query('SELECT * FROM units');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
}
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $listing_menu .= $list->start_tr($i, $row['men_id'], 'class="menu-normal record-item" ondblclick="mindowScript.addMenus(' . $row['men_id'] . ')" data-record_id="' . $row['men_id'] . '" data-men_name="' . $row['men_name'] . '" data-pro_unit="' . $array_unit[$row['men_unit_id']] . '"');
    /* code something */
    $listing_menu .= '<td class="center">' . $row['men_name'] . '</td>';
    $listing_menu .= '<td class="center">' . $array_unit[$row['men_unit_id']] . '</td>';
    $listing_menu .= $list->end_tr();
}
$listing_menu .= $list->showFooter();
//phân trang ajax
//ajax paging
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //catching ajax request
    $action = getValue('action','str','POST','',3);
    switch($action) {
        case 'searchAjax' :
        case 'pagingAjax' :
            $container = getValue('container','str','POST','',3);
            if($container == '#listing-menu') {
                echo $listing_menu;die();
            }
            break;
    }
    $search_ajax = getValue('search','int','GET',0);
    if($search_ajax) {
        echo $listing_menu;die();
    }
    die();
}

$left_column .= $listing_menu;
$left_column .= '</div>';



//List cơ sở
$list_agencies = array();
$list_agencies_option = '';
$db_agen = new db_query('SELECT * FROM agencies ORDER BY age_id ASC ');
while ($row = mysqli_fetch_assoc($db_agen->result)) {
    $list_agencies_option .= '<option value="' . $row['age_id'] . '">' . $row['age_name'] . '</option>';
}

//Các input thông tin khuyễn mãi
$right_control = '
<div class="col-xs-6">
    <div class="row">
        <div class="row-title">Tên chiến dịch</div>
        <div class="row-control">
            <input type="text" name="promo_name" id="promo_name">
        </div>
    </div>
    <div class="row">
        <div class="row-title fl">Áp dụng</div>
        <div class="row-control">
            <input type="text" class="form-control fl input-date" placeholder="dd/mm/yy" datepick-element="1" id="promo_start_date" style="width:115px;margin-right:5px">
            <input type="text" class="form-control fl" placeholder="00" id="promo_start_time_h" style="width:38px;"><span class="fl">:</span>
            <input type="text" class="form-control fl" placeholder="00" id="promo_start_time_i" style="width:38px;">
        </div>
    </div>
    <div class="row">
        <div class="row-title fl">Giảm giá/Hóa đơn</div>
        <div class="row-control">
            <input type="text" name="value_sale" id="promo_value" value="0" class="form-control fl" style="float:left;width:95px;margin-right:5px">
            <select name="type_sale" id="promo_type" class="form-control" style="width:100px">
                <option value="1"><i class="fa fa-pencil"></i>Phần trăm</option>
                <option value="2"><i class=" fa fa-money"></i>Tiền mặt</option>
            </select>
        </div>
    </div>
</div>
<div class="col-xs-6">
    <div class="row">
        <div class="row-title">Cửa hàng tại</div>
        <div class="row-control">
            <select name="bio_agencies_id" id="bio_agencies_id" class="form-control">
                ' . $list_agencies_option . '
            </select>
        </div>
    </div>
    <div class="row">
        <div class="row-title center"><i class="fa fa-arrow-right"></i></div>
        <div class="row-control">
            <input type="text" class="form-control fl input-date" placeholder="dd/mm/yy" datepick-element="1" id="promo_end_date" style="width:115px;margin-right:5px">
            <input type="text" class="form-control fl" placeholder="00" id="promo_end_time_h" style="width:38px;"><span class="fl">:</span>
            <input type="text" class="form-control fl" placeholder="00" id="promo_end_time_i" style="width:38px;">
        </div>
    </div>
    <div class="row">
        <div class="row-title center">Điều kiện áp dụng</div>
        <div class="row-control">
            <input type="text" name="condition_promo" value="0" id="promo_condition" style="width:180px;">
        </div>
        <div class="tooltip-info" title="chỉ áp dụng cho những hóa đơn có tổng mức thanh toán"><i class="fa fa-info-circle" id="info-condition"></i></div>
    </div>
</div>
';

$right_column .=
    '<div class="text-center section-title">Thông tin chiến dịch khuyến mãi</div>
<div id="import-control">
' . $right_control . '
</div>
<div id="listing-import" class="col-xs-12 row">
    <div class="table-listing-bound">
        <table class="table table-bordered table-hover table-listing">
            <thead>
                <tr>
                    <th width="40">STT</th>
                    <th>Tên thực đơn</th>
                    <th width="100px">Giảm giá</th>
                    <th width="100px">Giảm theo tiền</th>

                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>';

$footer_control = '
<div class="footer-control">
    <div class="col-xs-5">
        <div class="row">
            <div class="row-title">Ghi chú: </div>
            <div class="row-control">
                <textarea class="note-promo" name="note_promo" id="promo_note"></textarea>
            </div>
        </div>
    </div>
    <div class="col-xs-7">
        <div class="notice-promo">
            <strong>Lưu ý: </strong>Không thể áp dụng 2 chiến dịch khuyễn mãi trong cùng một khoảng thời gian<br/>
            - Không thể tạo chiến dịch mới có toàn bộ thời gian hoặc một phần thời gian nằm trong chiến dịch đã có<br/>
            - Khi giảm giá trên cả hóa đơn và chi tiết mặt hàng, thì hệ thống sẽ giảm mặt hàng trước và giảm toàn hóa đơn sau<br/>
        </div>
    </div>
</div>
';
$right_column .= $footer_control;

$footer_control = '

<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="mindowScript.addPromotions()">
        <i class="fa fa-save"></i>
        Thêm mới
    </label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('mindow_title', $mindow_title);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script_import.html');
$rainTpl->assign('custom_script', $custom_script);

$rainTpl->draw('mindow_iframe_2column');