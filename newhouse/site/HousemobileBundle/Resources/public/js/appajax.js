/**
 * author : 08cms
 * date   : 2017-2-14
 * name   : appajax
 * modify : 2017-2-14
 */

/**
 * @module appajax
 * @param {Object} cfg 加载路径对象 选择加载模块，定义对应url，如使用默认url即把url设置成0，如需重新定义url则重新书写
 *
 * @example 调用
 * ```
 * var ajax = {
 *     searchUrl: 0, // 启用加载搜索模块，使用默认路径
 *     headerUrl: BASE_URL + 'common/header', // 启用加载头部模块，使用自定义路径
 *     
 * }
 * seajs.use(['appajax'], function(appajax) {
 *     appajax.init(ajax);
 * })
 * ```
 * 
 */

define('appajax', function(require, exports, module) {
	var utils = require('utils');
	var ajax = require('ajax');
	var _isApp = 1;

	(function(out) {
	    module.exports = out;
	})({
		init: function (cfg) {
			var _this = this;
			var _cfg = (typeof cfg == "object" ? cfg : {});
			// 导航
			if (_cfg.navUrl != undefined) {
			    _this.ajax(_cfg.navUrl ? _cfg.navUrl : BASE_URL + '/common/navblock', function (res) {
					$('#baseNav').replaceWith(res);
					utils.urlTransform();
				})
		    };
	        // 头部
	        if (_cfg.headerUrl != undefined) {
			    _this.ajax(_cfg.headerUrl ? _cfg.headerUrl : BASE_URL + '/common/header', function (res) {
					$('#header').replaceWith(res);
		            _this.headerBack();
				});
		    };
		    // 筛选
		    if (_cfg.filterUrl != undefined) {
			    _this.ajax(_cfg.filterUrl ? _cfg.filterUrl : BASE_URL + '/houses/filterblock', function (res) {
					$('#ajaxfilter').replaceWith(res);
					_this.filterBack();
				});
		    };
		    // 列表
		    if (_cfg.listUrl != undefined) {
			    _this.ajax(BASE_URL + '/common/listblock', function (res) {
					$('#ajaxlist').replaceWith(res);
		            _this.listBack(_cfg.listUrl);
				});
		    };
		    // 搜索
		    if (_cfg.searchUrl != undefined) {
			    _this.ajax(BASE_URL + '/common/topsearch?appaction=' + (_cfg.searchUrl ? _cfg.searchUrl : '../houses/list.html'), function (res) {
					$('#search').replaceWith(res);
		            _this.searchBack();
				});
		    };
		    // 底部
		    if (_cfg.footerUrl != undefined) {
			    _this.ajax(_cfg.footerUrl ? _cfg.footerUrl : BASE_URL + '/common/footer', function (res) {
					$('#footer').replaceWith(res);
				});
		    };
		    // 地图
		    if (_cfg.mapUrl != undefined) {
			    _this.ajax(_cfg.mapUrl ? _cfg.mapUrl : BASE_URL + '/houses/mapblock', function (res) {
					$('#map').replaceWith(res);
				});
		    };
		    // 详情页导航
		    if (_cfg.detailNavUrl != undefined) {
			    _this.ajax(_cfg.detailNavUrl ? _cfg.detailNavUrl : BASE_URL + '/houses/detailnavblock', function (res) {
					$('#detailNav').replaceWith(res);
					_this.detailNavBack();
				});
		    };
		},
		ajax: function (url, callback) {
			var errorBack = arguments[2]
			ajax({
		        url: url,
		        type: 'GET',
		        dataType: 'html',
	            isSublimeLock: false,
		        data: {
		            _isApp: _isApp,
		            _area: AREA
		        },
		        success: function(res) {
		        	if (typeof callback == 'function') {
			        	callback(res);
		        	};
		        },
		        error: function(xhr, type){
		        	if (typeof errorBack == 'function') {
			            errorBack()
		        	} else {
		        		var $detailAjax = $('#detailAjax');
		        		if ($detailAjax.length) {
		        			$('#detailAjax').addClass('ajax-error');
		        		}
			            // console.log('error');
		        	};
		        }
		    });
		},
		hash: function () {
			// 搜索条件过滤
			var locationUrl = location.hash;
	        return locationUrl.replace('#', '');
		},
	    listBack: function (listurl) {
			// 列表回调
	    	var _this = this;
	        var listCfg = {};
	        // 搜索
            var _name = decodeURI(utils.searchParams('name'));

            // 默认条件
	        var params = $.extend({}, {
	            	_isApp: _isApp,
	            	_area: AREA
	            }, typeof arguments[1] == 'object' ? arguments[1] : {}, _name != 'undefined' ? {name: _name} : {})

	        // 传递参数() + (_this.hash() ? '&' : '') + _this.hash()
	        listCfg = {
	            url: listurl ? listurl : BASE_URL + '/houses/list?ajax=1',
	            datatype: 'html',
	            ajaxtype: 'GET',
	            callback: utils.urlTransform,
	            parseURL: utils.parseURL,
	            params: params
	        }
	        require.async(['sm', 'loadmore'], function (sm, loadmore) {
	            loadmore.init(listCfg);
	            utils.urlTransform();
	        });
	    },
	    headerBack: function () {
		    // 头部回调
	        require.async(['sm', 'touch', 'fx', 'common'], function (sm, touch, fx, common) {
	            common.toTop();
	            common.init({
	            	datatype: 'json',
	            	jsonpCallback: 'n08cms',
	            	data: {
	            		_isApp: _isApp,
	            		_area: AREA
	            	}
	            });
	            utils.urlTransform();
	        });
	    },
	    searchBack: function () {
		    // 搜索回调
	        require.async(['sm', 'mSearch'], function (sm, mSearch) {
	        	utils.urlTransform();
	        });
	    },
	    detailNavBack: function () {
	    	// 详情页导航回调
	    	require.async(['sm'], function () {
	    		$('.swiper-width-auto').swiper({
	    			slidesPerView: 'auto'
	    		})
	    	})
	    },
	    getcounts: function () {
	    	// 统计
	    	require.async(['getcounts'], function (getcounts) {
	    		getcounts.init({
                    url: BASE_URL + '/igetcounts/index',
                    datatype: 'json',
                    data: {
		            	_isApp: _isApp
                    }
                });
	    	});
	    },
	    commentBack: function (opt) {
	    	//评论
	    	var _opt = opt ? opt : {};
		    require.async(['comment', 'vote'], function (comment, vote){
		    	if (_opt.callback) {
		    		comment[_opt.callback]();
		    	};
		        vote.init({
		            saveUrl: BASE_URL + '/viewinter/vote',
		            data: {
		            	_isApp: _isApp
		            }
		        });
		        comment.init({
		            list: _opt.list ? _opt.list : '.comment-list',
		            url: _opt.url,
		            btnload: _opt.btnload ? _opt.btnload : null,
		            removeItem: _opt.removeItem ? _opt.removeItem : null,
		            data: {
		            	_isApp: _isApp
		            }
		        });
		    });
	    },
	    collectBack: function (opt) {
	    	// 收藏
	    	require.async(['collect'], function (collect) {
	    		collect.init({
		            type: 'GET',
		            datatype: 'json',
		            jsonpCallback: 'n08cms',
		            data: {
		                _isApp: _isApp,
		            }
		        });
	    	});
	    },
	    filterBack: function () {
	    	require.async(['filter'], function (filter) {
	    		filter.init({
	    			url: BASE_URL + '/common/filtersearch',
	    			datatype: 'html',
	    			data: {
		            	_isApp: _isApp,
		            	_area: AREA
	    			}
	    		})
	    	});
	    }


	})
});
//# sourceMappingURL=appajax.js.map
