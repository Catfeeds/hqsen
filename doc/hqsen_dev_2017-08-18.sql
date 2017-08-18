# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 47.92.31.234 (MySQL 5.5.54-0ubuntu0.14.04.1)
# Database: hqsen_dev
# Generation Time: 2017-08-18 06:33:42 +0000
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

LOCK TABLES `hqsen_area` WRITE;
/*!40000 ALTER TABLE `hqsen_area` DISABLE KEYS */;

INSERT INTO `hqsen_area` (`id`, `area_name`, `area_list`, `create_time`, `del_flag`)
VALUES
	(1,'浦东地区','1',1501496134,1),
	(2,'浦西地区','2,10,3,4,5,6,7,8,12,9,15,17,16,18',1501496142,1),
	(3,'周边地区','13,14,19',1501507286,1);

/*!40000 ALTER TABLE `hqsen_area` ENABLE KEYS */;
UNLOCK TABLES;


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
	(1,'浦东新区','浦东新区',1,1),
	(2,'卢湾区','卢湾区',2,1),
	(3,'黄浦区','黄浦区',2,1),
	(4,'虹口区','虹口区',2,1),
	(5,'杨浦区','杨浦区',2,1),
	(6,'闸北区','闸北区',2,1),
	(7,'普陀区','普陀区',2,1),
	(8,'长宁区','长宁区',2,1),
	(9,'静安区','静安区',2,1),
	(10,'徐汇区','徐汇区',2,1),
	(11,'南汇区','南汇区',0,1),
	(12,'闵行区','闵行区',2,1),
	(13,'奉贤区','奉贤区',3,1),
	(14,'金山区','金山区',3,1),
	(15,'松江区','松江区',2,1),
	(16,'青浦区','青浦区',2,1),
	(17,'嘉定区','嘉定区',2,1),
	(18,'宝山区','宝山区',2,1),
	(19,'崇明县','崇明县',3,1);

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

LOCK TABLES `hqsen_hotel` WRITE;
/*!40000 ALTER TABLE `hqsen_hotel` DISABLE KEYS */;

INSERT INTO `hqsen_hotel` (`id`, `hotel_name`, `hotel_address`, `area_id`, `create_time`, `del_flag`, `hotel_level`, `area_sh_id`, `weight`, `is_data`, `is_room`)
VALUES
	(1,'上海龙之梦万丽酒店','上海市长宁区长宁路1018号',2,1501507396,1,'A',8,1,1,1),
	(2,'上海明捷万丽酒店','上海市普陀区铜川路50号',2,1501509451,1,'A',7,1,1,1),
	(3,'马勒别墅饭店','上海市黄浦区陕西南路30号',2,1501510350,1,'A',3,1,1,1),
	(4,'富建酒店','上海市闵行区七莘路1885号',2,1501551216,1,'A',12,1,1,1),
	(5,'上海豫园万丽酒店','上海市黄浦区河南南路159号',2,1501551985,1,'A',3,2,1,1),
	(6,'上海淳大万丽酒店','联洋新社区长柳路100号',1,1501552302,1,'A',1,1,1,1),
	(7,'上海银星皇冠假日酒店','上海市静安区番禺路400号',2,1501552700,1,'B',9,3,1,1),
	(8,'明天广场JW万豪酒店','上海市黄浦区南京西路399号',2,1501552964,1,'A',3,3,1,1),
	(9,'上海三至喜来登酒店','上海市虹口区四平路59号',2,1501553468,1,'A',4,1,1,1),
	(10,'浦东假日酒店','上海市浦东新区东方路899号',1,1501555153,1,'B',1,10,1,1);

/*!40000 ALTER TABLE `hqsen_hotel` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `hqsen_hotel_data` WRITE;
/*!40000 ALTER TABLE `hqsen_hotel_data` DISABLE KEYS */;

