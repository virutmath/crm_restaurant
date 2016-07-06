<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal', 'str', 'POST', '', 2);
$action = getValue('action', 'str', 'POST', '', 2);

if ($action == 'execute') {
    switch ($action_modal) {
        case 'add_category':
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name', 'cat_name', 0, 0, '', 1, 'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type', 'cat_type', 0, 1, '');
            $myform->add('cat_parent_id', 'cat_parent_id', 1, 0, 0);
            $myform->add('cat_desc', 'cat_desc', 0, 0, '');
            $myform->add('cat_note', 'cat_note', 0, 0, '');
            if (!$myform->checkdata()) {
                $cat_picture = getValue('cat_picture', 'str', 'POST', '');
                if ($cat_picture) {
                    $myform->add('cat_picture', 'cat_picture', 0, 0, '');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới danh mục ' . $last_id . ' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'edit_category':
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name', 'cat_name', 0, 0, '', 1, 'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type', 'cat_type', 0, 1, '');
            $myform->add('cat_parent_id', 'cat_parent_id', 1, 0, 0);
            $myform->add('cat_desc', 'cat_desc', 0, 0, '');
            $myform->add('cat_note', 'cat_note', 0, 0, '');
            if (!$myform->checkdata()) {
                $cat_picture = getValue('cat_picture', 'str', 'POST', '');
                if ($cat_picture) {
                    $myform->add('cat_picture', 'cat_picture', 0, 0, '');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute($myform->generate_update_SQL('cat_id', $record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT, 'Sửa danh mục ' . $record_id . ' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'add_record':
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('pro_name', 'pro_name', 0, 0, '', 1, 'Bạn chưa nhập tên thực đơn');
            $myform->add('pro_unit_id', 'pro_unit_id', 1, 0, '', 1, 'Bạn chưa nhập đơn vị tính');
            $myform->add('pro_cat_id', 'pro_cat_id', 1, 0, '', 0, '');
            $myform->add('pro_instock', 'pro_instock', 1, 0, '', 0, '');
            $myform->add('pro_code', 'pro_code', 0, 0, '', 0, '');
            $myform->add('pro_note', 'pro_note', 0, 0, '', 0, '');
            if (!$myform->checkdata()) {
                $pro_image = getValue('pro_image', 'str', 'POST', '');
                if ($pro_image) {
                    module_upload_picture($pro_image);
                    $myform->add('pro_image', 'pro_image', 0, 0, '');
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //Thêm vào bảng chứa số lượng product_quantity
                //danh sách kho hàng
                $list_stores = category_type('stores');
                $sql_store = 'INSERT INTO product_quantity (product_id, store_id, pro_quantity)
                              VALUES';
                foreach ($list_stores as $store) {
                    $sql_store .= '(' . $last_id . ',' . $store['cat_id'] . ',0),';
                }
                $sql_store = rtrim($sql_store, ',');
                $db_store = new db_execute($sql_store);
                unset($db_store);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới bản ghi ' . $last_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;
        case 'edit_record':
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('pro_name', 'pro_name', 0, 0, '', 1, 'Bạn chưa nhập tên thực đơn');
            $myform->add('pro_unit_id', 'pro_unit_id', 1, 0, '', 1, 'Bạn chưa nhập đơn vị tính');
            $myform->add('pro_cat_id', 'pro_cat_id', 1, 0, '', 0, '');
            $myform->add('pro_instock', 'pro_instock', 1, 0, '', 0, '');
            $myform->add('pro_code', 'pro_code', 0, 0, '', 0, '');
            $myform->add('pro_note', 'pro_note', 0, 0, '', 0, '');
            if (!$myform->checkdata()) {
                $pro_image = getValue('pro_image', 'str', 'POST', '');
                if ($pro_image) {
                    module_upload_picture($pro_image);
                    $myform->add('pro_image', 'pro_image', 0, 0, '');
                }
                $db_insert = new db_execute($myform->generate_update_SQL($id_field, $record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD, 'Chỉnh sửa bản ghi ' . $record_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;
    }
}
//Phần hiển thị
//Khởi tạo
$left_control = '';
$right_control = '';
$footer_control = '';
$left_column = '';
$left_column_title = 'Nhóm mặt hàng';
$right_column = '';
$right_column_title = 'Danh sách mặt hàng';
$context_menu = '';
global $configuration;

$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn, $edit_btn, $trash_btn, 1);
$list_category = category_type($cat_type);

$db_count = new db_count('SELECT count(*) as count FROM ' . $bg_table);
$all_count = $db_count->total;
unset($db_count);
$db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "' . $bg_table . '"');
$trash_count = $db_count->total;
unset($db_count);
ob_start();
?>
    <ul class="list-unstyled list-vertical-crm">
        <li data-cat="all">
            <label class="active cat_name"><b><i class="fa fa-list fa-fw"></i> Tất cả (<?= $all_count ?>)</b></label>
        </li>
        <? foreach ($list_category as $cat) { ?>
            <?
            //nếu cat_parent_id = 0 thì là category cha
            if ($cat['cat_parent_id'] == 0) { ?>
                <li data-cat="<?= $cat['cat_id'] ?>" class="list-vertical-item">
                    <label class="cat_name"><i class="fa fa-minus-square-o collapse-li"></i> <?= $cat['cat_name'] ?>
                    </label>
                    <ul>
                        <?
                        //foreach lại 1 lần nữa trong mảng categoy để lấy ra các category con của cat cha hiện tại
                        foreach ($list_category as $cat_child) {
                            //đếm số bản ghi trong mỗi cat
                            $db_count = new db_count('SELECT count(*) as count FROM ' . $bg_table . ' WHERE ' . $cat_field . ' = ' . $cat_child['cat_id']);
                            $cat_count = $db_count->total;
                            unset($db_count);
                            if ($cat_child['cat_parent_id'] == $cat['cat_id']) {
                                ?>
                                <li data-cat="<?= $cat_child['cat_id'] ?>"
                                    data-parent="<?= $cat_child['cat_parent_id'] ?>" class="list-vertical-item">
                                    <label class="cat_name cat_sub"><i
                                            class="fa fa-caret-right"></i> <?= $cat_child['cat_name'] ?>
                                        (<?= $cat_count ?>)</label>
                                </li>
                            <?
                            }
                        } ?>
                    </ul>
                </li>
            <? } ?>
            <?
        } ?>
        <li data-cat="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?= $trash_count ?>)</b></label>
        </li>
    </ul>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối bên phải, hiển thị list user tương ứng
$right_control = list_admin_control_button($add_btn, $edit_btn, $trash_btn, 1);
//thêm các control khác
//control nhập hàng, yêu cầu quyền mới cho hiển thị
if (getPermissionValue('NHAP_HANG')) {
    $right_control .=
        '<div class="pull-left">
            <span class="control-btn" onclick="importProduct()"><i class="fa fa-download"></i> Nhập hàng</span>
        </div>';
}

//list kho hàng
$list_stores = array();
foreach (category_type('stores') as $store) {
    $list_stores[$store['cat_id']] = $store['cat_name'];
}
#Bắt đầu với datagrid
$list = new dataGrid($id_field, 3000, '#listing-product');
$list->add('pro_id', 'Mã hàng', 'string', 0, 1);
$list->add('pro_code', 'Mã có sẵn', 'string', 0, 1);
$list->add('pro_name', 'Tên hàng', 'string', 1, 1);
$list->add('pro_unit_id', 'Đơn vị tính');
$list->add('pro_instock', 'Tồn tối thiểu');
$list->add('pro_quantity', 'Tồn kho');
$store_id = getValue('store_id', 'int', 'GET', $configuration['con_default_store']);
$list->addSearch('Kho hàng', 'store_id', 'array', $list_stores, getValue('store_id', 'int', 'GET', $store_id));

$sql_search = '';
$sql_search .= ' AND store_id = ' . $store_id . ' ';
//Request with ajax
if ($isAjaxRequest) {
    $cat_id = getValue('cat_id', 'int', 'POST', 0);
    if ($cat_id) {
        $sql_search .= ' AND pro_cat_id = ' . $cat_id;
    }
}
$db_count = new db_count('SELECT count(*) as count
                            FROM ' . $bg_table . '
                            LEFT JOIN product_quantity ON pro_id = product_id
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ');
$total = $db_count->total;
unset($db_count);

$right_column .= '<div id="listing-product">';
$listing_product = '';
$db_listing = new db_query('SELECT *
                            FROM ' . $bg_table . '
                            LEFT JOIN product_quantity ON pro_id = product_id
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' ' . $id_field . ' ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$listing_product .= $list->showHeader($total_row);
$i = 0;
$array_unit = array();
$db_query = new db_query('SELECT * FROM units');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
}
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $listing_product .= $list->start_tr($i, $row[$id_field], 'class="menu-normal record-item" ondblclick="detailRecord()" onclick="active_record(' . $row[$id_field] . ')" data-record_id="' . $row[$id_field] . '"');
    /* code something */
    $pro_unit_id = $row['pro_unit_id'];
    $listing_product .= '<td class="center" width="100">' . format_codenumber($row['pro_id'], 6, PREFIX_PRODUCT_CODE) . '</td>';
    $listing_product .= '<td class="center" width="100">' . $row['pro_code'] . '</td>';
    $listing_product .= '<td>' . $row['pro_name'] . '</td>';
    $listing_product .= '<td class="center" width="100">' . $array_unit[$row['pro_unit_id']] . '</td>';
    $listing_product .= '<td class="center" width="100">' . $row['pro_instock'] . '</td>';
    $listing_product .= '<td class="center" width="100">' . $row['pro_quantity'] . '</td>';
    $listing_product .= $list->end_tr();
}
$listing_product .= $list->showFooter();
$right_column .= $listing_product;
$right_column .= '</div>';
// show tab footer
$footer_control .= '
    <div class="button_tab">
        <ul>
            <li><a href="../products/index.php" id="button_tab_active"><i class="fa fa-list"></i> DANH SÁCH MẶT HÀNG</a></li>
            <li><a href="../inventory_transfer/index.php"><i class="fa fa-check"></i> KIỂM KÊ - CHUYỂN KHO</a></li>
        </ul>
    </div>
';
if ($isAjaxRequest) {
    $action = getValue('action', 'str', 'POST', '');
    switch ($action) {
        case 'pagingAjax':
            echo $listing_product;
            break;
        case 'listRecord':
            echo $right_column;
            break;
    }
    die();
}

$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('module_name', $module_name);
$rainTpl->assign('error_msg', print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control', $left_control);
$rainTpl->assign('right_control', $right_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('left_column_title', $left_column_title);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('right_column_title', $right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script', $custom_script);
$rainTpl->draw('fullwidth_2column');