<?php
class customerClassAction extends Action
{
	public function custtotalbefore($table)
	{
		$where 	= '';
		$uid 	= $this->adminid;
		$lx		= $this->post('atype');
		$month	= $this->month = $this->post('month',date('Y-m'));
		$key	= $this->post('key');
		if($lx=='my'){
			$where=' and `id`='.$uid.'';
		}
		if($lx=='down'){
			$s 		= m('admin')->getdownwheres('id', $uid, 0);
			$where 	=' and ('.$s.' or `id`='.$uid.')';
		}
		if($key!=''){
			$where .= m('admin')->getkeywhere($key);
		}
		return array(
			'fields'=> 'id,name,deptname',
			'where'	=> $where,
		);
	}
	
	public function custtotalafter($table,$rows)
	{
		$crm = m('crm');
		foreach($rows as $k=>$rs){
			$rows[$k]['month'] = $this->month;
			$toarr 	= $crm->moneytotal($rs['id'], $this->month);
			
			foreach($toarr as $f=>$v){
				if($v==0)$v='';
				$rows[$k][$f] = $v;
			}
		}
		return array(
			'rows' => $rows
		);
	}
	
	public function custtotalgebefore($table)
	{
		return 'and 1=2';
	}
	
	public function custtotalgeafter($t, $rows)
	{
		$rows 		 = array();
		$crm 		 = m('crm');
		$dtobj		 = c('date');
		$uid		 = $this->post('uid', $this->adminid);
		$urs 		 = m('admin')->getone($uid, 'name,deptname');
		$start		 = $this->post('startdt', date('Y-01'));
		$end		 = $this->post('enddt', date('Y-m'));
		$jgm 		 = $dtobj->datediff('m', $start.'-01', $end.'-01');
		for($i=0; $i<=$jgm; $i++){
			$month 	= $dtobj->adddate($end.'-01', 'm', 0-$i, 'Y-m');
			$arr['month'] 	= $month;
			$arr['name'] 	= $urs['name'];
			$arr['deptname']= $urs['deptname'];
			
			$toarr 	= $crm->moneytotal($uid, $month);
			foreach($toarr as $f=>$v){
				if($v==0)$v='';
				$arr[$f] = $v;
			}
			$rows[]	= $arr;
		}
		
		$barr['rows'] 		= $rows;
		$barr['totalCount'] = count($rows);
		return $barr;
	}
	
	//客户转移
	public function movecustAjax()
	{
		$sid 	= $this->post('sid');
		$toid 	= $this->post('toid');
		if($sid==''||$sid=='')return;
		m('crm')->movetouser($this->adminid, $sid, $toid);
	}
	
	public function retotalAjax()
	{
		m('crm')->custtotal();
	}
	
		
	//批量添加客户
	public function addplcustAjax()
	{
		//渠道,编号,区域,客户姓名,联系方式,小区名称,面积,户型,装修时间,预算,风格,分配时间,硬装设计师,软装设计师,硬装状态,软装状态,竞争公司,合作公司,客户要求
		$rows  	= c('html')->importdata('laiyuan,mobile,routeline,name,tel,address,unitname,linkname,zxdate,budgettype,zxstyle,fpdate,gddesigner,rzdesigner,status,rzstatus,compet,type,remark');
		$oi 	= 0;
		$db 	= m('customer');
		// 0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单
		$status = array('待量单' =>0 ,'无效单' =>1 , '已退单' =>2 , '重单' =>3 , '跟进单' =>4 , '意向单' =>5 , '失败单' =>6 , '已签单' =>7 , '待定单' =>8 );
		foreach($rows as $k=>$rs){
			$rs['adddt']	= $this->now;
			$rs['optdt']	= $this->now;
			if (!is_integer($rs['status'])) {
				$rs['status']=$status[$rs['status']];
			}
			if (!is_integer($rs['rzstatus'])) {
				$rs['rzstatus']=$status[$rs['rzstatus']];
			}
			$rs['uid']		= $this->adminid;
			$rs['createid']		= $this->adminid;
			$rs['optname']		= $this->adminname;
			$rs['createname']	= $this->adminname;
			$db->insert($rs);
			$oi++;
		}
		backmsg('','成功导入'.$oi.'条数据');
	}

	
	//happy_add 异步获取统计数据
	public function loadDataAction()
	{
		$this->title = '统计分析';
		// $where	= "(uid=".$this->adminid." or ".$this->adminid."=1  or ".$this->adminid."=188 or ".$this->rock->dbinstr('shateid', $this->adminid).")";
		$where	= "(uid=".$this->adminid." or ".$this->adminid."=1 or ".$this->adminid."=188 or ".$this->rock->dbinstr('shateid', $this->adminid).' or '.$this->rock->dbinstr('gddesignerid', $this->adminid).' or '.$this->rock->dbinstr('rzdesignerid', $this->adminid).' or '.$this->rock->dbinstr('markerid',$this->adminid).'  or '.$this->rock->dbinstr('rzmarkerid',$this->adminid).'  or '.$this->rock->dbinstr('rzmendianid',$this->adminid).' or '.$this->rock->dbinstr('mendianid', $this->adminid)." or (".$this->adminid."=513  and `shateid` is not null))";

		$areaSearch = $this->post('areaSearch');
		$timeRecord = $this->post('timeRecord');
		$timeRecord2 = $this->post('timeRecord2');
		$desginRecord = $this->post('desginRecord');
		$laiyuanRecord = $this->post('laiyuanRecord');
		$unitnameRecord = $this->post('unitnameRecord');
		$shichangRecord = $this->post('shichangRecord');
		$unitname1 = $this->post('unitname1');


		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){		
			$where.=" and (`routeline` like '%$areaSearch%' or `email` like '%$areaSearch%' )";
		}

