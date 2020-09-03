<?php
class flowbillClassModel extends Model
{
	public $statustext;
	public $statuscolor;
	
	public function initModel()
	{
		$this->settable('flow_bill');
		$this->statustext	= explode(',','待处理,已审核,处理不通过,,,已作废');
		$this->statuscolor	= explode(',','blue,green,red,,,gray');
	}
	
	/**
	*	获取状态
	*/
	public function getstatus($zt, $lx=0)
	{
		$a1	= $this->statustext;
		$a2	= $this->statuscolor;
		$str 		= '<font color='.$a2[$zt].'>'.$a1[$zt].'</font>';
		if($lx==0){
			return $str;
		}else{
			return array($a1[$zt], $a2[$zt]);
		}
	}
	
	public function getrecord($uid, $lx, $page, $limit)
	{
		$srows	= array();
		$where	= 'a.uid='.$uid.'';
		$isdb	= 0;
		//未通过
		if($lx=='flow_wtg'){
			$where .= ' and a.`status`=2';
		}
		
		if($lx=='flow_dcl'){
			$where .= ' and a.`status`=0';
		}
		
		//已完成
		if($lx=='flow_ywc'){
			$where .= ' and a.`status`=1';
		}
		
		//待办
		if($lx=='daiban_daib' || $lx=='daiban_def'){
			$where	= 'a.`status`=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
			$isdb	= 1;
		}
		
		//经我处理
		if($lx=='daiban_jwcl'){
			$where	= $this->rock->dbinstr('allcheckid', $uid);
		}
		
		//我全部下级申请
		if($lx=='daiban_myxia'){
			$where 	= m('admin')->getdownwheres('uid', $uid, 0);
		}
		
		//我直属下级申请
		if($lx=='daiban_mydown'){
			$where 	= m('admin')->getdownwheres('uid', $uid, 1);
		}
		//happy_add 新增查询条件wap
		$key = $this->rock->post('key');
		$areaSearch = $this->rock->post('areaSearch');
		$timeRecord = $this->rock->post('timeRecord');
		$courseRecord = $this->rock->post('courseRecord');
		$projectRecord = $this->rock->post('projectRecord');
		$desginRecord = $this->rock->post('desginRecord');

		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){		
			$where.=" and (`weizhi` like '%$areaSearch%')";
		}

		if(!isempt($timeRecord)){	
		
			$tt=explode("-",$timeRecord);
			switch ($tt[1]) {
				case '上半年':$where.=" and (`createdt` like '%$tt[0]-01%' or `createdt` like '%$tt[0]-02%' or `createdt` like '%$tt[0]-03%' or `createdt` like '%$tt[0]-04%' or `createdt` like '%$tt[0]-05%' or `createdt` like '%$tt[0]-06%')";
					break;			
				case '下半年':$where.=" and (`createdt` like '%$tt[0]-07%' or `createdt` like '%$tt[0]-08%' or `createdt` like '%$tt[0]-09%' or `createdt` like '%$tt[0]-10%' or `createdt` like '%$tt[0]-11%' or `createdt` like '%$tt[0]-12%')";
					break;	
				case '第一季度':$where.=" and (`createdt` like '%$tt[0]-01%' or `createdt` like '%$tt[0]-02%' or `createdt` like '%$tt[0]-03%')";
					break;	
				case '第二季度':$where.=" and (`createdt` like '%$tt[0]-04%' or `createdt` like '%$tt[0]-05%' or `createdt` like '%$tt[0]-06%')";
					break;	
				case '第三季度':$where.=" and (`createdt` like '%$tt[0]-07%' or `createdt` like '%$tt[0]-08%' or `createdt` like '%$tt[0]-09%')";
					break;	
				case '第四季度':$where.=" and (`createdt` like '%$tt[0]-10%' or `createdt` like '%$tt[0]-11%' or `createdt` like '%$tt[0]-12%')";
					break;	
				default:$where.=" and (`createdt` like '%$timeRecord%')";
					break;
			}	
		}

		if(!isempt($courseRecord)){		
			$where.=" and (a.`coursename` like '%$courseRecord%')";
		}

		if(!isempt($projectRecord)){		
			$where.=" and (a.`author` like '%$projectRecord%')";
		}

