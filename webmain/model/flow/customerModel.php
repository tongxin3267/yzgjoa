<?php

class flow_customerClassModel extends flowModel
{
    public function initModel()
    {
        //0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单
        //$this->statearr		 = c('array')->strtoarray('停用|#888888,启用|green');
        //$this->statearr		 = c('array')->strtoarray('退单|#888888,签单中|green,已签单|green,量房|green,装修完成|green');
        $this->statearr = c('array')->strtoarray('待量单|#888888,无效单|green,已退单|green,重单|green,跟进单|green,意向单|green,失败单|green,已签单|green,待定单|green');
        $this->brandarr = c('array')->strtoarray('元贞国际|#888888,贞筑豪宅|#888888,梦依达|#888888,域嘉|#888888,元贞局装|#888888');
        //11.OA［形象建设部］［预算部］开工信息表业主号码隐藏或乱码。
        //12.CRM信息表渠道及预算对［设计部］［市场部］［门店］不可见。
        $this->admininfo = $this->db->getone('[Q]admin', $this->adminid, 'id,name,deptid,deptname,ranking,superid,superpath,deptpath,superman');
        $this->deptid = getconfig('crmdeptid');
        $this->rzdetpid = getconfig('rzdetpid');
        $this->clsdeptid = getconfig('clsdeptid');
        $this->clskefuid = getconfig('clskefuid');
        $this->kefudeptid = getconfig('kefudeptid');
        $this->isin = in_array($this->admininfo['deptid'], $this->deptid);
        $this->isinrz = in_array($this->admininfo['deptid'], $this->rzdetpid);
        $this->isincls = in_array($this->admininfo['deptid'], $this->clsdeptid);
        $this->isinkefu = in_array($this->admininfo['deptid'], $this->kefudeptid);

        // var_dump($this->isincls);


    }


