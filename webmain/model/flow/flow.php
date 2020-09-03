<?php

/**
 *    来自：Joy办公系统开发团队
 *    作者：磐石(rainrock)
 *    网址：http://xh829.com/
 *    系统的核心文件之一，处理工作流程模块的。
 */
class flowModel extends Model
{
    public $modenum;        //当前模块编号
    public $id = 0;    //当前单据ID
    public $moders;            //当前模块数组
    public $modeid;            //当前模块Id
    public $modename;        //当前模块名称
    public $sericnum;        //当前单据单号
    public $rs = array();    //当前单据记录信息
    public $urs = array();    //当前单据对应用户
    public $mwhere;
    public $mtable;                //当前模块对应表
    public $uname;                //当前单据对应用户姓名
    public $uid = 0;        //当前单据对应用户Id
    public $optid = 0;        //当前当街对应操作用Id，如提交人Id
    public $isflow = 0;        //当前模块是否有流程审核步骤


    //当初始化模块后调用
    protected function flowinit()
    {
    }

    //当初始化单据调用
    protected function flowchangedata()
    {
    }

    //删除单据时调用，$sm删除说明
    protected function flowdeletebill($sm)
    {
    }

    //作废单据时调用，$sm作废说明
    protected function flowzuofeibill($sm)
    {
    }

    //提交时调用
    protected function flowsubmit($na, $sm)
    {
    }

    //添加日志记录调用$arr 添加数组
    protected function flowaddlog($arr)
    {
    }

    protected function flowdatalog($arr)
    {
    }

    //审核之前调用$zt 状态， $sm说明
    protected function flowcheckbefore($zt, $sm)
    {
    }

    //审核完成后调用
    protected function flowcheckafter($zt, $sm)
    {
    }

    //流程全部完成后调用
    protected function flowcheckfinsh($zt)
    {
    }


    protected function flowgetfields($lx)
    {
    }

    protected function flowgetoptmenu($opt)
    {
    }

    //自定义审核人重新的方法$num 步骤单号
    protected function flowcheckname($num)
    {
    }

    //审核步骤根据$num 编号判断是否需要审核
    protected function flowcoursejudge($num)
    {
    }

    //操作单据
    protected function flowoptmenu($ors, $crs)
    {
    }

    //自定义是否可查看本单据
    protected function flowisreadqx()
    {
        return false;
    }


    protected function flowprintrows($r)
    {
        return $r;
    }

    //单据判断条件从写$lx类型，$uid用户Id
    protected function flowbillwhere($lx, $uid)
    {
        return '';
    }

    protected $flowweixinarr = array();
    protected $flowviewufieds = 'uid';

    //初始化单据可替换其他属性
    public function flowrsreplace($rs)
    {
        return $rs;
    }

    public function echomsg($msg)
    {
        if (!isajax()) exit($msg);
        showreturn('', $msg, 201);
        exit();
    }

    public function initdata($num, $id = null)
    {
        $this->modenum = $num;
        $this->moders = m('flow_set')->getone("`num`='$num'");
        if (!$this->moders) $this->echomsg('not found mode[' . $num . ']');
        $table = $this->moders['table'];
        $this->modeid = $this->moders['id'];
        $this->modename = $this->moders['name'];
        $this->isflow = (int)$this->moders['isflow'];
        $this->settable($table);
        $this->mtable = $table;
        $this->viewmodel = m('view');
        $this->billmodel = m('flow_bill');
        $this->checksmodel = m('flow_checks');
        $this->wheremodel = m('where');
        $this->flowinit();
        if ($id == null) return;
        $this->loaddata($id, true);
    }

    public function loaddata($id, $ispd = true)
    {
        $this->id = (int)$id;
        $this->mwhere = "`table`='$this->mtable' and `mid`='$id'";
        $this->rs = $this->getone($id);
        $this->rs['gjstatus'] = 127;
        if ('customer' == $this->mtable && $this->rs['id']) {
            $customer_id = $this->rs['id'];
            $supplier_customer = $this->db->getone("xinhu_supplier_customer", "`supplier_id`={$this->adminid} and `customer_id`={$customer_id}", "*");
            $this->rs['gjstatus'] = isset($supplier_customer['status']) ? $supplier_customer['status'] : 0;
        }
        $this->uname = '';
        if (!$this->rs) $this->echomsg('not found record');
        $this->rs['base_name'] = '';
        $this->rs['base_deptname'] = '';
        if (isset($this->rs['uid'])) $this->uid = $this->rs['uid'];
        if (!isset($this->rs['applydt'])) $this->rs['applydt'] = '';
        if (!isset($this->rs['status'])) $this->rs['status'] = 1;
        $uisfield = property_exists($this, 'uidfields') ? $this->uidfields : 'optid';
        if ($this->uid == 0 && isset($this->rs[$uisfield])) $this->uid = $this->rs[$uisfield];
        $this->optid = isset($this->rs['optid']) ? $this->rs['optid'] : $this->uid;
        $this->urs = $this->db->getone('[Q]admin', $this->uid, 'id,name,deptid,deptname,ranking,superid,superpath,deptpath,superman');
        if ($this->isempt($this->rs['applydt']) && isset($this->rs['optdt'])) $this->rs['applydt'] = substr($this->rs['optdt'], 0, 10);
        if ($this->urs) {
            $this->drs = $this->db->getone('[Q]dept', "`id`='" . $this->urs['deptid'] . "'");
            $this->uname = $this->urs['name'];
            $this->rs['base_name'] = $this->uname;
            if ($this->drs) {
                $this->rs['base_deptname'] = $this->drs['name'];
            }
        }
        $this->sericnum = '';
        $this->billrs = $this->billmodel->getone($this->mwhere);
        if ($this->billrs) {
            $this->sericnum = $this->billrs['sericnum'];
        } else {
            if ($this->isflow == 1) $this->savebill();
        }

        if ($ispd) $this->isreadqx();

        $this->rssust = $this->rs;
        $this->flowchangedata();

        $this->rs['base_modename'] = $this->modename;
        $this->rs['base_sericnum'] = $this->sericnum;
        $this->rs['base_summary'] = $this->rock->reparr($this->moders['summary'], $this->rs);
    }

    public function isreadqx()
    {
        $bo = false;
        if ($this->uid == $this->adminid && $this->adminid > 0) $bo = true;
        if (!$bo && $this->isflow == 1) {
            if ($this->billrs) {
                $allcheckid = $this->billrs['allcheckid'];
                if (contain(',' . $allcheckid . ',', ',' . $this->adminid . ',')) $bo = true;
            }
        }
        if (!$bo) {
            if ($this->urs && contain($this->urs['superpath'], '[' . $this->adminid . ']')) $bo = true;
        }/*
		//判断是不是组长 happy——add======删
		if(!$bo){
			$deptid = m('adming')->getisdeptid($this->adminid);
			var_dump($this->urs['deptpath']);
			if($this->urs && contain($this->urs['deptpath'],'['.$deptid.']'))$bo = true;
		}*/

        if (!$bo) $bo = $this->flowisreadqx();
        if (!$bo) {
            $where = $this->viewmodel->viewwhere($this->moders, $this->adminid, $this->flowviewufieds);
            $tos = $this->rows("`id`='$this->id'  $where ");
            if ($tos > 0) $bo = true;
        }
        if (!$bo) $this->echomsg('无权限查看模块[' . $this->modenum . '.' . $this->modename . ']' . $this->uname . '的数据');
    }

    public function iseditqx()
    {
        $bo = 0;
        if ($bo == 0 && $this->isflow == 1) {
            if ($this->billrs && $this->uid == $this->adminid) {
                if ($this->billrs['nstatus'] == 0 || $this->billrs['nstatus'] == 2) {
                    $bo = 1;
                }
            }
        }
        if ($bo == 0) {
            $where = $this->viewmodel->editwhere($this->moders, $this->adminid);
            $tos = $this->rows("`id`='$this->id'  $where ");
            if ($tos > 0) $bo = 1;
        }
        return $bo;
    }

    public function isdeleteqx()
    {
        $bo = 0;
        if ($bo == 0 && $this->isflow == 1) {
            if ($this->billrs && $this->uid == $this->adminid) {
                if ($this->billrs['nstatus'] == 0 || $this->billrs['nstatus'] == 2) {
                    $bo = 1;
                }
            }
        }
        if ($bo == 0) {
            $where = $this->viewmodel->deletewhere($this->moders, $this->adminid);
            $tos = $this->rows("`id`='$this->id'  $where ");
            if ($tos > 0) $bo = 1;
        }
        return $bo;
    }


    public function getfields($lx = 0)
    {
        $fields = array();
        $farr = $this->db->getrows('[Q]flow_element', "`mid`='$this->modeid' and `iszb`=0 and `iszs`=1", '`fields`,`name`', 'sort,id');
        foreach ($farr as $k => $rs) $fields[$rs['fields']] = $rs['name'];
        $fters = $this->flowgetfields($lx);
        if (is_array($fters)) $fields = array_merge($fields, $fters);
        return $fields;
    }

