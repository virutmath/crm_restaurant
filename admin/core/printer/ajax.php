<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class PrinterAjax extends AjaxCommon {
    function printOrder() {
        $desk_id = getValue('desk_id','int','POST',0);
        check_desk_exist($desk_id);
        $list_menu = getValue('list_menu','arr','POST',array());
        //cập nhật số lượng thực đơn đã in bếp vào trường cdm_printed_number
        $array_menu_success = array();
        foreach($list_menu as $menu) {
            $sql = 'UPDATE current_desk_menu
                    SET cdm_printed_number = cdm_printed_number + ' . $menu['print_number'].'
                    WHERE cdm_menu_id = '.$menu['men_id'].'
                    AND cdm_desk_id = '.$desk_id;
            $db_update = new db_execute($sql);
            if($db_update->total) {
                $array_menu_success[] = $menu;
            }
        }
        if(!$array_menu_success) {
            return;
        }else{
            $array_return = array('success'=>1,'list_menu'=>$array_menu_success);
        }
        //log action
        log_action(ACTION_LOG_PRINT_ORDER,'In chế biến xuống bếp - bàn ID ' . $desk_id);
        die(json_encode($array_return));
    }
}
$ajax = new PrinterAjax();
$ajax->execute();
