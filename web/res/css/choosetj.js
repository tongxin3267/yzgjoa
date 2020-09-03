
$(document).ready(function(){
	var deptname=js.getoption('deptallname');
	var adminid=js.getoption('adminid');
	if (deptname=='元贞团队/终端客户') {
		$('#haha').remove();
		$('#main_index2').remove();
		$('#menu-btn1').addClass('hide');
		$('#menu-btn2').addClass('hide');
		$('#menu-btn4').addClass('hide');
		$('#menu-btn3').addClass('hide');
		$('#menu-btn5').addClass('hide');
		$('#menu-btn6').addClass('hide');
		$('#menu-btn7').addClass('hide');
		
		//$('#list').remove();
	}

	function isContains(str, substr) {
	    return str.indexOf(substr) >= 0;
	}

	if (isContains(deptname, '设计部')||deptname=='元贞团队/工程监理部'||deptname.indexOf("元贞团队/市场部")>=0) {
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
		//crm
		$('#menu-btn6').addClass('hide');
	}
	if (deptname=='元贞团队/预算部'||deptname=='元贞团队/形象建设部') {
		$('#menu-btn3').addClass('hide');
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
	}/*
	console.log(isContains(deptname, '设计部'));
	console.log(deptname);
	happy_add 因为市场部和设计部分组，之后组长要能根据组员筛选 begin 20171017
	*/
	if (isContains(deptname, '设计部')) {
		$('#menu-btn5').removeClass('hide');
	}
    if (isContains(deptname, '市场部')) {
        $('#menu-btn8').removeClass('hide');
        $('#menu-btn10').removeClass('hide');

    }
	//end 20171017
	/*
	if (deptname=='元贞团队/设计部'||deptname=='元贞团队/工程监理部'||deptname.indexOf("元贞团队/市场部")>=0) {
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
		//crm
		$('#menu-btn6').addClass('hide');
	}*/
	if (deptname=='元贞团队/预算部'||deptname=='元贞团队/形象建设部') {
		$('#menu-btn3').addClass('hide');
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
	}
	if (deptname=='元贞团队/人事&材料部') {
		$('#menu-btn5').addClass('hide');
	}
	if (isContains(deptname, '门店')) {
		//crm
		$('#menu-btn6').addClass('hide');
	}
	if (deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部') {
		//crm
		$('.xzkhdiv').removeClass('hide');
		$('#main').removeClass('hide');
        $('#menu-btn10').removeClass('hide');
        $('#menu-btn11').removeClass('hide');
		//$('#main2').addClass('hide');
	}
    if (isContains(deptname, '供应商') ||isContains(deptname, '供货商') ||isContains(deptname, '合作商')  || adminid==clskefuid) {
        $('#menu-btn5').addClass('hide');
        $('#menu-btn6').addClass('hide');
        $('#menu-btn10').addClass('hide');
    }
 	$.ajax({
        type: 'post', 
        url: "index.php?d=we&m=ying&a=loadData",
        data:{},
        dataType:'json', 
        success: function(ret){
        	if(ret.data.data== 0){
   				$('#mainbody').addClass('hide');
   				$('#empty').removeClass('hide');
   			}else{
   				$('#mainbody').removeClass('hide');
   				$('#empty').addClass('hide');
				dealdatas(ret);
   			}
      }});
});

