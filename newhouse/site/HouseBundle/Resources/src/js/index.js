/*!
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年3月2日
* To change this template use File | Settings | File Templates.
*/

define(function (require, exports, module) {
	var self;
    var core = require("core");
    var pop	= require("pop");
    (function(out){
        module.exports=out;
    }) ({
    	//调用函数
    	/*
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
    			core.generalTip.ontextTip("auto index JS脚本错误！["+e+"]","error");
    		}
    	}
    	//入口函数
    	,main:function()
    	{
    		
    	}
    	,topnav:function()
    	{
    		//顶部导航
    		$("#topnav1 li, #topnav2 li").hover(function()
    		{
    			$(this).addClass("act");
    	    },function(){
    	    	$(this).removeClass("act");
    	    });
    		
    		//根据字母选择地区
    		$("#subwebletter i").off().on("click", function()
    		{
    			var obj = $(this).parent();
    			var letterVal = $(this).html();
    			$(this).siblings().removeClass("act").end().addClass("act");
    			
    			obj.next().find("a").removeClass("act");
    			obj.next().find("a[data-letter='"+letterVal+"']").addClass("act");
    			if(!obj.next().find("a").hasClass("act")){
    				obj.next().find(".nolettertip").animate({opacity: 'show'}, 1200).animate({opacity: 'hide'}, 1200).html('暂无'+letterVal+'字母分站信息');
    			}
    		});
    		
    		//登陆弹窗
    		$("[id^='poplogin_']").off().on("click",function(){
    			var obj = $(this);
    			if (target = obj.attr('data-url'))
    			{
	    			//设置弹窗参数
	    			var mypop = pop("", {
	    				url			:target,
	                    type		:"ajax",
	                    width       :400,
	                    height      :480,
	                    zIndex		:9999,
	                    draggable   :true,
	                    onclose		:function(box){                    	
	                    	//重新绑定中部主菜单功能
	                    	//self.mainMenuFun();
	                    }
	                });
	                mypop.open();
    			}
    		});
    	}
    	,poplogin:function()
    	{
    		core.generalFunction.ajaxPost(self.reloadnav);
    		core.generalFunction.ajaxGet(self.reloadnav);
    		$("#codeText").focus(function(){
                $(this).next().show();
            }).blur(function(){
            	//$(this).next().hide();
            })
    	}
    	,reloadnav:function(data)
    	{
    		if(data.status){
    			core.generalTip.ontextTip(data.info,"right");
    			window.location.reload();
    		}else{
    			$("#login").trigger("click");
    			core.generalTip.ontextTip(data.info,"error");
    		}
    	}
    });
});