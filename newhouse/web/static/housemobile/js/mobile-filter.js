'use strict';

/*!
 * @name mobile-filter
 * @author author
 * @date 2017-10-13 11:35:31
 * @description 手机检索功能
 */
// @require('babel');
define(['utils'], function (require, exports, module) {
    var utils = require('utils');

    var filter = {
        // 处理检索条件事件
        filterInit: function filterInit(opt) {
            var _self = this;
            var $modal;
            // tabBar的标记
            var $buttonsTabShadow;

            $(document).off('.filter').on('click.filter', opt.el, function (e) {
                var $this = $(this);
                var index;
                // tabBar
                var $buttonsTab = $this.closest('.buttons-tab');

                if (!$this.closest('.modal').length) {
                    // 原来的位置给一个标记
                    $buttonsTabShadow = $('<div class="buttons-tab buttons-tab-ex"></div>').insertAfter($buttonsTab);
                    // 处理弹窗
                    $modal = $($buttonsTab.data('target'))
                    // tab追加进来
                    .prepend($buttonsTab).attr({
                        'data-tag': 'modal-ex',
                        'data-condition': 1
                    }).addClass('modal modal-ex')
                    // 设置弹窗的高度
                    .height($(window).height() * .8)
                    // 移到到body下面
                    .appendTo('body');
                }
                // 处理重复显示
                if (!$modal.hasClass('modal-in')) {
                    $.openModal($modal[0]);
                }
                // 当前是激活，就关闭
                if ($this.hasClass('active')) {
                    $.closeModal($modal[0]);
                    return false;
                }

                index = $this.index() || 0;
                // tab
                $this.addClass('active').siblings().removeClass('active');
                // tab内容
                $modal.find('.tab').removeClass('active').eq(index).addClass('active');
                // app 链接替换
                /*if (typeof urlCallback == 'function') {
                    urlCallback();
                };*/

                // 重置内容
                $modal.off('close').on('close', function () {
                    $buttonsTab.find('.active').removeClass('active');
                    $buttonsTabShadow.replaceWith($buttonsTab);
                });
                return false;
            })
            // 点击遮罩层关闭弹窗
            .on('click.filter', '.modal-overlay-modal-ex, .modal-overlay-select-modal', function (e) {
                $.closeModal();
                return false;
            });

            // 初始化
            _self.filterCondition();
            _self.tabCon();
            //_self.setUrl();

        },

        // 弹窗后里面的内容的操作
        tabCon: function tabCon() {
            var _self = this;
            $(document).off('.condition')
            // 点击条件后立即跳转
            .on('click.condition', '[data-condition-item]', function (e) {
                if($(this).hasClass('stopsearch')){
                    return false;
                }
                _self.conditionItemSet(this, function () {

                    _self.setUrl();
                    // 关闭弹层
                    $.closeModal();
                    $("#areaTag,#rentTag,#houseTypeTag,#moreTag").removeClass("hover");
                    $("#screenBox,#rentList,#houseTypeList,#areaList,#moreList").hide();
                    $('#popLayer,#sortList').hide();
                    // 刷新列表
                    _self.refresh();
                });
                // 禁止冒泡
                return false;
            })
            // 点击条件弹窗里的提交按钮
            .on('click.condition', '[data-condition-submit]', function () {
                /**
                 * <a href="#" data-condition-submit="{
                        sendAjax: true,
                        container: '',
                        type: 'reset'
                 * }"></a>
                 *
                 */
                var _btnthis = $(this);
                var _data = $.extend(true, {}, {
                    sendAjax: true,
                    container: '',
                    type: ''
                }, utils.parseJSON(_btnthis.data('conditionSubmit')));
                // 按钮状态
                // _btnthis.text(_data.title || 'loading...');
                if (_data.type == 'reset') {
                    var $container = $(_data.container);
                    // input
                    $container.find('input[type="radio"]').prop('checked', false);
                    $container.find('.default input[type="radio"]').prop('checked', true);
                    // tab 标题
                    $container.find('.tab-link1 .tit').text(function (i, oldTit) {
                        // 不存在时返回原来的
                        return $(this).data('tit') || oldTit;
                    });
                    // 需要全局处理
                    $('.tab-link1.selected').removeClass('selected');
                    $('.tab-link1 .num').text('');
                }
                // 不需要发送请求
                if (!_data.sendAjax) {
                    return false;
                }
                $("#areaTag,#rentTag,#houseTypeTag,#moreTag").removeClass("hover");
                $("#screenBox,#rentList,#houseTypeList,#areaList,#moreList").hide();
                $('#popLayer,#sortList').hide();
                // 关闭弹层
                $.closeModal();

                _self.setUrl();

                // 刷新列表
                _self.refresh();
                return false;
            });
        },

        // 建个通用方法，方便扩展
        refresh: function refresh() {
            var _self = this;
            if ($('body').data('refresh')) {
                // 扩展一个接口，目前用于地图刷新
                $('body').trigger('refresh');
            } else {
                require.async('loadmore', function (loadmore) {
                     // 执行顺序乱了，做个延迟
                    setTimeout(function () {
                        loadmore.refresh();
                    }, 0);
                });
            }
        },

        // 条件设置
        conditionItemSet: function conditionItemSet(el, cb) {
            var $this = $(el);
            var $input = $this.find('input[type="radio"]');
            // 处理默认点击
            $input.prop('checked', true);
            // 判断是否是更多条件的点击
            var $conditionMore = $this.closest('[data-condition-more]');
            if ($conditionMore.length) {
                var num = $conditionMore.find('input:checked').not(function () {
                    return !this.value;
                }).length;
                if (num) {
                    $('[data-field="more"]').addClass('selected').find('.num').text('(' + num + ')');
                } else {
                    $('[data-field="more"]').removeClass('selected').find('.num').text('');
                }
                return false;
            }
            // 改变tab和标题
            var $tab = $('[data-field="' + $input[0].name + '"]');
            if ($tab.length) {
                // 保持所有
                var keep = $tab.hasClass('keep');
                var $tit = $tab.find('.tit');
                // 保存原来的tit
                !$tit.data('tit') && $tit.data('tit', $tit.text());

                if ($input.val()) {
                    !keep && $tit.text($this.find('.item-title').text());
                    $tab.addClass('selected');
                } else {
                    // 点击不限制
                    !keep && $tit.text($tit.data('tit'));
                    $tab.removeClass('selected');
                }
            }
            cb && cb.call();
        },

        // 设置url
        setUrl: function setUrl(container) {
            var _self = this;
            var container = container || '[data-condition="1"]';
            var oParams = $(container).find('input:checked').not(function () {
                return !this.value;
            }).serializeObject();
            var hash = $.param(oParams)
            // hash = '?' + hash.substring(1);
            location.hash = hash;
            _self.createTags(oParams)
        },
        //生成因子
        createTags: function createTags(o){
            console.log(o,'0000000')
            var region = "region",
                cate_circle = "cate_circle",
                cate_line = "cate_line",
                cate_metro = "cate_metro",
                order = "order";
            if(region in o && cate_circle in o){
                delete o.region
            }
            if(cate_line in o && cate_metro in o){
                delete o.cate_line
            }
            if(order in o){
                delete o.order
            }
            var _self = this;
             if($.isEmptyObject(o)){
                 $('.selected-tags').hide();
             }else{
                 $('.selected-tags').show();
             };

            var tagsWrapper = $('.selected-tags');
            var htmlStr = '';
            var hash = $.param(o);
            var repHash = '&'+ hash;
            for(var key in o){
                console.log(key,'key')
                var url = repHash.replace('&'+key+'='+ escape(o[key]),'');
                if(key == 'region' && o['cate_circle']){//如果是城区，并且商圈存在，所拼接城区的url上同时去除城区和商圈的key,vaule
                    url = url.replace('&cate_circle=' + escape(o['cate_circle']), '');
                }
                if(key == 'cate_line' && o['cate_metro']){
                    url = url.replace('&cate_metro=' + escape(o['cate_metro']), '');
                }
                url = url.substring(1);
                if(url){
                    url = 'http:\/\/' + window.location.hostname + window.location.pathname +'#'+ url;
                }else{
                    url = 'http:\/\/' + window.location.hostname + window.location.pathname ;
                }
                if(key == 'region' || key == 'cate_line') {
                    var insertStr = $('.levelTow li[type="' + key + '"] .item-inner').find('.hover').text();
                    if (insertStr && insertStr != '不限') { //list-tags
                        htmlStr += '<a hrefs="' + url + '"  class="list-tags p-default item-content" name="' + key + '"  value=' + o[key] + '><span> ' + insertStr + '</span><i></i></a>'
                    }
                }else if(key == 'name'){
                    console.log('-------name------')
                    var url = 'http:\/\/' + window.location.hostname + window.location.pathname;
                    var insertStr = o[key];
                    htmlStr += '<a hrefs="'+ url +'"  class="list-tags p-default item-content" name="' + key + '"  value=' + o[key] + '><span> '+ insertStr +'</span><i></i></a>'
                }else{
                    if(key == 'dj'){
                        var danjia = o[key];
                        var djArr = danjia.split('|');
                        var djArr1 = djArr[1].split(',');
                        if(djArr[0] == 'lt'){
                            var insertStr = djArr1[0]/10000 + '万以下';
                        }else if(djArr[0] == 'gt'){
                            var insertStr = djArr1[0]/10000 + '万以上';
                        }else{
                            var insertStr = djArr1[0]/10000 + '-' + djArr1[1]/10000 + '万';
                        }
                        htmlStr += '<a hrefs="'+ url +'"  class="list-tags p-default item-content" name="' + key + '"  value=' + o[key] + '><span> '+ insertStr +'</span><i></i></a>'
                    }else{
                        var tags = $('input[name = "'+ key +'"]').filter('input[type="radio"]:checked');
                        var insertStr = tags.siblings('.item-inner').find('.item-title').text();
                        if(insertStr && insertStr != '不限'){
                            htmlStr += '<a hrefs="'+ url +'"  class="list-tags p-default item-content" name="' + key + '"  value=' + o[key] + '><span> '+ insertStr +'</span><i></i></a>'
                        }
                    }

                }

            }
            tagsWrapper.html(htmlStr);
            //获取hrefs重定向
            $(".selected-tags a").each(function () {
               $(this).click(function () {
                   var url = $(this).attr('hrefs');
                   window.location.href = url;
                   var len = $(".selected-tags a").length;
                   if(len != 1){
                       location.reload();
                   }
               })
            });
            //筛选完成回到顶部
            $(".content")[0].scrollTop = "0";
        },

        /*
         * @name 筛选条件初始化
         * @param  {bealean} first 是否为页面初始加载
         */
        filterCondition: function filterCondition() {
            var _self = this;
            var obj  = {};
            var regionId;
            var cate_circleId;
            var cate_lineId;
            var cate_metroId;
            // 全部检索数据
            $.each(utils.getUrlHash(), function (k, v) {
                obj[k] = v;
                if(k == 'region'){
                    regionId = v;
                }
                if(k == 'cate_circle'){
                    cate_circleId = v;
                }
                if(k == 'cate_line'){
                    cate_lineId = v;
                }
                if(k == 'cate_metro'){
                    cate_metroId = v;
                }
                // 找到父级
                var conditionItem = $('[name="' + k + '"][value="' + v + '"]').closest('[data-condition-item="1"]')[0];
                //_self.setUrl();
                conditionItem && _self.conditionItemSet(conditionItem);
            });
            $('#itemList li').removeClass('hover');
            // 判断是否为地铁筛选
            if(cate_lineId || cate_metroId){
                $('#subwayItem').addClass('hover');
                $('#areaTag').html('地铁');
                $('#subwayItem').trigger('click');
                // 同时存在站点和线路
                if(cate_lineId && cate_metroId){
                    $('.levelTow li').find('.item-title').removeClass('hover');
                    $(".leveltow-cate_line-"+cate_lineId).find('.item-title').addClass('hover');
                    $('.leveltow-cate_line-'+cate_lineId).trigger('click');
                    $('.levelThree li').find('.item-title').removeClass('hover');
                    $(".levelthree-cate_metro-"+cate_metroId).find('.item-title').addClass('hover');
                }else{
                    // 只有线路的时候
                    if(cate_lineId){
                        $('.levelTow li').find('.item-title').removeClass('hover');
                        $(".leveltow-cate_line-"+cate_lineId).find('.item-title').addClass('hover');
                    }
                    // 只有站点的时候
                    if(cate_metroId){
                        var pid =$(".levelthree-cate_metro-"+cate_metroId).attr('pid');
                        $('.levelThree li').find('.item-title').removeClass('hover');
                        $(".levelthree-cate_metro-"+cate_metroId).find('.item-title').addClass('hover');
                        $('.levelTow li').find('.item-title').removeClass('hover');
                        $(".leveltow-cate_line-"+pid).find('.item-title').addClass('hover');
                        $('.leveltow-cate_line-'+pid).trigger('click');
                    }
                }

            }else{
                // 默认走区域筛选项
                $('#areaItem').addClass('hover');
                $('#areaTag').html('区域');
                $('#areaItem').trigger('click');
                // 同时存在城区和商圈
                if(regionId && cate_circleId){
                    $('.levelTow li').find('.item-title').removeClass('hover');
                    $(".leveltow-region-"+regionId).find('.item-title').addClass('hover');
                    $('.leveltow-region-'+regionId).trigger('click');
                    $('.levelThree li').find('.item-title').removeClass('hover');
                    $(".levelthree-cate_circle-"+cate_circleId).find('.item-title').addClass('hover');
                }else{
                    // 只存在城区时
                    if(regionId){
                        $('.levelTow li').find('.item-title').removeClass('hover');
                        $(".leveltow-region-"+regionId).find('.item-title').addClass('hover');
                    }
                    // 只存在商圈时
                    if(cate_circleId){
                        var pid =$(".levelthree-cate_circle-"+cate_circleId).attr('pid');
                        $('.levelThree li').find('.item-title').removeClass('hover');
                        $(".levelthree-cate_circle-"+cate_circleId).find('.item-title').addClass('hover');
                        $('.levelTow li').find('.item-title').removeClass('hover');
                        $(".leveltow-region-"+pid).find('.item-title').addClass('hover');
                        $('.leveltow-region-'+pid).trigger('click');
                    }
                }
            }
            _self.createTags(obj);

        }
    };
    module.exports = filter;
});
//# sourceMappingURL=http://localhost:8888/public/js/mobile-filter.js.map