INSERT INTO `hqsen_hotel_data` (`id`, `hotel_low`, `hotel_high`, `hotel_max_desk`, `hotel_type`, `hotel_phone`, `hotel_image`)
VALUES
	(1,'6588','8888','60','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150767220141104153232698.jpg\"]'),
	(2,'6588','8588','28','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150960920141104153814889.jpg\"]'),
	(3,'6888','9888','26','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155091420141104153841979.jpg\"]'),
	(4,'5888','7888','60','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155125820141104153843790.jpg\",\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155126220141104153843576.jpg\"]'),
	(5,'6188','7888','23','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155202220141104153844790.jpg\"]'),
	(6,'6880','10380','28','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155235320141104153814889.jpg\"]'),
	(7,'5888','6688','32','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155272620141104153232698.jpg\"]'),
	(8,'8888','12888','32','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155298620141111094634579.jpg\"]'),
	(9,'7388','10888','30','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155405320141104153844678.jpg\"]'),
	(10,'5688','7288','36','星级酒店','18321177032','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155518220141111094646209.jpg\"]');

/*!40000 ALTER TABLE `hqsen_hotel_data` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `hqsen_hotel_menu` WRITE;
/*!40000 ALTER TABLE `hqsen_hotel_menu` DISABLE KEYS */;

INSERT INTO `hqsen_hotel_menu` (`id`, `menu_name`, `menu_money`, `del_flag`, `hotel_id`)
VALUES
	(1,'红宝石之恋','6588',1,1),
	(2,'情比金坚','7288',1,1),
	(3,'至臻翡翠','7888',1,1),
	(4,'水晶之恋','8888',1,1),
	(5,'A套','6588',1,2),
	(6,'B套','7588',1,2),
	(7,'C套','8588',1,2),
	(8,'佳偶天成宴','6888',1,3),
	(9,'百年好合宴','7888',1,3),
	(10,'龙凤呈祥宴','8888',1,3),
	(11,'至尊良缘宴','9888',1,3),
	(12,'永结同心','5888',1,4),
	(13,'花好月圆','6888',1,4),
	(14,'百年好合','7888',1,4),
	(15,'百年琴瑟','8888',1,4),
	(16,'花好月圆','6188',1,5),
	(17,'良辰美景','6988',1,5),
	(18,'永结同心','7888',1,5),
	(19,'罗马假日','6880',1,6),
	(20,'首尔恋歌','7980',1,6),
	(21,'巴黎约定','9180',1,6),
	(22,' 雅典神话','10380',1,6),
	(23,'金玉良缘','5888',1,7),
	(24,'盟结良缘','6288',1,7),
	(25,'天赐良缘','6688',1,7),
	(26,'珠帘玉映筳','8888',1,8),
	(27,'缘定三生筳','10888',1,8),
	(28,'水晶婚典','7388',1,9),
	(29,'佳偶天成','8888',1,9),
	(30,'百年好合','9988',1,9),
	(31,'永结同心','10888',1,9),
	(32,'缘定今生','5688',1,10),
	(33,'龙凤呈祥','6188',1,10),
	(34,'金玉满堂','6688',1,10),
	(35,'百年好合','7288',1,10);

/*!40000 ALTER TABLE `hqsen_hotel_menu` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `hqsen_hotel_rec` WRITE;
/*!40000 ALTER TABLE `hqsen_hotel_rec` DISABLE KEYS */;

INSERT INTO `hqsen_hotel_rec` (`id`, `hotel_id`, `hotel_weight`, `del_flag`)
VALUES
	(1,'1','1',1),
	(2,'2','2',1),
	(3,'3','3',1),
	(4,'8','4',1),
	(5,'9','5',1),
	(6,'6','6',1),
	(7,'4','7',1),
	(8,'5','8',1);

/*!40000 ALTER TABLE `hqsen_hotel_rec` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `hqsen_hotel_room` WRITE;
/*!40000 ALTER TABLE `hqsen_hotel_room` DISABLE KEYS */;

