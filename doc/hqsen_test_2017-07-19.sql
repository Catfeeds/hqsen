# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 47.92.31.234 (MySQL 5.5.54-0ubuntu0.14.04.1)
# Database: hqsen_test
# Generation Time: 2017-07-19 06:22:19 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table hqsen_area
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_area`;

CREATE TABLE `hqsen_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `area_name` varchar(32) NOT NULL COMMENT '区域名字',
  `area_list` varchar(200) NOT NULL DEFAULT '' COMMENT '区域列表（黄浦区，徐汇区）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_area_sh
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_area_sh`;

CREATE TABLE `hqsen_area_sh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `area_value` varchar(15) NOT NULL COMMENT '区域value',
  `area_label` varchar(15) NOT NULL DEFAULT '' COMMENT '区域label',
  `link_area_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联区域ID',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `hqsen_area_sh` WRITE;
/*!40000 ALTER TABLE `hqsen_area_sh` DISABLE KEYS */;

INSERT INTO `hqsen_area_sh` (`id`, `area_value`, `area_label`, `link_area_id`, `del_flag`)
VALUES
	(1,'浦东新区','浦东新区',38,1),
	(2,'卢湾区','卢湾区',41,1),
	(3,'黄浦区','黄浦区',41,1),
	(4,'虹口区','虹口区',42,1),
	(5,'杨浦区','杨浦区',45,1),
	(6,'闸北区','闸北区',39,1),
	(7,'普陀区','普陀区',44,1),
	(8,'长宁区','长宁区',44,1),
	(9,'静安区','静安区',39,1),
	(10,'徐汇区','徐汇区',43,1),
	(11,'南汇区','南汇区',38,1),
	(12,'闵行区','闵行区',47,1),
	(13,'奉贤区','奉贤区',47,1),
	(14,'金山区','金山区',46,1),
	(15,'松江区','松江区',40,1),
	(16,'青浦区','青浦区',47,1),
	(17,'嘉定区','嘉定区',0,1),
	(18,'宝山区','宝山区',48,1),
	(19,'崇明县','崇明县',48,1);

/*!40000 ALTER TABLE `hqsen_area_sh` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hqsen_dajian_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_dajian_order`;

CREATE TABLE `hqsen_dajian_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `customer_name` varchar(32) NOT NULL COMMENT '受访者 消费者名字',
  `order_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '搭建订单类型 1布展',
  `order_phone` varchar(18) NOT NULL DEFAULT '' COMMENT '消费者 手机号',
  `order_area_hotel_type` int(3) NOT NULL DEFAULT '0' COMMENT '搭建信息 默认  1指定区域 ',
  `order_area_hotel_id` varchar(16) NOT NULL DEFAULT '0' COMMENT '后台编辑的区域ID 目前单选  可能像客资支持多选',
  `desk_count` int(4) NOT NULL DEFAULT '0' COMMENT '桌数',
  `order_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '预算',
  `use_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `watch_user` varchar(20) NOT NULL DEFAULT '0' COMMENT '跟进用户',
  `order_desc` varchar(200) NOT NULL DEFAULT '' COMMENT '订单描述',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `user_id` int(10) NOT NULL COMMENT ' 创建用户',
  `order_status` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0未知 1待处理 2跟踪中 3待结算 4已结算 5已取消',
  `order_from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1用户创建 2同步',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_feedback`;

CREATE TABLE `hqsen_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `content` varchar(255) NOT NULL COMMENT '意见反馈内容',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `user_id` int(10) unsigned NOT NULL COMMENT '创建者ID',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '创建者账号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_hotel
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_hotel`;

CREATE TABLE `hqsen_hotel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `hotel_name` varchar(100) NOT NULL COMMENT '酒店名字',
  `hotel_address` varchar(200) NOT NULL DEFAULT '' COMMENT '酒店地址',
  `area_id` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `hotel_level` varchar(4) NOT NULL DEFAULT 'A' COMMENT '酒店等级 A B C',
  `area_sh_id` int(5) unsigned NOT NULL COMMENT '上海区域ID关联 area_sh 表',
  `weight` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '同区域酒店权重',
  `is_data` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否录入详情1是 2 否',
  `is_room` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否录入宴会厅 1是 2 否',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_hotel_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_hotel_data`;

