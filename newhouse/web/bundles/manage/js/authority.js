/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月11日
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
		,group:function(title){
			$(".wk-pop-title").html("[<b style='color:rgb(129, 102, 94);'>"+title+"</b>] "+$(".wk-pop-title").text());
			//加载自动保存事件
			core.generalEvent.autoSave("editspan", "editinput");
			//显示隐藏
			$("[id^='showbtn_']").off().on("click",function(e){
				e.preventDefault();
				var _tmpId = $(this).attr("id");
				var tmpId  = _tmpId.split("_");
				//同级元素收缩
				$(this).parents("tr").siblings().find("[id^='showbtn_']").each(function(){
					$(this).text("+");
					$(this).parents("tr").nextUntil(".maintr").hide();
				});
			
				//自己的收缩动作
				if($(this).text()=="+"){
					$(this).text("-");
					$(this).parents("tr").nextUntil(".maintr").show();
				}else{
					$(this).text("+");
					$(this).parents("tr").nextUntil(".maintr").hide();
				}
			});
			
			//全选
			$("[id^='selectbtn_']").off().on("click",function(){
				var _tmpId = $(this).attr("id");
				var tmpId  = _tmpId.split("_");
				switch(tmpId[1])
				{
					case 'all':
					$(this).parents("tr").nextUntil(".maintr").find("input[type='checkbox']").prop("checked",$(this).is(':checked'));
						break;
					case 'sub':
					$(this).parent().next().find("input[type='checkbox']").prop("checked",$(this).is(':checked'));
						break;
					default:
						break;
				}
			});
		}
});
});