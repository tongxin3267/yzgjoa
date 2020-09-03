<?php
/**
*	此文件是流程模块【rzgongdi.软装工地】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_rzgongdi|input','flow')调用到对应方法
*/ 
class mode_jzrgfeeClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){

		// $bookid = $arr['bookid'];
		// $rzgongdiid = $arr['rzgongdiid'];
		$jzgongdiid = $arr['jzgongdiid'];
		// if(!isempt($bookid) && !isempt($rzgongdiid)) return $msg='不要太贪心，一次只能选择一个工地哟';
		if($jzgongdiid>0){}else{return $msg='请至少选择一个工地哟';}
		

		/*if(!isempt($jzgongdiid)) {
			$row = m('yzjuzhuang')->getone("`id`='$jzgongdiid'",'*');
			$arr=$row;
			//var_dump($arr);die();
		}*/
		
	}
		
	protected function saveafter($table, $arr, $id, $addbo){

		// var_dump($arr);die();
		// $bookid = $arr['bookid'];
		$jzgongdiid = $arr['jzgongdiid'];
		// if(!isempt($bookid)) {
		// 	m('book')->update("`rgfeeid`='$id'", "`id`='$bookid'");
		// }
		if(!isempt($jzgongdiid)) {
			m('yzjuzhuang')->update("`rgfeeid`='$id'", "`id`='$jzgongdiid'");
		}
	}
	public function getbookdata()
	{		
		//$rows = m('book')->getall("`ispublic`=1 and `state`=1",'carnum as name,id as value');
		$rows = m('book')->getall("`rgfeeid`=0  and `status`=0",'title as name,id as value');
		return $rows;
	}
	public function getrzgongdidata()
	{		
		//$rows = m('book')->getall("`ispublic`=1 and `state`=1",'carnum as name,id as value');
		$rows = m('rzgongdi')->getall("`rgfeeid`=0 and `status`=0",'title as name,id as value');
		return $rows;
	}	
	public function getjzgongdidata()
	{		
		$rows = m('yzjuzhuang')->getall("`rgfeeid`=0 and `status`=0",'title as name,id as value');
		return $rows;
	}
}	
			