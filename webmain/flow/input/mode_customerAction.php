<?php
class mode_customerClassAction extends inputAction{
	

	protected function savebefore($table, $arr, $id, $addbo){
		$name 	= $arr['name'];
		$mobile = $arr['mobile'];
		$yzbrand = $arr['yzbrand'];
		$rzmendian = $arr['rzmendian'];
		$rzdesigner = $arr['rzdesigner'];
		$mendian = $arr['mendian'];
		$gddesigner = $arr['gddesigner'];
		$marker = $arr['marker'];
		$rzmarker = $arr['rzmarker'];
		$db 	= m($table);
		//if($db->rows("`name`='$name' and `id`<>$id")>0)return '名称['.$name.']已存在';
		//var_dump($arr);
		//如果设计师不为空，提示录入品牌        如果门店不为空，提示录入品牌
		if(!isempt($rzdesigner)||!isempt($rzmendian)||!isempt($rzmarker)) {
			if (strpos($yzbrand,'2')===false) {
			 	return $msg='录入软装设计师或者软装门店或者软装顾问时，品牌梦依达必须录入';
			 } 
		}

		//如果品牌不为空，提示录入门店	
		if(strpos($yzbrand,'2')!==false){
			if (isempt($rzmendian)) {
				return $msg='勾选梦依达时，请同时选择软装门店';
			}elseif (isempt($rzmarker)) {
				return $msg='勾选梦依达时，请同时选择软装顾问';
			}
		} 

		//录入硬装设计师或者硬装门店的同时，请勾选元贞国际或贞筑豪宅2选1
		if(!isempt($mendian)||!isempt($gddesigner)||!isempt($marker)) {
			if (strpos($yzbrand,'1')===false&&strpos($yzbrand,'0')===false&&strpos($yzbrand,'3')===false&&strpos($yzbrand,'4')===false) {
				return $msg='录入硬装设计师或者硬装门店或者硬装顾问的同时，请勾选元贞国际或贞筑豪宅或域嘉或元贞局装4选1;';
			}
		}

		if(strpos($yzbrand,'1')!==false||strpos($yzbrand,'0')!==false||strpos($yzbrand,'3')!==false||strpos($yzbrand,'4')!==false) {
			if (isempt($mendian)) {
				return $msg='勾选元贞国际或贞筑豪宅或域嘉或元贞局装时，请同时选择硬装门店';
			}elseif (isempt($marker)) {
				return $msg='勾选元贞国际或贞筑豪宅或域嘉或元贞局装时，请同时选择硬装顾问';
			}
		}

		if(!isempt($mobile) && $db->rows("`mobile`='$mobile' and `id`<>$id")>0)return '手机号['.$mobile.']已存在';

	}
	
	
	protected function saveafter($table, $arr, $id, $addbo){
		$name = $arr['name'];
		m('custfina')->update("`custname`='$name'", "`custid`='$id'");
		m('custract')->update("`custname`='$name'", "`custid`='$id'");
		m('custsale')->update("`custname`='$name'", "`custid`='$id'");
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