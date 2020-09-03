<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'wx_agent',sort:'sort',dir:'asc',celleditor:true,
		
		columns:[{
			text:'应用Logo',dataIndex:'square_logo_url',renderer:function(v){
				if(isempt(v))v='images/noface.png';
				return '<img src="'+v+'" height="30" width="30">';
			}
		},{
			text:'名称',dataIndex:'name',editor:true
		},{
			text:'应用ID',dataIndex:'agentid'	
		},{
			text:'类型',dataIndex:'type',renderer:function(v){
				var s='未知';
				if(v==1)s='消息型'; 
				if(v==2)s='主页型'; 
				return s;
			}
		},{
			text:'可信任域名',dataIndex:'redirect_domain',editor:true
		},{
			text:'简介',dataIndex:'description',align:'left',editor:true,type:'textarea'
		},{
			text:'可用部门',dataIndex:'allow_partys'
		},{
			text:'是否禁用',dataIndex:'close',type:'checkbox'
		},{
			text:'地理上报',dataIndex:'report_location_flag',editor:true,renderer:function(v){
				var s='不上报';
				if(v==1)s='进入会话上报';if(v==2)s='持续上报';
				return s;
			}
		},{
			text:'上报用户进入',dataIndex:'isreportenter',editor:true,type:'checkbox'
		},{
			text:'用户变更通知',dataIndex:'isreportuser',editor:true,type:'checkbox'
		},{
			text:'操作',dataIndex:'opt',renderer:function(v,d){
				var s='<a href="javascript:;" onclick="upsse{rand}('+d.agentid+',0)">获取信息</a>';
				s+='<br><a href="javascript:;" onclick="upsse{rand}('+d.agentid+',1)">[更新]</a>';
				return s;
			}
		}],
		itemclick:function(){
			btn(false);
		}
	});
	
	function btn(bo){
		get('faxis_{rand}').disabled = bo;
	}
	
	var c = {
		faxiaox:function(){
			var d=a.changedata;
			js.prompt('向应用['+d.name+']发送消息','消息内容',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(d.name,txt)
			});
		},
		sheniokx:function(na,txt){
			js.msg('wait','发送中...');
			js.ajax(js.getajaxurl('sendagent','{mode}', '{dir}'),{name:na,msg:txt}, function(d){
				if(d.errcode==0){
					js.msg('success', '发送成功');
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'post,json');
		},
		getlist:function(){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('updateagent','{mode}', '{dir}'),{}, function(d){
				if(d.errcode==0){
					js.msg('success', '获取成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		getagent:function(id){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('getagent','{mode}', '{dir}'),{agentid:id}, function(d){
				if(d.errcode==0){
					js.msg('success', '获取成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		},
		updateagent:function(id){
			js.msg('wait','更新中...');
			js.ajax(js.getajaxurl('setagent','{mode}', '{dir}'),{agentid:id}, function(d){
				if(d.errcode==0){
					js.msg('success', '更新成功');
					a.reload();
				}else{
					js.msg('msg', d.errcode+':'+d.msg);
				}
			},'get,json');
		}
	};
	upsse{rand}=function(id, lx){
		if(lx==0)c.getagent(id);
		if(lx==1)c.updateagent(id);
	}
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
<td><button class="btn btn-default" click="getlist" type="button">获取微信上应用</button></td>
<td width="90%" style="padding-left:10px">创建应用需在<a href="https://qy.weixin.qq.com/cgi-bin/loginpage" target="_blank">[微信企业号后台]</a>创建</td>
<td align="right">
	<button class="btn btn-info" click="faxiaox" disabled id="faxis_{rand}" type="button">发送消息</button>
</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">地理位置上报可设置值：0不上报，1进入会话上报,2持续上报，请勿乱写其他值</div>
