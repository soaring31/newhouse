/*!
 * @name {{name}}
 * @author ahuing
 * @date {{ now()|date('Y-m-d H:i:s') }}
 */

define('{{name}}', ['validate'], function(require, exports, moudle) {
    // 在后台时未需要刷新整个页面
    if (typeof loginfag != 'undefined') {
        location.reload()
    }
    // 
    $('.checks').on('click', 'label', function() {
        $(this).addClass('active').siblings('label').removeClass('active');
    }).find('label').eq(0).click();
    // 第一个文本框选中
    $('.view-form').find('.text').eq(0).select();

    // login
    var $loginWrap = $('[data-login-wrap]');
    // 表单验证
    var validator = $loginWrap.find('form').validate();

    $loginWrap
        .off('.login')
        .on('click.login', '.login-head li', function() {
            var $this = $(this);
            var type = $this.data('type');
            /*if ($this.hasClass('act')) {
                return;
            }*/
            $this.addClass('act').siblings('li').removeClass('act');
            $('[name="type"][value="'+ type +'"]').prop('checked', true);
            // console.log(validator);
            if (type == 0) {
            // 普通登录
                $('.vcode').hide().find('input').prop('disabled', true);
                $('.codeText, .passWord').show().find('input').prop('disabled', false);
                $('#userName')
                    .attr({
                        'placeholder': '用户名/邮箱/手机号码'
                    })
                    .data({
                        'msgRequired': '用户名不能为空'
                    })
            } else {
            // 手机动态登录
                $('.vcode').show().find('input').prop('disabled', false);
                $('.codeText, .passWord').hide().find('input').prop('disabled', true);
                $('#userName')
                    .attr({
                        'placeholder': '手机号码',
                        'value': ''
                    })
                    .data({
                        'msgRequired': '手机号码不能为空'
                    });
            }
            // console.log(validator.errorList, validator.errorMap);

            // 重置表单验证
            // console.log(validator.customDataMessage());
            validator.resetForm();
        })
        .find('li').eq(0).click();

});