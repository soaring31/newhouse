
define(['webcookie','commonFn'],function(require, exports, module){
			// 获取token
			var token = WebCookie.getCookie('token');
			if(!token){
				token = 'q9xyyD-372dNuKap_H_QL_mg6NFroX9bOgSzZlPIzuJ9rP1wucmJbnrKgHjWuDQxt8ramylw6rptAjxj16Ck4g%3D%3D';
			}
			//解析url参数
			var zgConfig = {
				// 线上接口
				"postUrlPrefix1":'http://api.zhugefang.com/API/',
				"postUrlPrefix2": 'http://newhouse.zhuge.com/api/v1',
				// 测试接口
				// "postUrlPrefix":'http://api.zhugefang.test/API/',
				//"postUrlPrefix2" : 'http://cms.zhuge.com/app_dev.php/api/v1',
				"platformType":"2",
				"appName":"zgzf",
				"token":token
			};
			// 所有接口都需要传递的参数，优先从url中取，取不到时取cookie中的
			var commonParams = [
				['token','token',true],
				['user_type','user_type',true],
				['spread','spread',true],
				['pageEntrance','pageEntrance',true]
			];
			// 存储URL上所需的参数
			for (var i in commonParams) {
				if (commonParams[i][2]) {
					var value = getUrlParam(commonParams[i][0]);
					if(value){
						WebCookie.setCookie(commonParams[i][1],value);
					}
					if (!value) {
						value = WebCookie.getCookie(commonParams[i][0]);
						if(WebCookie.getCookie(commonParams[i][0]) && WebCookie.getCookie(commonParams[i][0]) == ' '){
							value = '';
						}
					}
					if (value) {
						if (commonParams[i].length > 1) {
							zgConfig[commonParams[i][1]] = value;
						}
						else {
							zgConfig[commonParams[i][0]] = value;
						}
					}
				}else if(zgConfig[commonParams[i][1]] == ' '){
					zgConfig[commonParams[i][1]] = '';
				}
			}
			post:function post(url, data, callback,type,method) {
				var appName = data.appName ? data.appName : zgConfig.appName;
				var platformType = data.platformType ? data.platformType : zgConfig.platformType;
				var token = data.token ? data.token : zgConfig.token;
				var postUrl = zgConfig.postUrlPrefix1;
				var method  = method ? method : 'post'
				if(type == '2'){
					postUrl = zgConfig.postUrlPrefix2;
				}
				data.appName = appName;
				data.platformType = platformType;
				data.token = token;
				$.ajax({
					"url":postUrl + url,
					"type":method,
					"data":data,
					success:function (xhr) {
						if ($.isFunction(callback)) {
							callback(xhr);
						}
					}
				});
			}
		    exports.post = post;
})
