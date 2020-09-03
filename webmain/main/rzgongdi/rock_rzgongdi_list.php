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
	var pid,optlx=0;
	var typeid=0,sspid=0,modenum='rzgongdi';
	var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('loadbookdatacourse','flow','main',{'setid':'55'}),
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
            });	
			if(sspid==0){
				typeid = d.pid;
				sspid = d.pid;
				c.loadfile('0','所有项目');
			}
		}
	});

	
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,celleditor:true,autoLoad:false,modenum:modenum,
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
		itemclick:function(){
			get('del_{rand}').disabled=false;
		},
		load:function(a){
			$('#all_{rand}').html(a.totalprice);
		},
		beforeload:function(){
			get('del_{rand}').disabled=true;
		}
	});


	var c = {
		reload:function(){
			at.reload();
		},
		loadfile:function(spd,nsd){
			$('#megss{rand}').html(nsd);
			a.setparams({'typeid':spd}, true);
		},
		genmu:function(){
			typeid = sspid;
			at.changedata={};
			this.loadfile('0','所有项目');
		},
		clicktypeeidt:function(){
			var d = at.changedata;
			if(d.id)c.clicktypewin(false, 1, d);
		},
		clicktypewin:function(o1, lx, da){
			var h = $.bootsform({
				title:'项目分类',height:250,width:300,
				tablename:'option',labelWidth:50,
				isedit:lx,submitfields:'name,sort,pid',cancelbtn:false,
				items:[{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'上级id',name:'pid',value:0,type:'hidden'
				},{
					labelText:'排序号',name:'sort',type:'number',value:0
				}],
				success:function(){
					if(optlx==0)at.reload();
					if(optlx==1)a.reload();
				}
			});
			if(lx==1)h.setValues(da);
			if(lx==0)h.setValue('pid', typeid);
			optlx = 0;
			return h;
		},
		typedel:function(o1){
			at.del({
				url:js.getajaxurl('deloption','option','system'),params:{'stable':'book'}
			});
		},
		del:function(){
			a.del();
		},
		adds:function(){
			openinput('项目',modenum);
		},
		search:function(){
			var s=get('key_{rand}').value;
			var l=get('keypp_{rand}').value;
			var dl=get('design_{rand}').value;
			var ml=get('manage_{rand}').value;
			var al=get('areaRecord{rand}').value;
			if (al=="全部") {
				var area=get('area{rand}').value;
					al=list[0].city[area].name=='全部'?'':list[0].city[area].name;
					
			}
			var brandRecord=get('brandRecord{rand}').value;
			a.setparams({key:s,keypp:l,desginRecord:dl,projectRecord:ml,areaSearch:al,brandRe:brandRecord},true);
		
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-70)+'px');
});
</script>


<table width="100%">
<tr valign="top">
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
<td width="10"></td>
<td>	
	<div>
	<table width="100%"><tr>
		<td align="left" nowrap>
			<button class="btn btn-primary" click="adds"  type="button"><i class="icon-plus"></i> 新增</button>&nbsp; 
			<button class="btn btn-default hide" click="genmu"  type="button">所有项目</button>&nbsp; 
			
		</td>
		
		<td style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"  placeholder="项目名称">
		</td>
		<td style="padding-left:10px" id="progress{rand}">
			<select id="keypp_{rand}"  >
			  <option value="">请选择审核进度</option>
			</select>
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
		<td style="padding-left:10px"  id="designer{rand}">
			<select id="design_{rand}"  >
			  <option value="">设计师</option>
			</select>
		</td>
		<td style="padding-left:10px" id="manager{rand}">
			<select id="manage_{rand}"  >
			  <option value="">工程监理</option>
			</select>
		</td>
		<td style="padding-left:10px">
			<button class="btn btn-default" click="search" type="button">搜索</button> 
		</td>
		<td width="90%">
		合计：<em id="all_{rand}" ></em>
			&nbsp;&nbsp;<span id="megss{rand}"></span>
		</td>
		<td align="right">
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</td>
	</tr></table>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>
</td>
</tr>
</table>
<script>
	if (deptname=='终端客户') {
		$('#progress{rand}').addClass('hide');
		$('#designer{rand}').addClass('hide');
		$('#laiyuan{rand}').addClass('hide');
		$('#manager{rand}').addClass('hide');
		
		//$('#list').remove();
	}
	if (deptname=='设计部'||deptname=='工程监理部'||deptname.indexOf("市场部")>=0) {
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