CREATE TABLE `hqsen_hotel_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `hotel_low` varchar(15) NOT NULL COMMENT '酒店最低价',
  `hotel_high` varchar(15) NOT NULL DEFAULT '' COMMENT '酒店最高价',
  `hotel_max_desk` varchar(15) NOT NULL DEFAULT '' COMMENT '酒店最大容纳桌数',
  `hotel_type` varchar(15) NOT NULL DEFAULT '' COMMENT '酒店类型（星级酒店 特色餐厅 婚礼会所 游轮婚宴）',
  `hotel_phone` varchar(45) NOT NULL DEFAULT '' COMMENT '酒店联系电话（区号＋联系电话＋分机号）或者手机号',
  `hotel_image` text NOT NULL COMMENT '酒店介绍图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_hotel_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_hotel_menu`;

CREATE TABLE `hqsen_hotel_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `menu_name` varchar(30) NOT NULL COMMENT '菜单名字',
  `menu_money` varchar(30) NOT NULL DEFAULT '' COMMENT '菜单价格',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `hotel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '酒店ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_hotel_rec
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_hotel_rec`;

CREATE TABLE `hqsen_hotel_rec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `hotel_id` varchar(15) NOT NULL COMMENT '酒店ID',
  `hotel_weight` varchar(15) NOT NULL DEFAULT '' COMMENT '酒店权重',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_hotel_room
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_hotel_room`;

CREATE TABLE `hqsen_hotel_room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `room_name` varchar(15) NOT NULL COMMENT '宴会厅名称',
  `room_max_desk` varchar(15) NOT NULL DEFAULT '' COMMENT '最大容纳桌数',
  `room_min_desk` varchar(15) NOT NULL DEFAULT '' COMMENT '最少容纳桌数',
  `room_best_desk` varchar(15) NOT NULL DEFAULT '' COMMENT '最佳容纳桌数',
  `room_m` varchar(15) NOT NULL DEFAULT '' COMMENT '宴会厅面积',
  `room_lz` varchar(15) NOT NULL DEFAULT '' COMMENT '宴会厅立柱数',
  `room_image` text NOT NULL COMMENT '宴会厅介绍图片',
  `hotel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '酒店ID',
  `room_high` varchar(15) NOT NULL DEFAULT '' COMMENT '宴会厅层高',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_kezi_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_kezi_order`;

CREATE TABLE `hqsen_kezi_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `customer_name` varchar(32) NOT NULL COMMENT '受访者 消费者名字',
  `order_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '客资订单类别 1婚宴 2会务 3宝宝宴',
  `order_phone` varchar(18) NOT NULL DEFAULT '' COMMENT '消费者 手机号',
  `order_area_hotel_type` int(3) NOT NULL DEFAULT '0' COMMENT '1指定区域 2指定酒店',
  `order_area_hotel_id` varchar(16) NOT NULL DEFAULT '0' COMMENT '后台编辑的区域ID或者酒店ID 酒店可以多个 22,5,6',
  `desk_count` int(4) NOT NULL DEFAULT '0' COMMENT '桌数',
  `order_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '预算',
  `use_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `watch_user` varchar(20) NOT NULL DEFAULT '0' COMMENT '跟进用户',
  `order_desc` varchar(200) NOT NULL DEFAULT '' COMMENT '订单描述',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `user_id` int(10) NOT NULL COMMENT ' 创建用户',
  `order_status` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0未知 1待处理 2跟踪中 3待结算 4已结算 5已取消',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_pay_ratio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_pay_ratio`;

CREATE TABLE `hqsen_pay_ratio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `kezi_user` varchar(10) NOT NULL COMMENT '客资提供者系数',
  `kezi_hotel` varchar(10) NOT NULL COMMENT '客资跟踪者系数',
  `dajian_user` varchar(10) NOT NULL COMMENT '搭建提供者系数',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `hqsen_pay_ratio` WRITE;
/*!40000 ALTER TABLE `hqsen_pay_ratio` DISABLE KEYS */;

INSERT INTO `hqsen_pay_ratio` (`id`, `kezi_user`, `kezi_hotel`, `dajian_user`, `del_flag`)
VALUES
	(14,'0.1','0.1','0.2',1);

/*!40000 ALTER TABLE `hqsen_pay_ratio` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hqsen_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user`;

