//设定材料商的专属客服userID
var clskefuid=513,isshow=true;
var myScroll=false,yy={
	sousoukey:'',
	resizehei:function(){
		var hei
	if (/LT-APP/.test(navigator.userAgent)) {
			$('#hhhhhhhhhh').remove();

		 hei= this.getheight()+50;
	} else {

		 hei= this.getheight();
	}
	if (yy.num=='daiban'|| yy.num=='customer'|| yy.num=='customerAnalyze'){
		$('#searchBar').removeClass('hide');
	}

	if (yy.num=='daiban') {
		$('#menu-btn3').removeClass('hide');
		$('#menu-btn4').removeClass('hide');
	}else{
		$('#menu-btn6').removeClass('hide');
		$('#menu-btn9').removeClass('hide');
		$('#menu-btn7').removeClass('hide');

	}
	var deptname=js.getoption('deptallname');
	var adminid=js.getoption('adminid');
	yy.totalshow=false;//oa工地统计，只显示管理员，财务部，门店，设计部
	if (isContains(deptname, '管理层')||deptname.indexOf("财务部")>=0||deptname.indexOf("门店")>=0||isContains(deptname, '设计部')) {
		yy.totalshow=true;
	}
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
	if ((yy.num=='buildin' || yy.num=='clpaifa')&&(isContains(deptname, '管理层')||isContains(deptname, '人事&材料部')||deptname.indexOf("财务部")>=0||isContains(deptname, '安装'))) {
		$('#searchBar2').removeClass('hide');
		$('#author').removeClass('hide');
		$('#clgys').removeClass('hide');
		$('#time').removeClass('hide');
	}
	if (yy.num=='buildin'&&isContains(deptname, '主材供应商')) {
		$('#searchBar2').removeClass('hide');
		$('#time').removeClass('hide');
	}
	if (yy.num=='clpaifa'&&isContains(deptname, '辅材供应商')) {
		$('#searchBar2').removeClass('hide');
		$('#time').removeClass('hide');
	}
	function isContains(str, substr) {
	    return str.indexOf(substr) >= 0;
	}
	if (isContains(deptname, '设计部')||isContains(deptname, '工程监理部')||isContains(deptname, '巡检部')||deptname.indexOf("元贞团队/市场部")>=0) {
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
		//crm
		$('#menu-btn6').addClass('hide');
	}
	/*
	console.log(isContains(deptname, '设计部'));
	console.log(deptname);
	happy_add 因为市场部和设计部分组，之后组长要能根据组员筛选 begin 20171017
	*/
	if (isContains(deptname, '设计部')&&yy.num=='customer') {
		$('#menu-btn5').removeClass('hide');
	}
	if (isContains(deptname, '市场部')) {
		$('#menu-btn8').removeClass('hide');
	}
	//end 20171017
	//begin 20180308
	//签约报备，目前只有设计师，管理员，财务有权限报备。
	if (isContains(deptname, '设计部') || deptname=='元贞团队/管理层'||deptname=='元贞团队/财务部') {
		$('.qybbdiv').removeClass('hide');
		//update 20180404
		/*硬装签约报备权限（管理员，财务，硬装设计师）元贞，域嘉
		*软装签约报备权限（管理员，财务，软装设计师）
		*人工费申请权限（管理员，财务，核算部）
		*/
		if (!isContains(deptname, '设计部')) {
			$('.rgfsqdiv').removeClass('hide');
			$('.jzrgfsqdiv').removeClass('hide');			
		}
		if (isContains(deptname, '元贞国际设计')||isContains(deptname, '域嘉定制精装')||isContains(deptname, '元贞局装')||isContains(deptname, '贞筑豪宅装饰')|| deptname=='元贞团队/管理层'||deptname=='元贞团队/财务部') {
			$('.yzbbdiv').removeClass('hide');
		}
		if(isContains(deptname, '元贞局装')|| deptname=='元贞团队/管理层'||deptname=='元贞团队/财务部'||isContains(deptname, '域嘉定制精装')){
			$('.jzbbdiv').removeClass('hide');
		}
		if (isContains(deptname, '梦依达软装馆')|| deptname=='元贞团队/管理层'||deptname=='元贞团队/财务部') {
			$('.rzbbdiv').removeClass('hide');
		}
		/*if (isContains(deptname, '域嘉定制精装')) {
			$('.rzbbdiv').addClass('hide');			
		}*/
	}
	//end 20180404

	if (deptname=='元贞团队/预算部'||deptname=='元贞团队/形象建设部') {
		$('#menu-btn3').addClass('hide');
		$('#menu-btn5').addClass('hide');
		$('#menu-btn4').addClass('hide');
	}

	//begin 20180504 把形象部殷名煌，加一个工地进度筛选栏
	if (deptname=='元贞团队/形象建设部') {
		$('#menu-btn3').removeClass('hide');
	}
	//end 20180504

	if (deptname=='元贞团队/人事&材料部') {
		$('#menu-btn5').addClass('hide');
	}
	if (isContains(deptname, '门店')) {
		//crm
		$('#menu-btn6').addClass('hide');
	}
	if (deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部') {
		//crm
		$('#menu-btn10').removeClass('hide');
		if (adminid!=188) {
			$('.xzkhdiv').removeClass('hide');
		}
	}
	if (deptname=='元贞团队/财务部'||isContains(deptname, '市场部')) {
		//crm
		$('#menu-btn10').removeClass('hide');
	}

	if ((deptname=='元贞团队/管理层'||deptname=='元贞团队/客服部')&&yy.num!='daiban') {
		//CRM
		$('#menu-btn8').removeClass('hide');
	}

	//begin 20190421 材料商筛选栏只保留 区域、日期、筛选、状态 crm
	if (isContains(deptname, '供应商') ||isContains(deptname, '供货商') ||isContains(deptname, '合作商') || adminid==clskefuid) {
		$('#menu-btn6').addClass('hide');
		$('#menu-btn5').addClass('hide');
		//$('#menu-btn9').addClass('hide');
		$('#menu-btn10').addClass('hide');
		$('#menu-btn8').addClass('hide');
		isshow=false;
	}

		var ob = this.showobj.css({'height':''+hei+'px'});
		return ob;
	},
	getheight:function(ss){
		var hei = 0;if(!ss)ss=0;
		if(get('searsearch_bar'))hei+=75;
		if(get('header_title'))hei+=50;
		if(get('footerdiv'))hei+=50;
		return $(window).height()-hei+ss;
	},
	initScroll:function(){
		if(get('searsearch_bar')){
			this.touchobj = $('#mainbody').rockdoupull({
				upbool:true,
				onupbefore:function(){
					return yy.onupbefore();
				},
				upmsgdiv:'showblank',
				onupsuccess:function(){
					yy.scrollEndevent();
				}
			});
		}
	},
	init:function(){
		this.num = json.num;
		this.showobj = $('#mainbody');
		$('.weui_navbar').click(function(){return false;});
		$('body').click(function(){
			$("div[id^='menushoess']").remove();
		});
		this.initScroll();
		this.resizehei();
		$(window).resize(function(){
			yy.resizehei();
		});
	},
	isContains:function(str, substr) {
	    return str.indexOf(substr) >= 0;
	},
	clickmenu:function(oi,o1){
		var sid='menushoess_'+oi+'';
		if(get(sid)){
			$('#'+sid+'').remove();
			return;
		}
		$("div[id^='menushoess']").remove();
		var a = json.menu[oi],slen=a.submenu.length,i,a1;
		this.menuname1 = a.name;
		this.menuname2 = '';
		if(slen<=0){
			this.clickmenus(a);
		}else{
			var o=$(o1),w=1/json.menu.length*100;
			var s='<div id="'+sid+'" style="position:fixed;z-index:5;left:'+(o.offset().left)+'px;bottom:50px; background:white;width:'+w+'%" class="menulist r-border-r r-border-l">';
			for(i=0;i<slen;i++){
				btn="";
				a1=a.submenu[i];
				if (this.isContains(deptname, '设计部') || deptname=='元贞团队/管理层'||deptname=='元贞团队/财务部'||deptname=='元贞团队/人事&材料部') {

					//update 20180404
					/*硬装签约报备权限（管理员，财务，硬装设计师）
					*软装签约报备权限（管理员，财务，软装设计师）
					*局装签约报备权限（管理员，财务，局装设计师）
					*人工费申请权限（管理员，财务，核算部）
					*/
					if (this.isContains(deptname, '设计部')) {
						if (a1.name=='硬装工费') {btn="rgfsqdiv hide";}
						if (a1.name=='局装工费') {btn="jzrgfsqdiv hide";}
					}
					if (!this.isContains(deptname, '梦依达软装馆')&&deptname!='元贞团队/管理层'&&deptname!='元贞团队/财务部') {
						if (a1.name=='软装报备') {btn="rzbbdiv hide";}
					}
					if (this.isContains(deptname, '梦依达软装馆')||deptname=='元贞团队/人事&材料部') {
						if (a1.name=='硬装报备') {btn="yzbbdiv hide";}
					}
					if (!this.isContains(deptname, '元贞局装')&&deptname!='元贞团队/管理层'&&deptname!='元贞团队/财务部'&&!this.isContains(deptname, '域嘉定制精装')&&!this.isContains(deptname, '梦依达软装馆')) {
						if (a1.name=='局装报备') {btn="jzbbdiv hide";}
					}

				}else{

					if (a1.name=='局装报备') {btn="jzbbdiv hide";}
					if (a1.name=='硬装报备') {btn="yzbbdiv hide";}
					if (a1.name=='软装报备') {btn="rzbbdiv hide";}
					if (a1.name=='硬装工费') {btn="rgfsqdiv hide";}
					if (a1.name=='局装工费') {btn="jzrgfsqdiv hide";}
				}

				s+='<div onclick="yy.clickmenua('+oi+','+i+')" class="r-border-t '+btn+'" style="color:'+a1.color+';">'+a1.name+'</div>';
			}
			s+='</div>';
			$('body').append(s);
		}
	},
	searchuser:function(){
		$('#searsearch_bar').addClass('weui_search_focusing');
		$('#search_input').focus();
	},
	searchcancel:function(){
		$('#search_input').blur();
		$('#searsearch_bar').removeClass('weui_search_focusing');
	},
	souclear:function(){
		$('#search_input').val('').focus();
	},
	sousousou:function(){
		var key = $('#search_input').blur().val();
		this.keysou(key);
	},
	clickmenua:function(i,j){
		var a = json.menu[i].submenu[j];
		this.menuname2 = a.name;
		this.clickmenus(a);
	},
	onclickmenu:function(a){
		return true;
	},
	clickmenus:function(a){
		$("div[id^='menushoess']").remove();
		if(!this.onclickmenu(a))return;
		var tit = this.menuname1;
		if(this.menuname2!='')tit+='→'+this.menuname2+'';
		document.title = tit;
		$('#header_title').html(tit);
		if(a.type==0){
			this.searchcancel();
			this.sousoukey='';
			this.clickevent(a);return;
		}
		if(a.type==1){
			var url=a.url,amod=this.num;
			if(url.substr(0,3)=='add'){
				if(url!='add')amod=url.replace('add_','');
				url='index.php?a=lum&m=input&d=flow&num='+amod+'&show=we';
			}
			js.location(url);
		}
	},
	clickevent:function(a){
		this.getdata(a.url, 1);
	},
	data:[],
	_showstotal:function(d){
		var d1,v,s,o1;
		for(d1 in d){
			v=d[d1];
			if(v==0)v='';
			o1= $('#'+d1+'_stotal');
			o1.html(v);
		}
	},
	regetdata:function(o,p){
		var mo = 'mode';
		if(o){
			o.innerHTML='<img src="images/loading.gif" align="absmiddle">';
			mo = 'none';
		}
		this.getdata(this.nowevent,p, mo);
	},
	getdata:function(st,p, mo){
		this.nowevent=st;
		this.nowpage = p;
		var areaRecord=$('#areaRecord').text();	 	 areaRecord=='区域'?areaRecord='':'basejm_'+jm.base64encode(areaRecord)+'';
		var timeRecord=$('#timeRecord').text();	 	 timeRecord=='日期'?timeRecord='':'basejm_'+jm.base64encode(timeRecord)+'';
		var courseRecord=$('#courseRecord').text();	 courseRecord=='进度'?courseRecord='':'basejm_'+jm.base64encode(courseRecord)+'';
		var projectRecord=$('#projectRecord').text();projectRecord=='监理'?projectRecord='':'basejm_'+jm.base64encode(projectRecord)+'';
		var shichangRecord=$('#shichangRecord').text();shichangRecord=='顾问'?shichangRecord='':'basejm_'+jm.base64encode(shichangRecord)+'';
		var desginRecord=$('#desginRecord').text();	 desginRecord=='设计师'?desginRecord='':'basejm_'+jm.base64encode(desginRecord)+'';
		var laiyuanRecord=$('#laiyuanRecord').text();laiyuanRecord=='渠道'?laiyuanRecord='':'basejm_'+jm.base64encode(laiyuanRecord)+'';
		var unitnameRecord=$('#unitnameRecord').text();	 unitnameRecord=='筛选'?unitnameRecord='':'basejm_'+jm.base64encode(unitnameRecord)+'';
		var statusRe=$('#statusRe').text();statusRe=='状态'?statusRe='':'basejm_'+jm.base64encode(statusRe)+'';
		var brandRe=$('#brandRe').text();brandRe=='品牌'?brandRe='':'basejm_'+jm.base64encode(brandRe)+'';

		//主材派发
		var timeRecord1=$('#timeRecord1').text();timeRecord1=='日期'?timeRecord1='':'basejm_'+jm.base64encode(timeRecord1)+'';
		var authorRecord=$('#authorRecord').text();authorRecord=='监理'?authorRecord='':'basejm_'+jm.base64encode(authorRecord)+'';
		var clgysRecord=$('#clgysRecord').text();clgysRecord=='材料供应商'?clgysRecord='':'basejm_'+jm.base64encode(clgysRecord)+'';

		if(!mo)mo='mode';
		var key = ''+this.sousoukey;
		if(key)key='basejm_'+jm.base64encode(key)+'';
		js.ajax('index','getyydata',{'page':p,'event':st,'num':this.num,'key':key,'areaSearch':areaRecord,'timeRecord':timeRecord,'time1':timeRecord1,'author':authorRecord,'clgys':clgysRecord,
			'courseRecord':courseRecord,'projectRecord':projectRecord,'shichangRecord':shichangRecord,'desginRecord':desginRecord,
			'laiyuanRecord':laiyuanRecord,'unitnameRecord':unitnameRecord,'status':statusRe,'brandRe':brandRe},function(ret){
			yy.showdata(ret);
		},mo, false,false, 'get');
	},
	reload:function(){
		this.getdata(this.nowevent,this.nowpage);
	},
	keysou:function(key){
		if(this.sousoukey == key)return;
		this.sousoukey = key;
		this.regetdata(false,1);
	},
	xiang:function(oi){
		var d = this.data[oi-1];
		var ids = d.id,nus=d.modenum,modne=d.modename;
		if(!ids)return;
		if(!nus||nus=='undefined')nus = this.num;
		var url='task.php?a=x&num='+nus+'&mid='+ids+'&show=we';
		js.location(url);
	},
	clpaifadeal:function(oi,type){
		//console.log('clpaifadeal');
		var d = this.data[oi-1];
		var ids = d.id,nus=d.modenum,modne=d.modename;
		if(!ids)return;
		if(!nus||nus=='undefined')nus = this.num;
		//type-处理:1,详情：0
		var url='task.php?a=clpf&num='+nus+'&mid='+ids+'&show=we&type='+type;
		js.location(url);
	},
	rgfeexiang:function(id){
		var ids = this.rgfeeid,nus='rgfee';
		if(this.num_menu=="yzjuzhuang"){
			nus='jzrgfee';
		}
		if(!ids)return;
		if(!nus||nus=='undefined')nus = this.num;
		var url='task.php?a=x&num='+nus+'&mid='+ids+'&show=we';
		js.location(url);
	},
	suboptmenu:{},
	showmenu:function(oi){
		var a = this.data[oi-1],ids = a.id,rgfeeid = a.rgfeeid,i;
		var nus=a.modenum;if(!nus||nus=='undefined')nus = this.num;
		if(a.type=='applybill' && nus){
			var url='index.php?a=lum&m=input&d=flow&num='+nus+'&show=we';
			js.location(url);return;
		}
		if(!ids)return;
		this.tempid 	= ids;
		this.tempnum 	= nus;
		this.temparr 	= {oi:oi};
		this.rgfeeid 	= rgfeeid;
		this.num_menu= nus;
		// console.log(a)
		if (rgfeeid>0) {
			var da = [{name:'工地详情',lx:998,oi:oi}];
		}else if (this.num=='clpaifa' || this.num=='buildin'){
			var da = [];
		}else {
			var da = [{name:'详情',lx:998,oi:oi}];
		}
		var subdata = this.suboptmenu[''+nus+'_'+ids+''];
		if(!subdata){
			da.push({name:'<img src="images/loadings.gif" align="absmiddle"> 加载菜单中...',lx:999});
			this.loadoptnum(nus,ids);
		}else{
			for(i=0;i<subdata.length;i++)da.push(subdata[i]);
		}
		js.showmenu({
			data:da,
			width:150,
			onclick:function(d){
				yy.showmenuclick(d);
			}
		});
	},
	showrecord:function(oi){
		// 根据id，获取最近5条工作日志
		var a = this.data[oi-1],ids = a.id,modenum = a.modenum,i;

		js.ajax('index','getyrecorddata',{'id':ids,'modenum':modenum},function(ret){
		
			var s='',i,len=ret.rows.length,d,st='',oi,hahah='';
			if (len>0) {			
				var html='<h3>工作日志</h3>';
				for(i=0;i<len;i++){

					d=ret.rows[i];
					var remark=d.explain==null||$.trim(d.explain)==''?d.name:d.explain;
						remark=remark?remark:d.name;
					html+='<div class="remark-one">'
							+'<div class="remark-text">'+remark+'</div>'
							+'<div class="remark-name"> 处理人：'+d.checkname+'<span class="zhanw">/</span>'+d.optdt+'</div>'
						+'</div>';
				}

				$('.tk-remark').html(html);
				$('.tk-bg').show();
				$('.tk-content').show();
			}else{
				js.msg('msg', '暂无工作日志','1');
			}
		}, false,false, 'get');
	},
	loadoptnum:function(nus,id){
		js.ajax('agent','getoptnum',{num:nus,mid:id},function(ret){
			yy.suboptmenu[''+nus+'_'+id+'']=ret;
			yy.showmenu(yy.temparr.oi);
		},'none');
	},
	changeCustomerStatus:function(d,text,supplierId){
        d.status = text;
        d.supplierId = supplierId;
        // if(text&&supplierId){
	        js.ajax('customerStatus','changeCustomerStatus',d,function(ret){
	            console.log(ret)
	        },'none');
       /* }else{
        	alert('请选择要变更状态的供应商');
        }*/
	},
	changeCustStatus:function(d,status,rzstatus){
        d.status = status;
        d.rzstatus = rzstatus;
        // if(text&&supplierId){
	        js.ajax('customerStatus','changeCustStatus',d,function(ret){
	            console.log(ret)
	        },'none');
       /* }else{
        	alert('请选择要变更状态的供应商');
        }*/
	},
	showmenuclick:function(d){
		d.num=this.num;d.mid=this.tempid;d.mid=this.tempid;
		d.modenum = this.tempnum;
		console.log(this.temparr.oi);//return;
		var lx = d.lx;if(!lx)lx=0;
        if(lx==123){
            js.wx.changeStatus(d,'请选择状态：',function(text,supplierId){
				yy.changeCustomerStatus(d, text,supplierId);
            });
            return;
        }
        if(lx==1234){
            js.wx.changeCustStatus(d,'请选择状态：',function(text,supplierId){
				yy.changeCustStatus(d, text,supplierId);
            });
            return;
        }
		if(lx==999)return;
		if(lx==998){this.xiang(d.oi);return;}
		if(lx==9393){this.rgfeexiang();return;}
		if(lx==9301){this.clpaifadeal(this.temparr.oi,1);return;}
		if(lx==9328){this.clpaifadeal(this.temparr.oi,0);return;}
		if(lx==996){this.xiang(this.temparr.oi);return;}
		this.changdatsss = d;
		if(lx==2 || lx==3){
			var clx='changeuser';if(lx==3)clx='changeusercheck';
			$('body').chnageuser({
				'changetype':clx,
				'titlebool':get('header_title'),
				'onselect':function(sna,sid){
					yy.xuanuserok(sna,sid);
				}
			});
			return;
		}
		if(lx==1 || lx==9 || lx==10){
			var bts = (d.issm==1)?'必填':'选填';
			js.wx.prompt(d.name,'请输入['+d.name+']说明('+bts+')：',function(text){
				if(!text && d.issm==1){
					js.msg('msg','没有输入['+d.name+']说明');
				}else{
					yy.showmenuclicks(d, text);
				}
			});
			return;
		}
		if(lx==11){
			var url='index.php?a=lum&m=input&d=flow&num='+d.modenum+'&mid='+d.mid+'&show=we';
			js.location(url);return;
		}
		this.showmenuclicks(d,'');
	},
	xuanuserok:function(nas,sid){
		if(!sid)return;
		var d = this.changdatsss,sm='';
		d.changename 	= nas;
		d.changenameid  = sid;
		this.showmenuclicks(d,sm);
	},
	showmenuclicks:function(d, sm){
		if(!sm)sm='';
		d.sm = sm;
		//追加说明上传文件
		if(d.sm){
			var valyes	= get('fileidview-inputEl').value;
			d.file = valyes;

		}
		for(var i in d)if(d[i]==null)d[i]='';
		js.ajax('index','yyoptmenu',d,function(ret){
			yy.suboptmenu[''+d.modenum+'_'+d.mid+'']=false;
			//yy.getdata(yy.nowevent, 1);
			alert('提交成功');
		});
	},
	showdata:function(a){
		this.overend = true;
		var s='',cont_new,i,len=a.rows.length,d,st='',oi,hahah='';
		var totaltext="";
		$('#showblank').remove();
		$('#notrecord').remove();
		if(typeof(a.stotal)=='object')this._showstotal(a.stotal);
		if(a.page==1){
			this.showobj.html('');
			this.data=[];
			if ((yy.num=='buildin'&&(a.event=='anzdcl'||a.event=='anzhis'||a.event=='tuihuohis')) || yy.num=='clpaifa') {
				ta = '<div class="showblank" id="showblank1" style="padding-top: 15px;padding-bottom: 0;">合计：'+a.alltotal+',商定合计：'+a.totalprice+'</div>';
				this.showobj.append(ta);
			}
			if (yy.num=='daiban'&&a.totalprice>0&&yy.totalshow) {
				totaltext = '<span style="padding-top: 15px;padding-bottom: 0;"> &nbsp; &nbsp; &nbsp; 合计：'+a.totalprice+'</span>';
			}
		}
		var count=a.count;
		if(count==0)count=len;
		if(count>0){
			this.nowpage = a.page;
			s = '<div class="showblank" id="showblank" style="padding-top: 15px;padding-bottom: 0;">共'+count+'条记录';
			if(a.maxpage>1)s+=',当前'+a.maxpage+'/'+a.page+'页 ';
			if(a.page<a.maxpage){
				// s+=', <a id="showblankss" onclick="yy.regetdata(this,'+(a.page+1)+')" href="javascript:;">点击加载</a>'+totaltext;
				s+=',下拉加载更多 '+totaltext;
				this.overend = false;
			}else{
				s+= ' '+totaltext;

			}
			s+= '</div>';
			this.showobj.append(s);
			if(a.count==0)$('#showblank').html('');
		}else{
			this.showobj.html('<div class="notrecord" id="notrecord">暂无记录</div>');
		}
		for(i=0;i<len;i++){
			d=a.rows[i];
// console.log(d)
			oi=this.data.push(d);
			if(d.showtype=='line' && d.title){
				s='<div class="contline">'+d.title+'</div>';
			}else{
				if(!d.statuscolor)d.statuscolor='';
				st='';
				if(d.ishui==1)st='color:#aaaaaa;';
				s='<div style="'+st+'" class="r-border contlist">';
				cont_new=hahah='';
				if(d.price) hahah='<span class="di-block mR20">合同金额 <span class="price-btn icon-btn"><img src="web/images/money.png?1"></span> '+d.price+'</span>';
				// if(d.price) hahah='<span style=" margin-left: 15px;font-size:10px;">'+d.price+'</span>';
				if(d.title){
					if(d.face){
						s+='<div onclick="yy.showmenu('+oi+')" class="face"><img src="'+d.face+'" align="absmiddle">'+d.title+'</div>';
					}else{
						s+='<div onclick="yy.showmenu('+oi+')" class="tit face">'+d.title+'</div>';
					}
				}
				if(d.optdt)s+='<div class="dt">'+d.optdt+'</div>';
				if(d.courseid==137||d.courseid==138||d.courseid==139||d.courseid==69||d.courseid==79||d.courseid==74||((d.courseid==null||d.courseid==0)&&yy.num=='daiban'&&d.status==1)||d.status==1){
					cont_new='<span class="di-block mR20"><span  style="font-weight: bold;opacity: 1;color: #3d8edb;">［已完工］</span></span>';
				}else if(d.dif){cont_new='<span class="di-block mR20"><span class="time-btn icon-btn"><img src="web/images/time.png?2"></span>工期剩余<span class="color-red">'+d.dif+'</span>天';}

				if(d.cont)s+='<div  onclick="yy.showmenu('+oi+')" class="cont">'+d.cont.replace(/\n/g,'<br>')+hahah+cont_new+'</div>';
				/*if(d.id && d.modenum){  //工地会显示操作还是详情
					s+='<div class="xq r-border-t"><font onclick="yy.showmenu('+oi+')">操作<i class="icon-angle-down"></i></font><span onclick="yy.xiang('+oi+')">详情&gt;&gt;</span>';
					s+='</div>';
				}*/
				// if(d.yzbrand && isshow)s+='<div style="opacity:0.7" class="brand">'+d.yzbrand+'</div>';
				if(d.yzbrand && isshow)s+='<div class="brand">'+d.yzbrand+'</div>';
				/*if(d.statustext&&yy.num=='daiban'){
					if (d.rzstatustext) {
						//s+='<div style="opacity:0.7" class="zt">'+d.statustext+'，'+d.rzstatustext+'</div>';
					}else{
						s+='<div style="opacity:0.7" class="zt">'+d.statustext+'</div>';
					}
				}*/

				/*if(d.courseid==137||d.courseid==138||d.courseid==139||d.courseid==69||d.courseid==79||d.courseid==74||((d.courseid==null||d.courseid==0)&&yy.num=='daiban'&&d.status==1)||d.status==1){
					s+='<div class="djs" style="font-weight: bold;opacity: 1;color: #3d8edb;">［已完工］</div>';
				}else if(d.dif){s+='<div class="djs">工期还剩<span style="background-color:red;  color: white;font-size: 14px;">'+d.dif+'</span>天</div>';}
*/
				if (yy.num=='daiban'||yy.num=='customer') {							
					s+='<div class="host-foot pl20">'
						+d.footcon+'<div class="btn-details"  onclick="yy.showrecord('+oi+')"  >工作日志</div>'
					+'</div>';
				}
				s+='</div>';

			//console.log(a);
			}
			this.showobj.append(s);
			if(yy.num=='daiban'||yy.num=='customer'){
				$('.dt').addClass('pl20');
				$('.cont').addClass('pl20');
			}else if (yy.num=='user') {
				$('.cont').addClass('pl30');				
			}
		}
		if(this.touchobj)this.touchobj.onupok();
	},
	onupbefore:function(){
		if(this.overend)return false;
		var a={
			'msg':'↑ 继续上拉加载第'+(this.nowpage+1)+'页',
			'msgok' : '<a id="showblankss">↓ 释放后</a>加载第'+(yy.nowpage+1)+'页...'
		};
		return a;
	},
	scrollEndevent:function(){
		yy.regetdata(get('showblankss'),yy.nowpage+1);
	}
}