'use strict';

/**
 * author : Ahuing
 * date   : 2017-04-27
 * name   : filter
 * modify : 2017-09-29
 */
// @require('babel');
define(['template'], function (require, exports, module) {
    var template = require('template');
    var app = {
        init: function init(opt) {
            var _self = this;
            var TPL = '<a href="#" data-clear="<%= name %>">\n                            <span><%= title %></span>\n                            <i class="font-filter-7"></i>\n                        </a>';
            var _opt = _self.opt = $.extend(true, {
                parent: document,
                filterShow: '[data-filter-show]',
                tpl: TPL,
                callBack: $.noop()
            }, opt);

            $(_opt.parent).off('.filter').on('click.filter', '[data-name]', function (e) {
                _self.clickHandle(this);
                // 不是clear
                if (!$(this).is('[data-clear]')) {
                    _self.filterShow();
                    _opt.callBack(this);
                };
                e.preventDefault();
            }).on('click.filter', '[data-clear]', function (e) {
                _self.filterShow(this);
                _opt.callBack(this);
                e.preventDefault();
            });
        },
        clickHandle: function clickHandle(el) {
            var $this = $(el);
            var _name = $this.data('name');
            $this.addClass('act').siblings('[data-name="' + _name + '"]').removeClass('act');

            // select
            if ($this.data('select')) {
                var clsFn = !$this.data('value') ? 'removeClass' : 'addClass';
                $this.closest('li')[clsFn]('active').find('.title').text($this.data('title'));
            };

            // sort
            if ($this.data('sort') && $this.data('sort') != 1) {
                var orderBy = $this.data('value');

                if ($this.hasClass('asc')) {
                    orderBy = 'desc';
                } else if ($this.hasClass('desc')) {
                    orderBy = 'asc';
                }

                $this.data({
                    value: $this.data('sort') + '|' + orderBy
                }).removeClass('asc desc').addClass(orderBy);
            };
        },
        getParams: function getParams(parent) {
            var _opt = this.opt;
            // .not(ignore)
            return $(parent || document).find('.act[data-name]').not('[data-ignore]').serializeArrayEx();
        },

        /**
         * @method filterShow
         * @description 处理filter显示
         * @param {String} [item] 要删除的条件， all时，删除所有
         */
        filterShow: function filterShow(clearEl) {
            var _self = this;
            var _opt = _self.opt;
            var $clear = $(clearEl);
            var clear = $clear.data('clear');
            // 存放已选择条件的框
            var $filterShow = $(_opt.filterShow);

            if ($filterShow.length) {
                if (clearEl) {
                    // 排除点击的
                    if (clear == 'all') {
                        $('.default[data-name]').not('.act').each(function () {
                            _self.clickHandle(this);
                        });
                        // 所有
                        $filterShow.find('.filters [data-clear]').remove();
                    } else {
                        // 指定
                        // 清除单个项
                        $('.default[data-name="' + clear + '"]').click();
                        $clear.remove();
                    }
                } else {
                    // 显示已选择的
                    var _html = '';
                    var aParams = _self.getParams();
                    var tpl = _opt.tpl.indexOf('#') == 0 ? $(_opt.tpl).text() : _opt.tpl;
                    var render = template.compile(tpl);
                    $.each(aParams, function (i, item) {
                        if (item.title) {
                            _html += render(item);
                        }
                    });

                    $filterShow.find('.filters').html(_html);
                }
                // 没有条件时需要隐藏
                if (!$filterShow.find('.filters [data-clear]').length) {
                    $filterShow.hide();
                } else {
                    $filterShow.show();
                }
            } else {
                if ($clear.length) {
                    $('.default[data-name]').not('.act').each(function () {
                        _self.clickHandle(this);
                    });
                }
            }
        }
    };
    module.exports = app;
});
//# sourceMappingURL=http://localhost:8888/public/js/filter.js.map
