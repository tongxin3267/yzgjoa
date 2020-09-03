<?php
class clpaifaClassModel extends Model
{
	private $_getjoinstr = array();
	
	public function initModel()
	{
		$this->settable('clpaifa');
	}
	
	/**
	*	关键词搜索的
	*/
	public function getkeywhere($key, $qz='', $ots='')
	{
		$where = " and ($qz`title` like '%$key%' or $qz`chuban` like '%$key%' or $qz`author` like '%$key%' or $qz`clgysname` like '%$key%'  or $qz`weizhi` like '%$key%' $ots)";
		return $where;
	}
	
	/**
	*	更新信息
	*/
	public function updateinfo($where='')
	{
		$rows	= $this->db->getall("select * from `[Q]clpaifa`  where id>0 $where order by `fid`");
		$total	= $this->db->count;
		$cl		= 0;
		return array($total, $cl);
	}

}