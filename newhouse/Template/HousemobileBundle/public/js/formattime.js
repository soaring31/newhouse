/*!
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年8月19日
 * To Created The Format Time.
 */
(function($, win, undefined) {
    $.fn.formatTime = function() {
        return this.each(function() {
            var $this = $(this);
            /**
             * @name defaults
             * @param {string} noend=null html代码片段或者文本说明，没有结束时间的时候执行，
             * @param {string} end=null html代码片段或者文本说明，结束时间到了之后执行
             * @param {number} endtime=null 时间戳
             * @param {number} interval=1000 毫秒，即时刷新时间
             * @param {number} type=2 时间的显示类型，可选值“1,2”，1表示剩余时间，2表示更新时间，默认值2
             * @param {number} updatetype=3 更新时间显示类型，可选值“1,2,3”，1显示方式为多少分钟或小时或天之前，2显示方式为日期+时间，3显示方式为日期，默认值3
             * @param {boolean} clear=false 布尔值，是否清除即时更新,(也就是说只会在刷新页面的时候才会更新日期)，只有在时间显示类型为2的时候才有效，倒计时不允许清除
             * @param {string} join=null 日期的拼接方式，默认年月日拼接
             * @param {boolean} timetype=0 布尔值, 判断传进来的时间格式，0表示传回来的是时间戳，1表示传回来的是年月日格式的时间
             * @示例 从data启动
             * ```html
             * <div class="format-time" data-endtime="1571573908" data-noend="<span>永久有效！</span>" data-end="<span>活动结束</span>" data-type="1">
             * ...
             * </div>
             * ```
             * @示例 从js启动
             * ```html
             * <div class="time"></div>
             * ```
             * ```js
             * $(".time").formatTime({
             *  noend: '<span>永久有效</end>',
             *  end: '<span>活动结束</span>',
             *  endtime: '1571573908',
             *  type: 1,
             * });
             * ```
             * @可用参数及默认配置
            */
            var defaults = {
                noend: null, // 没有结束时间执行html
                end: null, // 结束时间到了之后执行html
                endtime: null, // 结束时间 
                interval: 1000, // 执行时间
                type: 2, // 类型 1: 剩余时间 2: 更新时间
                updatetype: 3, // 更新时间显示类型
                clear: false, // 实时更新
                join: null, // 日期的拼接方式
                timetype: 0, // 传进来的日期格式
                text: '还剩', // 时间
            };
            var data = {
                noend: $this.data('noend'),
                end: $this.data('end'),
                endtime: $this.data('endtime'),
                interval: $this.data('interval'),
                type: $this.data('type'),
                updatetype: $this.data('updatetype'),
                clear: $this.data('clear'),
                join: $this.data('join'),
                timetype: $this.data('timetype'),
                text: $this.data('text'),
            };
            // var data = $this.data();
            
            var o = $.extend({}, defaults, data);
            var setTime;
            
            function time() {
                var nowDate = new Date();

                // 判断时间格式
                if (o.timetype) {
                    var theDate = new Date(o.endtime.replace(/-/g, '/'));                    
                } else {
                    var theDate = new Date(parseInt(o.endtime) * 1000);                    
                };

                var surplusTime = theDate.getTime() - nowDate.getTime(); // 毫秒值
                var surplusSecond = parseInt(surplusTime / 1000); // 秒
                var day = Math.floor(surplusSecond / 86400); // 天 surplusSecond / 60 / 60 / 24 分钟/小时/天
                var hour = Math.floor((surplusSecond - day * 86400) / 3600); // 小时 
                var minute = Math.floor((surplusSecond - day * 86400 - hour * 3600) / 60); // 分
                var second = Math.floor(surplusSecond - day * 86400 - hour * 　3600 - minute * 60); // 秒
                // 拼接时间
                var str = o.text + '：<span>' + day + '</span>天' + '<span>' + hour + '</span>时' + '<span>' + minute + '</span>分' + '<span>' + second + '</span>秒';

                if (o.type == 1) {

                    // 剩余时间判断
                    if (o.endtime == null || o.endtime == '' || o.endtime == '0') {

                        // 永久有效
                        clearInterval(setTime); // 清除调用
                        $this.html(o.noend);
                    } else {
                        if (day < 0) {

                            // 活动结束
                            clearInterval(setTime); // 清除调用
                            $this.html(o.end);
                        } else {

                            // 倒计时
                            $this.html(str);
                        };
                    };
                } else if (o.type == 2){
                    
                    // 更新时间判断
                    var updateTime = Math.abs(surplusSecond);
                    var myDateStr;
                    var info;

                    // 日期拼接判断
                    if (o.join) {
                        myDateStr = theDate.getFullYear() + o.join + (theDate.getMonth() + 1) + o.join + (theDate.getDate() < 10 ? '0' : '') + theDate.getDate();
                    } else {
                        myDateStr = theDate.getFullYear() + '年' + (theDate.getMonth() + 1) + '月' + (theDate.getDate() < 10 ? '0' : '') + theDate.getDate() + '日';
                    };

                    if (o.updatetype == 1) {

                        // 显示方式 才刚刚 或 n分钟前 或 n小时前 或 n天前 或 n个月前（会有误差）
                        switch(true) {
                            case updateTime < 60:
                                info = '才刚刚';
                            break;
                            case updateTime < 1800:
                                info = Math.floor(updateTime / 60) + '分钟前'
                            break;
                            case updateTime < 3600:
                                info = '半小时前';
                            break;
                            case updateTime < 86400:
                                info = Math.floor(updateTime / 3660) + '小时前';
                            break;
                            case updateTime < 86400 * 30:
                                info = Math.floor(updateTime / 86400) + '天前';
                            break;
                            default :
                                info = Math.floor(updateTime / 86400 / 30) + '个月前';
                            break;
                        };
                    } else if (o.updatetype == 2) {

                        // 显示方式 2019-10-20 20:18:28
                        info = myDateStr + ' ' + theDate.getHours() + ':' + theDate.getMinutes() + ':' + theDate.getSeconds();
                    } else {

                        // 显示方式 2019-10-20
                        info = myDateStr ;
                    };

                    // 更新时间
                    $this.html(info);

                    // 清除更新时间的即时更新
                    if (o.clear) {
                        clearInterval(setTime); // 清除调用
                    };
                };
            }
            setTime = win.setInterval(function() {
                time();
            }, o.interval);
        });
    };

    $(document).ready(function() {

        // 启动data
        $('[data-format="format-time"],.format-time').formatTime();
    });


})($, window);
//# sourceMappingURL=formattime.js.map
