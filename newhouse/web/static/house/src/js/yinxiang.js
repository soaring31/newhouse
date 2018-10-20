/**
 * author : ling
 * date   : 2016-11-18
 * name   : yinxiang
 * modify : 2016-11-18
 */

/**
 * @module yinxiang
 * @param {String} list jq选择器 印象的列表
 * @param {String} url 请求的列表地址
 * @param {String} saveUrl 点评提交的地址
 *
 *
 *
 * @example 调用
 * ```js
 * yinxiang.init({
 *	  list: '#items',
 *	  url: '{{ ("clist/yx_list")|U({aid:detail.id}) }}',
 *	  save_url: '{{ ("yinxiang/save")|U }}'
 * });
 * ```
 * @预览 地址?id=19
 * @example
 * ```html
 *	 <div class="bd-gray p15">
 *		<form  method="get" class="comment-form-self" action="{{ ('yinxiang/save')|U }}">
 *			<input type="hidden" name="form" value="houses_impress" />
 *			<input type="hidden" name="aid" value="{{detail.id}}" />
 *			<input type="hidden" name="cate_pushs" value="yinxiang" />
 *			<input type="hidden" name="_form_id" value="281" />
 *			<div class="search-group form form-bdrs">
 *				<button  class="btn submit" > <i class="font-ico-2"></i>
 *					添加印象
 *				</button>
 *				<div class="txt-wrap">
 *					<input type="text" placeholder="至多5个字" name="yinxiang" class="txt" id="yinxiang">< *  /div>
 *			</div>
 *		</form>
 *		<div class="blank15"></div>
 *		<div class="items clearfix" id="items">
 *		</div>
 *	</div>
 * http://192.168.1.7/newcore/web/app_dev.php/house/houses/detail?id=19
 * ```
 * @说明信息
 *
 *
 */

define(['jqmodal'], function(require, exports, module) {
	(function(out) {
		module.exports = out;

	})({
		// 初始化函数
		init: function(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend(true, {}, {
				list: null,
				url: null,
				save_url: null
			}, opt);

			var $list = $(_opt.list);
			// 防止重复初始化
			if ($list.hasClass('inited')) {
                return;
            }
            $list.addClass('inited');

			_self.opt.url = $(document.createElement('div')).html(_self.opt.url).text();

			_self.ajax();

			$(document)
				// 回车提交
		        .on('keypress', '#yinxiang', function(e) {

		        })
				.off('.yx')
				.on('click.yx', '.submit', function(e) {
						$this = $(this);
						$text = $('#yinxiang');
						var yxNum = $.cookie('yinxiang-num') || 0;

						if ($text.val().length <= 0) {
							$.jqModal.tip('印象不能为空', 'info');
							return false;
						} else if ($text.val().length > 5) {
							$.jqModal.tip('不能超过五个字', 'info');
							return false;
						} else if ($.cookie('yinxiang-num') >= 3) {
							$.jqModal.tip('亲，只能写3个印象哦!', 'info');
							return false;
						}



						$form = $this.closest('form'),
							data = {},
							eldata = $this.data();

						if ($form.length) {
							data = $form.find('input, select, textarea').serializeArray();
							eldata.type = 'POST';
						}

						$this.addClass('requesting');
						if ($this.hasClass('requesting')) {
							$.ajax({
									url: $form.attr('action'),
									data: data,
									type: eldata.type, // || 'GET',
									dataType: 'json'
								})
								.done(function(result, that) {
									if (result.status) {
										$.jqModal.tip(result.info, 'success');

										$.cookie('yinxiang-num', yxNum * 1 + 1);

										$form[0].reset();

										_self.ajax();
									} else {
										$.jqModal.tip(result.info, 'info');
									}

								})
								.always(function() {
									$this.removeClass('requesting');
									$this.html($this.data('title'));
								})
						}

						e.stopPropagation();
						return false;



					}) 

		},
		ajax:function(){
			var _self = this;
			var _opt = _self.opt;
			var $list = $(_opt.list);

			$.ajax({
					url: _opt.url,
					type: 'GET',
					dataType: 'html',
					data: {

					}
				})
				.done(function(result) {
					$list.html(result);
				})
				.fail(function() {

					$.jqModal.tip('操作失败!','info');
				})
				.always(function() {
					// console.log("complete");
				});
		}

	});
});
