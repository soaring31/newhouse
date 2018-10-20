/**
 * author : Ahuing
 * date   : 2016-11-9
 * name   : {{name}}
 * modify : {{ now()|date('Y-m-d H:i:s') }}
 */
// @require('babel');
define(['mapLibs', 'utils', 'template', 'jqmodal', 'filter', 'pagination'], function(require, exports, module) {
    var map = require('mapLibs');
    var utils = require('utils');
    var template = require('template');
    var filter = require('filter');

    /**
     * @module Mapview
     */
    var Mapview = function() {
                var _self = this;
                var $map = map.$map;
                // 缩放 或 拖拽
                $map.on('map-zoomend map-dragend', function() {
                    if (!_self.isArea()) {
                        // 当当前区域的坐标和可视地图中心偏离过多时，重置区域选项，后面再做
                        var $curRegion = $('a.act[data-name="region"]').not('.default');
                        if ($curRegion.length) {
                            var aPoint = $curRegion.data('point').split(',');
                            var curPoint = new BMap.Point(aPoint[0], aPoint[1]);
                            var centerPoint = map.map.getCenter();
                            if (map.map.getDistance(curPoint, centerPoint).toFixed(2) > 3000) {
                                filter.clickHandle('.default[data-name="region"]')
                            }
                        } else {
                            // 正常视图就重新显示就行
                            _self.drawItem();
                        }
                    } else {
                        // 区域时就重置区域选项
                        filter.clickHandle('.default[data-name="region"]')
                        _self.drawItem({aid: ''});
                    }
                })
                _self.events();

                filter.init({
                    callBack(el) {
                        _self.zoom(el);
                        // 清除按钮
                        $('[data-clear="all"]')[filter.getParams().length ? 'show' : 'hide']();
                        // 处理更多
                        var moreLen = filter.getParams('[data-filter-item-list]').length;
                        if (moreLen) {
                            $('[data-more-num]').text('('+ moreLen + ')')
                                .closest('li').addClass('active');
                        } else {
                            $('[data-more-num]').text('')
                                .closest('li').removeClass('active');
                        }
                        // 显示标注
                        _self.drawItem(null, el);
                    }
                });
            };

    Mapview.prototype = {
        locked: false,
        // 重画的zoom
        isArea: function() {
            return map.zoom < 14;
        },
        drawItem: function(oParams, el) {
            var _self       = this;
            var $map        = map.$map;
            // 当前视图的设置
            var markerList = _self.getMarkerListOpt();
            // 数据
            var _oParams = $.extend(true, {}, markerList.urlOpt, oParams);
            // 标注本身的设置
            var itemOpt = $.extend(true, {refresh: 1}, utils.parseJSON($(el).data('opt')));
            // console.log(itemOpt);
            if (!markerList) return false;

            // 停止掉之前的请求
            if (_self.ajax) {
                _self.ajax.abort();
            }
            $.jqModal.progress();

            _oParams = _self.getParams(_oParams);

            _self.ajax = $.ajax({
                url:  map.option.url,
                type: 'GET',
                dataType: 'json',
                data: _oParams
            })
            .done(function(result) {
                $.jqModal.progress('100%');
                if (result.status) {
                    // 标注在地图上
                    if (itemOpt.refresh) {
                        map.addMark(markerList, result.data.mklist || result.data.list, result.data.page);
                    }
                    // 显示在列表中
                    _self.list(markerList, result.data.list, result.data.page)
                } else {
                    $.jqModal.alert(result.info);
                }
            })
            .fail(function() {
                $.jqModal.progress('100%');
            })
            .always(function() {
                // console.log("complete");
            });
        },
        // 获取当前视图的配置
        getMarkerListOpt: function() {
            var _self = this;
            var markerList;
            var zoom        = map.zoom * 1;
            var markerLists = utils.parseJSON(map.$map.data('marker-lists'));

            $.each(markerLists, function(i, item) {
                if ($.inArray(zoom, item.zoom) > -1) {
                    markerList = item;
                }
            });
            if (!markerList) {
                console.error('当前地图缩放（'+ zoom +'）不在配置范围, 已为您切换的最佳缩放视图。');
                // map.zoom = 14;
                map.map.setZoom(14)
            }
            return markerList;
        },
        events: function() {
            var _self = this;
            var $map = map.$map;

            $map.off('.map')
                .on('map-inited', function() {
                    _self.drawItem();
                })

            var $filterWrap = _self.$filterWrap = $('[data-filter-wrap]');

            $(document)
                .off('.map-complex')
                // 搜索
                .on('click.map-complex', '[data-map-search-btn="1"]', function() {
                    var oSearchParams = $('input[data-map-search-input]').serializeObject();
                    _self.drawItem(oSearchParams);
                    return false;
                })
                // 回车搜索
                .on('keypress.map-complex', 'input[data-map-search-input]', function(e) {
                    var oSearchParams = $(this).serializeObject();
                    if (e.keyCode == 13) _self.drawItem(oSearchParams);
                })
                // 地图（列表）上区域的点击 同时触发检索条件里的点击
                .on('click.map-complex', '[data-name="region"]', function(e) {
                    var $this = $(this);
                    if (!$this.closest('[data-filter-item-select]').length) {
                        var $sameThis = $('[data-filter-item-select]')
                                            .find('[data-name="region"][data-value="'+ $this.data('value') +'"]');
                        filter.clickHandle($sameThis[0]);
                    };
                    return false;
                })
                // 收起
                .on('click.map-complex', '[data-toggle]', function() {
                    var $this = $(this);
                    var con = $this.html();
                    var oldCon = $this.data('oldCon');
                    var toggleCon = $this.data('toggle-con');

                    $this.html(toggleCon == con ? oldCon : toggleCon);
                    $this.data('oldCon', con);
                    $($this.data('toggle')).toggleClass('toggle-cls');
                })
        },
        // 缩放
        zoom: function(el) {
            var $el = $(el);
            if ($el.data('name') == 'region' && $el.data('point')) {
                var _self = this;
                var aPoint = []; // 地图中心
                aPoint = $el.data('point').split(',');
                if (aPoint.length) {
                    var refreshViewOpt = {
                        point: {
                            lng: aPoint[0],
                            lat: aPoint[1]
                        },
                        // zoom: aPoint[2] || 14
                        zoom: 14
                    };
                    map.refreshView(refreshViewOpt);
                }
            } else if ($el.is('[data-clear]')) {
                map.map.reset()
            }
        },
        getParams: function(opt) {
            var _self = this;
            // 如果是区域不限制搜索范围
            var _bounds = _self.isArea() ? {} : map.getBounds();
            // var _bounds = map.getBounds();
            var aParams = filter.getParams();
            var _params = {};
            $.each(aParams, function(i, item) {
                _params[item.name] = item.value;
            });
            return $.extend(true, {}, _bounds, _params, opt);
        },
        list: function(markerList, data, page) {
            var _self      = this;
            var $map       = map.$map;
            var zoom       = map.zoom;
            var listOpt    = markerList.listOpt;
            var $mapList   = $(listOpt.wrap);

            var aList = [];
            var tpl = listOpt.tpl;
            // 列表头
            var title;
                //  注意顺序aid放后面
            $('.act[data-name="region"]')
                .not('.default')
                .each(function() {
                    title = $(this).data('title');
                });
            $('.act[data-name="aid"]')
                .each(function() {
                    title = $(this).data('title');
                });

            $('[data-list-top]')
                .html(template(listOpt.tplListTop.slice(1), {
                                                                title: title,
                                                                num: page.pageCount
                                                            }))
            // 列表
            if (page.pageCount * 1) {
                $.each(data, function(i, item) {
                    item.point.lat = item.point.lat || map.option.point.lat;
                    item.point.lng = item.point.lng || map.option.point.lng;
                    aList.push(template(tpl.slice(1), item))
                });
            } else {
                aList.push('<li class="tac">暂无数据！</li>')
            }

            $mapList
                .html(aList.join(''))
                // 滚动到顶部
                .parent().scrollTop(0);

            _self.page(page);
        },
        // 分页
        page: function(page) {
            var _self = this;
            var $page = $('[data-page="1"]');
            var pageselectCallback = function(p) {
                //动态更新数据
                _self.drawItem({pageIndex: p + 1})
                return false;
            }

            $page.pagination(page.pageCount, {
                num_edge_entries    : 1, //边缘页数
                num_display_entries : 3, //主体页数
                callback            : pageselectCallback,
                items_per_page      : page.pageSize, //每页显示1项
                current_page        : page.pageIndex - 1,
                prev_text           : '前一页',
                next_text           : '后一页',
                load_first_page     : false
            });
        }
    };

    module.exports = new Mapview();
});
