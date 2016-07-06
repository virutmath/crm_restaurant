/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-16 10:41:21
*/

SET FOREIGN_KEY_CHECKS=0;

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