var total=0;
function dealdatas(ret) {
			var data=ret.data.data,i,seriesdata=[],legenddata,xAxisdata=[],yAxisdata=[];
			var legenddata="合计";
			//var laiyuandata=['土巴兔','优居客', '丁丁网', 'icolor','凯特猫', '家要美', '电销部', '介绍', '进店', '来电咨询', '网上预约', '工程部营销', '其他'];
			var deptname=js.getoption('deptallname');
			var lai={},bota=ret.data.bo,bolegenddata=["合计"],boseriesdata=[];
			var laiyuan=[],b0=[];
			var online=0,offline=0;

			$('#lfl').html("量房率≈"+ret.data.lfl+"%");

			var laiyuanTecord=$('#laiyuanRecord').text();

			/*if (laiyuanTecord!='渠道' &&(deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部')) {
				$('#main').remove();
				$('#mainnew').html('<div class="chart" id="main"></div>');
			}*/
			$.each(data,function(index,item){ 
				xAxisdata.push(item.status);
				yAxisdata.push(item.ta);
				//计算总值
				total+=Number(item.ta);
				b0.push(item.ta);
				var sd={};
				sd.value=item.ta;
				sd.name=item.status;
				seriesdata.push(sd);
			});
			//初始合计值
				var bo1={};
				bo1.type='line';
				bo1.name="合计";
				bo1.data=b0;
				boseriesdata.push(bo1);

			var arr = ['土巴兔','优居客', '丁丁网', 'icolor','凯特猫', '家要美', '网上预约','齐家网','云空间','吉宅网','信用家','城居网','华一网','海派优装','土拔鼠','乐享屋']; 
			var arr2 = ['电销部', '介绍', '进店', '来电咨询', '工程部营销', '其他']; 
			
				$.each(bota,function(ll,ss){ 				
						bolegenddata.push(ll);
						var bod={};
						bod.type='bar';
						bod.name=ll;
						bod.data=ss;
						var flag=$.inArray(ll, arr);
						if (flag!=-1) {
							bod.stack='线上';//console.log(ss);online+=ss;
						}else{
							bod.stack='线下';//console.log(offline);offline+=ss;
						}
						boseriesdata.push(bod);
				});

			showdatas(legenddata,xAxisdata,yAxisdata,seriesdata,bolegenddata,boseriesdata);
}
function showdatas(legenddata,xAxisdata,yAxisdata,seriesdata,bolegenddata,boseriesdata) {
	var	legenddata2=[legenddata];			
	var byId = function(id) {
		return document.getElementById(id);
	};
		var option2 = {
		    title : {
		        text: '',
		        x:'center'
		    },
		    label: {
                normal: {
                    show: true,
                    position: 'right'
                }
            },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		       // orient: 'vertical',
		        left: 'left',
		        data: xAxisdata
		    },
		    series : [
		        {
		            name: '',
		            type: 'pie',
		            radius : '65%',
		            center: ['50%', '60%'],
		            data:seriesdata,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                },
		                normal:{ 
                            label:{ 
                               show: true, 
                               formatter: '({d}%)\n{b}:{c}' 
                            }, 
                            labelLine :{show:false}
                        } 
		            }
		        }
		    ]
		};
	// 指定图表的配置项和数据
        var option3 = {
            title: {
                text: '合计:'+total,
		        x: '50%',
		        textAlign: 'center'
            },
            tooltip: {
		        trigger: 'axis',
		        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
		            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
		        }
		    },
            legend: {
                data:'legenddata2'
            },
            label: {
                normal: {
                    show: true,
                    position: 'top'
                }
            },
			xAxis: [{
				type: 'category',
				data: xAxisdata,
				axisLabel:{
                    rotate:30,interval: 0}
			}],
            yAxis: {},
			series: [{
				name: legenddata,
				type: 'bar',
				data: yAxisdata
			}]
        };

	// 指定图表的配置项和数据
        var option = {
            title: {
                text: ''
            },
            tooltip:{
		        trigger: 'axis',
		        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
		            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
		        }
		    },
            legend: {
                data:bolegenddata,
		      //  orient: 'vertical',
		       // left: 'left',top: '-2%',
            },
		    grid: {
		        left: '2%',
		        right: '1%',
		        bottom: '3%',
		        //top: '-3%',
		        containLabel: true
		    },
			xAxis: [{
				type: 'category',
				data: xAxisdata,
				axisLabel:{
                    rotate:30,interval: 0}
			}],
            yAxis: {},
			series: boseriesdata,
        };
		// 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(byId('main'));
        // 使用刚指定的配置项和数据显示图表。,true表示每次重新更新
        myChart.setOption(option,true);

		// 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(byId('main1'));
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option3,true);
		// 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(byId('main2'));
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option2,true);
        total=0;
    }


