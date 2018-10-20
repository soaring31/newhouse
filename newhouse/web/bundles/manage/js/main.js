/**
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 15-04-28
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
     var core	= require("core");
     var pop	= require("pop");
     var pager	= require("pager");

     var self;     
     var message = "哦！！！！";
     //每页显示8行
     var pageSize = 8;

    (function(out){
        module.exports=out;
    }) ({
    	//调用函数
    	/**
    	 * 调用函数
    	 * @page 函数名， par参数名
    	 * @return function
    	 */
    	init:function(page,par){
    		//初始化加載的腳本
    		try{
    			self = this;
    			self[page](par);
    		}catch(e) {
    			core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
    		}
    	}
    	//入口函数
    	,main:function()
    	{
    		//禁止右键
    		/*$("body").off().on("contextmenu", { msg: message }, function(e) {
    			core.generalTip.ontextTip(e.data.msg,"error");
    			return false;
    		});*/

    		//判断是什么浏览器
    		var browser = navigator.appName;
    		
    		//自适应高度
    		self.resizeWindows('#bezel-id');
    		
    		//加载顶部系统菜单功能
    		self.topMenuFun();
    		
    		//加载左侧菜单功能
    		self.leftMenuFun();
    	}
    	//项部菜单功能
    	,topMenuFun:function()
    	{
    		//频道菜单点击效果
    		$("[id^='mainchannel_']").off().on("click",function(e){
    			//停止冒泡
    			if(e.preventDefault)
    			    e.preventDefault();
				else
				    window.event.returnValue == false;

    			var _tmpId = $(this).attr("id");
    			var tmpId  = _tmpId.split("_");
    			if(!$(this).hasClass('on')){
    				//菜单class处理
    				self.menuClasschange($(this));
    				
    				core.generalFunction.ajaxSubmit({
	                    url:baseUrl+"/manage/main/topmenu",
	                    data:{"channel":tmpId[1], "csrf_token":csrf_token},
	                    automatic:false,
	                    dataType:'html',
	                    beforeSend:function()
	                    {
	                    	//卸载事件,防止重复点击
	                    	$("[id^='mainchannel_']").off();
	                    },
	                    complete:function()
	                    {
							//重新加载事件
	                    	self.topMenuFun();
	                    },
	                    success:function(data){
	                    	//更新区域HTML
	                    	$("#topmenu").html(data);
	                    }
	                });
    			}
    		});
    		//顶部菜单点击触发
    		$("#topmenu li").off().on("click",function(e){
    			//停止冒泡
    			if(e.preventDefault)
    			    e.preventDefault();
				else
				    window.event.returnValue == false;
    			
    			if((!$(this).hasClass('on'))&&(url = $(this).attr('data-url'))){
    				//菜单class处理
        			self.menuClasschange($(this));
	    			
        			//ajax提交
	    			core.generalFunction.ajaxSubmit({
	                    url: url,
	                    automatic:false,
	                    dataType:'html',
	                    beforeSend:function()
	                    {
	                    	//卸载事件,防止重复点击
	                    	$("#topmenu li").off();
	                    },
	                    complete:function()
	                    {
							//重新加载事件
	                    	self.topMenuFun();
	                    	self.leftMenuFun();
	                    },
	                    success:function(data){
	                    	//更新区域HTML
	                    	$("#cleft-id").html(data);
	                    }
	                });
    			}
    			//有些低版本的IE如果不加return 会报错
    			return;
    		});    		
    	}
    	,leftMenuFun:function()
    	{
    		//左边菜单点击触发展开
    		$("#cleft-id h4").off().on("click",function(e){
    			//停止冒泡
    			if(e.preventDefault)
    			    e.preventDefault();
				else
				    window.event.returnValue == false;
    			
    			if(!$(this).hasClass('on')){
    				//为防止重复点击，这里做按钮解绑动作
    				//$("#cleft-id h4").off();
	    			//删除同级的on Class效果
	    			$(this).siblings('h4').removeClass('on');
	    			$(this).siblings('ul').find('li').removeClass('on');
	    			
	    			//同级别的UL隐藏
	    			$(this).siblings('ul:visible').hide();

	    			//给自己增加on Class效果
	    			$(this).attr('class','on');

	    			//自己的下一个UL展示
	    			$(this).next("ul").show();
    			}
    		})
    		
    		//左边子菜单点击触发动作
    		$("#cleft-id li").off().on("click",function(e){
    			//停止冒泡
    			if(e.preventDefault)
    			    e.preventDefault();
				else
				    window.event.returnValue == false;
    			
    			if(!$(this).hasClass('on')){    				
    				if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
    				{
    					$("#cleft-id li").off();
	    				var data = {"pageSize":pageSize, "csrf_token":csrf_token};
	    				//菜单class处理
	        			self.menuClasschange($(this));
		    			
	        			//右侧main区域AJAX刷新
	        			self.mainAareaFlushe(url, data);
    				}
    			}
    		})
    		
    		//搜索区域事件
    		$("[id^='search_']").off().on('blur',function(){
    			var tval = $(this).val();
 	   			var sval = $(this).attr("data-source");
 	   			var _tmpId = $(this).attr("id");
 	   			var tmpId  = _tmpId.split("_");
 	   			var data = {"csrf_token":csrf_token, "pageIndex":1};
 	   			if(tval==sval)
 	   				return false;
 	   			
	 	   		if (url = $("#page").attr("title"))
	 	   		{
	 	   		    $("#pageIndex").text(1);
		   			var _url = url.substring(0, url.indexOf('?'));
		   				url = _url?_url:url;
		   				data = core.generalFunction.getAreaFormData($(this).parent(), data);

	 	   		    //右侧main区域AJAX刷新
		   			self.mainAareaFlushe(url, data, true);
	 	   		}
	 	   		
	 	   		return false;
    		}).on('keydown',function(e){
    			e = e || window.event;
    			if(e.keyCode==13)
    			{
    				$(this).blur();
    			    return false;
    			}
    		});
   		
    		//搜索区域事件
    		$("[id^='selectsearch_']").off().on('change',function(){
    			var tval = $(this).val();
 	   			var _tmpId = $(this).attr("id");
 	   			var tmpId  = _tmpId.split("_");
 	   		    var data = {"csrf_token":csrf_token, "pageIndex":1};
 	   			
	 	   		if (url = $("#page").attr("title"))
	 	   		{
		   			var _url = url.substring(0, url.indexOf('?'));
		   				url = _url?_url:url;
		   				data = core.generalFunction.getAreaFormData($(this).parent(), data);
		   				
		   			$("#pageIndex").text(1);
	 	   			
	 	   		    //右侧main区域AJAX刷新
		   			self.mainAareaFlushe(url, data, true);
	 	   		}
	 	   		
	 	   		return false;
    		});
 
    		//有些低版本的IE如果不加return 会报错
    		return ;
    	}
    	//中部主菜单功能
    	,mainMenuFun:function()
    	{
            $("#flushbtn").off().on('click',function(){
                if (url = $("#page").attr("title"))
                {
                    var data = {"csrf_token":csrf_token};
                    var _url = url.substring(0, url.indexOf('?'));
                        url = _url?_url:url;
                    
                    //右侧main区域AJAX刷新
                    self.mainAareaFlushe(url, data);
                }
            })
    		//收展效果
    		$("[id^='showtr_']").off().on("click",function(){
    			var _tmpId = $(this).attr("id");
    			var tmpId  = _tmpId.split("_");
    			//点击同级元素，收缩功能
    			$(this).parent().parent("tr").siblings().find("[id^='showtr_']").each(function(){
    				$(this).text("+");
    				$(this).parents("tr").next(".subtr").hide();
    			});
    			
    			//收缩动作
    			if($(this).text()=="+"){
    				$(this).text("-");
    				$(this).parents("tr").next(".subtr").show();
    			}else{
    				$(this).text("+");
    				$(this).parents("tr").next(".subtr").hide();
    			}
    		});
    		//中部主菜单点击效果
    		$("[id^='mainmenu_']").off().on("click",function(e){
    			//停止冒泡
    			if(e.preventDefault)
    			    e.preventDefault();
				else
				    window.event.returnValue == false;
    			
    			if(!$(this).hasClass('on')){
    				if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
    				{
		    			//防止重复提交
		    			$("[id^='mainmenu_']").off();
		    			var _tmpId = $(this).attr("id");
		    			var tmpId  = _tmpId.split("_");
		    			var data   = {"pageSize":pageSize, "csrf_token":csrf_token};
	
		    			//菜单class处理
		    			self.menuClasschange($(this));
		
		    			//右侧main区域AJAX刷新
		    			self.mainAareaFlushe(url, data, true);
    				}
    			}
    			return ;
    		});
    		
    		//中部菜单翻页效果    		
    		pager.init('showPager',{
                count: $("#pageCount").text(),
                pageSize: pageSize,
                pageIndex: $("#pageIndex").text(),
                parentObj: $('#page')
            },function(obj,page){
            	//动态更新数据
            	if($('#page').attr("title")){
            		var data = {"pageIndex":page, "pageSize":pageSize, "csrf_token":csrf_token};
            		self.mainAareaFlushe($('#page').attr("title"), data, true);
            	}
            });
    		
    		//操作按钮事件
    		self.btnFun();

    		return ;    		
    	}
    	//操作按钮事件
    	,btnFun:function()
    	{    		
    		//加载自动保存事件
    		core.generalEvent.autoSave("editspan", "editinput");
    		
    		$("[id^='btnfun_']").off().on("click",function(){
    			var _tmpId = $(this).attr("id");
    			var tmpId  = _tmpId.split("_");
    			if ($(this).hasClass('confirm')) {
					if (!confirm('确认要执行该操作吗?')) {
						return false;
					}
				}
    			switch(tmpId[1])
    			{		
	    			case 'delete':
	    				//删除事件
	    				self.btndelete($(this));
	    				break;
	    			case 'turnon':
	    				//启用事件
	    				self.btnturnon($(this));
	    				break;
	    			case 'onlymodify':
	    				//保存事件
	    				self.onlymodify($(this));
	    				break;
	    				//跳转功能
	    			case 'jump':
	    				self.jumpFun($(this));
	    				break;
	    			default:
	    				//其它事件
	    				self.btnothers($(this));
	    				break;
    			}
    		});
    	}
    	
    	//删除事件 
    	,btndelete:function(obj)
    	{
    		//判断是否定义URL
    		if ((url = obj.attr('data'))|| (url = obj.attr('data-url')))
    		{
				var parenttr = obj.parent().parent("tr");
	
	    		core.alert("确定要"+obj.val()+"数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
		        	core.generalFunction.ajaxSubmit({
		                  url: url,
		                  type: 'GET',
		                  beforeSend:function()
		                  {
		                	  //卸载事件,防止重复点击
		                	  $("[id^='btnfun_']").off();
		                  },
		                  complete:function()
		                  {
		                	  //重新加载事件
		                	  self.btnFun();
		                  },
		                  success:function(data){
	                	     
	                	      if(obj.hasClass('reload'))
                	    	  {
	                	    	//动态更新数据
	      		            	if($('#page').attr("title")){
	      		            		var pageIndex = 1;
	      		            		var data = {"pageIndex":pageIndex, "pageSize":pageSize, "csrf_token":csrf_token};
	      		            		self.mainAareaFlushe($('#page').attr("title"), data, true);
	      		            	}
                	    	  }else if(data.status)
                	    		  parenttr.remove();
		                  }
		              });
	    		}});
    		}
    	}
    	//启用事件
    	,btnturnon:function(obj)
    	{
    		var _tmpId	= obj.attr("id");
			var tmpId = _tmpId.split("_");
			var id = Number(tmpId[3]);
			var turnon = {0:'停用', 1:'启用'};
			var parenttr = obj.parent().parent("tr");
			var available = 0;
			var unavailable	= true;
			
			
			if (true == obj.is(':checked'))
			{
				available = 1;
				unavailable	= false;
			}
			
			if(available==1)				
				parenttr.insertAfter($("[id^='btnfun_turnon_']:checked:first").parent().parent("tr"));
			else
				parenttr.insertAfter($("[id^='btnfun_turnon_']:last").parent().parent("tr"));

        	core.generalFunction.ajaxSubmit({
                  url: "entry?entry="+tmpId[2]+"&action=modify",
                  data:{"id":id, "available":available, "csrf_token":csrf_token},
                  automatic:false,
                  beforeSend:function()
                  {
                	  //卸载事件,防止重复点击
                	  $("[id^='btnfun_']").off();
                	  $("[id^='btnfun_turnon_']").prop("disabled",true);
                  },
                  complete:function()
                  {
                	  //重新加载事件
                	  self.btnFun();
                	  $("[id^='btnfun_turnon_']").each(function(){
                		  if(Number($(this).attr("issystem"))!=1) $(this).prop("disabled",false);
                	  });
                  },
                  success:function(data){
                  	if(data.status){
                  		obj.val(available);
                  		core.generalTip.ontextTip(data.info,"right");              		
                  	}else{
                  		obj.prop("checked",unavailable);
                  		core.generalTip.ontextTip(data.info,"error");
                  	}
                  }
              });

    	}
    	//鼠标双击显示输入框自己动保存事件
    	,dblclickmodify:function(obj)
    	{
    		var _tmpId	= obj.attr("id");
    		var tmpId	= _tmpId.split("_");
    		var tmpVal	= obj.val();
			var _url	= "entry?entry="+tmpId[0]+"&action=modify";
			var _data	= { "id": tmpId[1], "csrf_token":csrf_token};
			
			//如果没有变更刚不执行AJAX提交动作
			if(obj.attr("data")==tmpVal)
			{
				obj.replaceWith("<span class='editspan' id='"+_tmpId+"'>"+tmpVal+"</span>");
            	//重新加载事件
            	self.btnFun();
            	return ;
			}
			
			//获得区域表单数据并嵌入
			_data = core.generalFunction.getAreaFormData(obj.parents('tr:first'), _data,2);

			//提交数据
			core.generalFunction.ajaxSubmit({
                url: _url,
                data: _data,
                automatic: false,
                beforeSend:function()
                {
                	//卸载事件,防止重复点击
                	$("[id^='btnfun_']").off();
                	obj.prop("disabled",true);
                },
                complete:function()
                {
                	obj.replaceWith("<span class='editspan' id='"+_tmpId+"'>"+tmpVal+"</span>");
                	//重新加载事件
                	self.btnFun();
                	obj.prop("disabled",false);
                },
                success:function(data){
                	if(data.status){
                		core.generalTip.ontextTip(data.info,"right");
                	}else{
                		tmpVal = obj.attr("data");
                        core.generalTip.ontextTip(data.info,"error");
                	}
                }
			});
    	}
    	//直接修改事件（不弹窗）
    	,onlymodify:function(obj)
    	{
    		var _tmpId	= obj.attr("id");
			var tmpId	= _tmpId.split("_");
			var id		= Number(obj.parent().attr("data"));
			var _url	= "entry?entry="+tmpId[2]+"&action=modify";
			var _data	= { "id": id, "csrf_token":csrf_token};
			
			//获得区域表单数据并嵌入
			_data = core.generalFunction.getAreaFormData(obj.parents('tr:first'), _data,2);

			//提交数据
			core.generalFunction.ajaxSubmit({
                url: _url,
                data: _data,
                automatic: false,
                beforeSend:function()
                {
                	//卸载事件,防止重复点击
                	$("[id^='btnfun_']").off();
                	obj.prop("disabled",true);
                },
                complete:function()
                {
                	//重新加载事件
                	self.btnFun();
                	obj.prop("disabled",false);
                },
                success:function(data){
                	if(data.status){
                		core.generalTip.ontextTip(data.info,"right");
                	}else
                        core.generalTip.ontextTip(data.info,"error");
                }
            });
    	}
    	//跳转功能
    	,jumpFun:function(obj)
    	{
    		if ((target = obj.attr('data'))||(target = obj.attr('data-url'))||(target = obj.attr('href'))|| (target = obj.attr('url')))
    		{
    			var data = {"pageIndex":1, "pageSize":pageSize, "csrf_token":csrf_token};
    			//右侧main区域AJAX刷新
    			self.mainAareaFlushe(target, data, true);
    		}
    		return false;
    	}
    	//其它事件
    	,btnothers:function(obj)
    	{
    		if ((target = obj.attr('data'))||(target = obj.attr('data-url'))||(target = obj.attr('href'))|| (target = obj.attr('url')))
    		{
	    		var _tmpId	= obj.attr("id");
				var tmpId	= _tmpId.split("_");
				var _width	= 750;
				var _height	= 450;
				if(tmpId[1]=='show'){
					_width	= 600;
					_height	= 400;
				}
				if(tmpId[1]=='preview'){
					_width	= 650;
					_height	= 350;
				}
				
				//设置弹窗参数
				var mypop = pop("", {
					url			:target,
	                title       : obj.attr('title')||obj.val(),
	                type		:"ajax",
	                width       :_width,
	                height      :_height,
	                zIndex		:99,
	                draggable   :true,
	                onclose		:function(box){                    	
	                	//重新绑定中部主菜单功能
	                	self.mainMenuFun();
	                }
	            });
	            mypop.open();
    		}
    	}
    	//右侧main区域AJAX刷新
    	,mainAareaFlushe:function(_url, _data, change)
    	{
    		var url = _url.split('?');

    		if(url.length>1)
    		{
    			_url = url[0];
    			var aBuf = url[1].split("&");
    		    for(var i=0, iLoop = aBuf.length; i<iLoop; i++)
    		    {
    		    	//分离key与Value
	    			var aTmp = aBuf[i].split("=");
    				_data[aTmp[0]] = aTmp[1];
    		    }
    		}

    		jumpParam[_url] = jumpParam[_url]?jumpParam[_url]:{};

    		//console.log(_data);
    		$.each(_data,function(key,val){
    			jumpParam[_url][key] = val;
    		});

    		core.generalFunction.ajaxSubmit({
                url: _url,
                data: jumpParam[_url],
                type: 'GET',
                automatic:false,
                dataType:'html',
                complete:function()
                {
                	//加载左侧菜单
        			self.leftMenuFun();

        			//加载中部主菜单功能
        			self.mainMenuFun();

        			//设置跳页URL
        			$("#page").prop("title",_url);
                },
                success:function(data){
                	var topNav = $("#topNav").html();
                	$("#rleft").html(data);
                	if(change){
                		$("#topNav").html(topNav);
                		if($("#topNav").parent().find('.on').length>1)
                			$("#topNav li").removeClass('on');
                	}
                },
                error: function(data){
                	$("#rleft").html(data.responseText);
                }
            });
    		return ;
    	}
    	//菜单class处理
    	,menuClasschange:function(handle )
    	{
    		//删除同级的on Class效果
    		handle.siblings().removeClass('on');
			
			//给自己增加on Class效果
    		handle.addClass('on');
    		
    		return ;
    	}
    	/* 自动适应窗体高度  */
    	,resizeWindows:function(id)
    	{
    		var obj = $(id);
    		var mainHeight = obj.height();
    		//-parseInt($("#"+id).css("margin-top"))*2
    		//获取主题的边框高度
    		var boderWidth = isNaN(parseInt(obj.css("border-width")))? 0:parseInt(obj.css("border-width"));
    		//获取~上外边距
    		var marginTop = isNaN(parseInt(obj.css("margin-top")))? 0:parseInt(obj.css("margin-top"));
    		//获取~下外边距
    		var marginBottom =isNaN(parseInt(obj.css("margin-bottom")))? 0:parseInt(obj.css("margin-bottom"));
    		var lastHeight = $(window).height()-boderWidth-marginTop-marginBottom;

    		if(mainHeight>=lastHeight){
    			lastHeight = mainHeight;
    		}

    		obj.height(lastHeight);

    		//有些低版本的IE如果不加return 会报错
    		return ;
    	}
    	/**
    	 * 图片、文件上传
    	 */
    	,uploadFun:function()
    	{
    		//图片上传、图片预览、选择文件
    	    core.generalEvent.initViewImg();
    	    core.generalEvent.initUploadImg();
    	}
    	/**
    	 * Ajax提交
    	 */
    	,ajaxSubmit:function()
    	{
    		core.generalFunction.ajaxPost(self.ajaxSubmitCallback);
    		core.generalFunction.ajaxGet(self.ajaxSubmitCallback);
    	}
    	,ajaxSubmitCallback:function(result, that)
    	{
    		if(result.status)
    		{
    			core.generalTip.ontextTip(result.info ? result.info : "操作成功！", 'right', function ()
    			{
    				if(that.hasClass('reload')||that.hasClass('close')||that.attr('data-close')==1)
    					$(".wk-pop-close").trigger("click");

					if(that.hasClass('reload')||that.attr('data-reload')==1)
					{
						//动态更新数据
		            	if($('#page').attr("title")){
		            		var pageIndex = 1;
		            		if($('#page').find('.on a').length>0)
		            			pageIndex = Number($('#page').find('.on a').text());
		            		var data = {"pageIndex":pageIndex, "pageSize":pageSize, "csrf_token":csrf_token};
		            		self.mainAareaFlushe($('#page').attr("title"), data, true);
		            	}
					}else if(result.url)
						location.href = result.url;
				
    			});
    		}else
    			core.generalTip.ontextTip(result.info,"error");
    	}
    })

});