<?php 
class loginClassAction extends ActionNot{
	
	public function defaultAction()
	{
		$this->tpltype	= 'html';
		$this->smartydata['ca_adminuser']	= $this->getcookie('ca_adminuser');
		$this->smartydata['ca_rempass']		= $this->getcookie('ca_rempass');
		$this->smartydata['ca_adminpass']	= $this->getcookie('ca_adminpass');
	}
	
	public function checkAjax()
	{
		$user 	= $this->jm->base64decode($this->post('adminuser'));
		$user	= str_replace(' ','',$user);
		$pass	= $this->jm->base64decode($this->post('adminpass'));
		$rempass= $this->post('rempass');
		$jmpass	= $this->post('jmpass');
		if($jmpass == 'true')$pass=$this->jm->uncrypt($pass);
		$arr 	= m('login')->start($user, $pass, 'pc');
		$barr 	= array();
		if(is_array($arr)){
			$uid 	= $arr['uid'];
			$name 	= $arr['name'];
			$user 	= $arr['user'];
			$token 	= $arr['token'];
			$face 	= $arr['face'];
			m('login')->setsession($uid, $name, $token, $user);
			$this->rock->savecookie('ca_adminuser', $user);
			$this->rock->savecookie('ca_rempass', $rempass);
			$ca_adminpass	= $this->jm->encrypt($pass);
			if($rempass=='0')$ca_adminpass='';
			$this->rock->savecookie('ca_adminpass', $ca_adminpass);
			$barr['success'] = true;
			$barr['face'] 	 = $face;
			$barr['maxsize'] = c('upfile')->getmaxzhao();
		}else{
			$barr['success'] = false;
			$barr['msg'] 	 = $arr;
		}
		$this->returnjson($barr);
	}
	
	public function exitAction()
	{
		m('login')->exitlogin('pc',$this->admintoken);
		$this->rock->location('?m=login');
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