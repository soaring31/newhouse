'use strict';

/*!
 * @name mobile-filter
 * @author author
 * @date 2017-10-13 11:35:31
 * @description 手机检索功能
 */
// @require('babel');
define(['utils'], function (require, exports, module) {
    var utils = require('utils');

    var filter = {
        // 处理检索条件事件
        filterInit: function filterInit(opt) {
            var _self = this;
            var $modal;
            // tabBar的标记
            var $buttonsTabShadow;

            $(document).off('.filter').on('click.filter', opt.el, function (e) {
                var $this = $(this);
                var index;
                // tabBar
                var $buttonsTab = $this.closest('.buttons-tab');

                if (!$this.closest('.modal').length) {
                    // 原来的位置给一个标记
                    $buttonsTabShadow = $('<div class="buttons-tab buttons-tab-ex"></div>').insertAfter($buttonsTab);
                    // 处理弹窗
                    $modal = $($buttonsTab.data('target'))
                    // tab追加进来
                    .prepend($buttonsTab).attr({
                        'data-tag': 'modal-ex',
                        'data-condition': 1
                    }).addClass('modal modal-ex')
                    // 设置弹窗的高度
                    .height($(window).height() * .8)
                    // 移到到body下面
                    .appendTo('body');
                }
                // 处理重复显示
                if (!$modal.hasClass('modal-in')) {
                    $.openModal($modal[0]);
                }
                // 当前是激活，就关闭
                if ($this.hasClass('active')) {
                    $.closeModal($modal[0]);
                    return false;
                }

                index = $this.index() || 0;
                // tab
                $this.addClass('active').siblings().removeClass('active');
                // tab内容
                $modal.find('.tab').removeClass('active').eq(index).addClass('active');
                // app 链接替换
                /*if (typeof urlCallback == 'function') {
                    urlCallback();
                };*/

                // 重置内容
                $modal.off('close').on('close', function () {
                    $buttonsTab.find('.active').removeClass('active');
                    $buttonsTabShadow.replaceWith($buttonsTab);
                });
                return false;
            })
            // 点击遮罩层关闭弹窗
            .on('click.filter', '.modal-overlay-modal-ex, .modal-overlay-select-modal', function (e) {
                $.closeModal();
                return false;
            });

            // 初始化
            _self.filterCondition();
            _self.tabCon();
        },

        // 弹窗后里面的内容的操作
        tabCon: function tabCon() {
            var _self = this;
            $(document).off('.condition')
            // 点击条件后立即跳转
            .on('click.condition', '[data-condition-item]', function (e) {
                _self.conditionItemSet(this, function () {
                    _self.setUrl();
                    // 关闭弹层
                    $.closeModal();
                    // 刷新列表
                    _self.refresh();
                });
                // 禁止冒泡
                return false;
            })
            // 点击条件弹窗里的提交按钮
            .on('click.condition', '[data-condition-submit]', function () {
                /**
                 * <a href="#" data-condition-submit="{
                        sendAjax: true,
                        container: '',
                        type: 'reset'
                 * }"></a>
                 * 
                 */
                var _btnthis = $(this);
                var _data = $.extend(true, {}, {
                    sendAjax: true,
                    container: '',
                    type: ''
                }, utils.parseJSON(_btnthis.data('conditionSubmit')));
                // 按钮状态
                // _btnthis.text(_data.title || 'loading...');
                if (_data.type == 'reset') {
                    var $container = $(_data.container);
                    // input
                    $container.find('input[type="radio"]').prop('checked', false);
                    $container.find('.default input[type="radio"]').prop('checked', true);
                    // tab 标题
                    $container.find('.tab-link1 .tit').text(function (i, oldTit) {
                        // 不存在时返回原来的
                        return $(this).data('tit') || oldTit;
                    });
                    // 需要全局处理
                    $('.tab-link1.selected').removeClass('selected');
                    $('.tab-link1 .num').text('');
                }
                // 不需要发送请求
                if (!_data.sendAjax) {
                    return false;
                }
                // 关闭弹层
                $.closeModal();

                _self.setUrl();

                // 刷新列表
                _self.refresh();
                return false;
            });
        },

        // 建个通用方法，方便扩展
        refresh: function refresh() {
            if ($('body').data('refresh')) {
                // 扩展一个接口，目前用于地图刷新
                $('body').trigger('refresh');
            } else {
                require.async('loadmore', function (loadmore) {
                     // 执行顺序乱了，做个延迟
                    setTimeout(function () {
                        loadmore.refresh();
                    }, 0);
                });
            }
        },

        // 条件设置
        conditionItemSet: function conditionItemSet(el, cb) {
            var $this = $(el);
            var $input = $this.find('input[type="radio"]');
            // 处理默认点击
            $input.prop('checked', true);
            // 判断是否是更多条件的点击
            var $conditionMore = $this.closest('[data-condition-more]');
            if ($conditionMore.length) {
                var num = $conditionMore.find('input:checked').not(function () {
                    return !this.value;
                }).length;
                if (num) {
                    $('[data-field="more"]').addClass('selected').find('.num').text('(' + num + ')');
                } else {
                    $('[data-field="more"]').removeClass('selected').find('.num').text('');
                }
                return false;
            }
            // 改变tab和标题
            var $tab = $('[data-field="' + $input[0].name + '"]');
            if ($tab.length) {
                // 保持所有
                var keep = $tab.hasClass('keep');
                var $tit = $tab.find('.tit');
                // 保存原来的tit
                !$tit.data('tit') && $tit.data('tit', $tit.text());

                if ($input.val()) {
                    !keep && $tit.text($this.find('.item-title').text());
                    $tab.addClass('selected');
                } else {
                    // 点击不限制
                    !keep && $tit.text($tit.data('tit'));
                    $tab.removeClass('selected');
                }
            }
            cb && cb.call();
        },

        // 设置url
        setUrl: function setUrl(container) {
            var container = container || '[data-condition="1"]';
            var oParams = $(container).find('input:checked').not(function () {
                return !this.value;
            }).serializeObject();
            location.hash = $.param(oParams);
        },

        /*
         * @name 筛选条件初始化
         * @param  {bealean} first 是否为页面初始加载
         */
        filterCondition: function filterCondition() {
            var _self = this;
            // 全部检索数据
            $.each(utils.getUrlHash(), function (k, v) {
                // 找到父级
                var conditionItem = $('[name="' + k + '"][value="' + v + '"]').closest('[data-condition-item="1"]')[0];
                conditionItem && _self.conditionItemSet(conditionItem);
            });
        }
    };
    module.exports = filter;
});
//# sourceMappingURL=http://localhost:8888/public/js/mobile-filter.js.map
