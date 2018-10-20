/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月19日
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
    			 manage_main.mainAareaFlushe(url+"?bundlename="+$("#bundlename").val(), {"csrf_token":csrf_token}, true);
    		 });
    		 
    		 var modelsForm = $("#modelsForm").val();
         	
         	if(modelsForm)
         		$("#model_form_id").val(modelsForm);
    	 }
    	 ,show:function()
    	 {
    		 var bundlename = $("#selectsearch_bundlename").val();
         	
         	if(bundlename)
         		$("#bundle").val(bundlename);
    	 }
    	 ,tplmanage:function()
    	 {
    		 $("[id^='popupsub_']").off().on("click",function(){
 	   			var _tmpId = $(this).attr("id");
 	   			var tmpId  = _tmpId.split("_");
 	   			if(!tmpId[2]) return false;
 	   			switch(tmpId[2])
 	   			{
 					//新增
 	    			case "add":
 	    				if($(this).parent().prev().find("#tplmanage_0_name").length>0) return false;
 	    				var url = $(this).attr('url');
 	    				$(this).parent().prev().find("tbody").append("<tr>" +
 	    					"<td></td>"+
 	    					"<td url=''><input type='text' class='editspan' data='' id='tplmanage_0_name' style='width:80%;text-align:center;' /></td>" +
 	    					"<td class='txtcenter'>now</td>" +
 	    					"<td class='txtcenter' url="+url+">" +
 	    						"<input id='popupsub_"+tmpId[1]+"_save' class='search-btn' value='保存' type='button' />" +
 	    					"</td>" +
 	    				"</tr>");
 	    				
 	    				//重新加载按钮事件
 	    				self.tplmanage();
 	    				break;
 	    			//保存
 	    			case "save":
 	    				var obj = $(this);
 	    				core.alert("确定要"+obj.val()+"数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
 	    					self.tplSaveFun(obj);
 	    				}});
 	    				break;
 	    			//删除
 	    			case "delete":
 	    				var obj = $(this);
 	    				core.alert("确定要"+obj.val()+"数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
 	    					self.tplDelFun(obj);
 	            		}});
 	    				break;
 	    			//编辑
 	    			case "edit":
 	    				var obj = $(this);
 	    				self.detailsSubmitEditFun(obj);
 	    				break;
 	    			default:
 	    				return false;
 	    				break;
 	   			}
 	   		});
 	   		
 	   		//加载自动保存事件
 			core.generalEvent.autoSave("editspan", "editinput");
    	 }
    	 /**
 	   	 * 保存
 	   	 */
 	   	,tplSaveFun:function(obj)
 	   	{
 	   		var url = "";
 	   		if(obj.parent().attr('url').length>0)
 	   			url = obj.parent().attr('url');
	   	
	   		if(!url) return false;
	   		
	   		var pData = { "name":$("#tplmanage_0_name").val(), "csrf_token":csrf_token};
	   		
 	   		if(!pData['name'])
 	   		{
 	   			core.generalTip.ontextTip("模板名称不能为空!!!","error");
 	   			return false;
 	   		}
 	   		
 	   		var _name = pData['name'].split(":");
 	   		
 	   		if(Number(_name.length)!=3)
 	   		{
 	   			alert("名称格式不能，正确格式为 【Bundle:控制器名:模版名】");
 	   			return false;
 	   		}
 		
 			//提交数据
 			core.generalFunction.ajaxSubmit({
 			    url: url,
 			    data: pData,
 			    automatic: true,
 			    beforeSend:function()
 			    {
 				   //卸载事件,防止重复点击
 				   $("[id^='popupsub_']").off();
 			    },
 			    complete:function()
 			    {
 			    	//重新加载事件
 			    	self.tplmanage();
 			    },
 			    success:function(data){
 				   	if(data.status){
						obj.parent().parent().after("<tr>" +
							"<td></td>" +
							"<td url='"+data.modurl+"'><span style='width:80%;' id='tplmanage_"+data.id+"_name' class='editspan'>"+pData['name']+"</span></td>" +
							"<td class='txtcenter'>"+data.cTime+"</td>" +
							"<td class='txtcenter' url='"+data.delurl+"'>" +
							"<input id='popupsub_"+data.id+"_delete' class='search-btn' value='删除' type='button' />" +
							"</td>" +
						"</tr>").remove();
						
						//加载自动保存事件
			 			core.generalEvent.autoSave("editspan", "editinput");
			 			
			 			//重新加载事件
	                	self.tplmanage();
 				   	}else
 				   		core.generalTip.ontextTip(data.info,"error");
 			   }
 			});
 	   	}
 	   	/**
 	   	 * 模版删除功能
 	   	 */
 	   	,tplDelFun:function(obj)
 	   	{
 	   		var url = "";
	 	   	if(obj.parent().attr('url').length>0)
				url = obj.parent().attr('url');
 	   	
 	   		if(!url) return false;
 	   		
 	   		var pData = { "csrf_token":csrf_token};
 	   		
 	   		//提交数据
			core.generalFunction.ajaxSubmit({
                url: url,
                data: pData,
                automatic: true,
                beforeSend:function()
                {
                	//卸载事件,防止重复点击
                	$("[id^='popupsub_']").off();
                	obj.prop("disabled",true);
                },
                complete:function()
                {
                	//重新加载事件
                	self.tplmanage();
                	obj.prop("disabled",false);
                },
                success:function(data){
                	if(data.status)
                		obj.parent().parent("tr").remove();
                }
            });
			return ;
 	   	}
 	   	/**
 	   	 * 创建方法
 	   	 */
 	   	,createaction:function()
 	   	{
 	   		var createtpl = $("#createtpl").val();
 	   		$("#createtpl").off().on("change", function(){
 	   		    createtpl = $(this).val();
 	   		    
 	   		    self.createtplEvent(createtpl);
 	   		});
 	   		
 	   		self.createtplEvent(createtpl);
 	   	}
 	   	,createtplEvent:function(createtpl)
 	   	{
 	   		switch(Number(createtpl))
 	   		{
	 	   		case 1:
	 	   		    $("#useform").parent().parent().show();
 	   		        $("#clonetpl").parent().parent().show();
 	   		        $("#usemodel").parent().parent().hide();
 	   		        $("#listgrid").parent().parent().hide();
 	   		        $("#listdesc").parent().parent().hide();
	 	   			break;
	 	   		case 2:
	 	   		    $("#useform").parent().parent().hide();
	   		        $("#clonetpl").parent().parent().show();
	   		        $("#usemodel").parent().parent().show();
	   		        $("#listgrid").parent().parent().show();
	   		        $("#listdesc").parent().parent().show();
	 	   			break;
	 	   		case 3:
		 	   		$("#useform").parent().parent().hide();
	   		        $("#clonetpl").parent().parent().hide();
	   		        $("#usemodel").parent().parent().show();
	   		        $("#listgrid").parent().parent().hide();
	   		        $("#listdesc").parent().parent().hide();
	 	   			break;
		 	   	case 4:
		 	   		$("#useform").parent().parent().hide();
	   		        $("#clonetpl").parent().parent().show();
	   		        $("#usemodel").parent().parent().hide();
	   		        $("#listgrid").parent().parent().hide();
	   		        $("#listdesc").parent().parent().hide();
	 	   			break;
	 	   		default:
	 	   		    $("#useform").parent().parent().hide();
	   		        $("#clonetpl").parent().parent().hide();
	   		        $("#usemodel").parent().parent().hide();
	   		        $("#listgrid").parent().parent().hide();
	   		        $("#listdesc").parent().parent().hide();
	 	   			break;
 	   		}
 	   	}
    });
});