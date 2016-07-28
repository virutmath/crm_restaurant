/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-07 22:13:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_group_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_group_role`;
CREATE TABLE `admin_group_role` (
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `custom_role_id` varchar(255) DEFAULT NULL,
  `role_add` int(11) DEFAULT '0',
  `role_edit` int(11) DEFAULT '0',
  `role_delete` int(11) DEFAULT '0' COMMENT 'xóa vĩnh viễn',
  `role_trash` int(11) DEFAULT '0' COMMENT 'xóa vào thùng rác',
  `role_recovery` int(11) DEFAULT '0',
  UNIQUE KEY `key` (`group_id`,`module_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_group_role
-- ----------------------------

-- ----------------------------
-- Table structure for admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `alo_id` int(11) NOT NULL AUTO_INCREMENT,
  `alo_admin_id` int(11) DEFAULT NULL,
  `alo_action_type` varchar(255) DEFAULT NULL,
  `alo_action_time` int(11) DEFAULT NULL,
  `alo_message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`alo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `adm_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `adm_loginname` varchar(255) DEFAULT NULL,
  `adm_password` varchar(255) DEFAULT NULL,
  `adm_mail` varchar(255) DEFAULT NULL,
  `adm_name` varchar(255) DEFAULT NULL,
  `adm_phone` varchar(255) DEFAULT NULL,
  `adm_birthday` int(11) DEFAULT NULL,
  `adm_isadmin` tinyint(1) NOT NULL,
  `adm_group_id` int(11) NOT NULL,
  `adm_note` varchar(255) DEFAULT NULL,
  `adm_user_config` int(11) DEFAULT '1',
  PRIMARY KEY (`adm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES ('1', 'admin', '25d55ad283aa400af464c76d713c07ad', '', 'Cửa hàng trưởng', '', null, '1', '1', '', null);

