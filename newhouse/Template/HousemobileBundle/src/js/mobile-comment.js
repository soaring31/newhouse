/**
 * author : ling
 * date   : 2016-12-01
 * name   : mobileComment
 * modify : 2016-12-20
 */
// require('babel')
/**
 * @module mobileComment
 * @description  移动端的评论
 * @param {String} list jq选择器 点评的列表
 * @param {String} replyBtn jq选择器 回复按钮
 * @param {String} form jq选择器 点评表单
 * @param {String} type ajax提交方式，默认GET
 * @param {String} datatype ajax提交格式
 * @param {String} url 请求的列表地址
 * @param {String} btnload 是否手动加载，是为1
 * @param {String} removeItem jq选择器 要移除的结构（回复的时候用到）
 * @param {String} loadMore jq选择器 加载更多
 * @param {object} load 加载时候的配置
 * @param {String} saveUrl 点评提交的地址
 * @param {object} data ajax提交数据
 * 
 * 
 * @example 调用
 * ```js
 * mComment.init({
 *        list: '.newslist',
 *        url: '{{("Intercomment/list")|U({checked: "1", aid: news_detail.id, models: "news"})}}',
 *        saveUrl: '{{ save_url }}',
 *        btnload: 1
 *    });      
 * ```
 * @预览 地址?id=1
 * @example
 * ```html
 * (手机模式)
 * http://192.168.1.7/newcore/web/app_dev.php/house/news/detail?id=1
 * ```
 * @说明信息
 * 调用的时候，一般用mComment.init({});
 * 如果要用评分的效果， 再执行这个 :  mComment.ratyInit();
 * 
 * 
 */
define(['ajax', 'utils', 'loadmore'], function(require, exports, module){
    var ajax = require('ajax');
    var utils = require('utils');
    var loadmore = require('loadmore');
    var loading = false;
    var $loadgif = $('.infinite-scroll-preloader');
    var app = {
        init: function(opt){
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                    list: null,
                    replyBtn: '.reply',
                    lookReply: '.replys',
                    form: '.mobile-form',
                    type: 'GET',
                    datatype: 'html',
                    url: null,
                    btnload: null,
                    removeItem: null,
                    saveUrl: null,
                    data: {}
            }, opt);

            var $list = $(_opt.list);
            var $form = $(_opt.form);
            
            _self.opt.url = utils.url(_self.opt.url);
            // 防止重复加载
            if($list.data('inited')){
                return ;
            }
            $list.data('inited', 1);

            $list
                .off('.comment')
                // 回复
                .on('click.comment', _opt.replyBtn, function(){
                    var $replyBtn = $(this);                    
                    var $item = $replyBtn.closest('[data-cid]');
                    var $replyWrap = $item.find('.replyWrap');                    
                    var $formClone = $list.find('.form-clone');

                    if (!$formClone.length) {
                        $formClone = $form.clone().addClass('form-clone');
                        $formClone.find('[data-role="submit"]').text('回复');
                        if (_opt.removeItem) {
                            $formClone.find(_opt.removeItem).remove();
                        }
                        $formClone.find('input[name="toid"]').val($item.data('cid'));


                        $formClone.find('form').on('done', function() {
                            loadmore.refresh();
                        });

                    }

                    $item.append($formClone);
                    
                    if ($replyBtn.hasClass('opened')) {
                        $replyBtn.removeClass('opened');
                        $formClone.hide();
                    } else {
                        $(_opt.replyBtn).removeClass('opened')
                        $replyBtn.addClass('opened');
                        $formClone.show();
                        $replyWrap.hide();
                    }

                    return false;
                })
                // 查看回复
                .on('click.comment', _opt.lookReply, function(){
                    var $lookReply = $(this);
                    var $item = $lookReply.closest('[data-cid]');

                    var $replyBtn = $lookReply.siblings(_opt.replyBtn);

                    var $replyWrap = $item.find('.replyWrap');

                    if (!$replyWrap.length) {
                        var replyData = $.extend({}, {
                            toid: $item.data('cid') || 0
                        }, _opt.data);
                        var $loading = $('<div class="replyWrap loading"></div>').appendTo($item);

                        ajax({
                            url: _opt.url,
                            type: _opt.type,
                            dataType: _opt.datatype,
                            data: replyData,
                            success: function(result) {
                                result = result || '<div class="no-hf">暂无回复</div>'
                                $item.append('<div class="replyWrap">' + result + '</div>');
                                $loading.remove();
                            },
                            error: function() {
                                $loading.remove();
                                $.alert('Ajax error');
                            },
                            complete: function() {

                            }
                        })
                    } else {
                        $replyWrap.toggle();
                    }

                    $replyBtn.removeClass('opened');
                    $item.find('.mobile-form').hide();
                })

                // loadmore.refresh(); 不需要请求列表，loadmore js已经请求

                $list.find('form').on('done', function() {
                        loadmore.refresh();
                    });
                return false;
        },

        // 星评
        ratyInit(){
            var mark = $('.mark').find('span').eq(0);
            var overall = $('.raty-all').find('input[type="hidden"]');
            $('[data-start-init]').each(function(i, el) {
                var initNum = parseInt($(this).data('startInit'));
                // console.log(initNum);
                var allNum = parseInt($(this).data('start'));
                var html = '';

                for (var i = 1; i <= allNum; i++) {
                    html += '<i class="icon font-f-101" data-num="' + i + '"></i>';
                }
                $(this).append($(html));

                $(this).find('[data-num]').each(function(k, el) {
                    if (k < initNum) {
                        $(el).addClass('start-light');
                    } else {
                        $(el).removeClass('start-light');
                    }
                });
                var _this = this;
                $(this)
                    .off('click')
                    .on('click', '[data-num]', function() {
                        var num = parseInt($(this).data('num'));
                        var score = 0;
                        $(this).siblings('input[type="hidden"]').val(num);
                        $(_this).find('[data-num]').each(function(k, el) {
                            if (k < num) {
                                $(el).addClass('start-light');
                            } else {
                                $(el).removeClass('start-light');
                            }
                        });
                        $('[data-start-init] .val').each(function(i, el) {
                            score += parseInt($(el).val());
                            mark.text(score / 5);
                            overall.val(score / 5);
                        });
                    });
            });
        }

    };

    module.exports = app;
});