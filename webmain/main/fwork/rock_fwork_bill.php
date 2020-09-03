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
	var atype=params.atype,zt=params.zt;
	console.log(deptname);
	if(!zt)zt='';
	
	var bools=false;
		var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('loadbookdatacourse','flow','main',{'setid':'45'}),
		columns:[{
			text:'项目分类',dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
		},{
			text:'ID',dataIndex:'id',width:'20%'
		}],
		load:function(d){				
			var ls=$('#keypp_{rand}');
			var ds=$('#design_{rand}');
			var ms=$('#manage_{rand}');
			$.each(d.course, function (index, obj) {
				var option = $("<option>").val(obj.id).text(obj.name);	
	      		ls.append(option);
            });	
			$.each(d.design, function (index, obj) {
				var option = $("<option>").val(obj.name).text(obj.name);	
	      		ds.append(option);
            });	
			$.each(d.manage, function (index, obj) {
				var option = $("<option>").val(obj.name).text(obj.name);	
	      		ms.append(option);
            });	/*
			if(sspid==0){
				typeid = d.pid;
				sspid = d.pid;
				c.loadfile('0','所有项目');
			}*/
		}
	});;
	
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_bill',params:{'atype':atype,'zt':zt},fanye:true,
		url:publicstore('{mode}','{dir}'),
		storeafteraction:'flowbillafter',storebeforeaction:'flowbillbefore',
		columns:[{
			text:'',dataIndex:'caozuo'
		},{
			text:'工作日志',dataIndex:'record'
		},{
			text:'项目名称',dataIndex:'title',align:'left'
		},{
			text:'品牌',dataIndex:'yzbrand'
		},{
			text:'业主姓名',dataIndex:'chuban'
		},{
			text:'联系方式',dataIndex:'telephone',sortable:true
		},{
			text:'金额',dataIndex:'price',sortable:true
		},{
			text:'设计师',dataIndex:'designer'
		},{
			text:'进度',dataIndex:'coursename',sortable:true
		},{
			text:'工期',dataIndex:'dif',editor:false
		},{
			text:'状态',dataIndex:'status',sortable:true
		}],
		celldblclick:function(){
			c.view();
		},
		load:function(a){
			$('#all_{rand}').html(a.totalprice);
			if(!bools){
				js.setselectdata(get('mode_{rand}'),a.flowarr,'id');
			}
			bools=true;
		},
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	xing{rand}=function(oi){
		a.changedata = a.getData(oi);
		c.view();
	}
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs(d.modename,d.modenum,d.id,'opegs{rand}');
		},
		search:function(){
			//var i=get('keypp_{rand}').value;
			//alert(i);
			//var dl=get('design_{rand}').value;
			//var ml=get('manage_{rand}').value;
			var date=get('dt1_{rand}').value;
			var date2=get('dt2_{rand}').value;
			if (date>date2) {alert('结束时间应大于开始时间'); return false;}
			var brandRecord=get('brandRecord{rand}').value;
			var al=get('areaRecord{rand}').value;
			if (al=="全部") {
				var area=get('area{rand}').value;
					al=list[0].city[area].name=='全部'?'':list[0].city[area].name;					
			}
			a.setparams({
				key:get('key_{rand}').value,
				dt1:date,
				dt2:date2,
				keypp:get('keypp_{rand}').value,
				desginRecord:get('design_{rand}').value,
				projectRecord:get('manage_{rand}').value,
				areaSearch:al,brandRe:brandRecord,
				modeid:get('mode_{rand}').value,
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			console.log(nowtabs.name);
			a.exceldown(nowtabs.name);
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			a.setparams({zt:lx});
			this.search();
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(function(){
		c.search();
	});
	opegs{rand}=function(){
		c.reload();
	}
	
	$('#state{rand}_'+zt+'').addClass('active');
	
	if(atype=='mywtg'){
		$('#stewwews{rand}').hide();
	}
});
</script>
<div>
	<table width="100%">
	<tr>
	<td nowrap style="width:150px ;display:none" >
<div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
		<select style="width:150px ;display:none" id="mode_{rand}" class="form-control" ><option value="0">-选择模块-</option></select>	
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="申请日期起" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="申请日期止" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="业主姓名/项目名称/工长姓名">
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
	
		<td style="padding-left:10px"  id="progress{rand}">
			<select id="keypp_{rand}"  >
			  <option value="">请选择审核进度</option>
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
		<td style="padding-left:10px" id="manager{rand}">
			<select id="manage_{rand}"  >
			  <option value="">工程监理</option>
			</select>
		</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  width="80%" style="padding-left:10px">
		合计：<em id="all_{rand}" ></em>
		<div id="stewwews{rand}" class="btn-group hide">
		<button class="btn btn-default" id="state{rand}_" click="changlx," type="button">全部状态</button>
		<button class="btn btn-default" id="state{rand}_0" click="changlx,0" type="button">待审核</button>
		<button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已审核</button>
		<button class="btn btn-default" id="state{rand}_2" style="color:red" click="changlx,2" type="button">未通过</button>
		</div>	
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<script>

	function isContains(str, substr) {
	    return str.indexOf(substr) >= 0;
	}
	if (deptname=='终端客户') {
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#laiyuan{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		
		//$('#list').remove();
	}
	if (isContains(deptallname, '设计部')||deptname=='工程监理部'||deptname.indexOf("市场部")>=0) {
		$('#designer{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		//crm
		$('#laiyuan{rand}').addClass('hide');
	}
	if (deptname=='预算部'||deptname=='形象建设部') {
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
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
	}
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