/**
 * author : ling
 * date   : 2016-11-30
 * name   : loadmore
 * modify : 2016-11-30
 */

/**
 * @module loadmore
 * @description 加载更多列表类信息
 * @说明信息
 * 列表下面点击或滚动时加载更多功能
 * 有两种模式,一种是通过滚动条来触发加载,要设置rang的距离;
 * 一种是通过点击按钮来触发加载
 *
 * @param {String} listUrl  请求的列表地址
 * @param {String} list jq选择器 列表外层
 * @param {Number} range=0 触发刷新的滚动距离, 相对滚动按钮
 * @param {String} moreBtn jq选择器 加载更多的按钮
 * @param {String} moreinfo jq选择器 加载更多的提示
 * @param {object} loadgif jq选择器 加载时候的动画 (class名不要为loading,已经被占用)
 *
 * @example 调用
 * ```js
 *loadmore.init({
 *    listUrl: 'clist/indexlist',
 *    list: '.list-container',
      moreBtn: 'more-btn',
      moreinfo: 'more-info',
 *    range: 100,
 *    loadgif: '.ilv-loading'
 *  });
 * ```
 * ```html
 *    <div class="list-container">
 *        <ul>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
             <li></li>
         </ul>
         <div class="more-info">正在加载</div>
         <div class="more-btn">加载更多...</div>
 *    </div>
 * ```
 * @参数说明
 */


define(['utils', 'template'], function(require, exports, module) {
    var utils = require('utils');
    var temp = require('template');
    var app = {
        init: function(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                listUrl: null,
                list: null,
                range: 0,
                moreBtn: null,
                moreinfo: null,
                loadgif: null,
                load: {}
            }, opt);
            var $list = $(_opt.list);

            // 防止重复执行
            if ($list.hasClass('inited')) {
                return;
            }
            // _self.more();

            $list.addClass('inited');

            if(_opt.moreBtn){
                $(document).off('click.more').on('click.more', _opt.moreBtn, function(){
                    _self.moreconfig();
                });
            }else {
                $(window).on('scroll', function(event) {
                    var scrollTop = $(document).scrollTop();
                    var winHeight = $(window).height();
                    var docHeight = $(document).height();

                    if ((docHeight - _opt.range) <= (scrollTop + winHeight)) {

                        _self.moreconfig();

                    }
                });

            }

        },

        moreconfig: function(){
            var _self = this;
            var _opt = _self.opt;
            var $list = $(_opt.list);
            var page = $list.find('[data-page-index]').eq(-1).data();

            var listPage = Math.ceil(page.pageCount / page.pageSize);

            if (page.pageIndex >= listPage) {

                if(_opt.moreBtn){
                    $(_opt.moreBtn).hide();
                }

                $(_opt.moreinfo).show().html('没有更多数据');

                return;
            }
            _self.more({
                pageIndex: page.pageIndex + 1,
            });
        },

        more: function(params) {
            var _self = this;
            var _opt = _self.opt;
            var _params = $.extend(true, {}, {
                pageIndex: 1,
                pageSize: 10
            }, params, _opt.load);

            var $list = $(_opt.list);

            if ($list.hasClass('sending')) {

                return;
            }

            $list.addClass('sending');

            $.ajax({
                    url:  _opt.listUrl,
                    type: 'GET',
                    dataType: 'html',
                    data: _params,
                    beforeSend: function() {

                        $(_opt.loadgif).show();
                        if(_opt.moreBtn){
                            $(_opt.moreBtn).hide();
                        }
                    }
                })
                .done(function(result) {

                    $(_opt.loadgif).hide();

                    if(_opt.moreBtn){
                            $(_opt.moreBtn).show();
                        }

                    $list[params.pageIndex ? 'append' : 'html'](result);

                    var page = $list.find('[data-page-index]').eq(-1).data();

                    // console.log(page);

                    var listPage = Math.ceil(page.pageCount / page.pageSize);

                    if (page.pageIndex >= listPage) {

                        if(_opt.moreBtn){
                            $(_opt.moreBtn).hide();
                        }

                        $(_opt.moreinfo).show().html('没有更多数据');

                        return;
                    }


                })
                .fail(function() {

                    // console.log("error");
                })
                .always(function() {

                    $list.removeClass('sending');

                    // console.log("complete");

                });
        }
    };

    module.exports = app;
});

//# sourceMappingURL=http://localhost:8888/public/js/loadmore.js.map
