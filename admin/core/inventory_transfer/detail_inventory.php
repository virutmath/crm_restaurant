<?
require_once 'inc_security.php';
// lay id phiếu kiểm kê kho hàng
$record_id = getValue('record_id','int','GET',0);
//khai báo biến hiển thị
$content_column = '';
$footer_control = '';
// lay ra thông tin phiếu kiểm kê
// list admin
$list_admin = array();
$db_admin = new db_query('SELECT * FROM admin_users');
while($row_admin = mysqli_fetch_assoc($db_admin->result)){
    $list_admin[$row_admin['adm_id']] = $row_admin['adm_name'];
} unset($db_admin);

// list staff id
$list_staff = array();
$db_users = new db_query('SELECT * FROM users');
while($row_users = mysqli_fetch_assoc($db_users->result)){
    $list_staff[$row_users['use_id']] = $row_users['use_name'];
} unset($db_users);

// list store
$list_store = array();
$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores"');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $list_store[$row_store['cat_id']] = $row_store['cat_name'];
} unset($db_store);

$staff_name = '';
$store_name = '';
$admin_name = '';
$note = '';
$db_inventory = new db_query('SELECT * FROM inventory WHERE inv_id = '.$record_id.'');
while($row_inventory = mysqli_fetch_assoc($db_inventory->result)){
    $staff_name = $list_staff[$row_inventory['inv_staff_id']];
    $store_name = $list_store[$row_inventory['inv_store_id']];
    $admin_name = $list_admin[$row_inventory['inv_admin_id']];
    $note = $row_inventory['inv_note'];
} unset($db_inventory);

$content_column .= '
    <div class="content">
        <div class="inventory_info col-xs-12">
            <div class="row">
                <div class="column_inventory col-xs-6">
                    <label class="inventory_lable">Nhân viên kiểm kê</label>
                    <div class="inventory_span_left"> '.$staff_name.'</div>
                </div>
                <div class="column_inventory col-xs-6">
                    <label class="inventory_lable">Kho hàng</label>
                    <div class="inventory_span_right"> '.$store_name.'</div>
                </div>
            </div>
            <div class="row">
                <div class="column_inventory col-xs-6">
                    <label class="inventory_lable">Người tạo phiếu</label>
                    <div class="inventory_span_left"> '.$admin_name.'</div>
                </div>
                <div class="column_inventory col-xs-6">
                    <label class="inventory_lable">Ghi chú</label>
                    <div class="inventory_span_right"> '.$note.'</div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
';

$listing_product = '';
$list = new dataGrid('inv_product_id',30, '#mindow-listing-product');
$list->add('', 'Mã hàng');
$list->add('', 'Tên mặt hàng');
$list->add('', 'SL trước khi kiểm kê');
$list->add('', 'SL sau khi kiểm kê');

//lay ta ten mt hang
$list_product = array();
$db_product = new db_query('SELECT * FROM products');
while($row_pro = mysqli_fetch_assoc($db_product->result)){
    $list_product[$row_pro['pro_id']] = $row_pro['pro_name'];
}unset($db_product);

// đếm số lượng mặt hàng kiểm kê
$db_count = new db_count('SELECT count(*) as count
                                  FROM inventory_products
                                  WHERE inv_id = '.$record_id.'');
$total = $db_count->total;unset($db_count);

// lấy ra số lượng các phiếu kiểm kê trong thùng rác
$db_listing = new db_query('SELECT * FROM inventory_products
                            WHERE inv_id = '.$record_id.' ORDER BY inv_product_id ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$i = 0;
$listing_product .= $list->showHeader($total_row);
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $listing_product .= $list->start_tr($i, $row['inv_id'], 'class="menu-trash record-item" id="record_'.$row['inv_product_id'].'"  data-record_id="' . $row['inv_product_id'] . '"');
    /* code something */
    $listing_product .= '<td class="center">' .format_codenumber($row['inv_product_id'],6) . '</td>';
    $listing_product .= '<td class="center">' .$list_product[$row['inv_product_id']] . '</td>';
    $listing_product .= '<td class="center">' .$row['inp_quantity_system']. '</td>';
    $listing_product .= '<td class="center">' .$row['inp_quantity_real'] . '</td>';
    $listing_product .= $list->end_tr();
}
$listing_product .= $list->showFooter();

$content_column .= $listing_product;

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$custom_script = file_get_contents('script_half.html');
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