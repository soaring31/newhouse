/**
 * @method popUnder
 * @description  背投广告
 * @param  {Number} beginTime          开始时间
 * @param  {Number} endTime            自动关闭时间
 * @param  {Number} speedTime      	   运动时间
 * @param  {Number} type         	   类型 1:改变高度;2:弹层
 * @param  {Object} boxClass           广告对象的选择器
 *
 * @调用
 * ```js
 *popunder.init();
 * ```
 * 
 *@已经集成在宏里面，直接调用宏方法
 *@当有多个背投广告时，boxClass的class名要不一样
 * ```html
 *
 *
 * ```
 **/

define(['jqmodal', 'utils'], function(require, exports, module) {
	var utils = require('utils');
	var ads = {
		init: function(opt) {
			var _self = this;
			var _opts = _self.opts = $.extend({}, {
				beginTime: 3000,
				endTime: 10000,
				speedTime: 1000
			}, opt);
			var $popad = $('[data-popads="1"]');
			if ($popad.length <= 0) {
				return;
			}

			$popad.each(function(i, el){
				var $this = $(this);
				var config = utils.parseJSON($this.data('config'));
				$this.fn = null;
				var opt = this.opt = $.extend({}, _opts, config);
				if (opt.type == 1) {
					$this.fn = $.proxy(_self.floatShow, this);
				} else if (opt.type == 2) {
					if(i == 0){
						this.opt.first = true;
					}
					this.opt.Index = i; 
					$this.fn = $.proxy(_self.fixShow, this);
				}

				$this.fn();	
				
			});
			

			$(document).off('.close').on('click.close', '[data-role="ad_close"]', function() {
				$(this).trigger('hideFun');
			});

			$(document).off('.popshow').on('click.popshow', '[data-popads="1"]', function() {
				$(this).trigger('showFun');
			});


		},

		floatShow: function() {
			var _self = this;
			var _opt = _self.opt;
			var $popad = $(this);
			// return;
			var $adbox = $(_opt.boxClass);
			var $close = $adbox.find('[data-role="ad_close"]');
			var endTime = null;
			// 第一次初始化
			$close.addClass('ad_close_float');
			$adbox.css({
				height: 0,
				width: '100%',
				overflow: 'hidden'
			}).show().find('img').width('100%');
			$adbox.hover(function() {
				$close.show();
			}, function() {
				$close.hide();
			});

			var beginTime = setTimeout(function() {
				$adbox.animate({
					height: _opt.height
				}, _opt.speedTime, function() {
					timeout();
				});
			}, _opt.beginTime);

			$close.on('hideFun', function() {
				// return;
				var $this = $(this);
				clearInterval(endTime);
				$adbox.animate({
					height: 0
				}, _opt.speedTime, function() {
					$this.hide();
					$popad.show().addClass('floatShow');
				})
			});

			$popad.on('showFun',  function() {
				$(this).hide();
				clearInterval(endTime);
				$adbox.animate({
					height: _opt.height
				}, _opt.speedTime, function() {
					timeout();
				});
			});


			function timeout() {
				endTime = setTimeout(function() {
					clearInterval(beginTime);
					$adbox.animate({
						height: 0
					}, _opt.speedTime, function() {
						$popad.show().addClass('floatShow');
						clearInterval(endTime);
					})
				}, _opt.endTime);
			};

		},

		fixShow: function() {
			var _self = this;
			var _opt = _self.opt = $.extend({}, {
				zIndex: parseInt(new Date().getTime() / 1000)
			}, _self.opt);;
			var $popad = $(this);
			var $adbox = $(_opt.boxClass);
			var $close = $adbox.find('[data-role="ad_close"]');
			var endTime = null;
			var $mark = $('body').find('.ad_mark');

			$adbox.css({
				position: 'fixed',
				left: '50%',
				top: '50%',
				width: 0,
				zIndex: _opt.zIndex + 1
			});
			$close.addClass('ad_close_fix').css({
				zIndex: _opt.zIndex + 2
			});

			if ($mark.length > 0) {
				$mark.css({
					'zIndex': _opt.zIndex
				}).show();
			} else {
				$mark = $('<div class="ad_mark"></div>').appendTo('body').css({
					'zIndex': _opt.zIndex
				});
			}

			if(!_opt.first){
				popChange();
			}else{
				var beginTime = setTimeout(function() {
					// debugger
					$adbox.show().animate({
						height: _opt.height,
						width: _opt.width,
						left: '25%',
						top: '15%'
					}, _opt.speedTime / 3, 'swing', function() {
						$adbox.css('overflow','visible');
						$close.show();
						endTime = setTimeout(function() {
							$close.trigger('hideFun');
							clearInterval(endTime);
						}, _opt.endTime);
					});
				}, _opt.beginTime);
			}



			$popad.on('showFun', function() {
				// debugger;
				$mark.show();
				$adbox.css('overflow','visible').show().animate({
					height: _opt.height,
					width: _opt.width,
					left: '25%',
					top: '15%'
				}, _opt.speedTime / 3, 'swing', function() {
					clearInterval(endTime);
					$popad.hide().css({
						left: '50%',
						bottom: '50%'
					});
					endTime = setTimeout(function() {
						$close.trigger('hideFun');
						clearInterval(endTime);
					}, _opt.endTime);
				});

			});

			$close.on('hideFun', function() {
				$mark.hide();
				$adbox.fadeOut('slow', function() {
					$adbox.css({
						height: 0,
						width: 0,
						left: '50%',
						top: '50%',
						overflow: 'hidden'
					});
					popChange();
					
				});

			});

			function popChange(){
				
				var $emVal = $popad.find('em');
				$popad.addClass('fixShow').show(50).animate({
					left: '0',
					bottom: 100 + _opt.Index * 80
				}).find('em').html('广告重播');
				$popad.hover(function(){
					$emVal.show();
				}, function(){
					$emVal.hide();
				});
			}

		}

	};

	module.exports = ads;
});
//# sourceMappingURL=popUnder.js.map
