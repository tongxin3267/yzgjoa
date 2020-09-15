<?php if(!defined('HOST'))die('not access');?>
<script src="web/res/mui/js/mui.min.js"></script>
<script>
var mianji='<?php echo json_encode($data['mianji']); ?>';
var huxing='<?php echo json_encode($data['huxing']); ?>';
var zxstyle='<?php echo json_encode($data['zxstyle']); ?>';
var huxing=JSON.parse(huxing);
var mianji=JSON.parse(mianji);
var zxstyle=JSON.parse(zxstyle);
</script>
<script type="text/javascript" src="web/res/css/sh.js"></script> 
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype,chengsuid='';
	var columns=[{
			text:'',dataIndex:'caozuo'
		},{
			text:'工作日志',dataIndex:'record'
		},{
			text:'渠道',dataIndex:'laiyuan'
		},{
			text:'ID',dataIndex:'mobile'
		},{
			text:'品牌',dataIndex:'yzbrand'
		},{
			text:'客户姓名',dataIndex:'name'
		},{
			text:'联系方式',dataIndex:'tel'
		},{
			text:'小区名称',dataIndex:'address'
		},{
			text:'面积',dataIndex:'unitname'
		},{
			text:'户型',dataIndex:'linkname'
		},{
			text:'装修时间',dataIndex:'zxdate',sortable:true
		},{
			text:'硬装设计师',dataIndex:'gddesigner'
		},{
			text:'硬装顾问',dataIndex:'marker'
		},{
			text:'硬装状态',dataIndex:'status',sortable:true
		},{
			text:'软装设计师',dataIndex:'rzdesigner'
		},{
			text:'软装顾问',dataIndex:'rzmarker'
		},{
			text:'软装状态',dataIndex:'rzstatus',sortable:true
		},{
			text:'竞争公司',dataIndex:'compet'
		},{
			text:'共享给',dataIndex:'shate'
		}];

	//come from indexaction --->globalbody()---(webmain/index/tpl_index)   初始化  happy_add
	if (deptname=='终端客户') {
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#laiyuan{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		
		//$('#list').remove();
	}
	// console.log(deptname);

	if (isContains(deptallname, '供应商') || isContains(deptallname, '供货商')) {
		$('#designer{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		//crm
		$('#shichang{rand}').addClass('hide');
		$('#laiyuan{rand}').addClass('hide');
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#brandRecord{rand}').addClass('hide');
		//crm
		//$('#status{rand}').addClass('hide');
		columns=[{
			text:'',dataIndex:'caozuo'
		},{
			text:'工作日志',dataIndex:'record'
		},{
			text:'渠道',dataIndex:'laiyuan'
		},{
			text:'ID',dataIndex:'mobile'
		},{
			text:'品牌',dataIndex:'yzbrand'
		},{
			text:'客户姓名',dataIndex:'name'
		},{
			text:'状态',dataIndex:'supplier_status',sortable:true
		},{
			text:'联系方式',dataIndex:'tel'
		}];
	}


	function isContains(str, substr) { 
	    return str.indexOf(substr) >= 0;
	}
	if (isContains(deptallname, '设计部')||deptname=='工程监理部'||deptname.indexOf("市场部")>=0) {
		$('#designer{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		//crm
		$('#shichang{rand}').addClass('hide');
		$('#laiyuan{rand}').addClass('hide');
	}
	if (deptname=='预算部'||deptname=='形象建设部') {
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		//crm
		$('#shichang{rand}').addClass('hide');

	}
	if(adminname=='陈海萍'){
		$('#shichang{rand}').removeClass('hide');
	}
	if (deptname=='人事&材料部') {
		$('#designer{rand}').addClass('hide');
	}
	if (deptname=='门店') {
		//crm
		$('#laiyuan{rand}').addClass('hide');
	}
	if (deptname=='管理层'||deptname=='客服部') {
		//crm
		$('.xzkhdiv{rand}').removeClass('hide');
		$('#main{rand}').removeClass('hide');
		//$('#main2').addClass('hide');
		columns=[{
			text:'',dataIndex:'caozuo'
		},{
			text:'工作日志',dataIndex:'record'
		},{
			text:'渠道',dataIndex:'laiyuan'
		},{
			text:'ID',dataIndex:'mobile'
		},{
			text:'品牌',dataIndex:'yzbrand'
		},{
			text:'客户姓名',dataIndex:'name'
		},{
			text:'联系方式',dataIndex:'tel'
		},{
			text:'小区名称',dataIndex:'address'
		},{
			text:'面积',dataIndex:'unitname'
		},{
			text:'户型',dataIndex:'linkname'
		},{
			text:'装修时间',dataIndex:'zxdate',sortable:true
		},{
			text:'硬装设计师',dataIndex:'gddesigner'
		},{
			text:'硬装顾问',dataIndex:'marker'
		},{
			text:'硬装状态',dataIndex:'status',sortable:true
		},{
			text:'软装设计师',dataIndex:'rzdesigner'
		},{
			text:'软装顾问',dataIndex:'rzmarker'
		},{
			text:'软装状态',dataIndex:'rzstatus',sortable:true
		},{
			text:'竞争公司',dataIndex:'compet'
		},/*{
			text:'备注',dataIndex:'remark'
		},*/{
			text:'共享给',dataIndex:'shate'
		}];
	}
	var a = $('#view_{rand}').bootstable({
		tablename:'customer',params:{'atype':atype},fanye:true,modenum:'customer',celleditor:false,checked:atype=='my',
		columns:columns,
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
		var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('loadbookdatacourse','flow','main',{'setid':'45'}),
		columns:[{
			text:'项目分类',dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
		},{
			text:'ID',dataIndex:'id',width:'20%'
		}],
		load:function(d){			
			var ds=$('#design_{rand}');
			$.each(d.design, function (index, obj) {
				var option = $("<option>").val(obj.name).text(obj.name);	
	      		ds.append(option);
            });		
			var sc=$('#shichang_{rand}');
			$.each(d.shichang, function (index, obj) {
				var option = $("<option>").val(obj.name).text(obj.name);	
	      		sc.append(option);
            });	
			var ly=$('#laiyuanRecord{rand}');
			$.each(d.laiyuan, function (index, obj) {
				var option = $("<option>").val(obj).text(obj);	
	      		ly.append(option);
            });	
			var hx=$('#linkname{rand}');
			$.each(d.huxing, function (index, obj) {
				var option = $("<option>").val(obj).text(obj);	
	      		hx.append(option);
            });	
		}
	});;
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('客户','customer',d.id);
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			//var as = ['all','qy','ting','stat'];
// happy_add 
			var as = ['all','td','qdz','yqd','lf','zxwc','stat'];
			a.setparams({'atype':atype+'_'+as[lx]},true);
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('客户', 'customer',id);
		},
		move:function(){
			var s= a.getchecked();
			if(s==''){js.msg('msg','没有选择记录');return;}
			chengsuid=s;
			js.confirm('是否客户转移给其他人，并客户下的合同和待收付款单和销售机会同时转移？', function(jg){
				if(jg=='yes')c.moveto();
			});
		},
		movetoss:function(sna,toid){
			js.ajax(js.getajaxurl('movecust','{mode}','{dir}'),{'toid':toid,'sid':chengsuid},function(s){
				a.reload();
			},'post',false,'转移给:'+sna+'...,转移成功');
		},
		moveto:function(sid){
			var cans = {
				type:'user',
				title:'转移给...',
				callback:function(sna,sid){
					if(sid)c.movetoss(sna,sid);
				}
			}
			setTimeout(function(){js.getuser(cans);},10);
		},
		search:function(){
			var s=get('key_{rand}').value;
			var date=get('dt1_{rand}').value;
			var date2=get('dt2_{rand}').value;
			if (date>date2) {alert('结束时间应大于开始时间'); return false;}
			var laiyuanRecord=get('laiyuanRecord{rand}').value;
			var linkname=get('linkname{rand}').value;
			var brandRecord=get('brandRecord{rand}').value;
			var status=get('status{rand}').value;
			var dl=get('design_{rand}').value;
			var sc=get('shichang_{rand}').value;
			var areaRecord=get('areaRecord{rand}').value;
			if (areaRecord=="全部") {
				var area=get('area{rand}').value;
					areaRecord=list[0].city[area].name=='全部'?'':list[0].city[area].name;
			}
			a.setparams({key:s,timeRecord:date,timeRecord2:date2,laiyuanRecord:laiyuanRecord,unitnameRecord:linkname,status:status,areaSearch:areaRecord,desginRecord:dl,shichangRecord:sc,brandRe:brandRecord},true);
		},
		daoru:function(){
			custmanagesss = a;
			addtabs({num:'customeraddpl',url:'main,customer,addpl',name:'导入客户'});
		},
		//弹框关闭
		close:function(){
			$('.tk-bg').hide();
			$('.tk-content').hide();
			$('.tk-head').hide();
		},

	};
	js.initbtn(c);
	
	if(atype!='my'){
		$('#move_{rand}').remove();
		$('#btnbnts_{rand}').remove();
		$('#drubtn_{rand}').remove();
	}
	
});
</script>
<div>
	<table width="100%">
	<tr>
