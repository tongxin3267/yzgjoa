<?php
class modeClassAction extends ActionNot
{
	public function initAction()
	{
		$aid 	= (int)$this->get('adminid');
		$token 	= $this->get('token');
		$aid 	= m('login')->autologin($aid, $token);
		
		if($aid==0){
			$this->mweblogin(1);
		}
		$this->getlogin(1);
	}

	public function defaultAction()
	{
		$fn	 	= $this->get('fn');
		$title 	= $this->rock->jm->base64decode($this->get('title'));
		if($title!='')$this->title = $title;
		$path 	= P.'/task/mode/html/'.$fn.'.html';
		if(!file_exists($path))exit('not found '.$fn.'');
		$this->displayfile = $path;
	}
	public function testAction()
	{
		$this->displayfile = $path;		
	}
	
	//移动端页面详情
	public function xAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');
		
		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		
		
		$arr 	 = m('flow')->getdatalog($num, $mid, 1);
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		if ($arr['changeurlstr']) {
			$arr['changeurlstr']	= '?a=x'.$arr['changeurlstr'];
		}
		$this->smartydata['arr'] = $arr;
		
		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_1.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$isheader = 0;
		if($this->web != 'wxbro' && $this->get('show')=='we')$isheader=1;
		$this->assign('isheader', $isheader);
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
	}
	
	//材料供应商配送移动端页面详情
	public function clpfAction()
	{
		$num = $title=$this->get('num');
		$mid 	 = (int)$this->get('mid');
		$clpaifa = m($title)->getone("`id`=".$mid,'*');
		$pagetitle 		= $clpaifa['title'];
		$this->title 	= $clpaifa['title'];
		if($pagetitle=='')$pagetitle = $clpaifa['modename'];
		$this->smartydata = $clpaifa;


		
		$arr 	 = m('flow')->getdatalog($num, $mid, 1);
		$this->smartydata['arr'] = $arr;
		if($title!="clpaifa"){

			//倒计时计算 begin				

			$anzcl=getconfig('anzcl');
			$anzonly=getconfig('anzonly');
			$clpaifaresult=$this->calcdiff($anzcl,$anzonly,$clpaifa);
			//倒计时计算 end		             
			//关联测量 begin	
			if (!empty($clpaifa['ralatedid'])) {
				$ralatedinfo = m($num)->getone("`id`=".$clpaifa['ralatedid'],'*');
				$this->smartydata['ralatedinfo'] = $ralatedinfo;
				
				//倒计时计算 begin
				$ralatedresult=$this->calcdiff($anzcl,$anzonly,$ralatedinfo,$suffix='');

				$this->assign('ralatedresult', $ralatedresult);
				//倒计时计算 end
	
			}
			// var_dump($ralatedresult['info']);die;
			//关联测量 end		

			$this->assign('clpaifaresult', $clpaifaresult);
			$this->assign('day', $clpaifaresult['day']);
		}

		$changeurlstruid=getconfig('changeurlstruid');
		if (in_array($this->adminid, $changeurlstruid)) {
			# code...
			// $show   =$this->get('show')=='we'?'x':'p';
			$show  = 'x';
	        $changeurlstr = '?a='.$show.'&num='.$clpaifa['table'].'&mid='.$clpaifa['mid'].'';  
			$this->assign('changeurlstr', $changeurlstr);
		}

		$isheader = 0;
		if($this->web != 'wxbro' && $this->get('show')=='we')$isheader=1;
		$this->assign('isheader', $isheader);
		$this->assign('adminid',$this->adminid);
		
		$author=$clpaifa['author'];
		$jianli = m('userinfo')->getone("`name`='$author'",'mobile');
		
		$this->assign('mobile', $jianli['mobile']);
		if($title!="clpaifa"){
			$spagepath 	= P.'/task/mode/tpl_mode_'.$title.'.html';
			$this->displayfile = $spagepath;
		}
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		
	}

	//pc端页面详情
	public function pAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');
		
		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		$stype 			= $this->get('stype');
		
		$arr 	 		= m('flow')->getdatalog($num, $mid, 0);
		if ($arr['changeurlstr']) {
			$arr['changeurlstr']	= '?a=p'.$arr['changeurlstr'];
		}
		// var_dump($arr['changeurlstr']);die;
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;
		
		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_0.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		$this->assign('stype', $stype);
		if($stype=='word'){
			m('file')->fileheader($arr['modename'].'.doc');
		}
	}
	
	//下载
	public function downAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}
	
	
	
	
	//happy_add
	
	public function showimageAction($id)
	{
		$this->display = false;
		//$id  = (int)$this->jm->gettoken('id');
		return $i=m('file')->showimage($id);
	}
	
	
	
	
	
	//导出页面
	public function eAction()
	{
		$num	= $this->get('num');
		$event	= $this->get('event');
		$stype	= $this->get('stype');
		
		$arr 	= m('flow')->printexecl($num, $event);
		$this->title = $arr['moders']['name'];
		$urlstr	= '?a=e&num='.$num.'&event='.$event.'';
		$this->assign('arr', $arr);
		$this->assign('urlstr', $urlstr);
		$this->assign('stype', $stype);
		if($stype!=''){
			$filename = $this->title;
			header('Content-type:application/vnd.ms-excel');
			header('Content-disposition:attachment;filename='.iconv("utf-8","gb2312",$filename).'.'.$stype.'');
		}
	}
	
	//邮件上打开详情
	public function aAction()
	{
		$num = $this->get('num');
		$mid = $this->get('mid');
		$act = 'p';
		if($this->rock->ismobile())$act='x';
		$url = 'task.php?a='.$act.'&num='.$num.'&mid='.$mid.'';
		$this->rock->location($url);
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

		if ($field=='clupdatelist') {
			$rows = $this->db->getone('[Q]flow_log',"`id`='$id'", '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`','`id` desc');
			$filename = '主材升级'.date('YmdHis');  
			$header = array('主材名称','预算单价','选样单价','预算数量','实际数量','变更金额','小计','备注');  
			$index = array('goods','yusuanprice','xuanyangprice','yusuannum','shijinum','cha','total','explain');  
		}else if ($field=='rgfeelist'){
			$rows = $this->db->getone('[Q]flow_log',"`id`='$id'", '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`','`id` desc');

			$filename = '人工费清单'.date('YmdHis');  
			$header = array('工作项目','预算工作量','实际工作量','变更工作量','单价','单位','小计','备注');  
			$index = array('goods','yusuan','shiji','cha','price','unit','total','explain'); 
		}else{
			$rows = $this->db->getone('[Q]clpaifa',"`id`='$id'", '*','`id` desc');

			$filename = '材料清单'.date('YmdHis');  
			$header = array('材料名称','数量','单位','单价','小计','备注');  
			$index = array('goods','paifanum','unit','price','total','explain'); 
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
	
	
	protected function calcdiff($anzcl,$anzonly,$clpaifa,$suffix=''){    
		//倒计时计算 begin				
		$clgysid=$clpaifa['clgysid'.$suffix];
		$deptid = m('admin')->getone("`id`='$clgysid'",'deptid');

		$deptid = $deptid['deptid'];
		$zxstyle=$clpaifa['zxstyle'.$suffix];
		if (in_array($clpaifa['cid'.$suffix], $anzonly)) {
			// 安装
			$i=3;			
			$j=2;			
		}else{
			// 测量
			$j=$i=1;
		}
		if ($zxstyle!=1) {
			// 新版时间，加上步长
			$i+=$zxstyle;
		}else{
			// 旧版时间
			$i=$j;
		}
		$day=$anzcl[$deptid]['day'.$i];
		$timeinfo=array();
		// var_dump($day);die;
		$timeinfo=array();
		$timeinfo['day']=$day;   
		// var_dump($i);

		if ($clpaifa['status'.$suffix]==0 || $clpaifa['status'.$suffix]==2 ) {	
			$past=strtotime(date("Y-m-d",time())); //最后处理时间 
			// $past=time();  //2015-5-20
			$today=strtotime(date("Y-m-d",strtotime($clpaifa['createdt'.$suffix]))); //最后处理时间 
			// $today=strtotime($rs['createdt']); //创建时间 
			$timeinfo['diff']=$day-ceil(($past-$today)/86400);  
			$timeinfo['text1']="还剩";  
			$timeinfo['text2']="天";  
			$timeinfo['info']="还剩".$timeinfo['diff']."天";   
		}else{			
			$past=strtotime(date("Y-m-d",strtotime($clpaifa['dealdt'.$suffix]))); //最后处理时间 
			$today=strtotime(date("Y-m-d",strtotime($clpaifa['createdt'.$suffix]))); //最后处理时间 
			// $past=strtotime($rs['dealdt']); //最后处理时间 
			// $today=strtotime($rs['createdt']); //创建时间 
			$diff=$day-ceil(($past-$today)/86400); 
			if ($diff<0) {
				$timeinfo['diff']=$diff; 
				$timeinfo['text1']="超期";  
				$timeinfo['text2']="天";   
				$timeinfo['info']="超期".$diff."天";   
			}
		}
		// var_dump($timeinfo);

		return $timeinfo;
		//倒计时计算 end
	}
}