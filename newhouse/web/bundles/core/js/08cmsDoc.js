/*!
 * @name <%= name %>
 * @author ahuing
 * @date <%= date %>
 */

/**
 * @module 08cmsDoc
 * @description 前台推送位广告位说明
 * @param {object} option 配置参数
 * @param {String} tag doc的标识
 * @example
 * ### 设置
 * ```html
 * <div class="ad" data-tag="ad-1,ad-2,ad-3"></div>
 * ```
 * ### 调用
 * ```html
 * 您的网址?debug=1
 * ```
 * ### 说明信息
 * - 预览 地址?debug=1
 * - 默认是调用推送位的标题，如果不是推送位可手动编辑说明信息，目录如下：
 * `\site\HouseBundle\Resources\views\Cmsdoc\index.html.twig`
 * 
 */
define(function (require, exports, module) {
    var $tags = $('[data-tag]');
    var aTags = [];
    // 加入查询标识
    $tags.each(function(i, el) {
        var aTag = $(this).data('tag').split(',');
        $.merge(aTags, aTag);
        $.unique(aTags);
    });
    
    var a = $.ajax({
        url     : (BASE_URL + '/cmsdoc').replace(/\/+cmsdoc/, '/cmsdoc'),
        type    : 'GET',
        data: {
            tags: aTags.join(',')
        },
        dataType: 'json'
    })
    .done(function(d) {
        // 遮罩层
        var $body = $('body');
        $('<div title="双击关闭遮罩层"></div>')
            .css({
                position       : 'absolute',
                left           : 0,
                right          : 0,
                top            : 0,
                backgroundColor: '#000',
                opacity        : .3,
                zIndex         : 999,
                height         : $body.height()
            })
            .appendTo('body')
            .dblclick(function() {
                $(this).remove();
            });
        // 标签
        $tags.each(function(i, el) {
            var $this = $(this);  
            var tag = $this.data('tag');

            if (tag) {
                var aTag      = tag.split(',');
                var aPushName = [];
                var isPush;
                var menu;
                // 标识
                var pushEname;

                $.each(aTag, function(i, v) {
                    if (d[v]) {
                         aPushName.push(v + '-' + d[v].name)
                         isPush = d[v].type == 'pushs';
                         pushEname = v;
                         menu = d[v].menu;
                    };
                });

                $this.css({
                        boxShadow: '0 0 1px #f00, 0 0 1px #f00, 0 0 1px #f00, 0 0 1px #f00, 0 0 1px #f00',
                        position: 'relative',
                    })

                $('<span>', {
                        text: aPushName.join(' + '),
                        title: aPushName.join(' + ')
                    })
                    .css({
                        position       : 'absolute',
                        backgroundColor: '#f00',
                        left           : 0,
                        right          : 0,
                        top            : 0,
                        color          : '#fff',
                        zIndex         : 99999,
                        font           : '12px/1.5 simsun',
                        padding        : '0 30px 0 5px',
                        overflow       : 'hidden',
                        height         : 18
                    })
                    .appendTo(this);
                    
                var aHash = [
                    '#/wangzhanyingxiao/tuisongguanli1',
                    '#/wangzhanyingxiao/guanggaoguanli2',
                    '#/wangzhanyingxiao/mnavsmanage'
                ]
                // 是推送位时，加编辑按钮
                if (isPush) {
                    $('<a>', {
                            href: (BASE_URL + '/main').replace(/\/(\/main)/, '$1') + aHash[menu - 1] + '/' + pushEname,
                            text: '编辑',
                            target: '_blank'
                        })
                        .css({
                            position: 'absolute',
                            right   : 0,
                            top     : 0,
                            color   : '#fff',
                            zIndex  : 99999,
                            font    : '12px/1.5 simsun',
                            margin  : 0,
                            border  : 0,
                            width   : 'auto',
                            height  : 'auto',
                            padding : '0 5px'
                        })
                        .appendTo(this)
                };
            };
        });
    })
    .fail(function() {
        console.warn("服务器或数据格式错误！");
    })
    .always(function() {
        // console.log("complete");
    });

});

//# sourceMappingURL=08cmsDoc.js.map
