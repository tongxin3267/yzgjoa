<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var c={
		yulan:function(){
			var celstr = '渠道,编号,区域,客户姓名,联系方式,小区名称,面积,户型,装修时间,预算,风格,分配时间,硬装设计师,软装设计师,硬装状态,软装状态,竞争公司,合作公司,客户要求';
			var cont = mobjs.val(),s='',a,a1,i,j,oi=0,cela=celstr.split(',');
			s+='<table class="basetable" border="1">';
			s+='<tr><td></td>';
			for(i=0;i<cela.length;i++)s+='<td>'+cela[i]+'</td>';
			s+='</tr>';
			a = cont.split('\n');
			for(i=0;i<a.length;i++){
				if(a[i]){
					oi++;
					a1 = a[i].split('	');
					s+='<tr>';
					s+='<td>'+oi+'</td>';
					for(j=0;j<a1.length;j++)s+='<td>'+a1[j]+'</td>';
					s+='</tr>';
				}
			}
			s+='</table>';
			$('#showview_{rand}').html(s);
		},
		downss:function(){
			js.open('upload/base/custimport2.xls');
		},
		insrtss:function(){
			var val = mobjs.val();
			mobjs.val(val+'	');
			mobjs.focus();
		},
		saveadd:function(){
			var val = mobjs.val();
			var vis = 'msgview_{rand}';
			if(isempt(val)){
				js.setmsg('没有输入任何东西','', vis);
				return;
			}
			js.setmsg('处理中...','', vis);
			js.ajax(js.getajaxurl('addplcust','{mode}','{dir}'),{importcont:val},function(ds){
				if(ds.success){
					js.msg('success', ds.msg);
					closenowtabs();
					try{custmanagesss.reload();}catch(e){}
				}else{
					js.setmsg(ds.msg,'', vis);
				}
			},'post,json',function(){
				js.setmsg('','', vis);
			});
		}
	}
	var mobjs = $('#maincont_{rand}');
	mobjs.keyup(function(){
		c.yulan();
	});
	
	
	onpasteuserman = function(event){
		c.yulan();
	}
	js.initbtn(c);
});
</script>

<div align="left">
<div>请根据Excel格式添加数据，将数据复制到下面文本框中，<a click="downss" href="javascript:;">下载导入Excel表格格式</a><br>也可以手动输入，多行代表多记录，整行字段用	分开，<a click="insrtss" href="javascript:;">插入间隔符</a><br>
eg:'渠道	编号	区域	客户姓名	联系方式	小区名称	面积	户型	装修时间	预算	风格	分配时间	<span style="color:red">单源状态</span>	竞争公司	合作公司	客户要求'<br>
eg:土巴兔	J2017010336	金山新城	赵天龙	13599999999	湖山香颂湾	100-150	三房	2017/6/2	待定	现代简约	2017/5/27	<span style="color:red">1	</span>青衫	总部	空间布局

<br><span style="color:red">单源状态可选值（0-8） <br>0|待量单,1|无效单,2|已退单,3|重单,4|跟进单,5|意向单,6|失败单,7|已签单,8|待定单</span></div>
<div><textarea style="height:250px;" onpaste="onpasteuserman(event)" id="maincont_{rand}" class="form-control"></textarea></div>
<div id="showview_{rand}"></div>
<div style="padding:10px 0px"><a click="yulan" href="javascript:;">[预览]</a>&nbsp; &nbsp; <button class="btn btn-success"click="saveadd" type="button">提交添加</button>&nbsp; <span id="msgview_{rand}"></span></div>
<div class="tishi">请严格按照规定格式添加，否则数据将错乱，客户默认拥有者是当前用户</div>
</div>
