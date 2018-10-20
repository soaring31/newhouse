/*!
 * @name {{name}}
 * @author author
 * @date {{now()|date('Y-m-d')}}
 */
// pc配置
var seajsCfg = {
        'jquery'         : 'core/js/vendor/dialog/lib/jquery-1.10.2',
        'common'         : 'core/js/common',
        // 地图
        'mapSimple'      : 'core/js/map-simple',
        // 点赞投票
        'vote'           : 'core/js/vote',
        // 收藏
        'collect'        : 'core/js/collect',
        'markdown'       : 'core/js/static/marked',
        // 加载更多
        'loadmore'       : 'core/js/loadmore',
        // 百度分享扩展
        'share'          : 'core/js/share',
        // 评论
        'comment'        : 'core/js/comment',
        // 表单交互
        'inter'          : 'core/js/inter',
        // 编辑器的插件
        'ueditorPlugin'  : 'core/js/ueditor-plugin',
        // 拖拽改变大小
        'dragResize'     : 'core/js/static/dragResize',
        'vue'            : 'core/js/static/vue.min',
        // 上传
        'webuploader'    : 'core/js/static/webuploader',
        'webupload'      : 'core/js/webupload',
        'webuploadCss'   : 'core/css/webupload.css',
        // 拖拽排序
        'sortable'       : 'core/js/static/jquery.sortable',
        // 模拟下拉列表
        'cxselect'       : 'core/js/static/jquery.cxselect',
        // 表单验证
        'validate'       : 'core/js/static/jquery.validate.min',
        // 列表页ajax
        'listPjax'       : 'core/js/list-pjax',
        'utils'          : 'core/js/utils',
        'listajax'       : 'core/js/list-ajax',
        
        'dialog'         : 'core/js/vendor/dialog/src/dialog-plus',
        'dialogc'        : 'core/js/vendor/dialog/src/dialog-config',
        //核心JS
        'core'           : 'core/js/core',
        'BMap'           : 'https://api.map.baidu.com/getscript?v=2.0&ak=LOi401eDYhqoYajLOFm45EMQFzio8U9N&services=&t=' + parseInt(new Date().getTime()/1000),
        'BMapDraw'       : 'http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min',
        'BMapDrawCss'    : 'http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css',
        'baidumap'       : 'core/js/baidumap',
        'mapLibs'        : 'core/js/map-libs',
        'mapComplex'     : 'core/js/map-complex',
        'pager'          : 'core/js/pager',
        'ueditor'        : 'core/js/vendor/ueditor/ueditor.all',
        'ueditor.conf'   : 'core/js/vendor/ueditor/ueditor.config',
        'chart'          : 'core/js/vendor/myChart/echarts-plain',
        //拼音转换JS    
        'pinyin'         : 'core/js/pinyin.js',
        'codemirror'     : 'core/js/vendor/codemirror/codemirror',
        'croppercss'     : 'core/js/vendor/cropper/cropper.css',
        // 路由
        'director'       : 'core/js/static/director',
        // 下拉菜单
        'dropdown'       : 'core/js/static/dropdown',
        // 分页
        'pagination'     : 'core/js/static/jquery.pagination',
        // 模板
        'template'       : 'core/js/static/template',
        // QQ表情
        'qqFace'         : 'core/js/static/jquery.qqFace',
        'cookie'         : 'core/js/static/jq.cookie',
        // fixed 固定元素
        'fixed'          : 'core/js/static/jquery-scrolltofixed',
        // 图库
        'gallery'        : 'core/js/static/gallery',
        'lightgallery'   : 'core/js/static/lightgallery',
        'lgthumb'        : 'core/js/static/lg-thumbnail',//幻灯片缩略图
        // 数据图形
        'charts'         : 'core/js/static/highcharts',
        // 数据图形2
        'chartmobile'    : 'core/js/static/chart.min',
        // 输入框提示
        'placeholder'    : 'core/js/static/jquery-placeholder',
        // 自动完成
        'autocomplete'   : 'core/js/static/jquery.autocomplete',
        // 时间拾取
        'daterangepicker': 'core/js/static/daterangepicker',
        // 时间格式化
        'moment'         : 'core/js/static/moment.min',
        // 二维码生成js
        'qrcode'         : 'core/js/static/jquery-qrcode',
        // 图片懒加载
        'lazyload'       : 'core/js/static/lazyload',
        // switch 按钮
        'switch'         : 'core/js/static/bootstrap-switch.min',
        // 弹层插件
        'layer'          : 'core/js/static/layer',
        'jqduang'        : 'core/js/plugin/jqduang',
        // 弹窗
        'jqmodal'        : 'core/js/plugin/jqmodal',
        'jqhoverS'       : 'core/js/plugin/jqhoverslider',
        // 滚动高亮当前
        'jqscrollspy'    : 'core/js/plugin/jqscrollspy',
        
        // 按钮插件
        'icheck'         : 'core/js/plugin/icheck.min',
        
        // ajax缓存
        'ajaxCache'      : 'core/js/plugin/jquery-ajax-localstorage-cache',
        
        // 下拉框搜索
        'chosen'         : 'core/js/plugin/chosen.jquery',
        
        // 说明文档
        '08cmsDoc'       : 'core/js/08cmsDoc',
        'manage'         : 'core/js/manage',
        'pop'            : 'core/js/pop',
        // 收藏
        // 'collect'     : 'core/js/collect',
        // 点评
        'jqraty'         : 'core/js/static/jquery.raty',
        // 统计
        // 'getcounts'   : 'core/js/getcounts',
        // 投票
        // 'vote'        : 'core/js/vote',
        // 下拉刷新
        // 'loadmore'    : 'core/js/loadmore',
        // 选项卡式刷新
        'tabLoadmore'    : 'core/js/tabloadmore',
        // 最近浏览
        'browserRecord'  : 'core/js/browserRecord',
        // 背投广告
        'advertise'      : 'core/js/advertise',
        'filter'         : 'core/js/filter',
        'ajaxbind1'      : 'core/js/ajaxbind1',
        // 颜色采集
        'jscolor'        : 'core/js/vendor/cart/jscolor.js',
        // 从manage移过来的
        'jplayer'        : 'core/js/vendor/audioplayer/inc/jquery.jplayer.min',
        'miniPlayer'     : 'core/js/vendor/audioplayer/inc/jquery.mb.miniPlayer',
        'miniPlayer-plus': 'core/js/vendor/audioplayer/inc/jquery.mb.miniPlayer-plus',
        'miniplayer.css' : 'core/js/vendor/audioplayer/css/miniplayer.css',
        'javascript'     : 'core/js/vendor/codemirror/javascript',
        'php'            : 'core/js/vendor/codemirror/php',
        'clike'          : 'core/js/vendor/codemirror/clike',
        'codemirrorcss'  : 'core/js/vendor/codemirror/codemirror.css',
        'slide'          : 'core/js/vendor/slide',
        'formvalidator'  : 'core/js/formvalidator',
        'coco2dx'        : 'core/js/vendor/cocos2d-js-v3.13/cocos2d-js-v3.13',
        'comm'           : 'core/js/comm',
        'main'           : 'core/js/main',
        'manage_main'    : 'manage/js/main',
        // 下面是比较特殊的，源文件在house里
        'searchxq'       : 'house/js/searchxq',
        'shapan'         : 'house/js/shapan',
        'ckplayer'       : 'core/js/ckplayer/ckplayer'
    };

seajs.config({
    map: [
        ['.js', '.js?v=' + (typeof VERSION != 'undefined' ? VERSION : '8.1')]
    ],
    base: jsbase,
    alias: seajsCfg,
    preload: ['jquery'],  // 预加载项
    charset: 'UTF-8'        // 文件编码
});
