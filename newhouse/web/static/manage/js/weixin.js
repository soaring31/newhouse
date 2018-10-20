/**
 * Created with JetBrains WebStorm.
 * User: Peace
 * Time: 2016年6月7日
 */
define(function (require, exports, module) {
     var core = require("core");
     //var pop = require("pop");
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
        //入口函数(选baidumap弹窗)
        ,main:function()
        {   
            $('span.copyspan1').off().on('dblclick',function(){
                $('.copyspan1').toggle();
                $('input.copyspan1').focus();
                $('input.copyspan1').select();
            });
            $('.copyspan1').on('blur',function(){
                $('.copyspan1').toggle();
            });  
            $('span.copyspan2').off().on('dblclick',function(){
                $('.copyspan2').toggle();
                $('input.copyspan2').focus();
                $('input.copyspan2').select();
            });
            $('.copyspan2').on('blur',function(){
                $('.copyspan2').toggle();
            });   
            //core.generalTip.ontextTip('请按[Ctrl+C]复制。',"error");
            //core.generalTip.onloadingTip('请按[Ctrl+C]复制。',"error");
            //alert('请按[Ctrl+C]复制。');
        }

    });
});