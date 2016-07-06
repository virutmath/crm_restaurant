<?
require_once 'inc_security.php';
//class Ajax - version 1.0

class PromoAjax extends AjaxCommon {
    //Hàm xử lý thêm mới chien dich khuyen mai
    function AddRecord() {
        global $time_end,$time_start;
        //check quyền
        checkCustomPermission('add');
        // ten chien dich khuyen mai
        $promo_name     = getValue('name','str','POST','');
        if(!$promo_name) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chưa nhập tên chiến dịch khuyến mại';
            die(json_encode($array_return));
        }
        $promo_agencies = getValue('agencies','int','POST',0,3);
        // thoi gian bat dau
        $start_time     = getValue('start_date','str','POST','',3);
        $time_start_h   = getValue('time_start_h','int','POST',0,3);
        $time_start_i   = getValue('time_start_i','int','POST',0,3);
        //thoi gian ket thuc
        $end_time       = getValue('end_date','str','POST','',3);
        $time_end_h     = getValue('time_end_h','int','POST',0,3);
        $time_end_i     = getValue('time_end_i','int','POST',0,3);
        // ghi chú
        $promo_note     = getValue('note','str','POST','',3);

        $list_menus     = getValue('menus','arr','POST',array());

        // dieu kien giam gia
        $promo_condition= getValue('condition','int','POST',0,3);
        // gia tri giảm giá hóa đơn dựa vào kiểu giảm giá promo_type có 2 giá trị là % và tiền mặt
        $promo_value    = getValue('promo_value','int','POST',0,3);
        $promo_type     = getValue('promo_type','int','POST',0,3);
        $time_start     = convertDateTime($start_time, $time_start_h . ':' . $time_start_i . ':0');
        if(!$time_start) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chưa nhập thời gian bắt đầu chiến dịch';
            die(json_encode($array_return));
        }
        $time_end       = convertDateTime($end_time, $time_end_h . ':' . $time_end_i . ':0');
        if(!$time_end) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chưa nhập thời gian kết thúc chiến dịch';
            die(json_encode($array_return));
        }
        if(!$list_menus && !$promo_value) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chương trình khuyến mại không phù hợp';
            die(json_encode($array_return));
        }
        // insert chiến dịch khuyến mãi

        $myform = new generate_form();
        $myform->addTable('promotions');
        $myform->add('pms_name','name',0,0,$promo_name,1,'Bạn chưa nhập tên chiến dịch');
        $myform->add('pms_agency_id','agencies',1,0,$promo_agencies,1,'Bạn chưa chọn địa điểm áp dụng');
        $myform->add('pms_start_time','time_start',0,1,$time_start,1,'Thời gian bắt đầu chưa nhập');
        $myform->add('pms_end_time','time_end',0,1,$time_end,1,'Thời gian kết thúc chưa nhập');
        $myform->add('pms_value_sale','promo_value',1,0,$promo_value,0);
        $myform->add('pms_type_sale','promo_type',1,0,$promo_type,0);
        $myform->add('pms_condition','condition',1,0,$promo_condition,0);
        $myform->add('pms_note','note',0,0,$promo_note,0);
        if (!$myform->checkdata()) {
            $db_insert = new db_execute_return();
            $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
            unset($db_insert);
            if(!$last_id) {
                //lỗi
                $array_return['error'] = 0;
                $array_return['msg'] = 'Đã có lỗi xảy ra. Vui lòng thử lại sau';
                $array_return['success'] = 1;
                die(json_encode($array_return));
            }
            $db_promo_menu = 'INSERT INTO promotions_menu(pms_id ,pms_menu_id, pms_menu_value, pms_menu_type)
                           VALUES';
            //sử dụng id của promotion để insert vào bảng promotions_menu
            foreach($list_menus as $menu) {
                $db_promo_menu .= '(
                           '.$last_id.',
                           '.$menu['men_id'].',
                           '.$menu['men_value'].',
                           '.$menu['men_type'].'
                           ),';
            }
            $db_promo_menu = rtrim($db_promo_menu,',');
            $db_insert_menu = new db_execute($db_promo_menu);unset($db_insert_menu);
            //log action
            log_action(ACTION_LOG_ADD, 'Thêm mới chiến dịch ' . $last_id . ' bảng promotions');
            $array_return = array(
                'success' => 1,
                'msg'=>'Thêm mới thành công'
            );
            die(json_encode($array_return));
        }
    }


    //Hàm xử lý cập nhật chiến dịch khuyến mãi
    function EditRecord() {
        //check quyền
        checkCustomPermission('edit');

        //khai bao bien global
        global $time_end,$time_start;
        $promo_id       = getValue('id','int','POST',0); // id chien dich
        if(!$promo_id) {
            //lỗi không tồn tại id của khuyến mại
            $array_return['error'] = 0;
            $array_return['msg'] = 'Bản ghi không tồn tại';
            die(json_encode($array_return));
        }
        // ten chien dich khuyen mai
        $promo_name     = getValue('name','str','POST','',2);
        if(!$promo_name) {
            //lỗi chưa nhập tên chiến dịch km
            $array_return['error'] = 0;
            $array_return['msg'] = 'Chưa nhập tên chiến dịch khuyến mại';
            die(json_encode($array_return));
        }
        $promo_agencies = getValue('agencies','int','POST',0,3);
        if(!$promo_agencies) {
            //lỗi chưa nhập tên chiến dịch km
            $array_return['error'] = 0;
            $array_return['msg'] = 'Chưa chọn cửa hàng áp dụng khuyến mại';
            die(json_encode($array_return));
        }
        // thoi gian bat dau
        $start_time     = getValue('start_date','str','POST','',3);
        $time_start_h   = getValue('time_start_h','int','POST',0,3);
        $time_start_i   = getValue('time_start_i','int','POST',0,3);
        //thoi gian ket thuc
        $end_time       = getValue('end_date','str','POST','');
        $time_end_h     = getValue('time_end_h','int','POST',0,3);
        $time_end_i     = getValue('time_end_i','int','POST',0,3);
        // ghi chú
        $promo_note     = getValue('note','str','POST','');
        $list_menus     = getValue('menus','arr','POST',array());
        // dieu kien giam gia
        $promo_condition= getValue('condition','str','POST',0,3);
        // gia tri giảm giá hóa đơn dựa vào kiểu giảm giá promo_type có 2 giá trị là % và tiền mặt
        $promo_value    = getValue('promo_value','int','POST',0,3);
        $promo_type     = getValue('promo_type','int','POST',0,3);
        $time_start     = convertDateTime($start_time, $time_start_h . ':' . $time_start_i . ':0');
        if(!$time_start) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chưa nhập thời gian bắt đầu chiến dịch';
            die(json_encode($array_return));
        }
        $time_end = convertDateTime($end_time, $time_end_h . ':' . $time_end_i . ':0');
        if(!$time_end) {
            $array_return['error']  = 0;
            $array_return['msg']    = 'Chưa nhập thời gian kết thúc chiến dịch';
            die(json_encode($array_return));
        }
        // ket quả tra ve


        // update chiến dịch khuyến mãi

        $myform = new generate_form();
        $myform->addTable('promotions');
        $myform->add('pms_name','name',0,0,$promo_name,1,'Bạn chưa nhập tên chiến dịch');
        $myform->add('pms_agency_id','agencies',1,0,$promo_agencies,1,'Bạn chưa chọn địa điểm áp dụng');
        $myform->add('pms_start_time','time_start',0,1,$time_start,1,'Thời gian bắt đầu chưa nhập');
        $myform->add('pms_end_time','time_end',0,1,$time_end,1,'Thời gian kết thúc chưa nhập');
        $myform->add('pms_value_sale','promo_value',1,0,$promo_value,0);
        $myform->add('pms_type_sale','promo_type',1,0,$promo_type,0);
        $myform->add('pms_condition','condition',1,0,$promo_condition,0);
        $myform->add('pms_note','note',0,0,$promo_note,0);
        if (!$myform->checkdata()) {
            $db_update = new db_execute($myform->generate_update_SQL('pms_id', $promo_id));
            //echo $myform->generate_update_SQL('pms_id', $promo_id);
            unset($db_update);
            //log action
            log_action(ACTION_LOG_ADD, 'Chỉnh sửa bản ghi ' . $promo_id . ' bảng promotions');
        }

        //xóa hết các thực đơn có trong chiến dịch khuyến mãi đang sửa và sau đó insert lại danh sách các thực đơn bổ sung
        $db_delete = new db_execute('DELETE FROM promotions_menu WHERE pms_id = '.$promo_id.'');
        unset($db_delete);
        // insert lại những thực đơn cập nhập
        $db_insert = 'INSERT INTO promotions_menu(pms_id ,pms_menu_id, pms_menu_value, pms_menu_type)
                           VALUES';
        //sử dụng id của promotion để insert vào bảng promotions_menu
        foreach($list_menus as $menu) {
            $db_insert .= '(
                           '.$promo_id.',
                           '.$menu['men_id'].',
                           '.$menu['men_value'].',
                           '.$menu['men_type'].'
                           ),';
        }
        $db_insert = rtrim($db_insert,',');
        $db_insert_menu = new db_execute($db_insert);
        unset($db_insert_menu);
        // trả về kết quả thành công
        $array_return['success'] = 1;
        $array_return['msg'] = 'Cập nhật thành công';
        die(json_encode($array_return));
    }
    // remove thực đơn ra khỏi chiến dịch đã có
    function removeMenus(){
        $menu_id = getValue('record_id','int','POST','');
        $del_promo =  new db_execute('DELETE FROM promotions_menu WHERE pms_menu_id = '.$menu_id.'');
        unset($del_promo);

    }

}
$ajax_promo = new PromoAjax();
$ajax_promo->execute();