    /**
     *    读取展示数据
     *    $lx 0pc, 1移动
     */
    public function getdatalog($lx = 0)
    {
        m('log')->addread($this->mtable, $this->id);
        $arr['modename'] = $this->modename;
        $arr['title'] = $this->modename;
        $arr['modeid'] = $this->modeid;
        $arr['modenum'] = $this->modenum;
        $arr['mid'] = $this->id;
        $arr['billrsid'] = $this->billrs['id'];
        $contview = '';

        $this->clsdeptid = getconfig('clsdeptid');
        $this->clskefuid = getconfig('clskefuid');
        $aaid = $this->adminid;
        $deptid = m('admin')->getone("`id`='$aaid'", 'deptid');
        //字段隐藏处理
        if ($this->isincls && $this->modenum == 'customer') {
            $path = P . '/flow/page/view_customer_gys_1.html';
        } else if ($aaid == $this->clskefuid && $this->modenum == 'customer') {
            $path = P . '/flow/page/view_customer_gys_2.html';
        }else{
            $path = '' . P . '/flow/page/view_' . $this->modenum . '_' . $lx . '.html';
        }
// var_dump($this->modenum,$path);die;
        $fstr = m('file')->getstr($this->mtable, $this->id, 1);
        $issubtabs = 0;
        if ($fstr != '') {
            $this->rs['file_content'] = $fstr;
        }
        if (isset($this->rs['explain'])) $this->rs['explain'] = str_replace("\n", '<br>', $this->rs['explain']);
        if (isset($this->rs['content'])) $this->rs['content'] = str_replace("\n", '<br>', $this->rs['content']);
        $subd = $this->getsubdata(0);
        $issubtabs = $subd['iscz'];
        $data = $this->flowrsreplace($this->rs, 1);

        # 工地和工费来回切换

        //获取有权限的ID
        $rgfeeuserid = getconfig('rgfeeuserid');
        $isin = in_array($this->adminid, $rgfeeuserid);
        $authorid = $this->db->getone('[Q]admin', "`name`='" . $data['author'] . "'", 'id');
        if (($isin || $this->adminid == $authorid['id']) && ($num = 'rgfee' || $num = 'jzrgfee' || $num = 'yzjuzhuang' || $num = 'rzgongdi' || $num = 'book')) {
            $description="工地详情";
            switch ($this->modenum) {
                case 'rgfee':
                    if (!empty($data['bookid'])) {
                        $num11="book";
                        $mid11=$data['bookid'];
                    }else if(!empty($data['rzgongdiid'])){                        
                        $num11="rzgongdi";
                        $mid11=$data['rzgongdiid'];
                    }
                    break;
                case 'jzrgfee':
                    if (!empty($data['jzgongdiid'])) {
                        $num11="yzjuzhuang";
                        $mid11=$data['jzgongdiid'];
                    }
                    break;
                
                case 'yzjuzhuang':
                    if (!empty($data['rgfeeid'])) {
                        $num11="jzrgfee";
                        $mid11=$data['rgfeeid'];
                    }
                    $description="工费详情";
                    break;

                case 'rzgongdi':
                    if (!empty($data['rgfeeid'])) {
                        $num11="rgfee";
                        $mid11=$data['rgfeeid'];
                    }
                    $description="工费详情";
                    break;

                case 'book':
                    if (!empty($data['rgfeeid'])) {
                        $num11="rgfee";
                        $mid11=$data['rgfeeid'];
                    }
                    $description="工费详情";
                    break;
                default:
                    $num11="";
                    $mid11="";
                    break;
            }
            if (!empty($num11)&&!empty($mid11)) {
                $changeurlstr = '&num='.$num11.'&mid='.$mid11.'';               
                $arr['changeurlstr'] = $changeurlstr;
                $arr['description'] = $description;
            }
        }

        // var_dump($data);die;

        if (file_exists($path)) {
            $contview = file_get_contents($path);
            $contview = $this->rock->reparr($contview, $data);
        }
        if ($this->isempt($contview)) {
            $_fields = array();
            if ($this->isflow == 1) {
                $_fields['base_sericnum'] = '单号';
                $_fields['base_name'] = '申请人';
                $_fields['base_deptname'] = '申请人部门';
            }
            $fields = array_merge($_fields, $this->getfields($lx));
            if ($lx == 0) foreach ($fields as $k => $rs) {
                $data['' . $k . '_style'] = 'width:75%';
                break;
            }
            if ($fstr != '') $fields['file_content'] = '相关文件';
            if ($issubtabs == 1) $fields[$subd['fields']] = $subd['name'];
            if (!isset($fields['optdt'])) $fields['optdt'] = '操作时间';
            $contview = c('html')->createtable($fields, $data);

            $contview = '<div align="center">' . $contview . '</div>';
        }
        $arr['contview'] = $contview;
        $this->clsdeptid = getconfig('clsdeptid');
        $aaid = $this->adminid;
        $deptid = m('admin')->getone("`id`='$aaid'", 'deptid');
        $this->isincls = in_array($deptid['deptid'], $this->clsdeptid);
        //看不到之前工作日志里的所有拜访记录
        if ($this->isincls && $num = 'customer') {
            $arr['logarr'] = $this->getlog($aaid);
        // var_dump($aaid);die;
            // $arr['logarr'] = array();
        } else {
            $arr['readarr'] = m('log')->getreadarr($this->mtable, $this->id);
            $arr['logarr'] = $this->getlog();
        }
        // var_dump($num);die;
        $arr['isedit'] = $this->iseditqx();
        $arr['isdel'] = $this->isdeleteqx();
        $arr['isflow'] = $this->isflow;
        $arr['flowinfor'] = array();
        if ($this->isflow == 1) $arr['flowinfor'] = $this->getflowinfor();
        if (isset($data['title'])) $arr['title'] = $data['title'];
        $_oarr = $this->flowdatalog($arr);
        if (is_array($_oarr)) foreach ($_oarr as $k => $v) $arr[$k] = $v;
        return $arr;
    }

    public function getsubdata($xu = 0)
    {
        $iscz = 0;
        $tables = $this->moders['tables'];
        $iszb = $xu + 1;
        $fields = 'subdata' . $xu . '';
        $subrows = $this->db->getrows('[Q]flow_element', '`mid`=' . $this->modeid . ' and `iszb`=' . $iszb . ' and `iszs`=1', '`fields`,`name`', '`sort`');
        if ($this->db->count > 0) {
            $iscz = 1;
            $headstr = 'xuhaos,,center';
            foreach ($subrows as $k => $rs) $headstr .= '@' . $rs['fields'] . ',' . $rs['name'] . '';
            if (!isset($this->rs[$fields])) {
                $rows = $this->db->getall('select * from `[Q]' . $tables . '` where mid=' . $this->id . ' order by sort');
                foreach ($rows as $k => $rs) $rows[$k]['xuhaos'] = $k + 1;
            } else {
                $rows = $this->rs[$fields];
            }
            $this->rs[$fields] = c('html')->createrows($rows, $headstr, '#cccccc', 'noborder');
            $this->rs['' . $fields . '_style'] = 'padding:0';
        }
        return array(
            'iscz' => $iscz,
            'xu' => $xu,
            'fields' => $fields,
            'name' => $this->moders['names']
        );
    }

    /**
     *    读取编辑数据
     */
    public function getdataedit()
    {
        $arr['data'] = $this->rssust;
        $arr['table'] = $this->mtable;
        $arr['tables'] = $this->moders['tables'];
        $arr['modeid'] = $this->modeid;
        $arr['isedit'] = $this->iseditqx();
        $arr['isflow'] = $this->isflow;
        $arr['user'] = $this->urs;
        $arr['status'] = $this->rs['status'];
        $arr['filers'] = m('file')->getfile($this->mtable, $this->id);
        return $arr;
    }

