/**
 * author : Ahuing
 * date   : 2016-11-9
 * name   : map
 * modify : 2016-11-15
 */


define(['BMap', 'utils', 'template'], function(require, exports, module) {
    var utils = require('utils');
    var template = require('template');

    
    // 复杂的自定义覆盖物
    var ComplexCustomOverlay = function(point, tpl) {
        this._point = point;
        this._tpl = tpl;
        this.disableMassClear = false;
    }

    ComplexCustomOverlay.prototype = new BMap.Overlay();
    ComplexCustomOverlay.prototype.initialize = function(map) {
        this._map = map;
        var $div = this.$div = $(this._tpl);

        $div.css({
                zIndex: BMap.Overlay.getZIndex(this._point.lat)
            })
            /*.on('touchend', function() {
                $(document).trigger('clickMarker', [this]);
            })*/

        // var $span = this.$span = $div.find('[data-name]');
        var that = this;

        this._map.getPanes().labelPane.appendChild($div[0]);

        /*$div.mouseover(function() {
                $span.text(that._overText);
            })
            .mouseout(function() {
                $span.text(that._text)
            });*/

        return $div[0];
    }
    ComplexCustomOverlay.prototype.draw = function() {
        var map = this._map;
        var pixel = map.pointToOverlayPixel(this._point);
        // console.log(pixel);
        this.$div.css({
            left: (pixel.x - this.$div.width() / 2) + "px",
            top: pixel.y - 30 + "px"
        });
    }
    /**
     * @module Map
     */
    var Map = function() {
                this.init();
            };

    Map.prototype = {
        tpls: function() {
        },
        init: function(_event, args) {
            var _self = this;
            
            _self.initMap();
        },
        initMap: function() {
            var _self = this;

            var $map = _self.$map = $('[data-map]');
            // 没有dom返回
            if (!$map.length) {
                 return;       
            }
            var mapData = _self.mapData = $map.data();

            var option = utils.parseJSON(mapData.map);

            var _option = _self.option = $.extend(true, {}, {
                                redraw: true,
                                zoom: 14
                        }, option);

            var mp = _self.map = new BMap.Map($map[0], {
                        enableMapClick: false,
                        minZoom: option.minZoom || 12,
                        maxZoom: option.maxZoom || 16
                    });

            // 防止重复加载
            if ($map.hasClass('inited')) {
                return;
            };
            $map.addClass('inited');
            mp.enableScrollWheelZoom(); //开启鼠标滚轮缩放
            mp.disableDoubleClickZoom();
            mp.disableInertialDragging();

            mp.disableContinuousZoom();
            // mp.addControl(new BMap.ScaleControl()); 
            // mp.addControl(new BMap.OverviewMapControl()); 


            // 缩放按钮插件
            var topRightNavigation = new BMap.NavigationControl({
                anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
                type: BMAP_NAVIGATION_CONTROL_SMALL,
                offset: new BMap.Size(20, 60)
            });

            mp.addControl(topRightNavigation);
            // 缩放控件
            // mp.addControl(new BMap.NavigationControl({
            //     type: BMAP_NAVIGATION_CONTROL_LARGE,
            //     anchor: BMAP_ANCHOR_TOP_RIGHT
            // }));
            // setTimeout(function() {
                _self.refreshView()
            // }, 300)

            _self.events();

            $map.on('map-inited', function() {
                _self.inited = true
            })

        },
        events: function() {
            var _self = this;
            var $map = _self.$map;
            var mp = _self.map;

            // 当地图的图块加载完成后会触发动作
            mp.addEventListener('tilesloaded', function() {
                if (!_self.inited) {
                    mp.addControl(new BMap.ScaleControl({
                        anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
                        offset: new BMap.Size(20, 20)
                    }))

                    $map.trigger('map-inited')
                }
            })
            // 缩放地图后
            mp.addEventListener('zoomend', function(a) {
                var currentZoom = _self.zoom = mp.getZoom();
                setTimeout(function() {
                    $map.trigger('map-zoomend');
                }, 200);
            })
            // 手抓 touchend 代替dragend
            mp.addEventListener('touchend', function() {
                setTimeout(function() {
                    $map.trigger('map-dragend');
                }, 200);
            })

            $(document)
                .off('.map-libs')
                // 重置地图
                .on('click.map-libs', '[data-map-reset]', function() {
                    mp.reset();
                })
        },
        refreshView: function(opt) {
            var _self = this;
            var mp = _self.map;
            var mapOpt = $.extend(true, {}, _self.option, opt);
            
            _self.zoom = mapOpt.zoom;

            mp.centerAndZoom(new BMap.Point(mapOpt.point.lng, mapOpt.point.lat), mapOpt.zoom);
            // _self.drawMark();
        },
        drawMark: function() {
            var _self = this;
            _self.setZoomAndLv();
            _self.getMark();
        },
        setZoomAndLv: function(zoom) {
            var _self = this;
            var mp = _self.map;

            if (zoom) {
                _self.zoom = zoom;
                mp.setZoom(zoom);
            }
        },
        /**
         * @method addMark
         * @description 添加标注到地图
         * @param {Object} opt 标注的配置
         * @param {String} opt.type 地图的类型
         * @param {Object} opt.$input jquery对象 标注的对象 
         * @param {String} opt.tpl 模板的id
         * @param {Array|Object} opt.data 标注的数据，如果是数组是多个标注，如果是对像则为单个标注
         */
        addMark: function(opt, data) {
            // 百度地图API功能
            var _self = this;
            var mp = _self.map;
            var _opt = opt;
            var _data = data || _opt.data
            var isMultiple = $.type(_data) == 'array';
            // 处理清除
            var currentOverlay = function(options) {
                if (options.disableMassClear) {
                    mp.addEventListener('clearoverlays', function() {
                        mp.addOverlay(options.overlay);
                    });
                }
            };
            // 多个
            if (isMultiple) {
                var tpl = _opt.tpl.indexOf('#') == 0 ? $(_opt.tpl).text() : _opt.tpl;
                var render = template.compile(tpl);
                mp.clearOverlays();

                $.each(_data, function(i, item) {
                    // console.log(local.search(item.name));
                    item._index = i;
                    if (!item.point) {
                        return;
                    };

                    var myCompOverlay = new ComplexCustomOverlay(new BMap.Point(item.point.lng, item.point.lat), render(item));
                    // 禁止清除
                    currentOverlay({
                        disableMassClear: item.disableMassClear,
                        overlay: myCompOverlay
                    });
                    mp.addOverlay(myCompOverlay);
                });

            } else {
                // 单个
                var point = _opt.point;
                var oPoint = new BMap.Point(point.lng, point.lat);

                var myCompOverlay = new BMap.Marker(oPoint);
                myCompOverlay._type = 'marker';

                // 禁止清除
                currentOverlay({
                    disableMassClear: _opt.disableMassClear || false,
                    overlay: myCompOverlay
                });

                mp.addOverlay(myCompOverlay);
                // 设置中心
                mp.centerAndZoom(oPoint, _opt.zoom);
            }

        },
        getBounds: function() {
            var _self = this;  
            var mp = _self.map;
            // return {};
            var bs = mp.getBounds();   //获取可视区域
            var bssw = bs.getSouthWest();   //可视区域左下角
            var bsne = bs.getNorthEast();   //可视区域右上角

            return {
                    lng: 'between|'+ bssw.lng +','+ bsne.lng,
                    lat: 'between|'+ bssw.lat +','+ bsne.lat
                };
        },
        mapSimple: function() {
            var _self = this;
            var $map = _self.$map;

            $map.off('.map')
                .on('map-inited', function() {
                    var markerLists = utils.parseJSON($map.data('markerLists'));
                    // 固定标注
                    _self.addMark(markerLists);
                    _self.locked = true;
                })

            var $filterWrap = _self.$filterWrap = $('[data-filter-wrap]');

            $(document)
                .off('.map-simple')
                .on('click.map-simple', '[data-map-reset]', function() {
                    _self.map.clearOverlays();
                })
                // 点击关键字
                .on('click.map-simple', '[data-map-list-close]', function() {
                    $(this).parent().hide();
                })
                // 关键字检索
                .on('click.map-simple', '[data-map-local-search]', function() {
                    var $this = $(this);
                    var _opt = $.extend(true, {}, {
                                    radius: 1000,
                                    zoom: 15,
                                }, utils.parseJSON($this.data('mapLocalSearch')));
                    var mp = _self.map;
                    // 配置上的原点
                    var oPoint = _self.option.point;
                    var mPoint = new BMap.Point(oPoint.lng, oPoint.lat);

                    mp.clearOverlays();
                    mp.reset();
                    mp.setZoom(_opt.zoom);
                    // 增加圆
                    var circle = new BMap.Circle(mPoint, _opt.radius, {
                        fillColor: "blue",
                        strokeWeight: 1,
                        fillOpacity: 0.3,
                        strokeOpacity: 0.3
                    });
                    var $mapList = $('[data-map-list]');

                    // 高亮
                    if ($this.hasClass('active')) {
                        $this.removeClass('active');
                        $mapList.hide();
                        return;
                    } else {
                        $mapList.show();
                        $this.addClass('active')
                            .siblings('[data-map-local-search]').removeClass('active');
                    }

                    var onResultsHtmlSet = function() {
                        $mapList.prepend('<span data-map-list-close="1" class="map-list-close icon font-f-21"></span>');
                        $map.trigger('local-search-done');
                    };

                    var local = new BMap.LocalSearch(mp, {
                        // onSearchComplete: onSearchComplete,
                        onResultsHtmlSet: onResultsHtmlSet,
                        onMarkersSet: function(markers) {
                            // mp.clearOverlays();
                            // map.addMark({tpl: _opt.tpl}, markers);
                            mp.addOverlay(circle);
                            // $('[data-map-list-title]').html($this.html());
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
        // 定位
        location: function(cb){
            var _self = this;
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(r) {
                if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                    var lng = r.point.lng;
                    var lat = r.point.lat;
                } else {
                    var lng = r.point.lng;
                    var lat = r.point.lat;

                }
                cb.call(_self, lng, lat);
            }, {
                enableHighAccuracy: true
            });
        }
    };


    module.exports = new Map();
});