<td width="220" style="display:none">
	<div style="border:1px #cccccc solid">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	  <div  class="panel-footer">
		<a href="javascript:" click="clicktypewin,0" onclick="return false"><i class="icon-plus"></i></a>&nbsp; &nbsp; 
		<a href="javascript:" click="clicktypeeidt" onclick="return false"><i class="icon-edit"></i></a>&nbsp; &nbsp; 
		<a href="javascript:" click="typedel" onclick="return false"><i class="icon-trash"></i></a>&nbsp; &nbsp; 
		<a href="javascript:" click="reload" onclick="return false"><i class="icon-refresh"></i></a>
	  </div>
	</div>  
</td>
	<td id="btnbnts_{rand}" style="padding-right:10px" >
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="分配日期起" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="分配日期止" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td>
		<input class="form-control" style="width:120px" id="key_{rand}"   placeholder="ID/客户姓名/联系方式">
	</td>
		<td style="padding-left:10px" id="brand{rand}">
			<select id="brandRecord{rand}"  >
			  <option value="">品牌</option>
			  <option value="0">元贞国际设计</option>
			  <option value="1">贞筑豪宅装饰</option>
			  <option value="2">梦依达软装馆</option>
			  <option value="3">域嘉</option>
			  <option value="4">元贞局装</option>
			</select>
		</td>
		<td style="padding-left:10px">	
			<div class="block AreaCenter" style="width: 40%;">		
			<select id="area{rand}">
				<option value="" selected>区域</option>
			</select></div>
		</td>
		<td >
			<div class="block AreaRight" style="display: block;">
			<select id="areaRecord{rand}">
				<option value="" selected>请选择</option>
			</select></div>		
			
		</td>
		<td style="padding-left:10px" id="designer{rand}">
			<select id="design_{rand}"  >
			  <option value="">设计师</option>
			</select>
		</td>
		<td style="padding-left:10px" id="shichang{rand}">
			<select id="shichang_{rand}"  >
			  <option value="">市场顾问</option>
			</select>
		</td>
		<td style="padding-left:10px" id="laiyuan{rand}">
			<select id="laiyuanRecord{rand}"  >
			  <option value="">渠道</option>
			</select>
		</td>
		<td style="padding-left:10px"  id="huxing{rand}" >
			<select id="linkname{rand}"  >
			  <option value="">户型</option>
			</select>
		</td>
		<td style="padding-left:10px">
			<select id="status{rand}"  >
			  <option value="">状态</option>
			  <option value="0">待量单</option>
			  <option value="1">无效单</option>
			  <option value="2">已退单</option>
			  <option value="3">重单</option>
			  <option value="4">跟进单</option>
			  <option value="5">意向单</option>
			  <option value="6">失败单</option>
			  <option value="7">已签单</option>
			  <option value="8">待定单</option>
			</select>
		</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
	<td  width="90%" style="padding-left:10px">
