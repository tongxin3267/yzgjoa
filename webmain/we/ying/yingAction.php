<?php
class yingClassAction extends ActionNot{

	public function initAction()
	{
		$this->mweblogin(0, true);
	}
	public function defaultAction()
	{
		$num = $this->get('num');
		$arr = m('reim')->getagent(0, "and `num`='$num'");
		if(!$arr)exit('sorry not found agent['.$num.']!');
		$rs  = $arr[0];
		$this->title = $rs['name'];
		$yyurl 	= ''.P.'/we/ying/yingyong/'.$num.'.html';
		if(!file_exists($yyurl))$yyurl='';
		$yyurljs 	= ''.P.'/we/ying/yingyong/'.$num.'.js';
		if(!file_exists($yyurljs))$yyurljs='';

		$unitname	= m('flow_element')->getone("`mid`='7' and `fields`='unitname' order by `sort`",'*');
		$data['mianji'] = explode(',', $unitname['data']);

		//户型获取
		$huxing	= m('flow_element')->getone("`mid`='7' and `fields`='linkname' order by `sort`",'*');
		$data['huxing'] = explode(',', $huxing['data']);

		//户型获取
		$zxstyle	= m('flow_element')->getone("`mid`='7' and `fields`='zxstyle' order by `sort`",'*');
		$data['zxstyle'] = explode(',', $zxstyle['data']);

		$this->assign('data', $data);
		$this->assign('arr', $rs);
		$this->assign('yyurl', $yyurl);
		$this->assign('yyurljs', $yyurljs);
	}

	public function locationAction()
	{
		$this->title = '考勤定位';
		$arr 	= m('waichu')->getoutrows($this->date,$this->adminid);
		$this->assign('rows', $arr);
		$dt 	= $this->rock->date;
		$dwarr	= m('location')->getrows("uid='$this->adminid' and `optdt` like '$dt%'",'optdt,label,id','`id` desc');
		$this->assign('dwarr', $dwarr);
		$kqrs 	= m('kaoqin')->dwdkrs($this->adminid, $this->date);
		$this->assign('kqrs', $kqrs);
	}
	/**
	*	baishe好看的不要删就对了
	*/
	public function CAnalysisAction()
	{
		//面积
		$unitname	= m('flow_element')->getone("`mid`='7' and `fields`='unitname' order by `sort`",'*');
		$data['mianji'] = explode(',', $unitname['data']);

		//户型获取
		$huxing	= m('flow_element')->getone("`mid`='7' and `fields`='linkname' order by `sort`",'*');
		$data['huxing'] = explode(',', $huxing['data']);

		//户型获取
		$zxstyle	= m('flow_element')->getone("`mid`='7' and `fields`='zxstyle' order by `sort`",'*');
		$data['zxstyle'] = explode(',', $zxstyle['data']);

		$this->assign('data', $data);
		// echo json_encode($data);

	}
	/**
	*	计算剩余假期时间
	*/
	public function getqjsytime($uid, $type, $dt='', $id=0)
	{
		$types 	= '增加'.$type.'';
		if($type=='调休')$types='加班';
		if($dt=='')$dt = $this->rock->now;
		$to1	= $this->db->getmou('[Q]kqinfo',"sum(totals)", "`kind`='请假' and `qjkind`='$type' and `uid`='$uid' and `id`<>$id ");
		$zto	= $this->db->getmou('[Q]kqinfo',"sum(totals)", "`kind`='$types' and `uid`='$uid'  and `status`=1 and `stime`<='$dt'");
		if(is_null($to1))$to1=0;
		if(is_null($zto))$zto=0;
		return intval($zto) - intval($to1);
	}