    public function flowrsreplace($rs)
    {
        $add = array('无', '#888888');
        // var_dump($rs);
        //摘要替换后显示 happy_add
        $rs['designerstatus'] = '<span style="font-size: 10px; white-space: nowrap;overflow: hidden;">';
        if (!isempt($rs['status'])) {
            if ($rs['status'] == '127') {
                $zt = $add;
            } else {
                $zt = $this->statearr[$rs['status']];
            }
            $rs['status'] = '<font color="' . $zt[1] . '">' . $zt[0] . '</font>';
        }
        if (isset($rs['gjstatus']) && !isempt($rs['gjstatus'])) {
            if ($rs['gjstatus'] == '127') {
                $zt = $add;
            } else {
                $zt = $this->statearr[$rs['gjstatus']];
            }
            $rs['gjstatus'] = '<font color="' . $zt[1] . '">' . $zt[0] . '</font>';
        }
        if (!isempt($rs['rzstatus'])) {
            if ($rs['rzstatus'] == '127') {
                $rzzt = $add;
            } else {
                $rzzt = $this->statearr[$rs['rzstatus']];
            }
            $rs['rzstatus'] = '<font color="' . $rzzt[1] . '">' . $rzzt[0] . '</font>';
        }

        //供应商客服只可看硬装状态，供应商取消查看硬装及软装跟进状态
        if (!$this->isincls) {
            if (!isempt($rs['gddesigner'])) {
                $rs['designerstatus'] .= '硬装设计师：' . $rs['gddesigner'] . ' &nbsp;|&nbsp;' . $rs['status'] . ' &nbsp; &nbsp; ';
            }else{                
                $rs['designerstatus'] .= '硬装设计师：未安排 &nbsp;|&nbsp;' . $rs['status'] . ' &nbsp; &nbsp; ';
            }
        }
        if (($this->clskefuid !=$this->adminid) && !$this->isincls) {           
            if (!isempt($rs['rzdesigner'])) {
                $rs['designerstatus'] .= '软装设计师：' . $rs['rzdesigner'] . ' &nbsp;|&nbsp;' . $rs['rzstatus'] . '</span>';
            }else{                
                $rs['designerstatus'] .= '软装设计师：未安排 &nbsp;|&nbsp;' . $rs['rzstatus'] . '</span>';
            }
        }

        /*if ($this->isincls) {//没有此字段，，，，，，如果要加的话，需要在获取的地方，关联此表取数据
            // var_dump($rs['gjstatus']);//die;

            if (!isempt($rs['gjstatus'])) {
                $rs['designerstatus'] .= '跟进状态：' . $rs['gjstatus'] . ' &nbsp; ';
            }
        }*/
        //显示状态已分享
        if (!isempt($rs['shateid']) && ( $this->adminid== 1 || $this->isinkefu)) {
            $rs['isshare'] = '  <font  style="font-size: 10px;color:red"> 已分享</font>';
        }
        $rs['designerstatus'] .= "</span> ";

        if (!isempt($rs['yzbrand'])) {
            $lxa = explode(',', $rs['yzbrand']);
            $yzbrand = "";
            foreach ($lxa as $key => $value) {
                # code...
                $br = $this->brandarr[$value];
                $yzbrand .= ',' . $br[0];
            }
        }
        if ($yzbrand != '') {
            $yzbrand = substr($yzbrand, 1);
            $rs['yzbrand'] = $yzbrand;
        }

        if ($rs['htshu'] == 0) $rs['htshu'] = '';
        if ($rs['moneyz'] == 0) $rs['moneyz'] = '';
        if ($rs['moneyd'] == 0) $rs['moneyd'] = '';
        // if (!isempt($rs['record'])) $rs['record'] = '<a href="#" class="show_record" onmouseover="showrecord(' . $rs['record'] . ')"  >' . $rs['name'] . '</a>';
        if (!isempt($rs['tel'])) $rs['tel'] = '<a href="tel:' . $rs['tel'] . '" class="hhhh">' . $rs['tel'] . '</a>';
        //字段隐藏处理
        if ($this->isin) {
            $rs['laiyuan'] = '****';
            $rs['budgettype'] = '****';
        }
        //字段隐藏处理
        if ($this->isincls) {
            $rs['marker'] = $this->adminname;
            $rs['mendian'] = '';
            $rs['rzmendian'] = '';
        }

        if ( $this->adminid == $this->clskefuid) {
            $rs['caozuoren'] .= '<div class=""> <span>共享给：' . $rs['shate'] . ' </span> </div> <div class="host-time">'.$rs['optdt'] .' </div> ';
        }else{
            /*if (!isempt($rs['marker'])||!isempt($rs['mendian'])) {
                $rs['caozuoren'] .= '硬装跟进人：' . $rs['marker'] . ' '.$rs['mendian'] .'';
            }  
            if (!isempt($rs['rzmarker'])||!isempt($rs['rzmendian'])) {
                $rs['caozuoren'] .= '硬装设计师：' . $rs['gddesigner'] . ' &nbsp;| ' . $rs['status'] . ' &nbsp; &nbsp;  ';
            } */  
            $ruanzhuang=$rs['rzmarker'].$rs['rzmendian'];
            $yingzhuang=$rs['marker'].$rs['mendian'];
            $ruanzhuang=$ruanzhuang?$rs['rzmarker'] . ' '.$rs['rzmendian']:'无';
            $yingzhuang=$yingzhuang?$rs['marker'] . ' '.$rs['mendian']:'无';

        }
        $rs['optdt']=date("Y-m-d",strtotime($rs['optdt']));


        //供应商取消查看渠道及软装跟进人
        if (!$this->isincls) {  
            $rs['caozuoren'] .= '<div class=""> <span>硬装跟进人：' . $yingzhuang.' </span>    &nbsp;  | &nbsp;软装跟进人：' .$ruanzhuang.'  &nbsp; &nbsp; </span></div> ';
            $rs['footcon'] = '渠道：'.$rs['laiyuan'] .' &nbsp;   |  &nbsp; 操作人：'.$rs['optname'] .'   &nbsp;  | &nbsp; <span style="color: #aaaaaa;">  '.$rs['optdt'] .'</span>';
        }else{
           # $rs['caozuoren'] .= '<div class=""> <span>硬装跟进人：' . $yingzhuang.' </span>    &nbsp;  </div> ';
            $rs['footcon'] = '硬装跟进人：' . $yingzhuang.' &nbsp;   |  &nbsp; 操作人：'.$rs['optname'] .'   &nbsp;  | &nbsp; <span style="color: #aaaaaa;">  '.$rs['optdt'] .'</span>';
        }



        // var_dump($rs);
        return $rs;
    }


