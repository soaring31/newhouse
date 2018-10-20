/*!
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月18日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module) {
     var core = require("core");
     var dialog = require("dialog");
     var self;

    (function(out){
        module.exports=out;
    }) ({
    	//调用函数
    	/*
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

    	,main:function()
    	{
    		$("[id^='mainsubmit_']").off().on("click",function(e){
    			e.preventDefault();
    			var _tmpId	= $(this).attr("id");
    			var tmpId	= _tmpId.split("_");
    			var _url	= $(this).attr('data-url');
    			var _pData	= { "csrf_token":csrf_token};
    			
    			//获得区域表单数据并嵌入
				_pData = core.generalFunction.getAreaFormData($(this).parents('.right-table:first'), _pData);
				
				//提交数据
    			core.generalFunction.ajaxSubmit({
                    url: _url,
                    data: _pData,
                    automatic: false,
                    beforeSend:function()
                    {
                    	//卸载事件,防止重复点击
                    	$("[id^='mainsubmit_']").off();
                    },
                    complete:function()
                    {
                    	//重新加载事件
                    	self.main();
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
    	 * 附件配置
    	 */
    	,cfupfile:function()
    	{
    		$("[name='upload_type']").off().on("change",function(){
    			var thisVal = $.trim($(this).val());
                switch(thisVal)
                {
                    case 'upyun':
                        $("[id^='upyun_']").show();
                        $("[id^='remote_']").hide();
                        break;
                    case 'remote':
                        $("[id^='upyun_']").hide();
                        $("[id^='remote_']").show();
                        break;
                    case 'local':
                        $("[id^='upyun_']").hide();
                        $("[id^='remote_']").hide();
                        break;
                }

    			// if(thisVal=='upyun')
    			// 	$("[id^='upyun_']").show();
    			// else
    			// 	$("[id^='upyun_']").hide();
    		})
    	}
    	/**
    	 * 在线支付配置
    	 */
    	,cfalipay:function()
    	{
    		$("[name='pay_type']").off().on("change",function(){
    			var thisVal = $.trim($(this).val());
    			switch(thisVal)
    			{
	    			case 'alipay':
	    				$("[id^='alipay_']").show();
	    				$("[id^='tenpay_']").hide();
	    				break;
	    			case 'tenpay':
	    				$("[id^='alipay_']").hide();
	    				$("[id^='tenpay_']").show();
	    				break;
    			}
    		})
    	}
    	/**
    	 * 模版配置
    	 */
    	,cfthemes:function()
    	{
    		
    	}
    	/**
    	 * 微wifi
    	 */
    	,cfweiwifi:function()
    	{
    		
    	}
    	/**
    	 * 平台支付配置
    	 */
    	,cfplatform:function()
    	{   		
    		$(".a_choose").off().on("click",function(){
    			var param = $(this).attr("data");
    			if(!param) return false;
    			param = param.split(",");
    			if($("#"+param[0]+'_setting').length<=0) return false;
    			
    			var content = $("#"+param[0]+'_setting');
    			
    			dialog({
    				title:param[1]+' 支付配置：',
    				content:content,
    				scrolling: 'auto',
    				lock:true,
    				okValue: '确 定',
    				ok: function () {
    					form = $('.form');
    					query = content.find('input,select,textarea').serialize();;
    					target = form.get(0).action;
    					//提交数据
						core.generalFunction.ajaxSubmit({
						    url: target,
							data: query,
							beforeSend:function()
		                    {
		                  	  	//卸载事件,防止重复点击
								//$(that).addClass('disabled').attr('autocomplete', 'off');
		                    },
							complete:function()
							{
								//$(that).removeClass('disabled').prop('disabled', false);
							},
							success:function(data){
						    }
						});
    			    },
    			    onclose:function(){
    			    	//console.log(this);
    				}

    			}).showModal();
    		});
    	}
    	//水印设置
    	,webwatermark:function()
    	{
    		$("[name='position[]']").off().on("change",function(){
    			var lengths = $("[name='position[]']:checked").length;
    			if(lengths>=3){
    				if(lengths>3){
    					core.generalTip.ontextTip('最多只能选择3个位置', 'error');
    					$(this).prop('checked',false);
    				}
    				
    				$("[name='position[]']").not("input:checked").prop('disabled',true);
    				
    				return false;
    			}
    			$("[name='position[]']").not("input:checked").prop('disabled',false);
    		});

    		self.seltype($("#type").val());
    		$("#type").off().on("change",function(){
         		self.seltype($(this).val());
    		})
            if ($("#type").closest('form').data('id')) {
                $("#type").prop('disabled', true).off();
            };
    	}
    	,seltype:function(type)
    	{
    		switch(Number(type))
    		{
    		    case 2:
    		    	$("#textcontent").parent().parent().show();
    		    	$("#fontfile").parent().parent().show();
    		    	$("#fontsize").parent().parent().show();
    		    	$("#angle").parent().parent().show();
    		    	$("#fontcolor").parent().parent().show();
    		    	$("#trans").parent().parent().hide();
    		    	$("#quality").parent().parent().hide();
    		    	$("#img").parent().parent().hide();
    		    	break;
    		    default:
    		    	$("#textcontent").parent().parent().hide();
			    	$("#fontfile").parent().parent().hide();
			    	$("#fontsize").parent().parent().hide();
			    	$("#angle").parent().parent().hide();
			    	$("#fontcolor").parent().parent().hide();
			    	$("#trans").parent().parent().show();
			    	$("#quality").parent().parent().show();
			    	$("#img").parent().parent().show();
    		    	break;
    		}
    	}
    });
});