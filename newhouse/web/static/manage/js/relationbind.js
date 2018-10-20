/**
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 15-06-13
 * Time: 下午13:43
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
    			core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
    		}
    	}

    	//入口函数
    	,main:function()
    	{
    		var source = $.parseJSON($("#master").attr('data-source'));
    		$("#master").off().on("change", function(){
    			var id = $(this).val();
    			//清勾
    			$("[id^='parent_form_']").prop("checked", false);

    			if(!source[id])
    				return false;

    			$.each(source[id], function(key, item){
    				$("#parent_form_"+key).prop("checked", true);
    			});
    		});

    		//全选
    		$("#parent_form").off().on('change',function(){
    			$("[id^='parent_form_']").prop("checked", $(this).prop('checked'));
    		})
    	}
    });
});