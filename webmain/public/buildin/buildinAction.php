<?php 
class buildinClassAction extends Action{
	
	/**
	*	安装测量制作页
	*/
	public function defaultAction()
	{
		$params['fid'] 	= $this->get('fid');
		$params['cid'] 		= $this->get('cid');
		$this->title 			= '安装测量';

		if(!$params['cid']||!$params['fid'])exit('sorry!参数错误，请退出重试!!!');

		//获取工地详情
		$flowinfo = m('flowbill')->getone("`id`=".$params['fid'],'*');
		$gongdiinfo = m($flowinfo['table'])->getone("`id`=".$flowinfo['mid'],'*');

		$mobile = m('userinfo')->getone("`name`='".$gongdiinfo['author']."'",'mobile');

		$fenleilist='';

		$res=$this->getCourse($params['cid']); 
		$this->title 			= $res['title'];
		// 只列出对应的供应商              获取材料供应商
		if ($res['clgysdeptid']) {
			$clgys = m('admin')->getall("`deptid`=".$res['clgysdeptid'],'*');
		}else{
			$clgys = m('admin')->getall("instr(`deptpath`,'[38]')>0 order by deptid",'*');
		}
		
		/*if ($res['pid']) {
			//获取所有工作项目
			$fenleilist1 = m('option')->getall("`pid`=".$res['pid'],'name,id');
			$data=array();
			foreach ($fenleilist1 as $key => $value) {
				$rows = m('goods')->getall("`typeid`=".$value['id'],'name,id,typeid,price,unit');
				$data=array_merge($data,$rows);
			}
		}elseif ($res['typeid']) {
			$rows = m('goods')->getall("`typeid`=".$res['typeid'],'name,id,typeid,price,unit');
			//$data=json_encode($rows);
			$data=$rows;
		}*/
			//var_dump($data);die();

		$anzonly=getconfig('anzonly');
		if (in_array($params['cid'], $anzonly)) {
			//获取历史测量数据 待安装的 
			$celiang = m('buildin')->getall("`fid`=".$params['fid']." and `buidintype`=1 and `progress`=2",'*');
		}
                // var_dump($celiang);                           

		$this->assign('celiang', $celiang);
		$this->assign('mobile', $mobile['mobile']);
		$this->assign('flowinfo', $flowinfo);
		$this->assign('gongdiinfo', $gongdiinfo);
		$this->assign('isheader', 1);
		$this->assign('type', 0);
		$this->assign('params', $params);
		$this->assign('flag', $res['flag']);
		$this->assign('clgys', $clgys);
		$this->assign('fenleilist', $fenleilist);
		$this->assign('data', $data);
	}
	/**
	*	退货
	*/
	public function tuihuoAction()
	{
		$params['fid'] 	= $this->get('fid');
		$params['cid'] 		= $this->get('cid');
		$this->title 			= '通知';

		if(!$params['cid']||!$params['fid'])exit('sorry!参数错误，请退出重试!!!');

		//获取工地详情
		$flowinfo = m('flowbill')->getone("`id`=".$params['fid'],'*');
		$gongdiinfo = m($flowinfo['table'])->getone("`id`=".$flowinfo['mid'],'*');

		//获取材料供应商
		// $clgys = m('admin')->getall("`deptid`=38",'*');
		$clgys = m('admin')->getall("instr(`deptpath`,'[38]')>0",'*');

		$res=$this->getCourse($params['cid']); 
		$this->title 			= $res['title'].'取消';

		$this->assign('flowinfo', $flowinfo);
		$this->assign('gongdiinfo', $gongdiinfo);
		$this->assign('isheader', 1);
		$this->assign('type', 1);
		$this->assign('params', $params);
		$this->assign('clgys', $clgys);
		$this->assign('fenleilist', $fenleilist);
		$this->assign('flag', $res['flag']);
		$this->assign('data', $data);
		$this->assign('callback', $params['callback']);
		$this->displayfile = ''.P.'/public/buildin/tpl_buildin.html';			
	}
	/**
	*	历史记录
	*/
	public function historyAction()
	{
		$params['fid'] 	= $this->get('fid');
		$params['cid'] 		= $this->get('cid');

		$res=$this->getCourse($params['cid']); 
		$this->title 			= $res['title'].'记录';

		if(!$params['cid']||!$params['fid'])exit('sorry!参数错误，请退出重试!!!');
		//获取工地详情
		$buildin = m('buildin')->getall("`fid`=".$params['fid']." and `cid`=".$params['cid'],'*','id asc');
		$flowinfo = m('flowbill')->getone("`id`=".$params['fid'],'*');
		$gongdiinfo = m($flowinfo['table'])->getone("`id`=".$flowinfo['mid'],'*');

		
		$clpfuid=getconfig('anzuid');
		$isinu = in_array($this->adminid,$clpfuid);
		
		//var_dump($gongdiinfo);die();
		$this->assign('isheader', 1);
		$this->assign('isinu',$isinu);
		$this->assign('params', $params);
		$this->assign('flag', $res['flag']);
		$this->assign('buildin', $buildin);
		$this->assign('gongdiinfo', $gongdiinfo);
		/*if($this->rock->ismobile()){
			$this->displayfile = ''.P.'/public/fileopen.html';			
		}
		var_dump($this->displayfile);*/
	}
	
