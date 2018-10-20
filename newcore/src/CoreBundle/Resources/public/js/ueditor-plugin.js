'use strict';

/*!
 * @name ueditor-plugin
 * @author Ahuing
 * @date 2017-10-09
 */
// @require('babel');
/**
 * @module ueditor-plugin
 * @description 百度编辑器自定义扩展
 */
define(['ueditor', 'jqmodal', 'template'], function (require, exports, module) {
    var template = require('template');

    var app = {
        tpls: {
            // 按钮模板
            btnTpl: '\n                <span class="<%= cls %> btn-custom" <%=# url ? \'data-url="\' + url + \'" data-role="show"\' : \'\' %> <%=# attr %> title="<%= title %>">\n                    <i class="<%= icon %>"></i><%= title %>\n                </span>\n            ',
            // 分页弹窗模板
            pageModalTpl: '\n                <div id="page-modal" class="p10">\n                    <form class="form">\n                        <% for (var i = 0; i < list.length; i++) { %>\n                            <div class="p5">\n                                \u7B2C<%= i + 1 %>\u9875\u6807\u9898\uFF1A<input class="txt" type="text" value="<%= list[i] %>"/>\n                            </div>\n                        <% } %>\n                    </form>\n                </div>\n            '
        },
        init: function init() {},
        /**
         * @method addBtn 
         * @description 工具栏上增加一个按钮
         * @param {String} name 按钮名称 `pageManage`-分页管理按钮 `pageInsert`-插入分页按钮 `btnShow`-show按钮
         * @param {Object} opt 按钮的配置，具体看按钮的方法
         */
        addBtn: function addBtn(name, opt) {
            var _self = this;
            // 分页
            var $toolbar = $(opt.ueWrap).find('.edui-toolbar');
            var $ueBar = $toolbar.find('.ue-bar');

            if (!$ueBar.length) {
                $ueBar = $('<div class="ue-bar"></div>').appendTo($toolbar);
            }

            if (_self[name]) {
                _self[name](opt).appendTo($ueBar);
            } else {
                $.jqModal.tip('没有' + name + '按钮');
            }
        },
        /**
         * @method pageInsert
         * @description 创建一个管理分页的按钮
         * @param {Object} opt 按钮配置
         * @param {String} opt.ueWrap 编辑器的外框
         * @param {String} opt.attr 按钮的属性
         * @param {String} opt.cls 按钮的class
         * @param {String} opt.title 按钮的标题
         * @return {Object} 按钮本身，jquery对象
         */
        pageManage: function pageManage(opt) {
            var _self = this;
            // 设置
            var _opt = $.extend({
                title: '分页设置'
            }, opt);

            var editor = _opt.editor;

            var $pageManageBtn = $(template.compile(_self.tpls.btnTpl)(_opt));

            $pageManageBtn.off().on('click', function (event) {
                var reg = /<p>([\s\\S]*?)#p#(.*?)#e#([\s\\S]*?)<\/p>/g;
                var ueContent = editor.getContent();
                var aPages = ueContent.match(reg);
                // 转化后的分页
                if (!aPages) {
                    $.jqModal.tip('没有分页！');
                    return;
                }
                var aPages1 = $.map(aPages, function (v, i) {
                    ueContent = ueContent.replace(v, '<!--08_REPLACE_PAGE_' + i + '-->');

                    return v.replace(/<\/*p>|#p#|#e#/g, '');
                });

                var $target = $.jqModal.modal({
                    type: 'html',
                    content: template.compile(_self.tpls.pageModalTpl)({ list: aPages1 }),
                    animate: null,
                    head: '分页管理',
                    css: {
                        width: 500
                    },
                    foot: '<button class="btn-accept btn-accept111">确定</button>\
                                <button data-close="1">取消</button>'
                });

                var acceptFn = function acceptFn(ev) {
                    var newUeContent = ueContent;
                    var bInputsValid = 1;
                    // 替换处理
                    $target.find('input').each(function (i, el) {
                        if (!this.value) {
                            bInputsValid = 0;
                            return false;
                        } else {
                            var newVal = '<p>#p#' + this.value + '#e#</p>';
                            newUeContent = newUeContent.replace('<!--08_REPLACE_PAGE_' + i + '-->', newVal);
                        }
                    });
                    // 验证通过
                    if (bInputsValid) {
                        // 设回内容
                        newUeContent && editor.setContent(newUeContent);
                        $target.jqModal('hide');
                    } else {
                        $.jqModal.tip('分页不能为空！');
                    }
                };
                $(document).off('.pageManage').on('click.pageManage', '.btn-accept111', acceptFn);

                return false;
            });

            return $pageManageBtn;
        },
        /**
         * @method pageInsert
         * @description 创建一个插入分页的按钮
         * @param {Object} opt 按钮配置
         * @param {String} opt.ueWrap 编辑器的外框
         * @param {String} opt.attr 按钮的属性
         * @param {String} opt.cls 按钮的class
         * @param {String} opt.title 按钮的标题
         * @return {Object} 按钮本身，jquery对象
         */
        pageInsert: function pageInsert(opt) {
            var _self = this;
            // 设置
            var _opt = $.extend({
                title: '插入分页'
            }, opt);

            var editor = _opt.editor;
            var $pageInsertBtn = $(template.compile(_self.tpls.btnTpl)(_opt));

            $pageInsertBtn.off().on('click', function () {
                editor.execCommand('insertHtml', "<p>#p#分页标题#e#</p>");
            });

            return $pageInsertBtn;
        },
        /**
         * @method btnShow
         * @description 创建一个按钮
         * @param {Object} opt 按钮配置
         * @param {String} opt.ueWrap 编辑器的外框
         * @param {String} opt.attr 按钮的属性
         * @param {String} opt.cls 按钮的class
         * @param {String} opt.title 按钮的标题
         * @return {Object} 按钮本身，jquery对象
         */
        btnShow: function btnShow(opt) {
            var _self = this;
            // 设置
            var _opt = $.extend({
                title: '插入楼盘',
                url: ''
            }, opt);

            return $(template.compile(_self.tpls.btnTpl)(_opt));
        }

        // 导出
    };module.exports = app;
});
//# sourceMappingURL=ueditor-plugin.js.map