    protected function flowprintrows($rows)
    {
        foreach ($rows as $k => $rs) {
            $zt = $this->statearr[$rs['status']];
            $rows[$k]['status'] = '<font color="' . $zt[1] . '">' . $zt[0] . '</font>';;
        }
        return $rows;
    }

    //是否有查看权限
    protected function flowisreadqx()
    {
        $bo = false;
        $shateid = ',' . $this->rs['shateid'] . ',' . $this->rs['gddesignerid'] . ',' . $this->rs['rzdesignerid'] . ',' . $this->rs['markerid'] . ',' . $this->rs['rzmarkerid'] . ',' . $this->rs['mendianid'] . ',';
        if (contain($shateid, ',' . $this->adminid . ',')) $bo = true;

        //判断是不是客服部的就行了 happy——add======
        if (!$bo) {
            if ($this->urs && contain($this->urs['deptpath'], '[11]')) $bo = true;
        }
        //到时候网上先不加这里吗，反应有问题再加上
        return $bo;
    }

    protected function flowgetfields($lx)
    {
        $arr = array();
        if ($this->uid == $this->adminid) {
            $arr['mobile'] = '手机号';
            $arr['tel'] = '电话';
            $arr['email'] = '邮箱';
            $arr['routeline'] = '交通路线';
        }
        return $arr;
    }


    protected function flowoptmenu($ors, $crs)
    {
        $zt = $ors['statusvalue'];
        $num = $ors['num'];
        if ($num == 'ztqh') {
            $this->update('`status`=' . $zt . '', $this->id);
        }

        if ($num == 'shate') {
            $cname = $crs['cname'];
            $cnameid = $crs['cnameid'];
            $this->update(array(
                'shateid' => $cnameid,
                'shate' => $cname,
                'shatedate' => $this->rock->now,
            ), $this->id);
            $this->push($cnameid, '客户管理', '将一个客户[{name}]共享给你，请注意及时查看');
        }

        //取消共享
        if($num=='unshate'){
            $this->update(array(
                'shateid'   => '',
                'shate'     => '',
            ), $this->id);
        }

        
        //放入公海
        if($num=='ghnoup'){
            $this->update(array(
                'isgh'  => '1',
                'uid'   => 0,
            ), $this->id);
        }
        
    }

