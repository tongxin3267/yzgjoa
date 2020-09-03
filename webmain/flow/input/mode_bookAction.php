<?php
class mode_bookClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
		$rers=$this->db->getone('[Q]flow_bill',"`mid`='".$id."' and `table`='$table'");
		if ($rers['status']==1) {
			$arr['status']		= $rers['status'];
		}
		return array('rows'=>$arr);
	}
		
	protected function saveafter($table, $arr, $id, $addbo){
		$row=$arr;
		unset($row['optid'],$row['uid'],$row['optname'],$row['courseid'],$row['coursename']);
		//var_dump($row);
		m('rgfee')->update($row, "`bookid`='$id'");
	}
	public function citydata()
	{
		$rows=array(array('value' => 1,'name' => '浦东',),
			array('value' => 2,'name' => '奉贤',),
			array('value' => 3,'name' => '金山',),
			array('value' => 4,'name' => '闵行',),
			array('value' => 5,'name' => '宝山',),
			array('value' => 6,'name' => '徐汇',),
			array('value' => 7,'name' => '普陀',),
			array('value' => 8,'name' => '杨浦',),
			array('value' => 9,'name' => '长宁',),
			array('value' => 10,'name' => '松江',),
			array('value' => 11,'name' => '嘉定',),
			array('value' => 12,'name' => '黄浦',),
			array('value' => 13,'name' => '静安',),
			array('value' => 14,'name' => '闸北',),
			array('value' => 15,'name' => '虹口',),
			array('value' => 16,'name' => '青浦',),
			array('value' => 17,'name' => '崇明',),
			array('value' => 18,'name' => '上海周边',),
			);
		return $rows;
	}
}	
			