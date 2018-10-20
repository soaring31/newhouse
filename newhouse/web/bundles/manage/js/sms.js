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
            //$('#msg').off().on('blur',function(){
            $("#msg").blur(function(){ 
                var vmsg = $('#msg').val(); //console.log(vmsg);
                $('#idMsglen').html(vmsg.length);
            });
            $('#idTestmsg').off().on('click',function(){
                $('#msg').val(defMsg);
                $('#msg').trigger('blur'); //.toggle();
                $('#tel').val(defTel);
            });  
        }

        //短信余额(后台balance设置)
        ,balance:function()
        {   
            $('#name').val('balance');
            $('#name').prop('readonly',true);
            $('#title').val('短信余额');
            $('#title').prop('readonly',true);
            $('#type').val('sms');
            $('#type').prop('readonly',true);
            //if(isEdit) $('#id').prop('readonly',true);
        }

    });
});