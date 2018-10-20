/**
* author : ling
* date   : 2016-12-19
* name   : comparison
* modify : 2017-06-12
 */
define(['utils', 'jqmodal'], function(require, exports, module) {
    var utils = require('utils');
    var $listBody = $('#list-body');
    var $listHead = $('#list-head');
    var aids = '';
    var aAids = [];
    var inited = false;

    var app = {
        init: function(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {}, {
                listTpl: '<li class="%cls%"><div>%con%</div></li>',
                url: null
            }, opt);

            var oUrl = utils.parseURL();

            aids = oUrl.hashParams[1];

            aAids =aids && aids.split(',') || [0, 0, 0, 0, 0];
            var chid = oUrl.params.chid;

            // 更新标题
            _self.upItem();
            // 根据id请求数据
            _self.getField();
            // 事件
            _self.events();
            
        },
        // 获取input对应的id
        /*getAids: function() {
            return $listHead.find('[data-onselect="1"]').map(function() {
                return $(this).data('aid');
            }).get().join(',');
        },*/
        // 事件
        events: function() {
            var _opt = this.opt;
            var _listTpl = _opt.listTpl;

            $listBody
                .off('.hover')
                .on('mouseover.hover', 'li', function() {
                        var I = $(this).index();
                        $listBody.find('ul').find('li:eq(' + I + ')').each(function(k, v) {
                            $(this).addClass('hover').siblings('li').removeClass('hover');
                        });
                    })
                .on('mouseout.hover', 'li', function() {
                        var I = $(this).index();
                        $listBody.find('ul').find('li:eq(' + I + ')').each(function(k, v) {
                            $(this).removeClass('hover');
                        });
                    })

            $listHead
                .off('.hover')
                .on('click.hover', '.reset', function() {
                    $(this).hide();
                    $obj = $(this).prev();
                    $obj.val('');
                    aAids[$obj.data('col') - 1] = 0;

                    index = $(this).closest('ul').index();

                    app.upItem(index);

                    app.setHeight();

                    app.upUrl();
                })
                // autocomplete 的 onselect 事件
                .find('input').on('onselect', function(el, data) {
                    // 如果已经存在 返回
                    if ($.inArray(data.id, aAids) > -1) {
                        $(this).val('');
                        return;
                    }
                    aAids[$(this).data('col') - 1] = aids = data.id;
                    app.getField();
                    app.upUrl();
                })
        },

        getField: function() {
            var _self = this;
            var _opt = _self.opt;

            $.ajax({
                    url: utils.url(_opt.url),
                    type: 'GET',
                    dataType: 'json',
                    // jsonpCallback: 'n08cms',
                    data: {
                        id: aids
                    },
                    beforeSend: function(){
                        $.jqModal.progress();
                    }
                })
                .done(function(data) {
                    var data = utils.parseJSON(data);
                    // 遍历多条数据
                    // data [{id: 1, name: 'aa'}, {id: 2, name: 'bb'}]
                    // aAids [0, 0, 1, 2]
                    $.each(data, function(i, item) {
                        $.each(aAids, function(n, id) {
                            if (id == item.id) {
                                // n 来确定位置(哪一列)
                                app.upItem1(++n, item);
                            };
                        });
                    });
                })
                .fail(function() {
                })
                .always(function() {
                    $.jqModal.progress('100%');
                });

        },
        /**
         * upItem
         * @description 更新列标题以及空列
         * @param  {Number} col 指定单独清空某一列
         */
        upItem: function(col) {
            var _opt = this.opt;
            var _data = _opt.data;
            var _listTpl = _opt.listTpl;

            if (!_data.length) return;

            var setCol = function(n) {
                var _html = '';

                $.each(_data, function(i, item) {
                    // 标题列表不调用函数
                    _html += _listTpl.replace(/%con%/, !n && item.title || '&nbsp;')
                                .replace(/%cls%/, item.field);
                });

                $listBody.find('.col' + n).html(_html);
            }

            if (col) {
                setCol(col);
            } else {
                $listBody.find('ul').each(setCol);
            }
        },
        /**
         * upItem1
         * @description 更新列数据
         * @param  {Number} n    第几列
         * @param  {Object} data 数据
         */
        // data = {name: 1, address: 2, ...} // 一条数据
        // _opt.data = [{name: 1, title: 2, ...}]
        upItem1: function(n, data) {
            var _opt = this.opt;
            var _html = '';
            var _listTpl = _opt.listTpl;

            $.each(_opt.data, function(i, item) {
                // 标题列表不调用函数
                var con = item.formatter && item.formatter.call(data, i) || data[item.field];
                _html += _listTpl.replace(/%con%/, con)
                                .replace(/%cls%/, item.field);
            });

            $listBody.find('.col' + n).html(_html);
            // 处理input
            $listHead.find('input').eq(--n)
                .val(data.name)
                .next().show();

            // app.doSearch($ths, inow);

            // 统一高度
            app.setHeight();
        },
        upUrl: function() {
            window.location.hash = '#/' + aAids.join(',');
        },
        /**
         * setHeight
         * @description 统一每行的高度，可以单独
         * @param {Number} index 指定某一行
         */
        setHeight: function(index) {
            var _setHeight = function(i) {
                var maxH = 0;
                $listBody.find('ul').find('li:eq(' + i + ')').each(function() {
                    maxH = Math.max(parseInt($(this).height()), maxH);
                }).css('min-height', maxH).eq(0).find('div').css('line-height', (maxH - 10) + 'px');
            }
            if (index) {
                // 某一行
                _setHeight(index);
            } else {
                // 行数
                var len = app.opt.data.length
                // 所有行
                for (var i = 0; i < len; i++) {
                    _setHeight(i);
                };
            }
        },
        /**
         * getImg
         * @param {String} url img的url
         * @param {Number} index 所在的索引
         * @return {String} img 标签
         */
        getImg: function(url, index) {
            var imgPre = BASE_URL.replace(/\/app_dev.php/, '');
            var _img = new Image();
            var _url = imgPre + url;
            // 图片加载完成 再设置一次
            _img.onload = function() {
                app.setHeight(index);
            }
            _img.src = _url;

            return '<img src="'+ _url  +'"/>';
        }
    };

    module.exports = app;
});

