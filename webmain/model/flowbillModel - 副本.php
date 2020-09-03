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
		$where	= 'uid='.$uid.'';
		$isdb	= 0;
		//未通过
		if($lx=='flow_wtg'){
			$where .= ' and `status`=2';
		}
		
		if($lx=='flow_dcl'){
			$where .= ' and `status`=0';
		}
		
		//已完成
		if($lx=='flow_ywc'){
			$where .= ' and `status`=1';
		}
		
		//待办
		if($lx=='daiban_daib' || $lx=='daiban_def'){
			$where	= '`status`=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
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

		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){
				$areaSearch =	'江楠';
		
				$where.=" and (`optname` like '%$areaSearch%' or `modename` like '%$areaSearch%' or `sericnum` like '%$areaSearch%' or `author` like '%$areaSearch%' or `coursename` like '%$areaSearch%' or `title` like '%$areaSearch%' or `chuban` like '%$areaSearch%' or `num` like '%$areaSearch%' or `designer` like '%$areaSearch%')";

		} 
		if(!isempt($key)){
				$where.=" and (`optname` like '%$key%' or `modename` like '%$key%' or `sericnum` like '%$key%' or `author` like '%$key%' or `coursename` like '%$key%' or `title` like '%$key%' or `chuban` like '%$key%' or `num` like '%$key%' or `designer` like '%$key%')";
			
		}
			
		/*
		//happy_add 新增查询条件wap
		$key 	= $this->rock->post('key');

		if(!isempt($key)){
			$keya = explode('_', $key);
			//happy_add 新增 筛选 查询
			if(isset($keya[1]) && !isempt($keya[1])){
				$kid = $keya[1];
				$kkey  = $keya[0];
				if ($kid=='areaSearch') {
					$where.=" and (`optname` like '%$key%' or `modename` like '%$key%' or `sericnum` like '%$key%' or `author` like '%$key%' or `coursename` like '%$key%' or `title` like '%$key%' or `chuban` like '%$key%' or `num` like '%$key%' or `designer` like '%$key%')";
				}

			}else {
				$where.=" and (`optname` like '%$key%' or `modename` like '%$key%' or `sericnum` like '%$key%' or `author` like '%$key%' or `coursename` like '%$key%' or `title` like '%$key%' or `chuban` like '%$key%' or `num` like '%$key%' or `designer` like '%$key%')";
			}
		}
		$arr= array(
			'table' => '`[Q]flow_bill` a left join `[Q]book` b on a.uid=b.id',
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.*,b.name,b.deptname',
			'order' => 'a.optdt desc'
		);*/
		/*$arr 	= $this->getlimit('`isdel`=0 and '.$where, $page,'a.*,b.author','`optdt` desc', $limit,'`[Q]flow_bill` a left join `[Q]book` b on a.mid=b.id');*/
		$arr 	= $this->getlimit('`isdel`=0 and '.$where, $page,'*','`optdt` desc', $limit);
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
			
			$title 		= '['.$rs['optname'].']'.$modename.'';
			$cont 		= '申请人：'.$rs['optname'].'<br>单号：'.$rs['sericnum'].'';
			$cont.='<br>项目名称：'.$rs['title'].'';
			$cont.='<br>业主姓名：'.$rs['chuban'].'';
			$cont.='<br>工程监理：'.$rs['author'].'';
			$cont.='<br>区域管理：'.$rs['num'].'';
			$cont.='<br>设计师：'.$rs['designer'].'';
			if(!isempt($summary))$cont.='<br>摘要：'.$summary.'';
			if(!isempt($rs['coursename']))$cont.='<br>状态：'.$rs['coursename'].'';
			if(!isempt($rs['nstatustext']))$cont.='<br>状态：'.$rs['nstatustext'].'';
			if(!isempt($rs['checksm']))$cont.='<br>处理说明：'.$rs['checksm'].'';
			
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
				'title' 	=> $rs['title'],
				'num' 	=> $rs['num'],
				'chuban' 		=> $rs['chuban'],
				'ishui' 	=> $ishui,
				'modename' 	=> $modename,
				'modenum' 	=> $modenum,
				'summary' 	=> $summary,
				'status'	=> $status
			);
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