mui.init({
	swipeBack:true //启用右滑关闭功能
});
var menuWrapper = document.getElementById("menu-wrapper");
var menu = document.getElementById("menu");
var menuWrapperClassList = menuWrapper.classList;
var backdrop = document.getElementById("menu-backdrop");

//方便根据品牌动态获取设计师、监理等 20180312
var brandRes=$('#brandRe').text();

mui('.tab_bar').on('tap', '#menu-btn1', function() {
	$('#menu').html('');
	var add='<div class="Area" id="areaSearch">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
		$('#menu-btn1').addClass('active');	
	$('#menu').append(add);	
	address(list[0].city);
});
mui('.tab_bar').on('tap', '#menu-btn2', function() {
	$('#menu').html('');
	var add='<div class="Area" id="timeSearch">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
		$('#menu-btn2').addClass('active');	
	$('#menu').append(add);	
	address(dtime[0].city);
});

mui('.tab_bar').on('tap', '#menu-btn3', function() {
	$('#menu').html('');
		js.ajax('index','loadbookdatacourse',{'setid':'45'},function(ret){		
			var f='<div class="Area" id="flowcouse"><div class="block AreaAll" ><ul>';
					f+='<li class="mui-table-view-cell">'
						+'<a href="javascript:;" id="">全部</a>'
					+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell">'
						+'<a href="javascript:;" id="'+n.id+'">'+n.name+'</a>'
					+'</li>';
				});
			f+='</ul></div></div>';
			$('#menu').append(f);
			mui('#flowcouse').on('tap', 'a',Prompt3);	
		});		
});

mui('.tab_bar').on('tap', '#menu-btn4', function() {
	$('#menu').html('');
		js.ajax('index','loaddesigndata',{'setid':'8'},function(ret){		
			if (ret.length>1) {

				var f='<div class="Area" id="project"><div class="block AreaAll" ><ul>';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="">全部</a>'
						+'</li>';
					$.each(ret,function(i,n)
					{
						f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="'+n.id+'">'+n.name+'</a>'
						+'</li>';
					});
				f+='</ul></div></div>';
				$('#menu').append(f);
				mui('#project').on('tap', 'a',Prompt4);	
			}else{
				$('#menu').append("<span style='font-size:12px'>非常抱歉，您当前没有工程监理筛选权限</span>");
				//$('#menu').css("height","auto");
				$('#menu-btn4').addClass('hide');
			}
		});		
});
//设计师
mui('.tab_bar').on('tap', '#menu-btn5', function() {
	$('#menu').html('');
		js.ajax('index','loadshichangdata',{'setid':'designdetpid','brandRes':brandRes},function(ret){
			//console.log(ret.length);
			if (ret.length>0) {

			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="design"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
					$.each(ret,function(i,n)
					{
						f+='<li class="mui-table-view-cell mui-indexed-list-item">'
							// +'<a href="javascript:;" id="'+n.id+'">'+n.name+'</a>'
							+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
						+'</li>';
					});
				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#design').on('tap', '#all',Prompt5);
				indexSearch('desginRecord');
				$('#menu-btn5').toggleClass('active');	
			}else{

				if (deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部') {
					
					$('#menu').append("<span style='font-size:12px'>非常抱歉，当前品牌没有设计师</span>");
				}else{

					$('#menu').append("<span style='font-size:12px'>非常抱歉，您当前没有设计师筛选权限</span>");
					//$('#menu').css("height","auto");
					$('#menu-btn5').addClass('hide');
				}
			}

		});		
});
//渠道
mui('.tab_bar').on('tap', '#menu-btn6', function() {
	$('#menu').html('');
		js.ajax('index','loadelementdata',{'mid':'7','fields':'laiyuan'},function(ret){		
			
			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="laiyuan"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell mui-indexed-list-item">'
						+'<input type="checkbox" style="top: 2px;float:right"/>'+n+''
					+'</li>';
				});

				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#laiyuan').on('tap', '#all',Prompt6);
				indexSearch('laiyuanRecord');
				$('#menu-btn6').toggleClass('active');	
		});		
});
//筛选-----根据js筛选
mui('.tab_bar').on('tap', '#menu-btn7', function() {
	$('#menu').html('');
	var add='<div class="Area" id="unitname">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'</div>';
	$('#menu-btn7').toggleClass('active');	
	$('#menu').append(add);	
	address_mulity(sxcontent[0].city,'unit');
});

