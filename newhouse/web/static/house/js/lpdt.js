seajs.use([],function () {
    function insetData(results) {
        console.log('3333')
        var innerEle = $('#lpdtCont');
        //var results = getData();
        var innerhtml = yieldHtml(results);
        innerEle.html(innerhtml);
    }
    function yieldHtml(results) {
        console.log(results,9009)
        console.log('6666')
        var htmlstr = '';
        var liststr = '';
        htmlstr += '<div class="section">';
        htmlstr += '<div class="dynamic_box">';
        if(results&&results.data&&results.data.list&&results.data.list.length>0){
            htmlstr += '<ul class="dynamic_list" id="dynamic_list">';
            for(var i in results.data.list){
                liststr += '<li key='+ i +'>'+
                    '<div class="dynamic">'+
                        '<p class="time">'+ results.data.list[i].building_time + '</p>'+
                        '<p class="dynamic_p">'+results.data.list[i].building_title+'</p>'+
                        '<p class="dynamic_dsc">'+results.data.list[i].building_content +
                        '<a href="" class="col-ff8400">[详情]</a>' +
                        '</p>' +
                    '</div>' +
                 '</li>';
             }
             console.log(liststr,'liststr')
             htmlstr+=liststr;
             htmlstr+='</ul>';
            if(results.data&&results.data.list&&results.data.list.length>=30){
                htmlstr += '<div class="more_dynamic" id="more_dynamic">查看更多动态</div>';
            }
            htmlstr+='</div></div>';
        }
        return htmlstr;
    }
    function getData() {
        console.log('22222')
        var postUrlPrefix = 'http://api.zhugefang.com/API/';
        var url = postUrlPrefix + 'NewHouseBorough/get_activity_information';
        var results = null;
        //楼盘动态
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "complex_id": '8815',
                "city": 'bj',
                "start":"1",
                "end":"10",
                "appName":'zgzf',
                "platformType":'2',
                "token":'aiVCm6MGerWXR6ltFB5REINbbqicLQ7a_nRFqXY-zs0uQtA8TjY9iFaXODJb_4CfNFwA0SQi958qG1JLINUHUw3D3D'
            }
        })
        .done(function(result) {
            results = result;
            console.log(results,'listdata')
            insetData(results);
        })
        .fail(function() {
            //$.jqModal.tip('无数据!','info');
        });
        //return listData;
    }
    getData();
});