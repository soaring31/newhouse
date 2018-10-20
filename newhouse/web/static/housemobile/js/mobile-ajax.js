/*!
 * @name mobile-ajax
 * @author ahuing
 * @date 2018-02-02 17:37:32
 * @description ajax模块
 */

define(['utils'], function(require, exports, module) {
    var utils = require('utils');
    var ajaxSubmitLock = true;
    var jsonType = 'application/json';
    var htmlType = 'text/html';
    var rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
    var scriptTypeRE = /^(?:text|application)\/javascript/i;
    var xmlTypeRE = /^(?:text|application)\/xml/i;
    var blankRE = /^\s*$/;
    var noop = function() {};
    var ajaxSubmit = function(cfg) {
        var _xhr = '';
        // 默认值
        var v = {
            type: 'GET',
            beforeSend: noop,
            success: noop,
            error: noop,
            complete: noop,
            context: null,
            xhr: function(protocol) {

                // 判断是否启用html5+的方式请求
                if (cfg.crossDomain !== 'undefined' && cfg.crossDomain === true) {
                    _xhr = new plus.net.XMLHttpRequest();
                } else {
                    _xhr = new window.XMLHttpRequest();
                };
                return _xhr;
            },
            accepts: {
                script: 'text/javascript, application/javascript, application/x-javascript',
                json: jsonType,
                xml: 'application/xml, text/xml',
                html: htmlType,
                text: 'text/plain'
            },
            timeout: 0,
            data: {},
            processData: true,
            cache: true
        };
        // 环境判断
        var plusReady = function(callback) {
            if(window.plus) {
                setTimeout(function() { //解决callback与plusready事件的执行时机问题(典型案例:showWaiting,closeWaiting)
                    callback();
                }, 0);
            } else {
                document.addEventListener("plusready", function() {
                    callback();
                }, false);
            }
            return this;
        };
        // 参数整理
        var param = function(obj, traditional) {
            var params = [];
            params.add = function(k, v) {
                this.push(encodeURIComponent(k) + '=' + encodeURIComponent(v));
            };
            serialize(params, obj, traditional);
            return params.join('&').replace(/%20/g, '+');
        };
        // 请求发送之前执行
        var ajaxBeforeSend = function(xhr, settings) {
            var context = settings.context
            if(settings.beforeSend.call(context, xhr, settings) === false) {
                return false;
            }
        };
        // 请求成功执行
        var ajaxSuccess = function(data, xhr, settings) {
            settings.success.call(settings.context, data, 'success', xhr);
            ajaxComplete('success', xhr, settings);
        };
        // 请求失败执行
        // type: "timeout", "error", "abort", "parsererror"
        var ajaxError = function(error, type, xhr, settings) {
            settings.error.call(settings.context, xhr, type, error);
            ajaxComplete(type, xhr, settings);
        };
        // 请求完成执行
        // status: "success", "notmodified", "error", "timeout", "abort", "parsererror"
        var ajaxComplete = function(status, xhr, settings) {
            settings.complete.call(settings.context, xhr, status);
        };

        // 参数序列化
        var serialize = function(params, obj, traditional, scope) {
            var type, array = $.isArray(obj),
                hash = $.isPlainObject(obj);
            $.each(obj, function(key, value) {
                type = $.type(value);
                if(scope) {
                    key = traditional ? scope :
                        scope + '[' + (hash || type === 'object' || type === 'array' ? key : '') + ']';
                }
                // handle data in serializeArray() format
                if(!scope && array) {
                    params.add(value.name, value.value);
                }
                // recurse into nested objects
                else if(type === "array" || (!traditional && type === "object")) {
                    serialize(params, value, traditional, key);
                } else {
                    params.add(key, value);
                }
            });
        };
        // 参数序列化
        var serializeData = function(options) {
            if(options.processData && options.data && typeof options.data !== "string") {
                var contentType = options.contentType;
                if(!contentType && options.headers) {
                    contentType = options.headers['Content-Type'];
                }
                if(contentType && ~contentType.indexOf(jsonType)) { //application/json
                    options.data = JSON.stringify(options.data);
                } else {
                    options.data = param(options.data, options.traditional);
                }
            }
            if(options.data && (!options.type || options.type.toUpperCase() === 'GET')) {
                options.url = appendQuery(options.url, options.data);
                options.data = undefined;
            }
        };
        // 链接拼接
        var appendQuery = function(url, query) {
            if(query === '') {
                return url;
            }
            return(url + '&' + query).replace(/[&?]{1,2}/, '?');
        };
        // 返回类型
        var mimeToDataType = function(mime) {
            if(mime) {
                mime = mime.split(';', 2)[0];
            }
            return mime && (mime === htmlType ? 'html' :
                mime === jsonType ? 'json' :
                scriptTypeRE.test(mime) ? 'script' :
                xmlTypeRE.test(mime) && 'xml') || 'text';
        };
        // 请求方法
        var ajax = function(url, options) {
            if(typeof url === "object") {
                options = url;
                url = undefined;
            }
            var settings = options || {};
            settings.url = url || settings.url;

            for(var key in v) {
                if(settings[key] === undefined) {
                    settings[key] = v[key];
                }
            }
            // 自定义
            // settings.url = (BASE_URL || '') + settings.url;
            settings.data._isApp = url_isApp || 0;

            // 设置分站id，app中使用
            if (utils.localStorage.getItem('_area')) {
                settings.data._area = utils.localStorage.getItem('_area');
            };

            serializeData(settings);
            var dataType = settings.dataType;

            if(settings.cache === false || ((!options || options.cache !== true) && ('script' === dataType))) {
                settings.url = appendQuery(settings.url, '_=' + $.now());
            }
            var mime = settings.accepts[dataType && dataType.toLowerCase()];
            var headers = {};
            var setHeader = function(name, value) {
                headers[name.toLowerCase()] = [name, value];
            };
            var protocol = /^([\w-]+:)\/\//.test(settings.url) ? RegExp.$1 : window.location.protocol;
            var xhr = settings.xhr(settings);
            var nativeSetHeader = xhr.setRequestHeader;
            var abortTimeout;

            setHeader('X-Requested-With', 'XMLHttpRequest');
            setHeader('Accept', mime || '*/*');
            if(!!(mime = settings.mimeType || mime)) {
                if(mime.indexOf(',') > -1) {
                    mime = mime.split(',', 2)[0];
                }
                xhr.overrideMimeType && xhr.overrideMimeType(mime);
            }
            if(settings.contentType || (settings.contentType !== false && settings.data && settings.type.toUpperCase() !== 'GET')) {
                setHeader('Content-Type', settings.contentType || 'application/x-www-form-urlencoded');
            }
            if(settings.headers) {
                for(var name in settings.headers)
                    setHeader(name, settings.headers[name]);
            }
            xhr.setRequestHeader = setHeader;

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    xhr.onreadystatechange = noop;
                    clearTimeout(abortTimeout);
                    var result, error = false;
                    var isLocal = protocol === 'file:';
                    if((xhr.status >= 200 && xhr.status < 300) || xhr.status === 304 || (xhr.status === 0 && isLocal && xhr.responseText)) {
                        dataType = dataType || mimeToDataType(settings.mimeType || xhr.getResponseHeader('content-type'));
                        result = xhr.responseText;
                        try {
                            // http://perfectionkills.com/global-eval-what-are-the-options/
                            if(dataType === 'script') {
                                (1, eval)(result);
                            } else if(dataType === 'xml') {
                                result = xhr.responseXML;
                            } else if(dataType === 'json') {
                                result = blankRE.test(result) ? null : $.parseJSON(result);
                            }
                        } catch(e) {
                            error = e;
                        }

                        if(error) {
                            ajaxError(error, 'parsererror', xhr, settings);
                        } else {
                            ajaxSuccess(result, xhr, settings);
                        }
                    } else {
                        // console.log(3245)
                        var status = xhr.status ? 'error' : 'abort';
                        var statusText = xhr.statusText || null;
                        if(isLocal) {
                            status = 'error';
                            statusText = '404';
                        }
                        ajaxError(statusText, status, xhr, settings);
                    }
                }
            };
            if(ajaxBeforeSend(xhr, settings) === false) {
                xhr.abort();
                ajaxError(null, 'abort', xhr, settings);
                return xhr;
            }

            if(settings.xhrFields) {
                for(var name in settings.xhrFields) {
                    xhr[name] = settings.xhrFields[name];
                }
            }

            var async = 'async' in settings ? settings.async : true;

            xhr.open(settings.type.toUpperCase(), settings.url, async, settings.username, settings.password);

            for(var name in headers) {
                if(headers.hasOwnProperty(name)) {
                    nativeSetHeader.apply(xhr, headers[name]);
                }
            }
            if(settings.timeout > 0) {
                abortTimeout = setTimeout(function() {
                    xhr.onreadystatechange = noop;
                    xhr.abort();
                    ajaxError(null, 'timeout', xhr, settings);
                }, settings.timeout);
            }
            xhr.send(settings.data ? settings.data : null);
            return xhr;
        };
        // 环境准备就绪才执行返回
        if (cfg.crossDomain !== 'undefined' && cfg.crossDomain === true) {
             plusReady(function () {
                return ajax(cfg)
            })
        } else {
            return ajax(cfg)
        };
       
    };

    module.exports = ajaxSubmit;
});
//# sourceMappingURL=http://localhost:8888/js/..\..\..\..\src\CoreBundle\Resources\src\js\mobileAjax.js.map

//# sourceMappingURL=mobile-ajax.js.map
