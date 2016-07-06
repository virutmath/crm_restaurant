<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class FinancialAjax extends AjaxCommon {
    function loadFormAddMoneyTicketIn() {
        //Kiểm tra quyền add
        checkPermission('add');
        //Lấy ra danh sách lý do thu
        $list_fin_cat = array();
        $db_cat = new db_query('SELECT cat_id, cat_name FROM '.$this->cat_table.' WHERE cat_type = "money_in"');
        while($row = mysqli_fetch_assoc($db_cat->result)) {
            $list_fin_cat[$row['cat_id']] = $row['cat_name'];
        }
        //user type
        $user_type = array(
            '' => ' -- Lựa chọn --',
            USER_TYPE_STAFF => 'Danh sách nhân viên',
            USER_TYPE_CUSTOMER => 'Danh sách khách hàng',
            USER_TYPE_SUPPLIER => 'Danh sách nhà cung cấp'
        );
        //open modal
        $this->openModal();
        $this->add(
            $this->form->staticText(array(
                'label'=>'Ngày lập',
                'value' => date('d/m/Y H:i')
            ))
        );
        $this->add(
            $this->form->number(array(
                'label'=>'Số tiền',
                'name'=>'fin_money',
                'id'=>'fin_money',
                'addon'=>DEFAULT_MONEY_UNIT
            ))
        );
        $this->add(
            $this->form->list_radio(array(
                'label' => 'Hình thức thanh toán',
                'list' => array(
                    array(
                        'label' => 'Tiền mặt',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type1',
                        'value' => PAY_TYPE_CASH,
                        'is_check' => 1
                    ),
                    array(
                        'label' => 'Thẻ',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type2',
                        'value' => PAY_TYPE_CARD,
                        'is_check' => 0
                    )
                ),
                'column' => 2
            ))
        );
        $this->add(
            $this->form->select(array(
                'label'=>'Loại phiếu',
                'name'=>'fin_cat_id',
                'id'=>'fin_cat_id',
                'option'=>$list_fin_cat
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Lý do khác',
                'name'=>'fin_reason_other',
                'id'=>'fin_reason_other'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Chứng từ kèm theo',
                'name'=>'fin_billcode',
                'id'=>'fin_billcode',
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Người nộp tiền',
                'name'=>'fin_username',
                'id' => 'fin_username',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên người nộp tiền'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Địa chỉ',
                'name' => 'fin_address',
                'id' => 'fin_address',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ'
            ))
        );
        $this->add(
            $this->form->selectMultiRelate(array(
                array(
                    'label'=>'Hoặc chọn từ',
                    'name'=>'user_type',
                    'id'=>'user_type',
                    'option'=>$user_type,
                    'action'=>'getUserList'
                ),
                array(
                    'name'=>'auto_username',
                    'id'=>'auto_username',
                    'extra'=>'onchange="fill_data()"'
                )
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'fin_note',
                'id' => 'fin_note',
            ))
        );

        //close modal
        $this->closeModal('add_money_ticket_in');
    }
    function loadFormEditMoneyTicketIn() {
        //Kiểm tra quyền edit
        checkPermission('edit');
        //Lấy ra danh sách lý do thu
        $list_fin_cat = array();
        $db_cat = new db_query('SELECT cat_id, cat_name FROM ' . $this->cat_table . ' WHERE cat_type = "money_in"');
        while ($row = mysqli_fetch_assoc($db_cat->result)) {
            $list_fin_cat[$row['cat_id']] = $row['cat_name'];
        }
        //user type
        $user_type = array(
            '' => ' -- Lựa chọn --',
            USER_TYPE_STAFF => 'Danh sách nhân viên',
            USER_TYPE_CUSTOMER => 'Danh sách khách hàng',
            USER_TYPE_SUPPLIER => 'Danh sách nhà cung cấp'
        );
        //lấy data record
        $record_id = getValue('record_id', 'int', 'POST', 0);
        $db_query = new db_query('SELECT * FROM financial WHERE fin_id = ' . $record_id);
        $data_record = mysqli_fetch_assoc($db_query->result);
        //open modal
        $this->openModal();
        $this->add(
            $this->form->staticText(array(
                'label' => 'Ngày lập',
                'value' => date('d/m/Y H:i', $data_record['fin_date'])
            ))
        );
        $this->add(
            $this->form->number(array(
                'label' => 'Số tiền',
                'name' => 'fin_money',
                'id' => 'fin_money',
                'value' => $data_record['fin_money'],
                'addon' => DEFAULT_MONEY_UNIT
            ))
        );
        $this->add(
            $this->form->list_radio(array(
                'label' => 'Hình thức thanh toán',
                'list' => array(
                    array(
                        'label' => 'Tiền mặt',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type1',
                        'value' => PAY_TYPE_CASH,
                        'is_check' => $data_record['fin_pay_type'] == PAY_TYPE_CASH
                    ),
                    array(
                        'label' => 'Thẻ',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type2',
                        'value' => PAY_TYPE_CARD,
                        'is_check' => $data_record['fin_pay_type'] == PAY_TYPE_CARD
                    )
                ),
                'column' => 2
            ))
        );
        $this->add(
            $this->form->select(array(
                'label' => 'Loại phiếu',
                'name' => 'fin_cat_id',
                'id' => 'fin_cat_id',
                'option' => $list_fin_cat,
                'selected' => $data_record['fin_cat_id']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Lý do khác',
                'name' => 'fin_reason_other',
                'id' => 'fin_reason_other',
                'value' => $data_record['fin_reason_other']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Chứng từ kèm theo',
                'name' => 'fin_billcode',
                'id' => 'fin_billcode',
                'value' => $data_record['fin_billcode']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Người nộp tiền',
                'name' => 'fin_username',
                'id' => 'fin_username',
                'value' => $data_record['fin_username'],
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên người nộp tiền'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Địa chỉ',
                'name' => 'fin_address',
                'id' => 'fin_address',
                'value' => $data_record['fin_address'],
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ'
            ))
        );
        $this->add(
            $this->form->selectMultiRelate(array(
                array(
                    'label' => 'Hoặc chọn từ',
                    'name' => 'user_type',
                    'id' => 'user_type',
                    'option' => $user_type,
                    'action' => 'getUserList'
                ),
                array(
                    'name' => 'auto_username',
                    'id' => 'auto_username',
                    'extra' => 'onchange="fill_data()"'
                )
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'fin_note',
                'id' => 'fin_note',
                'value' => $data_record['fin_note']
            ))
        );

        //close modal
        $this->closeModal('edit_money_ticket_in');
    }
    function loadFormAddMoneyTicketOut() {
        //Kiểm tra quyền add
        checkPermission('add');
        //Lấy ra danh sách lý do thu
        $list_fin_cat = array();
        $db_cat = new db_query('SELECT cat_id, cat_name FROM ' . $this->cat_table . ' WHERE cat_type = "money_out"');
        while ($row = mysqli_fetch_assoc($db_cat->result)) {
            $list_fin_cat[$row['cat_id']] = $row['cat_name'];
        }
        //user type
        $user_type = array(
            '' => ' -- Lựa chọn --',
            USER_TYPE_STAFF => 'Danh sách nhân viên',
            USER_TYPE_CUSTOMER => 'Danh sách khách hàng',
            USER_TYPE_SUPPLIER => 'Danh sách nhà cung cấp'
        );
        //open modal
        $this->openModal();
        $this->add(
            $this->form->staticText(array(
                'label' => 'Ngày lập',
                'value' => date('d/m/Y H:i')
            ))
        );
        $this->add(
            $this->form->number(array(
                'label' => 'Số tiền',
                'name' => 'fin_money',
                'id' => 'fin_money',
                'addon' => DEFAULT_MONEY_UNIT
            ))
        );
        $this->add(
            $this->form->list_radio(array(
                'label' => 'Hình thức thanh toán',
                'list' => array(
                    array(
                        'label' => 'Tiền mặt',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type1',
                        'value' => PAY_TYPE_CASH,
                        'is_check' => 1
                    ),
                    array(
                        'label' => 'Thẻ',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type2',
                        'value' => PAY_TYPE_CARD,
                        'is_check' => 0
                    )
                ),
                'column' => 2
            ))
        );
        $this->add(
            $this->form->select(array(
                'label' => 'Loại phiếu',
                'name' => 'fin_cat_id',
                'id' => 'fin_cat_id',
                'option' => $list_fin_cat
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Lý do khác',
                'name' => 'fin_reason_other',
                'id' => 'fin_reason_other'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Chứng từ kèm theo',
                'name' => 'fin_billcode',
                'id' => 'fin_billcode',
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Người nhận tiền',
                'name' => 'fin_username',
                'id' => 'fin_username',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên người nhận tiền'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Địa chỉ',
                'name' => 'fin_address',
                'id' => 'fin_address',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ'
            ))
        );
        $this->add(
            $this->form->selectMultiRelate(array(
                array(
                    'label' => 'Hoặc chọn từ',
                    'name' => 'user_type',
                    'id' => 'user_type',
                    'option' => $user_type,
                    'action' => 'getUserList'
                ),
                array(
                    'name' => 'auto_username',
                    'id' => 'auto_username',
                    'extra' => 'onchange="fill_data()"'
                )
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'fin_note',
                'id' => 'fin_note',
            ))
        );

        //close modal
        $this->closeModal('add_money_ticket_out');
    }
    function loadFormEditMoneyTicketOut() {
        //Kiểm tra quyền edit
        checkPermission('edit');
        //Lấy ra danh sách lý do thu
        $list_fin_cat = array();
        $db_cat = new db_query('SELECT cat_id, cat_name FROM ' . $this->cat_table . ' WHERE cat_type = "money_out"');
        while ($row = mysqli_fetch_assoc($db_cat->result)) {
            $list_fin_cat[$row['cat_id']] = $row['cat_name'];
        }
        //user type
        $user_type = array(
            '' => ' -- Lựa chọn --',
            USER_TYPE_STAFF => 'Danh sách nhân viên',
            USER_TYPE_CUSTOMER => 'Danh sách khách hàng',
            USER_TYPE_SUPPLIER => 'Danh sách nhà cung cấp'
        );
        //lấy data record
        $record_id = getValue('record_id', 'int', 'POST', 0);
        $db_query = new db_query('SELECT * FROM financial WHERE fin_id = ' . $record_id);
        $data_record = mysqli_fetch_assoc($db_query->result);
        //Neu la phieu chi he thong thi khong hien thi sua
        //open modal
        $this->openModal();
        $this->add(
            $this->form->staticText(array(
                'label' => 'Ngày lập',
                'value' => date('d/m/Y H:i', $data_record['fin_date'])
            ))
        );
        $this->add(
            $this->form->number(array(
                'label' => 'Số tiền',
                'name' => 'fin_money',
                'id' => 'fin_money',
                'value' => $data_record['fin_money'],
                'addon' => DEFAULT_MONEY_UNIT
            ))
        );
        $this->add(
            $this->form->list_radio(array(
                'label' => 'Hình thức thanh toán',
                'list' => array(
                    array(
                        'label' => 'Tiền mặt',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type1',
                        'value' => PAY_TYPE_CASH,
                        'is_check' => $data_record['fin_pay_type'] == PAY_TYPE_CASH
                    ),
                    array(
                        'label' => 'Thẻ',
                        'name' => 'fin_pay_type',
                        'id' => 'fin_pay_type2',
                        'value' => PAY_TYPE_CARD,
                        'is_check' => $data_record['fin_pay_type'] == PAY_TYPE_CARD
                    )
                ),
                'column' => 2
            ))
        );
        $this->add(
            $this->form->select(array(
                'label' => 'Loại phiếu',
                'name' => 'fin_cat_id',
                'id' => 'fin_cat_id',
                'option' => $list_fin_cat,
                'selected' => $data_record['fin_cat_id']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Lý do khác',
                'name' => 'fin_reason_other',
                'id' => 'fin_reason_other',
                'value' => $data_record['fin_reason_other']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Chứng từ kèm theo',
                'name' => 'fin_billcode',
                'id' => 'fin_billcode',
                'value' => $data_record['fin_billcode']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Người nhận tiền',
                'name' => 'fin_username',
                'id' => 'fin_username',
                'value' => $data_record['fin_username'],
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên người nhận tiền'
            ))
        );
        $this->add(
            $this->form->text(array(
                'label' => 'Địa chỉ',
                'name' => 'fin_address',
                'id' => 'fin_address',
                'value' => $data_record['fin_address'],
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ'
            ))
        );
        $this->add(
            $this->form->selectMultiRelate(array(
                array(
                    'label' => 'Hoặc chọn từ',
                    'name' => 'user_type',
                    'id' => 'user_type',
                    'option' => $user_type,
                    'action' => 'getUserList'
                ),
                array(
                    'name' => 'auto_username',
                    'id' => 'auto_username',
                    'extra' => 'onchange="fill_data()"'
                )
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'fin_note',
                'id' => 'fin_note',
                'value' => $data_record['fin_note']
            ))
        );

        //close modal
        $this->closeModal('edit_money_ticket_out');
    }

    function getUserList() {
        $user_type = getValue('data','int','POST',0);
        $listUser = array();
        switch($user_type) {
            case USER_TYPE_STAFF :
                //Danh sách nhân viên
                $db_query = new db_query('SELECT use_id, use_name AS name, use_address AS address FROM users');
                while ($row = mysqli_fetch_assoc($db_query->result)) {
                    $listUser[$row['use_id']] = $row;
                }
                unset($db_query);
                break;
            case USER_TYPE_CUSTOMER :
                //Danh sách khách hàng
                $db_query = new db_query('SELECT cus_name AS name, cus_id, cus_address AS address FROM customers');
                while ($row = mysqli_fetch_assoc($db_query->result)) {
                    $listUser[$row['cus_id']] = $row;
                }
                unset($db_query);
                break;
            case USER_TYPE_SUPPLIER :
                //Danh sách nhà cung cấp
                $db_query = new db_query('SELECT sup_id, sup_name AS name, sup_address AS address FROM suppliers');
                while ($row = mysqli_fetch_assoc($db_query->result)) {
                    $listUser[$row['sup_id']] = $row;
                }
                unset($db_query);
                break;
        }
        $this->add('<option> -- Chọn -- </option>');
        foreach ($listUser as $use_id => $use_data) {
            $this->add('<option value="' . $use_id . '" data-address="' . $use_data['address'] . '">' . $use_data['name'] . '</option>');
        }
    }

    /**
     * Hàm xóa phiếu thu chi
     * chỉ xóa dược các phiếu thu và phiếu chi tự tạo, các phiếu thu từ hệ thống không
     * xóa ở trong hàm này
     * @return bool
     */
    function deleteMoneyTicket() {
        //Kiểm tra quyền sửa xóa
        checkPermission('trash');
        $record_id = getValue('record_id','int','POST',0);
        //Kiểm tra xem đây có fai hóa đơn sinh ra từ hệ thống không
        $db_data = new db_query('SELECT *
                                 FROM ' . $this->bg_table . '
                                 LEFT JOIN ' . $this->cat_table .' ON cat_id = ' . $this->cat_field .'
                                 WHERE '. $this->id_field . '=' . $record_id);
        $array_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        $disallow_cat = array('money_system_in','money_system_out');
        if(!$array_data) {
            return false;
        }
        if(in_array($array_data['cat_type'],$disallow_cat)) {
            $array_return = array('error'=>'Bạn không thể xóa phiếu này vì nó được sinh ra từ hệ thống','success'=>0);
        }else{
            //Cho phép xóa
            move2trash($this->id_field,$record_id,$this->bg_table,$array_data,$array_data['cat_type']);
            $array_return = array('success'=>1);
        }
        $this->add(json_encode($array_return));
    }

    function terminalDeleteMoneyTicket() {
        //Kiểm tra quyền xóa hoàn toàn
        checkPermission('delete');
        $array_return = array();
        $record_id = getValue('record_id','int','POST',0);
        //kiểm tra xem đây là phiếu được sinh ra từ hệ thống hay phiếu tự thêm
        $db_check = new db_query('SELECT *
                                  FROM trash
                                  WHERE tra_record_id = ' . $record_id .'
                                  AND tra_table = "'.$this->bg_table.'"');
        $array_data = mysqli_fetch_assoc($db_check->result);
        if(!$array_data) {
            //Không tồn tại bản ghi này trong thùng rác, return luôn
            $array_return['error'] = 'Không tồn tại bản ghi này trong thùng rác';
        }else{
            //có dữ liệu bản ghi này trong thùng rác - check xem có phải phiếu hệ thống không
            //check thông qua trường tra_option_filter
            $disallow_type = array('money_system_in','money_system_out');
            if(in_array($array_data['tra_option_filter'], $disallow_type)) {
                //Là phiếu hệ thống không được xóa từ đây
                $array_return['error'] = 'Đây là phiếu do hệ thống tạo. Bạn không thể xóa phiếu này từ đây';
            }else{
                //là phiếu tự tạo, có thể xóa
                terminal_delete($record_id, $this->bg_table);
                $array_return['success'] = 1;
            }
        }
        $this->add(json_encode($array_return));
    }

    /**
     * list các bản ghi trong thùng rác
     */
    function listRecordTrash() {
        $html = '';
        //lấy ra danh sách các bản ghi trong thùng rác của bảng financial
        $control = getValue('control','str','POST','');
        $control = $control == 'in' ? 'in' : 'out';
        $control_text = $control == 'in' ? 'thu' : 'chi';
        $class_context_menu = 'menu-trash';
        //Bắt đầu modal
        $this->openModalNoForm();
        $this->add('<div class="h5 help-block">Danh sách phiếu '.$control_text.' trong thùng rác</div>');

        $this->list->add('','Ngày '.$control_text);
        $this->list->add('','Số phiếu');
        $this->list->add('','Người nhận');
        $this->list->add('','Diễn giải');
        $this->list->add('','Số tiền');

        $db_count = new db_count('SELECT count(*) as count
                                  FROM trash
                                  WHERE tra_table = "'.$this->bg_table.'"
                                        AND tra_option_filter = "money_'.$control.'"');
        $total = $db_count->total;unset($db_count);
        $array_row = trash_list($this->bg_table,10,0,'AND tra_option_filter = "money_'.$control.'"');
        $this->list->limit($total);
        $total_row = count($array_row);
        $html .= $this->list->showHeader($total_row);
        $i = 0;
        //Lấy ra list lý do thu chi
        $db_query = new db_query('SELECT * FROM '.$this->cat_table.' WHERE cat_type = "money_'.$control.'"');
        $list_cat = array();
        while($row = mysqli_fetch_assoc($db_query->result)) {
            $list_cat[$row['cat_id']] = $row['cat_name'];
        }
        foreach($array_row as $row){
            $i++;
            $html .= $this->list->start_tr($i,$row[$this->id_field],'class="'.$class_context_menu.' record-item" onclick="active_record('.$row[$this->id_field].')" data-record_id="'.$row[$this->id_field].'"');
            //Ngày tạo
            $html .= '<td class="center">' . date('d/m/Y H:i', $row['fin_date']) . '</td>';
            //Số phiếu - ID phiếu
            $html .= '<td class="center">' . format_codenumber($row[$this->id_field], 6) . '</td>';
            //Người nộp
            $html .= '<td>'.$row['fin_username'].'</td>';
            //Mô tả
            $html .= '<td>'.$list_cat[$row['fin_cat_id']].'</td>';
            //số tiền
            $html .= '<td class="text-right">' . format_number($row['fin_money']) . '</td>';
            $html .= $this->list->end_tr();
        }
        $html .= $this->list->showFooter();
        $this->add($html);
        $this->closeModalNoForm();
    }
    //Hàm view thông tin phiếu và người tạo phiếu
    function viewTrashMoneyUser() {
        $this->openMindows();
        $this->add('abc');
        $this->closeMindows();
    }


    /**
     * Hàm khôi phục phiếu thu chi từ thùng rác
     */
    function recoveryMoneyTicket() {
        //check quyền recovery
        checkPermission('recovery');
        $record_id = getValue('record_id','int','POST',0);
        if( trash_recovery($record_id,$this->bg_table) === TRUE ) {
            //khôi phục thành công
            $array_return = array('success'=>1);
        }else{
            $array_return = array('error'=>'Khôi phục không thành công');
        }
        $this->add(json_encode($array_return));
    }
}
$ajax = new FinancialAjax();
$ajax->execute();
