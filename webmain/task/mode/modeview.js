var anzonly = [73,135];
function othercheck(){}
function initbody(){
	$('body').click(function(){
		$('.menullss').hide();
	});
	$('body').keydown(c.onkeydown);
	$('#showmenu').click(function(){
		$('.menullss').toggle();
		return false;
	});
	$('.menullss li').click(function(){
		c.mencc(this);
	});
}
function showchayue(opt, st){
	alert('总查阅:'+st+'次\n最后查阅：'+opt+'');
}
function geturlact(act){
	var url=js.getajaxurl(act,'mode_'+modenum+'|input','flow');
	return url;
}
function check(o1){
	var da = {'sm':form('check_explain').value,'mid':mid,'modenum':modenum,'zt':_getaolvw('check_status'),
	'taskdesc':_getaolvw('taskdesc'),'xgwj':form('check_fileid').value};
	if(da.taskdesc==''){js.setmsg('请仔细阅读工作任务<br>确定任务已完成<br>并在工作任务前面打勾');return;}
	if(da.zt==''){js.setmsg('请选择处理动作');return;}
	if(isempt(da.sm)||form('check_explain').value.length<30){js.setmsg('不能少于30字哦，升职加薪就靠它了');return;}
	if(form('zhuanbanname')){
		da.zyname 	= form('zhuanbanname').value;
		da.zynameid = form('zhuanbannameid').value;
	}
	if(da.zt==1) {		
		if(form('check_rgfeelist')){
			da.rgfeelist 	= form('check_rgfeelist').value;
			da.totalprice 	= form('check_totalprice').value;
			da.alltotal 	= form('check_alltotal').value;
			if(da.rgfeelist==''){
				js.setmsg('请创建人工费清单');return;
			}
		}
		if(form('check_clupdatelist')){
			da.clupdatelist 	= form('check_clupdatelist').value;
			da.totalprice 	= form('check_totalprice').value;
			da.alltotal 	= form('check_alltotal').value;
			if(da.clupdatelist==''){
				js.setmsg('请创建人工费清单');return;
			}
		}
	}
	if(form('nextnameid') && da.zt=='1'){
		da.nextname 	= form('nextname').value;
		da.nextnameid 	= form('nextnameid').value;
		if(da.nextnameid==''){
			js.setmsg('请选择下一步处理人');return;
		}
	}
	if(!da.zynameid && da.zt=='1'){
		var fobj=$('span[fieidscheck]'),i,fid,fiad;
		for(i=0;i<fobj.length;i++){
			fiad = $(fobj[i]);
			fid	 = fiad.attr('fieidscheck');
			da['cfields_'+fid]=form(fid).value;
			if(da['cfields_'+fid]==''){js.setmsg(''+fiad.text()+'不能为空');return;}
		}
	}
	var ostr=othercheck(da);
	if(typeof(ostr)=='string'&&ostr!=''){js.setmsg(ostr);return;}
	if(typeof(ostr)=='object')for(var csa in ostr)da[csa]=ostr[csa];
	js.setmsg('处理中...');
	o1.disabled = true;
	var url = c.gurl('check');
	js.ajax(url,da,function(a){
		if(a.success){
			js.setmsg(a.msg,'green');console.log(c);
			c.callback();
			if(get('autocheckbox'))if(get('autocheckbox').checked)c.close();
		}else{
			js.setmsg(a.msg);
			o1.disabled = false;
		}
	},'post,json',function(){
		js.setmsg('处理失败请重试');o1.disabled = false;
	});
}
function _getaolvw(na){
	var v = '',i,o=$("input[name='"+na+"']");
	for(i=0;i<o.length;i++)if(o[i].checked)v=o[i].value;
	return v;
}

/**
*	nae记录名称 
*	zt状态名称 
*	ztid 状态id 
*	ztcol 状态颜色 
*	ocan 其他参数
*	las 说明字段Id默认other_explain
/*happy_add  添加相关文件*/

