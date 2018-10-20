/**
 * author : Ahuing
 * date   : 2016-11-9
 * name   : map
 * modify : 2016-11-15
 */

define(['mapLibs', 'utils', 'template', 'jqmodal'], function(require, exports, module) {
    var map = require('mapLibs');
    var utils = require('utils');
    var template = require('template');

    /**
     * @module Mapview
     */
    var Mapview = function() {
                var _self = this;
                var $map = map.$map;
                _self.events();
            };

    Mapview.prototype = {
        locked: false,
        // 重画的zoom
        drawZoom: 14,
        drawItem: function(opt) {
            var _self = this;
            var $map = map.$map;
            var _opt = opt;

            var zoom = map.zoom;
            var markerLists = utils.parseJSON($map.data('marker-lists'));
            // 固定标注
            map.addMark(markerLists);
        },
        events: function() {
            var _self = this;
            var $map = map.$map;

            $map.off('.map')
                .on('map-inited', function() {
                    _self.drawItem();
                    _self.locked = true;
                })

            var $filterWrap = _self.$filterWrap = $('[data-filter-wrap]');

            $(document)
                .off('.map-simple')
                .on('click.map-simple', '[data-map-reset]', function() {
                    map.map.clearOverlays();
                })
                // 点击关键字
                .on('click.map-simple', '[data-map-mkitem]', function() {
                    // console.log($(this));
                })
                // 关键字检索
                .on('click.map-simple', '[data-map-local-search]', function() {
                    var $this = $(this);
                    var _opt = $.extend(true, {}, {
                                    radius: 1000
                                }, utils.parseJSON($this.data('map-local-search')));
                    var mp = map.map;
                    var oPoint = map.option.point;
                    var mPoint = new BMap.Point(oPoint.lng, oPoint.lat);

                    mp.clearOverlays();
                    mp.reset();
                    // 增加圆
                    var circle = new BMap.Circle(mPoint, _opt.radius, {
                        fillColor: "blue",
                        strokeWeight: 1,
                        fillOpacity: 0.3,
                        strokeOpacity: 0.3
                    });
                    var $mapList = $('[data-map-list]');
                    var mapListData = $.extend(true, {
                                            clsPrefix: 'ls-item-'
                                        }, utils.parseJSON($mapList.data('map-list')));

                    var onSearchComplete1 = function(results){
                        // 结果处理函数
                        var resultsProcess = function(res) {
                            var ss = []
                            for (var i = 0; i < res.getCurrentNumPois(); i ++){
                                ss.push(template(mapListData.tpl.slice(1), $.extend({}, res.getPoi(i), {
                                    _index: i
                                })));
                            }
                            return ss;
                        }
                        // 判断状态是否正确
                        if (local.getStatus() == BMAP_STATUS_SUCCESS){
                            var s = [];
                            if ($.type(results) != 'array') {
                                s = resultsProcess(results);
                            } else {
                                // 多个时要单独处理下
                                for (var j = results.length - 1; j >= 0; j--) {
                                    s = s.concat(resultsProcess(results[j]));
                                };
                            }

                            $mapList.empty().html(s.join(''));
                        }
                    }
                    var onResultsHtmlSet = function() {
                        $mapList.find('li').each(function(i, el) {
                            $(this).find('div').addClass(function(i) {
                                return mapListData.clsPrefix + ++i;
                            });
                        });
                        $mapList.children().children().andSelf().addClass(function(i) {
                            return 'wrap-' + mapListData.clsPrefix + ++i;
                        })
                        .eq(2).find('a').text(function(i, text) {
                            return text.replace(/\[|\]/g, '');
                        })

                        $map.trigger('local-search-done');
                    };

                    var local = new BMap.LocalSearch(mp, {
                        // onSearchComplete: onSearchComplete,
                        onResultsHtmlSet: onResultsHtmlSet,
                        onMarkersSet: function(markers) {
                            // console.log(markers);
                            // mp.clearOverlays();
                            // map.addMark({tpl: _opt.tpl}, markers);
                            mp.addOverlay(circle);
                            $('[data-map-list-title]').html($this.html());
                        },
                        // 每页的数量
                        pageCapacity: 10,
                        renderOptions: {
                            panel: $mapList[0],
                            map: mp,
                            autoViewport: false,
                            selectFirstResult: false
                        }
                    });
                    local.searchNearby(_opt.name, mPoint, _opt.radius);
                })
        },
        // 街景
        panoInit: function(ele) {
            var _self = this;
            _self.panoAction('hide');
            
            var $ele = ele ? $(ele) : map.$map;
            var oPoint = map.option.point || utils.parseJSON($ele.data('map')).point;
            // console.log(oPoint);
            var panorama = _self.oPanorama = new BMap.Panorama($ele[0], {
                // albumsControl: true
            }); //默认为显示导航控件
            panorama.setPosition(new BMap.Point(oPoint.lng, oPoint.lat));
            // console.log(panorama.getId());
            if (!panorama.getId()) {
                // $.jqModal.tip('当前没有街景-_-', 'info');
            };
        },
        panoAction: function(act) {
            this.oPanorama && this.oPanorama[act]();
        }
    };

    module.exports = new Mapview();
});
