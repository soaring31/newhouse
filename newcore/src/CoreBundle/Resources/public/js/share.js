/*!
 * Created share.
 * User: xying
 * Date: 17-01-05
 * Time:
 * To use share.
 */
define(function(require, exports, module) {
	var now_share_container="";
	var now_share_opts={};
	var _hasInit=false;

	var app={
		shareClass:"bdsharebuttonbox"
		,sharedClass:"_bdsharebuttonbox"
		,shareJsId:"global_share_js"

		/**
		 * @name reset
		 * @description 初始化及重设分享组件。
		 * @example
		 * ```html
		 *<div class="bdsharebuttonbox"
		 *	 share-text="标题"
		 *	 share-desc="描述"
		 *	 share-pic="图片(绝对地址)"
		 *	 share-url="链接地址(绝对地址)">
         *
		 *<a class="bds_mshare" data-cmd="mshare"></a>
		 *<a class="bds_qzone" data-cmd="qzone" href="#"></a>
		 *<a class="bds_tsina" data-cmd="tsina"></a>
		 *<a class="bds_baidu" data-cmd="baidu"></a>
		 *<a class="bds_renren" data-cmd="renren"></a>
		 *<a class="bds_tqq" data-cmd="tqq"></a>
		 *<a class="bds_more" data-cmd="more">更多</a>
		 *<a class="bds_count" data-cmd="count"></a>
		 *</div>
		 * ```
		 * 解释：请采用上述的html形式，按钮组外层的容器必须有一个叫bdsharebuttonbox的样式，
		 * 按钮用a，不过必须有data-cmd
		 * ，譬如：微信分享的`data-cmd="weixin"`是微信
		 * 调用init和reset方法。
		 * ```js
		 * seajs.use(['share'], function(share) {
		 *       //加载分享
		 *     
		 *   });
		 *
		 * ```
		 */
		,reset:function(){

			var _this=this;
			var shareJs=_this._initShareJs();
			$("."+_this.shareClass+"[share-init='true']").removeClass(_this.shareClass).addClass(_this.sharedClass);
			//--构造参数。
			$("."+_this.shareClass+"[share-init!='true']").each(function(){
				var _child=$(this);
				_child.mouseover(function(){
					now_share_container=$(this);
					var _desc=_child.attr("share-desc");
					var _text=_child.attr("share-text");
					var _url=_child.attr("share-url");
					var _pic=_child.attr("share-pic");
					var _shareOpts={
						text:_text
						,desc:_desc
						,pic:_pic
						,url:_url
					};
					_shareOpts={
						bdText : _text,
						bdDesc : _desc,
						bdUrl : _url,
						bdPic : _pic
					};
					if(_desc||_text||_url||_pic){
						now_share_opts=_shareOpts;
					}
					else{
						now_share_opts=false;
					}
				});

			});
			setTimeout(function(){
				$("."+_this.shareClass).attr("share-init","true");
				$("."+_this.sharedClass).removeClass(_this.sharedClass).addClass(_this.shareClass);
			},200);
			// if(_hasInit){
			// 	window._bd_share_main.init();
			// 	return;
			// }
			// _hasInit=true;
			if(!window._bd_share_config){
				window._bd_share_config = {
					common : {
						//此处放置通用设置
						onBeforeClick:function(cmd,config){
							if(now_share_opts){
								return now_share_opts;
							}
							else {
								return config;
							}
						}
					},
					share : [
						//此处放置分享按钮设置
					],

				};
			}

			var _share_js_url = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='
				+ ~(-new Date() / 36e5)+"&time="+new Date().getTime();

			// document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
			shareJs.attr("src",_share_js_url);


		}
		,init:function(){
			if(_hasInit){
				return;
			}
			this.reset();
			_hasInit=true;
		}
		,_initShareJs:function(){
			var shellJs="";
			var _this=this;

			if($("#"+this.shareJsId).length<=0){
				var sJs=document.createElement("script");
				sJs.id="global_share_js";

				$(document.body).append(sJs);

				shellJs=sJs;

			}

			shellJs=$('#'+_this.shareJsId);
			return shellJs;
		}

	};

	app.init();

	module.exports=app;
})



//# sourceMappingURL=share.js.map
