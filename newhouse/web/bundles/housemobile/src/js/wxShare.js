define('wxShare', ['http://res.wx.qq.com/open/js/jweixin-1.0.0.js'], function(require, exports, module){
    require('http://res.wx.qq.com/open/js/jweixin-1.0.0.js');
/*  var type = navigator.userAgent.toLowerCase();
    var isWeixin = type.indexOf('micromessenger') != -1;
    if(!isWeixin){
        alert('请在微信客户端打开链接');
        return false;
    } */

    var app = {
        init: function(wxConfig, opt){
            var _self = this;
            var _opt = _self.opt = $.extend({},  {
                    title: '',    // 分享标题
                    link: '',    // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: '',  // 分享图标
                    desc: '',    // 分享描述
                    type: 'link',    // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function(){},
                    cancel: function(){}
            }, opt);


            var _wxConfig = $.extend(true, {}, {
                            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                            appId: '', // 必填，公众号的唯一标识
                            timestamp: '', // 必填，生成签名的时间戳
                            nonceStr: '', // 必填，生成签名的随机串
                            signature: '',// 必填，签名，见附录1
                            jsApiList: [
                                'onMenuShareTimeline',
                                'onMenuShareAppMessage',
                                'onMenuShareQQ',
                                'onMenuShareWeibo',
                                'onMenuShareQZone'
                            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                        }, wxConfig);

            wx.config(_wxConfig);

            
            wx.ready(function(){
                    wx.onMenuShareTimeline(_opt); //分享到朋友圈
                    wx.onMenuShareAppMessage(_opt); //分享给朋友
                    wx.onMenuShareQQ(_opt);
                    wx.onMenuShareWeibo(_opt);
                    wx.onMenuShareQZone(_opt);
            });

            wx.error(function(res){
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                console.log(res);
            });


        }
    };

    module.exports = app;
});