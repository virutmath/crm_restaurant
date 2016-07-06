<?
    require_once 'inc_security.php';
// lấy ra danh sách các phiếu kiểm kê trong thùng rác
$tra_record_id = getValue('tra_record_id','int','GET',0);
if($tra_record_id) {
    $sql_option = 'AND tra_record_id = ' . $tra_record_id . '';
}else {
    $sql_option = '';
}
$array_row = trash_list('inventory',100,0,$sql_option);
$total_row = count($array_row);

//Lấy ra list cửa hàng
$list_users = array();
$db_query_users = new db_query("SELECT * FROM users");
$list_users = array();
while($row = mysqli_fetch_assoc($db_query_users->result)) {
    $list_users[$row['use_id']] = $row['use_name'];
}unset($db_query_users);

//Lấy ra list admin
$list_admin = array();
$db_query_admin = new db_query("SELECT * FROM admin_users");
$list_admin = array();
while($row = mysqli_fetch_assoc($db_query_admin->result)) {
    $list_admin[$row['adm_id']] = $row['adm_name'];
}unset($db_query_admin);


//Lấy ra list kho hàng
$list_store = array();
$db_query_store = new db_query("SELECT * FROM categories_multi WHERE cat_type = 'stores'");
$list_store = array();
while($row = mysqli_fetch_assoc($db_query_store->result)) {
    $list_store[$row['cat_id']] = $row['cat_name'];
}unset($db_query_store);

//khai báo các phần hiển thị
$footer_control ='';
$content_column ='';
$content_column .= '
<div id="mindow-listing-inventory">';
//Danh sách phiếu kiểm kê
$listing_menu = '';
$list = new dataGrid('tra_id',100, '#mindow-listing-product');
$list->add('tra_record_id', 'Số phiếu', 'string', 1, 0,'Số phiếu');
$list->add('', 'Nhân viên kiểm kê');
$list->add('', 'Ngày kiểm kê');
$list->add('', 'Kho kiểm kê');
$list->add('', 'Người tạo');
$search_sql = ' AND tra_table = "inventory"';

// đếm số lượng phiếu kiểm kê trong thùng rác
$db_count = new db_count('SELECT count(*) as count
                                  FROM trash
                                  WHERE 1' . $list->sqlSearch().$search_sql.'');
$total = $db_count->total;unset($db_count);

// lấy ra số lượng các phiếu kiểm kê trong thùng rác
$db_listing = new db_query('SELECT *
                            FROM trash
                            WHERE 1 ' . $list->sqlSearch().$search_sql.'
                            ORDER BY ' . $list->sqlSort() . ' tra_id DESC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$i = 0;
$listing_menu .= $list->showHeader($total_row);
foreach($array_row as $row){
    $i++;
    if(!$row['inv_staff_id']) {
        $staff_name = '';
    }else{
        $staff_name = $list_users[$row['inv_staff_id']];
    }
    if(!$row['inv_store_id']) {
        $store_name = '';
    }else{
        $store_name = $list_store[$row['inv_store_id']];
    }
    if(!$row['inv_admin_id']) {
        $admin_name = '';
    }else{
        $admin_name = $list_admin[$row['inv_admin_id']];
    }

    $listing_menu .= $list->start_tr($i, $row['inv_id'], 'class="menu-trash record-item" id="record_'.$row['inv_id'].'" onclick="active_record('.$row['inv_id'].')" data-record_id="' . $row['inv_id'] . '"');
    /* code something */
    $listing_menu .= '<td class="center">' .format_codenumber($row['inv_id'],6) . '</td>';
    $listing_menu .= '<td class="center">' .$staff_name . '</td>';
    $listing_menu .= '<td class="center">' .date('d/m/Y H:i',$row['inv_time']). '</td>';
    $listing_menu .= '<td class="center">' .$store_name . '</td>';
    $listing_menu .= '<td class="center">' .$admin_name . '</td>';
    $listing_menu .= $list->end_tr();
}
$listing_menu .= $list->showFooter();

$content_column .= $listing_menu;
$content_column .= '</div>';
$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$custom_script = file_get_contents('custom_script_inventory.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('footer_control',$footer_control);
$rainTpl->draw('mindow_iframe_1column');
?>
<script>
    var mindow_listing_inventory = $('#content-column');
    if(mindow_listing_inventory.find('.enscroll-track').length < 1) {
        mindow_listing_inventory.find('.table-listing-bound').enscroll({
            showOnHover: false,
            minScrollbarLength: 28,
            addPaddingToPane : false
        });
    }
</script>