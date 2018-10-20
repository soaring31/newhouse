'use strict';

/**
 * author : Ahuing
 * date   : 2016-11-9
 * name   : mobile-map-complex
 * modify : 2017-10-13 15:47:38
 */
// @require('babel');
define(['mapLibs', 'utils', 'template', 'loadmore', 'filter', 'ajax'], function (require, exports, module) {
    var map = require('mapLibs');
    var utils = require('utils');
    var template = require('template');
    var loadmore = require('loadmore');
    var filter = require('filter');
    var ajax = require('ajax');

    /**
     * @module Mapview
     */
    var Mapview = function Mapview() {
        var _self = this;
        var $map = map.$map;

        // 拖拽
        $map.on('map-dragend', function () {
            // 拖拽 大于800米才刷新地图
            if (_self.getDistance() > 800) {
                _self.drawItem();
            }
        });
        // 缩放 
        $map.on('map-zoomend', function () {
            _self.drawItem();
        });
        _self.events();

        $('body')
        // 注意data, refresh == 1 时才会触发调用
        .data('refresh', 1).off('refresh').on('refresh', function () {
            _self.drawItem();
        });

        /*map.location(function(lng, lat) {
            map.addMark({
                point: {
                    lng: lng,
                    lat: lat
                },
                zoom: 15,
                disableMassClear: true
            })
        })*/
    };

    Mapview.prototype = {
        // 重画的zoom
        isArea: function isArea() {
            return map.zoom < 14;
        },
        drawItem: function drawItem(oParams, el) {
            var _self = this;
            var $map = map.$map;
            // 当前视图的设置
            var markerList = _self.getMarkerListOpt();
            // 数据
            var _oParams = $.extend(true, {}, markerList.urlOpt, oParams);
            // 标注本身的设置
            var itemOpt = $.extend(true, { refresh: 1 }, utils.parseJSON(el ? $(el).data('opt') : {}));

            if (!markerList) return false;

            // 停止掉之前的请求
            if (_self.ajax) {
                _self.ajax.abort();
            }
            $.showIndicator();

            _oParams = _self.getParams(_oParams);

            _self.ajax = ajax({
                url: map.option.url,
                type: 'GET',
                dataType: 'json',
                data: _oParams,
                success: function success(result) {
                    $.hideIndicator();
                    if (result.status) {
                        // 标注在地图上
                        if (itemOpt.refresh) {
                            var markerData = result.data.mklist || result.data.list;
                            map.addMark(markerList, markerData, result.data.page);

                            // 提示数量 有弹窗时不显示
                            if (!$('.popup-map-complex-info').hasClass('modal-in')) {
                                $.toast('共' + result.data.page.pageCount + '个结果', 1000);
                                // 清除高亮的marker
                                _self.actItemId = null;
                            }
                            // 处理高亮
                            _self.setActiveItem();
                        }
                    } else {
                        $.alert(result.info);
                    }
                },
                error: function error() {
                    $.hideIndicator();
                }
            });
        },
        // 处理高亮
        setActiveItem: function setActiveItem() {
            var _self = this;
            _self.actItemId && $('[data-map-item="' + _self.actItemId + '"]').addClass('act').siblings().removeClass('act');
        },
        // 获取drag的距离
        getDistance: function getDistance() {
            var _self = this;
            var oldCenterPoint = _self.centerPoint;
            var centerPoint = _self.centerPoint = map.map.getCenter();
            return map.map.getDistance(oldCenterPoint, centerPoint).toFixed(2);
        },
        // 获取当前视图的配置
        getMarkerListOpt: function getMarkerListOpt() {
            var _self = this;
            var markerList;
            var zoom = map.zoom * 1;
            var markerLists = utils.parseJSON(map.$map.data('markerLists'));

            $.each(markerLists, function (i, item) {
                if ($.inArray(zoom, item.zoom) > -1) {
                    markerList = item;
                }
            });
            if (!markerList) {
                console.error('当前地图缩放（' + zoom + '）不在配置范围, 已为您切换的最佳缩放视图。');
                // map.zoom = 14;
                map.map.setZoom(14);
            }
            // console.log(zoom, markerList);
            return markerList;
        },
        events: function events() {
            var _self = this;
            var $map = map.$map;

            $map.off('.map').on('map-inited', function () {
                _self.drawItem();
                _self.centerPoint = map.map.getCenter();
            });

            var $filterWrap = _self.$filterWrap = $('[data-filter-wrap]');
            $(document).off('.map-complex')
            // 用touch代替click，不然不生效
            .on('touchend', '[data-map-item]', function (e) {
                _self.actItemId = $(this).data('mapItem');
                // 弹窗
                _self.popup(this);
                // 注意和popup的顺序
                _self.zoom(this);
                e.stopPropagation();
            });
        },
        // 弹窗
        popup: function popup(el) {
            var _self = this;
            var $el = $(el);
            var oPopup = $.popup('.popup-map-complex-info');
            _self.setActiveItem();
            // 处理弹窗标题
            $(oPopup).find('.title').text(function (i, v) {
                return $el.data('title') || v;
            });

            $('.popup-overlay').off('click').on('click', function () {
                $.closeModal(oPopup);
            });

            var oData = $el.serializeObject();

            oData.pageIndex = 1;
            // 传当前id, 直接刷新列表
            loadmore.refresh({
                data: oData
            });
        },
        // 缩放
        zoom: function zoom(el) {
            if (!el) {
                return;
            }
            var $el = $(el);
            if ($el.data('point')) {
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
                map.map.reset();
            }
        },
        getParams: function getParams(opt) {
            var _self = this;
            // 如果是区域不限制搜索范围
            var _bounds = map.getBounds();

            // 链接上的参数 和 列表上的常驻隐藏参数 $container.data('ajaxData') 
            var data = $.extend({}, map.$map.data('ajaxData'), utils.getUrlParams(), utils.getUrlHash());
            return $.extend(true, {}, _bounds, data, opt);
        }
    };

    module.exports = new Mapview();
});
//# sourceMappingURL=mobile-map-complex.js.map
