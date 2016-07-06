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
$db_stock_transfer = new db_query('SELECT * FROM stock_transfer WHERE sto_id = '.$record_id.'');
while($row_stock_transfer = mysqli_fetch_assoc($db_stock_transfer->result)){
    $staff_name = $list_staff[$row_stock_transfer['sto_staff_id']];
    $from_store = $list_store[$row_stock_transfer['sto_from_storeid']];
    $to_store = $list_store[$row_stock_transfer['sto_to_storeid']];
    $admin_name = $list_admin[$row_stock_transfer['sto_admin_id']];
    $note = $row_stock_transfer['sto_note'];
} unset($db_stock_transfer);

$content_column .= '
    <div class="content">
        <div class="inventory_info col-xs-12">
            <div class="row">
                <div class="column_inventory col-xs-5">
                    <label class="inventory_lable">Nhân viên chuyển</label>
                    <div class="inventory_span_left"> '.$staff_name.'</div>
                </div>
                <div class="column_inventory_ col-xs-7">
                    <div class="row">
                        <div class="col-xs-7">
                            <label class="inventory_lable">Chuyển từ kho</label>
                            <div class="inventory_span_right" style="width: 120px"> '.$from_store.'</div>

                        </div>
                        <div class="col-xs-1"><i class="fa fa-arrow-right" style="margin:5px"></i></div>
                        <div class="col-xs-4">
                            <div class="inventory_span_right" style="width: 120px"> '.$to_store.'</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="column_inventory_ col-xs-5">
                    <label class="inventory_lable">Người tạo phiếu</label>
                    <div class="inventory_span_left"> '.$admin_name.'</div>
                </div>
                <div class="column_inventory_ col-xs-7">
                    <label class="inventory_lable">Ghi chú</label>
                    <div class="inventory_span_right" style="width:282px"> '.$note.'</div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
';

$listing_product = '';
$list = new dataGrid('sto_product_id',30, '#mindow-listing-product');
$list->add('', 'Mã hàng');
$list->add('', 'Tên mặt hàng');
$list->add('', 'Chuyển sang');

//lay ta ten mt hang
$list_product = array();
$db_product = new db_query('SELECT * FROM products');
while($row_pro = mysqli_fetch_assoc($db_product->result)){
    $list_product[$row_pro['pro_id']] = $row_pro['pro_name'];
}unset($db_product);

// đếm số lượng mặt hàng kiểm kê
$db_count = new db_count('SELECT count(*) as count
                                  FROM stock_transfer_products
                                  WHERE sto_id = '.$record_id.'');
$total = $db_count->total;unset($db_count);

// lấy ra số lượng các phiếu kiểm kê trong thùng rác
$db_listing = new db_query('SELECT * FROM stock_transfer_products
                            WHERE sto_id = '.$record_id.' ORDER BY pro_id ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$i = 0;
$listing_product .= $list->showHeader($total_row);
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $listing_product .= $list->start_tr($i, $row['sto_id'], 'class="menu-trash record-item" id="record_'.$row['pro_id'].'"  data-record_id="' . $row['pro_id'] . '"');
    /* code something */
    $listing_product .= '<td class="center">' .format_codenumber($row['pro_id'],6) . '</td>';
    $listing_product .= '<td class="center">' .$list_product[$row['pro_id']] . '</td>';
    $listing_product .= '<td class="center">' .$row['stp_quantity_transfer']. '</td>';
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
    var mindow_listing_stock_transfer = $('#content-column');
    if(mindow_listing_stock_transfer.find('.enscroll-track').length < 1) {
        mindow_listing_stock_transfer.find('.table-listing-bound').enscroll({
            showOnHover: false,
            minScrollbarLength: 28,
            addPaddingToPane : false
        });
    }
</script>