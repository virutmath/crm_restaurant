<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class ReportAjax extends AjaxCommon {
    function reportInventory(){
        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product = getValue('products','arr','POST','');
        $start_date = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');
        $store_id = getValue('store_id','int','POST',0);
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);
        $total_import = 0;
        $db_query = new db_query('SELECT bio_id FROM bill_out
                                  WHERE bio_store_id = '.$store_id.'
                                  AND bio_start_time >= '.$start_date.' AND bio_start_time <= '.$end_date.'');
        while($row = mysqli_fetch_assoc($db_query->result)){

            $db_query_import = new db_query('SELECT SUM(bid_pro_number) as sum_pro FROM bill_out_detail
                                             WHERE bid_bill_id = '.$row['bio_id'].' AND bid_pro_id IN('.$arr_pro.')');
            $row_total = mysqli_fetch_assoc($db_query_import->result);
            $total_import += $row_total['sum_pro'];
        }
        //echo $total_import;
        unset($db_query);

        //lấy số lượng bán hàng
        $total_menu = 0;
        $db_query_export = new db_query('SELECT bii_id FROM bill_in
                                  WHERE bii_store_id = '.$store_id.'
                                  AND bii_start_time >= '.$start_date.' AND bii_start_time <= '.$end_date.'');
        while($row_export = mysqli_fetch_assoc($db_query_export->result)){
            $db_menu_export = new db_query('SELECT bid_menu_number,sum_menu,bid_menu_id FROM bill_in_detail
                                            WHERE bid_bill_id = '.$row_export['bii_id'].'');
            while($row_menu = mysqli_fetch_assoc($db_menu_export->result)){
                //echo $total_menu = $row_menu['bid_menu_number'];
                //echo $row_menu['bid_menu_id']."<br/>";
//                $db_menu_pro = new db_query('SELECT mep_quantity,mep_product_id FROM menu_products
//                                         WHERE bid_menu_id = '.$row_menu['bid_menu_id'].'');
//                while($row_product = mysqli_fetch_assoc($db_menu_pro->result)){
//                    //echo $row_product['mep_product_id'];
//                    //echo "<br>";
//                    //echo $row_product['mep_quantity'];
//                }
            }

        }unset($db_menu_pro);
        unset($db_query_export);
    }

    // báo cáo giá trị tồn kho
    function reportStock(){
        $array_return = array();
        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product  = getValue('products','arr','POST','');
        if(!$array_product){
            $array_return['content']    = 'Chưa chọn sản phẩm';
            die(json_encode($array_return));
        }
        $store_id       = getValue('store_id','int','POST',0);
        if(!$store_id){
            $array_return['content']    = 'Chưa chọn kho hàng';
            die(json_encode($array_return));
        }
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('pro_id', 100);
        $list->add('', 'Tên mặt hàng');
        $list->add('', 'ĐVT');
        $list->add('', 'SL tồn');
        $list->add('', 'Giá nhập TB');
        $list->add('', 'Tổng tiền tồn');

        $sql_search = 'AND product_id IN('.$arr_pro.') AND store_id = '.$store_id.'';
        // select list danh
        $db_count = new db_count('SELECT count(*) as count
                            FROM product_quantity
                            WHERE 1 ' . $list->sqlSearch() .$sql_search. '
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM product_quantity
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' product_id DESC
                            ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi ten product
        $array_pro_name = array();
        $db_product = new db_query('SELECT pro_id,pro_name FROM products');
        while($row_pro = mysqli_fetch_assoc($db_product->result)){
            $array_pro_name[$row_pro['pro_id']] = $row_pro['pro_name'];
        }


        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_all  = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // lấy ra pro_unit_id để
            $db_query_unit  = new db_query('SELECT pro_unit_id FROM products WHERE pro_id = '.$row['product_id'].' ');
            $row_pro_unit   = mysqli_fetch_assoc($db_query_unit->result);

            // lấy ra đơn vị tính của sản phẩm
            $db_unit_name   = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row_pro_unit['pro_unit_id'] . '');
            $row_unit       = mysqli_fetch_assoc($db_unit_name->result);

            // tính giá nhập trung bình
            $db_price_ave   = new db_query('SELECT SUM(bid_pro_price) as total_price FROM bill_out_detail
                                          WHERE bid_pro_id = '.$row['product_id'].'');

            $row_price      = mysqli_fetch_assoc($db_price_ave->result);
            //đếm có bao nhiêu sản phẩm và lấy tổng số bản ghi để tính giá trung bình
            $db_count_price = new db_query('SELECT count(*) as count FROM bill_out_detail
                                            WHERE bid_pro_id = '.$row['product_id'].'');

            $count          = mysqli_fetch_assoc($db_count_price->result);
            // tính công thức giá nhập trung bình
            if($count['count'] > 0){
                $price_average = $row_price['total_price']/$count['count'];
            } else {
                $price_average = 0;
            }


            $left_column .= $list->start_tr($i, $row['product_id'], 'class="menu-normal record-item" data-record_id="' . $row['product_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">' . $array_pro_name[$row['product_id']] . '</td>';

            $left_column .= '<td width="100" class="center">' . $row_unit['uni_name'] . '</td>';

            $left_column .= '<td width="120" class="text-right">' . $row['pro_quantity'] . '</td>';

            $left_column .= '<td width="120"  class="text-right">'.number_format($price_average).'</td>';

            $left_column .= '<td width="120"  class="text-right">'.number_format($price_average * $row['pro_quantity']).'</td>';

            // tổng tiền tất cả mặt hàng đã chọn
            $total_all += ($price_average * $row['pro_quantity']);

            $left_column .= $list->end_tr();
        }unset($db_count_price);unset($db_price_ave);unset($db_listing);unset($db_unit_name);unset($db_query_unit);
        $left_column .= $list->showFooter();
        $total_money = number_format($total_all);
        $array_return['content']    = $left_column;
        $array_return['total']      = $total_money;
        die(json_encode($array_return));
    }

    // Thống kê bán hàng theo thực đơn
    function revenueMenus(){
        /* Phần số lượng in bếp chưa có để tính số lượng chênh lệnh so với số lượng bán hàng&*/
        $array_return = array();

        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product  = getValue('products','arr','POST','');
        if(!$array_product){
            $array_return['content'] = 'Chưa chọn sản phẩm';
            die(json_encode($array_return));
        }
        $start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');
        $store_id       = getValue('store_id','int','POST',0);
        if(!$store_id){
            $array_return['content'] = 'Chưa chọn kho hàng';
            die(json_encode($array_return));
        }
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('men_id', 100);
        $list->add('', 'Tên thực đơn');
        $list->add('', 'ĐVT');
        $list->add('', 'SL Bán');
        $list->add('', 'Giảm giá');
        $list->add('', 'Tổng tiền');

        //lấy ra những hóa đơn có store_id đã chọn
        $array_bii = array();
        $db_bill = new db_query('SELECT bii_id FROM bill_in
                                 WHERE  bii_store_id  = '.$store_id.'
                                 AND bii_start_time <= '.$end_date.' AND bii_start_time >= '.$start_date.'
                                 ');
        while($row_bill = mysqli_fetch_assoc($db_bill->result)){
            $array_bii[] = $row_bill['bii_id'];
        } unset($db_bill);
        $array_bii = implode(',',$array_bii);
        // kiểm tra mảng rỗng điều kiện bid_bill_id sẽ là mảng array(0)
        if($array_bii == null){
            $sql_search = 'AND bid_menu_id IN(' . $arr_pro . ') AND bid_bill_id IN(0)';
        } else {
            $sql_search = 'AND bid_menu_id IN(' . $arr_pro . ') AND bid_bill_id IN(' . $array_bii . ')';
        }


        // lấy ra bản ghi để thực hiển thị ajax
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_in_detail
                                  WHERE 1 ' . $list->sqlSearch() .$sql_search. '
                                  GROUP BY bid_menu_id
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM bill_in_detail
                      WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                      GROUP BY bid_menu_id
                      ORDER BY ' . $list->sqlSort() . 'bid_menu_id ASC
                      ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi ten product
        $array_menu_name = array();
        $db_menus = new db_query('SELECT men_id,men_name FROM menus');
        while($row_menus = mysqli_fetch_assoc($db_menus->result)){
            $array_menu_name[$row_menus['men_id']] = $row_menus['men_name'];
        }unset($db_menus);


        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_all  = 0; /* Tổng của tất cả hóa đơn bán hàng theo menu */
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // lấy ra pro_unit_id để
            $db_query_unit  = new db_query('SELECT men_unit_id FROM menus WHERE men_id = '.$row['bid_menu_id'].' ');
            $row_pro_unit   = mysqli_fetch_assoc($db_query_unit->result);
            // lấy ra đơn vị tính của sản phẩm
            $db_unit_name   = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row_pro_unit['men_unit_id'] . '');
            $row_unit       = mysqli_fetch_assoc($db_unit_name->result);

            // Tổng số lượng của menu cùng id trong bảng bill_in_detail
            $db_total_number = new db_query('SELECT SUM(bid_menu_number) AS total_number
                                             FROM bill_in_detail
                                             WHERE 1 ' . $list->sqlSearch() . '
                                             AND bid_menu_id = '.$row['bid_menu_id'].'
                                             GROUP BY bid_menu_id');
            $row_total_number = mysqli_fetch_assoc($db_total_number->result);

            $total_money = ($row_total_number['total_number']*$row['bid_menu_price']);
            $total_money = $total_money - $total_money*$row['bid_menu_discount']/100; /* tính tổng số tiền theo giảm giá thực đơn */

            $left_column .= $list->start_tr($i, $row['bid_menu_id'], 'class="menu-normal record-item" data-record_id="' . $row['bid_menu_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">' . $array_menu_name[$row['bid_menu_id']] . '</td>';

            $left_column .= '<td width="10%" class="center">' . $row_unit['uni_name'] . '</td>';

            $left_column .= '<td width="10%" class="text-right">'.number_format($row_total_number['total_number']).'</td>';
            $left_column .= '<td width="10%" class="text-right">'.$row['bid_menu_discount'].'</td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_money).'</td>';


            // tổng tiền tất cả mặt hàng đã chọn
            $total_all += $total_money;

            $left_column .= $list->end_tr();
        }unset($db_count_price);unset($db_price_ave);unset($db_listing);unset($db_unit_name);unset($db_query_unit);

        $left_column .= $list->showFooter();
        $total_all  = number_format($total_all);


        $array_return['content']    = $left_column;
        $array_return['total']      = $total_all;
        die(json_encode($array_return));
    }

    // Thống kê bán hàng theo nhân viên
    function revenueStaff(){

        $array_return = array();

        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_staff  = getValue('staffs','arr','POST','');
        if(!$array_staff){
            $array_return['content'] = 'Chưa chọn nhân viên';
            die(json_encode($array_return));
        }
        $start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');

        // select ra báo cáo với các thông tin trên
        $arr_staff = array();
        foreach($array_staff as $staff){
            $arr_staff[] = $staff;
        }
        $arr_staff = implode(',',$arr_staff);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('bii_id', 30);
        $list->add('', 'Tên nhân viên');
        $list->add('', 'Số HĐ');
        $list->add('', 'Doanh thu');
        $list->add('', 'Ghi có');
        $list->add('', 'Ghi nợ');


        $bill_condition = 'AND bii_start_time <= '.$end_date.' AND bii_start_time >= '.$start_date.' AND bii_staff_id IN(0,' . $arr_staff . ')';

        // lấy ra bản ghi để thực hiển thị ajax
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_in
                                  WHERE 1 ' . $list->sqlSearch() .$bill_condition. '
                                  GROUP BY bii_staff_id
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM bill_in
                      WHERE 1 ' . $list->sqlSearch() . $bill_condition . '
                      GROUP BY bii_staff_id
                      ORDER BY ' . $list->sqlSort() . 'bii_staff_id ASC
                      ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi theo ten nhân viên
        $array_staff_name = array();
        $db_staff = new db_query('SELECT use_id,use_name FROM users');
        while($row_staff = mysqli_fetch_assoc($db_staff->result)){
            $array_staff_name[$row_staff['use_id']] = $row_staff['use_name'];
        }unset($db_staff);

        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_bill_all = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // đếm số hợp đồng của mỗi nhân viên
            $db_count_bill = new db_query('SELECT * FROM bill_in WHERE bii_staff_id = '.$row['bii_staff_id'].'');
            $total_bill = mysqli_num_rows($db_count_bill->result); unset($db_count_bill);
            $total_bill_all += $total_bill;

            /* Hiển thị số doanh thu của nhân viên đã được lọc*/
            $db_total_staff_money = new db_query('SELECT SUM(bii_true_money) AS true_money,
                                                  SUM(bii_round_money) AS round_money,
                                                  SUM(bii_money_debit) AS debit_money
                                                  FROM bill_in WHERE bii_staff_id = '.$row['bii_staff_id'].'');
            $row_total_staff_money = mysqli_fetch_assoc($db_total_staff_money->result);
            $total_staff_money  = $row_total_staff_money['true_money']; /* Tổng doanh thu*/
            $total_round_money  = $row_total_staff_money['round_money']; /* Doanh thu ghi có*/
            $total_debit        = $row_total_staff_money['debit_money']; /* Ghi nợ*/
            unset($db_total_staff_money);

            // Hiển thị các hóa đơn với staff_id = 0
            $staff_id = $row['bii_staff_id'];
            if($staff_id == 0){
                $staff_name = 'Không chọn nhân viên';
            } else {
                $staff_name = $array_staff_name[$row['bii_staff_id']];
            }

            $left_column .= $list->start_tr($i, $row['bii_staff_id'], 'class="menu-normal record-item" data-record_id="' . $row['bii_staff_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">'.$staff_name.'</td>';

            $left_column .= '<td width="10%"  class="center">'.$total_bill.'</td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_staff_money).'</td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_round_money - $total_debit).' </td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_debit).' </td>';

            $left_column .= $list->end_tr();

        }unset($db_listing);unset($db_count_bill);

        $left_column .= $list->showFooter();


        /* Tính tổng doanh thu, ghi nợ, ghi có của tất cả nhân viên có trong mảng arr_staff */
        $db_arr_staff_money = new db_query('SELECT SUM(bii_true_money)  AS true_money,
                                                   SUM(bii_round_money) AS round_money,
                                                   SUM(bii_money_debit) AS debit_money
                                                   FROM bill_in WHERE bii_staff_id IN('.$arr_staff.',0)');
        $row_arr_staff_money = mysqli_fetch_assoc($db_arr_staff_money->result);
        $total_staff_money  = $row_arr_staff_money['true_money']; /* Tổng doanh thu*/
        $total_round_money  = $row_arr_staff_money['round_money'] - $row_arr_staff_money['debit_money']; /* Doanh thu ghi có*/
        $total_debit        = $row_arr_staff_money['debit_money']; /* Ghi nợ*/
        unset($db_arr_staff_money);

        /* Mảng trả về kết quả để hiển thị*/
        $array_return['content']        = $left_column;
        $array_return['all_bill']       = number_format($total_bill_all);
        $array_return['total']          = number_format($total_staff_money);
        $array_return['round_money']    = number_format($total_round_money);
        $array_return['debit_money']    = number_format($total_debit);
        die(json_encode($array_return));
    }

    // Thống kê bán hàng theo khách hàng
    function revenueCustomers(){

        $array_return = array();

        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_customers  = getValue('customers','arr','POST','');
        if(!$array_customers){
            $array_return['content'] = 'Chưa chọn khách hàng';
            die(json_encode($array_return));
        }
        $start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');

        // select ra báo cáo với các thông tin trên
        $arr_customers = array();
        foreach($array_customers as $customer){
            $arr_customers[] = $customer;
        }
        $arr_customers = implode(',',$arr_customers);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('bii_id', 100);
        $list->add('', 'Tên khách hàng');
        $list->add('', 'Số HĐ');
        $list->add('', 'Doanh thu');
        $list->add('', 'Ghi có');
        $list->add('', 'Ghi nợ');


        $bill_condition = 'AND bii_start_time <= '.$end_date.' AND bii_start_time >= '.$start_date.' AND bii_customer_id IN(0,' . $arr_customers . ')';

        // lấy ra bản ghi để thực hiển thị ajax
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_in
                                  WHERE 1 ' . $list->sqlSearch() .$bill_condition. '
                                  GROUP BY bii_customer_id
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM bill_in
                      WHERE 1 ' . $list->sqlSearch() . $bill_condition . '
                      GROUP BY bii_customer_id
                      ORDER BY ' . $list->sqlSort() . 'bii_customer_id ASC
                      ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi theo ten nhân viên
        $array_customer_name = array();
        $db_customers = new db_query('SELECT cus_id,cus_name FROM customers');
        while($row_customer = mysqli_fetch_assoc($db_customers->result)){
            $array_customer_name[$row_customer['cus_id']] = $row_customer['cus_name'];
        }unset($db_customers);

        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_bill_all = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // đếm số hợp đồng của mỗi nhân viên
            $db_count_bill = new db_query('SELECT * FROM bill_in WHERE bii_customer_id = '.$row['bii_customer_id'].'');
            $total_bill = mysqli_num_rows($db_count_bill->result); unset($db_count_bill);
            $total_bill_all += $total_bill;

            /* Hiển thị số doanh thu của nhân viên đã được lọc*/
            $db_total_customer_money = new db_query('SELECT SUM(bii_true_money) AS true_money,
                                                  SUM(bii_round_money) AS round_money,
                                                  SUM(bii_money_debit) AS debit_money
                                                  FROM bill_in WHERE bii_customer_id = '.$row['bii_customer_id'].'');
            $row_total_customer_money = mysqli_fetch_assoc($db_total_customer_money->result);
            $total_staff_money  = $row_total_customer_money['true_money']; /* Tổng doanh thu*/
            $total_round_money  = $row_total_customer_money['round_money']; /* Doanh thu ghi có*/
            $total_debit        = $row_total_customer_money['debit_money']; /* Ghi nợ*/
            unset($db_total_customer_money);

            // Hiển thị các hóa đơn với staff_id = 0
            $customer_id = $row['bii_customer_id'];
            if($customer_id == 0){
                $customer_name = 'Khách lẻ';
            } else {
                $customer_name = $array_customer_name[$row['bii_customer_id']];
            }

            $left_column .= $list->start_tr($i, $row['bii_customer_id'], 'class="menu-normal record-item" data-record_id="' . $row['bii_customer_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">'.$customer_name.'</td>';

            $left_column .= '<td width="10%"  class="center">'.$total_bill.'</td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_staff_money).'</td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_round_money - $total_debit).' </td>';

            $left_column .= '<td width="15%"  class="text-right">'.number_format($total_debit).' </td>';

            $left_column .= $list->end_tr();

        }unset($db_listing);unset($db_count_bill);

        $left_column .= $list->showFooter();


        /* Tính tổng doanh thu, ghi nợ, ghi có của tất cả nhân viên có trong mảng arr_staff */
        $db_arr_staff_money = new db_query('SELECT SUM(bii_true_money)  AS true_money,
                                                   SUM(bii_round_money) AS round_money,
                                                   SUM(bii_money_debit) AS debit_money
                                                   FROM bill_in WHERE bii_customer_id IN('.$arr_customers.',0)');
        $row_arr_customer_money = mysqli_fetch_assoc($db_arr_staff_money->result);
        $total_staff_money  = $row_arr_customer_money['true_money']; /* Tổng doanh thu*/
        $total_round_money  = $row_arr_customer_money['round_money'] - $row_arr_customer_money['debit_money']; /* Doanh thu ghi có*/
        $total_debit        = $row_arr_customer_money['debit_money']; /* Ghi nợ*/
        unset($db_arr_staff_money);

        /* Mảng trả về kết quả để hiển thị*/
        $array_return['content']        = $left_column;
        $array_return['all_bill']       = number_format($total_bill_all);
        $array_return['total']          = number_format($total_staff_money);
        $array_return['round_money']    = number_format($total_round_money);
        $array_return['debit_money']    = number_format($total_debit);
        die(json_encode($array_return));
    }

    // báo cáo mặt hàng tồn kho
    function reportProducts(){
        $array_return = array();
        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product  = getValue('products','arr','POST','');
        if(!$array_product){
            $array_return['content']    = 'Chưa chọn sản phẩm';
            die(json_encode($array_return));
        }
        $store_id       = getValue('store_id','int','POST',0);
        if(!$store_id){
            $array_return['content']    = 'Chưa chọn kho hàng';
            die(json_encode($array_return));
        }
        $start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('pro_id', 100);
        $list->add('', 'Tên mặt hàng');
        $list->add('', 'ĐVT');
        $list->add('', 'SL nhập');
        $list->add('', 'Tổng tiền tồn');



        // tảo mảng bill_id lọc theo thời gian và theo kho hàng
        $array_bill = array();
        $db_bill_out = new db_query('SELECT bio_id FROM bill_out WHERE bio_start_time >= '.$start_date.'
                                     AND bio_start_time <='.$end_date.' AND bio_store_id = '.$store_id.'');
        while($row_bill_out = mysqli_fetch_assoc($db_bill_out->result)){
            $array_bill[] = $row_bill_out['bio_id'];
        }unset($db_bill_out);
        $array_bill = implode(',',$array_bill);

        if($array_bill == null){
            $sql_search = ' AND bid_bill_id IN(0)';
        } else {
            $sql_search = ' AND bid_pro_id IN(' . $arr_pro . ') AND bid_bill_id IN(' . $array_bill . ')';
        }


        // select list danh
        $db_count = new db_count('SELECT count(*) as count
                            FROM bill_out_detail
                            WHERE 1 ' . $list->sqlSearch() .$sql_search. '
                            GROUP BY bid_pro_id
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM bill_out_detail
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            GROUP BY bid_pro_id
                            ORDER BY ' . $list->sqlSort() . ' bid_pro_id ASC
                            ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi ten product
        $array_pro_name = array();
        $db_product = new db_query('SELECT pro_id,pro_name FROM products');
        while($row_pro = mysqli_fetch_assoc($db_product->result)){
            $array_pro_name[$row_pro['pro_id']] = $row_pro['pro_name'];
        }


        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_all   = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // lấy ra pro_unit_id để
            $db_query_unit  = new db_query('SELECT pro_unit_id FROM products WHERE pro_id = '.$row['bid_pro_id'].' ');
            $row_pro_unit   = mysqli_fetch_assoc($db_query_unit->result);

            // lấy ra đơn vị tính của sản phẩm
            $db_unit_name   = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row_pro_unit['pro_unit_id'] . '');
            $row_unit       = mysqli_fetch_assoc($db_unit_name->result);

            //tính tổng số lượng và giá tiền theo mặt hàng
            $db_price_ave   = new db_query('SELECT SUM(bid_pro_price) AS total_price,
                                            SUM(bid_pro_number) AS total_number
                                            FROM bill_out_detail
                                            WHERE bid_pro_id = '.$row['bid_pro_id'].'');

            $row_total      = mysqli_fetch_assoc($db_price_ave->result);


            $left_column .= $list->start_tr($i, $row['bid_pro_id'], 'class="menu-normal record-item" data-record_id="' . $row['bid_pro_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">' . $array_pro_name[$row['bid_pro_id']] . '</td>';

            $left_column .= '<td width="100" class="center">' . $row_unit['uni_name'] . '</td>';

            $left_column .= '<td width="120" class="text-right">'.$row_total['total_number'].'</td>';

            $left_column .= '<td width="120"  class="text-right">'.number_format($row_total['total_price']).'</td>';

            // tổng tiền tất cả mặt hàng đã chọn
            $total_all += $row_total['total_number']*$row_total['total_price'];

            $left_column .= $list->end_tr();
        }unset($db_count_price);unset($db_price_ave);unset($db_listing);unset($db_unit_name);unset($db_query_unit);
        $left_column .= $list->showFooter();

        $array_return['content']    = $left_column;
        $array_return['total']      = number_format($total_all);
        die(json_encode($array_return));
    }
}
$ajax = new ReportAjax();
$ajax->execute();
