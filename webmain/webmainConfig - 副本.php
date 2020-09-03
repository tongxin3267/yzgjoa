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
	'db_base'	=> 'yzoa1006',	//数据库名称
	'perfix'	=> 'xinhu_',	//数据库表名前缀
	'qom'	=> 'xinhu_',	//session、cookie前缀
	'highpass'	=> '123123',	//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> 'mysqli',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> 'iarjsyuxmhnwbfvpczgoletdqk',	//系统随机字符串密钥
	'asynkey'	=> '91df91c1edd3ea7657a1ae42f7cb7945',	//这是异步任务key
	'openkey'	=> '219e6ed38364847ee702170f47645cf9',	//对外接口openkey
	'sqllog'	=> false,	//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> false,	//是否异步发送提醒消息，为true需开启服务端
	'install'	=> true,	//已安装，不要去掉啊
    'flow_pay' => '83,48,52,53,56,57,59,62,63,73,66',    //收款流程id，3天未收到款发短信用  
    //'flow_pay' => '55,61,74,80,84,83,48,52,53,56,57,59,62,63,73,66',    //收款流程id，3天未收到款发短信用  
	'flow_pay2user' => '55,61,74,80,84',    //收款流程id，3天未收到款发短信通知用户
    'flow_payuser' => '程佳',    //3天未收到款发短信给程佳
    'designdetpid' =>"15,21,22,23,25,26",    //所有品牌设计师部门    0元贞 1贞筑 3梦依达   
    'designdetpid0' =>"21,22,25",    //元贞品牌设计师部门    
    'designdetpid1' =>"23",    //贞筑品牌设计师部门    
    'designdetpid2' =>"26",    //梦依达品牌设计师部门    
    'shichangdetpid' =>"20,19,20,34",    //所有品牌家装顾问部门    
    'shichangdetpid0' =>"20",    //元贞品牌家装顾问部门    
    'shichangdetpid1' =>"20,19,20",    //贞筑品牌家装顾问部门    
    'shichangdetpid2' =>"34",    //梦依达品牌家装顾问部门    
    'jianlidetpid' =>"8,27,28,29",    //所有品牌监理部门    
    'jianlidetpid0' =>"29",    //元贞品牌监理部门    
    'jianlidetpid1' =>"27",    //贞筑品牌监理部门    
    'jianlidetpid2' =>"28",    //梦依达品牌监理部门
    'flow_finish' => '79,74,69',    //已完工流程id，工地列表分类，已完工和在建工地       
	'kefudeptid' =>array(11),    //客服访信息添加日志时，自动消息提醒设计师    
    'oadeptid' =>array(12,18,36),    //一些特殊字段不可见的部门ID	OA［形象建设部］［预算部］	    
	'high_view' => array(1,15,27,28,188,12,14,187,5,10),    //筛选设计师和工程监理高级权限userid    
    'crmdeptid' =>array(15,19,20,21,22,2,23,25,26),    //一些特殊字段不可见的部门ID	CRM［设计部］［市场部］［门店］	  
    'rgfeeflowid' =>array(95,96,100,106,111,112,81,79,48,82),    //需要人工费清单制作的流程ID    
    'rgfeeclflowid' =>array(58,65),    //人工费材料变更制作的流程ID-105   20180409换成了硬装工地流程   
    'rzdetpid' =>array(26,28,34,31),    //软装部门ID-----crm相关软装帐号，单源状态筛选时应自动匹配软装状态      
    'clpfflowid' =>array(50,52,56,62),    //材料派发制作的流程ID      
    'clpfuid' =>array(1,15),    //材料派发制作的相关人员ID     
    'rgfeeuserid' =>array(1,12,16),    //工费只有   admin   财务     监理   殷名煌 有权限       



);