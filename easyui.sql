-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 �?02 �?04 �?20:47
-- 服务器版本: 5.6.19
-- PHP 版本: 5.5.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hcf`
--

-- --------------------------------------------------------

--
-- 表的结构 `bf_auth_group`
--

CREATE TABLE IF NOT EXISTS `bf_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '' COMMENT '角色组名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用;1:启用)',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '规则id,和rule表关联',
  `describe` char(50) NOT NULL DEFAULT '' COMMENT '角色组描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `bf_auth_group`
--

INSERT INTO `bf_auth_group` (`id`, `title`, `status`, `rules`, `describe`) VALUES
(1, '超级管理员', 1, '', '拥有最大权限'),
(2, '默认组', 1, '3,7,8,22,23,32,37,39,40,41,42,44', '拥有常用权限'),
(3, '网站管理员', 1, '37,38,39,40', '拥有相对多的权限'),
(4, '编辑组', 1, '1,2,3,4,8,22,23', '拥有文章模块的所有权限'),
(5, '发布人员', 1, '3,4,6,8,22,23,35,36,40,41,42', '拥有发布、修改文章的权限'),
(6, '测试组', 1, '37,42', '测试专用组');

-- --------------------------------------------------------

--
-- 表的结构 `bf_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `bf_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '角色组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bf_auth_group_access`
--

INSERT INTO `bf_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- 表的结构 `bf_auth_rule`
--

CREATE TABLE IF NOT EXISTS `bf_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(1正常,0禁用)',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `state` varchar(8) NOT NULL DEFAULT '' COMMENT '菜单是否打开,有子级时,关闭',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- 转存表中的数据 `bf_auth_rule`
--

INSERT INTO `bf_auth_rule` (`id`, `name`, `title`, `type`, `status`, `condition`, `pid`, `state`) VALUES
(3, 'Admin/Auth/rule', '规则管理', 1, 1, '', 17, ''),
(4, 'Admin/Auth/userAdd', '用户添加', 1, 1, '', 17, ''),
(6, 'Admin/Auth/ruleDel', '规则删除', 1, 1, '', 17, ''),
(7, 'Admin/Auth/groupAdd', '角色添加', 1, 1, '', 17, ''),
(8, 'Admin/Auth/groupSave', '角色更新', 1, 1, '', 17, ''),
(22, 'Admin/Auth/index', '用户管理', 1, 1, '', 17, ''),
(15, '系统模块', '系统模块', 1, 1, '', 0, 'closed'),
(16, '文章模块', '文章模块', 1, 1, '', 0, 'closed'),
(17, '权限模块', '权限模块', 1, 1, '', 0, 'closed'),
(18, '会员模块', '会员模块', 1, 1, '', 0, 'closed'),
(19, '积分模块', '积分模块', 1, 1, '', 0, 'closed'),
(20, '其他', '其他', 1, 1, '', 0, 'closed'),
(23, 'Admin/Auth/group', '角色管理', 1, 1, '', 17, ''),
(29, 'Admin/Auth/userSave', '更新用户', 1, 1, '', 17, ''),
(30, 'Admin/Auth/userMove', '用户移动', 1, 1, '', 17, ''),
(31, 'Admin/Auth/userDel', '用户删除', 1, 1, '', 17, ''),
(32, 'Admin/Auth/ruleAdd', '规则添加', 1, 1, '', 17, ''),
(33, 'Admin/Auth/ruleSave', '规则更新', 1, 1, '', 17, ''),
(35, 'Admin/Auth/groupDel', '角色删除', 1, 1, '', 17, ''),
(36, 'Admin/Auth/AccessSet', '权限设置', 1, 1, '', 17, ''),
(37, 'Admin/Member/index', '会员管理', 1, 1, '', 18, ''),
(39, 'Admin/Member/addHandle', '会员添加', 1, 1, '', 18, ''),
(40, 'Admin/Member/editHandle', '会员编辑', 1, 1, '', 18, ''),
(41, 'Admin/Member/delHandle', '会员删除', 1, 1, '', 18, ''),
(42, 'Admin/Member/group', '会员组管理', 1, 1, '', 18, ''),
(44, 'Admin/Member/groupAdd', '会员组添加', 1, 1, '', 18, ''),
(49, 'Admin/System/clearCache', '删除缓存', 1, 1, '', 15, '');

-- --------------------------------------------------------

--
-- 表的结构 `bf_auth_user`
--

