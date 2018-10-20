seajs.use(['zst','echarts','zgrequest'],function (zst,echarts,zgrequest) {
// console.log(zgrequest.post,'22222')
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

	// 二级导航 展示
	$('.speed_open_list li').on('mouseover',function(){
		$('.speed_open_list li').removeClass('action');
		$(this).addClass('action');
	});
	$('.speed_open_list li').on('mouseleave',function(){
		$('.speed_open_list li').removeClass('action');
		$(this).removeClass('action');
	});
	$('.nav-more').on('mouseover',function(){
		$(this).show();
	});


    // 判断新房详情页顶部 传洋的广告是否展示
    if($('.j_headerImg img').length <= 0){
        $('.j_headerImg').hide();
    }
    // 判断新房列表页 右侧传洋立式广告位是否展示
    if($('.j_listRightImg img').length <= 0){
        $('.j_listRightImg').hide();
    }
    setTimeout(function(){
        if($('.j_headerImg img').length > 0){
            $('.j_headerImg').show();
        }
        // 判断新房列表页 右侧传洋立式广告位是否展示
        if($('.j_listRightImg img').length > 0){
            $('.j_listRightImg').show();
        }
    },1000);
	// 判断开通城市 新房 二手房 租房 模块隐藏
    function isOpenCityType(ishouses,isrent,isnewhouse){
        // 没有开通新房
        if(isnewhouse == 0){
            $('.j_isOpenNewhouse').hide();
        }
        // 没有开通二手房
        if(ishouses == 0){
            $('.j_isOpenHouse').hide();
        }
        //没有 开通整租房
        if(isrent == 0){
            $('.j_isOpenRent').hide();
        }
    }
    // 获取开通城市
    	var data = {};
      	zgrequest.post("/areas/opencities",data,function(xhr){
			var ishouse = 0;
			var isrent = 0;
			var isnewhouse = 0;
			if(xhr && xhr.code ==200 && xhr.data && xhr.data.length >0 ){
				var dataLst = xhr.data;
				for(var i = 0 ;i < dataLst.length;i++){
					if(cityad == dataLst[i].city){
						if(dataLst[i].open_type){
							if(dataLst[i].open_type.indexOf('1') > -1){
								ishouse = 1;
							}
							if(dataLst[i].open_type.indexOf('2') > -1){
								isrent = 1;
							}
							if(dataLst[i].open_type.indexOf('3') > -1){
								isnewhouse = 1;
							}
							isOpenCityType(ishouse,isrent,isnewhouse);
						}
					}
				}
			}
		},2)
})
