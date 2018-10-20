/**
 * author : ling
 * date   : 2016-11-29
 * name   : searchxq
 * modify : 2017-02-09
 */

/**
 * @module searchxq
 * @param  {String} xq  jq选择器 楼盘小区的id
 * @param  {String} aid jq选择器 楼盘的aid   
 * @return {String} url 请求地址          
 * @return {Array} autofill 点击小区自动替换的属性
 * @return {Array} notAutofillEdit 点击自动替换的字段，前提是此字段是空的情况下，如果此字段有值，则不替换
 *
 * @example
 * ```js
 * searchxq.init({
 *			url: 'majaxaction/xiaoqu'
 *		});
 *			
 */
define(['template', 'utils', 'jqmodal', 'autocomplete'], function (require, exports, module) {
	var template = require('template');
	var utils = require('utils');

	var app = {
		search: function search(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend(true, {}, {
				xq: '#lpmc',
				aid: '#aid',
				url: null,
				autofill: [],
				notAutofillEdit: ['name']
			}, opt);

			var $xq = $(_opt.xq);
			var $aid = $(_opt.aid);
			// $xq.autocomplete('dispose');
			$('div').remove('.autocomplete-suggestions');

			$xq.on('input propertychange', function () {
				$aid.val('');
			});

			$xq.autocomplete({
				ajaxSettings: {
					error: function error() {
						$.jqModal.tip('hide');
					}
				},
				serviceUrl: _opt.url,
				type: 'GET',
				dataType: "json",
				deferRequestBy: 300,
				triggerSelectOnValidInput: false,
				onSearchStart: function onSearchStart(params) {
					params.name = this.value;
				},
				formatResult: function formatResult(suggestion, currentValue) {
					if (suggestion.data.isTrue) {
						return suggestion.data.name + '&nbsp;&nbsp;地址:' + suggestion.data.address;
					} else {
						return suggestion.data.name;
					};
				},
				onSelect: function onSelect(suggestion) {
					$(this).trigger('onselect', suggestion);
					// 配置自动赋值字段
					var _form = this.form;

					var autofillIndex, autofillField;

					if (suggestion.data.isTrue == '1') {

						for (autofillIndex = 0, len = _opt.autofill.length; autofillIndex < len; autofillIndex++) {

							autofillField = _opt.autofill[autofillIndex];

							if (!$.inArray(autofillField, _opt.notAutofillEdit)) {

								if (_form[autofillField].value == '') {

									_form[autofillField].value = suggestion.data[autofillField];
								};
							} else {

								_form[autofillField].value = suggestion.data[autofillField];
							};
						}
					};
					delete autofillIndex;
					delete autofillField;
				}
			});
		},
		// 改变标题
		changeTitle: function changeTitle(params) {
			var _self = this;
			var _params = _self.params = $.extend(true, {}, {
				xq: '#lpmc',
				name: '#name',
				shi: '#shi',
				ting: '#ting',
				wei: '#wei',
				offon: null
			}, params);

			// console.log(_params.offon);
			if (_params.offon == '') {
				$(document).off('.hxing').on('change.hxing', _params.shi + ',' + _params.ting + ',' + _params.wei, function () {
					_self.clickEvent();
				});
			}
		},

		clickEvent: function clickEvent() {
			var _self = this;
			var _params = _self.params;
			var shi = $(_params.shi + ' option:selected').text() || '0室';
			var ting = $(_params.ting + ' option:selected').text() || '0厅';
			var wei = $(_params.wei + ' option:selected').text() || '0卫';
			var str = $(_params.xq).val() + ' ' + shi + ' ' + ting + ' ' + wei;
			$(_params.name).val(str);
		},

		// 自动计算面积
		division: function division(opt) {
			var _self = this;
			var opt = $.extend(true, {}, {
				divisor: '#zj',
				dividend: '#mj',
				result: '#dj'
			}, opt);

			var $divisor = $(opt.divisor),
			    $dividend = $(opt.dividend),
			    $result = $(opt.result);

			// 页面初始化时先算一次
			$result.val(division($divisor, $dividend));

			$divisor.on('input propertychange', function () {
				$result.val(division($divisor, $dividend));
			});

			$dividend.on('input propertychange', function () {
				$result.val(division($divisor, $dividend));
			});

			function division($divisor, $dividend) {
				var divisor = isNumber($divisor.val());
				var dividend = isNumber($dividend.val());
				if (dividend === 0) {
					return 0;
				} else {
					return Math.round(divisor * 10000 / dividend);
				}
			};

			function isNumber(num) {
				var num = $.trim(num);
				var re = /^[0-9]+.?[0-9]*$/;
				if (re.test(num)) {
					return num;
				} else {
					return 0;
				}
			};
		},

		// 关键字
		keywords: function keywords(args) {
			var _self = this;
			var _args = $.extend({}, {
				keyword: null
			}, args);

			$keywords = $(_args.keyword);

			$keywords.autocomplete('setOptions', {
				onSearchStart: function onSearchStart(params) {
					var arr = this.value.split(/,|，/);
					params.name = arr[arr.length - 1];
				}
			});

			var preval = '';
			$keywords.on('keydown', function () {
				var arr = $(this).val().trim().split(/,|，/);
				if (arr.length >= 2) {
					arr.pop();
				} else if (arr.length >= 5) {
					return;
				}
				preval = arr.join(',');
			});

			$keywords.on('onselect', function (event, arg) {
				if (arg.data.noChoose) {
					$(this).val(preval);
				} else {
					$(this).val(preval);
					var val = $(this).val().trim();
					// console.log(val);
					var keyword = arg.data.name;
					var valArr = val.split(/,|，/) || [];
					// console.log(valArr);
					// valArr.pop();
					var inArr = $.inArray(keyword, valArr);
					if (inArr == -1) {
						if (val == '') {
							val = keyword + ',';
						} else {
							val = valArr.join(',') + ',' + keyword + ',';
						}
					} else {
						val += ',';
					}

					// val = vals+ keyword+',';

					if (valArr.length < 5) {
						$(this).val(val);
						preval = val;
					}
				}
			});
		},

		formField: function formField(formRow) {
			var $select = $('select[data-formgroup0], select[data-formgroup1]').eq(0);
			var getData = $select.data();
			var formRow = formRow;
			var fieldData = {};
			var allfield = [];
			var reg = /^formgroup([0-9]+)$/;

			$.each(getData, function (i, value) {
				if (i.match(reg)) {
					var k = i.match(reg)[1];
					fieldData[k] = value;
					var arr = value.split(',');
					$.unique($.merge(allfield, arr));
				}
			});

			var selChange = function selChange(v) {
				var fields = fieldData[v].split(',');
				$.each(allfield, function (i, item) {
					$('#' + item).prop('disabled', true).closest(formRow).hide();
				});
				$.each(fields, function (k, val) {
					$('#' + val).prop('disabled', false).closest(formRow).show();
				});
			};

			$select.off().on('change', function () {
				selChange(this.value);
			});
			// 加载时先触发一次
			selChange($select.val());
		},

		// 楼盘内添加自动加载楼盘标题
		addTitle: function addTitle(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend(true, {}, {
				xq: '#lpmc',
				url: null
			}, opt);
			var $xq = $(_opt.xq);
			// 隐藏楼盘名称字段
			$xq.closest('.form-row').hide();

			if (_opt.url) {
				$.ajax({
					url: _opt.url,
					type: 'GET',
					dataType: 'json',
					data: {
						aid: _opt.aid
					}
				}).done(function (data) {
					var _data = data.suggestions[0].data;
					$xq.val(_data.name);
				}).fail(function () {
					console.log("error");
				}).always(function () {});
			};
		}

	};

	module.exports = app;
});
//# sourceMappingURL=http://localhost:8888/js/searchxq.js.map
