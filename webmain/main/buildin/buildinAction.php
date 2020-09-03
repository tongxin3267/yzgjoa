<?php
class buildinClassAction extends Action
{

	public function editAction()
	{
		$id = (int)$this->get('id',0);
		$data = m('buildin')->getone($id);
		$author=$data['author'];
		$jianli = m('userinfo')->getone("`name`='$author'",'mobile');



		//倒计时计算 begin				
	    $clgysid=$data['clgysid'];
		$deptid = m('admin')->getone("`id`='$clgysid'",'deptid');

		$anzcl=getconfig('anzcl');
		$anzonly=getconfig('anzonly');
		$deptid = $deptid['deptid'];

		$zxstyle=$data['zxstyle'];
	
		if (in_array($data['cid'.$suffix], $anzonly)) {
			// 安装
			$i=3;			
			$j=2;			
		}else{
			// 测量
			$j=$i=1;
		}
		if ($zxstyle!=1) {
			// 新版时间，加上步长
			$i+=$zxstyle;
		}else{
			// 旧版时间
			$i=$j;
		}
		// var_dump($i);
		$day=$anzcl[$deptid]['day'.$i];
		//倒计时计算 end				

        $logarr = $this->getlog($id,'buildin');
		$this->assign('logarr', $logarr);
		// var_dump($logarr);die;
		
		$this->assign('mobile', $jianli['mobile']);
		$this->assign('gongdiinfo', $data);
		$this->assign('day', $day);
		$this->assign('adminid',$this->adminid);
		//$this->returnjson($arr);
	}
	
	public function historyV1Action()
	{
		//v1----展示工地所有配送记录
		$id = (int)$this->get('id',0);
		$rows =  m('buildin')->getall("`fid`='$id' and ( clgysid='$this->adminid' or '$this->adminid'=1	) ",'*','cid');
	
		//var_dump($rows);
		$this->assign('gongdiinfo', $rows);
		$this->assign('adminid',$this->adminid);

        $logarr = $this->getlog($id,'buildin');
		$this->assign('logarr', $logarr);
		if($this->rock->ismobile()){
			$spagepath 	= P.'/main/buildin/view.html';
		}
		$this->smartydata['spagepath']		= $spagepath;
		//$this->returnjson($arr);
	}

	public function historyAction()
	{
		//v2----展示当前选择的配送记录
		$id = (int)$this->get('id',0);
		$rows =  m('buildin')->getone($id);
		$author=$rows['author'];
		$jianli = m('userinfo')->getone("`name`='$author'",'mobile');
		
		$this->assign('mobile', $jianli['mobile']);
	
		//var_dump($rows);
        $logarr = $this->getlog($id,'buildin');
		$this->assign('logarr', $logarr);
		$this->assign('gongdiinfo', $rows);
		$this->assign('adminid',$this->adminid);

		//$this->returnjson($arr);
		/*if($this->rock->ismobile()){
			$this->displayfile = ''.P.'/public/fileopen.html';			
		}*/
	}
	
	public function editsaveAjax()
	{
		$id = (int)$this->get('id',0);
		$type = $this->get('type');
		$xgwj = $this->get('xgwj');
		$explain = $this->get('explain');
		$dealdt 		= date('Y-m-d H:i:s');

		//happy_add 短信通知材料申请人&工程监理
		$anzonly=getconfig('anzonly');
		$buildininfo = m('buildin')->getone($id);
		$status=$type==1?3:1;

		if (in_array($buildininfo['cid'], $anzonly)) {
			$progress 		= 4; //工地进度：1测量中 2待安装 3安装中 4已安装 5已完成
		}else{
			$progress 		= 2; //工地进度：1测量中 2待安装 3安装中 4已安装 5已完成			
		}
		if ($type==1) {
			$progress		= 5; //工地进度：1测量中 2待安装 3安装中 4已安装 5已完成			
		}
		$data = m('buildin')->update(array(
				'status' 	=> $status,
				'progress' 	=> $progress,
				'dealdt' 	=> $dealdt,
				'xgwj' 	=> $xgwj,
				'explain' 	=> $explain,
				'dealid' 	=> $this->adminid,
			), $id);
		if($data){
			$flag= in_array($buildininfo['cid'], $anzonly)?'安装':'测量';
			
			$cont=$buildininfo['author'].'监理的'.$buildininfo['title'].'工地'.$buildininfo['clgysname'].'已经'.$flag.'完成，请注意监督查看！';
			if(!empty($buildininfo)){
				m('sms')->postsms('安装测量通知申请人', $cont, $buildininfo['optid']);
			}
			if(!empty($buildininfo)){
				m('sms')->postsms2($cont,$buildininfo['author']);
			}
			echo '处理成功';
		}else{
			echo '处理失败';
			# code...
		}
		//$this->returnjson($arr);
	}

