'use strict';

/*!
 * @name <%= name %>
 * @author ahuing
 * @date <%= date %>
 */
// @require('babel');
define('inter', ['jqmodal'], function (require, exports, module) {
    // var logincom = require('logincom');
    // 倒计时
    var countDown = function countDown(senconds, $el, tpl) {
        var $btnTxt = $el.find('.code-btn-text');
        if (senconds > 1) {
            $btnTxt.html(tpl ? tpl.replace('%s%', senconds--) : '(' + senconds-- + ')秒后重新发送');
            $el.addClass('disabled');
            setTimeout(function () {
                countDown(senconds, $el, tpl);
            }, 1000);
        } else {
            $btnTxt.html($el.data('title') || '短信验证码');
            $el.removeClass('disabled');
        }
    };
    // 接口
    var app = {
        // 发送短信及邮件
        dynamicCode: function dynamicCode(selector) {
            var $this = $(selector);
            $this.off('click').on('click', function () {
                var thisData = $this.data();
                var role = thisData.code;
                var codeVal = $('#' + thisData[role]).val();
                var params = thisData.params || {};

                if (role == 'tel') {
                    params.tel = codeVal;
                } else if (role == 'mail') {
                    params.mail = codeVal;
                }
                if (!codeVal) {
                    if (role == 'tel') {
                        $.jqModal.tip('手机号码不能为空', 'info');
                    } else if (role == 'mail') {
                        $.jqModal.tip('邮箱不能为空', 'info');
                    }
                    $('#' + thisData.role).focus();
                    return false;
                }

                if (!params.checked) {
                    $.jqModal.tip('模板已关闭或不存在', 'info');
                    return false;
                };

                if ($this.hasClass('disabled')) {
                    return false;
                };

                if (!$this.hasClass('requesting')) {
                    $this.addClass('requesting');

                    $.ajax({
                        type: 'POST',
                        url: thisData.src,
                        data: params,
                        dataType: 'json'
                    }).done(function (result) {
                        if (result.status) {
                            countDown(60, $this);
                        } else {
                            // 判断当前环境是前台还是后台
                            if (typeof web !== 'undefined' && web == 'frontEnd') {

                                $.jqModal.tip(result.info || 'error', 'error');
                            }
                        }
                    }).fail(function (result) {
                        $.jqModal.tip(result.info || 'error', 'error');
                    }).always(function () {
                        // console.log("complete");
                        $this.removeClass('requesting');
                    });
                }
            });
        }
    };

    $(document).off('.view'
    // 回车提交
    ).on('keypress.view', '.view-form input[type="text"], [data-view-form] input[type="text"]', function (e) {
        // console.log(111);
        // if (e.keyCode == 13) $(this.form).find('[data-role="submit"]').click();
    }
    // 提交表单
    ).on('click.view', '.view-form [data-role="submit"], [data-view-form] [data-role="submit"]', function (e) {
        var $form = $(this).closest('form');

        // 启用表单验证才去验证
        if ($form.data('validator')) {
            if ($form.valid()) {
                submitFn(e);
            } else {
                $form.data('validator').focusInvalid();
            }
        } else {
            submitFn(e);
        }

        e.stopPropagation();
        return false;
    });

    function submitFn(e) {
        var $this = $(e.currentTarget),
            $form = $this.closest('form'),
            data = {},
            eldata = $this.data();

        // 重置csrf_token，有时表单调用的是缓存，csrf_token失效
        $form.find('#csrf_token').val(csrf_token);

        if ($form.length) {
            data = $form.find('input, select, textarea'
            // 去掉多余的空格
            ).val(function (i, v) {
                return $.trim(v);
            }).serializeArray();
            eldata.type = 'POST';
        }

        if (!$this.hasClass('requesting')) {
            $this.addClass('requesting');
            var oldTitle = $this.html();
            $this.html(eldata.ajaxTitle || '提交中...');
            // tip
            eldata.tip = eldata.tip == 0 ? 0 : 1;
            $.ajax({
                url: eldata.url || $form.attr('action'),
                data: data,
                type: eldata.type, // || 'GET',
                dataType: 'json'
            }).done(function (result) {
                if (result.status) {
                    eldata.tip && $.jqModal.tip(result.info, 'success');
                    var jumpurl = result.url || eldata.jumpurl || $form.data('jumpurl');
                    if (jumpurl) {
                        $this.html('跳转中...');
                        location.href = jumpurl;
                    } else {
                        $form[0].reset();
                        $this.html(oldTitle);
                    }
                    $form.trigger('done', [result]);
                } else {
                    // 如果需要登录跳转到登录页面
                    if (typeof result.nologin != 'undefined' && result.nologin == 0) {
                        // 右上角登录按钮的链接
                        location.href = $('[data-modal=".winlogin"]').attr('href');
                    } else {
                        $.jqModal.tip(result.info, 'info');
                        $this.html(oldTitle);
                    }
                }
                // 重置验证码
                $form.find(".code-img").click();
            }).fail(function () {
                $this.html(oldTitle);
            }).always(function () {
                $this.removeClass('requesting');
            });
        }
    }
    // 使用按钮可用
    $('.btn[disabled="disabled"]').prop('disabled', '');

    module.exports = app;
});
//# sourceMappingURL=inter.js.map
