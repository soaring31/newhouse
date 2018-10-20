/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月22日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module) {
	var core = require("core");
	var manage_main = require("manage_main");
	var self;
	(function(out){
        module.exports=out;
	}) ({
         init:function(page,par){
            //初始化加載的腳本
             try{
                self = this;
                self[page](par);
             }catch(e) {
            	 core.generalTip.ontextTip("JS脚本错误！","error");
             }
         }
    	 ,main:function(){
    		 //切换bundle
    		 $("#bundlename").off().on("change", function(){
    			 var _url = $("#page").prop("title");
    			 var url = _url.substring(0, _url.indexOf('?'));
    			 url = url?url:_url;
    			 manage_main.mainAareaFlushe(url+"?bundlename="+$("#bundlename").val(), {"csrf_token":csrf_token});
    		 });
    		 
    		 var modelsForm = $("#modelsForm").val();
         	
         	if(modelsForm)
         		$("#model_form_id").val(modelsForm);
    	 }
    	 ,show:function()
    	 {

    	 }
         ,fsynmod:function()
         {
             $("#synmods").off().on("click", function(){
                var fm = $('#formsyn');
                var url = $(fm).prop('action');
                var bid = $('#tobid').val();
                $("input[name=model_ids]").each(function() {  
                    if($(this).prop("checked")) { 
                        var mk = 'mid_'+$(this).val();
                        var mid = $(this).val(); 
                        var jurl = url + '&tobid='+bid+'&mid='+mid+'';
                        $.getJSON(jurl, function (res, status) {
                            console.log(res);
                            //$('#'+mk).prop('disabled',true); 
                            $res = res.status ? '[OK]' : '[NG]';
                            $('#'+mk).replaceWith("<i id=''>"+$res+" </i>");
                        });
                    }  
                });
                //var _url = $("#page").prop("title");
             });
         }
    });
});