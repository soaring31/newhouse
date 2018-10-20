/**
 * author : 08cms
 * date   : 2017-2-14
 * name   : appajax
 * modify : 2017-2-14
 */
/**
 * @module imageUpload
 * @description html5+ 原生图片上传 
 * @param {Object} cfg 基础配置参数
 * @example 调用
 * ```html
 * <div data-upfile="1" data-cfg="{imgBox: '.img-list', bundle: '/newhouse/web/', temp: '#img_temp', name: 'name'}">上传</div>
 * ```
 * ``` js
 * seajs.use(['appimage'], function (appimage) {
 *     appimage.init();
 * })
 * ```
 * 默认参数说明
 * @param {String} cfg.imgBox=.img-list 图片预览区域
 * @param {String} cfg.bundle=/newhouse/web/ 图片路径补全
 * @param {String} cfg.temp=#img_temp 预览图片渲染模板
 * @param {String} cfg.name=name 字段名称
 * @param {Boolean} cfg.multiple 是否可以多选
 * @param {String} cfg.addType=append 预览图片显示方式，单选时直接覆盖，多选时可慢慢往后面加
 */
define(['utils', 'template'], function(require, exports, module) {
	var utils = require('utils');
	var tpl = require('template');

	function upload(opt) {
		return new upload.prototype.init(opt);
	};

	upload.prototype = {
		init: function (cfg) {
			var _this = this;
			var _cfg = _this.cfg = $.extend(true, {}, {
				isMultiple: false,
				addType: 'append'
			}, cfg);
			// 扩展API加载完毕后调用onPlusReady回调函数 
			document.addEventListener( "plusready", onPlusReady, false );
			// 扩展API加载完毕，现在可以正常调用扩展API 
			function onPlusReady() {
			}

			var buttons1 = [
                {
                    text: '拍照上传',
                    onClick: function() {
                        _this.getCamera();
                    }
                },
                {
                    text: '相册选择',
                    onClick: function() {
                        _this.getGallery()
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
		},
		/*
		 * @method getCamera 照相机获取
		 */
		getCamera: function () {
			var _this = this;
			var camera = plus.camera.getCamera();

			camera.captureImage( function (path) {

				_this.appendFile(path);
				// 保存到相册
				_this.saveGallery(path);
			}, function (error) {

				$.alert('读取拍照文件出错:' + error.massage)
			}, {  
                index: 1  
            });
		},
		/*
		 * @method saveGallery 保存相片到相册中
		 * @param {String} path 要保存文件的路径， 每次只能保存一张图片
		 */
		saveGallery: function (path) {
			plus.gallery.save(path, function () {

				console.log('图片保存成功!');
			}, function () {

				console.log('图片保存失败!');
			})
		},
		/*
		 * @method getGallery 相册中选择
		 * @param {Object} galleryOpt 文件选择条件  filter: 文件过滤类型 可选值'video''image''none', multiple:是否可以多选
		 * @param {String} path 文件路径合集，单文件时为字符串，多文件时为数组
		 */
		getGallery: function () {
			var _this = this;

			var _cfg = _this.cfg;

			var gallery = plus.gallery;

			var galleryOpt = {
				filter: 'image',
				multiple: _cfg.isMultiple
			}

			gallery.pick(function (path) {

				_this.appendFile(path);

			}, function (error) {

				$.alert('错误信息:' + error.message)

			}, galleryOpt);
		},
		/*
		 * @method 增加照片
		 */
		appendFile: function (p) {
			var _this = this;

			if (p.files) {
				for (var i = 0, len = p.files.length; i < len; i++ ){
					_this.upload({
						path: p.files[i],
						key: 'photo[]' 
					});
				}
			} else {
				_this.upload({
					path: p,
					key: 'photo[]' 
				});
			};
		},
		/*
		 * @method 相册中选择
		 */
		upload: function (data) {
			var _this = this;

			var _cfg = _this.cfg;

			// 创建上传任务
	        var task = plus.uploader.createUpload(_this.getUrl(), {  
                method: "POST"  
            }, function (t, status) {
            	// 上传完成回调
            	var _data = utils.parseJSON(t.responseText);

            	if (status == 200) {

					if (_data.rolename !== undefined) {
            			_this.showImg(_data);
            		} else {
            			$.alert('游客禁止上传图片!')
            		}
            	} else {

            		console.log('上传失败')
        		};
            });

            // 增加参数
			task.addData('dataType', 'json');

			// 增加文件
			task.addFile(data.path, {key: data.key});

			// 监听上传状态
			task.addEventListener( "statechanged", function ( upload, status ) {
				var uploadedSize = Number(upload.uploadedSize);
				var totalSize = Number(upload.totalSize);
				var rate = parseInt(uploadedSize / totalSize * 100)

				if (upload.state == 0) {
					if (!_cfg.isMultiple) {
		        		_cfg.addType = 'html';
		        	};
					$(_cfg.imgBox)[_cfg.addType]('<div class="img_list col-33 loading-wrap"><i id="loading"></i><div class="progress"><div class="progress-bar"><span>0</span>%</div></div></div>')
				};
				if (status == 200) {

					$(_cfg.imgBox).find('.loading-wrap').remove();
				} else {

					if (upload.state != 0) {
						$(_cfg.imgBox).find('.loading-wrap').find('.progress-bar').css({width: rate + '%'}).find('span').html(rate);
					};
				};
			}, false );
			// 开始上传
			task.start();
		},
		/*
		 * @method 提交链接
		 */
		getUrl: function () {
			var url = BASE_URL + '/attachment/upload';
        	return url;
		},
		/*
		 * @method 显示照片
		 */
		showImg: function (data) {
			var _this = this;
			var _cfg = _this.cfg;

			var htmlData = {
				i: 1,
				bundle: BASE_URL + '/',
				msg: data.msg,
				name: _cfg.name
			};

			var tplHtml = tpl(_cfg.temp.slice(1), {
				data: htmlData
			});

			var html = '';
			// html拼接
        	html += '<div class="img_list col-33">';
        	html += '<div class="img_box">';
        	html += tplHtml;
        	html += '</div></div>';

			$(_cfg.imgBox).append(html);
		}
	}

	upload.prototype.init.prototype = upload.prototype;

	window.upload = upload;

	var uploadInt = {
		init: function () {
			$(document).off('.app').on('click.app', '[data-upfile]', function () {

				/*
				 * 执行选择图片
				 */ 
				
				var $this = $(this);

				var data = $this.data();

				var cfg = utils.parseJSON($this.data('cfg'));

				var _imgBox = $this.parent().siblings(cfg.imgBox);

				var _cfg = $.extend(true, {}, cfg, {
					isMultiple: data.multiple ? true : false,
					imgBox: _imgBox
				});

				upload.call(this, _cfg);

				/*
				 * 执行清空文件
				 */
				if (!utils.searchParams('_id')) {

					$this.closest('form').on('done', function(){

						$(this).find('.img-list').html('');

					})

				};
			})
			.on('click.app', '[data-delete]', function () {

				/*
				 * 执行删除图片
				 */
				
				var $this = $(this);

				$.alert('确定要删除吗？', function () {

					$this.closest('.img_list').remove();

				});

			});
		}
	}

	module.exports = uploadInt;
});
//# sourceMappingURL=http://localhost:8888/public/js/appimage.js.map