    protected function flowbillwhere($uid, $lx)
    {
        $where = '(' . $uid . '=1 or ' . $uid . '=188 or `uid`=' . $uid . ' or ' . $this->rock->dbinstr('shateid', $uid) . ' or ' . $this->rock->dbinstr('gddesignerid', $uid) . ' or ' . $this->rock->dbinstr('rzdesignerid', $uid) . ' or ' . $this->rock->dbinstr('markerid', $uid) . ' or ' . $this->rock->dbinstr('rzmarkerid', $uid) . ' or ' . $this->rock->dbinstr('mendianid', $uid) . ' or ' . $this->rock->dbinstr('rzmendianid', $uid) . ' )';
        //$where 	= '`uid`='.$uid.' or shateid in ('.$uid.') ';
        //$where 	= '`uid`='.$uid.' and `status`=1';
        $key = $this->rock->post('key');
        $lxa = explode('_', $lx);
        $lxs = $lxa[0];
        if (isset($lxa[1])) $lx = $lxa[1];
        if ($lxs == 'my') {
            $where = '(' . $uid . '=1 or ' . $uid . '=188 or `uid`=' . $uid . ' or ' . $this->rock->dbinstr('shateid', $uid) . ' or ' . $this->rock->dbinstr('gddesignerid', $uid) . ' or ' . $this->rock->dbinstr('rzdesignerid', $uid) . ' or ' . $this->rock->dbinstr('markerid', $uid) . ' or ' . $this->rock->dbinstr('rzmarkerid', $uid) . '  or ' . $this->rock->dbinstr('mendianid', $uid) . ' or ' . $this->rock->dbinstr('rzmendianid', $uid) . ' )';
        }
        if ($lxs == 'shatemy') {
            $where = $this->rock->dbinstr('shateid', $uid);
        }
        if ($lxs == 'down') {

            //$receid = m('adming')->gjoins('g'.$uid);
            //var_dump($receid);
            $where = m('adming')->getdeptid($uid, 'gddesignerid');
            //var_dump($where);

            //$where = m('admin')->getdownwheres('gddesignerid', $uid, 0);

        }
        if ($lxs == 'dist') {
            $where = '`fzid`=' . $uid . '';
        }
        // happy_add var as = ['all','td','qdz','yqd','lf','zxwc','stat'];
        //0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单

        if ($lx == 'dld0') {
            $where .= ' and `status`=0 ';
        }
        if ($lx == 'wxd1') {
            $where .= ' and `status`=1 ';
        }
        if ($lx == 'ytd2') {
            $where .= ' and `status`=2 ';
        }
        if ($lx == 'cd3') {
            $where .= ' and `status`=3 ';
        }
        if ($lx == 'gjd4') {
            $where .= ' and `status`=4 ';
        }
        if ($lx == 'yxd5') {
            $where .= ' and `status`=5 ';
        }
        if ($lx == 'sbd6') {
            $where .= ' and `status`=6 ';
        }
        if ($lx == 'yqd7') {
            $where .= ' and `status`=7';
        }
        if ($lx == 'ddd8') {
            $where .= ' and `status`=8 ';
        }
        if ($lx == 'stat') {
            $where .= ' and `isstat`=1';
        }
        if ($lx == 'yfp') {
            $where .= ' and `uid`>0';
        }
        if ($lx == 'wfp') {
            $where .= ' and `uid`=0';
        }

        if ($lx == 'ting') {
            $where .= ' and `status`=0';
        }
        if ($lx == 'myty') {
            $where = '`uid`=' . $uid . ' and `status`=0';
        }

        if ($lx == 'all' || $lx == 'def' || $lx == 'myall') {
            $where = '(' . $uid . '=1  or ' . $uid . '=188 or `uid`=' . $uid . ' or ' . $this->rock->dbinstr('shateid', $uid) . ' or ' . $this->rock->dbinstr('gddesignerid', $uid) . ' or ' . $this->rock->dbinstr('rzdesignerid', $uid) . ' or ' . $this->rock->dbinstr('markerid', $uid) . ' or ' . $this->rock->dbinstr('rzmarkerid', $uid) . '  or ' . $this->rock->dbinstr('mendianid', $uid) . ' or ' . $this->rock->dbinstr('rzmendianid', $uid) . ' )';
        }
        //共享给我
        if ($lx == 'gxgw') {
            $where = $this->rock->dbinstr('shateid', $uid);
        }
        $order='adddt desc,status desc';
        $clskefuid = getconfig('clskefuid');
        if ($clskefuid==$this->adminid) {
            $where =' `shateid` is not null';
            $order='shatedate desc,status desc';
        }
        //我共享
        if ($lx == 'mygx') {
            $where = '`uid`=' . $uid . ' and `shateid` is not null';
        }

        //客户统计一览
        if ($lx == 'totolall') {
            $where = '1=1';
        }

        $areaSearch = $this->rock->post('areaSearch');
        $timeRecord = $this->rock->post('timeRecord');
        $timeRecord2 = $this->rock->post('timeRecord2');
        $desginRecord = $this->rock->post('desginRecord');
        $laiyuanRecord = $this->rock->post('laiyuanRecord');
        $unitnameRecord = $this->rock->post('unitnameRecord');
        $shichangRecord = $this->rock->post('shichangRecord');
        $unitname1 = $this->rock->post('unitname1');
        $status = $this->rock->post('status');
        $brandRe = $this->rock->post('brandRe');

        //happy_add 新增 筛选 查询
        if (!isempt($areaSearch)) {
            $dR = explode(",", $areaSearch);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `routeline`  , '$chkid' ) > 0   || INSTR( `email`  , '$chkid' ) > 0   ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
            // $where .= " and (`routeline` like '%$areaSearch%' or `email` like '%$areaSearch%' )";
        }