<!-- // happy_add --> 
		<div id="stewwews{rand}" class="btn-group hide">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">全部状态</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">退单</button>
		<button class="btn btn-default" id="state{rand}_2" click="changlx,2" type="button">签单中</button>
		<button class="btn btn-default" id="state{rand}_3" click="changlx,3" type="button">已签单</button>
		<button class="btn btn-default" id="state{rand}_4" click="changlx,4" type="button">量房</button>
		<button class="btn btn-default" id="state{rand}_5" click="changlx,5" type="button">装修完成</button>
		<button class="btn btn-default" id="state{rand}_6" click="changlx,6" type="button">标☆的</button>
		</div>	
	<div class="tab_bar hide" id="searchBar">
		<div class="tab_tit box_col " id="menu-btn1"><span class="tit"  id="areaRecord">区域</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col"  id="menu-btn2"><span class="tit" id="timeRecord">日期</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col hide"  id="menu-btn3"><span class="tit" id="courseRecord">工地进度</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col hide" id="menu-btn4"><span class="tit" id="projectRecord">工程监理</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col hide" id="menu-btn5"><span class="tit" id="desginRecord">设计师</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col hide"  id="menu-btn6"><span class="tit" id="laiyuanRecord">渠道</span><i class="icon-angle-down"></i></div>
        <div class="tab_tit box_col hi8de"  id="menu-btn7"><span class="tit" id="unitnameRecord">筛选</span><i class="icon-angle-down"></i></div>
    </div>
	</td>
	
	

<div id="menu-wrapper" class="menu-wrapper hidden">
	<div id="menu" class="menu">
		
	</div>
</div>
<div id="menu-backdrop" class="menu-backdrop"></div>
	<td align="right" nowrap>
		<button class="btn btn-default hide" id="move_{rand}" click="move" type="button">客户转移</button> &nbsp; 
		<button class="btn btn-default hide" id="xiang_{rand}" click="view" disabled type="button">详情</button><span id="drubtn_{rand}">&nbsp; 
		<button class="btn btn-default" click="daoru" type="button">导入</button></span>
	</td>
	</tr>
	</table>
	
</div>
<!--弹框start-->
<!-- 
<div class="tk-bg">
	<div class="tk-close" click="close">
		<img src="web/images/close.png" class="tk-img">
	</div>
</div> -->
<!--备注弹框-->

<div class="tk-content tk-same">
	<div class="tk-remark">
		
	</div>
</div>
<!--备注弹框end-->
<div class="blank10"></div>
<div id="view_{rand}"></div>  
<script>
	
address(list[0].city);
function address(data) {
    $(".AreaCenter select").html('');
    $.each(data, function(i, v) {
        $(".AreaCenter select").append('<option value=' + i + ' checked >' + v.name + '</option>');
    });
    var dogg=data;
	$("#area{rand}").change(function(){
		    $(".AreaCenter").css({
		        "width": "40%"
		    });
		    $(".AreaRight").show();
		    var id=$("#area{rand}").val();
		    $(".AreaRight select").html('<option value="全部">全部</option>');
		    $.each(dogg[id].area, function(index, item) {
		        $(".AreaRight select").append('<option value=' + item + '>' + item + '</option>')
		    })
	})
}
</script>