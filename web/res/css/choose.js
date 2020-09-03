mui.init({
	swipeBack:true //启用右滑关闭功能
});
var menuWrapper = document.getElementById("menu-wrapper");
var menu = document.getElementById("menu");
var menuWrapperClassList = menuWrapper.classList;
var backdrop = document.getElementById("menu-backdrop");

//方便根据品牌动态获取设计师、监理等 20180312
var brandRes=$('#brandRe').text();

// console.log(1111);
var deptname=js.getoption('deptallname');

function getParam(paramName) { 
    paramValue = "", isFound = !1; 
    if (this.location.search.indexOf("?") == 0 && this.location.search.indexOf("=") > 1) { 
        arrSource = unescape(this.location.search).substring(1, this.location.search.length).split("&"), i = 0; 
        while (i < arrSource.length && !isFound) arrSource[i].indexOf("=") > 0 && arrSource[i].split("=")[0].toLowerCase() == paramName.toLowerCase() && (paramValue = arrSource[i].split("=")[1], isFound = !0), i++ 
    } 
    return paramValue == "" && (paramValue = null), paramValue 
} 
mui('.tab_bar').on('tap', '#menu-btn1', function() {
	$('#menu').html('');
	var add='<div class="Area" id="areaSearch">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
	$('#menu-btn1').toggleClass('active');	
	$('#menu').append(add);	

	// address_mulity(list[0].city,'area');
	address(list[0].city);
});
mui('.tab_bar').on('tap', '#menu-btn2', function() {
	$('#menu').html('');
	var add='<div class="Area" id="timeSearch">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
	$('#menu-btn2').toggleClass('active');	
	$('#menu').append(add);	
	address(dtime[0].city);
});

mui('.tab_bar').on('tap', '#menu-btn3', function() {
	$('#menu').html('');
	console.log(yy.nowevent);
	var setid=yy.nowevent=='rgfdaib'?56:45;

		js.ajax('index','loadbookdatacourse',{'setid':setid,'brandRes':brandRes},function(ret){		


			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="flowcouse"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
					$.each(ret,function(i,n)
					{
						f+='<li class="mui-table-view-cell mui-indexed-list-item">'
							+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
						+'</li>';
					});
				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#flowcouse').on('tap', '#all',Prompt3);
				indexSearch('courseRecord');
				$('#menu-btn3').toggleClass('active');	
				
		});		
});

