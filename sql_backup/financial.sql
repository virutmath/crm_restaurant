/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-01 11:54:20
*/

SET FOREIGN_KEY_CHECKS=0;

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
  `fin_billcode` varchar(255) DEFAULT NULL,
  `fin_username` varchar(255) DEFAULT NULL,
  `fin_address` varchar(255) DEFAULT NULL,
  `fin_cat_id` int(11) DEFAULT '0',
  `fin_pay_type` tinyint(4) DEFAULT '0' COMMENT 'thanh toán bằng tiền mặt hay bằng thẻ',
  `fin_note` text,
  `fin_admin_id` int(11) DEFAULT '0',
  PRIMARY KEY (`fin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
