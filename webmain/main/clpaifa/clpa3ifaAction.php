<?php
class clpaifaClassAction extends Action
{
	
	/**
	*	历史记录
	*/
	public function historyAction()
	{
		$params['fid'] 	= $this->get('fid');
		$params['cid'] 		= $this->get('cid');

		$this->title 			= '材料配送记录';
		switch ($params['cid']) {
			case '50':
				$this->title 			= '基础材料配送记录';
				$typeid='287';
				break;
			case '52':
				$this->title 			= '水电材料配送记录';
				$pid='288';
				break;
			case '56':

				$this->title 			= '泥木材料配送记录';
				$typeid='289';
				break;
			case '62':
				$this->title 			= '油漆材料配送记录';
				$typeid='290';
				break;			
			default:
				$pid='288';
				break;
		}
		if(!$params['cid']||!$params['fid'])exit('sorry!参数错误，请退出重试!!!');
		//获取工地详情
		$clpaifa = m('clpaifa')->getall("`fid`=".$params['fid']." and `cid`=".$params['cid'],'*');
		$flowinfo = m('flowbill')->getone("`id`=".$params['fid'],'*');
		$gongdiinfo = m($flowinfo['table'])->getone("`id`=".$flowinfo['mid'],'*');
		
		//var_dump($gongdiinfo);die();
		$this->assign('isheader', 1);
		$this->assign('params', $params);
		$this->assign('clpaifa', $clpaifa);
		$this->assign('gongdiinfo', $gongdiinfo);
	}
	
}