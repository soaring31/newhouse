function b(){h=$(window).height(),t=$(document).scrollTop(),$("#back-top").attr("daty-id")?t>h?($("#gotop").show(),$(".fix-right .tips,.fix-right .has-ask").show()):($("#gotop").hide(),$(".fix-right .tips,.fix-right .has-ask").hide()):($(".fix-right .tips,.fix-right .has-ask").show(),t>h?$("#gotop").show():$("#gotop").hide())}function ent(){$(".ewm-close").click(function(e){$(".sh-erweima").hide().addClass("hide"),$(this).hide();var t=$(".sh-erweima").attr("class");localStorage.setItem(void 0,JSON.stringify(t))});var e=localStorage.getItem(void 0);e?e.indexOf("sh-erweima hide")>=0&&$(".sh-erweima,.ewm-close").hide():$(".sh-erweima,.ewm-close").show()}!function(e){e.fn.scrollLoading=function(t){var n={attr:"data-url",container:e(window),callback:e.noop},a=e.extend({},n,t||{});a.cache=[],e(this).each(function(){var t=this.nodeName.toLowerCase(),n=e(this).attr(a.attr),o={obj:e(this),tag:t,url:n};a.cache.push(o)});var o=function(t){e.isFunction(a.callback)&&a.callback.call(t.get(0))},r=function(){var t,n=a.container.height();t=e(window).get(0)===window?e(window).scrollTop():a.container.offset().top,e.each(a.cache,function(e,a){var r=a.obj,i=a.tag,s=a.url;if(r){var c=r.offset().top-t,l=c+r.height();(c>=0&&c<n||l>0&&l<=n)&&(s?"img"===i?o(r.attr("src",s)):r.load(s,{},function(){o(r)}):o(r),a.obj=null)}})};r(),a.container.bind("scroll",r)}}(jQuery),function(e,t,n,a){var o=e(t);e.fn.lazyload=function(a){function r(){var t=0;s.each(function(){var n=e(this);if(!c.skip_invisible||n.is(":visible"))if(e.abovethetop(this,c)||e.leftofbegin(this,c));else if(e.belowthefold(this,c)||e.rightoffold(this,c)){if(++t>c.failure_limit)return!1}else n.trigger("appear"),t=0})}var i,s=this,c={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:t,data_attribute:"original",skip_invisible:!0,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return a&&(void 0!==a.failurelimit&&(a.failure_limit=a.failurelimit,delete a.failurelimit),void 0!==a.effectspeed&&(a.effect_speed=a.effectspeed,delete a.effectspeed),e.extend(c,a)),i=void 0===c.container||c.container===t?o:e(c.container),0===c.event.indexOf("scroll")&&i.bind(c.event,function(){return r()}),this.each(function(){var t=this,n=e(t);t.loaded=!1,void 0!==n.attr("src")&&!1!==n.attr("src")||n.is("img")&&n.attr("src",c.placeholder),n.one("appear",function(){if(!this.loaded){if(c.appear){var a=s.length;c.appear.call(t,a,c)}e("<img />").bind("load",function(){var a=n.attr("data-"+c.data_attribute);n.hide(),n.is("img")?n.attr("src",a):n.css("background-image","url('"+a+"')"),n[c.effect](c.effect_speed),t.loaded=!0;var o=e.grep(s,function(e){return!e.loaded});if(s=e(o),c.load){var r=s.length;c.load.call(t,r,c)}}).attr("src",n.attr("data-"+c.data_attribute))}}),0!==c.event.indexOf("scroll")&&n.bind(c.event,function(){t.loaded||n.trigger("appear")})}),o.bind("resize",function(){r()}),/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)&&o.bind("pageshow",function(t){t.originalEvent&&t.originalEvent.persisted&&s.each(function(){e(this).trigger("appear")})}),e(n).ready(function(){r()}),this},e.belowthefold=function(n,a){return(void 0===a.container||a.container===t?(t.innerHeight?t.innerHeight:o.height())+o.scrollTop():e(a.container).offset().top+e(a.container).height())<=e(n).offset().top-a.threshold},e.rightoffold=function(n,a){return(void 0===a.container||a.container===t?o.width()+o.scrollLeft():e(a.container).offset().left+e(a.container).width())<=e(n).offset().left-a.threshold},e.abovethetop=function(n,a){return(void 0===a.container||a.container===t?o.scrollTop():e(a.container).offset().top)>=e(n).offset().top+a.threshold+e(n).height()},e.leftofbegin=function(n,a){return(void 0===a.container||a.container===t?o.scrollLeft():e(a.container).offset().left)>=e(n).offset().left+a.threshold+e(n).width()},e.inviewport=function(t,n){return!(e.rightoffold(t,n)||e.leftofbegin(t,n)||e.belowthefold(t,n)||e.abovethetop(t,n))},e.extend(e.expr[":"],{"below-the-fold":function(t){return e.belowthefold(t,{threshold:0})},"above-the-top":function(t){return!e.belowthefold(t,{threshold:0})},"right-of-screen":function(t){return e.rightoffold(t,{threshold:0})},"left-of-screen":function(t){return!e.rightoffold(t,{threshold:0})},"in-viewport":function(t){return e.inviewport(t,{threshold:0})},"above-the-fold":function(t){return!e.belowthefold(t,{threshold:0})},"right-of-fold":function(t){return e.rightoffold(t,{threshold:0})},"left-of-fold":function(t){return!e.rightoffold(t,{threshold:0})}})}(jQuery,window,document),define("common/jquery.scrollLoading",function(){}),function(e){e.fn.fixtop=function(t){var n=e.extend({marginTop:0,zIndex:1e3,fixedWidth:"100%"},t),a=this.offset().top-n.marginTop,o=this,r=(o.height(),n.marginTop,e("<div/>").css({display:o.css("display"),width:o.outerWidth(!0),height:o.outerHeight(!0),float:o.css("float")}));return e(window).scroll(function(t){var i=a;e(this).scrollTop()>i&&"fixed"!=o.css("position")&&(o.after(r),o.css({position:"fixed",top:n.marginTop+"px","z-index":n.zIndex,width:n.fixedWidth}),void 0!==n.fixed&&n.fixed(o)),e(this).scrollTop()<i&&"fixed"==o.css("position")&&(r.remove(),o.css({position:"relative",top:"0px","z-index":n.zIndex}),void 0!==n.unfixed&&n.unfixed(o))}),this}}(jQuery),define("common/fixtop",function(){}),$.stringFormat=function(e,t){e=String(e);var n=Array.prototype.slice.call(arguments,1),a=Object.prototype.toString;return n.length?(n=1==n.length&&null!==t&&/\[object Array\]|\[object Object\]/.test(a.call(t))?t:n,e.replace(/#\{(.+?)\}/g,function(e,t){var o=n[t];return"[object Function]"==a.call(o)&&(o=o(t)),void 0===o?"":o})):e},$.trimN=function(e){return e.replace(/\n{2,}/gm,"\n")},$.fixedOldComment=function(e){return e?$.decodeHTML($.trimN(e.replace(/<[^>]+>/g,"\n"))):e},$.replaceTpl=function(e,t,n){var a=String(e),o=n||/#\{([^}]*)\}/gm,r=String.trim||function(e){return e.replace(/^\s+|\s+$/g,"")};return a.replace(o,function(e,n){return t[r(n)]})},$.strHTML=function(e,t){e=String(e);var n=Array.prototype.slice.call(arguments,1),a=Object.prototype.toString;return n.length?(n=1==n.length&&null!==t&&/\[object Array\]|\[object Object\]/.test(a.call(t))?t:n,e.replace(/#\{(.+?)\}/g,function(e,t){var o=n[t];return"[object Function]"==a.call(o)&&(o=o(t)),void 0===o?"":$.encodeHTML(o)})):e},$.showIframeImg=function(e,t){var n=$(e),a=n.height(),o=n.width(),r=$.stringFormat("<style>body{margin:0;padding:0}img{width:#{0}px;height:#{1}px;}</style>",o,a),i="frameimg"+Math.round(1e9*Math.random());window.betafang[i]="<head>"+r+'</head><body><img id="img-'+i+"\" src='"+t+"' /></body>",e.append('<iframe style="display:none" id="'+i+'" src="javascript:parent.betafang[\''+i+'\'];" frameBorder="0" scrolling="no" width="'+o+'" height="'+a+'"></iframe>')},$.loadScript=function(e){function t(){if(o)return!1;o=!0,r.onload=null,r.onerror=null,a.complete&&a.complete(),s.resolve(),i.removeChild(r)}function n(){if(o)return!1;o=!0,a.fail&&a.fail(),i.removeChild(r),s.reject()}var a={url:"",charset:"utf-8",complete:$.noop,fail:$.noop};if($.extend(a,e),!a.url)throw"url is requireed";var o=!1,r=document.createElement("script"),i=document.getElementsByTagName("head")[0],s=$.Deferred();return r.onload=t,r.onerror=n,r.onreadystatechange=function(e){"complete"==r.readyState&&t()},r.type="text/javascript",r.src=a.url,r.charset=a.charset,i.appendChild(r),s},$.TextAreaUtil=function(e){var t=document.selection;return{getCursorPosition:function(e){var n=0;if(t){e.focus();try{var a=null;a=t.createRange();var o=a.duplicate();o.moveToElementText(e),o.setEndPoint("EndToEnd",a),e.selectionStartIE=o.text.length-a.text.length,e.selectionEndIE=e.selectionEndIE+a.text.length,n=e.selectionStartIE}catch(t){n=e.value.length}}else(e.selectionStart||"0"==e.selectionStart)&&(n=e.selectionStart);return n},getSelectedText:function(t){return e.getSelection?function(e){return void 0!=e.selectionStart&&void 0!=e.selectionEnd?e.value.slice(e.selectionStart,e.selectionEnd):""}(t):document.selection.createRange().text}}}(window),$.browser=$.browser||{},$.browser.ie=/msie (\d+\.\d+)/i.test(navigator.userAgent)?document.documentMode||+RegExp.$1:void 0;var betafang=window.betafang||{};$(function(){/msie (\d+\.\d+)/i.test(navigator.userAgent)&&$("body").addClass("ie","ie"+(document.documentMode||+RegExp.$1)),$(".lj-lazy").lazyload(),$(".lazyload").scrollLoading(),$("#keyword-box,#keyword-box-01").closest("form").on("submit",function(){var e=$(this),t=e.attr("data-action")||e.attr("action"),n=e.find(".txt"),a=$.trim(n.val());if(a==n.attr("placeholder")&&(a=""),t+=encodeURIComponent(a),"_blank"!=e.attr("target"))return window.location.href=t,!1;e.attr("action",t)})}),define("common/base",function(){});var ajax=function(){var e={},t=function(){};return e.get=function(e,n,a,o){if(a=a||t,o=o||t,!e)return!1;$.getJSON(e,n,function(e){0===e.status?a(e.data):o(e)},function(e){o({status:500,statusInfo:"服务请求失败"})})},e.post=function(e,n,a,o){if(a=a||t,o=o||t,!e)return!1;$.ajax({type:"POST",url:e,data:n,success:function(e){0===e.status?a(e.data):o(e)},failure:function(e){o({status:500,statusInfo:"服务请求失败"})},dataType:"json"})},e}();define("common/ajax",function(){}),function(){function e(e,t){var n=document.getElementsByTagName("head")[0],a=document.createElement("script");a.type="text/javascript",a.src=e,t=t||function(){},a.onload=a.onreadystatechange=function(){this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState||(t(),a.onload=a.onreadystatechange=null,n&&a.parentNode&&n.removeChild(a))},n.insertBefore(a,n.firstChild)}function t(t,n,o){var r="cbk_"+Math.round(1e4*Math.random()),i=a+"?from="+n+"&to=4&x="+t.lng+"&y="+t.lat+"&callback=BMap.Convertor."+r;o=o||function(){},e(i),BMap.Convertor[r]=function(e){delete BMap.Convertor[r];var t=new BMap.Point(e.x,e.y);o(t)}}function n(t,n,o){var r=a+"?from="+n+"&to=4&mode=1",i=[],s=[];o=o||function(){};var c=function(){var t="cbk_"+Math.round(1e4*Math.random());e(r+"&x="+i.join(",")+"&y="+s.join(",")+"&callback=BMap.Convertor."+t),i=[],s=[],BMap.Convertor[t]=function(e){delete BMap.Convertor[t];var n=null,a=[];for(var r in e)if(n=e[r],0===n.error){var i=new BMap.Point(n.x,n.y);a[r]=i}else a[r]=null;o(a)}};for(var l in t)l%20==0&&0!==l&&c(),i.push(t[l].lng),s.push(t[l].lat),l==t.length-1&&c()}var a="http://api.map.baidu.com/ag/coord/convert";window.BMap=window.BMap||{},BMap.Convertor=$({}),BMap.Convertor.translate=t,BMap.Convertor.translateMore=n}();var LJFixed=function(e,t){function n(t){if(!o.isSupportPlaceHolder){var n=e(t),a=n.attr("placeholder");""===n.val()&&n.val(a).addClass("placeholder"),n.focus(function(){n.val()===n.attr("placeholder")&&n.val("").removeClass("placeholder")}).blur(function(){""===n.val()&&n.val(n.attr("placeholder")).addClass("placeholder")}).closest("form").submit(function(){n.val()===n.attr("placeholder")&&n.val("")})}}function a(){e("input[placeholder],textarea[placeholder]").each(function(){"password"!=e(this).attr("type")&&n(this)})}var o={isSupportPlaceHolder:"placeholder"in t.createElement("input")};e(function(){a()}),e.fixPlaceholder=n;var r={};return r.fixedPlaceHolder=n,r}($,document);define("common/fixed",function(){});var Pagination=function(require){function e(e,t,n){var a=[];if(n=n||6,t=t||1,e<=n)for(var o=0;o<e;o++)a.push(o+1);else{a.push(1),t>4&&a.push("");for(var r=Math.max(t-2,2),i=Math.min(t+2,e-1),o=r;o<=i;o++)a.push(o);t<e-3&&a.push(""),a.push(e)}return a}function t(e,t,n,a){function o(e){if(a){var t=a.replace(/\{page\}/g,e);return 1===e&&t.search("pg1")>-1&&(t=t.search("pg1/")>-1?t.replace(/pg1\//,""):t.replace(/pg1/,"")),t}return"javascript:;"}var r=[];if(n=n||1,e&&e.length){n>1&&t>6&&r.push('<a href="'+o(n-1)+'" data-page="'+(n-1)+'" >上一页</a>');for(var i=e.length,s=0;s<i;s++)r.push(e[s]?"<a "+(e[s]==n?'class="on"':"")+' href="'+o(e[s])+'" data-page="'+e[s]+'">'+e[s]+"</a>":"<span>...</span>");n<t&&t>6&&r.push('<a href="'+o(n+1)+'" data-page="'+(n+1)+'">下一页</a>')}return r.join("")}function n(n){function a(){s.template=s.dom.attr("page-url");var e=s.dom.attr("target-wrapper");e&&(s.targetWrapper=$(e));var t=s.dom.attr("page-data");t?(t=$.parseJSON(t),$.extend(s,t)):s.targetWrapper&&(s.curPage=1,s.totalPage=s.targetWrapper.children().length)}function o(){if(!(s.totalPage<=1)){var n=e(s.totalPage,s.curPage);n.length||s.dom.hide();var a=t(n,s.totalPage,s.curPage,s.template);if(s.dom.html(a),s.targetWrapper){var o=s.targetWrapper.children();o.hide(),o.eq(s.curPage-1).show(),s.targetWrapper.find(".lj-lazy").lazyload()}}}function r(){s.targetWrapper&&c.on("showPage",function(e,t){s.curPage=t,o()})}function i(){s.dom.delegate("[data-page]","click",function(){var e=$(this).attr("data-page");c.trigger("showPage",parseInt(e))})}if(n){var s={dom:$(n),template:"",targetWrapper:"",totalPage:0,curPage:0},c=$({});return function(){a(),i(),r(),o()}(),c}}return $(function(){$("[comp-module='page']").each(function(){n($(this))})}),n}();define("common/pagination",function(){}),$(document).ready(function(e){b(),ent(),$("body").on("click","#gotop",function(){$("html , body").animate({scrollTop:0},1e3)})}),$(window).scroll(function(e){b()}),$("body").on("click","#back-top .mycart .popup",function(e){e.stopPropagation()}),define("common/backtop",function(){}),define("common/env",function(require){function e(e){var t="";return e.scheme&&(t+=e.scheme+"://"),e.host&&(t+=e.host),e.port&&(t+=":"+e.port),e.path&&(t+="/"+e.path),e.query&&(t+="?"+e.query),e.hash&&(t+="#"+e.hash),t}var t={host:"",scheme:"",port:"",env:"online"},n={};return n.getEnv=function(){return t.env},n.fixedHost=function(e){if(!e)return t.host;var n=e.substring(0,e.indexOf("."));switch(t.env){case"dev":case"test":if(0!==n.indexOf(t.env))return t.env+e}return e},n.fixedUrl=function(a){var o=$.parseURL(a);return o.host?(o.host=n.fixedHost(o.host),o.port=t.port,o.scheme||(o.scheme=t.scheme)):(o.host=t.host,o.scheme=t.scheme,o.port=t.port),e(o)},n.isSameDomain=function(e){return $.parseURL(e).host==t.host},function(){var e=$.parseURL(location.href);t.host=e.host,t.scheme=e.scheme,t.port=e.port;var n=t.host.substring(0,t.host.indexOf("."));0===n.indexOf("dev")?t.env="dev":0===n.indexOf("test")&&(t.env="test")}(),n}),window.LJMessenger=function(){function e(e,t){var n="";if(arguments.length<2?n="target error - target and name are both requied":"object"!=typeof e?n="target error - target itself must be window object":"string"!=typeof t&&(n="target error - target name must be string type"),n)throw new Error(n);this.target=e,this.name=t}function t(e,t){this.targets={},this.name=e,this.listenFunc=[],n=t||n,"string"!=typeof n&&(n=n.toString()),this.initListen()}var n="[LIANJIA_Messenger_CROSS]",a="postMessage"in window;return e.prototype.send=a?function(e){this.target.postMessage(n+e,"*")}:function(e){var t=window.navigator[n+this.name];if("function"!=typeof t)throw new Error("target callback function is not defined");t(n+e,window)},t.prototype.addTarget=function(t,n){var a=new e(t,n);this.targets[n]=a},t.prototype.initListen=function(){var e=this,t=function(t){if("object"==typeof t&&t.data&&(t=t.data),t&&"string"==typeof t&&t.indexOf(n)>=0){t=t.slice(n.length);for(var a=0;a<e.listenFunc.length;a++)e.listenFunc[a](t)}};a?"addEventListener"in document?window.addEventListener("message",t,!1):"attachEvent"in document&&window.attachEvent("onmessage",t):window.navigator[n+this.name]=t},t.prototype.listen=function(e){this.listenFunc.push(e)},t.prototype.clear=function(){this.listenFunc=[]},t.prototype.send=function(e){var t,n=this.targets;for(t in n)n.hasOwnProperty(t)&&n[t].send(e)},t}(),"object"!=typeof JSON&&(JSON={}),function(){"use strict";function f(e){return e<10?"0"+e:e}function quote(e){return escapable.lastIndex=0,escapable.test(e)?'"'+e.replace(escapable,function(e){var t=meta[e];return"string"==typeof t?t:"\\u"+("0000"+e.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+e+'"'}function str(e,t){var n,a,o,r,i,s=gap,c=t[e];switch(c&&"object"==typeof c&&"function"==typeof c.toJSON&&(c=c.toJSON(e)),"function"==typeof rep&&(c=rep.call(t,e,c)),typeof c){case"string":return quote(c);case"number":return isFinite(c)?String(c):"null";case"boolean":case"null":return String(c);case"object":if(!c)return"null";if(gap+=indent,i=[],"[object Array]"===Object.prototype.toString.apply(c)){for(r=c.length,n=0;n<r;n+=1)i[n]=str(n,c)||"null";return o=0===i.length?"[]":gap?"[\n"+gap+i.join(",\n"+gap)+"\n"+s+"]":"["+i.join(",")+"]",gap=s,o}if(rep&&"object"==typeof rep)for(r=rep.length,n=0;n<r;n+=1)"string"==typeof rep[n]&&(a=rep[n],(o=str(a,c))&&i.push(quote(a)+(gap?": ":":")+o));else for(a in c)Object.prototype.hasOwnProperty.call(c,a)&&(o=str(a,c))&&i.push(quote(a)+(gap?": ":":")+o);return o=0===i.length?"{}":gap?"{\n"+gap+i.join(",\n"+gap)+"\n"+s+"}":"{"+i.join(",")+"}",gap=s,o}}"function"!=typeof Date.prototype.toJSON&&(Date.prototype.toJSON=function(){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+f(this.getUTCMonth()+1)+"-"+f(this.getUTCDate())+"T"+f(this.getUTCHours())+":"+f(this.getUTCMinutes())+":"+f(this.getUTCSeconds())+"Z":null},String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(){return this.valueOf()});var cx,escapable,gap,indent,meta,rep;"function"!=typeof JSON.stringify&&(escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,meta={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},JSON.stringify=function(e,t,n){var a;if(gap="",indent="","number"==typeof n)for(a=0;a<n;a+=1)indent+=" ";else"string"==typeof n&&(indent=n);if(rep=t,t&&"function"!=typeof t&&("object"!=typeof t||"number"!=typeof t.length))throw new Error("JSON.stringify");return str("",{"":e})}),"function"!=typeof JSON.parse&&(cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,JSON.parse=function(text,reviver){function walk(e,t){var n,a,o=e[t];if(o&&"object"==typeof o)for(n in o)Object.prototype.hasOwnProperty.call(o,n)&&(a=walk(o,n),void 0!==a?o[n]=a:delete o[n]);return reviver.call(e,t,o)}var j;if(text=String(text),cx.lastIndex=0,cx.test(text)&&(text=text.replace(cx,function(e){return"\\u"+("0000"+e.charCodeAt(0).toString(16)).slice(-4)})),/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"")))return j=eval("("+text+")"),"function"==typeof reviver?walk({"":j},""):j;throw new SyntaxError("JSON.parse")})}(),define("xd/crossRequest",function(require){function e(e,t){var n=document.createElement("iframe");return n.id=e,n.name=e,n.src=t,n.style.cssText="display:none;width:0px;height:0px;",n.width=0,n.height=0,n.title="empty",document.body.appendChild(n),n}var t=new LJMessenger("LIANJIA_CROSS_MESSAGE","LIANJIA-CROSS");t.listen(function(e){e=JSON.parse(e);var n=e.name;t.targets[n]&&("state"==e.type?(t.targets[n].readyState="ready",t.targets[n].dealReady()):t.targets[n].deal(e.data,e.success))});var n={},a=function(e,t){var n=this;n.domain=e,t=t||$.parseURL(e).host.replace(/\./g,"-"),n.name=t,n.init()};return $.extend(a.prototype,{init:function(){var n=this,a=n.domain+"/xd/api/?name="+n.name,o=e(n.name,a);n.iframe=o.contentWindow,t.addTarget(n.iframe,n.name),n.reqArray=[],t.targets[n.name].deal=function(e,a){t.targets[n.name].isRequest=!1;var o=n.reqArray.shift(),r=!1;try{r=e}catch(e){}a?o.defer.resolve(r):o.defer.reject(r),n.next()},t.targets[n.name].dealReady=function(){n.next()}},next:function(){var e=this;if(t.targets[e.name].readyState&&e.reqArray.length&&!t.targets[e.name].isRequest){t.targets[e.name].isRequest=!0;var n=e.reqArray[0],a={type:"request",data:n.request},o=JSON.stringify(a);t.targets[e.name].send(o)}},request:function(e){var t=this,n=$.Deferred();return t.reqArray.push({defer:n,request:e}),t.next(),n}}),function(e,t){return n[e]?n[e]:n[e]=new a(e,t)}}),define("xd/Trans",function(require){var e=$.EventEmitter,t=require("xd/crossRequest"),n=require("common/env");return e.extend({initialize:function(e){var a={url:"",type:"get",dataType:"json",args:{}};$.extend(a,e),a.url=n.fixedUrl(a.url),a.method=a.type;var o=this;o.opt=a;var r=n.fixedUrl($.parseURL(a.url).host);n.isSameDomain(r)?o.isSame=!0:o.crossRequest=t(r)},request:function(e){var t=this,n=t.opt;return $.extend(n.args,e),n.data=n.args,t.isSame?$.ajax(n):this.crossRequest.request(n)}})}),define("common/login",function(){function e(){"test"==$.env.getEnv()?lianjiaCasManager.config({setLoginUrl:$.env.fixedUrl("http://login.lianjia.com/login/getUserInfo/"),service:location.href,getFirstTicket:"http://passport.off.lianjia.com/cas/prelogin/loginTicket",loginUrl:"http://passport.off.lianjia.com/cas/login"}):lianjiaCasManager.config({setLoginUrl:$.env.fixedUrl("https://login.lianjia.com/login/getUserInfo/"),service:location.href}),l&&l()}function t(e){lianjiaCasManager?e():l=e}function n(){if(!c){c=!0;var t=document.createElement("script");"test"==$.env.getEnv()?t.src="https://passport.off.lianjia.com/cas/js/passport.js":t.src="dev"===s?"http://passport.lianjia.com:8088/cas/js/passport.js":"https://passport.lianjia.com/cas/js/passport.js",t.type="text/javascript",t.charset="utf-8",t.onload=e,document.getElementsByTagName("head")[0].appendChild(t)}}function a(){return className=$(this).attr("class"),$(".overlay_bg").fadeIn(300),window.$ULOG.send("10179",{action:{xinfangpc_click:"10117_1"}}),$(".panel_login").removeAttr("class").addClass("panel_login animated "+className).fadeIn(),$("body").css({overflow:"hidden"}),n(),!1}function o(){new $.Trans({url:$.env.fixedUrl("//login.lianjia.com/login/getUserInfo/"),type:"jsonp"}).request().done(function(e){e&&e.username&&(e.code=1),$.listener.trigger("userInfo",e)}).fail(function(e){e&&$.listener.trigger("userInfo",e.data)})}function r(e){var t=ljConf.city_id;if($("#userNews").length>0){new $.Trans({url:$.env.fixedUrl("https://ajax.lianjia.com/ajax/user/favorite/getnotifynum/"),type:"jsonp",args:{cityId:t}}).request().done(function(t){t=t&&t.data,t&&t.unread_num&&e.append('<span class="login_bubble_tip">'+t.unread_num+"</span>"),$("#userNews").html($.template($("#News").html()).render({data:t}))})}}var i,s=require("common/env"),c=(require("xd/Trans"),!1),l=!1,s=$.env.getEnv();return i="test"===s?"https://passport.off.lianjia.com/cas/captcha.htm":"dev"===s?"http://passport.lianjia.com:8088/cas/captcha.htm":"https://passport.lianjia.com/cas/captcha.htm",$(document.body).ready(function(){function e(){$(".panel_login").fadeOut(),$(".overlay_bg").fadeOut(),$("body").css({overflow:""}),$("#dialog").removeClass("bounceIn")}function n(e){e=e||"用户名或者密码错误",s.find("dd").html(e),s.show()}function o(){s.hide()}var r=$(".verImg"),s=$("#con_login_user").find(".show-error");r.on("click",function(){var e=+new Date;$(this).attr("src",i+"?t="+e)}),$(".typeUserInfo").delegate(".btn-login","click",a),$(".overlay_bg,.claseDialogBtn").click(function(){e()}),$("#con_login_user").delegate("input","keyup",function(e){13==e.keyCode&&$(".login-user-btn").click()}),$(".login-user-btn").on("click",function(e){var a=$("#con_login_user").find(".item"),i=($("#con_login_agent").find(".item"),a.find(".users").val()),s=a.find(".password").val();if(!i)return void a.find(".users").focus();if(!s)return void a.find(".password").focus();var c=$("#con_login_user").find('[name="remember"]').get(0),l={username:i,password:s,code:""};if(c&&c.checked&&(l.remember=1),"none"!=$(".checkVerimg").css("display")){var u=$(".ver-img").val();if(!/^\d{4}$/.test(u))return n("验证码格式错误"),void $(".ver-img").focus();l.code=u}o(),t(function(){lianjiaCasManager.login(l,function(e){-1==e.code?n():($.listener.trigger("loginActSuccess"),window.NOTAUTOJUMP||location.reload())},function(e){1===e.code?n():2===e.code?(n("请输入验证码"),$(".checkVerimg").show(),r.trigger("click")):3===e.code&&(n("验证码输入错误"),$(".checkVerimg").show(),r.trigger("click"))})})})}),{init:function(){function e(e){var t=$(".typeUserInfo");e&&e.username&&(e.username=$.encodeHTML($.getLimitString(e.username,20,".."))),t.each(function(){var t=$(this),n=t.find(".template").html();if(n){n=$.template(n);var a=$.trim(n.render({data:e}));t.find(".typeShowUser").html(a)}})}$.listener.on("userInfo",function(t){e(t),t.username&&r($(".typeUserInfo").find(".typeShowUser a").eq(0))}),o()},openLoginDialog:a}}),define("common/scrollCaller",function(require){function e(){for(var e=i.scrollTop(),t=s.length-1;t>=0;t--)try{s[t].call(i,e)}catch(e){console.error&&console.error(e.stack)}}function t(){r&&clearTimeout(r),r=setTimeout(function(){e()},30)}function n(e){e?i.scroll(t):i.unbind("scroll",t)}function a(e){s.length||n(!0),s.push(e)}function o(e){var t=$.inArray(e,s);t>=0&&s.splice(t,1),s.length||n(!1)}var r=!1,i=$(window),s=[];return function(e){if(!e)throw"fun is required";return a(e),{destroy:function(){o(e)}}}}),define("common/lazyExecute",function(require){function e(e){for(var n,a=i.width(),o=window.innerHeight,s=0,c=r.length;s<c;s++)n=r[s],t(n,e,a,o)&&!n.always&&--n.times<=0&&(r.splice(s,1),c--,s--)}function t(e,t,n,a){var o=$(e.el);t||(t=document.documentElement.scrollTop||document.body.scrollTop),n||(n=i.width()),a||(a=window.innerHeight);var r=o.offset(),s=r.top-e.marginTop,c=s+o.height()+e.marginBottom,l=t,u=t+a;return!(c<l||s>u)&&(e.callback&&e.callback(),!0)}function n(){a=o(function(t){e(t)})}var a,o=require("common/scrollCaller"),r=[],i=$(window);return function(e){var o={el:"",marginTop:0,marginBottom:0,times:1,always:!1,callback:$.noop};if($.extend(o,e),o.el){if(!t(o)||o.always)return r.push(o),a||n(),{destroy:function(){var e=r.indexOf(o);e>=0&&r.splice(e,1)},pause:function(){var e=r.indexOf(o);e>=0&&r.splice(e,1)},resume:function(){r.indexOf(o)<0&&r.push(o)}}}}}),define("common/footer",function(require){function e(){var e=$(".lianjia-link-box .tab");$(".link-list div").eq(0).show(),$(".link-footer div").eq(0).show(),e.delegate("span","mouseover",function(e){var t=$(e.currentTarget),n=t.index(),a=t.closest(".tab").next(".link-list");t.addClass("hover").siblings("span").removeClass("hover"),a.find("div").eq(n).show().siblings("div").hide()})}function t(){$(document.body).on("mousedown",function(e){$(e.target).closest(".hot-sug,.search-txt ul,.del").length||($(".hot-sug").hide(),p.css({height:"35px",overflow:"hidden",border:"0px",background:"none",left:"0px",top:"0px",display:"none"}))}),$("#keyword-box:text").click(function(e){""==$(this).val()?$(e.target).next("div").show():($("#keyword-box").select(),$(e.target).next("div").show())}),$("#keyword-box").keydown(function(e){$(e.target).next("div").hide()})}function n(){var e=$(".frauds-list .tab");$(".link-list div").eq(0).show(),e.delegate("span","click",function(e){var t=$(e.currentTarget),n=t.index(),a=t.closest(".tab").next(".link-list");t.addClass("hover").siblings("span").removeClass("hover"),a.find("div").eq(n).show().siblings("div").hide()})}function a(){var e=$(".hot-sug ul");e.eq(0).show(),g.click(function(){p.css({height:"auto",overflow:"auto",background:"#fff",border:"1px solid #ccc",left:"-1px",top:"-1px",display:"block"})}),"ershoufang"==g.attr("actdata")&&$(".savesearch").show(),p.delegate("li label","click",function(t){var n=$(t.currentTarget),a=n.parent("li").index(),o=n.attr("actdata");o=o.split("=")[1],g.text(n.text()),g.attr("actdata",o),p.css({display:"none"});var r=$.queryToJson(n.attr("actData"));r&&defaultSuggest.suggestView.model.trans.setArgs(r);var i=$(this).attr("formact"),s=n.attr("tra"),c=n.attr("tips");n.closest(".search-txt").find("form").attr({action:i,target:s}),n.closest(".search-txt").find("form").attr({"data-action":i}),n.closest(".search-txt").find(".autoSuggest").attr("placeholder",c),e.eq(a).show().siblings("ul").hide();var l=n.closest(".search-txt").find(".autoSuggest");"placeholder"in document.createElement("input")?l.val(""):l.val(c),"ershoufang"==o?$(".savesearch").show():$(".savesearch").hide(),u()})}function o(){var e=$("#back-top");if(e.hasClass("fix-right-v2")||e.hasClass("fix-right-v3")){var t="";e.on("mouseenter","li",function(){var e=$(this).find(".popup").eq(0);t=this.className,e.show(),e.stop().animate({opacity:"1",right:"38px"},200)}).on("mouseleave","li",function(){var e=$(this).find(".popup").eq(0),n=this.className;t="",e.stop().animate({opacity:"0",right:"48px"},200,function(){n!=t&&e.hide()})})}else{var n=$("#back-top .tips li,#gotop");n.mouseenter(function(){$(this).find("span").css({opacity:"1"}),$(this).css({overflow:"inherit",width:"auto"})}),n.mouseleave(function(e){$(this).find("span").css({opacity:"0"}),$(this).css({overflow:"hidden",width:"37px"})})}}function r(){var e=$(".feedback-box");$("#tel").val();e.delegate("#sub","click",function(){var t=($("#sub"),$("#tips")),n=($("#count"),$("#tel").val()),a=$("#count").val();a=$.trim(a);var o=$("#count").attr("placeholder");if(""==a||a==o)return $(".erro-tips").show(),!1;var r={contact:n,content:a};$.ajax({type:"POST",url:"//www.lianjia.com/site/accuse/",dataType:"json",data:r,xhrFields:{withCredentials:!0},crossDomain:!0,success:function(n){0==n.status?(t.html("反馈成功非常感谢您的反馈！"),e.delay(2e3).fadeOut().removeClass("bounceIn"),v.delay(2e3).fadeOut()):t.html("反馈失败请重新填写！")}})}),e.delegate(".tab span","click",function(){$(".complain .tab-box").eq($(this).index()).show().siblings().hide(),$(this).addClass("check").siblings().removeClass("check")});e.delegate(".ent","click",function(){$("#tousu .btn-more").attr("href","https://"+window.location.host.split(".").slice(-3).join(".")+"/topic/tousu/");var e=ljConf.pageConfig.ajaxroot+"ajax/tousu/GetCityTousuBrief";$.ajax({url:e,dataType:"jsonp",data:{city_id:ljConf.city_id}}).done(function(e){var t=$(".feedback-box #list");t.html("");var e=e.data;if(e.data&&e.data.length<=0&&$("#tousu").hide(),e.data&&0==e.code){for(var n=e.data,a="",o=0;o<n.length;o++){var r;r=1==n[o].issue_status?"未处理":2==n[o].issue_status?"处理中":"已完成",a+=$.replaceTpl('<li><span class="time">#{issue_time}</span><span class="name">#{customer_name}</span><span class="phone">#{customer_phone}</span><span class="type">#{trade_type}</span><span class="finish">#{issue_status}</span></li>',{issue_time:n[o].issue_time,customer_name:n[o].customer_name,customer_phone:n[o].customer_phone,trade_type:n[o].trade_type,issue_status:r})}t.append(a)}})})}function i(){var e=($("#feedback"),$(".feedback-box"));e.fadeOut().removeClass("bounceIn"),e.html(m),v.fadeOut()}function s(){var e=$("#feedback"),t=$(".feedback-box");e.click(function(e){t.show(),t.addClass("bounceIn"),v.fadeIn(300),t.html(m)}),v.click(function(e){i()}),t.delegate(".closebok","click",function(e){i()})}function c(){$("#back-top").on("click","li",function(){var e=$(this).find("a").attr("data-url");if(e)if(window.loginData&&1==window.loginData.code)window.open(e);else{var t=$(".btn-login");t.length>0?t.trigger("click"):alert("请登录后使用，谢谢！")}})}function l(e,t){searchHis=localStorage.getItem(e),searchHis=JSON.parse(searchHis),searchHis?($.each(searchHis,function(e,n){n&&n.name==t.name&&searchHis.splice(e,1)}),searchHis.unshift(t),saveQuery=searchHis.slice(0,10)):saveQuery=[t],localStorage.setItem(e,JSON.stringify(saveQuery))}function u(){var e=$(".btn");if($(".search-tab .check").length>0){var t=$(".search-tab .check").attr("actdata"),n=e.attr("daty-id");menu=t+n,$("#keyword-box").on("formSelect",function(e,t){$(this).val($(t).find(".hot-title i").text()),url=$(t).attr("actdata"),url=url.substring(url.indexOf("&url=")+5,url.lastIndexOf("&title")),url=unescape(url),$(this).attr("url",url)}),e.click(function(e){if($("#keyword-box").attr("url")){var t=$("#keyword-box").val(),n=$("#keyword-box").attr("url");query={name:t,url:n},l(menu,query)}else{var a=$(".search-txt form").attr("data-action"),t=$("#keyword-box").val(),n="https://"+window.location.host+a+t;""!=t&&(query={name:t,url:n},l(menu,query))}});$(".hot-sug").delegate("li a","click",function(e){var t=$(e.currentTarget);name=t.text(),
url=t.attr("href"),query={name:name,url:url},l(menu,query)});$("#suggest-cont").delegate("ul li","click",function(e){var t=$(e.currentTarget);name=t.find(".hot-title i").text(),url=t.attr("actdata"),url=url.substring(url.indexOf("&url=")+5,url.lastIndexOf("&title")),url=unescape(url),query={name:name,url:url},l(menu,query)});var a=localStorage.getItem(menu);if(null!=(a=JSON.parse(a))){$("#keyword-box").val(a[0].name);var o=$(".hot-sug ul#"+t+" .list"),r=$(".hot-sug ul#"+t+" .hot-name"),i=o.html();r.text("搜索历史"),o.html(""),$.each(a,function(e,t){var n='<li><a href="'+t.url+'" data-log_index="'+(e+1)+'" data-log_value="'+t.name+'">'+$.encodeHTML(t.name)+"</a></li>";o.append(n)});var s=$("#"+t+" .del");s.show(),s.click(function(e){localStorage.removeItem(menu),o.html(""),o.append(i),r.text("热门搜索"),s.hide(),texval})}}}function f(){var e=(g.attr("actdata"),$(".savesearch"));e.length&&h({el:e,callback:function(){var e=ljConf.city_id,t=new $.ListView({el:".savesearch",template:"#savesearch",url:$.env.fixedUrl("http://ajax.lianjia.com/ajax/user/favorite/getSearchNotifyNum"),type:"jsonp",args:{cityId:e}});t.showloading=function(){},t.init()}});var t=$(".savesearch");t.find(".s-show");t.delegate(".more","click",function(e){var t=$(e.currentTarget);t.parent("ul").find(".list").css({height:"auto"}),t.hide()}),t.delegate(".s-show","click",function(e){var t=$(e.currentTarget);t.next(".cunn").toggle(),"none"==t.next(".cunn").css("display")?t.find("label").removeClass("down"):t.find("label").addClass("down"),$(".sug-tips ul").hide()}),$(".savesearch .s-show").click(function(){}),$(document.body).on("mousedown",function(e){$(e.target).closest(".savesearch").length||(t.find(".cunn").hide(),t.find("label").removeClass("down"))})}function d(){var e=$('[data-role="huodong-btn"]'),t=$('[data-role="huodong-mask"]'),n=$('[data-role="huodong-layer"]');e.length>0&&(e.click(function(){t.fadeIn(500),n.addClass("bounceIn").show()}),n.click(function(e){var a=$(e.target);(0==a.closest('[data-role="huodong-wrap"]').length||a.closest(".close").length>0)&&(t.fadeOut(500),n.removeClass("bounceIn").fadeOut())}))}var h=require("common/lazyExecute"),p=$(".search-tab .tabs"),g=$(".search-tab .check"),m=$(".feedback-box").html(),v=$(".overlay_bg");return{init:function(i){u(),t(),e(),a(),c(),d(),s(),r(),o(),f(),n()}}}),function(){$.listener=new $.EventEmitter(!0),$.env=require("common/env"),$(document.body).ready(function(){function e(){setTimeout(function(){$(".LOGVIEW:above-the-fold").each(function(){var e=$(this);LjUserTrack.send({typ:"show"},e.get(0)),e.removeClass("LOGVIEW")}),e()},100)}require("common/login").init();var t=$("#only");t.attr("data-city")&&(t.attr("data-city").indexOf("su")>=0||t.attr("data-city").indexOf("jn")>=0)&&($(".laisuzhou").addClass("laisuzhou-class"),$(document.body).delegate(".laisuzhou","click",function(e){return!1})),require("common/footer").init(),e(),function(){var e=$(document.body);e.on("mousedown",".LOGCLICK",function(){LjUserTrack.send({typ:""},$(this).get(0))}),$.listener.on("userInfo",function(t){e.on("mousedown",".LOGKEFU",function(){LjUserTrack.send({ljweb_mod:"imclick",ljweb_bl:1==t.code?"1":"0",ljweb_el:"BB120",ljweb_group:1})})})}()})}();