        if (!isempt($status)) {

            $dR = explode(",", $status);
            $status_chid = '';
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    // $status_chid .= ',"' . $chkid . '"';
                    $status_chid .= ',' . $chkid . '';
                }
            }
            if ($status_chid != '') $status_chid = substr($status_chid, 1);



            //供应商获取对应供应商状态
            $table = '';
            if (!$this->isincls) { 
                if ($this->isinrz) {
                    $where .= ' and (`rzstatus` in ('.$status_chid.')) ';
                } else {

                    if (!isempt($brandRe) && $brandRe == 2) {
                        //考虑品牌筛选管理员
                        $where .= ' and (`rzstatus` in ('.$status_chid.') ) ';
                    } else {
                        //$where.=' and (`status`='.$status.' or `rzstatus`='.$status.') ';
                        $where .= ' and (`status` in ('.$status_chid.') ) ';

                    }
                }
            }else{

                //$where.=' and `[Q]customer`.shate like "%'.$supplierName.'%"';
                $table = '[Q]customer left join `[Q]supplier_customer` on `[Q]supplier_customer`.customer_id=`[Q]customer`.id ';

                $where .= ' and (`[Q]supplier_customer`.`status` in ('.$status_chid.') and `[Q]supplier_customer`.supplier_id='.$uid.')' ;
                $order='shatedate desc,[Q]customer.status desc';
            }

            // var_dump($where);die;
        }

        if (!isempt($brandRe)) {

           /*  $dR = explode(",", $brandRe);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `yzbrand`  , '$chkid' ) > 0   ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";*/
            //$where.=' and `yzbrand`='.$brandRe;
            $where .= ' and ' . $this->rock->dbinstr('yzbrand', $brandRe);
        }

        if (!isempt($timeRecord) && $timeRecord != '全部') {

            if (isempt($timeRecord2)) {
                $tt = explode("-", $timeRecord);
                //日期筛选优化只选择了年的
                if (isset($tt[1])) {
                    switch ($tt[1]) {
                        case '上半年':
                            $where .= " and (`fpdate` like '%$tt[0]-01%' or `fpdate` like '%$tt[0]-02%' or `fpdate` like '%$tt[0]-03%' or `fpdate` like '%$tt[0]-04%' or `fpdate` like '%$tt[0]-05%' or `fpdate` like '%$tt[0]-06%')";
                            break;
                        case '下半年':
                            $where .= " and (`fpdate` like '%$tt[0]-07%' or `fpdate` like '%$tt[0]-08%' or `fpdate` like '%$tt[0]-09%' or `fpdate` like '%$tt[0]-10%' or `fpdate` like '%$tt[0]-11%' or `fpdate` like '%$tt[0]-12%')";
                            break;
                        case '第一季度':
                            $where .= " and (`fpdate` like '%$tt[0]-01%' or `fpdate` like '%$tt[0]-02%' or `fpdate` like '%$tt[0]-03%')";
                            break;
                        case '第二季度':
                            $where .= " and (`fpdate` like '%$tt[0]-04%' or `fpdate` like '%$tt[0]-05%' or `fpdate` like '%$tt[0]-06%')";
                            break;
                        case '第三季度':
                            $where .= " and (`fpdate` like '%$tt[0]-07%' or `fpdate` like '%$tt[0]-08%' or `fpdate` like '%$tt[0]-09%')";
                            break;
                        case '第四季度':
                            $where .= " and (`fpdate` like '%$tt[0]-10%' or `fpdate` like '%$tt[0]-11%' or `fpdate` like '%$tt[0]-12%')";
                            break;
                        default:
                            $where .= " and (`fpdate` like '%$timeRecord%')";
                            break;
                    }
                } else {
                    $where .= " and (`fpdate` like '%$timeRecord%')";
                }
            } else {
                $where .= " and (`fpdate` between '$timeRecord' and '$timeRecord2')";
            }
        } else if (!isempt($timeRecord) && $timeRecord == '全部') {
        } else {
            $year = date('Y');
            if ($clskefuid!=$this->adminid) {
                $where .= " and (`adddt` > '$year')";
            }
        }
        if (!isempt($shichangRecord)) {
             $dR = explode(",", $shichangRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `shate`  , '$chkid' ) > 0 || INSTR( `marker`  , '$chkid') > 0  || INSTR( `rzmarker`  , '$chkid') > 0";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";

            // $where .= " and (`shate` like '%$shichangRecord%' or " . $this->rock->dbinstr('marker', $shichangRecord) . ")";
            //$where.=" and ( `markerid`='$shichangRecord' )";
        }
        if (!isempt($desginRecord)) {
            //$where.=" and (`shate` like '%$desginRecord%')";
            //$where	= $this->rock->dbinstr('shate', $desginRecord);
            //$where	= $this->rock->dbinstr('gddesigner', $desginRecord);

            $dR = explode(",", $desginRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `shate`  , '$chkid' ) > 0 || INSTR( `gddesigner`  , '$chkid') > 0 || INSTR( `rzdesigner`  ,'$chkid' ) > 0 ";
                    // $desgin_chid .= ',"' . $chkid . '"';
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
            // var_dump($desgin_chid);die();
            //日期筛选优化只选择了年的
            // $where .= ' and (' . $this->rock->dbinstr('shate', $desginRecord) . ' or ' . $this->rock->dbinstr('gddesigner', $desginRecord) . ' or ' . $this->rock->dbinstr('rzdesigner', $desginRecord) . ')';


            // $where.=" and (`shate` in ($desgin_chid) or `gddesigner`  in ($desgin_chid) or `rzdesigner`  in ($desgin_chid) )";
        }

        if (!isempt($laiyuanRecord)) {

            $dR = explode(",", $laiyuanRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `laiyuan`  , '$chkid' ) > 0 ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
            // $where .= " and (`laiyuan` like '%$laiyuanRecord%')";
        }

        if (!isempt($unitnameRecord)) {
            
             $dR = explode(",", $unitnameRecord);
            $str="";
            foreach ($dR as $k1 => $chkid) {
                if (!isempt($chkid)) {
                    $str.="  || INSTR( `unitname`  , '$chkid' ) > 0 || INSTR( `zxstyle`  , '$chkid') > 0|| INSTR( `linkname`  , '$chkid') > 0 ";
                }
            }
            if ($str != '') $str = substr($str, 4);
            $where.=" and ( $str )";
            // $where .= " and (`unitname` like '%$unitnameRecord%' or `zxstyle` like '%$unitnameRecord%' or `linkname`='$unitnameRecord')";
        }
        if (!isempt($key)) $where .= " and (`name` like '%$key%' or `unitname` like '%$key%' or `optname`='$key'  or `tel` like '%$key%'  or `mobile` like '%$key%'  or `address` like '%$key%'  or `gddesigner` like '%$key%'  or `rzdesigner` like '%$key%' )";

        return array(
            'where' => 'and ' . $where,
            'fields' => '*',
            'table' => $table,
            //'fields'=> 'id,name,status,laiyuan,isgys,optdt,createname,optname,linkname,remark,unitname,shate,tel,type,adddt,moneyz,moneyd,htshu,address',
            'order' => $order
        );
    }
}