/*
Navicat MySQL Data Transfer

Source Server         : CONGNH-SERVER
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-25 08:59:51
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
INSERT INTO `admin_group_role` VALUES ('12', '3', '', '0', '0', '0', '0', '0');
INSERT INTO `admin_group_role` VALUES ('12', '2', '', '0', '0', '0', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=491 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_logs
-- ----------------------------
INSERT INTO `admin_logs` VALUES ('1', '1', 'add', '1422239074', 'Thêm mới bản ghi 1 bảng suppliers');
INSERT INTO `admin_logs` VALUES ('2', '1', 'trash', '1422241828', 'Xóa bản ghi 1 từ bảng suppliers');
INSERT INTO `admin_logs` VALUES ('3', '1', 'recovery', '1422241944', 'Khôi phục bản ghi 1 tới bảng suppliers');
INSERT INTO `admin_logs` VALUES ('4', '1', 'edit', '1422242205', 'Sửa danh mục 1 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('5', '1', 'edit', '1422242401', 'Sửa danh mục 1 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('6', '1', 'edit', '1422242532', 'Sửa danh mục 1 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('7', '1', 'edit', '1422242643', 'Sửa danh mục 1 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('8', '1', 'edit', '1422247129', 'Sửa cửa hàng 1 bảng agencies');
INSERT INTO `admin_logs` VALUES ('9', '1', 'edit', '1422247134', 'Sửa cửa hàng 1 bảng agencies');
INSERT INTO `admin_logs` VALUES ('10', '1', 'add', '1422255170', 'Thêm mới bản ghi 0 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('11', '1', 'add', '1422257138', 'Thêm mới bản ghi 1 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('12', '1', 'add', '1422257138', 'Thêm mới bản ghi 2 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('13', '1', 'add', '1422257711', 'Thêm mới bản ghi 1 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('14', '1', 'add', '1422257886', 'Thêm mới bản ghi 2 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('15', '1', 'trash', '1422257891', 'Xóa bản ghi 2 từ bảng service_desks');
INSERT INTO `admin_logs` VALUES ('16', '1', 'recovery', '1422257897', 'Khôi phục bản ghi 2 tới bảng service_desks');
INSERT INTO `admin_logs` VALUES ('17', '1', 'add', '1422257922', 'Thêm mới bản ghi 3 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('18', '1', 'trash', '1422257926', 'Xóa bản ghi 3 từ bảng service_desks');
INSERT INTO `admin_logs` VALUES ('19', '1', 'add', '1422257955', 'Thêm mới cửa hàng 2 bảng agencies');
INSERT INTO `admin_logs` VALUES ('20', '1', 'add', '1422257990', 'Thêm mới bản ghi 4 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('21', '1', 'add', '1422258306', 'Chỉnh sửa bản ghi 2 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('22', '1', 'add', '1422258311', 'Chỉnh sửa bản ghi 2 bảng service_desks');
INSERT INTO `admin_logs` VALUES ('23', '1', 'add', '1426836565', 'Thêm mới khu vực bàn ăn ID 3');
INSERT INTO `admin_logs` VALUES ('24', '1', 'add', '1426910254', 'Thêm mới danh mục 3 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('25', '1', 'add', '1426911099', 'Thêm mới danh mục 4 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('26', '1', 'add', '1426912948', 'Thêm mới danh mục 5 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('27', '1', 'add', '1426913763', 'Thêm mới danh mục 6 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('28', '1', 'edit', '1427080205', 'Sửa danh mục 4 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('29', '1', 'edit', '1427080214', 'Sửa danh mục 4 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('30', '1', 'trash', '1427080284', 'Xóa bản ghi 4 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('31', '1', 'trash', '1427080300', 'Xóa bản ghi 5 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('32', '1', 'trash', '1427080315', 'Xóa bản ghi 6 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('33', '1', 'add', '1427080336', 'Thêm mới danh mục 7 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('34', '1', 'add', '1427080364', 'Thêm mới danh mục 8 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('35', '1', 'add', '1427086646', 'Thêm mới bản ghi 0 bảng menus');
INSERT INTO `admin_logs` VALUES ('36', '1', 'add', '1427094367', 'Thêm mới bản ghi 0 bảng menus');
INSERT INTO `admin_logs` VALUES ('37', '1', 'add', '1427094441', 'Thêm mới bản ghi 0 bảng menus');
INSERT INTO `admin_logs` VALUES ('38', '1', 'add', '1427096154', 'Thêm mới bản ghi 0 bảng menus');
INSERT INTO `admin_logs` VALUES ('39', '1', 'add', '1427096446', 'Thêm mới bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('40', '1', 'add', '1427098016', 'Thêm mới bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('41', '1', 'add', '1427098164', 'Thêm mới bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('42', '1', 'add', '1427099836', 'Thêm mới bản ghi 5 bảng menus');
INSERT INTO `admin_logs` VALUES ('43', '1', 'add', '1427102300', 'Chỉnh sửa bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('44', '1', 'add', '1427102313', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('45', '1', 'add', '1427102596', 'Chỉnh sửa bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('46', '1', 'add', '1427102609', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('47', '1', 'add', '1427102843', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('48', '1', 'trash', '1427103032', 'Xóa bản ghi 1 từ bảng menus');
INSERT INTO `admin_logs` VALUES ('49', '1', 'add', '1427103466', 'Chỉnh sửa bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('50', '1', 'add', '1427103505', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('51', '1', 'add', '1427103541', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('52', '1', 'add', '1427103570', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('53', '1', 'add', '1427103846', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('54', '1', 'add', '1427103857', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('55', '1', 'add', '1427103877', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('56', '1', 'add', '1427104456', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('57', '1', 'add', '1427169134', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('58', '1', 'add', '1427169684', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('59', '1', 'add', '1427169862', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('60', '1', 'add', '1427170199', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('61', '1', 'add', '1427170327', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('62', '1', 'add', '1427170351', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('63', '1', 'add', '1427170362', 'Thêm mới danh mục 0 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('64', '1', 'add', '1427170489', 'Thêm mới danh mục 2 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('65', '1', 'add', '1427170513', 'Thêm mới danh mục 4 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('66', '1', 'add', '1427170530', 'Thêm mới danh mục 5 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('67', '1', 'add', '1427170540', 'Thêm mới danh mục 6 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('68', '1', 'add', '1427170543', 'Thêm mới danh mục 7 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('69', '1', 'add', '1427170549', 'Thêm mới danh mục 8 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('70', '1', 'trash', '1427171027', 'Xóa bản ghi 4 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('71', '1', 'trash', '1427171029', 'Xóa bản ghi 6 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('72', '1', 'trash', '1427171031', 'Xóa bản ghi 7 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('73', '1', 'trash', '1427171033', 'Xóa bản ghi 5 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('74', '1', 'trash', '1427171036', 'Xóa bản ghi 8 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('75', '1', 'edit', '1427171200', 'Sửa danh mục 2 bảng cus_cat_customer');
INSERT INTO `admin_logs` VALUES ('76', '1', 'edit', '1427172071', 'Sửa danh mục 2 bảng cus_cat_customer');
INSERT INTO `admin_logs` VALUES ('77', '1', 'add', '1427172094', 'Thêm mới danh mục 9 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('78', '1', 'edit', '1427172174', 'Sửa danh mục 2 bảng cus_cat_customer');
INSERT INTO `admin_logs` VALUES ('79', '1', 'add', '1427172187', 'Thêm mới danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('80', '1', 'edit', '1427172222', 'Sửa danh mục 10 bảng cus_cat_customer');
INSERT INTO `admin_logs` VALUES ('81', '1', 'add', '1427172232', 'Thêm mới danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('82', '1', 'add', '1427172659', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('83', '1', 'recovery', '1427182752', 'Khôi phục bản ghi 1 tới bảng menus');
INSERT INTO `admin_logs` VALUES ('84', '1', 'trash', '1427182783', 'Xóa bản ghi 1 từ bảng menus');
INSERT INTO `admin_logs` VALUES ('85', '1', 'trash', '1427182850', 'Xóa bản ghi 2 từ bảng menus');
INSERT INTO `admin_logs` VALUES ('86', '1', 'recovery', '1427182857', 'Khôi phục bản ghi 2 tới bảng menus');
INSERT INTO `admin_logs` VALUES ('87', '1', 'trash', '1427183855', 'Xóa bản ghi 3 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('88', '1', 'trash', '1427183965', 'Xóa bản ghi 10 từ bảng customer_cat');
INSERT INTO `admin_logs` VALUES ('89', '1', 'add', '1427185688', 'Thêm mới bản ghi 0 bảng customers');
INSERT INTO `admin_logs` VALUES ('90', '1', 'add', '1427185992', 'Thêm mới bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('91', '1', 'add', '1427187371', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('92', '1', 'add', '1427187613', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('93', '1', 'add', '1427187636', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('94', '1', 'add', '1427187651', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('95', '1', 'add', '1427187740', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('96', '1', 'add', '1427187807', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('97', '1', 'trash', '1427187864', 'Xóa bản ghi 1 từ bảng customers');
INSERT INTO `admin_logs` VALUES ('98', '1', 'recovery', '1427187871', 'Khôi phục bản ghi 1 tới bảng customers');
INSERT INTO `admin_logs` VALUES ('99', '1', 'add', '1427188905', 'Chỉnh sửa bản ghi 1 bảng customers');
INSERT INTO `admin_logs` VALUES ('100', '1', 'trash', '1427189739', 'Xóa bản ghi 1 từ bảng customers');
INSERT INTO `admin_logs` VALUES ('101', '1', 'recovery', '1427189751', 'Khôi phục bản ghi 1 tới bảng customers');
INSERT INTO `admin_logs` VALUES ('102', '1', 'add', '1427250884', 'Thêm mới danh mục 9 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('103', '1', 'trash', '1427250935', 'Xóa bản ghi 9 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('104', '1', 'add', '1427251162', 'Thêm mới danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('105', '1', 'add', '1427252811', 'Thêm mới danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('106', '1', 'add', '1427252852', 'Thêm mới bản ghi 0 bảng products');
INSERT INTO `admin_logs` VALUES ('107', '1', 'add', '1427253349', 'Thêm mới bản ghi 0 bảng products');
INSERT INTO `admin_logs` VALUES ('108', '1', 'add', '1427253558', 'Thêm mới bản ghi 0 bảng products');
INSERT INTO `admin_logs` VALUES ('109', '1', 'add', '1427253595', 'Thêm mới bản ghi 0 bảng products');
INSERT INTO `admin_logs` VALUES ('110', '1', 'add', '1427253680', 'Thêm mới bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('111', '1', 'add', '1427254271', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('112', '1', 'add', '1427254311', 'Thêm mới bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('113', '1', 'trash', '1427254420', 'Xóa bản ghi 2 từ bảng products');
INSERT INTO `admin_logs` VALUES ('114', '1', 'recovery', '1427254425', 'Khôi phục bản ghi 2 tới bảng products');
INSERT INTO `admin_logs` VALUES ('115', '1', 'edit', '1427255886', 'Sửa danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('116', '1', 'edit', '1427256009', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('117', '1', 'edit', '1427256019', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('118', '1', 'edit', '1427256165', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('119', '1', 'edit', '1427256624', 'Sửa danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('120', '1', 'edit', '1427256716', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('121', '1', 'edit', '1427256725', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('122', '1', 'add', '1427256836', 'Thêm mới danh mục 12 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('123', '1', 'edit', '1427256908', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('124', '1', 'edit', '1427256945', 'Sửa danh mục 11 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('125', '1', 'trash', '1427268318', 'Xóa bản ghi 11 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('126', '1', 'add', '1427268341', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('127', '1', 'add', '1427268367', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('128', '1', 'edit', '1427268433', 'Sửa danh mục 12 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('129', '1', 'add', '1427268757', 'Thêm mới danh mục 13 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('130', '1', 'add', '1427268880', 'Thêm mới danh mục 14 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('131', '1', 'add', '1427268896', 'Thêm mới danh mục 15 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('132', '1', 'trash', '1427269036', 'Xóa bản ghi 14 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('133', '1', 'trash', '1427269038', 'Xóa bản ghi 13 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('134', '1', 'add', '1427269125', 'Thêm mới danh mục 16 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('135', '1', 'trash', '1427269132', 'Xóa bản ghi 16 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('136', '1', 'trash', '1427269135', 'Xóa bản ghi 15 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('137', '1', 'edit', '1427269156', 'Sửa danh mục 12 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('138', '1', 'edit', '1427269192', 'Sửa danh mục 10 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('139', '1', 'trash', '1427269240', 'Xóa bản ghi 10 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('140', '1', 'trash', '1427269246', 'Xóa bản ghi 12 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('141', '1', 'add', '1427269265', 'Thêm mới danh mục 17 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('142', '1', 'add', '1427269277', 'Thêm mới danh mục 18 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('143', '1', 'add', '1427269342', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('144', '1', 'add', '1427269349', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('145', '1', 'add', '1427271161', 'Thêm mới danh mục 19 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('146', '1', 'add', '1427271291', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('147', '1', 'add', '1427271429', 'Thêm mới danh mục 20 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('148', '1', 'add', '1427271440', 'Thêm mới danh mục 21 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('149', '1', 'add', '1427271446', 'Thêm mới danh mục 22 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('150', '1', 'add', '1427273141', 'Thêm mới bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('151', '1', 'add', '1427273190', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('152', '1', 'add', '1427273212', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('153', '1', 'add', '1427273816', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('154', '1', 'add', '1427273855', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('155', '1', 'trash', '1427273869', 'Xóa bản ghi 3 từ bảng products');
INSERT INTO `admin_logs` VALUES ('156', '1', 'recovery', '1427273875', 'Khôi phục bản ghi 3 tới bảng products');
INSERT INTO `admin_logs` VALUES ('157', '1', 'add', '1427277277', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('158', '1', 'add', '1427339389', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('159', '1', 'add', '1427345374', 'Thêm mới danh mục 23 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('160', '1', 'edit', '1427345399', 'Sửa danh mục 23 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('161', '1', 'edit', '1427345404', 'Sửa danh mục 23 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('162', '1', 'trash', '1427345503', 'Xóa bản ghi 23 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('163', '1', 'add', '1427346216', 'Thêm mới danh mục 24 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('164', '1', 'add', '1427353997', 'Thêm mới bản ghi 0 bảng users');
INSERT INTO `admin_logs` VALUES ('165', '1', 'add', '1427356384', 'Thêm mới bản ghi 2 bảng users');
INSERT INTO `admin_logs` VALUES ('166', '1', 'add', '1427356405', 'Thêm mới bản ghi 3 bảng users');
INSERT INTO `admin_logs` VALUES ('167', '1', 'recovery', '1427421594', 'Khôi phục bản ghi 1 tới bảng menus');
INSERT INTO `admin_logs` VALUES ('168', '1', 'trash', '1427421602', 'Xóa bản ghi 1 từ bảng menus');
INSERT INTO `admin_logs` VALUES ('169', '1', 'add', '1427432023', 'Thêm mới bản ghi 6 bảng menus');
INSERT INTO `admin_logs` VALUES ('170', '1', 'add', '1427517243', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('171', '1', 'add', '1427517262', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('172', '1', 'add', '1427548511', 'Thêm mới bản ghi 7 bảng menus');
INSERT INTO `admin_logs` VALUES ('173', '1', 'add', '1427608716', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('174', '1', 'add', '1427608755', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('175', '1', 'add', '1427608790', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('176', '1', 'add', '1427608828', 'Thêm mới bản ghi 4 bảng products');
INSERT INTO `admin_logs` VALUES ('177', '1', 'add', '1427608849', 'Thêm mới bản ghi 5 bảng products');
INSERT INTO `admin_logs` VALUES ('178', '1', 'add', '1427608866', 'Thêm mới bản ghi 6 bảng products');
INSERT INTO `admin_logs` VALUES ('179', '1', 'add', '1427608883', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('180', '1', 'add', '1427608907', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('181', '1', 'add', '1427608925', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('182', '1', 'add', '1427621591', 'Thêm nguyên liệu pro_id 1 số lượng 1 vào thực đơn 3');
INSERT INTO `admin_logs` VALUES ('183', '1', 'add', '1427621736', 'Thêm nguyên liệu pro_id 4 số lượng 1 vào thực đơn 3');
INSERT INTO `admin_logs` VALUES ('184', '1', 'edit', '1427644225', 'Chỉnh nguyên liệu pro_id 4 số lượng 0.5 vào thực đơn 3');
INSERT INTO `admin_logs` VALUES ('185', '1', 'add', '1427644268', 'Thêm nguyên liệu pro_id 4 số lượng 1 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('186', '1', 'trash', '1427644522', 'Xóa bản ghi 7 từ bảng menus');
INSERT INTO `admin_logs` VALUES ('187', '1', 'add', '1427644543', 'Thêm nguyên liệu pro_id 5 số lượng 2 vào thực đơn 2');
INSERT INTO `admin_logs` VALUES ('188', '1', 'add', '1427644559', 'Thêm nguyên liệu pro_id 6 số lượng 3.5 vào thực đơn 5');
INSERT INTO `admin_logs` VALUES ('189', '1', 'add', '1427644571', 'Thêm nguyên liệu pro_id 2 số lượng 2 vào thực đơn 4');
INSERT INTO `admin_logs` VALUES ('190', '1', 'add', '1427644580', 'Thêm nguyên liệu pro_id 2 số lượng 2 vào thực đơn 3');
INSERT INTO `admin_logs` VALUES ('191', '1', 'add', '1427686222', 'Thêm mới danh mục 25 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('192', '1', 'add', '1427698016', 'Thêm mới danh mục 26 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('193', '1', 'add', '1427698076', 'Thêm mới danh mục 27 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('194', '1', 'add', '1427698106', 'Thêm mới danh mục 28 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('195', '1', 'add', '1427700350', 'Chỉnh sửa bản ghi 26 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('196', '1', 'add', '1427700437', 'Thêm mới danh mục 29 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('197', '1', 'add', '1427767882', 'Thêm mới danh mục 34 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('198', '1', 'add', '1427771618', 'Thêm mới bản ghi 4 bảng users');
INSERT INTO `admin_logs` VALUES ('199', '1', 'trash', '1427771650', 'Xóa bản ghi 4 từ bảng users');
INSERT INTO `admin_logs` VALUES ('200', '1', 'add', '1427771771', 'Chỉnh sửa bản ghi 1 bảng users');
INSERT INTO `admin_logs` VALUES ('201', '1', 'add', '1427771845', 'Chỉnh sửa bản ghi 1 bảng users');
INSERT INTO `admin_logs` VALUES ('202', '1', 'add', '1427771888', 'Chỉnh sửa bản ghi 2 bảng users');
INSERT INTO `admin_logs` VALUES ('203', '1', 'add', '1427771925', 'Chỉnh sửa bản ghi 3 bảng users');
INSERT INTO `admin_logs` VALUES ('204', '1', 'delete', '1427789269', 'Xóa hoàn toàn bản ghi 7 của bảng menus');
INSERT INTO `admin_logs` VALUES ('205', '1', 'add', '1427790438', 'Thêm mới phiếu thu 1 bảng financial');
INSERT INTO `admin_logs` VALUES ('206', '1', 'add', '1427815979', 'Thêm mới danh mục 35 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('207', '1', 'add', '1427816294', 'Thêm mới phiếu chi 2 bảng financial');
INSERT INTO `admin_logs` VALUES ('208', '1', 'add', '1427816433', 'Thêm mới danh mục 36 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('209', '1', 'add', '1427816614', 'Thêm mới phiếu chi 3 bảng financial');
INSERT INTO `admin_logs` VALUES ('210', '1', 'add', '1427938317', 'Thêm mới phiếu thu 4 bảng financial');
INSERT INTO `admin_logs` VALUES ('211', '1', 'trash', '1427942135', 'Xóa bản ghi 4 từ bảng financial');
INSERT INTO `admin_logs` VALUES ('212', '1', 'add', '1428049365', 'Thêm mới phiếu chi 5 bảng financial');
INSERT INTO `admin_logs` VALUES ('213', '1', 'recovery', '1428055880', 'Khôi phục bản ghi 4 tới bảng financial');
INSERT INTO `admin_logs` VALUES ('214', '1', 'trash', '1428055902', 'Xóa bản ghi 4 từ bảng financial');
INSERT INTO `admin_logs` VALUES ('215', '1', 'delete', '1428056701', 'Xóa hoàn toàn bản ghi 4 của bảng financial');
INSERT INTO `admin_logs` VALUES ('216', '1', 'trash', '1428066820', 'Xóa bản ghi 0 từ bảng sections');
INSERT INTO `admin_logs` VALUES ('217', '1', 'trash', '1428066828', 'Xóa bản ghi 0 từ bảng sections');
INSERT INTO `admin_logs` VALUES ('218', '1', 'edit', '1428395594', 'Chỉnh sửa bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('219', '1', 'edit', '1428395627', 'Chỉnh sửa bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('220', '1', 'add', '1428658563', 'Tạo bàn ID: 6');
INSERT INTO `admin_logs` VALUES ('221', '1', 'add', '1428658565', 'Thêm thực đơn ID: 2 vào bàn ID: 6');
INSERT INTO `admin_logs` VALUES ('222', '1', 'add', '1428658610', 'Thêm thực đơn ID: 2 vào bàn ID: 6');
INSERT INTO `admin_logs` VALUES ('223', '1', 'add', '1428734030', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('224', '1', 'add', '1428909156', 'Thêm thực đơn ID: 6 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('225', '1', 'add', '1428909160', 'Thêm thực đơn ID: 5 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('226', '1', 'add', '1428909173', 'Thêm thực đơn ID: 3 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('227', '1', 'add', '1428909245', 'Thêm thực đơn ID: 6 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('228', '1', 'add', '1428909415', 'Thêm thực đơn ID: 4 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('229', '1', 'add', '1428909446', 'Thêm thực đơn ID: 4 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('230', '1', 'add', '1428909450', 'Thêm thực đơn ID: 4 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('231', '1', 'add', '1428909464', 'Thêm thực đơn ID: 3 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('232', '1', 'add', '1428911047', 'Thêm thực đơn ID: 3 vào bàn ID: 6');
INSERT INTO `admin_logs` VALUES ('233', '1', 'edit', '1428917471', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('234', '1', 'edit', '1428917476', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('235', '1', 'edit', '1428917478', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('236', '1', 'edit', '1428917606', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('237', '1', 'edit', '1428917608', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('238', '1', 'edit', '1428917609', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('239', '1', 'edit', '1428917614', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('240', '1', 'edit', '1428917615', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('241', '1', 'edit', '1428917616', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('242', '1', 'edit', '1428917617', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('243', '1', 'edit', '1428917618', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('244', '1', 'add', '1428917701', 'Thêm thực đơn ID: 2 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('245', '1', 'edit', '1428973456', 'Thay đổi lựa chọn khách hàng ID (1) bàn ID 10');
INSERT INTO `admin_logs` VALUES ('246', '1', 'add', '1428973714', 'Tạo bàn ID: 7');
INSERT INTO `admin_logs` VALUES ('247', '1', 'edit', '1428977025', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('248', '1', 'edit', '1428977052', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('249', '1', 'edit', '1428977128', 'Cập nhật chọn loại giá men_price1 thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('250', '1', 'edit', '1428977139', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('251', '1', 'edit', '1428977141', 'Cập nhật chọn loại giá men_price1 thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('252', '1', 'edit', '1428977142', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('253', '1', 'edit', '1428977143', 'Cập nhật chọn loại giá men_price1 thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('254', '1', 'edit', '1428977151', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('255', '1', 'edit', '1428977151', 'Cập nhật chọn loại giá men_price1 thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('256', '1', 'edit', '1428977219', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('257', '1', 'edit', '1428977220', 'Cập nhật chọn loại giá men_price1 thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('258', '1', 'edit', '1428977220', 'Cập nhật chọn loại giá men_price thực đơn ID3 bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('259', '1', 'add', '1428979053', 'Thêm thực đơn ID: 4 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('260', '1', 'add', '1429018518', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('261', '1', 'add', '1429018526', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('262', '1', 'add', '1429018532', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('263', '1', 'add', '1429018538', 'Chỉnh sửa bản ghi 4 bảng products');
INSERT INTO `admin_logs` VALUES ('264', '1', 'add', '1429018548', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('265', '1', 'add', '1429018552', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('266', '1', 'add', '1429018556', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('267', '1', 'add', '1429018724', 'Thêm mới danh mục 37 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('268', '1', 'trash', '1429018888', 'Xóa bản ghi 21 từ bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('269', '1', 'edit', '1429018931', 'Sửa danh mục 37 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('270', '1', 'add', '1429018970', 'Thêm mới danh mục 38 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('271', '1', 'add', '1429019069', 'Thêm mới bản ghi 7 bảng products');
INSERT INTO `admin_logs` VALUES ('272', '1', 'add', '1429019108', 'Chỉnh sửa bản ghi 1 bảng products');
INSERT INTO `admin_logs` VALUES ('273', '1', 'add', '1429019147', 'Chỉnh sửa bản ghi 2 bảng products');
INSERT INTO `admin_logs` VALUES ('274', '1', 'add', '1429019224', 'Thêm mới danh mục 39 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('275', '1', 'edit', '1429019348', 'Sửa danh mục 22 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('276', '1', 'add', '1429019405', 'Thêm mới danh mục 40 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('277', '1', 'add', '1429019450', 'Chỉnh sửa bản ghi 3 bảng products');
INSERT INTO `admin_logs` VALUES ('278', '1', 'add', '1429020185', 'Chỉnh sửa bản ghi 6 bảng products');
INSERT INTO `admin_logs` VALUES ('279', '1', 'add', '1429020214', 'Chỉnh sửa bản ghi 4 bảng products');
INSERT INTO `admin_logs` VALUES ('280', '1', 'add', '1429020250', 'Chỉnh sửa bản ghi 5 bảng products');
INSERT INTO `admin_logs` VALUES ('281', '1', 'add', '1429020329', 'Thêm mới bản ghi 8 bảng products');
INSERT INTO `admin_logs` VALUES ('282', '1', 'edit', '1429020343', 'Sửa danh mục 20 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('283', '1', 'add', '1429020538', 'Thêm mới bản ghi 9 bảng products');
INSERT INTO `admin_logs` VALUES ('284', '1', 'add', '1429020580', 'Thêm mới bản ghi 10 bảng products');
INSERT INTO `admin_logs` VALUES ('285', '1', 'add', '1429020595', 'Chỉnh sửa bản ghi 10 bảng products');
INSERT INTO `admin_logs` VALUES ('286', '1', 'add', '1429020652', 'Thêm mới bản ghi 11 bảng products');
INSERT INTO `admin_logs` VALUES ('287', '1', 'add', '1429021432', 'Thêm mới bản ghi 12 bảng products');
INSERT INTO `admin_logs` VALUES ('288', '1', 'add', '1429021607', 'Thêm mới bản ghi 1 bảng menus');
INSERT INTO `admin_logs` VALUES ('289', '1', 'add', '1429021662', 'Thêm nguyên liệu pro_id 2 số lượng 1 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('290', '1', 'add', '1429021672', 'Thêm nguyên liệu pro_id 3 số lượng 1 vào thực đơn 1');
INSERT INTO `admin_logs` VALUES ('291', '1', 'add', '1429021735', 'Thêm mới bản ghi 13 bảng products');
INSERT INTO `admin_logs` VALUES ('292', '1', 'recovery', '1429021748', 'Khôi phục bản ghi 1 tới bảng menus');
INSERT INTO `admin_logs` VALUES ('293', '1', 'add', '1429021858', 'Thêm mới bản ghi 2 bảng menus');
INSERT INTO `admin_logs` VALUES ('294', '1', 'add', '1429021868', 'Thêm nguyên liệu pro_id 7 số lượng 1 vào thực đơn 2');
INSERT INTO `admin_logs` VALUES ('295', '1', 'add', '1429021931', 'Thêm mới bản ghi 3 bảng menus');
INSERT INTO `admin_logs` VALUES ('296', '1', 'add', '1429021965', 'Thêm nguyên liệu pro_id 1 số lượng 1 vào thực đơn 3');
INSERT INTO `admin_logs` VALUES ('297', '1', 'add', '1429022027', 'Thêm mới danh mục 41 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('298', '1', 'add', '1429022069', 'Thêm mới bản ghi 14 bảng products');
INSERT INTO `admin_logs` VALUES ('299', '1', 'add', '1429022124', 'Thêm mới bản ghi 15 bảng products');
INSERT INTO `admin_logs` VALUES ('300', '1', 'add', '1429022170', 'Thêm mới bản ghi 4 bảng menus');
INSERT INTO `admin_logs` VALUES ('301', '1', 'add', '1429022178', 'Thêm nguyên liệu pro_id 14 số lượng 1 vào thực đơn 4');
INSERT INTO `admin_logs` VALUES ('302', '1', 'add', '1429022240', 'Thêm mới bản ghi 5 bảng menus');
INSERT INTO `admin_logs` VALUES ('303', '1', 'add', '1429022247', 'Thêm nguyên liệu pro_id 15 số lượng 1 vào thực đơn 5');
INSERT INTO `admin_logs` VALUES ('304', '1', 'add', '1429022284', 'Thêm mới danh mục 42 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('305', '1', 'add', '1429022329', 'Thêm mới bản ghi 6 bảng menus');
INSERT INTO `admin_logs` VALUES ('306', '1', 'add', '1429022353', 'Thêm mới danh mục 43 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('307', '1', 'add', '1429022364', 'Thêm mới danh mục 44 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('308', '1', 'add', '1429022402', 'Thêm mới bản ghi 16 bảng products');
INSERT INTO `admin_logs` VALUES ('309', '1', 'add', '1429022415', 'Thêm mới bản ghi 17 bảng products');
INSERT INTO `admin_logs` VALUES ('310', '1', 'add', '1429022429', 'Thêm mới bản ghi 18 bảng products');
INSERT INTO `admin_logs` VALUES ('311', '1', 'add', '1429022444', 'Thêm mới bản ghi 19 bảng products');
INSERT INTO `admin_logs` VALUES ('312', '1', 'add', '1429022478', 'Thêm mới bản ghi 20 bảng products');
INSERT INTO `admin_logs` VALUES ('313', '1', 'add', '1429022521', 'Thêm nguyên liệu pro_id 13 số lượng 0.5 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('314', '1', 'add', '1429022535', 'Thêm nguyên liệu pro_id 17 số lượng 0.05 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('315', '1', 'add', '1429022547', 'Thêm nguyên liệu pro_id 18 số lượng 0.05 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('316', '1', 'add', '1429022556', 'Thêm nguyên liệu pro_id 19 số lượng 0.01 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('317', '1', 'add', '1429022565', 'Thêm nguyên liệu pro_id 20 số lượng 0.01 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('318', '1', 'add', '1429022631', 'Thêm mới bản ghi 21 bảng products');
INSERT INTO `admin_logs` VALUES ('319', '1', 'add', '1429022688', 'Thêm nguyên liệu pro_id 9 số lượng 0.05 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('320', '1', 'add', '1429022701', 'Thêm nguyên liệu pro_id 10 số lượng 0.05 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('321', '1', 'add', '1429022748', 'Thêm mới bản ghi 22 bảng products');
INSERT INTO `admin_logs` VALUES ('322', '1', 'add', '1429022761', 'Thêm nguyên liệu pro_id 22 số lượng 0.02 vào thực đơn 6');
INSERT INTO `admin_logs` VALUES ('323', '1', 'add', '1429022821', 'Thêm mới bản ghi 7 bảng menus');
INSERT INTO `admin_logs` VALUES ('324', '1', 'add', '1429022829', 'Thêm nguyên liệu pro_id 5 số lượng 0.8 vào thực đơn 7');
INSERT INTO `admin_logs` VALUES ('325', '1', 'add', '1429022839', 'Thêm nguyên liệu pro_id 20 số lượng 0.01 vào thực đơn 7');
INSERT INTO `admin_logs` VALUES ('326', '1', 'add', '1429022849', 'Thêm nguyên liệu pro_id 22 số lượng 0.05 vào thực đơn 7');
INSERT INTO `admin_logs` VALUES ('327', '1', 'add', '1429023570', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('328', '1', 'add', '1429023581', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('329', '1', 'add', '1429023634', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('330', '1', 'add', '1429023636', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('331', '1', 'add', '1429023637', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('332', '1', 'add', '1429023639', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('333', '1', 'add', '1429023640', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('334', '1', 'add', '1429023641', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('335', '1', 'add', '1429023644', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('336', '1', 'add', '1429023646', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('337', '1', 'add', '1429023647', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('338', '1', 'add', '1429023651', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('339', '1', 'add', '1429023652', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('340', '1', 'add', '1429023654', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('341', '1', 'add', '1429023656', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('342', '1', 'add', '1429023657', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('343', '1', 'edit', '1429023663', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('344', '1', 'edit', '1429023665', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('345', '1', 'edit', '1429023666', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('346', '1', 'edit', '1429023668', 'Cập nhật chọn loại giá men_price thực đơn ID2 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('347', '1', 'edit', '1429023669', 'Cập nhật chọn loại giá men_price1 thực đơn ID2 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('348', '1', 'add', '1429023754', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('349', '1', 'add', '1429023757', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('350', '1', 'add', '1429023759', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('351', '1', 'add', '1429023760', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('352', '1', 'edit', '1429023765', 'Cập nhật chọn loại giá men_price1 thực đơn ID5 bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('353', '1', 'add', '1429033742', 'Tạo bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('354', '1', 'add', '1429033744', 'Thêm thực đơn ID: 3 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('355', '1', 'add', '1429033745', 'Thêm thực đơn ID: 2 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('356', '1', 'add', '1429033747', 'Thêm thực đơn ID: 1 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('357', '1', 'add', '1429033757', 'Thêm thực đơn ID: 1 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('358', '1', 'add', '1429105086', 'Thêm mới danh mục 45 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('359', '1', 'add', '1429105092', 'Thêm mới danh mục 46 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('360', '1', 'add', '1429108117', 'Thêm mới bản ghi 23 bảng products');
INSERT INTO `admin_logs` VALUES ('361', '1', 'add', '1429108375', 'Thêm mới bản ghi 24 bảng products');
INSERT INTO `admin_logs` VALUES ('362', '1', 'edit', '1429158602', 'Thay đổi lựa chọn khách hàng ID (1) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('363', '1', 'edit', '1429158607', 'Thay đổi nhân viên phục vụ ID (2) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('364', '1', 'add', '1429178077', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('365', '1', 'add', '1429178088', 'Thêm thực đơn ID: 4 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('366', '1', 'add', '1429178228', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('367', '1', 'add', '1429178233', 'Thêm thực đơn ID: 1 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('368', '1', 'add', '1429178234', 'Thêm thực đơn ID: 1 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('369', '1', 'edit', '1429178238', 'Thay đổi số lượng thực đơn ID (1) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('370', '1', 'add', '1429178243', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('371', '1', 'add', '1429178245', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('372', '1', 'edit', '1429178254', 'Thay đổi số lượng thực đơn ID (6) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('373', '1', 'edit', '1429178263', 'Thay đổi số lượng thực đơn ID (4) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('374', '1', 'edit', '1429178269', 'Thay đổi số lượng thực đơn ID (2) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('375', '1', 'add', '1429179688', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('376', '1', 'edit', '1429179691', 'Thay đổi lựa chọn khách hàng ID (1) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('377', '1', 'edit', '1429179755', 'Thay đổi nhân viên phục vụ ID (3) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('378', '1', 'edit', '1429180068', 'Thay đổi lựa chọn khách hàng ID (1) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('379', '1', 'edit', '1429180178', 'Thay đổi nhân viên phục vụ ID (2) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('380', '1', 'edit', '1429180183', 'Thay đổi nhân viên phục vụ ID (3) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('381', '1', 'edit', '1429180220', 'Thay đổi nhân viên phục vụ ID (1) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('382', '1', 'edit', '1429180246', 'Thay đổi nhân viên phục vụ ID (2) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('383', '1', 'add', '1429180256', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('384', '1', 'add', '1429180486', 'Thêm thực đơn ID: 4 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('385', '1', 'add', '1429180487', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('386', '1', 'add', '1429180489', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('387', '1', 'add', '1429180490', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('388', '1', 'add', '1429180492', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('389', '1', 'add', '1429180493', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('390', '1', 'add', '1429180495', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('391', '1', 'edit', '1429180499', 'Thay đổi số lượng thực đơn ID (2) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('392', '1', 'add', '1429195954', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('393', '1', 'add', '1429328628', 'Thêm thực đơn ID: 3 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('394', '1', 'add', '1429328764', 'Thêm thực đơn ID: 2 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('395', '1', 'add', '1429328765', 'Thêm thực đơn ID: 6 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('396', '1', 'add', '1429328767', 'Thêm thực đơn ID: 7 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('397', '1', 'add', '1429328769', 'Thêm thực đơn ID: 5 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('398', '1', 'add', '1429328770', 'Thêm thực đơn ID: 4 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('399', '1', 'add', '1429328772', 'Thêm thực đơn ID: 1 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('400', '1', 'add', '1429332334', 'Tạo bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('401', '1', 'edit', '1429332343', 'Thay đổi nhân viên phục vụ ID (2) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('402', '1', 'add', '1429332346', 'Thêm thực đơn ID: 1 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('403', '1', 'add', '1429332348', 'Thêm thực đơn ID: 2 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('404', '1', 'add', '1429332349', 'Thêm thực đơn ID: 4 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('405', '1', 'add', '1429332349', 'Thêm thực đơn ID: 6 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('406', '1', 'add', '1429332351', 'Thêm thực đơn ID: 7 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('407', '1', 'add', '1429332352', 'Thêm thực đơn ID: 6 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('408', '1', 'add', '1429332352', 'Thêm thực đơn ID: 4 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('409', '1', 'add', '1429332356', 'Thêm thực đơn ID: 3 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('410', '1', 'add', '1429332364', 'Thêm thực đơn ID: 3 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('411', '1', 'add', '1429332365', 'Thêm thực đơn ID: 3 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('412', '1', 'edit', '1429332673', 'Cập nhật chọn loại giá men_price1 thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('413', '1', 'edit', '1429332673', 'Cập nhật chọn loại giá men_price2 thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('414', '1', 'edit', '1429332674', 'Cập nhật chọn loại giá men_price1 thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('415', '1', 'edit', '1429332675', 'Cập nhật chọn loại giá men_price thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('416', '1', 'edit', '1429332702', 'Cập nhật chọn loại giá men_price1 thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('417', '1', 'edit', '1429332703', 'Cập nhật chọn loại giá men_price thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('418', '1', 'edit', '1429332904', 'Thay đổi số lượng thực đơn ID (1) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('419', '1', 'add', '1429340672', 'Tạo bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('420', '1', 'add', '1429340678', 'Tạo bàn ID: 8');
INSERT INTO `admin_logs` VALUES ('421', '1', 'add', '1429340680', 'Tạo bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('422', '1', 'add', '1429522899', 'Thêm thực đơn ID: 2 vào bàn ID: 8');
INSERT INTO `admin_logs` VALUES ('423', '1', 'edit', '1429522905', 'Thay đổi số lượng thực đơn ID (2) bàn ID 8');
INSERT INTO `admin_logs` VALUES ('424', '1', 'edit', '1429522913', 'Thay đổi lựa chọn khách hàng ID (1) bàn ID 8');
INSERT INTO `admin_logs` VALUES ('425', '1', 'edit', '1429522916', 'Thay đổi nhân viên phục vụ ID (3) bàn ID 8');
INSERT INTO `admin_logs` VALUES ('426', '1', 'add', '1429524382', 'Thêm thực đơn ID: 2 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('427', '1', 'edit', '1429612300', 'Thay đổi số lượng thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('428', '1', 'edit', '1429612302', 'Thay đổi số lượng thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('429', '1', 'edit', '1429612305', 'Thay đổi số lượng thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('430', '1', 'edit', '1429612321', 'Thay đổi giảm giá thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('431', '1', 'edit', '1429612332', 'Thay đổi giảm giá thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('432', '1', 'edit', '1429612341', 'Thay đổi giảm giá thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('433', '1', 'edit', '1429612351', 'Thay đổi giảm giá thực đơn ID (4) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('434', '1', 'add', '1429776596', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('435', '1', 'add', '1429776606', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('436', '1', 'add', '1429849035', 'Thêm mới danh mục 47 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('437', '1', 'add', '1429849104', 'Thêm mới bản ghi 26 bảng products');
INSERT INTO `admin_logs` VALUES ('438', '1', 'trash', '1429849133', 'Xóa bản ghi 26 từ bảng products');
INSERT INTO `admin_logs` VALUES ('439', '1', 'add', '1429849183', 'Thêm mới danh mục 48 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('440', '1', 'add', '1429849265', 'Thêm mới bản ghi 27 bảng products');
INSERT INTO `admin_logs` VALUES ('441', '1', 'add', '1429849396', 'Thêm mới bản ghi 28 bảng products');
INSERT INTO `admin_logs` VALUES ('442', '1', 'add', '1429849532', 'Thêm mới bản ghi 29 bảng products');
INSERT INTO `admin_logs` VALUES ('443', '1', 'add', '1429849562', 'Chỉnh sửa bản ghi 28 bảng products');
INSERT INTO `admin_logs` VALUES ('444', '1', 'add', '1429849638', 'Thêm mới bản ghi 30 bảng products');
INSERT INTO `admin_logs` VALUES ('445', '1', 'add', '1429849802', 'Thêm mới bản ghi 31 bảng products');
INSERT INTO `admin_logs` VALUES ('446', '1', 'add', '1429849885', 'Thêm mới bản ghi 32 bảng products');
INSERT INTO `admin_logs` VALUES ('447', '1', 'add', '1429849963', 'Thêm mới bản ghi 33 bảng products');
INSERT INTO `admin_logs` VALUES ('448', '1', 'add', '1429850066', 'Thêm mới bản ghi 34 bảng products');
INSERT INTO `admin_logs` VALUES ('449', '1', 'add', '1429850145', 'Thêm mới bản ghi 8 bảng menus');
INSERT INTO `admin_logs` VALUES ('450', '1', 'add', '1429850202', 'Thêm nguyên liệu pro_id 13 số lượng 0.5 vào thực đơn 8');
INSERT INTO `admin_logs` VALUES ('451', '1', 'add', '1429850228', 'Thêm nguyên liệu pro_id 16 số lượng 0.1 vào thực đơn 8');
INSERT INTO `admin_logs` VALUES ('452', '1', 'add', '1429850278', 'Thêm mới bản ghi 9 bảng menus');
INSERT INTO `admin_logs` VALUES ('453', '1', 'add', '1429850334', 'Thêm nguyên liệu pro_id 27 số lượng 0.3 vào thực đơn 9');
INSERT INTO `admin_logs` VALUES ('454', '1', 'add', '1429850365', 'Thêm mới bản ghi 10 bảng menus');
INSERT INTO `admin_logs` VALUES ('455', '1', 'add', '1429850437', 'Thêm mới bản ghi 11 bảng menus');
INSERT INTO `admin_logs` VALUES ('456', '1', 'add', '1429850484', 'Thêm mới danh mục 49 bảng categories_multi');
INSERT INTO `admin_logs` VALUES ('457', '1', 'add', '1429850550', 'Thêm mới bản ghi 12 bảng menus');
INSERT INTO `admin_logs` VALUES ('458', '1', 'add', '1429850601', 'Thêm mới bản ghi 13 bảng menus');
INSERT INTO `admin_logs` VALUES ('459', '1', 'add', '1429850668', 'Thêm mới bản ghi 14 bảng menus');
INSERT INTO `admin_logs` VALUES ('460', '1', 'add', '1429850688', 'Thêm thực đơn ID: 13 vào bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('461', '1', 'add', '1429850700', 'Thêm thực đơn ID: 13 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('462', '1', 'add', '1429850802', 'Thêm mới bản ghi 15 bảng menus');
INSERT INTO `admin_logs` VALUES ('463', '1', 'add', '1429850861', 'Thêm mới bản ghi 16 bảng menus');
INSERT INTO `admin_logs` VALUES ('464', '1', 'add', '1429850991', 'Thêm mới bản ghi 17 bảng menus');
INSERT INTO `admin_logs` VALUES ('465', '1', 'add', '1429851173', 'Thêm thực đơn ID: 13 vào bàn ID: 2');
INSERT INTO `admin_logs` VALUES ('466', '1', 'edit', '1429851178', 'Thay đổi số lượng thực đơn ID (13) bàn ID 2');
INSERT INTO `admin_logs` VALUES ('467', '1', 'edit', '1429859721', 'Cập nhật chọn loại giá men_price1 thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('468', '1', 'edit', '1429859721', 'Cập nhật chọn loại giá men_price thực đơn ID1 bàn ID: 5');
INSERT INTO `admin_logs` VALUES ('469', '1', 'edit', '1429859731', 'Thay đổi giảm giá thực đơn ID (1) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('470', '1', 'edit', '1429859737', 'Thay đổi giảm giá thực đơn ID (1) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('471', '1', 'edit', '1429859742', 'Thay đổi giảm giá thực đơn ID (1) bàn ID 5');
INSERT INTO `admin_logs` VALUES ('472', '1', 'add', '1429861094', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('473', '1', 'add', '1429861098', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('474', '1', 'add', '1429861419', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('475', '1', 'add', '1429861687', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('476', '1', 'add', '1429861693', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('477', '1', 'add', '1429861732', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('478', '1', 'add', '1429861802', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('479', '1', 'add', '1429861948', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('480', '1', 'add', '1429861953', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('481', '1', 'add', '1429861999', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('482', '1', 'add', '1429862041', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('483', '1', 'add', '1429862063', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('484', '1', 'add', '1429862152', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('485', '1', 'add', '1429862154', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('486', '1', 'add', '1429862160', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('487', '1', 'add', '1429862207', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('488', '1', 'add', '1429862264', 'Cập nhật hệ thống cài đặt chung');
INSERT INTO `admin_logs` VALUES ('489', '1', 'add', '1429862754', 'Thêm thực đơn ID: 9 vào bàn ID: 10');
INSERT INTO `admin_logs` VALUES ('490', '1', 'add', '1429862755', 'Thêm thực đơn ID: 10 vào bàn ID: 10');

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
  PRIMARY KEY (`adm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 'Cửa hàng trưởng', '', null, '1', '1', '');
INSERT INTO `admin_users` VALUES ('5', 'phucvu', 'e10adc3949ba59abbe56e057f20f883e', '', 'Phục vụ bàn', '', '0', '0', '12', '');

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
INSERT INTO `admin_users_groups` VALUES ('12', 'Bán hàng', '0', 'Phụ trách bán hàng');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agencies
-- ----------------------------
INSERT INTO `agencies` VALUES ('1', 'Cơ sở chính', '18/1333 Giải Phóng, Hoàng Mai, Hà Nội', '0988165567', 'ipj1422247127.png', '');
INSERT INTO `agencies` VALUES ('2', 'Cơ sở Hai Bà Trưng', '51 Lê Đại Hành, Hai Bà Trưng Hà Nội', '0988165567', null, '');

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
  PRIMARY KEY (`bii_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_in
-- ----------------------------
INSERT INTO `bill_in` VALUES ('1', '1429195954', '1429332555', '2', '45', '0', '0', '1', '1', '0', '0', '0', '0', '610000', '610000', '1');
INSERT INTO `bill_in` VALUES ('2', '1429340678', '1429523881', '8', '45', '1', '3', '1', '1', '0', '0', '0', '0', '200000', '200000', '1');
INSERT INTO `bill_in` VALUES ('3', '1429332334', '1429859685', '5', '45', '0', '2', '1', '1', '0', '0', '0', '0', '1340000', '1340000', '1');

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
INSERT INTO `bill_in_detail` VALUES ('1', '1', '1', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '2', '1', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '3', '1', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '4', '1', '10000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '5', '1', '10000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '6', '1', '250000', '0');
INSERT INTO `bill_in_detail` VALUES ('1', '7', '1', '280000', '0');
INSERT INTO `bill_in_detail` VALUES ('2', '2', '10', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '1', '2', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '2', '1', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '3', '3', '20000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '4', '2', '10000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '6', '2', '250000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '7', '1', '280000', '0');
INSERT INTO `bill_in_detail` VALUES ('3', '13', '1', '420000', '0');

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
  PRIMARY KEY (`bio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bill_out
-- ----------------------------
INSERT INTO `bill_out` VALUES ('4', '1429522159', '45', '1', '45190000', '1', '');
INSERT INTO `bill_out` VALUES ('5', '1429585285', '46', '1', '800000', '1', '');

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
INSERT INTO `bill_out_detail` VALUES ('4', '1', '20', '8000');
INSERT INTO `bill_out_detail` VALUES ('4', '2', '40', '2000');
INSERT INTO `bill_out_detail` VALUES ('4', '3', '100', '1500');
INSERT INTO `bill_out_detail` VALUES ('4', '4', '30', '90000');
INSERT INTO `bill_out_detail` VALUES ('4', '5', '30', '130000');
INSERT INTO `bill_out_detail` VALUES ('4', '6', '40', '85000');
INSERT INTO `bill_out_detail` VALUES ('4', '7', '100', '8000');
INSERT INTO `bill_out_detail` VALUES ('4', '8', '100', '3500');
INSERT INTO `bill_out_detail` VALUES ('4', '9', '100', '15000');
INSERT INTO `bill_out_detail` VALUES ('4', '10', '100', '20000');
INSERT INTO `bill_out_detail` VALUES ('4', '11', '50', '65000');
INSERT INTO `bill_out_detail` VALUES ('4', '12', '100', '100000');
INSERT INTO `bill_out_detail` VALUES ('4', '13', '100', '160000');
INSERT INTO `bill_out_detail` VALUES ('4', '14', '100', '6000');
INSERT INTO `bill_out_detail` VALUES ('4', '15', '50', '6000');
INSERT INTO `bill_out_detail` VALUES ('5', '1', '100', '8000');

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
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories_multi
-- ----------------------------
INSERT INTO `categories_multi` VALUES ('1', 'Thường xuyên', 'supplier', '', 'ccp1422242642.png', '0', '0', '');
INSERT INTO `categories_multi` VALUES ('8', 'Đồ ăn nhẹ', 'menus', '', null, '0', '0', 'Các loại đồ ăn nhanh chế biến sẵn');
INSERT INTO `categories_multi` VALUES ('7', 'Bia', 'menus', '', null, '0', '0', 'Đồ uống liên quan đến bia');
INSERT INTO `categories_multi` VALUES ('20', 'Trứng', 'products', '', null, '17', '0', '');
INSERT INTO `categories_multi` VALUES ('17', 'Nguyên liệu', 'products', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('18', 'Không phải chế biến', 'products', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('19', 'Rau', 'products', '', null, '17', '0', '');
INSERT INTO `categories_multi` VALUES ('22', 'Mì ăn liền', 'products', '', 'old1429019345.jpg', '18', '0', '');
INSERT INTO `categories_multi` VALUES ('24', 'Nhân viên thử việc', 'users', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('29', 'Chi sửa chữa', 'money_out', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('27', 'Thu khác', 'money_in', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('28', 'Chi thuế', 'money_out', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('30', 'Bán hàng', 'money_system_in', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('31', 'Nhập hàng', 'money_system_out', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('35', 'Tiền lương', 'money_out', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('34', 'Nhân viên chính thức', 'users', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('33', 'Thanh toán công nợ khách hàng', 'money_system_in', null, null, '0', '0', null);
INSERT INTO `categories_multi` VALUES ('32', 'Thanh toán công nợ nhà cung cấp', 'money_system_out', null, null, '0', '0', '                                                                                                                                                                                                                                                               ');
INSERT INTO `categories_multi` VALUES ('36', 'Chi tiền vận chuyển', 'money_out', null, null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('38', 'Đồ uống', 'products', '', 'ops1429018968.jpg', '18', '0', '');
INSERT INTO `categories_multi` VALUES ('39', 'Thịt', 'products', '', 'scb1429019223.jpg', '17', '0', '');
INSERT INTO `categories_multi` VALUES ('40', 'Cá', 'products', '', 'gfl1429019403.jpg', '17', '0', '');
INSERT INTO `categories_multi` VALUES ('41', 'Nước ngọt', 'menus', '', 'scq1429022025.jpg', '0', '0', '');
INSERT INTO `categories_multi` VALUES ('42', 'Món chính', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('43', 'Loại khác', 'products', '', null, '17', '0', '');
INSERT INTO `categories_multi` VALUES ('44', 'Loại khác', 'products', '', null, '18', '0', '');
INSERT INTO `categories_multi` VALUES ('45', 'Kho Trần Đại Nghĩa', 'stores', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('46', 'Kho Lê Đại Hành', 'stores', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('47', 'Món khai vị', 'menus', '', null, '0', '0', '');
INSERT INTO `categories_multi` VALUES ('48', 'Củ quả', 'products', '', null, '17', '0', '');
INSERT INTO `categories_multi` VALUES ('49', 'Lẩu', 'menus', '', null, '0', '0', '');

-- ----------------------------
-- Table structure for configurations
-- ----------------------------
DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
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
INSERT INTO `configurations` VALUES ('1', 'Hải Xồm Restaurant', '51 Lê Đại Hành, HBT, HN', '043.456.7890', '1', '45', '1', null, '0', 'ktc1429861417.png');

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
INSERT INTO `current_desk` VALUES ('2', '1429340672', '', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `current_desk` VALUES ('10', '1429340680', '', '0', '0', '0', '0', '0', '0', '0');

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
  UNIQUE KEY `key` (`cdm_desk_id`,`cdm_menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of current_desk_menu
-- ----------------------------
INSERT INTO `current_desk_menu` VALUES ('10', '2', '1', '20000', 'men_price', '0', '1429524382', '1429524382');
INSERT INTO `current_desk_menu` VALUES ('2', '13', '1', '420000', 'men_price', '0', '1429850700', '1429851173');
INSERT INTO `current_desk_menu` VALUES ('10', '9', '1', '40000', 'men_price', '0', '1429862754', '1429862754');
INSERT INTO `current_desk_menu` VALUES ('10', '10', '1', '40000', 'men_price', '0', '1429862755', '1429862755');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES ('1', 'Nguyễn Hữu Công', 'Đình Bảng - Từ Sơn - Bắc Ninh', '985161911', 'nhcong07@gmail.com1', 'ok', 'jcw1427187612.jpg', '1', '', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customer_cat
-- ----------------------------
INSERT INTO `customer_cat` VALUES ('1', 'Thành viên mới', '5', '0', null, null, null);
INSERT INTO `customer_cat` VALUES ('2', 'Thành viên đồng', '7', '0', 'bcc1427171199.jpg', '', null);
INSERT INTO `customer_cat` VALUES ('11', 'Thành viên kim cương', '15', '0', null, '', null);
INSERT INTO `customer_cat` VALUES ('9', 'Thành viên bạc', '10', '0', null, '', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of desks
-- ----------------------------
INSERT INTO `desks` VALUES ('2', 'Bàn 1', '1', '');
INSERT INTO `desks` VALUES ('5', 'Bàn 4', '1', '');
INSERT INTO `desks` VALUES ('6', 'Bàn 5', '1', '');
INSERT INTO `desks` VALUES ('7', 'Bàn 6', '1', '');
INSERT INTO `desks` VALUES ('8', 'Bàn 7', '1', '');
INSERT INTO `desks` VALUES ('9', 'Bàn 1', '2', '');
INSERT INTO `desks` VALUES ('10', 'Bàn 2', '2', '');
INSERT INTO `desks` VALUES ('11', 'Bàn 8', '1', '');

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
  PRIMARY KEY (`fin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of financial
-- ----------------------------
INSERT INTO `financial` VALUES ('1', '1427790438', '1427790438', '120000', 'Thu tiền phạt nhân viên', '', 'Đổng Trà Na', 'Hà Nội', '27', '0', 'Phạt đi muộn ', '1');
INSERT INTO `financial` VALUES ('2', '1427816294', '1427816294', '200000', '', '', 'Hoàng Dung', 'Tương Dương', '35', '0', 'Tiền lương tháng 3', '1');
INSERT INTO `financial` VALUES ('3', '1427816614', '1427816614', '100000', 'Chuyển hàng từ BigC về kho', '', 'Siêu thị BigC', 'số 5 Nguyễn Văn Linh, Long Biên, Hà Nội', '36', '0', '', '1');
INSERT INTO `financial` VALUES ('5', '1428049365', '1428049365', '200000', '', '', 'Cục thuế', 'Hà Nội', '28', '0', '', '1');
INSERT INTO `financial` VALUES ('16', '1429522547', '1429522547', '45190000', 'Nhập hàng', '4', 'Siêu thị BigC', 'số 5 Nguyễn Văn Linh, Long Biên, Hà Nội', '31', '0', '', '1');
INSERT INTO `financial` VALUES ('17', '1429523881', '1429523881', '200000', 'Bán hàng', '2', 'Nguyễn Hữu Công', 'Đình Bảng - Từ Sơn - Bắc Ninh', '30', '0', '', '1');
INSERT INTO `financial` VALUES ('18', '1429585304', '1429585304', '800000', 'Nhập hàng', '5', 'Siêu thị BigC', 'số 5 Nguyễn Văn Linh, Long Biên, Hà Nội', '31', '0', '', '1');
INSERT INTO `financial` VALUES ('19', '1429859685', '1429859685', '1340000', 'Bán hàng', '3', 'Khách lẻ', '', '30', '0', '', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('1', 'Mì tôm trứng', '8', '8', '20000', '25000', '30000', 'aqp1429021604.jpg', '', '0');
INSERT INTO `menus` VALUES ('2', 'Bia Hà Nội', '1', '7', '20000', '25000', '0', 'olf1429021856.gif', '', '0');
INSERT INTO `menus` VALUES ('3', 'Bia Sài Gòn', '1', '7', '20000', '25000', '0', 'mwc1429021925.jpg', '', '0');
INSERT INTO `menus` VALUES ('4', 'Cocacola', '1', '41', '10000', '15000', '20000', 'njh1429022165.jpg', '', '0');
INSERT INTO `menus` VALUES ('5', 'Pepsi', '1', '41', '10000', '15000', '20000', 'kuz1429022238.jpg', '', '0');
INSERT INTO `menus` VALUES ('6', 'Lẩu gà nấm', '9', '42', '250000', '300000', '350000', 'oee1429022327.JPG', '', '0');
INSERT INTO `menus` VALUES ('7', 'Lẩu bò', '9', '42', '280000', '320000', '350000', 'zok1429022819.jpg', '', '0');
INSERT INTO `menus` VALUES ('8', 'Súp gà nấm tuyết', '5', '47', '90000', '0', '0', 'buy1429850143.jpg', '', '0');
INSERT INTO `menus` VALUES ('9', 'Khoai lang chiên', '7', '47', '40000', '0', '0', 'sou1429850275.jpg', '', '0');
INSERT INTO `menus` VALUES ('10', 'Ngô chiên', '7', '47', '40000', '0', '0', 'zli1429850364.jpg', '', '0');
INSERT INTO `menus` VALUES ('11', 'Đậu rán', '7', '47', '30000', '0', '0', 'gpv1429850435.jpg', '', '0');
INSERT INTO `menus` VALUES ('12', 'Lẩu gà', '9', '49', '300000', '0', '0', 'jkq1429850546.jpg', '', '0');
INSERT INTO `menus` VALUES ('13', 'Lẩu riêu cua sụn bắp bò', '9', '49', '420000', '0', '0', 'vjz1429850599.jpg', '', '0');
INSERT INTO `menus` VALUES ('14', 'Lẩu bò sa tế', '9', '49', '420000', '0', '0', 'lxi1429850665.jpg', '', '0');
INSERT INTO `menus` VALUES ('15', 'Lẩu ếch', '9', '49', '380000', '0', '0', 'fwb1429850800.jpg', '', '0');
INSERT INTO `menus` VALUES ('16', 'Cá chép om dưa', '9', '49', '300000', '0', '0', 'tey1429850859.jpg', '', '0');
INSERT INTO `menus` VALUES ('17', 'Cơm cháy Tràng Xuân', '5', '42', '90000', '0', '0', 'lyt1429850989.jpg', '', '0');

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
INSERT INTO `menu_products` VALUES ('1', '2', '1');
INSERT INTO `menu_products` VALUES ('1', '3', '1');
INSERT INTO `menu_products` VALUES ('2', '7', '1');
INSERT INTO `menu_products` VALUES ('3', '1', '1');
INSERT INTO `menu_products` VALUES ('4', '14', '1');
INSERT INTO `menu_products` VALUES ('5', '15', '1');
INSERT INTO `menu_products` VALUES ('6', '9', '0.05');
INSERT INTO `menu_products` VALUES ('6', '10', '0.05');
INSERT INTO `menu_products` VALUES ('6', '13', '0.5');
INSERT INTO `menu_products` VALUES ('6', '17', '0.05');
INSERT INTO `menu_products` VALUES ('6', '18', '0.05');
INSERT INTO `menu_products` VALUES ('6', '19', '0.01');
INSERT INTO `menu_products` VALUES ('6', '20', '0.01');
INSERT INTO `menu_products` VALUES ('6', '22', '0.02');
INSERT INTO `menu_products` VALUES ('7', '5', '0.8');
INSERT INTO `menu_products` VALUES ('7', '20', '0.01');
INSERT INTO `menu_products` VALUES ('7', '22', '0.05');
INSERT INTO `menu_products` VALUES ('8', '13', '0.5');
INSERT INTO `menu_products` VALUES ('8', '16', '0.1');
INSERT INTO `menu_products` VALUES ('9', '27', '0.3');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'Bia Sài Gòn', 'jtc1429019106.jpg', 'ok', '38', '1', '', '10', null);
INSERT INTO `products` VALUES ('2', 'Mỳ tôm', 'swf1429019131.jpg', 'ok', '22', '3', '', '10', '0');
INSERT INTO `products` VALUES ('3', 'Trứng gà', 'bif1429019449.jpg', 'ok', '20', '4', '', '10', '0');
INSERT INTO `products` VALUES ('4', 'Thịt lợn', 'zby1429020213.jpg', '', '39', '2', '', '10', null);
INSERT INTO `products` VALUES ('5', 'Thịt bò', 'xmk1429020247.jpg', '', '39', '2', '', '10', null);
INSERT INTO `products` VALUES ('6', 'Cá quả', 'hpp1429020172.JPG', '', '40', '2', '', '10', null);
INSERT INTO `products` VALUES ('7', 'Bia Hà Nội', 'fch1429019067.gif', '', '38', '1', '', '20', null);
INSERT INTO `products` VALUES ('8', 'Trứng vịt lộn', 'vqf1429020327.jpg', '', '20', '4', '', '10', null);
INSERT INTO `products` VALUES ('9', 'Rau muống', 'jnp1429020536.jpg', '', '19', '2', '', '10', null);
INSERT INTO `products` VALUES ('10', 'Rau mồng tơi', 'dnr1429020578.jpg', '', '19', '2', '', '10', null);
INSERT INTO `products` VALUES ('11', 'Cá chép', 'ndx1429020650.gif', '', '40', '2', '', '10', null);
INSERT INTO `products` VALUES ('12', 'Mì chũ', 'luw1429021431.jpg', '', '22', '2', '', '5', null);
INSERT INTO `products` VALUES ('13', 'Thịt gà', 'yld1429021731.jpg', '', '39', '2', '', '25', null);
INSERT INTO `products` VALUES ('14', 'Cocacola', 'aty1429022067.jpg', '', '38', '1', '', '40', null);
INSERT INTO `products` VALUES ('15', 'Pepsi', 'kas1429022122.jpg', '', '38', '1', '', '40', null);
INSERT INTO `products` VALUES ('16', 'Nấm hương', null, '', '43', '2', '', '10', null);
INSERT INTO `products` VALUES ('17', 'Nấm rơm', null, '', '43', '2', '', '10', null);
INSERT INTO `products` VALUES ('18', 'Nấm kim châm', null, '', '43', '2', '', '10', null);
INSERT INTO `products` VALUES ('19', 'Váng đậu', null, '', '43', '2', '', '20', null);
INSERT INTO `products` VALUES ('20', 'Hành lá', null, '', '19', '2', '', '10', null);
INSERT INTO `products` VALUES ('21', 'Bún', 'ews1429022627.jpg', '', '22', '2', '', '5', null);
INSERT INTO `products` VALUES ('22', 'Rau cải thìa', 'gnw1429022745.jpg', '', '19', '2', '', '24', null);
INSERT INTO `products` VALUES ('25', 'Bánh mì gối', 'xun1429108466.jpg', '', '44', '11', '', '50', '1');
INSERT INTO `products` VALUES ('27', 'Khoai lang', 'kuu1429849261.jpeg', '', '48', '2', '', '5', '1');
INSERT INTO `products` VALUES ('28', 'Ngô', 'vom1429849395.gif', '', '48', '2', '', '2', '1');
INSERT INTO `products` VALUES ('29', 'Cà chua', 'bzv1429849528.jpg', '', '48', '2', '', '2', '1');
INSERT INTO `products` VALUES ('30', 'Sấu', null, '', '48', '2', '', '2', '1');
INSERT INTO `products` VALUES ('31', 'Chuối xanh', 'kbl1429849800.jpg', '', '48', '2', '', '2', '1');
INSERT INTO `products` VALUES ('32', 'Bầu', 'gvx1429849879.jpg', '', '48', '2', '', '2', '1');
INSERT INTO `products` VALUES ('33', 'Rau cải', 'uve1429849960.jpg', '', '19', '2', '', '2', '1');
INSERT INTO `products` VALUES ('34', 'Súp lơ', 'pee1429850064.jpg', '', '19', '2', '', '2', '1');

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
INSERT INTO `product_quantity` VALUES ('1', '45', '17');
INSERT INTO `product_quantity` VALUES ('1', '46', '100');
INSERT INTO `product_quantity` VALUES ('2', '45', '38');
INSERT INTO `product_quantity` VALUES ('2', '46', '0');
INSERT INTO `product_quantity` VALUES ('3', '45', '98');
INSERT INTO `product_quantity` VALUES ('3', '46', '0');
INSERT INTO `product_quantity` VALUES ('4', '45', '30');
INSERT INTO `product_quantity` VALUES ('4', '46', '0');
INSERT INTO `product_quantity` VALUES ('5', '45', '29.2');
INSERT INTO `product_quantity` VALUES ('5', '46', '0');
INSERT INTO `product_quantity` VALUES ('6', '45', '40');
INSERT INTO `product_quantity` VALUES ('6', '46', '0');
INSERT INTO `product_quantity` VALUES ('7', '45', '89');
INSERT INTO `product_quantity` VALUES ('7', '46', '0');
INSERT INTO `product_quantity` VALUES ('8', '45', '100');
INSERT INTO `product_quantity` VALUES ('8', '46', '0');
INSERT INTO `product_quantity` VALUES ('9', '45', '99.9');
INSERT INTO `product_quantity` VALUES ('9', '46', '0');
INSERT INTO `product_quantity` VALUES ('10', '45', '99.9');
INSERT INTO `product_quantity` VALUES ('10', '46', '0');
INSERT INTO `product_quantity` VALUES ('11', '45', '50');
INSERT INTO `product_quantity` VALUES ('11', '46', '0');
INSERT INTO `product_quantity` VALUES ('12', '45', '100');
INSERT INTO `product_quantity` VALUES ('12', '46', '0');
INSERT INTO `product_quantity` VALUES ('13', '45', '99');
INSERT INTO `product_quantity` VALUES ('13', '46', '0');
INSERT INTO `product_quantity` VALUES ('14', '45', '98');
INSERT INTO `product_quantity` VALUES ('14', '46', '0');
INSERT INTO `product_quantity` VALUES ('15', '45', '50');
INSERT INTO `product_quantity` VALUES ('15', '46', '0');
INSERT INTO `product_quantity` VALUES ('16', '45', '0');
INSERT INTO `product_quantity` VALUES ('16', '46', '0');
INSERT INTO `product_quantity` VALUES ('17', '45', '-0.1');
INSERT INTO `product_quantity` VALUES ('17', '46', '0');
INSERT INTO `product_quantity` VALUES ('18', '45', '-0.1');
INSERT INTO `product_quantity` VALUES ('18', '46', '0');
INSERT INTO `product_quantity` VALUES ('19', '45', '-0.02');
INSERT INTO `product_quantity` VALUES ('19', '46', '0');
INSERT INTO `product_quantity` VALUES ('20', '45', '-0.03');
INSERT INTO `product_quantity` VALUES ('20', '46', '0');
INSERT INTO `product_quantity` VALUES ('21', '45', '0');
INSERT INTO `product_quantity` VALUES ('21', '46', '0');
INSERT INTO `product_quantity` VALUES ('22', '45', '-0.09');
INSERT INTO `product_quantity` VALUES ('22', '46', '0');
INSERT INTO `product_quantity` VALUES ('25', '45', '0');
INSERT INTO `product_quantity` VALUES ('25', '46', '0');
INSERT INTO `product_quantity` VALUES ('26', '45', '0');
INSERT INTO `product_quantity` VALUES ('26', '46', '0');
INSERT INTO `product_quantity` VALUES ('27', '45', '0');
INSERT INTO `product_quantity` VALUES ('27', '46', '0');
INSERT INTO `product_quantity` VALUES ('28', '45', '0');
INSERT INTO `product_quantity` VALUES ('28', '46', '0');
INSERT INTO `product_quantity` VALUES ('29', '45', '0');
INSERT INTO `product_quantity` VALUES ('29', '46', '0');
INSERT INTO `product_quantity` VALUES ('30', '45', '0');
INSERT INTO `product_quantity` VALUES ('30', '46', '0');
INSERT INTO `product_quantity` VALUES ('31', '45', '0');
INSERT INTO `product_quantity` VALUES ('31', '46', '0');
INSERT INTO `product_quantity` VALUES ('32', '45', '0');
INSERT INTO `product_quantity` VALUES ('32', '46', '0');
INSERT INTO `product_quantity` VALUES ('33', '45', '0');
INSERT INTO `product_quantity` VALUES ('33', '46', '0');
INSERT INTO `product_quantity` VALUES ('34', '45', '0');
INSERT INTO `product_quantity` VALUES ('34', '46', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sections
-- ----------------------------
INSERT INTO `sections` VALUES ('1', 'Trong nhà', '', '1');
INSERT INTO `sections` VALUES ('2', 'Ngoài trời', '', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of service_desks
-- ----------------------------
INSERT INTO `service_desks` VALUES ('1', 'Quầy thanh toán', '1', '', '', null);
INSERT INTO `service_desks` VALUES ('2', 'Quầy lễ tân', '1', '', '', '');
INSERT INTO `service_desks` VALUES ('4', 'Quầy thanh toán', '2', '', '', null);

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
INSERT INTO `suppliers` VALUES ('1', 'Siêu thị BigC', 'số 5 Nguyễn Văn Linh, Long Biên, Hà Nội', '', '', '', '', '', 'gyp1422239067.png', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trash
-- ----------------------------
INSERT INTO `trash` VALUES ('1', '15', 'admin_users_groups', '1421728954', 'eyJhZHVfZ3JvdXBfaWQiOiIxNSIsImFkdV9ncm91cF9uYW1lIjoicGhcdTFlZTVjIHZcdTFlZTUiLCJhZHVfZ3JvdXBfYWRtaW4iOiIwIiwiYWR1X2dyb3VwX25vdGUiOiIifQ==', null);
INSERT INTO `trash` VALUES ('13', '3', 'desks', '1422005896', 'eyJkZXNfaWQiOiIzIiwiZGVzX25hbWUiOiJCXHUwMGUwbiAyIiwiZGVzX3NlY19pZCI6IjEiLCJkZXNfbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('14', '2', 'categories_multi', '1422190530', 'eyJjYXRfaWQiOiIyIiwiY2F0X25hbWUiOiJkc2ZhIiwiY2F0X3R5cGUiOiJzdXBwbGllciIsImNhdF9kZXNjIjoiIiwiY2F0X2ltYWdlIjoiIiwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoiIn0=', null);
INSERT INTO `trash` VALUES ('12', '4', 'desks', '1422005892', 'eyJkZXNfaWQiOiI0IiwiZGVzX25hbWUiOiJCXHUwMGUwbiAzIiwiZGVzX3NlY19pZCI6IjEiLCJkZXNfbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('8', '6', 'admin_users', '1421987204', 'eyJhZG1faWQiOiI2IiwiYWRtX2xvZ2lubmFtZSI6InRodW5nYW4iLCJhZG1fcGFzc3dvcmQiOiJlMTBhZGMzOTQ5YmE1OWFiYmU1NmUwNTdmMjBmODgzZSIsImFkbV9tYWlsIjpudWxsLCJhZG1fbmFtZSI6IlRodSBuZ1x1MDBlMm4iLCJhZG1fcGhvbmUiOm51bGwsImFkbV9iaXJ0aGRheSI6bnVsbCwiYWRtX2lzYWRtaW4iOiIwIiwiYWRtX2dyb3VwX2lkIjoiMTIiLCJhZG1fbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('17', '3', 'service_desks', '1422257926', 'eyJzZWRfaWQiOiIzIiwic2VkX25hbWUiOiJRdVx1MWVhN3kgMSIsInNlZF9hZ2VuY3lfaWQiOiIxIiwic2VkX3Bob25lIjoiIiwic2VkX25vdGUiOiIiLCJzZWRfaW1hZ2UiOm51bGx9', null);
INSERT INTO `trash` VALUES ('18', '4', 'categories_multi', '1427080284', 'eyJjYXRfaWQiOiI0IiwiY2F0X25hbWUiOiJcdTAxMTBcdTFlZDMgXHUwMTAzbiBuaFx1MWViOSIsImNhdF90eXBlIjoibWVudXMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoib2sifQ==', null);
INSERT INTO `trash` VALUES ('19', '5', 'categories_multi', '1427080300', 'eyJjYXRfaWQiOiI1IiwiY2F0X25hbWUiOiJCaWEiLCJjYXRfdHlwZSI6Im1lbnVzIiwiY2F0X2Rlc2MiOiIiLCJjYXRfaW1hZ2UiOm51bGwsImNhdF9wYXJlbnRfaWQiOiIwIiwiY2F0X2hhc19jaGlsZCI6IjAiLCJjYXRfbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('20', '6', 'categories_multi', '1427080315', 'eyJjYXRfaWQiOiI2IiwiY2F0X25hbWUiOiJcdTAxMTBcdTFlZDMgXHUwMTAzbiBuaFx1MWViOSIsImNhdF90eXBlIjoibWVudXMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoiIn0=', null);
INSERT INTO `trash` VALUES ('22', '4', 'customer_cat', '1427171027', 'eyJjdXNfY2F0X2lkIjoiNCIsImN1c19jYXRfbmFtZSI6IlRoXHUwMGUwbmggdmlcdTAwZWFuIFx1MDExMVx1MWVkM25nIiwiY3VzX2NhdF9zYWxlIjoiMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('23', '6', 'customer_cat', '1427171029', 'eyJjdXNfY2F0X2lkIjoiNiIsImN1c19jYXRfbmFtZSI6IlRoXHUwMGUwbmggdmlcdTAwZWFuIFx1MDExMVx1MWVkM25nIiwiY3VzX2NhdF9zYWxlIjoiMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('24', '7', 'customer_cat', '1427171031', 'eyJjdXNfY2F0X2lkIjoiNyIsImN1c19jYXRfbmFtZSI6IlRoXHUwMGUwbmggdmlcdTAwZWFuIFx1MDExMVx1MWVkM25nIiwiY3VzX2NhdF9zYWxlIjoiMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('25', '5', 'customer_cat', '1427171033', 'eyJjdXNfY2F0X2lkIjoiNSIsImN1c19jYXRfbmFtZSI6IlRoXHUwMGUwbmggdmlcdTAwZWFuIFx1MDExMVx1MWVkM25nIiwiY3VzX2NhdF9zYWxlIjoiMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('26', '8', 'customer_cat', '1427171036', 'eyJjdXNfY2F0X2lkIjoiOCIsImN1c19jYXRfbmFtZSI6IlRoXHUwMGUwbmggdmlcdTAwZWFuIFx1MDExMVx1MWVkM25nIiwiY3VzX2NhdF9zYWxlIjoiMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('29', '3', 'categories_multi', '1427183855', 'eyJjYXRfaWQiOiIzIiwiY2F0X25hbWUiOiJLaFx1MDBmNG5nIHRoXHUwMWIwXHUxZWRkbmcgeHV5XHUwMGVhbiIsImNhdF90eXBlIjoic3VwcGxpZXIiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoib2sifQ==', null);
INSERT INTO `trash` VALUES ('30', '10', 'customer_cat', '1427183965', 'eyJjdXNfY2F0X2lkIjoiMTAiLCJjdXNfY2F0X25hbWUiOiJUaFx1MDBlMG5oIHZpXHUwMGVhbiB2XHUwMGUwbmciLCJjdXNfY2F0X3NhbGUiOiIxMCIsImN1c19jYXRfb3JkZXIiOm51bGwsImN1c19jYXRfcGljdHVyZSI6bnVsbCwiY3VzX2NhdF9ub3RlIjoiIiwiY3VzX2NhdF9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('33', '9', 'categories_multi', '1427250935', 'eyJjYXRfaWQiOiI5IiwiY2F0X25hbWUiOiJOZ3V5XHUwMGVhbiBsaVx1MWVjN3UgdFx1MDFiMFx1MDFhMWkgc1x1MWVkMW5nIiwiY2F0X3R5cGUiOiJwcm9kdWN0cyIsImNhdF9kZXNjIjoiIiwiY2F0X2ltYWdlIjpudWxsLCJjYXRfcGFyZW50X2lkIjoiMCIsImNhdF9oYXNfY2hpbGQiOiIwIiwiY2F0X25vdGUiOiJvayJ9', null);
INSERT INTO `trash` VALUES ('36', '14', 'categories_multi', '1427269036', 'eyJjYXRfaWQiOiIxNCIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IG1cdTFlZGJpIiwiY2F0X3R5cGUiOiJwcm9kdWN0cyIsImNhdF9kZXNjIjoiIiwiY2F0X2ltYWdlIjpudWxsLCJjYXRfcGFyZW50X2lkIjoiMCIsImNhdF9oYXNfY2hpbGQiOiIwIiwiY2F0X25vdGUiOiJvayJ9', null);
INSERT INTO `trash` VALUES ('35', '11', 'categories_multi', '1427268318', 'eyJjYXRfaWQiOiIxMSIsImNhdF9uYW1lIjoiXHUwMTEwXHUxZWQzIHVcdTFlZDFuZyIsImNhdF90eXBlIjoicHJvZHVjdHMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoiIn0=', null);
INSERT INTO `trash` VALUES ('37', '13', 'categories_multi', '1427269038', 'eyJjYXRfaWQiOiIxMyIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IG1cdTFlZGJpIiwiY2F0X3R5cGUiOiJwcm9kdWN0cyIsImNhdF9kZXNjIjoiIiwiY2F0X2ltYWdlIjpudWxsLCJjYXRfcGFyZW50X2lkIjoiMCIsImNhdF9oYXNfY2hpbGQiOiIwIiwiY2F0X25vdGUiOiJvayJ9', null);
INSERT INTO `trash` VALUES ('38', '16', 'categories_multi', '1427269132', 'eyJjYXRfaWQiOiIxNiIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IHRcdTAxYjBcdTAxYTFpIHNcdTFlZDFuZyIsImNhdF90eXBlIjoicHJvZHVjdHMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjEwIiwiY2F0X2hhc19jaGlsZCI6IjAiLCJjYXRfbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('39', '15', 'categories_multi', '1427269135', 'eyJjYXRfaWQiOiIxNSIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IHRcdTAxYjBcdTAxYTFpIHNcdTFlZDFuZyIsImNhdF90eXBlIjoicHJvZHVjdHMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoiIn0=', null);
INSERT INTO `trash` VALUES ('40', '10', 'categories_multi', '1427269240', 'eyJjYXRfaWQiOiIxMCIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IHRcdTAxYjBcdTAxYTFpIHNcdTFlZDFuZyIsImNhdF90eXBlIjoicHJvZHVjdHMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6bnVsbCwiY2F0X3BhcmVudF9pZCI6IjEyIiwiY2F0X2hhc19jaGlsZCI6IjAiLCJjYXRfbm90ZSI6Im9rIn0=', null);
INSERT INTO `trash` VALUES ('41', '12', 'categories_multi', '1427269246', 'eyJjYXRfaWQiOiIxMiIsImNhdF9uYW1lIjoiTmd1eVx1MDBlYW4gbGlcdTFlYzd1IGNcdTAwZjMgc1x1MWViNW4iLCJjYXRfdHlwZSI6InByb2R1Y3RzIiwiY2F0X2Rlc2MiOiIiLCJjYXRfaW1hZ2UiOm51bGwsImNhdF9wYXJlbnRfaWQiOiIwIiwiY2F0X2hhc19jaGlsZCI6IjAiLCJjYXRfbm90ZSI6IiJ9', null);
INSERT INTO `trash` VALUES ('43', '23', 'categories_multi', '1427345503', 'eyJjYXRfaWQiOiIyMyIsImNhdF9uYW1lIjoiTmhcdTAwZTJuIHZpXHUwMGVhbiBjaFx1MDBlZG5oIHRoXHUxZWU5YyIsImNhdF90eXBlIjoidXNlcnMiLCJjYXRfZGVzYyI6IiIsImNhdF9pbWFnZSI6InJnbzE0MjczNDUzOTIuanBnIiwiY2F0X3BhcmVudF9pZCI6IjAiLCJjYXRfaGFzX2NoaWxkIjoiMCIsImNhdF9ub3RlIjoib2sifQ==', null);
INSERT INTO `trash` VALUES ('49', '0', 'sections', '1428066820', 'bnVsbA==', '');
INSERT INTO `trash` VALUES ('50', '0', 'sections', '1428066828', 'bnVsbA==', '');
INSERT INTO `trash` VALUES ('46', '4', 'users', '1427771650', 'eyJ1c2VfaWQiOiI0IiwidXNlX25hbWUiOiJzZGYiLCJ1c2VfYWRkcmVzcyI6ImRmIiwidXNlX3Bob25lIjoiIiwidXNlX3BheSI6IjAiLCJ1c2VfZGlzY291bnQiOiIwIiwidXNlX2dyb3VwX2lkIjoiMCIsInVzZV9jb2RlIjoiIiwidXNlX25vdGUiOiIiLCJ1c2VfaW1hZ2UiOm51bGwsInVzZV9zdGF0dXMiOm51bGx9', null);
INSERT INTO `trash` VALUES ('51', '21', 'categories_multi', '1429018888', 'eyJjYXRfaWQiOiIyMSIsImNhdF9uYW1lIjoiVGhcdTFlY2J0IiwiY2F0X3R5cGUiOiJwcm9kdWN0cyIsImNhdF9kZXNjIjoiIiwiY2F0X3BpY3R1cmUiOm51bGwsImNhdF9wYXJlbnRfaWQiOiIxOCIsImNhdF9oYXNfY2hpbGQiOiIwIiwiY2F0X25vdGUiOiIifQ==', '');
INSERT INTO `trash` VALUES ('52', '26', 'products', '1429849133', 'eyJwcm9faWQiOiIyNiIsInByb19uYW1lIjoiVGhcdTFlY2J0IGdcdTAwZTAiLCJwcm9faW1hZ2UiOm51bGwsInByb19ub3RlIjoiIiwicHJvX2NhdF9pZCI6IjM5IiwicHJvX3VuaXRfaWQiOiIyIiwicHJvX2NvZGUiOiIiLCJwcm9faW5zdG9jayI6IjUiLCJwcm9fc3RhdHVzIjoiMSJ9', '');

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
INSERT INTO `triggers` VALUES ('billSubmit', '1');

-- ----------------------------
-- Table structure for units
-- ----------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `uni_id` int(11) NOT NULL AUTO_INCREMENT,
  `uni_name` varchar(255) DEFAULT NULL,
  `uni_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uni_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Đổng Trà Na', 'Hà Nội', '0985161911', '200000', '12', '34', '', '', null, null);
INSERT INTO `users` VALUES ('2', 'Hoàng Dung', 'Tương Dương', '0985161911', '110000', '2', '24', '', '', null, null);
INSERT INTO `users` VALUES ('3', 'Quách Tĩnh', 'Nhữ Nam', '0985161911', '130000', '3', '34', '', 'Nhân tài võ học', null, null);
