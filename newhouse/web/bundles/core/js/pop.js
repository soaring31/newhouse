define(function(require,exports,module){
    var pub = require('core')
        //,$ = require("jquery");
    ;(function(fn){
        module.exports = fn();
    })(function(){
        "use strict";
        var $win    = $(window)
            ,$doc   = $(document)
            ,$body  = $("body")
            // Window size
            ,windowHeight = $win.height()
            ,windowWidth = $win.width();
    /*
    * 弹出操作层,支持iframe，ajax，dialog三种模式（不处理ie6 select遮罩问题）
    * @param {String} str 内容 支持html
    * @param {Object} arg 参数
    * @example
                id          :"",
                css         :"",
                title       :"",
                url         :"",                //ajax和iframe用的url，dialog下则无效
                showHeader  :true,              //显隐标题
                template    :"",                //内容
                type        :"dialog",          //dialog，ajax，iframe
                width       :"420",
                height      :"300",
                draggable   :false,             //拖拽
                draggableOf :"",                //拖拽对象
                mask        :true,              //遮罩开关
                zIndex      :10000,
                position    :"center",          //居中或{left:x,top:y}设置位置
                reszie      :true,
                onclose     :null,
                onopen      :null,
                onshow      :null,
                onhide      :null,
                speed:		'fast', 		//fast/slow/normal
        		opacity:	0.80, 					//Value between 0 and 1

        var mypop = pop(html.join(""), {
                id          :"dialog-pop",
                title       :"我的弹出层",
                draggable   :true,
                width       :400,
                onopen      :function(p){}
            });
        mypop.open()
    */
        function F(content,cfg){
            if (!(this instanceof F)) { return new F(content,cfg) }
            this.SET        = $.extend({},F.SET,cfg);
            this.INIT       = -1;
            this.HTML       = null;
            this.MASK       = null;
            this.BOXID      = '';
            this.ISHIDE     = true;
            this.CONETNT    = content;
            this.BOXCLICK   = null;
            this.DOCCLICK   = null;
            this.ISCLICKBOX = null;
            this.init();
        };
        /* Static */
        F.ie6 = !-[1,]&&!window.XMLHttpRequest;
        F.SET = {
            id:"",              css:"",
            title:"",           url : "",
            data: {},
            template:"",        showHeader:true,
            type:"dialog",      width:"420",
            height:null,        draggable:false,        //拖拽
            mask:true,          zIndex:10000,
            cache:true,           //是否缓存
            position:"center",      //居中或{left:x,top:y}设置位置
            reszie:true,        onclose:null,
            onopen:null,        onshow:null,
            onhide:null,        oncancel:null,
            onsave:null,		dom:null,	//插入dom的window对象，常用于iframe            
            speed: 'fast', 					//fast/slow/normal
    		opacity: 0.80 					//Value between 0 and 1
        };
        F.template = function(isShowHead){
            var html = [],header = "";
                html.push('<div class="wk-pop">');
                if(isShowHead){
                    header ='<div class="wk-pop-header">'+
                            '   <a class="wk-pop-close" title="关闭" href="javascript:;">X</a>'+
                            '   <a class="wk-pop-title"></a>'+
                            '</div>'
                    html.push(header);
                }
                html.push(' <div class="wk-pop-body"></div>');
                html.push('</div>');
            return html.join('');
        };
        F.ajax = {};
        F.ajax.setContent = function(){
            var self    = this
                ,set    = self.SET
                ,box    = this.BOX
                ,headH  = box.find(".wk-pop-header").outerHeight(true)
                ,body   = box.find(".wk-pop-body");
	            pub.generalFunction.ajaxSubmit({
	                url: set.url,
                    data: set.data,
	                automatic:false,
	                type:"GET",
	                dataType:'html',
	                beforeSend : function(){
	                	body.addClass("wk-pop-loading");
	                },
	                complete : function(){
	                	body.removeClass("wk-pop-loading");
                        if(typeof set.onajaxcom === "function"){
                            set.onajaxcom(box);
                        }
	                },
	                success:function(html){
	                    body.html(html);
	                    if(set.height !== null){
                            var bodyH = set.height - headH;
                            // console.log(body.height(), bodyH);
	                        if(body.height() > bodyH){
	                            body.css({"overflow-y":"auto","height":bodyH});
	                        }else{
	                            box.height(set.height);
	                        }
                            body.height(bodyH);
	                    }
	                    if(!F.ie6)self.setPosition();
	                    self.setEvent(true);
	                },
	                error:function(e)
	                {
	                	body.html(e.responseText);
	                }
	            });
        };
        F.iframe = {};
        F.iframe.setContent = function(){
            var set     = this.SET
                ,self   = this
                ,headH  = this.BOX.find(".wk-pop-header").outerHeight()
                ,bodyH  = (set.height - headH)
                ,body   = this.BOX.find(".wk-pop-body")
                ,ifr    = $('<iframe class="wk-pop-iframe" width="100%" scrolling="no" height="'+ (bodyH-3) +'" frameborder="0" src="'+set.url+'"></iframe>');
                //(bodyH-3) is a hack
            	body.height(bodyH).addClass("wk-pop-loading");
            ifr.load(function(){
                var ifrHeight = $(this).contents().height();
                if(ifrHeight > bodyH){
                    body.css({"height":bodyH,"overflow-y":"auto"});
                }
                body.removeClass("wk-pop-loading");
            });
            body.html(ifr);
        };
        F.dialog = {};
        F.dialog.setContent = function(){
            var body = this.BOX.find(".wk-pop-body");
                body.html(this.CONETNT);
            if(this.SET.height !== null){
                var hH = this.BOX.find(".wk-pop-header").outerHeight(true);
                var nH = this.SET.height - hH;
                if(body.height() > nH){
                    body.css({overflow:"auto",height: nH});
                }
            }
            this.setEvent(true);
        };
        /* Static end*/
    var P = F.prototype;
        /**
         * 配置的处理，一般内部使用
         */
        P.setConfig = function(){
            var set     = this.SET
                ,self   = this
                ,boxid  = set.id || "wk-pop-"+Math.floor(Math.random()*100+1)
                //父页面的坑
                ,old    = $doc.find("#"+ boxid)
                ,htm    = set.template || F.template(set.showHeader)
                ,box    = $(htm)
            //向父页面插入
            if(set.dom){
                $win = $(set.dom.window);
                $doc = $(set.dom.document);
                $body = $(set.dom.document.body);
            }
            if(old.length>0){
                old.find("iframe").each(function(){
                    this.contentWindow.document.write('');
                    this.contentWindow.close();
                });
                old.remove();
            }
            old=null;

            box.css({
                visibility      : "hidden",
                position        : "absoulte",
                width           : set.width,
                height          : set.height,
                zIndex          : set.zIndex
            });
            box.attr("id",boxid);
            if(set.css){ box.addClass(set.css)}
            if(set.showHeader && set.title){
                box.find(".wk-pop-title").html(set.title);
            }

            box.appendTo($body);

            this.BOX        = box;
            this.HTML       = htm;
            this.BOXID      = boxid;
            this.BOXCLICK   = function(){ self.ISCLICKBOX = true;}
            this.DOCCLICK   = function(e){
                if(e.button!=0) return true;
                if(self.ISCLICKBOX === false){
                    self.on("clickout",[self]);
                }
                self.ISCLICKBOX = true;
            };
        };

        /**
         * 设置box的启用遮罩
         */
        P.setMask = function(isOpen){
            var set = this.SET;
            if(isOpen === "hide"){
                this.MASK && this.MASK.remove();
                return;
            }
            if(set.mask){
                if(!this.MASK) this.MASK = pub.mask('',{dom:set.dom,zIndex:set.zIndex-1,click:'',className:"wk-pop-mask"});
                if(isOpen === "show") this.MASK.show();
            }
        };

        /**
         * 设置box的绑定的事件，如.wk-pop-close,.wk-pop-cancel,.wk-pop-ok
         */
        P.setEvent = function(isReset){
            var set     = this.SET
                ,box    = this.BOX
                ,self   = this;
            box.find(".wk-pop-close").unbind().bind("click",function(){
                self.close();
            });
            box.find(".wk-pop-cancel").unbind("click").click(function(){
                self.cancel();
            });
            box.find(".wk-pop-ok,.wk-pop-save").unbind("click").click(function(){
                self.save();
            });

            if(!isReset || set.position !=="center"){
                $win.resize(function(){
                    self.setPosition(self.getPosition());
                });
                if(set.draggable){
                    this.setDraggable();
                }
                if(set.onclickout){
                    this.BOX.click(this.BOXCLICK,false)
                    $doc.click(this.DOCCLICK);
                }
            }
        };
        /**
         * 设置pop的托拽
         */
        P.setDraggable = function(){
            var set     = this.SET;
            var $box    = this.BOX;
            var $header = set.draggableOf ? $box.find(set.draggableOf) : $box.find(".wk-pop-header");
            var win     = {w:$win.width(),h:$win.height()}
            if($header.length<1) return;
            $header.css("cursor","move").mousedown(function(e){
                // 按下鼠标后再去取弹窗的大小，防止弹窗大小动态改变
                var box     = {w:$box.outerWidth(),h:$box.outerHeight()}
                var m       = {x:e.clientX ,y:e.clientY}
                    ,css    = {left : parseInt($box.css("left"),10),top : parseInt($box.css("top"),10)}
                    ,up     = function(ev){
                        if (this.releaseCapture){
                            this.releaseCapture();
                        }
                        $doc.unbind('mousemove', move);
                        $doc.unbind('mouseup', up);
                    }
                    ,move   = function(ev){
                        if (window.getSelection) {
                            window.getSelection().removeAllRanges();
                        } else {
                            document.selection.empty();
                        }
                        var offset = {
                            left : function(){
                                if( (css.left + box.w + (ev.clientX  - m.x)) >= win.w){
                                    return win.w - box.w;
                                }
                                return Math.max(css.left + ev.clientX - m.x,0)
                            }()
                            ,top : Math.max(css.top + ev.clientY - m.y,0)
                        };
                        if(typeof set.ondraggable === "function"){
                            set.ondraggable(offset,$box,win);
                        }
                        if(parseInt(set.draggable,10)!==1){
                            setTimeout(function(){
                                $box.css(offset);
                            },10*parseInt(set.draggable,10));
                        }else{
                            $box.css(offset);
                        }
                    }
                $doc.mousemove(move).mouseup(up);
            });
        };
        /**
         * 设置pop的坐标，可以传递一个css对象，并执行positon.callback回调
         */
        P.setPosition = function(css){
            var set = this.SET;
                css = css || this.getPosition();
            this.BOX.css(css);
            if( typeof set.position.callback === "function"){
                set.position.callback.call(this,[css,this.BOX]);
            }
        };
        /**
         * 获取pop的坐标，可以是相对某元素(position.ref)、自动居中（center）
         */
        P.getPosition = function(){
            var set     = this.SET
                ,box    = {}
                ,$box   = this.BOX
                ,css    = {position:"fixed"}
                ,win    = {h:$win.height(),w:$win.width()}
                ,doc    = {h:$doc.height(),w:$doc.width()}

            box.w = set.width;
            box.h = set.height===null?$box.outerHeight():set.height;

            if( pub.isObject(set.position) ){
                var ref = $(set.position.ref);
                    css.position = "absolute";
                    css.top = set.position.top || 0;
                    css.left = set.position.left || 0;
                if(ref.length>0){
                    var refCss = ref.offset();
                    if(css.top !== 0){
                        css.top += refCss.top;
                    }else{
                        var nTop = refCss.top + ref.outerHeight(true);
                        if(set.position.auto){
                            var nh = nTop + box.h;
                            if(nh > $doc.scrollTop() + win.h){
                                if(nh > doc.h){
                                    css.top = refCss.top - box.h;
                                    if(css.top < 0) css.top = 0;
                                }
                            }
                        }
                        css.top = nTop;
                    }//end top

                    if(css.left !==0){
                        css.left += refCss.left;
                    }else{
                        var nLeft = refCss.left + ref.outerWidth(true);
                        if(set.position.auto){
                            var nl = nLeft + box.w;
                            if(nl > $doc.scrollLeft() + win.w){
                                if(nl > doc.w){
                                    css.left = refCss.left - box.w;
                                    if(css.left < 0) css.left = 0;
                                }
                            }
                        }
                    }
                    //end left
                }else{
                    throw new Error("position.ref = "+set.position.ref+" is not find!","position.ref = "+set.position.ref+" is not find!");
                }
            }

            if(set.position === "center"){
                if(F.ie6){
                    var xhtml       = $("html");
                    css.position    = "absolute";
                    if( !xhtml.css("background-image") || xhtml.css("background-image") === "none" ){
                        xhtml.css("background-image","url(about:blank)");
                    }
                    xhtml = null;
                    css.top = (document.documentElement || document.body).scrollTop + ((win.h - box.h)/2);
                    css.left = (document.documentElement || document.body).scrollLeft + ((win.w - box.w)/2)
                }else{
                    css.top = "50%";
                    css.left = "50%";
                    if(set.draggable){
                        css.left    = (win.w - box.w)/2;
                        css.top     = (win.h - box.h)/2;
                    }else{
                        css["margin-left"] = "-"+ (box.w/2) +"px";
                        css["margin-top"] = "-"+ (box.h/2) +"px";
                    }
                }
            }
            return css;
        };
        /**
         * 初始化，显式使用
         */
        P.init = function(){
            this.INIT = 0;
            this.setConfig();
            this.setEvent();
            if(this.SET.initBeforeOpen){
                this.INIT = 1;
                B[this.SET.type].setContent.call(this);
            }else{
                this.BOX.hide();
            }
        };
        /*
         * 公用的事件代理，一般内部使用
         * @param {String} name 事件名称
         * @param {Array} args 参数数组
         * @exmaple
         *  this.on("open",[this,参数数组]);
         */
        P.on = function(name,args){
            var onEvent = this.SET["on"+name];
            if(typeof onEvent === "function"){
                return onEvent.apply(this,args)
            }
        };
        /**
         * 在某些情况需要重新设置参数，但无需立刻生效的
         * @param {Object} cfg 参数
         */
        P.reSet = function(cfg){
            pub.extend(this.SET,cfg);
        };
        /**
         * 打开pop
         */
        P.open = function(){
            var set = this.SET;
            if(this.ISHIDE){
                this.show();
            }else{
                return;
            }
            if(this.INIT < 1){
                this.INIT = 1;
                F[set.type].setContent.call(this);
            }
            this.setPosition();
            if(typeof set.onopen === "function"){
                set.onopen(this.BOX);
            }
            $doc.click(this.DOCCLICK);
        };
        /*
         * 关闭
         * 
         */
        P.close = function(){
            var set = this.SET;
            this.on("close",[this.BOX]);
            if(set.onclickout)$doc.unbind("click",this.DOCCLICK);
            this.remove();
            pub.generalFunction.onTipHide(false);
        };
        P.save = function(){
            this.on("save",[this.BOX]);
        }
        P.cancel = function(){
            this.on("cancel",[this.BOX]);
        }
        /**
         * 显示pop
         */
        P.show = function(){
        	var scroll	= this.getscroll();
        	var self	= this;
            var set		= this.SET;
            this.ISHIDE = false;
            //this.BOX.css("visibility","visible").show()//.fadeTo(set.speed,set.opacity);
            if(set.mask){
                this.setMask("show");
            }
            this.BOX.css("visibility","visible").fadeTo(set.speed,1,function(){
            	self.on("show",[self.BOX]);
            });
            //this.BOX.css("visibility","visible").animate({width: set.width},function(){
            //	self.on("show",[self.BOX]);
            //});

            this.on("show",[this.BOX]);
            
            //绑定esc键
            //框架模式下 可能无效
            pub.generalEvent.escKeyDown(self.BOXID,function(){            	
            	if(!self.ISHIDE && self.ISCLICKBOX){
                    // self.close();
                    // 一个一个关闭
                    $('.wk-pop').eq(-1).is(self.BOX) && self.close();
                }
            });
            
            return ;
        };
        /**
         * 隐藏pop
         */
        P.hide = function(){
        	var self	= this;
            var set 	= this.SET;
            this.ISHIDE = true;
            this.BOX.css(this.getPosition()).animate({height:50},function(){
            	self.BOX.css("visibility","hidden").hide();
            });
            //this.BOX.css(this.getPosition()).css("visibility","hidden").hide();
            this.on("hide",[this.BOX]);
            if(set.mask) this.setMask("hide");
            pub.generalFunction.onTipHide(false);
        };
        P.remove = function(){
        	var self	= this;
        	var set 	= this.SET;
            this.ISHIDE = true;
            this.BOX.fadeTo(set.speed,0,function(){
            	self.BOX.remove();
            });
           // this.BOX.remove();
            if(set.mask) this.setMask("hide");
            pub.generalFunction.onTipHide(false);
        }
        P.getscroll = function (){
			if (self.pageYOffset) {
				return {scrollTop:self.pageYOffset,scrollLeft:self.pageXOffset};
			} else if (document.documentElement && document.documentElement.scrollTop) { // Explorer 6 Strict
				return {scrollTop:document.documentElement.scrollTop,scrollLeft:document.documentElement.scrollLeft};
			} else if (document.body) {// all other Explorers
				return {scrollTop:document.body.scrollTop,scrollLeft:document.body.scrollLeft};
			};
		};
        /* code of pop end*/
        return F;
    });
});
//# sourceMappingURL=pop.js.map
