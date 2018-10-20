/**
 * author : ling
 * date   : 2016-12-23
 * name   : tabLoadmore
 * modify : 2016-12-23
 */

/**
 * @module tabLoadmore
 * @description 选项卡式刷新
 * @param {String} listUrl  请求的列表地址
 * @param {String} list jq选择器 外层列表
 * @param {String} moreBtn jq选择器 加载更多的按钮
 * @param {String} moreinfo jq选择器 加载更多的提示
 * @param {Object} loadgif jq选择器 加载时候的动画 (class名不要为loading,已经被占用)
 * @param {Object} load 加载的参数
 * @example 调用
 * ```html
 * <a  data-more-init="1" data-role="ajax" data-pre-page-index="1" data-page-index="1" data-config="{cate_id: '{{item.id|default('')}}'}" href="{{('news/list')|U({category: urlCategory})}}">最新</a>
 * 
 * ```
 * ```js
 *tabLoadmore.init({
 *            load: {pageSize: 10},
 *            list: '.newslist-container',
 *            listUrl: '{{("clist/newslist")|U({category: urlCategory})}}',
 *            moreBtn: '.click-look-more',
 *            moreinfo: '.nodata',
 *            loadgif: '.ilv-loading'
 *          });
 * ```
 * 
 * @说明信息
 * data-pre-page-index 是保存前一个的页数
 * data-page-index 是保存初始化时的页数
 * data-config 是加载更多时的参数
 * 
 * 
 */

define(['utils'], function(require, exports, module){
	var utils = require('utils');

	var config = {};
	var pageIndex = 1;
    var This;
    var noadd = 1;
    var url = '';

	var app = {
		init: function(opt){
			var _self = this;
            var _opt = _self.opt = $.extend(true, {},{
                        listUrl: null,
                        list: null,
                        moreBtn: null,
                        moreinfo: null,
                        loadgif: null,
                        load:{}
            }, opt);

            // 初始化最开始的那个
            if($('[data-more-init="1"]').size() > 0){
                var thisData = $('[data-more-init]').data();
                config = utils.parseJSON(thisData.config);
                pageIndex = thisData.moreInit ;
                config.pageIndex = pageIndex ;
                This = $('[data-more-init="1"]');
            }
            
            
            $(document).off('click.nav').on('click.nav', '[data-role="ajax"]', function(event){
                event.preventDefault(); 
                $(_opt.moreinfo).hide();
                var $this = $(this);
                This = $this;
                var thisData = $this.data();
                config = utils.parseJSON(thisData.config);
                url = $this.attr('href');
                // console.log(config);
                
                pageIndex = thisData.pageIndex ;
                // console.log(pageIndex);
                config.pageIndex = pageIndex ;
               
                $this.addClass('choose_on');
                $this.data('prePageIndex', pageIndex);
                $this.siblings('[data-role="ajax"]').each(function(i,el){
                    var prePageIndex = $(el).data('prePageIndex');
                    $(el).attr('data-page-index', prePageIndex);
                    $(el).removeClass('choose_on');
                });
               

                noadd = 0;
               
                
                _self.more(config)

            });

             $(document).off('click.cmore').on('click.cmore', _opt.moreBtn, function(){
                    noadd = 1;
                     config.pageIndex = pageIndex +1 ;
                     _self.more(config)
             });

		},

		more: function(params){
            var _self = this;
            var _opt = _self.opt;
            var _params = $.extend(true, {}, {
                pageIndex: 1,
                pageSize: 10
            }, params, _opt.load);

            var $list = $(_opt.list);
            
            if ($list.hasClass('sending')) {

                return;
            }

            $list.addClass('sending');

            $.ajax({
                    url:  _opt.listUrl ? _opt.listUrl : url,
                    type: 'GET',
                    dataType: 'html',
                    data: _params,
                    beforeSend: function() {

                        $(_opt.loadgif).show();
                        $(_opt.moreBtn).hide();
                    }
                })
                .done(function(result) {

                    $(_opt.loadgif).hide();

                    $(_opt.moreBtn).show();

                    $list[noadd ? 'append' : 'html'](result);

                   

                    This.attr('data-page-index', _params.pageIndex);
                    pageIndex =  _params.pageIndex;
                    
                    
                    var page = $list.find('[data-page-count]').eq(-1).data();
                    if(page){
                        var listPage = Math.ceil(page.pageCount / page.pageSize);

                        if (pageIndex >= listPage) {

                         
                            $(_opt.moreBtn).hide();
                           

                            $(_opt.moreinfo).show().html('没有更多数据');

                            return;
                        }

                    }else {
                        $(_opt.moreBtn).hide();
                           

                        $(_opt.moreinfo).show().html('没有更多数据');

                        return;
                    }


                })
                .fail(function() {

                    // console.log("error");
                })
                .always(function() {

                    $list.removeClass('sending');

                    // console.log("complete");

                });
		}
	};

	module.exports = app;
});