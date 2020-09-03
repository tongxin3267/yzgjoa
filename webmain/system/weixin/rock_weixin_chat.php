<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){	
	var a = $('#view1_{rand}').bootstable({
		tablename:'wx_chat',
		columns:[{
			text:'ID',dataIndex:'id'	
		},{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'会话ID',dataIndex:'chatid'
		},{
			text:'创建人',dataIndex:'owner'
		},{
			text:'成员',dataIndex:'userlist',renderer:function(s){
				return '<div style="width:150px;word-wrap:break-word;">'+s+'</div>';
			}
		},{
			text:'人员数',dataIndex:'sontts',renderer:function(v,d){
				var a1=d.userlist.split('|');
				return a1.length;
			}			
		},{
			text:'操作',dataIndex:'opt',renderer:function(v,d){
				var s='<a href="javascript:;" onclick="upsse{rand}(\''+d.chatid+'\')">[获取]</a>';
				return s;
			}
		}],
		itemclick:function(){
			btn(false);
		}
	});

	var b = $('#view2_{rand}').bootstable({
		tablename:'im_group',where:'[A][K]type<>2',url:publicstore('{mode}','{dir}'),storeafteraction:'aftechatshow',
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'创建人',dataIndex:'createname'
		},{
			text:'创建时间',dataIndex:'createdt'
		},{
			text:'状态',dataIndex:'zt',renderer:function(v,d){
				var s='<a href="javascript:;" style="color:red" onclick="upsses{rand}('+d.id+')">[创建]</a>';
				if(v==1)s='<a href="javascript:;" onclick="upsses{rand}('+d.id+')">[更新]</a>';
				return s;
			}
		},{
			text:'人员数',dataIndex:'sontts'	
		},{
			text:'ID',dataIndex:'id'	
		}]
	});
	
	function btn(bo){
		get('faxis_{rand}').disabled = bo;
	}	
	var c = {
		relad:function(){
			a.reload();
			b.reload();
		},
		updatedept:function(){
			js.msg('msg','未提供接口');
		},
		gengxin:function(id){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('getchatinfo','{mode}', '{dir}'),{chatid:id},function(d){
				if(d.errcode==0){
					js.msg('success', '获取成功');
					a.reload();
					b.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		gengxidn:function(id){
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('createchat','{mode}', '{dir}'),{id:id},function(d){
				if(d.errcode==0){
					js.msg('success', '处理成功');
					a.reload();
					b.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		sedxixas:function(){
			var d=a.changedata;
			js.prompt('向会话['+d.name+']发送消息','消息内容',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(d.id,txt)
			});
		},
		sheniokx:function(na,txt){
			js.msg('wait','发送中...');
			js.ajax(js.getajaxurl('sendchat','{mode}', '{dir}'),{gid:na,msg:txt}, function(d){
				if(d.errcode==0){
					js.msg('success', '发送成功');
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'post,json');
		}
	}
	upsse{rand}=function(id){
		c.gengxin(id);
	}
	upsses{rand}=function(id){
		c.gengxidn(id);
	}
	js.initbtn(c);
});
</script>



<table width="100%">
<tr valign="top">
	<td width="50%">
		<div>
		<button class="btn btn-info" click="sedxixas" disabled id="faxis_{rand}" type="button">发送消息</button>
		</div>
		<div class="blank10"></div>
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">微信会话列表</h3></div>
			<div id="view1_{rand}"></div>
			
		</div>
	</td>
	<td width="10"><div style="width:10px;overflow:hidden"></div></td>
	<td width="50%">
		<div><button class="btn btn-default" click="relad,0" type="button">刷新</button></div>
		<div class="blank10"></div>
		<div class="panel panel-success">
			<div class="panel-heading"><h3 class="panel-title">系统上会话列表</h3></div>
			
			<div id="view2_{rand}"></div>
		</div>
	</td>
</tr>
</table>