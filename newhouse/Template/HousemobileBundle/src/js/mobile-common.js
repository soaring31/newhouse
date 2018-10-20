/**
 * 通用库
 * @description 手机端通用库
 */
// @require('babel');
define(['ajax', 'utils'], function(require, exports, module) {
    var ajax = require('ajax');
    var utils = require('utils');
    // 倒计时
    var countDown = function(senconds, $el, tpl) {
        var $btnTxt = $el.find('.code-btn-text');
        if (senconds > 1) {
            $btnTxt.html(tpl ? tpl.replace('%s%', senconds--) : '(' + senconds-- + ')秒后重新发送');
            $el.addClass('disabled');
            setTimeout(function() {
                countDown(senconds, $el, tpl);
            }, 1000);
        } else {
            $btnTxt.html(($el.data('title') || '短信验证码'))
            $el.removeClass('disabled');
        }
    }
    
    var app = {
        formInit: function(opt) {
            var _self = this;
            var _opt = $.extend({}, {
                type: 'POST',
                datatype: 'json',
                jsonpCallback: null,
                data:{}
            }, opt);

            $(document)
                .off('.view-form')
                // 点击切换验证码
                .on('click.view-form', '[data-view-form] .code-img', function() {
                    
                    var src = $(this).data("src");
                    $(this).attr("src", src + "?" + Math.floor(Math.random() * (1000 + 1)));
                })
                // 换一张
                .on('click.view-form', '[data-view-form] .code-title', function() {
                    $(this).prev('img').trigger('tap');
                    return false;
                })
                // 回车提交
                .on('keypress.view-form', '[data-view-form] input[type="text"]', function(e) {

                })
                // 提交表单
                .on('click.view-form', '[data-view-form]  [data-role="submit"]', function(e) {
                    var $submit = $(this),
                        $form = $(this).closest('form'),
                        jumurl = $form.data('jumurl'),
                        data = {},
                        eldata = $submit.data();

                        // console.log(loginUrl);

                    if ($form.size()) {
                        
                        data = $form.serializeArray();
                        // console.log(data);
                        
                        // var str = '{';
                        // $.each(data, function(i, el) {
                        //  str += '"'+el.name+'":"'+el.value+'",';
                        // });
                        // str += '}';

                        // console.log(str);

                        // var datas = utils.parseJSON(str);

                        // console.log(datas);
                        var info = _self.validate($form);
                        if (info) {
                            $.alert(info)                        
                            return;
                        }
                    }
                    if (!$submit.hasClass('requesting')) {
                        $submit.addClass('requesting');

                        $submit.data('title', $submit.html()).html(eldata.loading || '正在提交...');
                        // console.log(_opt.data);
                        $.each(_opt.data, function(i,e){
                            data.push({
                                'name': i,
                                'value': e
                            });
                        });
                        // data = $.extend({}, data, _opt.data);

                        // console.log(data);
                        ajax({
                            url: eldata.url || $form.attr('action'),
                            type: eldata.type || _opt.type || $form.attr('method'), // || 'GET',
                            dataType: _opt.datatype,
                            data: data,
                            jsonpCallback: _opt.jsonpCallback,
                            success: function(result) {

                                if (result.status) {

                                    $.toast(result.info, 800);
                                    
                                    if (jumurl) {
                                        location.href = jumurl;
                                    } else if (result.url) {
                                        location.href = result.url;
                                    } else {
                                        
                                    }
                                    $form[0].reset();
                                    $form.trigger('done');
                                } else {

                                    $.alert(result.info);
                                }
                                // 重置验证码
                                $form.find(".code-img").trigger('tap');
                                
                            },
                            error: function(e) {
                                $.alert('Ajax error!');
                            },
                            complete: function() {
                                $submit.removeClass('requesting');
                                $submit.html($submit.data('title'));

                            }
                        });
                    }

                    e.stopPropagation();
                    return false;
                })
                // 发送短信
                .on('click.view-form', '[data-view-form] .code-btn', function() {
                    var $this = $(this);
                    var this_data = $this.data();
                    var role = this_data.code;
                    var codeVal = $('#'+this_data[role]).val();
                    if(role == 'tel'){
                        var option = {
                            tel: codeVal
                        }
                    }else if(role == 'mail'){
                        var option = {
                            mail: codeVal
                        }
                    }
                    var option = $.extend(true, {}, option, _opt.data, {
                        csrf_token: csrf_token
                    });
                    if(!codeVal){
                        if(role == 'tel'){
                            $.alert('手机号码不能为空');
                        }else if(role == 'mail'){
                            $.alert('邮箱不能为空');
                        }
                        $('#'+this_data.role).focus();
                        return false;

                    }
                    // 禁用
                    if ($this.hasClass('disabled')) {
                        return false;
                    };

                    if (!$this.hasClass('requesting')) {
                        $this.addClass('requesting');
                        ajax({
                            type: 'GET', // 短信发送此方式限定只能接受GET方式传送的参数,不要更改成POST
                            url: this_data.src,
                            data: option,
                            dataType: _opt.datatype,
                            jsonpCallback: _opt.jsonpCallback,
                            success: function(result){
                                if (result.status) {
                                    countDown(10, $this)
                                } else {
                                    $.alert(result.info || 'error');
                                }
                            },
                            error: function(result){
                                $.alert(result.info || 'error');
                            },
                            complete: function(){
                                $this.removeClass('requesting');
                            }
                        }); 
                    }

                })

                // 使用按钮可用
            $('.btn[disabled="disabled"]').prop('disabled', '');
        },
        validate($form) {
            var errInfo;

            $form.find([
                    'select',
                    'textarea',
                    '[type="text"]',
                    '[type="password"]',
                    '[type="file"]',
                    '[type="number"]',
                    '[type="search"]',
                    '[type="tel"]',
                    '[type="url"]',
                    '[type="email"]',
                    '[type="datetime"]',
                    '[type="date"]',
                    '[type="month"]',
                    '[type="week"]',
                    '[type="time"]',
                    '[type="datetime-local"]',
                    '[type="range"]',
                    '[type="color"]',
                    '[type="radio"]',
                    '[type="checkbox"]',
                ].join(',')).each(function(index, el) {
                    if (!this.checkValidity()) {
                        errInfo = $(this).data('msgRequired');
                        return false;
                    }
                });

            return errInfo;
        },
        toTop: function(){
            var $toTop = $('[data-to-top="1"]');
            if($toTop.size() <= 0){
                return;
            }
            var $content = $('.page-current').find('.content');
            $toTop
                .off('.top')
                .on('click.top', function(){
                    $content.scrollTo({scrollTop:0})
                });
            $content
                .off('.top')
                .on('scroll.top', function(){
                    var scrollTop = $(this).scrollTop();
                    var rand = parseInt($(this).height()/2);
                    if(scrollTop >= rand){
                        $toTop.show();
                    }else{
                        $toTop.hide();
                    }
                });
        },
        // 详情页面的统计
        /*dcounts: function(opt){
            var $counts = $('[data-detailcounts="1"]');
            if($counts.length >= 1){
                var config = utils.parseJSON($counts.data('config'));
                ajax({
                    url: opt.url,
                    type: 'GET',
                    dataType: 'json',
                    data: config,
                });
                
            }
        },*/

        /**
         * @method getcounts
         * @description  移动端的统计
         * @param {String} url 统计的地址
         * @param {String} data-counts 统计的配置, 包括models(模型),id,field(字段),service(服务名)
         * 
         * @example
         * ```js
         * common.getcounts({
         *  url: '("igetcounts/index")|U()'
         * });
         * ```
         * ```html
         * <span data-counts="{models: 'news', field: 'clicks', id: '1'}"></span>
         * ```
         * @说明信息
         * 这里的models是服务名，不是字段中的模型
         * 如果不是house服务的，加上service: '服务名'
         */
        getcounts: function(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                showload: 1,
                url: null,
                datatype: 'json',
                data: {}
            }, opt);

            var $counts = $('[data-counts]');
            if (_opt.showload === 1) {
                $counts.addClass('LOADING');
            }
            var oParams = {};
            var models = {};
            var service = {};
            $counts.each(function(i, el) {
                var $this = $(this);
                var countsParams = utils.parseJSON($this.data('counts'));
                if (!oParams[countsParams.models]) {
                    oParams[countsParams.models] = [];
                    models[countsParams.models] = [];
                    service[countsParams.models] = [];
                }
                // 获取服务名前缀
                service[countsParams.models] = countsParams.service || 'house';

                $this.addClass(countsParams.models + '-' + countsParams.id);
                // 存储同一个模型下的id
                oParams[countsParams.models].push(countsParams.id);

                // 存储同一个模型下的字段
                if (models[countsParams.models].indexOf(countsParams.field) == -1) {
                    models[countsParams.models].push(countsParams.field);
                }

            });

            $.each(oParams, function(k, val) {
                var ids = val.join(',');
                var fields = models[k].join(',');
                var data = $.extend({},  {
                        models: k,
                        service: service[k],
                        id: ids,
                        field: fields
                }, _opt.data);

                ajax({
                    url: _opt.url,
                    dataType: _opt.datatype,
                    type: 'GET',
                    data: data,
                    isSublimeLock: false,
                    success: function(d) {
                        $.each(utils.parseJSON(d), function(k, v) {
                            var $els = $counts.filter('.' + v.models + '-' + v.id);
                            $.each($els, function(i, el) {
                                var $el = $(el);
                                var countsParams1 = utils.parseJSON($el.data('counts'));
                                $el.replaceWith(v[countsParams1.field]);
                            });
                        });
                    }
                });
            });
        },
        /**
         * @method adcount
         * @description  移动端的统计广告点击次数
         * @param {String} saveUrl   统计提交的地址：控制器+方法
         * @param {String} data-ad   html上的广告统计配置
         * @param {String} models    统计的模型
         * @param {String} id        统计广告的id
         * @param {String} field     统计的字段
         * 
         * @example 调用
         * ```js
         *  common.adcount({
         *           saveUrl: '{{("viewinter/vote")|U()}}'
         *         });                
         * ```
         * ```html
         * <a data-ad="{models: 'pushs',id:{{o.id|default('')}},field: 'clicks' }" ></a>
        */
        adcount(opt) {
            var _opt = $.extend(true, {}, {
                saveUrl : ''
            }, opt);

            $(document)
                .off('click.ad')
                .on('click.ad', '[data-ad]', function(event) {
                    var $this = $(this);
                    var data = utils.parseJSON($this.data('ad'));

                    if (!localStorage.getItem('mobile-adcount-' + data.id)) {
                        ajax({
                            url: _opt.saveUrl,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: opt.id,
                                models: opt.models,
                                field: opt.field
                            },
                            success: function(result) {
                                if (result.status) {
                                    localStorage.setItem('mobile-adcount-' + opt.id, 1);
                                }
                            }
                        });
                    }

                })
        },
        /**
         * @method block
         * @description 通过ajax调用某个block信息
         * @param  {String} el  选择器
         * @param  {Object} opt 配置
         * @param  {Boolean} opt.bHash=false 是否加入hash作用ajax的参数
         * @param  {Boolean} opt.bParams=false 是否加入params作用ajax的参数
         * @example1
         * ```html
         * <div data-block="{
         *     bHash: true,
         *     bParams: true,
         *     ajax: {
         *         data: {
         *             tpl: 'aaa'
         *         }
         *     }
         * }"></div>
         * ```
         * ```js
         * block()
         * ```
         * @example2
         * ```html
         * <div id="aaa" data-abc="{
         *     bHash: true,
         *     bParams: true,
         *     ajax: {
         *         data: {
         *             tpl: 'aaa'
         *         }
         *     }
         * }"></div>
         * ```
         * ```js
         * block('#aaa', $('#aaa').data('abc'))
         * ```
         * 
         */
        block(el, opt) {
            var el = el || '[data-block]';
            var opt = opt || {};
            var datatag = 'block';

            $(el).each(function(i, ele) {
                var $this = $(this);
                // 禁止重复加载
                if ($this.data('loaded')) {
                    return;
                }
                $this.data('loaded', 1);
                // 总配置
                var _opt = $.extend(true, {}, opt, utils.parseJSON($this.data(datatag)));
                // 加入参数
                if (_opt.bParams) {
                    $.extend(_opt.ajax.data, utils.getUrlParams());
                }
                // 加入hash
                if (_opt.bHash) {
                    $.extend(_opt.ajax.data, utils.getUrlHash());
                }
                // ajax配置
                var ajaxOpt = $.extend(true, {
                    dataType: 'html',
                    success(res) {
                        var resJson = utils.parseJSON(res)
                        if (resJson.nologin == 0) {
                            location.href = '../main/login.html';
                        } else {
                            $this.replaceWith(res);
                        };
                    },
                    error() {
                        console.log('服务器错误！')
                        $this.data('loaded', 0);
                    }
                }, _opt.ajax);
                if (ajaxOpt.url) {
                    ajax(ajaxOpt)
                } else {
                    console.error('ajax url 不存在！');
                }
            });
        }
    };

    // app.init();

    module.exports = app;
});