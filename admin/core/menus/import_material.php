<?
require_once 'inc_security.php';
$import_menu        = isset($_FILES['import_menu']) ? $_FILES['import_menu'] : '';
if($import_menu)
{
    $filename       = $import_menu['tmp_name'];
    $arrMaterial     = analyzeExcel_Material($filename);
    foreach ( $arrMaterial as $value )
    {
        $ten_nguyenlieu   = $value['ten_nguyenlieu'];
        $unit_id = 0; 
        $donvi_tinh = $value['donvi_tinh'];
        $menu1      = $value['menu_cap_1'];
        if( $menu1 == '' ) continue;
        $uni_note = '';
        $array_replace = array('(g)','(gói)','(ml)', '(hộp)', '(miếng)', '(kg)');
        $ten_nguyenlieu = str_replace($array_replace,'',$ten_nguyenlieu);
        // kiem tra xem don vi tinh da ton tai trong bang units chua
        $db_unit    = new db_query("SELECT uni_id FROM units 
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
        }unset($db_unit);
        //
        // kiêm tra xem tòn tai menu cap 1 chua
        $db_categories_1 = new db_query("SELECT cat_id FROM categories_multi
                                        WHERE cat_name = '" .trim($menu1) . "' 
                                        AND cat_type = 'products'");
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
                                                                'products',
                                                                '".$cat_desc."',
                                                                '".$cat_picture."',
                                                                ".$cat_parent_id.",
                                                                ".$cat_has_child.",
                                                                '".$cat_note."'
                                                                )");
            unset($db_insert_categories);
            $cat_id = $db_categories_id;
        }
        unset($db_categories_1); 
        $pro_id = 0 ; 
        $pro_image = '';
        $pro_note = '';
        $pro_cat_id = 0;
        $pro_unit_id = $unit_id;
        $pro_code = '';
        $pro_instock = 0;
        $pro_status = 0;
        // kiem tra xem nguyen lieu da ton tai chua
        // neu ton tai nguyen lieu và pro_cat_id = $cat_id thì lấy ra pro_id
        // neu ton tai nguyen lieu nhung pro_cat_id khac $cat_id thi update lai pro_cat_id cho nguyen lieu do sau do lay ra pro_id
        // neu nguyen lieu chua ton tai thì insert lam ban ghi moi voi pro_cat_id = $cat_id
        $dbPro  = new db_query("SELECT * FROM products 
                                WHERE pro_name = '" . trim($ten_nguyenlieu) . "'
                                ");
        if( mysqli_num_rows($dbPro->result) >= 1 )
        {
            //lay ra id cua nguyen lieu
            $dataPro = mysqli_fetch_assoc($dbPro->result);
            $pro_id   = $dataPro['pro_id'];
            if ( $dataPro['pro_cat_id'] == 0 )
            {
                $db_update_product = new db_execute("UPDATE products 
                                                    SET pro_cat_id = ".$cat_id.", 
                                                    pro_unit_id = ".$unit_id."
                                                    WHERE pro_id = " . $pro_id . "");  
            }
        }
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
                                                                '" . trim($ten_nguyenlieu) . "',
                                                                '" . $pro_image . "',
                                                                '" . $pro_note . "',
                                                                " . $cat_id . ",
                                                                " . $pro_unit_id . ",
                                                                '" . $pro_code . "',
                                                                " . $pro_instock . ",
                                                                " . $pro_status . "
                                                                )");
            
            unset($db_insert_product);
            $pro_id = $db_product_id;
        }unset($dbPro);
        // soluong nguyuen lieu
        $pro_quantity = 0;
        //lay ra id kho
        $db_store = new db_query("SELECT cat_id FROM categories_multi 
                                  WHERE cat_type = 'stores'");
        while( $data_store = mysqli_fetch_assoc($db_store->result) )
        {
            $db_pro_quantity = new db_query("SELECT * FROM product_quantity
                                             WHERE product_id = " .$pro_id. "
                                             AND store_id = " .$data_store['cat_id']);
            if ( mysqli_num_rows($db_pro_quantity->result) >= 1 )
            {
                $db_update_product = new db_execute("UPDATE product_quantity 
                                                    SET pro_quantity = pro_quantity + ".$pro_quantity."
                                                    WHERE pro_id = " . $pro_id . "
                                                    AND store_id = " .$data_store['cat_id']);  
            }else{
                $db_insert_pro_quantity = new db_execute_return;
                $db_pro_quan = $db_insert_pro_quantity->db_execute("INSERT INTO product_quantity
                                                                    (
                                                                    product_id, 
                                                                    store_id,
                                                                    pro_quantity
                                                                    ) VALUES (
                                                                    " . $pro_id . ",
                                                                    " . $data_store['cat_id'] . ",
                                                                    " . $pro_quantity . "
                                                                    )");
                unset($db_insert_pro_quantity);
            }unset($db_pro_quantity);
        }unset($db_store);
        
    }// foreach
}
?>
<h3>Import danh sách nguyên liệu</h3>
<form action="" method="post" enctype="multipart/form-data" id="update-menu" name="update_menu">
<input id="file-menu" type="file" name="import_menu" class="file_menu" onchange="updateMenu()"/>
<button type="submit" id="submit_form"> abc </button>
</form>