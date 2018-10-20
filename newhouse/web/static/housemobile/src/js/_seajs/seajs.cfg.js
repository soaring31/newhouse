/*!
 * @name {{name}}
 * @author author
 * @date {{now()|date('Y-m-d')}}
 */

var seajsCfg = {
    'jquery'        : 'housemobile/js/static/zepto.min',
    'common'        : 'housemobile/js/mobile-common',
    'mapLibs'       : 'housemobile/js/mobile-map-libs',
    'mapComplex'    : 'housemobile/js/mobile-map-complex',
    'vote'          : 'housemobile/js/mobile-vote',
    'collect'       : 'housemobile/js/mobile-collect',
    'loadmore'      : 'housemobile/js/mobile-loadmore',
    'comment'       : 'housemobile/js/mobile-comment',
    'filter'        : 'housemobile/js/mobile-filter',
    'search'        : 'housemobile/js/mobile-search',
    'utils'         : 'housemobile/js/mobile-utils',
    // 移动端ajax
    'ajax'          : 'housemobile/js/mobile-ajax',
    // 微信分享
    'wxShare'       : 'housemobile/js/wxShare',
    'BMap'          : 'https://api.map.baidu.com/getscript?v=2.0&ak=LOi401eDYhqoYajLOFm45EMQFzio8U9N&services=&t=' + parseInt(new Date().getTime()/1000),
    // 模板
    'template'      : 'housemobile/js/static/template',
    // 数据图形2
    'chartmobile'   : 'housemobile/js/static/chart.min',
    // 移动端
    'sm'            : 'housemobile/js/sm',
    // 加载touch
    'touch'         : 'housemobile/js/static/touch',
    'share'         : 'housemobile/js/shareUrl',
    // zepto的animate插件
    'fx'            : 'housemobile/js/static/fx',
    // app
    'appajax'       : 'housemobile/js/appajax',
    'appimage'      : 'housemobile/js/appimage',
    'approot'       : 'housemobile/js/approot',
    'mUpfile'       : 'housemobile/js/mobileUpfile',
    // 格式化时间
    'formattime'    : 'housemobile/js/formattime',
    // 贷款计算器
    'toolgjj'       : 'housemobile/js/gjjloan2',
    'tooljsq'       : 'housemobile/js/jsq2',
    'toolrate'      : 'housemobile/js/rate',
    // 购房工具
    'calculator'    : 'housemobile/js/calculator',
    // 移动端
    'mobilezst'     : 'housemobile/js/mobilezst',
    'autocomplete'  : 'housemobile/js/static/zepto.autocomplete'
    };

seajs.config({
    map: [
        ['.js', '.js?2018-05-15']
    ],
    base: jsbase,
    alias: seajsCfg,
    preload: ['jquery'],  // 预加载项
    charset: 'UTF-8'        // 文件编码
});
