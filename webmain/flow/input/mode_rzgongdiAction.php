<?php
/**
*	此文件是流程模块【rzgongdi.软装工地】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_rzgongdi|input','flow')调用到对应方法
*/ 
class mode_rzgongdiClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		
	}
		
	protected function saveafter($table, $arr, $id, $addbo){
		//人工费申请相关信息同步更新
		$row=$arr;
		unset($row['optid'],$row['uid'],$row['optname'],$row['courseid'],$row['coursename']);
		m('rgfee')->update($row, "`rzgongdiid`='$id'");
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
			