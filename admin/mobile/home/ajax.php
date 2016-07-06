<?
require_once 'inc_security.php';
//class Ajax - version 1.0
$array_return = array();
class SampleAjax extends AjaxCommon {
    // cap nhat ghi chu cua ban
    function NOTEdesk()
    {
        checkPermission('edit');
        $noteDesk   = getValue('note','str','POST','');
        $deskID     = getValue('deskID','int','POST',0);
        // k ton tai noi dung ghi chu hoac k ton tai id ban -> dung ham
        if($noteDesk == '' || $deskID == 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        // kiem tra ban da duoc mo hay chua
        $db_count_current_desk      = new db_query("SELECT * FROM current_desk WHERE cud_desk_id = " . $deskID);
        $db_count                   = mysqli_num_rows($db_count_current_desk->result);unset($db_count_current_desk);
        if($db_count                == 0){
            $array_return['success'] = 0; 
            echo json_encode($array_return);
            exit();
        }
        // insert noi dung ghi chu vao ban co id  = deskID
        $db_update_current_desk     = new db_execute("UPDATE current_desk
                                                      SET cud_note = '" . $noteDesk . "' 
                                                      WHERE cud_desk_id = " . intval($deskID));
        if($db_update_current_desk->total) {
            //thành công
            $array_return['success'] = 'thành công';
        }unset($db_update_current_desk);
        echo json_encode($array_return);
    }
    // cap nhat khach hang cua ban
    function CUSdesk() 
    {
        checkPermission('edit');
        $cusID      = getValue('cusID','int','POST',0);
        $deskID     = getValue('deskID','int','POST',0);
        if($cusID == 0 || $deskID == 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        // kiem tra ban da duoc mo hay chua
        $db_count_current_desk      = new db_query("SELECT * FROM current_desk WHERE cud_desk_id = " . intval($deskID));
        $db_count                   = mysqli_num_rows($db_count_current_desk->result);unset($db_count_current_desk);
        if($db_count                == 0){
            $array_return['success'] = 0; 
            echo json_encode($array_return);
            exit();
        }
        // insert id khach hang vao ban co id  = deskID
        $db_update_current_desk     = new db_execute("UPDATE current_desk
                                                      SET cud_customer_id = '" . intval($cusID) . "' 
                                                      WHERE cud_desk_id = " . intval($deskID));
        if($db_update_current_desk->total) {
            //thành công
            $array_return['success'] = 'thành công';
            log_action(ACTION_LOG_EDIT, 'Thay đổi lựa chọn khách hàng ID (' . $cus_id . ') bàn ID ' . $desk_id);
        }unset($db_update_current_desk);
        echo json_encode($array_return);
    }
    // cap nhat nhan vien phuc vu
    function USEdesk() 
    {
        checkPermission('edit');
        $useID      = getValue('useID','int','POST',0);
        $deskID     = getValue('deskID','int','POST',0);
        if($useID == 0 || $deskID == 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        // kiem tra ban da duoc mo hay chua
        $db_count_current_desk      = new db_query("SELECT * FROM current_desk WHERE cud_desk_id = " . intval($deskID));
        $db_count                   = mysqli_num_rows($db_count_current_desk->result);unset($db_count_current_desk);
        if($db_count                == 0){
            $array_return['success'] = 0; 
            echo json_encode($array_return);
            exit();
        }
        // insert id nhan vien vao ban co id  = deskID
        $db_update_current_desk     = new db_execute("UPDATE current_desk
                                                      SET cud_staff_id = '" . intval($useID) . "' 
                                                      WHERE cud_desk_id = " . intval($deskID));
        if($db_update_current_desk->total) {
            //thành công
            $array_return['success'] = 'thành công';
            //log action
            log_action(ACTION_LOG_EDIT, 'Thay đổi nhân viên phục vụ ID (' . $staff_id . ') bàn ID ' . $desk_id);
        }unset($db_update_current_desk);
        echo json_encode($array_return);
    }
    // them mon vao hoa don
    function ADDbill() 
    {
        checkPermission('add');
        $typePrice  = getValue('typePrice','str','POST','');
        $deskID     = getValue('deskID','int','POST',0);
        $numberMenu = getValue('numberMenu','int','POST',0);
        $idMenu     = getValue('idMenu','int','POST',0);
        $men_price  = getValue('men_price','int','POST',0);
        if($typePrice == '' || $deskID == 0 || $numberMenu == 0 || $idMenu == 0){
            $array_return['success']    = 0; 
            echo json_encode($array_return);
            exit();
        }
        //tạm thời chưa có chương trình khuyến mại gán $cdm_menu_dis_count = 0
        $cdm_menu_discount = 0;
        // kiem tra xem mon vua goi da ton tai hay 
        $db_current_desk_menu           = new db_query("SELECT * FROM current_desk_menu
                                                        WHERE cdm_desk_id = " . intval($deskID) . "
                                                        AND cdm_menu_id = " . intval($idMenu) . "");
        // neu ton tai roi thi update so luong 
        if ( mysqli_num_rows ( $db_current_desk_menu->result ) >= 1 )
        {
            $db_update_menu_number         = new db_execute("UPDATE current_desk_menu 
                                                            SET cdm_number = cdm_number + " . intval($numberMenu) . ",
                                                            cdm_price = " . intval($men_price) . ",
                                                            cdm_price_type = '" . $typePrice . "',
                                                            cdm_menu_discount = " . $cdm_menu_discount . ",
                                                            cdm_updated_time = " . time() . " 
                                                            WHERE 1 
                                                            AND cdm_desk_id = " . intval($deskID) . "
                                                            AND cdm_menu_id = " . intval($idMenu) . "
                                                            ");
            if ( $db_update_menu_number->total >= 1 )
            {
                $array_return['success']    = 1;
                log_action(ACTION_LOG_EDIT, 'Thêm món an (' . $idMenu . ') dã có vào bàn ID ' . $deskID);
            } unset($db_update_menu_number);
            echo json_encode($array_return); 
            
        }
        // neu chua ton tai thi insert thanh menu moi
        // insert thong tin thuc don vao ban co id  = deskID
        else
        {
            $db_insert_current_desk_menu    = new db_execute_return();
            $current_desk_menu              = $db_insert_current_desk_menu->db_execute("INSERT INTO current_desk_menu 
                                                                                        (
                                                                                        cdm_desk_id, 
                                                                                        cdm_menu_id,
                                                                                        cdm_number,
                                                                                        cdm_price,
                                                                                        cdm_price_type,
                                                                                        cdm_menu_discount,
                                                                                        cdm_create_time,
                                                                                        cdm_updated_time
                                                                                        ) VALUES (
                                                                                        ".intval($deskID).",
                                                                                        ".intval($idMenu).",
                                                                                        ".intval($numberMenu).",
                                                                                        ".intval($men_price).", 
                                                                                        '".$typePrice."',
                                                                                        ".$cdm_menu_discount.", 
                                                                                        ".time().",
                                                                                        ".time()." 
                                                                                        )");
            unset($db_insert_current_desk_menu);
            // kiem tra insert da thanh cong chưa thanh cong chưa
            $db_count_current_desk_menu = new db_query("SELECT * FROM current_desk_menu 
                                                        WHERE cdm_desk_id = " . intval($deskID) . " 
                                                        ORDER BY cdm_create_time DESC 
                                                        LIMIT 1");
            if(mysqli_num_rows($db_count_current_desk_menu->result) != 0){
                $array_return['success']    = 1;
                log_action(ACTION_LOG_ADD, 'Thêm món ăn (' . $idMenu . ') vào bàn ID ' . $deskID);
            }
            unset($db_count_current_desk_menu);
            echo json_encode($array_return);
        }
    }
    // xoa menu 
    function deleteMenu()
    {
        checkPermission('delete');
        $desk_id = getValue('desk_id','int','POST',0);
        $menu_id = getValue('menu_id','int','POST',0);
        if ( $desk_id == 0 || $menu_id == 0 )
        {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }
        //kiem tra xem menu co ton tai trong ban k?
        $db_count_curent_desk_menu  = new db_count("SELECT count(*) as count FROM current_desk_menu 
                                                    WHERE cdm_desk_id = " . intval($desk_id) . " 
                                                    AND cdm_menu_id = " . intval($menu_id) . "");
        if ( $db_count_curent_desk_menu->total < 1 ) 
        {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }unset($db_count_curent_desk_menu);
        // thuc hien xoa menu trong ban
        $db_delete_menu             = new db_execute("DELETE FROM current_desk_menu 
                                                    WHERE cdm_desk_id = " . intval($desk_id) . "
                                                    AND cdm_menu_id = " . intval($menu_id) . "");
        unset($db_delete_menu);
        //kiem tra xem menu co ton tai trong ban k?
        $db_return_delete_menu      = new db_count("SELECT count(*) as count FROM current_desk_menu 
                                                    WHERE cdm_desk_id = " . intval($desk_id) . " 
                                                    AND cdm_menu_id = " . intval($menu_id) . "");
        if ( $db_return_delete_menu->total >= 1 ) 
        {
            $array_return['success']    = 0;
            echo json_encode($array_return);
            exit();
        }
        else
        {
            $array_return['success']    = 1;
        }unset($db_return_delete_menu);
        echo json_encode($array_return);
    }
}
$ajax = new SampleAjax();
$ajax->execute();
