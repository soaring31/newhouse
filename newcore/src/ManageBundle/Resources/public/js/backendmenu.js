/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月1日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module)
{
	var self;
	var core = require("core");
	
	(function(out){
		module.exports = out;
	})({
        init:function(page,par){
            //初始化加載的腳本
	        try{
	            self = this;
	            self[page](par);
	        }catch(e) {
	            core.generalTip.ontextTip("JS脚本错误！","error");
	        }
        }
        ,main:function()
        {
        	//加载自动保存事件
 			core.generalEvent.autoSave("editspan", "editinput");
 			
 			$("[id^='popupsub_']").off().on("click",function(){
 			    var _tmpId = $(this).attr("id");
 	   		    var tmpId  = _tmpId.split("_");
 	   		    if(!tmpId[2]) return false;
 	   			
 	   		    switch(tmpId[2])
 	   		    {
 	   		        case "add":
 	   		            if($(this).parent().prev().find("#backendmenu_0_name").length>0) return false;
 	   		            var url = $(this).attr('url');
 	   		            
 	   		            $(this).parent().prev().find("tbody").append("<tr>" +
 	    					"<td class='rtd'></td>"+
 	    					"<td class='txtcenter rtd'><input type='text' id='backendmenu_0_name' class='editspan' style='width:80%;' /></td>" +
 	    					"<td class='txtcenter rtd'><input type='text' id='backendmenu_0_action' class='editspan' style='width:80%;' /></td>" +
 	    					"<td class='txtcenter rtd'><input type='text' id='backendmenu_0_urlparams' class='editspan' style='width:80%;' /></td>" +
 	    					"<td class='txtcenter rtd'><input type='text' id='backendmenu_0_sort' class='editspan' style='width:80%;' /></td>" +
 	    					"<td class='txtcenter' url="+url+">" +
 	    						"<input id='popupsub_0_save' class='search-btn' value='保存' type='button' />" +
 	    					"</td>" +
 	    				"</tr>");
 	   		            
                        //重新加载按钮事件
 	    				self.main();
 	   		        	break;
                    //保存
 	    			case "save":
                        var obj = $(this);
 	    				core.alert("确定要"+obj.val()+"数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
 	    					self.saveFun(obj);
 	    				}});
 	    				break;
                    //删除
 	    			case "delete":
						var obj = $(this);
						core.alert("确定要"+obj.val()+"数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
							self.delFun(obj);
						}});
                        break;
 	    			default:
 	    				return false;
 	    				break;
 	   		    }
 			});
        }
        /**
 	   	 * 保存
 	   	 */
 	   	,saveFun:function(obj)
 	   	{
 	   		var url = "";
 	   		if(obj.parent().attr('url').length>0)
 	   			url = obj.parent().attr('url');
	   	
	   		if(!url) return false;
	   		
	   		var pData = {"csrf_token":csrf_token};
	   		
	   		//遍历
	   		$("[id^='backendmenu_0_']").map(function(){
	   			var _tmpId = $(this).attr("id");
	   			var tmpId = _tmpId.split("_");
	   			
	   			pData[tmpId.pop()] = $(this).val();
	   		});
	   		
 	   		if(!pData['name'])
 	   		{
 	   			core.generalTip.ontextTip("菜单名称不能为空!!!","error");
 	   			return false;
 	   		}
 	   		
 	   	    if(!pData['action'])
	   		{
	   			core.generalTip.ontextTip("所属动作不能为空!!!","error");
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
 			    	self.main();
 			    },
 			    success:function(data){
 				   	if(data.status){
						obj.parent().parent().after("<tr>" +
							"<td class='rtd'></td>" +
							"<td class='txtcenter rtd' url='"+data.modurl+"'><span style='width:80%;' id='backendmenu_"+data.id+"_name' class='editspan'>"+pData['name']+"</span></td>" +
							"<td class='txtcenter rtd' url='"+data.modurl+"'><span style='width:80%;' id='backendmenu_"+data.id+"_action' class='editspan'>"+pData['action']+"</span></td>" +
							"<td class='txtcenter rtd' url='"+data.modurl+"'><span style='width:80%;' id='backendmenu_"+data.id+"_urlparams' class='editspan'>"+pData['urlparams']+"</span></td>" +
							"<td class='txtcenter rtd' url='"+data.modurl+"'><span style='width:80%;' id='backendmenu_"+data.id+"_sort' class='editspan'>"+Number(pData['sort'])+"</span></td>" +
							"<td class='txtcenter' url='"+data.delurl+"'>" +
							"<input id='popupsub_"+data.id+"_delete' class='search-btn' value='删除' type='button' />" +
							"</td>" +
						"</tr>").remove();

						//加载自动保存事件
			 			core.generalEvent.autoSave("editspan", "editinput");

			 			//重新加载事件
	                	self.main();
 				   	}
 			   }
 			});
 	   	}
 	    /**
 	   	 * 删除
 	   	 */
 	   	,delFun:function(obj)
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
                	self.main();
                	obj.prop("disabled",false);
                },
                success:function(data){
                	if(data.status)
                		obj.parent().parent("tr").remove();
                }
            });
			return ;
 	   	}
	})
});