/**!
* 表单验证
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年3月3日
* To change this template use File | Settings | File Templates.
*/

define(function(require,exports,module)
{
	//一个对象	
	var self;
	var settings;
	
	var callback;
	
	//核心JS
	var core = require('core');
	
	var key
	,exists = []
	,attributes = [
		'vid',		// 表单项关联ID
		'min',		// 最小数值或字符串长度
		'max',
		'rev',		// 表单项显示名称，给默认提示信息用的
		'required', // 必填/选的表单项
		'mode',		// 字符串格式用于 checklimit 函数检查，如 'int,number,email,...' mode => mode
		'regx',		// 正则表达式规则
		'exts',		// 扩展参数
		'ajax',		// Aajx提示字串
		'init',		// 初始化提示字串。对比规则中如果原表单项为空显示这个
		'comp',		// 对比规则专用。如果原表单项填写了，显示这个
		'wait',		// Ajax等待时提示字串
		'warn',		// 警告错误提示字串
		'pass'		// 验证正确提示字串
	]
	,rules = {
		// 只作提示用
		'void' : {},
		// 必选填并不能为空值
		'must'	:{
			cmd : 'checkempty',
			arg : 'vid,form,mode,regx'
		},
		// 整数检查
		'int'	: {
			cmd : 'checkint',
			arg : 'item,must,regx,min,max'
		},
		// 小数检查
		'float' : {
			cmd : 'checkfloat',
			arg : 'item,must,regx,min,max'
		},
		// 时间日期检查
		'date'	: {
			cmd : 'checkdate',
			arg : 'item,must,min,max'
		},
		// 下拉框/单/复选框 是否有选
		'check'	: {
			cmd : 'checkcheck',
			arg : 'vid,form'
		}
	}
	;
	
	(function(fn){
        module.exports=fn;
	})({
	
		init:function(page,settings){
    		//初始化加載的腳本
    		try{
    			alert(this.id);
    			self = this;
    			var item
    			, rule
    			, parent
    			, offset
    			, i = 0
    			, object = validator.config[this.id].object
    			, elem = object.form.elements
    			, options = object.options;
    			self[page](settings);
    		}catch(e) {
    			core.generalTip.ontextTip("formValidator JS脚本错误！["+e+"]","error");
    		}
    	}
		,validator:function(paramt, callback){    
			var defaultset = {
				formName: 'form'
			};
			if (typeof callback == "function") {
				self.callback = callback;
			}
			
			//获取setting对象传过来的值合并dsfSettings对象的值
			settings = $.extend(defaultset, paramt);
			// console.log(settings);
			self.loadinit();
		}
		,loadinit:function()
		{
			//文本框失去焦点后
			$("."+settings.formName).find("[required]").blur(function(){
				alert('aa');
			});
		}
		,loadinitaaa:function()
		{
			// console.log(settings);
			//如果是必填的，则加红星标识.
	        $("form :input.required").each(function(){
	            var $required = $("<strong class='high'> *</strong>"); //创建元素
	            $(this).parent().append($required); //然后将它追加到文档中
	        });
			//文本框失去焦点后
			$("."+settings.formName).find('input').blur(function(){
	        //$('form :input').blur(function(){
	             var $parent = $(this).parent();
	             $parent.find(".formtips").remove();
	             //验证用户名
	             if( $(this).is('#username') ){
	                    if( this.value=="" || this.value.length < 6 ){
	                        var errorMsg = '请输入至少6位的用户名.';
	                        $parent.append('<span class="formtips onError">'+errorMsg+'</span>');
	                    }else{
	                        var okMsg = '输入正确.';
	                        $parent.append('<span class="formtips onSuccess">'+okMsg+'</span>');
	                    }
	             }
	             //验证邮件
	             if( $(this).is('#email') ){
	                if( this.value=="" || ( this.value!="" && !/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value) ) ){
	                      var errorMsg = '请输入正确的E-Mail地址.';
	                      $parent.append('<span class="formtips onError">'+errorMsg+'</span>');
	                }else{
	                      var okMsg = '输入正确.';
	                      $parent.append('<span class="formtips onSuccess">'+okMsg+'</span>');
	                }
	             }
	        }).keyup(function(){
	           $(this).triggerHandler("blur");
	        }).focus(function(){
	             $(this).triggerHandler("blur");
	        });//end blur
			
			//提交，最终验证。
	         $('#send').click(function(){
	                $("form :input.required").trigger('blur');
	                var numError = $('form .onError').length;
	                if(numError){
	                    return false;
	                } 
	                alert("注册成功,密码已发到你的邮箱,请查收.");
	         });

	        //重置
	         $('#res').click(function(){
	                $(".formtips").remove(); 
	         });
		}
	});
})