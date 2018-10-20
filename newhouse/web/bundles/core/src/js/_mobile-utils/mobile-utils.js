define('utils', [], function(require, exports, module){
    // @require('babel')
	var utils = {
		url: function(url){
			return $(document.createElement('div')).html(url).text()
		},
		/**
         * @method parseURL
         * @description 解析url
         * @param  {string} url 要解析的url
         * @return {object}     相关信息
         * @示例 调用
         * ```js
         * var myURL = parseURL('http://abc.com:8080/dir/index.html?id=255&m=hello#top');
         * myURL.file;     // = 'index.html'
         * myURL.hash;     // = 'top'
         * myURL.host;     // = 'abc.com'
         * myURL.query;    // = '?id=255&m=hello'
         * myURL.params;   // = Object = { id: 255, m: hello }
         * myURL.path;     // = '/dir/index.html'
         * myURL.segments; // = Array = ['dir', 'index.html']
         * myURL.port;     // = '8080'
         * myURL.protocol; // = 'http'
         * myURL.source;   // = 'http://abc.com:8080/dir/index.html?id=255&m=hello#top'
         * ```
         */
        parseURL: function(url) {
            var a = document.createElement('a');
            a.href = url;
            return {
                source: url,
                protocol: a.protocol.replace(':', ''),
                host: a.hostname,
                port: a.port,
                query: a.search,
                params: (function() {
                    var ret = {},
                        seg = a.search.replace(/^\?/, '').split('&'),
                        len = seg.length,
                        i = 0,
                        s;
                    for (; i < len; i++) {
                        if (!seg[i]) {
                            continue;
                        }
                        s = seg[i].split('=');
                        ret[s[0]] = decodeURIComponent(s[1]);
                    }
                    return ret;
                })(),
                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
                hash: a.hash.replace('#', ''),
                path: a.pathname.replace(/^([^\/])/, '/$1'),
                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
                segments: a.pathname.replace(/^\//, '').split('/')
            };
        },
        getUrlHash(url) {
            return this.stringToJson(url || location.hash.replace('#', ''))
        },
        getUrlParams(url) {
            return this.parseURL(url || location.href).params
        },
        /**
         * @method urlJoin
         * @description 连接url
         * @param  {String} url 原url
         * @param  {Object} params 参数
         * @return {String} 
         * @示例1 调用
         * ```js
         * var url = 'http://www.08cms.com'
         *  var params = {a: 1, b: 2};
         *  utils.urlJoin(url, params) // http://www.08cms.com?a=1&b=2
         * ```
         * @示例2 调用
         * ```js
         * var url = 'http://www.08cms.com?c=1'
         *  var params = {a: 1, b: 2};
         *  utils.urlJoin(url, params) // http://www.08cms.com?c=1&a=1&b=2
         * ```
         */
        urlJoin: function(url, params) {
            var isSingleUrl = url.indexOf('?') == -1;
            var str = $.param(params)
            url = str ? (url + (isSingleUrl ? '?' : '&') + str) : url
            return url
        },
        /*
         * @name hash字符串转化为对象
         * @param  {String} name 需要转换的字符串
         * @example
         * stringToJson('a=1&b=1') => {a: 1, b: 1}
         */
        stringToJson(str) {
            var _arr = {};
            if (str) {
                var _json = str.split('&');
                $.each(_json, function (k, v) {
                    var _hash = v.split('=');
                    _arr[_hash[0]] = decodeURIComponent(_hash[1]);
                })
            }
            return _arr;
        },
		/**
         * @method urlTransform
         * @description APP url 替换
         * @return {String} 
         * @示例1 调用
         * ```js
         * utils.urlTransform() // http://www.08cms.com?a=1&b=2
         * ```
         */
        urlTransform: function (path) {
            var _this = this;
            var _path = path ? path : '../';
            $("body").find('a').attr('href', function(i, v) {
                var oUrl = _this.parseURL(v);
                var url;
                var hash = '';
                var L = oUrl.segments.length;
                // 转变需要转变的地址
                if (oUrl.path.indexOf('.htm') == -1 && oUrl.protocol.indexOf('http') == -1 && L >= 2 && oUrl.file != null) {
                    url = _path + oUrl.segments[L - 2] + '/' + oUrl.segments[L - 1] + '.html';
                    if (oUrl.hash) {
                        hash = '#' + oUrl.hash;
                    };
                    return url + oUrl.query + hash;
                } else {
                    return oUrl.source;
                };
            })
        },
        /**
         * @method searchParams
         * @description url参数获取
         * @params {String} name 获取字段
         * @return {String}
         * @示例1 调用
         * ```js
         * utils.urlTransform() // http://www.08cms.com?a=1&b=2
         * ```
         */
        searchParams: function (name) {
            var _this = this;
            var _locationUrl = location.href;
            var _searchParams = _this.parseURL(_locationUrl);
            var _searchParam = _searchParams.query.substr(1);
            var _param;
            var _data;
            if (name) {
                $.each(_searchParam.split('&'), function (k, v) {
                    _data = v.split('=')
                    if (_data[0] == name) {
                        _param = _data[1];
                    };
                })
                return _param;
            } else {
                return _searchParams.query
            }
        },
       /**
         * @method parseJSON
         * @description 解析json字符串
         * @param  {string} jsonStr json字串
         * @return {object} json对象, 解析不成功返回空对象
         * @示例 调用
         * ```js
         * parseJSON('{"a": 1, "b": 1}'); // {"a": 1, "b": 1}
         * parseJSON('{a: 1, b: 1}'); // {"a": 1, "b": 1}
         * parseJSON('aaa'); // {}
         * ```
         */
        parseJSON: function(jsonStr) {
            var obj ={};
            try {
                if (typeof jsonStr == 'object') {
                    obj = jsonStr;
                } else if (typeof jsonStr == 'string' && /^[\[|\{](\s|.*|\w)*[\]|\}]$/.test(jsonStr)) {
                    obj = (new Function('return ' + jsonStr))();
                }
            } catch (err) {}
            return  obj;
        },
        localStorage: {
            /**
             * @method setItem
             * @description  存储值，可设置过期时间
             * @param {string} name 标识
             * @param {string|object} val  值
             * @param {number} time 过期时间 单位天
             * @示例1 调用
             * ```js
             *  utils.localStorage.setItem('aaa', 111, 1);
             * ```
             */
            setItem: function(name, val, time) {
                window.localStorage &&
                    localStorage.setItem(name, val + '||' + (new Date().getTime() + time * (1000 * 60 * 60 * 24)));
            },
            /**
             * @method getItem
             * @description 获取值
             * @param {string} name 标识
             * @return {*} 过期时返回空
             * @示例1 调用
             * ```js
             *  utils.localStorage.getItem('aaa'); // 111
             * ```
             */
            getItem: function(name) {
                if (!window.localStorage) {
                    return '';
                };
                var aVal = (localStorage.getItem(name) || '').split('||');
                if (aVal[1] && aVal[1] < new Date().getTime()) {
                    // 过期
                    localStorage.setItem(name, '');
                    return '';
                } else {
                    return aVal[0];
                }
            },
            /**
             * @method removeItem
             * @description  支持正则删除
             * @param {string} name 标识
             * @示例1 调用
             * ```js
             *  utils.localStorage.removeItem(/^manage-/);
             * ```
             */
            removeItem: function(name) {
                if (!window.localStorage) {
                    return '';
                };
                if (/^\/\D+\/$/.test(name)) {
                    // 支持正则
                    $.each(localStorage, function(k, v) {
                        var key = localStorage.key(k);
                        var reg = new RegExp(name);
                        reg.test(key) && localStorage.removeItem(key);
                    });
                } else {
                    localStorage.removeItem(name);
                }
            }
        }
        
	};

	module.exports = utils;
});