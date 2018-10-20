(function($, win, undefined) {

	$.fn.gallery = function(options) {
		var _this = this;

		var defaults = {
			ad: false, // 广告设置
			maxSize: false, // 设置是否启用屏幕宽度
			maxW: 500, // 设置大图区域宽度
			maxH: 500, // 设置大图区域高度
			wrap: '.picshow', // 外框
			bigpic: '.picshowtop', // 大图区域
			slist: '.picshowlist_mid', // 小图外框
			sul: '.picmidmid', // 小图列表
			pic: '#pic1', // 大图,图框
			curIndex: '.picshowtxt_left span', // 当前索引
			curTxt: '.picshowtxt_right', // 当前介绍
			curclass: 'selectpic', // 小图当前状态样式
			viewPic: '', // 查看原图
			fun: null, // 大图显示之后执行
		};
		var o = $.extend({}, defaults, options);
		var getli = $("ul li", o.sul);
		var liW = getli.outerWidth(true);
		var liLen = getli.length;
		// 初始化执行
		init();
		// 绑定按键执行
		bindClick();
		// 窗口大小变化
		$(win).resize(function() {
			bigimg();
		});

		// 左右箭头操作以及esc按钮
		$(document).keydown(function(event) {
			var key = event.keyCode;
			var enddisplay = $(".endtop").css("display");
			if (enddisplay == "none") {
				if (key == 37) {
					prev()
				} else {
					if (key == 39) {
						next()
					}
				}
			} else {
				if (key == 27) {
					$(".bodymodal").css("display", "none");
					$(".endtop").css("display", "none")
				}
			}
		});

		// 初始化
		function init() {
			// 区域大小初始化
			bigimg();
			//内容区宽度
			$("ul", o.sul).width(liW * liLen).css({
				"left": 0
			});
			var firstpic = $("li:first", o.sul);
			var firstsrc = firstpic.data("src");
			var firsttxt = firstpic.data("sub-html");
			bigShow(firstsrc, firsttxt, 0);
			if ($(o.wrap).is(':hidden')) {
				$(o.wrap).show();
			};
		};

		// 大图显示
		function bigShow(src, txt, cur) {
			if ($(o.pic).find('img')) {
				$(o.pic).find('img').remove();
			};
			var $img = $('<img>');
			var _img = new Image();
			_img.src = src;

			$(o.pic).prepend($img.attr({
				'src': _img.src,
				'curindex': cur
			})); // 添加到当前位置

			$(o.curIndex).text(cur + 1); // 当前index
			$(o.curTxt).html(txt); // 当前解说
			$(o.viewPic).attr('href', src); // 查看原图
			getli.eq(cur).addClass(o.curclass).siblings().removeClass(o.curclass); // 当前小图class
			// 显示图片之后执行函数
			if (typeof o.fun == "function") {
				o.fun();
			};
		};

		// 大图左移动
		function prev() {
			var currrentindex = parseFloat($("img", o.pic).attr("curindex"));
			if (currrentindex > 0) {
				var curprevli = getli.eq(currrentindex - 1);
				var curnextsrc = curprevli.data("src");
				var curnexttxt = curprevli.data("sub-html");
				bigShow(curnextsrc, curnexttxt, currrentindex - 1);
				middle(currrentindex - 1);
			};
		};

		// 大图右移动
		function next() {
			var currrentindex = parseFloat($("img", o.pic).attr("curindex"));
			if (currrentindex != (liLen - 1)) {
				var curnextli = getli.eq(currrentindex + 1);
				var curnextsrc = curnextli.data("src");
				var curnexttxt = curnextli.data("sub-html");
				bigShow(curnextsrc, curnexttxt, currrentindex + 1);
				middle(currrentindex + 1);
			} else {
				showAd();
			}
		};

		//居中显示
		function middle(index) {
			var $ul = $("ul", o.sul); // ul
			var ulW = parseFloat($ul.css("width")); // ul宽度
			var divW = parseFloat($(o.sul).css("width")); // div,也即是显示区宽度
			var indexW = parseFloat(liW * index + liW / 2); // 当前元素偏移的宽度 + 当前元素宽度的一半
			// console.log(liW * (index + 1))
			// console.log(liW / 2)
			if (ulW >= divW) {

				// 当ul的宽度大于显示区的宽度时执行
				if (indexW - divW / 2 > 0 || ulW - indexW < divW / 2) {

					// 当前偏移大于一半的显示区宽度或者剩余的宽度要比显示区域的一半要小
					if (ulW - indexW < divW / 2 + 2) {

						// 剩余的宽度要比显示区域的一半要小
						$ul.stop().animate({
							'left': divW - ulW
						}, 1000);
					} else {

						// 当前偏移大于一半的显示区宽度
						$ul.stop().animate({
							'left': divW / 2 - indexW
						}, 1000);
					};
				} else {

					// ul宽度小于显示区
					$ul.stop().animate({
						'left': 0
					}, 1000);
				};
			}
		};

		// 小图移动
		function smallBtn(direction) {
			var $ul = $("ul", o.sul);
			var ulW = parseFloat($ul.css("width"));
			var ulL = parseFloat($ul.css("left"));
			var divW = parseFloat($(o.sul).css("width"));
			if (direction == "prev") {
				if (ulW <= divW) {
					$ul.css("left", 0);
				} else if (-ulL > divW) {
					$ul.stop().animate({
						'left': ulL + divW
					}, 1000);
				} else {
					$ul.stop().animate({
						'left': 0
					}, 1000);
				}
			};
			if (direction == "next") {
				if (ulW <= divW) {
					$ul.css("left", 0);
				} else if (ulW + ulL - divW >= divW) {
					$ul.stop().animate({
						'left': ulL - divW
					}, 1000);
				} else {
					$ul.stop().animate({
						'left': divW - ulW
					}, 1000);
				}
			};
		};

		// 小图点击
		getli.click(function() {
			var $this = $(this);
			var currentliindex = $this.index();
			var bigimgsrc = $this.data("src");
			var curnexttxt = $this.data("sub-html");
			bigShow(bigimgsrc, curnexttxt, currentliindex)
			middle(currentliindex)
		});

		// 点击到最后一个时候返回值
		function showAd() {
			if (o.ad) {
				$(".bodymodal").css("display", "block");
				$(".endtop").slideDown();
			};
		};

		// 广告关闭
		function closeAd() {
			$(".endtop").css("display", "none");
			$(".bodymodal").hide();
		};

		// 重新浏览
		function replay() {
			closeAd();
			init();
		};

		// 图片区域的大小
		function bigimg() {
			if (o.maxSize) {
				var hei = $(win).height();
				var w = $(win).width();
				$(o.wrap).css({
					'width': w
				});
				$(o.bigpic).css({
					"height": hei - 100,
					"line-height": hei - 100 + 'px'
				});
				$(o.slist).css({
					'width': w
				});
				$(o.sul).css({
					'width': w - 62
				});
			} else {
				$(o.wrap).css({
					'width': o.maxW
				});
				$(o.bigpic).css({
					"height": o.maxH,
					"line-height": o.maxH + 'px',
					"width": o.maxW
				});
				$(o.slist).css({
					'width': o.maxW
				});
				$(o.sul).css({
					'width': o.maxW - 62
				});
			}
		};

		// 箭头隐藏显示
		function hover(superEle, subEle) {
			var sub = $(subEle);
			$(superEle).hover(function() {
				sub.show();
			}, function() {
				sub.hide();
			});
		};

		// 功能绑定
		function bindClick() {
			// 左箭头
			hover("#preArrow", "#preArrow_A");
			// 右箭头
			hover("#nextArrow", "#nextArrow_A");
			// 广告关闭
			$('.closebtn').click(function() {
				closeAd();
			});
			// 重新浏览
			$(".replaybtn").click(function() {
				replay();
			});
			// 上一张
			$("#preArrow").click(function() {
				prev()
			});
			// 下一张
			$("#nextArrow").click(function() {
				next();
			});
			// 小图左移动
			$("#preArrow_B").click(function() {
				smallBtn("prev");
			});
			// 小图右移动
			$("#nextArrow_B").click(function() {
				smallBtn("next")
			});
		};
	};


})(jQuery, window)