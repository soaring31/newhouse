/*!
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年8月30日
 * 走势图
 */
define(function(require, exports, module) {
    var chart = require('charts');
    // 走势图
    if (typeof(jsonData) != 'undefined' && jsonData.series && jsonData.series.length) {

        $.each(jsonData.series[0].data, function(i, b) {
            jsonData.min = i == 0 ? b[1] : Math.min(b[1], jsonData.min);
        });
        // 默认属性
        var options = {
            colors: jsonData.color,
            chart: {
                renderTo: 'zst',
                type: "line"
            },
            credits: {
                 enabled: false
            },
            title: {
                text: jsonData.title
            },
            subtitle: {
                text: ""
            },
            xAxis: {
                categories: jsonData.month_s,
                tickmarkPlacement: "on",
                labels: {
                    style: {
                        fontSize: "14px",
                        fontFamily: "Microsoft YaHei"
                    },
                    y: 25
                }
            },
            yAxis: {
                title: "",
                gridLineColor: "#ddd",
                opposite: true,
                labels: {
                    formatter: function() {
                        if (this.value == 0) {
                            return "待定";
                        } else {
                            return this.value + "元";
                        }
                    },
                    style: {
                        fontSize: "14px",
                        fontFamily: "Microsoft YaHei"
                    },
                    y: 3
                },
                min: jsonData.min
            },
            tooltip: {
                crosshairs: true,
                useHTML: true,
                borderWidth: 1,
                borderColor: "#999999",
                borderRadius: 3,
                backgroundColor: "#FFFFFF",
                style: {
                    padding: "8px"
                },
                shared: true,
                formatter: function() {
                    if (this.y == 0) {
                        return "待定"
                    } else {
                        return jsonData.series[0].name + '<br/>' + this.y + "元/m&sup2;"
                    }
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                line: {
                    fillOpacity: .4,
                    marker: {
                        symbol: "circle",
                        radius: 5,
                        lineWidth: 1
                    }
                }
            },
            series: jsonData.series
        };
        var option;

        // 自定义其他属性
        if ( typeof(zstOpt) != 'undefined' ) {
            option = $.extend({}, options, zstOpt);
        } else {
            option = options;
        };

        if ($('#zst').length) $('#zst').highcharts(option)
    }
});