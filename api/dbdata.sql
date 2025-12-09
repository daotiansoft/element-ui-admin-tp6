/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : dbdata

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 05/09/2023 13:59:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for dt_activation_cate
-- ----------------------------
DROP TABLE IF EXISTS `dt_activation_cate`;
CREATE TABLE `dt_activation_cate`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `create_time` int(20) NULL DEFAULT 0,
  `update_time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '激活码分类' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_activation_cate
-- ----------------------------
INSERT INTO `dt_activation_cate` VALUES (1, '数据比对', '测试用的', 1636805913, 1636805973);

-- ----------------------------
-- Table structure for dt_activation_code
-- ----------------------------
DROP TABLE IF EXISTS `dt_activation_code`;
CREATE TABLE `dt_activation_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(20) NULL DEFAULT 0 COMMENT '分类ID',
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `time` int(20) NULL DEFAULT 0 COMMENT '时长',
  `amount` int(20) NULL DEFAULT 0 COMMENT '次数',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `create_time` int(20) NULL DEFAULT 0,
  `active_time` int(20) NULL DEFAULT 0 COMMENT '激活时间',
  `active_uid` int(20) NULL DEFAULT 0 COMMENT '激活用户',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '激活码' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dt_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `dt_article_cate`;
CREATE TABLE `dt_article_cate`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态 1正常 -1隐藏',
  `create_time` int(20) NULL DEFAULT 0,
  `update_time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文章分类' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_article_cate
-- ----------------------------
INSERT INTO `dt_article_cate` VALUES (1, '分类一', '666', 1, 1652526653, 1652526653);
INSERT INTO `dt_article_cate` VALUES (2, '分类二', '', 1, 1652526661, 1652527025);

-- ----------------------------
-- Table structure for dt_article_list
-- ----------------------------
DROP TABLE IF EXISTS `dt_article_list`;
CREATE TABLE `dt_article_list`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(20) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(20) NULL DEFAULT NULL,
  `update_time` int(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文章列表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dt_balance_log
-- ----------------------------
DROP TABLE IF EXISTS `dt_balance_log`;
CREATE TABLE `dt_balance_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '类型',
  `user_id` int(20) NULL DEFAULT 0,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `balance` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '余额',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `create_time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '余额变动记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dt_balance_withdrawal
-- ----------------------------
DROP TABLE IF EXISTS `dt_balance_withdrawal`;
CREATE TABLE `dt_balance_withdrawal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '流水号',
  `user_id` int(20) NULL DEFAULT 0,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '提现金额',
  `pay` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '实付金额',
  `rate` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '手续费',
  `account` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '收款账号',
  `account_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '收款账号名称',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '状态',
  `desc` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `create_time` int(20) NULL DEFAULT 0,
  `auth_time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '余额提现' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dt_config
-- ----------------------------
DROP TABLE IF EXISTS `dt_config`;
CREATE TABLE `dt_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '英文名称',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '类型 text image select',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '中文名称',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '内容',
  `placeholder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '提示内容',
  `params` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '附加内容',
  `sort` int(10) NULL DEFAULT 50,
  `show` tinyint(1) NULL DEFAULT 1 COMMENT '是否显示 1是-1否',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_config
-- ----------------------------
INSERT INTO `dt_config` VALUES (1, 'site_name', 'text', '网站名称', '测试网站名称', '站点名称', NULL, 10, 1);
INSERT INTO `dt_config` VALUES (2, 'site_status', 'select', '网站停用', '1', '站点关闭后，用户无法登录', '[{\"id\":1,\"name\":\"正常\"},{\"id\":2,\"name\":\"停用\"}]', 10, 1);
INSERT INTO `dt_config` VALUES (3, 'logo', 'image', 'LOGO', '[domain]/storage/topic/20230523/c68c18dda07ac88b4b6e3f609a418ba8.png', NULL, NULL, 50, 1);
INSERT INTO `dt_config` VALUES (4, 'site_stop_msg', 'text', '站点停用提示', '系统维护中...', NULL, NULL, 10, 1);
INSERT INTO `dt_config` VALUES (5, 'demo_textarea', 'textarea', '多行文本', NULL, NULL, NULL, 1, -1);

-- ----------------------------
-- Table structure for dt_editor
-- ----------------------------
DROP TABLE IF EXISTS `dt_editor`;
CREATE TABLE `dt_editor`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '英文名称',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '中文名称',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '内容',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '富文本内容' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_editor
-- ----------------------------
INSERT INTO `dt_editor` VALUES (1, 'notice', '系统通知内容', '<p>欢迎使用！</p>');

-- ----------------------------
-- Table structure for dt_login_log
-- ----------------------------
DROP TABLE IF EXISTS `dt_login_log`;
CREATE TABLE `dt_login_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NULL DEFAULT 0,
  `device` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '登录设备',
  `time` int(20) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态1成功2失败',
  `desc` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '登录记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_login_log
-- ----------------------------
INSERT INTO `dt_login_log` VALUES (1, 1, 'api', 1693893501, 1, '登录成功', '127.0.0.1');

-- ----------------------------
-- Table structure for dt_token
-- ----------------------------
DROP TABLE IF EXISTS `dt_token`;
CREATE TABLE `dt_token`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NULL DEFAULT 0,
  `token` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `device` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '设备',
  `create_time` int(20) NULL DEFAULT 0,
  `expire_time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uid`(`uid`, `device`) USING BTREE,
  UNIQUE INDEX `token`(`token`, `device`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '登录token' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_token
-- ----------------------------
INSERT INTO `dt_token` VALUES (1, 1, '2ae1e43653f0ee1e76500e878977bbb0', 'api', 1693893501, 1694498301);

-- ----------------------------
-- Table structure for dt_upload
-- ----------------------------
DROP TABLE IF EXISTS `dt_upload`;
CREATE TABLE `dt_upload`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `size` int(20) NULL DEFAULT 0,
  `mime` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `path` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `time` int(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dt_user
-- ----------------------------
DROP TABLE IF EXISTS `dt_user`;
CREATE TABLE `dt_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '账号类型 admin  user',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rand_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '随机码',
  `balance` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '余额',
  `create_time` int(20) NULL DEFAULT 0,
  `update_time` int(20) NULL DEFAULT 0,
  `vip_time` int(20) NULL DEFAULT 0 COMMENT '会员时间',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态1正常2停用',
  `pid` int(10) NULL DEFAULT 0 COMMENT '邀请人',
  `activation_data` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '激活状态',
  `rate_balance_withdrawal` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '提现费率',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '账号表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dt_user
-- ----------------------------
INSERT INTO `dt_user` VALUES (1, 'admin', 'admin', '0d3e24d606ccb8b0e1e9d5c11bdfe1ae', 'xump', 0.00, 1652500366, 0, 0, 1, 0, '', 0.00);
INSERT INTO `dt_user` VALUES (2, 'user', '张三', 'f56fe008fb5908e9bd35a64730cc5226', 'cNc2', 9900.00, 1652506679, 1667956856, 0, 1, 0, '', 0.02);

SET FOREIGN_KEY_CHECKS = 1;
