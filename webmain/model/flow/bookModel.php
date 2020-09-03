<?php
class flow_bookClassModel extends flowModel
{	// happy_add 读取工长来审批
	public function initModel()
	{
		//11.OA［形象建设部］［预算部］开工信息表业主号码隐藏或乱码。
		$this->admininfo= $this->db->getone('[Q]admin',$this->adminid,'id,name,deptid,deptname,ranking,superid,superpath,deptpath,superman');
		$this->deptid=getconfig('oadeptid');
		$this->isin = in_array($this->admininfo['deptid'],$this->deptid);
		$this->brandarr		 = c('array')->strtoarray('元贞国际|#888888,贞筑豪宅|#888888,梦依达|#888888,域嘉|#888888,元贞局装|#888888');
		$this->statustext	= explode(',','待处理,已审核,处理不通过,,,已作废');
		$this->statuscolor	= explode(',','blue,green,red,,,gray');
		
		//var_dump($this->urs);


	}
		//happy_add 读取工长来审批
	protected function flowcheckname($num)
	{

		if($num=='gzshenpi'){
			/*
			$admin = m('userinfo')->getone('`name`="'.$this->rs['author'].'"');
			return array($admin['id'], $admin['name']);*/
			if(!$this->isempt($this->rs['author'])){
			$checkidn 	= explode(',', $this->rs['author']);
			$_chid		= $_chna	= '';
			foreach($checkidn as $k1=>$chkid){
				$admin = m('userinfo')->getone('`name`="'.$chkid.'"');
				$_chid.=','.$admin['id'].'';
				$_chna.=','.$admin['name'].'';
			}
			if($_chid!=''){$_chid= substr($_chid, 1);$_chna= substr($_chna, 1);}

			return array($_chid,$_chna);}
		}
		if($num=='mdmanager'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['mdarea'].'"');
			return array($admin['id'], $admin['name']);
		}
		if($num=='khcomment'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['chuban'].'"');
			return array($admin['id'], $admin['name']);
		}
		if($num=='designersp'){

			if(!$this->isempt($this->rs['designer'])){
			$checkidn 	= explode(',', $this->rs['designer']);
			$_chid		= $_chna	= '';
			foreach($checkidn as $k1=>$chkid){
				$admin = m('userinfo')->getone('`name`="'.$chkid.'"');
				$_chid.=','.$admin['id'].'';
				$_chna.=','.$admin['name'].'';
			}
			if($_chid!=''){$_chid= substr($_chid, 1);$_chna= substr($_chna, 1);}

			return array($_chid.',1',$_chna.',admin');
		}
		}
		if($num=='htysp'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['htys'].'"');
			return array($admin['id'], $admin['name']);
		}
		if($num=='allxg'){
			$mdarea = m('userinfo')->getone('`name`="'.$this->rs['mdarea'].'"');
			return array($mdarea['id'],$mdarea['name']);
		}
		if($num=='over'){
			$xunjian = m('userinfo')->getone('`name`="'.$this->rs['xunjian'].'"');
			$chuban = m('userinfo')->getone('`name`="'.$this->rs['chuban'].'"');
			$chengjia = m('userinfo')->getone('`name`="程佳"');
			return array($xunjian['id'].','.$chuban['id'].','.$chengjia['id'],$xunjian['name'].','.$chuban['name'].','.$chengjia['name']);
		}
	}
	public function flowrsreplace($rs,$isv=0)
	{
		if(isset($rs['typeid']))$rs['typeid'] 	= $this->db->getmou('[Q]option','name',"`id`='".$rs['typeid']."'");
		if(!isempt($rs['telephone'])){
			if ($this->isin) {	
				//$str = substr_replace($rs['tel'],'****',3,4);中文乱码报错，所以不用
				$rs['telephone']='****';	
			}else{
				$rs['telephone']='<a href="tel:'.$rs['telephone'].'" class="hhhh">'.$rs['telephone'].'</a>';		
			}
		}
		if(!isempt($rs['yzbrand'])){
			$lxa 	= explode(',', $rs['yzbrand']);
			$yzbrand	= "";
			foreach ($lxa as $key => $value) {
				# code...
				$br = $this->brandarr[$value];
				$yzbrand	.= ','.$br[0];
			}
		}
		if($yzbrand!=''){$yzbrand= substr($yzbrand, 1);$rs['yzbrand']	=$yzbrand;}

		// 状态格式化
		$rers 		= $this->db->getone('[Q]flow_bill', '`mid`="'.$rs['id'].'" and modeid=45');
		$wdst		 = $rs['status'];
		$status = '<font color="'.$statuscolor.'">'.$statustext.'</font>';
		if($wdst==0&&$rers['status']==0)$status='待<font color="blue">'.$rers['nowcheckname'].'</font>处理';
		$rs['status']=$status;

		// 计算工期
		if($rs['courseid']==69||$rs['courseid']==79||$rs['courseid']==74||$rs['status']==1||$rers['status']==1){
			$dif="已完工";
		}else{			
			$today=strtotime($rs['endtime']); //今天 
			$past=time();  //2015-5-20
			$dif=ceil(($today-$past)/86400); //60s*60min*24h 
		}
		$rs['dif']=$dif;

		return $rs;
	}

	protected function flowbillwhere($uid, $lx)
	{
		//happy_add 新增查询条件pc
		$where  = '';
		$typeid = $this->rock->post('typeid','0');
		$key 	= $this->rock->post('key');
		$keypp 	= $this->rock->post('keypp');
		$projectRecord = $this->rock->post('projectRecord');
		$desginRecord = $this->rock->post('desginRecord');
		$areaSearch = $this->rock->post('areaSearch');
		$brandRe = $this->rock->post('brandRe');

		if(!isempt($brandRe)){		
			//$where.=' and `yzbrand`='.$brandRe;
			$where.=' and '.$this->rock->dbinstr('yzbrand', $brandRe);
		}
		if($typeid!='0'){
			$where .= ' and `typeid`='.$typeid.'';
		}
		if($key != '')$where.=" and (`title` like '%$key%' or `author` like '%$key%' or `coursename` like '%$key%' or `designer` like '%$key%')";
		if($keypp != '')$where.='  and `courseid`='.$keypp.'';
		if(!isempt($projectRecord)){		
			$where.=" and (`author` like '%$projectRecord%')";
		}

		if(!isempt($desginRecord)){		
			$where.=" and (`designer` like '%$desginRecord%')";
		}


		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){		
			$where.=" and (`weizhi` like '%$areaSearch%' or `routeline` like '%$areaSearch%')";
		}
		return array(
			'where' => $where,
			'order' => 'optdt desc'
		);
	}
}