/**
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 16-11-17
 * Time: 上午9:53
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
     var core = require("core");
     var self;

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
    			core.generalTip.ontextTip("trash JS脚本错误！["+e+"]","error");
    		}
    	}
    	//入口函数
    	,main:function()
    	{
    		
    	}
    });
});