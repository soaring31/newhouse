'use strict';

/*!
 * @name mobile-loadmore
 * @author <%= author %>
 * @date <%= date %>
 */
/**
 * @module mobile-loadmore
 * @description 移动端列表加载更多
 * @param {Object} cfg 基础配置参数
 * @param {Object} cfg.params ajax查询条件
 * @example 调用
 * ```模板页
 * listCfg = {
 *     url: BASE_URL + '/house/demand/list?ajax=1',
 *     params: {
 *         type: '',
 *         uid: ''
 *     }
 * }
 * ```
 * ``` js
 * seajs.use(['listLoading'], function (listLoading) {
 *     listLoading.init(listCfg);
 * })
 * ```
 * 默认参数说明
 * @param {String} cfg.wrap=[data-container="list"]  内容显示外层
 * @param {String} cfg.container=.list-container 内容显示容器
 * @param {Number} cfg.maxItems=0 最大加载条数(总数) `0`表示不限制
 * @param {Number} cfg.pageCount 数据总数，在当前模板中必须要传回来的一个参数
 * @param {String} cfg.loopbody=li 循环结构
 * @param {String} cfg.preloader=.infinite-scroll-preloader 加载提示框
 * @param {String} cfg.callback 回调函数，app链接转换
 * @param {Object} cfg.ajaxOpt ajax配置
 * @param {Object} cfg.params ajax查询条件
 * @param {String} cfg.clickLoad=[data-list="1"] 点击加载钩子
 */

