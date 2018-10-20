/**
 * author : ling
 * date   : 2016-12-30
 * name   : mobileSearch
 * modify : {{ now()|date('Y-m-d')}} 
 */

/**
 * @module mobileSearch
 * @description  移动端搜索
 * @param  {String} data-role="search"       搜索记录的选择器
 * @param  {Object} config                   搜索的配置
 * @param  {String} config.clearBtn          清除搜索记录的选择器
 * @param  {String} config.tpl               显示记录的模板
 * @param  {number} config.size=10              显示记录的条数, 默认是10条
 * @param  {String} config.list              记录列表的选择器
 * @param  {String} config.text              显示记录文本内容的选择器
 * @param  {String} config.listItem          记录列表的子项选择器
 * @param  {number} config.num=1               当前localStorage有多少条记录
 *
 * @说明
 * mobileSearch用于手机端搜索时的记录
 *
 * @html调用
 * ```html
 * <a data-role="search" data-config="{
 *                         tpl:'#history', 
 *                         list: '.search-list', 
 *                         listItem: '.search-item',
 *                         text: '.text', 
 *                         clearBtn: '[data-clear]', 
 *                         size: 10
 * }" class="button button-fill button-primary
 *  col-15 sousuo" href="javascript:;">
 *     <span class="icon icon-search"></span>
 * </a>
 *```
 * @模板 在使用模板中, data数据的单引号是要去掉的
 * ```js
 *  <script id="history" type="text/html">
 *      <% for(var i = 0; i < 'data.length'; i++){ %>
 *          <li class="item-content search-item">
 *              <div class="item-inner">
 *                  <div class="item-title item-title-p0">
 *                      <span class="icon font-f-13"></span>
 *                      <span class="text"><%= 'data[i]' %> </span>
 *                  </div>
 *              </div>
 *          </li>
 *      <% } %>  
 *  </script>
 *  ```
 */

define(['template', 'utils'], function(require, exports, module){
    var template = require('template');
    var utils = require('utils');
    var arr = [];
    var reArr = [];
    var app = {
        init: function(){
            var _self = this;
            var $search = $('[data-role="search"]');
            var config =  utils.parseJSON($search.data('config'));
            var _opt = _self.opt = $.extend(true, {}, {
                clearBtn : null,
                tpl : null,
                size : 10,
                list: null,
                text: null,
                listItem: null,
                num : localStorage.getItem('search-num') || 1
            }, config);

            // console.log(_opt);
            
            $(document)
                .off('click.search')
                .on('click.search', '[data-role="search"]', function(){
                    var $form = $(this).closest('form');
                    var val = $form.find('input[name="name"]').val();

                    if($.inArray(val, arr) == -1){
                        localStorage.setItem('search-'+(_opt.num++), val);
                        localStorage.setItem('search-num', _opt.num);
                        
                        arr.push(val);
                        
                    }

                    _self.history();
                    $form.submit();
                });
            

            for(var i =1;i<_opt.num;i++){
                // console.log(localStorage.getItem('search-'+i));
                var val = localStorage.getItem('search-'+i);
                if($.inArray(val, arr) == -1 && val != null){
                    arr.push(val);
                }
            }
            // console.log(arr);

            _self.history();

            _self.deletes();

            _self.hclicks();
            

        },

        history: function(params){
            var _self = this;
            var _opt = _self.opt;
            
            var cha = arr.length - _opt.size;
            reArr = arr.concat();
            reArr.reverse();
            if(cha > 0){
                reArr.length = _opt.size;
            }
            

            for(var i=cha; i>=1;i--){
                localStorage.removeItem('search-'+i);
            }

            $(_opt.list).html(template(_opt.tpl.slice(1), {
                            data: reArr
                        }));

        },

        deletes: function(){
            var _self = this;
            var _opt = _self.opt;
            $(document).off('click.clear').on('click.clear', _opt.clearBtn, function(){
                for(var i=_opt.num-1,k=0;k<_opt.size;i--,k++){
                    localStorage.removeItem('search-'+i);
                }

                localStorage.setItem('search-num', 1);
                reArr = [];
                arr = [];
                $(_opt.list).html('');
            });
        },

        hclicks: function(){
            var _self = this;
            var _opt = _self.opt;
            var $form = $('[data-role="search"]').closest('form');
            var $input = $form.find('input[type="search"]');
            $(document).off('click.hclicks').on('click.hclicks', _opt.listItem, function(){
                var value = $.trim($(this).find(_opt.text).text());
                $input.val(value);

                $form.submit();
            });
        }
    };

    if($('[data-role="search"]').size() > 0){
        app.init();
    }

    exports.module = app;
});