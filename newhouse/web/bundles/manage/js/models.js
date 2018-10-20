/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月15日
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
				core.generalTip.ontextTip("[models.js]脚本错误！","error");
			}
        }
        ,main:function(){
			//切换表单
			$("#modelsForm").off().on("change", function(){
				var _url = $("#page").prop("title");
				var url = _url.substring(0, _url.indexOf('?'));
				url = url?url:_url;
				manage_main.mainAareaFlushe(url+"?id="+$("#modelsForm").val(), {"csrf_token":csrf_token});
			});
        }
        //表单管理
        ,modelsform:function()
        {
        	var bindform = $("#bindform").val();
        	// self.modelsFormchoicesHide(bindform);
        	$("#bindform, #type").off().on('change',function(){
        		bindform = $("#bindform").val();
        		// self.modelsFormchoicesHide(bindform);
        	});
        }
        ,modelsFormchoicesHide:function(bindform)
        {
        	if(bindform)
        		$("#bindfield").parent().parent().show();
        	else
        		$("#bindfield").parent().parent().hide();

        	var type = $("#type").val();

        	if(type==1)
        		$("#bindfield").parent().parent().show();
        }
        //表单属性管理
        ,modelsformattr:function()
        {
        	var type = $("#type").val();

        	self.choicesHide(type);

        	$("#type").off().on('change',function(){
        		type = $(this).val();
        		self.choicesHide(type);
        	});

        	$("#isonly").off().on('change',function(){
        		self.choicesHide(type);
        	});

            var dhtype = $("#dealhtml").val();
            self.dhTypeHide(dhtype);
        	$("#dealhtml").off().on('change',function(){
        		var dhtype = $(this).val();
                self.dhTypeHide(dhtype);
        	});

        	var modelsForm = $("#modelsForm").val();

        	if(modelsForm)
        		$("#model_form_id").val(modelsForm);
        }
        ,dhTypeHide:function(dhtype)
        {
        	if(dhtype=='clearhtml')
        		$("#dealhtmltags").parent().parent().parent().show();
        	else
        		$("#dealhtmltags").parent().parent().parent().hide();
        }
        ,choicesHide:function(type)
        {
        	switch(type)
        	{
        		case 'radio':
        		case 'checkbox':
                case 'switch':
        		case 'choice':
        			$("#choices").parent().parent().show();
        			$("#entitypath").parent().parent().hide();
        			$("#property").parent().parent().hide();
        			$("#query_builder").parent().parent().hide();
        			$("#iswatermark").parent().parent().hide();
        			break;
        		case 'ajaxbind':
        		case 'entity':
        		case 'push':
        		case 'ymlfile':
        		case 'entitygroup':
                case 'bundle':
        			$("#choices").parent().parent().hide();
        			$("#entitypath").parent().parent().show();
        			$("#property").parent().parent().show();
        			$("#query_builder").parent().parent().show();
        			$("#iswatermark").parent().parent().hide();
        			break;
        		case 'image':
                case 'ueditor':
        			$("#choices").parent().parent().hide();
        			$("#entitypath").parent().parent().hide();
        			$("#property").parent().parent().hide();
        			$("#query_builder").parent().parent().hide();
        			$("#iswatermark").parent().parent().show();
        			break;
        		default:
        			$("#choices").parent().parent().hide();
        			$("#entitypath").parent().parent().hide();
        			$("#property").parent().parent().hide();
        			$("#query_builder").parent().parent().hide();
        			$("#iswatermark").parent().parent().hide();
        			break;
        	}

        	var isonly = $("#isonly").val();
        	if(isonly>=1)
        		$("#query_builder").parent().parent().show();
        }
        //表单字段管理
        ,modelsfield:function()
        {
        	var type = $("#type").val();
        	self.choicesHide(type);
        	$("#type").off().on('change',function(){
        		type = $(this).val();
        		self.choicesHide(type);
        	});

        	var modelsForm = $("#modelsForm").val();
        	if(modelsForm)
        		$("#model_id").val(modelsForm);
        }
        //模型管理
        ,modelsmanage:function()
        {

        }

        //表单预览
        ,preview:function()
        {
        	$("[data-preview]").each(function(){
        		$(this).after("<span>"+$(this).attr('data-preview')+"</span>");
        	});

        	$("#submit").prop("disabled", true);
            $("table input[type=hidden]").prop("type","text");
            $("table").prop("hidden",false);
        }
    })

});