<?php
class fworkClassAction extends Action
{
	
	public function getmodearrAjax()
	{
		$rows = m('mode')->getmoderows($this->adminid,'and islu=1');
		$row  = array();
		$viewobj = m('view');
		$this->where = '';
		foreach($rows as $k=>$rs){
			$lx = $rs['type'];
			if(!$viewobj->isadd($rs['id'], $this->adminid))continue;
			if(!isset($row[$lx]))$row[$lx]=array();
			$row[$lx][] = $rs;
		}
		$this->returnjson(array('rows'=>$row));
	}
	
	
	
	
	
	
	public function flowbillbefore($table)
	{
		$lx 	= $this->post('atype');
		$dt 	= $this->post('dt1');
		$dt2 	= $this->post('dt2');
		$key 	= $this->post('key');
		$keypp 	= $this->post('keypp');
		$zt 	= $this->post('zt');
		$projectRecord = $this->post('projectRecord');
		$desginRecord = $this->post('desginRecord');
		$areaSearch = $this->post('areaSearch');
		$modeid = (int)$this->post('modeid','0');
		$uid 	= $this->adminid;
		$where	= 'and a.uid='.$uid.'';
    	$flowcourseid = getconfig('flow_finish');
    	$flownostartcourseid = getconfig('flow_nostart');

		//待办
		if($lx=='daib'){
			$where	= 'and a.`status`=0 and '.$this->rock->dbinstr('a.nowcheckid', $uid);
		}
		
		if($lx=='xia'){
			$where	= 'and '.$this->rock->dbinstr('b.superid', $uid);
		}
		
		if($lx=='jmy'){
			$where	= 'and '.$this->rock->dbinstr('a.allcheckid', $uid).' and a.courseid not in( '.$flownostartcourseid.') ';
		}
		if($lx=='finish'){
			$where	= 'and '.$this->rock->dbinstr('a.allcheckid', $uid).' and (a.courseid in( '.$flowcourseid.') or a.`status`=1  )';
		}else if ($lx=='all') {
			$where	= 'and '.$this->rock->dbinstr('a.allcheckid', $uid);		
			$where .= ' and ( `table`="book" or  `table`="rzgongdi" or  `table`="yzjuzhuang")';  //人工费不要算在全部工地里面  20190701
		}else if ($lx=='nostart') {
			$where	= 'and '.$this->rock->dbinstr('a.allcheckid', $uid).' and a.courseid in( '.$flownostartcourseid.')  and a.`status` =0';
		}else if ($lx!='daib') {
			$where.=' and a.courseid not in( '.$flowcourseid.')';	

		}
		
		if($lx=='mywtg'){
			$where.=" and a.status=2";
		}
		
		if($zt!='')$where.=" and a.status='$zt'";
		if($dt!=''){
			if (isempt($dt2)) {
			# code...
				$where.=" and a.applydt='$dt'";
			}else{
				$where.=" and (a.applydt between '$dt' and '$dt2')";
			}
		}
		if($modeid>0)$where.=' and a.modeid='.$modeid.'';
		//happy_add 新增查询条件pc
		if(!isempt($key))$where.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%' or a.sericnum like '$key%' or a.`author` like '%$key%' or a.`coursename` like '%$key%' or a.`title` like '%$key%' or a.`chuban` like '%$key%' or a.`num` like '%$key%' or a.`designer` like '%$key%')";
		//if(!isempt($keypp))$where.="and a.`courseid` like '%$keypp%')";
		
		if($keypp != '')$where.='  and a.`courseid`='.$keypp.'';
		
		
		$brandRe = $this->rock->post('brandRe');
		if(!isempt($brandRe)){		
			//$where.=' and `yzbrand`='.$brandRe;
			$where.=' and ('.$this->rock->dbinstr('c.yzbrand', $brandRe).' or '.$this->rock->dbinstr('d.yzbrand', $brandRe).' or '.$this->rock->dbinstr('g.yzbrand', $brandRe).')';
		}
		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){		
			$where.=" and (c.`weizhi` like '%$areaSearch%' or c.`routeline` like '%$areaSearch%')";
		}

		if(!isempt($projectRecord)){		
			$where.=" and (a.`author` like '%$projectRecord%')";
		}

		if(!isempt($desginRecord)){		
			$where.=" and (a.`designer` like '%$desginRecord%')";
		}
		$this->where = $where;

