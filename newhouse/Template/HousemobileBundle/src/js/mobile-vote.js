/**
 * author : ling
 * date   : 2016-12-01
 * name   : mobilevote
 * modify : 2016-12-19
 */

/**
 * @module mobilevote
 * @description  移动端的投票（点赞）
 * @param {String} saveUrl 点评提交的地址：控制器+方法()
 * @param {Object} data-vote 投票的参数 包括 models(模型),id,field(字段)
 * @param {String} data-vote-num 投票/点赞 的个数
 * 
 * @example 调用
 * ```js
 *  mVote.init({
 *           saveUrl: '{{("viewinter/vote")|U()}}'
 *         });                
 * ```
 * @预览 地址(手机端)
 * http://192.168.1.7/newcore/web/app_dev.php/ilv/news/detail?id=585 
 * @example
 * ```html
 * <!-- 单个投票（点赞） -->
 *<div class="bottom-zan" data-vote="{models: 'news', id: {{news_info.id}}, field: 'vote'}">
 *  <div class="zan">
 *      <i class="font-icon-04" ></i>
 *  </div>
 *  <span data-vote-num="{{news_info.vote|default(0)}}">{{news_info.vote|default(0)}}</span>
 *</div>
 * 
 * ```
 * @说明信息
 * models 是后台的模型，不是字段
 * 
 */


define(['sm', 'ajax', 'utils'], function(require, exports, module){
    var utils = require('utils');
    var ajax = require('ajax');
    var app = {
        init: function(opt){
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                saveUrl : null,
                type: 'GET',
                datatype: 'json',
                jsonpCallback: null,
                data: {}
            }, opt);


            $(document)
                .off('click.vote')
                .on('click.vote', '[data-vote]', function(){
                    var $vote = $(this);
                    var data = $vote.data();
                    var opt = utils.parseJSON(data.vote);
                    var cid = opt.id;

                    if (localStorage.getItem('mobile-vote-' + cid)) {
                        $.toast('亲，您已经点赞过了哦！', 800);
                        return;
                    }

                    if ($vote.hasClass('sending')) {
                        return;
                    }

                    var data = $.extend({},  {
                            id: opt.id,
                            models : opt.models,
                            field  : opt.field
                    }, _opt.data);
                    
                    ajax({
                        url: _opt.saveUrl,
                        type: _opt.type,
                        dataType: _opt.datatype,
                        jsonpCallback: _opt.jsonpCallback,
                        data: data,
                        beforeSend: function(){
                            $vote.addClass('sending');
                        },
                        success: function(result){
                            if (result.status) {
                                localStorage.setItem('mobile-vote-' + cid, 1);
                                $vote.trigger('done:vote', [result]);
                                $.toast('亲，感谢您的参与!');
                            } else {
                                $.alert(result.info);
                            }
                        },
                        error: function(){
                            $.alert('Ajax error!');
                        },
                        complete: function(){
                            $vote.removeClass('sending');
                        }
                    });

                    $vote.on('done:vote', function(event, result) {
                        var voteNum = $(this).find('[data-vote-num]').data('voteNum');
                        $(this).find('[data-vote-num]').text(voteNum + 1);
                    });
                })
        }
    };

    module.exports = app;
});