CREATE TABLE IF NOT EXISTS `bf_auth_user` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户账户',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `lastloginip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后一次登录ip',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `email` varchar(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `score` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `card` varchar(255) NOT NULL DEFAULT '' COMMENT '密保卡',
  `lang` varchar(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `bf_auth_user`
--

INSERT INTO `bf_auth_user` (`uid`, `username`, `password`, `lastloginip`, `lastlogintime`, `email`, `realname`, `score`, `card`, `lang`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f', '0.0.0.0', 1423053503, '4445@126.com', '老黄', 6000, '', ''),
(2, 'test001', '8a4cbfd19f0de75b55dae46bad0e', '0.0.0.0', 1422791964, 'xdsd@15.com', '黄生', 0, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `bf_member`
--

CREATE TABLE IF NOT EXISTS `bf_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `phpssouid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `encrypt` char(6) NOT NULL DEFAULT '',
  `nickname` char(20) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `lastip` char(15) NOT NULL DEFAULT '',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(32) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `areaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overduedate` int(10) unsigned NOT NULL DEFAULT '0',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `connectid` char(40) NOT NULL DEFAULT '',
  `from` char(10) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phpssouid` (`phpssouid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `bf_member`
--

INSERT INTO `bf_member` (`id`, `phpssouid`, `username`, `password`, `encrypt`, `nickname`, `regdate`, `lastdate`, `regip`, `lastip`, `loginnum`, `email`, `groupid`, `areaid`, `amount`, `point`, `modelid`, `message`, `islock`, `vip`, `overduedate`, `siteid`, `connectid`, `from`, `mobile`) VALUES
(1, 0, 'test001', '8a4cbfd19f0de75b55dae46bad0e', '', '', 0, 0, '', '', 0, 'sasq@126.com', 1, 0, '1000.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(2, 0, 'test002', '8a4cbfd19f0de75b55dae46bad0e', '', '', 0, 0, '', '', 0, 'sasq4@126.com', 4, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(3, 0, 'test003', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417885260, 0, '0.0.0.0', '', 0, 'sdsdkkj@126.com', 3, 0, '0.00', 0, 0, 0, 1, 0, 0, 1, '', '', ''),
(4, 0, 'test004', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417887562, 0, '0.0.0.0', '', 0, 'xdsd@126.com', 8, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(13, 0, 'test006', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417964470, 0, '0.0.0.0', '', 0, '456854@qq.com', 7, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(14, 0, 'test007', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417964504, 0, '0.0.0.0', '', 0, '445783@qq.com', 8, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(7, 0, 'test005', '8a4cbfd19f0de75b55dae46bad0e', '', '测试会员', 1417890742, 0, '0.0.0.0', '', 0, '8715567@qq.com', 2, 0, '0.00', 1000, 0, 0, 0, 1, 1418572799, 1, '', '', '4556684115'),
(8, 0, 'test008', '8a4cbfd19f0de75b55dae46bad0e', '', '空白格', 1417890758, 0, '0.0.0.0', '', 0, '87119959344@qq.com', 4, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(9, 0, 'test009', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417890782, 0, '0.0.0.0', '', 0, 'dsds2@sdsd.com', 3, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(10, 0, 'test010', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417890804, 0, '0.0.0.0', '', 0, 'xdsd2@126.com', 7, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(11, 0, 'test011', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1417893096, 0, '0.0.0.0', '', 0, '58555@126s.com', 5, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(12, 0, 'test012', '8a4cbfd19f0de75b55dae46bad0e', '', '张三的歌', 1417929274, 0, '0.0.0.0', '', 0, 'hhhhjjuuy@126.com', 2, 0, '2000.00', 0, 0, 0, 0, 1, 1419436799, 1, '', '', ''),
(15, 0, 'test013', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1418217872, 0, '0.0.0.0', '', 0, 'asxi665u@126.com', 3, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(21, 0, 'test', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1418489243, 0, '0.0.0.0', '', 0, 'xinkkj5@126.com', 1, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(18, 0, 'test014', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1418454304, 0, '0.0.0.0', '', 0, '58665@1261.com', 2, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(20, 0, 'test015', '8a4cbfd19f0de75b55dae46bad0e', '', '测试啊', 1418467500, 0, '0.0.0.0', '', 0, 'sds1@15.com', 4, 0, '0.00', 0, 0, 0, 1, 1, 1424966399, 1, '', '', ''),
(22, 0, 'admin', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1418489310, 0, '0.0.0.0', '', 0, 'sdsd@126.com', 4, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(23, 0, 'guest', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1418650730, 0, '0.0.0.0', '', 0, 'sdsdse@126.com', 1, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', ''),
(26, 0, 'tes6w6', '8a4cbfd19f0de75b55dae46bad0e', '', '', 1421760015, 0, '0.0.0.0', '', 0, 'sdsdwe@153.com', 7, 0, '0.00', 0, 0, 0, 1, 0, 0, 1, '', '', ''),
(27, 0, '棒棒棒棒', 'e10adc3949ba59abbe56e057f20f', '', '', 1422546679, 0, '0.0.0.0', '', 0, 'dsddsd@dsdsd.com', 2, 0, '0.00', 0, 0, 0, 0, 0, 0, 1, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `bf_member_group`
--

CREATE TABLE IF NOT EXISTS `bf_member_group` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(15) NOT NULL DEFAULT '',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `starnum` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `point` smallint(6) unsigned NOT NULL DEFAULT '0',
  `allowmessage` smallint(5) unsigned NOT NULL DEFAULT '0',
  `allowvisit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpostverify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowsearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowupgrade` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowsendmessage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpostnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `allowattachment` tinyint(1) NOT NULL DEFAULT '0',
  `price_y` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `price_m` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `price_d` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `icon` char(30) NOT NULL DEFAULT '',
  `usernamecolor` char(7) NOT NULL DEFAULT '',
  `description` char(100) NOT NULL DEFAULT '',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `disabled` (`disabled`),
  KEY `listorder` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `bf_member_group`
--

INSERT INTO `bf_member_group` (`id`, `name`, `issystem`, `starnum`, `point`, `allowmessage`, `allowvisit`, `allowpost`, `allowpostverify`, `allowsearch`, `allowupgrade`, `allowsendmessage`, `allowpostnum`, `allowattachment`, `price_y`, `price_m`, `price_d`, `icon`, `usernamecolor`, `description`, `sort`, `disabled`) VALUES
(1, '注册会员', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(2, '中级会员', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(3, '高级会员', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(4, '新手上路', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(5, '邮件认证', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(6, '禁止访问', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0),
(7, 'VIP组', 1, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 1, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 1, 0),
(8, '游客', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
