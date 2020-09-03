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
	'db_pass'	=> 'yzgj1988',	//数据库密码
	'db_base'	=> 'yzoa',	//数据库名称
	'perfix'	=> 'xinhu_',	//数据库表名前缀
	'qom'	=> 'xinhu_',	//session、cookie前缀
	'highpass'	=> 'byzlshw2019',	//超级管理员密码，可用于登录任何帐号  jkljkl111
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
    'designdetpid' =>"15,21,22,23,25,26,63,64",    //所有品牌设计师部门    0元贞 1贞筑 3梦依达   
    'designdetpid0' =>"21,22,25",    //元贞品牌设计师部门    
    'designdetpid1' =>"23",    //贞筑品牌设计师部门    
    'designdetpid2' =>"26",    //梦依达品牌设计师部门      
	'designdetpid3' =>"63",    //域嘉品牌设计师部门   
	'designdetpid4' =>"64",    //元贞局装品牌设计师部门    
    'shichangdetpid' =>"20,19,34",    //所有品牌家装顾问部门    
    'shichangdetpid0' =>"20",    //元贞品牌家装顾问部门    
    'shichangdetpid1' =>"20,19,20",    //贞筑品牌家装顾问部门    
    'shichangdetpid2' =>"34",    //梦依达品牌家装顾问部门    
    'jianlidetpid' =>"8,27,28,29,37,65",    //所有品牌监理部门    
    'jianlidetpid0' =>"29",    //元贞品牌监理部门    
    'jianlidetpid1' =>"27",    //贞筑品牌监理部门    
    'jianlidetpid2' =>"28",    //梦依达品牌监理部门
    'jianlidetpid3' =>"37",    //域嘉品牌监理部门
    'jianlidetpid4' =>"65",    //元贞局装品牌监理部门
    'flow_finish' => '79,74,69',    //已完工流程id，工地列表分类，已完工和在建工地  
	'flow_nostart' => '76,82,83,48,124,125,126',    //把元贞，域嘉，贞筑流程在【开工仪式】之前的所有工地显示为未开工地，元贞局装流程在【开工保护】之前的所有工地显示为未开工地       
	'kefudeptid' =>array(11),    //客服访信息添加日志时，自动消息提醒设计师    
    'oadeptid' =>array(12,18,36),    //一些特殊字段不可见的部门ID	OA［形象建设部］［预算部］	［巡检部］	    
	'high_view' => array(1,15,27,28,188,12,14,187,5,10),    //筛选设计师和工程监理高级权限userid    
    'crmdeptid' =>array(15,19,20,21,22,2,23,25,26),    //一些特殊字段不可见的部门ID	CRM［设计部］［市场部］［门店］	  
    'rgfeeflowid' =>array(95,96,100,106,111,140,144),    //需要人工费清单制作的流程ID    20181102取消了112剩余人工确认  20190911新增局装人工费140
    'rgfeeclflowid' =>array(58,65),    //人工费材料变更制作的流程ID-105   20180409换成了硬装工地流程   58,65
    'rzdetpid' =>array(26,28,34,31),    //软装部门ID-----crm相关软装帐号，单源状态筛选时应自动匹配软装状态    
    'clpfflowid' =>array(50,52,56,62,129),    //材料派发制作的流程ID       20190816,新增元贞局装 
    'clpfuid' =>array(1,15),    //材料派发制作的相关人员ID         
    'rgfeeuserid' =>array(1,12,15,16,24),    //工费只有   admin   财务  殷名煌  黄友光 有权限      监理   写代码中的   20190624,新增史节法 
    'anzflowid' =>array(58,65,73,132,135),    //安装测量制作的流程ID        20190816,新增元贞局装 
    'anzuid' =>array(1,10),    //安装测量制作的相关人员ID     
    'anzonly' =>array(73,135),    //只是安装          
    'xiadanuid' =>array(1,15,10),    //主材和辅材派发按钮可见         
    'changeurlstruid' =>array(1,15,10,12),    //材料配送切换工地详情    管理员，财务，史节法，徐鹏飞     
	'celonly' =>array(58,65,132,990,991,992,993,994),    //只是测量      加铝合金990 瓷砖991 石材测量58 
    'anzonlynum' =>"73,135",    //只是安装      字符串版     
    'celonlynum' =>"58,65,132,990,991,992,993,994",    //只是测量    字符串版         
    'buildinnum' =>"73,135,58,65,132,990,991,992,993,994",    //所有buildin    字符串版           
    'anzcl' =>array(
                '38'=>array('day1'=>5,'day2'=>2,'day3'=>5,'day4'=>5,'day5'=>2,'day6'=>2),//供应商部门  day1原测量 day2原安装 day3测量局装 day4测量整屋 day5安装局装 day6安装整屋
                '46'=>array('day1'=>7,'day2'=>15,'day3'=>7,'day4'=>7,'day5'=>15,'day6'=>15),//楼梯墙裙：测量（含设计确认）周期7天；安装周期15天。
                '47'=>array('day1'=>5,'day2'=>2,'day3'=>5,'day4'=>5,'day5'=>2,'day6'=>2),//瓷砖：测量（含设计确认）周期5天；安装（送货）周期2天。
                '48'=>array('day1'=>5,'day2'=>3,'day3'=>3,'day4'=>10,'day5'=>3,'day6'=>10),//大 理 石：测量（含设计确认）周期5天；安装（送货）周期3天。局部测量3天，全屋测量10天   局部施工3天，全屋施工10天
                '49'=>array('day1'=>7,'day2'=>10,'day3'=>7,'day4'=>7,'day5'=>10,'day6'=>10),//整屋定制：测量（含设计确认）周期7天；安装周期10天。
                '50'=>array('day1'=>7,'day2'=>8,'day3'=>7,'day4'=>7,'day5'=>8,'day6'=>8),//木    门：测量周期7天；安装周期8天。
                '51'=>array('day1'=>3,'day2'=>9,'day3'=>3,'day4'=>3,'day5'=>9,'day6'=>9),//移门淋浴房：测量周期3天；安装周期9天。
                '52'=>array('day1'=>3,'day2'=>8,'day3'=>3,'day4'=>3,'day5'=>8,'day6'=>8),//地    板：测量周期3天；安装周期8天。
                '61'=>array('day1'=>3,'day2'=>3,'day3'=>3,'day4'=>3,'day5'=>3,'day6'=>3),//7.  扣    板：测量周期3天；安装周期3天
                '53'=>array('day1'=>7,'day2'=>15,'day3'=>3,'day4'=>10,'day5'=>7,'day6'=>15),//楼梯墙裙：测量（含设计确认）周期7天；安装周期15天。
                '54'=>array('day1'=>5,'day2'=>7,'day3'=>3,'day4'=>7,'day5'=>3,'day6'=>7),//墙纸软包：测量（含设计确认）周期5天；安装周期7天。
                '55'=>array('day1'=>3,'day2'=>7,'day3'=>3,'day4'=>7,'day5'=>5,'day6'=>15),//硅 藻 泥：测量（含设计确认）周期3天；安装周期7天。
                '56'=>array('day1'=>7,'day2'=>15,'day3'=>3,'day4'=>10,'day5'=>3,'day6'=>10),//集成墙卡：测量（含设计确认）周期7天；安装周期15天。
                '57'=>array('day1'=>2,'day2'=>5,'day3'=>2,'day4'=>7,'day5'=>3,'day6'=>7),//12. 石 膏 线：测量周期2天；安装周期5天
                '58'=>array('day1'=>0,'day2'=>2,'day3'=>0,'day4'=>1,'day5'=>2,'day6'=>3),//13. 保    洁：测量周期0天；安装周期2天
                '59'=>array('day1'=>3,'day2'=>7,'day3'=>3,'day4'=>3,'day5'=>7,'day6'=>7),//14. 阳 光 房：测量周期3天；安装周期7天
                '60'=>array('day1'=>3,'day2'=>3,'day3'=>3,'day4'=>3,'day5'=>3,'day6'=>3),//15. 洁    具：测量周期3天；安装周期3天。

            ),    //安装测量制作相关供应商及对应工期         

    'clsdeptid' =>array(38,47,60,59,58,57,56,55,54,53,52,51,50,49,48,61,39,62),    //材料商的部门ID    去掉辅材35,
    'cls_fields' =>array('rzmendian','rzdesigner','rzstatus','mendian','marker','kfmanager','gddesigner','budgettype','fpdate','laiyuan'),    //材料商不可见的字段      
    'clskefuid' =>"513",    //材料商的专属客服userID    
    'kehuglDeptid' =>array(5,11,19,20,34),    //客户状态管理的相关deptID   
    'dept_fields' =>array(
        // 客户
        'changemendian'=>"1,2,32,30",  //硬装门店 
        'changerzmendian'=>"1,2,31", //软装门店
        'changekfmanager'=>"1,11",//客服经理
        'changemarker'=>"1,19,20,34,11",//硬装顾问
        'changerzmarker'=>"1,19,20,34",//软装顾问
        'changegddesigner'=>"1,15,25,21,22,23,26,63,64",//硬装设计师
        'changerzdesigner'=>"1,15,26",//软装设计师
        // 在建工地
        'changechuban'=>"1,14",//业主姓名
        'changexunjian'=>"1,36",//巡检部
        'changemdarea'=>"1,2,32,30,31",//门店经理
        'changedesigner'=>"1,15,25,21,22,23,26,63,64",//设计师
        'changeauthor'=>"1,8,37,27,29,37,65",//工程监理、安装工
        'changehtys'=>"1,18",//预算员
    ),//每个字段对应的部门id 



);