//品牌
mui('.tab_bar').on('tap', '#menu-btn10', function() {
	$('#menu').html('');

		js.ajax('index','loadelementdata',{'mid':'7','fields':'yzbrand'},function(ret){
			var f='<div class="Area" id="brand"><div class="block AreaAll" ><ul>';
			f+='<li class="mui-table-view-cell">'
				+'<a href="javascript:;" sid="">全部</a>'
			+'</li>';
			$.each(ret,function(i,n)
				{
					var b=n.split("|");
					n=b[1];
					i=b[0];
					f+='<li class="mui-table-view-cell"><a href="javascript:;" sid="'+i+'">'+n+'</a></li>';

				});
			f+='</ul></div></div>';
			$('#menu').append(f);
			mui('#brand').on('tap', 'a',Prompt10);
		});
	// mui('#brand').on('tap', 'a',Prompt10);
});
//供货商
mui('.tab_bar').on('tap', '#menu-btn11', function() {
    $('#menu').html('');
    js.ajax('index','loadsupplierdata',{},function(ret){

/*
			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="supplier"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell mui-indexed-list-item">'
						+'<input type="checkbox" style="top: 2px;float:right" value="'+n.id+'"/>'+n.name+''
					+'</li>';
				});

				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#supplier').on('tap', '#all',Prompt11);
				indexSearch('clgysRecord');
				$('#menu-btn11').toggleClass('active');	*/
        //console.log(ret.length);
		var f='<div class="Supplier Area" id="supplier"><div class="block SupplierAll" ><ul>';
		f+='<li class="mui-table-view-cell">'
			+'<a href="javascript:;" id="">全部</a>'
			+'</li>';
		$.each(ret,function(i,n)
		{
			f+='<li class="mui-table-view-cell">'
				+'<a href="javascript:;" id="'+n.id+'">'+n.name+'</a>'
				+'</li>';
		});
		f+='</ul></div></div>';
		$('#menu').append(f);
		mui('#supplier').on('tap', 'a',Prompt11);
    });
});
/*//筛选-----根据数据库筛选
mui('.tab_bar').on('tap', '#menu-btn7', function() {
	$('#menu').html('');
		js.ajax('index','loadelementdata',{'mid':'7','fields':'unitname'},function(ret){		
			var f='<div class="Area" id="unitname"><div class="block AreaAll" ><ul>';
			f+='<li class="mui-table-view-cell">'
						+'<a href="javascript:;" id="">全部</a>'
					+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell">'
						+'<a href="javascript:;" id="'+i+'">'+n+'</a>'
					+'</li>';
				});
			f+='</ul></div></div>';
			$('#menu').append(f);
			mui('#unitname').on('tap', 'a',Prompt7);	
		});		
});*/

backdrop.addEventListener('tap', toggleMenu);
document.getElementById("menu-btn4").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn3").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn2").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn1").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn5").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn6").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn7").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn11").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn10").addEventListener('tap', toggleMenu);
//下沉菜单中的点击事件

var busying = false;

function toggleMenu() {

	if (busying) {
		return;
	}
	busying = true;
	if (menuWrapperClassList.contains('mui-active')) {
		document.body.classList.remove('menu-open');
		menuWrapper.className = 'menu-wrapper fade-out-up animated';
		menu.className = 'menu mui-icon-arrowup animated';
		setTimeout(function() {
			backdrop.style.opacity = 0;
			menuWrapper.classList.add('hidden');
		}, 500);
	} else {
		document.body.classList.add('menu-open');
		menuWrapper.className = 'menu-wrapper fade-in-down animated mui-active';
		menu.className = 'menu mui-icon mui-icon-arrowdown animated';
		backdrop.style.opacity = 1;
	}
	setTimeout(function() {
		busying = false;
	}, 500);
}