CREATE TABLE `hqsen_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_name` varchar(32) NOT NULL COMMENT '唯一用户名',
  `nike_name` varchar(32) NOT NULL DEFAULT '' COMMENT '用昵称',
  `user_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `phone` varchar(18) NOT NULL DEFAULT '' COMMENT '手机号',
  `alipay_account` varchar(50) NOT NULL DEFAULT '' COMMENT '支付宝',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `user_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户来源 1未知用户 2超级管理员 3客资信息账号（手机注册用户）4酒店账号 11首销 12二销 13 财务 14 客服 15管理员',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `session_id` varchar(32) NOT NULL DEFAULT '' COMMENT 'substr(md5(id+create_time+time()),0,20)',
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1已启用 2已禁用',
  `bank_name` varchar(50) NOT NULL DEFAULT '' COMMENT '银行帐号',
  `bank_user` varchar(50) NOT NULL DEFAULT '' COMMENT '银行帐号',
  `bank_account` varchar(50) NOT NULL DEFAULT '' COMMENT '银行帐号',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_type` (`user_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `hqsen_user` WRITE;
/*!40000 ALTER TABLE `hqsen_user` DISABLE KEYS */;

INSERT INTO `hqsen_user` (`id`, `user_name`, `nike_name`, `user_pic`, `phone`, `alipay_account`, `create_time`, `user_type`, `del_flag`, `session_id`, `password`, `user_status`, `bank_name`, `bank_user`, `bank_account`, `last_login_time`)
VALUES
	(2,'monkey','1506815966','','15068159661','',1491029702,2,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(4,'sen','sen','','','',1491030559,2,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0);

/*!40000 ALTER TABLE `hqsen_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hqsen_user_dajian_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_dajian_order`;

CREATE TABLE `hqsen_user_dajian_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `dajian_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '搭建订单表ID 关联hqsen_dajian_order',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '搭建信息提供者（酒店账号）用户ID',
  `watch_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首销账号（跟踪者）ID',
  `watch_user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '首销名字',
  `watch_user_hotel_name` varchar(50) NOT NULL DEFAULT '' COMMENT '首销酒店名字',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '搭建跟踪者订单状态1待处理 2待审核 3待结算 4已结算 5已驳回 6已取消',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `order_phone` varchar(18) NOT NULL DEFAULT '' COMMENT '消费者 手机号',
  `user_order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '搭建提供者状态 1跟进中 2待结算 3已结算 4已取消',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `erxiao_order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '搭建二销状态 0首销还未通过 1待处理 2待审核 3已完结',
  `erxiao_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二销 用户ID',
  `erxiao_sign_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '搭建二销签单状态1 中款 2尾款 3附加款 4尾款时间',
  `create_user_money` varchar(20) NOT NULL DEFAULT '' COMMENT '创建者订单佣金',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_dajian_order_follow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_dajian_order_follow`;

CREATE TABLE `hqsen_user_dajian_order_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_dajian_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_user_kezi_order',
  `order_follow_time` varchar(20) NOT NULL DEFAULT '' COMMENT '下次跟踪时间',
  `order_follow_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪备注',
  `order_follow_create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '跟踪创建时间',
  `user_order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1有效 2无效 3签单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_dajian_order_other_sign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_dajian_order_other_sign`;

CREATE TABLE `hqsen_user_dajian_order_other_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_dajian_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_user_kezi_order',
  `sign_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 中款 2尾款 3附加款 4尾款时间',
  `order_money` varchar(20) NOT NULL DEFAULT '' COMMENT '合同金额',
  `order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `order_sign_pic` text NOT NULL COMMENT '签单凭证多图 json',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0未知 1初次录入 2再次录入',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_dajian_order_sign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_dajian_order_sign`;

CREATE TABLE `hqsen_user_dajian_order_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_dajian_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_user_kezi_order',
  `order_money` varchar(20) NOT NULL DEFAULT '' COMMENT '合同金额',
  `sign_using_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '举办时间：尾款时间',
  `sign_pic` text NOT NULL COMMENT '签单凭证多图 json',
  `first_order_money` varchar(20) NOT NULL DEFAULT '' COMMENT '首付款金额',
  `first_order_using_time` varchar(20) NOT NULL DEFAULT '' COMMENT '首付款时间',
  `next_pay_time` varchar(20) NOT NULL DEFAULT '' COMMENT '下次支付时间：中款时间',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0未知 1初次录入 2再次录入',
  `sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '首销财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改',
  `boss_sign_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首销总经理审核状态 0未知 1未处理 2通过 3驳回',
  `order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `sign_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '搭建二销签单状态 0首款   1 中款  2尾款  3附加款 4尾款时间',
  `sign_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二销用户ID',
  `sign_other_sign_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二销当前轮转的签单ID',
  `sign_other_sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '二销财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `dajian_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_dajian_order',
  `erxiao_unhandle_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二销待处理时间戳根据中款尾款两个时间来',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_dajian_sign_follow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_dajian_sign_follow`;

CREATE TABLE `hqsen_user_dajian_sign_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_sign_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对应签单ID 关联hqsen_user_kezi_order_sign',
  `status_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '审批备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审批创建时间',
  `sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '财务审核状态 0未知 1未处理 2通过 3驳回',
  `boss_sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '总经理审核状态 0未知 1未处理 2通过 3驳回',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_data`;

CREATE TABLE `hqsen_user_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `hotel_name` varchar(32) NOT NULL COMMENT '区域名字',
  `hotel_area` varchar(200) NOT NULL DEFAULT '' COMMENT '黄浦区',
  `hotel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '酒店ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '后台编辑区域ID',
  `last_order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次获取订单时间',
  `user_name` varchar(32) NOT NULL COMMENT '唯一用户名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_kezi_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_kezi_order`;

CREATE TABLE `hqsen_user_kezi_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `kezi_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客资订单表ID 关联hqsen_kezi_order',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `watch_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '酒店账号（跟踪者）ID',
  `watch_user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪者名字',
  `watch_user_hotel_name` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪者酒店名字',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 2为删除',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '客资跟踪者订单状态1待处理 2待审核 3待结算 4已结算 5已驳回 6已取消',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `order_phone` varchar(18) NOT NULL DEFAULT '' COMMENT '消费者 手机号',
  `user_order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '客资提供者状态 1跟进中 2待结算 3已结算 4已取消',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `create_user_money` varchar(20) NOT NULL DEFAULT '' COMMENT '创建者订单佣金',
  `watch_user_money` varchar(20) NOT NULL DEFAULT '' COMMENT '跟踪者订单佣金',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_kezi_order_follow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_kezi_order_follow`;

CREATE TABLE `hqsen_user_kezi_order_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_kezi_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_user_kezi_order',
  `order_follow_time` varchar(20) NOT NULL DEFAULT '' COMMENT '下次跟踪时间',
  `order_follow_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪备注',
  `order_follow_create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '跟踪创建时间',
  `user_order_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1有效 2无效 3签单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_kezi_order_sign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_kezi_order_sign`;

CREATE TABLE `hqsen_user_kezi_order_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_kezi_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_user_kezi_order',
  `order_money` varchar(20) NOT NULL DEFAULT '' COMMENT '合同金额',
  `order_other_money` varchar(20) NOT NULL DEFAULT '' COMMENT '附加款',
  `sign_using_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '举办时间',
  `sign_pic` text NOT NULL COMMENT '签单凭证多图 json',
  `del_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0未知 1初次录入 2再次录入',
  `sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改',
  `boss_sign_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '总经理审核状态 0未知 1未处理 2通过 3驳回',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `kezi_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户客资订单表ID 关联hqsen_kezi_order',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_user_kezi_sign_follow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_user_kezi_sign_follow`;

CREATE TABLE `hqsen_user_kezi_sign_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_sign_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对应签单ID 关联hqsen_user_kezi_order_sign',
  `status_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '审批备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审批创建时间',
  `sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '财务审核状态 0未知 1未处理 2通过 3驳回',
  `boss_sign_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '总经理审核状态 0未知 1未处理 2通过 3驳回',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
