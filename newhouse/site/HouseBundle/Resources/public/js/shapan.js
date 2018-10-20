/**
* author : ling
* date   : 2016-11-7
* name   : shapan
* modify : 2017-01-05
* 后台调用：shapan.init({url:'...'});
* 前台调用：shapan.showInfo(args);
 */

define(['jqmodal'], function(require, exports, module) {
	(function(out) {
		module.exports = out;
	})({
		init: function(opt) {
			var _self = this,
				dotsData = [],
				ldChecked = '',
				$dataOx = $('[data-ox]');

			// 保存从按钮上获取的楼栋信息
			for (var i = 0; i < $dataOx.size(); i++) {
				var data = $dataOx.eq(i).data('ox').split(',');
				var str = data[0] + '^' + data[1] + ':' + $dataOx.eq(i).val();
				dotsData.push(str);
				ldChecked += data[0] + ',';
			};

			$('#shapan-i').jqDrag({
				attachment: '#shapan'
			});


			var _opt = _self.opt = $.extend(true, {}, {
				dotsData: dotsData,
				url: null,
				ldChecked: ldChecked,
				csrf: null

			}, opt);
			/**
			 * url ：ajax请求地址(必填)
			 * dotsData：楼栋的信息，包括位置和id。一般不用填
			 * ldChecked : 保存楼栋的id。一般不用填
			 */



			var ldCheckedAids = _opt.ldChecked.split(',');
			// 初始化复选框按钮
			for (var i = 0; i < ldCheckedAids.length; i++) {
				var checkboxId = document.getElementById('shapan_' + ldCheckedAids[i]);

				if (checkboxId && $dataOx.eq(i).val().split(',')[1]) {
					checkboxId.checked = true;
					checkboxId.disabled = false;
				}
			}

			// 初始化-所有点
			_self.shaInit();
			_self.inputClick();
			return this;
		},

		shaInit: function() {
			var _self = this,
				_opt = _self.opt;
			for (var i = 0; i < _opt.dotsData.length; i++) {
				var iarr = _opt.dotsData[i].split(':');
				var imsg = iarr[0].split('^');
				var ipos = iarr[1].split(',');

				if ($('#shapan_' + imsg[0]).is(':checked')) {

					_self.shaIdot(imsg[0], imsg[1], parseInt(ipos[0]), parseInt(ipos[1]));
				}
			}
		},
		// 点击添加删除楼栋
		inputClick: function() {
			var _self = this,
				_opt = _self.opt;
			$('[data-ox]').off('click.input').on('click.input', function(event) {
				var ox = $(this).data('ox').split(',');
				_self.shaAdd(ox[0], ox[1]);
			});

			return this;
		},

		// 添加点
		shaAdd: function(id, msg) {
			var _self = this,
				_opt = _self.opt;
			var checkBox = document.getElementById('shapan_' + id);
			if (checkBox.checked == true) {
				_self.shaIdot(id, msg, 10, 10);
				_self.ajax(_opt.url, id, '0,0');
			} else {
				_self.shaDel(id);

			}

			return this;
		},
		// 删除点
		shaDel: function(id) {
			var _self = this,
				_opt = _self.opt;
			$('#dot_' + id).remove();
			$('#shapan_' + id).prop({
				checked: false,
				disabled: false
			});
			//清理表单项目
			_self.ajax(_opt.url, id, '0');

			return this;
		},
		//显示点的位置 
		shaIdot: function(id, ldmc, left, top) {
			var _self = this,
				_opt = _self.opt;

			var isMore = false;
			$('<a>', {
					id: 'dot_' + id,
					'class': 'sha-dot sha-dot-' + $('#shapan_' + id).attr('data-xszt'),
					html: ldmc + '<i></i><b title="close"></b>'
				})
				.css({
					left: left,
					top: top
				})

			.jqDrag({
					attachment: '#shapan-i'
				})
				.on('drag', function() {
					isMore = true;
				})
				.on('dragEnd', function(el, l, t) {

					$input = $('#' + this.id.replace('dot', 'shapan'))
					$input.val(l + ',' + t);

					if (isMore) {
						_self.ajax(_opt.url, id, l + ',' + t);
						isMore = false;
					}

				})
				.appendTo('#shapan-i')
				.find('b').off('click.shaDel').on('click.shaDel', function(event) {
					event.stopPropagation();
					_self.shaDel(id);
				});


			return this;
		},
		// 前台显示详细内容
		showInfo: function(args) {
			var _self = this,
				_args = $.extend(true, {}, {
					drag: null,
					dragParent: null,
					dot: null,
					info: null
				}, args);
			var $img = $(_args.drag + ' img');

			var range_w ;
			var range_h ;
			var is_left ;
			var is_top ;

			var drag_size = {
				width: $img.width(),
				height: $img.height()
			};

			if (!drag_size.width) {
				$img.load(function() {
					drag_size = {
						width: $(this).width(),
						height: $(this).height()
					};

					getSize();

				});

			}

			var parent_size = {
				width: $(_args.dragParent).width(),
				height: $(_args.dragParent).height()
			};

			getSize();

			function getSize(){
				 range_w = (parent_size.width - drag_size.width) / 2;
				 range_h = (parent_size.height - drag_size.height) / 2;
				 is_left = range_w > 0 ? true : false;
				 is_top = range_h > 0 ? true : false;

				$(_args.drag).css({
					left: range_w,
					top: range_h
				});
				
			};

			/**
			 * drag : 拖拽的对象
			 * dragParent : 拖拽的父级
			 * dot : 楼栋的点
			 * info ：楼栋的详细信息
			 */

			$(_args.drag)
				.jqDrag({
					attachment: _args.dragParent
				})
				.off('.shapan')
				.on('drag.shapan', function(el, left, top) {
					if (is_top) {
						$(this).css({
							top: range_h
						});
					}
					if (is_left) {
						$(this).css({
							left: range_w
						});
					}
				});

			// 楼栋信息显示
			$(document).on('click', '[data-shapan-item]', function() {
				var $this = $(this);
				var $box = $this.find('.shapan-box');
				var $icon = $this.find('[data-icon]');
				if ($box.is(':hidden')) {
					$this.siblings('[data-shapan-item]').hide();
					$this.show();
					$box.show();
					$icon.removeClass('font-ico-plus').addClass('font-ico-plus0');
				} else {
					$this.siblings('[data-shapan-item]').show().find('.shapan-box').hide();
					$box.hide();
					$icon.removeClass('font-ico-plus0').addClass('font-ico-plus');
				}
			});

			$(document).on('click', '[data-role="sha-dot"]', function() {
				var $box = $('[data-info-big]');
				if ($box.is(':hidden')) {
					$('[data-info]').trigger('click');
				}
				var index = $(this).data('index');
				setTimeout(function() {
					$('[data-shapan-item="' + index + '"]').trigger('click')

				}, 100);
			});

		},
		ajax: function(url, id, seat) {
			var _self = this;
			var _opt = _self.opt;

			$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: {
						id: id,
						seat: seat,
						csrf_token: _opt.csrf,
						_form_id: 309,
						checked: 1
					},
				})
				.done(function(result) {
					if (!result.status) {
						$.jqModal.tip(result.info, 'error')
					}
				})
				.fail(function() {
					$.jqModal.tip('操作失败！', 'error')
				})
				.always(function() {});
		}

	});
});
//# sourceMappingURL=shapan.js.map
