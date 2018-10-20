/*!
 * @name {{ name }} - jquery 扩展
 * @author {{ author }}
 * @date {{ date }}
 */
// require-hasEvent,toggler;
/**
 * 搜索属性
 * @module attrSearch(reg)
 * @param  {string} reg 表达式
 * 
 * @example  示例
 * ```html
 * <a href="" title=""></a><span title=""></span>
 * ```
 * ```js
 * $(':attrSearch("href^")'); // <a href=""></a>
 * $(':attrSearch("title^")'); // <a href="" title=""></a><span title=""></span>
 * ```
 */
 $.extend($.expr[':'], {
        attrSearch: function(el, i, reg) {
            var _reg = reg[3];
            var aReg = _reg.match(/[A-Za-z]+|\^$|\$$|\*$/g);
            var oAttributes = el.attributes;
            var n = 0;

            if (!oAttributes || aReg.length <= 1) return false;
            if (aReg[1] == '^') {
                aReg.reverse();
            };
            var r = new RegExp(aReg.join(''));

            $.each(oAttributes, function(ii, val) {
                var valName = val.localName;
                if (valName.search(r) > -1) {
                    n = 1;
                    return true;
                }
            });

            return n;
        }
    })