<?php
class flow_clpaifaClassModel extends flowModel
{
	public function initModel()
	{
	}
	
	public function flowrsreplace($rs)
	{
		$add=array('配送','退货');	
		if ($rs['type']==1) {
			# code...
			$rs['alltotal']=-abs($rs['alltotal']);
			$rs['totalprice']=-abs($rs['totalprice']);
		}	
		$rs['type']=$add[$rs['type']];
		if(!isempt($rs['mobile'])){
			$rs['mobile']='<a href="tel:'.$rs['mobile'].'" class="hhhh">'.$rs['mobile'].'</a>';	
			
		}
		//倒计时计算 begin				

		//设置【日期/时间】 默认时区
		date_default_timezone_set('Asia/Shanghai');
		 
		//获取当前小时
		$hour=date("Hi",strtotime($rs['createdt']));
		 
		//判断
		if($hour>1730){
	    	$day=2;
		    // echo "当前时间大于6点！";
		}else{
	    	$day=1;
		    // echo "当前时间小于6点！";
		}

		if ($rs['status']==0 || $rs['status']==2 ) {	
			$past=strtotime(date("Y-m-d",time())); //最后处理时间 
			// $past=time();  //2015-5-20
			$today=strtotime(date("Y-m-d",strtotime($rs['createdt']))); //最后处理时间 
			// $today=strtotime($rs['createdt']); //创建时间 
			$rs['diff']=$day-ceil(($past-$today)/86400);  
			$rs['text1']="还剩";  
			$rs['text2']="天";  
		}else{			
			$past=strtotime(date("Y-m-d",strtotime($rs['dealdt']))); //最后处理时间 
			$today=strtotime(date("Y-m-d",strtotime($rs['createdt']))); //最后处理时间 
			// $past=strtotime($rs['dealdt']); //最后处理时间 
			// $today=strtotime($rs['createdt']); //创建时间 
			$diff=$day-ceil(($past-$today)/86400); 
			if ($diff<0) {
				$rs['diff']=$diff; 
				$rs['text1']="超期";  
				$rs['text2']="天";   
			}
		}
		//倒计时计算 end
		
		if(!isempt($rs['dealdt'])){
			$rs['createdt']=$rs['dealdt'];	
			
		}
		return $rs;
	}
	protected function flowbillwhere($uid, $lx)
	{
		$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=15 or '.$this->adminid.'=12 or '.$this->adminid.'=1	)';
		$key 	= $this->rock->post('key');
		if($lx=='dcl'||$lx=='def'){
			$s.=' and `status` in(0,2)';
		}
		if($lx=='history'){
			$s.=' and `status` in(1,3)';
		}		
		if($lx=='all'){
			$s.=' and `status` in(0,2,1,3)';
		}		


		$timeRecord = $this->rock->post('timeRecord');
		$timeRecord2 = $this->rock->post('timeRecord2');
		$author = $this->rock->post('author');
		$clgys = $this->rock->post('clgys');
	
		if(!isempt($timeRecord) && !isempt($timeRecord2)){
			$s.=" and (`dealdt` between '$timeRecord' and '$timeRecord2')";
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
            $s .= ' and (`author` in ('.$status_chid.')) ';

			// $s.=" and `author`='$author' ";
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
            $s .= ' and (`clgysname` in ('.$status_chid.')) ';
			// $s.=" and `clgysname`='$clgys' ";
		}


		//时间筛选	begin	手机版
		$time1 = $this->rock->post('time1');
		if(!isempt($time1) && $time1!='全部'){			
			$tt=explode("-",$time1);
			//日期筛选优化只选择了年的	
			if(isset($tt[1])){
				switch ($tt[1]) {
					case '上半年':$s.=" and (`dealdt` like '%$tt[0]-01%' or `dealdt` like '%$tt[0]-02%' or `dealdt` like '%$tt[0]-03%' or `dealdt` like '%$tt[0]-04%' or `dealdt` like '%$tt[0]-05%' or `dealdt` like '%$tt[0]-06%')";
						break;			
					case '下半年':$s.=" and (`dealdt` like '%$tt[0]-07%' or `dealdt` like '%$tt[0]-08%' or `dealdt` like '%$tt[0]-09%' or `dealdt` like '%$tt[0]-10%' or `dealdt` like '%$tt[0]-11%' or `dealdt` like '%$tt[0]-12%')";
						break;	
					case '第一季度':$s.=" and (`dealdt` like '%$tt[0]-01%' or `dealdt` like '%$tt[0]-02%' or `dealdt` like '%$tt[0]-03%')";
						break;	
					case '第二季度':$s.=" and (`dealdt` like '%$tt[0]-04%' or `dealdt` like '%$tt[0]-05%' or `dealdt` like '%$tt[0]-06%')";
						break;	
					case '第三季度':$s.=" and (`dealdt` like '%$tt[0]-07%' or `dealdt` like '%$tt[0]-08%' or `dealdt` like '%$tt[0]-09%')";
						break;	
					case '第四季度':$s.=" and (`dealdt` like '%$tt[0]-10%' or `dealdt` like '%$tt[0]-11%' or `dealdt` like '%$tt[0]-12%')";
						break;	
					default:$s.=" and (`dealdt` like '%$time1%')";
						break;
				}
			}else{
				$s.=" and (`dealdt` like '%$time1%')";
			}
		}else if (!isempt($time1) && $time1=='全部'){
		}else if(isempt($timeRecord) && $lx!='dcl' && $lx!='def'){			
            $year = date('Y');
            $m = date('m');
            $s .= " and (`dealdt` like '$year-$m%')";
		}
		//时间筛选	 end	
		
		if(!isempt($key))$s.=" and (`title` like '%$key%' or `author` like '%$key%' or `chuban` like '%$key%' or `clgysname` like '%$key%')";
		
		return array(
			'table' => '`[Q]clpaifa` a left join `[Q]userinfo` b on a.author=b.name',
			'fields'=> 'a.*,b.mobile',
			'where' =>$s,
			'order' =>'dealdt desc ,createdt desc',
		);
	}
}