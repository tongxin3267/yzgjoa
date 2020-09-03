<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
	var submitfields = 'chuban,telephone,title,weizhi,author,designer,goods,type,status';
	if(adminid=='1')submitfields+=',type';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'admin',
		url:js.getajaxurl('publicsave','admin','system'),
		params:{int_filestype:'status,type,sort',add_otherfields:'adddt={now}',md5_filestype:'pass'},
		submitfields:submitfields,
		requiredfields:'name,user,ranking,deptname',
		success:function(){
			if(id==0)js.msg('success','成功添加帐号：'+h.form.user.value+'');
			closenowtabs();
			try{adminusermanage.reload();}catch(e){}
		},
		load:function(a){
			
		}
	});
	h.forminit();
	h.load(js.getajaxurl('loadclpaifa','clpaifa','main',{id:id}));
	console.log(h);
	if(adminid!='1')h.form.type.disabled=true;
	var c = {
		getdept:function(){
			js.getuser({
				nameobj:h.form.deptname,
				idobj:h.form.deptid,
				type:'dept',
				value:h.getValue('deptid'),
				title:'选择对应部门'
			});
		},
		getuser:function(){
			js.getuser({
				nameobj:h.form.superman,
				idobj:h.form.superid,
				type:'usercheck',
				value:h.getValue('superid'),
				title:'选择上级主管'
			});
		},
		removess:function(){
			h.form.superman.value='';
			h.form.superid.value='';
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	}
	
	js.initbtn(c);
	$('#dt1_{rand}').val(js.now());
});
</script>

<div align="center">
<div  style="padding:10px;width:700px">
	
<table bordercolor="#CCCCCC" class="ke-zeroborder" cellpadding="1" border="1" width="100%">
    <tbody>
        <tr>
            <td height="34" align="left" class="ys1" style="text-align:center;">业主姓名</td>
            <td class="ys2" style="text-align:left;"><?=$gongdiinfo['chuban']?></td>
            <td align="left" class="ys1" style="text-align:center;">联系方式</td>
            <td class="ys2" style="text-align:left;"><a href="tel:<?=$gongdiinfo['telephone']?>" class="hhhh"><?=$gongdiinfo['telephone']?></a></td>
        </tr>
        <tr>
            <td class="ys1" height="34" align="left" style="text-align:center;">项目名称</td>
            <td colspan="3" class="ys2" style="text-align:left;"><?=$gongdiinfo['title']?></td>
        </tr>
        <tr>
            <td height="34" align="left" class="ys1" style="text-align:center;">装修地址</td>
            <td colspan="3" class="ys2" style="text-align:left;"><?=$gongdiinfo['weizhi']?></td>
        </tr>
        <tr>
            <td align="left" class="ys1" width="22%" style="text-align:center;">工程监理</td>
            <td class="ys2" width="30%" style="text-align:left;"><?=$gongdiinfo['author']?></td>
            <td height="34" align="left" class="ys1" style="text-align:center;">设计师</td>
            <td class="ys2" style="text-align:left;"><?=$gongdiinfo['designer']?></td>
        </tr>
    </tbody>
</table>
	
	<form name="form_{rand}">
		<input name="id" value="0" type="hidden" />
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">       
		
		<tr>
			<td align="right">微信号：</td>
			<td class="tdinput"><input placeholder="手机号不能作为微信号"  onblur="js.replacecn(this)" name="weizhi" class="form-control"></td>
			<td align="right">姓名拼音：</td>
			<td class="tdinput"><input placeholder="拼音全拼(方便人员搜索)"  onblur="js.replacecn(this)" name="author" class="form-control"></td>
		</tr>
		
		
		<tr>
			
		</tr>
	
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button disabled class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
	
</div>
</div>