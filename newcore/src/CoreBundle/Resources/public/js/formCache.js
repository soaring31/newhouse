'use strict';

define('formCache', ['utils'], function (require, exports, module) {
    $.loadCSS({ content: ".form-load{margin-right:5px;width:20px;height:20px;vertical-align:-4px;display:inline-block;background:url(data:image/gif;base64,R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=) no-repeat}" });

    // @require('babel');
    var utils = require('utils');
    /**
     * @method init
     * @type {Object} formConfig 总配置
     * @type {String} formConfig.type=click 数据加载类型 `click` 点击时加载 `load` 页面加载完直接加载
     * @type {Object} formConfig.ajax ajax配置，与jquery一致
     * @type {Boolean} formConfig.ajax.data.formcls 样式 `common-form-lg`-大表单 `common-form-label`-显示labal `common-form-control`-100%显示
     * @type {Boolean} formConfig.ajax.data.formvalidstyle 验证信息显示位置
     * @type {Boolean} formConfig.ajax.data.formjumpurl 提交成功后的跳转
     * @type {String} formConfig.form.formflag 表单名称
     *
     * ```html
     * <div data-form-config="{
     *                    type: 'load',
     *                    ajax: {
     *                        url: '{{('bundlebindforms/ajaxform')|U}}',
     *                        data : {
     *                            formflag: 'view_showingsnews',
     *                            topic: '{{index_kft0.fromid|default("")}}',
     *                            formcls: 'common-form-lg'
     *                        }
     *                    }
     *                }"></div>
     *``` 
     *
     */
    var app = {
        init: function init(opt) {
            var _self = this;
            // 保存默认配置
            _self.defCfg = opt;
            // 直接加载
            var $form = $('[data-form-config]');
            $form.each(function (i, el) {
                var $el = $(this);
                var config = _self.config = utils.parseJSON($el.data('form-config'));
                if (config.type == 'load') {
                    _self.ajax($el);
                }
            });
            // 点击加载
            $(document).off('.formModal').on('click.formModal', '.btn-jqModal-ex', function (e) {
                var $modal = $form.filter($(this).attr('href'));
                var config = _self.config = utils.parseJSON($modal.data('form-config'));
                // 弹窗提示
                $modal.html('<div style="text-align: center;padding: 20px"><i class="form-load"></i>玩命加载中...</div>').jqModal(config.modal || {});
                _self.ajax($modal).always(function () {
                    // console.log("complete");
                    // 重新定位
                    $modal.jqModal('setPos');
                    $(e.target).trigger('shown');
                });
                return false;
            });
        },
        ajax: function ajax($el) {
            var _self = this;
            var config = _self.config;
            var ajaxOpt = $.extend(true, {}, {
                type: 'GET',
                dataType: 'html'
            }, _self.defCfg.ajax, config.ajax);
            return $.ajax(ajaxOpt).done(function (data) {
                $el.html(data);
            }).fail(function () {
                $el.html('服务器错误！');
                // console.log("error");
            });
        }
    };
    module.exports = app;
});