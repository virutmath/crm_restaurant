/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-08-27 17:56:27
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
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_logs
-- ----------------------------
INSERT INTO `admin_logs` VALUES ('1', '1', 'add', '1467904895', 'Thêm mới danh mục 6 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('2', '1', 'add', '1467904904', 'Thêm mới danh mục 7 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('3', '1', 'add', '1467904912', 'Thêm mới danh mục 8 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('4', '1', 'add', '1467905006', 'Thêm mới danh mục 9 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('5', '1', 'edit', '1467905015', 'Sửa danh mục 7 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('6', '1', 'add', '1467905026', 'Thêm mới danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('7', '1', 'add', '1467905037', 'Thêm mới danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('8', '1', 'add', '1467905058', 'Thêm mới danh mục 12 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('9', '1', 'edit', '1467905117', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('10', '1', 'edit', '1467905123', 'Sửa danh mục 12 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('11', '1', 'add', '1467905144', 'Thêm mới danh mục 13 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('12', '1', 'add', '1467905156', 'Thêm mới danh mục 14 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('13', '1', 'add', '1467905391', 'Thêm mới bản ghi 1 bảng menus');
INSERT INTO `admin_logs` VALUES ('14', '1', 'add', '1467905438', 'Thêm mới bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('15', '1', 'add', '1467905496', 'Thêm mới bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('16', '1', 'edit', '1467905519', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('17', '1', 'add', '1467905575', 'Thêm mới bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('18', '1', 'add', '1467905624', 'Thêm mới khu vực bàn ăn ID 1');
INSERT INTO `admin_logs` VALUES ('19', '1', 'add', '1467905635', 'Thêm mới bàn ăn ID 1');
INSERT INTO `admin_logs` VALUES ('20', '1', 'add', '1467905641', 'Thêm mới bàn ăn ID 2');
INSERT INTO `admin_logs` VALUES ('21', '1', 'add', '1467905650', 'Thêm mới bàn ăn ID 3');
INSERT INTO `admin_logs` VALUES ('22', '1', 'add', '1467905658', 'Thêm mới bàn ăn ID 4');
INSERT INTO `admin_logs` VALUES ('23', '1', 'add', '1467905672', 'Thêm mới khu vực bàn ăn ID 2');
INSERT INTO `admin_logs` VALUES ('24', '1', 'add', '1467905682', 'Thêm mới bàn ăn ID 5');
INSERT INTO `admin_logs` VALUES ('25', '1', 'add', '1467905787', 'Thêm mới danh mục 15 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('26', '1', 'add', '1467905801', 'Thêm mới danh mục 16 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('27', '1', 'add', '1467905839', 'Thêm mới danh mục 17 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('28', '1', 'add', '1467905853', 'Thêm mới danh mục 18 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('29', '1', 'add', '1467905860', 'Thêm mới danh mục 19 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('30', '1', 'add', '1467905868', 'Thêm mới danh mục 20 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('31', '1', 'add', '1467905907', 'Thêm mới bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('32', '1', 'add', '1467905923', 'Thêm mới bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('33', '1', 'add', '1467905936', 'Thêm mới bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('34', '1', 'add', '1467905948', 'Thêm mới bản ghi 4 bảng products');
INSERT INTO `admin_logs` VALUES ('35', '1', 'add', '1467905965', 'Thêm mới bản ghi 5 bảng products');
INSERT INTO `admin_logs` VALUES ('36', '1', 'add', '1467905984', 'Thêm mới bản ghi 6 bảng products');
INSERT INTO `admin_logs` VALUES ('37', '1', 'add', '1467905994', 'Thêm mới bản ghi 7 bảng products');
INSERT INTO `admin_logs` VALUES ('38', '1', 'add', '1467906009', 'Thêm mới bản ghi 8 bảng products');
INSERT INTO `admin_logs` VALUES ('39', '1', 'add', '1467906054', 'Thêm mới bản ghi 9 bảng products');
INSERT INTO `admin_logs` VALUES ('40', '1', 'add', '1467906066', 'Thêm mới danh mục 21 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('41', '1', 'edit', '1467906072', 'Sửa danh mục 21 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('42', '1', 'add', '1467906102', 'Thêm mới bản ghi 10 bảng products');
INSERT INTO `admin_logs` VALUES ('43', '1', 'add', '1467906118', 'Thêm mới bản ghi 11 bảng products');
INSERT INTO `admin_logs` VALUES ('44', '1', 'add', '1467906127', 'Thêm mới bản ghi 12 bảng products');
INSERT INTO `admin_logs` VALUES ('45', '1', 'add', '1467906140', 'Thêm mới bản ghi 13 bảng products');
INSERT INTO `admin_logs` VALUES ('46', '1', 'add', '1467906151', 'Thêm mới bản ghi 14 bảng products');
INSERT INTO `admin_logs` VALUES ('47', '1', 'add', '1467906182', 'Thêm mới bản ghi 15 bảng products');
INSERT INTO `admin_logs` VALUES ('48', '1', 'add', '1467906192', 'Thêm mới bản ghi 16 bảng products');
INSERT INTO `admin_logs` VALUES ('49', '1', 'add', '1467906201', 'Thêm mới bản ghi 17 bảng products');
INSERT INTO `admin_logs` VALUES ('50', '1', 'add', '1467906230', 'Thêm mới danh mục 22 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('51', '1', 'add', '1467906274', 'Thêm mới bản ghi 18 bảng products');
INSERT INTO `admin_logs` VALUES ('52', '1', 'edit', '1467906280', 'Sửa danh mục 22 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('53', '1', 'add', '1467906296', 'Thêm mới bản ghi 19 bảng products');
INSERT INTO `admin_logs` VALUES ('54', '1', 'add', '1467906306', 'Thêm mới bản ghi 20 bảng products');
INSERT INTO `admin_logs` VALUES ('55', '1', 'add', '1467906332', 'Thêm mới danh mục 23 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('56', '1', 'add', '1467906352', 'Thêm mới bản ghi 21 bảng products');
INSERT INTO `admin_logs` VALUES ('57', '1', 'add', '1467906380', 'Thêm mới bản ghi 22 bảng products');
INSERT INTO `admin_logs` VALUES ('58', '1', 'add', '1467906394', 'Thêm mới bản ghi 23 bảng products');
INSERT INTO `admin_logs` VALUES ('59', '1', 'add', '1467906409', 'Thêm mới bản ghi 24 bảng products');
INSERT INTO `admin_logs` VALUES ('60', '1', 'edit', '1467906414', 'Sửa danh mục 21 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('61', '1', 'add', '1467906461', 'Thêm mới bản ghi 25 bảng products');
INSERT INTO `admin_logs` VALUES ('62', '1', 'add', '1467906505', 'Thêm mới bản ghi 26 bảng products');
INSERT INTO `admin_logs` VALUES ('63', '1', 'add', '1467906536', 'Thêm nguyên liệu pro_id 22 số lượng 100 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('64', '1', 'add', '1467906548', 'Thêm nguyên liệu pro_id 17 số lượng 20 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('65', '1', 'add', '1467906564', 'Thêm nguyên liệu pro_id 16 số lượng 50 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('66', '1', 'add', '1467906581', 'Thêm nguyên liệu pro_id 18 số lượng 80 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('67', '1', 'add', '1467906616', 'Thêm mới danh mục 24 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('68', '1', 'add', '1467906665', 'Thêm mới bản ghi 27 bảng products');
INSERT INTO `admin_logs` VALUES ('69', '1', 'add', '1467906687', 'Thêm mới bản ghi 28 bảng products');
INSERT INTO `admin_logs` VALUES ('70', '1', 'add', '1467906702', 'Thêm mới bản ghi 29 bảng products');
INSERT INTO `admin_logs` VALUES ('71', '1', 'add', '1467906715', 'Thêm mới bản ghi 30 bảng products');
INSERT INTO `admin_logs` VALUES ('72', '1', 'add', '1467906753', 'Thêm mới bản ghi 31 bảng products');
INSERT INTO `admin_logs` VALUES ('73', '1', 'add', '1467906793', 'Thêm nguyên liệu pro_id 25 số lượng 100 vào thực đơn 2');
INSERT INTO `admin_logs` VALUES ('74', '1', 'add', '1467906815', 'Thêm nguyên liệu pro_id 28 số lượng 5 vào thực đơn 2');
INSERT INTO `admin_logs` VALUES ('75', '1', 'add', '1467906852', 'Tạo bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('76', '1', 'add', '1467906856', 'Thêm thực đơn ID: 1 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('77', '1', 'add', '1467906858', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('78', '1', 'add', '1467906861', 'Thêm thực đơn ID: 3 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('79', '1', 'add', '1468077065', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('80', '1', 'add', '1468077066', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('81', '1', 'add', '1468077069', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('82', '1', 'delete', '1468077076', 'Hủy thực đơn ID 2 ở bàn ID 1');
INSERT INTO `admin_logs` VALUES ('83', '1', 'add', '1468077077', 'Thêm thực đơn ID: 3 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('84', '1', 'add', '1468077080', 'Thêm thực đơn ID: 3 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('85', '1', 'add', '1468077081', 'Thêm thực đơn ID: 4 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('86', '1', 'delete', '1468077084', 'Hủy thực đơn ID 4 ở bàn ID 1');
INSERT INTO `admin_logs` VALUES ('87', '1', 'delete', '1468077087', 'Hủy thực đơn ID 3 ở bàn ID 1');
INSERT INTO `admin_logs` VALUES ('88', '1', 'add', '1468077101', 'Tạo bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('89', '1', 'print', '1468077188', 'In chế biến xuống bếp - bàn ID 1');
INSERT INTO `admin_logs` VALUES ('90', '1', 'delete', '1468077430', 'Hủy bàn ID 5');
INSERT INTO `admin_logs` VALUES ('91', '1', 'add', '1468077692', 'Tạo bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('92', '1', 'add', '1468077700', 'Thêm thực đơn ID: 1 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('93', '1', 'print', '1468077703', 'In chế biến xuống bếp - bàn ID 1');
INSERT INTO `admin_logs` VALUES ('94', '1', 'add', '1468167739', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('95', '1', 'add', '1468167743', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('96', '1', 'add', '1468340858', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('97', '1', 'add', '1468547097', 'Xóa thực đơn ID: 2 khỏi bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('98', '1', 'add', '1468547118', 'Thêm thực đơn ID: 1 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('99', '1', 'add', '1468547129', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('100', '1', 'delete', '1468547131', 'Hủy bàn ID 2');
INSERT INTO `admin_logs` VALUES ('101', '1', 'add', '1468547133', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('102', '1', 'delete', '1468547152', 'Hủy bàn ID 2');
INSERT INTO `admin_logs` VALUES ('103', '1', 'add', '1468749299', 'Thêm thực đơn ID: 3 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('104', '1', 'delete', '1468749353', 'Hủy bàn ID 1');
INSERT INTO `admin_logs` VALUES ('105', '1', 'add', '1468749511', 'Tạo bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('106', '1', 'add', '1468749528', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('107', '1', 'add', '1468937880', 'Thêm thực đơn ID: 1 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('108', '1', 'add', '1468937882', 'Thêm thực đơn ID: 3 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('109', '1', 'add', '1468937885', 'Thêm thực đơn ID: 4 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('110', '1', 'add', '1468938108', 'Tạo bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('111', '1', 'add', '1468938117', 'Thêm thực đơn ID: 1 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('112', '1', 'add', '1468938120', 'Thêm thực đơn ID: 2 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('113', '1', 'add', '1468938384', 'Thêm mới nhóm tài khoản ID 16 bảng admin_users_groups');
INSERT INTO `admin_logs` VALUES ('114', '1', 'trash', '1468946049', 'Xóa bản ghi 2 từ bảng service_desks');
INSERT INTO `admin_logs` VALUES ('115', '1', 'add', '1468979217', 'Thêm mới danh mục 25 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('116', '1', 'add', '1468979248', 'Thêm mới phiếu chi 3 bảng financial');
INSERT INTO `admin_logs` VALUES ('117', '1', 'add', '1468979371', 'Thêm mới danh mục 26 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('118', '1', 'add', '1468979388', 'Thêm mới bản ghi 1 bảng suppliers');
INSERT INTO `admin_logs` VALUES ('119', '1', 'add', '1468980458', 'Thêm thực đơn ID: 4 vào bàn ID: 1');
INSERT INTO `admin_logs` VALUES ('120', '1', 'trash', '1469676609', 'Xóa bản ghi 25 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('121', '1', 'add', '1469676621', 'Thêm mới danh mục 27 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('122', '1', 'add', '1469676682', 'Thêm mới phiếu thu 1 bảng financial');
INSERT INTO `admin_logs` VALUES ('123', '1', 'add', '1469676733', 'Thêm mới danh mục 28 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('124', '1', 'add', '1469676759', 'Thêm mới danh mục 29 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('125', '1', 'add', '1469676816', 'Chỉnh sửa bản ghi 28 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('126', '1', 'trash', '1469676822', 'Xóa bản ghi 29 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('127', '1', 'add', '1469676867', 'Thêm mới phiếu chi 2 bảng financial');
INSERT INTO `admin_logs` VALUES ('128', '1', 'add', '1469676987', 'Thêm mới phiếu chi 3 bảng financial');
INSERT INTO `admin_logs` VALUES ('129', '1', 'add', '1469677057', 'Thêm mới phiếu chi 4 bảng financial');
INSERT INTO `admin_logs` VALUES ('130', '1', 'add', '1469677162', 'Thêm mới phiếu chi 5 bảng financial');
INSERT INTO `admin_logs` VALUES ('131', '1', 'add', '1469677373', 'Thêm mới phiếu thu 6 bảng financial');
INSERT INTO `admin_logs` VALUES ('132', '1', 'add', '1469681687', 'Thêm mới phiếu thu 7 bảng financial');
INSERT INTO `admin_logs` VALUES ('133', '1', 'add', '1469799383', 'Thêm mới danh mục 30 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('134', '1', 'add', '1469799487', 'Thêm mới phiếu chi 8 bảng financial');
INSERT INTO `admin_logs` VALUES ('135', '1', 'add', '1469799913', 'Thêm mới phiếu thu 9 bảng financial');
INSERT INTO `admin_logs` VALUES ('136', '1', 'add', '1469802056', 'Thêm mới chiến dịch 1 bảng promotions');
INSERT INTO `admin_logs` VALUES ('137', '1', 'add', '1471361017', 'Thêm mới phiếu chi 10 bảng financial');

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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_users_groups
-- ----------------------------
INSERT INTO `admin_users_groups` VALUES ('1', 'admin', '1', null);
INSERT INTO `admin_users_groups` VALUES ('16', 'test', '0', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agencies
-- ----------------------------
INSERT INTO `agencies` VALUES ('1', 'Panda Chan - Cơ sở chính', 'Hoang Mai, Ha Noi, Viet Nam', '0914271489', null, null);

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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories_multi
-- ----------------------------
INSERT INTO `categories_multi` VALUES ('1', 'Bán hàng', 'money_system_in', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('2', 'Nhập hàng', 'money_system_out', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('3', 'Thanh toán công nợ khách hàng', 'money_system_in', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('4', 'Thanh toán công nợ NCC', 'money_system_out', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('5', 'Kho chính', 'stores', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('6', 'Salad', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('7', 'Sashimi', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('8', 'Sushi', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('9', 'Maki', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('10', 'Itame', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('11', 'Món chiên', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('12', 'Lẩu', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('13', 'Mushi & soup', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('14', 'Món nướng', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('15', 'Nguyên liệu', 'products', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('16', 'Không phải chế biến', 'products', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('17', 'Thịt', 'products', '', null, '15', '0', '');
INSERT INTO `categories_multi` VALUES ('18', 'Cá', 'products', '', null, '15', '0', '');
INSERT INTO `categories_multi` VALUES ('19', 'Trứng', 'products', '', null, '15', '0', '');
INSERT INTO `categories_multi` VALUES ('20', 'Tôm', 'products', '', null, '15', '0', '');
INSERT INTO `categories_multi` VALUES ('21', 'Rau củ', 'products', '', null, '15', '0', '');
INSERT INTO `categories_multi` VALUES ('22', 'Sốt, tương', 'products', '', null, '16', '0', '');
INSERT INTO `categories_multi` VALUES ('23', 'Mì ăn liền', 'products', '', null, '16', '0', '');
INSERT INTO `categories_multi` VALUES ('24', 'Gia vị', 'products', '', null, '16', '0', '');
INSERT INTO `categories_multi` VALUES ('27', 'Tiền vốn', 'money_in', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('26', 'Thường xuyên', 'supplier', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('28', 'Chi phí thực hành', 'money_out', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('30', 'Các trường hợp khác', 'money_out', null, null, '0', '0', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of configurations
-- ----------------------------
INSERT INTO `configurations` VALUES ('1', '1', 'Panda - Nhà hàng Nhật Bản', 'Nam Định', '0988165567', '1', '5', '1', null, '1', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of desks
-- ----------------------------
INSERT INTO `desks` VALUES ('1', 'Bàn 1', '1', '');
INSERT INTO `desks` VALUES ('2', 'Bàn 2', '1', '');
INSERT INTO `desks` VALUES ('3', 'Bàn 3', '1', '');
INSERT INTO `desks` VALUES ('4', 'Bàn 4', '1', '');
INSERT INTO `desks` VALUES ('5', 'Take Away 1', '2', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of financial
-- ----------------------------
INSERT INTO `financial` VALUES ('1', '1469676682', '1469676682', '100000000', 'Tiền tiết kiệm của 2 vợ chồng', '', 'Ms Hoa', 'Hoang Mai, Ha Noi, Viet Nam', '27', '0', '', '1', '1');
INSERT INTO `financial` VALUES ('2', '1469676867', '1469676867', '2000000', '', '', 'Ms Hòa', 'Hoang Mai, Ha Noi, Viet Nam', '28', '0', 'Chi phí thực hành tháng 7', '1', '1');
INSERT INTO `financial` VALUES ('3', '1469676987', '1469676987', '235000', '', '', 'Mr Kiên', 'Hoang Mai, Ha Noi, Viet Nam', '28', '0', 'Mua dao, thớt, cân, mài dao, vỉ nướng, kéo con', '1', '1');
INSERT INTO `financial` VALUES ('4', '1469677057', '1469677057', '450000', '', '', 'Mr Kiên', 'Hoang Mai, Ha Noi, Viet Nam', '28', '0', 'Mua nguyên liệu gồm gạo Nhật, nori, thanh cua, mirin', '1', '1');
INSERT INTO `financial` VALUES ('5', '1469677162', '1469677162', '10800000', '', '', 'Mr Kiên', 'Hoang Mai, Ha Noi, Viet Nam', '28', '0', 'Tiền học', '1', '1');
INSERT INTO `financial` VALUES ('6', '1469677373', '1469677373', '9000000', '', '', 'Ms Hoa', 'Hoang Mai, Ha Noi, Viet Nam', '27', '0', 'Tiền Cty trả tiền nhà', '1', '1');
INSERT INTO `financial` VALUES ('7', '1469681687', '1469681687', '20000000', '', '', 'Ms Hoa', 'Hoang Mai, Ha Noi, Viet Nam', '27', '1', 'Tiền trong thẻ Tech', '1', '1');
INSERT INTO `financial` VALUES ('8', '1469799487', '1469799487', '14500000', '', '', 'Mr Kiên', 'Hoang Mai, Ha Noi, Viet Nam', '30', '0', 'Thanh toán tiền bảo hiểm cho Gấu', '1', '1');
INSERT INTO `financial` VALUES ('9', '1469799913', '1469799913', '3485000', '', '', 'Ms Hoa', 'Hoang Mai, Ha Noi, Viet Nam', '27', '0', 'Tiền tiết kiệm trong thẻ', '1', '1');
INSERT INTO `financial` VALUES ('10', '1471361017', '1471361017', '1300000', '', '', 'Ms Hòa', 'Hoang Mai, Ha Noi, Viet Nam', '28', '0', 'Đưa dì hòa tiền đi chợ', '1', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kdims
-- ----------------------------
INSERT INTO `kdims` VALUES ('4', 'rlWeMKxvBvV0ZwSuLGxjMGN3BJMuZmV2LwL0BGEzBQRlLJDkZ2H3BFVfVaOup3ZvBvV2Zwp1GwVlBGSlZwR2BKp5ZmxkVa0=', '421aa90e079fa326b6494f812ad13e79', '1f3afea9715fe8aa1d9f1f2aafe2c33b');
INSERT INTO `kdims` VALUES ('8', 'rlWeMKxvBvV1AQVkA2VlAwN5AQxmBQEvMGZ5BGIwBGOuBQMzA2MvMFVfVaOup3ZvBvV3ZmL5FGp3ZwWaZGp3BTH3ZmLjVa0=', '54217b260949384be3995c90a86f7fbe', '02a4c660f6bbcebf62397f982f2e31b6');
INSERT INTO `kdims` VALUES ('7', 'rlWeMKxvBvWvLmLjLmSyZQqvAGH4LGEzMzDkZmN1ZGx2MzR4A2MyAlVfVaOup3ZvBvV3ZQZlGwLlZwqcZGZlZaV0AwH3Va0=', 'bc60c1e07b558a4ffd1305196fa87fe7', '986084274006c297e44eaae099c07cb5');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logs_session
-- ----------------------------
INSERT INTO `logs_session` VALUES ('1', '1', '1467825635', '0', null);
INSERT INTO `logs_session` VALUES ('2', '1', '1467904032', '0', null);
INSERT INTO `logs_session` VALUES ('3', '1', '1468077055', '0', null);
INSERT INTO `logs_session` VALUES ('4', '1', '1468162411', '0', null);
INSERT INTO `logs_session` VALUES ('5', '1', '1468255204', '0', null);
INSERT INTO `logs_session` VALUES ('6', '1', '1468336806', '0', null);
INSERT INTO `logs_session` VALUES ('7', '1', '1468515570', '0', null);
INSERT INTO `logs_session` VALUES ('8', '1', '1468546760', '0', null);
INSERT INTO `logs_session` VALUES ('9', '1', '1468749231', '0', null);
INSERT INTO `logs_session` VALUES ('10', '1', '1468937864', '0', null);
INSERT INTO `logs_session` VALUES ('11', '1', '1468978188', '0', null);
INSERT INTO `logs_session` VALUES ('12', '1', '1469631589', '0', null);
INSERT INTO `logs_session` VALUES ('13', '1', '1469674559', '0', null);
INSERT INTO `logs_session` VALUES ('14', '1', '1469799215', '0', null);
INSERT INTO `logs_session` VALUES ('15', '1', '1471360949', '0', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('1', 'Salad khoai tây', '7', '6', '30000', '0', '0', 'xrf1467905389.jpg', '', '0');
INSERT INTO `menus` VALUES ('2', 'Đậu nành luộc', '7', '6', '30000', '0', '0', 'fwt1467905437.jpg', '', '0');
INSERT INTO `menus` VALUES ('3', 'Salad rong biển tươi trứng cua đỏ', '7', '6', '42000', '0', '0', 'ovc1467905518.jpg', '', '0');
INSERT INTO `menus` VALUES ('4', 'Salad trứng cua đỏ', '7', '6', '45000', '0', '0', 'ecl1467905573.jpg', '', '0');

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
INSERT INTO `menu_products` VALUES ('1', '16', '50');
INSERT INTO `menu_products` VALUES ('1', '17', '20');
INSERT INTO `menu_products` VALUES ('1', '18', '80');
INSERT INTO `menu_products` VALUES ('1', '22', '100');
INSERT INTO `menu_products` VALUES ('2', '25', '100');
INSERT INTO `menu_products` VALUES ('2', '28', '5');

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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'Thịt lợn', null, '', '17', '12', '', '0', '1');
INSERT INTO `products` VALUES ('2', 'Thịt gà', null, '', '17', '12', '', '0', '1');
INSERT INTO `products` VALUES ('3', 'Thịt bò', null, '', '17', '12', '', '0', '1');
INSERT INTO `products` VALUES ('4', 'Cá hồi', null, '', '18', '12', '', '0', '1');
INSERT INTO `products` VALUES ('5', 'Trứng gà', null, '', '19', '4', '', '0', '1');
INSERT INTO `products` VALUES ('6', 'Trứng tôm', null, '', '19', '12', '', '0', '1');
INSERT INTO `products` VALUES ('7', 'Trứng cua', null, '', '19', '12', '', '0', '1');
INSERT INTO `products` VALUES ('8', 'Cá ngừ', null, '', '18', '12', '', '0', '1');
INSERT INTO `products` VALUES ('9', 'Cá trích', null, '', '18', '12', '', '0', '1');
INSERT INTO `products` VALUES ('10', 'Rong biển', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('11', 'Cải ngọt', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('12', 'Cải thìa', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('13', 'Xu hào', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('14', 'Bắp cải', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('15', 'Xà lách', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('16', 'Cà rốt', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('17', 'Cà chua', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('18', 'Sốt mayonaise', null, '', '22', '12', '', '0', '1');
INSERT INTO `products` VALUES ('19', 'Tương ớt', null, '', '22', '1', '', '0', '1');
INSERT INTO `products` VALUES ('20', 'Tương cà', null, '', '22', '1', '', '0', '1');
INSERT INTO `products` VALUES ('21', 'Mì Hảo Hảo', null, '', '23', '3', '', '0', '1');
INSERT INTO `products` VALUES ('22', 'Khoai tây', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('23', 'Hành lá', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('24', 'Hành tây', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('25', 'Đậu nành', null, '', '21', '12', '', '0', '1');
INSERT INTO `products` VALUES ('26', 'Tôm sú', null, '', '20', '12', '', '0', '1');
INSERT INTO `products` VALUES ('27', 'Nước mắm', null, '', '24', '13', '', '0', '1');
INSERT INTO `products` VALUES ('28', 'Muối tinh', null, '', '24', '12', '', '0', '1');
INSERT INTO `products` VALUES ('29', 'Hạt tiêu', null, '', '24', '12', '', '0', '1');
INSERT INTO `products` VALUES ('30', 'Bột nêm', null, '', '24', '12', '', '0', '1');
INSERT INTO `products` VALUES ('31', 'Mì Udon', null, '', '23', '12', '', '0', '1');

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
INSERT INTO `product_quantity` VALUES ('1', '5', '0');
INSERT INTO `product_quantity` VALUES ('2', '5', '100');
INSERT INTO `product_quantity` VALUES ('3', '5', '1000');
INSERT INTO `product_quantity` VALUES ('4', '5', '0');
INSERT INTO `product_quantity` VALUES ('5', '5', '100');
INSERT INTO `product_quantity` VALUES ('6', '5', '0');
INSERT INTO `product_quantity` VALUES ('7', '5', '10');
INSERT INTO `product_quantity` VALUES ('8', '5', '0');
INSERT INTO `product_quantity` VALUES ('9', '5', '0');
INSERT INTO `product_quantity` VALUES ('10', '5', '0');
INSERT INTO `product_quantity` VALUES ('11', '5', '0');
INSERT INTO `product_quantity` VALUES ('12', '5', '0');
INSERT INTO `product_quantity` VALUES ('13', '5', '0');
INSERT INTO `product_quantity` VALUES ('14', '5', '0');
INSERT INTO `product_quantity` VALUES ('15', '5', '0');
INSERT INTO `product_quantity` VALUES ('16', '5', '-100');
INSERT INTO `product_quantity` VALUES ('17', '5', '-40');
INSERT INTO `product_quantity` VALUES ('18', '5', '-160');
INSERT INTO `product_quantity` VALUES ('19', '5', '0');
INSERT INTO `product_quantity` VALUES ('20', '5', '0');
INSERT INTO `product_quantity` VALUES ('21', '5', '0');
INSERT INTO `product_quantity` VALUES ('22', '5', '-200');
INSERT INTO `product_quantity` VALUES ('23', '5', '0');
INSERT INTO `product_quantity` VALUES ('24', '5', '0');
INSERT INTO `product_quantity` VALUES ('25', '5', '-100');
INSERT INTO `product_quantity` VALUES ('26', '5', '0');
INSERT INTO `product_quantity` VALUES ('27', '5', '0');
INSERT INTO `product_quantity` VALUES ('28', '5', '-5');
INSERT INTO `product_quantity` VALUES ('29', '5', '0');
INSERT INTO `product_quantity` VALUES ('30', '5', '0');
INSERT INTO `product_quantity` VALUES ('31', '5', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotions
-- ----------------------------
INSERT INTO `promotions` VALUES ('1', 'test', '1', '1469743200', '1469743200', '5', '1', '0', '');

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
INSERT INTO `promotions_menu` VALUES ('1', '1', '0', '0');
INSERT INTO `promotions_menu` VALUES ('2', '1', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sections
-- ----------------------------
INSERT INTO `sections` VALUES ('1', 'Tầng 1', '', '1');
INSERT INTO `sections` VALUES ('2', 'Take away', '', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES ('1', 'Siêu thị Metro', 'Phạm Văn Đồng', '', '', '', '', '', null, '26', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trash
-- ----------------------------
INSERT INTO `trash` VALUES ('1', '2', 'service_desks', '1468946049', 'eyJzZWRfaWQiOiIyIiwic2VkX25hbWUiOiJRdVx1MWVhN3kgdGh1IG5nXHUwMGUybiIsInNlZF9hZ2VuY3lfaWQiOiIxIiwic2VkX3Bob25lIjpudWxsLCJzZWRfbm90ZSI6bnVsbCwic2VkX2ltYWdlIjpudWxsfQ==', '');
INSERT INTO `trash` VALUES ('2', '25', 'categories_multi', '1469676609', 'eyJjYXRfaWQiOiIyNSIsImNhdF9uYW1lIjoiTG9iYnkgY1x1MDBmNG5nIGFuIiwiY2F0X3R5cGUiOiJtb25leV9vdXQiLCJjYXRfZGVzYyI6bnVsbCwiY2F0X3BpY3R1cmUiOm51bGwsImNhdF9wYXJlbnRfaWQiOiIwIiwiY2F0X2hhc19jaGlsZCI6IjAiLCJjYXRfbm90ZSI6IiJ9', '');
INSERT INTO `trash` VALUES ('3', '29', 'categories_multi', '1469676822', 'eyJjYXRfaWQiOiIyOSIsImNhdF9uYW1lIjoiTXVhIGRcdTFlZTVuZyBjXHUxZWU1ICIsImNhdF90eXBlIjoibW9uZXlfb3V0IiwiY2F0X2Rlc2MiOm51bGwsImNhdF9waWN0dXJlIjpudWxsLCJjYXRfcGFyZW50X2lkIjoiMCIsImNhdF9oYXNfY2hpbGQiOiIwIiwiY2F0X25vdGUiOiIifQ==', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

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
INSERT INTO `units` VALUES ('13', 'ml', null);
INSERT INTO `units` VALUES ('14', 'l', null);

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
