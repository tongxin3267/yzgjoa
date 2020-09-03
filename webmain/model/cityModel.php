<?php
//城市
class cityClassModel extends Model
{
	//获取城市路径
	public function getpath($id)
	{
		$this->pathss = array();
		$this->getpaths($id);
		return $this->pathss;
	}
	private function getpaths($id)
	{
		$rs = $this->getone($id);
		if($rs && $rs['pid']!=$id){
			$this->getpaths($rs['pid']);
			$this->pathss[] = $rs;
		}
	}
	
	
	/**
	*	导入数据
	*/
	public function daorudata()
	{
		$barr = c('xinhuapi')->getdata('base','city');
		if(!$barr['success'])return returnerror($barr['msg']);
		$data = $barr['data'];
		$shul = 0;
		foreach($data as $k=>$rs){
			$id = $rs['id'];
			if($this->rows($id)==0){
				$shul++;
				$this->insert($rs);
			}
		}			
		return returnsuccess('成功导入'.$shul.'条数据');
	}
	
	/**
	*	籍贯/城市数据
	*/
	public function citydata()
	{
		// $rows	= $this->db->getall('SELECT a.name,b.name as name1 FROM `[Q]city` a left join `[Q]city` b on a.`pid`=b.`id` where a.`type` in(3)');


		$sql="SELECT a.name,b.name AS name1,b1.name AS name2,b2.name AS name3".
		" FROM xinhu_city a".
		" LEFT JOIN xinhu_city b ON b.pid=a.id  ".
		" LEfT JOIN xinhu_city b1 ON b1.pid=b.id  ".
		" LEFT JOIN xinhu_city b2 ON b2.pid=b1.id  ".
		" where a.`type` in(1)";
		$rows	= $this->db->getall($sql);

 
		$barr 	= array();
		foreach($rows as $k=>$rs){
			$name = $rs['name'];
			if($name!=$rs['name1'])$name=$name.$rs['name1'].$rs['name2'].$rs['name3'];
			$barr[] = array(
				'name' => $name,
				'cityname' => $rs['name1'],
				'shengname' => $rs['name'],
			);
		}
		return $barr;
	}
	
	/**
	*	省份数据
	*/
	public function shengdata()
	{
		$rows	= $this->db->getall('SELECT `name` FROM `[Q]city` where `pid`=1 order by `sort`');
		$barr 	= array();
		foreach($rows as $k=>$rs){
			$name = $rs['name'];
			$barr[] = array(
				'name' => $name
			);
		}
		return $barr;
	}
	
	/**
	*	全部城市数据，3级
	*/
	public function alldata()
	{
		$chche = c('cache');
		$cdata = $chche->get('cityalldata');
		if(!$cdata){
			$this->drowsa = $this->getall('1=1','*','`sort`');
			$this->datass = array();
			$this->alldatas($this->drowsa, '1', 0,'');
			
			$chche->set('cityalldata', $this->datass);
			return $this->datass;
		}else{
			return $cdata;
		}
	}
	private function alldatas($rows, $pid, $xu, $sub, $sheng='', $city='')
	{
		$keya = array('shengname','cityname','xianname');
		$zxij = 0;
		foreach($rows as $k=>$rs){
			if($rs['pid']==$pid){
				$zxij++;
				$sar	= array(
					'name' 	=> $sub.$rs['name'],
					'value' => $rs['name'],
					'padding'=> 30*$xu,
					'shengname' => $sheng,
					'cityname' => $city,
					'xianname' => '',
				);
				$keysas 	  = $keya[$xu];
				$sar[$keysas] = $rs['name'];
				$this->datass[] = $sar;
				
				unset($this->drowsa[$k]);
				if($xu==0)$sheng = $rs['name'];
				if($xu==1)$city  = $rs['name'];
				$xiji 	= $this->alldatas($this->drowsa, $rs['id'], $xu+1, $sub.$rs['name'], $sheng, $city);
			}
		}
		return $zxij;
	}
}