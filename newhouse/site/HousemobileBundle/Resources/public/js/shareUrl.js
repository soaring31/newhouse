/*!
 * Created share.
 * User: xying
 * Date: 16-07-28
 * Time:
 * To use share.
 */
define(['sm'], function(require, exports, module) {

	var share = {
		/* 分享到QQ空间
		*参数说明：title标题，summary摘要，pic小图片，url分享要链接到的地址
		*	@name: shareToQzone
		*	@param: title {string}  标题
		*		    pic  {string}   图片
		*		    url  {string}   地址
		*/
		shareToQzone: function(title, summary, pic, url) {
			var p = {
                url: url,
                showcount: '1',
                /*是否显示分享总数,显示：'1'，不显示：'0' */
                desc: '',
                /*默认分享理由(可选)*/
                summary: summary,
                /*分享摘要(可选)*/
                title: title,
                /*分享标题(可选)*/
                site: '',
                /*分享来源 如：腾讯网(可选)*/
                pics: pic,
                /*分享图片的路径(可选)*/
                style: '203',
                width: 98,
                height: 22
            };
            var s = [];
            for (var i in p) {
                s.push(i + '=' + encodeURIComponent(p[i] || ''));
            }
            var _u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?' + s.join('&');
            w = window.screen.width,
            window.location.href = _u;
		},
		/*分享到新浪
		*@name: shareToSina
		*@param: articleTitle {string}  标题
		*	    pic  {string}   图片
		*	    articleURL  {string}   地址
		*/
		shareToSina: function(articleTitle, pic, articleURL) {
			var url = "http://v.t.sina.com.cn/share/share.php",
                _url = articleURL,
                _title = articleTitle,
                _appkey = '',
                _ralateUid = '',
                c = '',
                pic = pic;
            w = window.screen.width,
                h = window.screen.height;
            c = url + "?url=" + encodeURIComponent(_url) + "&appkey=" + _appkey + "&title=" + _title + "&pic=" + pic + "&ralateUid=" + _ralateUid + "&language=";
            window.location.href = c;
		},
		/*分享到QQ好友
		*@name: shareToQQ
		*@param: articleTitle {string}  标题
		*	    articleImg  {string}   图片
		*	    articleURL  {string}   地址
		*/
		shareToQQ: function(articleTitle, articleImg, articleURL) {
			var c = "http://connect.qq.com/widget/shareqq/index.html?url=" + encodeURIComponent(articleURL) + "&showcount=0&desc=&summary=&title=" + encodeURIComponent(articleTitle) + "&pics=" + encodeURIComponent(articleImg) + "&style=203&width=19&height=22";
			window.open(c, "shareQQ", "height=485, width=720,top=200,left=200,toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=yes,status=no");
		},
		/*
			调用说明： 
		 */
		init: function () {
			var _this = this;
			$(document)
				.off('.share')
				.on('click.share', '.share-actions', function () {
					var $this = $(this);
					var _title = $this.data('text'),
						_pic = $this.data('pic'),
						_url = $this.data('url'),
						_abstract = $this.data('abstract');

			        var buttons1 = [
			            {
			                text: '请选择',
			                label: true
			            },
			            {
			                text: '分享到QQ空间',
			                bold: true,
			                color: 'danger',
			                onClick: function() {
			                    _this.shareToQzone(_title, _abstract, _pic, _url);
			                }
			            },
			            {
			                text: '分享到新浪微博',
			                onClick: function() {
			                    _this.shareToSina(_title, _pic, _url);
			                }
			            }
			            ];
			            var buttons2 = [
			            {
			                text: '取消',
			                bg: 'danger'
			            }
			        ];
			        var groups = [buttons1, buttons2];
			        $.actions(groups);
			    });
		}
	};

	share.init();

	module.exports=share;
});
//# sourceMappingURL=shareUrl.js.map
