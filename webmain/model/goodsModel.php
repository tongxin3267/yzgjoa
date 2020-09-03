<?php
class goodsClassModel extends Model
{
	public function setstock($id='', $lsx='1')
	{
		$where = '';
		if($id!='')$where=' and `aid` in('.$id.')';
		$sql = 'SELECT sum(count)stock,aid FROM `[Q]goodss` where `status` in('.$lsx.') '.$where.' GROUP BY aid';
		$rows= $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$this->update(array(
				'stock' => $rs['stock']
			), $rs['aid']);
		}
	}
	
	public function getgoodstype()
	{
		$dbs 	= m('option');
		$rowss  = $dbs->getdata('goodstype');
		$rows	= array();
		foreach($rowss as $k=>$rs){
			$rows[] = array(
				'name' => $rs['name'],
				'value' => $rs['id'],
			);
			$rowsa = $dbs->getdata($rs['id']);
			if($rowsa)foreach($rowsa as $k1=>$rs1){
				$rows[] = array(
					'name' => '	&nbsp;	&nbsp; ├'.$rs1['name'],
					'value' => $rs1['id'],
				);
				//第三级 20180701 happy_add
				$rowsb = $dbs->getdata($rs1['id']);
				if($rowsb)foreach($rowsb as $k2=>$rs2){
					$rows[] = array(
						'name' => '	&nbsp;	&nbsp; &nbsp;	&nbsp;├'.$rs2['name'],
						'value' => $rs2['id'],
					);

				}

			}
		}
		return $rows;
	}
	
	public function getgoodsdata()
	{
		$rowss  = m('goods')->getall('1=1','id,name');
		$rows	= array();
		foreach($rowss as $k=>$rs){
			$rows[] = array(
				'name' => $rs['name'],
				'value' => $rs['id'],
			);
		}
		return $rows;
	}
}