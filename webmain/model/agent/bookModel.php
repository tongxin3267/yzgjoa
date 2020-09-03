<?php
class agent_bookClassModel extends agentModel
{
	
	public function gettotal()
	{
		$stotal	= $this->gettotalss($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function gettotalss($uid)
	{
		$to = 0;
		return $to;
	}
	
	protected function agentrows($rows, $rowd, $uid)
	{
		foreach($rowd as $k=>$rs){
		//happy_add
			if($rs['status']==0){
				$rows[$k]['statustext']='处理中';
				$rows[$k]['statuscolor']='#888888';
				$rows[$k]['ishui']		=1;
			}
			if($rs['status']==1){
				$rows[$k]['statustext']='已完成';
				$rows[$k]['statuscolor']='green';
				$rows[$k]['ishui']		=1;
			}
		}
		return $rows;
	}
}