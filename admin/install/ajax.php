<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class ResetAjax extends AjaxCommon {
    /* Xóa tất cả dữ liệu trong database với các điều kiện*/
    function resetDefaultAll(){
        $array_return = array();
        $array_table = getValue('tables','arr','POST',array());
        foreach($array_table as $table){//
            switch($table){
                case 'categories_multi':
                    // lấy ra những bản ghi có cat_type cần lấy
                    $db_query_cate = new db_query('SELECT * FROM categories_multi WHERE cat_type IN("money_system_in","money_system_out")');
                    $list_cate = $db_query_cate->resultArray();
                    $db_delete_table    = 'TRUNCATE TABLE categories_multi';
                    $db_delete_exc      = new db_execute($db_delete_table);
                    // insert lại dữ liệu đã lấy ra ở trên
                    $db_insert_cate = 'INSERT INTO categories_multi(cat_name,cat_type,cat_desc,cat_picture,cat_parent_id,cat_has_child,cat_note) VALUES';

                    foreach($list_cate as $cate){
                        $db_insert_cate .= '(
                            "'.$cate['cat_name'].'",
                            "'.$cate['cat_type'].'",
                            "'.$cate['cat_desc'].'",
                            "'.$cate['cat_picture'].'",
                            '.$cate['cat_parent_id'].',
                            '.$cate['cat_has_child'].',
                            "'.$cate['cat_note'].'"
                        ),';
                    }
                    $db_insert_cate = rtrim($db_insert_cate,',');
                    $db_excute_cat = new db_execute($db_insert_cate); unset($db_excute_cat);

                    break;
                case 'admin_users':
                    $db_delete_table    = 'DELETE FROM admin_users WHERE adm_id<>1';
                    $db_delete_exc      = new db_execute($db_delete_table);
                    break;
                case 'admin_users_groups':
                    $db_delete_table    = 'DELETE FROM admin_users_groups WHERE adu_group_id<>1';
                    $db_delete_exc      = new db_execute($db_delete_table);
                    break;
                case 'triggers':
                    $db_delete_table    = 'UPDATE triggers SET tri_status=0';
                    $db_delete_exc      = new db_execute($db_delete_table);
                    break;
                default:
                    $db_query_table = new db_query('SELECT * FROM '.$table.'');
                    $total_row = mysqli_num_rows($db_query_table->result);
                    if($total_row > 0){
                        $db_delete_table    = 'TRUNCATE TABLE '.$table.'';
                        $db_delete_exc      = new db_execute($db_delete_table);
                    } else {
                        continue;
                    }
            }unset($db_delete_exc);
        }
        /* Khi chạy thành công vòng lặp sẽ trả về kết quả thành công*/
        $array_return['success'] = 1;
        $array_return['msg'] = 'Thiết lập dữ liệu mặc định thành công';
        die(json_encode($array_return));

    }
    /* Thêm mới cửa hàng và kho hàng mặc định, khi bắt đầu cài đặt một cửa hàng mới*/
    function addAgenStoreDefault(){
        $name_agencies = getValue('name','str','POST','');
        if($name_agencies == ''){
            $array_return['error'] = 'Bạn chưa nhập tên cửa hàng';
             die(json_encode($array_return));
        }
        $name_stores = getValue('name_store','str','POST','');
        if($name_stores == ''){
            $array_return['error'] = 'Bạn chưa nhập tên cửa hàng';
            die(json_encode($array_return));
        }
        $addres_agencies = getValue('address','str','POST','');
        $phone_agencies = getValue('phone','str','POST','');
        $note_agencies = getValue('note','str','POST','');

        /* Câu lệnh thêm mới dữ liệu*/
        $db_insert_agencies = 'INSERT INTO agencies (age_name,age_address,age_phone,age_note)
                                            VALUES (
                                            "'.$name_agencies.'",
                                            "'.$addres_agencies.'",
                                            "'.$phone_agencies.'",
                                            "'.$note_agencies.'"
                                            )';

        $db_insert_exc = new db_execute($db_insert_agencies);
        /* Thêm mới kho hàng*/
        $db_insert_store = 'INSERT INTO categories_multi(cat_name,cat_type) VALUES ("'.$name_stores.'","stores")';
        $db_store_exc = new db_execute($db_insert_store);
        unset($db_insert_exc);
        unset($db_store_exc);

        $array_return['success'] = 1;
        $array_return['msg'] = 'Thêm mới thành công !';
        die(json_encode($array_return));


    }
    /* Thêm mới quầy phục vụ*/
    function addServDesk(){
        $name_servdesk = getValue('name','str','POST','');
        if($name_servdesk == ''){
            $array_return['error'] = 'Bạn chưa nhập tên quầy phục vụ';
            die(json_encode($array_return));
        }
        $agencies_id = getValue('agencies_id','str','POST','');
        if($agencies_id == ''){
            $array_return['error'] = 'Bạn chưa nhập tên cửa hàng';
            die(json_encode($array_return));
        }
        $phone_servdesk = getValue('phone','str','POST','');
        $note_servdesk = getValue('note','str','POST','');

        /* Câu lệnh thêm mới dữ liệu*/
        $db_insert_servdesk = 'INSERT INTO service_desks (sed_name,sed_phone,sed_agency_id,sed_note)
                                            VALUES (
                                            "'.$name_servdesk.'",
                                            "'.$phone_servdesk.'",
                                            "'.$agencies_id.'",
                                            "'.$note_servdesk.'"
                                            )';

        $db_insert_exc = new db_execute($db_insert_servdesk);
        if($db_insert_exc){
            $array_return['success'] = 1;
            $array_return['msg'] = 'Thêm mới thành công !';
            die(json_encode($array_return));
        } else {
            $array_return['error'] = 'Có lỗi xảy ra!';
            die(json_encode($array_return));
        }
    }
}
$ajax = new ResetAjax();
$ajax->execute();
