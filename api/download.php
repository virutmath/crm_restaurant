<?
require_once 'config.php';


class Download
{
    var $download_result = '';
    var $agency_id;
    var $username;
    var $password;

    function __construct()
    {
        $this->username = getValue('username', 'str', 'POST', '');
        $this->password = getValue('password', 'str', 'POST', '');
        if (!$this->username || !$this->password) {
            die('Username and password are required');
        }
        //check xem username password có đúng là user admin gốc không
        $user_id = checkLogin($this->username, $this->password);
        if ($user_id != 1) {
            die('Username and password aren\'t valid');
        }
        $this->agency_id = getValue('agency', 'int', 'POST', 0);
        if (!$this->agency_id) {
            die('Agency isn\'t provided');
        }
    }

    function countAll()
    {
        $array_return = array();
        $db_count = new db_count('SELECT count(*) as count FROM menus');
        $array_return['menu'] = $db_count->total;
        $db_count = new db_count('SELECT count(*) as count
                                  FROM desks
                                  LEFT JOIN sections ON sec_id = des_sec_id
                                  LEFT JOIN service_desks ON sed_id = sec_service_desk
                                  WHERE sed_agency_id = ' . $this->agency_id);
        $array_return['desk'] = $db_count->total;
        //đếm nguyên liệu
        $db_count = new db_count('SELECT count(*) as count
                                  FROM products');
        $array_return['product'] = $db_count->total;
        //đếm hóa đơn bán hàng
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_in');
        $array_return['bill_in'] = $db_count->total;
        //đếm hóa đơn nhập hàng
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_out');
        $array_return['bill_out'] = $db_count->total;
        //đếm phiếu thu chi
        $db_count = new db_count('SELECT count(*) as count
                                  FROM financial');
        $array_return['financial'] = $db_count->total;
        unset($db_count);
        //các module không cần đếm thì trả về 1 lần load
        $array_return['commonConfig'] = 1;
        $array_return['current_desk'] = 1;
        $array_return['staff'] = 1;
        $array_return['promotion'] = 1;
        $array_return['promotion'] = 1;
        $array_return['supplier'] = 1;
        $array_return['customer'] = 1;
        $this->add($array_return);
    }

    function getListMenu()
    {
        $array_return = array('menu' => array(), 'menu_products' => array());
        $db_menu = new db_query('SELECT * FROM menus');
        while ($row = mysqli_fetch_assoc($db_menu->result)) {
            $row['men_image_path'] = get_picture_path($row['men_image']);
            //lấy ra số lượng menu_products
            $db_menu_product = new db_query('SELECT * FROM menu_products WHERE mep_menu_id = ' . $row['men_id']);
            $row['menu_products'] = $db_menu_product->resultArray();
            $array_return['menu'][] = $row;
        }
        $this->add($array_return);
    }

    function getListDesk()
    {
        $array_return = array('desk' => array(), 'section' => array(), 'service_desk' => array());
        //lấy ra danh sách service desk
        $db_svd = new db_query('SELECT *
                                FROM service_desks
                                WHERE sed_agency_id = ' . $this->agency_id . '');
        $array_return['service_desk'] = $db_svd->resultArray();
        unset($db_svd);
        $list_service_desk = array();
        foreach ($array_return['service_desk'] as $svd) {
            $list_service_desk[] = $svd['sed_id'];
        }
        $list_service_desk = implode(',', $list_service_desk);
        if (!$list_service_desk) {
            echo 'Service desk error';
            die();
        }
        //lấy ra danh sách khu vực nằm ở chi nhánh này
        $db_section = new db_query('SELECT *
                                    FROM sections
                                    WHERE sec_service_desk IN (' . $list_service_desk . ')');
        $array_return['section'] = $db_section->resultArray();
        unset($db_section);
        //lấy ra danh sách bàn theo các khu vực có trong array
        $list_section = array();

        foreach ($array_return['section'] as $sect) {
            $list_section[] = $sect['sec_id'];
        }
        $list_section = implode(',', $list_section);
        if ($list_section) {
            $db_desk = new db_query('SELECT *
                                     FROM desks
                                     WHERE des_sec_id IN (' . $list_section . ')');
            $array_return['desk'] = $db_desk->resultArray();
            unset($db_desk);
        }

        $this->add($array_return);
    }

    function getProduct()
    {
        $array_return = array('products' => array(), 'product_quantity' => array());
        $record = getValue('record', 'int', 'POST', 0);
        $db = new db_query('SELECT * FROM products
                            ORDER BY pro_id ASC
                            LIMIT ' . $record . ',1');
        $array_return['products'] = mysqli_fetch_assoc($db->result);
        unset($db);
        $image_path = get_picture_path($array_return['products']['pro_image']);
        $array_return['products']['image_path'] = $image_path;
        $db_quantity = new db_query('SELECT * FROM product_quantity WHERE product_id = ' . $array_return['products']['pro_id']);
        $array_return['product_quantity'] = $db_quantity->resultArray();
        unset($db_quantity);
        $this->add($array_return);
    }

    function getPromotion()
    {
        $array_return = array('promotions' => array(), 'promotions_menu' => array());
        $db = new db_query('SELECT *
                            FROM promotions
                            WHERE pms_agency_id = ' . $this->agency_id . ' OR pms_agency_id = ' . PROMOTION_ALL_AGENCY);
        $array_return['promotions'] = $db->resultArray();
        unset($db);
        $list_promotion = '';
        foreach ($array_return['promotions'] as $promotion) {
            $list_promotion .= $promotion['pms_id'] . ',';
        }
        $list_promotion = rtrim($list_promotion, ',');
        if ($list_promotion) {
            $db_promo_menu = new db_query('SELECT *
                                       FROM promotions_menu
                                       WHERE pms_id IN (' . $list_promotion . ')');
            $array_return['promotions_menu'] = $db_promo_menu->resultArray();
            unset($db_promo_menu);
        }

        $this->add($array_return);
    }

    function getBillIn()
    {
        $array_return = array('bill_in' => array(), 'bill_in_detail' => array());
        $record = getValue('record', 'int', 'POST', 0);
        $db = new db_query('SELECT *
                            FROM bill_in
                            ORDER BY bii_id ASC
                            LIMIT ' . $record . ',1');
        $array_return['bill_in'] = mysqli_fetch_assoc($db->result);
        unset($db);
        if ($array_return['bill_in']) {
            $db_bill_detail = new db_query('SELECT *
                                            FROM bill_in_detail
                                            WHERE bid_bill_id = ' . $array_return['bill_in']['bii_id']);
            $array_return['bill_in_detail'] = $db_bill_detail->resultArray();
            unset($db_bill_detail);
        }

        $this->add($array_return);
    }

    function getBillOut()
    {
        $array_return = array('bill_out' => array(), 'bill_out_detail' => array());
        $record = getValue('record', 'int', 'POST', 0);
        $db = new db_query('SELECT *
                            FROM bill_out
                            ORDER BY bio_id ASC
                            LIMIT ' . $record . ',1');
        $array_return['bill_out'] = mysqli_fetch_assoc($db->result);
        unset($db);
        if ($array_return['bill_out']) {
            $db_bill_detail = new db_query('SELECT *
                                            FROM bill_out_detail
                                            WHERE bid_bill_id = ' . $array_return['bill_out']['bio_id']);
            $array_return['bill_out_detail'] = $db_bill_detail->resultArray();
            unset($db_bill_detail);
        }

        $this->add($array_return);
    }

    function getStaffUser()
    {
        //lấy ra các nhân viên
        //nhân viên được lấy theo chi nhánh cửa hàng
        $array_return = array();
        $db = new db_query('SELECT * FROM users WHERE use_agency_id = ' . $this->agency_id);
        $array_return['users'] = $db->resultArray();
        unset($db);
        $this->add($array_return);
    }

    function getSupplier()
    {
        //lấy ra danh sách nhà cung cấp
        $array_return = array();
        $db = new db_query('SELECT * FROM suppliers');
        while ($row = mysqli_fetch_assoc($db->result)) {
            $row['image_path'] = get_picture_path($row['sup_image']);
            $array_return['suppliers'][] = $row;
        }
        $this->add($array_return);
    }

    function getCustomer() {
        //lấy ra danh sách khách hàng
        $array_return = array();
        $db = new db_query('SELECT * FROM customers');
        while ($row = mysqli_fetch_assoc($db->result)) {
            $row['image_path'] = get_picture_path($row['cus_picture']);
            $array_return['customers'][] = $row;
        }
        $db = new db_query('SELECT * FROM customer_cat');
        $array_return['customer_cat'] = $db->resultArray();
        $this->add($array_return);
    }

    function getFinancial() {
        //lấy ra danh sách thu chi
        $array_return = array();
        $record = getValue('record','int','POST',0);
        $db = new db_query('SELECT * FROM financial ORDER BY fin_id ASC LIMIT '.$record.',1');
        $array_return['financial'] = mysqli_fetch_assoc($db->result);unset($db);
        $this->add($array_return);
    }

    function getCurrentDesk() {
        $array_return = array();
        $db = new db_query('SELECT * FROM current_desk');
        $array_return['current_desk'] = $db->resultArray();unset($db);
        $db = new db_query('SELECT * FROM current_desk_menu');
        $array_return['current_desk_menu'] = $db->resultArray();unset($db);
        $this->add($array_return);
    }

    function getCommonConfig()
    {
        $array_return = array();
        //lấy ra thông tin nhà hàng, thông tin user tương ứng với đại lý
        $age_id = $this->agency_id;
        //configuration
        $db_config = new db_query('SELECT * FROM configurations WHERE con_default_agency = ' . $age_id);
        $configuration = $db_config->resultArray();
        unset($db_config);
        if (!$configuration) {
            die('Configuration doesn\'t exist');
        }
        $array_return['configurations'] = $configuration;
        $list_admin_id = array();
        foreach($configuration as $config) {
            $list_admin_id[] = $config['con_admin_id'];
        }
        $list_admin_id = implode(',',$list_admin_id);
        $db_user = new db_query('SELECT *
                                 FROM admin_users
                                 WHERE adm_id IN (' . $list_admin_id . ')
                                 OR adm_user_config IN (' . $list_admin_id . ')');
        $user = $db_user->resultArray();
        unset($db_user);
        $array_return['admin_users'] = $user;
        //lấy thông tin về phân quyền và nhóm user
        $db_user_group = new db_query('SELECT * FROM admin_users_groups');
        $user_group = $db_user_group->resultArray();
        unset($db_user_group);
        $array_return['admin_users_groups'] = $user_group;
        //lấy ra danh sách các nhóm user được quyền lấy từ bảng phân quyền
        $list_group_accept = array();
        foreach ($array_return['admin_users'] as $a_user) {
            $list_group_accept[] = $a_user['adm_group_id'];
        }
        $list_group_accept = implode(',', $list_group_accept);
        $db_role = new db_query('SELECT * FROM admin_group_role
                                 WHERE group_id IN (' . $list_group_accept . ')');
        $list_role = $db_role->resultArray();
        unset($db_role);
        $array_return['admin_group_role'] = $list_role;
        //lấy ra bảng custom_role
        $db_custom_role = new db_query('SELECT * FROM custom_roles');
        $list_custom_role = $db_custom_role->resultArray();
        unset($db_custom_role);
        $array_return['custom_roles'] = $list_custom_role;
        //lấy thông tin về navigate_admin
        $db_navigate = new db_query('SELECT * FROM navigate_admin');
        $array_return['navigate_admin'] = $db_navigate->resultArray();
        unset($db_navigate);
        //lấy thông tin về module
        $db_module = new db_query('SELECt * FROM modules');
        $array_return['modules'] = $db_module->resultArray();unset($db_module);
        //lấy thông tin về đại lý
        $db_agency = new db_query('SELECT * FROM agencies WHERE age_id = ' . $age_id);
        $agency = $db_agency->resultArray();
        unset($db_agency);
        $array_return['agencies'] = $agency;
        //lấy thông tin về đơn vị tính
        $db_unit = new db_query('SELECT * FROM units');
        $unit = $db_unit->resultArray();
        unset($db_unit);
        $array_return['units'] = $unit;
        //lấy thông tin về categories_multi
        $db_cat = new db_query('SELECT * FROM categories_multi');
        $array_return['categories_multi'] = $db_cat->resultArray();
        unset($db_cat);
        //lay thong tin ve service_desk
        $db_svd = new db_query('SELECT * FROM service_desks');
        $array_return['service_desks'] = $db_svd->resultArray();
        unset($db_svd);
        //lay thong tin ve kiem kho
        $db_inven = new db_query('SELECT * FROM inventory');
        $array_return['inventory'] = $db_inven->resultArray();
        unset($db_inven);
        $db_inven_dt = new db_query('SELECT * FROM inventory_products');
        $array_return['inventory_products'] = $db_inven_dt->resultArray();
        unset($db_inven_dt);
        //lay thong tin ve chuyen kho
        $db_stock = new db_query('SELECT * FROM stock_transfer');
        $array_return['stock_transfer'] = $db_stock->resultArray();
        unset($db_stock);
        $db_stock_product = new db_query('SELECT * FROM stock_transfer_products');
        $array_return['stock_transfer_products'] = $db_stock_product->resultArray();
        unset($db_stock_product);
        //trả kết quả về client
        $this->add($array_return);
    }

    public function execute()
    {
        $function = getValue('action', 'str', 'POST', '');
        if (method_exists($this, $function)) {
            $this->$function();
            ob_clean();
            echo $this->download_result;
        } else {
            ob_clean();
            die('Method download -- ' . $function . ' -- doesn\'t exist!');
        }
    }

    private function add($string = '')
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        $this->download_result .= $string;
        return $this;
    }
}

$download = new Download();
$download->execute();