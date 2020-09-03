<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(s){
				var a=js.decode(s);
				get('weixincorpid_{rand}').value=a.corpid;
				get('weixinsecret_{rand}').value=a.secret;
				get('weixinchaturl_{rand}').value=a.chaturl;
				get('weixinchaturls_{rand}').value=a.chaturl;
				get('weixinchatsecret_{rand}').value=a.chatsecret;
				get('weixinkefusecret_{rand}').value=a.kefusecret;
				get('weixinhuitoken_{rand}').value=a.huitoken;
				get('weixinaeskey_{rand}').value=a.aeskey;
				get('weixinchattb_{rand}').value=a.chattb;
			});
		},
		save:function(o){
			if(ISDEMO){js.msg('success','demo上就不要保存');return;}
			var d={};
			d.corpid = get('weixincorpid_{rand}').value;
			d.secret = get('weixinsecret_{rand}').value;
			d.chatsecret = get('weixinchatsecret_{rand}').value;
			d.kefusecret = get('weixinkefusecret_{rand}').value;
			d.huitoken = get('weixinhuitoken_{rand}').value;
			d.aeskey = get('weixinaeskey_{rand}').value;
			d.chattb = get('weixinchattb_{rand}').value;
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				js.msg();
				js.alert('保存成功，请点测试相关按钮');
			});
		},
		testss:function(o1,lx){
			if(ISDEMO){js.msg('success','demo上就不要测试，我们都测试通过的');return;}
			js.msg('wait','测试发送中...');
			js.ajax(js.getajaxurl('testsend','{mode}','{dir}'),{lx:lx}, function(a){
				if(a.success){
					js.msg('success',a.msg);
				}else{
					js.msg('msg',a.msg);
				}
			},'get,json');
		}
	};
	js.initbtn(c);
	c.init();
	if(!ISDEMO)$('#showddd_{rand}').append('<a href="http://xh829.com/?a=down&id=22" target="_blank">[下载帮助文档]</a>');
});
</script>

<div align="left">
<div  style="padding:10px;">
	
		
		
		<table cellspacing="0" width="600" border="0" cellpadding="0">

		
		<tr>
			<td  align="right"><font color=red>*</font> 企业号corpid：</td>
			<td class="tdinput"><input id="weixincorpid_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" width="180"><font color=red>*</font> 管理员secret：</td>
			<td class="tdinput">
			<textarea id="weixinsecret_{rand}" style="height:60px" class="form-control"></textarea>
			<font id="showddd_{rand}" color="#888888">请到<a href="https://qy.weixin.qq.com/cgi-bin/loginpage" target="_blank">[微信企业号后台]</a>设置即可得到相关值。</font>
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td  class="tdinput" align="left"><button click="testss,0" class="btn btn-default" type="button">测试发送</button>
		</td>
		</tr>
		
		<tr>
			<td  colspan="2"><div class="inputtitle">回调Token设置</div></td>
		</tr>
		
		
		<tr>
			<td  align="right">回调Token：</td>
			<td class="tdinput"><input id="weixinhuitoken_{rand}" class="form-control"></td>
		</tr>

		<tr>
			<td  align="right">回调EncodingAESKey：</td>
			<td class="tdinput">
			<textarea id="weixinaeskey_{rand}" style="height:60px" class="form-control"></textarea>
			
			</td>
		</tr>
		
		
		<tr>
			<td  colspan="2"><div class="inputtitle">企业客服设置</div></td>
		</tr>
		
		<tr>
			<td  align="right">企业客服secret：</td>
			<td class="tdinput">
			<textarea id="weixinkefusecret_{rand}" style="height:80px" class="form-control"></textarea>
			</td>
		</tr>
		<tr>
			<td  align="right">企业内部客服回调地址：</td>
			<td class="tdinput"><input id="weixinchaturls_{rand}" readonly value="http://demo.xh829.com/api.php?m=chatwx" class="form-control"></td>
		</tr>
		
		
		<tr>
			<td  colspan="2"><div class="inputtitle">企业会话IM设置<div style="padding:5px;line-height:18px;font-size:12px"><font color="red">腾讯微信企业号已关闭企业会话IM的服务，未开启企业会话将不能开启了，已开启不要停用否则再也启用不了，未开启就不需要设置[会话secret]，可用企业客服(只能单人发消息)代替。</font></div></div></td>
		</tr>
		
		<tr>
			<td  align="right">会话secret：</td>
			<td class="tdinput">
			<textarea id="weixinchatsecret_{rand}" style="height:60px" class="form-control"></textarea>
			</td>
		</tr>
		
		<tr>
			<td  align="right">会话回调地址：</td>
			<td class="tdinput"><input id="weixinchaturl_{rand}" readonly value="http://demo.xh829.com/api.php?m=chatwx" class="form-control"></td>
		</tr>
		
		
		<tr>
			<td  align="right">是否同步到微信上：</td>
			<td class="tdinput"><select id="weixinchattb_{rand}"  class="form-control"><option value="0">不同步</option><option value="1">同步</option></select>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="tdinput"><div  align="center" style="color:#888888;">以上如不设置，微信上会话将不会同步到系统中，如要同步需开启会话回调功能。</div>
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp;&nbsp;
			<button click="testss,1" class="btn btn-default" type="button">测试会话IM发送</button>&nbsp;&nbsp;
			<button click="testss,2" class="btn btn-default" type="button">测试企业客服发送</button>
			</span>
		</td>
		</tr>
		

	
</div>
</div>