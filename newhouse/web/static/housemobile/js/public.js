//alert 弹窗列表页
window.alert = function(txt,total) {
    if (txt == undefined) return; //禁止弹出undefined
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.height = (document.documentElement.scrollHeight || document.body.scrollHeight) + "px";
    var alertFram = document.createElement("DIV");
    alertFram.id = "alertFram";
    var window_H = window.innerHeight;
    strHtml = txt;
    alertFram.innerHTML = strHtml;
    document.body.appendChild(alertFram);
    document.body.appendChild(shield);
    //动态赋值楼盘个数
    $(".totalLoupan").text(total);
    setTimeout(function() {
            $(alertFram).css({
                'width': "80%",
                'opacity': 0.9,
                'transform': 'scale(1,1)',
                '-webkit-transform': 'scale(1,1)'
            });
    }, 100);
    setTimeout(function() {
        $(alertFram).css({
            'opacity': 0,
            'transform': 'scale(0.5,0.5)',
            '-webkit-transform': 'scale(0.5,0.5)'
        });
    },2000);
    setTimeout(function() {
        $(alertFram).remove();
        $(shield).remove();
    }, 2300);
    document.body.onselectstart = function() {
        return false;
    };
};

var Common = function(){
//  获取cookie方法
    this.getCookie = function (name){
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return unescape(arr[2]);
        }else{
            return null;
        }
    };
//  获取url上参数方法
    this.getUrlParameter = function (name) {
        function GetRequest() {
            var url = location.search; //获取url中"?"符后的字串
            var theRequest = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for(var i = 0; i < strs.length; i ++) {
                    theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
                }
            }
            return theRequest;
        }
        var Request = new Object();
        Request = GetRequest();
        return Request[name];
    };
//  app 跳转到h5 返回键问题
    this.backIndex = function(url,getUrl,type){

        //调用android方法
        function callapp(){
            window.app.closeBrowser();
        }

         var urlForm = '',   //url上参数
             cookieForm = ''; //从cookie上获取的参数
         var isApp = 1; //是否是首页进来的逻辑

         //获取url参数
         urlForm = getUrl;
         //获取cookie
         cookieForm = zgCommon.getCookie(type);
         //判断isApp判断是首页跳转过来还是正常逻辑
         if(cookieForm || urlForm){
             isApp = 1
             $(".pull-left").removeClass("back").addClass("click-back");
         }else{
             isApp = 0
         }
         //设置cookie
         if(urlForm != "null" && !cookieForm){
           document.cookie=type+'='+urlForm;
         }
         //获取cookie
         cookieForm = zgCommon.getCookie(type);
         //h5和android ios交互
         if(isApp){
              if(cookieForm == 'ios'){
                $(".click-back").attr("href","zgzf://closeBrowser");
                  // var Notch = app.hasNotchInScreen();
                  // if(eval(Notch)){
                  //     $(".lp-bar-detail").css("height","3rem");
                  // }
              }else if(cookieForm == 'android'){
                $(".click-back").click(function(){
                    callapp();
                })
              }else{
                $(".click-back").click(function(){
                    window.location.href=url
                })
              }
         }
    };
