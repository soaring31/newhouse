/**
 * author : ling
 * date   : 2017-04-27
 * name   : listPjax
 * modify : 2017-06-01
 */

/**
 * @module listPjax
 * @description list + pushState + ajax 让列表网页使用ajax展示，优化体验，提高性能
 * @example 调用
 * ```js
 *listPjax.init({
        url: "{{('houses/list')|U}}",
        list: '[data-list]'
    });
 * ```
 */
// @require('babel');
define(['utils', 'jqmodal', 'template', 'ajaxCache', 'filter'], function(require, exports, module) {
    var utils = require('utils');
    var template = require('template');
    var filter = require('filter');

    var listPjax = {
        /**
         * @method init
         * @param {Object} opt 初始化配置
         * @param {String}     opt.url ajax的基本链接
         * @param {String}     opt.circleUrl 商圈的请求链接
         * @param {String}     opt.list jq选择器 外层列表
         * @param {String}     opt.sort jq选择器 排序项         *
         * @param {String}     opt.data_page jq选择器，该对象包含data-page属性:分页的总数，总页数，当前页数
         * @param {String}     opt.data_minpage jq选择器，迷你分页外层元素
         * @param {String}     opt.filterShow jq选择器 显示条件的外层
         * @param {Boolean}     opt.showCircle 是否显示商圈
         */
        init: function(opt) {
            if (!history.pushState) return;
            var _self = this;
            var TPL = `
                    <dl class="clearfix <%= name %>">
                        <dt><%= title %>：</dt>
                        <dd>
                            <a href="javascript:;" data-value class="act default" data-name="<%= name %>">不限</a>
                            <% var item = null; %>
                            <% for (var i = 0; i < data.length; i++) { %>
                            <% item = data[i]; %>
                            <a href="javascript:;" data-title="<%= item.name %>" data-value="<%= item.id %>" data-name="<%= name %>">
                                <%= item.name %>
                            </a>
                            <% } %>
                        </dd>
                    </dl>
                    `;
            var _opt = _self.opt = $.extend({}, {
                url          : null,
                list         : '[data-list]',
                page         : '.p-bar a',
                data_page    : '[data-page]',
                data_minpage : '[data-minpage]',
                level        : {},
                next: {
                    tpl: TPL
                }
            }, opt);

            _self.events();
            // 处理分页
            _self.page();

            filter.init({
                callBack(el) {
                    _self.nextLevel(el);
                    _self.pjax();
                }
            });
        },
        // 处理分页
        page: function(opt) {
            var _self = this;
            var _opt = $.extend(true, {
                    data_minpage: '[data-minpage]',
                    page        : '.p-bar a',
                    data_page   : '[data-page]'
                }, _self.opt, opt);
            var Pageinfo = {
                   pageIndex: 1,
                   pageSize : 1,
                   // 页数
                   pageSum  : 1,
                   suffix   : '条符合条件',
                   pageCount: 0
               },
               _html  = '';

            if ($(_opt.data_page).length) {
                var new0 = utils.parseJSON($(_opt.data_page).data('page'));
                $.extend(Pageinfo, new0);
            } else {
                console.error('列表缺少分页标记');
            }

            // 页数 重新计算
            Pageinfo.pageSum = Math.ceil(Pageinfo.pageCount / Pageinfo.pageSize) || 1;

            _html = `<p>共<span data-num="1" class="num">${Pageinfo.pageCount}</span>${Pageinfo.suffix}</p>
                    <p class="last">
                        <a href="javascript:;" id="page_prev" ><i class="font-lp-33"></i></a>
                        <span id="page_cur">${Pageinfo.pageIndex}</span>/<span id="page_index">${Pageinfo.pageSum}</span>
                        <a href="javascript:;" id="page_next" ><i class="font-lp-34"></i></a>
                    </p>`;

            $(_opt.data_minpage).html(_html);

            $(document)
                .off('.small-page')
                .on('click.small-page', _opt.data_minpage + ' a', function(e) {
                    if ($(this).is('#page_prev')) {
                        location.href = $(_opt.page).filter('.prev').attr('href') || '#';
                    } else {
                        location.href = $(_opt.page).filter('.next').attr('href') || '#';
                    }
                    e.preventDefault();
                })
        },
        events: function() {
            var _self = this;
            var _opt = _self.opt;
            // 下级的配置
            var next = _opt.next || { data: {} };
            // pjax后退
            $(window)
                .off('.listPjax')
                .on('popstate.listPjax', function() {
                    // 暂时先用跳转，后续再完善pjax
                    location.href = location.href;
                    // _self.pjax()
                })
        },
        /**
         * @description 处理下一级
         * @param  {Object} oParent 父级配置
         * @param  {Object} opt     当前级配置
         */
        nextLevel: function(parent) {
            // 当前父级上有下级的标记才启用下级，处理下级
            // 当前级配置
            var $actList = $(parent).closest('[data-next]');
            var opt = utils.parseJSON($actList.data('next'));

            if (!opt) {
                return;
            }
            // 先移除下级
            $actList.next('.' + opt.name).remove();

            var oParent = $(parent).serializeArrayEx()[0];
            // 可能点击到`不限`
            if (!oParent) {
                return;
            };

            var next = this.opt.next;

            var done = function(_data) {
                if (_data.status) {
                    _data.pid = oParent.value;
                    if (next.tpl) {
                        // 过滤
                        _data.data = $.grep(_data.data, function(item) {
                                        return $.inArray((_data.pid + ''), (item.pid + '').split(',')) > -1;
                                    });

                        if (_data.data.length) {
                            var tpl = next.tpl.indexOf('#') == 0 ? $(next.tpl).text() : next.tpl;
                            var render = template.compile(tpl);

                            $actList.after(render({
                                title: opt.title,
                                name: opt.name,
                                data: _data.data
                            }));
                        }
                    } else {
                        console.log('请指定模板！');
                    }
                }
            }
            this.nextAjax && this.nextAjax.abort();
            this.nextAjax = $.ajax({
                    url: utils.url(next.url),
                    type: 'GET',
                    dataType: 'json',
                    // 缓存标识
                    cacheKey: 'next_' + opt.name,
                    // 启用缓存
                    localCache: true,
                    data: {
                        models: opt.name
                    }
                })
                .done(done)
                .fail(function() {
                    // console.log("error");
                })
                .always(function() {
                    this.nextAjax = null;
                });
        },
        /**
         * @method pjax
         * @description ajax请示，并修改地址栏地址
         * @param {Object} setting $.ajax 的配置
         * @param {String} [ignore] 参数元素中要忽略的元素，Jq选择器
         */
        pjax: function(setting, ignore) {
            var _self = this;
            var _opt = _self.opt;
            var level = _opt.level;
            var filterParams = filter.getParams();
            var _setting = $.extend(true, {}, {
                url: _opt.url,
                type: 'GET',
                dataType: 'html',
                data: filterParams,
                headers: {
                    ajax: 1
                },
                beforeSend: function() {
                    $.jqModal.progress();
                }
            }, setting);

           
            // 没有参数不做请求
            /*if ($.isEmptyObject(_setting.data)) {
                return;
            };*/
            // console.log(_setting.data);
            _self.ajax && _self.ajax.abort();
            _self.ajax = $.ajax(_setting)
                .done(function(data) {
                    var _data = utils.parseJSON(data);
                    if (!$.isEmptyObject(_data)) {
                        if (typeof _data.status != 'undefined' && _data.status == 'false') {
                            $.jqModal.tip(_data.info, 'error');
                        }
                    } else {
                        // var $data = $(data).is(_opt.list) ? $(data) : $(data).find(_opt.list).html();
                        $(_opt.list).html(data)
                            .trigger('pjaxDone');
                         _self.page();
                        // 去掉不需要的参数
                        delete _setting.data.ajax;
                        // 修改url
                        history.pushState(null, null, utils.urlJoin(_opt.url, _setting.data));
                    }
                })
                .fail(function() {
                    // console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                    $.jqModal.progress('100%');
                    delete _self.ajax
                });
        }
    };

    module.exports = listPjax;
});