	/**
	*	表单提交
	*/
	public function buildinsaveAction()
	{

		// Q1:原测量记录状态未变更
		$msg	= '';
		$success= false;
		$params['fid'] 	= $this->get('fid');
		$params['cid'] 		= $this->get('cid');
		if(!$params['cid']||!$params['fid'])exit('sorry!参数错误，请退出重试!!!');
		$params['type'] 		= $this->post('type');
		$params['zxstyle'] 		= $this->post('zxstyle');
		$params['ralatedid'] 		= $this->post('ralatedid');
		$params['alltotal'] 		= $this->post('alltotal');
		$params['goods'] 		= $this->post('goods');
		$params['totalprice'] 		= $this->post('totalprice');
		//选择供应商
		$params['clgysid'] 		= $this->post('clgysid');
		$params['clgysname'] 		= $this->post('clgysname');
		$this->title 			= '清单制作';

		$anzonly=getconfig('anzonly');
		if ($params['type']==1&&in_array($params['cid'], $anzonly)) {
			$params['alltotal']=-abs($params['alltotal']);
			$params['totalprice']=-abs($params['totalprice']);
		}
		if (in_array($params['cid'], $anzonly)) {
			$params['buidintype'] 		= 2; //类型 1测量 2安装
			$params['progress'] 		= 3; //工地进度：1测量中 2带安装 3安装中 4已安装 5已完成
			
		}else{
			$params['buidintype'] 		= 1; //类型 1测量 2安装
			$params['progress'] 		= 1; //工地进度：1测量中 2带安装 3安装中 4已安装 5已完成			
		}

		//获取工地详情
		$flowinfo = m('flowbill')->getone("`id`=".$params['fid'],'*');
		$gongdiinfo = m($flowinfo['table'])->getone("`id`=".$flowinfo['mid'],'*');
		//重组要保存的数组
		$params['mid'] 		= $flowinfo['mid'];
		$params['sericnum'] 		= $flowinfo['sericnum'];
		$params['table'] 		= $flowinfo['table'];
		$params['modeid'] 		= $flowinfo['modeid'];
		$params['modename'] 		= $flowinfo['modename'];
		$params['createdt'] 		= date('Y-m-d H:i:s');
		$params['optid'] 		= $this->adminid;
		$params['optname'] 		= $this->adminname;
		$params['buildtime'] 		= $this->post('buildtime');
		$params['status'] 		= $params['type']==1?2:0;
		
		$params['yzbrand'] 		= $gongdiinfo['yzbrand'];
		$params['author'] 		= $gongdiinfo['author'];
		$params['title'] 		= $gongdiinfo['title'];
		$params['chuban'] 		= $gongdiinfo['chuban'];
		$params['weizhi'] 		= $gongdiinfo['weizhi'];
		$params['routeline'] 		= $gongdiinfo['routeline'];
		$params['telephone'] 		= $gongdiinfo['telephone'];
		$params['designer'] 		= $gongdiinfo['designer'];

		$res = m('buildin')->insert($params);

		if ($res) {
			$type= $params['type']==1?'取消':'通知';
			$flag= in_array($params['cid'], $anzonly)?'安装':'测量';

			if ($params['ralatedid']) {
				$data = m('buildin')->update(array(
					'progress' 	=> 3,
				), $params['ralatedid']);
			}
			//happy_add 短信通知材料供应商
			if ($type==1) {
				$cont=$gongdiinfo['author'].'监理的'.$gongdiinfo['title'].'工地'.$flag.'有项目取消，请及时登录后台查看并处理，谢谢！';
			}else {
				$cont=$gongdiinfo['author'].'监理的'.$gongdiinfo['title'].'工地需要进行现场'.$flag.'，请及时登录后台查看并处理，谢谢！';
			}
			m('sms')->postsms('安装测量通知申请人', $cont, $params['clgysid']);
			$success=true;
			$msg="提交成功";
		}else{
			$msg="提交失败，请重新提交";

		}

			//var_dump($params);die();
		$arr = array('success'=>$success,'msg'=>$msg);

		$this->returnjson($arr);
	}