//  新房列表页搜索
    this.xinfangSearch = function (cityId,cityName) {
          /*var WapCookie = {
              setCookie:function (key, value) {
                  var realKey = '_WAP_' + key;
                  document.cookie= realKey+ " = " + value;
              },
              getCookie:function (key) {
                  var realKey = '_WAP_' + key;
                  return zgCommon.getCookie(realKey);
              }
          };*/
        var lzCookie = {
            /*setCookie:function (key, value) {
                var realKey = '_WAP_' + key;
                $.cookie(realKey, value);
            },*/
            setCookie:function (c_name,value,expiredays) {
                var exdate=new Date();
                exdate.setDate(exdate.getDate()+expiredays);
                document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+";path=/;domain=zhuge.com";
            },
            /*getCookie:function (key) {
                    var realKey = '_WAP_' + key;
                    return $.cookie(realKey);
                }*/
            getCookie:function(c_name) {
                if (document.cookie.length>0)  {
                    var c_start=document.cookie.indexOf(c_name + "=");
                    if (c_start!=-1)  {
                        c_start=c_start + c_name.length+1 ;
                        c_end=document.cookie.indexOf(";",c_start);
                        if (c_end==-1)
                            c_end=document.cookie.length;
                        return unescape(document.cookie.substring(c_start,c_end));
                    }
                }
                return "";
            }
        };
          var house_type = '3',
              city = cityName,
              cityId = cityId,
              urlHost = window.location.host,
              token ='TvSV3iSTmKLwsqQlAiGy0v7kSFfTxzfydLwygKjIvG55DSVuUXaVWVmMmYfb4Em-GrWhJ4iN30-P9A5qLr8PEA%3D%3D';
              var lz_url = window.location.href;
              if(lz_url.indexOf("app_dev.php") > 0){
                  var urlPath = '/app_dev.php/xinfang';//测试和本地需要app_dev.php；
              }else{
                  var urlPath = '/xinfang';//上线时app_dev.php须去掉；
              }
              var searchUrl = '/guesswords';
              var zgConfig = {
                  // 线上接口
                  "postUrlPrefix":seajs.data.vars.apiUrl,
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

              //检测输入框
              txtEvent();
              function txtEvent() {
                  $(window).bind("input propertychange","#search",function () {
                      //自动完成
                      autoSearch();
                  });
              }
              //自动完成
              function autoSearch() {
                  if ($("#search").val() == "") {
                      $("#search-content").hide();
                      return false;
                  }
                  var postData = {
                      "city": city,
                      "word": $("#search").val(),
                      "houseType": '3'
                  }
                  ajaxPost(searchUrl,postData, function (xhr) {
                      if (xhr.data&&xhr.data.length > 0) {
                          $("#search-content").show();
                          var tempHtml = "<span class=\"search-drp-title\">您可能在找<\/span>";
                          var searchUrl = '';
                          xhr = xhr.data;
                          for (var i = 0; i < xhr.length; i++) {
                              if(house_type == '3'){//如果是新房
                                  switch(xhr[i].type_name){

                                      case "新房":
                                          searchUrl = "http://" + urlHost + urlPath + '/detail/' + xhr[i].id + '.html';
                                          break;
                                      case "城区":
                                          searchUrl =  "http://" + urlHost + urlPath + '?#region=' + xhr[i].id;
                                          break;
                                      case "商圈":
                                          searchUrl = "http://"  + urlHost + urlPath + '?#cate_circle=' + xhr[i].id;
                                          break;
                                      case "地铁线":
                                          searchUrl = "http://" + urlHost + urlPath + '?#cate_line=' + xhr[i].id;
                                          break;
                                      case "地铁站":
                                          searchUrl = "http://" + urlHost + urlPath + '?#cate_metro=' + xhr[i].id;
                                          break;
                                      default:
                                          searchUrl = "http://" + urlHost + urlPath + '?#name=' + xhr[i].name;
                                          break;
                                  }
                              }
                              if(house_type == '3'){
                                  tempHtml += "<a hrefs=\"" + searchUrl + "\" allname= "+ xhr[i].name +" class=\"search-drp-item\">" +
                                      "<span class=\"search-drp-name\">" + xhr[i].name + " <\/span>" +
                                      "<span class=\"search-drp-address\"> - " + xhr[i].type_name + "<\/span>" +
                                      "<\/a>";
                              }
                          }
                          $("#autoSearchList").html(tempHtml);
                          //url重定向和cookie存储
                          clickSearch();
                      } else {
                          $("#search-content").hide();
                      }
                  });
              }

              //搜索结果点击
              function clickSearch() {
                  $(window).on("click","#autoSearchList a",function () {
                          var url = $(this).attr("hrefs"),
                              allname = $(this).attr("allname");
                          //添加到搜索历史
                          addHistory(url, allname);
                          window.location.href = url;
                      });
              }

              //添加到搜索历史
              function addHistory(url, allname) {
                  var searchHistoryData = lzCookie.getCookie("searchHistory");
                  if (!searchHistoryData) {
                      searchHistoryData = "{}";
                  }
                  var search_str = JSON.parse(searchHistoryData),
                      tempObj = new Object(),
                      isAdd = true;
                  if (search_str && search_str[cityName] && search_str[cityName][house_type]) {
                      for (var i = 0; i < search_str[cityName][house_type].length; i++) {
                          if (search_str[cityName][house_type][i].value == url) {
                              isAdd = false;
                          }
                      }
                      if (isAdd) {
                          search_str[cityName][house_type].push({
                              "name": allname,
                              "value": url
                          });

                          if (search_str[cityName][house_type].length > 10) {
                              search_str[cityName][house_type].shift();
                          }
                      }
                  } else {
                      if (search_str) {
                          search_str[cityName] = new Object();
                          search_str[cityName][house_type] = [{
                              "name": allname,
                              "value": url
                          }];
                          if(house_type == "1"){
                              search_str[cityName]["2"] = [];
                              search_str[cityName]["3"] = [];
                          }
                          if(house_type == "2"){
                              search_str[cityName]["1"] = [];
                              search_str[cityName]["3"] = [];
                          }
                          if(house_type == "3"){
                              search_str[cityName]["2"] = [];
                              search_str[cityName]["1"] = [];
                          }
                      } else {
                          tempObj[cityName] = new Object();
                          tempObj[cityName][house_type] = [{
                              "name": allname,
                              "value": url
                          }];
                          if(house_type == "1"){
                              tempObj[cityName]["2"] = [];
                              tempObj[cityName]["3"] = [];
                          }
                          if(house_type == "2"){
                              tempObj[cityName]["1"] = [];
                              tempObj[cityName]["3"] = [];
                          }
                          if(house_type == "3"){
                              tempObj[cityName]["2"] = [];
                              tempObj[cityName]["1"] = [];
                          }
                          search_str = tempObj;
                      }
                  }
                  lzCookie.setCookie("searchHistory", JSON.stringify(search_str));
              };

          //清除历史记录
          function clearHistory() {
              $(window).on("click",".clear-jl",function () {
                  var search_str = JSON.parse(lzCookie.getCookie("searchHistory"));
                  search_str[cityName][house_type] = [];
                  lzCookie.setCookie("searchHistory", JSON.stringify(search_str));
                  //读取历史记录
                  readHistory();
              });
          }

          //读取历史记录
        readHistory();
          function readHistory() {
              var search_str = '',
                  tempHtml = "";
              var search_data = lzCookie.getCookie("searchHistory");
              if(!search_data){
                  $(".list-group-history").hide();
                  return;
              }
              search_str = JSON.parse(search_data);
              $(".search-list-box").html("");
              if (search_str) {
                  if (search_str[cityName]) {
                      if (search_str[cityName][house_type]) {
                          if (search_str[cityName][house_type].length > 0) {
                              for (var i = 0; i < search_str[cityName][house_type].length; i++) {
                                  var str = decodeURIComponent(search_str[cityName][house_type][i].name);
                                  tempHtml += "<a href=\"" + search_str[cityName][house_type][i].value + "\">" + str + "<\/a>";
                              }
                              // $("#historyTitle,#historyBox").show();
                              $(".list-group-history").show();
                              $(".search-list-box").html(tempHtml);
                          } else {
                              $(".list-group-history").hide();
                          }
                      }
                  } else {
                      $(".list-group-history").hide();
                  }
              }
          }
          // 清除搜索框内容
          $(window).on("click",".search-qc-box",function () {
             $("#search").val("");
              $("#search-content").hide();
          });

          //读取cookie
          $(".open-popup").click(function () {
              readHistory();
          });

          $(window).on("click",".sousuo-icon",function () {
              var text = $("#search").val();
              if(text == ''){
                  return false;
              }
              var url = "http://" + urlHost + urlPath + '?#name=' + text,
                  allname = text;
              //添加到搜索历史
              addHistory(url, allname);
              window.location.href = url;
          });
          //清除cookie
          clearHistory();
    };
//  判断是否是app端传递过来有关传漾广告 如果 cy = app 则隐藏全部返回按键
    this.cookieCy = function () {
          var cy = ''; //app传递过来的传漾标识cy = app;
          var urlCy = ''; // url上传递的参数
          var cookieCy = ''; // cookie里面获取参数
          urlCy = this.getUrlParameter('cy');
          if(urlCy == 'app'){
              document.cookie = "cy=" + 'app';
              cy = urlCy
          }
          cookieCy = this.getCookie('cy');
          if(cookieCy == "app"){
              cy = cookieCy
          }
          if(cy == "app"){
              // $("header").css("height","0");
              $("header").hide();
              $("header a").hide();
              $("header h1").hide();
          }
    };
//  判断是否是端上进来的资讯详情页(只需要判断url上有没有参数即可)
    this.appZxxq = function () {
        var view = ''; //app传递过来的资讯详情标识 view = app;
        view = this.getUrlParameter('view');
        if(view == "app"){
            $("header").hide();
            $("header a").hide();
            $("header h1").hide();
        }
    };
    //  判断是否是端上进来的房源详情页(只需要判断url上有没有参数即可)
    this.appFyxq = function () {
        var view = ''; //app传递过来的资讯详情标识 view = app;
        view = this.getUrlParameter('view');
        if(view == "app"){
            $('.bar-nav').remove();
        }
    };
//  判断各列表页切换
    this.checkList = function () {
          $('.page-group').on('click',function (e) {
              var title_id = e.target.id;
              var $box = $(".drop_down");
              var $sj = $(".title span");
              if(title_id != 'list_title'){
                  if($sj.hasClass("hover")){
                      $box.hide();
                      $sj.removeClass("hover");
                  }
              }else if(title_id == 'list_title'){
                  $box.toggle();
                  if($box.css("display") == "block"){
                      $sj.addClass("hover");
                  }else if($box.css("display") == "none"){
                      $sj.removeClass("hover");
                  }
              }
          })
    };
//  列表底部下载与关闭
    this.listDownload = function () {
          $("#homeAppLinkFixed").on("click", function() {
              window.location.href = "http://m.zhugefang.com/Wap/Index/downloadAppPage";
          });
          //关闭下载按钮
          $("#closeAppBtnFixed").unbind("click").bind("click", function() {
              $("#homeAppLink").hide();
          })

    };
//  滑动显示列表底部下载框
    this.listSlide = function () {
            //监听滑动
            function scrollList( startY, endY) {
                var dy = startY - endY;
                var result = 0;
                if(dy>0) {//向上滑动
                    result=1;
                }else{//向下滑动
                    result=2;
                }
                return result;
            }
            //滑动处理
            var startY;
            document.getElementsByClassName('content')[0].addEventListener('touchstart',function (ev) {
                startY = ev.touches[0].pageY;
            }, false);
            document.getElementsByClassName('content')[0].addEventListener('touchend',function (ev) {
                var endY;
                endY = ev.changedTouches[0].pageY;
                var direction = scrollList( startY, endY);
                switch(direction) {
                    case 0:
                        break;
                    case 1:
                        // 向上
                        $("#homeAppLink").animate({"bottom":"0rem"});
                        break;
                    case 2:
                        // 向下
                        $("#homeAppLink").animate({"bottom":"-2.5rem"});
                        break;
                }
            }, false);
     };
//  获取二级菜单
    this.getCity = function (cityname,url) {
        var title=$('#list_title').text();
        $.ajax({
            type:'get',
            data:'',
            url:url+'/API/City/getcity',
            success:function (data) {
                var list=data.data;
                var len=list.length;
                for(var i=0;i<len;i++){
                    if(list[i].city==cityname){
                        var open_type=list[i].open_type;
                        var cms=list[i].cms;
                        var cms_id=list[i].cms_id;
                    }
                }
                var open_arr=open_type.split(',');
                var html='';
                var host='http://m.zhuge.com/';
                if(cms) {
                    html += '<li><a class="homePage" href="/' + cityname + '">首页</a></li>';
                }else{
                    html += '<li><a class="homePage" href="http://m.zhuge.test/'+cityname+'">首页</a></li>';
                }
                if(title=='新房'){
                    // if(cms) {
                    //     if ($.inArray('1', open_arr) != -1) {
                    //         html += '<li><a href="/' + cityname + '/house">二手房</a></li>';
                    //     }
                    //     if ($.inArray('2', open_arr) != -1) {
                    //         html += '<li><a href="/' + cityname + '/rent">租房</a></li>';
                    //     }
                    // }else{
                        if($.inArray('1',open_arr)!=-1){
                            html+='<li><a href="'+host+cityname+'/house">二手房</a></li>';
                        }
                        if($.inArray('2',open_arr)!=-1){
                            html+='<li><a href="'+host+cityname+'/rent">租房</a></li>';
                        }
                    // }
                }
                else if(title=='二手房'){
                    if(cms) {
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/'+cityname+'/newhouse">新房</a></li>';
                        }
                        // if ($.inArray('2', open_arr) != -1) {
                        //     html += '<li><a href="/' + cityname + '/rent">租房</a></li>';
                        // }
                        if ($.inArray('2', open_arr) != -1) {
                            html += '<li><a href="'+host + cityname + '/rent">租房</a></li>';
                        }
                    }else{
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/xinfang/?_area='+cms_id+'">新房</a></li>';
                        }
                        if ($.inArray('2', open_arr) != -1) {
                            html += '<li><a href="'+host + cityname + '/rent">租房</a></li>';
                        }
                    }
                }
                else if(title=='租房'){
                    if(cms) {
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/'+cityname+'/newhouse">新房</a></li>';
                        }
                        // if ($.inArray('1', open_arr) != -1) {
                        //     html += '<li><a href="/'+ cityname + '/house">二手房</a></li>';
                        // }
                        if ($.inArray('1', open_arr) != -1) {
                            html += '<li><a href="'+host+ cityname + '/house">二手房</a></li>';
                        }
                    }else{
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/xinfang/?_area='+cms_id+'">新房</a></li>';
                        }
                        if ($.inArray('1', open_arr) != -1) {
                            html += '<li><a href="'+host+ cityname + '/house">二手房</a></li>';
                        }
                    }
                }else{
                    if(cms) {
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/'+cityname+'/newhouse">新房</a></li>';
                        }
                        // if ($.inArray('1', open_arr) != -1) {
                        //     html += '<li><a href="/'+ cityname + '/house">二手房</a></li>';
                        // }
                        // if ($.inArray('2', open_arr) != -1) {
                        //     html += '<li><a href="/' + cityname + '/rent">租房</a></li>';
                        // }
                        if ($.inArray('1', open_arr) != -1) {
                            html += '<li><a href="'+host+ cityname + '/house">二手房</a></li>';
                        }
                        if ($.inArray('2', open_arr) != -1) {
                            html += '<li><a href="'+host + cityname + '/rent">租房</a></li>';
                        }

                    }else{
                        if ($.inArray('3', open_arr) != -1) {
                            html += '<li><a href="/xinfang/?_area='+cms_id+'">新房</a></li>';
                        }
                        if ($.inArray('1', open_arr) != -1) {
                            html += '<li><a href="'+host+ cityname + '/house">二手房</a></li>';
                        }
                        if ($.inArray('2', open_arr) != -1) {
                            html += '<li><a href="'+host + cityname + '/rent">租房</a></li>';
                        }
                    }
                }
                // if(cms) {
                //     html += '<li><a href="/'+ cityname + '/house/community">找小区</a></li>';
                //     html += '<li><a href="/markindex/'+cityname+'">诸葛指数</a></li>';
                //     html += '<li><a href="/'+cityname+'/1/zgsay">诸葛说</a></li>';
                // }else{
                    html+='<li><a href="'+host+cityname + '/house/community">找小区</a></li>';
                    html += '<li><a href="'+host+'markindex/'+cityname+'">诸葛指数</a></li>';
                    html += '<li><a href="'+host+cityname+'/1/zgsay">诸葛说</a></li>';
                // }
                $('.drop_down').html(html)
            },
            error:function (data) {

            }
        })
    };
