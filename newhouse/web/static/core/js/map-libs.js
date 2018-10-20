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
            left: pixel.x + "px",
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
            return {
                close: '<div class="tools-btn-bar">\
                            <div data-tools-btn-cancel="1" class="tools-btn tools-btn-cancel">清除</div>\
                        </div>'
            };
        },
        init: function(_event, args) {
            var _self = this;
            
            if ($('[data-map-picker-btn]').length) {
                _self.initBtn();
            } else {
                _self.initMap();
            }
        },

        polylineStyle: {                        
            strokeColor: '#e43',    //边线颜色。
            fillColor: '#e43',      //填充颜色。当参数为空时，圆形将没有填充效果。
            strokeWeight: 2,       //边线的宽度，以像素为单位。
            strokeOpacity: .8,    //边线透明度，取值范围0 - 1。
            fillOpacity: .2,      //填充的透明度，取值范围0 - 1。
            strokeStyle: 'solid' //边线的样式，solid或dashed。
        },
        initMap: function() {
            var _self = this;

            var $map = _self.$map = $('[data-map]');

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
            mp.addControl(new BMap.OverviewMapControl()); 
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

            if (mapData.picker) {
                _self.picker(utils.parseJSON(mapData.picker));
            }

        },
        mapItemHover: function(e) {
            var _self = this;
            var $el = $(e.currentTarget);
            if (e.type == 'mouseenter') {
                $('[data-map-item="'+ $el.data('mapItem') +'"]')
                    .addClass('item-hover')
                    .each(function() {
                        _self.addStroke($(this));
                    });
            } else {
                $('[data-map-item="'+ $el.data('mapItem') +'"]').removeClass('item-hover');
                _self.removeStroke();
            }
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
            // 手抓
            mp.addEventListener('dragend', function() {
                // setTimeout(function() {
                    $map.trigger('map-dragend');
                // }, 200);
            })

            $(document)
                .off('.map-libs')
                // 地图上的标注
                .on('mouseenter.map-libs mouseleave.map-libs', '[data-map-item]', $.proxy(_self.mapItemHover, _self))
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
                var point = _opt.$input.data('marker') || _opt.$input.val();
                if (!point) return ;
                var aPoints = point.split(',');
                var oPoint = new BMap.Point(aPoints[0], aPoints[1]);

                var myCompOverlay = new BMap.Marker(oPoint);
                myCompOverlay._type = 'marker';
                mp.addOverlay(myCompOverlay);
                // 设置中心
                mp.centerAndZoom(oPoint, aPoints[2]);
            }

        },
        addStroke: function($el) {
            var _self = this;
            var mp = _self.map;
            var _polyline = $el.data('polyline') || $el.val();
            if (!_polyline) return ;
            var aPoints = _polyline.split('|');
            var aOPoint = [];

            $.each(aPoints, function(i, p) {
                var aPoint = p.split(',');
                aOPoint.push(new BMap.Point(aPoint[0], aPoint[1]));
            });
            /* iterate through array or object */
            var polyline = new BMap.Polygon(aOPoint, _self.polylineStyle);

            polyline._type = 'polyline';

            mp.addOverlay(polyline);
            // 设置中心
            if ($el.is('input')) {
                var zoom = aPoints[aPoints.length - 1].split(',')[2]
                mp.centerAndZoom(aOPoint[0], zoom);
            };
        },
        removeStroke: function(o) {
            var _self = this;
            var mp = _self.map;

            if (o) {
                mp.removeOverlay(o);
            } else {
                var overlays = mp.getOverlays();
                $.each(overlays, function(i, v) {
                    if (v._type == 'polyline') {
                        mp.removeOverlay(v);
                    };
                });
            }
        },
        /**
         * @method initBtn
         * @description 绑定拾取器按钮
         * @param  {Object} opt 按钮的配置
         * @param  {String} opt.url 点击后请求的`url`
         * @param  {String} opt.inputID 保存数据的表单id
         */
        initBtn: function() {
            var _self = this;

            var fnClick = function(e) {
                var $pickerBtn = $(this);
                var inputID = $pickerBtn.data('input-id');
                var url = $pickerBtn.data('url');

                var point = $(inputID).val();
                $.jqModal.modal({
                    content: url,
                    head: '选取百度地图',
                    type: "ajax",
                    animate: null,
                    css: {
                        width: 850,
                        height: 500
                    },
                    ajaxOpt: {
                        data: {
                            point: point
                        }
                    },
                    done: function() {
                        
                        _self.initMap();
                        _self.initSearch();
                    }
                })
            };

            require.async('jqmodal', function(pop) {
                $(document)
                    .off('.mapPicker')
                    .on('click.mapPicker', '[data-map-picker-btn]', fnClick);

                var $markerBtn = $('[data-map-btn-type="marker"]');

                if ($markerBtn.length) {
                    var mkInputID = $markerBtn.data('input-id');
                    var mkBtnType = $markerBtn.data('map-btn-type');
                    $(document).on('input.mapPicker propertychange.mapPicker', mkInputID, function() {
                        if (mkBtnType == 'marker') {
                            if (this.value && this.value.indexOf(',') > -1) {
                                var aPoint = this.value.split(',');
                            } else {
                                var aPoint = ['', ''];
                            }

                            $('[data-map-src="'+ mkInputID +'"]').each(function(i, el) {
                                this.value = aPoint[i]
                            });
                        }
                    })
                };
            });
            
        },
        /**
         * @method initSearch
         * @description 初始化搜索框
         * @param  {Object} tpl 搜索的模板
         * @param  {String} resultTpl 数据的模板
         */
        initSearch: function(){
            var _self = this;
            var mp = _self.map;
            var tpl = '<div class="map-search">\
                        <input class="search-value" type="text"/>\
                        <a class="search-btn"><i class="font-ico-search"></i></a>\
                        </div>';
            var resultTpl = '<div class="info">\
                            <p><%= info.title %></p>\
                            <p><%= info.address %></p>\
                            <p>确定标记在这个位置吗？</p>\
                        </div>\
                        <div class="blank10"></div>\
                        <div class="form">\
                            <a class="btn btn-xs mr5" data-point="{lat:<%= point.lat %>,lng:<%= point.lng %>}" data-map-submit="2">确定</a>\
                        </div>';
            $('[data-map]').append($(tpl));

            var local = new BMap.LocalSearch(mp, {
                onSearchComplete: result
            })

            var $searchBtn = $('.search-btn');
            var $searchInput = $('.search-value');
            
            $searchInput.on('keypress.mapsearch', function(e){
                if(e.keyCode == 13)
                    search();
            })
            $searchBtn.off('.mapSearch').on('click.mapSearch', function(){
                search();
            });

            function search(){
                var value = $searchInput.val();
                local.search(value);
                $.jqModal.tip('loading','load');
            }
            // 搜索数据的修改
            function result(data){
               if (local.getStatus() == BMAP_STATUS_SUCCESS) {
                    var s = [];
                    
                    for (var i = 0; i < data.getCurrentNumPois(); i++) {
                        var o = {};
                        o.point = data.getPoi(i).point;
                        o.info = {
                            title: data.getPoi(i).title,
                            address: data.getPoi(i).address
                        };
                        s.unshift(o);
                    }
                    var inputID = utils.parseJSON(_self.mapData.picker).inputID;
                     
                    _self.picker(utils.parseJSON(_self.mapData.picker),s,resultTpl);
                    $.jqModal.tip('hide');
                }else{
                   
                    // $.alert('没有数据');
                    $.jqModal.tip('没有数据','error');
                }
               
            };
        },
        /**
         * @method pick
         * @description 生成地图拾取页面
         * @param  {Object} opt 拾取器的配置
         * @param  {String} opt.poptpl template的id
         * @param  {String} opt.type 地图类型 `marker|polyline` 点和线
         * @param  {String} opt.inputID 保存数据的表单id
         * @param  {String} data 搜索得到的数据
         * @param  {String} searchTpl 搜索的数据template
         */
        picker: function(opt, data, searchTpl) {

            var _self = this;
            var _opt = opt;
            var mp = _self.map;

            var tpl = '<div class="info">确定标记在这个位置吗？</div>\
                        <div class="blank10"></div>\
                        <div class="form">\
                            <a class="btn btn-xs mr5" data-map-submit="1">确定</a>\
                            <a class="btn btn-xs btn-danger " data-map-cancel="1" >取消</a>\
                        </div>'
            var tplText = _opt.poptpl ? template(_opt.poptpl.slice(1), {}) : tpl;

            var infowindow = new BMap.InfoWindow(tplText, {offset: new BMap.Size(0, -20)});

            // 搜索后添加marker
            if(data){
                var stpl = searchTpl.indexOf('#') == 0 ? $(searchTpl).text() : searchTpl;
                var render = template.compile(stpl);
                $.each(data, function(i, item){
                    var infoPoint = new BMap.Point(item.point.lng, item.point.lat);
                    var myCompOverlay = new BMap.Marker(infoPoint);
                    var infowindow = new BMap.InfoWindow(render(item), {offset: new BMap.Size(0, -20)});
                    mp.openInfoWindow(infowindow, infoPoint);
                    myCompOverlay.addEventListener('mouseover', function(){
                        mp.openInfoWindow(infowindow, infoPoint);
                        $('a[data-map-submit=2]').on('click', submit);
                    });
                    mp.addOverlay(myCompOverlay);
                    
                })
                    $('a[data-map-submit=2]').on('click', submit)

                    function submit(){
                        var point = [];
                        point.push(utils.parseJSON($(this).data('point')));
                        var aPointStr = getPoint(point);
                        $(_opt.inputID).val(aPointStr).trigger('input.mapPicker');
                        $('.jq-modal-act').jqModal('hide');
                    }
            }

            function getPoint(point){
                var zoom = _self.zoom = mp.getZoom();
                var aPointStr = $.map(point, function(v) {
                            return v.lng + ',' + v.lat;
                        }).join('|') + ',' + zoom;
                return aPointStr;
            }

            require.async(['BMapDraw', 'BMapDrawCss'], function() {
                // 百度地图API功能
                var overlays = [];
                var overlaycomplete = function(e) {                   

                    var overlay = e.overlay;
                    var aPoint = [];
                    

                    overlay._type = overlay.getPosition ? 'marker' : 'polyline';

                    clearOverlays();
                    // clearOverlays(overlay._type);
                    // 单个或多个
                    if (overlay.getPosition) {
                        aPoint.push(overlay.getPosition());
                    } else {
                        aPoint = overlay.getPath();
                    }

                    overlays.push(overlay);

                    var aPointStr = getPoint(aPoint);

                    // console.log(aPointStr);
                    // 保存坐标
                    var lastPoint = aPoint[aPoint.length - 1];
                    var infoPoint = new BMap.Point(lastPoint.lng, lastPoint.lat);

                    mp.openInfoWindow(infowindow, infoPoint);

                    setTimeout(function() {
                        _opt.type == overlay._type && fnON(_opt.inputID, aPointStr, overlay._type);
                    }, 100);


                    _self.$map.trigger('picker.map', [overlay._type, aPointStr]);
                    // 点击地图时也重置工具
                    mp.removeEventListener('click');
                    mp.addEventListener('click', resetModes);
                };

                var fnON = _self.fnON = function(inputID, point, type) {
                    $('a[data-map-submit=1]').off('.dragMap').on('click.dragMap', function() {
                        $(inputID).val(point).trigger('input.mapPicker');
                        // 处理经纬度
                        /*if (type == 'marker') {
                            var aPoint = point.split(',');
                            $('[data-map-src="'+ inputID +'"]').each(function(i, el) {
                                this.value = aPoint[i]
                            });
                        }*/
                        $('.jq-modal-act').jqModal('hide');
                    })
                    $('a[data-map-cancel=1]').off('.dragMap').on('click.dragMap', function() {
                        infowindow.close();
                        clearOverlays();
                        // 点击取消时重置工具
                        resetModes();
                    });  
                }
                var resetModes = function() {
                    if (_opt.type == 'marker') {
                        drawingManager.setDrawingMode(BMAP_DRAWING_MARKER);
                    } else if (_opt.type == 'polyline') {
                        drawingManager.setDrawingMode(BMAP_DRAWING_POLYLINE);
                    }
                    // drawingManager.open();
                }
                //实例化鼠标绘制工具
                var drawingModes = [];
                if (_opt.type == 'marker') {
                    drawingModes.push(BMAP_DRAWING_MARKER);
                } else if (_opt.type == 'polyline') {
                    drawingModes.push(BMAP_DRAWING_POLYLINE);
                }

                var drawingManager = new BMapLib.DrawingManager(mp, {
                    isOpen: false, //是否开启绘制模式
                    enableDrawingTool: true, //是否显示工具栏
                    drawingToolOptions: {
                        anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
                        offset: new BMap.Size(5, 5), //偏离值
                        scale: .7,
                        drawingModes : drawingModes
                    },
                    circleOptions: _self.polylineStyle, //圆的样式
                    polylineOptions: _self.polylineStyle, //线的样式
                    polygonOptions: _self.polylineStyle, //多边形的样式
                    rectangleOptions: _self.polylineStyle //矩形的样式
                });

                var setDefMarker = function(type) {
                    mp.getOverlays()
                }
                
                 //添加鼠标绘制工具监听事件，用于获取绘制结果
                drawingManager.addEventListener('overlaycomplete', overlaycomplete);

                // 激活默认工具 标注默认位置
                if (_opt.type == 'marker') {
                    drawingManager.setDrawingMode(BMAP_DRAWING_MARKER);
                    _self.addMark({$input: $(_opt.inputID)})
                } else if (_opt.type == 'polyline') {
                    drawingManager.setDrawingMode(BMAP_DRAWING_POLYLINE);
                    _self.addStroke($(_opt.inputID));
                }

                overlays = mp.getOverlays();
               
                drawingManager.open();

                function clearOverlays(T) {

                    for(var i = 0; i < overlays.length; i++){
                        if (T) {
                            if (overlays[i]._type == T) {
                                mp.removeOverlay(overlays[i]);
                            } 
                        } else {
                            mp.removeOverlay(overlays[i]);
                            overlays.length = 0   
                        }
                    }
                }   
            });
        },
        getBounds: function() {
            var _self = this;  
            var mp = _self.map;

            var bs = mp.getBounds();   //获取可视区域
            var bssw = bs.getSouthWest();   //可视区域左下角
            var bsne = bs.getNorthEast();   //可视区域右上角

            return {
                    lng: 'between|'+ bssw.lng +','+ bsne.lng,
                    lat: 'between|'+ bssw.lat +','+ bsne.lat
                };
        }
    };


    module.exports = new Map();
});

//# sourceMappingURL=http://localhost:8888/public/js/map-libs.js.map