	//获取当前流程
	protected function getCourse($cid)
	{

		$res['pid']='';
		$res['typeid']='';
		switch ($cid) {
			case '992':
				$res['title'] 			= '铝合金测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '59';//阳光房供货商
				// $res['typeid']='287';
				break;
			case '990':
				$res['title'] 			= '铝合金测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '59';//阳光房供货商
				// $res['typeid']='287';
				break;
			case '993':
				$res['title'] 			= '瓷砖测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '47';//瓷砖供货商
				// $res['typeid']='287';
				break;
			case '991':
				$res['title'] 			= '瓷砖测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '47';//瓷砖供货商
				// $res['typeid']='287';
				break;
			case '994':
				$res['title'] 			= '石材测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '48';//大理石供货商
				// $res['typeid']='287';大理石供货商
				break;
			case '58':
				$res['title'] 			= '石材测量';
				$res['flag'] 			= '测量';
				$res['clgysdeptid'] 			= '48';//大理石供货商
				// $res['typeid']='287';大理石供货商
				break;
			case '65':
				$res['title'] 			= '安装工程测量';
				$res['flag'] 			= '测量';
				// $res['pid']='288';
				break;
			case '132':
				$res['title'] 			= '安装工程测量';
				$res['flag'] 			= '测量';
				// $res['pid']='288';
				break;
			case '73':
				$res['title'] 			= '通知安装';
				$res['flag'] 			= '安装';
				// $res['pid']='289';
				break;
			case '135':
				$res['title'] 			= '局装安装';
				$res['flag'] 			= '安装';
				// $res['pid']='289';
				break;			
			default:
				$res['title'] 			= '安装测量';
				$res['flag'] 			= '测量';
				// $res['pid']=='288';
				break;
		}
		return $res;
	}
	
	//导出清单详情
	public function createAction()
	{
		$id  = (int)$this->jm->gettoken('id');
		$field  = $this->jm->gettoken('field');
		$rows = $this->db->getone('[Q]flow_log',"`id`='$id'", '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`','`id` desc');

		if ($field=='clupdatelist') {
			$filename = '主材升级'.date('YmdHis');  
			$header = array('主材名称','预算单价','选样单价','预算数量','实际数量','变更金额','小计','备注');  
			$index = array('goods','yusuanprice','xuanyangprice','yusuannum','shijinum','cha','total','explain');  
		}else{
			$filename = '人工费清单'.date('YmdHis');  
			$header = array('工作项目','预算工作量','实际工作量','变更工作量','单价','单位','小计','备注');  
			$index = array('goods','yusuan','shiji','cha','price','unit','total','explain'); 
		}
		$this->createtableAction($rows,$filename,$header,$index,$field); 
	}
	
	//生成html预览文件
	public function createhtmlAction()
	{
		$id  = (int)$this->jm->gettoken('id');
		$field  = $this->jm->gettoken('field');
		$rows = $this->db->getone('[Q]flow_log',"`id`='$id'", '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`','`id` desc');

		if ($field=='clupdatelist') {
			$filename = '主材升级'.date('YmdHis');  
			$header = array('主材名称','预算单价','选样单价','预算数量','实际数量','变更金额','小计','备注');  
			$index = array('goods','yusuanprice','xuanyangprice','yusuannum','shijinum','cha','total','explain');  
		}else{
			$filename = '人工费清单'.date('YmdHis');  
			$header = array('工作项目','预算工作量','实际工作量','变更工作量','单价','单位','小计','备注');  
			$index = array('goods','yusuan','shiji','cha','price','unit','total','explain'); 
		}

		$list=json_decode($rows[$field]);
		$list=json_decode(json_encode($list),TRUE);
        $strexport="<table><tr><td>";  
		  
	    $teble_header = implode("<td>",$header);  
	    $strexport .= $teble_header;
        $strexport.="</tr>";   

		foreach($list AS $row) {
			if(isset($row['goods'])){

		        $strexport.="<tr>";  
			  	foreach($index as $val){  
		            $strexport.="<td>".$row[$val]."</td>";     
		        }  
		        $strexport.="</tr>";   
		    }  
	        
		}

        $strexport.="<tr><td></td><td></td><td></td><td></td><td></td><td></td>";   
        $strexport.='<td>商定总价'.$rows['totalprice']."</td>";     
        $strexport.='<td>合计'.$rows['alltotal']."</td></tr>";  
		echo $strexport.'</table>';
		//$this->smartydata['strexport'] = $strexport;

		//echo '<table>'.implode('', $table).'</table>';
		//$this->createtableAction($rows,$filename,$header,$index,$field); 
	}
	

	protected function createtableAction($rows,$filename,$header=array(),$index = array(),$field){    
	    header("Content-type:application/vnd.ms-excel");    
	    header("Content-Disposition:filename=".$filename.".xls");    
	    $teble_header = implode("\t",$header);  
	    $strexport = $teble_header."\r";  

		$list=json_decode($rows[$field]);
		$list=json_decode(json_encode($list),TRUE);
		//var_dump($list);die();
	    foreach ($list as $row){   
			if(isset($row['goods'])){

				//$row=json_decode(json_encode($row),TRUE); 
		        foreach($index as $val){  
		            $strexport.=$row[$val]."\t";     
		        }  
		        $strexport.="\r"; 
		    }  
	  
	    }    
        $strexport.="\r\t\t\t\t\t\t";   
        $strexport.='商定总价'.$rows['totalprice']."\t";     
        $strexport.='合计'.$rows['alltotal']."\t";     

	    $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
	    exit($strexport);       
	}
}