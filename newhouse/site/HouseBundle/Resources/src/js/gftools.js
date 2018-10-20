/*!
* 详情页参考月供插件
* @author xying 2016-8-22
*/

define(function (require, exports, module) {
    var charts = require('charts');

    // 利率配置
    var lilv_gjj = new Array(.0275, .0325);
    var lilv_syx = new Array(.0435, .0475, .0475, .049);
    //保留两位小数
    function TwoDec(floatvar) {
        var f_x = parseFloat(floatvar);
        if (isNaN(f_x)) {
            return 0;
        }
        var f_x = Math.round(f_x * 100) / 100;
        var s_x = f_x.toString();
        var pos_decimal = s_x.indexOf('.');
        if (pos_decimal < 0) {
            pos_decimal = s_x.length;
            s_x += '.';
        }
        while (s_x.length <= pos_decimal + 2) {
            s_x += '0';
        }
        return s_x;
    }
    
    (function($) {
        // 月供插件
        $.yg_func = function() {
            // if ($("#m4").length == 0) return false;
            // $.formStyle({
            //     vType: "select",
            //     vFontSize: "14px",
            //     vIsId: true,
            //     vClass: ".slt_long",
            //     vWidth: 172
            // });

            $(".house_yuegong_input dd").each(function(index, element) {
                $(this).css("z-index", 10 - index);
            });

            // select 选择插件
            $(".input-model").click(function() {
                $('.slt_long').not($(this).find('.slt_long')).hide();
                var $child = $(this).find('.slt_long');
                $child.toggle();
                $child.find('li').click(function(e) {
                    var $this = $(this);
                    var $parent = $this.parent();
                    var _id = $parent.attr('sname');
                    var _txt = $.trim($this.text());
                    var _val = $.trim($this.attr('val'));
                    $('#' +　_id).val(_val).trigger("keyup").trigger("change");;
                    $parent.siblings('span').text(_txt);
                    $parent.hide();
                    $this.addClass('on').siblings().removeClass('on');
                    return false;
                });
                return false;
            });
            $(document).click(function() {
                $('.slt_long').hide();
            });

            var total_p = TwoDec($("#total_p").text());

            change_val();
            yg_rlt();

            $("[name=year_p]").change(function(e) {
                change_val();
                yg_rlt();
            });

            $("#guofang").change(function(e) {
                var v = $(this).val();
                $("ol[sname=percent_p] li").removeClass("dis").eq(0).addClass("dis");
                $("ol[sname=percent_p] li").eq(6).click().click();

                $("ol[sname=year_p] li").removeClass("dis").eq(0).addClass("dis");
                $("ol[sname=year_p] li").eq(20).click().click();

                if (v == 2) {
                    $("ol[sname=percent_p]").find("li:eq(0)").removeClass("dis");
                    $("ol[sname=percent_p]").find("li:gt(0)").addClass("dis");
                    $("ol[sname=percent_p] li").eq(0).click().click();

                    $("ol[sname=year_p] li").addClass("dis").eq(0).removeClass("dis");
                    $("ol[sname=year_p] li").eq(0).click().click();
                } else if (v == 1) {
                    $("ol[sname=percent_p]").find("li:gt(5)").addClass("dis");
                    $("ol[sname=percent_p] li").eq(5).click().click();
                    $("ol[sname=year_p] li").eq(20).click().click();
                } else {
                    $("ol[sname=percent_p] li").eq(6).click().click();
                    $("ol[sname=year_p] li").eq(20).click().click();
                }
                change_val();
                yg_rlt();
            });

            //贷款类别
            $("#yg_type").change(function(e) {
                if ($(this).val() == 2) {
                    $(".mix").show();
                    $("#yg_gjj").val(0).blur();
                } else {
                    $(".mix").hide();
                }
                change_val();
                yg_rlt();
            });

            //按揭成数
            $("#percent_p").change(function(e) {
                $("#yg_syx").val(0);
                $("#yg_gjj").val(0);
                change_val();
                yg_rlt();
            });

            //贷款类别金额
            $(".ipt_d").blur(function(e) {
                if (isNaN($(this).val()) || parseFloat($("#daikuan_z").text()) < parseFloat($(this).val())) {
                    $(this).val(0);
                }
                $(this).siblings(".ipt_d").val(TwoDec(parseFloat($("#daikuan_z").text()) - parseFloat($(this).val())));
                $(this).val(TwoDec($(this).val()));
            });

            //开始计算
            $("#ygbtn").click(function(e) {
                change_val();
                yg_rlt();
            });

            //联动
            function change_val() {
                var percent_p = parseInt($("#percent_p").val());
                var daikuan_p = total_p * percent_p / 10;
                var shoufu_p = total_p - total_p * percent_p / 10;
                $("#shoufu").text(TwoDec(shoufu_p));
                $("#cksf").text(TwoDec(shoufu_p));
                $("#daikuan").text(TwoDec(daikuan_p));
                $("#daikuan_z").text(TwoDec(daikuan_p));
                $("#chengshu").text(percent_p);
            }

            //月供结果
            function yg_rlt() {
                //贷款类别
                var yg_type = parseInt($("#yg_type").val());
                //年数
                var nianshu = parseInt($("#year_p").val()) / 12;
                //var lilv_gjj = new Array(0.2750, 0.0325);
                //var lilv_syx = new Array(0.0435, 0.0500, 0.0515);
                var lilv0 = 0,
                    lilv1 = 0;
                if (nianshu <= 1) {
                    lilv0 = lilv_syx[0];
                } else if (nianshu > 1 && nianshu <= 3) {
                    lilv0 = lilv_syx[1];
                } else if (nianshu > 3 && nianshu <= 5) {
                    lilv0 = lilv_syx[2];
                } else if (nianshu > 5) {
                    lilv0 = lilv_syx[3];
                }
                if (nianshu < 6) {
                    lilv1 = lilv_gjj[0];
                } else {
                    lilv1 = lilv_gjj[1];
                }

                jishuan(lilv0, lilv1);

                function jishuan(vlilv0, vlilv1) {
                    //月利息
                    var yuelilv0 = vlilv0 / 12;
                    var yuelilv1 = vlilv1 / 12;
                    //成数
                    var chengshu = parseInt($("#percent_p").val()) / 10;
                    //月数
                    var yueshu = 12 * nianshu;
                    //总价
                    var zongjia = parseFloat($("#total_p").text()) * 10000;

                    //贷款
                    var daikuan = zongjia * chengshu
                    var daikuan0 = 0;
                    var daikuan1 = 0;
                    if (yg_type == 0) {
                        daikuan0 = daikuan;
                        daikuan1 = 0;
                        lilv = vlilv0;
                        $("#lilv_txt").html("(<span>利率商业性" + TwoDec(lilv * 100) + "%</span>)");
                    } else if (yg_type == 1) {
                        daikuan0 = 0;
                        daikuan1 = daikuan;
                        lilv = vlilv1;
                        $("#lilv_txt").html("(<span>利率公积金" + TwoDec(lilv * 100) + "%</span>)");
                    } else if (yg_type == 2) {
                        var gongjijin = parseFloat($("#yg_gjj").val()) * 10000;
                        daikuan0 = daikuan - gongjijin;
                        daikuan1 = gongjijin;
                        lilv = 0;
                        $("#lilv_txt").html("(<span>利率公积金" + TwoDec(vlilv0 * 100) + "%</span> <span>商业性" + TwoDec(vlilv1 * 100) + "%</span>)");
                    }

                    //首付
                    var shoufu = zongjia - daikuan;

                    //月供
                    var yuegong0 = (yuelilv0 / (1 - (1 / (Math.pow(1 + yuelilv0, yueshu))))) * daikuan0
                    var yuegong1 = (yuelilv1 / (1 - (1 / (Math.pow(1 + yuelilv1, yueshu))))) * daikuan1
                    var yuegong = parseInt(yuegong0 + yuegong1);
                    var lixi = TwoDec((yuegong * yueshu - daikuan + 10000) / 10000);
                    $("#lixi").text(lixi);
                    if (isNaN(yuegong)) {
                        yuegong = 0;
                    }
                    $("#yuegong").text(yuegong);
                    $("#ckyg").text(yuegong);
                    $("#shoufu").text(TwoDec(shoufu / 10000));
                    $("#daikuan").text(TwoDec(daikuan / 10000));
                    $("#chengshu").text(chengshu * 10);
                    $("#shoucheng").text(10 - chengshu * 10);
                    show_pie(shoufu / 10000, daikuan / 10000, lixi);
                }
            }
            function show_pie(v0, v1, v2){
                var data = (parseInt(v0) + parseInt(v1) + parseInt(v2)) / 100;
                $('#bingtu').highcharts({
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    title: {
                        text: '',
                    },
                    tooltip: {
                        // enabled: true,
                        formatter: function() {
                            return this.point.name + ':' + this.point.y +'%';
                        }
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: true,
                                formatter: function() {  
                                var parse = (this.y)*data;
                                return this.point.name + '<br>' + parseFloat(parse).toFixed(2) +'万元<br>(' + this.point.y +'%)';

                        }   
                            },
                            tooltip: {
                                followPointer: true,
                                headerFormat: '',

                            }
                        }
                    },
                    series: [{
                        data: [{
                            name: '参考首付',
                            color: '#ffa644',
                            y: parseFloat(v0 / data).toFixed(2) * 1
                        }, {
                            name: '贷款金额',
                            color: '#2f69bf',
                            y: parseFloat(v1 / data).toFixed(2) * 1
                        }, {
                            name: '支付利息',
                            color: '#62bc4b',
                            y: parseFloat(v2 / data).toFixed(2) * 1
                        }]
                    }]                   
                });
            }


// $('#bingtu').highcharts({
//     title: {
//         text: ''
//     },
//     plotOptions: {
//         pie: {
//             dataLabels: {
//                 enabled: true,
//                 distance: -60,
//                 y: 10,
//             },
//             stacking: 'normal'
//         }
//     },
//     series: [{
//         type: 'pie',
//         name: 'Browser share',
//         dataLabels: {
//             // distance: -30
//         },
//         data: [{
//             name: '参考首付',
//             y: 10,
//             color: '#ffa644',
//             dataLabels: {
//                 rotation: 0,
//             }
//         }, {
//             name: '贷款金额',
//             y: 24,
//             color: '#2f69bf',
//             dataLabels: {
//                 rotation: 0
//             }
//         }, {
//             name: '支付利息',
//             y: 24,
//             color: '#62bc4b',
//             dataLabels: {
//                 rotation: 0
//             }
//         }]
//     }]
// }, function(c) {
//     setTimeout(function() {
//         rotate(c)
//     }, 500)
// });

function rotate(chart) {
    var allY, angle1, angle2, angle3;
    $.each(chart.series, function(i, s) {
        angle1 = 0;
        angle2 = 0;
        angle3 = 0;
        allY = 0;
        $.each(s.points, function(i, p) {
            allY += p.y;
        });
        $.each(s.points, function(i, p) {
            angle2 = angle1 + p.y * 360 / (allY);
            angle3 = angle2 - p.y * 360 / (2 * allY);
            p.update({
                dataLabels: {
                    rotation: angle3 > 180 ? 90 + angle3 : -90 + angle3
                }
            })
            angle1 = angle2;
        });
    });
}





            //圆饼图
            // function show_pie(v0, v1, v2) {
            //     var data = [];
            //     data[0] = {
            //         label: "参考首付",
            //         data: v0
            //     }
            //     data[1] = {
            //         label: "贷款金额",
            //         data: v1
            //     }
            //     data[2] = {
            //         label: "支付利息",
            //         data: v2
            //     }
            //     $.plot('#donut', data, {
            //         series: {
            //             pie: {
            //                 show: true,
            //                 radius: 1,
            //                 label: {
            //                     show: true,
            //                     radius: 1 / 2,
            //                     formatter: labelFormatter,
            //                     threshold: 0.1
            //                 }
            //             }
            //         },
            //         legend: {
            //             show: false
            //         },
            //         colors: ["#FFA644", "#2F69BF", "#62BC4B"]
            //     });

            //     function labelFormatter(label, series) {
            //         return "<div style='font-size:12px; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
            //     }
            // }
        }

    })(jQuery);
})