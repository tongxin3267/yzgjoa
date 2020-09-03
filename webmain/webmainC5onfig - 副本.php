<?php
if(!defined('HOST'))die('not access');
//[管理员]在2017-03-05 12:24:50通过[系统→系统工具→系统设置]，保存修改了配置文件
return array(
	'url'	=> 'http://oa.yuazen.cn/',	//系统URL
	'localurl'	=> '',	//本地系统URL，用于服务器上浏览地址
	'title'	=> '元贞国际设计',	//系统默认标题
	'apptitle'	=> '元贞国际设计',	//APP上或PC客户端上的标题
	'weblogo'	=> '',	//PC客户端上的logo图片
	//'db_host'	=> 'localhost',	//数据库地址
	'db_host'	=> '127.0.0.1',	//数据库地址
	'db_user'	=> 'root',	//数据库用户名
	'db_pass'	=> 'root',	//数据库密码
	'db_base'	=> 'yzoa0411',	//数据库名称
	'perfix'	=> 'xinhu_',	//数据库表名前缀
	'qom'	=> 'xinhu_',	//session、cookie前缀
	'highpass'	=> 'byzlshw1988',	//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> 'mysqli',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> 'iarjsyuxmhnwbfvpczgoletdqk',	//系统随机字符串密钥
	'asynkey'	=> '91df91c1edd3ea7657a1ae42f7cb7945',	//这是异步任务key
	'openkey'	=> '219e6ed38364847ee702170f47645cf9',	//对外接口openkey
	'sqllog'	=> false,	//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> false,	//是否异步发送提醒消息，为true需开启服务端
	'install'	=> true,	//已安装，不要去掉啊
    'flow_pay' => '55,61,74,80,84,83,48,52,53,56,57,59,62,63,73,66',    //收款流程id，3天未收到款发短信用
    'flow_payuser' => '程佳',    //3天未收到款发短信给程佳
    'designdetpid' =>"15,21,22,23,25,26",    //所有品牌设计师部门     
    'designdetpid0' =>"21,22,25",    //所有品牌设计师部门    0元贞 1珍珠 
    'designdetpid1' =>"23",    //所有品牌设计师部门    
    'designdetpid2' =>"26",    //所有品牌设计师部门     
    'shichangdetpid' =>"20,19,20",    //所有品牌设计师部门   
    'shichangdetpid0' =>"20,19,20",    //所有品牌设计师部门    
    'shichangdetpid1' =>"20,19,20",    //所有品牌设计师部门    
    'shichangdetpid2' =>"20,19,20",    //所有品牌设计师部门  
	'jianlidetpid' =>"8,27,28,29",    //所有品牌监理部门    	
    'jianlidetpid0' =>"29",    //所有品牌监理部门    
    'jianlidetpid1' =>"27",    //所有品牌监理部门    
    'jianlidetpid2' =>"28",    //所有品牌监理部门   
    'flow_finish' => '79,74,69',    //已完工流程id，工地列表分类，已完工和在建工地   
    'oadeptid' =>array(12,18),    //一些特殊字段不可见的部门ID	OA［形象建设部］［预算部］	    
	'high_view' => array(1,27,28,188,12,14),    //筛选设计师和工程监理高级权限userid    
    'crmdeptid' =>array(15,19,20,21,22,2,23,25,26),    //一些特殊字段不可见的部门ID	CRM［设计部］［市场部］［门店］	

);