INSERT INTO `hqsen_hotel_room` (`id`, `room_name`, `room_max_desk`, `room_min_desk`, `room_best_desk`, `room_m`, `room_lz`, `room_image`, `hotel_id`, `room_high`, `del_flag`)
VALUES
	(1,'A 厅','23','15','20','448平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150778220141104153232698.jpg\"]',1,'5.6米',1),
	(2,'B厅','21','15','18','427平米','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150789620141104153814889.jpg\"]',1,'5.6米',1),
	(3,'C厅','15','10','12','338平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150794720141104153841979.jpg\"]',1,'5.6米',1),
	(4,'大宴会厅','28','16','20','520平米','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150973620141104153232698.jpg\"]',2,'5.5米',1),
	(5,'小会议厅','10','6','8','189平米','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150150982120141104153814889.jpg\"]',2,'2.8米',1),
	(6,'2F','26','10','20','包间','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155102220141104153844678.jpg\",\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155102620141104153844790.jpg\"]',3,'3.2米',1),
	(7,'国宴厅','60','20','35','1200平米','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155134920141104153232698.jpg\"]',4,'8米',1),
	(8,'富锦荟','20','10','16','400平米','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155194420141104153844790.jpg\"]',4,'3.3米',1),
	(9,'宴会厅','23','15','18','487平方','2','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155210520141104153232698.jpg\",\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155211220141104153814889.jpg\"]',5,'4.5米',1),
	(10,'景观厅','15','10','12','315平方','4','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155220020141104153814889.jpg\"]',5,'7.2米',1),
	(11,'宴会厅','28','22','25','545平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155244220141104153814889.jpg\",\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155244820141104153232698.jpg\"]',6,'6米',1),
	(12,'扬子厅','14','8','10','300平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155249120141104153844790.jpg\"]',6,'3.6米',1),
	(13,'金爵厅','32','25','20','550平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155281420141104153232698.jpg\"]',7,'5.5米',1),
	(14,'银星厅','25','18','15','450平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155284720141104153814889.jpg\"]',7,'5米',1),
	(15,'琥珀厅','16','10','12','350平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155287620141104153841979.jpg\"]',7,'3米',1),
	(16,'碧玉厅','16','10','12','350平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155291620141104153842439.jpg\"]',7,'3米',1),
	(17,'大宴会厅','38','8','32','600平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155305120141104153844678.jpg\",\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155305520141104153844790.jpg\"]',8,'6.6米',1),
	(18,'大宴会厅','30','15','20','600平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155430020141104153814889.jpg\"]',9,'7米',1),
	(19,'水晶宴会厅 ','14','5','10','250平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155441920141104153841979.jpg\"]',9,'4.5米',1),
	(20,'上海厅','36','10','30','570平方','0','[\"http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/150155525720141104153843576.jpg\"]',10,'4.6米',1);

/*!40000 ALTER TABLE `hqsen_hotel_room` ENABLE KEYS */;
UNLOCK TABLES;


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
  `sync_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '同步状态 1 未同步 2已同步',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table hqsen_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hqsen_log`;

