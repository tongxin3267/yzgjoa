<?php
class flow_bookClassModel extends flowModel
{
	public function flowrsreplace($rs,$isv=0)
	{
		if(isset($rs['typeid']))$rs['typeid'] 	= $this->db->getmou('[Q]option','name',"`id`='".$rs['typeid']."'");
		return $rs;
	}

	protected function flowbillwhere($uid, $lx)
	{
		//happy_add 新增查询条件pc
		$where  = '';
		$typeid = $this->rock->post('typeid','0');
		$key 	= $this->rock->post('key');
		$keypp 	= $this->rock->post('keypp');
		if($typeid!='0'){
			$where .= ' and `typeid`='.$typeid.'';
		}
		if($key != '')$where.=" and (`title` like '%$key%' or `author` like '%$key%' or `coursename` like '%$key%')";
		if($keypp != '')$where.='  and `courseid`='.$keypp.'';
		return array(
			'where' => $where,
			'order' => 'optdt desc'
		);
	}
}