/**
 * author : xying
 * name   : listajax
 */
define(['jqmodal', 'utils'], function(require, exports, module) {

    var utils = require('utils');

    var listajax = {
        /**
         * @variable pageindex
         * @description 初始化加载
         */
        pageindex: 1,
        /**
         * @variable loop
         * @description 防止多次重复加载锁
         */
        loop: true,
        /**
         * @variable addParams
         * @description 加载更多筛选条件初始化
         */
        addParams: {},
        /**
         * @method init
         * @param {Object}  opt 初始化配置
         * @param {String}  opt.url ajax的基本链接
         * @param {String}  opt.type ajax的请求方式
         * @param {String}  opt.dataType ajax的返回数据格式
         * @param {String}  opt.success ajax成功时自定义函数
         * @param {String}  opt.error ajax失败时自定义函数
         * @param {String}  opt.data ajax请求统一参数
         * @param {String}  opt.list jq选择器 加载外框
         * @param {String}  opt.listChild jq选择器 列表样式或者标签
         * @param {String}  opt.addBtn jq选择器 更多数据按钮
         * @param {String}  opt.filterBtn jq选择器 筛选按钮
         * @param {String}  opt.innerType 数据载入方式，'append'：不断往后加数据，'html': 清空数据再加
         */
        init: function (opt) {
            var _this = this;
            var _opt = _this.opt = $.extend({}, {
                url: null,
                type: "POST",
                dataType: 'html',
                success: null,
                error: null,
                data: {},
                list: null,
                listChild: 'li',
                addBtn: null,
                filterBtn: null,
                innerType: 'append',
                defaultAct: 'javascript:;'
            }, opt);

            // 当存在筛选按钮时执行
            if (_opt.filterBtn) {

                _this.filter();

                // 刷新时执行
                $(_opt.filterBtn + '[href="'+(location.hash || _opt.defaultAct)+'"]').click();
            };

            $(_opt.addBtn).click(function () {
                _this.addParams = $.extend(true, {}, _this.addParams, {pageIndex: _this.pageindex});
                _this.ajax(_this.addParams);
            });

        },
        /**
         * @method filter
         * @description 筛选点击
         */
        filter: function () {
            var _this = this;
            var _opt = _this.opt;
            $(_opt.filterBtn).click(function () {
                // 禁止连续多次点击
                if (_this.loop) {
                    var params = utils.parseJSON($(this).data('params'));

                    _this.addParams = $.extend(true, {}, _opt.data, params);

                    $(_opt.list).html('');

                    _this.pageindex = 1;

                    $(document).find(_opt.filterBtn).removeClass('act');

                    $(this).addClass('act');

                    _this.ajax(_this.addParams);
                };
            });
        },
        /**
         * @method ajax
         * @description 请求方法
         * @param {Object} params 传进来的请求参数
         */
        ajax: function (params) {
            var _this = this;
            var _opt = this.opt;

            // 当前内容框
            var $list = $(_opt.list);

            // 已加载内容数量
            var len = $list.find(_opt.listChild).length;

            // 已加载信息
            var listData = $list.find(_opt.listChild + ':first').data();

            // 如果现有的数据大于等于总数，则直接返回
            if (len > 0) {
                if (len >= listData.pagecount || listData.noinfo) {
                    _this.nomore();
                    return;
                };
            };

            // 条件合并
            params = $.extend(true, {}, params, {pageIndex: _this.pageindex});

            // 请求开始
            if (_this.loop) {

                // 锁定请求，避免多次重复请求
                _this.loop = false;

                $.ajax({
                    url: _opt.url,
                    type: _opt.type,
                    dataType: _opt.dataType,
                    data: params,
                    beforeSend: function () {
                        // $.jqModal.tip('加载中...', 'load');
                        $.jqModal.progress();
                    },
                    success: function (data) {
                        // 开启锁
                        _this.loop = true;

                        // 进度状态条
                        $.jqModal.progress('100%');

                        // 添加数据
                        $list[_opt.innerType](data);

                        // 页数自加
                        _this.pageindex++;

                        // 屏蔽按钮
                        if (typeof _opt.addBtn == 'string') {
                            // 总数
                            var pagecount = $list.find(_opt.listChild + ':first').data('pagecount');
                            // 已加载总数
                            var count = $list.find(_opt.listChild).length;

                            if (count < pagecount) {
                                $(_opt.addBtn).show();
                            };
                        };

                        // 自定义返回操作
                        if (typeof _opt.success == 'function') {
                            _opt.success(data);
                        };
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        // 开启锁
                        _this.loop = true;

                        // 进度状态条
                        $.jqModal.progress('100%')

                        // 移除更多按钮
                        _this.removeAdd();

                        // 自定义报错
                        if (typeof _opt.error == 'function') {
                            _opt.error(XMLHttpRequest, textStatus, errorThrown)
                        } else {
                            $.jqModal.tip('Ajax error：' + XMLHttpRequest.responseText)
                        };
                    }
                })
            };

        },
        /**
         * @method nomore
         * @description 暂无更多数据
         */
        nomore: function () {
            var _this = this;
            var _opt = _this.opt;

            // 没数据提示
            $.jqModal.tip('暂无更多数据!');

            // 移除增加按钮
            _this.removeAdd();
        },
        /**
         * @method removeAdd
         * @description 隐藏增加按钮
         */
        removeAdd: function () {
            var _this = this;
            var _opt = _this.opt;
            // 当按钮存在的时候执行
            if (_opt.addBtn) {
                $(_opt.addBtn).hide();
            };
        }



    }

    module.exports = listajax;
});