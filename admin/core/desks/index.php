<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_section':
            $myform = new generate_form();
            $myform->addTable('sections');
            $myform->add('sec_name','sec_name',0,0,'',1,'Bạn chưa nhập tên khu vực');
            $myform->add('sec_note','sec_note',0,0,'',0);
            $myform->add('sec_service_desk','sec_service_desk',1,0,'',0);
            if(!$myform->checkdata()){
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới khu vực bàn ăn ID '.$last_id);
                redirect('index.php');
            }
            break;
        case 'edit_section':
            $record_id = getValue('record_id','int','POST','');
            $myform = new generate_form();
            $myform->addTable('sections');
            $myform->add('sec_name','sec_name',0,0,'',1,'Bạn chưa nhập tên khu vực');
            $myform->add('sec_note','sec_note',0,0,'',0);
            if(!$myform->checkdata()){
                $db_insert = new db_execute($myform->generate_update_SQL('sec_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa khu vực bàn ăn ID '.$record_id);
                redirect('index.php');
            }
            break;
        case 'add_desk':
            $myform = new generate_form();
            $myform->addTable('desks');
            $myform->add('des_name','des_name',0,0,'',1,'Bạn chưa nhập tên bàn');
            $myform->add('des_sec_id','des_sec_id',1,0,0,1,'Bạn chưa chọn khu vực');
            $myform->add('des_note','des_note',0,0,'',0);
            if(!$myform->checkdata()){
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới bàn ăn ID '.$last_id);
                redirect('index.php');
            }
            break;
        case 'edit_desk':
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable('desks');
            $myform->add('des_name','des_name',0,0,'',1,'Bạn chưa nhập tên bàn');
            $myform->add('des_sec_id','des_sec_id',1,0,0,1,'Bạn chưa chọn khu vực');
            $myform->add('des_note','des_note',0,0,'',0);
            if(!$myform->checkdata()){
                $db_insert = new db_execute($myform->generate_update_SQL('des_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa bàn ăn ID '.$record_id);
                redirect('index.php');
            }
            break;
    }
}


//Phần hiển thị
//Khởi tạo
$left_control = '';
$right_control = '';
$left_column = '';
$right_column = '';
$context_menu = '';

//khối bên trái hiển thị khu vực bàn ăn: sections
$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$array_section = array();
//Lấy ra danh sách khu vực trong nhà hàng - theo chi nhánh
$string_section_id = '0,';
$db_query = new db_query('SELECT sec_id,sec_name
                          FROM sections
                          LEFT JOIN service_desks ON sed_id = sec_service_desk
                          WHERE sed_agency_id = ' . $configuration['con_default_agency']);
while($row = mysqli_fetch_assoc($db_query->result)){
    $db_select = new db_count('SELECT count(*) as count
                                FROM desks
                                WHERE des_sec_id = '.$row['sec_id']);
    $row['count'] = $db_select->total;unset($db_select);
    $array_section[] = $row;
    $string_section_id .= $row['sec_id'] . ',';
}
$string_section_id = rtrim($string_section_id,',');
//Đếm tất cả số bàn đang có - theo section đã được lọc ở trên
$db_count = new db_count('SELECT count(*) as count
                          FROM desks
                          WHERE des_sec_id IN ('.$string_section_id.')');
$left_count_desk = $db_count->total;unset($db_count);
ob_start();
?>
    <ul class="list-unstyled list-vertical-crm">
        <li data-section="all">
            <label class="active section_name"><b><i class="fa fa-list fa-fw"></i> Tất cả (<?=$left_count_desk?>)</b></label>
        </li>
        <? foreach($array_section as $section) {
            ?>
            <li class="list-vertical-item" data-section="<?=$section['sec_id']?>" data-count-desk="<?=$section['count']?>">
                <label class="section_name"><?=$section['sec_name']?> (<?=$section['count']?>)</label>
            </li>
        <?
        }?>
        <li data-section="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?=count_item_trash('desks','agency_'.$configuration['con_default_agency'])?>)</b></label>
        </li>
    </ul>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối bên phải, hiển thị list user tương ứng
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
ob_start();
?>
<div class="list-desk">
<? foreach($array_section as $section){?>
    <div class="section-name bold"><?=$section['sec_name']?></div>
    <?
    $db_desk = new db_query('SELECT *
                              FROM desks WHERE des_sec_id = '.$section['sec_id']);
    while($row = mysqli_fetch_assoc($db_desk->result)){?>
    <div class="col-sm-2 desk-item menu-normal" id="record_<?=$row['des_id']?>" onclick="active_desk(this)" data-record_id="<?=$row['des_id']?>">
        <?=$row['des_name']?>(ID:<?=$row['des_id']?>)
    </div>
        <?
    }?>
    <div class="clearfix"></div>
    <?
}?>
</div>
<?
$right_column = ob_get_contents();
ob_clean();
$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);

$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('modal_2column');