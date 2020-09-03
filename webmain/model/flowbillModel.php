<?php
class flowbillClassModel extends Model
{
	public $statustext;
	public $statuscolor;
	
	public function initModel()
	{
		$this->settable('flow_bill');
		$this->statustext	= explode(',','待处理,已审核,处理不通过,,,已作废');
		$this->brandarr		 = c('array')->strtoarray('元贞国际|#888888,贞筑豪宅|#888888,梦依达|#888888,域嘉|#888888,元贞局装|#888888');
		$this->statuscolor	= explode(',','blue,green,red,,,gray');
		//11.OA［形象建设部］［预算部］开工信息表业主号码隐藏或乱码。
		$this->admininfo= $this->db->getone('[Q]admin',$this->adminid,'id,name,deptid,deptname,ranking,superid,superpath,deptpath,superman');
		$this->deptid=getconfig('oadeptid');
		$this->isin = in_array($this->admininfo['deptid'],$this->deptid);

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
    	$flowcourseid = getconfig('flow_finish');
    	$flownostartcourseid = getconfig('flow_nostart');

		$srows	= array();
		$where	= '(a.uid='.$uid.' or '.$uid.'=188)';
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
		if($lx=='daiban_daib' || $lx=='daiban_def'|| $lx=='daiban_gddaib'|| $lx=='daiban_rgfdaib'){
			$where	= 'a.`status`=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
			$isdb	= 1;
		}
		
		//经我处理
		if($lx=='daiban_jwcl'){
			$where	= '('.$this->rock->dbinstr('allcheckid', $uid) .' or '.$uid.'=188) and  a.courseid not in( '.$flownostartcourseid.') ';
		}
		
		if($lx=='daiban_finish'){
			$where	= '('.$this->rock->dbinstr('allcheckid', $uid).'  or '.$uid.'=188) and ( a.courseid in( '.$flowcourseid.')  or a.`status`=1 )';
			//$where	= $this->rock->dbinstr('allcheckid', $uid).' and a.courseid in( '.$flowcourseid.') ';
		}else if ($lx=='daiban_all') {
			$where	='('. $this->rock->dbinstr('allcheckid', $uid) .' or '.$uid.'=188)';					

		}else if ($lx=='daiban_nostart') {
			//工地列表在建工地下面加一个未开工地，把元贞，域嘉，贞筑流程在【开工仪式】之前的所有工地显示为未开工地，元贞局装流程在【开工保护】之前的所有工地显示为未开工地
			$where=' ('.$this->rock->dbinstr('allcheckid', $uid).'  or '.$uid.'=188) and  a.courseid in( '.$flownostartcourseid.') and a.`status` =0';	

		}else if ($lx!='daiban_def'&&$lx!='daiban_daib'&&$lx!='daiban_gddaib'&&$lx!='daiban_rgfdaib') {
			$where.=' and a.courseid not in( '.$flowcourseid.') and a.`status` <> 1';	

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
		$brandRe = $this->rock->post('brandRe');

		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){	
            $dR = explode(",", $areaSearch);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( b.`weizhi`  , '$chkid' ) > 0   || INSTR( b.`routeline`  , '$chkid' ) > 0 || INSTR( d.`weizhi`  , '$chkid' ) > 0 || INSTR( d.`routeline`  , '$chkid' ) > 0   ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
			// $where.=" and (b.`weizhi` like '%$areaSearch%' or b.`routeline` like '%$areaSearch%' or d.`weizhi` like '%$areaSearch%' or d.`routeline` like '%$areaSearch%')";
		}

		if(!isempt($brandRe)){		


             $dR = explode(",", $brandRe);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( b.`yzbrand`  , '$chkid' ) > 0 || INSTR( d.`yzbrand`  , '$chkid' ) > 0 || INSTR( g.`yzbrand`  , '$chkid' ) > 0   ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
			//$where.=' and `yzbrand`='.$brandRe;
			// $where.=' and ('.$this->rock->dbinstr('b.yzbrand', $brandRe).' or '.$this->rock->dbinstr('d.yzbrand', $brandRe).')';
		}

		if(!isempt($timeRecord)&& $timeRecord!='全部'){	
		
			$tt=explode("-",$timeRecord);
			//日期筛选优化只选择了年的	
			if(isset($tt[1])){
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
			}else{
				$where.=" and (`createdt` like '%$timeRecord%')";
			}	
		}else if (!isempt($timeRecord) && $timeRecord=='全部'){
		}else{	
			if ($lx!='daiban_def'&&$lx!='daiban_daib'&&$lx!='daiban_gddaib'&&$lx!='daiban_rgfdaib'&&$this->admininfo['deptid']!='14') {
				$year= date('Y');
				$where.=" and (`createdt` > '$year')";
			}		
		}

		if(!isempt($courseRecord)){		

			$dR = explode(",", $courseRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="|| INSTR( a.`coursename`  , '$chkid' ) > 0  ";
                }
            }
            if ($str != '') $str = substr($str, 2);
            $where.=" and ( $str )";
			/*if ($courseRecord=='已处理完成') {
				# code...
				$where.=" and (a.`status`=1)";
			}else{*/

				// $where.=" and (a.`coursename` like '%$courseRecord%')";
			//}
		}