		if(!isempt($desginRecord)){		
			$where.=" and (a.`designer` like '%$desginRecord%')";
		}

		if(!isempt($key)){
				$where.=" and (a.`optname` like '%$key%' or a.`modename` like '%$key%' or a.`sericnum` like '%$key%' or a.`author` like '%$key%' or a.`coursename` like '%$key%' or a.`title` like '%$key%' or a.`chuban` like '%$key%' or a.`num` like '%$key%' or a.`designer` like '%$key%')";
			
		}			
		$arr 	= $this->getlimit('`isdel`=0  and modeid=45 and '.$where, $page,'a.*,b.author,b.telephone','a.`optdt` desc', $limit,'`[Q]flow_bill` a left join `[Q]book` b on a.mid=b.id');
		//$arr 	= $this->getlimit('`isdel`=0 and '.$where, $page,'*','`optdt` desc', $limit);
		$rows 	= $arr['rows'];
		
		$modeids= '0';
		foreach($rows as $k=>$rs)$modeids.=','.$rs['modeid'].'';
		$modearr= array();
		if($modeids!='0'){
			$moders = m('flow_set')->getall("`id` in($modeids)",'id,num,name,summary');
			foreach($moders as $k=>$rs)$modearr[$rs['id']] = $rs;
		}
		foreach($rows as $k=>$rs){
			$modename	= $rs['modename'];
			$summary	= '';
			$modenum 	= '';
			$statustext	= '记录不存在';
			$statuscolor= '#888888';
			$ishui		= 0;
			if(isset($modearr[$rs['modeid']])){
				$mors 	= $modearr[$rs['modeid']];
				$modename 	= $mors['name'];
				$summary 	= $mors['summary'];
				$modenum 	= $mors['num'];
				$rers 		= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
				$summary	= $this->rock->reparr($summary, $rers);
				if($rers){
					$status		 = $rers['status'];
					$statustext  = $this->statustext[$status];
					$statuscolor = $this->statuscolor[$status];
					if($rers['isturn']==0){
						$statustext  = '待提交';
						$statuscolor = '#ff6600';
					}else{
						if($status==0)$statustext='待'.$rs['nowcheckname'].'处理';
					}
					if($rers['status']==5)$ishui = 1;
				}else{
					$this->update('isdel=1', $rs['id']);
				}
			}
			
			if(!isempt($rs['chuban']))$title 		= '['.$rs['num'].']'.$rs['title'].'';
			$cont= '';
			if(!isempt($rs['telephone']))$cont= '<span style="font-size:14px">业主姓名：'.$rs['chuban'].' | 联系方式：<a href="tel:'.$rs['telephone'].'">'.$rs['telephone'].'</a></span>';
			//if(!isempt($rs['title']))$cont.='<br>区域管理：'.$rs['num'].' | 项目名称：'.$rs['title'].'';
			//if(!isempt($rs['designer']))$cont.='<br>工程监理：'.$rs['author'].' | 设计师：'.$rs['designer'].'';
			if(!isempt($summary))$cont.='<br>摘要：'.$summary.'';
			if(!isempt($rs['coursename']))$cont.='<br><span style="font-size:10px">工程监理：'.$rs['author'].' | 设计师：'.$rs['designer'].' | 状态：'.$rs['coursename'].'</span>';
			//if(!isempt($rs['nstatustext']))$cont.='<br>状态：'.$rs['nstatustext'].'';
			//if(!isempt($rs['checksm']))$cont.='<br>处理说明：'.$rs['checksm'].'';
			
			$srows[]= array(
				'title' => $title,
				'cont' 	=> $cont,
				'ishui' => $ishui,
				'id' 	=> $rs['mid'],
				'uid' 	=> $rs['uid'],
				'optdt' 	=> $rs['optdt'],
				'sericnum' 	=> $rs['sericnum'],
				'applydt' 	=> $rs['applydt'],
				'statustext' 	=> $statustext,
				'statuscolor' 	=> $statuscolor,
				'modenum'		=> $modenum,
				'modename'		=> $modename
			);
		}
		$arr['rows'] 	= $srows;
		