	public function cancelAjax()
	{
		$id = (int)$this->get('id',0);
		$remark = $this->get('remark');
		$type = $this->get('type');
		$dealdt 		= date('Y-m-d H:i:s');

		//happy_add 短信通知材料申请人&工程监理
		$anzonly=getconfig('anzonly');
		$buildininfo = m('buildin')->getone($id);
		$status=$type==1?3:1;

		$progress 		= 6; //工地进度：1测量中 2待安装 3安装中 4已安装 5已完成 6已取消
	
		$data = m('buildin')->update(array(
				'status' 	=> $status,
				'remark' 	=> $remark,
				'progress' 	=> $progress,
				'dealdt' 	=> $dealdt,
				'dealid' 	=> $this->adminid,
			), $id);
		if($data){
			$flag= in_array($buildininfo['cid'], $anzonly)?'安装':'测量';
			
			$cont=$buildininfo['author'].'监理的'.$buildininfo['title'].'工地已经取消'.$flag.'，请注意！！！';
			if(!empty($buildininfo)){
				m('sms')->postsms('安装测量通知申请人', $cont, $buildininfo['clgysid']);
			}
			if(!empty($buildininfo)){
				m('sms')->postsms2($cont,$buildininfo['author']);
			}
			echo '处理成功';
		}else{
			echo '处理失败';
			# code...
		}
		//$this->returnjson($arr);
	}
	public function editsave2Ajax()
	{
		$id = (int)$this->get('id',0);
		$goods = $this->get('goods');
		$alltotal = $this->get('alltotal');
		$totalprice = $this->get('totalprice');
		$updatedt 		= date('Y-m-d H:i:s');

		$type = $this->get('type');
		if ($type==1) {
			$alltotal=-abs($alltotal);
			$totalprice=-abs($totalprice);
		}

		$data = m('buildin')->update(array(
				'goods' 	=> $goods,
				'alltotal' 	=> $alltotal,
				'totalprice' 	=> $totalprice,
				'updatedt' 	=> $updatedt,
				'updateid' 	=> $this->adminid,
			), $id);

		if($data){
			//happy_add 短信通知材料供应商&工程监理
			$buildininfo = m('buildin')->getone($id);
			$cont=$buildininfo['author'].'监理的'.$buildininfo['title'].'工地测量项目已变更，请注意查看，并及时调整方案！';
			if(!empty($buildininfo)){
				m('sms')->postsms('安装测量通知供应商', $cont, $buildininfo['clgysid']);
			}
			if(!empty($buildininfo)){
				m('sms')->postsms2($cont,$buildininfo['author']);
			}
			echo '处理成功';
		}else{
			echo '处理失败';
			# code...
		}
		//$this->returnjson($arr);
	}
	public function beforeshow($table)
	{
		//var_dump($this->adminid); 
		// 会调用flow下buildnmodel，所以此处可省略
		/*$fields = 'a.*,b.mobile';
		$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	or '.$this->adminid.'=10 or '.$this->adminid.'=12 ) and (status in (0,2))';
		$key 	= $this->post('key');
		if($key!=''){
			$s.= m($table)->getkeywhere($key);
		}

		return array(
			'table' => '`[Q]buildin` a left join `[Q]userinfo` b on a.author=b.name',
			'fields'=> $fields,
			'where'	=> $s,
			'order' =>'dealdt desc ,createdt desc',
		);*/
	}
	public function groupshow($table)
	{
		//var_dump($this->adminid);
		/*$fields = 'a.*,b.mobile';
		//$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	) group by fid';
		$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	or '.$this->adminid.'=10 or '.$this->adminid.'=12 ) and (status in (1,3))';
		$key 	= $this->post('key');
		$timeRecord = $this->rock->post('timeRecord');
		$timeRecord2 = $this->rock->post('timeRecord2');
		if($key!=''){
			$s.= m($table)->getkeywhere($key);
		}

		if(!isempt($timeRecord) && !isempt($timeRecord2)){
			$s.=" and (`dealdt` between '$timeRecord' and '$timeRecord2')";
		}
		return array(
			'table' => '`[Q]buildin` a left join `[Q]userinfo` b on a.author=b.name',
			'fields'=> $fields,
			'where'	=> $s,
			'order' =>'dealdt desc ,createdt desc',
		);*/
	}
	
	public function fieldsafters($table, $fid, $val, $id)
	{
		$fields = '*';
		//$fields = 'title,chuban,telephone,designer';
		//$fields = 'sex,ranking,tel,mobile,workdate,email,quitdt';
	}
	public function updatedataAjax()
	{
		echo "string";
		$a = $this->updatess();
		echo '总'.$a[0].'条记录,更新了'.$a[1].'条';
	}
	
	public function updatess($whe='')
	{
		return m('buildin')->updateinfo($whe);
	}
	


    public function getlog($id,$table='buildin')
    {
        // $where = '  `mid` =' . $id . ' and `table` =' . $table . '';
		$rows = m('flow_log')->getrows("  `mid` ='$id' and `table`='$table'",'`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`', '`id` desc');

        // $rows = $this->db->getrows('[Q]flow_log', $where, '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`', '`id` desc');
        $uids = '';
        $dts = c('date');
        // var_dump($rows);die();
        // $fo = m('file');
        foreach ($rows as $k => $rs) {
            $uids .= ',' . $rs['checkid'] . '';
            $col = $rs['color'];
            if (isempt($col)) $col = 'green';
            if (contain($rs['statusname'], '不')) $col = 'red';
            $rows[$k]['color'] = $col;
            $rows[$k]['optdt'] = $dts->stringdt($rs['optdt'], 'G(周w) H:i:s');
        }
        if ($uids != '') {
            $rows = m('admin')->getadmininfor($rows, substr($uids, 1), 'checkid');
        }
        return $rows;
    }
}