<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	if (atype=='anzdcl'||atype=='anzhis') {$('#hahah_{rand}').show();}
	var a = $('#admin_{rand}').bootstable({
		tablename:'clpaifa',modenum:'clpaifa',params:{'atype':atype},celleditor:true,fanye:true,
		storebeforeaction:'groupshow',modedir:'{mode}:{dir}',fieldsafteraction:'fieldsafters',
		columns:[{
			text:'项目名称',dataIndex:'title',sortable:true,align:'left'
		},{
			text:'装修地址',dataIndex:'weizhi'
		},{
			text:'工程监理',dataIndex:'author'
		},{
			text:'监理电话',dataIndex:'mobile'
		},{
			text:'合计',dataIndex:'alltotal'
		},{
			text:'商定总价',dataIndex:'totalprice',sortable:true
		},{
			text:'超期',dataIndex:'diff',sortable:true
		},{
			text:'类型',dataIndex:'type',sortable:true
		},{
			text:'供应商',dataIndex:'clgysname',sortable:true
		},{
			text:'处理时间',dataIndex:'dealdt',sortable:true
		},{
			text:'备注',dataIndex:''
		},/*{
			text:'进度',dataIndex:'coursename',sortable:true
		},*/],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		load:function(d){	
				$('#all_{rand}').html(d.alltotal);
				$('#total_{rand}').html(d.totalprice);
		}
	});
	
	if (deptname=='管理层'||deptname=='人事&材料部'||deptname.indexOf("安装部")>=0||deptname.indexOf("财务部")>=0) {
		$('#author{rand}').show();
		$('#clgys{rand}').show();


		var at = $('#optionview_{rand}').bootstree({
			url:js.getajaxurl('loadclpaifadatacourse','flow','main'),
			columns:[{
				text:'ID',dataIndex:'id',width:'20%'
			}],
			load:function(d){	
				console.log(d.author);
				var ds=$('#author_{rand}');
				$.each(d.author, function (index, obj) {
					var option = $("<option>").val(obj.name).text(obj.name);	
		      		ds.append(option);
	            });		
				var sc=$('#clgys_{rand}');
				$.each(d.clgys, function (index, obj) {
					var option = $("<option>").val(obj.name).text(obj.name);	
		      		sc.append(option);
	            });
			}
		});
	}
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes'){btn(true);console.log(a);}}});
		},
		search:function(){
			var s=get('key_{rand}').value;
			var date=get('dt1_{rand}').value;
			var date2=get('dt2_{rand}').value;
			if (date>date2) {alert('结束时间应大于开始时间'); return false;}
			var author=get('author_{rand}').value;
			var clgys=get('clgys_{rand}').value;
			a.setparams({key:s,timeRecord:date,timeRecord2:date2,author:author,clgys:clgys},true);
		},
		clickwin:function(o1,lx){
			console.log(a);
			var icon='plus',name='材料配送',id=0;
			if(lx==1){
				id = a.changeid;
				//id = a.changedata.fid;               v1展示工地所有配送记录
				icon='history';
				name='详情['+a.changedata.title+']';
			};
			adminusermanage = a;

			// var url = 'index.php?a=history&m=clpaifa&d=main&id='+id;
			var url = 'task.php?a=clpf&num=clpaifa&mid='+id+'&show=pc&type=0';
			// var url = 'index.php?a=history&m=clpaifa&d=main&id='+id;
			//js.location(url);
			
			var s='',tit=name;if(!tit)tit='材料配送详情';

			var H	= (document.body.clientHeight<winHb())?winHb()-5:document.body.clientHeight;
			H="578px";
			js.tanbody('clupdatewin',tit,450,300,{
				html:'<div style="height:'+H+';overflow:hidden"><iframe src="" name="winiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
				bbar:'none'
			});
			winiframe.location.href=url;
			return false;
			//addtabs({num:'admin'+id+'',url:'main,clpaifa,edit,id='+id+'',icons:icon,name:name+'材料配送'});
		},
		piliang:function(){
			adminusermanage = a;
			addtabs({num:'admina',url:'system,admin,editpl',icons:'plus',name:'批量添加用户'});
		},
		refresh:function(){
			js.msg('wait', '更新中...');
			$.get(js.getajaxurl('updatedata','admin','system'), function(da){
				js.msg('success', da);
			});
		},
		daochu:function(){
			a.exceldown();
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		editface:function(){
			editfacechang(a.changeid, a.changedata.name);
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
		//get('face_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
});
</script>



<div>

<table width="100%"><tr>
<td width="220" style="display:none">
	<div style="border:1px #cccccc solid">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>	  
	</div>  
</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="处理日期起" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="处理日期止" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<div class="input-group" style="width:250px">
			<input class="form-control" id="key_{rand}"   placeholder="业主姓名/项目名称/装修地址/工程监理/设计师">
			
		</div>
	</td>
	<td width="1%"></td>
	<td style="display: none;" id="author{rand}">
		<select id="author_{rand}"  >
		  <option value="">工程监理</option>
		</select>
	</td>
	<td width="1%"></td>
	<td style="display: none;" id="clgys{rand}">
		<select id="clgys_{rand}"  >
		  <option value="">材料供应商</option>
		</select>
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td width="2%"></td>
	<td width="40%" id="hahah_{rand}" style="display: none3;"> 合计：<em id="all_{rand}" ></em> &nbsp;&nbsp;&nbsp;&nbsp;商定总价：<em id="total_{rand}" ></em></td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 材料配送记录 </button> &nbsp; 
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="admin_{rand}"></div>