function indexSearch(id){

		var header = document.querySelector('header.mui-bar');
		var list = document.getElementById('list');
		var done = document.getElementById('done');
		//create
		window.indexedList = new mui.IndexedList(list);
		// console.log(indexedList);
		//done event
		done.addEventListener('tap', function() {
			var checkboxArray = [].slice.call(list.querySelectorAll('input[type="checkbox"]'));
			var checkedValues = [];
			var checkedID = [];
			checkboxArray.forEach(function(box) {
				if (box.checked) {
					checkedValues.push(box.parentNode.innerText);
					if (id=='statusRecord') {
						checkedID.push(box.value);
					}
				}
			});
			if (checkedValues.length > 0) {

				$('#'+id).html(''+checkedValues);
				if (id=='statusRecord') {
					$('#statusRe').html(''+checkedID);
				}
				commonSearch();
			}
			
		}, false);
		mui('.mui-indexed-list-inner').on('change', 'input', function() {
			var count = list.querySelectorAll('input[type="checkbox"]:checked').length;
			var value = count ? "完成(" + count + ")" : "完成";
			done.innerHTML = value;
			if (count) {
				if (done.classList.contains("mui-disabled")) {
					done.classList.remove("mui-disabled");
				}
			} else {
				if (!done.classList.contains("mui-disabled")) {
					done.classList.add("mui-disabled");
				}
			}
		});
}
/*顶部条件选择*/

function address_mulity(data,type) {
    $(".AreaCenter ul").html('');
    $.each(data, function(i, v) {
        $(".AreaCenter ul").append('<li data-id=' + i + '>' + v.name + '</li>');
    });
    var dogg=data;
	var html='';
    $.each(dogg, function(index, item) {    	
			html+='<div class="block AreaRight RightArea'+index+'" style="display: n2one;"><ul>'
			+'</ul>';
        	html+='</div>';
    })
	$('.Area').append(html);	

    $.each(dogg, function(index, item) { 
	    $.each(dogg[index].area, function(i, item) {
    		$(".RightArea"+index+" ul").append('<li class="mui-table-view-cell mui-indexed-list-item "><input type="checkbox" name="' + type + '" value=' + item + ' style="top: 2px;float:right;"/>' + item + '</li>')

	    })
    })

	mui('.AreaCenter ul').on('tap', 'li', function(e) {
		    var id = $(this).attr('data-id');
		    $(".AreaRight").hide();
		    $(".RightArea"+id).show();
		    $('.AreaCenter ul li').removeClass('active')
		    $(this).addClass('active');
		    $(".AreaCenter").css({
		        "width": "40%"
		    });
		/*mui('#timeSearch ul').on('tap', 'li', Prompt2);
		mui('#timeSearch1 ul').on('tap', 'li', PromptTime);*/

	})

	var add='</div><div class="opt_box">'
               + '<a href="javascript:;" id="' + type + '" class="btn btn_green" data-act="submit" style="    color: white;">确定</a>'
            +'</div>';
	$('#menu').append(add);	
	mui('#menu').on('tap', '#unit', Prompt7);
	mui('#menu').on('tap', '#area', Prompt);

}

function address(data) {
    $(".AreaCenter ul").html('');
    $.each(data, function(i, v) {
        $(".AreaCenter ul").append('<li data-id=' + i + '>' + v.name + '</li>');
    });
    var dogg=data;
	mui('.AreaCenter ul').on('tap', 'li', function(e) {

		    $('.AreaCenter ul li').removeClass('active')
		    $(this).addClass('active');
		   // var data = data.data
		    $(".AreaCenter").css({
		        "width": "40%"
		    });
		    $(".AreaRight").show();
		    var id = $(this).attr('data-id');
		    $(".AreaRight ul").html('');
		    $.each(dogg[id].area, function(index, item) {
		        $(".AreaRight ul").append('<li data-cid=' + index + '>' + item + '</li>')
		    })
		mui('#areaSearch ul').on('tap', 'li', Prompt);
		mui('#timeSearch ul').on('tap', 'li', Prompt2);
		mui('#unitname ul').on('tap', 'li', Prompt7);

	})
}

