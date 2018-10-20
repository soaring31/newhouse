/**
 * @module jq.cookie
 * @description cookie操作，获取、存储、删除
 * @param {string} name cookie 名称
 * @param {string} value cookie 的值
 * @param {object} options cookie 其它配置
 * @param {number} options.expires cookie 时间设置 单位：秒
 * @param {string} options.path cookie 路径
 * @param {string} options.domain cookie 域名
 * @param {boolean} options.secure cookie 当设置为true时，表示创建的 Cookie 会被以安全的形式向服务器传输，也就是只能在 HTTPS 连接中被浏览器传递到服务器端进行会话验证，如果是 HTTP 连接则不会传递该信息，所以不会被窃取到Cookie 的具体内容
 * @example
	```js
$.cookie('the_cookie'); // 获得cookie
$.cookie('the_cookie', 'the_value'); // 设置cookie
$.cookie('the_cookie', 'the_value', { expires: 7 }); //设置带时间的cookie，单位：秒
$.cookie('the_cookie', '', { expires: -1 }); // 删除
$.cookie('the_cookie', null); // 删除 cookie
$.cookie('the_cookie', 'the_value', {expires: 7, path: '/', domain: 'jquery.com', secure: true});//新建一个cookie 包括有效期 路径 域名等
	```
 */

jQuery.cookie = function(name, value, options) {
	if (typeof value != 'undefined') {
		options = options || {};
		if (value === null) {
			value = '';
			options.expires = -1
		}
		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
			var date;
			if (typeof options.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 1000))
			} else {
				date = options.expires
			}
			expires = '; expires=' + date.toUTCString()
		}
		var path = options.path ? '; path=' + options.path : '';
		var domain = options.domain ? '; domain=' + options.domain : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('')
	} else {
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]);
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break
				}
			}
		}
		return cookieValue
	}
};