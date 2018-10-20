/**
 * Created with JetBrains WebStorm.
 * User: Peace
 * Time: 2016年6月7日
 */
define(function(require, exports, module) {
	var self;
	var core = require("core");
	require("croppercss");
	var $window = $(window);
	var $document = $(document);
	var location = window.location;
	var navigator = window.navigator;
	var ArrayBuffer = window.ArrayBuffer;
	var Uint8Array = window.Uint8Array;
	var DataView = window.DataView;
	var btoa = window.btoa;

	// Constants
	var NAMESPACE = 'cropper';

	// Classes
	var CLASS_MODAL = 'cropper-modal';
	var CLASS_HIDE = 'cropper-hide';
	var CLASS_HIDDEN = 'cropper-hidden';
	var CLASS_INVISIBLE = 'cropper-invisible';
	var CLASS_MOVE = 'cropper-move';
	var CLASS_CROP = 'cropper-crop';
	var CLASS_DISABLED = 'cropper-disabled';
	var CLASS_BG = 'cropper-bg';

	// Events
	var EVENT_MOUSE_DOWN = 'mousedown touchstart pointerdown MSPointerDown';
	var EVENT_MOUSE_MOVE = 'mousemove touchmove pointermove MSPointerMove';
	var EVENT_MOUSE_UP = 'mouseup touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel';
	var EVENT_WHEEL = 'wheel mousewheel DOMMouseScroll';
	var EVENT_DBLCLICK = 'dblclick';
	var EVENT_LOAD = 'load.' + NAMESPACE;
	var EVENT_ERROR = 'error.' + NAMESPACE;
	var EVENT_RESIZE = 'resize.' + NAMESPACE; // Bind to window with namespace
	var EVENT_BUILD = 'build.' + NAMESPACE;
	var EVENT_BUILT = 'built.' + NAMESPACE;
	var EVENT_CROP_START = 'cropstart.' + NAMESPACE;
	var EVENT_CROP_MOVE = 'cropmove.' + NAMESPACE;
	var EVENT_CROP_END = 'cropend.' + NAMESPACE;
	var EVENT_CROP = 'crop.' + NAMESPACE;
	var EVENT_ZOOM = 'zoom.' + NAMESPACE;

	// RegExps
	var REGEXP_ACTIONS = /e|w|s|n|se|sw|ne|nw|all|crop|move|zoom/;
	var REGEXP_DATA_URL = /^data\:/;
	var REGEXP_DATA_URL_HEAD = /^data\:([^\;]+)\;base64,/;
	var REGEXP_DATA_URL_JPEG = /^data\:image\/jpeg.*;base64,/;

	// Data keys
	var DATA_PREVIEW = 'preview';
	var DATA_ACTION = 'action';

	// Actions
	var ACTION_EAST = 'e';
	var ACTION_WEST = 'w';
	var ACTION_SOUTH = 's';
	var ACTION_NORTH = 'n';
	var ACTION_SOUTH_EAST = 'se';
	var ACTION_SOUTH_WEST = 'sw';
	var ACTION_NORTH_EAST = 'ne';
	var ACTION_NORTH_WEST = 'nw';
	var ACTION_ALL = 'all';
	var ACTION_CROP = 'crop';
	var ACTION_MOVE = 'move';
	var ACTION_ZOOM = 'zoom';
	var ACTION_NONE = 'none';

	// Supports
	var SUPPORT_CANVAS = $.isFunction($('<canvas>')[0].getContext);
	var IS_SAFARI = navigator && /safari/i.test(navigator.userAgent)
			&& /apple computer/i.test(navigator.vendor);

	// Maths
	var num = Number;
	var min = Math.min;
	var max = Math.max;
	var abs = Math.abs;
	var sin = Math.sin;
	var cos = Math.cos;
	var sqrt = Math.sqrt;
	var round = Math.round;
	var floor = Math.floor;

	var TEMPLATE = ('<div class="cropper-container">'
			+ '<div class="cropper-wrap-box">'
			+ '<div class="cropper-canvas"></div>' + '</div>'
			+ '<div class="cropper-drag-box"></div>'
			+ '<div class="cropper-crop-box">'
			+ '<span class="cropper-view-box"></span>'
			+ '<span class="cropper-dashed dashed-h"></span>'
			+ '<span class="cropper-dashed dashed-v"></span>'
			+ '<span class="cropper-center"></span>'
			+ '<span class="cropper-face"></span>'
			+ '<span class="cropper-line line-e" data-action="e"></span>'
			+ '<span class="cropper-line line-n" data-action="n"></span>'
			+ '<span class="cropper-line line-w" data-action="w"></span>'
			+ '<span class="cropper-line line-s" data-action="s"></span>'
			+ '<span class="cropper-point point-e" data-action="e"></span>'
			+ '<span class="cropper-point point-n" data-action="n"></span>'
			+ '<span class="cropper-point point-w" data-action="w"></span>'
			+ '<span class="cropper-point point-s" data-action="s"></span>'
			+ '<span class="cropper-point point-ne" data-action="ne"></span>'
			+ '<span class="cropper-point point-nw" data-action="nw"></span>'
			+ '<span class="cropper-point point-sw" data-action="sw"></span>'
			+ '<span class="cropper-point point-se" data-action="se"></span>'
			+ '</div>' + '</div>');

	// Utilities
	var fromCharCode = String.fromCharCode;
	var DEFAULTS = {

		// Define the view mode of the cropper
		viewMode : 0, // 0, 1, 2, 3

		// Define the dragging mode of the cropper
		dragMode : 'crop', // 'crop', 'move' or 'none'

		// Define the aspect ratio of the crop box
		aspectRatio : NaN,

		// An object with the previous cropping result data
		data : null,

		// A jQuery selector for adding extra containers to preview
		preview : '',

		// Re-render the cropper when resize the window
		responsive : true,

		// Restore the cropped area after resize the window
		restore : true,

		// Check if the current image is a cross-origin image
		checkCrossOrigin : true,

		// Check the current image's Exif Orientation information
		checkOrientation : true,

		// Show the black modal
		modal : true,

		// Show the dashed lines for guiding
		guides : true,

		// Show the center indicator for guiding
		center : true,

		// Show the white modal to highlight the crop box
		highlight : true,

		// Show the grid background
		background : true,

		// Enable to crop the image automatically when initialize
		autoCrop : true,

		// Define the percentage of automatic cropping area when initializes
		autoCropArea : 0.8,

		// Enable to move the image
		movable : true,

		// Enable to rotate the image
		rotatable : true,

		// Enable to scale the image
		scalable : true,

		// Enable to zoom the image
		zoomable : true,

		// Enable to zoom the image by dragging touch
		zoomOnTouch : true,

		// Enable to zoom the image by wheeling mouse
		zoomOnWheel : true,

		// Define zoom ratio when zoom the image by wheeling mouse
		wheelZoomRatio : 0.1,

		// Enable to move the crop box
		cropBoxMovable : true,

		// Enable to resize the crop box
		cropBoxResizable : true,

		// Toggle drag mode between "crop" and "move" when click twice on the cropper
		toggleDragModeOnDblclick : true,

		// Size limitation
		minCanvasWidth : 0,
		minCanvasHeight : 0,
		minCropBoxWidth : 0,
		minCropBoxHeight : 0,
		minContainerWidth : 200,
		minContainerHeight : 100,

		// Shortcuts of events
		build : null,
		built : null,
		cropstart : null,
		cropmove : null,
		cropend : null,
		crop : null,
		zoom : null
	};
	(function(out) {
		module.exports = out;
	})
			({
				/**
				 * 调用函数
				 * @page 函数名， par参数名
				 * @return function
				 */
				init : function(page, par) {
					//初始化加載的腳本
					try {
						self = this;
						self[page](par);
					} catch (e) {
						core.generalTip.ontextTip("image JS脚本错误！[" + e + "]",
								"error");
						console.error(e);
					}
				}
				//入口函数(选baidumap弹窗)
				,
				main : function() {
					var self = this;
					$(".upimages").off().on("click", function() {
						if ((url = $(this).attr('data')) || (url = $(this).attr('data-url')))
						{
							var param = $(this).attr("upparam");
							var multiple =  self.multiple = Number($(this).attr("mtip"));
							var display = multiple==0?"style='display:none;'":"";

							if (!param)
								return false;
							var param = param.split(",");
							var title = "上传图片";

							if (param[1]) {
								switch (param[1]) {
									case 'files':
										title = "文件上传";
										break;
									case 'music':
										title = "音乐上传";
										break;
									case 'video':
										title = "视频上传";
										break;
									case 'flv':
										title = "flash上传";
										break;
									default:
										break;
								}
							}
							//支持框架(ifram)
							$ = typeof parent.document == "undefined" ? $ : top.$;
							$.jqModal.modal({
								head : title,
								animate: null,
								content : url,
								type: 'iframe',
								css: {
									width : 600,
									height : 400
								}
							}).on('hideFun', function() {
								cl
								var imgData = $(this).data('returnValue');
								if (imgData) {
									BASE_URL = BASE_URL.replace(/app_dev.php/,"");
									$("#"+ param[0]).val(imgData);
									$("#"+ param[0]+ "_src").attr("src", imgData);

									var thisvalue = imgData.split(',');


									$.each(thisvalue, function(key, item) {
										if (key == 0&& $("#"+ param[0]+ "list ul li").length == 0)
											isfenmian = "<div class='isfenmian'></div>";
										else
											isfenmian = "<div class='isfenmian hide'></div>";

										var textdiv = "<div class='progressContainer'>"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "X[]' id='"
												+ param[0]
												+ "X' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "Y[]' id='"
												+ param[0]
												+ "Y' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "W[]' id='"
												+ param[0]
												+ "W' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "H[]' id='"
												+ param[0]
												+ "H' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "Rotate[]' id='"
												+ param[0]
												+ "Rotate' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "ScaleX[]' id='"
												+ param[0]
												+ "ScaleX' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "ScaleY[]' id='"
												+ param[0]
												+ "ScaleY' />"
												+ "<input type='hidden' name='"
												+ param[0]
												+ "source[]' value='"
												+ item
												+ "' />"
												+ "<a style='visibility: visible;' href='javascript: void(0);' class='progressCancel'></a>"
												+ "<span class='item_box'>"
												+ "<img src='"
												+ BASE_URL
												+ item.replace(/^\//,"")
												+ "' height='60' width='80' />"
												+ "</span>"
												+ "<div style='display: none;' class='progressBarStatus'></div>"
												+ isfenmian
												+ "<div style='display: none;' class='fm_bg'></div>"
												+ "<div style='display: none;' class='fengmian'>"
												+ "<div class='set'>"
												+ "<a href='javascript: void(0);' title='左移' class='scroll_l'></a>"
												+ "<a href='javascript: void(0);' class='set_fm'></a>"
												+ "<a href='javascript: void(0);' title='右移' class='scroll_r'></a>"
												+ "</div>"
												+ "<div class='cut'>"
												// + "<a href='javascript: void(0);' title='裁剪' data-url='"
												// + BASE_URL
												// + item.replace(/^\//,"")
												// + "' data-name='"
												// + param[0]
												// + "' class='cut_fm'></a>"
												+ "<a href='javascript: void(0);' title='查看原图' data-url='"
												+ BASE_URL
												+ item.replace(/^\//,"")
												+ "' class='viewimages cut_fm2'></a>"
												+ "</div>"
												+ "</div>"
												+ "<div class='item_input' "+display+">"
												+ "<i class='tline hc'></i>"
												+ "<textarea placeholder='请输入描述' style='resize: none;' class='c_ccc' name='"
												+ param[0]
												+ "desc[]'></textarea>"
												+ "</div>"
												+ "<div class='item_link' "+display+">"
												+ "<i class='t line hc'></i>"
												+ "<textarea style='resize: none;' class='c_ccc' name='"
												+ param[0]
												+ "link[]'></textarea>"
												+ "</div>"
												+ "</div>";
										// 单选需要删除原来的图片
										if(multiple==0) {
											$("#"+ param[0]+ "list ul").empty();
										}
										// 多选要补上当前字段的值
										if(multiple==1) {
											textdiv = "<input type='hidden' name='" + param[0] + "[]' value='" + item + "' />" + textdiv;
										}
										// 不管单选还是多选将返回的数据载入列表
										$("#"+ param[0]+ "list ul").append("<li>"+ textdiv+ "</li>");

										// 放出图片文本框，并赋值
										if(multiple==0) {
											$('input[name="'+ param[0] + '[]"]').val(item);
										}
									});
									// 更新图片的数量
									self.updateImgNum($('input[name="'+ param[0] + '[]"]')[0]);
								}

								//初始化图片功能
								self.initImgFun();
							});
						}
						return false;
					});

					//初始化图片功能
					self.initImgFun();
				}
				//初始化图片功能
				,
				initImgFun : function() {
					$(".progressContainer").mouseover(function() {
						$(this).find(".fm_bg").show();
						$(this).find(".fengmian").show();
					}).mouseout(function() {
						$(this).find(".fm_bg").hide();
						$(this).find(".fengmian").hide();
					});

					//左移
					$(".progressContainer .scroll_l").off().on(
							"click",
							function() {
								$(this).parents('li:first').insertBefore(
										$(this).parents('li:first').prev());
								$(this).parents('ul:first').find(".isfenmian")
										.hide();
								$(this).parents('ul:first').find(
										"li:first-child .isfenmian").show();
							});

					//右移
					$(".progressContainer .scroll_r").off().on(
							"click",
							function() {
								$(this).parents('li:first').insertAfter(
										$(this).parents('li:first').next());
								$(this).parents('ul:first').find(".isfenmian")
										.hide();
								$(this).parents('ul:first').find(
										"li:first-child .isfenmian").show();
							});

					//设为封面
					$(".progressContainer .set_fm").off().on(
							"click",
							function() {
								$(this).parents('li:first').insertBefore(
										$(this).parents('ul:first').find(
												'li:first'));
								$(this).parents('ul:first').find(".isfenmian")
										.hide();
								$(this).parents('ul:first').find(
										"li:first-child .isfenmian").show();
							});

					//栽剪图
					$(".progressContainer .cut_fm").off().on(
							"click",
							function() {
								var name = $(this).attr("data-name");
								var _width = $("[name='" + name + "width']")
										.val() / 10;
								var _height = $("[name='" + name + "height']")
										.val() / 10;
								var _this = $(this).parents('li:first');
								var dataX = _this.find("[name='" + name
										+ "X[]']");
								var dataY = _this.find("[name='" + name
										+ "Y[]']");
								var dataHeight = _this.find("[name='" + name
										+ "H[]']");
								var dataWidth = _this.find("[name='" + name
										+ "W[]']");
								var dataRotate = _this.find("[name='" + name
										+ "Rotate[]']");
								var dataScaleX = _this.find("[name='" + name
										+ "ScaleX[]']");
								var dataScaleY = _this.find("[name='" + name
										+ "ScaleY[]']");
								if ((url = $(this).attr('data'))
										|| (url = $(this).attr('data-url'))) {
									$(".imgcontainer").html(
											"<img src=" + url + " />");
									self.croppers($('.imgcontainer > img'), {
										'aspectRatio' : Math.ceil(_width / 10)
												/ Math.ceil(_height / 10),
										'crop' : function(e) {
											// 出来裁切后的图片数据.
											dataX.val(Math.round(e.x));
											dataY.val(Math.round(e.y));
											dataHeight
													.val(Math.round(e.height));
											dataWidth.val(Math.round(e.width));
											dataRotate.val(e.rotate);
											dataScaleX.val(e.scaleX);
											dataScaleY.val(e.scaleY);
										}
									});
								}
							});

					//删除动作
					self.delUploadImg();

					//查看原图
					self.initViewImg();
					//input 地址改变
					self.changeUrl();
				},
				changeUrl: function() {
					$('.img-url').off('.change').on('input.change propertychange.change', function() {
						var thisName = $(this).attr('name').replace('[]', '');
						var $item = $('#' + thisName + 'list');
						var imgUrl = this.value;
						if (imgUrl) {
							// 补全地址
							if (!/http/.test(imgUrl)) {
								imgUrl = BASE_URL.replace(/app_dev.php/,"") + '/' + imgUrl;
							};
							$item.find('.item_box img').attr('src', imgUrl);
							$item.find('.cut_fm2').attr('data-url', imgUrl);
						};
					})
				},
				initViewImg : function() {
					// 缩放图片, 防止图片过大时弹窗错位
					var getImgSize = function(img, iW, iH) {
			            var nH, nW
			            	,nW = _w = img.width
			            	,nH = _H = img.height;
			            if (_w > 0 && _H > 0) {
			                if (_w / _H >= iW / iH && _w > iW) {
			                    nW = iW;
			                    nH = parseInt(_H * iW / _w);
			                }else if (_H > iH) {
			                    nH = iH;
			                    nW = parseInt(_w * iH / _H);
			                }
			            }
			            return [nW,nH];
			        }
			        var winW = $(window).width();
			        var winH = $(window).height();

					$(".viewimages").off().on("click",function() {
						var url = $(this).attr('data-url');
						var param = $(this).attr("viewparam");

						BASE_URL = BASE_URL.replace(/app_dev.php/, "");

						if (param && $('#' + param).val())
							url = $('#' + param).val();

						if (!url) {
							core.generalTip.ontextTip('没有图片', "error");
							return false;
						}
						var _img = new Image();
						// 图片不能有缓存，否则预览时看不到新加的水印
						var imgUrl = '/' + url.replace(/^\//, "") + '?t=' + parseInt(new Date().getTime()/1000);
						// 图片加载完成再弹出，否则会错位
						_img.onload=function () {
							// 弹窗的图片大小限制在w<1200和h<600
							var aImgWH = getImgSize(_img, winW * .9, winH * .8);
							var html = '<img style="width: '+ aImgWH[0] +'px; height: '+ aImgWH[1] +'px" src="'+ imgUrl +'" />';
							// 调用弹窗
			            	$.jqModal.lay(html);
						}

						_img.src = imgUrl;
						
						return false;
					});
				}

				/**
				 * 删除图片
				 */
				,
				delUploadImg : function() {
					var self = this;
					$(".progressCancel").off().on("click", function() {
						var _this = $(this).parent().parents('ul:first');
						// 清空文本框地址
						$(this).closest('.form-cell').find('.img-url').val('');
						$(this).closest('[name="thumbsource[]"]').val('');
						if (self.multiple = 0) {
							$(this).parents('li:first').hide();
						} else {
							$(this).parents('li:first').remove();
						}

						_this.find("li:first-child .isfenmian").show();
						self.updateImgNum(_this[0]);
					});
				}
				,
				// 更新图片数量用于js表单验证
				updateImgNum: function(ele) {
					var $formCell = $(ele).closest('.form-cell');
					try {
						$formCell.find('input[name="input_num"]').val(function() {
							return $formCell.find('.imgs-wrap li').length;
						}).valid();
					} catch (err) {
						console.warn(err);
					}
				},
				croppers : function(element, options) {
					self.$element = $(element);
					self.options = $.extend({}, DEFAULTS, $
							.isPlainObject(options)
							&& options);
					self.isLoaded = false;
					self.isBuilt = false;
					self.isCompleted = false;
					self.isRotated = false;
					self.isCropped = false;
					self.isDisabled = false;
					self.isReplaced = false;
					self.isLimited = false;
					self.wheeling = false;
					self.isImg = false;
					self.originalUrl = '';
					self.canvas = null;
					self.cropBox = null;
					self.croppersInit();
				},
				croppersInit : function() {
//					console.log('croppersInit');

					var $this = self.$element;
					var url;

					if ($this.is('img')) {
						self.isImg = true;

						// Should use `$.fn.attr` here. e.g.: "img/picture.jpg"
						self.originalUrl = url = $this.attr('src');

						// Stop when it's a blank image
						if (!url)
							return;

						// Should use `$.fn.prop` here. e.g.: "http://example.com/img/picture.jpg"
						url = $this.prop('src');
					} else if ($this.is('canvas') && SUPPORT_CANVAS) {
						url = $this[0].toDataURL();
					}

					self.load(url);
				},
				// A shortcut for triggering custom events
				trigger : function(type, data) {
					var e = $.Event(type, data);

					self.$element.trigger(e);

					return e;
				},
				load : function(url) {
					var options = self.options;
					var $this = self.$element;
					var read;
					var xhr;

					if (!url)
						return;

					// Trigger build event first
					$this.one(EVENT_BUILD, options.build);

					if (self.trigger(EVENT_BUILD).isDefaultPrevented())
						return;

					self.url = url;
					self.image = {};

					if (!options.checkOrientation || !ArrayBuffer) {
						return self.clone();
					}

					read = $.proxy(self.read, self);

					// XMLHttpRequest disallows to open a Data URL in some browsers like IE11 and Safari
					if (REGEXP_DATA_URL.test(url)) {
						return REGEXP_DATA_URL_JPEG.test(url) ? read(self.dataURLToArrayBuffer(url))
								: self.clone();
					}

					xhr = new XMLHttpRequest();

					xhr.onerror = xhr.onabort = $.proxy(function() {
						self.clone();
					}, self);

					xhr.onload = function() {
						self.read(self.response);
					};

					if (options.checkCrossOrigin && self.isCrossOriginURL(url)
							&& $this.prop('crossOrigin')) {
						url = self.addTimestamp(url);
					}

					xhr.open('get', url);
					xhr.responseType = 'arraybuffer';
					xhr.send();
				}

				,
				read : function(arrayBuffer) {
					var options = self.options;
					var orientation = self.getOrientation(arrayBuffer);
					var image = self.image;
					var rotate;
					var scaleX;
					var scaleY;

					if (orientation > 1) {
						self.url = self.arrayBufferToDataURL(arrayBuffer);

						switch (orientation) {

						// flip horizontal
						case 2:
							scaleX = -1;
							break;

						// rotate left 180°
						case 3:
							rotate = -180;
							break;

						// flip vertical
						case 4:
							scaleY = -1;
							break;

						// flip vertical + rotate right 90°
						case 5:
							rotate = 90;
							scaleY = -1;
							break;

						// rotate right 90°
						case 6:
							rotate = 90;
							break;

						// flip horizontal + rotate right 90°
						case 7:
							rotate = 90;
							scaleX = -1;
							break;

						// rotate left 90°
						case 8:
							rotate = -90;
							break;
						}
					}

					if (options.rotatable)
						image.rotate = rotate;

					if (options.scalable) {
						image.scaleX = scaleX;
						image.scaleY = scaleY;
					}

					self.clone();
				},
				clone : function() {
					var options = self.options;
					var $this = self.$element;
					var url = self.url;
					var crossOrigin = '';
					var crossOriginUrl;
					var $clone;

					if (options.checkCrossOrigin && self.isCrossOriginURL(url)) {
						crossOrigin = $this.prop('crossOrigin');

						if (crossOrigin) {
							crossOriginUrl = url;
						} else {
							crossOrigin = 'anonymous';

							// Bust cache (#148) when there is not a "crossOrigin" property
							crossOriginUrl = self.addTimestamp(url);
						}
					}

					self.crossOrigin = crossOrigin;
					self.crossOriginUrl = crossOriginUrl;
					self.$clone = $clone = $('<img'
							+ self.getCrossOrigin(crossOrigin) + ' src="'
							+ (crossOriginUrl || url) + '">');

					if (self.isImg) {
						if ($this[0].complete) {
							self.start();
						} else {
							$this.one(EVENT_LOAD, $.proxy(self.start, self));
						}
					} else {
						$clone.one(EVENT_LOAD, $.proxy(self.start, self)).one(
								EVENT_ERROR, $.proxy(self.stop, self))
								.addClass(CLASS_HIDE).insertAfter($this);
					}
				},
				start : function() {
					var $image = self.$element;
					var $clone = self.$clone;

					if (!self.isImg) {
						$clone.off(EVENT_ERROR, self.stop);
						$image = $clone;
					}

					self.getImageSize($image[0], $.proxy(function(naturalWidth,
							naturalHeight) {
						$.extend(self.image, {
							naturalWidth : naturalWidth,
							naturalHeight : naturalHeight,
							aspectRatio : naturalWidth / naturalHeight
						});

						self.isLoaded = true;
						self.build();
					}, self));
				},

				stop : function() {
					self.$clone.remove();
					self.$clone = null;
				},

				build : function() {
					var options = self.options;
					var $this = self.$element;
					var $clone = self.$clone;
					var $cropper;
					var $cropBox;
					var $face;

					if (!self.isLoaded) {
						return;
					}

					// Unbuild first when replace
					if (self.isBuilt) {
						self.unbuild();
					}

					// Create cropper elements
					self.$container = $this.parent();
					self.$cropper = $cropper = $(TEMPLATE);
					self.$canvas = $cropper.find('.cropper-canvas').append(
							$clone);
					self.$dragBox = $cropper.find('.cropper-drag-box');
					self.$cropBox = $cropBox = $cropper
							.find('.cropper-crop-box');
					self.$viewBox = $cropper.find('.cropper-view-box');
					self.$face = $face = $cropBox.find('.cropper-face');

					// Hide the original image
					$this.addClass(CLASS_HIDDEN).after($cropper);

					// Show the clone image if is hidden
					if (!self.isImg) {
						$clone.removeClass(CLASS_HIDE);
					}

					self.initPreview();
					self.bind();

					options.aspectRatio = max(0, options.aspectRatio) || NaN;
					options.viewMode = max(0, min(3, round(options.viewMode))) || 0;

					if (options.autoCrop) {
						self.isCropped = true;

						if (options.modal) {
							self.$dragBox.addClass(CLASS_MODAL);
						}
					} else {
						$cropBox.addClass(CLASS_HIDDEN);
					}

					if (!options.guides) {
						$cropBox.find('.cropper-dashed').addClass(CLASS_HIDDEN);
					}

					if (!options.center) {
						$cropBox.find('.cropper-center').addClass(CLASS_HIDDEN);
					}

					if (options.cropBoxMovable) {
						$face.addClass(CLASS_MOVE)
								.data(DATA_ACTION, ACTION_ALL);
					}

					if (!options.highlight) {
						$face.addClass(CLASS_INVISIBLE);
					}

					if (options.background) {
						$cropper.addClass(CLASS_BG);
					}

					if (!options.cropBoxResizable) {
						$cropBox.find('.cropper-line, .cropper-point')
								.addClass(CLASS_HIDDEN);
					}

					self.setDragMode(options.dragMode);
					self.render();
					self.isBuilt = true;
					self.setData(options.data);
					$this.one(EVENT_BUILT, options.built);

					// Trigger the built event asynchronously to keep `data('cropper')` is defined
					setTimeout($.proxy(function() {
						self.trigger(EVENT_BUILT);
						self.isCompleted = true;
					}, self), 0);
				},

				unbuild : function() {
					if (!self.isBuilt) {
						return;
					}

					self.isBuilt = false;
					self.isCompleted = false;
					self.initialImage = null;

					// Clear `initialCanvas` is necessary when replace
					self.initialCanvas = null;
					self.initialCropBox = null;
					self.container = null;
					self.canvas = null;

					// Clear `cropBox` is necessary when replace
					self.cropBox = null;
					self.unbind();

					self.resetPreview();
					self.$preview = null;

					self.$viewBox = null;
					self.$cropBox = null;
					self.$dragBox = null;
					self.$canvas = null;
					self.$container = null;

					self.$cropper.remove();
					self.$cropper = null;
				},

				render : function() {
					self.initContainer();
					self.initCanvas();
					self.initCropBox();

					self.renderCanvas();

					if (self.isCropped)
						self.renderCropBox();
				},
				initContainer : function() {
					var options = self.options;
					var $this = self.$element;
					var $container = self.$container;
					var $cropper = self.$cropper;

					$cropper.addClass(CLASS_HIDDEN);
					$this.removeClass(CLASS_HIDDEN);

					$cropper.css((self.container = {
						width : max($container.width(),
								num(options.minContainerWidth) || 200),
						height : max($container.height(),
								num(options.minContainerHeight) || 100)
					}));

					$this.addClass(CLASS_HIDDEN);
					$cropper.removeClass(CLASS_HIDDEN);
				},

				// Canvas (image wrapper)
				initCanvas : function() {
					var viewMode = self.options.viewMode;
					var container = self.container;
					var containerWidth = container.width;
					var containerHeight = container.height;
					var image = self.image;
					var imageNaturalWidth = image.naturalWidth;
					var imageNaturalHeight = image.naturalHeight;
					var is90Degree = abs(image.rotate) === 90;
					var naturalWidth = is90Degree ? imageNaturalHeight
							: imageNaturalWidth;
					var naturalHeight = is90Degree ? imageNaturalWidth
							: imageNaturalHeight;
					var aspectRatio = naturalWidth / naturalHeight;
					var canvasWidth = containerWidth;
					var canvasHeight = containerHeight;
					var canvas;

					if (containerHeight * aspectRatio > containerWidth) {
						if (viewMode === 3) {
							canvasWidth = containerHeight * aspectRatio;
						} else {
							canvasHeight = containerWidth / aspectRatio;
						}
					} else {
						if (viewMode === 3) {
							canvasHeight = containerWidth / aspectRatio;
						} else {
							canvasWidth = containerHeight * aspectRatio;
						}
					}

					canvas = {
						naturalWidth : naturalWidth,
						naturalHeight : naturalHeight,
						aspectRatio : aspectRatio,
						width : canvasWidth,
						height : canvasHeight
					};

					canvas.oldLeft = canvas.left = (containerWidth - canvasWidth) / 2;
					canvas.oldTop = canvas.top = (containerHeight - canvasHeight) / 2;

					self.canvas = canvas;
					self.isLimited = (viewMode === 1 || viewMode === 2);
					self.limitCanvas(true, true);
					self.initialImage = $.extend({}, image);
					self.initialCanvas = $.extend({}, canvas);
				},

				limitCanvas : function(isSizeLimited, isPositionLimited) {
					var options = self.options;
					var viewMode = options.viewMode;
					var container = self.container;
					var containerWidth = container.width;
					var containerHeight = container.height;
					var canvas = self.canvas;
					var aspectRatio = canvas.aspectRatio;
					var cropBox = self.cropBox;
					var isCropped = self.isCropped && cropBox;
					var minCanvasWidth;
					var minCanvasHeight;
					var newCanvasLeft;
					var newCanvasTop;

					if (isSizeLimited) {
						minCanvasWidth = num(options.minCanvasWidth) || 0;
						minCanvasHeight = num(options.minCanvasHeight) || 0;

						if (viewMode) {
							if (viewMode > 1) {
								minCanvasWidth = max(minCanvasWidth,
										containerWidth);
								minCanvasHeight = max(minCanvasHeight,
										containerHeight);

								if (viewMode === 3) {
									if (minCanvasHeight * aspectRatio > minCanvasWidth) {
										minCanvasWidth = minCanvasHeight
												* aspectRatio;
									} else {
										minCanvasHeight = minCanvasWidth
												/ aspectRatio;
									}
								}
							} else {
								if (minCanvasWidth) {
									minCanvasWidth = max(minCanvasWidth,
											isCropped ? cropBox.width : 0);
								} else if (minCanvasHeight) {
									minCanvasHeight = max(minCanvasHeight,
											isCropped ? cropBox.height : 0);
								} else if (isCropped) {
									minCanvasWidth = cropBox.width;
									minCanvasHeight = cropBox.height;

									if (minCanvasHeight * aspectRatio > minCanvasWidth) {
										minCanvasWidth = minCanvasHeight
												* aspectRatio;
									} else {
										minCanvasHeight = minCanvasWidth
												/ aspectRatio;
									}
								}
							}
						}

						if (minCanvasWidth && minCanvasHeight) {
							if (minCanvasHeight * aspectRatio > minCanvasWidth) {
								minCanvasHeight = minCanvasWidth / aspectRatio;
							} else {
								minCanvasWidth = minCanvasHeight * aspectRatio;
							}
						} else if (minCanvasWidth) {
							minCanvasHeight = minCanvasWidth / aspectRatio;
						} else if (minCanvasHeight) {
							minCanvasWidth = minCanvasHeight * aspectRatio;
						}

						canvas.minWidth = minCanvasWidth;
						canvas.minHeight = minCanvasHeight;
						canvas.maxWidth = Infinity;
						canvas.maxHeight = Infinity;
					}

					if (isPositionLimited) {
						if (viewMode) {
							newCanvasLeft = containerWidth - canvas.width;
							newCanvasTop = containerHeight - canvas.height;

							canvas.minLeft = min(0, newCanvasLeft);
							canvas.minTop = min(0, newCanvasTop);
							canvas.maxLeft = max(0, newCanvasLeft);
							canvas.maxTop = max(0, newCanvasTop);

							if (isCropped && self.isLimited) {
								canvas.minLeft = min(cropBox.left, cropBox.left
										+ cropBox.width - canvas.width);
								canvas.minTop = min(cropBox.top, cropBox.top
										+ cropBox.height - canvas.height);
								canvas.maxLeft = cropBox.left;
								canvas.maxTop = cropBox.top;

								if (viewMode === 2) {
									if (canvas.width >= containerWidth) {
										canvas.minLeft = min(0, newCanvasLeft);
										canvas.maxLeft = max(0, newCanvasLeft);
									}

									if (canvas.height >= containerHeight) {
										canvas.minTop = min(0, newCanvasTop);
										canvas.maxTop = max(0, newCanvasTop);
									}
								}
							}
						} else {
							canvas.minLeft = -canvas.width;
							canvas.minTop = -canvas.height;
							canvas.maxLeft = containerWidth;
							canvas.maxTop = containerHeight;
						}
					}
				},
				renderCanvas : function(isChanged) {
					var canvas = self.canvas;
					var image = self.image;
					var rotate = image.rotate;
					var naturalWidth = image.naturalWidth;
					var naturalHeight = image.naturalHeight;
					var aspectRatio;
					var rotated;

					if (self.isRotated) {
						self.isRotated = false;

						// Computes rotated sizes with image sizes
						rotated = self.getRotatedSizes({
							width : image.width,
							height : image.height,
							degree : rotate
						});

						aspectRatio = rotated.width / rotated.height;

						if (aspectRatio !== canvas.aspectRatio) {
							canvas.left -= (rotated.width - canvas.width) / 2;
							canvas.top -= (rotated.height - canvas.height) / 2;
							canvas.width = rotated.width;
							canvas.height = rotated.height;
							canvas.aspectRatio = aspectRatio;
							canvas.naturalWidth = naturalWidth;
							canvas.naturalHeight = naturalHeight;

							// Computes rotated sizes with natural image sizes
							if (rotate % 180) {
								rotated = self.getRotatedSizes({
									width : naturalWidth,
									height : naturalHeight,
									degree : rotate
								});

								canvas.naturalWidth = rotated.width;
								canvas.naturalHeight = rotated.height;
							}

							self.limitCanvas(true, false);
						}
					}

					if (canvas.width > canvas.maxWidth
							|| canvas.width < canvas.minWidth) {
						canvas.left = canvas.oldLeft;
					}

					if (canvas.height > canvas.maxHeight
							|| canvas.height < canvas.minHeight) {
						canvas.top = canvas.oldTop;
					}

					canvas.width = min(max(canvas.width, canvas.minWidth),
							canvas.maxWidth);
					canvas.height = min(max(canvas.height, canvas.minHeight),
							canvas.maxHeight);

					self.limitCanvas(false, true);

					canvas.oldLeft = canvas.left = min(max(canvas.left,
							canvas.minLeft), canvas.maxLeft);
					canvas.oldTop = canvas.top = min(max(canvas.top,
							canvas.minTop), canvas.maxTop);

					self.$canvas.css({
						width : canvas.width,
						height : canvas.height,
						left : canvas.left,
						top : canvas.top
					});

					self.renderImage();

					if (self.isCropped && self.isLimited) {
						self.limitCropBox(true, true);
					}

					if (isChanged) {
						self.output();
					}
				},
				renderImage : function(isChanged) {
					var canvas = self.canvas;
					var image = self.image;
					var reversed;

					if (image.rotate) {
						reversed = self.getRotatedSizes({
							width : canvas.width,
							height : canvas.height,
							degree : image.rotate,
							aspectRatio : image.aspectRatio
						}, true);
					}

					$.extend(image, reversed ? {
						width : reversed.width,
						height : reversed.height,
						left : (canvas.width - reversed.width) / 2,
						top : (canvas.height - reversed.height) / 2
					} : {
						width : canvas.width,
						height : canvas.height,
						left : 0,
						top : 0
					});

					self.$clone.css({
						width : image.width,
						height : image.height,
						marginLeft : image.left,
						marginTop : image.top,
						transform : self.getTransform(image)
					});

					if (isChanged) {
						self.output();
					}
				},

				initCropBox : function() {
					var options = self.options;
					var canvas = self.canvas;
					var aspectRatio = options.aspectRatio;
					var autoCropArea = num(options.autoCropArea) || 0.8;
					var cropBox = {
						width : canvas.width,
						height : canvas.height
					};

					if (aspectRatio) {
						if (canvas.height * aspectRatio > canvas.width) {
							cropBox.height = cropBox.width / aspectRatio;
						} else {
							cropBox.width = cropBox.height * aspectRatio;
						}
					}

					self.cropBox = cropBox;
					self.limitCropBox(true, true);

					// Initialize auto crop area
					cropBox.width = min(max(cropBox.width, cropBox.minWidth),
							cropBox.maxWidth);
					cropBox.height = min(max(cropBox.height, cropBox.minHeight),
							cropBox.maxHeight);

					// The width of auto crop area must large than "minWidth", and the height too. (#164)
					cropBox.width = max(cropBox.minWidth, cropBox.width * autoCropArea);
					cropBox.height = max(cropBox.minHeight, cropBox.height
							* autoCropArea);
					cropBox.oldLeft = cropBox.left = canvas.left
							+ (canvas.width - cropBox.width) / 2;
					cropBox.oldTop = cropBox.top = canvas.top
							+ (canvas.height - cropBox.height) / 2;

					self.initialCropBox = $.extend({}, cropBox);
				},

				limitCropBox : function(isSizeLimited, isPositionLimited) {
					var options = self.options;
					var aspectRatio = options.aspectRatio;
					var container = self.container;
					var containerWidth = container.width;
					var containerHeight = container.height;
					var canvas = self.canvas;
					var cropBox = self.cropBox;
					var isLimited = self.isLimited;
					var minCropBoxWidth;
					var minCropBoxHeight;
					var maxCropBoxWidth;
					var maxCropBoxHeight;

					if (isSizeLimited) {
						minCropBoxWidth = num(options.minCropBoxWidth) || 0;
						minCropBoxHeight = num(options.minCropBoxHeight) || 0;

						// The min/maxCropBoxWidth/Height must be less than containerWidth/Height
						minCropBoxWidth = min(minCropBoxWidth, containerWidth);
						minCropBoxHeight = min(minCropBoxHeight, containerHeight);
						maxCropBoxWidth = min(containerWidth, isLimited ? canvas.width
								: containerWidth);
						maxCropBoxHeight = min(containerHeight,
								isLimited ? canvas.height : containerHeight);

						if (aspectRatio) {
							if (minCropBoxWidth && minCropBoxHeight) {
								if (minCropBoxHeight * aspectRatio > minCropBoxWidth) {
									minCropBoxHeight = minCropBoxWidth / aspectRatio;
								} else {
									minCropBoxWidth = minCropBoxHeight * aspectRatio;
								}
							} else if (minCropBoxWidth) {
								minCropBoxHeight = minCropBoxWidth / aspectRatio;
							} else if (minCropBoxHeight) {
								minCropBoxWidth = minCropBoxHeight * aspectRatio;
							}

							if (maxCropBoxHeight * aspectRatio > maxCropBoxWidth) {
								maxCropBoxHeight = maxCropBoxWidth / aspectRatio;
							} else {
								maxCropBoxWidth = maxCropBoxHeight * aspectRatio;
							}
						}

						// The minWidth/Height must be less than maxWidth/Height
						cropBox.minWidth = min(minCropBoxWidth, maxCropBoxWidth);
						cropBox.minHeight = min(minCropBoxHeight, maxCropBoxHeight);
						cropBox.maxWidth = maxCropBoxWidth;
						cropBox.maxHeight = maxCropBoxHeight;
					}

					if (isPositionLimited) {
						if (isLimited) {
							cropBox.minLeft = max(0, canvas.left);
							cropBox.minTop = max(0, canvas.top);
							cropBox.maxLeft = min(containerWidth, canvas.left
									+ canvas.width)
									- cropBox.width;
							cropBox.maxTop = min(containerHeight, canvas.top
									+ canvas.height)
									- cropBox.height;
						} else {
							cropBox.minLeft = 0;
							cropBox.minTop = 0;
							cropBox.maxLeft = containerWidth - cropBox.width;
							cropBox.maxTop = containerHeight - cropBox.height;
						}
					}
				},

				renderCropBox : function() {
					var options = self.options;
					var container = self.container;
					var containerWidth = container.width;
					var containerHeight = container.height;
					var cropBox = self.cropBox;

					if (cropBox.width > cropBox.maxWidth
							|| cropBox.width < cropBox.minWidth) {
						cropBox.left = cropBox.oldLeft;
					}

					if (cropBox.height > cropBox.maxHeight
							|| cropBox.height < cropBox.minHeight) {
						cropBox.top = cropBox.oldTop;
					}

					cropBox.width = min(max(cropBox.width, cropBox.minWidth),
							cropBox.maxWidth);
					cropBox.height = min(max(cropBox.height, cropBox.minHeight),
							cropBox.maxHeight);

					self.limitCropBox(false, true);

					cropBox.oldLeft = cropBox.left = min(max(cropBox.left,
							cropBox.minLeft), cropBox.maxLeft);
					cropBox.oldTop = cropBox.top = min(
							max(cropBox.top, cropBox.minTop), cropBox.maxTop);

					if (options.movable && options.cropBoxMovable) {

						// Turn to move the canvas when the crop box is equal to the container
						self.$face
								.data(
										DATA_ACTION,
										(cropBox.width === containerWidth && cropBox.height === containerHeight) ? ACTION_MOVE
												: ACTION_ALL);
					}

					self.$cropBox.css({
						width : cropBox.width,
						height : cropBox.height,
						left : cropBox.left,
						top : cropBox.top
					});

					if (self.isCropped && self.isLimited) {
						self.limitCanvas(true, true);
					}

					if (!self.isDisabled) {
						self.output();
					}
				},

				output : function() {
					self.preview();

					if (self.isCompleted) {
						self.trigger(EVENT_CROP, self.getData());
					} else if (!self.isBuilt) {

						// Only trigger one crop event before complete
						self.$element.one(EVENT_BUILT, $.proxy(function() {
							self.trigger(EVENT_CROP, self.getData());
						}, this));
					}
				},

				initPreview : function() {
					var crossOrigin = self.getCrossOrigin(self.crossOrigin);
					var url = crossOrigin ? self.crossOriginUrl : self.url;
					var $clone2;

					self.$preview = $(self.options.preview);
					self.$clone2 = $clone2 = $('<img' + crossOrigin + ' src="' + url
							+ '">');
					self.$viewBox.html($clone2);
					self.$preview.each(function() {
						var $this = $(this);

						// Save the original size for recover
						$this.data(DATA_PREVIEW, {
							width : $this.width(),
							height : $this.height(),
							html : $this.html()
						});

						/**
						 * Override img element styles
						 * Add `display:block` to avoid margin top issue
						 * (Occur only when margin-top <= -height)
						 */
						$this.html('<img' + crossOrigin + ' src="' + url + '" style="'
								+ 'display:block;width:100%;height:auto;'
								+ 'min-width:0!important;min-height:0!important;'
								+ 'max-width:none!important;max-height:none!important;'
								+ 'image-orientation:0deg!important;">');
					});
				},

				resetPreview : function() {
					self.$preview.each(function() {
						var $this = $(this);
						var data = $this.data(DATA_PREVIEW);

						$this.css({
							width : data.width,
							height : data.height
						}).html(data.html).removeData(DATA_PREVIEW);
					});
				},

				preview : function() {
					var image = self.image;
					var canvas = self.canvas;
					var cropBox = self.cropBox;
					var cropBoxWidth = cropBox.width;
					var cropBoxHeight = cropBox.height;
					var width = image.width;
					var height = image.height;
					var left = cropBox.left - canvas.left - image.left;
					var top = cropBox.top - canvas.top - image.top;

					if (!self.isCropped || self.isDisabled) {
						return;
					}

					self.$clone2.css({
						width : width,
						height : height,
						marginLeft : -left,
						marginTop : -top,
						transform : self.getTransform(image)
					});

					self.$preview.each(function() {
						var $this = $(this);
						var data = $this.data(DATA_PREVIEW);
						var originalWidth = data.width;
						var originalHeight = data.height;
						var newWidth = originalWidth;
						var newHeight = originalHeight;
						var ratio = 1;

						if (cropBoxWidth) {
							ratio = originalWidth / cropBoxWidth;
							newHeight = cropBoxHeight * ratio;
						}

						if (cropBoxHeight && newHeight > originalHeight) {
							ratio = originalHeight / cropBoxHeight;
							newWidth = cropBoxWidth * ratio;
							newHeight = originalHeight;
						}

						$this.css({
							width : newWidth,
							height : newHeight
						}).find('img').css({
							width : width * ratio,
							height : height * ratio,
							marginLeft : -left * ratio,
							marginTop : -top * ratio,
							transform : self.getTransform(image)
						});
					});
				},
				bind : function() {
					var options = self.options;
					var $this = self.$element;
					var $cropper = self.$cropper;

					if ($.isFunction(options.cropstart)) {
						$this.on(EVENT_CROP_START, options.cropstart);
					}

					if ($.isFunction(options.cropmove)) {
						$this.on(EVENT_CROP_MOVE, options.cropmove);
					}

					if ($.isFunction(options.cropend)) {
						$this.on(EVENT_CROP_END, options.cropend);
					}

					if ($.isFunction(options.crop)) {
						$this.on(EVENT_CROP, options.crop);
					}

					if ($.isFunction(options.zoom)) {
						$this.on(EVENT_ZOOM, options.zoom);
					}

					$cropper.on(EVENT_MOUSE_DOWN, $.proxy(self.cropStart, this));

					if (options.zoomable && options.zoomOnWheel) {
						$cropper.on(EVENT_WHEEL, $.proxy(self.wheel, this));
					}

					if (options.toggleDragModeOnDblclick) {
						$cropper.on(EVENT_DBLCLICK, $.proxy(self.dblclick, this));
					}

					$document.on(EVENT_MOUSE_MOVE,
							(self._cropMove = self.proxy(self.cropMove, this)))
							.on(EVENT_MOUSE_UP,
									(self._cropEnd = self.proxy(self.cropEnd, this)));

					if (options.responsive) {
						$window.on(EVENT_RESIZE, (self._resize = self.proxy(self.resize,
								this)));
					}
				},

				unbind : function() {
					var options = self.options;
					var $this = self.$element;
					var $cropper = self.$cropper;

					if ($.isFunction(options.cropstart)) {
						$this.off(EVENT_CROP_START, options.cropstart);
					}

					if ($.isFunction(options.cropmove)) {
						$this.off(EVENT_CROP_MOVE, options.cropmove);
					}

					if ($.isFunction(options.cropend)) {
						$this.off(EVENT_CROP_END, options.cropend);
					}

					if ($.isFunction(options.crop)) {
						$this.off(EVENT_CROP, options.crop);
					}

					if ($.isFunction(options.zoom)) {
						$this.off(EVENT_ZOOM, options.zoom);
					}

					$cropper.off(EVENT_MOUSE_DOWN, self.cropStart);

					if (options.zoomable && options.zoomOnWheel) {
						$cropper.off(EVENT_WHEEL, self.wheel);
					}

					if (options.toggleDragModeOnDblclick) {
						$cropper.off(EVENT_DBLCLICK, self.dblclick);
					}

					$document.off(EVENT_MOUSE_MOVE, self._cropMove).off(EVENT_MOUSE_UP,
							self._cropEnd);

					if (options.responsive) {
						$window.off(EVENT_RESIZE, self._resize);
					}
				},

				resize : function() {
					var restore = self.options.restore;
					var $container = self.$container;
					var container = self.container;
					var canvasData;
					var cropBoxData;
					var ratio;

					// Check `container` is necessary for IE8
					if (self.isDisabled || !container) {
						return;
					}

					ratio = $container.width() / container.width;

					// Resize when width changed or height changed
					if (ratio !== 1 || $container.height() !== container.height) {
						if (restore) {
							canvasData = self.getCanvasData();
							cropBoxData = self.getCropBoxData();
						}

						self.render();

						if (restore) {
							self.setCanvasData($.each(canvasData, function(i, n) {
								canvasData[i] = n * ratio;
							}));
							self.setCropBoxData($.each(cropBoxData, function(i, n) {
								cropBoxData[i] = n * ratio;
							}));
						}
					}
				},

				dblclick : function() {
					if (self.isDisabled) {
						return;
					}

					if (self.$dragBox.hasClass(CLASS_CROP)) {
						self.setDragMode(ACTION_MOVE);
					} else {
						self.setDragMode(ACTION_CROP);
					}
				},

				wheel : function(event) {
					var e = event.originalEvent || event;
					var ratio = num(self.options.wheelZoomRatio) || 0.1;
					var delta = 1;

					if (self.isDisabled) {
						return;
					}

					event.preventDefault();

					// Limit wheel speed to prevent zoom too fast
					if (self.wheeling) {
						return;
					}

					self.wheeling = true;

					setTimeout($.proxy(function() {
						self.wheeling = false;
					}, this), 50);

					if (e.deltaY) {
						delta = e.deltaY > 0 ? 1 : -1;
					} else if (e.wheelDelta) {
						delta = -e.wheelDelta / 120;
					} else if (e.detail) {
						delta = e.detail > 0 ? 1 : -1;
					}

					self.zoom(-delta * ratio, event);
				},

				cropStart : function(event) {
					var options = self.options;
					var originalEvent = event.originalEvent;
					var touches = originalEvent && originalEvent.touches;
					var e = event;
					var touchesLength;
					var action;

					if (self.isDisabled) {
						return;
					}

					if (touches) {
						touchesLength = touches.length;

						if (touchesLength > 1) {
							if (options.zoomable && options.zoomOnTouch
									&& touchesLength === 2) {
								e = touches[1];
								self.startX2 = e.pageX;
								self.startY2 = e.pageY;
								action = ACTION_ZOOM;
							} else {
								return;
							}
						}

						e = touches[0];
					}

					action = action || $(e.target).data(DATA_ACTION);

					if (REGEXP_ACTIONS.test(action)) {
						if (self.trigger(EVENT_CROP_START, {
							originalEvent : originalEvent,
							action : action
						}).isDefaultPrevented()) {
							return;
						}

						event.preventDefault();

						self.action = action;
						self.cropping = false;

						// IE8  has `event.pageX/Y`, but not `event.originalEvent.pageX/Y`
						// IE10 has `event.originalEvent.pageX/Y`, but not `event.pageX/Y`
						self.startX = e.pageX || originalEvent && originalEvent.pageX;
						self.startY = e.pageY || originalEvent && originalEvent.pageY;

						if (action === ACTION_CROP) {
							self.cropping = true;
							self.$dragBox.addClass(CLASS_MODAL);
						}
					}
				},

				cropMove : function(event) {
					var options = self.options;
					var originalEvent = event.originalEvent;
					var touches = originalEvent && originalEvent.touches;
					var e = event;
					var action = self.action;
					var touchesLength;

					if (self.isDisabled) {
						return;
					}

					if (touches) {
						touchesLength = touches.length;

						if (touchesLength > 1) {
							if (options.zoomable && options.zoomOnTouch
									&& touchesLength === 2) {
								e = touches[1];
								self.endX2 = e.pageX;
								self.endY2 = e.pageY;
							} else {
								return;
							}
						}

						e = touches[0];
					}

					if (action) {
						if (self.trigger(EVENT_CROP_MOVE, {
							originalEvent : originalEvent,
							action : action
						}).isDefaultPrevented()) {
							return;
						}

						event.preventDefault();

						self.endX = e.pageX || originalEvent && originalEvent.pageX;
						self.endY = e.pageY || originalEvent && originalEvent.pageY;

						self.change(e.shiftKey, action === ACTION_ZOOM ? event : null);
					}
				},

				cropEnd : function(event) {
					var originalEvent = event.originalEvent;
					var action = self.action;

					if (self.isDisabled) {
						return;
					}

					if (action) {
						event.preventDefault();

						if (self.cropping) {
							self.cropping = false;
							self.$dragBox.toggleClass(CLASS_MODAL, self.isCropped
									&& self.options.modal);
						}

						self.action = '';

						self.trigger(EVENT_CROP_END, {
							originalEvent : originalEvent,
							action : action
						});
					}
				},

				change : function(shiftKey, event) {
					var options = self.options;
					var aspectRatio = options.aspectRatio;
					var action = self.action;
					var container = self.container;
					var canvas = self.canvas;
					var cropBox = self.cropBox;
					var width = cropBox.width;
					var height = cropBox.height;
					var left = cropBox.left;
					var top = cropBox.top;
					var right = left + width;
					var bottom = top + height;
					var minLeft = 0;
					var minTop = 0;
					var maxWidth = container.width;
					var maxHeight = container.height;
					var renderable = true;
					var offset;
					var range;

					// Locking aspect ratio in "free mode" by holding shift key (#259)
					if (!aspectRatio && shiftKey) {
						aspectRatio = width && height ? width / height : 1;
					}

					if (self.limited) {
						minLeft = cropBox.minLeft;
						minTop = cropBox.minTop;
						maxWidth = minLeft
								+ min(container.width, canvas.left + canvas.width);
						maxHeight = minTop
								+ min(container.height, canvas.top + canvas.height);
					}

					range = {
						x : self.endX - self.startX,
						y : self.endY - self.startY
					};

					if (aspectRatio) {
						range.X = range.y * aspectRatio;
						range.Y = range.x / aspectRatio;
					}

					switch (action) {
					// Move crop box
					case ACTION_ALL:
						left += range.x;
						top += range.y;
						break;

					// Resize crop box
					case ACTION_EAST:
						if (range.x >= 0
								&& (right >= maxWidth || aspectRatio
										&& (top <= minTop || bottom >= maxHeight))) {

							renderable = false;
							break;
						}

						width += range.x;

						if (aspectRatio) {
							height = width / aspectRatio;
							top -= range.Y / 2;
						}

						if (width < 0) {
							action = ACTION_WEST;
							width = 0;
						}

						break;

					case ACTION_NORTH:
						if (range.y <= 0
								&& (top <= minTop || aspectRatio
										&& (left <= minLeft || right >= maxWidth))) {

							renderable = false;
							break;
						}

						height -= range.y;
						top += range.y;

						if (aspectRatio) {
							width = height * aspectRatio;
							left += range.X / 2;
						}

						if (height < 0) {
							action = ACTION_SOUTH;
							height = 0;
						}

						break;

					case ACTION_WEST:
						if (range.x <= 0
								&& (left <= minLeft || aspectRatio
										&& (top <= minTop || bottom >= maxHeight))) {

							renderable = false;
							break;
						}

						width -= range.x;
						left += range.x;

						if (aspectRatio) {
							height = width / aspectRatio;
							top += range.Y / 2;
						}

						if (width < 0) {
							action = ACTION_EAST;
							width = 0;
						}

						break;

					case ACTION_SOUTH:
						if (range.y >= 0
								&& (bottom >= maxHeight || aspectRatio
										&& (left <= minLeft || right >= maxWidth))) {

							renderable = false;
							break;
						}

						height += range.y;

						if (aspectRatio) {
							width = height * aspectRatio;
							left -= range.X / 2;
						}

						if (height < 0) {
							action = ACTION_NORTH;
							height = 0;
						}

						break;

					case ACTION_NORTH_EAST:
						if (aspectRatio) {
							if (range.y <= 0 && (top <= minTop || right >= maxWidth)) {
								renderable = false;
								break;
							}

							height -= range.y;
							top += range.y;
							width = height * aspectRatio;
						} else {
							if (range.x >= 0) {
								if (right < maxWidth) {
									width += range.x;
								} else if (range.y <= 0 && top <= minTop) {
									renderable = false;
								}
							} else {
								width += range.x;
							}

							if (range.y <= 0) {
								if (top > minTop) {
									height -= range.y;
									top += range.y;
								}
							} else {
								height -= range.y;
								top += range.y;
							}
						}

						if (width < 0 && height < 0) {
							action = ACTION_SOUTH_WEST;
							height = 0;
							width = 0;
						} else if (width < 0) {
							action = ACTION_NORTH_WEST;
							width = 0;
						} else if (height < 0) {
							action = ACTION_SOUTH_EAST;
							height = 0;
						}

						break;

					case ACTION_NORTH_WEST:
						if (aspectRatio) {
							if (range.y <= 0 && (top <= minTop || left <= minLeft)) {
								renderable = false;
								break;
							}

							height -= range.y;
							top += range.y;
							width = height * aspectRatio;
							left += range.X;
						} else {
							if (range.x <= 0) {
								if (left > minLeft) {
									width -= range.x;
									left += range.x;
								} else if (range.y <= 0 && top <= minTop) {
									renderable = false;
								}
							} else {
								width -= range.x;
								left += range.x;
							}

							if (range.y <= 0) {
								if (top > minTop) {
									height -= range.y;
									top += range.y;
								}
							} else {
								height -= range.y;
								top += range.y;
							}
						}

						if (width < 0 && height < 0) {
							action = ACTION_SOUTH_EAST;
							height = 0;
							width = 0;
						} else if (width < 0) {
							action = ACTION_NORTH_EAST;
							width = 0;
						} else if (height < 0) {
							action = ACTION_SOUTH_WEST;
							height = 0;
						}

						break;

					case ACTION_SOUTH_WEST:
						if (aspectRatio) {
							if (range.x <= 0
									&& (left <= minLeft || bottom >= maxHeight)) {
								renderable = false;
								break;
							}

							width -= range.x;
							left += range.x;
							height = width / aspectRatio;
						} else {
							if (range.x <= 0) {
								if (left > minLeft) {
									width -= range.x;
									left += range.x;
								} else if (range.y >= 0 && bottom >= maxHeight) {
									renderable = false;
								}
							} else {
								width -= range.x;
								left += range.x;
							}

							if (range.y >= 0) {
								if (bottom < maxHeight) {
									height += range.y;
								}
							} else {
								height += range.y;
							}
						}

						if (width < 0 && height < 0) {
							action = ACTION_NORTH_EAST;
							height = 0;
							width = 0;
						} else if (width < 0) {
							action = ACTION_SOUTH_EAST;
							width = 0;
						} else if (height < 0) {
							action = ACTION_NORTH_WEST;
							height = 0;
						}

						break;

					case ACTION_SOUTH_EAST:
						if (aspectRatio) {
							if (range.x >= 0
									&& (right >= maxWidth || bottom >= maxHeight)) {
								renderable = false;
								break;
							}

							width += range.x;
							height = width / aspectRatio;
						} else {
							if (range.x >= 0) {
								if (right < maxWidth) {
									width += range.x;
								} else if (range.y >= 0 && bottom >= maxHeight) {
									renderable = false;
								}
							} else {
								width += range.x;
							}

							if (range.y >= 0) {
								if (bottom < maxHeight) {
									height += range.y;
								}
							} else {
								height += range.y;
							}
						}

						if (width < 0 && height < 0) {
							action = ACTION_NORTH_WEST;
							height = 0;
							width = 0;
						} else if (width < 0) {
							action = ACTION_SOUTH_WEST;
							width = 0;
						} else if (height < 0) {
							action = ACTION_NORTH_EAST;
							height = 0;
						}

						break;

					// Move canvas
					case ACTION_MOVE:
						self.move(range.x, range.y);
						renderable = false;
						break;

					// Zoom canvas
					case ACTION_ZOOM:
						self.zoom((function(x1, y1, x2, y2) {
							var z1 = sqrt(x1 * x1 + y1 * y1);
							var z2 = sqrt(x2 * x2 + y2 * y2);

							return (z2 - z1) / z1;
						})(abs(self.startX - self.startX2), abs(self.startY
								- self.startY2), abs(self.endX - self.endX2),
								abs(self.endY - self.endY2)), event);
						self.startX2 = self.endX2;
						self.startY2 = self.endY2;
						renderable = false;
						break;

					// Create crop box
					case ACTION_CROP:
						if (!range.x || !range.y) {
							renderable = false;
							break;
						}

						offset = self.$cropper.offset();
						left = self.startX - offset.left;
						top = self.startY - offset.top;
						width = cropBox.minWidth;
						height = cropBox.minHeight;

						if (range.x > 0) {
							action = range.y > 0 ? ACTION_SOUTH_EAST
									: ACTION_NORTH_EAST;
						} else if (range.x < 0) {
							left -= width;
							action = range.y > 0 ? ACTION_SOUTH_WEST
									: ACTION_NORTH_WEST;
						}

						if (range.y < 0) {
							top -= height;
						}

						// Show the crop box if is hidden
						if (!self.isCropped) {
							self.$cropBox.removeClass(CLASS_HIDDEN);
							self.isCropped = true;

							if (self.limited) {
								self.limitCropBox(true, true);
							}
						}

						break;

					// No default
					}

					if (renderable) {
						cropBox.width = width;
						cropBox.height = height;
						cropBox.left = left;
						cropBox.top = top;
						self.action = action;

						self.renderCropBox();
					}

					// Override
					self.startX = self.endX;
					self.startY = self.endY;
				},

				// Show the crop box manually
				crop : function() {
					if (!self.isBuilt || self.isDisabled) {
						return;
					}

					if (!self.isCropped) {
						self.isCropped = true;
						self.limitCropBox(true, true);

						if (self.options.modal) {
							self.$dragBox.addClass(CLASS_MODAL);
						}

						self.$cropBox.removeClass(CLASS_HIDDEN);
					}

					self.setCropBoxData(self.initialCropBox);
				},

				// Reset the image and crop box to their initial states
				reset : function() {
					if (!self.isBuilt || self.isDisabled) {
						return;
					}

					self.image = $.extend({}, self.initialImage);
					self.canvas = $.extend({}, self.initialCanvas);
					self.cropBox = $.extend({}, self.initialCropBox);

					self.renderCanvas();

					if (self.isCropped) {
						self.renderCropBox();
					}
				},

				// Clear the crop box
				clear : function() {
					if (!self.isCropped || self.isDisabled) {
						return;
					}

					$.extend(self.cropBox, {
						left : 0,
						top : 0,
						width : 0,
						height : 0
					});

					self.isCropped = false;
					self.renderCropBox();

					self.limitCanvas(true, true);

					// Render canvas after crop box rendered
					self.renderCanvas();

					self.$dragBox.removeClass(CLASS_MODAL);
					self.$cropBox.addClass(CLASS_HIDDEN);
				},

				/**
				 * Replace the image's src and rebuild the cropper
				 *
				 * @param {String} url
				 * @param {Boolean} onlyColorChanged (optional)
				 */
				replace : function(url, onlyColorChanged) {
					if (!self.isDisabled && url) {
						if (self.isImg) {
							self.$element.attr('src', url);
						}

						if (onlyColorChanged) {
							self.url = url;
							self.$clone.attr('src', url);

							if (self.isBuilt) {
								self.$preview.find('img').add(self.$clone2).attr('src',
										url);
							}
						} else {
							if (self.isImg) {
								self.isReplaced = true;
							}

							// Clear previous data
							self.options.data = null;
							self.load(url);
						}
					}
				},

				// Enable (unfreeze) the cropper
				enable : function() {
					if (self.isBuilt) {
						self.isDisabled = false;
						self.$cropper.removeClass(CLASS_DISABLED);
					}
				},

				// Disable (freeze) the cropper
				disable : function() {
					if (self.isBuilt) {
						self.isDisabled = true;
						self.$cropper.addClass(CLASS_DISABLED);
					}
				},

				// Destroy the cropper and remove the instance from the image
				destroy : function() {
					var $this = self.$element;

					if (self.isLoaded) {
						if (self.isImg && self.isReplaced) {
							$this.attr('src', self.originalUrl);
						}

						self.unbuild();
						$this.removeClass(CLASS_HIDDEN);
					} else {
						if (self.isImg) {
							$this.off(EVENT_LOAD, self.start);
						} else if (self.$clone) {
							self.$clone.remove();
						}
					}

					$this.removeData(NAMESPACE);
				},

				/**
				 * Move the canvas with relative offsets
				 *
				 * @param {Number} offsetX
				 * @param {Number} offsetY (optional)
				 */
				move : function(offsetX, offsetY) {
					var canvas = self.canvas;

					self.moveTo(self.isUndefined(offsetX) ? offsetX : canvas.left
							+ num(offsetX), self.isUndefined(offsetY) ? offsetY : canvas.top
							+ num(offsetY));
				},

				/**
				 * Move the canvas to an absolute point
				 *
				 * @param {Number} x
				 * @param {Number} y (optional)
				 */
				moveTo : function(x, y) {
					var canvas = self.canvas;
					var isChanged = false;

					// If "y" is not present, its default value is "x"
					if (self.isUndefined(y)) {
						y = x;
					}

					x = num(x);
					y = num(y);

					if (self.isBuilt && !self.isDisabled && self.options.movable) {
						if (self.isNumber(x)) {
							canvas.left = x;
							isChanged = true;
						}

						if (self.isNumber(y)) {
							canvas.top = y;
							isChanged = true;
						}

						if (isChanged) {
							self.renderCanvas(true);
						}
					}
				},

				/**
				 * Zoom the canvas with a relative ratio
				 *
				 * @param {Number} ratio
				 * @param {String} _event (private)
				 */
				zoom : function(ratio, _event) {
					var canvas = self.canvas;

					ratio = num(ratio);

					if (ratio < 0) {
						ratio = 1 / (1 - ratio);
					} else {
						ratio = 1 + ratio;
					}

					self.zoomTo(canvas.width * ratio / canvas.naturalWidth, _event);
				},

				/**
				 * Zoom the canvas to an absolute ratio
				 *
				 * @param {Number} ratio
				 * @param {String} _event (private)
				 */
				zoomTo : function(ratio, _event) {
					var options = self.options;
					var canvas = self.canvas;
					var width = canvas.width;
					var height = canvas.height;
					var naturalWidth = canvas.naturalWidth;
					var naturalHeight = canvas.naturalHeight;
					var originalEvent;
					var newWidth;
					var newHeight;
					var offset;
					var center;

					ratio = num(ratio);

					if (ratio >= 0 && self.isBuilt && !self.isDisabled
							&& options.zoomable) {
						newWidth = naturalWidth * ratio;
						newHeight = naturalHeight * ratio;

						if (_event) {
							originalEvent = _event.originalEvent;
						}

						if (self.trigger(EVENT_ZOOM, {
							originalEvent : originalEvent,
							oldRatio : width / naturalWidth,
							ratio : newWidth / naturalWidth
						}).isDefaultPrevented()) {
							return;
						}

						if (originalEvent) {
							offset = self.$cropper.offset();
							center = originalEvent.touches ? self.getTouchesCenter(originalEvent.touches)
									: {
										pageX : _event.pageX || originalEvent.pageX
												|| 0,
										pageY : _event.pageY || originalEvent.pageY
												|| 0
									};

							// Zoom from the triggering point of the event
							canvas.left -= (newWidth - width)
									* (((center.pageX - offset.left) - canvas.left) / width);
							canvas.top -= (newHeight - height)
									* (((center.pageY - offset.top) - canvas.top) / height);
						} else {

							// Zoom from the center of the canvas
							canvas.left -= (newWidth - width) / 2;
							canvas.top -= (newHeight - height) / 2;
						}

						canvas.width = newWidth;
						canvas.height = newHeight;
						self.renderCanvas(true);
					}
				},

				/**
				 * Rotate the canvas with a relative degree
				 *
				 * @param {Number} degree
				 */
				rotate : function(degree) {
					self.rotateTo((self.image.rotate || 0) + num(degree));
				},

				/**
				 * Rotate the canvas to an absolute degree
				 * https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function#rotate()
				 *
				 * @param {Number} degree
				 */
				rotateTo : function(degree) {
					degree = num(degree);

					if (self.isNumber(degree) && self.isBuilt && !self.isDisabled
							&& self.options.rotatable) {
						self.image.rotate = degree % 360;
						self.isRotated = true;
						self.renderCanvas(true);
					}
				},

				/**
				 * Scale the image
				 * https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function#scale()
				 *
				 * @param {Number} scaleX
				 * @param {Number} scaleY (optional)
				 */
				scale : function(scaleX, scaleY) {
					var image = self.image;
					var isChanged = false;

					// If "scaleY" is not present, its default value is "scaleX"
					if (self.isUndefined(scaleY)) {
						scaleY = scaleX;
					}

					scaleX = num(scaleX);
					scaleY = num(scaleY);

					if (self.isBuilt && !self.isDisabled && self.options.scalable) {
						if (self.isNumber(scaleX)) {
							image.scaleX = scaleX;
							isChanged = true;
						}

						if (self.isNumber(scaleY)) {
							image.scaleY = scaleY;
							isChanged = true;
						}

						if (isChanged) {
							self.renderImage(true);
						}
					}
				},

				/**
				 * Scale the abscissa of the image
				 *
				 * @param {Number} scaleX
				 */
				scaleX : function(scaleX) {
					var scaleY = self.image.scaleY;

					self.scale(scaleX, self.isNumber(scaleY) ? scaleY : 1);
				},

				/**
				 * Scale the ordinate of the image
				 *
				 * @param {Number} scaleY
				 */
				scaleY : function(scaleY) {
					var scaleX = self.image.scaleX;

					self.scale(self.isNumber(scaleX) ? scaleX : 1, scaleY);
				},

				/**
				 * Get the cropped area position and size data (base on the original image)
				 *
				 * @param {Boolean} isRounded (optional)
				 * @return {Object} data
				 */
				getData : function(isRounded) {
					var options = self.options;
					var image = self.image;
					var canvas = self.canvas;
					var cropBox = self.cropBox;
					var ratio;
					var data;

					if (self.isBuilt && self.isCropped) {
						data = {
							x : cropBox.left - canvas.left,
							y : cropBox.top - canvas.top,
							width : cropBox.width,
							height : cropBox.height
						};

						ratio = image.width / image.naturalWidth;

						$.each(data, function(i, n) {
							n = n / ratio;
							data[i] = isRounded ? round(n) : n;
						});

					} else {
						data = {
							x : 0,
							y : 0,
							width : 0,
							height : 0
						};
					}

					if (options.rotatable) {
						data.rotate = image.rotate || 0;
					}

					if (options.scalable) {
						data.scaleX = image.scaleX || 1;
						data.scaleY = image.scaleY || 1;
					}

					return data;
				},

				/**
				 * Set the cropped area position and size with new data
				 *
				 * @param {Object} data
				 */
				setData : function(data) {
					var options = self.options;
					var image = self.image;
					var canvas = self.canvas;
					var cropBoxData = {};
					var isRotated;
					var isScaled;
					var ratio;

					if ($.isFunction(data)) {
						data = data.call(self.element);
					}

					if (self.isBuilt && !self.isDisabled && $.isPlainObject(data)) {
						if (options.rotatable) {
							if (self.isNumber(data.rotate) && data.rotate !== image.rotate) {
								image.rotate = data.rotate;
								self.isRotated = isRotated = true;
							}
						}

						if (options.scalable) {
							if (self.isNumber(data.scaleX) && data.scaleX !== image.scaleX) {
								image.scaleX = data.scaleX;
								isScaled = true;
							}

							if (self.isNumber(data.scaleY) && data.scaleY !== image.scaleY) {
								image.scaleY = data.scaleY;
								isScaled = true;
							}
						}

						if (isRotated) {
							self.renderCanvas();
						} else if (isScaled) {
							self.renderImage();
						}

						ratio = image.width / image.naturalWidth;

						if (self.isNumber(data.x)) {
							cropBoxData.left = data.x * ratio + canvas.left;
						}

						if (self.isNumber(data.y)) {
							cropBoxData.top = data.y * ratio + canvas.top;
						}

						if (self.isNumber(data.width)) {
							cropBoxData.width = data.width * ratio;
						}

						if (self.isNumber(data.height)) {
							cropBoxData.height = data.height * ratio;
						}

						self.setCropBoxData(cropBoxData);
					}
				},

				/**
				 * Get the container size data
				 *
				 * @return {Object} data
				 */
				getContainerData : function() {
					return self.isBuilt ? self.container : {};
				},

				/**
				 * Get the image position and size data
				 *
				 * @return {Object} data
				 */
				getImageData : function() {
					return self.isLoaded ? self.image : {};
				},

				/**
				 * Get the canvas position and size data
				 *
				 * @return {Object} data
				 */
				getCanvasData : function() {
					var canvas = self.canvas;
					var data = {};

					if (self.isBuilt) {
						$.each([ 'left', 'top', 'width', 'height', 'naturalWidth',
								'naturalHeight' ], function(i, n) {
							data[n] = canvas[n];
						});
					}

					return data;
				},

				/**
				 * Set the canvas position and size with new data
				 *
				 * @param {Object} data
				 */
				setCanvasData : function(data) {
					var canvas = self.canvas;
					var aspectRatio = canvas.aspectRatio;

					if ($.isFunction(data)) {
						data = data.call(self.$element);
					}

					if (self.isBuilt && !self.isDisabled && $.isPlainObject(data)) {
						if (self.isNumber(data.left)) {
							canvas.left = data.left;
						}

						if (self.isNumber(data.top)) {
							canvas.top = data.top;
						}

						if (self.isNumber(data.width)) {
							canvas.width = data.width;
							canvas.height = data.width / aspectRatio;
						} else if (self.isNumber(data.height)) {
							canvas.height = data.height;
							canvas.width = data.height * aspectRatio;
						}

						self.renderCanvas(true);
					}
				},

				/**
				 * Get the crop box position and size data
				 *
				 * @return {Object} data
				 */
				getCropBoxData : function() {
					var cropBox = self.cropBox;
					var data;

					if (self.isBuilt && self.isCropped) {
						data = {
							left : cropBox.left,
							top : cropBox.top,
							width : cropBox.width,
							height : cropBox.height
						};
					}

					return data || {};
				},

				/**
				 * Set the crop box position and size with new data
				 *
				 * @param {Object} data
				 */
				setCropBoxData : function(data) {
					var cropBox = self.cropBox;
					var aspectRatio = self.options.aspectRatio;
					var isWidthChanged;
					var isHeightChanged;

					if ($.isFunction(data)) {
						data = data.call(self.$element);
					}

					if (self.isBuilt && self.isCropped && !self.isDisabled
							&& $.isPlainObject(data)) {

						if (self.isNumber(data.left)) {
							cropBox.left = data.left;
						}

						if (self.isNumber(data.top)) {
							cropBox.top = data.top;
						}

						if (self.isNumber(data.width)) {
							isWidthChanged = true;
							cropBox.width = data.width;
						}

						if (self.isNumber(data.height)) {
							isHeightChanged = true;
							cropBox.height = data.height;
						}

						if (aspectRatio) {
							if (isWidthChanged) {
								cropBox.height = cropBox.width / aspectRatio;
							} else if (isHeightChanged) {
								cropBox.width = cropBox.height * aspectRatio;
							}
						}

						self.renderCropBox();
					}
				},

				/**
				 * Get a canvas drawn the cropped image
				 *
				 * @param {Object} options (optional)
				 * @return {HTMLCanvasElement} canvas
				 */
				getCroppedCanvas : function(options) {
					var originalWidth;
					var originalHeight;
					var canvasWidth;
					var canvasHeight;
					var scaledWidth;
					var scaledHeight;
					var scaledRatio;
					var aspectRatio;
					var canvas;
					var context;
					var data;

					if (!self.isBuilt || !SUPPORT_CANVAS) {
						return;
					}

					if (!self.isCropped) {
						return self.getSourceCanvas(self.$clone[0], self.image);
					}

					if (!$.isPlainObject(options)) {
						options = {};
					}

					data = self.getData();
					originalWidth = data.width;
					originalHeight = data.height;
					aspectRatio = originalWidth / originalHeight;

					if ($.isPlainObject(options)) {
						scaledWidth = options.width;
						scaledHeight = options.height;

						if (scaledWidth) {
							scaledHeight = scaledWidth / aspectRatio;
							scaledRatio = scaledWidth / originalWidth;
						} else if (scaledHeight) {
							scaledWidth = scaledHeight * aspectRatio;
							scaledRatio = scaledHeight / originalHeight;
						}
					}

					// The canvas element will use `Math.floor` on a float number, so floor first
					canvasWidth = floor(scaledWidth || originalWidth);
					canvasHeight = floor(scaledHeight || originalHeight);

					canvas = $('<canvas>')[0];
					canvas.width = canvasWidth;
					canvas.height = canvasHeight;
					context = canvas.getContext('2d');

					if (options.fillColor) {
						context.fillStyle = options.fillColor;
						context.fillRect(0, 0, canvasWidth, canvasHeight);
					}

					// https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D.drawImage
					context.drawImage
							.apply(context,
									(function() {
										var source = self.getSourceCanvas(self.$clone[0],
												self.image);
										var sourceWidth = source.width;
										var sourceHeight = source.height;
										var canvas = self.canvas;
										var params = [ source ];

										// Source canvas
										var srcX = data.x + canvas.naturalWidth
												* (abs(data.scaleX || 1) - 1) / 2;
										var srcY = data.y + canvas.naturalHeight
												* (abs(data.scaleY || 1) - 1) / 2;
										var srcWidth;
										var srcHeight;

										// Destination canvas
										var dstX;
										var dstY;
										var dstWidth;
										var dstHeight;

										if (srcX <= -originalWidth
												|| srcX > sourceWidth) {
											srcX = srcWidth = dstX = dstWidth = 0;
										} else if (srcX <= 0) {
											dstX = -srcX;
											srcX = 0;
											srcWidth = dstWidth = min(sourceWidth,
													originalWidth + srcX);
										} else if (srcX <= sourceWidth) {
											dstX = 0;
											srcWidth = dstWidth = min(originalWidth,
													sourceWidth - srcX);
										}

										if (srcWidth <= 0 || srcY <= -originalHeight
												|| srcY > sourceHeight) {
											srcY = srcHeight = dstY = dstHeight = 0;
										} else if (srcY <= 0) {
											dstY = -srcY;
											srcY = 0;
											srcHeight = dstHeight = min(sourceHeight,
													originalHeight + srcY);
										} else if (srcY <= sourceHeight) {
											dstY = 0;
											srcHeight = dstHeight = min(originalHeight,
													sourceHeight - srcY);
										}

										// All the numerical parameters should be integer for `drawImage` (#476)
										params.push(floor(srcX), floor(srcY),
												floor(srcWidth), floor(srcHeight));

										// Scale destination sizes
										if (scaledRatio) {
											dstX *= scaledRatio;
											dstY *= scaledRatio;
											dstWidth *= scaledRatio;
											dstHeight *= scaledRatio;
										}

										// Avoid "IndexSizeError" in IE and Firefox
										if (dstWidth > 0 && dstHeight > 0) {
											params.push(floor(dstX), floor(dstY),
													floor(dstWidth), floor(dstHeight));
										}

										return params;
									}).call(this));

					return canvas;
				},

				/**
				 * Change the aspect ratio of the crop box
				 *
				 * @param {Number} aspectRatio
				 */
				setAspectRatio : function(aspectRatio) {
					var options = self.options;

					if (!self.isDisabled && !self.isUndefined(aspectRatio)) {

						// 0 -> NaN
						options.aspectRatio = max(0, aspectRatio) || NaN;

						if (self.isBuilt) {
							self.initCropBox();

							if (self.isCropped) {
								self.renderCropBox();
							}
						}
					}
				},

				/**
				 * Change the drag mode
				 *
				 * @param {String} mode (optional)
				 */
				setDragMode : function(mode) {
					var options = self.options;
					var croppable;
					var movable;

					if (self.isLoaded && !self.isDisabled) {
						croppable = mode === ACTION_CROP;
						movable = options.movable && mode === ACTION_MOVE;
						mode = (croppable || movable) ? mode : ACTION_NONE;

						self.$dragBox.data(DATA_ACTION, mode).toggleClass(CLASS_CROP,
								croppable).toggleClass(CLASS_MOVE, movable);

						if (!options.cropBoxMovable) {

							// Sync drag mode to crop box when it is not movable(#300)
							self.$face.data(DATA_ACTION, mode).toggleClass(CLASS_CROP,
									croppable).toggleClass(CLASS_MOVE, movable);
						}
					}
				},
				isNumber:function(n) {
					return typeof n === 'number' && !isNaN(n);
				},

				isUndefined:function (n) {
					return typeof n === 'undefined';
				},

				toArray:function (obj, offset) {
					var args = [];

					// This is necessary for IE8
					if (self.isNumber(offset)) {
						args.push(offset);
					}

					return args.slice.apply(obj, args);
				},

				// Custom proxy to avoid jQuery's guid
				proxy:function (fn, context) {
					var args = self.toArray(arguments, 2);

					return function() {
						return fn.apply(context, args.concat(self.toArray(arguments)));
					};
				},
				isCrossOriginURL:function (url) {
					var parts = url.match(/^(https?:)\/\/([^\:\/\?#]+):?(\d*)/i);

					return parts
							&& (parts[1] !== location.protocol
									|| parts[2] !== location.hostname || parts[3] !== location.port);
				},
				addTimestamp:function (url) {
					var timestamp = 'timestamp=' + (new Date()).getTime();

					return (url + (url.indexOf('?') === -1 ? '?' : '&') + timestamp);
				},

				getCrossOrigin:function (crossOrigin) {
					return crossOrigin ? ' crossOrigin="' + crossOrigin + '"' : '';
				},

				getImageSize:function (image, callback) {
					var newImage;

					// Modern browsers (ignore Safari, #120 & #509)
					if (image.naturalWidth && !IS_SAFARI) {
						return callback(image.naturalWidth, image.naturalHeight);
					}

					// IE8: Don't use `new Image()` here (#319)
					newImage = document.createElement('img');

					newImage.onload = function() {
						callback(this.width, this.height);
					};

					newImage.src = image.src;
				},

				getTransform:function (options) {
					var transforms = [];
					var rotate = options.rotate;
					var scaleX = options.scaleX;
					var scaleY = options.scaleY;

					// Scale should come first before rotate (#633)
					if (self.isNumber(scaleX) && self.isNumber(scaleY)) {
						transforms.push('scale(' + scaleX + ',' + scaleY + ')');
					}

					if (self.isNumber(rotate)) {
						transforms.push('rotate(' + rotate + 'deg)');
					}

					return transforms.length ? transforms.join(' ') : 'none';
				},

				getRotatedSizes:function (data, isReversed) {
					var deg = abs(data.degree) % 180;
					var arc = (deg > 90 ? (180 - deg) : deg) * Math.PI / 180;
					var sinArc = sin(arc);
					var cosArc = cos(arc);
					var width = data.width;
					var height = data.height;
					var aspectRatio = data.aspectRatio;
					var newWidth;
					var newHeight;

					if (!isReversed) {
						newWidth = width * cosArc + height * sinArc;
						newHeight = width * sinArc + height * cosArc;
					} else {
						newWidth = width / (cosArc + sinArc / aspectRatio);
						newHeight = newWidth / aspectRatio;
					}

					return {
						width : newWidth,
						height : newHeight
					};
				},

				getSourceCanvas:function (image, data) {
					var canvas = $('<canvas>')[0];
					var context = canvas.getContext('2d');
					var dstX = 0;
					var dstY = 0;
					var dstWidth = data.naturalWidth;
					var dstHeight = data.naturalHeight;
					var rotate = data.rotate;
					var scaleX = data.scaleX;
					var scaleY = data.scaleY;
					var scalable = self.isNumber(scaleX) && self.isNumber(scaleY)
							&& (scaleX !== 1 || scaleY !== 1);
					var rotatable = self.isNumber(rotate) && rotate !== 0;
					var advanced = rotatable || scalable;
					var canvasWidth = dstWidth * abs(scaleX || 1);
					var canvasHeight = dstHeight * abs(scaleY || 1);
					var translateX;
					var translateY;
					var rotated;

					if (scalable) {
						translateX = canvasWidth / 2;
						translateY = canvasHeight / 2;
					}

					if (rotatable) {
						rotated = self.getRotatedSizes({
							width : canvasWidth,
							height : canvasHeight,
							degree : rotate
						});

						canvasWidth = rotated.width;
						canvasHeight = rotated.height;
						translateX = canvasWidth / 2;
						translateY = canvasHeight / 2;
					}

					canvas.width = canvasWidth;
					canvas.height = canvasHeight;

					if (advanced) {
						dstX = -dstWidth / 2;
						dstY = -dstHeight / 2;

						context.save();
						context.translate(translateX, translateY);
					}

					if (rotatable) {
						context.rotate(rotate * Math.PI / 180);
					}

					// Should call `scale` after rotated
					if (scalable) {
						context.scale(scaleX, scaleY);
					}

					context.drawImage(image, floor(dstX), floor(dstY), floor(dstWidth),
							floor(dstHeight));

					if (advanced) {
						context.restore();
					}

					return canvas;
				},

				getTouchesCenter:function (touches) {
					var length = touches.length;
					var pageX = 0;
					var pageY = 0;

					if (length) {
						$.each(touches, function(i, touch) {
							pageX += touch.pageX;
							pageY += touch.pageY;
						});

						pageX /= length;
						pageY /= length;
					}

					return {
						pageX : pageX,
						pageY : pageY
					};
				},

				getStringFromCharCode:function (dataView, start, length) {
					var str = '';
					var i;

					for (i = start, length += start; i < length; i++) {
						str += fromCharCode(dataView.getUint8(i));
					}

					return str;
				},

				getOrientation:function (arrayBuffer) {
					if(!arrayBuffer)
						return false;

					var dataView = new DataView(arrayBuffer);
					var length = dataView.byteLength;
					var orientation;
					var exifIDCode;
					var tiffOffset;
					var firstIFDOffset;
					var littleEndian;
					var endianness;
					var app1Start;
					var ifdStart;
					var offset;
					var i;

					// Only handle JPEG image (start by 0xFFD8)
					if (dataView.getUint8(0) === 0xFF && dataView.getUint8(1) === 0xD8) {
						offset = 2;

						while (offset < length) {
							if (dataView.getUint8(offset) === 0xFF
									&& dataView.getUint8(offset + 1) === 0xE1) {
								app1Start = offset;
								break;
							}

							offset++;
						}
					}

					if (app1Start) {
						exifIDCode = app1Start + 4;
						tiffOffset = app1Start + 10;

						if (self.getStringFromCharCode(dataView, exifIDCode, 4) === 'Exif') {
							endianness = dataView.getUint16(tiffOffset);
							littleEndian = endianness === 0x4949;

							if (littleEndian || endianness === 0x4D4D /* bigEndian */) {
								if (dataView.getUint16(tiffOffset + 2, littleEndian) === 0x002A) {
									firstIFDOffset = dataView.getUint32(tiffOffset + 4,
											littleEndian);

									if (firstIFDOffset >= 0x00000008) {
										ifdStart = tiffOffset + firstIFDOffset;
									}
								}
							}
						}
					}

					if (ifdStart) {
						length = dataView.getUint16(ifdStart, littleEndian);

						for (i = 0; i < length; i++) {
							offset = ifdStart + i * 12 + 2;

							if (dataView.getUint16(offset, littleEndian) === 0x0112 /* Orientation */) {

								// 8 is the offset of the current tag's value
								offset += 8;

								// Get the original orientation value
								orientation = dataView.getUint16(offset, littleEndian);

								// Override the orientation with its default value for Safari (#120)
								if (IS_SAFARI) {
									dataView.setUint16(offset, 1, littleEndian);
								}

								break;
							}
						}
					}

					return orientation;
				},

				dataURLToArrayBuffer:function (dataURL) {
					var base64 = dataURL.replace(REGEXP_DATA_URL_HEAD, '');
					var binary = atob(base64);
					var length = binary.length;
					var arrayBuffer = new ArrayBuffer(length);
					var dataView = new Uint8Array(arrayBuffer);
					var i;

					for (i = 0; i < length; i++) {
						dataView[i] = binary.charCodeAt(i);
					}

					return arrayBuffer;
				},

				// Only available for JPEG image
				arrayBufferToDataURL:function (arrayBuffer) {
					var dataView = new Uint8Array(arrayBuffer);
					var length = dataView.length;
					var base64 = '';
					var i;

					for (i = 0; i < length; i++) {
						base64 += fromCharCode(dataView[i]);
					}

					return 'data:image/jpeg;base64,' + btoa(base64);
				}
			});
});