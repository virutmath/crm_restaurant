<?
require_once 'inc_security.php';
if(!isset($_GET['data_record_id']) && !isset($_GET['position'])){
    die;
}
$data_record_id         = getValue('data_record_id','int','GET',0);
$position               = getValue('position','str','GET','');
function percens($struc,$per){
    $return = $struc * $per / 100;
    return $return;
}
$title_detail_left      = '';
$title_detail_right     = 'Thông tin hóa đơn';
$left_column            = '';
$right_content          = '';
$totalAll               = 0;
$con_lai                = 0;
$phuphi                 = '';
$cus_id                 = 0;
$ngay_hen_tra           = '';

if(trim($position)      == 'left' || trim($position) == 'bill_in'){
    $title_detail_left  = 'Danh sách thực đơn nhập vào hóa đơn';
    
    $list = new dataGrid('bid_menu_id',10);
    $list->add('men_id', 'Mã');
    $list->add('men_name', 'Tên thực đơn');
    $list->add('uni_name', 'ĐVT');
    $list->add('bid_menu_number', 'SL');
    $list->add('bid_menu_price', 'Đơn giá');
    $list->add('bid_menu_discount', 'Giảm');
    $list->add('', 'Thành tiền');
    // xem chi tiet hoa don tu trang index
    if(trim($position)      == 'left'){
        // tổng số thực đơn trong hóa đơn
        $db_count = new db_count('SELECT count(*) as count
                                    FROM bill_in_detail
                                    WHERE 1 '.$list->sqlSearch().' AND bid_bill_id = ' . $data_record_id . '
                                    ');
        $total = $db_count->total;unset($db_count);
        // lọc danh sách thực đơn của hóa đơn
        $menu_listing   = new db_query('SELECT * FROM bill_in_detail 
                                        INNER JOIN menus 
                                        ON bid_menu_id = men_id
                                        WHERE bid_bill_id = ' . $data_record_id . ' '
                                        . $list->limit($total));
    }
    // xem chi tiet hoa don tu trong thung rac
    if(trim($position)      == 'bill_in'){
        // tổng số thực đơn trong hóa đơn
        $db_count = new db_count('SELECT count(*) as count
                                    FROM trash
                                    WHERE 1 '.$list->sqlSearch().' 
                                    AND tra_record_id = ' . $data_record_id . '
                                    AND tra_table = \'bill_in_detail\'
                                    ');
        $total = $db_count->total;unset($db_count);
        // lọc danh sách thực đơn của hóa đơn
        $menu_listing   = new db_query('SELECT * FROM trash 
                                        WHERE tra_record_id = ' . $data_record_id . '
                                        AND tra_table = \'bill_in_detail\''
                                        . $list->limit($total));
    }
    $total          = mysqli_num_rows($menu_listing->result);
    $left_column        .= $list->showHeader($total);
    $i              = 0;
    $men_id         = '';
    $men_name       = '';
    while ($row     = mysqli_fetch_assoc($menu_listing->result)){
        //
        if(trim($position)      == 'bill_in'){
            $row    = json_decode(base64_decode($row['tra_data']),1);
        }
        // tong so tien bang gia thuc don nhan voi so luong - cho giam gia
        $total      = $row['bid_menu_price'] * $row['bid_menu_number'] - $row['bid_menu_discount'];
        $i++;
        // đơn vị tính
        if(trim($position)      == 'left') {
            $men_id     = $row['men_id']; // id thuc don
            $men_name   = $row['men_name']; // ten thuc don
            // lay ra don vi tinh
            $unit       = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row['men_unit_id']);
        }
        //xem chi tiet từ trong thùng rác
        if(trim($position)      == 'bill_in'){
            // lay ra don vi tinh // id thuc don // ten thuc don
            $unit = new db_query('SELECT * FROM menus 
                                    LEFT JOIN units ON uni_id = men_unit_id
                                    WHERE men_id = ' . $row['bid_menu_id']);
        }
        $row_       = mysqli_fetch_assoc($unit->result);unset($unit);
        if(trim($position)      == 'bill_in'){
            $men_id = $row_['men_id'];
            $men_name   = $row_['men_name'];
        }
        //
        $left_column    .= $list->start_tr($i,$row['bid_menu_id'],'class="menu-normal record-item" onclick="active_record('.$row['bid_menu_id'].')" data-record_id="'.$row['bid_menu_id'].'"');
        $left_column    .= '<td class="center">'.format_codenumber($men_id,6,'').'</td>';
        $left_column    .= '<td>'.$men_name.'</td>';
        $left_column    .= '<td class="center">'.$row_['uni_name'].'</td>';
        $left_column    .= '<td class="center">'.$row['bid_menu_number'].'</td>';
        $left_column    .= '<td class="text-right">'.number_format($row['bid_menu_price']).'</td>';
        $left_column    .= '<td class="center">'.number_format($row['bid_menu_discount']).'</td>';
        $left_column    .= '<td class="text-right">'.number_format($total).'</td>';
        $left_column    .= $list->end_tr();
        $totalAll       += $total;
    }
    $left_column             .= $list->showFooter();
    unset($menu_listing);
    // lấy ra thời gian vào và ra của khách hàng
    if(trim($position) == 'left'){
        $db_bill_in     = new db_query('SELECT * FROM bill_in
                                        WHERE bii_id = ' . $data_record_id);
    }
    if(trim($position) == 'bill_in'){
        // lấy ra thời gian vào và ra của khách hàng
        $db_bill_in     = new db_query('SELECT * FROM trash
                                        WHERE tra_record_id = ' . $data_record_id . ' AND tra_table = \''.$position.'\'');
    }
    if($data_bill_in   = mysqli_fetch_assoc($db_bill_in->result)){
        $nhanvien       = 'Không chọn nhân viên';
        if(trim($position) == 'bill_in'){
            $data_bill_in   = json_decode(base64_decode($data_bill_in['tra_data']),1);
        }
        $gio_vao        = date('d/m/Y h:m', $data_bill_in['bii_start_time']);
        $gio_ra         = date('d/m/Y h:m', $data_bill_in['bii_end_time']);
        // lấy ra số bàn và vị trí bàn khách hàng
        $db_des_sec     = new db_query('SELECT * FROM desks 
                                        INNER JOIN sections ON des_sec_id = sec_id
                                        WHERE des_id = ' . $data_bill_in['bii_desk_id']);
        if($data_des_sec   = mysqli_fetch_assoc($db_des_sec->result)){
            $khu_vuc        = $data_des_sec['sec_name'];
            $ban            = $data_des_sec['des_name'];
        };unset($db_des_sec);
        // lấy ra địa điểm
        $db_age_sed     = new db_query('SELECT * FROM service_desks 
                                        INNER JOIN agencies ON sed_agency_id = age_id
                                        WHERE sed_id = ' . $data_bill_in['bii_service_desk_id']);
        if($data_age_sed   = mysqli_fetch_assoc($db_age_sed->result)){
            $dia_diem       = $data_age_sed['age_name'].' - '.$data_age_sed['sed_name'];
        };unset($db_age_sed);
        // lấy ra kho
        $db_store       = new db_query('SELECT * FROM categories_multi
                                        WHERE cat_id = ' . $data_bill_in['bii_store_id']);
        if($data_store     = mysqli_fetch_assoc($db_store->result)){
            $store          = $data_store['cat_name'];
        };unset($db_store);
        // lấy ra tên nhân viên
        $db_user        = new db_query('SELECT * FROM users
                                        WHERE use_id = ' . $data_bill_in['bii_staff_id']);
        if($data_user      = mysqli_fetch_assoc($db_user->result)){
            $nhanvien       = $data_user['use_name'];
        };unset($db_user);
        // lấy ra tên thu ngân
        $db_adm         = new db_query('SELECT * FROM admin_users
                                        WHERE adm_id = ' . $data_bill_in['bii_admin_id']);
        if($data_adm    = mysqli_fetch_assoc($db_adm->result)){
            $thu_ngan   = $data_adm['adm_name'];
        };unset($db_adm);  
        $customer_id    = $data_bill_in['bii_customer_id'];
        $nhanvien_id    = $data_bill_in['bii_staff_id'];
        $phu_phi        = $data_bill_in['bii_extra_fee'];
        $giam_gia       = $data_bill_in['bii_discount'];
        $vat_           = $data_bill_in['bii_vat'];
        $tong_thanhtoan = $data_bill_in['bii_round_money'] ;
        
        $pp             = percens($data_bill_in['bii_true_money'],$data_bill_in['bii_extra_fee']);
        $sale           = percens($data_bill_in['bii_true_money'],$data_bill_in['bii_discount']);
        $vat            = percens($data_bill_in['bii_true_money'],$data_bill_in['bii_vat']);
        $totalAll_      = $totalAll - $pp - $sale - $vat;
        $da_tt          = $tong_thanhtoan - $data_bill_in['bii_money_debit'];
        if($data_bill_in['bii_status']   == BILL_STATUS_SUCCESS){
            $status     = 'Đã trả đủ';
        }else{
            $status     = 'Ghi nợ';   
            $con_lai    = $data_bill_in['bii_money_debit'];
            $ngay_hen_tra = date('d-m-Y',$data_bill_in['bii_date_debit']);
        }
        if($data_bill_in['bii_type']   == PAY_TYPE_CASH){
            $type       = 'Tiền mặt';
        }else{
            $type       = 'Thẻ';
        }

        if($data_bill_in['bii_customer_id'] == 0){
            $customer   = 'Khách lẻ';
        }else{
            $db_cus     = new db_query('SELECT cus_id,cus_name FROM customers WHERE cus_id = ' . $data_bill_in['bii_customer_id']);
            $row_       = mysqli_fetch_assoc($db_cus->result); unset($db_cus);
            $customer   = $row_['cus_name'];
            $cus_id     = $row_['cus_id'];
        }
    };unset($db_bill_in);
    // right;
    $right_content  .= '<table cellpadding="0" cellspacing="0" border="0" class="bill-inf">';
    $right_content  .= '<tr><td>Giờ vào - ra:</td><td>'.$gio_vao.' - '.$gio_ra.'</td></tr>';
    $right_content  .= '<tr><td>Khu vực - Bàn:</td><td>'.$khu_vuc.' - '.$ban.'</td></tr>';
    $right_content  .= '<tr><td>Địa điểm:</td><td>'.$dia_diem.'</td></tr>';
    $right_content  .= '<tr><td>Xuất từ kho:</td><td>'.$store.'</td></tr>';
    $right_content  .= '<tr><td>Khách hàng:</td><td>'.$customer.' <i class="fa fa-picture-o detail_customer" data-cus_id="'.$cus_id.'"></i></td></tr>';
    $right_content  .= '<tr><td>Nhân viên:</td><td>'.$nhanvien.' <i class="fa fa-picture-o detail_user" data-use_id="'.$nhanvien_id.'"></i></td></tr>';
    $right_content  .= '<tr><td>Thu ngân:</td><td>'.$thu_ngan.'</td></tr>';
    $right_content  .= '<tr><td>Trạng thái:</td><td>'.$status.'</td></tr>';
    $right_content  .= '<tr><td>Thanh toán bằng:</td><td>'.$type.'</td></tr>';
    $right_content  .= '</table>';
    
    $right_content  .= '<div class="cn-kh" style="opacity: .4;">';
    $right_content  .= '<span class="cn-n-kh">Công nợ khách hàng</span>'; 
    $right_content  .= '<form action="" method=""><table cellpadding="0" cellspacing="0" border="0" class="tb-cnkh">';
    $right_content  .= '<tr><td>Đã thanh toán:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$da_tt.'"/></td></tr>';
    $right_content  .= '<tr><td>Còn lại:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$con_lai.'"/></td></tr>';
    $right_content  .= '<tr><td>Ngày hẹn:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="0"/></td></tr>';
    $right_content  .= '</table></form></div>';
    $right_content  .= '<input name="" class="cn-gh-ch" value=""/>';
    $right_content  .= '<div class="print-close">';
    $right_content  .= '<span class="bill-print"><i class="fa fa-print"></i> In hóa đơn</span>';
    $right_content  .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span></div>';
    /// phu phi
    $phuphi         .= '<div class="pp-vat"><table cellpadding="0" cellspacing="0" border="0" class="vat">';
    $phuphi         .= 
    '<tr>
        <td>Phụ phí:</td>
        <td><b>'.$phu_phi.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($pp).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td>Giảm giá:</td>
        <td><b>'.$giam_gia.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($sale).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td>VAT:</td>
        <td><b>'.$vat_.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($vat).'</b></td>
    </tr></table>';
    $phuphi         .= '<table cellpadding="0" cellspacing="0" border="0" class="total-tr">'; 
    $phuphi         .= 
    '<tr>
        <td>Tổng tiền:</td>
        <td class="text-right"><b>'.number_format($totalAll_).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td class="border-bot">Thanh toán:</td>
        <td class="text-right border-bot total-bill-money"><b>'.number_format($tong_thanhtoan).' '.DEFAULT_MONEY_UNIT.'</b></td>
    </tr></table></div>';

}//

if(trim($position)      == 'right' || trim($position) == 'bill_out'){
    $title_detail_left  = 'Danh sách mặt hàng nhập vào hóa đơn';
    $list               = new dataGrid('bid_pro_id',10);
    $list->add('bid_pro_id', 'Mã hàng');
    $list->add('pro_name', 'Tên sản phẩm');
    $list->add('uni_name', 'ĐVT');
    $list->add('bid_pro_number', 'SL');
    $list->add('bid_pro_price', 'Đơn giá');
    $list->add('', 'Thành tiền');
    // xem chi tiet hoa don nhap tu trang index
    if(trim($position)      == 'right'){
        $db_count           = new db_count('SELECT count(*) as count
                                    FROM bill_out_detail
                                    WHERE 1 '.$list->sqlSearch().' AND bid_bill_id = ' . $data_record_id . '
                                    ');
        $total              = $db_count->total;unset($db_count);
        // danh sach san pham nhap trong hoa don
        $menu_listing       = new db_query('SELECT * FROM bill_out_detail 
                                            LEFT JOIN products ON bid_pro_id = pro_id
                                            LEFT JOIN units ON pro_unit_id = uni_id
                                            WHERE bid_bill_id = ' . $data_record_id .' '. $list->limit($total));
    }                                   
    //  xem chi tiet hoa don nhâp từ trong thung rac
    if(trim($position) == 'bill_out'){
        $db_count_bill_in_trash = new db_count('SELECT count(*) as count
                                                FROM trash
                                                WHERE 1 '.$list->sqlSearch().' 
                                                AND tra_record_id = ' . $data_record_id . '
                                                AND tra_table = \'bill_out_detail\'');
        $total = $db_count_bill_in_trash->total;unset($db_count_bill_in_trash);
        // lọc danh sách thực đơn của hóa đơn
        $menu_listing   = new db_query('SELECT * FROM trash 
                                        WHERE tra_record_id = ' . $data_record_id . '
                                        AND tra_table = \'bill_out_detail\''
                                        . $list->limit($total));
    }
    $total              = mysqli_num_rows($menu_listing->result);
    $left_column        .= $list->showHeader($total);                                
    $i                  = 0;
    while ($row         = mysqli_fetch_assoc($menu_listing->result)){
        if(trim($position) == 'right'){
            $pro_name   = $row['pro_name'];
            $uni_name   = $row['uni_name'];
        }
        if(trim($position) == 'bill_out'){
            $row        = json_decode(base64_decode($row['tra_data']),1);
            $db_pro_unit    = new db_query('SELECT pro_name, uni_name FROM products 
                                            LEFT JOIN units ON pro_unit_id = uni_id
                                            WHERE pro_id = ' . $row['bid_pro_id']);
            $data_pro_unit  = mysqli_fetch_assoc($db_pro_unit->result);unset($db_pro_unit);
            $pro_name   = $data_pro_unit['pro_name'];
            $uni_name   = $data_pro_unit['uni_name'];
        }
        $total          = $row['bid_pro_price'] * $row['bid_pro_number'];
        $left_column    .= $list->start_tr($i,$row['bid_pro_id'],'class="menu-normal record-item" onclick="active_record('.$row['bid_pro_id'].',\'left\')" data-record_id="'.$row['bid_pro_id'].'"');
        $left_column    .= '<td class="center">'.format_codenumber($row['bid_pro_id'],6,'').'</td>';
        $left_column    .= '<td>'.$pro_name.'</td>';
        $left_column    .= '<td class="center">'.$uni_name.'</td>';
        $left_column    .= '<td class="center">'.$row['bid_pro_number'].'</td>';
        $left_column    .= '<td class="text-right">'.number_format($row['bid_pro_price']).'</td>';
        $left_column    .= '<td class="text-right">'.number_format($total).'</td>';
        $left_column    .= $list->end_tr();
        $totalAll += $total;
    }unset($menu_listing);
    $left_column        .= $list->showFooter();
    // lay ra thong tin cua nha cug cap, trang thai thanh toan,ngay nhap khi xem chi tiet tu trang index
    if(trim($position) == 'right'){
        $db_date_brand_store_status        = new db_query('SELECT * FROM bill_out
                                                            LEFT JOIN categories_multi ON bio_store_id = cat_id
                                                            LEFT JOIN suppliers ON bio_supplier_id = sup_id
                                                            WHERE bio_id = ' . $data_record_id);
    } 
    // lay ra thong tin cua nha cug cap, trang thai thanh toan,ngay nhap khi xem chi tiet tu trong thung rac
    if(trim($position) == 'bill_out'){
        $db_date_brand_store_status     = new db_query('SELECT * FROM trash
                                                        WHERE tra_record_id = ' . $data_record_id .'
                                                        AND tra_table = \''.$position.'\'');
    }
    $data_sup           = mysqli_fetch_assoc($db_date_brand_store_status->result);unset($db_date_brand_store_status);
    if(trim($position) == 'right'){
        $store              = $data_sup['cat_name'];
        $nhacc              = $data_sup['sup_name'];
        if($data_sup['sup_image']    == ''){
            $avata              = '<span class="ava-cus"><i class="fa fa-camera-retro fa-2x"></i></span><p>Không có hình</p>';
        }else{
            $avata              = '<img src="'.get_picture_path($data_sup['sup_image']).'"/>';
        }
    }
    if(trim($position) == 'bill_out'){
        $data_sup       = json_decode(base64_decode($data_sup['tra_data']),1);
        // lay ra ten kho
        $db_store       = new db_query('SELECT cat_name FROM categories_multi
                                        WHERE cat_id = ' . $data_sup['bio_store_id']);
        $data_store     = mysqli_fetch_assoc($db_store->result);unset($db_store);
        $store              = $data_store['cat_name'];
        // lay ra ten nha cung cap
        $db_nhacc       = new db_query('SELECT sup_image, sup_name FROM suppliers
                                        WHERE sup_id = ' . $data_sup['bio_supplier_id']);
        $data_nhacc     = mysqli_fetch_assoc($db_nhacc->result);unset($db_nhacc);
        $nhacc          = $data_nhacc['sup_name'];
        if($data_nhacc['sup_image']    == ''){
            $avata              = '<span class="ava-cus"><i class="fa fa-camera-retro fa-2x"></i></span><p>Không có hình</p>';
        }else{
            $avata              = '<img src="'.get_picture_path($data_nhacc['sup_image']).'"/>';
        }
    }
    $ngay_nhap          = date('d-m-Y',$data_sup['bio_start_time']);
    
    $da_thanhtoan       = $data_sup['bio_total_money'] - $data_sup['bio_money_debit'];
    
    if($data_sup['bio_status']   == 0){
        $thanhtoan          = 'Còn nợ lại';
        $con_lai            = $data_sup['bio_money_debit'];
        $ngay_hen_tra       = date("d-m-Y",$data_sup['bio_date_debit']);
    }else{
        $thanhtoan          = 'Thanh toán đủ';
    }
    
    ///// HTML
    $right_content  .= '<div class="box-content-inf-bill">';
    $right_content  .= '<div class="box-content-inf-bill-left"><form method="" action="">';
    $right_content  .= '<p>Ngày nhập:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$ngay_nhap.'"/>';
    $right_content  .= '<p>Nhập vào kho:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$store.'"/>';
    $right_content  .= '<p>Tổng thanh toán:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($totalAll).'"/>';
    $right_content  .= '<p>Trạng thái thanh toán:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$thanhtoan.'"/>';
    $right_content  .= '</form></div>';
    $right_content  .= '<div class="box-content-inf-bill-right">';
    $right_content  .= '<p>Nhà cung cấp</p>';
    $right_content  .= '<div class="box-ava-cus">'.$avata.'</div>';                             
    $right_content  .= '<p class="brand-name">'.$nhacc.'</p></div>';
    $right_content  .= '<div class="clear"></div></div>';
    
    $right_content  .= '<div class="cn-kh" style="opacity: .4;">';
    $right_content  .= '<span class="cn-n-kh">Công nợ nhà cung cấp</span>'; 
    $right_content  .= '<form action="" method=""><table cellpadding="0" cellspacing="0" border="0" class="tb-cnkh">';
    $right_content  .= '<tr><td>Đã thanh toán:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($da_thanhtoan).'"/></td></tr>';
    $right_content  .= '<tr><td>Còn lại:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($con_lai).'"/></td></tr>';
    $right_content  .= '<tr><td>Ngày hẹn:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$ngay_hen_tra.'"/></td></tr>';
    $right_content  .= '</table></form></div>';
    $right_content  .= '<input name="" class="cn-gh-ch" value=""/>';
    $right_content  .= '<div class="print-close">';
    $right_content  .= '<span class="bill-print"><i class="fa fa-print"></i> In hóa đơn</span>';
    $right_content  .= '<span class="bill-close"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span></div>';
}
$rainTpl = new RainTPL();
add_more_css('bill_detail.css',$load_header);
$rainTpl->assign('load_header',$load_header);

$rainTpl->assign('title_detail_left',$title_detail_left);
$rainTpl->assign('title_detail_right',$title_detail_right);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_content',$right_content);
$rainTpl->assign('phuphi',$phuphi);
$custom_script = file_get_contents('script_bill_detail.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('bill_detail');