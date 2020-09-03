<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'admin',sort:'sort',dir:'asc',celleditor:true,fanye:true,
		storebeforeaction:'beforeusershow',storeafteraction:'afterusershow',url:publicstore('{mode}','{dir}'),
		columns:[{
			text:'头像',dataIndex:'face',renderer:function(v){
				if(isempt(v))v='images/noface.png';
				return '<img src="'+v+'" height="24" width="24">';
			}
		},{
			text:'用户名',dataIndex:'user'
		},{
			text:'姓名',dataIndex:'name'
		},{
			text:'部门',dataIndex:'deptname'
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'启用',dataIndex:'status',type:'checkbox',sortable:true,editor:true
		},{
			text:'性别',dataIndex:'sex'
		},{
			text:'激活状态',dataIndex:'zt',align:'left',renderer:function(v,d){
				var s='';
				if(d.iscj==1){
					s='<a href="javascript:;" onclick="upsse{rand}('+d.id+')">[更新]</a>';
					if(d.isgz==1)s+='<font color=green>已激活</font>';
					if(d.isgz==4)s+='未激活';
					if(d.isgz==2)s+='<font color=#888888>已禁用</font>';
					if(d.isgc==1)s+='<font color=red>需更新</font>';
				}else{
					s='<a href="javascript:;" style="color:red" onclick="upsse{rand}('+d.id+')">[创建]</a>';
				}
				return s;
			}
		},{
			text:'手机号',dataIndex:'mobile',editor:true
		},{
			text:'邮箱',dataIndex:'email',editor:true
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'	
		}],
		load:function(d){
			var s='创建企业微信上用户时，请先确保组织结构已同步更新了';
			if(d.notstr!='')s='微信有系统有不存在的用户：<font color=red>'+d.notstr+'</font>，请点按钮删除';
			$('#showmsg{rand}').html(s);
		}
	});
	
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		getlist:function(){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('reloaduser','{mode}', '{dir}'),{}, function(d){
				if(d.errcode==0){
					js.msg('success', '获取成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		create:function(id){
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('optuser','{mode}', '{dir}'),{id:id}, function(d){
				if(d.errcode==0){
					js.msg('success', '更新成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		delaluser:function(){
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('delalluser','{mode}', '{dir}'),{}, function(d){
				if(d.errcode==0){
					js.msg('success', '删除成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		anaytouser:function(){
			js.confirm('确定要将将企业微信上用户同步到系统上吗？同步了，将覆盖系统原来创建的哦。',function(jg){
				if(jg=='yes')c.anaytousers();
			});
		},
		anaytousers:function(){
			js.msg('wait','同步中...');
			js.ajax(js.getajaxurl('anaytouser','{mode}', '{dir}'),{},function(d){
				if(d.errcode==0){
					js.msg('success', '同步完成成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		faxiaox:function(){
			var d=a.changedata;
			if(!d.id){js.msg('msg','请先选中人');return;}
			js.prompt('向人员['+d.name+']发送消息','消息内容',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(d.id,txt)
			});
		},
		sheniokx:function(id,txt){
			js.msg('wait','发送中...');
			js.ajax(js.getajaxurl('senduser','{mode}', '{dir}'),{id:id,msg:txt}, function(d){
				if(d.errcode==0){
					js.msg('success', '成功:'+d.msg+'');
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'post,json');
		},
		sendanayface:function(id,txt){
			js.msg('wait','发送中...');
			js.ajax(js.getajaxurl('sendanayface','{mode}', '{dir}'),false, function(d){
				if(d.errcode==0){
					js.msg('success', '成功:'+d.msg+'');
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		}
	};
	upsse{rand}=function(id){
		c.create(id);
	}
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
<td><button class="btn btn-default" click="getlist" type="button">获取企业微信上用户</button></td>
<td style="padding-left:10px">
	<div class="input-group" style="width:220px;">
		<input class="form-control" id="key_{rand}"   placeholder="姓名/部门/职位/用户名">
		<span class="input-group-btn">
			<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
		</span>
	</div>
</td>
<td width="90%" style="padding-left:10px"></td>
<td align="right" nowrap>
	<button class="btn btn-default" click="sendanayface" type="button">异步获取头像</button>&nbsp;&nbsp;
	<button class="btn btn-default" click="anaytouser" type="button">将企业微信上的用户同步到系统上</button>&nbsp;&nbsp;
	<button class="btn btn-info" click="faxiaox" type="button">发消息</button>&nbsp;&nbsp;
	<button class="btn btn-danger" click="delaluser" type="button">删除企业微信上系统不存在的用户</button>
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div id="showmsg{rand}" class="tishi">创建企业微信上用户时，请先确保组织结构已同步更新了，异步获取头像：需要有服务端，发送只会获取有激活企业微信，用户没有设置头像的。</div>
