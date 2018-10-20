/*  
 ----分页控件----
 【setting】:			json对象的参数说明
 【count】:			表示总数
 【pageSize】:		 表示每页显示条数
 【parentClass】:		表示控件最外面的div的class名称
 【selectClass】:		表示当前选中页的class名称【样式】
 【showFirPage】:		表示当数据不足一页时，是否显示分页控件
 【showInputPage】:	表示是否显示输入文本分页
 【callback】:		表示点击页面后的回调函数
 【notclickClass】:	表示此按钮不能点击
 【PrevHome】：		首页Id
 【PrevPageText】：	上一页ID
 【NextPagetext】：	下一面ID
 【prevPageClass】:	上一页的class名称
 【nextPageClass】:	上一页的class名称
 【ParevLastPage】：	末页ID
 【GoPage】：			跳转文本框ID
 【showcount】:		是否显示记录条数
 【showpagenum】:		是否显示多少页
 【But_Go】:			跳转按钮ID
 */

define(function(require,exports,module)
{
	
	//一个对象
	var obj;
	var self;
	
	//控制页对象
	var objPager;
	
	//总页数
	var pageCount;
	//设置当前页
	var currentPage;
	
	var callback;

	var core = require("core");
	
	(function(out){
		module.exports=out;
	}) ({
		//调用函数
    	/**
    	 * 调用函数
    	 * @page 函数名， par参数名
    	 * @return function
    	 */
    	init:function(page,settings,callback){
    		//初始化加載的腳本
    		try{
    			self = this;
    			self[page](settings,callback);
    		}catch(e) {
    			core.generalTip.ontextTip("pager JS脚本错误！["+e+"]","error");
    		}
    	}
		,showPager:function(settings, callback){    
			var dsfSetting = {
				count: 1,
				pageSize: 10,
				parentClass: "pagenav clearfix",
				itemSelClass: "pagenav-cell on",
				itemClass: "pagenav-cell",
				notclickClass: "",
				prevPageClass: "upPage",
				nextPageClass: "nextPage",
				showFirPage: false,
				showInputPage: false,
				pageIndex: 1,
				pageItemShowTotal: 6,
				PrevHome: "PrevHome",
				PrevPageText: "PrevPageText",
				NextPagetext: "NextPagetext",
				ParevLastPage: "ParevLastPage",
				GoPage: "GoPage",
				But_Go: "But_Go",
				showcount: false,
				showpagenum: false,
				showmoreword: true,
				prevhtml: "上一页",
				nexthtml: "下一页",
				parentObj: $("#page")
			};
			 if (typeof callback == "function") {
				 self.callback = callback;
             }

			//获取setting对象传过来的值合并dsfSettings对象的值
			obj = $.extend(dsfSetting, settings);
			
			//最少显示页数
			if (obj.pageItemShowTotal < 5) {
				obj.pageItemShowTotal = 6;
			}
			
			//获取总页数
			pageCount = Math.ceil(parseInt(obj.count) / parseInt(obj.pageSize));
			pageCount = pageCount == 0 ? 1 : pageCount;			
			
			//设置当前页
			currentPage = obj.pageIndex;

			//第一次加载事件			
			objPager= "<ul class='" + obj.parentClass + "'>"+self.pageHtml(currentPage, pageCount)+ "</ul>";
			//写入html
			obj.parentObj.html(objPager);
			
			//对象化
			objPager = $(objPager);
			
			//绑定按钮
			self.addPageClikc();
		}
		//加入分页html代码
		,pageHtml:function(pageIndex, leng)
		{
			var Isshows = true;
			
			//否count 超过每页总条数
			var show_pager = obj.count > obj.pageSize;
			
			//判断显示条件
            if (!obj.showFirPage && !show_pager) {
                Isshows = false;
            }
            
            var pagehtml = "";
            
            if (Isshows) {

            	if (obj.showcount) {
                    pagehtml += '<span class="fn-left">共 ' + obj.count + ' 条记录</span>';
                }
                
                if (pageIndex == 1) {
                    pagehtml += '<li id="' + obj.PrevPageText + '" name="notClick" class="'+obj.prevPageClass+'"><span>' + obj.prevhtml + '</span></li>';
                } else {
                    pagehtml += '<li id="' + obj.PrevPageText + '" name="" class="'+obj.prevPageClass+'"><a href="javascript:void(0);">' + obj.prevhtml + '</a></li>';
                }

                if (pageCount <= obj.pageItemShowTotal) {
                    for (var i = 1; i <= pageCount; i++) {
                        if (pageIndex == i) {
                            pagehtml += '<li class="' + obj.itemSelClass + '"><a href="javascript:void(0);">' + i + '</a></li>'
                        } else {
                            pagehtml += '<li class="' + obj.itemClass + '"><a href="javascript:void(0);">' + i + '</a></li>';
                        }
                    }
                } else {
                    if (pageIndex > (obj.pageItemShowTotal - 2) && obj.showmoreword) {
                        pagehtml += '<li class="' + obj.itemClass + '"><a href="javascript:void(0);">1</a></li>';
                        pagehtml += '<li><span>…</span></li>';
                    }
                    for (var i = self.beginPageIndex(pageIndex) ; i <= self.endPageIndex(pageIndex) ; i++) {
                        if (pageIndex == i) {
                            pagehtml += '<li class="' + obj.itemSelClass + '"><a href="javascript:void(0);">' + i + '</a></li>'
                        } else {
                            pagehtml += '<li class="' + obj.itemClass + '"><a href="javascript:void(0);">' + i + '</a></li>';
                        }
                    }
                    if (pageIndex <= pageCount - (obj.pageItemShowTotal - 2) && obj.showmoreword) {
                        pagehtml += '<li><span>…</span></li>';
                        pagehtml += '<li class="' + obj.itemClass + '"><a href="javascript:void(0);">' + pageCount + '</a></li>';
                    }
                }

                if (pageIndex == pageCount) {
                    pagehtml += '<li id="' + obj.NextPagetext + '" name="notClick" class="'+obj.nextPageClass+'"><span>' + obj.nexthtml + '</span></li>';
                } else {
                    pagehtml += '<li id="' + obj.NextPagetext + '" class="'+obj.nextPageClass+'"><a href="javascript:void(0);">' + obj.nexthtml + '</a></li>';
                }

                if (obj.showpagenum) {
                    pagehtml += '<span class="pagenav-cell pagenav-cell-ellipsis">共' + pageCount + '页</span>';
                }
            }
            return pagehtml;
		}
		
		,beginPageIndex:function(pageIndex)
		{
			if (pageIndex <= (obj.pageItemShowTotal - 2)) return 1;
			if (pageIndex >= pageCount - (obj.pageItemShowTotal - 4)) return pageCount - (obj.pageItemShowTotal - 2);
			return pageIndex - (obj.pageItemShowTotal - 4);
		}
		
		,endPageIndex:function (pageIndex) {
            if (pageIndex <= (obj.pageItemShowTotal - 4)) return obj.pageItemShowTotal - 1;
            if (pageIndex >= pageCount - (obj.pageItemShowTotal - 3)) return pageCount;
            return parseInt(pageIndex) + (obj.pageItemShowTotal - 4);
        }
		
		//上一页
		,prevClick:function () {
            if (parseInt(currentPage) - 1 == 0)
                currentPage = 1;
            else
                currentPage = parseInt(currentPage) - 1;

            $(objPager).html(self.pageHtml(currentPage, pageCount));
            
            //写入html
            
            obj.parentObj.html(objPager);
            self.addPageClikc();
            if (typeof self.callback == "function") {
            	self.callback(objPager, currentPage);
            }
        }
		
		/*******************↓下一页↓*****************/
        ,nextClick:function () {
            if (parseInt(currentPage) + 1 >= pageCount) {
                currentPage = pageCount;
            } else {
                currentPage = parseInt(currentPage) + 1;
            }
            $(objPager).html(self.pageHtml(currentPage, pageCount));
            obj.parentObj.html(objPager);
            self.addPageClikc();
            if (typeof self.callback == "function") {
            	self.callback(objPager, currentPage);
            }
        }

        /*******************↓首页↓*****************/
        ,prevHomeClick:function () {
        	currentPage = 1;
            $(objPager).html(self.pageHtml(1, pageCount));
            obj.parentObj.html(objPager);
            self.addPageClikc();
            if (typeof self.callback == "function") {
            	self.callback(objPager, currentPage);
            }
        }
        /*******************↓末页↓*****************/
        ,parevLastPageClick:function () {
        	currentPage = pageCount;
            $(objPager).html(self.pageHtml(pageCount, pageCount));
            obj.parentObj.html(objPager);
            self.addPageClikc();
            if (typeof self.callback == "function") {
            	self.callback(objPager, currentPage);
            }
        }
        
        /*******************↓go事件↓*****************/
        ,but_go:function () {
            var IsNumber = /^[0-9]+$/;

            var txt_Number = $("#" + obj.GoPage).val();

            if ($.trim(txt_Number).length > 0) {
                if (txt_Number != 0) {
                    if (IsNumber.exec(txt_Number)) {
                        if (txt_Number != currentPage) {
                            if (txt_Number <= pageCount) {
                                currentPage = txt_Number;
                                $(objPager).html(PateHtml(txt_Number, pageCount));
                                addPageClikc();
                                callback(objPager, txt_Number);
                            } else
                                alert("超出页码范围！");
                        } else
                            alert("当前页面！");
                    }
                    else
                        alert("页码只能输入数字！");
                } else
                    alert("页码不能输入0！");
            } else
                alert("请输入页码！");
        }
		
		/*******************↓<a>添加事件<a>↓*****************/
        ,addPageClikc:function (_firstindex) {
            var $butGo;
            var $prevPagetext;
            var $nextPagetext;
            var $prevHome;
            var $parevLastPage;
            
            if (obj.parentObj != null) {
                $butGo = obj.parentObj.find("#" + obj.But_Go);
                $prevPagetext = obj.parentObj.find("#" + obj.PrevPageText);
                $nextPagetext = obj.parentObj.find("#" + obj.NextPagetext);
                $prevHome = obj.parentObj.find("#" + obj.PrevHome);
                $parevLastPage = obj.parentObj.find("#" + obj.ParevLastPage);
            } else {
                $butGo = $("#" + obj.But_Go);
                $prevPagetext = $("#" + obj.PrevPageText);
                $nextPagetext = $("#" + obj.NextPagetext);
                $prevHome = $("#" + obj.PrevHome);
                $parevLastPage = $("#" + obj.ParevLastPage);
            }

            $butGo.off().one("click",(function () { self.but_go(); }));
            $prevPagetext.off().one("click",(function () { if ($(this).attr("name") != "notClick") { self.prevClick(); } }));
            $nextPagetext.off().one("click",(function () { if ($(this).attr("name") != "notClick") { self.nextClick(); } }));
            $prevHome.off().one("click",(function () { self.prevHomeClick(); }));
            $parevLastPage.off().one("click",(function () { self.parevLastPageClick(); }));
            $prevPagetext.nextUntil("#" + obj.NextPagetext).off().one("click",(function () {
                if (typeof (self.callback) == 'function' && $.trim($(this).prop("class")) != obj.selectClass) {
                    currentPage = $(this).text();
                    if (currentPage != "&lt;&lt;" && currentPage != "&gt;&gt;" && currentPage != "…"&&!$(this).hasClass("on")) {
                        $(objPager).html(self.pageHtml(currentPage, pageCount));
                        obj.parentObj.html(objPager);
                        self.callback(objPager, currentPage);
                    }
                    self.addPageClikc();
                }
            }));
            if (_firstindex) {
                if (typeof self.callback == "function") {
                	self.callback(objPager, _firstindex);
                }
            }
        }
	})
});
//# sourceMappingURL=pager.js.map
