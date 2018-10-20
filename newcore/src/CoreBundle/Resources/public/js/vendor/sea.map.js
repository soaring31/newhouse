(function(){
	//取配置对象及缓存版本
	var map = document.getElementById("jsConfig")
		,v = (map&&map.src||"").split("?")
		,v = v.length>0?v[1]||"":""
	var cfg = {
			//文件目录bundles/
			base: jsbase,
			//js文件绝对路径
			alias: {
				//jquery路径				
				"jquery"			: "core/js/vendor/dialog/lib/jquery-1.10.2.js"
				,"dialog"			: "core/js/vendor/dialog/src/dialog-plus.js"
				,"jplayer"			: "core/js/vendor/audioplayer/inc/jquery.jplayer.min.js"
				,"miniPlayer"		: "core/js/vendor/audioplayer/inc/jquery.mb.miniPlayer.js"
				,"miniPlayer-plus"	: "core/js/vendor/audioplayer/inc/jquery.mb.miniPlayer-plus.js"
				,"miniplayer.css"	: "core/js/vendor/audioplayer/css/miniplayer.css"
				,"ueditor"			: "core/js/vendor/ueditor/ueditor.all.js"
				,"ueditor.conf"		: "core/js/vendor/ueditor/ueditor.config.js"
				,"chart"			: "core/js/vendor/myChart/echarts-plain.js"
				,'codemirror'		: "core/js/vendor/codemirror/codemirror.js"
				,'javascript'		: "core/js/vendor/codemirror/javascript.js"
				,'php'				: "core/js/vendor/codemirror/php.js"
				,'clike'			: "core/js/vendor/codemirror/clike.js"
				,'codemirrorcss'	: "core/js/vendor/codemirror/codemirror.css"
				,'jscolor'			: "core/js/vendor/cart/jscolor.js"
				,"slide"			: "core/js/vendor/slide.js"
				,"formvalidator"	: "core/js/formvalidator.js"
				,"croppercss"		: "core/js/vendor/cropper/cropper.css"
				,'baidumap'         : 'core/js/baidumap'
				,"coco2dx"			: "core/js/vendor/cocos2d-js-v3.13/cocos2d-js-v3.13.js"
					
				//核心JS
				,"core"				: "core/js/core.js"					
				,"comm"		        : "core/js/comm.js"					
				//弹窗JS
				,"pop"				: "core/js/pop.js"				
				//翻页JS	
				,"pager"			: "core/js/pager.js"				
				//拼音转换JS	
				,"pinyin"			: "core/js/pinyin.js"			
				,"main"				: "core/js/main.js"
				,"manage_main"		: "manage/js/main.js"				
				,"auto"             : "auto/js/seajs.config.js"
				,"template"         : "house/js/static/template"
				,"member"           : "member/js/seajs.config.js"
				,"chosen"           : "core/js/plugin/chosen.jquery"
			},
			preload: ["jquery", "ueditor.conf", "codemirror"],	// 预加载项
			charset: 'UTF-8'		// 文件编码
		}
	//转换网址
	for(var c in cfg.alias){
		cfg.alias[c] += v&&("?"+v);
	}

	try{
		//配置系统
		seajs.config(cfg);
	}catch(e){};
})();
