<?php
/**
	Joy办公系统团队应用
*/
class agent_xinhuClassModel extends agentModel
{
	protected function agentdata($uid, $lx)
	{
		$rows[] = array(
			'title' => '欢迎使用Joy办公系统',
			'cont'	=> '官网：<a href="http://xh85529.com/" target="_blank">http://xh855529.com/</a><br>版本：'.VERSION.'',
			'statuscolor' => 'green',
			'statustext'  => '官网'
		);
		$rows[] = array(
			'title' => 'Joy办公系统开源协议',
			'cont'	=> '我们是开源PHP系统，可以自己企业单位内部使用，禁止商业销售，欢迎研究学习使用，好的设计你可以借鉴，不好的你可以吐槽，让我们改善。',
			'statuscolor' => 'green',
			'statustext'  => '官网'
		);
		$arr['rows'] 	= $rows;
		return $arr;
	}
}