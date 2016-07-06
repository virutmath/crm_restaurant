<?
require_once 'inc_security.php';
//class Ajax - version 1.0
$array_return = array();
class HomeAjax extends AjaxCommon
{
    // ham xoa hoa don ban
    function deleteBillIn(){
        checkPermission('trash');
        $billIn_id                      = getValue('billIn_id','int','POST',0);
        $reason_other                   = 'Bán hàng';
        // kiểm tra id hóa đơn có tồn tại không
        if(!$billIn_id) {
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        // nếu hóa đơn chưa thanh toán công nợ thì k cho xóa
        $sql                            = new db_query('SELECT * FROM bill_in WHERE bii_id = ' . intval($billIn_id));
        $debit                          = mysqli_fetch_assoc($sql->result);unset($sql);
        if(!$debit){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        if($debit['bii_money_debit']  != 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        //
        $arr_fin_id = array();
        // xóa phiếu thu trong bảng financies
        $delete_financies               = new db_query('SELECT * FROM financial 
                                                        WHERE (fin_billcode = ' . intval($billIn_id) . ' 
                                                        AND fin_reason_other = \'' . trim($reason_other) . '\')
                                                        OR (fin_billcode = ' . intval($billIn_id) . ' 
                                                        AND fin_reason_other = \'' .trim('Công nợ khách hàng'). '\')');
        while($data_financies           = mysqli_fetch_assoc($delete_financies->result)){
            $arr_fin_id[] = $data_financies['fin_id'];
            move2trash('fin_id',$data_financies['fin_id'],'financial',$data_financies,'Phiếu thu');
        }unset($delete_financies);
        $array_fin_id = implode(',',$arr_fin_id);
        // lấy ra list menu id
        // xoa thong tin hoa don trong ban bill_in detail
        $sql                            = new db_query('SELECT * FROM bill_in_detail WHERE bid_bill_id = ' . intval($billIn_id));  
        while ($data                    = mysqli_fetch_assoc($sql->result)){
            $list_menu_id[]             = $data['bid_menu_id'];
            $list_quantity[$data['bid_menu_id']] = $data['bid_menu_number'];
            move2trash('bid_bill_id',$billIn_id,'bill_in_detail',$data);
        };unset($sql);
        
        // kiểm tra hóa đơn còn tồn tại không, còn thì xóa. 
        // lấy ra id của kho        
        $sql                            = new db_query('SELECT * FROM bill_in WHERE bii_id = ' . intval($billIn_id) . ' LIMIT 1');
        $data_bill_in                   = mysqli_fetch_assoc($sql->result);unset($sql);
        if($data_bill_in){
            $store                      = $data_bill_in['bii_store_id'];
            foreach($list_menu_id as $menu_pro_id){
                // lấy ra số lượng sản phẩm và sl nguyên liệu
                $db_product             = new db_query('SELECT *
                                                        FROM menu_products
                                                        LEFT JOIN product_quantity ON product_id = mep_product_id
                                                        WHERE mep_menu_id = ' . $menu_pro_id . '
                                                        AND store_id = ' . $store);
                                            //
                // cập nhật lại số lượng khi xóa
                while($row_pro          = mysqli_fetch_assoc($db_product->result)) {
                    $sql_minus          = 'UPDATE product_quantity 
                                          SET pro_quantity = pro_quantity + ' . ($row_pro['mep_quantity'] * $list_quantity[$row_pro['mep_menu_id']]) . ' 
                                          WHERE product_id = ' . $row_pro['product_id'] . ' 
                                          AND store_id = ' . $store;
                    $db_update          = new db_execute($sql_minus);
                    unset($db_update);
                }
            }
            $data_bill_in['arr_fin_id'] = $array_fin_id;
            // thực hiện chuyển hóa đơn vào thùng rác
            move2trash('bii_id',$billIn_id,'bill_in',$data_bill_in);
            $array_return               = array('success'=>1);
        }else{
            exit();
        }
        echo json_encode($array_return);       
    }//end hàm xóa hóa đơn bán hàng
    // hàm xóa hóa đơn nhập
    function deleteBillOut(){
        checkPermission('trash');
        $billOut_id                     = getValue('billOut_id','int','POST',0);
        $reason_other                   = 'Nhập hàng';
        // kiem tra hoa don co ton tai k
        // nếu hóa đơn ton tai nhung chưa thanh toán công nợ thì k cho xóa
        $sql                            = new db_query('SELECT * FROM bill_out WHERE bio_id = ' . intval($billOut_id));
        $debit                          = mysqli_fetch_assoc($sql->result);unset($sql);
        if(!$debit) {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }
        if($debit['bio_money_debit']  != 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        // lay ra id cua kho duoc nhap vao
        // chuyen tat ca vao bang thung rac
        $sql                            = new db_query('SELECT * FROM bill_out_detail WHERE bid_bill_id = ' . intval($billOut_id));  
        while ($data                    = mysqli_fetch_assoc($sql->result)){
            $list_menu_id[] = $data['bid_pro_id'];
            $list_quantity[$data['bid_pro_id']] = $data['bid_pro_number'];
            move2trash('bid_bill_id',$billOut_id,'bill_out_detail',$data);
        };unset($sql);
        // xóa phiếu chi trong bảng financies
        $delete_financies               = new db_query('SELECT * FROM financial 
                                                        WHERE (fin_billcode = ' . intval($billOut_id) . ' 
                                                        AND fin_reason_other = \'' . trim($reason_other) . '\')
                                                        OR (fin_billcode = ' . intval($billOut_id) . '
                                                        AND fin_reason_other = \''.trim('Công nợ nhà cung cấp').'\')');
        while($data_financies           = mysqli_fetch_assoc($delete_financies->result)){
            $arr_fin_id[]               = $data_financies['fin_id'];
            move2trash('fin_id',$data_financies['fin_id'],'financial',$data_financies,'Phiếu chi');
        }unset($delete_financies);
        $array_fin_id = implode(',',$arr_fin_id);
        // lay ra id kho
        $sql                            = new db_query('SELECT * FROM bill_out WHERE bio_id = ' . intval($billOut_id) . ' LIMIT 1');
        $data                           = mysqli_fetch_assoc($sql->result);unset($sql);
        if($data){
            $store                      = $data['bio_store_id'];
            foreach($list_menu_id as $menu_pro_id){
                 //l?y ra s? lu?ng s?n ph?m và sl nguyên li?u
                $db_product             = new db_query('SELECT *
                                                        FROM products
                                                        LEFT JOIN product_quantity ON product_id = pro_id
                                                        WHERE pro_id = ' . $menu_pro_id . '
                                                        AND store_id = ' . $store);
                                            
                 //c?p nh?t l?i s? lu?ng khi xóa
                while($row_pro          = mysqli_fetch_assoc($db_product->result)) {
                    $sql_minus          = 'UPDATE product_quantity SET pro_quantity = pro_quantity - ' . $list_quantity[$row_pro['pro_id']] . ' WHERE product_id = ' . $row_pro['pro_id'] . ' AND store_id = ' . $store;
                    $db_update          = new db_execute($sql_minus);
                    unset($db_update);
                }
            }
            $data['arr_fin_id'] = $array_fin_id;
            //th?c hi?n chuy?n hóa don vào thùng rác
            move2trash('bio_id',$billOut_id,'bill_out',$data);
            $array_return['success']    = 1;
        }else{
            exit();
        }
        echo json_encode($array_return);
    }// end hàm xóa hóa đơn nhập
    // khôi phục hóa đơn
    function restoreBill(){
        checkPermission('recovery');
        $record_id                      = getValue('record_id','int','POST',0);
        $table                          = getValue('table','str','POST','');
        $tra_table_financies            = 'financial';
        ///* kiểm tra xem hóa đơn còn tồn tại trong thùng rác hay đã bị xóa
        $db_count                       = new db_count('SELECT count(*) AS count FROM trash 
                                                        WHERE tra_record_id = ' . intval($record_id) . ' 
                                                        AND tra_table = \'' . $table .'\'');
        if ($db_count->total            == 0) {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }
        //
        $db_count                       = new db_count('SELECT count(*) AS count FROM trash 
                                                        WHERE tra_record_id = ' . intval($record_id) . ' 
                                                        AND tra_table = \''. $table .'_detail\'');
        if ($db_count->total            == 0) {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }
        //*/
        if(trim($table)                 == 'bill_in'){
            $tra_option_filter          = 'Phiếu thu';   
            //lấy ra id kho 
            // lay ra id phieu thu trong thung rac
            $sql            = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . intval($record_id) . ' 
                                            AND tra_table = \'' . $table .'\'');
            $tra_data       = mysqli_fetch_assoc($sql->result);unset($sql);
            $tra_data       = json_decode(base64_decode($tra_data['tra_data']),1);
            $store          = $tra_data['bii_store_id'];
            $list_fin_id    = $tra_data['arr_fin_id'];
            $list_fin_id    = explode(',',$list_fin_id);
            // kiểm tra xem phiếu thu trong thùng rác tồn tại hay không
            foreach($list_fin_id as $fin_id){
                $db_count_financies         = new db_count('SELECT count(*) AS count FROM trash 
                                                            WHERE tra_record_id = ' . intval($fin_id) . ' 
                                                            AND tra_table = \''. trim($tra_table_financies) .'\'');
                if ($db_count_financies->total  == 0) {
                    $array_return['success']    = 0;
                    echo json_encode($array_return);
                    exit();
                }
                trash_recovery($fin_id, $tra_table_financies);
            }
            //Lấy ra số lượng của các thực đơn trong hóa đơn ở trong thùng rác
            $sql            = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . intval($record_id) . ' 
                                            AND tra_table = \'' . $table .'_detail\'');
            while ($row     = mysqli_fetch_assoc($sql->result)){
                $record_id_bill_detail  = $row['tra_record_id'];
                $table_bill_detail      = $row['tra_table'];
                $data       = json_decode(base64_decode($row['tra_data']),1);
                
                $db_product = new db_query('SELECT *
                                            FROM menu_products
                                            LEFT JOIN product_quantity ON product_id = mep_product_id
                                            WHERE mep_menu_id = ' . $data['bid_menu_id'] . '
                                            AND store_id = ' . $store);                          
                 //cap nhat lai so luong khi xóa
                while($row_pro  = mysqli_fetch_assoc($db_product->result)) {
                    $sql_minus  = 'UPDATE product_quantity 
                                    SET pro_quantity = pro_quantity - ' . ($row_pro['mep_quantity'] * $data['bid_menu_number']) . ' 
                                    WHERE product_id = ' . $row_pro['product_id'] . ' 
                                    AND store_id = ' . $store;
                    $db_update = new db_execute($sql_minus);
                    unset($db_update);
                }unset($db_product);
                trash_recovery($record_id_bill_detail, $table_bill_detail);
            }
            unset($sql);
            trash_recovery($record_id, $table);
            $array_return = array('success'=>1);
        }
        if(trim($table) == 'bill_out'){
            $tra_option_filter = 'Phiếu chi';
            //l?y ra id kho 
            // lấy ra id phiếu chi trong thùng rác
            $sql            = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . intval($record_id) . ' 
                                            AND tra_table = "' . $table .'"');                                
            $tra_data       = mysqli_fetch_assoc($sql->result);unset($sql);
            $tra_data       = json_decode(base64_decode($tra_data['tra_data']),1);
            $store          = $tra_data['bio_store_id'];
            $id_fin         = $tra_data['arr_fin_id'];
            $list_fin_id    = explode(',',$id_fin);
            foreach($list_fin_id as $fin_id){
                // kiểm tra xem phiếu thu trong thùng rác tồn tại hay không
                $db_count_financies         = new db_count('SELECT count(*) AS count FROM trash 
                                                            WHERE tra_record_id = ' . intval($fin_id) . ' 
                                                            AND tra_table = \''. trim($tra_table_financies) .'\'');
                if ($db_count_financies->total  == 0) {
                    $array_return['success']    = 0;
                    echo json_encode($array_return);
                    exit();
                }
                trash_recovery($fin_id, $tra_table_financies);
            }
            $sql            = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . intval($record_id) . ' 
                                            AND tra_table = "' . $table .'_detail"');
            while ($row     = mysqli_fetch_assoc($sql->result)){
                $data       = json_decode(base64_decode($row['tra_data']),1);
                $record_id_bill_detail  = $row['tra_record_id'];
                $table_bill_detail      = $row['tra_table'];
                $db_product = new db_query('SELECT *
                                            FROM products
                                            LEFT JOIN product_quantity ON product_id = pro_id
                                            WHERE pro_id = ' . $data['bid_pro_id'] . '
                                            AND store_id = ' . $store);
                while($row_pro = mysqli_fetch_assoc($db_product->result)) {
                    // cap nhat lai so luong sau khi khoi phuc hoa don nhap
                    $sql_minus = 'UPDATE product_quantity SET pro_quantity = pro_quantity + ' . $data['bid_pro_number'] . ' WHERE product_id = ' . $row_pro['product_id'] . ' AND store_id = ' . $store;
                    $db_update = new db_execute($sql_minus);
                    unset($db_update);
                }unset($db_product);
                trash_recovery($record_id_bill_detail, $table_bill_detail);
            }
            unset($sql);
            trash_recovery($record_id, $table);
            
            $array_return = array('success'=>1);
        }
        echo json_encode($array_return);
    }// end khoi phuc hoa don
    // xoa hoa don vinh vien trong thung rac
    function deleteRecord(){
        checkPermission('delete');
        $tra_financies  = 'financial'; 
        $record_id  = getValue('record_id','int','POST',0);
        $table      = getValue('table','str','POST','');
        // check 
        if($record_id == 0 || !$record_id){
            $array_return = array('success'=>0);
            exit();
        }
        if($table == '' || !$table){
            $array_return = array('success'=>0);
            exit();
        }
        // laay ra id financial cua hoa don can xoa
        $db_fin = new db_query('SELECT * FROM trash 
                                WHERE tra_record_id = ' . $record_id .'
                                AND tra_table = \''.$table.'\'');
        $data_fin   = mysqli_fetch_assoc($db_fin->result);unset($db_fin);
        $data_fin   = json_decode(base64_decode($data_fin['tra_data']),1);
        $tra_fin_id = $data_fin['arr_fin_id'];
        $tra_fin_id = explode(',',$tra_fin_id);
        foreach($tra_fin_id as $fin_id){
            terminal_delete(intval(trim($fin_id)), $tra_financies);
        }
        // xoa chi tiet hoa don trong thung rac
        if(trim($table) == 'bill_in'){
            terminal_delete($record_id, 'bill_in_detail');
        }
        //
        if(trim($table) == 'bill_out'){
            terminal_delete($record_id, 'bill_out_detail');
        }
        terminal_delete($record_id, $table);
        $array_return = array('success'=>1);
        echo json_encode($array_return);
    }// end xoa vinh vien trong thung rac
}
$ajax = new HomeAjax();
$ajax->execute();