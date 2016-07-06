/*
Navicat MySQL Data Transfer

Source Server         : CONGNH-SERVER
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-12 09:22:20
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotions
-- ----------------------------
INSERT INTO `promotions` VALUES ('3', 'Khuyến mãi 1/5', '1', '1431325800', '1431358200', '5', '1', '0', 'Khuyến mãi dành cho tất cả khách hàng');
INSERT INTO `promotions` VALUES ('4', 'Chiến dịch mùa hè', '1', '1431338400', '1431358200', '5', '1', '0', 'ok man');
INSERT INTO `promotions` VALUES ('5', 'Khuyễn mãi đặc biệt', '1', '1431325800', '1431358200', '5', '1', '0', '');
INSERT INTO `promotions` VALUES ('6', 'Khuyễn mãi đặc biệt', '1', '1431325800', '1431358200', '5', '1', '0', '');
INSERT INTO `promotions` VALUES ('7', 'Khuyến mãi 1/6', '1', '1433140200', '1433172600', '5', '1', '0', 'ok');
INSERT INTO `promotions` VALUES ('8', 'Khuyến mãi 1/6', '1', '1433140200', '1433172600', '5', '1', '0', 'ok');
INSERT INTO `promotions` VALUES ('9', 'Khuyến mãi 1/6', '1', '1433140200', '1433172600', '5', '1', '0', 'ok');
INSERT INTO `promotions` VALUES ('10', 'Khuyến mãi to', '1', '1431325800', '1431338400', '5', '1', '0', '');
INSERT INTO `promotions` VALUES ('11', 'chien dich moi', '2', '1431412200', '1431444600', '5', '1', '0', '');
INSERT INTO `promotions` VALUES ('12', 'Khuyến mãi lớn', '1', '1431412200', '1431444600', '5', '1', '0', '');
INSERT INTO `promotions` VALUES ('13', 'Khuyến mãi lớn', '1', '1431412200', '1431444600', '5', '1', '0', '');

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