		return $arr;
	}
	
	//获取待办处理数字
	public function daibanshu($uid)
	{
		$where	= '`status`=0 and isdel=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
		$to 	= $this->rows($where);
		return $to;
	}
	
	//未通过的
	public function applymywgt($uid)
	{
		$where	= '`status`=2 and isdel=0 and `uid`='.$uid.'';
		$to 	= $this->rows($where);
		return $to;
	}
	
	//单据数据
	public function getbilldata($rows)
	{
		error_reporting(0);
		$srows	= array();
		$modeids= '0';
		foreach($rows as $k=>$rs)$modeids.=','.$rs['modeid'].'';
		$modearr= array();
		if($modeids!='0'){
			$moders = m('flow_set')->getall("`id` in($modeids)",'id,num,name,summary');
			foreach($moders as $k=>$rs)$modearr[$rs['id']] = $rs;
		}
		foreach($rows as $k=>$rs){
			$modename	= $rs['modename'];
			$summary	= '';
			$modenum 	= '';
			$statustext	= '记录不存在';
			$statuscolor= '#888888';
			$wdst 		= 0;
			$ishui 		= 0;
			if(isset($modearr[$rs['modeid']])){
				$mors 	= $modearr[$rs['modeid']];
				$modename 	= $mors['name'];
				$summary 	= $mors['summary'];
				$modenum 	= $mors['num'];	
				$rers 		= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
				$summary	= $this->rock->reparr($summary, $rers);
				if($rers){
					$wdst		 = $rers['status'];
					$statustext  = $this->statustext[$wdst];
					$statuscolor = $this->statuscolor[$wdst];
					if($rers['isturn']==0){
						$statustext  = '待提交';
						$statuscolor = '#ff6600';
						$wdst		 = 1;
					}
					if($rers['status']==5)$ishui = 1;
				}else{
					$this->update('isdel=1', $rs['id']);
				}
			}
			$status = '<font color="'.$statuscolor.'">'.$statustext.'</font>';
			if($wdst==0)$status='待<font color="blue">'.$rs['nowcheckname'].'</font>处理';
			//happy_add 返回数据多一点
			$srows[]= array(
				'id' 		=> $rs['mid'],
				'optdt' 	=> $rs['optdt'],
				'applydt' 	=> $rs['applydt'],
				'name' 		=> $rs['name'],
				'deptname' 	=> $rs['deptname'],
				'sericnum' 	=> $rs['sericnum'],
				'author' 		=> $rs['author'],
				'designer' 		=> $rs['designer'],
				'telephone' 		=> $rers['telephone'],
				'nbbh' 		=> $rers['nbbh'],
				'weizhi' 		=> $rers['weizhi'],
				'housesize' 		=> $rers['housesize'],
				'housetype' 		=> $rers['housetype'],
				'budgettype' 		=> $rers['budgettype'],
				'designer' 		=> $rers['designer'],
				'mdarea' 		=> $rers['mdarea'],
				'price' 		=> $rers['price'],
				'telwatpri' 		=> $rers['telwatpri'],
				'cbdt' 		=> $rers['cbdt'],
				'endtime' 		=> $rers['endtime'],
				'explain' 		=> $rers['explain'],
				'designerpromise' 		=> $rers['designerpromise'],
				'price' 		=> $rers['price'],
				'telwatpri' 		=> $rers['telwatpri'],
				'coursename' 		=> $rs['coursename'],
				'title' 	=> $rs['title'],
				'num' 	=> $rs['num'],
				'chuban' 		=> $rs['chuban'],
				'ishui' 	=> $ishui,
				'modename' 	=> $modename,
				'modenum' 	=> $modenum,
				'summary' 	=> $summary,
				'status'	=> $status
			);//$srows[]=$rers;

		}
		return $srows;
	}
	
	public function homelistshow()
	{
		$arr 	= $this->getrecord($this->adminid, 'flow_dcl', 1, 5);
		$rows  	= $arr['rows'];
		$arows 	= array();
		foreach($rows as $k=>$rs){
			$cont = '【'.$rs['modename'].'】单号:'.$rs['sericnum'].',日期:'.$rs['applydt'].'，<font color="'.$rs['statuscolor'].'">'.$rs['statustext'].'</font>';
			
			$arows[] = array(
				'cont' 		=> $cont,
				'modename' 	=> $rs['modename'],
				'modenum' 	=> $rs['modenum'],
				'id' 		=> $rs['id'],
				'count'		=> $arr['count']
			);
		}
		return $arows;
	}
}