function Prompt() {
	var area1=$(".AreaCenter .active").text();
	var area2=$(this).text();
	//area1==area2?area=area1+'-'+area2:area=area2;
	if (area2=='全部') {		
		$('#areaRecord').html('区域');	
		commonSearch();
		$('#menu-btn1').removeClass('active');	
	}else{
		$('#areaRecord').html(area2);
		commonSearch();
		$('#menu-btn1').addClass('active');
	}	
}
function Prompt2() {
	var time1=$(".AreaCenter .active").text();
	var time2=$(this).text();
	time1==time2?time=time2:time=time1+'-'+time2;
	if (time=='全部') {		
		$('#timeRecord').html('全部');	
		commonSearch();
		$('#menu-btn2').removeClass('active');	
	}else{
		$('#timeRecord').html(time);	
		commonSearch();
		$('#menu-btn2').addClass('active');	
	}
}

function Prompt3() {

	var courseRecord=$(this).text();	
	if (courseRecord=='全部') {		
		$('#courseRecord').html('工地进度');	
		commonSearch();
		$('#menu-btn3').removeClass('active');	
	}else{
		$('#courseRecord').html(courseRecord);
		commonSearch();
		$('#menu-btn3').addClass('active');	
	}
}
function Prompt4() {
	var projectRecord=$(this).text();
	if (projectRecord=='全部') {		
		$('#projectRecord').html('工程监理');	
		commonSearch();
		$('#menu-btn4').removeClass('active');	
	}else{
		$('#projectRecord').html(projectRecord);
		commonSearch();
		$('#menu-btn4').addClass('active');	
	}
}
function Prompt5() {
	
	var desginRecord=$(this).text();

	if (desginRecord=='全部') {		
		$('#desginRecord').html('设计师');	
		commonSearch();
		$('#menu-btn5').removeClass('active');	
	}else{
		$('#desginRecord').html(desginRecord);
		commonSearch();
		$('#menu-btn5').addClass('active');	
	}
}

function Prompt6() {
	
	var laiyuanRecord=$(this).text();

	if (laiyuanRecord=='全部') {		
		$('#laiyuanRecord').html('渠道');	
		commonSearch();
		$('#menu-btn6').removeClass('active');	
	}else{
		$('#laiyuanRecord').html(laiyuanRecord);
		commonSearch();
		$('#menu-btn6').addClass('active');	
	}
}

//筛选-----根据js筛选
function Prompt7() {
    var str = "";
    $("input[name='unit']:checked").each(function (index, item) {
        
        if ($("input[name='unit']:checked").length-1==index) {
            str += $(this).val();
        } else {
            str += $(this).val() + ",";
        }  
    });
	if (str=='全部') {		
		$('#unitnameRecord').html('筛选');	
		commonSearch();
		$('#menu-btn7').removeClass('active');	
	}else{
		$('#unitnameRecord').html(str);
		commonSearch();
		$('#menu-btn7').addClass('active');
	}	
}
//品牌
function Prompt10() {
	var brandname=$(this).text();
	var	brand=$(this).attr("sid");

	brandRes=brand;
	$('#brandRe').html(brand);	
	//area1==area2?area=area1+'-'+area2:area=area2;
	if (brandname=='全部') {		
		$('#brandRecord').html('品牌');	
		commonSearch();
		$('#menu-btn10').removeClass('active');	
	}else{
		$('#brandRecord').html(brandname);
		commonSearch();
		$('#menu-btn10').addClass('active');
	}	

}

