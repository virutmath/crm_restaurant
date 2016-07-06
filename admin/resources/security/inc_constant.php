<?
define('DEVELOPER_ENVIRONMENT',true);
//hiển thị quỹ tiền theo từng chi nhánh
define('SHOW_FINANCIAL_PRIVATE',true);
define('WEBSITE_NAME','CRM Restaurant');
define('ACTION_LOG_DELETE','delete');
define('ACTION_LOG_TRASH','trash');
define('ACTION_LOG_RECOVERY','recovery');
define('ACTION_LOG_EDIT','edit');
define('ACTION_LOG_ADD','add');
define('ACTION_LOG_LOGIN','login');
define('ACTION_LOG_PRINT_ORDER','print');
define('PAY_TYPE_CASH',0);
define('PAY_TYPE_CARD',1);
define('DEFAULT_MONEY_UNIT', 'VNĐ');
define('USER_TYPE_STAFF',1);
define('USER_TYPE_CUSTOMER',2);
define('USER_TYPE_SUPPLIER',3);
//định nghĩa tiền tố id
define('PREFIX_BILL_CODE','HĐ');
define('PREFIX_CUSTOMER_CODE','76');
define('PREFIX_STAFF_CODE','26');
define('PREFIX_PRODUCT_CODE','68');
//định nghĩa trạng thái thanh toán hóa đơn
define('BILL_STATUS_SUCCESS',1);
define('BILL_STATUS_DEBIT',0);
//Category ID cho quỹ tiền
//Bán hàng
define('FINANCIAL_CAT_BAN_HANG',30);
//nhap hang
define('FINANCIAL_CAT_NHAP_HANG',31);
// cong no ban hang
define('FINANCIAL_CAT_CONG_NO_BAN_HANG',33);
// cong no nha cung cap
define('FINANCIAL_CAT_CONG_NO_NHAP_HANG',32);
// kieu thanh toan trong bang khuyen mai
define('PROMOTION_TYPE_PERCENT',1);
define('PROMOTION_TYPE_MONEY',2);
define('PROMOTION_ALL_AGENCY',0);
// menus cat type
define('MENU_CAT_TYPE','menus');
// store cat type
define('STORE_CAT_TYPE','stores');
//Cấu hình hiển thị
define('DISPLAY_LISTING_MENU_BY_CATEGORY',true);
/**
 * Document - Note
 *
 * @Phần cài đặt : thực đơn mở bàn được lưu trong field configurations.con_start_menu
 * Định dạng lưu là base64 json_encode của mảng có dạng array('men_id'=>số_lượng)
 * @Phần thực đơn
 *      Khi xóa 1 thực đơn, ta không xóa công thức thực đơn để tiện cho việc phục hồi dữ liệu, mặc dù có thể gây dư thừa dữ liệu
 *          tuy nhiên số lượng không đáng kể. Bảng chi tiết thực đơn (công thức thực đơn) là 1 bảng phụ, không cần thiết phải xóa
 *      Tương tự với các module khác có bảng phụ, không cần phải xóa chi tiết
 * Vấn đề logic khi bán hàng
 * @Bán hàng : trừ số lượng nguyên liệu trong kho hàng (pro_quantity)
 *            Số lượng tồn kho của thực đơn chỉ để hiển thị khi cần kiểm tra - không
 *               dùng số lượng này để check tồn kho khi thanh toán hóa đơn
 *            Khi thanh toán hóa đơn bán hàng xong cần log lại việc thanh toán
 * @Cấu hình cài đặt : configurations
 *      Các cấu hình cài đặt này sẽ đi theo từng user
 *      Với các user cấp quản lý : được tùy chỉnh cấu hình hệ thống - trong lần đăng nhập sẽ được yêu cầu cài đặt hệ thống
 *      (file user_config.php)
 *      Với các user sử dụng (nhân viên bán hàng, phục vụ bàn, nhân viên thu ngân...) được user quản lý tạo ra, hệ thống sẽ sử dụng
 *      cấu hình của user quản lý này để áp dụng cho user vừa được tạo
 *      Việc này đảm bảo cho tính thống nhất. Ví dụ khi tài khoản banhang_cn1 (bán hàng chi nhánh 1) đăng nhập
 *      nó sẽ sử dụng cấu hình hệ thống của tài khoản quản lý tạo ra tài khoản bán hàng này ví dụ quanly_cn1
 * @Vấn đề logic về chi nhánh
 *      Các thành phần cần phân biệt theo chi nhánh bao gồm : khu vực (sections), bàn ăn (desks), quầy phục vụ (service_desks)
 *      Các thành phần này khi xóa cần yêu cầu thêm option_filter là số chi nhánh
 */