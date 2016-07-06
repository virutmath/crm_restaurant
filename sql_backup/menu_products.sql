/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : crm_restaurant

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-30 09:09:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for menu_products
-- ----------------------------
DROP TABLE IF EXISTS `menu_products`;
CREATE TABLE `menu_products` (
  `mep_menu_id` int(11) NOT NULL,
  `mep_product_id` int(11) NOT NULL,
  `mep_quantity` float DEFAULT NULL,
  UNIQUE KEY `key` (`mep_menu_id`,`mep_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
