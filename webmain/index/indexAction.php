<?php 
class indexClassAction extends Action{
	
	public function defaultAction()
	{
		$afrom 			= $this->get('afrom');
		$this->tpltype	= 'html';
		$my			= $this->db->getone('[Q]admin', "`id`='$this->adminid'",'`face`,`id`,`name`,`ranking`,`deptname`,`deptallname`,`type`,`style`');
		$allmenuid	= m('sjoin')->getuserext($this->adminid, $my['type']);
		
		$mewhere	= '';
		$isadmin	= 1;
		$myext		= $allmenuid;
		if($myext != '-1'){
			$isadmin	= 0;	
			$mewhere	= ' and `id` in('.str_replace(array('[',']'), array('',''), $myext).')';
		}
		$this->rock->savesession(array(
			'adminallmenuid'	=> $allmenuid,
			'isadmin'			=> $isadmin
		));
		$this->smartydata['topmenu'] 	= m('menu')->getall("`pid`=0 and `status`=1 $mewhere order by `sort`");
		
		
		
		$this->smartydata['showkey']	= $this->jm->base64encode($this->jm->getkeyshow());
		$this->smartydata['my']			= $my;
		$this->smartydata['afrom']		= $afrom;
		$this->smartydata['face']		= $this->rock->repempt($my['face'], 'images/noface.png');
		$this->smartydata['style']		= $this->rock->repempt($my['style'], '0');
	}
	
	private function menuwheres()
	{
		$this->menuwhere = '';
		$myext	= $this->getsession('adminallmenuid');
		if($myext != '-1'){	
			$this->menuwhere	= ' and `id` in('.str_replace(array('[',']'), array('',''), $myext).')';
		}
	}
	
	/**
	*	搜索菜单
	*/
	public function getmenusouAjax()
	{
		$key = $this->post('key');
		$this->menuwheres();
		$this->addmenu = m('menu')->getall("`status`=1 $this->menuwhere and `name` like '%$key%' and ifnull(`url`,'')<>'' order by `pid`,`sort` limit 10",'`id`,`num`,`url`,`icons`,`name`,`pid`');
		$arr	= $this->getmenu(0, 1);
		$this->returnjson($arr);
	}
	
	/**
	*	获取菜单
	*/
	public function getmenuAjax()
	{
		$pid 	= $this->get('pid');
		$this->menuwheres();
		$this->addmenu = m('menu')->getall("`status`=1 $this->menuwhere order by `sort`,`id`",'`id`,`num`,`url`,`icons`,`name`,`pid`');
		$arr	= $this->getmenu($pid,0);
		$this->returnjson($arr);
	}
	
	private function getmenu($pid, $lx=0)
	{
		$menu	= $this->addmenu;
		$rows	= array();
		foreach($menu as $k=>$rs){
			if($lx == 0 && $pid != $rs['pid'])continue;
			$num			= $rs['num'];
			$sid			= $rs['id'];
			$icons			= $rs['icons'];
			if(isempt($num))$num 		= 'num_'.$sid;
			if(isempt($icons))$icons 	= 'bookmark-empty';
			$rs['icons']	= $icons;
			$rs['num']		= $num;
			if($lx == 0){
				$children		= $this->getmenu($sid);
				$rs['children']	= $children;
				$rs['stotal']	= count($children);
			}else{
				$rs['stotal']	= 0;
			}
			$rows[] = $rs;
		}
		return $rows;
	}
	
	public function downAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}
	public function showimageAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->showimage($id);
	}
	
	/**
	*	单页显示
	*/
	public function showAction()
	{
		$url 	= $this->get('url');
		if($url=='')exit('无效请求');
		$this->defaultAction();
	}
	
	/**
	*	获取模版文件
	*/
	public function getshtmlAction()
	{
		$surl = $this->jm->base64decode($this->get('surl'));
		if(isempt($surl))exit('not found');
		$file = ''.P.'/'.$surl.'.php';
		if(!file_exists($file))$file = ''.P.'/'.$surl.'.shtml';
		if(!file_exists($file))exit('404 not found '.$surl.'');
		$this->displayfile = $file;
	}

	/**
	*	财务流程后每隔3天不到提醒催款涵
	*/
	public function getoverdueAction()
	{
		//step1.select from book where courseid in flow_pay
    	$flowcourseid = getconfig('flow_pay');
    	$flow_pay2user = getconfig('flow_pay2user');
        $file2  = 'overdue.txt';
        $file  = 'overdue2.txt';

		//$flow_payuser = getconfig('flow_payuser');

		$where 	= 'courseid in( '.$flowcourseid.')';
		$where2	= 'courseid in( '.$flow_pay2user.')';
        $tbook = m('flow_bill')->getall($where,'mid,title,chuban,author,num,nowcheckname');
        $tbook2 = m('flow_bill')->getall($where2,'mid,title,chuban,author,num,nowcheckname');
		//step1.end
		//step2.循环 find from log  where courseid in flow_pay and mid =循环的id
		foreach($tbook as $k=>$rs){
			$where= 'courseid in( '.$flowcourseid.') and mid='.$rs['mid'].'';
			$rows = $this->db->getone('[Q]flow_log',$where, 'optdt','`id` desc');
  			$past=strtotime ($rows['optdt']);  //2015-5-20
			$dif=ceil((time()-$past)/86400);
			//step3.判断能不能整除3 yes→4，no→end
			if ($dif%3==0) {
				//step4.发送短信 begin
				$cont = '您有一个['.$rs['num'].']['.$rs['title'].']['.$rs['chuban'].']的工地应推进流程,已经过了'.$dif.'天';	
				if($cont!='')m('sms')->postsms2($cont, $rs['nowcheckname']);				
				//step4.发送短信 end
			}
		} 
        $s= file_put_contents($file2,'login---getoverdueAction'.$cont.date("Y-m-d H:i:s",time())."\n"."\r\n",FILE_APPEND);

		//新增     财务收款阶段阶段通知付款信息调整设置为通知客户 20180412
		foreach($tbook2 as $k=>$rs){
			$where2= 'courseid in( '.$flow_pay2user.') and mid='.$rs['mid'].'';
			$rows = $this->db->getone('[Q]flow_log',$where2, 'optdt','`id` desc');
  			$past=strtotime ($rows['optdt']);  //2015-5-20
			$dif=ceil((time()-$past)/86400);
			//step3.判断能不能整除3 yes→4，no→end
			if ($dif%3==0) {
				//step4.发送短信 begin
				//$cont = '您有一个['.$rs['num'].']['.$rs['title'].']['.$rs['chuban'].']的工地应推进流程,已经过了'.$dif.'天';	
				$cont = '温馨提示：尊敬的客户您好，为保障施工顺利进行，请熟知合约《资金安全协议》及《安全账号告知函》中的支付方式尽快支付工程款项，款项的拖延会导致工期顺延或停工及其他损失，如对款项金额或支付方式有疑问可致电021-67100131。感谢您的支持与配合，祝装修愉快！元贞财务部';	
				if($cont!='')m('sms')->postsms2($cont, $rs['chuban']);				
				//step4.发送短信 end
			}
		} 

        //催款记录
        $s= file_put_contents($file,'财务收款阶段阶段通知付款信息调整设置为通知客户'.$cont.date("Y-m-d H:i:s",time())."\n"."\r\n",FILE_APPEND);die(1);

	}
}