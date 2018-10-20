/*!
* @author : xiaoying <1710035347@qq.com>
* @date   : 2016-06-15
* @name   : pagecard
* @modify : 2016-06-15 14:35:38
 */
function PageCard(args){
    var _this = this;   
    /*可用参数*/
    /*
    * @param {string} btn  jq选择器 控制元素
    * @param {string} con  jq选择器 重排元素父级
    * @param {string} btn  jq选择器 重排元素
    * @param {string} hover  控制元素当前状态的class
    * @param {number} timer 重排元素需要花的时间 默认1秒
    */ 
    this.btn = $(args.btn);
    this.con = $(args.con);
    this.child = $(args.child);
    this.hover = args.hover;
    this.timer = args.timer || 1000;
    var arr1 = _this.getChildHeight(this.child);
    var arr2 = _this.getparentYHeight(this.child);
    //内容区域显示位置的高度
    this.con.parent().css("height",this.con.height() + "px");
    //定义顶部按钮的点击事件
    this.btn.each(function(k,v){
        $(v).click(function(){
            $(this).addClass(_this.hover).siblings().removeClass(_this.hover);
            _this.con.eq(0).stop().animate({"marginTop":-arr2[k] + "px"}, _this.timer , function(){
                //重新排序
                _this.rearrange(_this.con.eq(0), _this.child.length, k);
                //重新记录新的排列顺序
                arr2 = _this.getparentYHeight(_this.child);
                _this.con.css("marginTop","0px");
            })
        })
    });
}

PageCard.prototype = {
    /**
     * @method getChildHeight 
     * @description 获取需要显示的每个子元素的高度
     * @param  要处理的子元素组
     * @return {arra}     处理后的数据保存在一个数组中
    */
    getChildHeight:function(args){
        var arr = new Array();
        for(i = 0, len = args.length; i < len; i++){
            arr.push(args.eq(i).outerHeight(true));
        }
        return arr;
    },
    /**
     * @method getparentYHeight 
     * @description 获取需要显示的每个子元素距离父级的高度
     * @param  要处理的子元素组
     * @return {array}     处理后的数据保存在一个数组中
    */
    getparentYHeight:function(args){
        var arr = new Array();
        for(i = 0, len = args.length; i < len; i++){
            arr.push(args.eq(i).position().top);
        }
        return arr;
    },
    /**
     * @method rearrange 
     * @description 内容显示区块重新排序
     * @param  obj 重新排序的区域；  len  重新排序的元素个数； index  当前点击对象的索引值
    */
    rearrange:function(obj,len,index){
        for(var i = 0; i < len; i++){
            var id = $(":first",obj).attr("id");
            if (id != index) {
                obj.append($(":first",obj));
            }else{
                return;
            };
        }
    }
};
//# sourceMappingURL=..\..\src\js\plugin\pagecard.js.map

//# sourceMappingURL=pagecard.js.map
