/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-16 09:09:23
*/

SET FOREIGN_KEY_CHECKS=0;

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
  PRIMARY KEY (`con_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of configurations
-- ----------------------------
INSERT INTO `configurations` VALUES ('1', 'Hải Xồm Restaurant', '51 Lê Đại Hành, HBT, HN', '043.456.7890', '1', '45', '1', null, '0');
