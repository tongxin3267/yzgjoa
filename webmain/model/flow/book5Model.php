<?php
class flow_bookClassModel extends flowModel
{	// happy_add 读取工长来审批
	
		//happy_add 读取工长来审批
	protected function flowcheckname($num)
	{

		if($num=='gzshenpi'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['author'].'"');
			return array($admin['id'], $admin['name']);
		}
		if($num=='mdmanager'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['mdarea'].'"');
			return array($admin['id'], $admin['name']);
		}
		if($num=='khcomment'){
			$admin = m('userinfo')->getone('`name`="'.$this->rs['chuban'].'"');
			return array($admin['id'], $admin['name']);
		}
	}
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