		return array(
			//'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id  left join `[Q]book` c on a.mid=c.id left join `[Q]rzgongdi` d on a.mid=d.id left join `[Q]rgfee` f on a.mid=f.id',
			'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id  left join `[Q]book` c on  (a.mid = c.id and a.modeid =45  ) left join `[Q]rzgongdi` d on  (a.mid = d.id and a.modeid =55) left join `[Q]yzjuzhuang` g on (a.mid = g.id and a.modeid =59)',
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.*,b.name,b.deptname,c.yzbrand,d.yzbrand,g.yzbrand',
			'order' => 'a.optdt desc'
		);
	}
	
	public function flowbillafter($table, $rows)
	{
		$table=" `[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id  left join `[Q]book` c on  (a.mid = c.id and a.modeid =45  ) left join `[Q]rzgongdi` d on  (a.mid = d.id and a.modeid =55) left join `[Q]yzjuzhuang` g on (a.mid = g.id and a.modeid =59)";
		$fields2 ='SUM(c.price) totalprice,SUM(d.price) totalprice2,SUM(g.price) totalprice3';

		//PC版 OA金额统计 计算总价 happy 20191125 begin		
		$rer 		= $this->db->getone($table, 'a.isdel=0 '.$this->where,$fields2);
		$totalprice = array_sum($rer);
		//PC版 OA金额统计 计算总价 happy 20191125 end	
		
		$rows = m('flowbill')->getbilldata($rows);
		$rows=$this->getyrecorddata($rows);

		return array(
			'rows'		=> $rows,
			'totalprice'		=> $totalprice,
			'flowarr' 	=> m('mode')->getmodemyarr($this->adminid)
		);
	}
	
	
	/**
	*	显示z最新跟进记录
	*/
	private function getyrecorddata($rows)
	{
		$adb	= m('flow_log');
		foreach($rows as $k=>$rs){
			if(!isset($rs['id']))continue;
			$id=$rs['id'];
			$modenum=$rs['modenum'];
			$record = $adb->getmou("CONCAT(`explain`,' <br>',optdt) as record", "mid ='$id'  and `table` = '$modenum' ", 'id desc');
			$record2="' ". $record ." '";
			$rows[$k]['record'] = '<span class="record-pc" onclick="showrecord(' . $record2 . ')"  >' . $record . '</span>';
		}
		return $rows;
	}
	
	public function meetqingkbefore($table)
	{
		$pid = $this->option->getval('hyname','-1', 2);
		return array(
			'where' => "and `pid`='$pid'",
			'order' => 'sort',
			'field' => 'id,name',
		);
	}
	
	public function meetqingkafter($table, $rows)
	{
		$dtobj 		= c('date');
		$startdt	= $this->post('startdt', $this->date);
		$enddt		= $this->post('enddt');
		if($enddt=='')$enddt = $dtobj->adddate($startdt,'d',7);
		$jg 		= $dtobj->datediff('d',$startdt, $enddt);
		if($jg>30)$jg = 30;
		$flow 		= m('flow:meet');
		$data 		= m('meet')->getall("`status`=1 and `type`=0 and `startdt`<='$enddt 23:59:59' and `enddt`>='$startdt' order by `startdt` asc",'hyname,title,startdt,enddt,state,joinname,optname');
		$datss 		= array();
		foreach($data as $k=>$rs){
			$rs 	= $flow->flowrsreplace($rs);
			$key 	= substr($rs['startdt'],0,10).$rs['hyname'];
			if(!isset($datss[$key]))$datss[$key] = array();
			$str 	= '['.substr($rs['startdt'],11,5).'→'.substr($rs['enddt'],11,5).']'.$rs['title'].'('.$rs['joinname'].') '.$rs['state'].'';
			$datss[$key][] = $str;
		}
		
		$columns	= $rows;
		$barr 		= array();
		$dt 		= $startdt;
		for($i=0; $i<=$jg; $i++){
			if($i>0)$dt = $dtobj->adddate($dt,'d',1);
			$w 		= $dtobj->cnweek($dt);
			$status	= 1;
			if($w=='六'||$w=='日')$status	= 0;
			$sbarr	= array(
				'dt' 		=> '星期'.$w.'<br>'.$dt.'',
				'status' 	=> $status
			);
			foreach($rows as $k=>$rs){
				$key 	= $dt.$rs['name'];
				$str 	= '';
				if(isset($datss[$key])){
					foreach($datss[$key] as $k1=>$strs){
						$str.= ''.($k1+1).'.'.$strs.'<br>';
					}
				}
				$sbarr['meet_'.$rs['id'].''] = $str;
			}
			$barr[] = $sbarr;
		}
		$arr['columns'] = $columns;
		$arr['startdt'] = $startdt;
		$arr['enddt'] 	= $enddt;
		$arr['rows'] 	= $barr;
		$arr['totalCount'] 	= $jg+1;
		
		return $arr;
	}
}