function _submitother(nae,zt,ztid,ztcol,ocan,las){
	if(!las)las='other_explain';
	if(!nae||!get(las)){js.setmsg('sorry;不允许操作','','msgview_spage');return;}
	var sm=$('#'+las+'').val();
	if(!ztcol)ztcol='';
	if(!zt)zt='';if(!ocan)ocan={};
	if(!ztid){js.setmsg('没有选择状态','','msgview_spage');return;}
	if(!sm){js.setmsg('没有输入备注/说明','','msgview_spage');return;}
	var da = js.apply({'name':nae,'mid':mid,'modenum':modenum,'ztcolor':ztcol,'zt':zt,'ztid':ztid,'sm':sm},ocan);
	js.setmsg('处理中...','','msgview_spage');
	js.ajax(c.gurl('addlog'),da,function(s){
		js.setmsg('处理成功','green', 'msgview_spage');
		$('#spage_btn').hide();
	},'post',function(s){
		js.setmsg(s,'','msgview_spage');
	});
	return false;
}
var c={
	callback:function(cs){
		var calb = js.request('callback');
		if(!calb)return;
		try{parent[calb](cs);}catch(e){}
		try{opener[calb](cs);}catch(e){}
		try{parent.js.tanclose('openinput');}catch(e){}
	},
	gurl:function(a){
		var url=js.getajaxurl(a,'flowopt','flow');
		return url;
	},
	close:function(){
		window.close();
		try{parent.js.tanclose('winiframe');}catch(e){}
	},
	other:function(nae,las){
		_submitother(nae,'','1','',las);
	},
	others:function(nae,zt,ztid,ztcol,ocan,las){
		_submitother(nae,zt,ztid,ztcol,ocan,las);
	},
	mencc:function(o1){
		var lx=$(o1).attr('lx');
		if(lx=='2')c.delss();
		if(lx=='3')c.close();
		if(lx=='4')location.reload();
		if(lx=='0')c.clickprint();
		if(lx=='5')c.daochuword();
		if(lx=='1'){
			var url='index.php?a=lu&m=input&d=flow&num='+modenum+'&mid='+mid+'';
			js.location(url);
		}
	},
	clickprint:function(){
		c.hideoth();
		window.print();
	},
	daochuword:function(){
		var url='task.php?a=p&num='+modenum+'&mid='+mid+'&stype=word';
		js.location(url);
	},
	hideoth:function(){
		$('.menulls').hide();
		$('.menullss').hide();
		$('a[temp]').remove();
	},
	delss:function(){
		js.confirm('删除将不能恢复，确定要<font color=red>删除</font>吗？',function(lx){
			if(lx=='yes')c.delsss();
		});
	},
	delsss:function(){
		var da = {'mid':mid,'modenum':modenum,'sm':''};
		js.ajax(c.gurl('delflow'),da,function(a){
			js.msg('success','单据已删除,3秒后自动关闭页面,<a href="javascript:;" onclick="c.close()">[关闭]</a>');
			c.callback();
			setTimeout('c.close()',3000);
		},'post');
	},
	onkeydown:function(e){
		var code	= event.keyCode;
		if(code==27){
			c.close();
			return false;
		}
		if(event.altKey){
			if(code == 67){
				c.close();
				return false;
			}
		}
	},
	changeshow:function(lx){
		$('#showrecord'+lx+'').toggle();
	},
	callback:function(cs){
		var calb = js.request('callback');
		if(!calb){
			try{
			if(ismobile==0){
			parent.bootstableobj[moders.num].reload();
			parent.js.msg('success','处理成功');
			parent.js.tanclose('winiframe');}
			}catch(e){}
			return;
		}
		try{parent[calb](cs);}catch(e){}
		try{opener[calb](cs);}catch(e){}
		try{parent.js.tanclose('winiframe');}catch(e){}
	},
	save:function(){
		var d = this.savesss();
		if(!d)return;
		if(ismobile==1){
			js.msg('wait','保存中...');
			get('AltS').disabled=true;
			f.fileobj.start();
		}else{
			this.saveken();
		}
	},
	saveken:function(){
		var d = this.savesss();
		if(!d)return;
		this.saveok(d);
	},
	showtx:function(msg){
		js.setmsg(msg);
		if(ismobile==1)js.msg('msg', msg);
	},
	selectdatadata:{},
	selectdata:function(s1,ced,fid,tit){
		if(isedit==0)return;
		if(!tit)tit='请选择...';
		var a1 = s1.split(',');
		$.selectdata({
			data:this.selectdatadata[fid],title:tit,
			url:geturlact('getselectdata',{act:a1[0]}),
			checked:ced, nameobj:form(fid), idobj:form(a1[1]),
			onloaddata:function(a){
				c.selectdatadata[fid]=a;
			}
		});
	},
	savesss:function(){
		var d=this.getsubdata(0);
		if(js.ajaxbool||isedit==0)return false;
		var len = arr.length,i,val,fid,flx,nas;
		changesubmitbefore();
		var d = js.getformdata();
		for(i=0;i<len;i++){
			if(arr[i].iszb!='0')continue;
			fid=arr[i].fields;
			flx=arr[i].fieldstype;
			nas=arr[i].name;
			if(ismobile==0 && arr[i].islu=='1' && flx=='htmlediter'){
				d[fid] = this.editorobj[fid].html();
			}
			val=d[fid];
			if(arr[i].isbt=='1'){
				if(isempt(val)){
					if(form(fid))form(fid).focus();
					this.showtx(''+nas+'不能为空');
					return false;
				}
			}
			if(val && flx=='email'){
				if(!js.email(val)){
					this.showtx(''+nas+'格式不对');
					form(fid).focus();
					return false;
				}
			}
		}
		var s=changesubmit(d);
		if(typeof(s)=='string'&&s!=''){
			this.showtx(s);
			return false;
		}
		if(typeof(s)=='object')d=js.apply(d,s);
		d.sysmodeid=moders.id;
		d.sysmodenum=moders.num;
		return d;
	},
	saveok:function(d){
		js.setmsg('保存中...');
		get('AltS').disabled=true;
		js.ajax(geturlact('save'),d,function(str){
			var a = js.decode(str);
			c.backsave(a, str);
		}, 'post', function(){
			get('AltS').disabled=false;
			js.setmsg('error:内部错误,可F12调试');
		});
	},
	backsave:function(a,str){
		var msg = a.msg;
		if(a.success){
			js.setmsg(msg,'green');
			js.msg('success','保存成功');
			this.formdisabled();
			$('#AltS').hide();
			form('id').value=a.data;
			isedit=0;
			this.callback(a.data);
			try{
			js.sendevent('reload', 'yingyong_mode_'+moders.num+'');
			js.backla();}catch(e){}
			savesuccess();
		}else{
			if(typeof(msg)=='undefined')msg=str;
			get('AltS').disabled=false;
			js.setmsg(msg);
			js.msg('msg',msg);
		}
	},
	showdata:function(){
		var smid=form('id').value;
		if(smid=='0'||smid==''){
			isedit=1;
			$('#AltS').show();
			c.initdatelx();
			c.initinput();
			initbodys(smid);
		}else{
			js.setmsg('加载数据中...');
			js.ajax(geturlact('getdata'),{mid:smid,flownum:moders.num},function(str){
				c.showdataback(js.decode(str));	
			},'post', function(){
				js.setmsg('error:内部错误,可F12调试');
			});
		}
	},
	initinput:function(){
		var o,o1,sna,i;
		var o = $('div[id^="filed_"]');
		if(isedit==1)o.show();
		for(i=0;i<o.length;i++){
			o1 = o[i];sna= $(o1).attr('tnam');
			if(isedit==1){
				$.rockupload({
					'inputfile':''+o1.id+'_inp',
					'initremove':false,maxsize:1,
					'oparams':{sname:sna},'uptype':'image',
					'onsuccess':function(f,gstr){
						var sna= f.sname,d=js.decode(gstr);
						get('imgview_'+sna+'').src = d.filepath;
						form(sna).value=d.filepath;
					}
				});
			}
			var val = form(sna).value;
			if(val)get('imgview_'+sna+'').src=val;
		}
	},
	showviews:function(o1){
		$.imgview({'url':o1.src,'ismobile':ismobile==1});
	},
	initdatelx:function(){
		
	},
	showdataback:function(a){
		if(a.success){
			var da = a.data;
			js.setmsg();
			var len = arr.length,i,fid,val,flx,ojb,j;
			data=da.data;
			for(i=0;i<len;i++){
				fid=arr[i].fields;
				flx=arr[i].fieldstype;
				if(arr[i].islu=='1' && arr[i].iszb=='0' && fid.indexOf('temp_')!=0){
					val=da.data[fid];
					if(val==null)val='';
					if(flx=='checkboxall'){
						ojb=$("input[name='"+fid+"[]']");
						val=','+val+',';
						for(j=0;j<ojb.length;j++){
							if(val.indexOf(','+ojb[j].value+',')>-1)ojb[j].checked=true;
						}
					}else if(flx=='checkbox'){
						form(fid).checked = (val=='1');
					}else if(flx=='htmlediter' && ismobile==0){
						this.editorobj[fid].html(val);
					}else if(flx.substr(0,6)=='change'){
						if(form(fid))form(fid).value=val;
						fid = arr[i].data;
						if(!isempt(fid)&&form(fid))form(fid).value=da.data[fid];
					}else{
						if(form(fid))form(fid).value=val;
					}
				}
			}
			isedit=da.isedit;
			if(form('base_name'))form('base_name').value=da.user.name;
			if(form('base_deptname'))form('base_deptname').value=da.user.deptname;
			js.downupshow(da.filers,'fileidview','', (isedit==0));
			var subd = da.subdata,subds;
			for(j=0;j<=3;j++){
				subds=subd['subdata'+j+''];
				if(subds)for(i=0;i<subds.length;i++){
					subds[i].sid=subds[i].id;
					if(form('xuhao'+j+'_'+i+'')){
						c.adddatarow(j,i, subds[i]);
					}else{
						c.insertrow(j, subds[i], true);
					}
				}
			}
			c.initinput();
			initbodys(form('id').value);
			if(isedit==0){
				this.formdisabled();
				js.setmsg('无权编辑');
			}else{
				$('#AltS').show();
				c.initdatelx();
			}
			if(da.isflow==1){
				$('.status').show();
				var zt=da.status;
				if(da.data.isturn=='0')zt='3';
				get('statusimg').src='images/status'+zt+'.png';
			}
		}else{
			get('AltS').disabled=true;
			this.formdisabled();
			js.setmsg(a.msg);
			js.msg('msg',a.msg);
		}
	},
	date:function(o1,lx){
		$(o1).rockdatepicker({view:lx,initshow:true});
	},
	close:function(){
		window.close();
	},
	formdisabled:function(){
		$('form').find('*').attr('disabled', true);
		$('#fileupaddbtn').remove();
	},
	upload:function(){
		js.upload('',{showid:'fileidview'});
	},
	rgfeezizuo:function(){
		js.rgfeezizuo('',{showid:'rgfeeview'});
	},	
	clupdate:function(){
		js.clupdate('',{showid:'clupdateview'});
	},
	noclpaifa:function(){
			// js.msg('msgshowdivla','流程未到不可下单哦');
			alert("流程未到不可下单哦");
	},
	noclpaifa2:function(){
			// js.msg('msgshowdivla','流程未到不可下单哦');
			alert("暂无历史记录哦");
	},
	nobuildin:function(){
			// js.msg('msgshowdivla','流程未到不可下单哦');
			alert("流程未到不可下单哦");
	},
	nobuildin2:function(){
			// js.msg('msgshowdivla','流程未到不可下单哦');
			alert("暂无历史记录哦");
	},
	clpaifa:function(cid, fid){
		var h1=$(window).height(),h2=document.body.scrollHeight,s1;
		if(h2>h1)h1=h2;
		var col='';
		var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:9999" >';
		s+='<div id="menulistshow_s" style="width: 150px; margin-top: 246.5px; position: fixed; left: 112.5px;position:fixed;-webkit-overflow-scrolling:touch" class="menulist r-border-r r-border-l">';
			s+='<div class="r-border-t" onclick="c.paifa('+cid+','+fid+');">材料配送</div>';
			s+='<div class="r-border-t" onclick="c.tuihuo('+cid+','+fid+');">退货</div>';
			s+='<div class="r-border-t" onclick="c.history('+cid+','+fid+');">历史配送记录</div>';		
		s+='</div>';
		s+='</div>';

		$('body').append(s);
		$('.menullss').toggle();
		return false;
		//js.clupdate('',{showid:'clupdateview'});
	},
	buildin:function(cid, fid){
		console.log(cid);
		console.log(anzonly);
		console.log(anzonly.indexOf(cid));
		var h1=$(window).height(),h2=document.body.scrollHeight,s1;
		if(h2>h1)h1=h2;
		var col='',action="'buildin'",flag="测量";
		if(anzonly.indexOf(cid) > -1)	var flag="安装";
		var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:9999" >';
			s+='<div id="menulistshow_s" style="width: 150px; margin-top: 246.5px; position: fixed; left: 112.5px;position:fixed;-webkit-overflow-scrolling:touch" class="menulist r-border-r r-border-l">';
			s+='<div class="r-border-t" onclick="js.paifa('+cid+','+fid+','+action+');">'+flag+'通知</div>';
			if(anzonly.indexOf(cid) > -1) s+='<div class="r-border-t" onclick="js.tuihuo('+cid+','+fid+','+action+');">退货</div>';
			s+='<div class="r-border-t" onclick="js.history('+cid+','+fid+','+action+');">'+flag+'记录</div>';		
		s+='</div>';
		s+='</div>';

		$('body').append(s);
		$('.menullss').toggle();
		return false;
		//js.clupdate('',{showid:'clupdateview'});
	},
	buildin2:function(cid, fid){
		var h1=$(window).height(),h2=document.body.scrollHeight,s1;
		if(h2>h1)h1=h2;
		var col='',action="'buildin'",flag="测量";
		if(anzonly.indexOf(cid) > -1)	var flag="安装";
		var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:9999" >';
		s+='<div id="menulistshow_s" style="width: 150px; margin-top: 246.5px; position: fixed; left: 112.5px;position:fixed;-webkit-overflow-scrolling:touch" class="menulist r-border-r r-border-l">';
		s+='<div class="r-border-t" onclick="js.history('+cid+','+fid+','+action+');">'+flag+'记录</div>';		
		s+='</div>';
		s+='</div>';

		$('body').append(s);
		$('.menullss').toggle();
		return false;
	},
	clpaifa2:function(cid, fid){   //工长只有看的权限
		var h1=$(window).height(),h2=document.body.scrollHeight,s1;
		if(h2>h1)h1=h2;
		var col='';
		var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:9999" >';
		s+='<div id="menulistshow_s" style="width: 150px; margin-top: 246.5px; position: fixed; left: 112.5px;position:fixed;-webkit-overflow-scrolling:touch" class="menulist r-border-r r-border-l">';
			/*s+='<div class="r-border-t" onclick="c.paifa('+cid+','+fid+');">材料配送</div>';
			s+='<div class="r-border-t" onclick="c.tuihuo('+cid+','+fid+');">退货</div>';*/
			s+='<div class="r-border-t" onclick="c.history('+cid+','+fid+');">历史配送记录</div>';		
		s+='</div>';
		s+='</div>';

		$('body').append(s);
		$('.menullss').toggle();
		return false;
		//js.clupdate('',{showid:'clupdateview'});
	},
	paifa:function(na, lx){
		js.paifa(na,lx);
	},
	tuihuo:function(na, lx){
		js.tuihuo(na,lx);
	},
	history:function(na, lx){
		js.history(na,lx);
	},
	changeuser:function(na, lx){
		js.changeuser(na,lx);
	},
	changeclear:function(na){
		js.changeclear(na);
	},
};