// @require('babel');
define(function (require, exports, module) {
    var loading = false;
    var utils = require('utils');
    var ajax = require('ajax');

    var list = {
        // 初始化
        init: function init(cfg) {
            var _this = this;
            var _cfg = _this.cfg = $.extend({}, {
                wrap: '[data-container="list"]',
                container: '.list-container',
                wrapFilter: '[data-condition="1"]',
                maxItems: 0,
                preloader: '.infinite-scroll-preloader',
                callback: null,
                // 需要加入url和hash上的参数
                bUrlHash: 1,
                // 自动加载数据
                autoLoad: 1,
                ajaxOpt: {},
                pageCount: 0,
                pageIndex: 1,
                clickLoad: '[data-list="1"]'
            }, cfg);
            // 首次加载
            // _this.pageCount(_cfgParams);

            // 筛选条件
            $(document).off('.loadmore').on('infinite.loadmore', '.infinite-scroll', function () {
                // 滚动刷新
                _this.loadMore();
            }).on('click.loadmore', _cfg.clickLoad, function () {
                // 点击加载
                _this.loadMore();
            }).on('refresh.loadmore', '.pull-to-refresh-content', function () {
                // 下拉刷新
                _this.refresh();
                $.pullToRefreshDone('.pull-to-refresh-content');
            });

            // 加载数据
            _cfg.autoLoad && _this.addItem();
        },
        // 加载更多
        loadMore: function loadMore() {
            var _this = this;
            var _cfg = _this.cfg;

            // 加载
            _this.addItem({
                data: {
                    pageIndex: ++_cfg.pageIndex
                }
            });
        },
        // 刷新列表
        refresh: function refresh(ajaxOpt) {
            // 重新启动滚动刷新
            this.start();

            // 加载数据
            this.addItem(ajaxOpt);
        },
        // 设置默认配置
        setDefOption: function setDefOption(cfg) {
            $.extend(true, this.cfg, cfg);
        },
        // 获取数据
        addItem: function addItem(ajaxOpt) {
            var _this = this;
            var _cfg = _this.cfg;
            // 列表的外框
            var $container = $(_cfg.wrap).find(_cfg.container);
            // 如果正在加载，则退出
            if (loading) return;

            // 设置flag
            loading = true;

            var ajaxSuccess = function ajaxSuccess(data) {

                $(_cfg.preloader).hide();
                $(_cfg.clickLoad).show();
                // data-page-count
                // 取最后一条上的配置
                var $page = $(data).filter('[data-page]').eq(-1);
                var oPage = $page.length ? utils.parseJSON($page.data('page')) : {};
                var pageCount = _cfg.pageCount = oPage.pageCount;

                //弹窗 显示楼盘个数 当没有数据时 pageCount = undefined
                if(pageCount && oPage.pageIndex == '1'){
                    alert($("#alertHTML").html(), pageCount);
                }
                // 根据页码自动判断是替换还是追加
                var innerType = oPage.pageIndex == 1 ? 'html' : 'append';
                _cfg.pageIndex = oPage.pageIndex;

                if ($page.length == 0 && $(data).length) {
                    $('.j_tips-alert').show();
                    // $('.j_tips-alert').html('<span>暂无更多数据999</span>');
                    $('<span style="color:#999;display: block;text-align: center;margin-top: 1rem">暂无更多数据</span>').appendTo($(".list-block .list-container"));
                    $('body').click(function(e){
                        $('.j_tips-alert').hide(300);
                        //e.stopPropagation();
                    })
                    setTimeout(function(){
                        $('.j_tips-alert').hide(300);
                    },2000)
                    //$.alert('暂无更多数据或没有page标识');
                    // 删除加载事件
                    _this.delete();
                } else {
                    $('.j_tips-alert').hide();
                    // 添加新条目
                    $container[innerType](data);
                    // 如果加的数量小于每页设置的数量，表示是最后一页
                    if ($(data).length < oPage.pageSize || !$(data).length) {
                        $container.append('<div class="tac tip" style="padding: 8px;clear: both; color: #aaa;">暂无更多数据</div>');
                        // $.alert('暂无更多数据')
                        // 删除加载事件
                        _this.delete();
                    }
                };
                // 链接替换
                if (typeof _callback == 'function') {
                    _callback.call(data);
                };
                // 释放加载权
                loading = false;
                // 已加载的总数
                var loadedConts = oPage.pageSize * (oPage.pageIndex - 1) + $(data).length;
                // 如果总数超过限制数量，关闭加载权
                if (_cfg.maxItems && _cfg.maxItems <= loadedConts) {
                    loading = true;
                }
            };

            // 列表上的常驻隐藏参数 $container.data('ajaxData')
            var data = $container.data('ajaxData') || {};
            // 链接上的参数
            if (_cfg.bUrlHash) {
                $.extend(data, utils.getUrlParams(), utils.getUrlHash());
            };
            // 合并所有
            var _ajaxOpt = $.extend(true, {
                type: 'GET',
                dataType: 'html',
                data: {
                    pageIndex: 1
                },
                isSublimeLock: false,
                beforeSend: function beforeSend() {
                    $(_cfg.preloader).show();
                    $(_cfg.clickLoad).hide();
                },

                success: ajaxSuccess,
                error: function error(XMLHttpRequest, textStatus, errorThrown) {
                    loading = false;
                    $.alert(XMLHttpRequest.status);
                }
            }, _cfg.ajaxOpt, ajaxOpt, {
                data: data
            });

            // console.log(_ajaxOpt);
            if (_ajaxOpt.url) {
                // 如果是重新请求先清空
                if (_ajaxOpt.data.pageIndex == 1) {
                    $container.html('');
                }
                // 开始请求，需要再转换下url
                ajax($.extend(_ajaxOpt, {
                    url: utils.url(_ajaxOpt.url)
                }));
            } else {
                console.error('ajax url 不存在！');
                loading = false;
            }
        },
        // 删除下拉刷新
        delete: function _delete() {
            var _this = this;
            var _cfg = _this.cfg;
            // 加载完毕 则注销无限加载事件 以防不必要的加载
            $.detachInfiniteScroll($(_cfg.wrap));
            // 删除加载提示符
            $('.infinite-scroll-preloader').hide();
            $(_cfg.clickLoad).hide();
            return;
        },
        // 启动下拉刷新
        start: function start() {
            // 启动滚动加载
            var _this = this;
            var _cfg = _this.cfg;
            $.attachInfiniteScroll($(_cfg.wrap));
        }
    };

    module.exports = list;
});
//# sourceMappingURL=http://localhost:8888/public/js/mobile-loadmore.js.map
