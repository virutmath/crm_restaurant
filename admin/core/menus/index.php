<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_category':
            checkPermission('add');
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type','cat_type',0,1,'');
            $myform->add('cat_parent_id','cat_parent_id',1,0,0);
            $myform->add('cat_desc','cat_desc',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cat_picture = getValue('cat_picture','str','POST','');
                if($cat_picture){
                    $myform->add('cat_picture','cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới danh mục '.$last_id.' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'edit_category':
            checkPermission('edit');
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($cat_table);
            $myform->add('cat_name','cat_name',0,0,'',1,'Bạn chưa nhập tên danh mục');
            $myform->add('cat_type','cat_type',0,1,'');
            $myform->add('cat_parent_id','cat_parent_id',1,0,0);
            $myform->add('cat_desc','cat_desc',0,0,'');
            $myform->add('cat_note','cat_note',0,0,'');

            if(!$myform->checkdata()){
                $cat_picture = getValue('cat_picture','str','POST','');
                if($cat_picture){
                    $myform->add('cat_picture','cat_picture',0,0,'');
                    //upload ảnh
                    module_upload_picture($cat_picture);
                }
                $db_insert = new db_execute($myform->generate_update_SQL('cat_id',$record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Sửa danh mục '.$record_id. ' bảng categories_multi');
                redirect('index.php');
            }
            break;
        case 'add_record':

            checkPermission('add');
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('men_name','men_name',0,0,'',1,'Bạn chưa nhập tên thực đơn');
            $myform->add('men_unit_id','men_unit_id',0,0,'',1,'Bạn chưa nhập đơn vị tính');
            $myform->add('men_cat_id','men_cat_id',0,0,'',0,'');
            $myform->add('men_price','men_price',1,0,'',1,'Bạn chưa nhập giá bán chính');
            $myform->add('men_price1','men_price1',1,0,'',0,'');
            $myform->add('men_price2','men_price2',1,0,'',0,'');
            $myform->add('men_note','men_note',0,0,'',0,'');
            //echo $myform->generate_insert_SQL(); die();
            if(!$myform->checkdata()){
                $men_image = getValue('men_image','str','POST','');
                if($men_image){
                    module_upload_picture($men_image);
                    $myform->add('men_image','men_image',0,0,'');
                }
                $db_insert = new db_execute_return();
                $last_id = $db_insert->db_execute($myform->generate_insert_SQL());
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_ADD,'Thêm mới bản ghi '.$last_id.' bảng '.$bg_table);
                redirect('index.php');
            }
            break;
        case 'edit_record':
            checkPermission('edit');
            $record_id = getValue('record_id','int','POST',0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            /* code something */
            $myform->add('men_name','men_name',0,0,'',1,'Bạn chưa nhập tên thực đơn');
            $myform->add('men_unit_id','men_unit_id',0,0,'',1,'Bạn chưa nhập đơn vị tính');
            $myform->add('men_cat_id','men_cat_id',0,0,'',0,'');
            $myform->add('men_price','men_price',1,0,'',1,'Bạn chưa nhập giá bán chính');
            $myform->add('men_price1','men_price1',1,0,'',0,'');
            $myform->add('men_price2','men_price2',1,0,'',0,'');
            $myform->add('men_note','men_note',0,0,'',0,'');
            if(!$myform->checkdata()){
                $men_image = getValue('men_image','str','POST','');
                if($men_image){
                    $myform->add('men_image','men_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($men_image);
                }$men_image = getValue('men_image','str','POST','');
                if($men_image){
                    $myform->add('men_image','men_image',0,0,'');
                    //upload ảnh
                    module_upload_picture($men_image);
                }
                $db_insert = new db_execute($myform->generate_update_SQL($id_field, $record_id));
                unset($db_insert);
                //log action
                log_action(ACTION_LOG_EDIT,'Chỉnh sửa bản ghi '.$record_id.' bảng '.$bg_table);
                redirect('index.php');
            }
            break;
        case 'add_menu_product' :
            //quyền đặc biệt : công thức chế biến
            $custom_permission = 'CONG_THUC_CHE_BIEN';
            checkCustomPermission($custom_permission);
            $mep_menu_id = getValue('mep_menu_id','int','POST',0);
            $mep_product_id = getValue('mep_product_id','int','POST',0);
            $mep_quantity = getValue('mep_quantity','flo','POST',0);
            //check dữ liệu
            if(!$mep_menu_id) {
                add_error_msg('Không có thực đơn nào được chọn');
            }
            if(!$mep_product_id) {
                add_error_msg('Không có sản phẩm nào được chọn');
            }
            if(!$mep_quantity) {
                add_error_msg('Số lượng không hợp lệ');
            }

            if(!$bg_errorMsg) {
                $db = new db_execute('INSERT INTO menu_products (mep_menu_id, mep_product_id, mep_quantity)
                                      VALUES ('.$mep_menu_id.', '.$mep_product_id.', '.$mep_quantity.')
                                      ON DUPLICATE KEY UPDATE
                                      mep_quantity = ' . $mep_quantity);
                log_action(ACTION_LOG_ADD,'Thêm nguyên liệu pro_id '.$mep_product_id.' số lượng '.$mep_quantity.' vào thực đơn ' . $mep_menu_id);
                redirect('index.php');
            }
            break;
        case 'edit_menu_product':
            //quyền đặc biệt : công thức chế biến
            $custom_permission = 'CONG_THUC_CHE_BIEN';
            checkCustomPermission($custom_permission);
            $mep_menu_id = getValue('mep_menu_id','int','POST',0);
            $mep_product_id = getValue('mep_product_id','int','POST',0);
            $mep_quantity = getValue('mep_quantity','flo','POST',0);
            //check dữ liệu
            if(!$mep_menu_id) {
                add_error_msg('Không có thực đơn nào được chọn');
            }
            if(!$mep_product_id) {
                add_error_msg('Không có sản phẩm nào được chọn');
            }
            if(!$mep_quantity) {
                add_error_msg('Số lượng không hợp lệ');
            }
            if(!$bg_errorMsg) {
                $db = new db_execute('UPDATE menu_products
                                      SET mep_quantity = ' . $mep_quantity . '
                                      WHERE mep_menu_id = ' . $mep_menu_id.'
                                        AND mep_product_id = ' . $mep_product_id.'
                                      LIMIT 1');
                log_action(ACTION_LOG_EDIT,'Chỉnh nguyên liệu pro_id '.$mep_product_id.' số lượng '.$mep_quantity.' vào thực đơn ' . $mep_menu_id);
                redirect('index.php');
            }
            break;
    }
}
// cap nhat danh sach menu
$import_menu    = isset($_FILES['import_menu']) ? $_FILES['import_menu'] : '';
if($isAjaxRequest && $import_menu)
{
    if ( $import_menu['type']  === 'application/vnd.ms-excel')
    {
        //ten file excel
        $nameExcel              = $import_menu['tmp_name'];
         //luu du lieu trong file excel
        $dataMenu               = analyzeExcel($nameExcel);
        foreach ($dataMenu as $value )
        {
            $men_name       = $value['ten_thucdon'];
            $donvi_tinh     = $value['donvi_tinh'];
            $menu1          = $value['menu_cap_1'];
            $menu2          = $value['menu_cap_2'];
            if ( $menu1     == '' ) $menu1 = $menu2;
            if ( $menu1 == '' && $menu2 == '' || $menu2 == '' ) continue;
            
            $men_cat_id     = 0;
            $men_price      = '' ? 0 : floatval($value['gia_ban']);
            $men_price1     = 0;
            $men_price2     = 0;
            $men_image      = '';
            $men_note       = '';
            $men_editable   = 0;
            $list_pro       = $value['nguyenlieu'];
            //lay ra id cua don vi tinh
            $unit_id        = 0;
            $uni_note       = '';
            // cat id
            $cat_id         = 0;
            // kiem tra xem don vi tinh da ton tai trong bang units chua
            $db_unit        = new db_query("SELECT uni_id FROM units 
                                            WHERE uni_name = '" . trim($donvi_tinh) . "'");
             //neu co roi thi lay ra id cua don vi tinh do
            if( mysqli_num_rows($db_unit->result) >= 1 )
            {
                $data_units = mysqli_fetch_assoc($db_unit->result);
                 //id don vi tinh
                $unit_id    = $data_units['uni_id'];
            } //neu chua co thi insert don vi tinh vao bang unit sau do lai lay ra id cua don vi tinh vua insert vao
            else{
                $db_insert_unit = new db_execute_return;
                $db_units_id    = $db_insert_unit->db_execute("INSERT INTO units
                                                                (
                                                                uni_name, 
                                                                uni_note
                                                                ) 
                                                                VALUES 
                                                                (
                                                                '" . trim($donvi_tinh) . "',
                                                                '".$uni_note."'
                                                                )");
                unset($db_insert_unit);
                $unit_id = $db_units_id;
            }
            // lay ra men cat id cua menu 
            // kiem tra xem menu cap 1 categories dda ton tai chua neu chua ton tai thi insert thanh ban ghi moi
            $db_categories_1 = new db_query("SELECT cat_id FROM categories_multi
                                            WHERE cat_name = '" .trim($menu1) . "' 
                                            AND cat_type = '" . MENU_CAT_TYPE . "'");
            if ( mysqli_num_rows($db_categories_1->result) >= 1 )
            {
                $data_cat   = mysqli_fetch_assoc($db_categories_1->result);
                $cat_id = $data_cat['cat_id'];
            }
            else
            {
                $cat_desc = '';
                $cat_picture = '';
                $cat_parent_id = 0;
                $cat_has_child = 0;
                $cat_note = '';
                $db_insert_categories = new db_execute_return;
                $db_categories_id = $db_insert_categories->db_execute("INSERT INTO categories_multi
                                                                    (
                                                                    cat_name, 
                                                                    cat_type, 
                                                                    cat_desc, 
                                                                    cat_picture, 
                                                                    cat_parent_id, 
                                                                    cat_has_child, cat_note
                                                                    ) VALUES (
                                                                    '" .trim($menu1) . "',
                                                                    '" . MENU_CAT_TYPE . "',
                                                                    '".$cat_desc."',
                                                                    '".$cat_picture."',
                                                                    ".$cat_parent_id.",
                                                                    ".$cat_has_child.",
                                                                    '".$cat_note."'
                                                                    )");
                unset($db_insert_categories);
                $cat_id = $db_categories_id;
            }
            $men_cat_id = $cat_id;
            unset($db_categories_1); 
            // kiem tra xem menu cap 2 da ton tai hay chua  neu chua thi inset thang ban ghi moi
            $db_categories_2 = new db_query("SELECT cat_id FROM categories_multi
                                            WHERE cat_name = '" .trim($menu2) . "' 
                                            AND cat_parent_id = " . $cat_id . "
                                            AND cat_type = '" . MENU_CAT_TYPE . "'");
            if ( mysqli_num_rows($db_categories_2->result) >= 1 )
            {
                $data_cat_2   = mysqli_fetch_assoc($db_categories_2->result);
                $men_cat_id = $data_cat_2['cat_id'];
            }
            else
            {
                $cat_desc = '';
                $cat_picture = '';
                $cat_parent_id = $cat_id;
                $cat_has_child = 0;
                $cat_note = '';
                $db_insert_categories = new db_execute_return;
                $db_categories_id = $db_insert_categories->db_execute("INSERT INTO categories_multi
                                                                    (
                                                                    cat_name, 
                                                                    cat_type, 
                                                                    cat_desc, 
                                                                    cat_picture, 
                                                                    cat_parent_id, 
                                                                    cat_has_child, cat_note
                                                                    ) VALUES (
                                                                    '" .trim($menu2) . "',
                                                                    '" . MENU_CAT_TYPE . "',
                                                                    '".$cat_desc."',
                                                                    '".$cat_picture."',
                                                                    ".$cat_parent_id.",
                                                                    ".$cat_has_child.",
                                                                    '".$cat_note."'
                                                                    )");
                unset($db_insert_categories);
                $men_cat_id = $db_categories_id;
            }unset($db_categories_2); 
           //kiem tra xem menu co trong csdl k 
           //neu co thì update lai thong tin menu dong thoi lay ra id cua menu do
           //chua co thì insert moi
            $menu_id = 0;
            $db_menu        = new db_query("SELECT * FROM menus 
                                            WHERE men_name = '" . trim($men_name) . "'");
            if ( mysqli_num_rows($db_menu->result) >= 1)
            {
                $db_update_menu = new db_execute("UPDATE menus
                                                men_unit_id=".$unit_id.",
                                                men_cat_id=".$men_cat_id.",
                                                men_price=".$men_price.",
                                                men_price1=".$men_price1.",
                                                men_price2=".$men_price2.",
                                                men_image='".$men_image."',
                                                men_note='".$men_note."',
                                                men_editable=".$men_editable." 
                                                WHERE men_name = '" . trim($men_name) . "'"
                                                );
                unset($db_update_menu);
                $data_menu = mysqli_fetch_assoc($db_menu->result);
                $menu_id = $data_menu['men_id'];
            }else
            {
                $db_insert_menu = new db_execute_return;
                $db_menu_id     = $db_insert_menu->db_execute("INSERT INTO menus
                                                                (
                                                                men_name, 
                                                                men_unit_id, 
                                                                men_cat_id, 
                                                                men_price, 
                                                                men_price1, 
                                                                men_price2, 
                                                                men_image, 
                                                                men_note, 
                                                                men_editable
                                                                ) 
                                                                VALUES(
                                                                '".trim($men_name)."',
                                                                ".$unit_id.",
                                                                ".$men_cat_id.",
                                                                ".$men_price.",
                                                                ".$men_price1.",
                                                                ".$men_price2.",
                                                                '".$men_image."',
                                                                '".$men_note."',
                                                                ".$men_editable."
                                                                )");
                unset($db_insert_menu);
                $menu_id = $db_menu_id;
            }
            
            foreach ( $list_pro as $val)
            {
                $pro_name = $val['ten_nguyenlieu'];
                // so luong cua nguyen lieu
                $mep_quantity = $val['soluong'];
                $pro_image = '';
                $pro_note = '';
                $pro_cat_id = 0;
                $pro_unit_id = 0;
                $pro_code = '';
                $pro_instock = 0;
                $pro_status = 0;
                $idPro = 0;
                $array_replace = array('(g)','(gói)','(ml)', '(hộp)', '(miếng)', '(kg)');
                $pro_name = str_ireplace($array_replace,'',$pro_name);
                //don vi tinh cua nguyen lieu
                $uni_name = trim(str_replace(array($pro_name, '(', ')'),'',$val['ten_nguyenlieu']));
                // kiem tra xem don vi tinh da ton tai chua
                // roi thi lay ra id
                // chua thi insert thanh ban ghi moi roi lay ra id
                $uni_note = '';
                $db_unit = new db_query("SELECT * FROM units 
                                        WHERE uni_name = '".$uni_name."'");
                if ( mysqli_num_rows($db_unit->result) >= 1 )
                {
                    $data_unit = mysqli_fetch_assoc($db_unit->result);
                    $pro_unit_id = $data_unit['uni_id'];
                }else{
                    $db_insert_unit = new db_execute_return;
                    $db_unit_id = $db_insert_unit->db_execute("INSERT INTO units
                                                                (
                                                                uni_name, 
                                                                uni_note
                                                                ) VALUES (
                                                                '".$uni_name."',
                                                                '".$uni_note."'
                                                                )");
                    $pro_unit_id = $db_unit_id;
                    unset ($db_insert_unit);
                }unset($db_unit);
                // kiem tra trong csdl ton tai nguyen lieu nay chua
                // neu co roi thi lay ra id
                // neu chua co thi insert vao song lay ra id
                $dbPro  = new db_query("SELECT pro_id FROM products 
                                        WHERE pro_name = '" . trim($pro_name) . "'");
                //neu co roi
                if( mysqli_num_rows($dbPro->result) >= 1 )
                {
                    $dataPro = mysqli_fetch_assoc($dbPro->result);
                     //lay ra id cua nguyen lieu
                    $idPro   = $dataPro['pro_id'];
                }
                 //neu chua co thi insert nguyen lieu vao bang products
                else{   
                    $db_insert_product  = new db_execute_return;
                    $db_product_id      = $db_insert_product->db_execute("INSERT INTO products
                                                                        (
                                                                        pro_name, 
                                                                        pro_image, 
                                                                        pro_note, 
                                                                        pro_cat_id,
                                                                        pro_unit_id, 
                                                                        pro_code, 
                                                                        pro_instock, 
                                                                        pro_status
                                                                        ) 
                                                                        VALUES 
                                                                        (
                                                                        '" . trim($pro_name) . "',
                                                                        '" . $pro_image . "',
                                                                        '" . $pro_note . "',
                                                                        " . $pro_cat_id . ",
                                                                        " . $pro_unit_id . ",
                                                                        '" . $pro_code . "',
                                                                        " . $pro_instock . ",
                                                                        " . $pro_status . "
                                                                        )");
                    
                    unset($db_insert_product);
                    $idPro = $db_product_id;
                }unset($dbPro);
                // co id product roi 
                // lay ra id cua tat ca cac kho cua cua hang
                //$arrStore_id = array();
//                $db_store = new db_query ("SELECT * FROM categories_multi 
//                                           WHERE cat_type = '" . STORE_CAT_TYPE . "'");
//                while ( $data_store = mysqli_fetch_assoc($db_store->result) )
//                {
//                    $arrStore_id[] = $data_store['cat_id'];
//                }
                //
                //sau khi co dc id cua nguyen lieu va id cua menu 
                // neu ton tai ca menu id va pro id thi update nguoc lai thi insert 
                $db_menu_quantity = new db_query ("SELECT * FROM menu_products 
                                                   WHERE mep_menu_id = " . $menu_id . "
                                                   AND mep_product_id = " . $idPro . "");
                if ( mysqli_num_rows($db_menu_quantity->result) >= 1 )
                {
                    $db_update_menu_quantity = new db_execute("UPDATE menu_products
                                                                mep_quantity = mep_quantity + " . floatval($mep_quantity) . "
                                                                WHERE mep_menu_id = " . $menu_id . "
                                                                AND mep_product_id = " .$idPro . ""
                                                                );
                    unset($db_update_menu_quantity);
                }
                else{
                    $sql_menu_products = new db_execute("INSERT INTO menu_products
                                                        (
                                                        mep_menu_id, 
                                                        mep_product_id, 
                                                        mep_quantity
                                                        ) 
                                                        VALUES(
                                                        ".$menu_id.",
                                                        ".$idPro.",
                                                        ".floatval($mep_quantity)."
                                                        )");
                    unset($sql_menu_products);
                }
            }
        }
    }     
}
//Phần hiển thị
//Khởi tạo
$left_control = '';
$center_control = '';
$right_control = '';
$footer_control = '';

$left_column = '';
$left_column_title = 'Danh mục';

$center_column_title = 'Danh sách thực đơn';
$center_column = '';

$right_column = '';
$right_column_title = 'Công thức chế biến';
$context_menu = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$list_category = category_type($cat_type);

$db_count = new db_count('SELECT count(*) as count FROM ' . $bg_table);
$all_count = $db_count->total;
unset($db_count);
$db_count = new db_count('SELECT count(*) as count FROM trash WHERE tra_table = "' . $bg_table . '"');
$trash_count = $db_count->total;
unset($db_count);
ob_start();
?>
    <ul class="list-unstyled list-vertical-crm">
        <li data-cat="all">
            <label class="active cat_name"><b><i class="fa fa-list fa-fw"></i> Tất cả (<?= $all_count ?>)</b></label>
        </li>
        <? foreach ($list_category as $cat) {?>
            <?
            //nếu cat_parent_id = 0 thì là category cha
            if ($cat['cat_parent_id'] == 0) {
                //Đếm số bản ghi có trong tất cả các category con của category này
                $db_count = new db_count('SELECT count(*) as count
                                          FROM ' . $bg_table . '
                                          WHERE ' . $cat_field . ' IN (
                                            SELECT cat_id
                                            FROM categories_multi
                                            WHERE cat_id = '.$cat['cat_id'].' OR cat_parent_id = '.$cat['cat_id'].')');
                $cat_parent_count = $db_count->total;unset($db_count);
                ?>
                <li cat-parent="<?=$cat['cat_id']?>" data-cat="<?=$cat['cat_id'] ?>" class="list-vertical-item">
                    <label class="cat_name"><i class="fa fa-minus-square-o collapse-li"></i> <?= $cat['cat_name'] ?> (<?=$cat_parent_count?>)</label>
                </li>
            <? }

            ?>
            <?
            //foreach lại 1 lần nữa trong mảng category để lấy ra các category con của cat cha hiện tại
            foreach ($list_category as $cat_child) {
                //đếm số bản ghi trong mỗi cat
                $db_count = new db_count('SELECT count(*) as count FROM ' . $bg_table . ' WHERE ' . $cat_field . ' = ' . $cat_child['cat_id']);
                $cat_count = $db_count->total;
                unset($db_count);
                if($cat_child['cat_parent_id']== $cat['cat_id']){
                    ?>
                    <li data-cat="<?= $cat_child['cat_id'] ?>" data-parent="<?=$cat_child['cat_parent_id']?>" class="list-vertical-item">
                        <label class="cat_name cat_sub"><i class="fa fa-caret-right"></i> <?= $cat_child['cat_name'] ?> (<?= $cat_count ?>)</label>
                    </li>
                <?}
            }?>
            <?
        } ?>
        <li data-cat="trash">
            <label class="section_name"><b><i class="fa fa-trash fa-fw"></i> Thùng rác (<?= $trash_count ?>)</b></label>
        </li>
    </ul>
<?
$left_column = ob_get_contents();
ob_clean();

//Khối trung tam, hiển thị list menus tương ứng
$center_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$center_control .= '<form action="" method="post" enctype="multipart/form-data" id="update-menu" name="update_menu">';
$center_control .= '<label for="file-menu" class="control-btn"><i class="fa fa-download"></i> Cập nhật thực đơn</label>';
$center_control .= '<input id="file-menu" type="file" name="import_menu" class="file_menu" onchange="updateMenu()"/>';
$center_control .= '<button type="submit" id="submit_form"></button>';
$center_control .= '</form>';
#Bắt đầu với datagrid
$list = new dataGrid($id_field,3000);
$list->add('men_id','Mã');
$list->add('men_name','Tên thực đơn','string',1,1);
$list->add('men_unit_id','Đơn vị tính','string',0,0);
$list->add('men_price','Giá bán');
$list->add('','SL tồn');

$db_count = new db_count('SELECT count(*) as count
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ');
$total = $db_count->total;unset($db_count);
$cat_id = getValue('cat_id','int','POST',0);
if($cat_id && $isAjaxRequest){
    $db_extra_left_join = ' LEFT JOIN ' . $cat_table .' ON '. $cat_field . ' = cat_id ';
    $db_extra_and       = 'AND ' . $cat_field . ' = ' . $cat_id;
}else{
    $db_extra_left_join = '';
    $db_extra_and = '';
}
$db_listing = new db_query('SELECT *
                            FROM '.$bg_table.$db_extra_left_join.'
                            WHERE 1 '.$list->sqlSearch().$db_extra_and.'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);
$center_column .= $list->showHeader($total_row);
$i = 0;
$array_unit = array();
$db_query = new db_query('SELECT * FROM units');
while($row = mysqli_fetch_assoc($db_query->result)){
    $array_unit[$row['uni_id']] = $row['uni_name'];
}
while($row = mysqli_fetch_assoc($db_listing->result)){
    $i++;
    $center_column .= $list->start_tr($i,$row[$id_field],'class="menu-normal record-item" ondblclick="detailRecord()" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
    /* code something */
    $center_column .= '<td class="center">'.format_codenumber($row['men_id'],3).'</td>';
    $center_column .= '<td>'.$row['men_name'].'</td>';
    $center_column .= '<td class="center">' . (isset($array_unit[$row['men_unit_id']]) ? $array_unit[$row['men_unit_id']] : '') . '</td>';
    $center_column .= '<td class="text-right">'.format_number($row['men_price']).' vnđ</td>';
    $center_column .= '<td class="center">'.'</td>';
    $center_column .= $list->end_tr();
}
$center_column .= $list->showFooter();

//Khối bên phải, hiển thị công thức chế biến tương ứng với menu
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
$right_column = '<div class="alert-warning alert text-center">Chọn một thực đơn để xem công thức chế biến</div>';


if($isAjaxRequest){
    $action = getValue('action','str','POST','');
    switch($action){
        case 'listRecord' :
        echo $center_column;
        break;
    }
    if($import_menu){
        if($import_menu['type'] === 'application/vnd.ms-excel'){
            echo $center_column;
        }
    }
    die;
}

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('center_control', $center_control);
$rainTpl->assign('right_control', $right_control);
$rainTpl->assign('footer_control', $footer_control);

$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('left_column_title',$left_column_title);

$rainTpl->assign('center_column_title',$center_column_title);
$rainTpl->assign('center_column',$center_column);

$rainTpl->assign('right_column',$right_column);
$rainTpl->assign('right_column_title',$right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_3column');