-- MySQL dump 10.13  Distrib 5.6.27, for linux-glibc2.5 (x86_64)
--
-- Host: 139.196.59.18    Database: poodle_test
-- ------------------------------------------------------
-- Server version	5.6.27-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `itemname` varchar(64) COLLATE utf8_bin NOT NULL,
  `userid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bizrule` text COLLATE utf8_bin,
  `data` text COLLATE utf8_bin,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_bin,
  `bizrule` text COLLATE utf8_bin,
  `data` text COLLATE utf8_bin,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_bin NOT NULL,
  `child` varchar(64) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gov_category`
--

DROP TABLE IF EXISTS `gov_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gov_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '单位分类ID',
  `cate_name` varchar(120) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='政府分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gov_user`
--

DROP TABLE IF EXISTS `gov_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gov_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `gov_cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单位分类ID',
  `gov_cate_name` varchar(55) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '政府分类名称',
  `u_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型：0.默认 1.问题上报员 2.处理问题单位 3.管理员 3.超级管理员',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态: 0.可用 1.不可用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operation_log`
--

DROP TABLE IF EXISTS `operation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `op_type` smallint(8) unsigned NOT NULL COMMENT '用户操作类型',
  `op_time` int(10) unsigned NOT NULL COMMENT '用户操作时间',
  `op_markup` text COLLATE utf8_bin COMMENT '操作备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=882 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problem`
--

DROP TABLE IF EXISTS `problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题ID',
  `release_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传用户ID',
  `release_username` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '发布用户名',
  `address` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '问题地址',
  `description` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '问题描述',
  `deal_cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分配单位分类ID',
  `deal_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '负责处理的用户ID',
  `deal_username` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '负责处理的问题的用户名',
  `deal_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '处理时长，以小时为单位',
  `accept_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接受时间',
  `times_up` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核通过，但超过给定的时间限制',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '问题状态: 0.新建 1.已发派 2.申请延时 3.申请联动 4.退单 5.处理中 6.带审核 7.打回 8.审核通过 9.关闭',
  `is_delay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否延期',
  `delay_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '延时次数(申请审批通过后的次数)',
  `delay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '延长时间，以小时为单位',
  `is_assistant` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要辅助',
  `assist_unit` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '辅助单位ID集合(存json字符串)',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `assign_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分配时间',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `idx_up_uid` (`release_uid`),
  KEY `idx_assign_time` (`assign_time`),
  KEY `idx_deal_uid` (`deal_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10034 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='问题信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problem_image`
--

DROP TABLE IF EXISTS `problem_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题id',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题ID',
  `img_path` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '图片路径',
  `img_width` smallint(6) NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `img_height` smallint(6) NOT NULL DEFAULT '0' COMMENT '图片高度',
  `img_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片类型: 1.问题图片 2.待审核图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0禁用1启用2不合格',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='问题图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problem_log`
--

DROP TABLE IF EXISTS `problem_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题log id',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题id',
  `pre_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '操作前状态',
  `cur_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '操作后状态',
  `oper_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作用户ID',
  `oper_user` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '操作账户',
  `log_desc` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '日志说明',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户特殊说明',
  `data` text CHARACTER SET utf8 COMMENT '特殊数据',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '日志状态',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_oper_uid` (`oper_uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='问题日志';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-30 22:56:42