//监理
mui('.tab_bar').on('tap', '#menu-btn4', function() {
	$('#menu').html('');
		js.ajax('index','loadshichangdata',{'setid':'jianlidetpid','brandRes':brandRes},function(ret){		
			

			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="project"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
					$.each(ret,function(i,n)
					{
						f+='<li class="mui-table-view-cell mui-indexed-list-item">'
							+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
						+'</li>';
					});
				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#project').on('tap', '#all',Prompt4);
				indexSearch('projectRecord');
				$('#menu-btn4').toggleClass('active');	
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

//市场部
mui('.tab_bar').on('tap', '#menu-btn8', function() {
	$('#menu').html('');
		js.ajax('index','loadshichangdata',{'setid':'shichangdetpid','brandRes':brandRes},function(ret){	
			if (ret.length>0) {	

			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="shichang"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell mui-indexed-list-item">'
						+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
					+'</li>';
				});

				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#shichang').on('tap', '#all',Prompt8);
				indexSearch('shichangRecord');
				$('#menu-btn8').toggleClass('active');	

			}else{
				if (deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部') {
					
					$('#menu').append("<span style='font-size:12px'>非常抱歉，当前品牌没有家装顾问</span>");
				}else{
					$('#menu').append("<span style='font-size:12px'>非常抱歉，您当前没有家装顾问筛选权限</span>");
					$('#menu-btn8').addClass('hide');
				}
			}
		});		
});
//单源状态筛选

//市场部
mui('.tab_bar').on('tap', '#menu-btn9', function() {
	$('#menu').html('');

			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="status"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell mui-indexed-list-item">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';

				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="0"/>待量单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="1"/>无效单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="2"/>已退单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="3"/>重单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="4"/>跟进单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="5"/>意向单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="6"/>失败单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="7"/>已签单</li>';
				f+='<li class="mui-table-view-cell mui-indexed-list-item"><input type="checkbox" style="top: 2px;float:right" value="8"/>待定单</li>';
				
				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#status').on('tap', '#all',Prompt9);
				indexSearch('statusRecord');	
				$('#menu-btn9').toggleClass('active');	
});

//品牌
mui('.tab_bar').on('tap', '#menu-btn10', function() {
	$('#menu').html('');
		js.ajax('index','loadelementdata',{'mid':'7','fields':'yzbrand'},function(ret){		
			var f='<div class="Area" id="brand"><div class="block AreaAll" ><ul class="mui-table-view">';
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
			$('#menu-btn10').toggleClass('active');	
		});		
});

//监理
mui('.tab_bar').on('tap', '#author', function() {
	$('#menu').html('');
	js.ajax('index','loadbuildindata',{'setid':'author'},function(ret){
			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="authors"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell mui-indexed-list-item">'
						+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
					+'</li>';
				});

				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#authors').on('tap', '#all',PromptAuthor);
				indexSearch('authorRecord');
				$('#author').toggleClass('active');	
	});
});
//供应商
mui('.tab_bar').on('tap', '#clgys', function() {
	var load="clgys";
	if (yy.num=='clpaifa') {
		var load="fucaigys";
	}
	$('#menu').html('');
	js.ajax('index',"loadbuildindata",{'setid':load},function(ret){	

			var f='<div id="list" class="mui-indexed-list">'
				+'<div class="mui-indexed-list-search mui-input-row mui-search">'
					+'<input type="search" class="mui-input-clear mui-indexed-list-search-input" style="width: 80%;" placeholder="搜索">'
				+'<a id="done" class="mui-btn mui-btn-link mui-pull-right mui-btn-blue mui-disabled">完成</a></div>';
				f+='<div class="Area" id="clgyser"><div class="block AreaAll" >';
				f+='<div class="mui-indexed-list-inner"><ul class="mui-table-view">';
				f+='<li class="mui-table-view-cell">'
							+'<a href="javascript:;" id="all">全部</a>'
						+'</li>';
				$.each(ret,function(i,n)
				{
					f+='<li class="mui-table-view-cell mui-indexed-list-item">'
						+'<input type="checkbox" style="top: 2px;float:right"/>'+n.name+''
					+'</li>';
				});

				f+='</ul></div>';
				f+='</div></div></div>';
				$('#menu').append(f);
				mui('#clgyser').on('tap', '#all',PromptClgys);
				indexSearch('clgysRecord');
				$('#clgys').toggleClass('active');	
	});
});
// 时间1    按月或者季度
mui('.tab_bar').on('tap', '#time1', function() {
	$('#menu').html('');
	var add='<div class="Area" id="timeSearch1">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
	$('#time1').toggleClass('active');	
	$('#menu').append(add);	
	address(dtime[0].city);
});
/*
// 时间2
mui('.tab_bar').on('tap', '#time2', function() {
	$('#menu').html('');
	var add='<div class="Area" id="timeSearch2">'
			+'<div class="block AreaCenter" style="width: 40%;"><ul></ul></div>'
			+'<div class="block AreaRight" style="display: block;"><ul>'
			+'</ul></div>';
		$('#time2').addClass('active');	
	$('#menu').append(add);	
	address(dtime[0].city);
});*/

backdrop.addEventListener('tap', toggleMenu);
document.getElementById("menu-btn4").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn3").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn2").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn1").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn5").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn6").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn7").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn8").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn9").addEventListener('tap', toggleMenu);
document.getElementById("menu-btn10").addEventListener('tap', toggleMenu);
document.getElementById("author").addEventListener('tap', toggleMenu);
document.getElementById("clgys").addEventListener('tap', toggleMenu);
document.getElementById("time1").addEventListener('tap', toggleMenu);
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
		menu.className = 'menu ';
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

		mui('#timeSearch1 ul').on('tap', 'li', PromptTime);

	})
}

/*顶部条件选择*/

function address_third(data) {
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

		mui('#timeSearch1 ul').on('tap', 'li', PromptTime);

	})
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

function PromptTime() {
	var time1=$(".AreaCenter .active").text();
	
	var time2=$(this).text();
	time1==time2?time=time2:time=time1+'-'+time2;
	if (time=='全部') {		
		$('#timeRecord1').html('全部');	
		commonSearch();
		$('#time1').removeClass('active');	
	}else{
		$('#timeRecord1').html(time);	
		commonSearch();
		$('#time1').addClass('active');	
	}
}