	//happy_add 异步获取统计数据
	public function loadDataAction()
	{
		$this->title = '统计分析';
		$where	= "(uid=".$this->adminid." or ".$this->adminid."=1 or ".$this->adminid."=188 or ".$this->rock->dbinstr('shateid', $this->adminid).' or '.$this->rock->dbinstr('gddesignerid', $this->adminid).' or '.$this->rock->dbinstr('rzdesignerid', $this->adminid).' or '.$this->rock->dbinstr('markerid',$this->adminid).'  or '.$this->rock->dbinstr('rzmarkerid',$this->adminid).'  or '.$this->rock->dbinstr('rzmendianid',$this->adminid).' or '.$this->rock->dbinstr('mendianid', $this->adminid)." or (".$this->adminid."=513  and `shateid` is not null))";


		$areaSearch = $this->rock->post('areaSearch');
		$timeRecord = $this->rock->post('timeRecord');
		$desginRecord = $this->rock->post('desginRecord');
		$laiyuanRecord = $this->rock->post('laiyuanRecord');
		$unitnameRecord = $this->rock->post('unitnameRecord');
		$unitname1 = $this->rock->post('unitname1');
        $brandRe = $this->rock->post('brandRe');
        $supplierId = $this->rock->post('supplierId');
        $supplierName = $this->rock->post('supplierName');
		$fields='status';

        $clskefuid = getconfig('clskefuid');
        $userdata = m('admin')->getinfor($this->adminid);
        $leftJoin = '';
        if (false !== strpos($userdata['unitname'], "供应商")||((1==$this->adminid || $clskefuid==$this->adminid)&&!empty($supplierId))) {
            $supplier_id=!empty($supplierId)?$supplierId:$this->adminid;
            $leftJoin = 'left join `xinhu_supplier_customer` on `xinhu_supplier_customer`.customer_id=`[Q]customer`.id and `xinhu_supplier_customer`.supplier_id='.$supplier_id;
            $fields='`xinhu_supplier_customer`.`status`';
            $where.=' and `[Q]customer`.shate like "%'.$supplierName.'%"';
            if ($clskefuid==$this->adminid) {
            	$where .=' and `shateid` is not null';
	        }
        }
		if(!isempt($brandRe)){
			// $where.=' and `yzbrand`='.$brandRe;
			$where.=' and ' .$this->rock->dbinstr('yzbrand', $brandRe);
			if ($brandRe==2) {
				$fields='rzstatus';
			}
		}

        $rzdetpid = getconfig('rzdetpid');
        $isinrz = in_array($userdata['deptid'], $rzdetpid);

			
        if ($isinrz) {
			$fields='rzstatus';
        }
		// $this->showreturn($fields);


		//happy_add 新增 筛选 查询
		if(!isempt($areaSearch)){
			$where.=" and (`routeline` like '%$areaSearch%' or `email` like '%$areaSearch%' )";
		}

		if(!isempt($timeRecord) && $timeRecord!='全部'){
			$tt=explode("-",$timeRecord);
			//日期筛选优化只选择了年的
			if(isset($tt[1])){
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
				$where.=" and (`fpdate` like '%$timeRecord%')";
			}
		}else if (!isempt($timeRecord) && $timeRecord=='全部'){
		}else{
			$year= date('Y');
			$where.=" and (`adddt` > '$year')";
		}
		if(!isempt($desginRecord)){
            $dR = explode(",", $desginRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `shate`  , '$chkid' ) > 0 || INSTR( `gddesigner`  , '$chkid') > 0 || INSTR( `rzdesigner`  ,'$chkid' ) > 0 ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";

		}
		if(!isempt($laiyuanRecord)){

            $dR = explode(",", $laiyuanRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="|| INSTR( `laiyuan`  , '$chkid' ) > 0  ";
                }
            }
            if ($str != '') $str = substr($str, 2);
            $where.=" and ( $str )";
            /*
			switch ($laiyuanRecord) {
				case '线上':$where.=" and (`laiyuan` like '优居客' or `laiyuan` like '土巴兔' or `laiyuan` like 'icolor' or `laiyuan` like '丁丁网' or `laiyuan` like '凯特猫' or `laiyuan` like '家要美' or `laiyuan` like '网上预约' or `laiyuan` like '齐家网' or `laiyuan` like '云空间' or `laiyuan` like '吉宅网' or `laiyuan` like '信用家' or `laiyuan` like '城居网')";
					break;
				case '线下':$where.=" and (`laiyuan` like '工程部营销' or `laiyuan` like '其他' or `laiyuan` like '介绍' or `laiyuan` like '进店' or `laiyuan` like '来电咨询' or `laiyuan` like '电销部')";
					break;
				default:$where.=" and (`laiyuan` like '%$laiyuanRecord%')";
					break;
			}*/
		}

