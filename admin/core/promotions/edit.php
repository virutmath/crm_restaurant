<?
require_once 'inc_security.php';
//kiểm tra quyền nhập hàng
checkCustomPermission('edit');
// lấy record_id bảng promotion
$record_id = getValue('record_id','int','GET',0);
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
$search_cat_id = getValue('men_cat_id');
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

$left_column .= $listing_menu;
$left_column .= '</div>';




//query ra các input thông tin khuyễn mãi cần cập nhật
$db_promo = new db_query('SELECT *FROM promotions WHERE pms_id = ' . $record_id . ' LIMIT 1');
$detail_promotion = mysqli_fetch_assoc($db_promo->result);unset($db_promo);


//List cơ sở
$list_agencies = array();
$list_agencies_option = '';
$db_agen = new db_query('SELECT * FROM agencies ORDER BY age_id ASC ');
while ($row = mysqli_fetch_assoc($db_agen->result)) {
    if($row['age_id'] == $detail_promotion['pms_agency_id']){
        $check_agencies = 'selected="selected"';
    } else {
        $check_agencies = '';
    }
    $list_agencies_option .= '<option value="' . $row['age_id'] . '" '.$check_agencies.'>' . $row['age_name'] . '</option>';
}unset($db_agen);

// kieu thanh toan
$array_type_sale ='
    <option value="1" '.($detail_promotion['pms_type_sale'] == PROMOTION_TYPE_PERCENT ? 'selected' : '').'><i class="fa fa-pencil"></i>Phần trăm</option>
    <option value="2" '.($detail_promotion['pms_type_sale'] == PROMOTION_TYPE_MONEY ? 'selected' : '').'><i class=" fa fa-money"></i>Tiền mặt</option>
';

// select danh sach menu
$array_menu = array();
$db_menu = new db_query('SELECT * FROM menus');
while($row = mysqli_fetch_assoc($db_menu->result)){
    $array_menu[$row['men_id']] = $row['men_name'];
} unset($db_menu);


// select cac mat hang co trong khuyen mai
$list_menu_promo = '';
//mảng chứa menuItem để fill vào menuList bên javascript
$array_menuList = array();
$db_menu_list = new db_query('SELECT * FROM promotions_menu WHERE pms_id = "'.$record_id.'"');
$i=0;
while($row_menu = mysqli_fetch_assoc($db_menu_list->result)) {
    $i++;
    $array_menuList[$row_menu['pms_menu_id']] = array(
        'men_id' => $row_menu['pms_menu_id'],
        'men_name'=> $array_menu[$row_menu['pms_menu_id']],
        'men_value' => $row_menu['pms_menu_value'],
        'men_type' => $row_menu['pms_menu_type']
    );
    $list_menu_promo .='<tr class="menu-normal record-item" id="record_'.$row_menu['pms_menu_id'].'" onclick="mindowScript.activeMenuImportById('.$row_menu['pms_menu_id'].')"  data-record_id="' . $row_menu['pms_menu_id'] . '" >
                            <td class="center">'.$i.'</td>
                            <td class="center">'.$array_menu[$row_menu['pms_menu_id']].' <input type="hidden" disabled id="menu_id"></td>
                            <td class="center"><input type="text"  class="menu_value" value="'.$row_menu['pms_menu_value'].'" data-record_id="' . $row_menu['pms_menu_id'] . '" id="menu_value_' . $row_menu['pms_menu_id'] . '" ></td>
                            <td class="center">'.($row_menu['pms_menu_type'] ? '<input class="menu_type" value="1" data-men_type="'.$row_menu['pms_menu_id'].'" type="checkbox" checked >' :'<input class="menu_type" value="0  " data-men_type="'.$row_menu['pms_menu_id'].'" class="menu_type" type="checkbox">') .'</td>
                        </tr>';
} unset($db_menu_list);
// hien thi cot ben phai
$right_column .=
    '<div class="text-center section-title">Thông tin chiến dịch khuyến mãi</div>
<div id="import-control">
    <div class="col-xs-6">
        <div class="row">
            <div class="row-title">Tên chiến dịch</div>
            <div class="row-control">
                <input type="text" class="form-control fl" name="promo_name" id="promo_name" value="'.$detail_promotion['pms_name'].'">
                <input type="hidden" value="'.$detail_promotion['pms_id'].'" id="promo_id">
            </div>
        </div>
        <div class="row">
            <div class="row-title fl">Áp dụng</div>
            <div class="row-control">
                <input type="text" class="form-control fl input-date" value="'.date('d/m/Y',$detail_promotion['pms_start_time']).'" placeholder="dd/mm/yy" datepick-element="1" id="promo_start_date" style="width:115px;margin-right:5px">
                <input type="text" class="form-control fl" placeholder="00" value="'.date('h',$detail_promotion['pms_start_time']).'" id="promo_start_time_h" style="width:38px;"><span class="fl">:</span>
                <input type="text" class="form-control fl" placeholder="00" value="'.date('i',$detail_promotion['pms_start_time']).'" id="promo_start_time_i" style="width:38px;">
            </div>
        </div>
        <div class="row">
            <div class="row-title fl">Giảm giá/Hóa đơn</div>
            <div class="row-control">
                <input type="text" name="value_sale" value="'.$detail_promotion['pms_value_sale'].'" id="promo_value" class="form-control fl" style="width:95px;margin-right:5px">
                <select name="type_sale" id="promo_type" class="form-control" style="width:100px">
                    '.$array_type_sale.'
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
                <input type="text" class="form-control fl input-date" value="'.date('d/m/Y',$detail_promotion['pms_end_time']).'" placeholder="dd/mm/yy" datepick-element="1" id="promo_end_date" style="width:115px;margin-right:5px">
                <input type="text" class="form-control fl" value="'.date('h',$detail_promotion['pms_end_time']).'" placeholder="00" id="promo_end_time_h" style="width:38px;"><span class="fl">:</span>
                <input type="text" class="form-control fl" value="'.date('i',$detail_promotion['pms_end_time']).'" placeholder="00" id="promo_end_time_i" style="width:38px;">
            </div>
        </div>
        <div class="row">
            <div class="row-title center">Điều kiện áp dụng</div>
            <div class="row-control">
                <input type="text" class="form-control" value="'.$detail_promotion['pms_condition'].'" name="condition_promo" id="promo_condition" style="width:180px;">
            </div>
            <div class="tooltip-info" title="chỉ áp dụng cho những hóa đơn có tổng mức thanh toán"><i class="fa fa-info-circle" id="info-condition"></i></div>
        </div>
    </div>
</div>
<!-- danh sách mặt hàng giảm giá-->
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
            '.$list_menu_promo.'
        </table>
    </div>
</div>
<!-- ghi chú-->
<div class="footer-control">
    <div class="col-xs-5">
        <div class="row">
            <div class="row-title">Ghi chú: </div>
            <div class="row-control">
                <textarea class="note-promo" name="note_promo" id="promo_note">'.$detail_promotion['pms_note'].'</textarea>
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

$footer_control = '
<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="mindowScript.editPromotions()">
        <i class="fa fa-save"></i>
        Lưu lại
    </label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script_import.html');
$string_menuList = $array_menuList ? json_encode($array_menuList) : '{}';
$custom_script .=
    '<script>
mindowScript.initMenuList('.$string_menuList.');
</script>';

$rainTpl->assign('custom_script', $custom_script);

$rainTpl->draw('mindow_iframe_2column');