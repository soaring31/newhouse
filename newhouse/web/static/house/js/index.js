seajs.use(['zst','echarts'],function () {
	// 当前城市简称
	var cityad = $('#cityad').val();
	// 当前城市ID
	var cityid = $('#cityid').val();
	// 下载客户端 扫二维码
	$('.download,.client_box').on('mouseover', function() {
	    $('.client_box').show();
	    $('#download_roll').addClass('don_change');
	});
	$('.download,.client_box').on('mouseleave', function() {
	    $('.client_box').hide();
	    $('#download_roll').removeClass('don_change');
	});

  	// 获取价格走势
    $.ajax({
        url:seajs.data.vars.apiUrl+"/trends",
        type:"post",
        data:{
        	"city":cityad,
        	"area":cityid
        },
        success:function (xhr) {
            var newData = [];
            var sellData = [];
            var date = [];
            var series = [];
            var min,max;
            var smin,smax,nmin,nmax;
            if(xhr && xhr.code == 200 && xhr.data){
            	if(xhr.data.new_house_price){
                    if(xhr.data.new_house_price.tagout_price && xhr.data.new_house_price.tagout_price.length>0){

                        var len = xhr.data.new_house_price.tagout_price.length;
                        var yuan=xhr.data.new_house_price.tagout_price[len-1].price;
                        if(yuan==0){
                            $('.j_expNewHouse .j_price').html('<span>暂无</span>');
                        }else{
                            $('.j_expNewHouse .j_price span').html(yuan);
                        }
                        for(var i=0;i<xhr.data.new_house_price.tagout_price.length;i++){
                            date.push(xhr.data.new_house_price.tagout_price[i].date);
                            newData.push(xhr.data.new_house_price.tagout_price[i].price);
                        }
                    }
                    if(xhr.data.new_house_price.percentage>0){
                        $('.j_expNewHouse .j_trend span').html('涨'+xhr.data.new_house_price.percentage+'%');
                    }else if(xhr.data.new_house_price.percentage<0){
                        $('.j_expNewHouse .j_trend span').html('降'+(-xhr.data.new_house_price.percentage)+'%');
                    }else {
                        $('.j_expNewHouse .j_trend span').html('持平');
                    }
                    $('.j_expNewHouse .j_sellNum span').html(xhr.data.new_house_price.sell_house_count);
                    $('.j_expNewHouse .j_newNum span').html(xhr.data.new_house_price.month_house_count);
            	}
            	if(xhr.data.sell_house_price){
                    if(xhr.data.sell_house_price.tagout_price && xhr.data.sell_house_price.tagout_price.length>0){
                        for(var i=0;i<xhr.data.sell_house_price.tagout_price.length;i++){
                            if(date.length <= 0){
                                date.push(xhr.data.sell_house_price.tagout_price[i].date);
                            }
                            sellData.push(xhr.data.sell_house_price.tagout_price[i].price);
                        }
                    }
                    $('.j_expHouse .j_price span').html(xhr.data.sell_house_price.average_price);
                    if(xhr.data.sell_house_price.percentage>0){
                        $('.j_expHouse .j_trend span').html('涨'+xhr.data.sell_house_price.percentage+'%');
                    }else{
                        $('.j_expHouse .j_trend span').html('降'+xhr.data.sell_house_price.percentage+'%');
                    }
                    $('.j_expHouse .j_sellNum span').html(xhr.data.sell_house_price.all_house_count);
                    $('.j_expHouse .j_newNum span').html(xhr.data.sell_house_price.new_count);
                }
                if(newData.length <= 0 && sellData.length<=0){
                    $('.exp-trend').hide();
                    return;
                }
                
                
                // 是否开通二手房
                if(sellData && sellData.length>0){
                    smin = Math.min.apply(Math, sellData);
                    smax = Math.max.apply(Math, sellData);
                    series.push({
                        data: sellData,
                        type: 'line',
                        name:'二手房',
                        itemStyle: {
                            normal: {
                                color: '#FF8400'
                            }
                        }
                    });
                    $('.trend-title').append('<i style="background:#FF8400;"></i>二手房');
                }else{
                    nmin = Math.min.apply(Math, newData);
                    nmax = Math.max.apply(Math, newData);
                }
                // 是否开通新房
                if(newData && newData.length>0){
                    nmin = Math.min.apply(Math, newData);
                    nmax = Math.max.apply(Math, newData);
                    series.push({
                        data:newData,
                        type: 'line',
                        name: '新房',
                        itemStyle: {
                            normal: {
                                color: '#FF5C36'
                            }
                        }
                    });
                    $('.trend-title').append('<i style="background:#FF5C36;"></i>新房');
                }else{
                    smin = Math.min.apply(Math, sellData);
                    smax = Math.max.apply(Math, sellData);
                }
                if(smax>nmax){
                    max = smax;
                }else{
                    max = nmax;
                }
                if(nmin>smin){
                    min = smin;
                }else{
                    min = nmin;
                }
                max = parseInt(max + (max - min) / 5);
                min = parseInt(min - (max - min) / 5);
                option = {
                    tooltip: {
                        trigger: 'axis',
                        textStyle: {
                            align: "left"
                        }
                    },
                    grid: {
                        left: '-70',
                        right: '0',
                        top: '0',
                        bottom: '0',
                        show:false,
                        containLabel:true
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: true,
                        data: date,
                        show: false,
                        scale: false,
                        axisLabel:{
                            show:false
                        },
                        splitLine: {
                            lineStyle: {
                                color: ["#efefef"],
                                width: 1
                            },
                            show:false,
                            interval:1
                        },
                        axisLine: {
                            show: false,
                            lineStyle:{
                                color:["#efefef"]
                            }
                        }
                    },
                    yAxis: {
                        type: 'value',
                        show: true,
                        max: max,
                        min: min,
                        axisTick: {
                            show: false
                        },
                        axisLine: {
                            show: false,
                            lineStyle:{
                                color:["#efefef"]
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                color: ["#efefef"],
                                width: 1
                            },
                            show:true,
                            interval:1
                        },
                        axisLabel: {
                            formatter: '￥{value}'+'元',
                            textStyle: {
                                color: "#878787"
                            }
                        },
                    },
                    series: series
                };
                var myChart = echarts.init(document.getElementById('trend'));
                myChart.setOption(option);
            }

	    }
    });
})