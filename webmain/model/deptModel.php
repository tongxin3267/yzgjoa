<?php
class deptClassModel extends Model
{
	public function getdata($where="1=1")
	{
		$rows = $this->getall($where,'id,name,pid,sort','sort');
		$dbs  = m('admin');
		foreach($rows as $k=>$rs){
			$stotal = $dbs->rows("`status`=1 and instr(`deptpath`,'[".$rs['id']."]')>0");
			$rows[$k]['stotal'] = $stotal;
		}
		return $rows;
	}
	
}