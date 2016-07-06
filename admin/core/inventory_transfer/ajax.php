<?
require_once 'inc_security.php';

//viết theo class Ajax
class InventoryAjax extends AjaxCommon
{
    // xóa phiếu kiểm kê
    function deleteInventory()
    {
        //Kiểm tra quyền sửa xóa
        checkPermission('trash');
        $record_id      = getValue('record_id', 'int', 'POST', 0);
        //Kiểm tra xem đây có fai hóa đơn sinh ra từ hệ thống không
        //check quyền xóa
        checkPermission('trash');
        $array_return   = array();
        $db_data        = new db_query('SELECT * FROM inventory WHERE inv_id = ' . $record_id . ' LIMIT 1');
        $cuscat_data    = mysqli_fetch_assoc($db_data->result);unset($db_data);

        move2trash('inv_id', $record_id, 'inventory', $cuscat_data);
        // Khi xóa phiếu kiểm kê thì trừ số lượng sản phẩm đã kiểm kê trước đó.
        if($record_id){
            // Khi khôi phục thì cộng hoặc trừ số lượng đã kiểm kê
            $arr_product = array();
            $db_query_inv = new db_query('SELECT inv_product_id FROM inventory_products WHERE inv_id = '.$record_id.'');
            while($row_inv = mysqli_fetch_assoc($db_query_inv->result)){
                // query xem số lượng chênh lệch của sản phẩm(product_id) khi kiểm kho
                $db_quantity_inv = new db_query('SELECT * FROM inventory_products
                                                 WHERE inv_product_id = '.$row_inv['inv_product_id'].'
                                                 AND inv_id = '.$record_id.'');
                $row_quantity = mysqli_fetch_assoc($db_quantity_inv->result); // số lượng chênh lệch của sản phẩm khi kiểm
                $quantity_inv = $row_quantity['inp_quantity_system'] - $row_quantity['inp_quantity_real'];
                $arr_product[$row_inv['inv_product_id']] = $quantity_inv; // Mảng dữ liệu gồm các sản phẩm kiêm kê và số lượng chênh lệch

            }unset($db_quantity_inv); unset($db_query_inv);
            // lọc ra các sản phẩm kiểm kê và lấy số lượng chênh lệch để cộng hoặc trừ vào số lượng trong kho
            foreach($arr_product as $key => $product){
                $db_update_quantity = new db_execute('UPDATE product_quantity
                                                      SET pro_quantity = pro_quantity + '.$product.'
                                                      WHERE product_id = '.$key.'
                                                      AND store_id = '.$cuscat_data['inv_store_id'].'');
                unset($db_update_quantity);
            }
        }
        //log action
        log_action(ACTION_LOG_DELETE, 'Xóa phiếu kiểm kê với id là  ' . $record_id . ' bảng inventory');
        $array_return   = array('success' => 1 , 'msg' => 'Hoàn tất');
        die(json_encode($array_return));
    }

    /* xóa phiếu chuyển kho hàng */
    function deleteMoneyStockTransfer()
    {
        /* Kiểm tra quyền sửa xóa */
        checkPermission('trash');
        $record_id      = getValue('record_id', 'int', 'POST', 0);
        //Kiểm tra xem đây có fai hóa đơn sinh ra từ hệ thống không
        //check quyền xóa
        checkPermission('trash');
        $array_return   = array();
        $db_data        = new db_query('SELECT * FROM stock_transfer WHERE sto_id = ' . $record_id . ' LIMIT 1');
        $cuscat_data    = mysqli_fetch_assoc($db_data->result);
        unset($db_data);
        move2trash('sto_id', $record_id, 'stock_transfer', $cuscat_data);
        //log action
        log_action(ACTION_LOG_DELETE, 'Xóa phiếu chuyển kho với id là  ' . $record_id . ' bảng inventory');
        $array_return   = array('success' => 1 , 'msg' => 'Hoàn tất');
        die(json_encode($array_return));
    }

    /* Hàm cập nhập số lượng sản phẩm khi chọn kho hàng trong phần thêm mới*/
    function selectStore()
    {
        $store_id   = getValue('store_id', 'int', 'POST', 0);
        $array_list_product = array();
        $db_query   = new db_query('SELECT * FROM product_quantity WHERE store_id = ' . $store_id);
        while ($row = mysqli_fetch_assoc($db_query->result)) {
            $array_list_product[$row['product_id']] = $row['pro_quantity'];
        }
        $this->add($array_list_product);
    }

    /* Kiểm kê sản phẩm thay đổi lại số lượng trong kho */
    function inventory()
    {
        //id nguoi kiem ke
        $staff_id   = getValue('staff_id', 'int', 'POST', 0);
        if (!$staff_id) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa nhập tên nhân viên');
            die(json_encode($array_return));
        }

        // id kho hàng kiểm kê
        $store_id   = getValue('store_id', 'int', 'POST', 0);
        if (!$store_id) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa chọn kho kiểm kê');
            die(json_encode($array_return));
        }

        $note       = getValue('note', 'str', 'POST', '');

        $products   = getValue('products', 'arr', 'POST', '');
        if (!$products) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa chọn mặt hàng kiểm kê');
            die(json_encode($array_return));
        }

