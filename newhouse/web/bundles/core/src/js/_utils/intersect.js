/*!
 * @name {{ name }} - jquery 扩展
 * @author {{ author }}
 * @date {{ date }}
 */

 $.extend({
 	/**
 	 * @name intersect
 	 * @description 取两个数组的交集
 	 * @param  {Array} a 要处理的数组1
 	 * @param  {Array} b 要处理的数组2
 	 * @return {Array}   处理后的数组
 	 * @example
 	 * ```js
 	 * $.intersect([1,2,3], [2,3,4]) // [2,3]
 	 * ```
 	 */
     intersect: function(a, b) {
         var flip = {},
             res = [];
         for (var i = 0; i < b.length; i++) flip[b[i]] = i;
         for (i = 0; i < a.length; i++)
             if (flip[a[i]] != undefined) res.push(a[i]);
         return res;
     }
 })