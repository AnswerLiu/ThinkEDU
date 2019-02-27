/*
Navicat MySQL Data Transfer

Source Server         : Reborn
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : edu

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-03-23 22:48:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for te_config
-- ----------------------------
DROP TABLE IF EXISTS `te_config`;
CREATE TABLE `te_config` (
  `id` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL COMMENT '网站title',
  `keywords` varchar(255) DEFAULT NULL COMMENT '网站关键字',
  `description` longtext COMMENT '网站说明',
  `logo` varchar(255) DEFAULT NULL COMMENT '网站logo',
  `version` char(20) DEFAULT NULL COMMENT '版本号',
  `status` tinyint(2) DEFAULT '1' COMMENT '网站状态：1 开启；0 关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站状态及信息';

-- ----------------------------
-- Records of te_config
-- ----------------------------
INSERT INTO `te_config` VALUES ('0', 'ThinkEDU--打造最好用的教学管理系统', '打造最好用的教学管理系统', 'ThinkEDU,Thinkedu,ThinkPHP,php,H-ui,教学,教学系统,教学管理系统,老师,学生', null, 'V1.0', '1');

-- ----------------------------
-- Table structure for te_course
-- ----------------------------
DROP TABLE IF EXISTS `te_course`;
CREATE TABLE `te_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程管理表';

-- ----------------------------
-- Records of te_course
-- ----------------------------

-- ----------------------------
-- Table structure for te_gardes
-- ----------------------------
DROP TABLE IF EXISTS `te_gardes`;
CREATE TABLE `te_gardes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garde_name` varchar(255) DEFAULT NULL COMMENT '班级名称',
  `length` char(20) DEFAULT NULL COMMENT '学制',
  `price` decimal(10,2) DEFAULT NULL COMMENT '学费',
  `start_time` int(11) DEFAULT NULL COMMENT '开班时间',
  `teacher` char(20) DEFAULT NULL COMMENT '授课老师',
  `status` tinyint(2) DEFAULT '1' COMMENT '是否启用：1 启用；0 关闭；',
  `create_time` int(11) DEFAULT NULL COMMENT '班级创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_delete` tinyint(2) DEFAULT '1' COMMENT '是否删除 1；正常；0删除；',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='班级管理表';

-- ----------------------------
-- Records of te_gardes
-- ----------------------------
INSERT INTO `te_gardes` VALUES ('1', 'php就业班1期', '2年', '16800.00', '1529544600', '于耀翔', '1', '1521010939', '1521037984', null, '1');
INSERT INTO `te_gardes` VALUES ('2', 'php就业班2期', '2年', '16800.00', '1529546600', '程雯', '1', '1521012939', null, null, '1');
INSERT INTO `te_gardes` VALUES ('3', 'php就业班3期', '2年', '16800.00', '1529548600', '李媛媛', '1', '1521014939', '1521040478', null, '1');
INSERT INTO `te_gardes` VALUES ('4', 'php就业班4期', '2年', '17800.00', '1529568600', '白洁', '1', '1521016939', '1521016780', null, '1');
INSERT INTO `te_gardes` VALUES ('5', 'UI就业班1期', '1年', '24880.00', '1522026000', '谢霆锋', '1', '1521038800', '1521038851', null, '1');
INSERT INTO `te_gardes` VALUES ('6', 'UI就业班2期', '1年', '22880.00', '1522112400', '桂纶镁', '1', '1521038820', null, null, '1');

-- ----------------------------
-- Table structure for te_menu
-- ----------------------------
DROP TABLE IF EXISTS `te_menu`;
CREATE TABLE `te_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT '父id，对应id',
  `name` char(50) DEFAULT NULL COMMENT '菜单名称',
  `class` char(30) DEFAULT NULL COMMENT '类名',
  `link` char(50) DEFAULT NULL COMMENT '菜单链接，格式：''控制器/方法名''',
  `status` tinyint(2) DEFAULT '1' COMMENT '菜单状态：1 启用；0 关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='菜单管理表';

-- ----------------------------
-- Records of te_menu
-- ----------------------------
INSERT INTO `te_menu` VALUES ('1', '0', 'ThinkEDU', null, '/', '1');
INSERT INTO `te_menu` VALUES ('2', '1', '学生管理', 'menu-student', '/', '1');
INSERT INTO `te_menu` VALUES ('3', '1', '老师管理', 'menu-teacher', '/', '1');
INSERT INTO `te_menu` VALUES ('4', '1', '课程管理', 'menu-course', '/', '1');
INSERT INTO `te_menu` VALUES ('5', '1', '班级管理', 'menu-gardes', '/', '1');
INSERT INTO `te_menu` VALUES ('6', '1', '管理员管理', 'menu-admin', '/', '1');
INSERT INTO `te_menu` VALUES ('7', '1', '系统管理', 'menu-system', '/', '1');
INSERT INTO `te_menu` VALUES ('8', '2', '学生列表', 'student-list', 'student/studentList', '1');
INSERT INTO `te_menu` VALUES ('9', '3', '老师列表', 'teacher-list', 'teacher/teacherList', '1');
INSERT INTO `te_menu` VALUES ('10', '4', '课程列表', 'course-list', 'course/courseList', '1');
INSERT INTO `te_menu` VALUES ('11', '5', '班级列表', 'gardes-list', 'gardes/gardesList', '1');
INSERT INTO `te_menu` VALUES ('12', '6', '管理员列表', 'admin-list', 'user/adminList', '1');

-- ----------------------------
-- Table structure for te_student
-- ----------------------------
DROP TABLE IF EXISTS `te_student`;
CREATE TABLE `te_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garde_id` int(11) DEFAULT NULL COMMENT '关联gardes表的id',
  `student_name` char(20) DEFAULT NULL COMMENT '学生姓名',
  `sex` tinyint(1) DEFAULT '2' COMMENT '性别：0 女；1 男；2 保密',
  `age` tinyint(4) DEFAULT NULL COMMENT '年龄',
  `tel` char(11) DEFAULT NULL COMMENT '手机号',
  `email` char(100) DEFAULT NULL COMMENT '邮箱',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态 1 正常；0 关闭',
  `start_time` int(11) DEFAULT NULL COMMENT '入学时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_delete` tinyint(2) DEFAULT '1' COMMENT '是否删除：1 未删除；0 已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='学生管理表';

-- ----------------------------
-- Records of te_student
-- ----------------------------
INSERT INTO `te_student` VALUES ('1', '4', '周瑜', '1', '28', '15501028020', 'zhouyu@thinkedu.com', '1', '1521509400', '1521279636', null, null, '1');
INSERT INTO `te_student` VALUES ('2', '4', '周泰', '1', '29', '15502012080', 'zhoutai@thinkedu.com', '1', '1521509400', '1521280636', null, null, '1');
INSERT INTO `te_student` VALUES ('3', '4', '孙尚香', '0', '23', '15502037040', 'sunshangxiang@thinkedu.com', '1', '1521509400', '1521281636', null, null, '1');

-- ----------------------------
-- Table structure for te_teacher
-- ----------------------------
DROP TABLE IF EXISTS `te_teacher`;
CREATE TABLE `te_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garde_id` int(11) DEFAULT NULL COMMENT '对应gardrs表id,班级id',
  `teacher_name` char(20) DEFAULT NULL COMMENT '老师姓名',
  `degree` tinyint(2) DEFAULT '0' COMMENT '学历 1 大专；2 本科；3 研究生；4 博士；0 其他',
  `tel` char(11) DEFAULT NULL COMMENT '手机号',
  `school` char(20) DEFAULT NULL COMMENT '毕业学校',
  `hiredate` int(11) DEFAULT NULL COMMENT '入职时间',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态 1 正常；0 关闭',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_delete` tinyint(2) DEFAULT '1' COMMENT '是否删除 1 正常；0 删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='老师管理表';

-- ----------------------------
-- Records of te_teacher
-- ----------------------------
INSERT INTO `te_teacher` VALUES ('1', '4', '白洁', '3', '13126698995', '对外经济贸易大学', '1521423000', '1', '1521274955', null, null, '1');
INSERT INTO `te_teacher` VALUES ('2', '2', '于耀翔', '2', '13126698997', '北京大学', '1521443000', '1', '1521284955', null, null, '1');
INSERT INTO `te_teacher` VALUES ('3', '3', '程雯', '4', '13126698992', '对外经济贸易大学', '1521453000', '1', '1521285955', null, null, '1');

-- ----------------------------
-- Table structure for te_user
-- ----------------------------
DROP TABLE IF EXISTS `te_user`;
CREATE TABLE `te_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL COMMENT '登录名',
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` char(11) DEFAULT NULL COMMENT '手机号',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `role` tinyint(2) DEFAULT '0' COMMENT '用户角色：0管理员；1超级管理员；2老师',
  `rank` varchar(20) DEFAULT NULL COMMENT '职位',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态：1启用；0禁用',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_delete` tinyint(2) DEFAULT '1' COMMENT '是否删除：0是；1否',
  `login_time` int(11) DEFAULT NULL COMMENT '登陆时间',
  `login_count` int(11) DEFAULT '0' COMMENT '登陆次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `手机号唯一` (`tel`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='管理员用户表';

-- ----------------------------
-- Records of te_user
-- ----------------------------
INSERT INTO `te_user` VALUES ('1', 'admin', '管理员', 'e10adc3949ba59abbe56e057f20f883e', 'admin@qq.com', '13126698991', '1500167997', '1521806286', '1', null, '1', null, '1', '1521806286', '23');
INSERT INTO `te_user` VALUES ('2', 'liupeng', '刘鹏', 'e10adc3949ba59abbe56e057f20f883e', 'liiupeng@qq.com', '13126698992', '1500168011', '1521622456', '0', null, '1', null, '1', '1521622456', '4');
INSERT INTO `te_user` VALUES ('3', 'zhaoyun', '赵云', 'e10adc3949ba59abbe56e057f20f883e', 'zhaoyun@thinkedu.com', '13126698993', '1500169011', '1521039960', '0', null, '0', '1521039960', '0', '1520149011', '0');
INSERT INTO `te_user` VALUES ('4', 'diaochan', '貂蝉', 'e10adc3949ba59abbe56e057f20f883e', 'diaochan@thinkedu.com', '13126698994', '1500267997', '1520522847', '0', null, '1', null, '1', '1520149011', '0');
INSERT INTO `te_user` VALUES ('5', 'baijie', '白洁', 'e10adc3949ba59abbe56e057f20f883e', 'baijie@thinkedu.com', '13126698995', '1520525968', '1520526118', '0', null, '1', null, '1', '1520526118', '1');
