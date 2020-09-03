<?php

class customerStatusClassAction extends apiAction
{
    public function changeCustomerStatusAction()
    {
        $customer_id = $this->post('mid');
        $status = $this->post('status');
        $supplierId = $this->post('supplierId');
        $supplierId = !empty($supplierId) ? $supplierId : $this->adminid;

        $arr = $this->db->getall("select * from `xinhu_supplier_customer` where `supplier_id`={$supplierId} and `customer_id`={$customer_id}");
        if (0 < count($arr)) {
            $result = $this->db->query("update `xinhu_supplier_customer` set `status`={$status} where `supplier_id`={$supplierId} and `customer_id`={$customer_id}");
        } else {
            $result = $this->db->query("insert into `xinhu_supplier_customer` values (null,'{$customer_id}','{$supplierId}','{$status}')");
        }

        $statusarr= explode(',','待量单,无效单,已退单,重单,跟进单,意向单,失败单,已签单,待定单');
        $this->addlog(array(
            'mid' => $customer_id,
            'explain' => "客户状态变更为:{$statusarr[$status]}",
            'name' => "状态变更",
            'statusname' => '变更',
            'status' => 1,
            'color' => 'tree'
        ));
        $this->showreturn($result);
    }

    public function addlog($arr)
    {
        $addarr = array(
            'table' => 'customer',
            'checkname' => $this->adminname,
            'checkid' => $this->adminid,
            'optdt' => $this->rock->now,
            'courseid' => '0',
            'status' => '1',
            'ip' => $this->rock->ip,
            'web' => $this->rock->web,
            'modeid' => 7
        );
        foreach ($arr as $k => $v) $addarr[$k] = $v;
        m('flow_log')->insert($addarr);
    }

    public function getCustomerStatusAction()
    {
        $customer_id = $this->post('mid');
        $supplierId = $this->post('supplierId');
        $supplierId = !empty($supplierId) ? $supplierId : $this->adminid;
        $status = $this->post('status');
        $where['supplier_id'] = $this->adminid;
        $result = $this->db->getall("select * from `xinhu_supplier_customer` where `supplier_id`={$supplierId} and `customer_id`={$customer_id}");
        $data = isset($result[0]) ? $result[0] : array();
        $this->showreturn($data);
    }

    public function getCustomerAllStatusAction()
    {
        $customer_id = $this->post('mid');
        $result = $this->db->getall("select * from `xinhu_customer` where `id`={$customer_id}");
        $data = isset($result[0]) ? $result[0] : array();
        $this->showreturn($data);
    }
    public function changeCustStatusAction()
    {
        $customer_id = $this->post('mid');
        $status = $this->post('status');
        $rzstatus = $this->post('rzstatus');
        $old_result= $this->db->getone('[Q]customer',$customer_id,'*');
        $result = $this->db->query("update `xinhu_customer` set `status`={$status},`rzstatus`={$rzstatus} where  `id`={$customer_id}");
        
        $statusarr= explode(',','待量单,无效单,已退单,重单,跟进单,意向单,失败单,已签单,待定单');
        $msg="";
        if ($old_result['status']!=$status) {
            $msg.="客户硬装状态变更为:{$statusarr[$status]};";
        }
        if ($old_result['rzstatus']!=$rzstatus) {
            $msg.="客户软装状态变更为:{$statusarr[$rzstatus]}";
        }
        $this->addlog(array(
            'mid' => $customer_id,
            'explain' => $msg,
            'name' => "状态变更",
            'statusname' => '变更',
            'status' => 1,
            'color' => 'tree'
        ));
        $this->showreturn($result);
    }

}