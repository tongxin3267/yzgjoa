<?php
class flow_customerClassModel extends flowModel
{
	public function initModel()
	{
		//0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单
		//$this->statearr		 = c('array')->strtoarray('停用|#888888,启用|green');
		//$this->statearr		 = c('array')->strtoarray('退单|#888888,签单中|green,已签单|green,量房|green,装修完成|green');
		$this->statearr		 = c('array')->strtoarray('待量单|#888888,无效单|green,已退单|green,重单|green,跟进单|green,意向单|green,失败单|green,已签单|green,待定单|green');
	}
	

	
	public function flowrsreplace($rs)
	{
		$zt = $this->statearr[$rs['status']];
		$rs['status']	= '<font color="'.$zt[1].'">'.$zt[0].'</font>';
		if($rs['htshu']==0)$rs['htshu']='';
		if($rs['moneyz']==0)$rs['moneyz']='';
		if($rs['moneyd']==0)$rs['moneyd']='';
		return $rs;
	}
	
	
	protected function flowprintrows($rows)
	{
		foreach($rows as $k=>$rs){
			$zt = $this->statearr[$rs['status']];
			$rows[$k]['status']		= '<font color="'.$zt[1].'">'.$zt[0].'</font>';;
		}
		return $rows;
	}
	
	//是否有查看权限
	protected function flowisreadqx()
	{
		$bo = false;
		$shateid = ','.$this->rs['shateid'].',';
		if(contain($shateid,','.$this->adminid.','))$bo=true;
		return $bo;
	}
	
	protected function flowgetfields($lx)
	{
		$arr = array();
		if($this->uid==$this->adminid){
			$arr['mobile'] 		= '手机号';
			$arr['tel'] 		= '电话';
			$arr['email'] 		= '邮箱';
			$arr['routeline'] 	= '交通路线';
		}
		return $arr;
	}

	
	protected function flowoptmenu($ors, $crs)
	{
		$zt  = $ors['statusvalue'];
		$num = $ors['num'];
		if($num=='ztqh'){
			$this->update('`status`='.$zt.'', $this->id);
		}
		
		if($num=='shate'){
			$cname 	 = $crs['cname'];
			$cnameid = $crs['cnameid'];
			$this->update(array(
				'shateid' 	=> $cnameid,
				'shate' 	=> $cname,
			), $this->id);
			$this->push($cnameid, '客户管理', ''.$this->adminname.'将一个客户【{name}】共享给你');
		}	
	}
	
	protected function flowbillwhere($uid, $lx)
	{
		$where 	= '`uid`='.$uid;
		//$where 	= '`uid`='.$uid.' or shateid in ('.$uid.') ';
		//$where 	= '`uid`='.$uid.' and `status`=1';
		$key 	= $this->rock->post('key');
		$lxa 	= explode('_', $lx);
		$lxs 	= $lxa[0];
		if(isset($lxa[1]))$lx = $lxa[1];
		
		if($lxs=='my'){
			$where = '`uid`='.$uid.'';
		}
		if($lxs=='shatemy'){
			$where	= $this->rock->dbinstr('shateid', $uid);
		}
		if($lxs=='down'){
			$where = m('admin')->getdownwheres('uid', $uid, 0);
		}
		if($lxs=='dist'){
			$where = '`fzid`='.$uid.'';
		}
			// happy_add var as = ['all','td','qdz','yqd','lf','zxwc','stat'];
		//0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单

		if($lx=='dld0'){
			$where.=' and ((`uid`='.$uid.' and `status`=0) or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=0))';
		}
		if($lx=='wxd1'){
			$where.=' and ((`uid`='.$uid.' and `status`=1)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=1))';
		}
		if($lx=='ytd2'){
			$where.=' and ((`uid`='.$uid.' and `status`=2)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=2))';
		}
		if($lx=='cd3'){
			$where.=' and ((`uid`='.$uid.' and `status`=3)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=3))';
		}
		if($lx=='gjd4'){
			$where.=' and ((`uid4`='.$uid.' and `status`=4)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=4))';
		}
		if($lx=='yxd5'){
			$where.=' and ((`uid`='.$uid.' and `status`=5)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=5))';
		}
		if($lx=='sbd6'){
			$where.=' and ((`uid`='.$uid.' and `status`=6)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=6))';
		}
		if($lx=='yqd7'){
			$where.=' and ((`uid`='.$uid.' and `status`=7)  or ('.$this->rock->dbinstr('shateid', $uid).' and `status`=7))';
		}
		if($lx=='ddd8'){
			$where.=' and ((`uid`='.$uid.' and `status`=8)   or  ('.$this->rock->dbinstr('shateid', $uid).' and `status`=8))';
		}
		if($lx=='stat'){
			$where.=' and `isstat`=1';
		}
		if($lx=='yfp'){
			$where.=' and `uid`>0';
		}
		if($lx=='wfp'){
			$where.=' and `uid`=0';
		}
		
		if($lx=='ting'){
			$where.=' and `status`=0';
		}
		if($lx=='myty'){
			$where 	= '`uid`='.$uid.' and `status`=0';
		}
		
		if($lx=='myall'){
			$where 	= '`uid`='.$uid.' or '.$this->rock->dbinstr('shateid', $uid);
		}
		if($lx=='all' || $lx=='def'){
			$where 	= '`uid`='.$uid.' or '.$this->rock->dbinstr('shateid', $uid);
		}
		//共享给我
		if($lx=='gxgw'){
			$where	= $this->rock->dbinstr('shateid', $uid);
		}
		//我共享
		if($lx=='mygx'){
			$where 	= '`uid`='.$uid.' and `shateid` is not null';
		}
		
		//客户统计一览
		if($lx=='totolall'){
			$where = '1=1';
		}
		
		$areaSearch = $this->rock->post('areaSearch');
		$timeRecord = $this->rock->post('timeRecord');
		$desginRecord = $this->rock->post('desginRecord');
		$laiyuanRecord = $this->rock->post('laiyuanRecord');
		$unitnameRecord = $this->rock->post('unitnameRecord');
		$unitname1 = $this->rock->post('unitname1');

		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){		
			$where.=" and (`routeline` like '%$areaSearch%' or `email` like '%$areaSearch%' )";
		}

		if(!isempt($timeRecord)){		
			$where.=" and (`fpdate` like '$timeRecord%')";
		}
		if(!isempt($desginRecord)){		
			$where.=" and (`shate` like '%$desginRecord%')";
		}
		if(!isempt($laiyuanRecord)){		
			$where.=" and (`laiyuan` like '%$laiyuanRecord%')";
		}

		if(!isempt($unitnameRecord)){
			$where.=" and (`unitname` like '%$unitnameRecord%' or `zxstyle` like '%$unitnameRecord%' or `linkname`='$unitnameRecord')";
		}
		if(!isempt($key))$where.=" and (`name` like '%$key%' or `unitname` like '%$key%' or `optname`='$key')";
	
		return array(
			'where' => 'and '.$where,
			'fields'=> 'id,name,status,laiyuan,isgys,createname,optname,unitname,shate,tel,type,adddt,moneyz,moneyd,htshu',
			'order' => 'status desc,optdt desc'
		);
	}
}