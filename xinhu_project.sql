/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xhoa

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-02-28 16:23:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xinhu_project
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_project`;
CREATE TABLE `xinhu_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '项目名称',
  `typeid` smallint(6) DEFAULT '0' COMMENT '对应分类',
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `author` varchar(20) DEFAULT NULL COMMENT '工长',
  `chuban` varchar(50) DEFAULT NULL COMMENT '业主姓名',
  `cbdt` date DEFAULT NULL COMMENT '出版日期',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `weizhi` varchar(50) DEFAULT NULL COMMENT '存放位置',
  `shul` smallint(6) DEFAULT '0' COMMENT '数量',
  `adddt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optid` smallint(6) DEFAULT '0',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `isbn` varchar(30) DEFAULT NULL,
  `jieshu` smallint(6) DEFAULT '0' COMMENT '被借阅数',
  `uid` smallint(6) DEFAULT '0',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `courseid` int(20) DEFAULT NULL COMMENT '当前审核进度',
  `coursename` varchar(2000) DEFAULT NULL COMMENT '当前审核进度名称',
  `customersource` varchar(50) DEFAULT NULL COMMENT '客户来源',
  `thirdpro` varchar(50) DEFAULT NULL COMMENT '第三方协议',
  `telephone` bigint(11) DEFAULT NULL COMMENT '联系方式',
  `endtime` date DEFAULT NULL COMMENT '预计结束时间',
  `housesize` smallint(6) DEFAULT '80' COMMENT '房屋面积',
  `housetype` varchar(50) DEFAULT NULL COMMENT '房屋类型',
  `budgettype` varchar(50) DEFAULT NULL COMMENT '预算类型',
  `telwatpri` bigint(10) DEFAULT NULL COMMENT '含水电价',
  `designer` varchar(50) DEFAULT NULL COMMENT '设计师',
  `designerpromise` varchar(500) DEFAULT NULL COMMENT '设计师承诺',
  `mdarea` varchar(50) DEFAULT NULL COMMENT '门店区域',
  `nbbh` varchar(10) DEFAULT NULL COMMENT '内部编号',
  `htys` varchar(50) DEFAULT NULL COMMENT '预算员',
  `routelin` varchar(50) DEFAULT NULL COMMENT '区域1',
  `routeline` varchar(50) DEFAULT NULL COMMENT '区域',
  `yzbrand` varchar(50) DEFAULT '0' COMMENT '品牌',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='装修项目列表';

-- ----------------------------
-- Records of xinhu_project
-- ----------------------------
