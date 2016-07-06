/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm_offline

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-05 13:30:16
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Table structure for inventory_products
-- ----------------------------
DROP TABLE IF EXISTS `inventory_products`;
CREATE TABLE `inventory_products` (
  `inv_id` int(11) DEFAULT '0' COMMENT 'id của phiếu kiểm kê kho hàng',
  `inv_product_id` int(11) DEFAULT '0' COMMENT 'ID sản phẩm có trong phiếu kiểm kê',
  `inp_quantity_system` int(11) DEFAULT '0' COMMENT 'Số lượng trên hệ thống',
  `inp_quantity_real` int(11) DEFAULT '0' COMMENT 'Số lượng thực tế'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