        //thời gian mặc định tạo phiếu
        $default_time = time();

        global $admin_id;
        //kiểm kê lại số lượng sản phẩm

        $myform = new generate_form();
        $myform->addTable('inventory');
        $myform->add('inv_staff_id', 'staff_id', 1, 0, $staff_id, 1, 'Bạn chưa nhập tên nhân viên');
        $myform->add('inv_store_id', 'store_id', 1, 0, $store_id, 1, 'Bạn chưa chọn kho hàng kiểm kê');
        $myform->add('inv_time', 'inv_time', 1, 0, $default_time, '');
        $myform->add('inv_note', 'note', 0, 0, $note, '');
        $myform->add('inv_admin_id', 'admin_id', 1, 0, $admin_id, '');

        if (!$myform->checkdata()) {
            $db_insert      = new db_execute_return();
            $last_id        = $db_insert->db_execute($myform->generate_insert_SQL());

            unset($db_insert);
            if (!$last_id) {
                //lỗi
                $array_return = array('error' => 0,'msg' => 'Đã có lỗi xảy ra! Vui lòng thử lại sau');
                die(json_encode($array_return));
            }
            // insert sản phẩm được kiểm kê
            $db_product_menu    = 'INSERT INTO inventory_products(inv_id ,inv_product_id, inp_quantity_system, inp_quantity_real)
                           VALUES';
            //sử dụng id của promotion để insert vào bảng promotions_menu
            foreach ($products as $product) {
                $db_product_menu .= '(
                           ' . $last_id . ',
                           ' . $product['pro_id'] . ',
                           ' . $product['pro_quantity'] . ',
                           ' . $product['pro_quantity_real'] . '
                           ),';
            }
            $db_product_menu    = rtrim($db_product_menu, ',');
            $db_insert_product  = new db_execute($db_product_menu);
            unset($db_insert_product);
            //log action
            log_action(ACTION_LOG_ADD, 'Thêm mới danh sách hàng kiểm kê ' . $last_id . ' bảng inventory');
            // update lại số lượng trong bảng product_quantity sau khi kiểm kê
            foreach ($products as $product) {
                $db_update_quantity      = 'UPDATE product_quantity SET  pro_quantity = ' . $product['pro_quantity_real'] . '
                                            WHERE store_id = ' . $store_id . '
                                            AND product_id = ' . $product['pro_id'] . ',';
                $db_update_quantity      = rtrim($db_update_quantity, ',');
                $product_update_quantity = new db_execute($db_update_quantity);
                unset($product_update_quantity);
            }
            //log action
            log_action(ACTION_LOG_EDIT, 'Cập nhật số lượng hàng với mã phiếu kiểm kê là  ' . $last_id . ' bảng product_quantity');
            $array_return = array('success' => 1,'msg' => 'Thêm mới thành công');
            die(json_encode($array_return));
        }

    }

    /* Chuyển kho , cập nhập lại số lượng mặt hàng từ trong kho chuyển đến và chuyển đi */
    function stock_transfer()
    {
        //id nguoi kiem ke
        $staff_id           = getValue('staff_id', 'int', 'POST', 0);
        if (!$staff_id) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa nhập tên nhân viên');
            die(json_encode($array_return));
        }

        $from_store         = getValue('from_store', 'int', 'POST', 0);
        if (!$from_store) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa chọn chuyển từ kho');
            die(json_encode($array_return));
        }

        $to_store           = getValue('to_store', 'int', 'POST', 0);
        if (!$to_store) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa chọn chuyển đến kho');
            die(json_encode($array_return));
        }

        if ($from_store == $to_store) {
            $array_return = array('error' => 0,'msg' => 'Chuyển kho không hợp lệ');
            die(json_encode($array_return));
        }

        $note           = getValue('note', 'str', 'POST', '');

        $products       = getValue('products', 'arr', 'POST', '');
        if (!$products) {
            $array_return = array('error' => 0,'msg' => 'Bạn chưa chọn mặt hàng chuyển kho');
            die(json_encode($array_return));
        }
        //thời gian mặc định tạo phiếu
        $default_time   = time();

        global $admin_id;
        // thêm thông tin vào phiếu chuyển kho hàng

        $myform = new generate_form();
        $myform->addTable('stock_transfer');
        $myform->add('sto_staff_id', 'staff_id', 1, 0, $staff_id, 1, 'Bạn chưa nhập tên nhân viên');
        $myform->add('sto_from_storeid', 'from_store', 1, 0, $from_store, 1, 'Bạn chưa chọn chuyển từ kho');
        $myform->add('sto_to_storeid', 'to_store', 1, 0, $to_store, 1, 'Bạn chưa chọn kho chuyển đến');
        $myform->add('sto_time', 'sto_time', 1, 0, $default_time, 0);
        $myform->add('sto_note', 'note', 0, 0, $note, '');
        $myform->add('sto_admin_id', 'admin_id', 1, 0, $admin_id, 0);

        if (!$myform->checkdata()) {
            $db_insert = new db_execute_return();
            $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
            unset($db_insert);
            if (!$last_id) {
                //lỗi
                $array_return = array('error' => 0,'msg' => 'Đã có lỗi xảy ra! Vui lòng thử lại sau');
                die(json_encode($array_return));
            }

            // insert sản phẩm được chuyển với số lượng trước khi chuyển và sau khi chuyển
            $db_product_menu = 'INSERT INTO stock_transfer_products(sto_id ,pro_id, stp_quantity_inventory, stp_quantity_transfer)
                                VALUES';
            //sử dụng id của stock_transfer để insert vào bảng stock_transfer_products
            foreach ($products as $product) {
            $db_product_menu .= '(
                       ' . $last_id . ',
                       ' . $product['pro_id'] . ',
                       ' . $product['pro_quantity'] . ',
                       ' . $product['pro_quantity_transfer'] . '
                       ),';
            }
            $db_product_menu   = rtrim($db_product_menu, ',');
            $db_insert_product = new db_execute($db_product_menu);
            unset($db_insert_product);

            //log action
            log_action(ACTION_LOG_ADD, 'Thêm mới danh sách hàng chuyển kho ' . $last_id . ' bảng stock_transfer');

            // update lại số lượng trong bảng product_quantity sau khi chuyển kho
            foreach ($products as $product) {
                $db_from_store      = 'UPDATE product_quantity SET  pro_quantity = pro_quantity - ' . $product['pro_quantity_transfer'] . '
                                       WHERE store_id = ' . $from_store . '
                                       AND product_id = ' . $product['pro_id'] . ',';
                $db_from_store      = rtrim($db_from_store, ',');
                $product_from_store = new db_execute($db_from_store);
                unset($product_from_store);

                $db_to_store        = 'UPDATE product_quantity SET  pro_quantity = pro_quantity + ' . $product['pro_quantity_transfer'] . '
                                       WHERE store_id = ' . $to_store . '
                                       AND product_id = ' . $product['pro_id'] . ',';
                $db_to_store        = rtrim($db_to_store, ',');
                $product_to_store   = new db_execute($db_to_store);
                unset($product_to_store);
            }

            //log action
            log_action(ACTION_LOG_EDIT, 'Update số lượng hàng với mã phiếu chuyển kho là  ' . $last_id . ' bảng product_quantity');
                $array_return = array('success' => 1,'msg' => 'Thêm mới thành công');
            die(json_encode($array_return));
        }

    }

    /* Kiểm tra số lượng trong kho để hiển thị bảng import */
    function checkQuantity()
    {
        //lấy thông tin các giá trị
        $store_id       = getValue('store_id', 'int', 'POST', 0);
        $product_id     = getValue('product_id', 'int', 'POST', 0);
        $quantity       = 0;
        // lấy giá số lượng của các mặt hàng có trong kho lựa chọn
        $db_query       = new db_query('SELECT pro_quantity FROM product_quantity
                                        WHERE store_id =' . $store_id . '
                                        AND product_id = ' . $product_id . '');
        while ($row     = mysqli_fetch_assoc($db_query->result)) {
            $quantity   = $row['pro_quantity'];
        }
        $this->add($quantity);
    }

    /**
     * Hàm khôi phục phiếu kiểm kê từ thùng rác
     */
    function recoveryInventory()
    {
        //check quyền recovery
        checkPermission('recovery');
        $record_id          = getValue('record_id', 'int', 'POST', 0);
        // Trước khi khôi phục phiếu kiểm kê cần phải khôi phục số sản phẩm đã kiêm kê. Điều kiện kho hàng từ phiếu kiểm kê
        if($record_id){
            // Khi khôi phục thì cộng hoặc trừ số lượng đã kiểm kê
            $arr_product = array();
            $db_query_inv = new db_query('SELECT inv_product_id FROM inventory_products WHERE inv_id = '.$record_id.'');
            while($row_inv = mysqli_fetch_assoc($db_query_inv->result)){
                // query xem số lượng chênh lệch của sản phẩm(product_id) khi kiểm kho
                $db_quantity_inv = new db_query('SELECT * FROM inventory_products
                                                 WHERE inv_product_id = '.$row_inv['inv_product_id'].'
                                                 AND inv_id = '.$record_id.'');
                $row_quantity = mysqli_fetch_assoc($db_quantity_inv->result); // số lượng chênh lệch của sản phẩm khi kiểm
                $quantity_inv = $row_quantity['inp_quantity_system'] - $row_quantity['inp_quantity_real'];
                $arr_product[$row_inv['inv_product_id']] = $quantity_inv; // Mảng dữ liệu gồm các sản phẩm kiêm kê và số lượng chênh lệch

            }unset($db_quantity_inv); unset($db_query_inv);
            // lọc ra các sản phẩm kiểm kê và lấy số lượng chênh lệch để cộng hoặc trừ vào số lượng trong kho
            $sql_trash            = new db_query('SELECT * FROM trash
                                                      WHERE tra_record_id = ' .$record_id . '
                                                      AND tra_table = "inventory"');
            $tra_data       = mysqli_fetch_assoc($sql_trash->result);unset($sql_trash);
            $tra_data       = json_decode(base64_decode($tra_data['tra_data']),1);
            $store_id       = $tra_data['inv_store_id'];

            foreach($arr_product as $key => $product){
                $db_update_quantity = new db_execute('UPDATE product_quantity SET pro_quantity = pro_quantity - '.$product.'
                                                      WHERE product_id = '.$key.'
                                                      AND store_id = '.$store_id.'');
                unset($db_update_quantity);
            }
        }
        // Khôi phục phiếu kiểm kê trong thùng rác
        if (trash_recovery($record_id, 'inventory') === TRUE) {
            //khôi phục thành công
            $array_return   = array('success' => 1 , 'msg' => 'Hoàn tất');
        } else {
            $array_return   = array('error' => 0, 'msg' => 'Đã xảy ra lỗi vui lòng thử lại sau');
        }
        $this->add(json_encode($array_return));
    }

    /**
     * Hàm khôi phục phiếu kiểm kê từ thùng rác
     */
    function recoveryStockTransfer()
    {
        //check quyền recovery
        checkPermission('recovery');
        $record_id = getValue('record_id', 'int', 'POST', 0);
        if (trash_recovery($record_id, 'stock_transfer') === TRUE) {
            //khôi phục thành công
            $array_return = array('success' => 1,'msg' => 'Khôi phục không thành công');
        } else {
            $array_return = array('error'   => 0,'msg' => 'Đã có lỗi vui lòng thử lại sau');
        }
        $this->add(json_encode($array_return));
    }

    // xóa vĩnh viên phiếu kiểm kê
    function terminalDeleteInventory()
    {
        //kiểm tra quyền xóa vĩnh viễn
        checkPermission('delete');
        $record_id = getValue('record_id', 'int', 'POST', 0);
        //xóa hoàn toàn
        terminal_delete($record_id, 'inventory');
        $array_return = array('success' => 1);
        die(json_encode($array_return));
    }

    // xóa vĩnh viên phiếu kiểm kê
    function terminalDeleteStockTransfer()
    {
        //kiểm tra quyền xóa vĩnh viễn
        checkPermission('delete');
        $record_id = getValue('record_id', 'int', 'POST', 0);
        //xóa hoàn toàn
        terminal_delete($record_id, 'stock_transfer');
        $array_return = array('success' => 1);
        die(json_encode($array_return));
    }

    /*
        Lọc phiếu kiểm kê theo ngày tháng, nhân viên
    */
    function fillerInventory()
    {
        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('inv_id', 30);
        $list->add('', 'Số phiếu');
        $list->add('', 'Nhân viên kiểm kê');
        $list->add('', 'Ngày kiểm kê');
        $list->add('', 'Kho kiểm kê');

        // lấy biên từ form tìm kiếm theo ngày và nhân viên
        $start_date_in = getValue('start_date_in', 'str', 'POST', 0);
        $date_from = convertDateTime($start_date_in, '0:0:0');

        $end_date_in = getValue('end_date_in', 'str', 'POST', 0);
        $date_to = convertDateTime($end_date_in, '0:0:0');

        $staff_id = getValue('list_staff_id', 'int', 'POST', 0);
        $search_sql = '';
        if ($start_date_in && $end_date_in && $staff_id) {
            $search_sql = ' AND inv_time >=' . $date_from . ' AND inv_time =<' . $date_to . ' AND inv_staff_id = ' . $staff_id . ' ';
        } elseif ($staff_id) {
            $search_sql = ' AND inv_staff_id = ' . $staff_id . '';
        } elseif ($start_date_in && $end_date_in) {
            $search_sql = ' AND inv_time >=' . $date_from . ' AND inv_time <=' . $date_to . '';
        } else {
            $search_sql = '';
        }

        // slect list danh sách phiếu kiểm kê
        $db_count = new db_count('SELECT count(*) as count
                            FROM inventory
                            WHERE 1 ' . $list->sqlSearch() . $search_sql . '
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT *
                            FROM inventory
                            WHERE 1 ' . $list->sqlSearch() . $search_sql . '
                            ORDER BY ' . $list->sqlSort() . ' inv_id DESC
                            ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);
        // tạo mảng để hiện thị tên nhân viên
        $staff_array = array();
        $db_staff = new db_query('SELECT * FROM users');
        while ($row_user = mysqli_fetch_assoc($db_staff->result)) {
            $staff_array[$row_user['use_id']] = $row_user['use_name'];
        }
        // tạo mảng để hiện thị kho hàng
        $store_array = array();
        $db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores" ');
        while ($row_store = mysqli_fetch_assoc($db_store->result)) {
            $store_array[$row_store['cat_id']] = $row_store['cat_name'];
        }
        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-left"');
        $i = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            $left_column .= $list->start_tr($i, $row['inv_id'], 'class="menu-normal record-item" ondblclick="detail_inventory(' . $row['inv_id'] . ')" onclick="active_record(' . $row['inv_id'] . ')" data-record_id="' . $row['inv_id'] . '"');
            /* code something */
            //Số phiếu - ID phiếu
            $left_column .= '<td class="center" width="">' . format_codenumber($row['inv_id'], 6) . '</td>';
            //Người trả
            $left_column .= '<td width="120" class="center">' . $staff_array[$row['inv_staff_id']] . '</td>';

            //Mô tả
            $left_column .= '<td class="center">' . date('d/m/Y H:i', $row['inv_time']) . '</td>';
            //số tiền
            $left_column .= '<td width="120" class="text-left">' . $store_array[$row['inv_store_id']] . '</td>';
            $left_column .= $list->end_tr();
        }
        $left_column .= $list->showFooter();
        $this->add($left_column);

    }

    // lọc phiếu kiểm kê theo ngày tháng nhân viên
    function fillerStockTransfer()
    {
        $right_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('sto_id', 30);
        $list->add('', 'Số phiếu');
        $list->add('', 'Nhân viên chuyển');
        $list->add('', 'Ngày chuyển');
        $list->add('', 'Từ kho');
        $list->add('', 'Đến kho');

        // lấy biên từ form tìm kiếm theo ngày và nhân viên
        $start_date_in  = getValue('start_date_in', 'str', 'POST', 0);

        $date_from      = convertDateTime($start_date_in, '0:0:0');

        $end_date_in    = getValue('end_date_in', 'str', 'POST', 0);

        $date_to        = convertDateTime($end_date_in, '0:0:0');

        $staff_id       = getValue('list_staff_id', 'int', 'POST', 0);

        if ($start_date_in && $end_date_in && $staff_id) {
            $search_sql = ' AND sto_time >=' . $date_from . ' AND sto_time <=' . $date_to . ' AND sto_staff_id = ' . $staff_id . ' ';
        } elseif ($staff_id) {
            $search_sql = ' AND sto_staff_id = ' . $staff_id . '';
        } elseif ($start_date_in && $end_date_in) {
            $search_sql = ' AND sto_time >=' . $date_from . ' AND sto_time <=' . $date_to . '';
        } else {
            $search_sql = '';
        }

        // slect list danh sách phiếu kiểm kê
        $db_count = new db_count('SELECT count(*) as count
                            FROM stock_transfer
                            WHERE 1 ' . $list->sqlSearch() . $search_sql . '
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT *
                            FROM stock_transfer
                            WHERE 1 ' . $list->sqlSearch() . $search_sql . '
                            ORDER BY ' . $list->sqlSort() . ' sto_id DESC
                            ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);
        // tạo mảng để hiện thị tên nhân viên
        $staff_array = array();
        $db_staff = new db_query('SELECT * FROM users');
        while ($row_user = mysqli_fetch_assoc($db_staff->result)) {
            $staff_array[$row_user['use_id']] = $row_user['use_name'];
        }
        // tạo mảng để hiện thị kho hàng
        $store_array = array();
        $db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores" ');
        while ($row_store = mysqli_fetch_assoc($db_store->result)) {
            $store_array[$row_store['cat_id']] = $row_store['cat_name'];
        }
        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $right_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            $right_column .= $list->start_tr($i, $row['sto_id'], 'class="menu-normal record-item" ondblclick="detail_stock_transfer(' . $row['sto_id'] . ')" dblclick="detail_record(' . $row['sto_id'] . ')" onclick="active_record(' . $row['sto_id'] . ')" data-record_id="' . $row['sto_id'] . '"');
            /* code something */
            //Số phiếu - ID phiếu
            $right_column .= '<td class="center" width="">' . format_codenumber($row['sto_id'], 6) . '</td>';
            //Người trả
            $right_column .= '<td width="120" class="center">' . $staff_array[$row['sto_staff_id']] . '</td>';

            //Mô tả
            $right_column .= '<td class="center">' . date('d/m/Y H:i', $row['sto_time']) . '</td>';
            //số tiền
            $right_column .= '<td width="120" class="text-left">' . $store_array[$row['sto_from_storeid']] . '</td>';
            $right_column .= '<td width="120" class="text-left">' . $store_array[$row['sto_to_storeid']] . '</td>';
            $right_column .= $list->end_tr();
        }
        $right_column .= $list->showFooter();
        $this->add($right_column);

    }
    /* Xóa dữ liệu khi chuột phải trong DOM import product*/
    function removeProducts(){
        /* Kiểm tra quyền sửa xóa */
        checkPermission('trash');
        $record_id      = getValue('record_id', 'int', 'POST', 0);

        //check quyền xóa
        checkPermission('trash');
        $array_return   = array();
        $db_data        = new db_query('SELECT * FROM stock_transfer_products WHERE pro_id = ' . $record_id . ' LIMIT 1');
        $cuscat_data    = mysqli_fetch_assoc($db_data->result);
        unset($db_data);
        move2trash('pro_id', $record_id, 'stock_transfer_products', $cuscat_data);
        //log action
        log_action(ACTION_LOG_DELETE, 'Xóa id sản phẩm là  ' . $record_id . ' bảng stock_transfer_products  ');
        $array_return   = array('success' => 1 , 'msg' => 'Hoàn tất');
        die(json_encode($array_return));

    }
}

// khai bao cac bien global
$array_unit = array();
$db_query = new db_query('SELECT * FROM units');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
}
unset($db_query);
// khoi tao doi tuong object ajax va thuc thi
$ajax_inventory = new InventoryAjax();
$ajax_inventory->execute();
