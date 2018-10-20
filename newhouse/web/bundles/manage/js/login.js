/**
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 15-03-16
 * Time: 下午3:27
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
     var core = require("core");
     var self;
    (function(out){
        module.exports=out;
    }) ({
         init:function(page,par){
             //this[page](par);

            //初始化加載的腳本
             try{
                self = this;
                self[page](par);
             }catch(e) {
            	 core.generalTip.ontextTip("JS脚本错误！","error");
             }
         }

        //登陸界面Js
        ,main:function(){
            //更換驗證碼圖片
            $("#codeImg").off().on("click",function(e){
            	e.preventDefault();
            	var src = $(this).attr("data-src");
                $(this).attr("src", src+"?"+Math.floor(Math.random() * ( 1000 + 1)));
            });

            //登陸按鈕            
            core.generalFunction.ajaxPost(self.ajaxSubmitCallback);
    		core.generalFunction.ajaxGet(self.ajaxSubmitCallback);
        }
        ,ajaxSubmitCallback:function(result, that)
    	{
    		if(result.status)
	            location.href = result.url;
    		else{
    			$("#codeImg").trigger('click');
    			core.generalTip.ontextTip(result.info,"error");
    		}
    	}
    })

});