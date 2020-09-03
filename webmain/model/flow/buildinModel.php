<?php
class flow_buildinClassModel extends flowModel
{
	public function initModel()
	{
		$clgys = m('admin')->getall("instr(`deptpath`,'[38]')>0",'id,deptid,deptname');
		$this->map = array();
		foreach ($clgys as &$it){ $this->map[$it['id']] = &$it; } //先整理一下数组以数据
		$this->anzcl=getconfig('anzcl');
		$this->anzonly=getconfig('anzonly');
	}
	
	public function flowrsreplace($rs)
	{
	    $anzonly=$this->anzonly;
		$flag=in_array($rs['cid'], $anzonly)?'安装':'测量';
		$add=array($flag,'退货');	
		if ($rs['type']==1) {
			if (in_array($rs['cid'], $anzonly)) {
				$rs['alltotal']='￥'.-abs($rs['alltotal']);
				$rs['totalprice']='￥'.-abs($rs['totalprice']);
			}else{
				unset($rs['alltotal'],$rs['totalprice']);
			}
		}else{
			if (in_array($rs['cid'], $anzonly)) {
				$rs['totalprice']='￥'.$rs['totalprice'];
				$rs['alltotal']='￥'.$rs['alltotal'];
			}else{
				unset($rs['alltotal'],$rs['totalprice']);
			}
		}	
		$rs['type']=$add[$rs['type']];
		if(!isempt($rs['mobile'])){
			$rs['mobile']='<a href="tel:'.$rs['mobile'].'" class="hhhh">'.$rs['mobile'].'</a>';	
			
		}

		//倒计时计算 begin	
		$anzcl=getconfig('anzcl');
		$anzonly=getconfig('anzonly');
		$clpaifaresult=$this->calcdiff($anzcl,$anzonly,$rs);
		if (!empty($clpaifaresult)) {
			$rs['diff']=$clpaifaresult['diff']; 
			$rs['text1']=$clpaifaresult['text1'];  
			$rs['text2']=$clpaifaresult['text2'];   
		}  
		//倒计时计算 end		
		//关联测量 begin	   现在这个如果没完成就不用计算关联的 优先显示完成中的
		if (!empty($rs['ralatedid'])&&($rs['status']==1 || $rs['status']==3) ) {
			$ralatedresult=$this->calcdiff($anzcl,$anzonly,$rs,$suffix='1');
			if (!empty($ralatedresult)) {
				if (!empty($clpaifaresult)) {
					//自带倒计时<0    则两数之和	
					if ($ralatedresult['diff']<0) {
						$rs['diff']=$clpaifaresult['diff']+$ralatedresult['diff']; 
					}
				}else {
					//自带倒计时为空	
					$rs['diff']=$ralatedresult['diff']; 
					$rs['text1']=$ralatedresult['text1'];  
					$rs['text2']=$ralatedresult['text2'];   
				}
			}  
		}
		// var_dump($rs);die;
		//关联测量 end	
		//倒计时计算 end

		if(!isempt($rs['dealdt'])){
			$rs['createdt']=$rs['dealdt'];	
			
		}
		return $rs;
	}
	protected function flowbillwhere($uid, $lx)
	{
		$s 		= 'and ( a.clgysid='.$this->adminid.' or '.$this->adminid.'=15 or '.$this->adminid.'=10 or '.$this->adminid.'=12 or '.$this->adminid.'=1	)';
		$key 	= $this->rock->post('key');
		$anzonlynum=getconfig('anzonlynum');
		$celonlynum=getconfig('celonlynum');
		$buildinnum=getconfig('buildinnum');

		//待处理
		if($lx=='dcl'||$lx=='def'){
			if ($this->adminid==12) {
				# 财务部默认展示已安装...
				$s.=' and a.`progress` in(4) ';
			}else{
				$s.=' and a.`cid` in('.$buildinnum.') and a.`status` in(0,2) ';

			}
		}

		if($lx=='celdcl'){
			// $s.=' and a.`cid` in('.$celonlynum.')  and a.`progress` in(1)';
			$s.=' and a.`cid` in('.$celonlynum.')  and a.`progress` in(1)';
		}
		if($lx=='anzdcl'){
			$s.=' and a.`cid` in('.$anzonlynum.')  and a.`status` in(0,2)';
		}

		//历史记录		
		if($lx=='history'){
			$s.=' and a.`progress` in(1,2,4,5)';
		}	
		if($lx=='celhis'){
			$s.=' and a.`cid` in('.$celonlynum.')  and a.`progress` in(2)';
			// $s.=' and a.`cid` in('.$celonlynum.')  and a.`status` in(1,3)';
		}
		if($lx=='anzhis'){
			$s.=' and a.`cid` in('.$anzonlynum.')  and a.`progress` in(4)';
		}
		// progress工地进度：1测量中 2带安装 3安装中 4已安装 5已完成(退货) 6已取消       楼上代码待优化，历史数据处理好之后
		if($lx=='cancelhis'){
			$s.=' and a.`progress` in(6) ';
		}	
		if($lx=='tuihuohis'){
			$s.=' and a.`progress` in(5) ';
		}
		
		$timeRecord = $this->rock->post('timeRecord');
		$timeRecord2 = $this->rock->post('timeRecord2');
		$author = $this->rock->post('author');
		$clgys = $this->rock->post('clgys');
		//时间筛选	begin	手机版
		$time1 = $this->rock->post('time1');
	
		if(!isempt($timeRecord) && !isempt($timeRecord2)){
			$s.=" and (a.`dealdt` between '$timeRecord' and '$timeRecord2')";
		}else if(isempt($time1) && $lx!='dcl' && $lx!='def'  && $lx!='celdcl' && $lx!='anzdcl'){			
            $year = date('Y');
            $m = date('m');
            $s .= " and (a.`dealdt` > '$year-$m')";
		}
		if(!isempt($author)){
			$dR = explode(",", $author);
            $status_chid = '';
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $status_chid .= ',"' . $chkid . '"';
                }
            }
            if ($status_chid != '') $status_chid = substr($status_chid, 1);
            $s .= ' and (a.`author` in ('.$status_chid.')) ';
		}
		if(!isempt($clgys)){
			$dR = explode(",", $clgys);
            $status_chid = '';
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $status_chid .= ',"' . $chkid . '"';
                }
            }
            if ($status_chid != '') $status_chid = substr($status_chid, 1);
            $s .= ' and (a.`clgysname` in ('.$status_chid.')) ';
		}

		//时间筛选	begin	手机版
		if(!isempt($time1) && $time1!='全部'){			
			$tt=explode("-",$time1);
			//日期筛选优化只选择了年的	
			if(isset($tt[1])){
				switch ($tt[1]) {
					case '上半年':$s.=" and (a.`dealdt` like '%$tt[0]-01%' or a.`dealdt` like '%$tt[0]-02%' or a.`dealdt` like '%$tt[0]-03%' or a.`dealdt` like '%$tt[0]-04%' or a.`dealdt` like '%$tt[0]-05%' or a.`dealdt` like '%$tt[0]-06%')";
						break;			
					case '下半年':$s.=" and (a.`dealdt` like '%$tt[0]-07%' or a.`dealdt` like '%$tt[0]-08%' or a.`dealdt` like '%$tt[0]-09%' or a.`dealdt` like '%$tt[0]-10%' or a.`dealdt` like '%$tt[0]-11%' or a.`dealdt` like '%$tt[0]-12%')";
						break;	
					case '第一季度':$s.=" and (a.`dealdt` like '%$tt[0]-01%' or a.`dealdt` like '%$tt[0]-02%' or a.`dealdt` like '%$tt[0]-03%')";
						break;	
					case '第二季度':$s.=" and (a.`dealdt` like '%$tt[0]-04%' or a.`dealdt` like '%$tt[0]-05%' or a.`dealdt` like '%$tt[0]-06%')";
						break;	
					case '第三季度':$s.=" and (a.`dealdt` like '%$tt[0]-07%' or a.`dealdt` like '%$tt[0]-08%' or a.`dealdt` like '%$tt[0]-09%')";
						break;	
					case '第四季度':$s.=" and (a.`dealdt` like '%$tt[0]-10%' or a.`dealdt` like '%$tt[0]-11%' or a.`dealdt` like '%$tt[0]-12%')";
						break;	
					default:$s.=" and (a.`dealdt` like '%$time1%')";
						break;
				}
			}else{
				$s.=" and (a.`dealdt` like '%$time1%')";
			}
		}else if (!isempt($time1) && $time1=='全部'){
		}else if(isempt($timeRecord) && $lx!='dcl' && $lx!='def' && $lx!='celdcl' && $lx!='anzdcl'){			
            $year = date('Y');
            $m = date('m');
            $s .= " and (a.`dealdt` like '$year-$m%')";
		}
		//时间筛选	 end	

		if(!isempt($key))$s.=" and (a.`title` like '%$key%' or a.`author` like '%$key%' or a.`chuban` like '%$key%' or a.`clgysname` like '%$key%')";
		
		return array(
			'table' => '`[Q]buildin` a left join `[Q]userinfo` b on a.author=b.name  left join `[Q]buildin` c on (a.ralatedid = c.id )',
			'fields'=> 'a.*,b.mobile,c.clgysid clgysid1,c.cid cid1,c.status status1,c.createdt createdt1,c.dealdt dealdt1',
			'where' =>$s,
			'order' =>'a.dealdt desc ,a.createdt desc',
		);
	}


	
	protected function calcdiff($anzcl,$anzonly,$clpaifa,$suffix=''){    
		//倒计时计算 begin				
		$clgysid=$clpaifa['clgysid'.$suffix];
		$deptid = m('admin')->getone("`id`='$clgysid'",'deptid');

		$deptid = $deptid['deptid'];
		$zxstyle=$clpaifa['zxstyle'.$suffix];//1以前的2局装3整屋

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
		
		// var_dump('suffix'.$suffix);
		

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
		// var_dump($clpaifa);
		// var_dump($clpaifa['dealdt'.$suffix]);
		// var_dump($clpaifa['createdt'.$suffix]);

		return $timeinfo;
		//倒计时计算 end
	}
}