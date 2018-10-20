/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月8日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module) {
     var core = require("core");
     var self;

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
    			core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
    		}
    	}
    	//入口函数
    	,main:function()
    	{
    		var h = $(window).height();
            $('body').height(h);
            $('.mianBox').height(h);
            self.centerWindow(".tipInfo");

            var wait = Number($("#wait").text());
            var href = $("#href").prop("href");
            var interval = setInterval(function(){
                var time = --wait;
                $("#wait").text(time);
                if(time <= 0) {
                    clearInterval(interval);
                    if(href)
                    	location.href = href;
                    else
                    	window.history.go(-1);

                };
            }, 1000);
    	}
    	//2.将盒子方法放入这个方，方便法统一调用
        ,centerWindow:function(a) {
            self.center(a);
            //自适应窗口
            $(window).unbind().bind('scroll resize',function() {
            	self.center(a);
            });
        }
        //1.居中方法，传入需要剧中的标签
        ,center:function(a) {
            var wWidth = $(window).width();
            var wHeight = $(window).height();
            var boxWidth = $(a).width();
            var boxHeight = $(a).height();
            var scrollTop = $(window).scrollTop();
            var scrollLeft = $(window).scrollLeft();
            var top = scrollTop + (wHeight - boxHeight) / 2;
            var left = scrollLeft + (wWidth - boxWidth) / 2;
            $(a).css({
                "top": top,
                "left": left
            });
        }
    });
});
//# sourceMappingURL=jumppage.js.map
