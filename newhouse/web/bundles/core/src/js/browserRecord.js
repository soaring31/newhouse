/**
 * author : ling
 * date   : 2016-12-26
 * name   : browserRecord
 * modify : 2016-12-26
 */

/**
 * @module browserRecord
 * @description  记录浏览器的浏览记录
 * @param  {String} len       记录的条数
 * @param  {String} url       请求记录的地址
 * @param  {String} id        记录的id
 * @param  {String} models    记录的模型   
 * 
 * @调用
 * ```js
 * @说明 
 * save方法用在需要记录的页面上,如详情页 
 * browserRecord.save({
 *      id : '{{sale_detail.id|default('')}}',
 *      models: '{{sale_detail.models|default('')}}'
 * });
 *
 * @说明 
 * init方法用在需要获取显示记录的页面上 
 * browserRecord.init({
 * 		url: '{{("viewinter/record")|U}}'
 * });
 * ```  
 * 
 * @模板,模板跟init方法一起放在需要显示记录的页面上 (例子中模板的'data'数据在实际使用时要去掉单引号)
 *  <script id="record" type="text/html">
 *    	<h2 class="s-title">最近浏览过的房子</h2>
 *	    <div class="s-look">
 *	        <ul>
 *	        <% for(var i = 0; i < 'data.length'; i++){ %>
 *	            <li>
 *	                <i></i>
 *	                <a class="left" href="<%= 'data[i].url' %>" target="_blank" title="<%= 'data[i].name' %>"><%= 'data[i].name' %></a>
 *	                <span class="middle"><%= 'data[i].mj' %><b>m&sup2;</b></span>
 *	                <span class="right"><%= 'data[i].zj' %><b>万</b></span>
 *	            </li>
 *	            <% } %>    
 *	        </ul>
 *	    </div>
 *	</script>
 */
define(['template', 'utils', 'cookie'], function(require, exports, module) {
	var template = require('template');
	var utils = require('utils');

	var app = {
		init: function(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend(true, {}, {
				len: 10,
				url: null
			}, opt);

			var $record = $('[data-browser-recored="1"]');

			if ($record.length == 0) {
				return;
			}

			if ($record.eq(0).hasClass('inited')) {
				return;
			}

			$record.eq(0).addClass('inited');

			$record.each(function(i, el) {
				var $recordCur = $(this);
				var config = utils.parseJSON($recordCur.data('config'));
				if ($.cookie(config.models) == null) {
					return;
				}
				var models = config.models;
				var ids = $.cookie(config.models);
				var field = config.field;

				$.ajax({
						url: _opt.url,
						type: 'GET',
						dataType: 'json',
						data: {
							models  : models,
							id      : ids,
							field   : field

						},
						beforeSend: function() {

						}
					})
					.done(function(data) {
						var datas = [];
						$.each(data, function(i, el) {
							datas.push(el);
						});

						$recordCur.html(template(config.tpl.slice(1), {
							data: datas
						}));
					})
					.fail(function() {

					})
					.always(function() {

					});

			});

		},


		save: function(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend(true, {}, {
				id    : null,
				models: null,
				len   : 10
			}, opt);
			var cookieName = _opt.models;
			var id = _opt.id;
			var historyp = null;
			var opt = {
				expires: 60 * 60 * 24 * 30,
				path: '/'
			};

			if (id == null || id == '') {
				return;
			}
			if ($.cookie(cookieName) == null) {
				$.cookie(cookieName, id, opt);
			} else {
				var ids = $.cookie(cookieName).split(',');

				if ($.inArray(id, ids) == -1 && ids.length < _opt.len) {
					ids.push(id);

				}
				historyp = ids.join(',');
				$.cookie(cookieName, historyp, opt);

			}


		}
	};

	module.exports = app;
});