CREATE TABLE `hqsen_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `uuid` varchar(100) NOT NULL COMMENT '意见反馈内容',
  `log_content` text COMMENT '创建者账号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
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
	(92,'13813813800','13813813800','','13813813800','',1491030559,3,1,'8d84f6b947c1a7cb7eef7934d044186f','e10adc3949ba59abbe56e057f20f883e',1,'','','',1501557302),
	(4,'admin','admin','','','',1491030559,2,1,'2c34d02cc531706ab54e44d4aa7577c7','e10adc3949ba59abbe56e057f20f883e',1,'','','',1501567561),
	(93,'18321598476','18321598476','','18321598476','18321598476',1501548413,3,1,'ab3d73f8401751eeaa1efdf70204c090','',1,'','','',1501548413),
	(94,'zbsx001','','','','',1501556287,11,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(95,'zbex001','','','','',1501556305,12,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(96,'pxsx001','','','','',1501556331,11,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(97,'pxex001','','','','',1501556353,12,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(98,'pdsx001','','','','',1501556373,11,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(99,'pdex001','','','','',1501556394,12,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(100,'pskf001','','','','',1501556502,14,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(101,'pscw001','','','','',1501556518,13,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(102,'psbj001','','','','',1501556543,16,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(103,'psgly001','','','','',1501556568,15,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(104,'pdjr001','','','','',1501562879,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(105,'szxld001','','','','',1501562899,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(106,'mtgcwh001','','','','',1501562969,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(107,'yxhg001','','','','',1501564860,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(108,'cdwl001','','','','',1501564912,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(109,'yywl001','','','','',1501564934,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(110,'fjjd001','','','','',1501564960,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(111,'mlbs001','','','','',1501564979,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0),
	(112,'mjwl001','','','','',1501565000,4,1,'964633c9e129ef002b757f3a61ebcfc0','e10adc3949ba59abbe56e057f20f883e',1,'','','',1501565073),
	(113,'lzmwl001','','','','',1501565023,4,1,'','e10adc3949ba59abbe56e057f20f883e',1,'','','',0);

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
  `old_order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '尾款原时间',
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
  `auto_type` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '1不同步 2同步',
  `area_sh_id` int(5) unsigned NOT NULL COMMENT '上海区域ID关联 area_sh 表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `hqsen_user_data` WRITE;
/*!40000 ALTER TABLE `hqsen_user_data` DISABLE KEYS */;

INSERT INTO `hqsen_user_data` (`id`, `hotel_name`, `hotel_area`, `hotel_id`, `user_id`, `area_id`, `last_order_time`, `user_name`, `auto_type`, `area_sh_id`)
VALUES
	(1,'','周边地区',0,94,3,0,'zbsx001',2,0),
	(2,'','周边地区',0,95,3,0,'zbex001',2,0),
	(3,'','浦西地区',0,96,2,0,'pxsx001',2,0),
	(4,'','浦西地区',0,97,2,0,'pxex001',2,0),
	(5,'','浦东地区',0,98,1,0,'pdsx001',2,0),
	(6,'','浦东地区',0,99,1,0,'pdex001',2,0),
	(7,'','',0,100,0,0,'pskf001',2,0),
	(8,'','',0,101,0,0,'pscw001',2,0),
	(9,'','',0,102,0,0,'psbj001',2,0),
	(10,'','',0,103,0,0,'psgly001',2,0),
	(11,'浦东假日酒店','浦东新区',10,104,1,0,'pdjr001',2,1),
	(12,'上海三至喜来登酒店','虹口区',9,105,2,0,'szxld001',2,4),
	(13,'明天广场JW万豪酒店','黄浦区',8,106,2,0,'mtgcwh001',2,3),
	(14,'上海银星皇冠假日酒店','静安区',7,107,2,0,'yxhg001',2,9),
	(15,'上海淳大万丽酒店','浦东新区',6,108,1,0,'cdwl001',2,1),
	(16,'上海豫园万丽酒店','黄浦区',5,109,2,0,'yywl001',2,3),
	(17,'富建酒店','闵行区',4,110,2,0,'fjjd001',2,12),
	(18,'马勒别墅饭店','黄浦区',3,111,2,0,'mlbs001',2,3),
	(19,'上海明捷万丽酒店','普陀区',2,112,2,0,'mjwl001',2,7),
	(20,'上海龙之梦万丽酒店','长宁区',1,113,2,0,'lzmwl001',2,8);

/*!40000 ALTER TABLE `hqsen_user_data` ENABLE KEYS */;
UNLOCK TABLES;


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
  `order_from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1用户创建 2同步 3同步订单操作过',
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
