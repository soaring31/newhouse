/*!
 * 核心工具 core
 * User: Anday
 * Date: 14-05-03
 * Time: 下午3:28
 */
define(function (require, e, m) {
	var ajaxSubmitLock = true;
    var _system = {
        _window: (typeof parent.document == "undefined" ? $(window) : $(parent.window)),
        _document: (typeof parent.document == "undefined" ? $(document) : $(parent.document)),
        _body:(typeof parent.document == "undefined" ? $("body") : $(parent.document.body)),
        /**********************通用页面变量**************************/
        pageVariable: {
            customUIParent: "body",         //指定要绑定自定义控件的父级对像ID
            validatorFormID: "form1",            //指定要验证表单的ID
            validatorFormMethods: null,           //验证成功之后反回函数
            formSubmitLock: false,                 //提交之前锁定按钮
            clearPopCallbackTimeOut: null           //清除弹出回调时间
        },
        /**********************通用事件**************************/
        generalEvent: {
            /**********************绑定回车键盘事件**************************/
            enterKeyDown: function (methods, issurekey) {
                $(document).keydown(function (e) {
                    if (issurekey) {
                        if ((e.keyCode == 13 || e.keyCode == 0)) {
                            if (typeof (methods) == 'function') {
                            	methods();
                            }
                        }
                    }
                });
            },
            /**********************绑定ESC键盘事件**************************/
            escKeyDown:function(BOXID,callback){
            	$(document).keydown(function (e) {
                    if ((e.keyCode == 27)) {
                        if (typeof (callback) == 'function') {
                        	callback();
                        }
                    }
                });
            },
            /**********************解除绑定键盘事件**************************/
            unbindKeyDown: function () {
                $(document).unbind("keydown");
            },
            
            /*****************  *****类型判断*****************************/
            isType: function(type) {
                return function(obj) {
                    return Object.prototype.toString.call(obj) === "[object " + type + "]";
                }
            },
            change_event:function(obj){	
            	var hiderel = $(obj).attr('toggle-data');
            	if(hiderel=='')	return false;
            	
            	var arr = new Array();
                arr = hiderel.split(",");
                $.each(arr, function (index, tx) {	
            		var arr2 = new Array();
            		arr2 = tx.split("@");
            		if(arr2[1]=='hide'){
            		    $('.toggle-'+arr2[0]).hide();
            		}else{
            			$('.toggle-'+arr2[0]).show();
            		}
            	});
            	
            },
            /* 选择图文素材 */
        	openSelectAppMsg:function(pop,dataUrl,callback){
        		var $contentHtml = $('<div class="appmsg_dialog" style="padding:10px; max-height:560px;overflow-y:hidden;overflow-x:hidden;"><ul><center><br/><br/><br/><img src="'+IMG_PATH+'/loading.gif"/></center></ul></div>');

        		// 设置弹窗参数
				var mypop = pop($contentHtml, {
					title : "选择图文素材",
					width : 700,
					height : 500,
					onshow : function(box) {
						//提交数据
						core.generalFunction.ajaxSubmit({
						    url: dataUrl,
							data: {'type':'ajax'},
							dataType:'html',
							automatic: false,
							success:function(data){
								$data = $(data);
		        				$('ul',$contentHtml).html($data);
		        				$data.find('.material_list').masonry({
		        					// options
		        					itemSelector : '.appmsg_li'
		        					//columnWidth : 308
		        				  });
		        				$('li',$contentHtml).on('click',function(){
		        					if(typeof callback=="function"){
		        						callback(this);
		        						mypop.close();
                                    }
		        				});
						    }
						});
					}
				});
				mypop.open();        		
        	},
            //上传附件组件
            initUploadFile:function(){
            	$(".upload_file").each(function(index, obj) {
            		setTimeout(function(){
            			var name = $(obj).find('input[type="hidden"]').attr('name');
            			$("#upload_file_"+name).uploadify({
            				"height"          : 30,
            				"swf"             : STATIC+"/uploadify/uploadify.swf",
            				"fileObjName"     : "download",
            				"buttonText"      : "上传附件",
            				"uploader"        : UPLOAD_FILE,
            				"width"           : 120,
            				'removeTimeout'	  : 1,
            				"onUploadSuccess" : function(file, data, response) {
            					core.generalEvent.onUploadFileSuccess(file, data, name);
            				}
            			});
            		},300);
            	});
            },
            onUploadFileSuccess:function(file, data, name){
            	var data = $.parseJSON(data);
            	if(data.status){
            		$("input[name="+name+"]").val(data.id);
            		$("input[name="+name+"]").parent().find('.upload-img-box').html(
            			"<div class=\"upload-pre-file\"><span class=\"upload_icon_all\"></span>" + data.name + "</div>"
            		);
            	} else {
            		core.generalTip.ontextTip(data.info,"error");
            		setTimeout(function(){
            			$(that).removeClass('disabled').prop('disabled',false);
            		},1500);
            	}
            },
            /**
             * 上传图片
             */
            initUploadImg:function()
            {
//            	$.getScript("http://api.map.baidu.com/getscript?v=1.4&ak=&services=&t=",function(){
//            		
//            	});
            	
            }

            /**
             * 预览图片
             */
            ,initViewImg:function()
            {
            	
            },
            /**
             * 选择文件
             */
            chooseFile:function()
            {
            	$(".choosefile").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var param = $(this).attr("chooseparam");
	            		if(!param) return false;
	        			var param = param.split(",");
	        			var title = "选择文件";
	        			//支持框架(ifram)
	        			dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
	        			dialog({
	            			title: title,
	            			//url: BASE_URL+"/"+_bundlepath_+"/attachment/index?type="+param[1],
	            			url: url,
	            			width:600,
	            			height:400,
	            			scrolling: 'auto',
	            			onclose: function () {
	            				if (this.returnValue) {
	            					$("#"+param[0]).val(this.returnValue);
	            					$("#"+param[0]+"_src").attr("href",this.returnValue);
	            				}
	            				//console.log('onclose');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});
            },
            /**
             * 选择模版
             */
            chooseTpl:function()
            {
            	$(".choosetpl").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var param = $(this).attr("chooseparam");
	            		if(!param) return false;
	        			var param = param.split(",");
	        			var type = Number(param[2]);
	        			var tpid = Number($("#"+param[0]).val());
	        			var title = "预览选中模板";
	        			//var url = BASE_URL+"/"+_bundlepath_+"/classify/choosetpl?tpid="+tpid+"&type="+type;
	        			var width = 500;
	        			var height = 390;
	        			if(type == 1 || type == 3 || type == 5 || type == 6)
	        			{
	        				var title = "选择模板";
	            			var url = BASE_URL+"/"+_bundlepath_+"/classify/choosetpl?type="+type;
	            			var width = 1093;
	            			var height = 550;
	        			}
	        			dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
	        			dialog({
	            			title: title,
	            			url: url,
	            			width:width,
	            			height:height,
	            			scrolling: 'auto',
	            			onclose: function () {
	            				if (this.returnValue) {
	            					$("#"+param[1]).val("已选择模版 "+this.returnValue);
	            					$("#"+param[0]).val(this.returnValue);
	            				}
	            				//console.log('onclose');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});	
            },
            /**
             * 添加图文件消息
             */
            addImgMessage:function()
            {
            	dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
            	$(".addImgMessage").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var param = $(this).attr("msgparam");
	            		if(!param) return false;
	            		
	            		var param = param.split(",");
	            		
	            		dialog({
	            			title: '选择图文消息',
	            			url: url,
	            			width:600,
	            			height:400,
	            			scrolling: 'auto',
	            			data: param[0],
	            			onclose: function () {
	            				if (this.returnValue) {
	            					var tmpId = this.returnValue.split(",");
	            					var viewtime = $("#viewtime").length>0?$("#viewtime").val():'';
	            					var id = tmpId[0];
	            					var title = tmpId[1];
	            					var pic = tmpId[2];
	            					var info = tmpId[3];
	            					var thisHTML = "<div class='mediaPanel'>" +
	            							"<div class='mediaHead'>" +
	            								"<span class='title' id='zbt'>"+title+"</span><span class='time'>"+viewtime+"</span>" +
	            								"<div class='clr'></div>" +
	            							"</div>" +
	            							"<div class='mediaImg'>" +
	            								"<img id='suicaipic1' src='"+pic+"' />" +
	            							"</div>" +
	            							"<div class='mediaContent mediaContentP'>" +
	            								"<p id='zinfo'>"+info+"</p>" +
	            							"</div>" +
	            							"<div class='mediaFooter'>" +
	            								"<span class='mesgIcon right'></span>" +
	            								"<span style='line-height: 50px;' class='left'>查看全文</span>" +
	            								"<div class='clr'></div>" +
	            							"</div>" +
	            						"</div>";
	            					$("#"+param[3]).fadeOut();
	            					$("#"+param[0]).val(id);
	            					$("#"+param[1]).html(thisHTML);
	            					$("#"+param[2]).fadeIn();
	            					$("#"+param[4]).append('<div id="appmsgItem4" data-fileid="" data-id="4" class="appmsg_item js_appmsg_item "><img class="js_appmsg_thumb appmsg_thumb" src="'+pic+'"><i class="appmsg_thumb default" style="background:url('+pic+');background-size:100% 100%">&nbsp;</i><h4 class="appmsg_title"><a onClick="return false;" href="javascript:void(0);" target="_blank">'+title+'</a></h4></div>');
	            				}
	            			},
	            			onremove: function () {
	            				//console.log('onremove');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});
            },
            clearMessage:function()
            {
            	$(".clearMessage").off().on("click",function(){
            		var param = $(this).attr("msgparam");
            		if(!param) return false;
            		
            		var param = param.split(",");
            		
            		$("#"+param[2]).fadeOut();
            		$("#"+param[0]).val("");
					$("#"+param[1]).empty();		
					$("#"+param[4]).html('<div class="appmsg_info"><em class="appmsg_date"></em></div><div class="cover_appmsg_item" id="multione"></div>');
					$("#"+param[3]).fadeIn();
            	});
            },
            /**
             * 选择粉丝
             */
            addFans:function()
            {
            	dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
            	$(".addfans").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var param = $(this).attr("fansparam");
	            		if(!param) return false;
	            		
	            		var param = param.split(",");
	            		var token = $("#token").length>0?$("#token").val():"";
	            		var group = $("#group").length>0?Number($("#group").val()):0;
	            		
	            		if(group<=0){
	            			core.generalTip.ontextTip("请选择分组", "error");
	            			return false;
	            		}
	            		
	            		dialog({
	            			title: '插入链接或关键字',
	            			url: url,
	            			width:600,
	            			height:400,
	            			scrolling: 'auto',
	            			data: param[0],
	            			onshow: function () {
	            				//console.log('onshow');
	            			},
	            			oniframeload: function () {
	            				
	            			},
	            			onclose: function () {
	            				if (this.returnValue) {
	            					var thisVal = $("#"+param[0]).length>0?$("#"+param[0]).val():'';
	            					$("#"+param[0]).val(thisVal?(thisVal +","+ this.returnValue):this.returnValue);
	            				}
	            				//console.log('onclose');
	            			},
	            			onremove: function () {
	            				//console.log('onremove');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});
            },
            /**
             * 设置经纬度
             */
            setLatlng:function()
            {
            	dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
            	$(".setLatlng").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var token = $("#token").length>0?$("#token").val():"";
	            		var param = $(this).attr("latlngparam");
	            		if(!param) return false;
	            		
	            		var param = param.split(",");
	            		
	            		//经度
	            		var longitude = $("#"+param[0]).val();
	            		
	            		//纬度
	            		var latitude = $("#"+param[1]).val();
	            		
	            		dialog({
	            			title: '设置经纬度',
	            			url: url,
	            			width:600,
	            			height:400,
	            			scrolling: 'auto',
	            			onshow: function () {
	            				//console.log('onshow');
	            			},
	            			oniframeload: function () {
	            				
	            			},
	            			onclose: function () {
	            				if (this.returnValue) {
	            					var thisValue = this.returnValue;
	            					thisValue = thisValue.split("|");
	            					$("#"+param[0]).val(thisValue[0]);
	            					$("#"+param[1]).val(thisValue[1]);
	            				}
	            				//console.log('onclose');
	            			},
	            			onremove: function () {
	            				//console.log('onremove');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});
            },
            /**
             * 插入链接
             */
            addLink:function()
            {
            	dialog = typeof parent.document == "undefined" ? dialog : top.dialog;
            	$(".addlink").off().on("click",function(){
            		if ((url = $(this).attr('data'))|| (url = $(this).attr('data-url')))
            		{
	            		var param = $(this).attr("linkparam");
	            		if(!param) return false;
	            		
	            		var param = param.split(",");
	            		
	            		dialog({
	            			title: '插入链接或关键字',
	            			url: url,
	            			width:600,
	            			height:400,
	            			scrolling: 'auto',
	            			data: param[0],
	            			onshow: function () {
	            				//console.log('onshow');
	            			},
	            			oniframeload: function () {
	            				
	            			},
	            			onclose: function () {
	            				if (this.returnValue) {
	            					$("#"+param[0]).val(this.returnValue);
	            				}
	            				//console.log('onclose');
	            			},
	            			onremove: function () {
	            				//console.log('onremove');
	            			}
	            		}).showModal();
            		}
            		return false;
            	});
//            	art.dialog.data('domid', domid);
//            	art.dialog.open('?g=User&m=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'鎻掑叆閾炬帴鎴栧叧閿瘝',width:600,height:400,yesText:'鍏抽棴',background: '#000',opacity: 0.45});
            },
            /************************自动保存事件***************************/
            autoSave: function(ojbName1, ojbName2){            	
            	//双击进入编辑状态事件
            	$("[class='"+ojbName1+"']").off().on("dblclick",function(){
            		if ((url = $(this).parent().attr('url'))
    						|| (url = $(this).parent().attr('data-url'))
    						|| (url = $(this).attr('url'))
    						|| (url = $(this).attr('data-url')))
    			    {
	            		//替换当前元素(span转换成input)
	        			$(this).replaceWith("<input type='text' class='"+ojbName2+"' style='width:80%;' id='"+$(this).attr("id")+"' value='"+$(this).text()+"' sourcedata='"+$(this).text()+"'  />");
	
	        			//光标定位
	        			$("[class='"+ojbName2+"']").focus();
	
	        			//光标离开文本框自动保存
	        			$("[class='"+ojbName2+"']").off().on("blur",function(){
	            			//鼠标双击显示输入框自己动保存事件
	        				var obj	   = $(this);
	        				var _tmpId = obj.attr("id");
	         	    		var tmpId  = _tmpId.split("_");
	        	    		var tmpVal = obj.val();
	        				var _data  = { "csrf_token":csrf_token };
	        					_data[tmpId.pop()] = tmpVal;
	
        					if(tmpId.length>=3)
        					    _data['pid'] = tmpId.pop();
        					
	        				//如果没有变更则不执行AJAX提交动作
	        				if(obj.attr("sourcedata")==tmpVal)
	        				{
	        					//替换当前元素(input转换成span)
	        					obj.replaceWith("<span class='editspan' id='"+_tmpId+"' data-url='"+url+"'>"+tmpVal+"</span>");
	
	        	            	//重新加载事件
	        					core.generalEvent.autoSave(ojbName1, ojbName2);
	        	            	return ;
	        				}
	
	        				//提交数据
	        				core.generalFunction.ajaxSubmit({
	        	                url: url,
	        	                data: _data,
	        	                automatic: false,
	        	                beforeSend:function()
	        	                { 	                	
	        	                	//当前元素禁止编辑
	        	                	obj.prop("disabled",true);
	        	                },
	        	                complete:function()
	        	                {
	        	                	//替换当前元素(input转换成span)
	        	                	obj.replaceWith("<span class='editspan' id='"+_tmpId+"' data-url='"+url+"'>"+tmpVal+"</span>");
	
	        	                	//重新加载事件
	        	                	core.generalEvent.autoSave(ojbName1, ojbName2);
	        	                	
	        	                	//当前元素允许编辑
	        	                	obj.prop("disabled",false);
	        	                },
	        	                success:function(data){
	        	                	if(data.status){
	        	                		core.generalTip.ontextTip(data.info,"right");
	        	                	}else{
	        	                		//还原源数据
	        	                		tmpVal = obj.attr("sourcedata");
	        	                        core.generalTip.ontextTip(data.info,"error");
	        	                	}
	        	                }
	        				});
	        			});
    			    }
        		});
            }
        },
        /**********************通用函数**************************/
        generalFunction: {
            /**********************获取鼠标位置**************************/
            getMousePosition: function (e) {
                var posx = 0;
                var posy = 0;

                if (!e) var e = window.event;

                if (e.pageX || e.pageY) {
                    posx = e.pageX;
                    posy = e.pageY;
                } else if (e.clientX || e.clientY) {
                    posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                    posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
                }

                return { x: posx, y: posy };
            },
            /**********************获取当前浏览器的宽度和高度**************************/
            getBrowserSize: function (isAddScroll, isparent) {
                var brow = 0;
                var broh = 0;

                if (isAddScroll) {
                    brow = core._window.width() + core._document.body.scrollLeft + core._document.documentElement.scrollLeft;
                    broh = core._window.height() + core._document.body.scrollTop + core._document.documentElement.scrollTop;
                } else {
                    if (isparent) {
                        brow = core._window.width();
                        broh = core._window.height();
                    } else {
                        brow = $(window).width();
                        broh = $(window).height();
                    }
                }

                return { w: Math.ceil(brow), h: Math.ceil(broh) };
            }

            //Ajax提交
        	,ajaxButton:function()
        	{
        		self.ajaxPost();
        		self.ajaxGet();
        	}
            //ajax POST提交
            ,ajaxPost:function(callback)
        	{
            	var automatic = true;
        		if(typeof callback == "function") {        				
        			automatic = false;
				}
        		$(".ajax-post,[data-submit='post']").off().on("click",(function() {
    				var target, query, form;
    				var target_form = $(this).attr('target-form')||"form";

    				var that = $(this);
    				var nead_confirm = false;
    				if ((that.attr('type') == 'submit')||(that.is(':button'))|| (target = that.attr('href'))|| (target = that.attr('url'))|| (target = that.attr('data-url')))
    				{
    					form = $('.' + target_form);

    					// 无数据时也可以使用的功能
    					if (that.attr('hide-data') === 'true') {
    						form = $('.hide-data');
    						query = form.serialize();
    						if (that.hasClass('confirm')) {
    							if (!confirm('确认要执行该操作吗?')) {
    								return false;
    							}
    						}
    					} else if (form.get(0) == undefined) {
    						return false;
    					} else if (form.get(0).nodeName == 'FORM') {
    						if (that.hasClass('confirm')) {
    							if (!confirm('确认要执行该操作吗?')) {
    								return false;
    							}
    						}
    						if (!target)
    							target = form.attr('action');

    						query = form.serialize();
    					} else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA'){
    						form.each(function(k, v) {
    							if (v.type == 'checkbox'&& v.checked == true) {
    								nead_confirm = true;
    							}
    						})
    						if (nead_confirm&& $(this).hasClass('confirm')) {
    							if (!confirm('确认要执行该操作吗?')) {
    								return false;
    							}
    						}
    						query = form.serialize();
    					} else {
    						if ($(this).hasClass('confirm')) {
    							if (!confirm('确认要执行该操作吗?')) {
    								return false;
    							}
    						}
    						query = form.find('input,select,textarea').serialize();
    					}

    					//提交数据
    					core.generalFunction.ajaxSubmit({
    					    url: target,
    						data: query,
    						automatic: automatic,
    						beforeSend:function()
    	                    {
    	                  	  	//卸载事件,防止重复点击
    							that.prop('disabled', true);
    	                    },
    						complete:function()
    						{
    							that.prop('disabled', false);
    						},
    						success:function(data){
    							if(typeof callback == "function") {        				
    								callback(data, that);
    							}
    					    }
    					});
    				}
    				return false;
    			}));
        	}
            
            //ajax GET提交
        	,ajaxGet:function(callback)
        	{
        		var automatic = true;
        		if(typeof callback == "function") {        				
        			automatic = false;
				}
        		//ajax get请求
        		$(".ajax-get,[data-submit='get']").off().on("click",function() {
    				var target;
    				var that = $(this);
    				if ($(this).hasClass('confirm')) {
    					if (!confirm('确认要执行该操作吗?')) {
    						return false;
    					}
    				}
    				if ((that.attr('type') == 'submit')|| (target = that.attr('href'))|| (target = that.attr('url'))|| (target = that.attr('data-url')))
    				{
    					//提交数据
    					core.generalFunction.ajaxSubmit({
    					    url: target,
    						data: {},
    						type: "GET",
    						automatic: automatic,
    						beforeSend:function()
    	                    {
    	                  	  	//卸载事件,防止重复点击
    							that.prop('disabled',true);
    	                    },
    						complete:function()
    						{
    							that.prop('disabled', false);
    						},
    						success:function(data){
    							if(typeof callback == "function") {        				
    								callback(data, that);
    							}
    					    }
    					});
    				}
    				return false;
    			});
        	},
            /**************************获得区域表单数据****************************/
            //ojb	对象
            //_data	嵌入数组
            getAreaFormData:function(obj, _data, key, callback)
            {
            	var key = key?key:1;
            	var errorlist = {};
            	//遍历input输入框
            	obj.find("input").map(function(){
            		var must = Number($(this).attr("request"));
    				var rev = $(this).attr("rev");
    				switch($(this).prop("type"))
    				{
    					//单选框
    					case 'radio':
   							//取name名
							var _tmpId = $(this).prop("name");
							var tmpIds = _tmpId.split("_");
							
							//策略判断
    	    				if(must>0&&rev&&!$(this).is(":checked"))
    	            		{
    	    	    			if (typeof callback == "function") {        				
    	    	    				callback("必须选择"+rev);
    	    	    			}
    	            		}
							
    						//判断是否已选,选中则直接赋值
    						if($(this).is(":checked")) _data[tmpIds[key]] = $(this).val();

    						break;

     					//复选框
    					case 'checkbox':
    						//取name名
							var _tmpId = $(this).prop("name");
							var tmpIds = _tmpId.split("_");
							
							//策略判断
    	    				if(must>0&&rev&&!$(this).is(":checked"))
    	            		{
    	    	    			if (typeof callback == "function") {        				
    	    	    				callback("必须选择"+rev);
    	    	    			}
    	            		}
							
    						//判断是否已选
    						if($(this).is(":checked")){    							
    							//直接赋值
    							_data[tmpIds[key]] = _data[tmpIds[key]]?_data[tmpIds[key]]+","+$(this).val():$(this).val();
    						}else{
    							if($("[name='"+_tmpId+"']").length>=1)
    								//直接赋值
    								_data[tmpIds[key]] = _data[tmpIds[key]]?_data[tmpIds[key]]:'';
    							else
    								_data[tmpIds[key]] = 0;
    						}
							
    						break;
    					//隐藏的文本框
    					case 'hidden':
    						//取id名
    						var _tmpId = $(this).prop("id");
    						var tmpIds = _tmpId.split("_");
    						var minstr = Number($(this).attr("min"));
    						var maxstr = Number($(this).attr("max"));
    						if($(this).attr("name")=="editorsinput")
    						{
    							//赋值
    							if($("#editors").length>0) _data[tmpIds[key]] = UE.getEditor('editors').getContent();
    						}else{
    							
    							//策略判断
        	    				if(must>0&&rev&&!$(this).val())
        	            		{
        	    	    			if (typeof callback == "function") {        				
        	    	    				callback(rev+"不能为空");
        	    	    			}
        	            		}
        	    				
        	    				if(minstr>0&&$(this).val().length<minstr)
        	    				{
        	    					if (typeof callback == "function") {
        	    						if(maxstr>0)
        	    							callback("请输"+minstr+"到"+maxstr+"个字符的"+rev);
        	    						else
        	    							callback("请输"+minstr+"个字符以上的"+rev);
        	    	    			}
        	    				}
        	    				
        	    				if(maxstr>0&&$(this).val().length>maxstr)
        	    				{
        	    					if (typeof callback == "function") {
        	    						if(minstr>0)
        	    							callback("请输"+minstr+"到"+maxstr+"个字符的"+rev);
        	    						else
        	    							callback("请输"+maxstr+"个字符以内的"+rev);
        	    	    			}
        	    				}
        						
        						//直接赋值
        						_data[tmpIds[key]] = $(this).val();
    						}
    						
    						break;
    					//文本框
    					case 'text':
    					//密码框
    					case 'password':
    						//取id名
    						var _tmpId = $(this).prop("id");
    						var tmpIds = _tmpId.split("_");
    						var minstr = Number($(this).attr("min"));
    						var maxstr = Number($(this).attr("max"));
    						
    						//策略判断
    	    				if(must>0&&rev&&!$(this).val())
    	            		{
    	    	    			if (typeof callback == "function") {        				
    	    	    				callback(rev+"不能为空");
    	    	    			}
    	            		}
    	    				
    	    				if(minstr>0&&$(this).val().length<minstr)
    	    				{
    	    					if (typeof callback == "function") {
    	    						if(maxstr>0)
    	    							callback("请输"+minstr+"到"+maxstr+"个字符的"+rev);
    	    						else
    	    							callback("请输"+minstr+"个字符以上的"+rev);
    	    	    			}
    	    				}
    	    				
    	    				if(maxstr>0&&$(this).val().length>maxstr)
    	    				{
    	    					if (typeof callback == "function") {
    	    						if(minstr>0)
    	    							callback("请输"+minstr+"到"+maxstr+"个字符的"+rev);
    	    						else
    	    							callback("请输"+maxstr+"个字符以内的"+rev);
    	    	    			}
    	    				}
    						
    						//直接赋值
    						_data[tmpIds[key]] = $(this).val();
    						break;
    				}
    			});
    			
    			//遍历select选择框,textarea输入框
            	obj.find("select,textarea").map(function(){
            		var _tmpId = $(this).prop("id");            		
            		if(_tmpId){
						var tmpIds = _tmpId.split("_");
						//直接赋值
						_data[tmpIds[key]] = $(this).val();
						
	    				var must = Number($(this).attr("request"));
	    				var rev = $(this).attr("rev");
	
	    				if(must>0&&!$(this).val()&&rev)
	            		{
	    	    			if (typeof callback == "function") {        				
	    	    				callback(rev+"没有选择");
	    	    			}
	            		}
            		}
    			});

            	
            	return _data;
            },
            /************************下拉框ajax联动事件****************************/
            cotypeMselectEvent:function(obj, objname,url){
            	obj.off().on('change',function(e){
            		e.preventDefault();
            		var _tmpId	= $(this).attr("name");
        			var tmpId	= _tmpId.split("_");
        			var coid	= Number(tmpId[1]);
        			var ccid	= Number($(this).val());
        			var _self	= $(this);
        			var _url	= url?url:"entry?entry="+tmpId[2]+"&action=easy";
        			var _data	= { "csrf_token":csrf_token, "coid":coid, "ccid":ccid};
        			
        			//给隐藏的INPUT赋值
        			$(this).prevAll('input:first').val($(this).val());
        			
        			if(coid<=0||ccid<=0) return false;
        			//提交数据
        			core.generalFunction.ajaxSubmit({
                        url: _url,
                        data: _data,
                        automatic: false,
                        beforeSend:function()
                        {
                        	//当前元素禁止编辑,防止重复点击
                        	obj.prop("disabled",true);
                        	_self.nextAll('select').remove();
                        	//卸载事件,防止重复点击
                        	obj.off();
                        },
                        complete:function()
                        {
                        	//当前元素禁止编辑,防止重复点击
                        	obj.prop("disabled",false);

                        	//重新加载事件
                        	core.generalFunction.cotypeMselectEvent($("[name^='"+objname+"']"), objname, url);
                        },
                        success:function(result){
                        	if(result.status){
                        		var option = "";
                        		$.each(result.data, function(key,item){
                        			option += "<option value='"+item.ccid+"'>"+item.title+"</option>";
                        		});
                        		_self.after("<select style='margin-left:2px;' " +
            						"name='"+objname+"_"+coid+"_"+tmpId[2]+"' " +
            						"class='"+_self.attr('class')+"'>" +
            						"<option>请选择</option>"
            						+ option +
            					"</select>");
                        	}
                        }
                    });
            	});
            },
            /***************************上传图片********************************/
            imgUpload:function(obj,url,iconInput,rmInput){
            	$("[name='+obj+']").fileupload({
        	        url: url,
        	        dataType: 'json',
        	        add: function(e, data){
        	        	var acceptFileTypes = /(\.|\/)(gif|jpe?g|png)$/i;
        	        	if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
          	        		core.generalTip.ontextTip('该文件格式不允许上传',"error");
          	        		return false;
        	        	}
        	        	if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 1000000) {
        	        		core.generalTip.ontextTip('上传文件太大',"error");
        	        		return false;
        	        	}
        	        	
        	        	//开始提交
        	        	data.submit();
        	        },
        	        send: function(e, data){
        	        	//显示提示框
        	        	core.generalTip.onloadingTip('');
        	        },
        	        done: function (e, data) {
        	        	//隐藏提示框
        	        	core.generalFunction.onTipHide(false);
        	        	if(data.result.status){
        	        		$("#"+iconInput).val(data.result.filename);
        	        		$("#"+iconInput).attr('data',data.result.name);
        	        		
        	        		//删除图标按钮显示
        	        		$("#"+rmInput).show();
        	        	}else
        	        		core.generalTip.ontextTip(data.result.info,"error");
        	        },
        	        fail:function(e, data){
        	        	//隐藏提示框
        	        	core.generalFunction.onTipHide(false);
        	        	core.generalTip.ontextTip("上传失败","error");
        	        }
        	    });
            },
            /**********************写入当前请求地区cookie**************************/
            getAccessAreaAddCookie: function (ip, areaid, areaname) {
                var accessAreaStr = { ip: ip, areaid: areaid, areaname: areaname };
                document.cookie = "accessAreaStr=" + JSON.stringify(accessAreaStr);
            },
            /*********************表单验证提交**************************/
            formValidatorSubmit: function (d) {
                var v = {
                	formClass: "form",        //表单Class
                    ajaxForm: true,           //是不是ajax提交
                    validatorGroup:"1",       //多表表单验证
                    submitVerification: null, //提交前自定义验证
                    success: null,            //提交成功后回调
                    automatic: true,          //是否自动执行
                    dataType: "json",         //返回类型号
                    type: "post"              //提交值
                }
                
                //获取setting对象传过来的值合并dsfSettings对象的值
                var formParame = $.extend(v, d);

                $.formValidator.initConfig({
                    formID: formParame.formId,
                    validatorGroup: formParame.validatorGroup,
                    onSuccess: function () {
                        return core.generalFunction.ajaxSubmit(formParame);
                    },
                    onError: function (msg, obj, errorlist) {
                        $.map(errorlist, function (msg) {
                        });
                    }
                });
                
                //自动装载验证信息
                $("#"+formParame.formId).find("input[data-validator=validatorInput]").not("[readonly]").not("[disabled]").each(function () {

                    var validatorOption = $.parseJSON($(this).attr("data-validator-option"));
                    $(this).formValidator({ validatorGroup: validatorOption.validatorGroup, onShow: validatorOption.onShow, onFocus: validatorOption.onFocus, onCorrect: validatorOption.onCorrectClass, empty: validatorOption.empty });
                    if (typeof validatorOption.max != "undefined") {
                        $(this).inputValidator({ min: validatorOption.min, max: validatorOption.max, onError: validatorOption.onError });
                    }
                    if (typeof validatorOption.fun != "undefined") {
                        $(this).functionValidator({ fun: eval(validatorOption.fun) });
                    }
                    if (typeof validatorOption.regExp != "undefined") {
                        $(this).regexValidator({ regExp: validatorOption.regExp, dataType: validatorOption.dataType, onError: validatorOption.onError });
                    }
                });
            },
            /*********************表单直接提交**************************/
            formSubmit: function (d) {
                var v = {
                    formId: "form1",        //表单ID
                    ajaxForm: true,         //是不是ajax提交
                    submitVerification: null,   //提交前自定义验证
                    success: null,              //提交成功后回调
                    automatic: true,            //是否自动执行
                    dataType: "json",           //返回类型号
                    type: "post"                //提交值
                }
                var formParame = $.extend(v, d);


                var isSumbit = true;
                var _from=$("#" + formParame.formId);
                if (typeof formParame.submitVerification == "function") {
                    isSumbit = formParame.submitVerification();
                }

                if (isSumbit) {
                    core.generalTip.onloadingTip("");
                    if (formParame.ajaxForm) {
                        if (core.pageVariable.formSubmitLock) {
                            return false;
                        }
                        core.pageVariable.formSubmitLock = true;
                        _from.find("textarea[placeholder],input[placeholder]").each(function () {
                            if ($(this).val() == $(this).attr('placeholder')) {
                                $(this).val('');
                            }
                        });
                        var optionParame =_from.serialize();
                        $.ajax({
                            async: false,
                            type: formParame.type,
                            url: _from.attr("action"),
                            dataType: formParame.dataType,
                            data:optionParame,
                            success: function (data) {
                                setTimeout(function () { core.pageVariable.formSubmitLock = false; }, 2000);
                                core.generalFunction.onTipHide(false);

                                switch (formParame.dataType) {
                                    case "json":
                                        var tipType = "right";
                                        if (!data.status) {
                                            tipType = "error";
                                        }

                                        if(formParame.automatic){
                                            core.generalTip.ontextTip(data.info ? data.info : "保存失败！", tipType, function () {
                                                if (typeof formParame.success == "function") {
                                                    formParame.success(data);
                                                }
                                            });
                                        }else{
                                            if (typeof formParame.success == "function") {
                                                formParame.success(data);
                                            }
                                        }
                                        break;
                                    default:
                                        if (typeof formParame.success == "function") {
                                            formParame.success(data);
                                        }
                                        break;
                                }
                            }
                        });
                    } else { return true; }
                }
                return false;
            },
            /*********************控制ajax请求**************************/
            ajaxSubmit:function(d){
                var v={
                    async:true,        //是否为异步请求
                    dataType:"json",    //接收格式
                    data:{},            //参数
                    url:"",             //url
                    type:"POST",        //请求类型
                    cache:true,         //是否清除反缓
                    headers:null,
                    beforeSend:null,
                    complete:null,
                    istiphide:false,      //是否隐藏提示框
                    automatic:true,      //是否自动执行
                    success:null,        //返回结果函数
                    error:null,          //错误之后回调
                    ajaxSubmitLockTime:10  //锁定时间
                };

                var ajaxParame= $.extend(v,d);
                if(!ajaxParame.istiphide){
                    core.generalTip.onloadingTip('');
                }
                if(ajaxSubmitLock){
                    ajaxSubmitLock=false;
                    return $.ajax({
                        async:ajaxParame.async,
                        dataType:ajaxParame.dataType,
                        data:ajaxParame.data,
                        url:ajaxParame.url,
                        type:ajaxParame.type,
                        cache:ajaxParame.cache,
                        headers:ajaxParame.headers,
                        beforeSend:function(){
                        	if(typeof ajaxParame.beforeSend=="function"){
                                ajaxParame.beforeSend();
                            }
                        },
                        complete:function(){
                        	if(typeof ajaxParame.complete=="function"){
                                ajaxParame.complete();
                            }
                        },
                        success:function(data){
                        	ajaxSubmitLock=true;
                            // setTimeout(function(){ajaxSubmitLock=true;},ajaxParame.ajaxSubmitLockTime);
                            if(!ajaxParame.istiphide)
                                core.generalFunction.onTipHide(false);
                            try{
                            	switch (ajaxParame.dataType){
	                            	case "json":
	                            		if(ajaxParame.automatic){
	                                        var tipType = "right";
	                                        if (!data.status) {
	                                            tipType = "error";
	                                        }
	                                        core.generalTip.ontextTip(data.info ? data.info : "操作失败！", tipType, function () {
	                                            if (typeof ajaxParame.success== "function") {
	                                                ajaxParame.success(data);
	                                            }
	                                        });
	                                        core.relogin(data);
	                                    }else{
	                                        if(typeof ajaxParame.success=="function"){
	                                            ajaxParame.success(data);
	                                        }
	                                    }
	                                    break;
	                            	case "html":
	                            		//判断返回值不是 json 格式
	                            		if (data.match("^\{(.+:.+,*){1,}\}$"))
	                            		{
	                            			var data = jQuery.parseJSON(data);
		                            		if(!data.status){
		                                        core.generalTip.ontextTip(data.info ? data.info : "操作失败！", "error");
		                                         core.relogin(data);
		                                        return true;
		                                    }
	                                        if(typeof ajaxParame.success=="function"){
	                                            ajaxParame.success(data.info);
	                                        }
		                            		return true;
	                            		}
	                            		if(typeof ajaxParame.success=="function"){
	                                        ajaxParame.success(data);
	                                    }
	                            		break;
	                            	default :
	                                    if(typeof ajaxParame.success=="function"){
	                                        ajaxParame.success(data);
	                                    }
	                                	break;
	                            }
                    		}catch(e) {
                    			core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
                    		}
                        },
                        error:function(e){
                        	try{
								ajaxSubmitLock=true;
								core.generalFunction.onTipHide(false);
								if(typeof ajaxParame.error=="function"){
								    ajaxParame.error(e);
								}
                        	}catch(e) {
                    			core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
                    		}
                        }
                    });
                }
            },
            /**********************读取当前请求地区cookie**************************/
            getCookie: function (name) {
                var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
                if (arr != null) return unescape(arr[2]); return null;
            },
            /**********************显示提示信息**************************/
            onTipShow: function (_this) {
                var browserSize =core.generalFunction.getBrowserSize(false, true);
                var left = Math.ceil((browserSize.w - $(_this).width()) / 2);
                var top = Math.ceil(browserSize.h / 2) + 30;
                core._body.find(_this).css({ left: left + "px", top: top + "px" }).fadeIn(200);
            },
            /**********************隐藏当前提不信息**************************/
            onTipHide: function (isAlert, callback) {
                if (isAlert) {
                    if (typeof callback == "function") {
                        setTimeout(function () {
                            callback();
                        }, 2100);
                    }
                    setTimeout(function () {
                        core._body.find("#alertX").fadeOut(500);
                    }, 1500);
                } else {
                    if(typeof core._body!="undefined"){
                        core._body.find("#loadingX").hide();
                    }
                }
            },
            /**********************上传文件获取cookie*************************************/
            uploadCookies:function(){
                var cookieArr = document.cookie.split(';');
                var obj = {};
                for(var i=0;i <cookieArr.length;i++ ){
                    var arr=cookieArr[i].split("=");
                    obj[arr[0]] = arr[1];
                }
                return obj;
            },
            /**wind同步请求**/
            synLogin:function(_syndata,callback){
            	$.each(_syndata,function(i,item){
            		$.ajax({
            			url:decodeURIComponent(item),
                        //第三方站点使用JSONP
            			dataType:"JSONP",
            			type: "GET",
            			async:false
            		});
            	});
            	if (typeof callback == "function") {
            		setTimeout(function () {
                        callback();
                    }, 1000);
                }
            },
            
            /**定时器**/
            timerControl:function(callback){           	
            	if (typeof callback == "function") {
               		setTimeout(function () {
                        callback();
                    }, 1000);
                }
            }
        },
        generalTip: {
            /**********************提示消息框**************************/
            ontextTip: function (text, type, callback) {
            	type = type?type:'error';
                var _alertX = core._body.find("#alertX");
                if (typeof _alertX[0] == "undefined") {
                    var html = '<div class="x_msgbox_layer_wrap" id="alertX" style="padding:10px 50px;"><span id="mode_tips_v2" style="z-index:10000;" class="x_msgbox_layer"><span class="gtl_ico_hits ' + type + '"></span><b id="content1">' + text + '</b><span class="gtl_end"></span></span></div>';
                    core._body.append(html);
                } else {
                    _alertX.find(".gtl_ico_hits").attr("class", "gtl_ico_hits " + type);
                    _alertX.find("#content").text(text);
                }
                core.generalFunction.onTipShow("#alertX");
                if(type=="right"){
                    core.generalFunction.onTipHide(true, callback);
                }else{
                    core.generalFunction.onTipHide(true);
                }
            },  
            /**********************提示加载框**************************/
            onloadingTip: function (text) {
                var _loadingX = core._body.find("#loadingX");
                if (typeof _loadingX[0] == "undefined") {
                    var html = '<div id="loadingX" class="x_msgbox_load"><img src="'+jsbase+'/core/images/loader_ye.gif"><br/><span id="content1">' + text + '</span></div>';
                    core._body.append(html);
                    core.generalFunction.onTipShow("#loadingX");
                } else {
                    _loadingX.find("#content").html(text);
                    core.generalFunction.onTipShow("#loadingX");
                }
            }
        }
    };
    /*以上是旧版本API，请勿再扩展
    * 如需扩展请在下面结构中另行定义
    */
    var core =_system;
    /*内部缓存*/
    var _cache = {};
    /*还未修改的旧有API*/
    if(typeof parent.document == "undefined"){
        core._body = $("body");
        core._win = $(window);
        core._doc = $(document);
    }else{
        core._body = $(parent.document.body);
        core._win = $(parent.window);
        core._doc = $(parent.document);
    }
    core.Alert = _system.generalTip.ontextTip;
    core.Loading = _system.generalTip.onloadingTip;
    core.Hide = function(type){
        switch(type.toLowerCase()){
            case "alert":
                core._body.find("#alertX").fadeOut(500);
                break;
            case "loading":
                core._body.find("#loadingX").hide();
            break;
        }
    };
    /*旧API转换结束*/

    /*内部函数end*/
    core.Cookie = core.cookie = {};
    //设置cookie
    /*
     * @param {String} key cookie名称
     * @param {String} value cookie值
     * @param {Object} options  {String} domain 所在域名 ; {String} path 所在路径 ; {Number} hour 存活时间，单位：小时
    */
    core.Cookie.set = function(key,value,options){
        var options = options||{};
        if(options.hour){
            var today = new Date();
            var expire = new Date();
            expire.setTime(today.getTime() + options.hour * 3600000);
        }
        window.document.cookie =
            key + "=" + value
            + (options.path ? "; path=" + options.path : "")
            + (options.hour ? "; expires=" + expire.toGMTString() : "")
            + (options.domain ? "; domain=" + options.domain : "");
        return this;
    }
    //删除指定cookie
    core.Cookie.remove = function(key,options){
        if(!key)return false;
        options = options||{};
        options.expires = new Date(0);
        options.path = '/';
        core.Cookie.set(key,'',options);
        return this;
    }
    //获取指定cookie
    core.Cookie.get = function(key){
         var reg = new RegExp("(^| )" + key + "=([^;]*)(;|\x24)"),
             result = reg.exec(document.cookie);
         if(result){
             return result[2]||null;
         }
    }
    //是否为空字符串 null
    core.isEmpty  = function(unknow){
        return unknow === "" || unknow === undefined || unknow === null
    }
    //是否对象
    core.isObject   = core.generalEvent.isType("Object");
    //是否字符串
    core.isString   = core.generalEvent.isType("String");
    //是否函数
    core.isFunction = core.generalEvent.isType("Function");
    //是否DOM元素
    core.isDOM      =
    core.isElement  = function(o){
        return (typeof HTMLElement === "object" ? o instanceof HTMLElement : //DOM2
            o && typeof o === "object" && o.nodeType === 1 && typeof o.nodeName === "string"
        );
    };
    /*正则工具*/
    core.Regex = {};
    /**
     * rgba
     * @example
     *  match rgba(255,255,255,.5) rgba(0,0,0,0) ...
     *  no match rgba(256,256,256,.5) ...
     */
    core.Regex.RGBA = /^\s*rgba\s*\(\s*(25[0-5]|2[0-4][0-9]|1\d{2}|[1-9]?\d)\s*,\s*(25[0-5]|2[0-4][0-9]|1\d{2}|[1-9]?\d)\s*,\s*(25[0-5]|2[0-4][0-9]|1\d{2}|[1-9]?\d)\s*,\s*(\d+\.?|\d*\.\d+)\s*\)\s*$/i;
	/*正则工具end*/
	/*对象工具*/
    core.Object = {};
    /**
     * 合并对象属性
     * @param {Object} obj 要合并到对象， 可以有多个
     * @return {Object}
     * @example
     *  core.extend({},obj1,obj2);//返回全新的合并后的obj
     *  core.extend(obj1,obj2);//返回合并后的obj1，obj2不会被改变
     *  core.extend(obj2,obj1);//返回合并后的obj2，obj1不会被改变
     */
    core.Object.extend = function () {
        var length = arguments.length;
        var obj = arguments[0];
        if(!obj || typeof obj === "number" || obj.constructor !== Object){
            obj = {};
        }
        var tmp;
        for (var i = 1; i < length; i++){
            tmp = arguments[i];
            if(tmp){
                for (var o in tmp){
                    obj[o] = tmp[o];
                }
            }
        }
        return obj;
    };
    /*对象工具 end*/
    /*插入css样式到页面*/
    core.style = core.css = function (css, styleId,parent){
        var util = core;
        var doc = parent ? parent.document : document;

        // parameter error
        if (!util.isString(css)) {
            return;
        }

        // one style need insert only once
        styleId = (styleId && util.isString(styleId) && styleId) || ('Jobcn-css-' + new Date().getTime());
 
        // insert to head as a style tag if need
        if (!doc.getElementById(styleId)) {
            var head = doc.getElementsByTagName('head')[0];
            var style = doc.createElement('style');
            style.type = 'text/css';
            style.id = styleId;
            if (style.styleSheet){
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(doc.createTextNode(css));
            }
            head.appendChild(style);
        }
    };
    /**
     * 遮罩
     * @param {Object} el 要显示的内容，可以是HTML Element、jQuery Object or HTML String，必填
     * @param {Object} opt 遮罩的控制参数，可省略
     * @param {Function} callback 回调函数，可省略
     * @return {Object} this 返回值说明:
     *      show: {Function} // show mask
     *      hide: {Function} // hide mask
     *      remove: {Function} // remove mask
     *      id: {String} // mask id
     *      content: {DOM Element} // mask content
     *      el: {DOM Element} mask element
     * @example
     *  opt参数说明：
     *      background: 背景颜色和透明度，请用rgba格式， 默认 rgba(0,0,0,0.5) 黑色半透明
     *      className: 背景层class名，默认'jobcn_mask'
     *      click: 背景层点击效果，默认为'hidden'隐藏，选择remove时将移除，其它任何值不响应
     *      id: 背景层id，默认为'jobcn_mask' + Date().getTime()
     *      isOpen: 是否直接显示，默认true，为false时调用show()显示
     *      zIndex: 背景层层高，默认10000
     */
    core.mask = function(el, opt, callback){
        var mask = {};
        var util = core;

        // parameter overloading
        if (util.isFunction(opt)) {
            callback = opt;
            opt = {};
        }
        // init
        // parent is hidden option
        var defaults = {
                id: 'zfw-mask' + new Date().getTime(),
                className: 'zfw-mask',
                isOpen: true,
                background: 'rgba(0,0,0,0.5)',
                zIndex: 10000,
                click: 'hidden',
                parent: 'body',
                //是否插入父页面
                dom : null
            };
        opt = util.Object.extend(defaults, opt);
        opt.id = opt.id.replace(/#/g, '');
        opt.parent = opt.parent.replace(/#/g, '');
        //兼容父页面操作
        var doc = opt.dom ? opt.dom.document : document;
        // is append to body
        var isBody = (opt.parent === 'body');

        // background
        // transform the rgba to AARRGGBB and use on IE678
        var matchColors = util.Regex.RGBA;
        var match = matchColors.exec(opt.background);
        if (match !== null) {
            var tempStr = '';
            // AARRGGBB
            var colorList = [match[4]*255%256>>0, match[1]%256>>0, match[2]%256>>0, match[3]%256>>0];
            for (var i = 0; i < 4; i++){
                if (colorList[i] < 16){
                    tempStr += '0';
                }
                tempStr += colorList[i].toString(16);
            }
            tempStr = tempStr.toUpperCase();
            opt.filter = 'Gradient(startColorStr=#'+tempStr+',endColorStr=#'+tempStr+');'
        } else {
            // default
            opt.background = 'rgba(0,0,0,0.5)';
            opt.filter = 'Gradient(startColorStr=#80000000,endColorStr=#80000000);';
        }

        // browser
        var isIE = navigator.userAgent.match(/MSIE (\d*)/i);
        isIE = isIE ? isIE[1] : false;
        var isIE6 = isIE && isIE < 7;

        var body, de;
        var table_style = 'display:none;';
        if (isBody){
            body = doc.getElementsByTagName('body')[0];
            de = doc.documentElement;
            // fix the IE6 unsupport the position of fixed
            if (isIE6) {
                var css = [];
                css.push("html{background-image:url(about:blank);background-attachment:fixed}");
                css.push("." + opt.className + "{position:absolute;top:expression(documentElement.scrollTop)}");
                util.css(css.join(''));
            } else {
                table_style += 'position:fixed;top:0;';
            }
        } else {
            // append to opt.parent
            body = doc.getElementById(opt.parent);
            // this line may make more question ???
            var _pos = body.style.position;
            if(_pos === '' || _pos === 'static'){
                body.style.position = 'relative';
            }
            de = body;
            table_style += 'position:absolute;top:0;';
        }

        table_style += 'left:0;bottom:0;right:0;background:' + opt.background + ';z-index:' + opt.zIndex + ';';

        // fix the IE 678 unsupport the rgba
        if (isIE  && isIE < 9) {
            table_style += 'filter:progid:DXImageTransform.Microsoft.' + opt.filter;
        }

        // create table and append the element
        var table = doc.createElement('table');
        var table_tr = table.insertRow(-1);
        var table_td = table_tr.insertCell(-1);
        var div = doc.createElement('div');
        table_td.appendChild(div);

        table.id = opt.id;
        table.className = opt.className;
        table.style.cssText = table_style;

        //table_td.id = opt.id + '_td';
        //table_td.style.textAlign = 'center';

        div.style.marginLeft = 'auto';
        div.style.marginRight = 'auto';
        div.style.position = 'relative';
        div.id = opt.id + '_content';

        // html element
        if (util.isElement(el)){
            div.appendChild(el);
        // jquery object
        } else if (util.isObject(el) && util.isFunction(el.css)){
            el.appendTo(div);
        // html string
        } else {
            div.innerHTML = el;
        }

        body.appendChild(table);

        /**
         * 遮罩显示
         */
        mask.show = function(){
            // if (isBody){
                // hidden the scroll bar
                // document[isIE < 9 ? "documentElement" : "body"].style.overflow = "hidden";
            // }
            // offsetHeight and offsetWidth is better than clientHeight and clientWidth, in sometimes it may return 0 in ie
            if (isBody) {
                table.style.width = de.clientWidth + 'px';
                table.style.height = de.clientHeight + 'px';
            } else {
                table.style.width = de.offsetWidth + 'px';
                table.style.height = de.offsetHeight + 'px';
            }
            table.style.display = (isIE == 6) ? "block" : "table";

            if (isIE6) {
                var selects = body.getElementsByTagName('select'); // hack of ie6
                for (var i = 0, l = selects.length; i < l; i++){
                    selects[i].style.visibility = 'hidden';
                }
            }
        }

        /**
         * 遮罩隐藏
         */
        mask.hide = function(){
            // if (isBody){
                // open the scroll bar
                // document[isIE < 9 ? "documentElement" : "body"].style.overflow="";
            // }

            table.style.display = "none";

            if (isIE6) {
                var selects = body.getElementsByTagName('select'); // hack of ie6
                for (var i = 0, l = selects.length; i < l; i++){
                    selects[i].style.visibility = 'visible';
                }
            }
        };

        /**
         * 遮罩移除
         */
        mask.close = mask.remove = function(){
        	if(table)
        		body.removeChild(table);
            //callback && util.isFunction(callback) && callback();
        };

        // click to hide or remove the mask
        if (opt.click === 'hidden' || opt.click === 'remove') {
            table.onclick = function(e){
                e = e || event;
                var element = e.target || e.srcElement;
                if (element === table_td || element === div) {
                    if (opt.click === 'remove') {
                        mask.remove();
                    } else {
                        mask.hide();
                    }
                }
            };
        }

        // fix the mask size
        var setSize = function(){
            if (isBody) {
                table.style.width = de.clientWidth + 'px';
                table.style.height = de.clientHeight + 'px';
            } else {
                table.style.width = de.offsetWidth + 'px';
                table.style.height = de.offsetHeight + 'px';
            }
        }

        var addEvent = function(elem, type, eventHandle) {
            if (elem == null || elem == undefined) return;
            if ( elem.addEventListener ) {
                elem.addEventListener( type, eventHandle, false );
            } else if ( elem.attachEvent ) {
                elem.attachEvent( "on" + type, eventHandle );
            } else {
                elem["on"+type]=eventHandle;
            }
        };
        // resize
        addEvent(window, 'resize', setSize);

        // show the mask layer
        !!opt.isOpen && mask.show();

        // callback
        callback && util.isFunction(callback) && callback();

        mask.id = opt.id;
        mask.content = div;
        mask.el = table;

        return mask;
    };
    /*mask end*/

    /*loading工具*/
    core.loading = function(isShow,parent){
        if(isShow===false){
            _cache.loading && _cache.loading.remove();
        }else{
            var rgba = "rgba(255,255,255,0.5)"
            if(typeof isShow === "string" && isShow == "black")rgba = "rgba(0,0,0,0.5)";
            _cache.loading = this.mask('<div align="center"><i></i></div>',{dom:parent,zIndex:10002,background:rgba,click:'',className:"loading-mask"});
        }
    };
    /*alert弹出层  依赖jQuery*/
    core.alert = function(msg,cfg){
        var callback,remove;
        var def = {type : "tip",
                remove : "remove",
                callback : null,
                title:"系统提示",
                ok:"确认",
                no:"取消",
                cancel:false,
                confirm:false
            };
        //def.type 的值有：tip warn err stop ok
        jQuery(["tip","warn","err","stop","success"]).each(function(i,k){
            def[k] = "w08cms-alert w08cms-alert-"+k;
        });
        jQuery.extend(def,cfg);

        var _type = def[def.type];
        //参数错误不执行
        if(!_type){alert('参数不正确');return;}
        var html = '<div class="'+_type+'">'
            html +='<div class="tt"><a href="javascript:;" title="关闭" class="close"></a><span class="tit">'+def.title+'</span></div>'
            html +='<div class="bd clearfix"><div class="msg"><span class="ico '+def.type+'"></span>'+msg+'</div></div>'
            if(def.ok||def.cancel){
                html +='<div class="ft">'
                html +=(def.confirm?'<a href="javascript:;" class="ok">'+def.ok+'</a>':'')
                html +=(def.cancel?'<a href="javascript:;" class="cancel">'+def.no+'</a>':'')
                html +='</div>'
                html +='</div>';
            }
        var htmldom = jQuery(html);
        var _alert = this.mask(htmldom
            ,{dom:parent,zIndex:10009,click:def.remove,className:"w08cms-alert-mask"});
        /*校正提示框的位置*/
        if(htmldom.find(".msg").height()>32){
            htmldom.find(".ico").css("top","auto");
        }
        /*控制器*/
        htmldom.find(".close,.cancel").click(function(){
            _alert.remove();
        });

        htmldom.find(".ok").click(function(){
        	if(core.isFunction(def.callback)){
        		def.callback();
        	}
           _alert.remove();
        });
    };
    
    //图片效果
    core.imgexchange = function(d,num){
    	var _tmpId = d.attr("id");
    	var tmpId  = _tmpId.split("_");
    	//num是页面只显示的个数
    	//获得总个数
    	var countNum    = d.parent().find(".navlist").length;
    	if(countNum<num) return false;
    	var _maxSize    = Number(countNum -num);
    	var _firstSize  = Number(d.parent().find(".navlist:visible:eq(0)").index());
    	var _firstId    = d.parent().find(".navlist:visible").attr("id");
    	switch(tmpId[2]){
        	case "left":
        		//图片左翻
        		if(_firstSize<=_maxSize){
        			$("#"+_firstId).hide(100);
        			//显示右翻按钮
	        		$("#"+tmpId[0]+"_"+tmpId[1]+"_right").fadeIn();
        		}
        		
        		//左翻按钮隐藏
        		if(_firstSize>=_maxSize) $("#"+tmpId[0]+"_"+tmpId[1]+"_left").fadeOut();	        		
        		
        		break;
        	case "right":
        		//图片右翻
        		if(_firstSize>1){
        			$("#"+_firstId).prev().show(100);
        			//显示左翻按钮
	        		$("#"+tmpId[0]+"_"+tmpId[1]+"_left").fadeIn();
        		}
        		
        		//右翻按钮隐藏
        		if(_firstSize<=2) $("#"+tmpId[0]+"_"+tmpId[1]+"_right").fadeOut();
        		
        		break;
    	}
    }
    
    //根据滚动位置出现浮窗
    core.scroll = function(obj,str)
    {
    	var trfirst		= obj.find("tr:first");
    	var trheight	= Number(trfirst.height());
    	var trstyle		= trfirst.attr('style');
    	var divhtml		= "<div class='scrollshow' style='width100%;height:"+Number(trfirst.height())+"px; "+trstyle+"'>";

    	trfirst.find(str).each(function(){
    		divhtml	+="<div style='width:"+$(this).innerWidth()+"px;' class='"+($(this).attr('class')?$(this).attr('class'):'')+"'>"+$(this).text()+"</div>";
    	});
    	divhtml	+="</div>";

    	//判断是否有scrollshow元素，没有则添加
    	if(obj.find(".scrollshow").length==0) trfirst.before(divhtml);
    	if(trheight<=0) return ;
    	obj.scroll(function(){
    		var scroll_top = obj.scrollTop();
    		if(scroll_top>=trheight-3){
    			obj.find(".scrollshow").slideDown();
    		}else{
    			obj.find(".scrollshow").slideUp();
    		}
    	})
    }

    // 提示没登录后跳转页面
    core.relogin = function(d){
    	if (typeof d.nologin !== 'undefined' && d.nologin == 0) {
            if (d.jumpUrl) {
                window.location.href = d.jumpUrl;
            }else{
                window.location.reload();
            } 
        }
    }
    m.exports = core;
});