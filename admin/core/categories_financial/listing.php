<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute'){
    switch($action_modal){
        case 'add_category':
            checkPermission('add');
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type','cat_type',0,1,'');
            $myform->add('cat_desc','cat_desc',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cat_picture = getValue('cat_picture','str','POST','');
                if($cat_picture){
                    $myform->add('cat_picture','cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute_return();echo $myform->generate_insert_SQL();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới danh mục '.$last_id.' bảng categories_multi');
                redirect('index.php');
            }
            break;

    }
}

//Phần hiển thị
//Khởi tạo
$left_control = '';
$left_column = '';
$left_column_title = 'Danh sách kho hàng';

$add_btn = getPermissionValue('add');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn, '', $trash_btn, 1);
$list_category = category_type($cat_type);

?>
    <ul class="list-unstyled list-vertical-crm">
        <li data-cat="all">
            <label class="active cat_name"><b><i class="fa fa-list fa-fw"></i> Tất cả </b></label>
        </li>
        <? foreach ($list_category as $cat) {?>
            <?
            //nếu cat_parent_id = 0 thì là category cha
            if ($cat['cat_parent_id'] == 0) { ?>
                <li cat-parent="<?=$cat['cat_id']?>">
                    <label><?= $cat['cat_name'] ?></label>
                </li>
            <? }

            ?>
            <?
            //foreach lại 1 lần nữa trong mảng categoy để lấy ra các category con của cat cha hiện tại
            foreach ($list_category as $cat_child) {

                if($cat_child['cat_parent_id']== $cat['cat_id']){
                    ?>
                    <li data-cat="<?= $cat_child['cat_id'] ?>" data-parent="<?=$cat_child['cat_parent_id']?>" class="list-vertical-item">
                        <label class="cat_name"><?= $cat_child['cat_name'] ?></label>
                    </li>
                <?}
            }?>
        <?
        } ?>
        <li data-cat="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác </b></label>
        </li>
    </ul>
<?
$left_column = ob_get_contents();
ob_clean();

$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('left_column',$left_column);

$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('modal_1column');