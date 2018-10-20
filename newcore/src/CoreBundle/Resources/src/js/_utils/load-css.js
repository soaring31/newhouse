/**
 * loadCSS
 * @description 加载css到head标签内
 * @param  {Object} config 配置对象
 * @param  {String} config.content css文本内容
 * @param  {String} config.url css的链接
 * @example
 * ```js
 * $.loadCSS({content: 'a {color: #f00;}'})
 * ```
 * ```js
 * $.loadCSS({url: 'http:a.com/a.css'})
 * ```
 */
$.loadCSS = function(config) {
    var head = document.getElementsByTagName("head")[0];

    if (config.content) {
        var style  = document.createElement('style');
        style.type = 'text/css';
        
        if (style.styleSheet) { // for IE
            style.styleSheet.cssText = config.content;
        } else {
            style.innerHTML = config.content.replace(/jsbase/g, jsbase);
        }

        head.appendChild(style);
        // callback();
    }
    else if (config.url) {
        var link  = document.createElement('link');

        link.href = config.url;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        head.appendChild(link);
    }
};