		if(!isempt($timeRecord)){
			if(isempt($timeRecord2)){
				$tt=explode("-",$timeRecord);
				switch ($tt[1]) {
					case '上半年':$where.=" and (`fpdate` like '%$tt[0]-01%' or `fpdate` like '%$tt[0]-02%' or `fpdate` like '%$tt[0]-03%' or `fpdate` like '%$tt[0]-04%' or `fpdate` like '%$tt[0]-05%' or `fpdate` like '%$tt[0]-06%')";
						break;			
					case '下半年':$where.=" and (`fpdate` like '%$tt[0]-07%' or `fpdate` like '%$tt[0]-08%' or `fpdate` like '%$tt[0]-09%' or `fpdate` like '%$tt[0]-10%' or `fpdate` like '%$tt[0]-11%' or `fpdate` like '%$tt[0]-12%')";
						break;	
					case '第一季度':$where.=" and (`fpdate` like '%$tt[0]-01%' or `fpdate` like '%$tt[0]-02%' or `fpdate` like '%$tt[0]-03%')";
						break;	
					case '第二季度':$where.=" and (`fpdate` like '%$tt[0]-04%' or `fpdate` like '%$tt[0]-05%' or `fpdate` like '%$tt[0]-06%')";
						break;	
					case '第三季度':$where.=" and (`fpdate` like '%$tt[0]-07%' or `fpdate` like '%$tt[0]-08%' or `fpdate` like '%$tt[0]-09%')";
						break;	
					case '第四季度':$where.=" and (`fpdate` like '%$tt[0]-10%' or `fpdate` like '%$tt[0]-11%' or `fpdate` like '%$tt[0]-12%')";
						break;	
					default:$where.=" and (`fpdate` like '%$timeRecord%')";
						break;
				}
			}else{
				$where.=" and (`fpdate` between '$timeRecord' and '$timeRecord2')";
			}
		}
		if(!isempt($shichangRecord)){		
			$where.=" and (`shate` like '%$shichangRecord%')";
		}
		if(!isempt($desginRecord)){		
			$where.=" and (`gddesigner` like '%$desginRecord%')";
		}
		if(!isempt($laiyuanRecord)){
			switch ($laiyuanRecord) {
				case '线上':$where.=" and (`laiyuan` like '优居客' or `laiyuan` like '土巴兔' or `laiyuan` like 'icolor' or `laiyuan` like '丁丁网' or `laiyuan` like '凯特猫' or `laiyuan` like '家要美' or `laiyuan` like '网上预约' or `laiyuan` like '齐家网' or `laiyuan` like '云空间' or `laiyuan` like '吉宅网' or `laiyuan` like '信用家' or `laiyuan` like '城居网')";
					break;
				case '线下':$where.=" and (`laiyuan` like '工程部营销' or `laiyuan` like '其他' or `laiyuan` like '介绍' or `laiyuan` like '进店' or `laiyuan` like '来电咨询' or `laiyuan` like '电销部')";
					break;					
				default:$where.=" and (`laiyuan` like '%$laiyuanRecord%')";
					break;
			}		
		}

		if(!isempt($unitnameRecord)){
			$where.=" and (`unitname` like '%$unitnameRecord%' or `zxstyle` like '%$unitnameRecord%' or `linkname`='$unitnameRecord')";
		}

		$rows 	= $this->db->getall('SELECT status,laiyuan,count(*) ta,group_concat(laiyuan) FROM `[Q]customer` where '.$where.' GROUP BY status');
		//$rows 	= $this->db->getall('SELECT status,laiyuan,count(*) ta,group_concat(laiyuan) FROM `[Q]customer`  GROUP BY status');
		//$rows 	= $this->db->getmou("[Q]customer","status,laiyuan,count(*) ta,group_concat(laiyuan)","uid='$this->adminid' GROUP BY status");
		//$rows 	= $this->db->getall("[Q]customer","status,laiyuan,count(*) ta,group_concat(laiyuan)","(uid='$this->adminid' or '$this->adminid'=1) GROUP BY status");
		//$a1= explode(',','待量,无效,退,重,跟进,意向,败,签,待定');
		$a1= explode(',','待量单,无效单,已退单,重单,跟进单,意向单,失败单,已签单,待定单');
		
		//渠道获取
		$laiyuan	= m('flow_element')->getone("`mid`='7' and `fields`='laiyuan' order by `sort`",'*');
		$a2 = explode(',', $laiyuan['data']);
		// $a2= explode(',','土巴兔,优居客,丁丁网,icolor,凯特猫,家要美,电销部,介绍,进店,来电咨询,网上预约,工程部营销,其他,齐家网,云空间,吉宅网,信用家,城居网');

		$bo=array();
		$a3=array();

		//ssss$this->showreturn($rows);
		foreach($rows as $k=>$rs){
			//var_dump($rs);
			$rows[$k]['status']		= $a1[$rs['status']];
			$ii=explode(",",$rs['group_concat(laiyuan)']);
			$rows[$k]['ba']	=array_count_values($ii);
			foreach($a2 as $ak=>$as){
				if(!array_key_exists($as,$rows[$k]['ba']))
				{
					$rows[$k]['ba'][$as]=0;
				} 
			}
			$bo=array_merge_recursive($bo,$rows[$k]['ba']);	

		}
		//去掉全0
		foreach($bo as $bk=>$bs){
			if(is_array($bs)){
				//var_dump($bs);
				if($sum=array_sum($bs)==0)
				{
					unset($bo[$bk]);
				} 
			}
		}

		$data['data']=$rows;
		$data['bo']=$bo;
		//return $data;

		$this->showreturn($data);
	}
}