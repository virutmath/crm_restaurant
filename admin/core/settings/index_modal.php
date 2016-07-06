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

$left_column = '';
$right_column = '';

$left_column .= '
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
    $listing_menu .= $list->start_tr($i, $row['men_id'], 'class="menu-normal record-item" ondblclick="mindowScript.addMenus(' . $row['men_id'] . ')" data-record_id="' . $row['men_id'] . '" data-men_name="' . $row['men_name'] . '" data-men_unit="' . $array_unit[$row['men_unit_id']] . '"');
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
$db_agen = new db_query('SELECT * FROM agencies');
$list_agencies_option .= '<option value="0"> Toàn hệ thống</option>';
while ($row = mysqli_fetch_assoc($db_agen->result)) {
    $list_agencies_option .= '<option value="' . $row['age_id'] . '">' . $row['age_name'] . '</option>';
}

// lấy list danh sách thực đơn đã được thêm ở trường con_start_menu
global $configuration;
$list_menu = '';
$array_start_menu = (json_decode(base64_decode($configuration['con_start_menu']),1));
// query mảng để lấy id và số lượng
$i = 0;
if($array_start_menu != null) {
    foreach ($array_start_menu as $key => $value) {
        $i++;
        $menu_id = $key;
        $menu_quantity = $value;
        //select đơn vị tính
        $array_unit = array();
        $db_query = new db_query('SELECT * FROM units');
        while ($row = mysqli_fetch_assoc($db_query->result)) {
            $array_unit[$row['uni_id']] = $row['uni_name'];
        }

        // select các thực đơn
        $db_menu = new db_query('SELECT men_name,men_unit_id FROM menus WHERE men_id =' . $menu_id . '');
        while ($row_men = mysqli_fetch_assoc($db_menu->result)) {
            $list_menu .= '
        <tr id="record_' . $menu_id . '" class="menu-normal record-item" data-record_id="' . $menu_id . '">
            <td width="15" class="center"><span style="color:#142E62; font-weight:bold">' . $i . '</span></td>
            <td class="center">' . $row_men['men_name'] . '<input type="hidden" disabled id="menu_id" value="' . $menu_id . '"></td>
            <td class="center">' . $array_unit[$row_men['men_unit_id']] . '</td>
            <td class="center"><input type="text" style="width:50px" class="menu_value form-control" value="' . $menu_quantity . '" data-record_id="' . $menu_id . '" id="menu_value_' . $menu_id . '" ></td>
        </tr>';
        }
        unset($db_menu);
    }
}
unset($db_start_menu);


//query các thực đơn có trong mảng




//Các input thông tin khuyễn mãi
$right_control = '<div class="notice"><label>Khi mở bàn mặc định có các thực đơn này vào bàn ăn</label></div>';

$right_column .=
    '
<div id="import-control">
' . $right_control . '
</div>
<div id="listing-import" class="col-xs-12 row">
    <div class="table-listing-bound">
        <table class="table table-bordered table-hover table-listing">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên thực đơn</th>
                    <th>ĐVT</th>
                    <th style="width:50px">SL</th>
                </tr>
            </thead>
            <tbody>
                '.$list_menu.'
            </tbody>
        </table>
    </div>
</div>';


$footer_control = '
<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="mindowScript.defaultMenus()">
        <i class="fa fa-save"></i>
        Lưu lại
    </label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom_modal.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script_modal.html');
$rainTpl->assign('custom_script', $custom_script);

$rainTpl->draw('mindow_modal_2column');