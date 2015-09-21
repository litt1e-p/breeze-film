<?php
	require_once('sysConfig.php');
	$config=array(
		//'配置项'=>'配置值'
			'DEFAULT_AJAX_RETURN'=>'JSON',
			'SHOW_PAGE_TRACE'=>true,    // 显示页面Trace信息
			'URL_MODEL'=>'2', //URL模式
			'URL_CASE_INSENSITIVE'=>false, //URL访问不再区分大小写
			'DB_PARAMS'=>array(\PDO::ATTR_CASE=>\PDO::CASE_NATURAL),
			'MODULE_ALLOW_LIST'=>array(
					'Home',
					'Admin',
					'Cdn'
			),
			'DEFAULT_MODULE'=>'Cdn',
			//权限验证设置
			'AUTH_CONFIG'=>array(
		        'AUTH_ON'=>true, //认证开关
		        'AUTH_TYPE'=>1, // 认证方式，1为时时认证；2为登录认证。
		        'AUTH_GROUP'=>$sysConfig['DB_PREFIX'].'auth_group', //用户组数据表名
		        'AUTH_GROUP_ACCESS'=>$sysConfig['DB_PREFIX'].'auth_group_access', //用户组明细表
		        'AUTH_RULE'=>$sysConfig['DB_PREFIX'].'auth_rule', //权限规则表
		        'AUTH_USER'=>$sysConfig['DB_PREFIX'].'auth_user'//用户表
		    ),
		    //超级管理员id,拥有全部权限,只要用户uid在这个角色组里的,就跳出认证.可以设置多个值,如array('1','2','3')
		    'ADMINISTRATOR'=>array('1'),
		    //不需要认证的规则
		    'NO_AUTH_RULES'=>array(
		    	'Admin/Index/index',//后台首页
		    	'Admin/Index/logout',//退出
		    	'Admin/Auth/userList',//用户列表
		    	'Admin/Auth/groupList',//角色列表
		    	'Admin/Auth/ruleList',//规则列表
		    	'Admin/Auth/accessList',//权限列表
		    	'Admin/Auth/checkEmail',//检测邮箱
		    	'Admin/Auth/checkRule',//检测规则
		    	'Admin/Auth/checkGroup',//检测角色
		    	'Admin/Auth/checkUser',//检测用户
		    	'Admin/Member/memberList',//会员列表
		    	'Admin/Member/groupList',//会员组列表
		    	'Admin/Member/checkUser',//检测会员
		    	'Admin/Member/checkGroup',//检测会员组
		    	'Admin/Member/checkEmail',//检测邮箱
		    ),

	);
	return array_merge($config,$sysConfig);
