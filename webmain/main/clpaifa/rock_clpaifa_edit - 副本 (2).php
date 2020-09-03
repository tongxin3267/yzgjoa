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
			console.log(a);
		}
	});
	h.forminit();
	h.load(js.getajaxurl('loadclpaifa','clpaifa','main',{id:id}));
	if(adminid!='1')h.form.type.disabled=true;
});
</script>
<style type="text/css">
	.inputhidden{
    height: 40px;
    border: none;}
</style>

<div align="center">
<div  style="padding:10px;width:700px">

	<form name="form_{rand}">
		<table bordercolor="#CCCCCC" class="ke-zeroborder" cellpadding="1" border="1" width="100%">
			<tr>
				<td align="right">业主姓名：</td>
				<td class="tdinput"><input  name="chuban" class="inputhidden" readonly="readonly"></td>
				<td align="right">联系方式：</td>
				<td class="tdinput"><input   name="telephone" class="inputhidden" readonly="readonly"></td>
			</tr>
			
			<tr>
				<td align="right">项目名称：</td>
				<td class="tdinput"><input  name="title" class="inputhidden" readonly="readonly"></td>
				<td align="right">装修地址：</td>
				<td class="tdinput"><input   name="weizhi" class="inputhidden" readonly="readonly"></td>
			</tr>
			
			<tr>
				<td align="right">工程监理：</td>
				<td class="tdinput"><input  name="author" class="inputhidden" readonly="readonly"></td>
				<td align="right">设计师：</td>
				<td class="tdinput"><input   name="designer" class="inputhidden" readonly="readonly"></td>
			</tr>
		</table>
		<input name="id" value="0" type="hidden" />
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">       
		
		
		
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