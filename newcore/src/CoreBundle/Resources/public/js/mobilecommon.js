define(['ajax', 'utils'], function(require, exports, module) {
	var ajax = require('ajax');
	var utils = require('utils');
	// 倒计时
    var countDown = function(senconds, $el, tpl) {
        var $btnTxt = $el.find('.code-btn-text');
        if (senconds > 1) {
            $btnTxt.html(tpl ? tpl.replace('%s%', senconds--) : '(' + senconds-- + ')秒后重新发送');
            $el.addClass('disabled');
            setTimeout(function() {
                countDown(senconds, $el, tpl);
            }, 1000);
        } else {
            $btnTxt.html(($el.data('title') || '短信验证码'))
            $el.removeClass('disabled');
        }
    }
    
	var app = {
		init: function(opt) {
			var _opt = $.extend({}, {
				type: 'GET',
				datatype: 'json',
				jsonpCallback: null,
				data:{}
			}, opt);
			

			$(document)
				// 点击切换验证码
				.on('tap', '.mobile-form .code-img', function() {
					
					var src = $(this).data("src");
					$(this).attr("src", src + "?" + Math.floor(Math.random() * (1000 + 1)));
				})
				// 换一张
				.on('tap', '.mobile-form .code-title', function() {
					$(this).prev('img').trigger('tap');
					return false;
				})
				// 回车提交
				.on('keypress', '.mobile-form input[type="text"]', function(e) {

				})
				// 提交表单
				.on('tap', '.mobile-form [data-role="submit"]', function(e) {
					var $this = $(this),
						$form = $this.closest('form'),
						url = $form.data('url'),
						loginUrl = $form.data('login-url'),
						data = {},
						eldata = $this.data();

						// console.log(loginUrl);

					if ($form.size()) {
						
						data = $form.serializeArray();
						// console.log(data);
						
						// var str = '{';
						// $.each(data, function(i, el) {
						// 	str += '"'+el.name+'":"'+el.value+'",';
						// });
						// str += '}';

						// console.log(str);

						// var datas = utils.parseJSON(str);

						// console.log(datas);

						eldata.type = _opt.type;
					}

					if (!$this.hasClass('requesting')) {
						$this.addClass('requesting');

						$this.data('title', $this.html()).html(eldata.loading || '正在提交...');
						// console.log(_opt.data);
						$.each(_opt.data, function(i,e){
							data.push({
								'name': i,
								'value': e
							});
						});
						// data = $.extend({}, data, _opt.data);

						// console.log(data);
						
						ajax({
							url: eldata.url || $form.attr('action'),
							type: eldata.type, // || 'GET',
							dataType: _opt.datatype,
							data: data,
							jsonpCallback: _opt.jsonpCallback,
							success: function(result) {

								if (result.status) {

									$.alert(result.info);
									if(loginUrl){
										location.href = loginUrl;
									}
									else if (url) {
										location.href = url;

									}else if (result.url) {
										location.href = result.url;
									} else if (eldata.jumpurl) {
										location.href = eldata.jumpurl;
										
									} else {
										
									}
									$form[0].reset();
									$form.trigger('done');
								} else {

									$.alert(result.info);
								}
								
								$form.find(".code-img").trigger('tap');
								
							},
							error: function(e) {
								$.alert('Ajax error!');
							},
							complete: function() {
								$this.removeClass('requesting');
								$this.html($this.data('title'));

							}
						});
					}

					e.stopPropagation();
					return false;
				})
				// 发送短信
				.on('tap', '.code-btn', function() {
					var $this = $(this);
		            var this_data = $this.data();
		            var role = this_data.code;
		            var codeVal = $('#'+this_data[role]).val();
		            if(role == 'tel'){
		                var option = {
		                    tel: codeVal
		                }
		            }else if(role == 'mail'){
		                var option = {
		                    mail: codeVal
		                }
		            }
		            var option = $.extend(true, {}, option, _opt.data, {
		            	csrf_token: csrf_token
		            });
		            if(!codeVal){
		                if(role == 'tel'){
		                    $.alert('手机号码不能为空');
		                }else if(role == 'mail'){
		                    $.alert('邮箱不能为空');
		                }
		                $('#'+this_data.role).focus();
		                return false;

		            }
					// 禁用
					if ($this.hasClass('disabled')) {
						return false;
					};

					if (!$this.hasClass('requesting')) {
						$this.addClass('requesting');
						ajax({
							type: 'GET', // 短信发送此方式限定只能接受GET方式传送的参数,不要更改成POST
							url: this_data.src,
							data: option,
							dataType: _opt.datatype,
							jsonpCallback: _opt.jsonpCallback,
							success: function(result){
								if (result.status) {
									countDown(10, $this)
								} else {
									$.alert(result.info || 'error');
								}
							},
							error: function(result){
								$.alert(result.info || 'error');
							},
							complete: function(){
								$this.removeClass('requesting');
							}
						});	
					}

				})
				// 使用按钮可用
			$('.btn[disabled="disabled"]').prop('disabled', '');
		},

		toTop: function(){
			var $toTop = $('[data-to-top="1"]');
			if($toTop.size() <= 0){
				return;
			}
			$toTop.on('tap', function(){
				$('.content').scrollTo({scrollTop:0})
			});
			$('.content').on('scroll', function(){
				var scrollTop = $(this).scrollTop();
				var rand = parseInt($(this).height()/2);
				if(scrollTop >= rand){
					$toTop.show();
				}else{
					$toTop.hide();
				}
			});
		},

		dcounts: function(opt){
            var $counts = $('[data-detailcounts="1"]');
            if($counts.length >= 1){
                var config = utils.parseJSON($counts.data('config'));
                ajax({
                    url: opt.url,
                    type: 'GET',
                    dataType: 'json',
                    data: config,
                });
                
            }
        },

		parseJSON: function(jsonStr){
			return typeof jsonStr == 'object' ? jsonStr : (new Function('return ' + jsonStr))();
		}
	};

	// app.init();

	module.exports = app;
});
//# sourceMappingURL=mobilecommon.js.map
