<?php 
class indexClassAction extends apiAction
{
	public function indexAction()
	{
		$this->showreturn('','error', 203);
	}
	
	/**
	*	手机app读取
	*/
	public function indexinitAction()
	{
		$dbs 			= m('reim');
		$ntime			= floatval($this->post('ntime'));
		$uid 			= $this->adminid;
		//$reimarr 		= m('reim')->getwdarr($uid);
		//$arr['reimarr'] = $reimarr;
		$arr['loaddt']  	= $this->now;
		$arr['splittime'] 	= (int)($ntime/1000-time());
		$arr['reimarr']		= $dbs->gethistory($uid);
		$this->showreturn($arr);
	}
	
	public function lunxunAction()
	{
		$uid 			= $this->adminid;
		$loaddt			= $this->post('loaddt');
		//$reimarr 		= m('reim')->getwdarr($uid, $loaddt);
		$reimarr 		= m('reim')->gethistory($uid, $loaddt);
		$arr['reimarr'] = $reimarr;
		$arr['loaddt']  = $this->now;
		m('login')->uplastdt();
		$this->showreturn($arr);
	}
	
	
	//应用获取数据
	public function getyydataAction()
	{
		$num 	= $this->post('num');
		$event 	= $this->post('event');
		$page 	= (int)$this->post('page');
		$rows 	= m('agent:'.$num.'')->getdata($this->adminid, $num, $event, $page);
		
		$this->showreturn($rows);
	}
	
	//应用获取跟进记录
	public function getyrecorddataAction()
	{
		$id 	= $this->post('id');
		$modenum 	= $this->post('modenum')=='undefined'?'customer':$this->post('modenum');
		$arr 	= m('flow_log')->getlimit("mid ='$id'  and `table` = '$modenum' ", '0', '*', 'id desc', '5', $flow_log);		
		$this->showreturn($arr);
	}
	
	public function yyoptmenuAction()
	{
		$num 	= $this->post('modenum');
		$sm 	= $this->post('sm');
		$optid 	= (int)$this->post('optmenuid');
		$zt 	= (int)$this->post('statusvalue');
		$mid 	= (int)$this->post('mid');
		$msg 	= m('flow')->opt('optmenu', $num, $mid, $optid, $zt, $sm);
		if($msg != 'ok')$this->showreturn('', $msg, 201);
		$this->showreturn('');
	}
	
	public function pushtestAction()
	{
		m('reim')->pushagent('1','会议','关于端午节放假通知');
		//$a = c('apiCloud')->send(1,'通知','内容');
		//$a = c('JPush')->send('2','发来一条消息', '内容');
		//print_r($a);
		echo 'ok';
	}
	
	public function changetxAction()
	{
		$apptx = (int)$this->post('apptx');
		m('admin')->update("`apptx`='$apptx'", $this->adminid);
		$this->showreturn('');
	}
	//happy_add获取流程步骤
	public function loadbookdatacourseAction()
	{
		$setid	= (int)$this->post('setid');
		$brandRes	= (int)$this->post('brandRes');

		$designdeptid=explode(',', getconfig('designdetpid2'));
		//var_dump($this);die();
		//梦依达软装
		if ($brandRes==2||$this->adminid==187||in_array($this->userrs['deptid'],$designdeptid)) {
			# code...
			$setid	= 55;
		}elseif ($brandRes==4) {
			$setid	= 59;
		}
		$data	= m('flow_course')->getall("`setid`='$setid' order by `sort`",'*');
		$this->showreturn($data);
	}

	//happy_add获取管理员
	public function loaddesigndataAction()
	{
		$setid	= (int)$this->post('setid');
		$data	= m('admin')->getall("`deptid`='$setid' order by `sort`",'*');
		$this->showreturn($data);
	}

	//happy_add获取多个id的用户
	public function loadshichangdataAction()
	{
		$uid 			= $this->adminid;
		$highUid = getconfig('high_view');
		$isin = in_array($this->adminid,$highUid);

		if($isin){
			//客服部和管理员---直接加的id   happy20171025
			/*$setid	= $this->post('setid');
			$data	= m('admin')->getall("`deptid` in($setid) order by `sort`",'*');*/
			$setid	= $this->post('setid');

			if ($this->adminid==187) {
				$setid	.= '2';
			}else if($this->adminid==5||$this->adminid==14) {
				$setid	.= '0';
			}else{
				$brandRe = $this->rock->post('brandRes');
				if(!isempt($brandRe)){	
					$setid	.= $brandRe;
				}
			}
			$designdetpid = getconfig($setid);
			$data	= m('admin')->getall("`deptid` in($designdetpid) order by `sort`",'*');
		}else{
			$where = m('adming')->getdeptids($uid,'gddesignerid');
			$data	= m('admin')->getall("`id` in($where) order by `sort`",'*');

			//var_dump($where);
		}
		//$data	= m('admin')->getall("`deptid`='$setid' order by `sort`",'*');
		$this->showreturn($data);
	}

	//happy_add获取多个id的用户
	public function loadbuildindataAction()
	{
		$setid	= $this->post('setid');
		if ($setid=='clgys') {
			//获取材料供应商
			$data  = m('admin')->getall("instr(`deptpath`,'[38]')>0 order by `deptid`",'id,name');
		}elseif ($setid=='fucaigys') {
			//获取辅材供应商
			$data  = m('admin')->getall("instr(`deptpath`,'[35]')>0 order by `deptid`",'id,name');
		}else{
			//获取工程监理
			$data  = m('admin')->getall("instr(`deptpath`,'[8]')>0 order by `deptid`",'id,name');
		}
		$this->showreturn($data);
	}

    //happy_add获取管理员
    public function loadelementdataAction()
    {
        $mid	= (int)$this->post('mid');
        $fields	= $this->post('fields');
        $data	= m('flow_element')->getone("`mid`='$mid' and `fields`='$fields' order by `sort`",'*');

        $lxa 	= explode(',', $data['data']);
        $this->showreturn($lxa);
    }

    //xin.zou获取供货商数据
    public function loadsupplierdataAction()
    {
        $mid	= (int)$this->post('mid');

        $where ="";
        //获取该客户共享的供应商
        if($mid){
        	$data	= m('customer')->getone("`id`='$mid'",'shateid');
	        $clsIds = $data['shateid'];
	        $where ="id in ({$clsIds})";
        }else{
	        $clsIds = implode(',',getconfig('clsdeptid',array()));
	        $where ="deptid in ({$clsIds})";	        	
        }
        //供应商获取
        $clsList   = m('admin')->getall($where,'*');
        $this->showreturn($clsList);
    }
}