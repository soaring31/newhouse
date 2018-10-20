'use strict';

/*!
 * name   : comment
 * date : 2017-10-20 13:59:19
 * @description  评论功能,包括评分,加载更多,回复
 */

// @require('babel');
define(['qqFace', 'common', 'jqmodal', 'utils', 'cookie', 'jqraty', 'validate'], function (require, exports, module) {
    var utils = require('utils');
    (function (out) {
        module.exports = out;
    })({
        /**
         * @name init
         * @description  评论功能,包括评分,加载更多,回复
         * @param {Object} opt 配置
         * @param {String} opt.list jq选择器 点评的列表
         * @param {String} opt.reply=.replys jq选择器 查看回复详情按钮
         * @param {String} opt.replyBtn=.wyhf jq选择器 回复按钮
         * @param {String} opt.form=.comment-form jq选择器 点评表单(外框|form的外元素)
         * @param {String} opt.url 请求的列表地址
         * @param {String} opt.removeItem jq选择器 要移除的结构（回复的时候用到）
         * @param {String} opt.loadMore=.load-more jq选择器 加载更多
         * @param {object} opt.load 加载时候的配置
         * @param {String} opt.saveUrl 点评提交的地址
         *
         * @example 调用
         * ```js
         *  comment.init({
         *       list: '#pl-list',
         *       url: 'intercomment/lplist',
         *       loadMore: '#load-more',
         *       saveUrl: '',
         *       removeItem: '.up',
         *       load : {pageSize: 12}
         *   });
         * ```
         *
         */
        // 初始化函数
        init: function init(opt) {
            var _self = this,
                _opt = _self.opt = $.extend(true, {}, {
                list: null,
                reply: '.replys',
                replyBtn: '.wyhf',
                form: '.comment-form',
                url: null,
                removeItem: null,
                loadMore: '.load-more',
                load: {},
                saveUrl: null,
                callfn: null
            }, opt),
                $list = $(_opt.list),
                $form = $(_opt.form);

            // 防止重复初始化
            if ($list.hasClass('inited')) {
                return;
            }
            $list.addClass('inited');
            // 表单验证
            var validator = $(_opt.form).find('form').validate();

            // 点击回复
            $list.off('.cmt').on('click.cmt', _opt.replyBtn, function () {
                var $replyBtn = $(this);
                //评论内所有的回复框
                var $formClone = $list.find('.form-clone');
                //单个回复单元
                var $item = $replyBtn.closest('[data-cid]');
                // 查看回复的框
                var $replysWrap = $item.find('.replys-wrap');

                if (!$formClone.length) {
                    // 先重置下表单验证
                    validator.resetForm();
                    $formClone = $form.clone().addClass('form-clone');
                    $formClone.children('form').removeClass('form-' + $formClone.find('input[name="_form_id"]').val());
                    if (_opt.removeItem) {
                        $formClone.find(_opt.removeItem).remove();
                    }
                }
                // 设置回复ID
                $formClone.find('input[name="toid"]').val($item.data('cid'));
                // 先隐藏查看回复的框
                if ($replysWrap.length > 0) {
                    $replysWrap.slideUp();
                }
                if ($replyBtn.hasClass('opened')) {
                    $replyBtn.removeClass('opened');
                    $item.append($formClone.slideUp());
                } else {
                    $formClone.prev().find(_opt.replyBtn).removeClass('opened');
                    $replyBtn.addClass('opened');
                    $item.append($formClone.slideDown());
                }

                if (!$formClone.hasClass('inited')) {

                    // 为form绑定完成事件
                    $formClone.find('form').on('done', function () {
                        _self.loadMore();
                    }
                    // 重置tabindex
                    );$formClone.find('[tabindex]').attr('tabindex', function () {
                        return $(this).attr('tabindex') * 1 + 100;
                    }
                    // 回复只写内容，其它的去掉
                    );$formClone.find('[data-other-fields="1"]').remove();
                    // 修改按钮的标题
                    $formClone.find('.btn span').text('回复');
                    // 复制后要刷新下验证码
                    $formClone.off('.yzm').on('click.yzm', '[data-code-codetext="1"]', function () {
                        $(this).find('img').attr('src', function (i, v) {
                            return $(this).data('src') + '&t=' + Math.floor(Math.random() * (1000 + 1));
                        });
                    }).find('[data-code-codetext="1"]').click();
                    // 去掉表单验证的标识

                    // 重置表单验证
                    $formClone.removeClass('form-origin').find('form').attr({
                        errorLabelContainer: function errorLabelContainer(i, v) {
                            return v.replace(/origin/, 'clone');
                        }
                    }).validate

                    // 处理表情
                    ();var $btnC1 = $formClone.find('.btn-c');
                    var $btnC1Data = $btnC1.data();
                    $btnC1Data.assign = '.form-clone #content';
                    if ($btnC1.length) {
                        $btnC1.qqFace($btnC1Data);
                    }

                    $formClone.addClass('inited');
                };
                return false;
            }

            // 查看评论回复列表
            ).on('click', _opt.reply, function () {
                var $replys = $(this);
                var $item = $replys.closest('[data-cid]');
                var $replysWrap = $item.find('.replys-wrap');
                var $formClone = $list.find('.form-clone');

                if (!$replysWrap.length) {
                    $.ajax({
                        url: utils.url(_opt.url),
                        dataType: 'html',
                        data: {
                            toid: $item.data('cid') || ''
                        }
                    }).done(function (result) {
                        $item.append('<div class="replys-wrap">' + _self.replace_em(result) + '</div>');
                    }).fail(function () {}).always(function () {});
                } else {
                    $replysWrap.slideToggle();
                };
                if ($formClone.length > 0) {
                    $formClone.slideUp();
                    $(_opt.replyBtn).removeClass('opened');
                }
                return false;
            }
            // 点击加载更多,列表初始化
            );_self.listInit({
                list: _opt.list,
                loadMore: _opt.loadMore
            });

            // 提示完成后
            $form.find('form').on('done', function () {
                _self.loadMore();
            }
            // 处理表情
            );var $btnC = $form.find('.btn-c');
            if ($btnC.length) {
                $btnC.qqFace($btnC.data());
            }
        },

        // 点评列表
        listInit: function listInit(opt) {
            var _self = this;
            $(document).off('.loadMore').on('click.loadMore', opt.loadMore, function () {
                var page = utils.parseJSON($(opt.list).find('[data-page]').eq(-1).data('page'));

                _self.loadMore(opt, {
                    pageIndex: page.pageIndex + 1
                });
            });

            _self.loadMore(opt);
        },
        // params=> {pageSize: }
        loadMore: function loadMore(opt, params) {
            var _self = this;
            var _opt = $.extend(true, {}, _self.opt, opt);
            var _params = $.extend(true, {}, {
                pageIndex: 1,
                toid: 0,
                order: 'id|desc',
                is_append: true
            }, params);
            var $list = $(_opt.list);
            var $loadMore = $(_opt.loadMore);

            if (_params['is_append']) {
                $loadMore.data('title', $loadMore.html()).html('加载中..');
            }

            return $.ajax({
                url: utils.url(_opt.url), // 对url进行转码
                type: 'GET',
                dataType: 'html',
                data: _params
            }).done(function (result) {
                // 有参数时追加
                $list[_params['is_append'] && params ? 'append' : 'html'](_self.replace_em(result));

                // 显示加载更多
                var page = utils.parseJSON($list.find('[data-page]').eq(-1).data('page'));

                var isShow = page ? page.pageIndex < Math.ceil(page.pageCount / page.pageSize) : 0;
                $loadMore[isShow ? 'show' : 'hide']();
                $list.trigger('ajaxDone');
            }).fail(function () {
                // console.log("error");
            }).always(function () {
                $loadMore.html($loadMore.data('title'));
            });
        },
        replace_em: function replace_em(str) {
            str = str.replace(/\[em_([0-9]*)\]/g, '<img src="' + jsbase + '/house/images/face/$1.gif" border="0" />');
            return str;
        },
        raty: function raty(opt, sel) {
            var _self = this;
            var _opt = $.extend(true, {}, {
                number: 5,
                path: jsbase + "/house/images/",
                width: 150,
                size: 18,
                starHalf: 'star-half.png',
                starOff: 'star-off-big.png',
                starOn: 'star-on-big.png',
                targetType: 'number',
                score: 3,
                mouseover: function mouseover(score, evt) {
                    $(this).nextAll('.item-fen').html(score + '分');
                },
                mouseout: function mouseout(score, evt) {
                    if (score) {
                        $(this).nextAll('.item-fen').html(score + '分');
                    } else {
                        $(this).nextAll('.item-fen').html('暂无评分');
                    }
                },
                click: function click(score, evt) {
                    var num = 0;
                    $(this).nextAll('.val').val(score);
                    $(this).nextAll('.item-fen').html(score + '分');
                    $('.pf-left .item .val').each(function (index, el) {

                        num += parseInt($(this).val());
                    });
                    $('.pf-right .overall span').html(num / 5);
                    $('.pf-right').children('.val').val(num / 5);
                }
            }, opt);

            $(sel || '.pf-left .item .item-start').each(function (index, el) {
                var $this = $(this);
                // 启用当前评分
                var selOpt = $.extend(true, {}, _opt, {
                    score: $this.data('score')
                });

                // 防止重复初始化
                if (!$this.data('raty-inited')) {
                    $this.raty(selOpt).data('raty-inited', 1);
                }
            });
        },

        /**
         * @name zhibo
         * @description  直播刷新
         * @param {Object} opt 总配置
         * @param {String} opt.list jq选择器 直播的列表
         * @param {String} opt.url 请求的列表地址
         * @param {String} opt.loadMore=.load-more jq选择器 加载更多
         * @param {String} opt.orderby=id 排序字段
         * @param {String} opt.ordertype=desc 排序方法
         * @param {String} opt.time=60 * 1000 刷新频率
         *
         * @预览 地址?id=19
         * ```html
         * http://192.168.1.7/newcore/web/app_dev.php/house/houses/dcomment?id=19
         * ```
         * @说明信息 1
         *
         * - 调用的时候，一般用`comment.init({})`;
         * - 如果要用评分的效果， 再执行这个 `comment.raty()`;
         * - 如果要调用直播刷新效果，请单独执行以下代码：
         * ```js
         * comment.zhibo({
         *        url: 'clist/zblist',
         *       list: '#zblist'
         *
         *    });
         * ```
         */
        zhibo: function zhibo(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                loadMore: '.load-more',
                url: null,
                orderby: 'id',
                ordertype: 'desc',
                time: 60 * 1000
            }, opt);

            var $list = $(_opt.list);

            if ($list.hasClass('inited')) {
                return;
            }
            $list.addClass('inited');

            _self.opt.url = $(document.createElement('div')).html(_self.opt.url).text();

            _self.loadMore({
                order: _opt.orderby + '|' + _opt.ordertype,
                is_append: false
            });

            var timer = setInterval(function () {
                _self.loadMore({
                    order: _opt.orderby + '|' + _opt.ordertype,
                    is_append: false

                });
            }, _opt.time);

            $(document).on('change', '#refresh', function () {
                _opt.time = $(this).find('option:selected').val() * 60 * 1000;
                clearInterval(timer);
                if (_opt.time) {
                    _opt.time = setInterval(function () {

                        _self.loadMore({
                            order: _opt.orderby + '|' + _opt.ordertype,
                            is_append: false

                        });
                    }, _opt.time);
                }
            }).on('change', '#orderby', function () {
                _opt.ordertype = $(this).find('option:selected').val();

                clearInterval(timer);
                if (_opt.time) {
                    timer = setInterval(function () {
                        _self.loadMore({
                            order: _opt.orderby + '|' + _opt.ordertype,
                            is_append: false

                        });
                    }, _opt.time);
                }
            }).off('click.loadMore').on('click.loadMore', _opt.loadMore, function () {
                page = $list.find('[data-cid]').eq(-1).data();

                _self.loadMore({
                    order: _opt.orderby + '|' + _opt.ordertype,
                    pageIndex: page.pageIndex + 1

                });
            });
        }

    });
});
//# sourceMappingURL=comment.js.map
