/**
 * author : ling
 * date   : 2016-11-9
 * name   : collect
 * modify : 2016-12-21
 */

/**
 * @module collect
 * @description  点击收藏
 * @param {String} data-collected  表示是否已收藏
 * @param {String} data-collect    收藏的配置,包括aid,models,uid,url
 * @param {String} aid             收藏的关联id
 * @param {String} models          收藏的模型
 * @param {String} uid             收藏的会员id
 * @param {String} url             收藏请求的地址
 *
 * @说明信息
 * 调用前先获取当前会员有没有收藏过
 * 调用的时候，标签可以改，但结构请保持不变
 * 
 * @example 调用
 * ```html
 * 
 * 
 *
 * 
 *{% set hascollect = ('house.collects')|getAll({checked:0, aid: xq_detail.id|default(''), models: xq_detail.models|default(''), uid: uid }) %}
 *
 * 
 *
 *
 * <a data-collected="0" data-collect="{aid:,models: '', uid: 0 }" data-
 * url="collects/save">
 *     <i class="font-icons-19 mr5"></i>
 *	   <span  data-after="已收藏">收藏</span>
 *</a>						
 * ```
 * @预览 地址?id=19
 * @example
 * ```html
 * http://192.168.1.7/newcore/web/app_dev.php/house/houses/detail?id=19
 * ```
 * 
 * 
 */

define(['jqmodal', 'utils'], function(require, exports, module) {
	var utils = require('utils');
	
		
	var app = {
		init: function() {
			var _self = this;
			var $collected = $('[data-collected]');
			if($collected.data('collected')*1 > 0){
				$collected.addClass('collected');
				var $val = $collected.find('[data-after]');
				$val.html($val.data('after'));

			}
			$(document).off('.collect').on('click.collect', '[data-collect]', function() {
				var $this = $(this);
				var $collects = utils.parseJSON($this.data('collect'));
				var $url = $this.data('url');
				var $val = $this.find('[data-after]');
			   

				if (!$this.hasClass('collected')) {
					_self.ajax($url, $collects, $this);
				}
			});

		},

		ajax: function(url, data, obj) {
			obj.addClass('requesting');
			if (obj.hasClass('requesting')) {
				$.ajax({
						url: url,
						type: 'POST',
						dataType: 'json',
						data: data
					})
					.done(function(result) {
						if (result.status) {
							
							$.jqModal.tip(result.info, 'success');

							obj.addClass('collected');
							var $val = obj.find('[data-after]');
							$val.html($val.data('after'));
						} else {
							
								$.jqModal.tip(result.info, 'info');
								
							
						}
					})
					.fail(function() {
						$.jqModal.tip('提交失败', 'info');
					})
					.always(function() {
						obj.removeClass('requesting');
					});
			}

		}
		
	};
	app.init();
	module.exports = app;

});

//# sourceMappingURL=..\src\js\collect.js.map
//# sourceMappingURL=collect.js.map