-- ----------------------------
-- Table structure for admin_users_groups
-- ----------------------------
DROP TABLE IF EXISTS `admin_users_groups`;
CREATE TABLE `admin_users_groups` (
  `adu_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `adu_group_name` varchar(255) DEFAULT NULL,
  `adu_group_admin` tinyint(4) DEFAULT '0',
  `adu_group_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`adu_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_users_groups
-- ----------------------------
INSERT INTO `admin_users_groups` VALUES ('1', 'admin', '1', null);

-- ----------------------------
-- Table structure for agencies
-- ----------------------------
DROP TABLE IF EXISTS `agencies`;
CREATE TABLE `agencies` (
  `age_id` int(11) NOT NULL AUTO_INCREMENT,
  `age_name` varchar(255) DEFAULT NULL,
  `age_address` varchar(255) DEFAULT NULL,
  `age_phone` varchar(255) DEFAULT NULL,
  `age_image` varchar(255) DEFAULT NULL,
  `age_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`age_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agencies
-- ----------------------------

-- ----------------------------
-- Table structure for bill_in
-- ----------------------------
DROP TABLE IF EXISTS `bill_in`;
CREATE TABLE `bill_in` (
  `bii_id` int(11) NOT NULL AUTO_INCREMENT,
  `bii_start_time` int(11) NOT NULL,
  `bii_end_time` int(11) NOT NULL,
  `bii_desk_id` int(11) NOT NULL COMMENT 'id khu vực bàn ăn',
  `bii_store_id` int(11) NOT NULL COMMENT 'xuất hàng từ kho nào',
  `bii_customer_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id khách hàng - 0 là khách lẻ',
  `bii_staff_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id nhân viên - 0 là không chọn nhân viên',
  `bii_admin_id` int(11) NOT NULL DEFAULT '0' COMMENT 'người thực hiện thanh toán hóa đơn',
  `bii_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'trạng thái hóa đơn : đã trả đủ hay ghi nợ',
  `bii_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'loại thanh toán tiền mặt hay thẻ',
  `bii_extra_fee` int(11) NOT NULL DEFAULT '0' COMMENT 'phụ phí tính theo %',
  `bii_vat` int(11) NOT NULL DEFAULT '0' COMMENT 'thuế VAT tính theo %',
  `bii_discount` int(11) NOT NULL DEFAULT '0' COMMENT 'giảm giá tính theo %',
  `bii_true_money` int(11) NOT NULL COMMENT 'tiền thực khách fai thanh toán',
  `bii_round_money` int(11) NOT NULL COMMENT 'tiền khách thanh toán sau khi làm tròn',
  `bii_service_desk_id` int(11) NOT NULL DEFAULT '0',
  `bii_money_debit` int(11) DEFAULT '0' COMMENT 'số tiền còn nợ',
  `bii_date_debit` int(11) DEFAULT '0' COMMENT 'ngày hẹn trả nợ',
  PRIMARY KEY (`bii_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_in
-- ----------------------------

-- ----------------------------
-- Table structure for bill_in_detail
-- ----------------------------
DROP TABLE IF EXISTS `bill_in_detail`;
CREATE TABLE `bill_in_detail` (
  `bid_bill_id` int(11) NOT NULL,
  `bid_menu_id` int(11) NOT NULL,
  `bid_menu_number` int(11) NOT NULL DEFAULT '1' COMMENT 'số lượng của thực đơn',
  `bid_menu_price` int(11) NOT NULL DEFAULT '0' COMMENT 'đơn giá của thực đơn',
  `bid_menu_discount` int(11) NOT NULL DEFAULT '0' COMMENT '% giảm giá của thực đơn',
  UNIQUE KEY `bid_bill_id` (`bid_bill_id`,`bid_menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_in_detail
-- ----------------------------

-- ----------------------------
-- Table structure for bill_out
-- ----------------------------
DROP TABLE IF EXISTS `bill_out`;
CREATE TABLE `bill_out` (
  `bio_id` int(11) NOT NULL AUTO_INCREMENT,
  `bio_start_time` int(11) DEFAULT NULL,
  `bio_store_id` int(11) DEFAULT NULL,
  `bio_status` tinyint(4) DEFAULT NULL,
  `bio_total_money` int(11) DEFAULT NULL,
  `bio_supplier_id` int(11) DEFAULT NULL,
  `bio_note` varchar(255) DEFAULT NULL,
  `bio_type` tinyint(4) DEFAULT '0' COMMENT 'loại thanh toán tiền mặt hay thẻ',
  `bio_admin_id` int(11) DEFAULT '0',
  `bio_money_debit` int(11) DEFAULT '0' COMMENT 'số tiền còn nợ',
  `bio_date_debit` int(11) DEFAULT '0' COMMENT 'ngày hẹn trả nợ',
  PRIMARY KEY (`bio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_out
-- ----------------------------

-- ----------------------------
-- Table structure for bill_out_detail
-- ----------------------------
DROP TABLE IF EXISTS `bill_out_detail`;
CREATE TABLE `bill_out_detail` (
  `bid_bill_id` int(11) NOT NULL,
  `bid_pro_id` int(11) NOT NULL,
  `bid_pro_number` float(11,0) NOT NULL DEFAULT '1' COMMENT 'số lượng của thực đơn',
  `bid_pro_price` int(11) NOT NULL DEFAULT '0' COMMENT 'đơn giá của thực đơn',
  UNIQUE KEY `bid_bill_id` (`bid_bill_id`,`bid_pro_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_out_detail
-- ----------------------------

-- ----------------------------
-- Table structure for categories_multi
-- ----------------------------
DROP TABLE IF EXISTS `categories_multi`;
CREATE TABLE `categories_multi` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) DEFAULT NULL,
  `cat_type` varchar(255) DEFAULT NULL,
  `cat_desc` varchar(255) DEFAULT NULL,
  `cat_picture` varchar(255) DEFAULT NULL,
  `cat_parent_id` int(11) DEFAULT '0',
  `cat_has_child` int(11) DEFAULT '0',
  `cat_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories_multi
-- ----------------------------
INSERT INTO `categories_multi` VALUES ('1', 'Bán hàng', 'money_system_in', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('2', 'Nhập hàng', 'money_system_out', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('3', 'Thanh toán công nợ khách hàng', 'money_system_in', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('4', 'Thanh toán công nợ NCC', 'money_system_out', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('5', 'Kho chính', 'stores', null, null, '0', '0', null);

-- ----------------------------
-- Table structure for configurations
-- ----------------------------
DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_admin_id` int(11) NOT NULL,
  `con_restaurant_name` varchar(255) DEFAULT NULL,
  `con_restaurant_address` varchar(255) DEFAULT NULL,
  `con_restaurant_phone` varchar(255) DEFAULT NULL,
  `con_default_agency` int(11) DEFAULT '0',
  `con_default_store` int(11) DEFAULT '0',
  `con_default_svdesk` int(11) DEFAULT '0',
  `con_start_menu` varchar(255) DEFAULT NULL,
  `con_negative_export` tinyint(4) DEFAULT '0' COMMENT 'cho phép xuất âm kho hàng',
  `con_restaurant_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`con_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of configurations
-- ----------------------------

-- ----------------------------
-- Table structure for current_desk
-- ----------------------------
DROP TABLE IF EXISTS `current_desk`;
CREATE TABLE `current_desk` (
  `cud_desk_id` int(11) NOT NULL,
  `cud_start_time` int(11) DEFAULT NULL,
  `cud_note` varchar(255) DEFAULT NULL,
  `cud_customer_id` int(11) DEFAULT NULL,
  `cud_staff_id` int(11) DEFAULT NULL,
  `cud_extra_fee` float DEFAULT '0' COMMENT 'phụ phí tính theo %',
  `cud_customer_discount` float DEFAULT '0' COMMENT '% giảm giá tính theo loại khách hàng',
  `cud_vat` float DEFAULT '0' COMMENT 'thuế VAT theo %',
  `cud_debit` tinyint(4) DEFAULT '0' COMMENT 'ghi nợ hay ko?',
  `cud_pay_type` tinyint(4) DEFAULT '0' COMMENT 'loại thanh toán : tiền mặt hay thẻ',
  PRIMARY KEY (`cud_desk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of current_desk
-- ----------------------------

-- ----------------------------
-- Table structure for current_desk_menu
-- ----------------------------
DROP TABLE IF EXISTS `current_desk_menu`;
CREATE TABLE `current_desk_menu` (
  `cdm_desk_id` int(11) NOT NULL,
  `cdm_menu_id` int(11) DEFAULT NULL,
  `cdm_number` int(11) DEFAULT '0' COMMENT 'số lượng của menu, là dạng interger, khác với số lượng sản phẩm trong thực đơn có thể là float',
  `cdm_price` int(11) DEFAULT '0' COMMENT 'giá bán của menu áp dụng trong bàn này',
  `cdm_price_type` varchar(255) DEFAULT NULL COMMENT 'loại giá áp dụng cho menu : giá chính thức, giá 1 hay giá 2',
  `cdm_menu_discount` float DEFAULT '0' COMMENT 'giảm giá của menu (khi có chương trình km)',
  `cdm_create_time` int(11) DEFAULT '0',
  `cdm_updated_time` int(11) DEFAULT '0',
  `cdm_printed_number` int(11) DEFAULT '0',
  UNIQUE KEY `key` (`cdm_desk_id`,`cdm_menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of current_desk_menu
-- ----------------------------

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `cus_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_name` varchar(255) NOT NULL,
  `cus_address` varchar(255) DEFAULT NULL,
  `cus_phone` varchar(11) DEFAULT NULL,
  `cus_email` varchar(255) DEFAULT NULL,
  `cus_note` text,
  `cus_picture` varchar(255) DEFAULT NULL,
  `cus_cat_id` int(11) DEFAULT NULL,
  `cus_code` varchar(255) DEFAULT NULL,
  `cus_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`cus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customers
-- ----------------------------

-- ----------------------------
-- Table structure for customer_cat
-- ----------------------------
DROP TABLE IF EXISTS `customer_cat`;
CREATE TABLE `customer_cat` (
  `cus_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_cat_name` varchar(255) NOT NULL,
  `cus_cat_discount` int(11) DEFAULT '0' COMMENT 'chiet khau theo nhom khach hang tinh theo %',
  `cus_cat_sales` int(11) DEFAULT '0' COMMENT 'doanh so theo nhom khach hang',
  `cus_cat_picture` varchar(255) DEFAULT NULL,
  `cus_cat_note` text,
  `cus_cat_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`cus_cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customer_cat
-- ----------------------------

-- ----------------------------
-- Table structure for custom_roles
-- ----------------------------
DROP TABLE IF EXISTS `custom_roles`;
CREATE TABLE `custom_roles` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_module_id` int(11) DEFAULT NULL,
  `rol_name` varchar(255) DEFAULT NULL,
  `rol_unique_tag` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of custom_roles
-- ----------------------------
INSERT INTO `custom_roles` VALUES ('1', '4', 'Thanh toán công nợ', 'THANH_TOAN_CONG_NO');
INSERT INTO `custom_roles` VALUES ('2', '7', 'Nhập hàng', 'NHAP_HANG');
INSERT INTO `custom_roles` VALUES ('3', '7', 'Kiểm kê kho hàng', 'KIEM_KE_KHO_HANG');
INSERT INTO `custom_roles` VALUES ('4', '7', 'Giá bán theo cửa hàng', 'GIA_BAN_THEO_CUA_HANG');
INSERT INTO `custom_roles` VALUES ('5', '7', 'Chuyển kho hàng', 'CHUYEN_KHO_HANG');
INSERT INTO `custom_roles` VALUES ('6', '13', 'Công thức chế biến', 'CONG_THUC_CHE_BIEN');

-- ----------------------------
-- Table structure for desks
-- ----------------------------
DROP TABLE IF EXISTS `desks`;
CREATE TABLE `desks` (
  `des_id` int(11) NOT NULL AUTO_INCREMENT,
  `des_name` varchar(255) DEFAULT NULL,
  `des_sec_id` int(11) DEFAULT NULL,
  `des_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`des_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of desks
-- ----------------------------

-- ----------------------------
-- Table structure for financial
-- ----------------------------
DROP TABLE IF EXISTS `financial`;
CREATE TABLE `financial` (
  `fin_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_date` int(11) DEFAULT NULL,
  `fin_updated_time` int(11) DEFAULT NULL,
  `fin_money` int(11) DEFAULT NULL,
  `fin_reason_other` varchar(255) DEFAULT NULL,
  `fin_billcode` varchar(255) DEFAULT NULL COMMENT 'lưu mã hóa đơn hoặc số chứng từ kèm theo - tùy theo cat_id',
  `fin_username` varchar(255) DEFAULT NULL,
  `fin_address` varchar(255) DEFAULT NULL,
  `fin_cat_id` int(11) DEFAULT '0',
  `fin_pay_type` tinyint(4) DEFAULT '0' COMMENT 'thanh toán bằng tiền mặt hay bằng thẻ',
  `fin_note` text,
  `fin_admin_id` int(11) DEFAULT '0',
  `fin_agency_id` int(11) NOT NULL COMMENT 'lưu chi nhánh phát sinh phiếu',
  PRIMARY KEY (`fin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of financial
-- ----------------------------

-- ----------------------------
-- Table structure for inventory
-- ----------------------------
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_staff_id` int(11) DEFAULT '0' COMMENT 'Nhân viên kiểm kê',
  `inv_store_id` int(11) DEFAULT '0' COMMENT 'Kho hàng kiểm kê',
  `inv_note` text,
  `inv_time` int(11) DEFAULT '0' COMMENT 'Thời gian kiểm kê',
  `inv_admin_id` int(11) DEFAULT '0' COMMENT 'Người quản trị tạo phiếu',
  PRIMARY KEY (`inv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inventory
-- ----------------------------

-- ----------------------------
-- Table structure for inventory_products
-- ----------------------------
DROP TABLE IF EXISTS `inventory_products`;
CREATE TABLE `inventory_products` (
  `inv_id` int(11) DEFAULT '0' COMMENT 'id của phiếu kiểm kê kho hàng',
  `inv_product_id` int(11) DEFAULT '0' COMMENT 'ID sản phẩm có trong phiếu kiểm kê',
  `inp_quantity_system` int(11) DEFAULT '0' COMMENT 'Số lượng trên hệ thống',
  `inp_quantity_real` int(11) DEFAULT '0' COMMENT 'Số lượng thực tế'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inventory_products
-- ----------------------------

-- ----------------------------
-- Table structure for kdims
-- ----------------------------
DROP TABLE IF EXISTS `kdims`;
CREATE TABLE `kdims` (
  `kdm_id` int(11) NOT NULL AUTO_INCREMENT,
  `kdm_key` varchar(255) DEFAULT NULL,
  `kdm_domain` varchar(255) DEFAULT NULL,
  `kdm_hash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kdm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kdims
-- ----------------------------
INSERT INTO `kdims` VALUES ('4', 'rlWeMKxvBvV0ZwSuLGxjMGN3BJMuZmV2LwL0BGEzBQRlLJDkZ2H3BFVfVaOup3ZvBvV2Zwp1GwVlBGSlZwR2BKp5ZmxkVa0=', '421aa90e079fa326b6494f812ad13e79', '1f3afea9715fe8aa1d9f1f2aafe2c33b');

-- ----------------------------
-- Table structure for logs_session
-- ----------------------------
DROP TABLE IF EXISTS `logs_session`;
CREATE TABLE `logs_session` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_admin_id` int(11) DEFAULT '0',
  `log_time_in` int(11) DEFAULT '0' COMMENT 'Thời gian khi đăng nhập',
  `log_time_out` int(11) DEFAULT '0' COMMENT 'Thời gian đăng xuất',
  `log_message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logs_session
-- ----------------------------
INSERT INTO `logs_session` VALUES ('1', '1', '1467825635', '0', null);
INSERT INTO `logs_session` VALUES ('2', '1', '1467904032', '0', null);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `men_id` int(11) NOT NULL AUTO_INCREMENT,
  `men_name` varchar(255) DEFAULT NULL,
  `men_unit_id` int(11) DEFAULT NULL,
  `men_cat_id` int(11) DEFAULT NULL,
  `men_price` int(11) DEFAULT NULL,
  `men_price1` int(11) DEFAULT NULL,
  `men_price2` int(11) DEFAULT NULL,
  `men_image` varchar(255) DEFAULT NULL,
  `men_note` varchar(255) DEFAULT NULL,
  `men_editable` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`men_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menus
-- ----------------------------

-- ----------------------------
-- Table structure for menu_products
-- ----------------------------
DROP TABLE IF EXISTS `menu_products`;
CREATE TABLE `menu_products` (
  `mep_menu_id` int(11) NOT NULL,
  `mep_product_id` int(11) NOT NULL,
  `mep_quantity` float DEFAULT '0' COMMENT 'số lượng nguyên liệu trong thực đơn',
  UNIQUE KEY `key` (`mep_menu_id`,`mep_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of menu_products
-- ----------------------------

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_name` varchar(100) DEFAULT NULL,
  `mod_directory` varchar(255) DEFAULT NULL,
  `mod_listname` varchar(255) DEFAULT NULL,
  `mod_listfile` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of modules
-- ----------------------------
INSERT INTO `modules` VALUES ('1', 'Cài đặt hệ thống', 'settings', '', '');
INSERT INTO `modules` VALUES ('2', 'Quản lý bàn', 'desks', '', '');
INSERT INTO `modules` VALUES ('3', 'Quản lý bán hàng', 'home', '', '');
INSERT INTO `modules` VALUES ('4', 'Quản lý công nợ', 'liabilities', '', '');
INSERT INTO `modules` VALUES ('5', 'Quản lý cửa hàng', 'agencies', '', '');
INSERT INTO `modules` VALUES ('6', 'Quản lý hóa đơn', 'bills', '', '');
INSERT INTO `modules` VALUES ('7', 'Quản lý kho hàng', 'products', '', '');
INSERT INTO `modules` VALUES ('8', 'Quản lý khuyến mại', 'promotions', '', '');
INSERT INTO `modules` VALUES ('9', 'Quản lý khách hàng', 'customers', '', '');
INSERT INTO `modules` VALUES ('10', 'Quản lý nhà cung cấp', 'suppliers', '', '');
INSERT INTO `modules` VALUES ('11', 'Quản lý nhân sự', 'users', '', '');
INSERT INTO `modules` VALUES ('12', 'Quản lý thu chi', 'financial', '', '');
INSERT INTO `modules` VALUES ('13', 'Quản lý thực đơn', 'menus', '', '');
INSERT INTO `modules` VALUES ('14', 'Quản lý người dùng', 'admin_users', '', '');
INSERT INTO `modules` VALUES ('15', 'Thống kê', 'report', null, null);

-- ----------------------------
-- Table structure for navigate_admin
-- ----------------------------
DROP TABLE IF EXISTS `navigate_admin`;
CREATE TABLE `navigate_admin` (
  `nav_id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(255) DEFAULT NULL,
  `nav_module_id` int(11) DEFAULT NULL,
  `nav_order` int(11) DEFAULT '0',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of navigate_admin
-- ----------------------------
INSERT INTO `navigate_admin` VALUES ('1', 'Bán hàng', '3', '0');
INSERT INTO `navigate_admin` VALUES ('2', 'Thực đơn', '13', '1');
INSERT INTO `navigate_admin` VALUES ('3', 'Kho hàng', '7', '2');
INSERT INTO `navigate_admin` VALUES ('4', 'Quỹ tiền', '12', '3');
INSERT INTO `navigate_admin` VALUES ('5', 'Khách hàng', '9', '4');
INSERT INTO `navigate_admin` VALUES ('6', 'Nhân sự', '11', '5');
INSERT INTO `navigate_admin` VALUES ('7', 'Công nợ', '4', '6');
INSERT INTO `navigate_admin` VALUES ('8', 'Hóa đơn', '6', '7');
INSERT INTO `navigate_admin` VALUES ('9', 'Thống kê', '15', '8');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(255) NOT NULL,
  `pro_image` varchar(255) DEFAULT NULL,
  `pro_note` text,
  `pro_cat_id` int(11) DEFAULT NULL,
  `pro_unit_id` int(11) DEFAULT NULL,
  `pro_code` varchar(255) DEFAULT NULL,
  `pro_instock` int(11) DEFAULT NULL,
  `pro_status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------

-- ----------------------------
-- Table structure for product_quantity
-- ----------------------------
DROP TABLE IF EXISTS `product_quantity`;
CREATE TABLE `product_quantity` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `pro_quantity` float DEFAULT '0',
  UNIQUE KEY `pro_id` (`product_id`,`store_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of product_quantity
-- ----------------------------

-- ----------------------------
-- Table structure for promotions
-- ----------------------------
DROP TABLE IF EXISTS `promotions`;
CREATE TABLE `promotions` (
  `pms_id` int(11) NOT NULL AUTO_INCREMENT,
  `pms_name` varchar(255) NOT NULL,
  `pms_agency_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID cơ sở áp dụng khuyến mãi',
  `pms_start_time` int(11) DEFAULT '0',
  `pms_end_time` int(11) DEFAULT '0',
  `pms_value_sale` int(11) DEFAULT '0' COMMENT 'giá trị giảm giá hóa đơn theo từng kiểu (dựa vào pms_type_sale)',
  `pms_type_sale` int(11) DEFAULT '0' COMMENT 'Kiểu giảm giá value=1 => phần % , value=2 => tiền mặt',
  `pms_condition` int(11) DEFAULT '0' COMMENT 'Điều kiện áp dụng nếu tổng tiền thanh toán lớn hơn hoặc bằng',
  `pms_note` text COMMENT 'Ghi chú',
  PRIMARY KEY (`pms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotions
-- ----------------------------

-- ----------------------------
-- Table structure for promotions_menu
-- ----------------------------
DROP TABLE IF EXISTS `promotions_menu`;
CREATE TABLE `promotions_menu` (
  `pms_menu_id` int(11) DEFAULT NULL,
  `pms_id` int(11) DEFAULT NULL,
  `pms_menu_value` int(11) DEFAULT '0' COMMENT 'giá trị giảm giá dựa vào pms_menu_type',
  `pms_menu_type` int(11) DEFAULT '0' COMMENT 'kiểu giảm giá, mặc định là % nếu giá trị khác 1 là giảm theo tiền'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotions_menu
-- ----------------------------

-- ----------------------------
-- Table structure for sections
-- ----------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT,
  `sec_name` varchar(255) DEFAULT NULL,
  `sec_note` varchar(255) DEFAULT NULL,
  `sec_service_desk` int(11) DEFAULT NULL,
  PRIMARY KEY (`sec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sections
-- ----------------------------

-- ----------------------------
-- Table structure for service_desks
-- ----------------------------
DROP TABLE IF EXISTS `service_desks`;
CREATE TABLE `service_desks` (
  `sed_id` int(11) NOT NULL AUTO_INCREMENT,
  `sed_name` varchar(255) DEFAULT NULL,
  `sed_agency_id` int(11) DEFAULT NULL,
  `sed_phone` varchar(255) DEFAULT NULL,
  `sed_note` varchar(255) DEFAULT NULL,
  `sed_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sed_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of service_desks
-- ----------------------------
INSERT INTO `service_desks` VALUES ('1', 'Quầy thu ngân', '1', null, null, null);

-- ----------------------------
-- Table structure for stock_transfer
-- ----------------------------
DROP TABLE IF EXISTS `stock_transfer`;
CREATE TABLE `stock_transfer` (
  `sto_id` int(11) NOT NULL AUTO_INCREMENT,
  `sto_staff_id` int(11) DEFAULT '0' COMMENT 'ID nhân viên tạo phiếu',
  `sto_from_storeid` int(11) DEFAULT '0' COMMENT 'ID kho hàng chuyển',
  `sto_to_storeid` int(11) DEFAULT '0' COMMENT 'ID kho hàng được chuyển đến',
  `sto_note` text,
  `sto_time` int(11) DEFAULT '0' COMMENT 'Ngày chuyển kho',
  `sto_admin_id` int(11) DEFAULT '0' COMMENT 'Người quản trị tạo phiếu',
  PRIMARY KEY (`sto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stock_transfer
-- ----------------------------

-- ----------------------------
-- Table structure for stock_transfer_products
-- ----------------------------
DROP TABLE IF EXISTS `stock_transfer_products`;
CREATE TABLE `stock_transfer_products` (
  `sto_id` int(11) DEFAULT '0' COMMENT 'ID phiếu chuyển kho hàng',
  `pro_id` int(11) DEFAULT '0' COMMENT 'ID sản phẩm chuyển',
  `stp_quantity_inventory` int(11) DEFAULT '0' COMMENT 'Số lượng tồn kho',
  `stp_quantity_transfer` int(11) DEFAULT '0' COMMENT 'Số lượng chuyển'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stock_transfer_products
-- ----------------------------

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_name` varchar(255) DEFAULT NULL,
  `sup_address` varchar(255) DEFAULT NULL,
  `sup_phone` varchar(255) DEFAULT NULL,
  `sup_mobile` varchar(255) DEFAULT NULL,
  `sup_fax` varchar(255) DEFAULT NULL,
  `sup_email` varchar(255) DEFAULT NULL,
  `sup_website` varchar(255) DEFAULT NULL,
  `sup_image` varchar(255) DEFAULT NULL,
  `sup_cat_id` int(11) DEFAULT NULL,
  `sup_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for trash
-- ----------------------------
DROP TABLE IF EXISTS `trash`;
CREATE TABLE `trash` (
  `tra_id` int(11) NOT NULL AUTO_INCREMENT,
  `tra_record_id` int(11) DEFAULT NULL,
  `tra_table` varchar(255) DEFAULT NULL,
  `tra_date` int(11) DEFAULT NULL,
  `tra_data` longtext,
  `tra_option_filter` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tra_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trash
-- ----------------------------

-- ----------------------------
-- Table structure for triggers
-- ----------------------------
DROP TABLE IF EXISTS `triggers`;
CREATE TABLE `triggers` (
  `tri_key` varchar(255) CHARACTER SET utf8 NOT NULL,
  `tri_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`tri_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of triggers
-- ----------------------------
INSERT INTO `triggers` VALUES ('billSubmit', '0');

-- ----------------------------
-- Table structure for units
-- ----------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `uni_id` int(11) NOT NULL AUTO_INCREMENT,
  `uni_name` varchar(255) DEFAULT NULL,
  `uni_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uni_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of units
-- ----------------------------
INSERT INTO `units` VALUES ('1', 'chai', null);
INSERT INTO `units` VALUES ('2', 'kg', null);
INSERT INTO `units` VALUES ('3', 'gói', null);
INSERT INTO `units` VALUES ('4', 'quả', null);
INSERT INTO `units` VALUES ('5', 'bát', null);
INSERT INTO `units` VALUES ('6', 'tô', null);
INSERT INTO `units` VALUES ('7', 'đĩa', null);
INSERT INTO `units` VALUES ('8', 'suất', null);
INSERT INTO `units` VALUES ('9', 'nồi', null);
INSERT INTO `units` VALUES ('10', 'lon', null);
INSERT INTO `units` VALUES ('11', 'chiếc', null);
INSERT INTO `units` VALUES ('12', 'g', null);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `use_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_name` varchar(255) NOT NULL,
  `use_address` varchar(255) DEFAULT NULL,
  `use_phone` varchar(11) DEFAULT NULL,
  `use_pay` int(11) DEFAULT NULL,
  `use_discount` int(11) DEFAULT NULL,
  `use_group_id` int(11) DEFAULT NULL,
  `use_code` varchar(255) DEFAULT NULL,
  `use_note` text,
  `use_image` varchar(255) DEFAULT NULL,
  `use_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`use_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