//  全网比价查看更多弹窗
    this.sourceAlert = function () {
            $(document).on('click','.viewSource',function () {
                $("#source").show();
                // $("body").addClass("fix");
                var channel =  $(this).attr('channel');
                var source_url = $(this).attr('data');
                $(".header .channel").html(channel);
                $(".content .content_url").html(source_url);
            });
            $(".close").click(function () {
                $("#source").hide();
                $("body").removeClass("fix");
            });
    };
//  全网比价数据展示
    this.priceData = function ($box,loupanId,num,$boxParent,$totalNum,url) {
        $.ajax({
            url: url + '/api/front/house/globe/compare',
            type: 'get',
            data: {
                id: loupanId
            },
            success: function (data) {
                var html = '';
                var list = data.data;
                var len = list.length;
                if($totalNum != ''){
                    //共有多少家平台销售
                    $totalNum.text(len);
                }
                if (len > 0) {
                    if(num != ''){
                        len = num
                    }
                    for (var i = 0; i < len; i++) {
                        var price = list[i].developer_offer;
                        price = price || '待定';
                        html += '<div class="whole_price">'+
                            '<div class="whole_left">'+
                            '<p class="whole_title">' + list[i].sourcename +'</p>'+
                            '<p class="line">'+
                            '<i class="w_price">' + price + '</i>'+
                            '<i class="w_mess">' + list[i].developer_offer_expiry + '</i>'+
                            '</p>'+
                            '</div>'+
                            '<a class="whole_right viewSource" href="javascript:;" data="' + list[i].source_url + '" channel=' + list[i].sourcename + '>查看来源</a>'+
                            '</div>'
                    }
                    $box.html(html).show();
                    if(num != ''){
                        $boxParent.show();
                    }
                } else {
                    $box.html('暂无数据').css('text-align', 'center');
                }
            }
        })
    };
//  用户协议与免责声明
    this.agreement = function (box,parameter) {
        $(window).on("click",box,function () {
            var host= window.location.host;
            var url = " http://"+ host +"/houses/" + parameter;
            $(box).attr("href",url);
        });

    }

};
var zgCommon = new Common();
