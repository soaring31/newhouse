/*!
 * @name {{name}}
 * @author Ahuing
 * @date {{now()|date('Y-m-d H:i:s')}}
 * @description ajax多级联动
 */
/**
 * @require('babel') 
 */
define('{{name}}', ['utils', 'cxselect'], function(require, exports, module) {
    var utils = require('utils');
    var app = {
        init(opt) {
            var _self = this;
            var _opt = _self.opt = $.extend(true, {
                el      : null,
                dataUrl : null,
                data    : {},
                subData : {},
                cfg     : {},
                subCfg  : {},
                type    : 1, //  1 - 多级联动 2 - ajax联动, 3 - 单个select, 只用于同一分类的情况
                cxSelect: {
                    emptyStyle: 'none',
                    jsonName  : 'name', // 数据标题字段名称
                    jsonValue : 'id', // 数据值字段名称
                    jsonSub   : 'children'
                }
            }, opt);
            // 整个入口由type决定
            if (_opt.type == 1) {
                var newData = [];
                // select配置
                var aSelectOpt = [];
                var limitlevel;
                // 存放默认值
                var aValues = [];

                if ($.isEmptyObject(_opt.subCfg)) {
                // 同分类
                    var tree = utils.parseTree(_opt.data, _opt.cfg.pid);
                    // 限制几级
                    limitlevel = _opt.cfg.limitlevel || tree.level;
                    // 编辑
                    if (_opt.cfg.value) {
                        aValues = utils.getTreePids(_opt.data, _opt.cfg.value).reverse();
                    }
                    aSelectOpt = [_opt.cfg];
                    newData = tree.data;
                } else {
                // 不同分类
                    limitlevel = 2;
                    $.each(_opt.data, function(i, item) {
                        var aSubitem = [];
                        $.each(_opt.subData, function(ii, subitem) {
                            // console.log(item.id + '', (subitem.pid + '').split(','));
                            // 小坑split必须是字符串
                            if ($.inArray(item.id + '', (subitem.pid + '').split(',')) > -1) {
                                aSubitem.push(subitem);
                            }
                        })
                        item.children = aSubitem;      
                    })
                    aSelectOpt = [_opt.cfg, _opt.subCfg];
                    newData = _opt.data;
                }

                var oSelects = _self.createSelects(aSelectOpt, aValues, limitlevel);
                // 生成select
                $(_opt.el)
                    .append(oSelects.html)
                    .cxSelect($.extend(true, _opt.cxSelect, {
                                    selects: oSelects.aClass,
                                    data: newData
                                }));
                        
            } else if (_opt.type == 2) {
                // console.log(_opt);
                var oSelects = _self.createSelects([_opt.cfg], _opt.cfg.values, _opt.cfg.limitlevel);
                // 生成select
                $(_opt.el)
                    .append(oSelects.html)
                    .cxSelect($.extend(true, _opt.cxSelect, {
                                    selects: oSelects.aClass,
                                    data: _opt.data
                                }));
            } else if (_opt.type == 3) {
                var tree = utils.parseTree(_opt.data, _opt.cfg.pid);
                var getOption = function(data, level) {
                    var option = [];
                    var level = level || 0;
                    // 生成空格
                    var getSpace = function(num) {
                        var space = [];
                        for (var i = 0; i < num; i++) {
                            space.push('&nbsp;');
                        }
                        return space.join('');
                    };
                    // 一级加粗
                    var optionStyle = level ? '' : ' style="font-weight: bold;" ';
                    level++;

                    $.each(data, function(i, item) {
                        option.push(`<option class="level-${level}" ${optionStyle} value="${item.id}">
                                        ${getSpace((level - 1) * 3)}${item.name}
                                    </option>`)
                        if (item.children.length) {
                             option = option.concat(getOption(item.children, level))
                        }
                    })

                    return option.join('');
                }
                var sOption = getOption(tree.data);

                $(_opt.el)
                    .append(`<select class="select-${_opt.cfg.name}" name="${_opt.cfg.name}" value="${_opt.cfg.value}">${sOption}</select>`)
            }
        },
        createSelects(aSelectOpt, aValues, limitlevel) {
            var _opt = this.opt;
            var selectOpt = {};
            var selectTpl = `<select class="{class}" name="{name}" data-value="{value}" {dataUrl}>
                            </select>`;
            // 存放select的class
            var sClass;    
            var aClass = [];
            var aSelect = [];
            var dataUrl = _opt.type == 2 ? 
                            `data-url="${_opt.dataUrl}" data-query-name="${_opt.cfg.field}"`: 
                            '';

            for (var i = 0; i < limitlevel; i++) {
                sClass = 'select-'+ i;
                selectOpt = aSelectOpt[i] || aSelectOpt[0];
                aClass.push(sClass)

                aSelect.push(utils.template(selectTpl, {
                    dataUrl: i > 0 ? dataUrl : '',
                    name   : selectOpt.name,
                    value  : aValues[i] || selectOpt.value || '',
                    class  : sClass
                }))
            }

            return {
                html: aSelect.join(''),
                aClass: aClass
            };
        }
    };

    module.exports = app;
});
