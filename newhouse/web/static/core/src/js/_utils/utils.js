/*!
 * @name {{ name }}
 * @author {{ author }}
 * @date {{ now()|date('Y-m-d') }}
 * @description 通用工具类
 */
/**
 * @module {{ name }}
 * 
 * @require('babel')
 */
define('{{ name }}', function(require, exports, module) {
    var self;

    (function(out) {
        module.exports = out;
    })({
        //调用函数
        /*
         * 调用函数
         * @page 函数名， par参数名
         * @return function
         */
        init: function(page, par) {
                //初始化加載的腳本
                // self = this;
                // self[page](par);

                try {
                    self = this;
                    self[page](par);
                } catch (e) {
                    console.error(e);
                }
            },
        searchEx: function($el) {
            var el = $el[0];
            var name = $el.data('name') || 'name';
            var searchmode = name.split(','),
                aSql = [];

            for (var i = 0; i < searchmode.length; i++) {
                aSql.push($.trim(searchmode[i]) + ',like,' + '%' + el.value + '%');
            }

            $el.data({
                'name': 'name',
                'value': el.value ? 'orX|' + aSql.join('|') : '',
            });
        },
        /**
         * @method parseURL
         * @description 解析url
         * @param  {string} url 要解析的url
         * @return {object}     相关信息
         * @example 示例 调用
         * ```js
         * var myURL = parseURL('http://abc.com:8080/dir/index.html?id=255&m=hello#top=1');
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
         * myURL.base;   // = 'http://abc.com:8080/dir/index.html'
         * ```
         */
        parseURL: function(url) {
            var a = document.createElement('a');
            a.href = url || location.href;
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
                        ret[s[0]] = s[1];
                    }
                    return ret;
                })(),
                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
                hash: a.hash.replace('#', ''),
                hashParams: this.route(a.hash),
                path: a.pathname.replace(/^([^\/])/, '/$1'),
                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
                segments: a.pathname.replace(/^\//, '').split('/'),
                base: a.href.split('?')[0]
            };
        },
        /**
         * @method parseJSON
         * @description 解析json字符串
         * @param  {string} jsonStr json字串
         * @return {object} json对象, 解析不成功返回空对象
         * @example 示例 调用
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
        canvasSupport: function canvasSupport() {
            return !!document.createElement('canvas').getContext;
        },
        /**
         * @method getBase64Image
         * @description 将图片转为base64格式
         * @param  {Object} img 图片对象
         * @return {String}     base64代码
         * @example 示例 调用
         * ```js
         * var img = new Image();
         * img.src = '1.jpg';
         * img.onload = function() {
         *   getBase64Image(img);
         *   //你的代码
         *   }
         * ```
         */
        getBase64Image: function(img) {
            var canvas = document.createElement("canvas");
            var dataURL;

            canvas.width = img.width;
            canvas.height = img.height;

            if (canvas.getContext) {
                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, img.width, img.height);

                var dataURL = canvas.toDataURL("image/png");
            }
            canvas = null
            return dataURL;
            // return dataURL.replace("data:image/png;base64,", "");
        },
        /**
         * @method urlJoin
         * @description 连接url
         * @param  {String} url 原url
         * @param  {Object} params 参数
         * @return {String}
         * @example 示例1 调用
         * ```js
         * var url = 'http://www.08cms.com'
         *  var params = {a: 1, b: 2};
         *  utils.urlJoin(url, params) // http://www.08cms.com?a=1&b=2
         * ```
         * @example 示例2 调用
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
        /**
         * @method route
         * @description 路由设置
         * @param {String|Number} i `i`以1开始<br> 1. i为数字时v存在，设置路由<br> 2. i为数字时v不存在，获取路由<br> 3. 都不存在返回当前路由的数据<br> 4. i为字符串，解析路由<br> 5. i=setDefault 设置路由默认值
         * @param  {String} [v] 存在时为设置路由，否则为获取路由值
         * @param  {String} [joinStr] 末尾再加个字符
         * @return {Array} 解析过的路由参数
         *
         * @example 示例1 设置路由
         * ```js
         *  utils.route(1, 'a');
         *  console.log(location.hash); // 'http://www.xxx.com#/a'
         * ```
         * @example 示例2 获取路由的数组
         * ```js
         * location.hash = "http://www.xxx.com#/a/b";
         * var url = utils.route();
         * console.log(url); // ['#', 'a', 'b'];
         * ```
         * @example 示例3 获取路由值（通过索引）
         * ```js
         * location.hash = "http://www.xxx.com#/a/b";
         * var url = utils.route(1);
         * console.log(url); // 'a';
         * ```
         * @example 示例4 解析路由字串
         * ```js
         * var url = "#/a/b";
         * var aUrl = utils.route(url);
         * console.log(aUrl); // ['#', 'a', 'b'];
         * ```
         */
        route: function(i, v, joinStr) {
            var sHash = location.hash;

            if(i && !$.isNumeric(i)) {
                // 解析url
                sHash = i;
            }
            var arrHash = sHash ? sHash.split('/') : ['#'];
            // 索引0时亦可以设置
            if (v || v === 0) {
                // set
                var nArrHash = arrHash.slice(0, i);
                nArrHash.push(v);
                joinStr && nArrHash.push(joinStr);
                location.hash = nArrHash.join('/');
            } else {
                // get
                return (i && $.isNumeric(i)) ? arrHash[i] : arrHash;
            }
        },
        /**
         * @method copySelect
         * @description 文本框内容
         * @return {String}
         * @example 示例1 调用
         * ```js
         *  utils.copySelect();
         * ```
         * ``` html
         *  <input id="copySelect" name="copySelect" value="需要复制的内容" />
         *  <a data-copy="copySelect">点击复制</a>
         * ```
         */
        copySelect: function () {
            $(document).off('.copySelect').on('click.copySelect', '[data-copy]', function () {
                var $this = $(this);
                var data = $this.data();
                var _selectTag = data.copy;
                $("#" + _selectTag).select();
                try {
                    document.execCommand('Copy');
                    $.jqModal.tip('已复制到剪贴板!');
                } catch (e) {
                    $.jqModal.tip('浏览器不支持自动复制，请手动复制!');
                }
                return false;
            });
        },
        localStorage: {
            /**
             * @method setItem
             * @description  存储值，可设置过期时间
             * @param {string} name 标识
             * @param {string|object} val  值
             * @param {number} time 过期时间 单位天
             * @example 示例1 调用
             * ```js
             *  utils.localStorage.setItem('aaa', 111, 1);
             * ```
             */
            setItem: function(name, val, time) {
                try {
                    window.localStorage &&
                        localStorage.setItem(name, val + '||' + ($.now() + time * (1000 * 60 * 60 * 24)));
                } catch (err) {
                    // console.warn(err);
                }
            },
            /**
             * @method getItem
             * @description 获取值
             * @param {string} name 标识
             * @return {*} 过期时返回空
             * @example 示例1 调用
             * ```js
             *  utils.localStorage.getItem('aaa'); // 111
             * ```
             */
            getItem: function(name) {
                try {
                    if (!window.localStorage) {
                        return '';
                    };
                    var aVal = (localStorage.getItem(name) || '').split('||');
                    if (aVal[1] && aVal[1] < $.now()) {
                        // 过期
                        localStorage.setItem(name, '');
                        return '';
                    } else {
                        return aVal[0];
                    }
                } catch (err) {
                    return '';
                }
            },
            /**
             * @method removeItem
             * @description  支持正则删除
             * @param {string} name 标识
             * @example 示例1 调用
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
                        var reg = new RegExp(name);
                        reg.test(k) && localStorage.removeItem(k);
                    });
                } else {
                    localStorage.removeItem(name);
                }
            }
        },
        /**
         * @method unique
         * @description  将给定的数组去重
         * @param {Array} arr 要处理的数据
         * @param {String} key 数据项里的键值
         * @example 示例1 调用
            ```js
            var data = [1,2,3,3,2,1];
            console.log(JSON.stringify(data));  
            console.log(unique(data)); 
            console.log('');

            data = [{name: "a", value: ""}, {name: "a", value: "1"}, {name: "a", value: "2"}, {name: "b", value: ""}, {name: "b", value: "1"}, {name: "b", value: "2"}];
            console.log(JSON.stringify(data), 'name');  
            console.log(unique(data, 'name'));  
            console.log('');

            data = [{name: "aaa", value: ""}, {name: "bbb", value: "111"}];
            console.log(JSON.stringify(data), 'name');  
            console.log(unique(data, 'name')); 
            console.log('');

            data = [{name: "aaa", value: "111"}, {name: "bbb", value: "111"}, {name: "aaa", value: ""}];
            console.log(JSON.stringify(data), 'name');  
            console.log(unique(data, 'name')); 
            console.log('');

            data = [{name: "aaa", value: "111"}, {name: "bbb", value: "111"}, {name: "aaa", value: "333"}];
            console.log(JSON.stringify(data), 'value');  
            console.log(unique(data, 'value')); 
            console.log('');

            data = [{name: "aaa[]", value: "111"}, {name: "aaa[]", value: "222"}];
            console.log(JSON.stringify(data), 'name');  
            console.log(unique(data, 'name')); 
            // 打印结果
            [1,2,3,3,2,1]
            [1,2,3]
            
            [{"name":"aaa","value":""},{"name":"bbb","value":"111"},{"name":"aaa","value":"333"},{"name":"bbb","value":"222"}] name
            [{"name":"bbb","value":"111"},{"name":"aaa","value":"333"}]
            
            [{"name":"aaa","value":""},{"name":"bbb","value":"111"}] name
            [{"name":"aaa","value":""},{"name":"bbb","value":"111"}]
            
            [{"name":"aaa","value":"111"},{"name":"bbb","value":"111"},{"name":"aaa","value":""}] name
            [{"name":"aaa","value":"111"},{"name":"bbb","value":"111"}]
            
            [{"name":"aaa","value":"111"},{"name":"bbb","value":"111"},{"name":"aaa","value":"333"}] value
            [{"name":"aaa","value":"111"},{"name":"aaa","value":"333"}]
            
            [{"name":"aaa[]","value":"111"},{"name":"aaa[]","value":"222"}] name
            [{"name":"aaa[]","value":"111"},{"name":"aaa[]","value":"222"}]
            ```
         */
        unique: function(arr, key) {
            // 保存每项的状态
            var oNames  = {};
            // 保存每项的索引
            var oIndex  = {};
            var key     = key || 'name';
            // 新数据
            var matches = [];
            var len     = arr.length;
            var item;

            for (var i = 0; i < len; i++) {
                item = arr[i];
                if (typeof item == 'object') {
                // 对象数组
                    // 多选
                    if (/\[\]$/.test(item[key])) {
                        matches.push(item);
                    } else {
                    // 单选
                        // oNames[name]
                        if (!oNames.hasOwnProperty(item[key])) {
                            // console.log(item[key], i);
                            // 前面没有key
                            // 加入当前的
                            oNames[item[key]] = item['value'];
                            // 当前的索引值
                            oIndex[item[key]] = matches.length;
                            matches.push(item);
                        } else if (!oNames[item[key]]) {
                            // 前面有key但为空
                            // 删除前面的
                            matches.splice(oIndex[item[key]], 1);
                            // 加入当前的
                            oNames[item[key]] = item['value'];
                            // 当前的索引值
                            oIndex[item[key]] = matches.length;
                            matches.push(item);
                        }
                    }
                } else if (!oNames[item]) {
                    // 一维数组
                    oNames[item] = 1;
                    matches.push(item);
                }
            }

            return matches;
        },
        // {a: 1, b: 1} => [{name: 'a', value: 1}, {name: 'b', value: 1}]
        objectToSerializeArray: function(obj) {
            return $.map(obj || [], function(v, k){
                return {name: k, value: v};
            })
        },
        // [{name: 'a', value: 1}, {name: 'b', value: 1}] => {a: 1, b: 1}
        serializeArrayToObject: function(arr) {
            var obj = {};
            $.each(arr, function(i, item) {
                if (item.value) {
                    if (/(.+)\[\]$/.test(item.name)) {
                        obj[RegExp.$1] = (obj[RegExp.$1] ? obj[RegExp.$1] + ',' : '') + item.value;
                    } else {
                        obj[item.name] = item.value;
                    }
                }
            });
            return obj;
        },
        /**
         * @method getImgSize
         * @description  获取图片的显示大小
         * @param {Object} img 对象
         * @param {Number} maxW 允许的最大宽度
         * @param {Number} maxH 允许的最大高度
         */
        getImgSize: function(img, maxW, maxH) {
            var nH, nW, _W, _H;

            nW = _W = img.width;
            nH = _H = img.height;

            if (_W > 0 && _H > 0) {
                if (_W / _H >= maxW / maxH && _W > maxW) {
                    nW = maxW;
                    nH = parseInt(_H * maxW / _W);
                } else if (_H > maxH) {
                    nH = maxH;
                    nW = parseInt(_W * maxH / _H);
                }
            }
            return [nW, nH];
        },

        /**
         * @method url
         * @description URL转义
         * @param  {string} 待转义URL地址
         * @return {string} 返回正常的网址
         * @example 示例 调用
         * ```js
         * url('http://dev.08cms.com/bundlebindforms/comparison?formid=houses&amp;models=houses&amp;id=1'); //http://dev.08cms.com/bundlebindforms/comparison?formid=houses&models=houses&id=1
         * ```
         */

        url: function(url) {
            return $(document.createElement('div')).html(url).text();
        },

        /**
         * @method browser
         * @description 获取当时浏览器的相关属性，名称和版本, 用法和jquery 1.9以前的版本一样
         * @return {Object} browser对象
         * @example 示例
         * ```js
         * utils.browser().msie // true 当前为ie浏览器
         * utils.browser().version == '6.0' // true 当前浏览器版本6.0
         * ```
         */
        browser: function() {
            var browser = {},
                ua = window.navigator.userAgent,
                browserMatch = uaMatch(ua);

            if (browserMatch.browser) {
                browser[browserMatch.browser] = true;
                browser.version = browserMatch.version;
            }
            return browser;
        },

        /**
         * @method parseTree
         * @description 根据第一个找到整个家族
         * @param  {Array} data 待处理的数据
         * @param {Number} pid=0 从哪个id开始找
         * @return {Object} result 结果
         * @return {Array} result.data 处理的数据
         * @return {Number} result.level 共多少层
         * @example 示例
         * ```js
         * var data = [
         *     {
         *         id: 1,
         *         pid: 0
         *     },
         *     {
         *         id: 2,
         *         pid: 1
         *     },
         *     {
         *         id: 3,
         *         pid: 2
         *     },
         *     {
         *         id: 4,
         *         pid: 2
         *     },
         * ]
         * utils.parseTree(data, 0);
         * //输出数据
         * {
         *     id: 1,
         *     pid: 0,
         *     children: [
         *       {
         *         id: 2,
         *         pid: 1,
         *         children: [
         *            {
         *             id: 3,
         *             pid: 2,
         *             children: []
         *            },
         *            {
         *             id: 4,
         *             pid: 2,
         *             children: []
         *            }
         *         ]
         *       }
         *     ]
         * }
         * ```
         */
        parseTree: function(data, pid) {
            let _self = this;
            let _level = 1;
            let tree = function(data, pid, level) {
                let temp;
                let result = []
                level      = level || 0;
                pid        = pid || 0;
                level++;
                $.each(data, function(k, item) {
                    if (item.pid == pid) {
                        temp = tree(data, item.id, level);
                        var newItem = item;
                        if (temp.length > 0) {
                            newItem.children = temp;
                        } else {
                            newItem.children = [];
                        }
                        newItem.level = level;
                        _level = _level < level ? level : _level;
                        result.push(newItem);
                    }
                });

                return result;
            }

            return {
                data: tree(data, pid),
                level: _level
            };
        },
        /**
         * @method getTreePids
         * @description 根据ID找到所有父ID, 包括当前ID
         * @param  {Array} data 待处理的数据
         * @param {Number} pid 从哪个id开始找
         * @return {Array}
         * @example 调用
         * ```js
         * var data = [
         *     {
         *         id: 1,
         *         pid: 0
         *     },
         *     {
         *         id: 2,
         *         pid: 1
         *     },
         *     {
         *         id: 3,
         *         pid: 2
         *     },
         *     {
         *         id: 4,
         *         pid: 2
         *     },
         * ]
         * utils.getTreePids(data, 4)
         * // [4, 3, 2, 1]
         * ```
         */
        getTreePids: function(data, id) {
            let result = [id];
            let getPid = function(data, id) {
                $.each(data, function(k, item) {
                        if (item.id == id && item.pid) {
                            result.push(item.pid)
                            getPid(data, item.pid)
                        }
                    });
            }
            getPid(data, id);
            return result;
        },
        /**
         * @method template
         * @description 最简单的template，比artTemplate简单
         * @param  {String} tpl  模板代码
         * @param  {Object} data 数据
         * @return {String}      解析好的代码
         * @example
         * ```js
         * utils.template('<a href="{url}">{title}</a>', {url: 'http://www.a.com', title: '标题'})
         * ```
         * @example
         * 解析后
         * ```html
         * <a href="http://www.a.com">标题</a>
         * ```
         */
        template: function(tpl, data) {
            var re = /{([^{<>]+)}/g;
            var match;
            var _tpl = tpl;
            while (match = re.exec(tpl)) {
                _tpl = _tpl.replace( match[0] , data[match[1]] || '' );
            }
            return _tpl.replace(/[\r\t\n]/g, " ");
        }

    })

    // 浏览器版本
    function uaMatch(ua) {
        var webkit = /(webkit)\/([\w.]+)/,
            opera = /(opera)(?:.*version)?[ \/]([\w.]+)/,
            msie = /(msie) ([\w.]+)/,
            mozilla = /(mozilla)(?:.*? rv:([\w.]+))?/;

        ua = ua.toLowerCase();

        var match = webkit.exec(ua) || opera.exec(ua) || msie.exec(ua) || ua.indexOf("compatible") < 0 && mozilla.exec(ua) || [];

        return {
            browser: match[1] || "",
            version: match[2] || "0"
        };
    }

});


