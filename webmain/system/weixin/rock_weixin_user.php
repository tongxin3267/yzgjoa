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
			text:'状态',dataIndex:'zt',align:'left',renderer:function(v,d){
				var s='',zt=d.wxstatus;
				if(d.iscj==1){
					s='<a href="javascript:;" onclick="upsse{rand}('+d.id+')">[更新]</a>';
					if(zt==0||zt==4)s+='未关注';
					if(zt==2)s+='已冻结';
					if(zt==1)s+='<font color="green">已关注</font>';
					if(d.isgc==1)s+='<font color="red">需更新</font>';
				}else{
					s='<a href="javascript:;" style="color:red" onclick="upsse{rand}('+d.id+')">[创建]</a>';
				}
				
				return s;
			}
		},{
			text:'微号',dataIndex:'wxweixinid',renderer:function(v){
				var s = '&nbsp;';
				if(!ISDEMO)s=v;
				return s;
			}
		},{
			text:'手机号',dataIndex:'mobile',editor:true
		},{
			text:'邮箱',dataIndex:'email',editor:true
		},{
			text:'微信号',dataIndex:'weixinid',editor:true
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'	
		}],
		load:function(d){
			var s='创建微信上用户时，请先确保组织结构已同步更新了';
			if(d.notstr!='')s='微信有系统有不存在的用户：<font color=red>'+d.notstr+'</font>，请点按钮删除';
			$('#showmsg{rand}').html(s);
		},
		beforeload:function(){
			get('btn1_{rand}').disabled=true;
		},
		itemclick:function(){
			get('btn1_{rand}').disabled=false;
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
		shenkeuxx:function(){
			var d=a.changedata;
			if(d.wxstatus!='1'){
				js.msg('msg','该用户未关注不能发客服消息');
				return;
			}
			js.prompt('发客服消息','消息内容',function(lxbd,msg){
				if(lxbd=='yes' && msg){
					c.sendmsg(msg);
				}
			});
		},
		sendmsg:function(txt){
			var d=a.changedata;
			var d1={receid:d.id,cont:txt};
			js.ajax(js.getajaxurl('sendkefu','{mode}', '{dir}'),d1, function(d){
				if(d.errcode==0){
					js.msg('success', '发送成功');
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'post,json',false,'发送中...');
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
<td><button class="btn btn-default" click="getlist" type="button">获取微信上用户</button></td>
<td style="padding-left:10px">
	<div class="input-group" style="width:250px;">
		<input class="form-control" id="key_{rand}"   placeholder="姓名/部门/职位/用户名">
		<span class="input-group-btn">
			<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
		</span>
	</div>
</td>
<td width="90%" style="padding-left:10px"></td>
<td align="right" nowrap>
	<button class="btn btn-default" id="btn1_{rand}" disabled click="shenkeuxx" type="button">发客服消息</button>&nbsp;
	<button class="btn btn-danger" click="delaluser" type="button">删除微信上系统不存在的用户</button>
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div id="showmsg{rand}" class="tishi">创建微信上用户时，请先确保组织结构已同步更新了</div>
