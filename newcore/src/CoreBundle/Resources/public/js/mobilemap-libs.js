define(['BMap', 'template'], function(require, exports, module){
	var template = require('template');
	var Map = function(){
		return new Map.fn.init();
	};



	Map.fn = Map.prototype = {
		init: function(){
			return this;
		},
		initMap: function(){
			var _self = this;
			var $map = _self.$map = $('[data-map]');
			var mapData = $map.data('map');
			var opt = parseJSON(mapData);
			var _opt = _self.opt = $.extend(true, {}, {
				redraw : true,
				zoom : 14
			}, opt);
			

			var mp = _self.map = new BMap.Map($map[0], {
                    enableMapClick: false,
                    minZoom: 12,
                    maxZoom: 16
                });



			if($map.hasClass('inited')) return;
			$map.addClass('inited');

			mp.enableScrollWheelZoom(); //开启鼠标滚轮缩放
            mp.disableDoubleClickZoom();
            mp.disableInertialDragging();
            mp.disableContinuousZoom();
            
          	mp.addControl(new BMap.OverviewMapControl()); 
          	
          	 _self.refreshView();

          	if($('[data-map-nav]').length > 0){
          		_self.navBtn();
          	}
		},

		refreshView: function(opt){
			var _self = this;
            var mp = _self.map;
            var mapOpt = $.extend(true, {}, _self.opt, opt);
            
            _self.zoom = mapOpt.zoom;

            var point = new BMap.Point(mapOpt.point.lng, mapOpt.point.lat);

            
            mp.centerAndZoom(point, mapOpt.zoom);

            _self.drawMap();

		},

		drawMap: function(opt){
			var _self = this;
            var mp = _self.map;
            var mapOpt = $.extend(true, {}, _self.opt, opt);
            
            _self.zoom = mapOpt.zoom;

            var oPoint = new BMap.Point(mapOpt.point.lng, mapOpt.point.lat);

            var Marker = new BMap.Marker(oPoint);
                mp.addOverlay(Marker);

    
				
            _self.textpop(Marker,oPoint);



           


		},

		textpop: function(Marker, point){
			var _self = this;
			var _opt = _self.opt;
			var mp = _self.map;

			// console.log(_opt.info);
			var tplText = template(_opt.tpl.slice(1),{data:_opt.info});



			var label = new BMap.Label(tplText, {offset: new BMap.Size( -120,-70)});
			Marker.setLabel(label);
			Marker.addEventListener('click',function(){
				$('.pop').toggle();

				// mp.openInfoWindow(InfoWindow,point);
			});
			
		},

		navBtn: function(){
			var _self = this;
			var mp = _self.map;
			var local = new BMap.LocalSearch(mp, {
				renderOptions:{map: mp}
			});
			$(document).on('tap', '[data-map-item]', function(){
				var $this = $(this);
				$this.siblings('[data-map-item]').removeClass('act');
				$this.addClass('act');
				var val = $(this).data('mapItem');
				// val = "'"+val+"'";
				// console.log(val);
				// console.log($(this).data('map-item'));
				local.search(val);
			});
		}
	};

	Map.fn.init.prototype = Map.prototype;

	 function parseJSON(jsonStr){
		return typeof jsonStr == 'object' ? jsonStr : (new Function('return ' + jsonStr))();
	};

	module.exports =  Map();
});
//# sourceMappingURL=..\..\src\js\mobilemap-libs.js.map
