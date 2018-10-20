/**
 * author : ling
 * date   : 2016-11-23
 * name   : mobileCollect
 * modify : 2016-11-23
 */

/**
 * @module mobileCollect
 * @description  移动端的收藏
 * @param {Number} data-collected 判断是否已经收藏过
 * @param {Object} data-collect  收藏的配置, 包括models(模型),uid(会员id),aid 
 * @param {String} data-url 收藏提交的地址
 * 
 * @example 调用
 * ```html
 * 
 * 
 * 
 *{% set hascollect = ('house.collects')|getAll({checked:0, aid: xq_detail.id|default(''), models: xq_detail.models|default(''), uid: uid }) %}
 *
 *
 *
 *
 * <a data-collected="0" data-collect="{aid:,models: '', uid: 0 }" data-
 * url="collects/save">
 *     <i class="font-icons-19 mr5"></i>
 *     <span  data-after="已收藏">收藏</span>
 *</a>                      
 * ```
 * @预览 地址?id=19 (移动端)
 * @example
 * ```html
 * http://192.168.1.7/newcore/web/app_dev.php/house/houses/detail?id=19
 * ```
 * @说明信息
 * 调用的时候，标签可以改，但结构请保持不变
 * 
 * 
 */

define('collect', ['ajax', 'utils'], function(require, exports, module) {
    var utils = require('utils');
    var ajax = require('ajax');
    var app = {
        init: function(opt) {
            var _self = this;
            _self.opt = $.extend({}, {
                type: 'POST',
                datatype: 'json',
                jsonpCallback: null,
                data: {}
            }, opt);

            // 检查收藏
            $('[data-collect]').each(function() {
                var $this = $(this);

                if ($this.data('inited')) {
                    return;
                }
                if ($this.data('collected') * 1) {
                    $this.addClass('collected')
                        .find('[data-after]').text(function() {
                            return $(this).data('after');
                        });
                }

                $this.data('inited', 1);
            });
            // 绑定事件
            $(document)
                .off('collect')
                .on('click.collect', '[data-collect]', function() {
                    var $this = $(this);
                    var data = utils.parseJSON($this.data('collect'));
                    var url = $this.data('url');

                    if (!$this.hasClass('collected')) {
                        _self.ajax(url, data, $this);
                    } else {
                        // 取消收藏
                    }
                });
        },
        ajax: function(url, data, $obj) {
            var _self = this;
            var _opt = _self.opt;

            if (!$obj.hasClass('requesting')) {
                $obj.addClass('requesting');

                data = $.extend({}, data, _opt.data);

                ajax({
                    url: url,
                    type: _opt.type,
                    dataType: _opt.datatype,
                    // jsonpCallback: _opt.jsonpCallback,
                    data: data,
                    success: function(result) {
                        if (result.status) {
                            $.toast('亲，收藏成功!', 800);
                            $obj.addClass('collected')
                                .find('[data-after]').text(function() {
                                return $(this).data('after');
                            });
                        } else {
                            $.alert(result.info);
                        // 跳转链接，先找程序返回的url，再找自定义链接
                            location.href = result.url || $obj.data('jumpurl');
                        }
                    },
                    error: function() {
                        $.alert('Ajax error!');
                    },
                    complete: function() {
                        $obj.removeClass('requesting');
                    }
                });
            }

        }
    };


    module.exports = app;
});
//# sourceMappingURL=http://localhost:8888/public/js/mobile-collect.js.map
