'use strict';

/*!
 * name   : common
 * date : 2017-12-15 11:55:26
 * @description  前台通用js
 */
// @require('babel');
define('common', ['inter', 'utils', 'template', 'cookie'], function (require, exports, module) {

    require('inter');
    var utils = require('utils');
    var template = require('template');

    // 需要依赖另一个模块
    var modules = {
        placeholder: function placeholder() {
            // $('input[data-placeholder="1"], textarea[data-placeholder="1"]').placeholder();
            $('input[placeholder], textarea[placeholder]').placeholder();
        },
        // 二维码
        qrcode: function qrcode() {
            $('[data-qrcode="1"]').each(function (i, el) {
                var $this = $(this);
                var thisData = $this.data();
                var option = {
                    render: 'canvas',
                    background: '#fff',
                    quiet: 2,
                    radius: .5,
                    size: 76
                };
                // 兼容处理
                if (!utils.canvasSupport()) {
                    option.render = 'table';
                }
                // 正常初始化
                var qrcodeInit = function qrcodeInit() {
                    var _option = $.extend(true, {}, option, thisData);

                    $this.qrcode(_option);
                };
                // 增加logo
                if (thisData.logo && utils.canvasSupport()) {
                    var img = new Image();
                    img.src = thisData.logo;

                    img.onload = function () {
                        var $img = $('<img src="' + utils.getBase64Image(img) + '">').hide().appendTo($this);

                        var _option_logo = $.extend(true, {}, option, {
                            ecLevel: 'Q',
                            mode: 4,
                            mSize: .2,
                            image: $img[0] //logo图片
                        }, thisData);

                        $this.qrcode(_option_logo);
                    };

                    img.onerror = qrcodeInit;
                } else {

                    qrcodeInit();
                }
            });
        },
        // 自动完成
        autocomplete: function autocomplete() {
            $('input[data-autocomplete="1"]').each(function () {
                var $this = $(this);
                var thisData = $this.data();
                var curOpt = {
                    serviceUrl: this.form.action,
                    type: "POST", //ajax提交方式（可选GET，POST）默认GET
                    dataType: "json", //数据格式（可选text，json，jsonp）默认text
                    params: {
                        search: 1
                    },
                    //额外查询参数
                    deferRequestBy: 300, //延迟提交 默认为0
                    // ajaxSettings:[{
                    //     global:false,
                    // }],//其他设置
                    // lookup: nhlTeams, //返回本地数据
                    // lookupLimit: 10, //限制显示条数,
                    //autoSelectFirst:true,
                    triggerSelectOnValidInput: false, // 只要input focus就触发 onSelect;
                    onSearchStart: function onSearchStart(params) {
                        params.name = this.value;
                    },
                    formatResult: function formatResult(suggestion, currentValue) {
                        return suggestion.data.name; //返回数据形式设定
                    },
                    onSelect: function onSelect(suggestion) {
                        if ($this.hasEvent('onselect')) {
                            $this.trigger('onselect', [suggestion.data]);
                        } else {
                            window.open(suggestion.data.url);
                        }
                    }
                };

                $this.autocomplete($.extend(true, {}, curOpt, thisData));
            });
        }
        // 统计点击
        /*dcounts: function(opt){
            var $counts = $('[data-detailcounts="1"]');
            if($counts.length >= 1){
                var config = utils.parseJSON($counts.data('config'));
                $.ajax({
                    url: opt.url,
                    type: 'GET',
                    dataType: 'json',
                    data: config,
                });
                
            }
        }*/
    };

    var app = {
        // 通用点击关闭
        closeex: function closeex() {
            $('[data-closeex]').each(function (index, el) {
                var $this = $(this);
                var cfg = utils.parseJSON($this.data('closeex'));

                $this.off('click').on('click', function (e) {
                    $(cfg.target).fadeOut();
                    $(cfg.showTarget).fadeIn();
                    e.stopPropagation();
                    e.preventDefault();
                });
                $(cfg.showTarget).off('click').on('click', function (e) {
                    $(this).fadeOut();
                    $(cfg.target).fadeIn();
                    e.stopPropagation();
                    e.preventDefault();
                });
            });
        },
        gotop: function gotop() {
            var $goTop = $('[data-gotop="1"]');
            var range = $goTop.data('range') || 300;
            var time = $goTop.data('time') || 500;
            $goTop.hide();
            $(window).on('scroll', function (event) {
                if ($(window).scrollTop() > range) {
                    $goTop.fadeIn();
                } else {
                    $goTop.fadeOut();
                }
            });

            $(document).off('.gotop').on('click.gotop', '[data-gotop="1"]', function () {
                $('html, body').stop(true).animate({
                    scrollTop: 0
                }, time);
            });
        },
        ajax: function ajax(args) {
            var _args = $.extend(true, {}, {
                url: null,
                type: 'GET',
                dType: 'json',
                data: {}
            }, args);

            return $.ajax({
                url: _args.url,
                type: _args.type,
                dataType: _args.dType,
                data: _args.data
            });
        },

        /**
         * @method login
         * @description 网站头部登录状态
         * @param  {String} url 请求登录信息的地址                      
         * @param  {String} logoutUrl 退出的地址                     
         * @param  {Object} backfn  回调函数   
         * @example      
         * ```html
         * <div class="login" data-login="1" data-config="{tpl: '#logintpl', url: 'aaa/login', logoutUrl : 'aaa/logout', modal: '.winlogin' }">加载中...</div>
         * ```
         * @js调用
         * ```js
         *logincom.init({
         * 
         *});
         *```
         *@回调函数(回调函数就是在调用的地方用on()绑定一个回调名为'backfn'的函数)
         *```js
         *$('#login').on('backfn', function(){
         *       $('.login-info').hoverSlider({
         *              offsetx:0,
         *              offsety:40,
         *              obj:'.login-operate'
         *       });
         *  });
         *
         * ```
         * @信息说明
         * 还需要有一个模板的，这里仅供参考，具体可以看common/login
         * ```js
         * <script id="logintpl" type="text/html">
         *      <div class="login-in">
         *            <div class="login-info" > <i class="font-icons-2"></i>
         *                <span>name</span> <i class="font-icons-1"></i>
         *            </div>
         *        </div>
         *        <div class="login-out">
         *            <a href="house/login"  data-modal=".winlogin"  title="登录"><i class="font-icons-2"></i>
         *                登录
         *            </a>
         *            <a href="register/index" title="注册" target="_blank"><i class="font-icons-30"></i>
         *                注册
         *            </a>
         *        </div>
         *</script>
         * ```
         */
        login: function login(opt) {
            var _self = this;
            _self.$login = $('[data-login]').eq(0);
            var config = utils.parseJSON(_self.$login.data('config'));
            _self.url = config.url;
            _self.logoutUrl = config.logoutUrl;
            _self.loginUrl = config.loginUrl;
            _self.modal = config.modal;

            $.ajax({
                url: _self.url,
                type: 'GET',
                dataType: 'jsonp',
                data: {
                    datatype: 'jsonp'
                }

            }).done(function (result) {
                // result;
                app.finish(result);
                _self.$login.trigger('backfn', [result]);
            }).fail(function () {}).always(function () {});

            $(document).off('.logout').on('click.logout', '[data-logout]', function () {
                var $login = $(this).closest('[data-login]');

                $.ajax({
                    url: _self.logoutUrl,
                    type: 'GET',
                    beforeSend: function beforeSend() {
                        $login.html('退出中...');
                    }
                }).done(function (result) {
                    // console.log(result);
                    _self.finish(result);
                }).fail(function () {
                    // console.log("error");
                }).always(function () {
                    // console.log("complete");
                });
            });
        },

        finish: function finish(data) {

            var config = utils.parseJSON(this.$login.data('config'));

            var _config = $.extend(true, {}, {
                tpl: '#login'
            }, config);

            this.$login.html(template(_config.tpl.slice(1), {
                data: data
            }));
        }
        // 需要手动执行
    };var app1 = {
        form: function form(opt) {
            var _self = this;
            // 保存默认配置
            _self.defCfg = opt;
            // 直接加载
            var $form = $('[data-form-config]');
            $form.each(function (i, el) {
                var $el = $(this);
                var config = _self.config = utils.parseJSON($el.data('form-config'));
                if (config.type == 'load') {
                    _self.formAjax($el);
                }
            });
            // 点击加载
            $(document).off('.formModal').on('click.formModal', '.btn-jqModal-ex', function (e) {
                var $modal = $form.filter($(this).attr('href'));
                var config = _self.config = utils.parseJSON($modal.data('form-config'));

                // 弹窗提示
                $modal.html('<div style="text-align: center;padding: 20px"><i class="ico font-modal-load"></i>玩命加载中...</div>').jqModal(config.modal || {});

                _self.formAjax($modal).always(function () {
                    // console.log("complete");
                    // 重新定位
                    $modal.jqModal('setPos');
                    $(e.target).trigger('shown');
                });

                return false;
            });
        },
        formAjax: function formAjax($el) {
            var _self = this;
            var config = _self.config;
            var ajaxOpt = $.extend(true, {}, {
                type: 'GET',
                dataType: 'html'
            }, _self.defCfg.ajax, config.ajax);

            return $.ajax(ajaxOpt).done(function (data) {
                $el.html(data);
            }).fail(function () {
                $el.html('服务器错误！');
                // console.log("error");
            });
        },

        /**
         * @method adcount
         * @description  统计广告点击的次数
         * @param {String} saveUrl   统计提交的地址：控制器+方法
         * @param {String} data-ad   html上的广告统计配置,包括models,id,field
         * @param {String} models    统计的模型
         * @param {String} id        统计广告的id
         * @param {String} field     统计的字段
         * 
         * @example 调用
         * ```js
         *  adcount.init({
         *           saveUrl: 'viewinter/vote'
         *         });                
         * ```
         * ```html
         * <a data-ad="{models: 'pushs',id:,field: 'clicks' }" ></a>
         * ```
        */
        adcount: function adcount(opt) {
            var _opt = $.extend(true, {}, {
                saveUrl: ''
            }, opt);

            $(document).off('.ad').on('click.ad', '[data-ad]', function (event) {
                var $this = $(this);
                var data = utils.parseJSON($this.data('ad'));

                if (!$.cookie('adcount-' + data.id)) {
                    $.ajax({
                        url: _opt.saveUrl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            models: data.models,
                            id: data.id,
                            field: data.field
                        }
                    }).done(function () {
                        $.cookie('adcount-' + data.id, 1);
                    });
                };
            });
        },


        /**
         * @method getcounts
         * @description 动态调用统计的数据
         * @param {Object} counts - html标签上的配置信息
         * @param {String} counts.models - 统计的模型, 根据模型的不同发送不同的请求
         * @param {String} counts.field  - 需要统计的字段
         * @param {Number} counts.id     - 要统计的数据的id
         * @param {String} counts.url    - 统计请求的地址
         * @param {Boolean} [counts.showload=1] - 是否显示加载图标
         * @param {String} [counts.service=house] - 这里的models是服务名，不是字段中的模型, 例：`data-counts="{models: 'news', service: 'auto', field: 'clicks', id: '1'}"`
         * 
         * @example
         * ```js
         * // 默认页面上已调用，可以不写
         * getcounts.init({
         *     url: '("igetcounts/index")|U'
         * });
         * ```
         * ```html
         * <span data-counts="{models: 'news', field: 'clicks', id: '1'}"></span>
         * ```
         */
        getcounts: function getcounts(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                showload: 1,
                url: null
            }, opt);

            var $counts = $('[data-counts]');
            if (_opt.showload === 1) {
                $counts.addClass('LOADING');
            }
            var oParamsArray = [];
            var oParams = {};
            var models = {};
            var service = {};
            $counts.each(function (i, el) {
                var $this = $(this);
                var countsParams = utils.parseJSON($this.data('counts'));
                if ($.inArray(countsParams.models, oParamsArray) == -1) {
                    oParamsArray.push(countsParams.models);
                    oParams[countsParams.models] = [];
                    models[countsParams.models] = [];
                    service[countsParams.models] = [];
                }
                // 获取服务名前缀
                service[countsParams.models] = countsParams.service || 'house';

                $this.addClass(countsParams.models + '-' + countsParams.id);
                // 存储同一个模型下的id
                if ($.inArray(countsParams.id, oParams[countsParams.models]) == -1) {

                    oParams[countsParams.models].push(countsParams.id);
                }

                // 存储同一个模型下的字段
                if ($.inArray(countsParams.field, models[countsParams.models]) == -1) {
                    models[countsParams.models].push(countsParams.field);
                }
            });

            $.each(oParams, function (k, val) {
                var ids = val.join(',');
                var fields = models[k].join(',');

                $.ajax({
                    url: _opt.url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        models: k,
                        service: service[k],
                        id: ids,
                        field: fields
                    },
                    beforeSend: function beforeSend() {}
                }).done(function (d) {
                    if ($.isEmptyObject(d)) {
                        $.each($counts, function (i, el) {
                            $el = $(el);
                            $el.removeClass('LOADING');
                            $el.replaceWith(0);
                        });

                        return;
                    }
                    $.each(d, function (k, v) {
                        var $els = $counts.filter('.' + v.models + '-' + v.id);
                        $.each($els, function (i, el) {
                            var $el = $(el);
                            var countsParams1 = utils.parseJSON($el.data('counts'));
                            var field = v[countsParams1.field] || 0;
                            $el.removeClass('LOADING');
                            $el.replaceWith(field);
                        });
                    });
                }).fail(function () {
                    var $els;
                    for (var i = 0; i < ids.length; i++) {
                        $els = $counts.filter('.' + k + '-' + ids[i]);
                        $.each($els, function (i, el) {
                            $el = $(el);

                            $el.removeClass('LOADING');
                            $el.replaceWith(0);
                        });
                    }
                }).always(function () {});
            });
        }
    };
    $(function () {
        // 按需加载并初始化
        $.each(modules, function (k, v) {
            var $k = $('[data-' + k + '="1"]');
            if ($k.length) {
                require.async(k, v);
            };
        });

        // 按需初始化
        $.each(app, function (k, v) {
            var $k = $('[data-' + k + ']');
            if ($k.length) {
                app[k]();
            };
        });
    });

    module.exports = $.extend({}, app1, app, modules);
});
//# sourceMappingURL=http://localhost:8888/public/js/common.js.map
