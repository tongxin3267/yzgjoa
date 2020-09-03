<?php
class clpaifaClassAction extends Action
{

	public function editAction()
	{
		$id = (int)$this->get('id',0);
		$data = m('clpaifa')->getone($id);
		$author=$data['author'];
		$jianli = m('userinfo')->getone("`name`='$author'",'mobile');
		
        $logarr = $this->getlog($id);
		$this->assign('logarr', $logarr);
		$this->assign('mobile', $jianli['mobile']);
		$this->assign('gongdiinfo', $data);
		$this->assign('adminid',$this->adminid);
		//$this->returnjson($arr);
	}
	
	public function historyV1Action()
	{
		//v1----展示工地所有配送记录
		$id = (int)$this->get('id',0);
		$rows =  m('clpaifa')->getall("`fid`='$id' and ( clgysid='$this->adminid' or '$this->adminid'=1	) ",'*','cid');
	
		//var_dump($rows);
		$this->assign('gongdiinfo', $rows);
		$this->assign('adminid',$this->adminid);

        $logarr = $this->getlog($id);
		$this->assign('logarr', $logarr);
		if($this->rock->ismobile()){
			$spagepath 	= P.'/main/clpaifa/view.html';
		}
		$this->smartydata['spagepath']		= $spagepath;
		//$this->returnjson($arr);
	}

	public function historyAction()
	{
		//v2----展示当前选择的配送记录
		$id = (int)$this->get('id',0);
		$rows =  m('clpaifa')->getone($id);
		$author=$rows['author'];
		$jianli = m('userinfo')->getone("`name`='$author'",'mobile');
		
		$this->assign('mobile', $jianli['mobile']);
	
        $logarr = $this->getlog($id);
		$this->assign('logarr', $logarr);
		//var_dump($rows);
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

		$status=$type==1?3:1;
		$data = m('clpaifa')->update(array(
				'status' 	=> $status,
				'dealdt' 	=> $dealdt,
				'xgwj' 	=> $xgwj,
				'explain' 	=> $explain,
				'dealid' 	=> $this->adminid,
			), $id);
		if($data){
			//happy_add 短信通知材料申请人&工程监理
			$clpaifainfo = m('clpaifa')->getone($id);
			$cont=$clpaifainfo['author'].'监理的'.$clpaifainfo['title'].'工地材料已经由'.$clpaifainfo['clgysname'].'配送成功，请注意查收！';
			if(!empty($clpaifainfo)){
				m('sms')->postsms('材料配送通知申请人', $cont, $clpaifainfo['optid']);
			}
			if(!empty($clpaifainfo)){
				m('sms')->postsms2($cont,$clpaifainfo['author']);
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
		$type = $this->get('type');
		$dealdt 		= date('Y-m-d H:i:s');
		$remark = $this->get('remark');

		//happy_add 短信通知材料申请人&工程监理
		$anzonly=getconfig('anzonly');
		$clpaifainfo = m('clpaifa')->getone($id);
		$status=$type==1?3:1;

		$progress 		= 6; //工地进度：1测量中 2待安装 3安装中 4已安装 5已完成 6已取消
	
		$data = m('clpaifa')->update(array(
				'status' 	=> $status,
				'remark' 	=> $remark,
				'progress' 	=> $progress,
				'dealdt' 	=> $dealdt,
				'dealid' 	=> $this->adminid,
			), $id);
		if($data){
			
			$cont=$clpaifainfo['author'].'监理的'.$clpaifainfo['title'].'工地已经取消配送，请注意！！！';
			if(!empty($clpaifainfo)){
				m('sms')->postsms('安装测量通知申请人', $cont, $clpaifainfo['clgysid']);
			}
			if(!empty($clpaifainfo)){
				m('sms')->postsms2($cont,$clpaifainfo['author']);
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
		
		$data = m('clpaifa')->update(array(
				'goods' 	=> $goods,
				'alltotal' 	=> $alltotal,
				'totalprice' 	=> $totalprice,
				'updatedt' 	=> $updatedt,
				'updateid' 	=> $this->adminid,
			), $id);
		if($data){
			//happy_add 短信通知材料供应商&工程监理
			$clpaifainfo = m('clpaifa')->getone($id);
			$cont=$clpaifainfo['author'].'监理的'.$clpaifainfo['title'].'工地材料已变更，请注意查看，并及时调整配送安排！';
			if(!empty($clpaifainfo)){
				m('sms')->postsms('材料配送通知材料供应商', $cont, $clpaifainfo['clgysid']);
			}
			if(!empty($clpaifainfo)){
				m('sms')->postsms2($cont,$clpaifainfo['author']);
			}
			echo '处理成功';
		}else{
			echo '处理失败';
			# code...
		}
		//$this->returnjson($arr);
	}
/*	public function beforeshow($table)
	{
		//var_dump($this->adminid); 
		$fields = 'a.*,b.mobile';
		$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	or '.$this->adminid.'=15 or '.$this->adminid.'=12 ) and (status in (0,2))';
		$key 	= $this->post('key');
		if($key!=''){
			$s.= m($table)->getkeywhere($key);
		}

		return array(
			'table' => '`[Q]clpaifa` a left join `[Q]userinfo` b on a.author=b.name',
			'fields'=> $fields,
			'where'	=> $s,
			'order' =>'dealdt desc ,createdt desc',
		);
	}
	public function groupshow($table)
	{
		//var_dump($this->adminid);
		$fields = 'a.*,b.mobile';
		//$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	) group by fid';
		$s 		= 'and ( clgysid='.$this->adminid.' or '.$this->adminid.'=1	or '.$this->adminid.'=15 or '.$this->adminid.'=12 ) and (status in (1,3))';
		$key 	= $this->post('key');
		$timeRecord = $this->rock->post('timeRecord');
		$timeRecord2 = $this->rock->post('timeRecord2');
		if($key!=''){
			$s.= m($table)->getkeywhere($key);
		}

		if(!isempt($timeRecord) && !isempt($timeRecord2)){
			$s.=" and (`dealdt` between '$timeRecord' and '$timeRecord2')";
		}else{			
            $year = date('Y');
            $m = date('m');
            $s .= " and (`createdt` like '$year-$m%')";
		}
		
		$array= array(
			'table' => '`[Q]clpaifa` a left join `[Q]userinfo` b on a.author=b.name',
			'fields'=> $fields,
			'where'	=> $s,
			'order' =>'dealdt desc ,createdt desc',
		);
		//var_dump($array);die;
		return $array;
		
	}*/
	
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
		return m('clpaifa')->updateinfo($whe);
	}
	
    public function getlog($id,$table='clpaifa')
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