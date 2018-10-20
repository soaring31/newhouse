/**
 * author : ling
 * date   : 2016-11-21
 * name   : vote
 * modify : 2016-12-19
 */

/**
 * @module vote
 * @description  投票（点赞）
 * @param {String} saveUrl 点评提交的地址：控制器+方法()
 * @param {Object} data-vote 投票的参数 包括 models(模型),id,field(字段)
 * @param {String} data-vote-num 投票/点赞 的个数
 * @param {String} data-multiple 指定多选时候的选择器
 *
 * @说明
 * 以上参数中，saveUrl是在js中调用的,其他都是在html上调用的
 * 
 * @example 调用
 * ```js
 *	vote.init({
 *           saveUrl: 'viewinter/vote'
 *         });                
 * ```
 * @example
 * ```html
 * <!-- 单个投票（点赞） -->
 *<span  data-vote="{models: 'news', id: 11, field: 'vote'}"><i data-vote-num="0"></i></span>
 *<!-- 多个投票 -->
 *<ul class="vote-wrap">
 *	<li data-vote-item="1">选项1<i data-vote-num="1">11</i></li>
 * 	<li data-vote-item="2">选项2<i data-vote-num="1">2</i></li>
 *  <li data-vote-item="3">选项3<i data-vote-num="1">4</i></li>
 *  <li data-vote-item="4">选项4<i data-vote-num="1">3</i></li>
 *  <li data-vote-item="5">选项5<i data-vote-num="1">2</i></li>
 *</ul>
 *<button id="votes-btn"  data-vote="{models: 'news', field: 'vote'}" data-multiple=".vote-wrap"></button>
 *
 * ```
 * 
 * 
 */

define(['jqmodal', 'utils', 'cookie'], function(require, exports, module) {
	var utils = require('utils');
	(function(out) {
		module.exports = out;
	})({
		init: function(opt) {
			var _self = this,
				_opt = _self.opt = $.extend(true, {}, {
					saveUrl: null,
					type: 'GET',
					datatype: 'json',
					data: {}
				}, opt);			

			// 提交
			$(document).off('.vote').on('click.vote', '[data-vote]', function() {
				var $vote = $(this);
				var data = $vote.data();
				var opt = utils.parseJSON(data.vote);
				var cid = opt.id;
				var ids = [];
				var multiple = data.multiple;
				

				if (multiple) {
					$(multiple).find('[data-vote-item]').each(function(i, elem) {
						if ($(this).find('.checked').length) {
							ids.push($(this).data('vote-item'));
						}
					});
					cid = ids[0];
					opt.id = ids.join(',');
					
					if ($('.checked').size() <= 0) {
						$.jqModal.tip('亲，您还有没有填哦!', 'info');
						return;
					}
				}

				if ($.cookie('comment-vote-' + cid)) {
					$.jqModal.tip('亲，已经投过了！', 'info');
					return false;
				};

				if (!$vote.hasClass('requesting')) {
					$vote.addClass('requesting');

					var data = $.extend({},  {
							id: opt.id,
							models: opt.models,
							field: opt.field
					}, _opt.data);
					

					$.ajax({
						url: _opt.saveUrl,
						type: _opt.type,
						dataType: _opt.dataType,
						data: data
					})
					.done(function(result) {
						result = utils.parseJSON(result);
						if (result.status) {
							$.jqModal.tip('亲，感谢您的参与!', 'info');
							$.cookie('comment-vote-' + cid, 1);
							$('input[type="radio"]').removeClass('checked');
							$vote.trigger('done.vote', [result]);
						} else {
							$.jqModal.tip(result.info, 'info');
						}

					})
					.fail(function(xhr,status,error){
						// console.log(error);
					})
					.always(function() {
						$vote.removeClass('requesting');
					});

				}

				// 完成后动作接口
				$vote.on('done.vote ', function(event, result) {
					if (multiple) {
						// 投票
						return ;
					}else {
						// 点赞
						var voteNum = $(this).find('[data-vote-num]').data('vote-num');
						$(this).find('[data-vote-num]').text(voteNum+1);
					}
				});
			})
		}
	});
});


//# sourceMappingURL=vote.js.map
