/*!
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年8月30日
 * 走势图
 */
define(function(require, exports, module) {
    var init = function(el, data, options) {
        var chart = require('chartmobile');
        var el = el || '#cms08';
        var $el = $(el);

        if (!$el.is('canvas')) {
            $el = $('<canvas></canvas>').insertAfter(el);
            $(el).remove();
        }
        // 调整走势图的位置
        $el.attr({
                width: $(window).width() * .9
            })
            .css({
                margin: '0 auto',
                display: 'block'
            })
        // console.log($el);
        var ctx = $el[0].getContext("2d");
        var datasets = [];

        // 默认颜色值
        if (typeof zstColor == 'undefined') {
            var zstColor = ['rgba(238,68,51,1)', 'rgba(238,129,244,1)'];
        };

        // 循环数据
        /*for (var i = 0, len = zstData.length; i < len; i++ ){
            datasets.push({
                label: "",
                fillColor: "transparent",
                strokeColor: zstColor[i],
                pointColor: zstColor[i],
                data: zstData[i]
            });
        };*/

        // data定义
        var data = {
            labels: data.xAxis,
            datasets: [{
                label: "",
                fillColor: "transparent",
                strokeColor: zstColor[0],
                pointColor: zstColor[0],
                data: data.data
            }]
        };

        // 配置定义
        if (typeof options == 'undefined') {
            var _options = $.extend(true, {}, {
                // 小提示的圆角
                // tooltipCornerRadius: 0,

                // 折线的曲线过渡，0是直线，默认0.4是曲线
                // bezierCurveTension: 0,

                // bezierCurveTension: 0.4,

                // 关闭曲线功能
                // bezierCurve: false,

                // 背景表格显示
                // scaleShowGridLines : false,

                // 点击的小提示
                tooltipTemplate: "{% raw %}<%if (label){%><%=label%>：<%}%><%= value %> 元/m2{% endraw %}",

                //自定义背景小方格、y轴每个格子的单位、起始坐标
                // scaleOverride: true,
            }, options);
            ;
        };
        // console.log(options)
        new Chart(ctx).Line(data, _options);
    }

    module.exports = init;
});