function Prompt11() {
    var supplierRecord=$(this).text();
    var supplierId=this.id;

    if (supplierRecord=='全部') {
        $('#supplierRecord').html('供货商');
        commonSearch();
        $('#menu-btn11').removeClass('active');
    }else{
        $('#supplierRecord').html(supplierRecord);
        $('#supplierRecord').data('supplierId',supplierId);
        commonSearch();
        $('#menu-btn11').addClass('active');
    }
}
/*
function Prompt7() {
	toggleMenu();
	var unitnameRecord=$(this).text();

	if (unitnameRecord=='全部') {		
		$('#unitnameRecord').html('筛选');	
		commonSearch();
		$('#menu-btn7').removeClass('active');	
	}else{
		$('#unitnameRecord').html(unitnameRecord);
		commonSearch();
		$('#menu-btn7').addClass('active');	
	}
}*/

function Areas(data) {
}
function commonSearch() {

	var areaRecord=$('#areaRecord').text();	 	 areaRecord=='区域'?areaRecord='':'basejm_'+jm.base64encode(areaRecord)+'';
	var timeRecord=$('#timeRecord').text();	 	 timeRecord=='日期'?timeRecord='':'basejm_'+jm.base64encode(timeRecord)+'';
	var courseRecord=$('#courseRecord').text();	 courseRecord=='工地进度'?courseRecord='':'basejm_'+jm.base64encode(courseRecord)+'';
	var projectRecord=$('#projectRecord').text();projectRecord=='工程监理'?projectRecord='':'basejm_'+jm.base64encode(projectRecord)+'';
	var desginRecord=$('#desginRecord').text();	 desginRecord=='设计师'?desginRecord='':'basejm_'+jm.base64encode(desginRecord)+'';
    var laiyuanRecord=$('#laiyuanRecord').text();laiyuanRecord=='渠道'?laiyuanRecord='':'basejm_'+jm.base64encode(laiyuanRecord)+'';
    var supplierRecord=$('#supplierRecord').text();
    if(supplierRecord=='供货商'){
        var supplierId='';
        var supplierName='';
    }else{
        var supplierId=$('#supplierRecord').data('supplierId');
        var supplierName=supplierRecord;
    }
	var unitnameRecord=$('#unitnameRecord').text();	 unitnameRecord=='筛选'?unitnameRecord='':'basejm_'+jm.base64encode(unitnameRecord)+'';
	var brandRe=$('#brandRe').text();brandRe=='品牌'?brandRe='':'basejm_'+jm.base64encode(brandRe)+'';

		var key = $('#search_input').val();
		$('#tan').hide();toggleMenu();
		mo='mode';/*
		js.ajax('index','getyydata',
			{'page':1,'event':yy.nowevent,'num':yy.num,'areaSearch':areaRecord,'timeRecord':timeRecord,
			'courseRecord':courseRecord,'projectRecord':projectRecord,'desginRecord':desginRecord,
			'laiyuanRecord':laiyuanRecord,'unitnameRecord':unitnameRecord,'key':key},function(ret){
			yy.showdata(ret);
		},mo, false,false, 'get');*/

 	$.ajax({
        type: 'post', 
        url: "index.php?d=we&m=ying&a=loadData",
        data:{'areaSearch':areaRecord,'timeRecord':timeRecord,
			'courseRecord':courseRecord,'projectRecord':projectRecord,'desginRecord':desginRecord,
			'laiyuanRecord':laiyuanRecord,'unitnameRecord':unitnameRecord,'key':key,'brandRe':brandRe,'supplierId':supplierId,'supplierName':supplierName},
        dataType:'json', 
        success: function(ret){
    
        	if(ret.data.data== 0){
   				$('#mainbody').addClass('hide');
   				$('#empty').removeClass('hide');
   			}else{
   				$('#mainbody').removeClass('hide');
   				$('#empty').addClass('hide');
				dealdatas(ret);
   			}
      }});

}


mui('.AreaLeft ul').on('tap', 'li', function() {
    $(".AreaLeft ul li").removeClass('active')
    $(this).addClass('active');
    if ($(this).index() == 0) {
        address(list[0].city);
        $(".AreaCenter").css({
            "width": "70%"
        });
        $(".AreaRight").hide();
    } else {
        address(dt[0].city);
        $(".AreaCenter").css({
            "width": "70%"
        });
        $(".AreaRight").hide();
    }
})
