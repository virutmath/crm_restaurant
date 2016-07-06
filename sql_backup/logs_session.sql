/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm_offline

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-05 13:28:16
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
