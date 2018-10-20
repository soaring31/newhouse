define(['ajax','utils'], function(require, exports, module){
	var ajax = require('ajax');
	var utils = require('utils');
	var app = {
		init(opt){
			var _self = this;
			_self.opt = $.extend({} ,{
				cacheTTL: 1,
				type: 'GET'
			}, opt);
			
			var $form = $('[data-formcache="1"]');
			$.each($form,function(i, el){
				var config = utils.parseJSON($(this).data('config'));
				_self.ajax(config, el);
			});

		},

		ajax(config, obj){
			var _self = this;
			$.ajax({
				url: config.url,
				localCache: true,
				cacheTTL : _self.opt.cacheTTL,
				type: 'GET',
				dataType: 'html',
				data: config.data,
			})
			.done(function(data) {
				$(obj).html(data);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
			
		},

		clicked(config, obj){
			var _self = this;
			$(obj).off('.cache').on('click.cache', function(){
				_self.ajax(config, config.target);
				$(this).trigger('formcache');
			});
		}
	}

	module.exports =  app;
})
//# sourceMappingURL=mobileFormCache.js.map