function Prompt() {
	var area1=$(".AreaCenter .active").text();
	var area2=$(this).text();

    /*var str = "";   地区多选
    $("input[name='area']:checked").each(function (index, item) {
        
        if ($("input[name='area']:checked").length-1==index) {
            str += $(this).val();
        } else {
            str += $(this).val() + ",";
        }  
    });*/
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
		$('#courseRecord').html('进度');	
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
		$('#projectRecord').html('监理');	
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

function Prompt8() {
	
	var shichangRecord=$(this).text();

	if (shichangRecord=='全部') {		
		$('#shichangRecord').html('顾问');	
		commonSearch();
		$('#menu-btn8').removeClass('active');	
	}else{
		$('#shichangRecord').html(shichangRecord);
		commonSearch();
		$('#menu-btn8').addClass('active');	
	}
}

//筛选-----根据js筛选

function Prompt9() {
	
	var statusname=$(this).text();

	$('#statusRe').html('');	
	//area1==area2?area=area1+'-'+area2:area=area2;
	if (statusname=='全部') {		
		$('#statusRecord').html('状态');	
		commonSearch();
		$('#menu-btn9').removeClass('active');	
	}else{
		$('#statusRecord').html(statusname);
		commonSearch();
		$('#menu-btn9').addClass('active');
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
/*
function Prompt7() {
	
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

function PromptAuthor() {
	
	var authorRecord=$(this).text();
	if (authorRecord=='全部') {		
		$('#authorRecord').html('监理');	
		commonSearch();
		$('#author').removeClass('active');	
	}else{
		$('#authorRecord').html(authorRecord);
		commonSearch();
		$('#author').addClass('active');	
	}
}

function PromptClgys() {
	
	var clgysRecord=$(this).text();
	if (clgysRecord=='全部') {		
		$('#clgysRecord').html('材料供应商');	
		commonSearch();
		$('#clgys').removeClass('active');	
	}else{
		$('#clgysRecord').html(clgysRecord);
		commonSearch();
		$('#clgys').addClass('active');	
	}
}

function Areas(data) {
}
function commonSearch() {

	var areaRecord=$('#areaRecord').text();	 	 areaRecord=='区域'?areaRecord='':'basejm_'+jm.base64encode(areaRecord)+'';
	var timeRecord=$('#timeRecord').text();	 	 timeRecord=='日期'?timeRecord='':'basejm_'+jm.base64encode(timeRecord)+'';
	var courseRecord=$('#courseRecord').text();	 courseRecord=='进度'?courseRecord='':'basejm_'+jm.base64encode(courseRecord)+'';
	var projectRecord=$('#projectRecord').text();projectRecord=='监理'?projectRecord='':'basejm_'+jm.base64encode(projectRecord)+'';
	var shichangRecord=$('#shichangRecord').text();shichangRecord=='顾问'?shichangRecord='':'basejm_'+jm.base64encode(shichangRecord)+'';
	var desginRecord=$('#desginRecord').text();	 desginRecord=='设计师'?desginRecord='':'basejm_'+jm.base64encode(desginRecord)+'';
	var laiyuanRecord=$('#laiyuanRecord').text();laiyuanRecord=='渠道'?laiyuanRecord='':'basejm_'+jm.base64encode(laiyuanRecord)+'';
	var unitnameRecord=$('#unitnameRecord').text();	 unitnameRecord=='筛选'?unitnameRecord='':'basejm_'+jm.base64encode(unitnameRecord)+'';

	//主材派发
	var timeRecord1=$('#timeRecord1').text();timeRecord1=='日期'?timeRecord1='':'basejm_'+jm.base64encode(timeRecord1)+'';
	var authorRecord=$('#authorRecord').text();authorRecord=='监理'?authorRecord='':'basejm_'+jm.base64encode(authorRecord)+'';
	var clgysRecord=$('#clgysRecord').text();clgysRecord=='材料供应商'?clgysRecord='':'basejm_'+jm.base64encode(clgysRecord)+'';
	//var statusRecord=$('#statusRecord').text();statusRecord=='市场部'?statusRecord='':'basejm_'+jm.base64encode(statusRecord)+'';
	var statusRe=$('#statusRe').text();statusRe=='状态'?statusRe='':'basejm_'+jm.base64encode(statusRe)+'';
	var brandRe=$('#brandRe').text();brandRe=='品牌'?brandRe='':'basejm_'+jm.base64encode(brandRe)+'';
	//var statusRecord=$('#statusRecord').text();
	//var statusRe=$('#statusRe').text();statusRe==''?statusRe='':'basejm_'+jm.base64encode(statusRe)+'';
	//status==''?status='':'basejm_'+jm.base64encode(status)+'';
// console.log(brandRe);
	$('#tan').hide();toggleMenu();
		var key = $('#search_input').val();
		mo='mode';
		js.ajax('index','getyydata',
			{'page':1,'event':yy.nowevent,'num':yy.num,'areaSearch':areaRecord,'timeRecord':timeRecord,'time1':timeRecord1,'author':authorRecord,'clgys':clgysRecord,
			'courseRecord':courseRecord,'projectRecord':projectRecord,'shichangRecord':shichangRecord,'desginRecord':desginRecord,
			'laiyuanRecord':laiyuanRecord,'unitnameRecord':unitnameRecord,'key':key,'status':statusRe,'brandRe':brandRe},function(ret){
			yy.showdata(ret);
		},mo, false,false, 'get');

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
