/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm_offline

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-05 13:31:38
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Table structure for stock_transfer_products
-- ----------------------------
DROP TABLE IF EXISTS `stock_transfer_products`;
CREATE TABLE `stock_transfer_products` (
  `sto_id` int(11) DEFAULT '0' COMMENT 'ID phiếu chuyển kho hàng',
  `pro_id` int(11) DEFAULT '0' COMMENT 'ID sản phẩm chuyển',
  `stp_quantity_inventory` int(11) DEFAULT '0' COMMENT 'Số lượng tồn kho',
  `stp_quantity_transfer` int(11) DEFAULT '0' COMMENT 'Số lượng chuyển'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
