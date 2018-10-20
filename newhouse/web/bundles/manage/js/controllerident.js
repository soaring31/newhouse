/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月3日
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

	   	}
	   	,show:function()
	   	{
	   		$("[id^='node_']").off().on('click', function(){
	   			obj = $(this);
	   			var _tmpId = obj.attr("id");
	   			var tmpId = _tmpId.split("_");
	   			switch(tmpId[1])
	   			{
	   				//别名变更处理
		   			case 'alias':
		   				self.aliasChange(obj);
		   				break;
		   				
		   			//查询变更处理
		   			case 'query':
		   				self.queryChange(obj);
		   				break;
		   			case 'query1':
		   				self.queryChange1(obj);
		   				break;
		   			//关联变更处理
		   			case 'join':
		   				self.joinChange(obj);
		   				break;
	   			}
	   		});
	   		var dataModel = $("[name='dataModel']").val();
	   		self.dataModelShow(dataModel);
	   		$("[name='dataModel']").off().on('change', function()
	   		{
	   			dataModel = $(this).val();
	   			self.dataModelShow(dataModel);
	   		});
	   	}
	   	,dataModelShow:function(dataModel)
	   	{
	   		switch(Number(dataModel))
	   		{
		   		case 2:
		   		case 4:
		   			$("[id^='dataModel_']").show();
		   			break;
		   		default:
		   			$("[id^='dataModel_']").hide();
		   			break;
	   		}
	   	}
	   	/**
	   	 * 别名变更处理
	   	 */
	   	,aliasChange:function(obj)
	   	{
	   		var _tmpId = obj.attr("id");
   			var tmpId = _tmpId.split("_"); 			
   			
   			switch(tmpId[2])
   			{
	   			case 'add':
	   				var url = obj.attr("data-url");
	   	   			
	   	   			if(!url) return false;
	   				//获取div
	   				var divTest = obj.parent().prev();
	   				if(divTest.length)
	   				{
	   					//克隆上一个div
   						var newDiv = divTest.clone(true);
   						divTest.after(newDiv);
	   				}else{
	   					var tableName = obj.parents("tbody").find("[name='tableName']").val();
	   					if(!tableName)
	   					{
	   						core.generalTip.ontextTip("请设置正确的表名称","error");
	   						return false;
	   					}
	   					//获得表字段
   				   		core.generalFunction.ajaxSubmit({
   							url: url,
   							data:{"tablename":tableName},
   							beforeSend:function()
   							{
   								//卸载事件,防止重复点击
   								$("[id^='node_']").off();
   							},
   							complete:function()
   							{
   								//重新加载事件
   								self.show();
   							},
   							success:function(result){
   								if(result.status){
   									divTest = "<div>";
   									divTest +="<input type='text' class='w100' name='dataAliasK[]' />-";
   			   						divTest +="<select type='text' class='w100' name='dataAliasV[]'>";
   			   						$.each(result.data,function(fieldK,fieldV)
   			   						{
   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
   			   						});
   			   						divTest +="</select>";
   			   						divTest +="<input type='button' class='detail-btn' id='node_alias_delete' value='删除' />";
   			   						divTest +="</div>";

   			   						obj.parent().before(divTest);
   			   						
   			   						//重新加载事件
   			   						self.show();
   								}
   							}
   				       	});
	   				}
	   				break;
	   			case 'delete':
	   				obj.parent().remove();
	   				break;
   			}
	   	}
	   	,queryChange:function(obj)
	   	{
	   		var _tmpId = obj.attr("id");
   			var tmpId = _tmpId.split("_"); 			
   			
   			switch(tmpId[2])
   			{
	   			case 'add':
	   				var url = obj.attr("data-url");
	   	   			
	   	   			if(!url) return false;
	   				//获取div
	   				var divTest = obj.parent().prev();
	   				if(divTest.length)
	   				{
	   					//克隆上一个div
   						var newDiv = divTest.clone(true);
   						divTest.after(newDiv);
	   				}else{
	   					var tableName = obj.parents("tbody").find("[name='tableName']").val();
	   					if(!tableName)
	   					{
	   						core.generalTip.ontextTip("请设置正确的表名称","error");
	   						return false;
	   					}
	   					//获得表字段
   				   		core.generalFunction.ajaxSubmit({
   							url: url,
   							data:{"tablename":tableName},
   							beforeSend:function()
   							{
   								//卸载事件,防止重复点击
   								$("[id^='node_']").off();
   							},
   							complete:function()
   							{
   								//重新加载事件
   								self.show();
   							},
   							success:function(result){
   								if(result.status){
   									divTest = "<div>";
   									
   									//select1
   			   						divTest +="<select name='dataQueryS[]'>";
   			   						$.each(result.data,function(fieldK,fieldV)
   			   						{
   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
   			   						});
   			   						divTest +="</select>";
   			   						
   			   						//select2
	   			   					divTest +="<select name='dataQueryK[]'>";
   			   						$.each(result.symbol,function(symbolK,symbolV)
   			   						{
   			   							divTest += "<option value='"+symbolK+"'>"+symbolV+"</option>";
   			   						});
   			   						divTest +="</select>";
   			   						divTest +="<input type='text' class='w100' name='dataQueryV[]' />";
   			   						divTest +="<input type='button' class='detail-btn' value='删除' id='node_query_delete' />";
   			   						divTest +="</div>";

   			   						obj.parent().before(divTest);
   			   						
   			   						//重新加载事件
   			   						self.show();
   								}
   							}
   				       	});
	   				}
	   				break;
	   			case 'delete':
	   				obj.parent().remove();
	   				break;
   			}
	   	}
	   	,queryChange1:function(obj)
	   	{
	   		var _tmpId = obj.attr("id");
   			var tmpId = _tmpId.split("_"); 			
   			
   			switch(tmpId[2])
   			{
	   			case 'add':
	   				var url = obj.attr("data-url");
	   	   			
	   	   			if(!url)
	   	   				return false;

	   				//获取div
	   				var divTest = obj.parent().prev();
	   				if(divTest.length)
	   				{
	   					//克隆上一个div
   						var newDiv = divTest.clone(true);
   						divTest.after(newDiv);
	   				}else{
	   					var tableName = obj.parents("tbody").find("[name='subTableName']").val();
	   					if(!tableName)
	   					{
	   						core.generalTip.ontextTip("请设置正确的子表名称","error");
	   						return false;
	   					}
	   					//获得表字段
   				   		core.generalFunction.ajaxSubmit({
   							url: url,
   							data:{"tablename":tableName},
   							beforeSend:function()
   							{
   								//卸载事件,防止重复点击
   								$("[id^='node_']").off();
   							},
   							complete:function()
   							{
   								//重新加载事件
   								self.show();
   							},
   							success:function(result){
   								if(result.status){
   									divTest = "<div>";
   									
   									//select1
   			   						divTest +="<select name='dataQueryS1[]'>";
   			   						$.each(result.data,function(fieldK,fieldV)
   			   						{
   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
   			   						});
   			   						divTest +="</select>";
   			   						
   			   						//select2
	   			   					divTest +="<select name='dataQueryK1[]'>";
   			   						$.each(result.symbol,function(symbolK,symbolV)
   			   						{
   			   							divTest += "<option value='"+symbolK+"'>"+symbolV+"</option>";
   			   						});
   			   						divTest +="</select>";
   			   						divTest +="<input type='text' class='w100' name='dataQueryV1[]' />";
   			   						divTest +="<input type='button' class='detail-btn' value='删除' id='node_query1_delete' />";
   			   						divTest +="</div>";

   			   						obj.parent().before(divTest);
   			   						
   			   						//重新加载事件
   			   						self.show();
   								}
   							}
   				       	});
	   				}
	   				break;
	   			case 'delete':
	   				obj.parent().remove();
	   				break;
   			}
	   	}
	   	,joinChange:function(obj)
	   	{
	   		var _tmpId = obj.attr("id");
   			var tmpId = _tmpId.split("_"); 			
   			
   			switch(tmpId[2])
   			{
	   			case 'add':
	   				//获取div
	   				var divTest = obj.parent().prev();
	   				if(divTest.length<=0)
	   				{
	   					core.generalTip.ontextTip("参数错误","error");
   						return false;
	   				}
	   				
	   				if($("form").length>=3)
	   				{
	   					core.generalTip.ontextTip("关联表不能超过3个","error");
   						return false;
	   				}
	   				
   					//克隆上一个div
					var newDiv = divTest.clone(true);
					
					var _tempClass = divTest.attr("class");
   					var tempClass = _tempClass.split("_");
   					
   					var newClass = tempClass[0]+"_"+(Number(tempClass[1])+1);
 
   					newDiv.attr("class",newClass);
   					newDiv.find("[name='submit']").attr("target-form",newClass);
   					newDiv.find("[name='tableName']").val("");
   					newDiv.find("[name='aliasName']").val("");
   					newDiv.find("[name='dataAliasK[]']").parent().remove();
   					newDiv.find("[name='dataQueryS[]']").parent().remove();   					
   					
					divTest.after(newDiv);
	   				break;
	   			case 'delete':	   				
	   				
	   				var url = obj.attr("data-url");
	   	   			
	   	   			if(!url) return false;
	   	   			var identName = $("[name='identName']").val();
	   	   			var tableName = obj.parents("form").find("[name='tableName']").val();
		   	   		core.alert("确定要删除"+tableName+"表关联吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
			        	core.generalFunction.ajaxSubmit({
			                  url: url,
			                  data:{"identName":identName, "tableName":tableName},
			                  beforeSend:function()
			                  {
			                	  //卸载事件,防止重复点击
			                	  $("[id^='node_']").off();
			                  },
			                  complete:function()
			                  {
			                	  //重新加载事件
			                	  self.show();
			                  },
			                  success:function(data){
		                	      if(data.status)
		                	    	  obj.parents("form").remove();
			                  }
			              });
		    		}});
	   				break;
   			}
	   	}
    });
})