/**
 * @name advertise
 * @description  背投广告1
 * @param  {Object} cfg 当前广告配置信息
 * @param {string} cfg.opt 大屏大广告外层元素位选择器
 * @param {string} cfg.height  大屏大广告外层元素高度
 * @param {string} cfg.width  大屏大广告外层元素宽度
 * @param {string} cfg.style  展示方式，1-js内置样式，2-js外置样式
 * @param {string} cfg.btn 广告关闭/重播外层元素选择器
 * @param {Number} cfg.showing 大屏广告图展示时间
 * @param {Number} cfg.slideTime 关闭/打开过渡动画时间
 * @调用
 * ```js
 *advertise.init({showing: 20000});
 * ```
 *
 *@说明
 *已经集成在宏里面，直接调用宏方法
 *
 * ```html
 *
 *{{ cgb.pop_ads({tag: 'btgg',width:800,height:500,type:1, boxClass: 'pop_ads', class:''}) }}
 * ```
 */
define(['jqmodal', 'utils', 'cookie'], function(require, exports, module) {
	var utils = require('utils');

	var ads = {
		init: function (opt) {
			// 初始化
			var _self = this;
			var _opts = _self.opts = $.extend({}, {
				showing: 10000,
				slideTime: 500,
			}, opt);

			var $popad = $('[data-popads="1"]');

			if ($popad.length <= 0) {
				return;
			}

			$popad.each(function(i, el){
				var $this = $(this);
				var config = utils.parseJSON($this.data('config'));
				var cookie = $.cookie('advertise'+i);
				if(!cookie){
					_self.play(config);
					$.cookie('advertise'+i, 1);
				}else{
					_self.close(config);
				}
				// 重播
				$this.find('[data-replays]').on('click', function () {
					_self.play(config);
				});
				// 关闭
				$this.find('[data-close]').on('click', function () {
					_self.close(config);
				});
				// 移除
				$this.find('[data-remove]').on('click', function () {
					_self.remove(config);
				});
			});
		},
		// 播放
		/**
		 * @method 播放广告
		 * @param  {Object} cfg 当前广告配置信息
		 * @param {string} cfg.opt 大屏大广告外层元素位选择器
		 * @param {string} cfg.height  大屏大广告外层元素高度
		 * @param {string} cfg.width  大屏大广告外层元素宽度
		 * @param {string} cfg.style  展示方式，1-js内置样式，2-js外置样式
		 * @param {string} cfg.btn 广告关闭/重播外层元素选择器
		 * @param {Number} cfg.showing 大屏广告图展示时间
		 * @param {Number} cfg.slideTime 关闭/打开过渡动画时间
		 */
		play: function (cfg) {
			var _self = this;
			var _opts = _self.opts;
			var _height = cfg.height;
			var _width = cfg.width;
			$(cfg.btn).hide();
			switch (cfg.style) {
				case 1:
					$(cfg.opt).css({
						'position': cfg.pos || 'fixed',
						'top': '50%',
						'left': '50%',
						'height': _height + 'px',
						'width': _width + 'px',
						'margin-left': - _width / 2 + 'px',
						'margin-top': - _height / 2 + 'px',
						'z-index': cfg.zindex || 2000
					}).stop().slideDown(cfg.slideTime || _opts.slideTime);
				break;
				case 2:
					$(cfg.opt).stop().slideDown(cfg.slideTime || _opts.slideTime);
				break;
			}
			// console.log(cfg.showing, _opts.showing)
			_self.timer = setTimeout(function () {
				_self.close(cfg);
			}, cfg.showing || _opts.showing);
			// 关闭按钮显示隐藏
			// _self.hover(cfg.opt, '[data-close]');
		},
		// 关闭
		/**
		 * @method 关闭广告
		 * @param  {Object} cfg 当前广告配置信息
		 * @param {string} cfg.opt 大屏大广告外层元素位选择器
		 * @param {string} cfg.height  大屏大广告外层元素高度
		 * @param {string} cfg.width  大屏大广告外层元素宽度
		 * @param {string} cfg.style  展示方式，1-js内置样式，2-js外置样式
		 * @param {string} cfg.btn 广告关闭/重播外层元素选择器
		 * @param {Number} cfg.showing 大屏广告图展示时间
		 * @param {Number} cfg.slideTime 关闭/打开过渡动画时间
		 */
		close: function (cfg) {
			var _self = this;
			var _opts = _self.opts;
			var _height = cfg.sm.height;
			var _width = cfg.sm.width;
			var _right = cfg.sm.right;
			$(cfg.opt).stop().slideUp(cfg.slideTime || _opts.slideTime, function () {
				switch (cfg.style) {
					case 1:
						$(cfg.btn).css({
							'position': cfg.sm.pos || 'fixed',
							'top': '50%',
							'right': _right ? _right + 'px' : '0',
							'height': _height + 'px',
							'width': _width + 'px',
							'margin-top': - _height / 2 + 'px',
							'z-index': cfg.zindex || 2000
						}).stop().slideDown(cfg.slideTime || _opts.slideTime);
					break;
					case 2:
						$(cfg.btn).stop().slideDown(cfg.slideTime || _opts.slideTime)
					break;
				}
				// $(cfg.btn).slideDown(cfg.slideTime || _opts.slideTime)
			});

			clearInterval(_self.timer);
			// 小图操作按钮显示隐藏
			// _self.hover(cfg.btn, '.operat-btn');
		},
		// 移除
		/**
		 * @method 移除广告
		 * @param  {Object} cfg 当前广告配置信息
		 */
		remove: function (cfg) {
			var _self = this;
			var _opts = _self.opts;
			$(cfg.opt).closest('[data-popads]').remove();
		},
		// 按钮显示
		/**
		 * @method 显示隐藏按钮
		 * @param  {String} opt 父级选择器
		 * @param  {String} btn 需要显示的选择器
		 */
		hover: function (opt, btn) {
			$(opt).hover(function () {
				var _btn = $(this).find(btn);
				if (_btn.is(':hidden')) {
					$(this).find(btn).stop().slideDown();
				};
			}, function () {
				var _btn = $(this).find(btn);
				if (_btn.is(':visible')) {
					$(this).find(btn).stop().slideUp();
				};
			});
		}
	};

	module.exports = ads;
})
