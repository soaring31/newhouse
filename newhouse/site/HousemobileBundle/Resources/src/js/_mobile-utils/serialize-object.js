/*!
 * @name {{ name }} - jquery 扩展
 * @author {{ author }}
 * @date {{ date }}
 */
// require-intersect,attrSearch;
 $.fn.serializeObject = function(name, value) {
    var o = {};    
    $.each(this, function(i, el) {
         var $this = $(this);
         var k = typeof name != 'undefined'? $this.data(name) : $this.data('name') || this.name ,
            // 去掉前后空格
             v = $.trim(typeof value != 'undefined'? $this.data(value): $this.data('value') || this.value) ;
         if (!v) return;

         if (o[k] && o[k].indexOf('in|') === 0) {
             var a = o[k].slice(3).split(','),
                 b = v.slice(3).split(',');
             v = 'in|' + $.intersect(a, b).join(',')
         }
         o[k] = v || '';
    });    
    return o;    
 };
