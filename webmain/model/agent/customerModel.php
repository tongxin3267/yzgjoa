<?php
class agent_customerClassModel extends agentModel
{
	public function initModel()
	{
		$this->statearr		 = c('array')->strtoarray('待量单|#888888,无效单|green,已退单|green,重单|green,跟进单|green,意向单|green,失败单|green,已签单|green,待定单|green');
		$this->brandarr		 = c('array')->strtoarray('元贞国际|#888888,贞筑豪宅|#888888,梦依达|#888888,域嘉|#888888,元贞局装|#888888');

	}
	
	
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
		$add=array('无','#888888');
		foreach($rowd as $k=>$rs){

			if(!isempt($rs['status'])){
				if ($rs['status']=='127') {
					$zt=$add;
				}else{
					$zt = $this->statearr[$rs['status']];
				}
				$rows[$k]['statustext']=$zt[0];
				$rows[$k]['statuscolor']=$zt[1];
			}
			if(!isempt($rs['rzstatus'])){
				if ($rs['rzstatus']=='127') {
					$rzzt=$add;
				}else{
					$rzzt = $this->statearr[$rs['rzstatus']];
				}
				$rows[$k]['rzstatustext']=$rzzt[0];
				$rows[$k]['rzstatuscolor']=$rzzt[1];
			}

			if(!isempt($rs['yzbrand'])){
				$lxa 	= explode(',', $rs['yzbrand']);
				$yzbrand	= "";
				foreach ($lxa as $key => $value) {
					# code...
					$br = $this->brandarr[$value];
					$yzbrand	.= ','.$br[0];
				}
			}
			if($yzbrand!=''){$yzbrand= substr($yzbrand, 1);$rows[$k]['yzbrand']	=$yzbrand;}
			

		}
		return $rows;
	}
	//20180228   简化为楼上
	protected function agentrows_backup($rows, $rowd, $uid)
	{
		foreach($rowd as $k=>$rs){
		//$this->statearr		 = c('array')->strtoarray('退单|#888888,签单中|green,已签单|green,量房|green,装修完成|green');
		//$this->statearr		 = c('array')->strtoarray('待量单|#888888,无效单|green,已退单|green,重单|green,跟进单|green,意向单|green,失败单|green,已签单|green,待定单|green');
			if($rs['status']==0){
				$rows[$k]['statustext']='待量单';
				$rows[$k]['statuscolor']='#888888';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==1){
				$rows[$k]['statustext']='无效单';
				$rows[$k]['statuscolor']='green';
				$rows[$k]['ishui']		=1;
			}
			if($rs['status']==2){
				$rows[$k]['statustext']='已退单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==3){
				$rows[$k]['statustext']='重单';
				$rows[$k]['statuscolor']='#666666';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==4){
				$rows[$k]['statustext']='跟进单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==5){
				$rows[$k]['statustext']='意向单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==6){
				$rows[$k]['statustext']='失败单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==7){
				$rows[$k]['statustext']='已签单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
			if($rs['status']==8){
				$rows[$k]['statustext']='待定单';
				$rows[$k]['statuscolor']='green';
				//$rows[$k]['ishui']		=1;
			}
		}
		return $rows;
	}
}