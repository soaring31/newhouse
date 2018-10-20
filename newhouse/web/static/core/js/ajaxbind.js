/**!
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月29日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module) {
    var self;
    (function(out){
        module.exports=out;
    }) ({
        //调用函数
        /*
         * 调用函数
         * @page 函数名， par参数名
         * @return function
         */
        init:function(page){
            //初始化加載的腳本
            self = this;
            self[page]();
        },
        ajaxBind:function() {
            var change = function(){
                var that = this;
                    var $cfg        = $(that).closest('.form-cell').find('.cfg');
                    // 一级
                    var opt         =  $.extend(true, {ajaxLevel: ''}, $cfg.data());
                    // 级别
                    var limitlevel  = $cfg.attr('limitlevel');
                    // 下级配置
                    var srcTit      = $cfg.attr('src-title');
                    var srcRequired = $cfg.attr('src-required');
                    var srcName     = $cfg.attr('src-name');
                    // 当前索引，从1开始
                    var ind = $(that).attr('ind');
                    var thisVal = $(that).val();
                    // var need = 0;
                    // 不同分类的联动，第二级就直接返回，没有第三级
                    if (srcName && ind > 1) {
                        // 表单验证
                        $(that).attr({
                                'data-msg-required'  : srcTit + '不能为空',
                                'required'           : srcRequired || '',
                                'errorlabelcontainer': '.error-wrap'
                            })
                        return;
                    }
                    //清除已有的子选项
                    $(that).nextAll('select').remove();

                    var level = $(that).find('option:selected').attr('data-ajax-level');

                    $(that).closest('form').find('#' + opt.ajaxLevel).val(++level);

                    // 到级别限制 或者 没有选择，值为空时
                    if ((limitlevel && ind >= limitlevel) || !thisVal) {
                        return;
                    }
                    //卸载事件,防止重复点击
                    $(that).prop('disabled', true);
                    $.ajax({
                        url: opt.ajaxurl,
                        data: {
                            pid : thisVal,
                            csrf: opt.ajaxparam
                        },
                        dataType: 'json',
                        tip: 0
                    })
                    .done(function(data) {
                        if (data.status && data.data.length) {
                            var aOpts = ['<option value="" selected="selected">' + (srcTit || '不限') + '</option>'];

                            $.each(data.data, function(ii, vo) {
                                aOpts.push('<option data-ajax-level="' + vo.level + '" value="' + vo.id + '">' + vo.name + '</option>');
                            })

                            var $sel = $('<select>', {
                                'data-type': 'ajaxbind',
                                'ind'    : ++ind,
                                'name'     : srcName || $cfg.attr('name')
                            }).append(aOpts.join(''))
                            $(that).after($sel);
                        }
                    })
                    .always(function() {
                        $(that).prop('disabled', false);
                    });
            };
            $('body')
                .off('.ajaxbind')
                .on('change.ajaxbind', '[data-type="ajaxbind"]', change)
                .on('click.ajaxbind', '[data-type="changebind"]', function() {
                    var $this = $(this);
                    $this.parent().find("[data-type='ajaxbind']").eq(0).show();
                    $this.prevAll('.tit-value, .tit').andSelf().off().remove();
                    return false;
                });

            // 初始化时执行一次    
            var $select = $('[data-type="ajaxbind"]').not('.inited, .hide');
            $select.each(function(index, el) {
                var $this = $(this);
                // 是否是第一个select
                var isFirst = !$this.prev('[data-type="ajaxbind"]').length;
                var isNext = !$this.next('[data-type="ajaxbind"]').length;
                if (this.value && isFirst && isNext) {
                    $this.addClass('inited');
                    change.call(this)
                }
            });
        }
    });
});


//# sourceMappingURL=http://localhost:8888/public/js/ajaxbind.js.map
