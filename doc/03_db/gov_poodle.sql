/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : gov_poodle

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-10-20 21:08:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for gov_category
-- ----------------------------
DROP TABLE IF EXISTS `gov_category`;
CREATE TABLE `gov_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '单位分类ID',
  `cate_name` varchar(120) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='政府分类';

-- ----------------------------
-- Table structure for gov_user
-- ----------------------------
DROP TABLE IF EXISTS `gov_user`;
CREATE TABLE `gov_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(150) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(20) NOT NULL DEFAULT '' COMMENT '密码',
  `gov_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属部门',
  `gov_cate_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '单位分类ID',
  `gov_cate_name` varchar(55) NOT NULL DEFAULT '' COMMENT '政府分类名称',
  `u_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户类型：1上传照片2分配任务3管理员',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息';