		if(!isempt($projectRecord)){		

			$dR = explode(",", $projectRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="|| INSTR( a.`author`  , '$chkid' ) > 0  ";
                }
            }
            if ($str != '') $str = substr($str, 2);
            $where.=" and ( $str )";
			// $where.=" and (a.`author` like '%$projectRecord%')";
		}

		if(!isempt($desginRecord)){		   
			$dR = explode(",", $desginRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="|| INSTR( a.`designer`  , '$chkid' ) > 0  ";
                }
            }
            if ($str != '') $str = substr($str, 2);
            $where.=" and ( $str )";
			// $where.=" and (a.`designer` like '%$desginRecord%')";
		}

		if(!isempt($key)){
				$where.=" and (a.`optname` like '%$key%' or a.`modename` like '%$key%' or a.`sericnum` like '%$key%' or a.`author` like '%$key%' or a.`coursename` like '%$key%' or a.`title` like '%$key%' or a.`chuban` like '%$key%' or a.`num` like '%$key%' or a.`designer` like '%$key%')";
			
		}	

		/*if ($lx!='daiban_def'&&$lx!='daiban_daib') {
			$condition=' and a.courseid not in( '.$flowcourseid.')';	
			if(!isempt($courseRecord)){		
				if ($courseRecord=='已处理完成') {
					$condition=' and a.courseid in( '.$flowcourseid.')';
				}else{
					$where.=" and (a.`coursename` like '%$courseRecord%')";
				}
			}
			$where.=''.$condition.'';			
		}*/
		//待办        省略部分人工费列表
		if($lx=='daiban_daib' || $lx=='daiban_def'){
			$where1 ='`isdel`=0  and (modeid=45 or modeid=55 or modeid=56 or modeid=59 or modeid=60) and '.$where;
			$fields1 ='a.*,b.author,b.telephone,b.rgfeeid,d.author dauthor,d.telephone dtelephone,d.rgfeeid drgfeeid,f.author fauthor,f.telephone ftelephone,g.rgfeeid grgfeeid';
			$table1 ='`[Q]flow_bill` a left join `[Q]book` b on (a.mid = b.id and a.modeid =45  ) left join `[Q]rzgongdi` d on (a.mid = d.id and a.modeid =55) left join `[Q]rgfee` f on (a.mid = f.id and a.modeid =56) left join `[Q]yzjuzhuang` g on (a.mid = g.id and a.modeid =59)  left join `[Q]jzrgfee` j on (a.mid = j.id and a.modeid =60)';
			$fields2 ='SUM(b.price) totalprice,SUM(d.price) totalprice2,SUM(g.price) totalprice3';
			$arr 	= $this->getlimit($where1, $page,$fields1,'a.`optdt` desc', $limit,$table1);
		}else if($lx=='daiban_gddaib'){	
			$where1 ='`isdel`=0  and (modeid=45 or modeid=55 or modeid=59) and '.$where;
			$fields1 ='a.*,b.author,b.telephone,b.rgfeeid,d.author dauthor,d.telephone dtelephone,d.rgfeeid drgfeeid,g.rgfeeid grgfeeid';
			$table1 ='`[Q]flow_bill` a left join `[Q]book` b on (a.mid = b.id and a.modeid =45  ) left join `[Q]rzgongdi` d on (a.mid = d.id and a.modeid =55) left join `[Q]yzjuzhuang` g on (a.mid = g.id and a.modeid =59)';
			$fields2 ='SUM(b.price) totalprice,SUM(d.price) totalprice2,SUM(g.price) totalprice3';
			$arr 	= $this->getlimit($where1, $page,$fields1,'a.`optdt` desc', $limit,$table1);
		}else if($lx=='daiban_rgfdaib'){

			$where1 ='`isdel`=0  and (modeid=56  or modeid=60) and '.$where;
			$fields1 ='a.*,f.coursename coursename,f.telephone ftelephone';
			$table1 ='`[Q]flow_bill` a left join `[Q]rgfee` f on (a.mid = f.id and a.modeid =56)  left join `[Q]jzrgfee` j on (a.mid = j.id and a.modeid =60)';
			$fields2 ="";
			$arr 	= $this->getlimit($where1, $page,$fields1,'a.`optdt` desc', $limit,$table1);
		}else{
			$where1 ='`isdel`=0  and (modeid=45 or modeid=55 or modeid=59) and '.$where;
			$fields1 ='a.*,b.author,b.telephone,b.rgfeeid,d.author dauthor,d.telephone dtelephone,d.rgfeeid drgfeeid,g.rgfeeid grgfeeid';
			$fields2 ='SUM(b.price) totalprice,SUM(d.price) totalprice2,SUM(g.price) totalprice3';
			$table1 ='`[Q]flow_bill` a left join `[Q]book` b on (a.mid = b.id and a.modeid =45  ) left join `[Q]rzgongdi` d on (a.mid = d.id and a.modeid =55) left join `[Q]yzjuzhuang` g on (a.mid = g.id and a.modeid =59)';
			$arr 	= $this->getlimit($where1, $page,$fields1,'a.`optdt` desc', $limit,$table1);
		}
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
			//$yzbrand	= '元贞国际设计';
			$statuscolor= '#888888';
			$ishui		= 0;
			if(isset($modearr[$rs['modeid']])){
				$mors 	= $modearr[$rs['modeid']];
				$modename 	= $mors['name'];
				$summary 	= $mors['summary'];
				$modenum 	= $mors['num'];
				$rers 		= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
				$summary	= $this->rock->reparr($summary, $rers);
				//var_dump($rers);
				if($rers){
					$status		 = $rs['status'];
					$statustext  = $this->statustext[$status];
					$statuscolor = $this->statuscolor[$status];
					if($rers['isturn']==0){
						$statustext  = '待提交';
						$statuscolor = '#ff6600';
					}else{
						if($status==0)$statustext='待'.$rs['nowcheckname'].'处理';
					}
					if($rers['status']==5)$ishui = 1;

					if(!isempt($rers['yzbrand'])){
						$lxa 	= explode(',', $rers['yzbrand']);
						$yzbrand	= "";
						foreach ($lxa as $key => $value) {
							# code...
							$br = $this->brandarr[$value];
							$yzbrand	.= ','.$br[0];
						}
					}
					if($yzbrand!=''){$yzbrand= substr($yzbrand, 1);}
				}else{
					$this->update('isdel=1', $rs['id']);
				}
			}
			  $today=strtotime($rers['endtime']); //今天 
			  $past=time();  //2015-5-20
			  $dif=ceil(($today-$past)/86400); //60s*60min*24h 
			$dif=str_pad($dif,3,"0",STR_PAD_LEFT); 
			if(!isempt($rs['chuban']))$title 		= '['.$rs['num'].']'.$rs['title'].'';
			$cont= '';
			//字段隐藏处理
			if(!isempt($rers['telephone'])){
				if ($this->isin) {	
					$cont= '<span style="font-size:13px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：****</span>';

				}else{
					$cont= '<span style="font-size:13px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：<a href="tel:'.$rers['telephone'].'">'.$rers['telephone'].'</a></span>';
				}
			}/*
			//字段隐藏处理
			if(!isempt($rs['dtelephone'])){
				if ($this->isin) {	
					$cont= '<span style="font-size:14px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：****</span>';

				}else{
					$cont= '<span style="font-size:14px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：<a href="tel:'.$rs['dtelephone'].'">'.$rs['dtelephone'].'</a></span>';
				}
			}
			//字段隐藏处理
			if(isset($rs['ftelephone'])&&!isempt($rs['ftelephone'])){
				if ($this->isin) {	
					$cont= '<span style="font-size:14px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：****</span>';

				}else{
					$cont= '<span style="font-size:14px">业主姓名：'.$rers['chuban'].'  &nbsp;  | &nbsp;联系方式：<a href="tel:'.$rs['dtelephone'].'">'.$rs['ftelephone'].'</a></span>';
				}
			}*/
			//字段隐藏处理

			if(!isset($rs['rgfeeid']))$rs['rgfeeid']=0;
			if(!isempt($rs['drgfeeid']))$rs['rgfeeid']=$rs['drgfeeid'];
			if(!isempt($rs['grgfeeid']))$rs['rgfeeid']=$rs['grgfeeid'];

			//if(!isempt($rs['title']))$cont.='<br>区域管理：'.$rs['num'].'  &nbsp;  | &nbsp;项目名称：'.$rs['title'].'';
			//if(!isempt($rs['designer']))$cont.='<br>工程监理：'.$rs['author'].'  &nbsp;  | &nbsp;设计师：'.$rs['designer'].'';
			if(!isempt($summary))$cont.='<br>摘要：'.$summary.'';
			//if(!isempt($rs['coursename']))$cont.='<br><span style="font-size:10px">工程监理：'.$rs['author'].'  &nbsp;  | &nbsp;设计师：'.$rs['designer'].'  &nbsp;  | &nbsp;状态：'.$rs['coursename'].'</span>';
			
			$coursename=' 状态：'.$rs['coursename'].'  &nbsp;  | &nbsp;';
			if($rs['status']){
				$coursename='';				
			}
			// $cont.='<span class="di-block mR20">合同金额 <span class="price-btn icon-btn">￥</span> 6666</span>' ;
			// $cont.='<span class="di-block mR20"><span class="time-btn icon-btn"><img src="web/images/time.png"></span>工期剩余<span class="color-red">108</span>天' ;

			/*if(!isempt($rers['yzbrand']==2)){
				# code...
				$cont.='<br><span style="font-size:10px">设计师：'.$rers['designer'].$coursename;
			}else{

				$cont.='<br><span style="font-size:10px">工程监理：'.$rers['author'].'  &nbsp;  | &nbsp;设计师：'.$rers['designer'].$coursename;
			}*/

        	$rs['optdt']=date("Y-m-d",strtotime($rs['optdt']));
        	$rs['footcon'] = $coursename .$statustext.'  &nbsp;  | &nbsp;<span style="color: #aaaaaa;"> '.$rs['optdt'] .'</span>';

			//if(!isempt($rs['nstatustext']))$cont.='<br>状态：'.$rs['nstatustext'].'';
			//if(!isempt($rs['checksm']))$cont.='<br>处理说明：'.$rs['checksm'].'';
			// $dt='22222222222222';
			$rs['optdt']='工程监理：'.$rers['author'].'  &nbsp;  | &nbsp;设计师：'.$rers['designer'].'  &nbsp;  | &nbsp;店长：'.$rers['mdarea'];
			$srows[]= array(
				'title' => $title,
				'cont' 	=> $cont,
				// 'dt' 	=> $dt,
				'ishui' => $ishui,
				'id' 	=> $rs['mid'],
				'uid' 	=> $rs['uid'],
				'optdt' 	=> $rs['optdt'],
				'sericnum' 	=> $rs['sericnum'],
				'applydt' 	=> $rs['applydt'],
				'rgfeeid' 	=> $rs['rgfeeid'],
				'price' 	=> $rers['price'],
				'statustext' 	=> $statustext,
				'yzbrand' 	=> $yzbrand,
				'statuscolor' 	=> $statuscolor,
				'status' 	=> $rs['status'],
				'modenum'		=> $modenum,
				'dif'		=> $dif,
				'courseid'		=> $rs['courseid'],
				'footcon'		=> $rs['footcon'],
				'modename'		=> $modename
			);
		}
		$arr['rows'] 	= $srows;
		
		//OA金额统计 计算总价 happy 20191125 begin
		if (!isempt($fields2)) {
			$rer 		= $this->db->getone($table1, $where1,$fields2);
			$totalprice = array_sum($rer);
			// var_dump($rer);die;
			$arr['totalprice'] 	= $totalprice;
		}
		//OA金额统计 计算总价 happy 20191125 end	
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
	{	//此处有bug，针对其他类型的待处理，没有以下数据来源，所以会报错
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
					if ($wdst) {
						unset($rs['coursename']);
					}
					if($rers['status']==5)$ishui = 1;
				}else{
					$this->update('isdel=1', $rs['id']);
				}
			}
			$status = '';
			// $status = '<font color="'.$statuscolor.'">'.$statustext.'</font>';
			if($wdst==0&&$rs['status']==0)$status='待<font color="blue">'.$rs['nowcheckname'].'</font>处理';
			//字段隐藏处理
			if(!isempt($rers['telephone'])){
				if ($this->isin) {	
					//$str = substr_replace($rs['tel'],'****',3,4);中文乱码报错，所以不用
					$rers['telephone']='****';	
				}else{
					$rers['telephone']='<a href="tel:'.$rers['telephone'].'" class="hhhh">'.$rers['telephone'].'</a>';		
				}
			}
			//字段隐藏处理
			
		if(!isempt($rers['yzbrand'])){
			$lxa 	= explode(',', $rers['yzbrand']);
			$yzbrand	= "";
			foreach ($lxa as $key => $value) {
				# code...
				$br = $this->brandarr[$value];
				$yzbrand	.= ','.$br[0];
			}
		}
		// 计算工期
		if($rers['courseid']==69||$rers['courseid']==79||$rers['courseid']==74||$rers['courseid']==137||$rers['courseid']==138||$rers['courseid']==139||$rers['status']==1||$rs['status']==1){
			$dif="已完工";
		}else{			
			$today=strtotime($rers['endtime']); //今天 
			$past=time();  //2015-5-20
			$dif=ceil(($today-$past)/86400); //60s*60min*24h 
		}
		if($yzbrand!=''){$yzbrand= substr($yzbrand, 1);$rers['yzbrand']	=$yzbrand;}
			// var_dump($rers);
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
				'rgfeeid' 	=> $rers['rgfeeid'],
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
				'title' 	=> $rers['title'],
				'num' 	=> $rs['num'],
				'chuban' 		=> $rers['chuban'],
				'yzbrand' 		=> $rers['yzbrand'],
				'footcon'		=> $rers['footcon'],
				'dif' 		=> $dif,
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