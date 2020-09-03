<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	if (atype=='anzdcl'||atype=='anzhis') {$('#hahah_{rand}').show();}
	var a = $('#admin_{rand}').bootstable({
		tablename:'buildin',modenum:'buildin',params:{'atype':atype},celleditor:true,fanye:true,
		storebeforeaction:'beforeshow',modedir:'{mode}:{dir}',fieldsafteraction:'fieldsafters',
		columns:[{
			text:'项目名称',dataIndex:'title'
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
			text:'提交时间',dataIndex:'createdt',sortable:true
		},],
		itemclick:function(){
			get('del_{rand}').disabled=false;
			btn(false);
		},
		beforeload:function(){
			get('del_{rand}').disabled=true;
			btn(true);
		},
		load:function(d){	
			if (atype=='anzdcl'||atype=='anzhis') {
				$('#all_{rand}').html(d.alltotal);
				$('#total_{rand}').html(d.totalprice);
			}

		}
	});
	if (deptname=='管理层'||deptname=='人事&材料部'||deptname.indexOf("安装部")>=0||deptname.indexOf("财务部")>=0) {
		$('#author{rand}').show();
		$('#clgys{rand}').show();


		var at = $('#optionview_{rand}').bootstree({
			url:js.getajaxurl('loadbuildindatacourse','flow','main'),
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
			a.del({check:function(lx){
			}});
		},
		search:function(){
			var s=get('key_{rand}').value;
			var author=get('author_{rand}').value;
			var clgys=get('clgys_{rand}').value;
			a.setparams({key:s,author:author,clgys:clgys},true);
		},
		clickwin:function(o1,lx){
			var icon='plus',name='安装测量',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='测量['+a.changedata.title+']';
			};
			adminusermanage = a;

			var url = 'index.php?a=edit&m=buildin&d=main&id='+id;
			
			var s='',tit=name;if(!tit)tit='安装测量';

			var H	= (document.body.clientHeight<winHb())?winHb()-5:document.body.clientHeight;
			H="578px";
			js.tanbody('clupdatewin',tit,450,300,{
				html:'<div style="height:'+H+';overflow:hidden"><iframe src="" name="winiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
				bbar:'none'
			});
			winiframe.location.href=url;
			return false;
		},
		piliang:function(){
			adminusermanage = a;
			addtabs({num:'admina',url:'system,admin,editpl',icons:'plus',name:'批量添加用户'});
		},
		refresh:function(){
			js.sendevent('reload', '');
		},
		adds:function(){
			openinput('材料派发','buildin');
		},
		daochu:function(){
			a.exceldown();
		},
		editface:function(){
			editfacechang(a.changeid, a.changedata.name);
		}
	};
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
	}
	function savesuccess(){};

	
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
		<div class="input-group" style="width:250px">
			<input class="form-control" id="key_{rand}"   placeholder="业主姓名/项目名称/装修地址/工程监理">
			
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
	<td width="1%"></td>
	<td width="80%" id="hahah_{rand}" style="display: none;"> 合计：<em id="all_{rand}" ></em> &nbsp;&nbsp;&nbsp;&nbsp;商定总价：<em id="total_{rand}" ></em></td>
	<td width="80%" > </td>
	<td align="right" nowrap>
		<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 安装测量详情 </button> &nbsp; 
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="admin_{rand}"></div>