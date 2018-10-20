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
	   	,ident:function()
	   	{
	   		
	   	}
	   	/**
	   	 * 数据标识保存事件
	   	 */
	   	,identSaveFun:function()
	   	{
	   		$("[id^='nodeBtn_']").off().on("click",function(){
	   			var _tmpId = $(this).attr("id");
	   			var tmpId  = _tmpId.split("_");
	   			var _pData = {"csrf_token":csrf_token};
	   			
	   			//计数器
	   			var num = 0;
	   			//遍历
	   			$("[id$='_identName']").each(function(){
	   				//如果标识名称为空，则不往下执行
	   				if(!$.trim($(this).val())) return false;

	   				var _identName = $(this).attr("id");
	   				var identName  = _identName.split("_");
	   				var identVal   = $.trim($(this).val());
	   				
	   				//其它
	   				$("[id^='dataList_"+identName[1]+"_']").map(function(){
	   					var _dataList = $(this).attr("id");
	   					var dataList  = _dataList.split("_");
	   					
	   					_pData[identVal+"_"+dataList[2]] = $.trim($(this).val());
	   				});
	   				
	   				//字段别名
	   				$("[id^='dataAliasK_"+identName[1]+"_']").map(function(){
	   					var _dataAliasK = $(this).attr("id");
	   					var dataAliasK  = _dataAliasK.split("_");
	   					var aliasVal	= $.trim($(this).val());
	   					var aliasDst	= $.trim($("#dataAliasV_"+dataAliasK[1]+"_"+dataAliasK[2]).val());
	   					
	   					_pData[identVal+"_alias_"+aliasVal] = aliasDst;
	   				});
	   				
	   				//查询参数
	   				$("[id^='dataQueryS_"+identName[1]+"_']").map(function(){
	   					var _dataQueryS = $(this).attr("id");
	   					var dataQueryS  = _dataQueryS.split("_");

	   					//原字段名
	   					var querySrc = $.trim($(this).val());
	   					
	   					//运算符
	   					var querySym = $.trim($("#dataQueryK_"+dataQueryS[1]+"_"+dataQueryS[2]).val());
	   					
	   					//查询值
	   					var queryVal = $.trim($("#dataQueryV_"+dataQueryS[1]+"_"+dataQueryS[2]).val());
	   					
	   					_pData[identVal+"_query_"+querySrc+"_"+querySym] = queryVal;
	   				});
	   				
	   				//关联查询
	   				$("[id^='joinName_"+identName[1]+"_']").map(function(){
	   					//如果关联表名称为空，则不往下执行
		   				if(!$.trim($(this).val())) return false;

		   				var _joinName = $(this).attr("id");
		   				var joinName  = _joinName.split("_");
		   				var joinNameV = $.trim($(this).val());
		   				
		   				//关联其它
		   				$("[id^='joinList_"+joinName[1]+"_"+joinName[2]+"_']").map(function(){
		   					var _joinList = $(this).attr("id");
		   					var joinList  = _joinList.split("_");
		   					
		   					_pData[identVal+"_join_"+joinNameV+"_"+joinList[3]] = $.trim($(this).val());
		   				});
		   				
		   				//关联字段别名
		   				$("[id^='joinAliasK_"+joinName[1]+"_"+joinName[2]+"_']").map(function(){
		   					var _joinAliasK = $(this).attr("id");
		   					var joinAliasK  = _joinAliasK.split("_");
		   					var aliasVal	= $.trim($(this).val());
		   					var aliasDst	= $.trim($("#joinAliasV_"+joinAliasK[1]+"_"+joinAliasK[2]+"_"+joinAliasK[3]).val());

		   					_pData[identVal+"_join_"+joinNameV+"_alias_"+aliasVal] = aliasDst;
		   				});
		   				
		   				//关联查询参数
		   				$("[id^='joinQueryS_"+joinName[1]+"_"+joinName[2]+"_']").map(function(){
		   					var _joinQueryS = $(this).attr("id");
		   					var joinQueryS  = _joinQueryS.split("_");

		   					//原字段名
		   					var querySrc = $.trim($(this).val());
		   					
		   					//运算符
		   					var querySym = $.trim($("#joinQueryK_"+joinQueryS[1]+"_"+joinQueryS[2]+"_"+joinQueryS[3]).val());
		   					
		   					//查询值
		   					var queryVal = $.trim($("#joinQueryV_"+joinQueryS[1]+"_"+joinQueryS[2]+"_"+joinQueryS[3]).val());
		   					
		   					_pData[identVal+"_join_"+joinNameV+"_query_"+querySrc+"_"+querySym] = queryVal;
		   				});
	   				});
	   				
	   				num++;
	   			});
	   			
	   			//提交url
		   		var _url = "entry?entry="+tmpId[1]+"&action=identsave";
		   		
		   		if(num==0)
		   		{
		   			core.generalTip.ontextTip('未添加数据标识',"error");
		   			return false;
		   		}
		   		
		       	core.generalFunction.ajaxSubmit({
					url: _url,
					data:_pData,
					automatic:false,
					beforeSend:function()
					{
						//卸载事件,防止重复点击
						$("[id^='nodeBtn_']").off();
					},
					complete:function()
					{
						//重新加载事件
						self.identSaveFun();
					},
					success:function(data){
						if(data.status)
							core.generalTip.ontextTip(data.info,"right");
						else
							core.generalTip.ontextTip(data.info,"error");
					}
		       	});
	   		});
	   	}

	   	/**
	   	 * 标识新增功能
	   	 */
	   	,identAddFuns:function()
	   	{
	   		
	   	}
	   	,identAddFuns: function()
	   	{
	   		$("[id^='nodeAdd_']").off().on('click',function(){
	   			var ojb = $(this);
	   			var _tmpId = $(this).attr("id");
	   			var tmpId  = _tmpId.split("_");
	   			var _pData = {"csrf_token":csrf_token};
	   			
	   			if(tmpId[1]=='alias'||tmpId[1]=='query')
					var tablename = $("#dataList_"+tmpId[2]+"_tableName").val();
				else
					var tablename = $("#joinName_"+tmpId[2]+"_"+tmpId[3]).val();

				if(tmpId[1]!='join'&&tmpId[1]!='node'&&!tablename){
					core.generalTip.ontextTip("表名称不能为空","error");
					return false;
				}

	   			switch(tmpId[1])
	   			{
	   				//普通查询别名功能
	   				case 'alias':
	   				//关联查询别名功能
	   				case 'joinalias':
	   					var divTest = $(this).parent().prev();  //获取div
	   					if(divTest.length){
	   						//克隆上一个div
	   						var newDiv = divTest.clone(true);
		   					divTest.after(newDiv);
		   					var _tempNo = divTest.find("input[type='text']").attr("id");
		   					var tempNo = _tempNo.split("_");
		   					tempNo[tempNo.length-1] = parseInt(tempNo[tempNo.length-1])+1;

		   					//join
		   					newDiv.find("input[type='text']").attr("id", tempNo.join('_'));
		   					
		   					if(tmpId[1]=='alias')
		   						tempNo[0] = "alias";
		   					else
		   						tempNo[0] = "joinalias";
		   					newDiv.find("input[type='button']").attr("id", "nodeDel_"+tempNo.join('_'));
		   					
		   					if(tmpId[1]=='alias')
		   						tempNo[0] = "dataAliasV";
		   					else
		   						tempNo[0] = "joinAliasV";
		   					newDiv.find("select").attr("id", tempNo.join('_'));
	   					}else{
	   						//获得表字段
	   				   		core.generalFunction.ajaxSubmit({
	   							url: "entry?entry="+tmpId[tmpId.length-1]+"&action=getjson&&type=1&&tablename="+tablename,
	   							data:{"csrf_token":csrf_token},
	   							automatic:false,
	   							beforeSend:function()
	   							{
	   								//卸载事件,防止重复点击
	   								$("[id^='nodeAdd_']").off();
	   							},
	   							complete:function()
	   							{
	   								//重新加载事件
	   								self.identEdit();
	   							},
	   							success:function(result){
	   								if(result.status){
	   									var joinQueryS;
	   									
	   									if(tmpId[1]=='alias'){
		   									divTest = "<div>";
		   									divTest +="<input type='text' class='w100' id='dataAliasK_"+tmpId[2]+"_1' />-";
		   			   						divTest +="<select type='text' class='w100' id='dataAliasV_"+tmpId[2]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.data,function(fieldK,fieldV)
		   			   						{
		   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   							joinQueryS += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						divTest +="<input type='button' class='search-btn' value='删除' id='nodeDel_alias_"+tmpId[2]+"_1' />";
		   			   						divTest +="</div>";
		   			   						$("[id^='dataQueryS_"+tmpId[2]+"_']").html(joinQueryS);
	   									}else if(tmpId[1]=='joinalias'){
	   										divTest = "<div>";
		   									divTest +="<input type='text' class='w100' id='joinAliasK_"+tmpId[2]+"_"+tmpId[3]+"_1' />-";
		   			   						divTest +="<select type='text' class='w100' id='joinAliasV_"+tmpId[2]+"_"+tmpId[3]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.data,function(fieldK,fieldV)
		   			   						{
		   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   							joinQueryS += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						divTest +="<input type='button' class='search-btn' value='删除' id='nodeDel_joinalias_"+tmpId[2]+"_"+tmpId[3]+"_1' />";
		   			   						divTest +="</div>";
		   			   						$("[id^='joinQueryS_"+tmpId[2]+"_"+tmpId[3]+"_']").html(joinQueryS);
	   									}

	   			   						ojb.parent().before(divTest);
	   								}else{
	   									core.generalTip.ontextTip(result.info,"error");
	   									return false;
	   								}
	   							}
	   				       	});  						
	   					}
	   					break;
	   				case "query":
	   				case "joinquery":
	   					var divTest = $(this).parent().prev();  //获取div
	   					if(divTest.length){
	   						//克隆上一个div
	   						var newDiv = divTest.clone(true);
		   					divTest.after(newDiv);
		   					var _tempNo = divTest.find("input[type='text']").attr("id");
		   					var tempNo = _tempNo.split("_");
		   					tempNo[tempNo.length-1] = parseInt(tempNo[tempNo.length-1])+1;
		   					
		   					//join
		   					newDiv.find("input[type='text']").attr("id", tempNo.join('_'));
		   					
		   					if(tmpId[1]=='query')
		   						tempNo[0] = "query";
		   					else
		   						tempNo[0] = "joinquery";
		   					newDiv.find("input[type='button']").attr("id", "nodeDel_"+tempNo.join('_'));

		   					//join
		   					if(tmpId[1]=='query')
		   						tempNo[0] = "dataQueryS";
		   					else
		   						tempNo[0] = "joinQueryS";
		   					newDiv.find("select").eq(0).attr("id", tempNo.join('_'));

		   					if(tmpId[1]=='query')
		   						tempNo[0] = "dataQueryK";
		   					else
		   						tempNo[0] = "joinQueryK";
		   					newDiv.find("select").eq(1).attr("id", tempNo.join('_'));
	   					}else{
	   						//获得表字段
	   				   		core.generalFunction.ajaxSubmit({
	   							url: "entry?entry="+tmpId[tmpId.length-1]+"&action=getjson&&type=1&&tablename="+tablename,
	   							data:{"csrf_token":csrf_token},
	   							automatic:false,
	   							beforeSend:function()
	   							{
	   								//卸载事件,防止重复点击
	   								$("[id^='nodeAdd_']").off();
	   							},
	   							complete:function()
	   							{
	   								//重新加载事件
	   								self.identEdit();
	   							},
	   							success:function(result){
	   								if(result.status){
	   									var joinQueryS;
	   									
	   									if(tmpId[1]=='query'){
		   									divTest = "<div>";
		   									
		   									//select1
		   			   						divTest +="<select id='dataQueryS_"+tmpId[2]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.data,function(fieldK,fieldV)
		   			   						{
		   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						
		   			   						//select2
			   			   					divTest +="<select id='dataQueryK_"+tmpId[2]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.symbol,function(symbolK,symbolV)
		   			   						{
		   			   							divTest += "<option value='"+symbolK+"'>"+symbolV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						divTest +="<input type='text' class='w100' id='dataQueryV_"+tmpId[2]+"_1' />";
		   			   						divTest +="<input type='button' class='search-btn' value='删除' id='nodeDel_query_"+tmpId[2]+"_1' />";
		   			   						divTest +="</div>";
	   									}else if(tmpId[1]=='joinquery'){
	   										divTest = "<div>";
		   									
		   									//select1
		   			   						divTest +="<select id='joinQueryS_"+tmpId[2]+"_"+tmpId[3]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.data,function(fieldK,fieldV)
		   			   						{
		   			   							divTest += "<option value='"+fieldK+"'>"+fieldV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						
		   			   						//select2
			   			   					divTest +="<select id='joinQueryK_"+tmpId[2]+"_"+tmpId[3]+"_1'>";
		   			   						divTest +="<option value=''>&nbsp;</option>";
		   			   						$.each(result.symbol,function(symbolK,symbolV)
		   			   						{
		   			   							divTest += "<option value='"+symbolK+"'>"+symbolV+"</option>";
		   			   						});
		   			   						divTest +="</select>";
		   			   						divTest +="<input type='text' class='w100' id='joinQueryV_"+tmpId[2]+"_"+tmpId[3]+"_1' />";
		   			   						divTest +="<input type='button' class='search-btn' value='删除' id='nodeDel_joinquery_"+tmpId[2]+"_"+tmpId[3]+"_1' />";
		   			   						divTest +="</div>";
	   									}

	   			   						ojb.parent().before(divTest);
	   								}else{
	   									core.generalTip.ontextTip(result.info,"error");
	   									return false;
	   								}
	   							}
	   				       	});	   						
	   					}
	   					break;
	   				case 'node':
	   					var tdTest1 = $("#dataHeared_0");
	   					var tdTest2 = $("#dataToggle_0");
	   					var newTd1 	= tdTest1.clone(true);
	   					var newTd2 	= tdTest2.clone(true);
	   					var _lastHeared = $("[id^='dataHeared_']:last").attr("id");
	   					var _lastToggle = $("[id^='dataToggle_']:last").attr("id");
	   					var lastHeared 	= _lastHeared.split("_");
	   					var lastToggle 	= _lastToggle.split("_");

	   					lastHeared[lastHeared.length-1] = parseInt(lastHeared[lastHeared.length-1])+1;
	   					lastToggle[lastToggle.length-1] = parseInt(lastToggle[lastToggle.length-1])+1;
	   					newTd1.removeClass("hide");
	   					newTd2.removeClass("hide");
	   					newTd1.attr("id", lastHeared.join('_'));
	   					newTd2.attr("id", lastToggle.join('_'));
	   					
	   					newTd1.find("#nodeDel_node_0").attr("id", "nodeDel_node_"+lastHeared[lastHeared.length-1]);
	   			
	   					newTd2.find("#nodeAdd_alias_0_"+tmpId[2]).attr("id", "nodeAdd_alias_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("#nodeAdd_query_0_"+tmpId[2]).attr("id", "nodeAdd_query_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("#nodeAdd_join_0_"+tmpId[2]).attr("id", "nodeAdd_join_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("#nodeAdd_alias_0_"+tmpId[2]).attr("id", "nodeAdd_alias_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("#nodeAdd_alias_0_"+tmpId[2]).attr("id", "nodeAdd_alias_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("#nodeAdd_alias_0_"+tmpId[2]).attr("id", "nodeAdd_alias_"+lastHeared[lastHeared.length-1]+"_"+tmpId[2]);
	   					newTd2.find("[id^='dataList_']").each(function(){
	   						var _tmpDataList = $(this).attr("id");
	   						var tmpDataList = _tmpDataList.split("_");
	   						tmpDataList[1] = lastHeared[lastHeared.length-1];
	   						$(this).attr("id",tmpDataList.join('_'));
	   					});
	   					
	   					//console.log(newTd2.find("input"));
	   					
	   					ojb.parent().parent().parent().append(newTd1);
	   					ojb.parent().parent().parent().append(newTd2);
	   					break;
	   				case 'join':
	   					return false;
	   					var divTest = $(this).parent().prevAll("div:last");  //获取div
	   					var _tempNo = $(this).parent().prev("div").find("[id^='joinName_']:first").attr("id");
	   					var tempNo = _tempNo.split("_");
	   					//alert(tempDivId);
	   					//return false;
	   					
	   					//克隆上一个div
   						var newDiv = divTest.clone(true);
   						
   						tempNo[tempNo.length-1] = parseInt(tempNo[tempNo.length-1])+1;
   						
   						//join
	   					newDiv.find("#joinName_0_0").attr("id", tempNo.join('_'));
	   					
	   					if(tmpId[1]=='query')
	   						tempNo[0] = "query";
	   					else
	   						tempNo[0] = "joinquery";
	   					newDiv.find("input[type='button']").attr("id", "nodeDel_"+tempNo.join('_'));

	   					//join
	   					if(tmpId[1]=='query')
	   						tempNo[0] = "dataQueryS";
	   					else
	   						tempNo[0] = "joinQueryS";
	   					newDiv.find("select").eq(0).attr("id", tempNo.join('_'));

	   					if(tmpId[1]=='query')
	   						tempNo[0] = "dataQueryK";
	   					else
	   						tempNo[0] = "joinQueryK";
	   					newDiv.find("select").eq(1).attr("id", tempNo.join('_'));
							
	   					$(this).parent().before(newDiv);
	   					break;
	   			}
	   		});
	   	}

	   	/**
	   	 * 标识删除功能
	   	 */
	   	,identDelFun:function()
	   	{
	   		$("[id^='nodeDel_']").off().on('click',function(){
	   			var obj = $(this);
	   			var _tmpId = obj.attr("id");
	   			var tmpId   = _tmpId.split("_");
	   			core.alert("确定要"+obj.val()+"该数据吗？",{'type':'warn','confirm':true,'cancel':true,callback:function(){
	   				switch(tmpId[1])
	   				{
	   					case 'node':
	   						$("#dataToggle_"+tmpId[2]).remove();
	   						$("#dataHeared_"+tmpId[2]).remove();
	   						break;
	   					default:
	   						obj.parent().remove();
	   						break;
	   				}	   				
	   			}});	   			
	   		});
	   	}
    })
})