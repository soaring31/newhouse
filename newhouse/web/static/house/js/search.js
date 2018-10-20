seajs.use(['cookie'],function(cookie,a){
        var house_type = "3";
        var token = 'TvSV3iSTmKLwsqQlAiGy0v7kSFfTxzfydLwygKjIvG55DSVuUXaVWVmMmYfb4Em-GrWhJ4iN30-P9A5qLr8PEA%3D%3D';
        var city = $('#cityad').val();
        var cityId = $('#cityid').val();
        var cityParams = '?_area=' + cityId;
        var url = window.location.href;
        var port='';
        //var testReg=/^http:\/\/\S+(zhugefang\.test)\S+/;
        //var localhostReg=/^http:\/\/\S+(:3000)\S+/;
        //var online=/^http:\/\/\S+(zhugefang\.com)\S+/;
        // if(testReg.test(window.location.href)){
        //     // 测试
        //     port = '.zhugefang.test/';
        // }
        // if(localhostReg.test(window.location.href)){
        //     // 本地
        //     port = '.zhuge.com:3000/';
        // }
        // if(online.test(window.location.href)){
        //     // 线上
        //     port = '.zhugefang.com/';
        // }
        port = '.zhuge.com/';//08cms中二手房，租房目前只走线上
        // 列表路由url
        //var urlLink = "http://"+city+".newhouse"+port;
        var host = window.location.host;
        var urlPath = '/xinfang';//上线须去掉；
        var urlLink = urlPath + '/detail/';
        var searchUrl = '/guesswords';
        var type = url.split('.')[1];
        // 是否是小区列表的搜索
        var boroughList = false;

        // 刚进入页面时 判断一下 type 类型，暂时只走新房的，后期开通二手房，租房等可进行判断
        // if(type == 'house'){
        //     house_type = '1';
        // }else if(type == 'rent'){
        //     house_type = '2';
        //     urlLink = "http://"+city+".rent"+port;
        // }else if(type == 'newhouse'){
        //     house_type = '3';
        //     urlLink = "http://"+city+".newhouse"+port;
        //     searchUrl = 'NewHouseSearch/getsswrod';
        //     $('.input_text').attr('placeholder','请输入楼盘／商圈／地铁');
        // }
        //
        // if(window.location.href.indexOf('community')>0){
        //     boroughList = true;
        //     house_type = '1';
        //     urlLink = "http://"+city+".house"+port+'community/';
        //     searchUrl = 'Search/guessword';
        //     boroughList = true;
        // }

        var zgConfig = {
       	 // 线上接口
       	 "postUrlPrefix":seajs.data.vars.externalLinks,
       	 // 测试接口
       	 //"postUrlPrefix":'http://api.zhugefang.test/API/',
       	 "platformType":"2",
       	 "appName":"zgzf",
       	 "token":token,
         "method":"post"
        };
        function ajaxPost(url, data, callback) {
           	 var appName = data.appName ? data.appName : zgConfig.appName;
           	 var platformType = data.platformType ? data.platformType : zgConfig.platformType;
           	 var token = data.token ? data.token : zgConfig.token;
           	 data.appName = appName;
           	 data.platformType = platformType;
           	 data.token = token;
             if(data.houseType == 3 || data.houseType == 5){
                 zgConfig.postUrlPrefix = seajs.data.vars.apiUrl;
             }else{
                 zgConfig.postUrlPrefix = seajs.data.vars.externalLinks;
             }
           	 $.ajax({
           		 "url":zgConfig.postUrlPrefix + url,
           		 "type":zgConfig.method,
           		 "data":data,
           		 success:function (xhr) {
           			 if ($.isFunction(callback)) {
           				 callback(xhr);
           			 }
           		 }
           	 });
        }
        // 首页切换房源搜索
        $('.search-list-box .select_list').on('click','.select-li',function(){
            var clickHouseType = $(this).attr('house_type');
            $(this).addClass('hover').siblings().removeClass('hover');
            $('#searchTxt').val('');
            switch(clickHouseType){
                case '1':
                    house_type = '1';
                    urlLink = "http://"+city+".house"+port;
                    searchUrl = 'Search/guessword';
                    $('.input_text').attr('placeholder','请输入小区／商圈／地铁');
                    boroughList = false;
                    break;
                case '2':
                    house_type = '2';
                    urlLink = "http://"+city+".rent"+port;
                    searchUrl = 'Search/guessword';
                    $('.input_text').attr('placeholder','请输入小区／商圈／地铁');
                    boroughList = false;
                    break;
                case '3':
                    house_type = '3';
                    urlLink = host+"/xinfang/detail/";
                    searchUrl = '/guesswords';
                    $('.input_text').attr('placeholder','请输入楼盘／商圈／地铁');
                    boroughList = false;
                    break;
                case '4':
                    house_type = '1';
                    urlLink = "http://"+city+".house"+port+'community/';
                    searchUrl = 'Search/guessword';
                    boroughList = true;
                    $('.input_text').attr('placeholder','请输入小区／商圈／地铁');
                    break;
                case '5':
                    house_type = '5';
                    urlLink = host+"/news/list";
                    searchUrl = '/guesswords';
                    boroughList = false;
                    $('.input_text').attr('placeholder','请输入要查询的关键词');
                    break;
            }
        });

        //自动完成
        function autoSearch() {
            $(document).on('input','#searchTxt', function() {
                if ($(this).val() == "") {
                    $("#autoSearchList").hide();
                    return false;
                }
                var code = new RegExp("\/houseType\/([a-zA-Z0-9\u4e00-\u9fa5\-\+\-]+)?"),
                    url = window.location.pathname;
                var postData =null;
                if(boroughList){
                    postData = {
                        "city": city,
                        "word": $("#searchTxt").val(),
                        "houseType": house_type,
                        "borough":"1",
                    };
                }else {
                    postData = {
                        "city": city,
                        "word": $("#searchTxt").val(),
                        "houseType": house_type
                    };
                }
                if (code.test(decodeURI(url))) {
                    house_type = "2";
                }
                ajaxPost(searchUrl,postData, function (xhr) {
                    if (xhr.data&&xhr.data.length > 0) {
                        $("#autoSearchList").show();
                        var tempHtml = "<span class=\"search-drp-title\">您可能在找<\/span>";
                        var searchUrl = '';
                        xhr = xhr.data;
                        for (var i = 0; i < xhr.length; i++) {
                            var keyword = encodeURIComponent(xhr[i].keyword);
                            if(house_type == '3'){//如果是新房
                                switch(xhr[i].type_name){
                                    case "新房":
                                        searchUrl = urlLink + xhr[i].id + '.html' + cityParams;
                                        break;
                                    case "城区":
                                        searchUrl = urlPath+ '/' + xhr[i].id + cityParams;
                                        break;
                                    case "商圈":
                                        searchUrl = urlPath+ '/' + xhr[i].pid + '-' + xhr[i].id + cityParams;
                                        break;
                                    case "地铁线":
                                        searchUrl = urlPath+ '/' + 'no-no-' + xhr[i].id + cityParams;
                                        break;
                                    case "地铁站":
                                        searchUrl = urlPath+ '/' + 'no-no-' + xhr[i].pid + '-' + xhr[i].id + cityParams;
                                        break;
                                    default:
                                        searchUrl = urlPath + cityParams;
                                        break;
                                }
                            }else{
                                switch (xhr[i].field) {
                                    case "subwayline":
                                        //地铁线
                                        searchUrl = urlLink + "l" + xhr[i].other_id;
                                        break;
                                    case "subway":
                                        //地铁站
                                        searchUrl = urlLink + "m" + xhr[i].other_id;
                                        break;
                                    case "borough_id":
                                        //小区
                                        searchUrl = urlLink + "q" + xhr[i].other_id;
                                        break;
                                    case "cityarea_id":
                                        //城区
                                        searchUrl = urlLink + xhr[i].pinyin;
                                        break;
                                    case "cityarea2_id":
                                        //商圈
                                        searchUrl = urlLink + xhr[i].pinyin;
                                        break;
                                    case "community_id":
                                        //社区
                                        searchUrl = urlLink + "C"+xhr[i].other_id+"v_" + keyword + "_";
                                        break;
                                    case "complex_id":
                                        // 楼盘
                                        searchUrl = urlLink + "q" + xhr[i].other_id;
                                        break;
                                    case "source":
                                        // 楼盘
                                        searchUrl = urlLink + "s" + xhr[i].other_id;
                                        break;
                                    default:
                                        //关键字
                                        searchUrl = urlLink + "v_" + keyword + "_";
                                        break;
                                }
                            }


                            if(house_type == '3'){
                                tempHtml += "<a href=\"" + searchUrl + "\" class=\"search-drp-item\"  target=\"_blank\">" +
                                    "<span class=\"search-drp-name\">" + xhr[i].name + "-<\/span>" +
                                    "<span class=\"search-drp-address\">" + xhr[i].type_name + "<\/span>" +
                                    //"<span class=\"search-drp-num\">" + xhr[i].count_broker + "套<\/span>" +
                                    "<\/a>";
                            }else{
                                tempHtml += "<a href=\"" + searchUrl + "\" class=\"search-drp-item\" target=\"_blank\">" +
                                    "<span class=\"search-drp-name\">" + xhr[i].keyword + "-<\/span>" +
                                    "<span class=\"search-drp-address\">" + xhr[i].type_name + "<\/span>";
                                if(window.location.href.indexOf('community')>0){
                                    tempHtml +="<span class=\"search-drp-num\"></span>";
                                }else {
                                    if(boroughList){
                                        tempHtml +="<span class=\"search-drp-num\"></span>";
                                    }else {
                                        tempHtml +="<span class=\"search-drp-num\">约" + xhr[i].count_broker + "套</span>";
                                    }

                                }
                                tempHtml += "</a>";
                            }

                        }
                        $("#autoSearchList").html(tempHtml);
                    } else {
                        $("#autoSearchList").hide();
                    }
                });
            });
            $(document).on("click","#searchBtn", function() {
                if ($("#searchTxt").val() != "") {
                    if(house_type == '3'){
                        window.location.href = '/xinfang' + "?name=" + encodeURIComponent($("#searchTxt").val());
                    }else if(house_type == '5'){
                        window.location.href = '/news/list' + "?name=" + encodeURIComponent($("#searchTxt").val());
                    }else{
                        window.location.href = urlLink + "v_" + encodeURIComponent($("#searchTxt").val())+"_";
                    }
                } else {
                    if(house_type == '1'){
                        window.location.href = "http://"+ city + ".house.zhuge.com";
                    }
                    if(house_type == '2'){
                        window.location.href = "http://"+ city + ".rent.zhuge.com";
                    }
                    if(house_type == '3'){
                        window.location.href = "/xinfang";
                    }
                    if(house_type == '5'){
                        window.location.href = "/news";
                    }
                }
            });

            //回车搜索
            $(document).on('keypress','#searchTxt' ,function(event) {
                if (event.keyCode == "13") {
                    $("#searchBtn").click();
                }
            });
            $('#slider-wrap').unbind("click").bind("click", function(event) {
                $("#autoSearchList").hide();
            });
        };

        function getCookie(name) {
            var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
            if (arr = document.cookie.match(reg))
                return unescape(arr[2]);
            else
                return null;
        }

        //tip提示
        function tip() {
            var browser = navigator.appName
            var b_version = navigator.appVersion
            var version = b_version.split(";");
            var trim_Version = version[1].replace(/[ ]/g, "");
            if (browser == "Microsoft Internet Explorer" && trim_Version == "MSIE6.0") {
                $("#tipBox").fadeIn();
                setTimeout(function() {
                    $("#tipBox").fadeOut();
                }, 2000);
            } else if (browser == "Microsoft Internet Explorer" && trim_Version == "MSIE7.0") {
                $("#tipBox").fadeIn();
                setTimeout(function() {
                    $("#tipBox").fadeOut();
                }, 2000);
            } else if (browser == "Microsoft Internet Explorer" && trim_Version == "MSIE8.0") {
                $("#tipBox").fadeIn();
                setTimeout(function() {
                    $("#tipBox").fadeOut();
                }, 2000);
            } else if (browser == "Microsoft Internet Explorer" && trim_Version == "MSIE9.0") {
                $("#tipBox").fadeIn();
                setTimeout(function() {
                    $("#tipBox").fadeOut();
                }, 2000);
            }
        };
        autoSearch();
        tip();
})
