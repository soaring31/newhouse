/*!
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 15-04-28
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
/*
 * @module main
 */
define(function (require, exports, module) {
    (function(out){
        module.exports=out;
    })({
    	init:function(){
    		// console.log("sd");
    	},
    	tab : function(className) {
    		if(arguments[0]){
    			var tab = $(className);
    		}else{
    			var tab = $(".tab-wrap");
    		}		    
		    var header = $(".tab-header", tab);
		    header.find("li").hover(function () {
		        var $index = $(this).index();
		        $(this).addClass("cur").siblings().removeClass("cur");
		        $(this).parent(".tab-header").next(".tab-body")
		            .find(".tab-item").eq($index).addClass("cur")
		            .siblings().removeClass("cur");
		    });
		},
		nav:function(showClass, hideClass) {
            var li = $(showClass);
            li.hover(function () {
                var $this = $(this);
                if ($this.find("b").length <= 0) return false;
                $this.find("b").show("fast")
                    .end().addClass("hover")
                    .siblings().removeClass("hover");
                $(showClass).find(hideClass).show();
            }, function () {
                $(this).removeClass("hover").find("b").hide();
                $(showClass).find(hideClass).hide();
            });
        },
        returnTop:function(selector){
        	var $selector = $(selector);
        	$(window).scroll(function () {
	            var $scrollTop = $(window).scrollTop();
	            if ($scrollTop > 500) {
	                $selector.show();
	            } else {
	                $selector.hide();
	            };            
	        });
	        $selector.click(function(){
	        	$("body,html").animate({ scrollTop: "0px" }, 1000);
	        });
        },
		al:function(){
			alert("sad");
		}
    })

});

//# sourceMappingURL=service.js.map
