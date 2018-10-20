/**
 * @module mobileUpfile
 * @description  移动端上传图片
 * @param  {Object} fileInput                上传图片的按钮选择器
 * @param  {Object} obj                      上传按钮的对象，当fileInput没有时，用这个
 * @param  {String} maxSize                  图片的最大体积
 * @param  {String} url                      上传图片的地址
 * @param  {String} bundle                   图片所属的bundle
 * @param  {Object} imgBox                   图片外框选择器
 * @param  {Object} temp                     图片的模板选择器
 * @param  {String} name                     图片的名称
 *
 * @example
 * ```html
 * {% raw %}
 *  <div class="file-input col-33" data-upfile="1" data-cfg="{url: '{{('attachment/upFile')|U({}, true)}}', imgBox: '.img-list', bundle: '/newhouse/web/', temp: '#img_temp', name: ''" data-multiple="1">
 *        <input type="file"  multiple="multiple" id="{{o.id|default('')}}" name="{{o.
 *        name|default('')}}[]">
 *  </div>
 *  {% endraw %}
 * ```
 */

define(['utils', 'template'], function(require, exports, module) {
	var temp = require('template');
	var utils = require('utils');

	function upload(opt) {
		return new upload.prototype.init(opt);
	};

	upload.prototype = {
		init: function(opt) {
			var _self = this;
			var _opt = _self.opt = $.extend({}, {
				fileInput: null,
				obj: null,
				maxSize: 1000000, //1M
				url: '',
				bundle: '',
				imgBox: null,
				temp: null,
				name: ''
			}, opt);

			_self.filesArr = [];

			_self.index = 1;
			_self.oldIndex = 1;

			_self.$this = _opt.fileInput ? $(_opt.fileInput).eq(0) : _opt.obj;

			_self.isMultiple = _self.$this.attr('multiple');

			_self.imgBox = _self.$this.parents('.row').siblings(_opt.imgBox);

			var $imgList = _self.imgBox.children('.img_list');

			if($imgList.length > 0){
				_self.index = _self.oldIndex = $imgList.length + 1;
			}

			_self.$this.off('.upfile').on('change.upfile', _self.getFiles.bind(this));

			$(document).off('.upload').on('click.upload', '[data-delete]', function(){
				$(this).parents('.img_list').remove();
			});

		},

		// 获取文件的信息
		getFiles: function(e) {
			var _self = this;
			var files = e.target.files || e.dataTranfer.files;
			_self.filesArr = _self.filter(files);
			_self.eventHundle();

		},

		// 绘制图片
		viewFiles: function(option, i) {
			var _self = this;
			var _opt = _self.opt;

			var data = {
				i: i,
				bundle: _opt.bundle,
				msg: option.msg,
				name: _opt.name
			};

			var img = temp(_opt.temp.slice(1), {
				data: data
			});


			if (_self.isMultiple) {
				_self.imgBox.find('#upload_list_' + i).children('.img_box').html(img);
			} else {
				_self.imgBox.find('#upload_list').children('.img_box').html(img);
			}


			// $.each(_self.filesArr, function(i, file) {
			// 	if(file){
			// 		var reader = new FileReader();
			// 		reader.onload = function(e){
			// 	var img = '<img id="upload_img_'+i+'" src="'+e.target.result+'" class="uploadImg"/><span>'+file.name+'</span><a href="javascript:;" data-delete="'+i+'">x</a>';
			// 	$('#upload_list_'+i).html(img).on('click', '[data-delete]', function(){
			// 		var index = $(this).data('delete');
			// 		$('#upload_list_'+index).remove();
			// 		_self.filesArr.splice(index,1);
			// 	});;
			// };

			// 		reader.readAsDataURL(file);

			// 	}
			// });


		},

		eventHundle: function() {
			var _self = this;
			var _opt = _self.opt;
			// console.log($(obj));
			$.each(_self.filesArr, function(index, file) {
				var formData = new FormData();
				formData.append('photo[]', file);
				formData.append('dataType', 'json');

				$.ajax({
					url: _opt.url,
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						_self.multiple();

					},
					success: function(data) {
						var data = utils.parseJSON(data);

						if (data.status) {
							_self.viewFiles(data, _self.oldIndex);
						} else {
							$.alert(data.info);
						}
					},
					error: function() {},
					complete: function() {
						_self.oldIndex++;
						_self.filesArr = [];
					}
				});


			});
		},
		// 多图上传
		multiple: function() {
			var _self = this;
			var _opt = _self.opt;
			if (_self.isMultiple) {

				var $list = $('<div id="upload_list_' + _self.index + '" class="img_list col-33"><div class="img_box"><div id="loading"></div></div></div>');
				_self.index++;
				_self.imgBox.append($list);
			} else {
				var list = '<div id="upload_list" class="img_list col-33"><div class="img_box"><div id="loading"></div></div></div>';
				_self.imgBox.html(list);
			}
		},
		// 对图片进行过滤
		filter: function(files) {
			var _self = this;
			var _opt = _self.opt;
			var arr = [];
			$.each(files, function(index, el) {
				if (el.type.indexOf('image') == 0) {
					if (el.size >= _opt.maxSize) {
						alert('这张"' + el.name + '"图片过大,应小于' + _self.bitName());
					} else {
						arr.push(el);
					}
				} else {
					alert('文件"' + el.name + '"不是图片');
				}
			});
			return arr;
		},

		bitName: function() {
			var _self = this;
			var _opt = _self.opt;
			var name = '';
			if (_opt.maxSize >= 1000000) {
				name = parseInt(_opt.maxSize / 1000000) + 'M';
			} else if (_opt.maxSize >= 1000) {
				name = parseInt(_opt.maxSize / 1000) + 'k';
			} else {
				name = _opt.maxSize + 'bit';
			}
			return name;
		},
	};

	upload.prototype.init.prototype = upload.prototype;

	window.upload = upload;


	var upfile = {
		uploadInit: function() {
			$('[data-upfile="1"]').each(function(i, e) {
				var $this = $(this);
				var cfg = utils.parseJSON($this.data('cfg'));
				var opt = $.extend({}, {
					obj: $this.children('input[type="file"]')
				}, cfg);

				upload(opt);

				$('[data-upfile="1"]').closest('form').on('done', function(){
					$(this).find('.img-list').html('');
				})
			});
		}
	};

	upfile.uploadInit();

	module.exports = upfile;
});