		if(!isempt($unitnameRecord)){
             $dR = explode(",", $unitnameRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `unitname`  , '$chkid' ) > 0 || INSTR( `zxstyle`  , '$chkid') > 0|| INSTR( `linkname`  , '$chkid') > 0 ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
		}

		$rows 	= $this->db->getall('SELECT '.$fields.' status,laiyuan,count(*) ta,group_concat(laiyuan) FROM `[Q]customer` '.$leftJoin.' where '.$where.' GROUP BY '.$fields.'');

		//$rows 	= $this->db->getall('SELECT status,laiyuan,count(*) ta,group_concat(laiyuan) FROM `[Q]customer`  GROUP BY status');
		//$rows 	= $this->db->getmou("[Q]customer","status,laiyuan,count(*) ta,group_concat(laiyuan)","uid='$this->adminid' GROUP BY status");
		//$rows 	= $this->db->getall("[Q]customer","status,laiyuan,count(*) ta,group_concat(laiyuan)","(uid='$this->adminid' or '$this->adminid'=1) GROUP BY status");
		//$a1= explode(',','待量,无效,退,重,跟进,意向,败,签,待定');
		$a1= explode(',','待量单,无效单,已退单,重单,跟进单,意向单,失败单,已签单,待定单');
		// $this->showreturn($rows);
		error_reporting(0);
		//渠道获取
		$laiyuan	= m('flow_element')->getone("`mid`='7' and `fields`='laiyuan' order by `sort`",'*');
		$a2 = explode(',', $laiyuan['data']);

		// $a2= explode(',','土巴兔,优居客,丁丁网,icolor,凯特猫,家要美,电销部,介绍,进店,来电咨询,网上预约,工程部营销,其他,齐家网,云空间,吉宅网,信用家,城居网');
		$bo=array();
		$a3=array();

		// 量房率=（重单+跟进单+意向单+失败单+已签单+待定单）除以总单数
		$oarr=array(3,4,5,6,7,8);
		$totals_all=0;
		$others_all=0;
		foreach($rows as $k=>$rs){
			$totals_all+=$rows[$k]['ta'];
			if (in_array($rows[$k]['status'], $oarr)) {
				$others_all+=$rows[$k]['ta'];
			}
			if ($rs['status']==127) {
				$rows[$k]['status']		= '无';
			}else{
				$rows[$k]['status']		= isset($a1[$rs['status']])?$a1[$rs['status']]:$a1[0];
			}
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
		$lfl=round($others_all/$totals_all*100,2);
		$data['others_all']=$others_all;
		$data['totals_all']=$totals_all;
		$data['lfl']=$lfl;
		// $this->showreturn($lfl);
		//去掉全0
		foreach($bo as $bk=>$bs){
			
			//if (is_array($bs)) {
				if($sum=array_sum($bs)==0)
				{
					unset($bo[$bk]);
			//	}
			}
		}

		$data['data']=$rows;
		$data['bo']=$bo;

		$this->showreturn($data);
	}
	/*




	public function loadDataAction()
	{	public function getall($where, $fields='*', $order='', $limit='')
   58  	{
   59  		$sql	= $this->db->getsql(array(
   ..
   64  			'limit'		=> $limit
   65  		));
   66: 		return $this->db->getall($sql);
   67  	}
   68
		//$mid	= (int)$this->post('mid');
		//$fields	= $this->post('fields');
		$data	= m('flow_element')->getone("`mid`='7' and `fields`='laiyuan' order by `sort`",'*');

		//$arr 	= m('waichu')->getoutrows($this->date,$this->adminid);
		$lxa 	= explode(',', $data['data']);
		$this->showreturn($lxa);
	}*/
}