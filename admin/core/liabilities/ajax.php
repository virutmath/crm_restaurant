<?
require_once 'inc_security.php';
//class Ajax - version 1.0
$array_return = array();
class HomeAjax extends AjaxCommon
{
    function payDebit(){
        checkPermission('edit');
        $id_object      = getValue('id_object','int','POST',0);
        $type_object    = getValue('type_object','str','POST','');
        $next_money_pay = getValue('next_money_pay','int','POST',0);
        $type_pay       = getValue('type_pay','int','POST',0);
        $ghichu         = getValue('ghichu','str','POST','');
        //
        global $admin_id, $configuration;
        $tabel_bill         = '';
        $table_left_join    = '';
        $bill_id            = '';
        $status             = '';
        $id_obj             = '';
        $debit              = '';
        $table_financy      = '';
        $type_debit         = '';
        $name_object        = '';
        $address_object     = '';
        $cat_id             = 0;
        $result             = array();
        $table_financy      = 'financial';
        $array_return       = array();
        
        if($id_object && $type_object && $next_money_pay){
            // neu nhan duoc kieu hoa don la customer
            if(trim($type_object)   == 'customer'){
                $tabel_bill         = 'bill_in';
                $table_left_join    = 'LEFT JOIN customers ON bii_customer_id = cus_id ';
                $bill_id            = 'bii_id';
                $status             = 'bii_status';
                $id_obj             = 'bii_customer_id';
                $debit              = 'bii_money_debit';
                $type_debit         = 'Công nợ khách hàng';
                $name_object        = 'cus_name';
                $address_object     = 'cus_address';
                $cat_id             = FINANCIAL_CAT_CONG_NO_BAN_HANG;
            }
            // neu nhan duoc kieu hoa don la supplier
            if(trim($type_object)   == 'supplier'){
                $tabel_bill         = 'bill_out';
                $table_left_join    = 'LEFT JOIN suppliers ON bio_supplier_id = sup_id ';
                $bill_id            = 'bio_id';
                $status             = 'bio_status';
                $id_obj             = 'bio_supplier_id';
                $debit              = 'bio_money_debit';
                $type_debit         = 'Công nợ nhà cung cấp';
                $name_object        = 'sup_name';
                $address_object     = 'sup_address';
                $cat_id             = FINANCIAL_CAT_CONG_NO_NHAP_HANG;
            }
            
            $db_pay_debit   = new db_query('SELECT * FROM ' . $tabel_bill . ' ' . $table_left_join . '
                                            WHERE ' . $status . ' <> ' . BILL_STATUS_SUCCESS . ' 
                                            AND ' . $id_obj . ' = ' . $id_object . '
                                            AND ' . $debit . ' <> ' . BILL_STATUS_DEBIT);
            $db_num         = mysqli_num_rows($db_pay_debit->result);
            if($db_num){
                while($data_bill    = mysqli_fetch_assoc($db_pay_debit->result)){
                    //$result[]       = $data_bill;
                    //quá trình thực hiện 
                    //nếu $next_money_pay > 0
                    // nếu số nợ của hóa đơn nhỏ hơn hoặc bằng số tiền khách trả thì thực hiện trừ tiền trong next_money_pay
                    if($next_money_pay > 0 && $next_money_pay >= $data_bill[$debit]){
                        
                        //trừ tiền thành công tiến hành cập nhật lại hóa đơn
                        $db_update_bill    = new db_execute('UPDATE ' . $tabel_bill . ' 
                                                            SET 
                                                            ' . $debit . ' = ' . BILL_STATUS_DEBIT . ', 
                                                            ' . $status . ' = ' . BILL_STATUS_SUCCESS . '
                                                            WHERE ' . $bill_id . ' = ' . $data_bill[$bill_id]);
                        if($db_update_bill->total) {
                            //thành công
                            $array_return['success'] = 'thành công';
                        }
                        unset($db_update_bill);
                        //phát sinh 1 phiếu trong financies
                        $db_insert_financy        = new db_execute_return();
                        $last_id_financy = $db_insert_financy->db_execute('INSERT INTO ' . $table_financy . '
                                                                            (
                                                                            fin_date, 
                                                                            fin_updated_time, 
                                                                            fin_money, 
                                                                            fin_reason_other, 
                                                                            fin_billcode, 
                                                                            fin_username, 
                                                                            fin_address, 
                                                                            fin_cat_id, 
                                                                            fin_pay_type, 
                                                                            fin_note, 
                                                                            fin_admin_id,
                                                                            fin_agency_id
                                                                            ) 
                                                                            VALUES 
                                                                            (
                                                                            '.time().', 
                                                                            '.time().', 
                                                                            '.$data_bill[$debit].', 
                                                                            "'.$type_debit.'", 
                                                                            '.$data_bill[$bill_id].', 
                                                                            "'.$data_bill[$name_object].'", 
                                                                            "'.$data_bill[$address_object].'", 
                                                                            '.$cat_id.', 
                                                                            '.$type_pay.', 
                                                                            "'.$ghichu.'", 
                                                                            '.$admin_id.',
                                                                            '.$configuration['con_default_agency'].'
                                                                            )
                                                                            ');
                        unset($db_insert_financy);
                        //thực hiện trừ tiền trong số khách trả
                        $next_money_pay = $next_money_pay - $data_bill[$debit];
                        continue;
                    }
                    // nếu số tiền khách trả lớn hơn 0 và nhỏ hơn số nợ trong hóa đơn thì thực hiện lấy số tiền nợ trong hóa đơn - cho số khách trả 
                    // cập nhật lại số tiền nợ còn lại sau khi trừ 
                    if($next_money_pay > 0 && $next_money_pay < $data_bill[$debit]){
                        $db_update_bill    = new db_execute('UPDATE ' . $tabel_bill . ' 
                                                            SET ' . $debit . ' = ' . $debit . ' - ' . $next_money_pay . ' 
                                                            WHERE ' . $bill_id . ' = ' . $data_bill[$bill_id]);
                        if($db_update_bill->total) {
                            //thành công
                            $array_return['success'] = 'thành công';
                        }unset($db_update_bill);
                        // cũng phát sinh 1 phiếu trong financies
                        $db_insert_financy        = new db_execute_return();
                        $last_id_financy = $db_insert_financy->db_execute('INSERT INTO ' . $table_financy . '
                                                                            (
                                                                            fin_date, 
                                                                            fin_updated_time, 
                                                                            fin_money, 
                                                                            fin_reason_other, 
                                                                            fin_billcode, 
                                                                            fin_username, 
                                                                            fin_address, 
                                                                            fin_cat_id, 
                                                                            fin_pay_type, 
                                                                            fin_note, 
                                                                            fin_admin_id,
                                                                            fin_agency_id
                                                                            ) 
                                                                            VALUES 
                                                                            (
                                                                            '.time().', 
                                                                            '.time().', 
                                                                            '.$next_money_pay.', 
                                                                            "'.$type_debit.'", 
                                                                            '.$data_bill[$bill_id].', 
                                                                            "'.$data_bill[$name_object].'", 
                                                                            "'.$data_bill[$address_object].'", 
                                                                            '.$cat_id.', 
                                                                            '.$type_pay.', 
                                                                            "'.$ghichu.'", 
                                                                            '.$admin_id.',
                                                                            '.$configuration['con_default_agency'].'
                                                                            )
                                                                            ');
                        // lúc này số tiền khách trả sẽ còn 0;
                        $next_money_pay = 0;
                        continue;
                    }
                    if($next_money_pay == 0){
                        break;
                    }
                }
            }
            echo json_encode($array_return);
            unset($db_pay_debit);
        }
    }
}
$ajax = new HomeAjax();
$ajax->execute();