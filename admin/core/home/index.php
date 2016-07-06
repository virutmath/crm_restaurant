<?
require_once 'inc_security.php';
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


//Phần xử lý
$action_modal = getValue('action_modal', 'str', 'POST', '', 2);
$action = getValue('action', 'str', 'POST', '', 2);
//Phần hiển thị
//Khởi tạo
//List thực đơn
$listing_menu = '';
//Thông tin nhà hàng
$restaurant_info = array('res_name' => '', 'res_address' => '', 'res_phone' => '');

//Khu vực, bàn ăn
$list_desk = array();
//menu chuột phải
$context_menu = '';


//cửa hàng mặc định
$age_id = $configuration['con_default_agency'];
//thông tin về cửa hàng
$db_agency = new db_query('SELECT * FROM agencies WHERE age_id = ' . $age_id);
$agency_data = mysqli_fetch_assoc($db_agency->result);
unset($db_agency);
$restaurant_info['res_name'] = $configuration['con_restaurant_name'];
$restaurant_info['res_address'] = $configuration['con_restaurant_address'];
$restaurant_info['res_phone'] = $configuration['con_restaurant_phone'];


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');


//Hiển thị danh sách thực đơn
if (!DISPLAY_LISTING_MENU_BY_CATEGORY) {
    //lấy ra danh sách thực đơn đang có
    $list = new dataGrid('men_id', $listing_menu_size, '#listing-menu');
    $list->add('men_name', 'Tên thực đơn', 'string', 1, 0);
    $list->add('', 'ĐVT');
    $list->add('', 'Giá bán');
    $list->addSearch('', 'men_cat_id', 'array', $men_cat_id, getValue('men_cat_id'));
    $sql_search = '';
    $search_cat_id = getValue('men_cat_id');
    if ($search_cat_id) {
        $sql_search .= ' AND men_cat_id = ' . $search_cat_id . ' ';
    }
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
    $array_unit = array();
    $db_query = new db_query('SELECT * FROM units');
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $array_unit[$row['uni_id']] = $row['uni_name'];
    }
    while ($row = mysqli_fetch_assoc($db_listing->result)) {
        $i++;
        $listing_menu .= $list->start_tr($i, $row['men_id'], 'class="menu-normal record-item" ondblclick="addMenuToDesk(' . $row['men_id'] . ')" data-record_id="' . $row['men_id'] . '"');
        /* code something */
        $listing_menu .= '<td class="text-left">' . $row['men_name'] . '</td>';
        $listing_menu .= '<td class="center">' . $array_unit[$row['men_unit_id']] . '</td>';
        $listing_menu .= '<td class="text-right">' . format_number($row['men_price']) . '</td>';
        $listing_menu .= $list->end_tr();
    }
    $listing_menu .= $list->showFooter();

//phân trang ajax
//ajax paging
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //catching ajax request
        $action = getValue('action', 'str', 'POST', '', 3);
        switch ($action) {
            case 'searchAjax' :
            case 'pagingAjax' :
                $container = getValue('container', 'str', 'POST', '', 3);
                if ($container == '#listing-menu') {
                    echo $listing_menu;
                    die();
                }
                break;
        }
        $search_ajax = getValue('search', 'int', 'GET', 0);
        if ($search_ajax) {
            echo $listing_menu;
            die();
        }
        die();
    }
} else {
    //Hiển thị theo cấp danh mục
    //thêm css cấp danh mục
    add_more_css('css/custom_list_menu.css',$load_header);
    $list_category_menu = array();
    $db = new db_query('SELECT cat_id,cat_name FROM categories_multi WHERE cat_type = "menus" AND cat_parent_id = 0');
    while($row = mysqli_fetch_assoc($db->result)) {
        $count = 0;
        //lấy ra các danh mục con
        $db_cat_child = new db_query('SELECT cat_id,cat_name FROM categories_multi WHERE cat_type = "menus" AND cat_parent_id = ' . $row['cat_id']);
        while($row_cat_child = mysqli_fetch_assoc($db_cat_child->result)) {
            //lấy ra các menu con của danh mục này
            $db_menu = new db_query('SELECT men_id,men_name FROM menus WHERE men_cat_id = '. $row_cat_child['cat_id']);
            $list_menu = $db_menu->resultArray();
            $count += count($list_menu);
            $row_cat_child['list_menu_child'] = $list_menu;
            $row_cat_child['count_menu'] = count($list_menu);
            $row['list_cat_child'][] = $row_cat_child;
        }
        //lấy ra các menu của danh mục này
        $db_menu = new db_query('SELECT men_id,men_name FROM menus WHERE men_cat_id = ' . $row['cat_id']);
        $list_menu = $db_menu->resultArray();
        $row['list_menu_child'] = $list_menu;
        $row['count_menu'] = $count + count($list_menu);
        unset($db_menu);
        unset($db_cat_child);
        $list_category_menu[] = $row;
    }
    $tmpTpl = new RainTPL();
    $tmpTpl->assign('list_category_menu',$list_category_menu);
    $listing_menu = $tmpTpl->draw('v2/home/home_list_menu',1);
}

//Lấy ra các bàn đang mở để active
$desk_active_id = array();
$db_current_desk = new db_query('SELECT * FROM current_desk');
while ($row = mysqli_fetch_assoc($db_current_desk->result)) {
    $desk_active_id[] = $row['cud_desk_id'];
}
//lấy ra danh sách khu vực bàn ăn
$list_desk = array();
$db_query = new db_query('SELECT *
                          FROM sections
                          LEFT JOIN service_desks ON sec_service_desk = sed_id
                          WHERE sed_agency_id = ' . $age_id);
while ($row = mysqli_fetch_assoc($db_query->result)) {
    //select ra các bàn trong section này
    $db_desk = new db_query('SELECT * FROM desks WHERE des_sec_id = ' . $row['sec_id']);
    while ($row_desk = mysqli_fetch_assoc($db_desk->result)) {
        $row_desk['full_name'] = $row['sec_name'] . ' - ' . $row_desk['des_name'];
        //Nếu bàn này có trong list active thì thêm active vào
        $row_desk['active'] = in_array($row_desk['des_id'], $desk_active_id);
        $row['list_desk'][] = $row_desk;
    }
    unset($db_desk);
    $row['count'] = isset($row['list_desk']) ? count($row['list_desk']) : 0;
    $list_desk[] = $row;
}


$rainTpl = new RainTPL();
add_more_css('css/custom.css', $load_header);
add_more_css('css/custom.css', $load_header, 'print');
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('module_name', $module_name);
$rainTpl->assign('error_msg', print_error_msg($bg_errorMsg));
$rainTpl->assign('permission_print_order',getPermissionValue('IN_CHE_BIEN'));
$rainTpl->assign('pay_type_cash',PAY_TYPE_CASH);
$rainTpl->assign('pay_type_card',PAY_TYPE_CARD);
$rainTpl->assign('listing_menu', $listing_menu);
$rainTpl->assign('restaurant_info', $restaurant_info);
$rainTpl->assign('list_desk', $list_desk);
$rainTpl->assign('list_menu_json',json_encode($list_category_menu));

$rainTpl->draw('/v2/home/home');