    /*
	*	读取流程信息
	*/
    public function getflowinfor()
    {
        $ischeck = 0;
        $ischange = 0;
        $str = '';
        $arr = $this->getflow();
        $nowcheckid = ',' . $arr['nowcheckid'] . ',';
        if (contain($nowcheckid, ',' . $this->adminid . ',')) {
            $ischeck = 1;
        }
        $logarr = $this->getlog();
        $nowcur = $this->nowcourse;
        if ($this->rock->arrvalue($this->nextcourse, 'checktype') == 'change') $ischange = 1; //需要自己选择下一步处理人
        $sarr['ischeck'] = $ischeck;
        $sarr['ischange'] = $ischange;
        $sarr['nowcourse'] = $nowcur;
        $sarr['nextcourse'] = $this->nextcourse;
        $sarr['beforecourse'] = $this->beforecourse;
        $sarr['nstatustext'] = $arr['nstatustext'];

        //读取当前审核表单
        $_checkfields = $this->rock->arrvalue($nowcur, 'checkfields');
        $checkfields = array();
        if ($ischeck == 1 && !isempt($_checkfields)) {
            $inputobj = c('input');
            $inputobj->flow = $this;
            $inputobj->mid = $this->id;
            $inputobj->urs = $this->urs;
            $elwswhere = "`mid`='$this->modeid' and `iszb`=0 and instr(',$_checkfields,', concat(',',`fields`,','))>0";
            $infeidss = $inputobj->initFields($elwswhere);
            foreach ($infeidss as $_fs => $fsva) {
                $_sfes = $fsva['fields'];
                $_type = $fsva['fieldstype'];
                $checkfields[$_sfes] = array(
                    'inputstr' => $inputobj->getfieldcont($_sfes),
                    'name' => $fsva['name'],
                    'fieldstype' => $_type,
                    'fieldsarr' => $fsva,
                    'showinpus' => 1
                );
                if (substr($_type, 0, 6) == 'change' && !isempt($fsva['data'])) {
                    $_sfes = $fsva['data'];
                    $checkfields[$_sfes] = array(
                        'inputstr' => '',
                        'name' => $fsva['name'] . 'id',
                        'fieldsarr' => false,
                        'showinpus' => 2
                    );
                }
            }
        }
        //var_dump($this);die();
        $sarr['checkfields'] = $checkfields;
        if ($this->rs['status'] == 2) $sarr['nstatustext'] .= ',<font color="#AB47F7">待提交人处理</font>';
        $loglen = count($logarr);

        $clpfflowid = getconfig('clpfflowid');
        $clpfuid = getconfig('clpfuid');

        $anzflowid = getconfig('anzflowid');
        $xiadanuid = getconfig('xiadanuid');
        $anzuid = getconfig('anzuid');
        $buildin="buildin2";
        $clpaifa="clpaifa2";
        //新增判断是不是管理员和总监和史节法
        $isinu = in_array($this->adminid, $clpfuid);
        $isinxiadnau = in_array($this->adminid, $xiadanuid);
        //新增判断是不是管理员和总监和史节法
        $isinanzu = in_array($this->adminid, $anzuid);
        if ($isinanzu) {
            $buildin="buildin";
        }
        if ($isinu) {
            $clpaifa="clpaifa";
        }

        //回退了的流程。不显示。用group？
        $logarr2 = $this->getlogUnique();
        $loglen2 = count($logarr2);
        //group只能去处已经触发并完成的处理，如果回退中包含待处理的需要手动array_unique.功能待完善
        foreach ($logarr2 as $k => $rs) {
            $rs = $logarr2[$loglen2 - $k - 1];
            if ($rs['courseid'] > 0) {
                $sty = '';
                $col = $rs['color'];
                if ($str != '') $str .= ' → ';
                 // 这是无论如何都会执行的 20191126   下单按钮踢出去放下面的情况
                $str.='<div class="div01  linediv stepdone  ">'
                           .'<img src="images/2.png" class="  img-position">'
                               .'<div class="line"></div>'
                            .'</div>   ' 
                        .'<div class=" P10 w100 pT5">'  
                              .'<div class="box-three"></div>'
                             .'<div class="status-div">  <span class="step_text">' . $rs['actname'] . '</span> <span class="time_text ">' . $rs['optdt'] . '</span> </div>'
                             .'<div class="desc_status "> <span class="active" color="' . $col . '">' . $rs['statusname'] . '</span> </div>'
                        .'</div>';

                $isin = in_array($rs['courseid'], $clpfflowid);

                $isinanz = in_array($rs['courseid'], $anzflowid);

                if ($button_str != '' && ( $isin || $isinanz) ) $button_str .= ' → ';
                
                //我觉得这里可以重新盘一下，按流程id来。然后根据用户来区分是只能看还是可以处理
                // 按钮是否可见

               
                if ($isinxiadnau || $this->adminname == $this->billrs['author']) {
                    //材料派发
                    if ($isin) {
                        # 可以处理的相关人员...
                        $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.'.$clpaifa.'(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';

                        if ($rs['courseid']==50) { //基础材料派发      +   铝合金测量
                            $button_str .= '→';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        }else if ($rs['courseid']==56) {//泥木材料派发    +   瓷砖测量
                             $button_str .= '→';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                        }
                        //局装 
                        if ($rs['courseid']==129) { //材料配送   铝合金测量 材料派发 瓷砖测量 
                            $button_str .= '→';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(992,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                            $button_str .= '→';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(993,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                        }

                    }else if ($isinanz) {
                        if ($rs['courseid']==132) { //复量变更 = 石材测量 + 安装工程测量  
                        //局装 
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(994,' . $this->billrs['id'] . ')" >石材测量</span>';
                            $button_str .= '→';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                        }else{

                            if ($rs['courseid']==58) {
                                $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                            }else{
                                $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$buildin.'(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';
                            }
                        }
                    }
                }



/*
                //新增onclic事件
                if ($isin && $isinu) {
                    $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.clpaifa(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';

                    if ($rs['courseid']==50) { //基础材料派发      +   铝合金测量
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                    }else if ($rs['courseid']==56) {//泥木材料派发    +   瓷砖测量
                         $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }


                    //局装 
                    if ($rs['courseid']==129) { //材料配送   铝合金测量 材料派发 瓷砖测量 
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(992,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(993,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }

                } else if ($isin && $this->adminname == $this->billrs['author']) {
                    $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.clpaifa2(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';
                    if ($rs['courseid']==50) { 
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                    }else if ($rs['courseid']==56) {
                         $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }


                     //局装 
                    if ($rs['courseid']==129) { 
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(992,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(993,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }
                } else if ($isinanz && $isinanzu) {


                    if ($rs['courseid']==132) { //复量变更 = 石材测量 + 安装工程测量  
                    //局装 
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(994,' . $this->billrs['id'] . ')" >石材测量</span>';
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                    }else{

                        if ($rs['courseid']==58) {
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                        }else{
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';
                        }
                    }
                } else if ($isinanz && $this->adminname == $this->billrs['author']) {

                    if ($rs['courseid']==132) {
                    //局装 
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(994,' . $this->billrs['id'] . ')" >石材测量</span>';
                        $button_str .= '→';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                    }else{
                        if ($rs['courseid']==58) {
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                        }else{
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.buildin2(' . $rs['courseid'] . ',' . $this->billrs['id'] . ')" >' . $rs['actname'] . '</span>';
                        }
                    }
                } */

            }
        }

        foreach ($this->flowarr as $k => $rs) {
            //如果后期要加判断不管流程过没过都可以继续配送就改这里

            if ($rs['ischeck'] == 0) {
                $sty = 'color:#888888';
                $no = 'no';
                if ($rs['isnow'] == 1) $sty = 'font-weight:bold;color:#93c2ff';
                if ($rs['isnow'] == 1 || $rs['id'] == 135 || $rs['id'] == 73) $no = '';
                if ($str != '') $str .= ' <font color=#888888>→</font> ';
                 // 这是无论如何都会执行的 20191126   下单按钮踢出去放下面的情况
                $str.='<div class="div01  linediv ">'
                           .'<i class="iconfont icon-success-fill icon-position"></i>'
                               .'<div class="line"></div>'
                            .'</div>   ' 
                        .'<div class=" P10 w100 pT5">'  
                              .'<div class="box-three"></div>'
                             .'<div class="status-div">  <span class="step_text">' . $rs['name'] . '</span></div>'
                             // .'<div class="desc_status "> <span class="" >' . $rs['nowcheckname'] . '</span> </div>'
                        .'</div>';



                // 这是无论如何都会执行的 20191126   下单按钮踢出去放下面的情况
               /* $str .= '<span style="' . $sty . '">' . $rs['name'] . '';

                if (!isempt($rs['nowcheckname'])) $str .= '（' . $rs['nowcheckname'] . '）';
                $str .= '</span>';*/
                $isin = in_array($rs['id'], $clpfflowid);
                $isinanz = in_array($rs['id'], $anzflowid);

                if ($button_str != '' && ( $isin || $isinanz)  ) $button_str .= '<font color=#888888>→</font>';
                //新增onclic事件    no代表不能点击，如果以后流程不到也想下单就去掉前面的no
                /*
                 权限：  14.史节法只有辅材派发权限（基础材料，水电材料，泥木材料，油漆材料），主材没有派发权限，只有查看记录权限
                        15.徐鹏飞权限为，铝合金测量，瓷砖测量，石材测量，安装工程测量，通知安装。


                下单按钮：元贞，域嘉

                1.基础材料派发
                对应识别下单流程【铝合金测量  基础材料派发】 
                2.水电材料派发
                对应识别下单流程【水电材料派发】   
                3.泥木材料派发
                对应识别下单流程【泥木材料派发   瓷砖测量】  
                4.安装测量
                对应识别下单流程【石材测量】
                5.油漆材料派发
                对应识别下单流程【油漆材料派发】  
                6.安装工程测量
                对应识别下单流程【安装工程测量】   
                7.通知安装
                对应识别下单流程【通知安装】

                局装
                1.材料配送
                对应识别下单流程【铝合金测量 材料派发 瓷砖测量     】  
                2.复量变更
                对应识别下单流程【石材测量  安装工程测量】  
                3.通知安装
                对应识别下单流程【通知安装】



                测量历史记录放到复量变更里面

                上面序号标识为oa流程，到了后，下面一排对应括号内的下单按自动钮识别，点击进入可派发材料。流程未到，下单按钮点不进去，派发不了。

                */

                //我觉得这里可以重新盘一下，按流程id来。然后根据用户来区分是只能看还是可以处理
               
                if ($isinxiadnau || $this->adminname == $this->billrs['author']) {
                    //材料派发
                    if ($isin) {
                        $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.'.$no.$clpaifa.'(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                        //元贞，域嘉 
                        if ($rs['id']==50) { 
                            $button_str .= '<font color=#888888>→</font>';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        }else if ($rs['id']==56) {
                             $button_str .= '<font color=#888888>→</font>';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                        }
                        
                        //局装   史节法只有辅材派发权限（基础材料，水电材料，泥木材料，油漆材料），主材没有派发权限，只有查看记录权限
                        if ($rs['id']==129) { 
                            $button_str .= '<font color=#888888>→</font>';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                            $button_str .= '<font color=#888888>→</font>';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                        }

                    }else if ($isinanz) {
                        if ($rs['id']==132) {
                        //局装 
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(58,' . $this->billrs['id'] . ')" >石材测量</span>';
                            $button_str .= '<font color=#888888>→</font>';
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                        }else{
                            if ($rs['id']==58) {
                                $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                            }else{
                                $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.$buildin.'(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                            }

                        }
                    }
                }





/*
                if ($isin && $isinu) {
                    $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.'.$no.'clpaifa(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                    //元贞，域嘉 
                    if ($rs['id']==50) { 
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                    }else if ($rs['id']==56) {
                         $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }
                    
                    //局装   史节法只有辅材派发权限（基础材料，水电材料，泥木材料，油漆材料），主材没有派发权限，只有查看记录权限
                    if ($rs['id']==129) { 
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }


                } else if ($isin && $this->adminname == $this->billrs['author']) {
                    $button_str .= '<span style="' . $sty . '" class="clpaifa"   onclick="c.'.$no.'clpaifa2(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                    //元贞，域嘉 
                    if ($rs['id']==50) { 
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                    }else if ($rs['id']==56) {
                         $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }

                    //局装 
                    if ($rs['id']==129) { 
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(990,' . $this->billrs['id'] . ')" >铝合金测量</span>';
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(991,' . $this->billrs['id'] . ')" >瓷砖测量</span>';
                    }

                } else if ($isinanz && $isinanzu) {
                    if ($rs['id']==132) {
                    //局装 
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(58,' . $this->billrs['id'] . ')" >石材测量</span>';
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                    }else{
                        if ($rs['id']==58) {
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                        }else{
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                        }

                    }

                } else if ($isinanz && $this->adminname == $this->billrs['author']) {
                    if ($rs['id']==132) {
                    //局装 
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(58,' . $this->billrs['id'] . ')" >石材测量</span>';
                        $button_str .= '<font color=#888888>→</font>';
                        $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >安装工程测量</span>';
                    }else{
                        if ($rs['id']==58) {
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >石材测量</span>';
                        }else{
                            $button_str .= '<span style="' . $sty . '" class="buildin"   onclick="c.'.$no.'buildin2(' . $rs['id'] . ',' . $this->billrs['id'] . ')" >' . $rs['name'] . '</span>';
                        }
                    }
                }*/

            }
        }
        $sarr['flowcoursestr'] = $str;
        $sarr['flowcoursebutton_str'] = $button_str;
    // var_dump();die;

        $actstr = ',通过|green,不通过|red';
        if (isset($nowcur['courseact'])) {
            $actstrt = $nowcur['courseact'];
            if (!isempt($actstrt)) $actstr = ',' . $actstrt;
        }
        $act = c('array')->strtoarray($actstr);
        foreach ($act as $k => $as1) if ($k > 0 && $as1[0] == $as1[1]) $act[$k][1] = '';
        $sarr['courseact'] = $act;
        $nowstatus = $this->rs['status'];
        if ($this->isflow == 1 && $this->rs['isturn'] == 0) $nowstatus = 3;

        $rers = $this->db->getone('[Q]flow_bill', "`mid`='" . $this->rs['id'] . "' and `table`='$this->modenum'");
        if ($rers['status'] == 1) {
            $sarr['nowstatus'] = $rers['status'];
        }
        return $sarr;
    }

    private $getlogrows = array();

    public function getlog($aaid = '')
    {
        //客户管理才会这样
        $where = $this->mwhere;
        if ($num = 'customer') {
            $this->clsdeptid= getconfig('clsdeptid');
            // 除管理员外，其它所有账号对供应商客服及材料商跟进记录查看权限取消。
            $deptid="'".implode("','", $this->clsdeptid)."'";
            $rnurs = $this->db->getrows('[Q]admin', "`deptid` in ($deptid) ", 'id', 'sort');
            foreach ($rnurs as $k => $rns) {
                $cuid .= "','" . $rns['id'] ." ";
            }
            if ($cuid != '') {
                $cuid = substr($cuid, 2)."'";
            }
            // var_dump($cuid);die;
            if ($aaid) {
                $where .= " and `checkid` in ('$this->adminid','513')";
            }else if($this->adminid == getconfig('clskefuid')){
                $where .= " and `checkid` in ($cuid,'513')";
            }else if($this->adminid!=1){
                $where .= " and `checkid` not in ($cuid,'513')";            
            }
        }
        if ($this->getlogrows) return $this->getlogrows;
        $rows = $this->db->getrows('[Q]flow_log', $where, '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`', '`id` desc');
        $uids = '';
        $dts = c('date');
        $fo = m('file');
        foreach ($rows as $k => $rs) {
            $uids .= ',' . $rs['checkid'] . '';
            $col = $rs['color'];
            if (isempt($col)) $col = 'green';
            if (contain($rs['statusname'], '不')) $col = 'red';
            $rows[$k]['color'] = $col;
            $rows[$k]['optdt'] = $dts->stringdt($rs['optdt'], 'G(周w) H:i:s');
            /*
			$fstr 			   = $fo->getstr('flow_log', $rs['id'], 1);
			if($fstr!='')if(!isempt($rs['explain'])){$rs['explain'].='<br>'.$fstr.'';}else{$rs['explain']=$fstr;}
			$rows[$k]['explain']= $rs['explain'];*/
        }
        if ($uids != '') {
            $rows = m('admin')->getadmininfor($rows, substr($uids, 1), 'checkid');
        }
        $this->getlogrows = $rows;
        return $rows;
    }

    public function getlogUnique()
    {
        //if($this->getlogrows)return $this->getlogrows;
        $rows = $this->db->getrows('[Q]flow_log', $this->mwhere . ' group by courseid', '`checkname` as `name`,`checkid`,`name` as actname,`optdt`,`explain`,`statusname`,`courseid`,`color`,`fileid`,`id`,`rgfeelist`,`clupdatelist`,`totalprice`,`alltotal`', '`id` desc');
        $uids = '';
        $dts = c('date');
        $fo = m('file');
        foreach ($rows as $k => $rs) {
            $uids .= ',' . $rs['checkid'] . '';
            $col = $rs['color'];
            if (isempt($col)) $col = 'green';
            if (contain($rs['statusname'], '不')) $col = 'red';
            $rows[$k]['color'] = $col;
            $rows[$k]['optdt'] = $dts->stringdt($rs['optdt'], 'G(周w) H:i:s');
            /*
			$fstr 			   = $fo->getstr('flow_log', $rs['id'], 1);
			if($fstr!='')if(!isempt($rs['explain'])){$rs['explain'].='<br>'.$fstr.'';}else{$rs['explain']=$fstr;}
			$rows[$k]['explain']= $rs['explain'];*/
        }
        if ($uids != '') {
            $rows = m('admin')->getadmininfor($rows, substr($uids, 1), 'checkid');
        }
        //$this->getlogrows = $rows;
        return $rows;
    }

    public function addlog($arr = array(), $fileid = '', $czid = '')
    {
        $addarr = array(
            'table' => $this->mtable,
            'mid' => $this->id,
            'checkname' => $this->adminname,
            'checkid' => $this->adminid,
            'optdt' => $this->rock->now,
            'courseid' => '0',
            'status' => '1',
            'ip' => $this->rock->ip,
            'web' => $this->rock->web,
            'modeid' => $this->modeid
        );
        // var_dump($czid,$$this->mtable);die;
        if ($czid == -34) {
            //$isdel=$this->db->getone('[Q]rgfee',"`id`='".$this->rs['rgfeeid']."'",'id');
            $table="rgfee";
            if ($this->mtable=='yzjuzhuang') {
                $table="jzrgfee";
            }
            $addarr = array(
                'table' => $table,
                'mid' => $this->rs['rgfeeid'],
                'checkname' => $this->adminname,
                'checkid' => $this->adminid,
                'optdt' => $this->rock->now,
                'courseid' => '0',
                'status' => '1',
                'ip' => $this->rock->ip,
                'web' => $this->rock->web,
                'modeid' => $this->modeid
            );
        }
        foreach ($arr as $k => $v) $addarr[$k] = $v;
        m('flow_log')->insert($addarr);
        $ssid = $this->db->insert_id();
        if ($fileid != '') m('file')->addfile($fileid, $this->mtable, $this->id);
        if ($czid != -12) {
            # code...
            $logfileid = $this->rock->post('logfileid');
            if ($logfileid != '') m('file')->addfile($logfileid, $this->mtable, $this->id);
        }
        $addarr['id'] = $ssid;
        $this->flowaddlog($addarr);
        return $ssid;
    }

    public function submit($na = '', $sm = '')
    {
        if ($na == '') $na = '提交';
        $isturn = 1;
        if ($na == '保存') $isturn = 0;
        $this->addlog(array(
            'name' => $na,
            'explain' => $sm
        ));
        if ($this->isflow == 1) {
            $marr['isturn'] = $isturn;
            //$marr['status'] = 0;
            $this->rs['status'] = 0;
            $this->update($marr, $this->id);
            $farr = $this->getflow();
            //$farr['status'] = 0;
            $this->savebill($farr);
            if ($isturn == 1) {
                $this->nexttodo($farr['nowcheckid'], 'submit');
            }
        }
        $this->flowsubmit($na, $sm);
    }


    /**
     * 追加说明
     */
    public function zhuijiaexplain($sm = '', $czid)
    {

        //step1：判断是不是业主 deptid==14
        $userdata = m('admin')->getinfor($this->adminid);

        //手机版追加说明添加文件
        $fileid = $this->rock->post('file');
        if (isempt($fileid)) {
            $fileid = $this->rock->post('logfileid');
        }
        //      die($fileid);
        $this->addlog(array(
            'explain' => $sm,
            'fileid' => $fileid,
            'name' => '追加说明',
            'status' => 1,
        ), '', $czid);
        $zt = $this->rs['status'];
        //if(($zt==2 && $this->isflow==1)||$userdata['deptid']==5){好像比较麻烦
        if ($zt == 2 && $this->isflow == 1) {
            $marr['status'] = 0;
            $this->rs['status'] = 0;
            $this->update($marr, $this->id);
            $farr = $this->getflow();
            $farr['status'] = 0;
            $this->savebill($farr);
            $this->nexttodo($farr['nowcheckid'], 'zhui', $sm);
        }
        //step2：找到客服和监理 deptid==14    管理员 deptid==5
        if ($userdata['deptid'] == 14) {
            //happy_add 短信通知 史节法和监理
            $title = '业主追加说明';
            $jlunames = '史节法';
            if ($this->rs['author']) {
                $jluname = explode(',', $this->rs['author']);
                foreach ($jluname as $kjl => $vjl) {
                    $jlunames .= "','" . $vjl . "";
                }
            }
            //var_dump($jlunames);
            $cont = '您有一条[' . $this->rs['title'] . '][' . $this->rs['chuban'] . ']的留言:' . $sm;
            if ($cont != '') m('sms')->postsms2($cont, $jlunames);
        }

        //step3：每天巡检做日志，通知工程经理黄友光和admin
        if ($userdata['deptid'] == 36) {
            $pushnames = $userdata['name']."','管理员";
            if ($this->rs['author']) {
                $jluname = explode(',', $this->rs['author']);
                foreach ($jluname as $kjl => $vjl) {
                    $pushnames .= "','" . $vjl . "";
                }
            }
            //var_dump($jlunames);
            $cont = '您有一条[' . $this->rs['title'] . '][' . $this->rs['chuban'] . ']的留言:' . $sm;
            if ($cont != '') m('sms')->postsms2($cont, $pushnames);
        }
    }

    /*
	*	获取流程
	*/
    public function getflow($sbo = false)
    {
        $rows = $this->db->getrows('[Q]flow_course', "`setid`='$this->modeid' and `status`=1", '*', '`sort`,id asc');
        $this->flowarr = array();
        $allcheckid = $nowcheckid = $nowcheckname = $nstatustext = '';
        $allcheckids = array();
        $nstatus = $this->rs['status'];
        $this->nowcourse = array();
        $this->nextcourse = array();
        $this->beforecourse = array();//xin.zou 2017.03.16  初始化前置节点数据
        $this->flowisend = 0;

        $curs = $this->db->getrows('[Q]flow_log', "$this->mwhere and `courseid`>0", 'checkid,checkname,courseid,`valid`,`status`,`statusname`,`name`', 'id desc');
        /*-----------start--xin.zou 新增获取前置节点信息-----------------------*/
        $curs_da = array();
        $re_curs = array_reverse($curs);
        $beforeNoCheck = false;
        $nowCurs = array();
        foreach ($re_curs as $k => $rs) {
            if ($rs['status'] == 2 && $beforeNoCheck == false) continue;
            $beforeNoCheck = true;
            if ($rs['status'] == 1) {
                $curs_da[$rs['courseid']] = $rs;
            } else {
                if (isset($curs_da[$this->getBeforeCourseId($rows, $rs['courseid'])])) {
                    $nowCurs = $curs_da[$this->getBeforeCourseId($rows, $rs['courseid'])];
                    unset($curs_da[$this->getBeforeCourseId($rows, $rs['courseid'])]);
                }
            }

        }
        /*------------end--xin.zou 新增获取前置节点信息--------------------*/
        $cufss = $ztnas = $chesarr = array();
        foreach ($curs as $k => $rs) {
            $_su = '' . $rs['courseid'] . '';
            $_su1 = '' . $rs['courseid'] . '_' . $rs['checkid'] . '';
            if (isset($curs_da[$rs['courseid']]) && $rs['valid'] == 1 && $rs['status'] == 1) {
                if (!isset($cufss[$_su])) $cufss[$_su] = 0;
                $cufss[$_su]++;
                $chesarr[$_su1] = 1;
            }
            //循环增加checkid   这句话是把处理过的先提出来摆在最前面
            //更换监理，前监理工地列表中工地消失（前监理不要他看）
            //if (!in_array($rs['checkid'], $allcheckids)) $allcheckids[] = $rs['checkid'];
            if ($nstatustext == '' && $rs['courseid'] > 0) {
                $nstatustext = '' . $rs['checkname'] . '处理' . $rs['statusname'] . '';
                $nstatus = $rs['status'];
            }
            $ztnas[$rs['courseid']] = '' . $rs['checkname'] . '' . $rs['statusname'] . '';
        }
        $nowstep = $zongsetp = -1;
        $isend = 0;
        foreach ($rows as $k => $rs) {
            $whereid = (int)$rs['whereid'];
            $checkshu = $rs['checkshu'];

            if ($whereid > 0) {
                $bo = $this->wheremanzhu($whereid);
                if (!$bo) continue;
            }

            if (!isempt($rs['num'])) {
                $bo = $this->flowcoursejudge($rs['num']);
                if (is_bool($bo) && !$bo) continue;
            }

            $zongsetp++;
            $uarr = $this->getcheckname($rs);
            $checkid = $uarr[0];
            $checkname = $uarr[1];
            $ischeck = 0;
            $checkids = $checknames = '';

            $_su = '' . $rs['id'] . '';
            $nowshu = 0;
            if (isset($cufss[$_su])) $nowshu = $cufss[$_su];

            if (!$this->isempt($checkid)) {
                $checkida = explode(',', $checkid);
                $checkidna = explode(',', $checkname);
                $_chid = $_chna = '';

                foreach ($checkida as $k1 => $chkid) {
                    $_su1 = '' . $rs['id'] . '_' . $chkid . '';
                    if (!in_array($chkid, $allcheckids)) $allcheckids[] = $chkid;
                    if (!isset($chesarr[$_su1])) {
                        $_chid .= ',' . $chkid . '';
                        $_chna .= ',' . $checkidna[$k1] . '';
                    }
                }
                if ($_chid != '') $_chid = substr($_chid, 1);
                if ($_chna != '') $_chna = substr($_chna, 1);

                if ($_chid == '') {
                    $ischeck = 1;
                } else {
                    if ($checkshu > 0 && $nowshu >= $checkshu) $ischeck = 1;
                }
                $checkids = $_chid;
                $checknames = $_chna;
            } else {
                if ($checkshu > 0 && $nowshu >= $checkshu) $ischeck = 1;
                //需要全部审核时 同时已有审核过了 也没有审核人了
                if ($checkshu == 0 && $nowshu > 0) $ischeck = 1;
            }

            $rs['ischeck'] = $ischeck;
            $rs['islast'] = 0;
            $rs['checkid'] = $checkid;
            $rs['checkname'] = $checkname;
            $rs['nowcheckid'] = $checkids;
            $rs['nowcheckname'] = $checknames;
            $rs['isnow'] = 0;
            $rs['nowstep'] = $zongsetp;

            if ($ischeck == 0 && $nowstep == -1) {
                $rs['isnow'] = 1;
                $nowstep = $zongsetp;
                $this->nowcourse = $rs;    //当前审核步骤信息
                $nowcheckid = $checkids;
                $nowcheckname = $checknames;
            }

            if ($nowstep > -1 && $zongsetp == $nowstep + 1) $this->nextcourse = $rs; //下一步信息
            $this->flowarr[] = $rs;
        }
        //xin.zou判断前置节点信息获取是否正常，正常则赋值
        if (isset($this->flowarr[$nowstep - 1])) {
            $this->beforecourse = $this->flowarr[$nowstep - 1];
        }

        if ($zongsetp > -1) $this->flowarr[$zongsetp]['islast'] = 1;
        if ($nowstep == -1) {
            $isend = 1;
        } else {
            $nstatustext = '待' . $nowcheckname . '处理';
        }
        $this->flowisend = $isend;
        $allcheckid = join(',', $allcheckids);
        $arrbill['allcheckid'] = $allcheckid;
        $arrbill['nstatus'] = $nstatus;
        $rers = $this->db->getone('[Q]flow_bill', "`mid`='" . $this->rs['id'] . "' and `table`='$this->modenum'");
        if ($rers['status'] == 1) {
            $arrbill['nstatustext'] = '已审核';
            $arrbill['status'] = $rers['status'];
            $arrbill['courseid'] = $uparr['courseid'] = $this->nowcourse['id'];
            $arrbill['coursename'] = $uparr['coursename'] = '已审核';
        } else {

            $arrbill['nowcheckid'] = $nowcheckid;
            $arrbill['nowcheckname'] = $nowcheckname;
            $arrbill['nstatustext'] = $nstatustext;
            $arrbill['courseid'] = $uparr['courseid'] = $this->nowcourse['id'];
            $arrbill['coursename'] = $uparr['coursename'] = $this->nowcourse['name'];
            $arrbill['status'] = $this->rs['status'];
        }

        $tbook = m($this->modenum)->update($uparr, "`id`='$this->id'");
        if ($sbo) $this->getflowsave($arrbill);
        return $arrbill;
    }

    /**
     * @author xin.zou
     * @date 2017.03.16
     * 获取流程步凑前一节点id
     * @param $curs
     * @param $courseid
     * @return int
     */
    private function getBeforeCourseId($curs, $courseid)
    {
        $nowK = 0;
        foreach ($curs as $k => $rs) {
            if ($courseid == $rs['id']) {
                $nowK = $k;
                break;
            }
        }
        if (isset($curs[$nowK - 1])) {
            return $curs[$nowK - 1]['id'];
        }
        return 0;
    }

    private function wheremanzhu($id)
    {
        $ser = $this->wheremodel->getflowwhere($id, $this->adminid);
        if (!$ser) return true;
        $str = $ser['ntr'];
        if (!isempt($str)) {
            $to = $this->db->rows('[Q]admin', "`id`='$this->uid' and ($str)");
            if ($to > 0) return false;
        }
        $str = $ser['str'];
        if (!isempt($str)) {
            $to = $this->rows("`id`='$this->id' and $str");
            if ($to == 0) return false;
        }
        $str = $ser['utr'];
        if (!isempt($str)) {
            $to = $this->db->rows('[Q]admin', "`id`='$this->uid' and $str");
            if ($to == 0) return false;
        }
        return true;
    }

    public function getflowsave($sarr, $suvu = false)
    {
        if ($suvu) $sarr['updt'] = $this->rock->now;
        $this->billmodel->update($sarr, $this->mwhere);

    }

    //获取审核人
    private function getcheckname($crs)
    {
        $type = $crs['checktype'];
        $cuid = $name = '';
        $courseid = $crs['id'];
        if (!$this->isempt($crs['num'])) {
            $uarr = $this->flowcheckname($crs['num']);
            if (is_array($uarr)) {
                if (!$this->isempt($uarr[0])) return $uarr;
            }
        }

        $cheorws = $this->checksmodel->getall($this->mwhere . ' and courseid=' . $courseid . ' and `status`=0', 'checkid,checkname');
        if ($cheorws) {
            foreach ($cheorws as $k => $rs) {
                $cuid .= ',' . $rs['checkid'] . '';
                $name .= ',' . $rs['checkname'] . '';
            }
            if ($cuid != '') {
                $cuid = substr($cuid, 1);
                $name = substr($name, 1);
                return array($cuid, $name);
            }
        }

        if ($type == 'super') {
            $cuid = $this->urs['superid'];
            $name = $this->urs['superman'];
        }
        if ($type == 'dept' || $type == 'super') {
            if ($this->isempt($cuid)) {
                $cuid = $this->drs['headid'];
                $name = $this->drs['headman'];
            }
        }
        if ($type == 'apply') {
            $cuid = $this->urs['id'];
            $name = $this->urs['name'];
        }
        if ($type == 'opt') {
            $cuid = $this->rs['optid'];
            $name = $this->rs['optname'];
        }
        if ($type == 'user') {
            $cuid = $crs['checktypeid'];
            $name = $crs['checktypename'];
        }
        if ($type == 'rank') {
            $rank = $crs['checktypename'];
            if (!$this->isempt($rank)) {
                $rnurs = $this->db->getrows('[Q]admin', "`status`=1 and `ranking`='$rank'", 'id,name', 'sort');
                foreach ($rnurs as $k => $rns) {
                    $cuid .= ',' . $rns['id'] . '';
                    $name .= ',' . $rns['name'] . '';
                }
                if ($cuid != '') {
                    $cuid = substr($cuid, 1);
                    $name = substr($name, 1);
                }
            }
        }
        $cuid = $this->rock->repempt($cuid);
        $name = $this->rock->repempt($name);
        return array($cuid, $name);
    }

    /**
     *    创建编号
     */
    public function createbianhao($num, $fid)
    {
        if (isempt($num)) $num = '' . $this->modenum . '-';
        @$appdt = $this->rs['applydt'];
        if (isempt($appdt)) $appdt = $this->rock->date;
        $apdt = str_replace('-', '', $appdt);
        $num = str_replace('Ymd', $apdt, $num);
        return $this->db->sericnum($num, '[Q]' . $this->mtable . '', $fid, 3);
    }


    /**
     *    创建流程单号
     */
    public function createnum()
    {
        $num = $this->moders['sericnum'];
        if ($num == '无' || $this->isempt($num)) $num = 'TM-Ymd-';
        @$appdt = $this->rs['applydt'];
        if (isempt($appdt)) $appdt = $this->rock->date;
        $apdt = str_replace('-', '', $appdt);
        $num = str_replace('Ymd', $apdt, $num);
        return $this->db->sericnum($num, '[Q]flow_bill');
    }

    public function savebill($oarr = array())
    {
        //happy_add 新增字段到流程里面
        $dbs = $this->billmodel;
        $whes = $this->mwhere;
        $biid = (int)$dbs->getmou('id', $whes);
        $arr = array(
            'table' => $this->mtable,
            'mid' => $this->id,
            'optdt' => isset($this->rs['optdt']) ? $this->rs['optdt'] : $this->rock->now,
            'optname' => $this->adminname,
            'optid' => $this->adminid,
            'modeid' => $this->modeid,
            'updt' => $this->rock->now,
            'isdel' => '0',
            'nstatus' => $this->rs['status'],
            'applydt' => $this->rs['applydt'],
            'author' => $this->rs['author'],
            'designer' => $this->rs['designer'],
            'title' => $this->rs['title'],
            'num' => $this->rs['mdarea'],
            'chuban' => $this->rs['chuban'],
            'modename' => $this->modename
        );
        foreach ($oarr as $k => $v) $arr[$k] = $v;
        if ($biid == 0) {
            $arr['uid'] = $this->uid;
            $arr['createdt'] = $arr['optdt'];
            $arr['sericnum'] = $this->createnum();
            $whes = '';
            $this->sericnum = $arr['sericnum'];
        }
        //var_dump($arr);die;
        $dbs->record($arr, $whes);
        return $arr;
    }

    public function nexttodo($nuid, $type, $sm = '', $act = '')
    {
        //var_dump($this);die;
        $cont = '';
        $gname = '流程待办';/*
		if($type=='submit' || $type=='next'){
			$cont = '您有['.$this->adminname.']的['.$this->modename.',单号:'.$this->sericnum.']需要处理';
		}
		//退回
		if($type == 'nothrough'){
			$cont = '您提交['.$this->modename.',单号:'.$this->sericnum.']'.$this->adminname.'处理['.$act.']，原因:['.$sm.']';
			$gname= '流程申请';
		}
		if($type == 'finish'){
			$cont = '您提交的['.$this->modename.',单号:'.$this->sericnum.']已全部处理完成';
		}
		if($type == 'zhui'){
			$cont = '您有['.$this->adminname.']的['.$this->modename.',单号:'.$this->sericnum.']需要处理，追加说明:['.$sm.']';
		}*/
        if ($type == 'submit' || $type == 'next') {
            $cont = '您有业主[' . $this->rs['chuban'] . ']的[' . $this->rs['title'] . ']项目需要处理,备注:' . $sm . ',工长是' . $this->rs['author'];
        }
        //退回
        if ($type == 'nothrough') {

            $cont = '您提交的[' . $this->rs['chuban'] . ']的[' . $this->rs['title'] . ']项目处理[' . $act . '],备注:' . $sm . ',工长是' . $this->rs['author'];
            //$cont = '您提交['.$this->modename.',单号:'.$this->sericnum.']'.$this->adminname.'处理['.$act.']，原因:['.$sm.']';
            $gname = '流程申请';
        }
        if ($type == 'finish') {
            $cont = '您提交的[' . $this->rs['chuban'] . ']的[' . $this->rs['title'] . ']项目已全部处理完成,备注:' . $sm . ',工长是' . $this->rs['author'];
            //$cont = '您提交的['.$this->modename.',单号:'.$this->sericnum.']已全部处理完成';
        }
        if ($type == 'zhui') {
            $cont = '您有业主[' . $this->rs['chuban'] . ']的[' . $this->rs['title'] . ']项目需要处理,备注:' . $sm . ',工长是' . $this->rs['author'];
            //$cont = '您有['.$this->adminname.']的['.$this->modename.',单号:'.$this->sericnum.']需要处理，追加说明:['.$sm.']';
        }
        if ($cont != '') $this->push($nuid, $gname, $cont);
    }

    private function addcheckname($courseid, $uid, $uname)
    {
        $zyarr = array(
            'table' => $this->mtable,
            'mid' => $this->id,
            'modeid' => $this->modeid,
            'courseid' => $courseid,
            'optid' => $this->adminid,
            'optname' => $this->adminname,
            'optdt' => $this->rock->now,
            'status' => 0
        );
        $this->checksmodel->delete($this->mwhere . ' and `checkid`=' . $uid . ' and `courseid`=' . $courseid . '');
        $zyarr['checkid'] = $uid;
        $zyarr['checkname'] = $uname;


        $this->checksmodel->insert($zyarr);
    }


    /**
     *    判断保存的数据是否
     */
    public function savedatastr($fval, $farr, $data = array())
    {
        $str = '';
        if (!$farr) return $str;
        $savewhere = $farr['savewhere'];
        $name = $farr['name'];
        $types = $farr['fieldstype'];
        if (isempt($savewhere) || isempt($fval)) return $str;
        $savewhere = str_replace(array('{0}', '{date}', '{now}'), array($name, $this->rock->date, $this->rock->now), $savewhere);
        $savewhere = $this->rock->reparr($savewhere, $data);
        $saees = explode(',', $savewhere);
        if ($types == 'date' || $types == 'datetime') $fval = strtotime($fval);
        if ($types == 'number') $fval = floatval($fval);
        foreach ($saees as $saeess) {
            $fsaed = explode('|', $saeess);
            $msg = isset($fsaed[2]) ? $fsaed[2] : '' . $name . '数据不符号';
            $val = isset($fsaed[1]) ? $fsaed[1] : '';
            $lfs = $fsaed[0];
            if ($val != '') {
                if ($types == 'date' || $types == 'datetime') $val = strtotime($val);
                if ($types == 'number') $val = floatval($val);
                if ($lfs == 'gt') {
                    $bo = $fval > $val;
                    if (!$bo) return $msg;
                }
                if ($lfs == 'egt') {
                    $bo = $fval >= $val;
                    if (!$bo) return $msg;
                }
                if ($lfs == 'lt') {
                    $bo = $fval < $val;
                    if (!$bo) return $msg;
                }
                if ($lfs == 'elt') {
                    $bo = $fval <= $val;
                    if (!$bo) return $msg;
                }
                if ($lfs == 'eg') {
                    $bo = $fval == $val;
                    if (!$bo) return $msg;
                }
                if ($lfs == 'neg') {
                    $bo = $fval != $val;
                    if (!$bo) return $msg;
                }
            }
        }
        return $str;
    }

    /**
     *    处理
     */
    public function check($zt, $sm = '', $xgwj = '', $rgfeelist, $totalprice, $alltotal, $clupdatelist)
    {

        //20190319    工地处理完成之后，该工地对应的人工费status被修改为了1，所以人工费会报错。所以暂时注销此处代码
        // if ($this->rs['status'] == 1) $this->echomsg('流程已处理完成,无需操作');

        $arr = $this->getflow();
        $flowinfor = $this->getflowinfor();
        if ($flowinfor['ischeck'] == 0) {
            $this->echomsg('当前是[' . $arr['nowcheckname'] . ']处理');
        }
        $nowcourse = $this->nowcourse;
        $nextcourse = $this->nextcourse;
        $beforecourse = $this->beforecourse;//xin.zou 2017.03.16 获取指定人信息
        $zynameid = $this->rock->post('zynameid');
        $zyname = $this->rock->post('zyname');
        $nextname = $this->rock->post('nextname');
        $nextnameid = $this->rock->post('nextnameid');
        $iszhuanyi = $ischangenext = 0;
        if ($zt == 1 && $this->rock->arrvalue($nextcourse, 'checktype') == 'change') {
            if ($nextnameid == '') $this->echomsg('请选择下一步处理人');
            $ischangenext = 1;
        }
        if ($zynameid != '' && $zt == 1) {
            if ($zynameid == $this->adminid) $this->echomsg('不能转给自己');
            if ($sm != '') $sm .= ',';
            $sm .= '转给：' . $zyname . '';
            $iszhuanyi = 1;
        }
        $ufied = array();
        if ($iszhuanyi == 0 && $zt == 1) {
            foreach ($flowinfor['checkfields'] as $chef => $chefv) {
                $ufied[$chef] = $this->rock->post('cfields_' . $chef . '');
                if (isempt($ufied[$chef])) $this->echomsg('' . $chefv['name'] . '不能为空');
                $_str = $this->savedatastr($ufied[$chef], $chefv['fieldsarr'], $this->rs);
                if ($_str != '') $this->echomsg($_str);
            }
        }
        $barr = $this->flowcheckbefore($zt, $ufied, $sm);
        $msg = '';
        if (is_array($barr) && isset($barr['msg'])) $msg = $barr['msg'];
        if (is_string($barr)) $msg = $barr;
        if (!isempt($msg)) $this->echomsg($msg);

        if ($ufied) {
            $bo = $this->update($ufied, $this->id);
            if (!$bo) $this->echomsg('dberr:' . $this->db->error());
        }

        $courseact = $flowinfor['courseact'];
        $act = $courseact[$zt];
        $courseid = $nowcourse['id'];

        //xin.zou 2017.03.16 如果是退回指定人节点是   指定人选择不通过 将删除指定人信息
        if (2 == $zt) {
            $this->checksmodel->delete($this->mwhere . ' and `checkid`=' . $this->adminid . ' and `courseid`=' . $courseid . '');
        }

        if ($iszhuanyi == 1) {
            $this->addcheckname($courseid, $zynameid, $zyname);
            $nowcourse['id'] = 0;
        }
        if ($ischangenext == 1) {
            $_nesta = explode(',', $nextnameid);
            $_nestb = explode(',', $nextname);
            foreach ($_nesta as $_i => $_nes) $this->addcheckname($nextcourse['id'], $_nesta[$_i], $_nestb[$_i]);
        }

        $this->addlog(array(
            'courseid' => $nowcourse['id'],
            'name' => $nowcourse['name'],
            'status' => $zt,
            'statusname' => $act[0],
            'color' => $act[1],
            'explain' => $sm,
            'rgfeelist' => $rgfeelist,
            'clupdatelist' => $clupdatelist,
            'totalprice' => $totalprice,
            'alltotal' => $alltotal,
            'fileid' => $xgwj,
        ));

        $uparr = array();
        $bsarr = $this->getflow();

        if ($zt == 1) {
            $nextcheckid = $bsarr['nowcheckid'];
            $uparr['status'] = 0;
            $bsarr['status'] = 0;
            $this->nexttodo($nextcheckid, 'next', $sm, $act[0]);
        } else if ($zt == 2) {
            if (empty($this->beforecourse)) {
                $checkid = $nowcourse['checkid'];
            } else {
                $checkid = $beforecourse['checkid'];
            }
            $bsarr['status'] = 0;
            $uparr['status'] = $zt;
            $this->nexttodo($checkid, 'nothrough', $sm, $act[0]);
        }
        $this->flowcheckafter($zt, $sm);

        $bsarr['nstatus'] = $zt;
        $bsarr['checksm'] = $sm;

        if (!$this->nowcourse) {//没有当前步骤就是结束完成了
            $uparr['status'] = $zt;
            $bsarr['status'] = $zt;
            $this->nexttodo($this->optid, 'finish', $sm);
            $this->flowcheckfinsh($zt);
        }

        if ($uparr) {
            $this->update($uparr, $this->id);
            foreach ($uparr as $k => $v) $this->rs[$k] = $v;
        }
        $this->getflowsave($bsarr, true);

        /*happy_add  修改项目进度*/
        /*xin.zou start  根据审核状态执行指定节点数据组织*/
        if (1 == $zt) {
            //happpy_eeee
            $nnnid = $nextcourse['id'];
            $upa4rr = array(
                'courseid' => $nnnid,
                'coursename' => $nextcourse['name']);
        } elseif (2 == $zt) {
            if (empty($this->beforecourse)) {
                $nnnid = $courseid;
                $upa4rr = array(
                    'courseid' => $nnnid,
                    'coursename' => $nowcourse['name']);
            } else {
                $nnnid = $courseid - 1;
                $upa4rr = array(
                    'courseid' => $nnnid,
                    'coursename' => $beforecourse['name']);
            }
        }
        /*xin.zou end  根据审核状态执行指定节点数据组织*/
        $tbook = m($this->modenum)->update($upa4rr, "`id`='$this->id'");
        $where = array(
            'mid' => $this->id,
            'modeid' => $this->modeid);
        $tbook = m('flow_bill')->update($upa4rr, $where);
        return '处理成功';
    }


    public function push($receid, $gname = '', $cont, $title = '', $wkal = 0)
    {
        if ($this->isempt($receid) && $wkal == 1) $receid = 'all';
        if ($this->isempt($receid)) return false;
        if ($gname == '') $gname = $this->modename;
        $reim = m('reim');
        $url = '' . URL . 'task.php?a=p&num=' . $this->modenum . '&mid=' . $this->id . '';
        $wxurl = '' . URL . 'task.php?a=x&num=' . $this->modenum . '&mid=' . $this->id . '';
        $emurl = '' . URL . 'task.php?a=a&num=' . $this->modenum . '&mid=' . $this->id . '';
        if ($this->id == 0) {
            $url = '';
            $wxurl = '';
            $emurl = '';
        }
        $slx = 0;
        $pctx = $this->moders['pctx'];
        $mctx = $this->moders['mctx'];
        $wxtx = $this->moders['wxtx'];
        $emtx = $this->moders['emtx'];
        if ($pctx == 0 && $mctx == 1) $slx = 2;
        if ($pctx == 1 && $mctx == 0) $slx = 1;
        if ($pctx == 0 && $mctx == 0) $slx = 3;
        $cont = $this->rock->reparr($cont, $this->rs);
        if (contain($receid, 'u') || contain($receid, 'd')) $receid = m('admin')->gjoin($receid);
        m('todo')->addtodo($receid, $this->modename, $cont, $this->modenum, $this->id);
        $reim->pushagent($receid, $gname, $cont, $title, $url, $slx);


        if ($title == '') $title = $this->modename;
        //happy_add 短信通知
        m('sms')->postsms($title, $cont, $receid);
        //邮件提醒发送不发送全体人员的，太多了
        if ($emtx == 1 && $receid != 'all') {
            $emcont = '您好：<br>' . $cont . '(邮件由系统自动发送)';
            if ($emurl != '') {
                $emcont .= '<br><a href="' . $emurl . '" target="_blank" style="color:blue"><u>详情&gt;&gt;</u></a>';
            }
            m('email')->sendmail($title, $emcont, $receid);
        }

        if ($wxtx == 1 && $reim->isanwx()) {
            $wxarra = $this->flowweixinarr;
            $wxarr = array(
                'title' => $title,
                'description' => $cont,
                'url' => $wxurl
            );
            foreach ($wxarra as $k => $v) $wxarr[$k] = $v;
            m('weixin:index')->sendnews($receid, '' . $gname . ',0', $wxarr);
            $this->flowweixinarr = array();
        }
    }

    public function getwxurl($num = '')
    {
        if ($num == '') $num = $this->modenum;
        $str = '' . URL . '?m=ying&d=we&num=' . $num . '';
        return $str;
    }

    public function deletebill($sm = '', $qxpd = true)
    {
        if ($qxpd) {
            $is = $this->isdeleteqx();
            if ($is == 0) return '无权删除';
        }
        m('flow_log')->delete($this->mwhere);
        m('reads')->delete($this->mwhere);
        m('file')->delfiles($this->mtable, $this->id);
        $tables = $this->moders['tables'];
        if (!isempt($tables)) {
            $arrse = explode(',', $tables);
            foreach ($arrse as $arrses) m($arrses)->delete('mid=' . $this->id . '');
        }
        $this->billmodel->delete($this->mwhere);
        $this->delete($this->id);
        $this->flowdeletebill($sm);
        return 'ok';
    }

    /**
     *    单据作废处理
     */
    public function zuofeibill($sm = '')
    {
        $this->update('`status`=5', $this->id);
        $zfarr = array(
            'status' => 5,
            'nstatus' => 5,
            'checksm' => '作废：' . $sm . '',
            'nowcheckid' => '',
            'nowcheckname' => '',
            'nstatustext' => '作废',
            'updt' => $this->rock->now,
        );
        $this->billmodel->update($zfarr, $this->mwhere);
        $this->flowzuofeibill($sm);
        return 'ok';
    }


    /*
	*	获取操作菜单
	*/
    public function getoptmenu($flx = 0)
    {
        $rows = $this->db->getrows('[Q]flow_menu', "`setid`='$this->modeid' and `status`=1", 'id,wherestr,name,statuscolor,statusvalue,num,islog,issm,type', '`sort`');
        $arr = array();
        foreach ($rows as $k => $rs) {
            $wherestr = $rs['wherestr'];
            $bo = false;
            if (isempt($wherestr)) {
                $bo = true;
            } else {
                $ewet = m('where')->getstrwhere($this->rock->jm->base64decode($wherestr));
                $tos = $this->rows("`id`='$this->id' and $ewet");
                if ($tos > 0) $bo = true;
            }
            $rs['lx'] = $rs['type'];
            $rs['optnum'] = $rs['num'];
            if (!isempt($rs['num'])) {
                $glx = $this->flowgetoptmenu($rs['num']);
                if (is_bool($glx)) $bo = $glx;
            }
            $rs['optmenuid'] = $rs['id'];
            if (!isempt($rs['statuscolor'])) $rs['color'] = $rs['statuscolor'];
            unset($rs['id']);
            unset($rs['num']);
            unset($rs['wherestr']);
            unset($rs['type']);
            unset($rs['statuscolor']);
            if ($bo) $arr[] = $rs;
        }
        //var_dump($arr);die();

        //happy_add 如果是材料供应商赋予处理权限
        if ($this->modenum == 'clpaifa') {
            $hh = $this->rs['type'] == 0 ? '配送' : '退货';
            if (($this->rs['status'] == 0 || $this->rs['status'] == 2) && $this->rs['clgysid'] == $this->adminid) {
                $arr[] = array('name' => '<b>材料' . $hh . '...</b>', 'color' => '#1abc9c', 'lx' => 9301);
            } else {
                $arr[] = array('name' => '<b>材料' . $hh . '详情...</b>', 'color' => '#1abc9c', 'lx' => 9328);
            }
        } else if ($this->modenum == 'buildin') {
            $hh = $this->rs['type'] == 0 ? '测量' : '取消';
            if (($this->rs['status'] == 0 || $this->rs['status'] == 2) && $this->rs['clgysid'] == $this->adminid) {
                // $arr[] = array('name'=>'<b>安装'.$hh.'...</b>','color'=>'#1abc9c','lx'=>9301);
                $arr[] = array('name' => '<b>详情...</b>', 'color' => '#1abc9c', 'lx' => 9301);
            } else {
                // $arr[] = array('name'=>'<b>安装'.$hh.'详情...</b>','color'=>'#1abc9c','lx'=>9328);
                $arr[] = array('name' => '<b>详情...</b>', 'color' => '#1abc9c', 'lx' => 9328);
            }

        }
        if ($this->isflow == 1) {/*
			if(($this->rs['status'] == 0 || $this->rs['status'] == 1) && $this->uid == $this->adminid){
				$arr[] = array('name'=>'追加说明...','lx'=>1,'optmenuid'=>-12);
			}*/
            //happy_add 赋予所有人追加说明权限
            $arr[] = array('name' => '工作日志...', 'lx' => 1, 'optmenuid' => -12);
            if (isset($this->rs['rgfeeid']) && $this->rs['rgfeeid'] > 0) {
                if ($this->modenum == 'yzjuzhuang') {
                    $isdel = $this->db->getone('[Q]jzrgfee', "`id`='" . $this->rs['rgfeeid'] . "'", 'id');
                }else{
                    $isdel = $this->db->getone('[Q]rgfee', "`id`='" . $this->rs['rgfeeid'] . "'", 'id');
                }
                //判断人工费有没有真实存在
                if ($isdel) {
                    //获取有权限的ID
                    $rgfeeuserid = getconfig('rgfeeuserid');
                    $isin = in_array($this->adminid, $rgfeeuserid);
                    $authorid = $this->db->getone('[Q]admin', "`name`='" . $this->rs['author'] . "'", 'id');
                    if ($isin || $this->adminid == $authorid['id']) {
                        $arr[] = array('name' => '<b>工费说明...</b>', 'color' => '#1abc9c', 'lx' => 1, 'optmenuid' => -34);
                        $arr[] = array('name' => '<b>工费详情...</b>', 'color' => '#1abc9c', 'lx' => 9393);
                    }
                }

            }
            $chearr = $this->getflowinfor();
            if ($chearr['ischeck'] == 1) {
                $arr[] = array('name' => '<b>去处理单据...</b>', 'color' => '#1abc9c', 'lx' => 996);
                if (1 == 2) foreach ($chearr['courseact'] as $zv => $dz) {
                    if ($zv > 0) {
                        $assar = array('name' => $dz[0], 'color' => $dz[1], 'optnum' => 'check', 'issm' => 1, 'islog' => 0, 'statusvalue' => $zv, 'lx' => '10', 'optmenuid' => -10);
                        if ($zv == 1) $assar['issm'] = 0;
                        $arr[] = $assar;
                    }
                }
            }
        }
        //var_dump($arr);die();

        if ($this->iseditqx() == 1) {
            $arr[] = array('name' => '编辑', 'optnum' => 'edit', 'lx' => '11', 'optmenuid' => -11);
        }

        if ($this->isdeleteqx() == 1) {
            $arr[] = array('name' => '删除', 'color' => 'red', 'optnum' => 'del', 'issm' => 0, 'islog' => 0, 'statusvalue' => 9, 'lx' => '9', 'optmenuid' => -9);
        }
        $userdata = m('admin')->getinfor($this->adminid);
        
        //happy_add 如果是客户管理。新增状态变更菜单 20190904
        if ($this->modenum == 'customer' && in_array($userdata['deptid'],getconfig('kehuglDeptid')) && $this->adminid != getconfig('clskefuid')) {
            $btn = array('name' => '<b>状态变更</b>', 'color' => '#1abc9c', 'lx' => 1234, 'optmenuid' => -34, "num" => "changeStatus");
            $btn['userType']=$this->adminid == getconfig('kehuglDeptid')?'kf':'';
            $arr[] = $btn;
        } 
        if ((false !== strpos($userdata['unitname'], "供应商")||$this->adminid == getconfig('clskefuid')) && 7 == $this->modeid && !empty($this->rs['shateid'])) {
            $btn = array('name' => '<b>状态变更</b>', 'color' => '#1abc9c', 'lx' => 123, 'optmenuid' => -34, "num" => "changeStatus");
            $btn['userType']=$this->adminid == getconfig('clskefuid')?'kf':'';
            $arr[] = $btn;
        }
        return $arr;
    }

    /**
     *    操作菜单操作
     */
    public function optmenu($czid, $zt, $sm = '')
    {
        $msg = '';
        $cname = $this->rock->post('changename');
        $cnameid = $this->rock->post('changenameid');
        //拜访记录添加文件      1222
        $fileid = $this->rock->post('file');
        $cdate = $this->rock->post('changedate');
        if ($czid == -9) {
            $msg = $this->deletebill($sm);
        } else if ($czid == -10) {
            $msg = $this->check($zt, $sm);
            if (contain($msg, '成功')) $msg = 'ok';
        } else if ($czid == -12 || $czid == -34) {
            $this->zhuijiaexplain($sm, $czid);
        } else {
            //感觉是拜访记录处理 happy_add 20180320


            $ors = m('flow_menu')->getone("`id`='$czid' and `setid`='$this->modeid' and `status`=1");
            if (!$ors) return '菜单不存在';
            $name = str_replace('.', '', $ors['name']);
            $actname = $ors['actname'];
            if (isempt($actname)) $actname = $name;
            if ($ors['islog'] == 1) {
                if (!isempt($cname)) {
                    if (!isempt($sm)) $sm .= ',';
                    $sm .= '' . $name . ':' . $cname . '';
                }
                $this->addlog(array(
                    'explain' => $sm,
                    'name' => $actname,
                    'fileid' => $fileid,
                    'statusname' => $ors['statusname'],
                    'status' => $ors['statusvalue'],
                    'color' => $ors['statuscolor']
                ));
            }
            $barrs = array(
                'cname' => $cname,
                'sm' => $sm,
                'cnameid' => $cnameid,
                'cdate' => $cdate
            );
            if ($ors['type'] == 4 && !isempt($ors['fields'])) {
                $fielsa = explode(',', $ors['fields']);
                $uarrs = array();
                foreach ($fielsa as $fielsas) {
                    $fsdiwe = 'fields_' . $fielsas . '';
                    if (isset($_REQUEST[$fsdiwe])) {
                        $uarrs[$fielsas] = $this->rock->post($fsdiwe);
                        $barrs[$fsdiwe] = $uarrs[$fielsas];
                    }
                }
                if ($uarrs) $this->update($uarrs, $this->id);
            }
            $upgcont = $ors['upgcont'];
            if (!isempt($upgcont)) {
                $upgcont = $this->rock->jm->base64decode($upgcont);
                $upgcont = str_replace(array('{now}', '{date}', '{adminid}', '{admin}', '{sm}', '{cname}', '{cnameid}'), array($this->rock->now, $this->rock->date, $this->adminid, $this->adminname, $sm, $cname, $cnameid), $upgcont);
                $this->update($upgcont, $this->id);
            }
            $this->flowoptmenu($ors, $barrs);


            //step1：判断是不是设计师或者客服 deptid==14
            $userdata = m('admin')->getinfor($this->adminid);
            $designdeptid = explode(',', getconfig('designdetpid'));
            $shichangdetpid = explode(',', getconfig('shichangdetpid'));
            $kefudeptid = getconfig('kefudeptid');
            $clsdeptid = getconfig('clsdeptid');
            $clskefuid = getconfig('clskefuid');

            //step2：找到设计师 deptid==14
            //设计师回访信息添加日志时自动消息提醒复客服
            if (in_array($userdata['deptid'], $designdeptid)) {
                //happy_add 短信通知 史节法和监理
                $title = '设计师回访信息添加日志时自动消息提醒复客服';
                $kfmanager = '';
                if ($this->rs['kfmanager']) {
                    $kfmanager = $this->rs['kfmanager'];
                    //新增通知市场部 20180411
                    //短信互相通知 ，设计师写工作日志的时候，市场部组长可以收到短信，
                    if ($this->rs['marker']) {
                        $kfmanager .= "','" . $this->rs['marker'] . "";
                    }
                }
                $cont = '您有一条[' . $this->rs['address'] . '][' . $userdata['name'] . ']的拜访记录:' . $sm;
                if ($cont != '') m('sms')->postsms2($cont, $kfmanager);

            } else if (in_array($userdata['deptid'], $kefudeptid)) {
                //客服访信息添加日志时，自动消息提醒设计师
                $title = '客户回访信息添加日志时，自动消息提醒设计师';
                $designer = '';
                if ($this->rs['gddesigner']) {
                    $designer .= $this->rs['gddesigner'];
                    if ($this->rs['rzdesigner']) {
                        $designer .= "','" . $this->rs['rzdesigner'] . "";
                    }
                } else if ($this->rs['rzdesigner']) {
                    $designer = $this->rs['rzdesigner'];
                }
                $cont = '您有一条[' . $this->rs['address'] . '][' . $userdata['name'] . ']的拜访记录:' . $sm;
                if ($cont != '') m('sms')->postsms2($cont, $designer);
            } else if (in_array($userdata['deptid'], $shichangdetpid)) {
                //市场部写日志的时候，设计师也可以收到短信
                $title = '市场部写日志的时候，设计师也可以收到短信';
                $designer = '';
                if ($this->rs['gddesigner']) {
                    $designer .= $this->rs['gddesigner'];
                    if ($this->rs['rzdesigner']) {
                        $designer .= "','" . $this->rs['rzdesigner'] . "";
                    }
                } else if ($this->rs['rzdesigner']) {
                    $designer = $this->rs['rzdesigner'];
                }
                $cont = '您有一条[' . $this->rs['address'] . '][' . $userdata['name'] . ']的拜访记录:' . $sm;
                if ($cont != '') m('sms')->postsms2($cont, $designer);
            } else if (in_array($userdata['deptid'], $clsdeptid)) {
                //供应商写日志的时候，通知客服
                $title = '供应商写日志的时候，通知客服';                
                $cont = '您有一条[' . $this->rs['address'] . '][' . $userdata['name'] . ']的拜访记录:' . $sm;
                if ($cont != '') m('sms')->postsms2($cont, $clskefuid);
            }else if ($this->adminid == $clskefuid) {
                //客服写日志的时候，通知供应商
                $title = '客服写日志的时候，通知供应商';        

                $shateid = explode(',', $this->rs['shate']);
                foreach ($shateid as $kjl => $vjl) {
                    $shateid .= "','" . $vjl . "";
                }        
                $cont = '您有一条[' . $this->rs['address'] . '][' . $userdata['name'] . ']的拜访记录:' . $sm;
                if ($cont != '') m('sms')->postsms2($cont, $shateid);
            }
        }
        if ($msg == '') $msg = 'ok';
        return $msg;
    }

    /**
     *    单据展示条件搜索
     */
    public function billwhere($uid, $lx)
    {
        $arr['table'] = $this->mtable;
        $arr['fields'] = '';
        $arr['order'] = '';
        $nas = $this->flowbillwhere($uid, $lx);
        $inwhere = '';
        if (substr($lx, 0, 5) == 'grant') {
            $inwhere = $this->viewmodel->viewwhere($this->moders, $this->adminid, $this->flowviewufieds);
        }
        $_wehs = '';
        if (is_array($nas)) {
            if (isset($nas['where'])) $_wehs = $nas['where'];
            if (isset($nas['order'])) $arr['order'] = $nas['order'];
            if (isset($nas['fields'])) $arr['fields'] = $nas['fields'];
            if (isset($nas['table'])) $arr['table'] = $nas['table'];
        } else {
            $_wehs = $nas;
        }
        $arr['where'] = $inwhere . ' ' . $_wehs;
        return $arr;
    }

    public function getflowrows($uid, $lx, $limit = 5)
    {
        $nas = $this->billwhere($uid, $lx);
        $table = $nas['table'];
        if (!contain($table, ' ')) $table = '[Q]' . $table . '';
        $rows = $this->db->getrows($table, '1=1 ' . $nas['where'] . '', $nas['fields'], $nas['order'], $limit);

        return $rows;
    }


    /**
     *    打印导出
     */
    public function printexecl($event)
    {
        $arr['moders'] = $this->moders;
        $arr['fields'] = $this->getfields();
        $cell = 1;
        foreach ($arr['fields'] as $k => $v) $cell++;
        $arr['cell'] = $cell;

        $where = '1=1';
        $str1 = $this->moders['where'];
        if (!isempt($str1)) {
            $str1 = $this->rock->covexec($str1);
            $where = $str1;
        }

        $vwhere = $this->viewmodel->viewwhere($this->moders, $this->adminid);
        $rows = $this->getrows('' . $where . ' ' . $vwhere . '', '*', 'id desc', 100);
        $arr['rows'] = $this->flowprintrows($rows);
        $arr['count'] = $this->db->count;
        return $arr;
    }
}