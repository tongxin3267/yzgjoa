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
		//$where	= '`status`=0 and isdel=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
		$rows 	= $this->db->getall('SELECT status,laiyuan,count(*) ta,group_concat(laiyuan) FROM `[Q]customer`  GROUP BY status');
		$a1= explode(',','待量单,无效单,已退单,重单,跟进单,意向单,失败单,已签单,待定单');
		//$a2= explode(',','土巴兔,优居客, 丁丁网, icolor,凯特猫, 家要美, 电销部, 介绍, 进店, 来电咨询, 网上预约, 工程部营销, 其他');
		foreach($rows as $k=>$rs){
			//$rows[$k]['laiyuan']		= $rs['laiyuan'];
			$rows[$k]['status']		= $a1[$rs['status']];
			$ii=explode(",",$rs['group_concat(laiyuan)']);

			$rows[$k]['ba']	=array_count_values($ii);
			//$rows[$k]['ba']		= $rs['group_concat(laiyuan)'];
			//$rows[$k]['ba']		= $a1[$rs['group_concat(status)']];
		}


/*

			$yyyy[$k]	=array_count_values($ii);
			//对比数组a2整一下


			
        $file  = 'paylog.txt';
        echo $content='-------'.var_export($rows,true).'-----</br>';  
        $f= file_put_contents($file,"订单支付".$content."\n",FILE_APPEND);*/
		$this->showreturn($rows);
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