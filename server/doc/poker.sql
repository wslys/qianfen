/*
Navicat MySQL Data Transfer

Source Server         : mysql_two
Source Server Version : 50637
Source Host           : localhost:3306
Source Database       : poker

Target Server Type    : MYSQL
Target Server Version : 50637
File Encoding         : 65001

Date: 2018-02-08 16:07:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for room
-- ----------------------------
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL COMMENT '房间号',
  `owner` int(11) DEFAULT NULL COMMENT '房主',
  `title` varchar(255) DEFAULT NULL COMMENT '房间标题',
  `type` tinyint(1) DEFAULT NULL COMMENT '房间类型',
  `status` tinyint(1) DEFAULT '1' COMMENT '房间状态：【0、已经废弃， 1、准备中， 2、游戏中】',
  `if_pwd` tinyint(1) DEFAULT '0' COMMENT '是否需要密码进入房间',
  `pwd` varchar(255) DEFAULT NULL COMMENT '房间密码',
  `if_show` tinyint(1) DEFAULT '1' COMMENT '是否在大厅显示',
  `if_match` tinyint(1) DEFAULT '0' COMMENT '是否允许观战',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '房间创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '房间信息更改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='游戏房间表';

-- ----------------------------
-- Records of room
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(255) DEFAULT NULL COMMENT 'OpenID',
  `nick_name` varchar(255) DEFAULT NULL COMMENT '用户昵称',
  `avatar_url` varchar(255) DEFAULT NULL COMMENT '用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。',
  `gender` varchar(255) DEFAULT NULL COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `rank` varchar(255) DEFAULT NULL COMMENT '等级',
  `rience` int(11) DEFAULT NULL COMMENT '经验值',
  `rank_formula` varchar(255) DEFAULT NULL COMMENT '等级计算公式【有可能会员账号与普通账号的等级计算公式不同】',
  `rience_formula` varchar(255) DEFAULT NULL COMMENT '积分计算公式【有可能会员账号与非会员账号每把获得的经验的计算公式不同】',
  `city` varchar(255) DEFAULT NULL COMMENT '用户所在城市',
  `province` varchar(255) DEFAULT NULL COMMENT '用户所在省份',
  `country` varchar(255) DEFAULT NULL COMMENT '用户所在国家',
  `language` varchar(255) DEFAULT NULL COMMENT '用户的语言，简体中文为zh_CN',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
