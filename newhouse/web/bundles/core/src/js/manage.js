/*!
 * @name {{name}} v1.01
 * @author {{author}}
 * @date {{now()|date('Y-m-d H:i:s')}}
 * @description 管理后台功能库
 */
/**
 * @module Manage
 */
'use strict';

define('{{name}}', [
        'utils',
        'pinyin',
        'template',
        'pagination',
        'dropdown',
        'jqmodal',
        'dragResize',
        'validate',
        'markdown',
        'ajaxCache',
        'moment'
    ],
    function(require, exports, module) {
    var utils    = require('utils');
    var pinyin   = require('pinyin');
    var template = require('template');
    var Manage = function() {
                    // ajax的请求集合
                    this.xhrPool        = {};
                    this.arrRouteParams = [];
                    var BUNDLE_NAME     = typeof BUNDLE_NAME != 'undefined' ? 
                                            BUNDLE_NAME :
                                            'manage';
                    // 缓存key的前缀
                    this.cacheKeyPre    = BUNDLE_NAME + '_';
                };
    var out;
    /*$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
        // 处理tip提示
        // jqXHR.tip = options.tip == 0 ? 0 : 1;
    });*/
    // ajax 默认
    $.ajaxSetup({
        dataType: 'json',
        // 兼容ie8
        cache: !utils.browser().msie,
        data: {
            'csrf_token': csrf_token
        },
        beforeSend: function(jqXHR) {
            $.jqModal.progress();
            this.ajaxKey = this.ajaxKey || this.url;
            // 防止重复提交
            out.ajaxAbort(this.ajaxKey);
            // 将当前请求加入队列，便于管理
            out.xhrPool[this.ajaxKey] = jqXHR;
        },
        success: function(data, status) {
        },
        error: function() {
            // $.jqModal.tip('服务器错误！', 'error');
        },
        complete: function(jqXHR) {
            // 当退出登录后，再次操作会跳转到登录页
            // 提示信息必须放complete里，不然会被覆盖
            var data = jqXHR.responseText;
            var relogin = function(d){
                if (d.nologin === 0) {
                    if (d.jumpUrl) {
                        window.location.href = d.jumpUrl;
                    }else{
                        window.location.reload();
                    }
                }
            };
            // 显示弹窗提示
            var tip = this.tip === 0 ? 0 : 1;
            var _alert = function(d) {
                // 防止与第三方插件(自动搜索)冲突
                // status 必须存在，否则为普通的json数据请求
                if (d.status !== undefined && !d.status) {
                    $.jqModal.alert(d.info || '操作失败！', 'error');
                    relogin(d);
                } else if (d.status && tip) {
                    $.jqModal.tip(d.info, 'success');
                }
            };
            var _data = utils.parseJSON(data);
            if (!$.isEmptyObject(_data)) {
                _alert(_data);
            }            


            $.jqModal.progress('100%');
            // 如果当前请求在队列中，删除
            delete out.xhrPool[this.ajaxKey];
        }
    });

    Manage.prototype = {
        //调用函数
        /*
         * 调用函数
         * @page 函数名， par参数名
         * @return function
         */
        init: function(page, par) {
            //初始化加載的腳本
            // this[page](par);
            // try{
                this[page](par);
            // }catch(e) {
            //     core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
            //     console.warn(e);
            // }
        },
        //入口函数
        /**
         * @method main
         * @param  {Object} opt 主配置
         * @param  {String} opt.homePage=mindex 首页设置
         */
        main: function(opt) {
            opt = opt || {};
            var _self = this;
            // 设置首页
            _self.homePage = opt.homePage || 'mindex';
            $(window)
            .off('.manage')
            .on('resize.manage', function() {
                _self.resizeWindows();
                _self.resizeWindows('[data-content="1"]');
                // 保存window的宽高
                _self.setWH();
                // 窗口缩小时，关闭左侧菜单
                $('[data-tab="1"]').trigger('click', [_self.WINDOW_WH[0] < 996 ? 'close' : 'open']);
            });


            //自适应高度
            _self.resizeWindows();

            // 第一次需要保存window的宽高
            _self.setWH();

            _self.events();
            // 侧栏
            $('[data-aside="1"]')
                .addClass(utils.localStorage.getItem(_self.cacheKeyPre + 'aside') || null);
            // 清除所有后台缓存
            var rAsideCache = new RegExp(_self.cacheKeyPre);
            utils.localStorage.removeItem(rAsideCache);
            // 路由中转链接
            _self.router();

        },
        events: function() {
            var _self = this;
            // topmenu
            //频道菜单点击效果
            $(document)
                .off('.manage')
                /**
                 * @name  pinyin 自动拼音
                 * @param  {Object} eldata $(this).data() `data`数据
                 * @param  {String} eldata.pinyin 当前元素`change`事件时 `pinyin`的值自动等于当前值的拼音
                 * @param  {bealean} eldata.camel 当前元素`change`事件时 camel=1时 `pinyin`的值自动等于首字母 + 当前值的拼音
                 * @param  {bealean} eldata.always 当前元素`change`事件时 always=1时 添加编辑都会自动拼音
                 * @示例 调用
                 * ```html
                 *   <input type="text" data-pinyin="#ename" name="name"/>
                 *   <input type="text" id="ename" name="ename"/>
                 * ```
                 */
                .on('change.manage', 'input[data-pinyin]', function() {
                    // console.log($(this.form).data('id'), 111);
                    var $this = $(this);
                    if (!$(this.form).data('id') || $this.data('always')) {
                        if ($this.data('camel')) {
                            var _val = pinyin.getCamelChars(this.value) + ',' +
                                        pinyin.getFullChars(this.value);
                            $($this.data('pinyin')).val(_val);
                        } else {
                            $($this.data('pinyin')).val(pinyin.getFullChars(this.value));
                        }
                    }
                })
                /**
                 * @name  same 自动自动完成其它字段
                 * @param  {Object} eldata $(this).data() `data`数据
                 * @param  {String} eldata.same 当前元素`change`事件时 `$(eldata.same)`的值自动等于当前值
                 * @示例 调用
                 * ```html
                 *   <input type="text" data-same="#ename" name="name"/>
                 *   <input type="text" id="ename" name="ename"/>
                 * ```
                 */
                .on('change.manage', 'input[data-same]', function() {
                    if (!$(this.form).data('id')) {
                        $($(this).data('same')).val(this.value);
                    }
                })
                /**
                 * @name  level 多级菜单
                 * @param  {Object} eldata $(this).data() `data`数据
                 * @param  {number|string} eldata.level `top` 菜单的最外层 `1` - 当前元素为菜单项(不管几级，只要是菜单项就要加) `next` 当前元素为下级菜单
                 * @param  {number} eldata.triggerNext=0 点击菜单项时是否下级菜单的点击
                 * @param  {String} eldata.type=show `show` 其它菜单收起当前展开不收起 `toggle` 其它菜单收起当前菜单toggle `singleToggle` 其它菜单不动，当前菜单toggle
                 * @示例 调用
                 * ```html
                 *   <ul data-level="top" data-trigger-next="1" data-type="toggle">
                 *       <li data-level="1">一级菜单</li>
                 *       <li data-level="next">
                 *           <ul>
                 *               <li><a href="#" data-level="1">二级菜单</a></li>
                 *               <li><a href="#" data-level="1">二级菜单</a></li>
                 *               <li><a href="#" data-level="1">二级菜单</a></li>
                 *           </ul>
                 *       </li>
                 *   </ul>
                 * ```
                 */
                .on('click.manage', '[data-level=1]',  function(e) {
                    _self.levelHandle(this); 
                    //停止冒泡
                    e && e.stopPropagation();
                })
                // 行内编辑时阻止冒泡
                .on('click.manage', '.edit', function(e) {
                    e.stopPropagation();
                })
                .on('click.manage', [
                    '[data-role="route"]',
                    '[data-role="delete"]',
                    'th[data-role="sort"]',
                    '[data-role="show"]',
                    '[data-role="checkbox"]',
                    '[data-role="submit"]',
                    '[data-role="jump"]',
                    '[data-role="disabled"]',
                ].join(','), $.proxy(_self.middleware, _self))
                .on('change.manage', [
                    'input[data-role="search"]',
                    'select[data-role="select"]',
                ].join(','), $.proxy(_self.middleware, _self))
                .on('dblclick.manage', '[data-role="itemEdit"]', $.proxy(_self.middleware, _self))
                // 处理说明信息框
                .on('mouseover.manage', '.prompt', function() {
                    $(this).addClass('prompt-hover');
                })
                .on('mouseout.manage', '.prompt', function() {
                    $(this).removeClass('prompt-hover');
                })
                .on('click.manage', '.select-item :checkbox, .select-item :radio', _self.checkItem)
                .on('click.manage', '[data-tab=1]', function(e, tabType) {
                    var $this = $(this);
                    var $aside = $this.closest('.aside');
                    var _tabType = $aside.hasClass('aside-sm');

                    if (tabType) {
                        _tabType = tabType == 'open';
                    }
                    // 除了自动判断外，还要根据tabType做出动作
                    if (_tabType) {
                        // 注意顺序
                        $('.aside-sm').find('.level2 .on')
                            .closest('.level2').addClass('on');
                        $aside.removeClass('aside-sm');
                    } else {
                        $aside.addClass('aside-sm');
                        $('.aside-sm').find('.level2.on').removeClass('on');
                    }
                    e.stopPropagation();
                })
                .on('mouseover.manage', '.aside-sm h4', function(e) {
                    var $this = $(this);

                    $this.next('.level2').addClass('on').css({
                        top: $this.position().top
                    }).siblings('.level2').removeClass('on');
                    e.stopPropagation();
                })
                .on('mouseover.manage', '.aside-sm .level2', function(e) {
                    e.stopPropagation();
                })
                .on('mouseover.manage', function() {
                    $('.aside-sm').find('.level2').removeClass('on');
                })
                // 监听按键
                .on('keyup.manage', function(e) {
                    // console.log('当前按键：' + e.keyCode);
                    // 注意顺序
                    var aKeys = ['ctrlKey', 'altKey', 'shiftKey'];
                    var aNewKeys = [];
                    $.each(aKeys, function(i, key) {
                        if (e[key]) {
                            aNewKeys.push(key.replace('Key', ''));
                        }
                    });
                    aNewKeys.push(e.keyCode);

                    $('[data-bindkey="'+ aNewKeys.join('+') +'"]').click();
                });
        },
        levelHandle: function(el) {
            var _self = this;
            var $this = $(el),
                // 外层
                $levelTop = $this.closest('[data-level=top]'),
                ajaxUrl = $this.data('ajax'),
                T = $levelTop.data('type');

            var cb = function() {
                // 当前级别和下级
                var $thisAndNext = $this.next('[data-level=next]').andSelf();
                switch (T) {
                    case 'toggle':
                        $levelTop.find('.on').not($thisAndNext).removeClass('on');
                        $this.parents('[data-level=next]').addClass('on');
                        $thisAndNext.addClass('on');
                        $this.parents('[data-level=next]')
                            .prev('[data-level=1]').addClass('on');
                        break;

                    case 'singleToggle':
                        // $this.parents('[data-level=next]')
                        $thisAndNext.toggleClass('on');
                        $this.parents('[data-level=next]').addClass('on')
                            .prev('[data-level=1]').addClass('on');
                        break;

                    default :
                        $levelTop.find('.on').not($thisAndNext).removeClass('on');
                        $this.parents('[data-level=next]')
                            .addClass('on').prev('[data-level=1]').addClass('on');
                        $thisAndNext.addClass('on');
                }
                if ($levelTop.data('triggerNext')) {
                    $this.next().find('[data-level]').eq(0).click();
                }
                // 如果是菜单管理，点击后要重置表头
                if ($levelTop.closest('.list').length) {
                    _self.tableFixed();
                }
            };

            if (ajaxUrl && !$this.hasClass('opened')) {
                $.ajax({
                        url: ajaxUrl,
                        dataType: 'html'
                    })
                    .done(function(d) {
                        $this.after(d).addClass('opened');
                        cb();
                    })
                    .always(function() {
                    });
            } else {
                cb();
            }
        },
        disabled: $.noop,
        // 保存window的宽高
        setWH: function() {
            this.WINDOW_WH = [$(window).width(), $(window).height()];
        },
        // 重置表单
        reset: function() {
            this.$area.find(this.param.filter).val('')
                .filter('input[data-role="search"]').data('value', '');
        },
        // 中间件
        middleware: function(e) {
            var $this = $(e.currentTarget),
                eldata = $this.data();
            eldata.$el = $this;
            // e是jq对象直接返回数据
            if (e.isJq) {
                return eldata;
            }
            this[eldata.role](eldata);

            e.stopPropagation();

            $(document).click();
            return false;
        },
        // 复选框，单选框
        checkItem: function(args) {
            if (args.type == 'click') {
                var $this = $(this);
                var $thisParent = $this.closest('.select-wrap');
                if ($this.hasClass('default')) {
                // 点击不限
                    $thisParent.find(':checked').not(this).prop('checked', false);
                } else {
                // 点击其它
                    $thisParent.find('.default').prop('checked', false);
                }
                // 点击事件
                $thisParent.find('.select-item')
                    .removeClass('select-item-act')
                    .find(':checked').closest('.select-item').addClass('select-item-act');

                $(this).find('input').focus();
            } else {
                // 初始化
                $(args).find(':checked').closest('.select-item').addClass('select-item-act');
            }
        },
        /**
         * @method itemEdit
         * @description 行内编辑
         * @param  {Object} eldata $(this).data() `data`参数配置
         * @param  {String} eldata.role 选择器类型
         * @param  {String} eldata.name 内容对应的字段名
         * @param  {String} eldata.url 编辑后`ajax`提交的地址，没有时data-param上的配置
         * @param  {String} eldata.size 单选文本的size属性，控制宽度用的
         * @PS tr上必有data-id
         * @示例 调用
         * ```html
         * <section data-param="{xxx}">
         * <table>
         *   <tr data-id="1">
         *     <span data-role="itemEdit" data-name="name">标题标题</span>
         *   </tr>
         * </table>
         * </section>
         * ```
         */
        itemEdit: function(eldata) {
            // input框的默认大小, 按字段名配置
            var oSize = {
                sort: 3,
                sort_fixed: 3
            };
            var _self = this;
            var $this = eldata.$el;
            var html = eldata.value || $this.text(),
                $input = $('<input>', {
                                type: 'text',
                                className: 'input-sm',
                                name: eldata.name,
                                size: oSize[eldata.name] || eldata.size || 3
                            });
            var data = {
                id: $this.closest('[data-id]').data('id'),
                _is_appointed: 1
            };
            // 注释移除属性，目的是修改的时候宽度不定
            $this.html($input);//.removeAttr('data-role');
            $input.trigger('focus')
                .val(html)
                .off('.manage')
                .on('keypress.manage', function(e) {
                    if (e.keyCode == 13) {
                        $(this).trigger('blur.manage');
                    }
                })
                .on('blur.manage', function() {
                    var _this = this;
                    if (_this.value === html) {
                        $this.html(html);
                    } else {
                        // console.log(_this);
                        data[_this.name] = _this.value;
                        //卸载事件,防止重复点击
                        $(_this).prop('disabled', true);
                        $.ajax({
                            url: _self.param.submitUrl,
                            type: 'POST',
                            data: data
                        })
                        .done(function(d) {
                            if (d.status) {
                                $this.html(_this.value);
                            } else {
                                $this.html(html);
                            }
                            eldata = null;
                        })
                        .always(function() {
                            $(_this).prop('disabled', false);
                        });

                    }
                    $this.attr('data-role', 'itemEdit');
                });

        },
        /*
         * @method getData
         * @description 获取查询条件数据
         * @param  {String} role=flush 对应的key
         * @return {Object}     json数据
         * @示例 调用
         * ```js
         * getData('page') // 获取点击分页时要的数据
         * ```
         */
        getData: function(role) {
            role = role || 'flush';
            var oFilterMap = {
                sort  : ['sort', 'search', 'select'],
                search: ['search'],
                select: ['sort', 'search', 'select'],
                page  : ['sort', 'search', 'select', 'page'],
                delete: ['sort', 'search', 'select', 'page'],
                flush : ['sort', 'search', 'select', 'page']
            };
            // 没area就空 
            if (!this.$area || !oFilterMap[role]) {
                 return {};       
            }

            var oFilterSelMap = {
                   // filter:  '.on[data-role="filter"]',
                   sort  :  'th.act[data-role="sort"]',
                   search:  'input[data-role="search"]',
                   select:  'select.act[data-role="select"]',
                   page  :  '[data-page="1"]'
                };

            var elementsMap = {};
            // 重置
            $.each(oFilterSelMap, function(k, v) {
                // page不需要重置
                if (k != 'page' && $.inArray(k, oFilterMap[role]) == -1) {
                    if (k == 'sort') {
                        $(v).removeClass('asc desc act')
                            .removeData('value');
                    } else if (k == 'select') {
                        var $select = $(v);
                        $select.removeClass('act')
                            .val('')
                            .removeData('value')
                            .removeAttr('data-value');

                        // 更新chosen
                        if ($select.data('chosen')) {
                            $select.data('chosen').results_update_field();
                            $select.next().removeClass('chosen-container-selected');
                        }

                    }
                }
            });

            $.each(oFilterMap, function(k, v) {
                var aFilter = [];
                $.each(v, function(i, vv) {
                    aFilter.push(oFilterSelMap[vv]);
                });
                elementsMap[k] = aFilter.join(',');
            });
            return this.$area.find(elementsMap[role]).serializeObject();
        },
        /**
         * @method sort
         * @description 排序选择器
         * @param  {Object} eldata data数据
         * @param  {String} eldata.role 选择器类型
         * @param  {String} eldata.sort 要排序的字段
         * @其它参数 见`select`方法
         * @示例 调用
         * ```html
         *   <th data-role="sort" data-sort="clicks">点击</th>
         * ```
         */
        sort: function(eldata) {
            var _self = this;
            var orderBy = eldata.$el.hasClass('desc') ? 'asc' : 'desc';
            eldata.$el.data({
                    name: 'order',
                    value: eldata.sort + '|' + orderBy
                })
                .removeClass('asc desc act')
                .addClass(orderBy + ' act')
                .siblings('.act')
                .data('value', '')
                .removeClass('asc desc act');

            // eldata.data = eldata.data || {};
            // eldata.data.order = eldata.sort + '|' + orderBy;

            _self.flush(eldata);
        },

        /**
         * @method jump
         * @description 跳转，与route一样的配置，不同的是点击后地址栏有时没有路由标识，通常在三级菜单上用
         */
        jump: function(eldata) {
            // debugger;
            var _self = this;
            var setting = _self.getSetting(eldata);
            var $area = _self.$area;
            var isPop = $area && $area.is('.jq-modal');
            var $target;
            // eldata.target = eldata.target || eldata.$el.closest('[data-target]').data('target');
            eldata.target = _self.getTarget(eldata);
            if (isPop) {
            // 弹窗展示
                $target = $area.find(eldata.target);
            } else {
            // 非弹窗
                $target = $('[data-content="1"]').find(eldata.target);
            }

            _self.menuClasschange(eldata.$el);
            // 移除日期插件
            $('[data-date]').each(function() {
                if ($(this).data('daterangepicker')) {
                    $(this).data('daterangepicker').remove();
                }
            });
            // debugger;
            try {
                $.ajax(setting)
                    .done(function(d) {
                        if ($.type(d) == 'string') {
                            try {
                                var $d = $(d);
                                var target = eldata.from || eldata.target;
                                var $dFilter = $d.filter(target);
                                var _html = $dFilter.length ?
                                                $dFilter.html() :
                                                $d.find(target).html();

                                $target.empty().html(_html);
                            } catch (err) {
                                console.warn(err);
                            }
                        }

                        eldata.$el.trigger('done', [d]);
                        // 处理hash
                        if (!isPop) {
                            // 只设置第三个路由
                            var routeIndex;
                            // 使用全局的标记
                            if (typeof MENU_ROUTE_INDEX != 'undefined') {
                                routeIndex = MENU_ROUTE_INDEX + 1;
                            }
                            // 先使用本身的标记
                            if (eldata.routeIndex) {
                                routeIndex = eldata.routeIndex * 1 + 1;     
                            }
                            _self.setRoute(routeIndex || 3, eldata.route);
                        }
                    })
                    .always(function() {
                        // 重设参数
                        _self.setParam($area, function(isList) {
                            if (isList) {
                                // 处理表格头fixed
                                _self.tableFixed();
                                // 处理分页
                                _self.page();
                            }
                        });
                        //重置滚动条
                        _self.resizeWindows($area);
                        // 处理底部说明
                        _self.markdown($target);
                        // 释放
                        $target = $area = null;
                    });
                } catch(err) {
                    console.warn(err);
                }
        },
        /**
         * @method select
         * @description 下拉列表筛选
         * @param {Object} eldata `data`数据
         * @param {String} eldata.role 选择器类型
         * @param {String} eldata.url 请求的`url`,可选，没有时会自动找data-param的参数
         * @param {String} eldata.type=POST 对应`ajax`的`type`
         * @param {String} eldata.dataType=html 对应`ajax`的`dataType`
         * @param {Object} eldata.selectdate={format:'YYYY-MM-DD'} 对应`moment`的配置 `unix: 1 `表示使用时间戳
         * @param {Object} param 页面上的手动配置
         * @param {String} param.defUrl 列表链接
         * @param {String} param.showUrl 弹窗的表单链接
         * @param {String} param.submitUrl 表单的提交链接
         * @param {String} param.fixed=.filter 列表的头的fixed选择器
         * @param {String} param.list=.list-con 列表的选择器
         * @param {Object} param.data={} 需要固定的参数，也就是说这里设置的参数一直当前页面里存在，直到`jump`到其它页面,当`selectdate`存在时
         `<option value="lt｜1M">选项</option>` M表示月,其它具体参数参考 {@link http://momentjs.cn/docs/|moment}插件
         * @示例 调用
         * ```html
         *   <select name=""
         *       data-role="select"
         *       data-url="http://www.08cms.com/get/"
         *       data-type="post" >
         *       <option value="">选项</option>
         *   </select>
         * ```
         * @示例 从`data-param`获取配置调用
         * ```twig
         *   <div data-param="{
         *       defUrl    : 'aaa/list',
         *       showUrl   : 'aaa/show',
         *       submitUrl : 'aaa/save',
         *       deleteUrl : 'aaa/delete',
         *       fixed     : '.filter',
         *       list      : '.list-con',
         *       data      : {}
         *   }">
         *
         *       <select data-role="select" name="category">
         *           <option value="1">选项</option>
         *       </select>
         *
         *   </div>
         * ```
         */
        select: function(eldata) {
            var _self = this;
            var val = eldata.$el[0].value;
            if (eldata.selectdate && val) {
                // 利用moment插件将1M转换成一个月
                var selectdate = utils.parseJSON(eldata.selectdate);
                // 利用正则将自定义的格式(1M or lt|1M)转换成moment可用的格式，并最终转换成时间戳
                var regFn = function($1, $2, $3) {
                    var dateStr =  moment().subtract($2, $3);
                    var val1;
                    if (selectdate.unix) {
                        val1 = moment(dateStr).unix();
                    } else {
                        val1 = moment(dateStr).format(selectdate.format || 'YYYY-MM-DD');
                    }
                    // console.log(val1, $2, $3);
                    return val1;
                };
                // 1M or lt|1M
                var val2 = val.replace(/(\d+)(\w)/, regFn);
                // 为了防止出错，两种方式都要写
                eldata.$el.data('value', val2);
                eldata.$el.attr('data-value', val2);
            }
            // 选中时高亮
            _self.filterToggleAct(eldata.$el);
            // 没有值时要重置 data-vale
            if (!val) {
                eldata.$el.removeData('value').removeAttr('data-value');
            }
            _self.flush(eldata);
        },
        // 选中时高亮
        filterToggleAct: function(el) {
            el[$.trim(el.val()) ? 'addClass' : 'removeClass' ]('act');
        },
        /**
         * @method search
         * @description 搜索选择器
         * @param  {Object} eldata `data`数据
         * @param  {String} eldata.role 选择器类型
         * @param  {String} name 要搜索的字段
         * @其它参数 见`select`方法
         * @示例 调用 些控件只传name过去，要定义搜索请在服务器端做处理(标识里)
         * ```html
         *   <input type="text" data-role="search" name="name"/>
         * ```
         */
        search: function(eldata) {
            var _self = this;
            var $el = eldata.$el;

            // utils.searchEx(eldata.$el);

            // 搜索时高亮
            _self.filterToggleAct($el);

            eldata.type = 'POST';
            _self.flush(eldata);
        },

        /**
         * @method page
         * @description 分页选择器
         * @param  {Object} eldata `data`数据
         * @param  {Boolean} eldata.page 启用分页
         * @param  {number} eldata.pageCount 总结果数
         * @param  {number} eldata.pageIndex 当前分页
         * @param  {number} eldata.pageSize 每页显示条数
         * @其它参数 见`select`方法
         * @示例 调用
         * ```html
         *   <div class="pager" data-page="1" data-page-count="100" data-page-index="1" data-pageSize="8">
         *   </div>
         * ```
         */
        page: function(pageselectCallback) {

            var _self = this;
            // 没有配置不执行
            if (!_self.param) {
                return;
            }
            var $page = _self.$area.find('[data-page]');
            if (!$page.length) {
                _self.$area.find(_self.param.list).addClass('no-page');
                return;
            }
            var pageIndex = $page.data('page-index') || 1;
            var eldata = _self.middleware({currentTarget: $page, isJq: 1});
            // 没有数据
            if (!eldata) {
                return false;
            }
            // console.log(eldata.$el);
            pageselectCallback = pageselectCallback || function(page) {
                //动态更新数据
                $page.data({
                    name: 'pageIndex',
                    value: page + 1
                });
                _self.flush(eldata);
                return false;
            };
            // debugger;
            //中部菜单翻页效果
            $page.data({
                    name: 'pageIndex',
                    value : pageIndex
                })
                .pagination(eldata.pageCount, {
                    num_edge_entries   : 1, //边缘页数
                    num_display_entries: 10, //主体页数
                    // link_to            : utils.urlJoin(_self.param.defUrl, {pageSize: '__id1__'}),
                    // num_edge_entries   : 3,
                    callback           : pageselectCallback,
                    items_per_page     : eldata.pageSize, //每页显示1项
                    current_page       : pageIndex - 1,
                    prev_text          : '前一页',
                    next_text          : '后一页',
                    load_first_page    : false
                });
        },

        /*
         * @method setParam
         * @description 获取页面上的`data-param`配置
         * @param  {Object} $target 从哪个页面找`data-param`
         * @param  {Function} fn
         * @示例 调用
         * ```js
         *   setParam($target)
         * ```
         */
        setParam: function($target, fn) {
            var _self = this;
            var $param = $target.find('[data-param]');

            _self.$area = $target;
            // 先重置原来区域的配置
            _self.param = {};
            if ($param.length) {
                // _self.$area.data('pageIndex', 1);
                _self.param = utils.parseJSON($param.data('param'));
                // 删除空字段
                if (_self.param.data) {
                    $.each(_self.param.data, function(k, v) {
                        if (v === '') {
                            delete _self.param.data[k];
                        }
                    });
                }
            }
            var isList = !($target.find('[data-list-con-show="1"]').length);
            // 不是列表时移到提示
            if (!isList) {
                $target.find('[data-prompt-list="1"]').remove();
            }
            if (fn) {
                fn.call(_self, isList);
            }
        },

        getSetting: function(eldata) {
            eldata = eldata || {};
            var _self = this;
            var Default = {
                type      : 'GET',
                dataType  : 'html'
            };

            if (eldata.role == 'submit') {
                var $form = eldata.$el.closest('form');
                if ($form.length && !eldata.$el.attr('data-url')) {
                    eldata.url = $form.attr('action');
                }
            }

            eldata.url = eldata.url || 
                        eldata.$el && 
                        eldata.$el.attr('href') &&
                        !eldata.$el.attr('href').match(/^#+/) &&
                        eldata.$el.attr('href') || undefined;
            // 最终的url
            var url = eldata.url || _self.param[eldata.role + 'Url'] || _self.param.defUrl;

            var _url = utils.parseURL(url);
            // dom上本身的数据eldata.data是过度，以后统一使用params
            var elementData = utils.parseJSON(eldata.data);
            var elementParams = utils.parseJSON(eldata.params);
            // 删除掉area
            delete _url.params.area;
            // role
            // role对应的当前区域里的select,sort,page,search数据
            var filterData = {};
            // 添加时不要filterData,不是添加时才要
            if (!eldata.isAdd) {
                filterData = _self.getData(eldata.role);
            }
            // 最终的data
            var globalParams = _self.param && _self.param.data || {};
            var data = $.extend({}, 
                                globalParams, 
                                filterData, 
                                _url.params, 
                                elementData, 
                                elementParams);
            
            var oSetting = $.extend(true, {}, Default, {
                url  : _url.base || undefined,
                data : data,
                type : eldata.type,
                // 有模板时一定是json
                dataType: eldata.tpl ? 'json' : eldata.dataType
            });
            return oSetting;
        },
        /*
         * @method flush
         * @description ajax处理 sort, select, search, page
         * @param {Object} eldata 刷新配置
         * @param {Object} eldata.data ajax的data
         * @param {String} eldata.url ajax的url
         * @param {String} eldata.target 要刷新的区域
         * @param {String} eldata.type=GET ajax的type
         * @param {String} eldata.dataType=html ajax的dataType
         * @param {funtion} done `ajax.done`，可覆盖默认函数
         * @param {funtion} fail `ajax.fail`，可覆盖默认函数
         * @param {funtion} always `ajax.always`，可覆盖默认函数
         */

        flush: function(eldata, done, always) {
            eldata  = eldata || {};
            var _self   = this;
            var setting = _self.getSetting(eldata);
            
            var target  = eldata.target || _self.param.list || '[data-param]';
            // console.log(_self.$area);
            var $target = _self.$area.find(target);
            
            var isMenu  = $target.find('[data-level="top"]').length && eldata.isEdit;
            // var isMenu = 0;

            var menuDone = function(d, $tRow) {
                // 如果是菜单刷新单条数据
                    // 当前行的选择标识
                    var sSel = '[data-id="'+ $tRow.data('id') +'"]';
                    try {
                        // $tRow.html($(d).find(sSel).html())
                        // 刷新下级，注意顺序
                        var $tRowNext = $tRow.next('[data-level="next"]');
                        if ($tRowNext.length) {
                            $tRowNext
                                .replaceWith($(d).find(sSel).next('[data-level="next"]'));
                        }
                        // 只刷新当前行
                        $tRow.replaceWith($(d).find(sSel));
                    } catch (err) {
                        console.warn(err);
                    }
                };
            // debugger;
            return $.ajax(setting)
                .done(done || function(d) {
                    // 当前行
                    var $tRow = $target.find('.is-active-btn').closest('tr');
                    // 菜单 and 不是ajax的菜单
                    if (isMenu && !$tRow.data('ajax')) {
                        menuDone(d, $tRow);
                    } else {
                        try {
                            $target.empty().html($(d).find(target).html());
                        } catch (err) {
                            console.warn(err);
                        }
                    }
                })
                .always(always || $.noop())
                .always(function() {
                    // 处理表格头fixed
                    _self.tableFixed();
                    _self.page();
                });
        },
        /**
         * @method checkbox
         * @description 复选框选择器 单选时 `父级必须有data-id`
         * @param  {Object} eldata $(this).data() `data`数据
         * @param  {String} eldata.role 选择器类型
         * @示例 调用,如果checkbox在`data-param'的`param.list`里为单选，反之为全选
         * ```html
         *     <div data-param="{list: '.list'}"></div>
         *     <div data-id><i data-role="checkbox">全选</i></div>
         *     <ul class="list">
         *         <li data-id="1"><i data-role="checkbox">选择</i></li>
         *         <li data-id="1"><i data-role="checkbox">选择</i></li>
         *         <li data-id="3"><i data-role="checkbox">选择</i></li>
         *     </ul>
         * ```
         */
        checkbox: function(eldata) {
            var $tr      = eldata.$el.closest('[data-id]');
            // 样式的class
            var cls      = 'chked';
            var actClass = $tr.hasClass(cls) ? 'removeClass' : 'addClass';
            var $area    = this.$area;

            if (!$tr.data('id')) {
            // 多选
                $area.find('[data-role="checkbox"]')
                    .closest('[data-id]')[actClass](cls);
                $tr[actClass](cls);
            } else {
            // 单选
                $tr[actClass](cls);
                // 处理多选按钮
                var isAll = !$area.find('td [data-role="checkbox"]')
                        .closest('[data-id]:not(".'+ cls +'")').length;

                $area.find('th [data-role="checkbox"]')
                        .closest('[data-id]')[isAll ? 'addClass' : 'removeClass'](cls);
            }

            eldata = null;
        },
        /**
         * @method show
         * @description 用弹窗显示内容, 如果父级有`data-id`url上会自动加入id
         * @param  {Object} eldata `data`数据
         * @param  {String} eldata.role 选择器类型
         * @param  {Boolean} eldata.reload 关闭后是否重置刷新
         * @param  {Object} eldata.fields 在弹窗时批量传递多个参数及值, key要传递的key，value为要取的列表的值(列表上必须有)
         * @其它参数 见`select`方法
         * @示例 调用
         * ```html
         *   <a data-role='show' href='web/app_dev.php/house/mnewsmanage/show'>编辑</a>
         * ```
         * @示例 调用2
         * ```html
         * <div data-param="{list: '.list', showUrl: 'web/app_dev.php/house/mnewsmanage/show'}"></div>
         * <div class="list">
         * <tr data-id='5'>
         *   <a data-role='show' href=''>编辑</a>
         * </tr>
         * </div>
         * 点击时的ajax.url = 'web/app_dev.php/house/mnewsmanage/show?id=5'
         * ```
         */
        show: function(eldata) {
            var _self   = this;
            //设置弹窗参数
            var el      = eldata.$el[0];
            var setting = _self.getSetting(eldata);
            // 保存原来area, 关闭窗口后设回
            var oArea   = _self.$area;
            // widow width height
            var WH      = _self.WINDOW_WH;

            // 移除已存在的标记
            oArea.find('.is-active-btn').removeClass('is-active-btn');
            // 当前按钮添加标记
            eldata.$el.addClass('is-active-btn');
            // if (!setting.data.pid && !setting.data.aid && eldata.$el.hasClass('btn'))
            // setting.data.id = eldata.$el.closest('[data-id]').data('id') || undefined;
            var isInlineEdit = (!setting.data.pid && eldata.$el.hasClass('btn')) ||
                                    eldata.$el.hasClass('is-edit');
            if (isInlineEdit) {
                // 有ID时还使用原来的ID没有时才去找对应的ID
                setting.data._id = setting.data._id ||
                                    eldata.$el.closest('[data-id]').data('id') ||
                                    undefined;
            }

            // 默认的大小
            var aPopSize = [WH[0] * 0.8, WH[1] * 0.8];
            // 获取弹窗大小
            if (utils.localStorage.getItem(_self.cacheKeyPre + 'popSize')) {
                aPopSize = utils.localStorage.getItem(_self.cacheKeyPre + 'popSize').split(',');
            }
            // iframe
            if (eldata.iframe) {
                $.jqModal.modal({
                    head: el.title || el.value || el.innerHTML,
                    zIndex: 2000,
                    animate: null,
                    content: utils.urlJoin(setting.url, setting.data),
                    css: {
                        width: Math.min(aPopSize[0], WH[0] * 0.95),
                        height: Math.min(aPopSize[1], WH[1] * 0.95),
                    },
                    type: 'iframe',
                    done: function() {

                    }
                });
                return false;
            }
            // 在弹窗时批量传递多个参数及值
            $.each(utils.parseJSON(eldata.fields) || {}, function(k, v) {
                /* iterate through array or object */
                setting.data[k] =_self.getListData(v);
            });
            // 正常调用
            $.jqModal.modal({
                head: el.title || el.value || el.innerHTML,
                zIndex: 2000,
                animate: null,
                content: '<div class="tac" style="line-height: '+
                            (Math.min(aPopSize[1], WH[1] * 0.95) - 34) +
                            'px"><i class="ico font-modal-load"></i>正在玩命加载...</div>',
                css: {
                    width: Math.min(aPopSize[0], WH[0] * 0.95),
                    height: Math.min(aPopSize[1], WH[1] * 0.95)
                },
                type: 'html',
                done: function() {
                    var $target = this;
                    // 弹窗的ajax请求标识，用于关闭弹窗时对ajax请求有针对的abort
                    setting.ajaxKey = 'modalPage';
                    $.ajax(setting).done(function(data) {

                        $target.html(data);
                        if ($target.find('.sub-menu').children().length === 0) {
                            $target.find('.sub-menu').parent().hide();
                        }
                        // var $wkPopBody = $target.find('.wk-pop-body');
                        // 弹出如果是列表也调整布局
                        _self.setParam($target, function(isList) {
                            if (isList) {
                                // 处理表格头fixed
                                _self.tableFixed();
                                // 处理分页
                                _self.page();
                            }
                        });
                        // 处理侧栏，要triggerNext
                        _self.triggerNext($target);
                        // debugger;
                        // eldata.editor && $wkPopBody.data('editor', eldata.editor);
                        //重置滚动条
                        _self.resizeWindows($target);
                        // 初始化复选框
                        _self.checkItem($target);
                        _self.markdown($target);
                    });
                }
            })
            .on('hideFun', function() {
                // 中止弹窗上正在请求的ajax
                _self.ajaxAbort('modalPage');
                // 作用域设回原来的
                _self.setParam(oArea);
                // 刷新, 如果打开的弹窗里submit里有reload，这个就不用设置
                if (eldata.reload) {
                    _self.flush();
                }
                $(this).find('[data-date]').each(function() {
                    if ($(this).data('daterangepicker')) {
                        $(this).data('daterangepicker').remove();
                    }
                });

            })
            .data('jqModal')
            .$box
            .dragResize({
                minWidth: WH[0] * 0.5,
                minHeight: WH[1] * 0.5
            })
            .on('resizing.dragResize', function() {
                var $box = $(this);
                _self.resizeWindows($box.find('.jq-modal').jqModal('setPos'));
                // 本地保存弹窗大小
                var aSize = [parseFloat($box.css('width')), parseFloat($box.css('height'))];
                utils.localStorage.setItem(_self.cacheKeyPre + 'popSize', aSize);
            })
            .append('<i class="south-east-resize resize" title="拖动改变弹窗大小"></i>');

            $(document)
                .off('.resize')
                .on('mousedown.resize', 'i.resize', function(e) {
                    $('.jq-modal-act').data('jqModal').$box.data('dragResize')
                        .resize(e, this.className.replace('-resize resize', ''));
                });
        },
        menuClasschange: function($handle) {
            //删除同级的on Class效果
            if ($handle.closest('#topmenu').length) {
                $handle.closest('#topmenu').find('.on').removeClass('on');
                $handle.addClass('on');
            } else if ($handle.data('level')) {
                // 如果是多级菜单
                this.levelHandle($handle);
            } else if ($handle.closest('#main-hd').length) {
                $handle.addClass('on').siblings('.on').removeClass('on');
            }
        },
        /**
         * @method resizeWindows
         * @description 自动适应窗体高度
         * @param  {Object} el jq选择器
         * @param  {String} el.sizeForm jq选择器 要减去的高度
         * @param  {String} el.sizeTarget jq选择器 要设置的高度
         * @示例 调用
         * ```html
         *   <div data-size-form=".main" data-size-target=".bar"></div>
         * ```
         */
        resizeWindows: function(el) {
            var _self = this;
            var $el, eldata;

            if (el) {
                $el = $(el);
                eldata = $el.data();
            } else {
                // window
                $el = $(window);
                eldata = {
                    sizeFrom: '.header, .footer',
                    sizeTarget: '.aside, .main'
                };
            }

            if (_self.$area) {
                // 隐藏顶部菜单
                if (!_self.$area.find('.sub-menu').children().length) {
                    _self.$area.find('.sub-menu').parent().remove();
                }
                // 隐藏说明信息
                if (!$.trim(_self.$area.find('.promptUl').text())) {
                    _self.$area.find('.prompt').remove();
                } else {
                    _self.$area.find('.prompt').height(function(i, v) {
                        return v;
                    });
                }
            }

            if ($el.is('.jq-modal')) {
                // layout弹窗基本框架
                var oModal = $el.data('jqModal');
                $el.add(oModal.$bd).height(oModal.$box.outerHeight() - oModal.$hd.outerHeight());
                oModal = null;
                eldata = {
                    sizeFrom: '.main-hd, .filter, .oprate, [data-prompt-list="1"]',
                    sizeTarget: '.list-con'
                };
                // 表头宽度
                // $(window).trigger('resize.tbresize');
            }

            var $sizeFrom = el ? $el.find(eldata.sizeFrom) : $(eldata.sizeFrom);
            var $sizeTarget = el ? $el.find(eldata.sizeTarget) : $(eldata.sizeTarget);

            var H = 0;
            $sizeFrom.each(function() {
                H += $(this).outerHeight(true);
            });

            $sizeTarget.each(function() {
                var $this = $(this);
                // 新高度
                var nH = $el.height() - H - ($this.outerHeight(true) - $this.height());
                var parseH = nH;
                if ($this.is('.list-con')) {
                    var oH = $this.data('height') || 10000;
                    parseH = Math.min(oH, nH);
                    $this.find('form').css('min-height', parseH - 64);
                }
                $this.height(parseH);
            });
        },
        ajaxAbort: function(ajaxKey) {
            var _self = this;
            if (ajaxKey && _self.xhrPool[ajaxKey]) {
                // 终止指定
                _self.xhrPool[ajaxKey].abort();  //  aborts connection
                delete _self.xhrPool[ajaxKey]; //  removes from list by index   
            } else {
                // 终止全部
                $.each(_self.xhrPool, function(key, jqXHR) {   
                //  cycle through list of recorded connection
                    jqXHR.abort();  //  aborts connection
                    delete _self.xhrPool[key]; //  removes from list by index
                });
            }
        },
        /**
         * @method submit
         * @description ajax提交
         * @param  {Object} eldata $(this).data() `data`数据
         * @param  {String} eldata.role=submit 选择器类型
         * @param  {String} eldata.add 点击按钮动作为添加内容，值对应添加的字段名,例如,`data-add="{aname: 'name'}"`意思是在列表项上取`data-name`的值，并用`aname`值为标识提交到服务器
         * @param  {String} eldata.params ajax附加参数，如果是批量时，全部都一样，最终会与add合并
         * @param  {String} eldata.confirm 弹出提示，确定后直接请求ajax
         * @param  {Object} eldata.data-modal-opt 弹出提示，弹窗的配置
         * @param  {Object} eldata.data-modal-opt.text 弹窗的标题
         * @param  {Object} eldata.data-modal-opt.field 要提交的内容对应的是字段名
         * @param  {Boolean} eldata.close=0 提交后是否关闭弹窗, 调用在弹窗里调用启作用
         * @param  {Boolean|String} eldata.reload=0 提交后是否刷新当前操作区, 1 - 刷新当前操作区，string-刷新指定区域
         * @param  {Boolean} eldata.ajax 点击按钮时直接`ajax`请求
         * @param  {String} eldata.url `ajax`的`url`为空的找其`form`的`action`
         * @param  {String} eldata.ajaxTitle `ajax`请求时按钮的标题
         * @param  {Function} dataBefore 在数据整理前的动作
         * @param  {Function} before 在表单提交前的动作，些方法存在时表单不自动提交，请手动触发表单提交
         * @其它参数 见`select`方法
         * @示例 批量修改 `data-id="1", data-id="3"`
         * ```html
         *   <div class="list">
         *       <div class="item chked" data-id="1"></div>
         *       <div class="item"></div>
         *       <div class="item chked" data-id="3"></div>
         *   </div>
         *   <a data-role="submit" data-params="{a: 1, b: 2}">提交</a>
         * ```
         * @示例 批量添加 `data-id="1", data-id="3"`
         * ```html
         *   <div class="list">
         *       <div class="item chked" data-id="1" ></div>
         *       <div class="item"></div>
         *       <div class="item chked" data-id="3"></div>
         *   </div>
         *   <a data-role="submit" data-add="{fromid: 'id'}" data-params="{a: 1, b: 2}">提交</a>
         * ```
         * @示例 ajax请求, url不存在直接到页面上找
         * ```html
         *   <a data-role="submit" data-ajax="1" data-url="http://www.a.com" data-ajax-title="加载中..." data-params="{a: 1, b: 2}">提交</a>
         * ```
         * @示例 confirm后再ajax请求, url不存在直接到页面上找
         * ```html
         *   <a data-role="submit" data-confirm="你确定要执行吗？" data-url="http://www.a.com" data-ajax-title="加载中..." data-params="{a: 1, b: 2}">提交</a>
         * ```
         * @示例 modal-opt弹出表单填写后再ajax请求, url不存在直接到页面上找, 使用批量操作时一定要确定请求是新增(data-add)还是修改数据
         * ```html
         *   <a data-role="submit" data-modal-opt="{text: '你确定要执行吗？', field: 'remark'}" data-url="http://www.a.com" data-params="{a: 1, b: 2}">提交</a>
         * ```
         * @示例 在form里调用
         * ```html
         * <form action="xxxxx">
         *   <input type="button"  data-role="submit" data-type="post" data-close="1" data-reload="1" value="提交"/>
         * </form>
         * ```
         */
        submit: function(eldata) {
            var _self   = this;
            var $form   = eldata.$el.closest('form');
            var setting = _self.getSetting(eldata);
            var data    = setting.data;
            var ajaxUrl = setting.url;
            var tip     = eldata.tip * 1 === 0 ? 0 : 1;
            var submitFn = function(e, _data) {
                // 提交之前的动作
                $.ajax({
                    tip: tip,
                    url: ajaxUrl,
                    // 如果传过来data，优先使用
                    data: _data || data,
                    // 提交必须用POST
                    type: 'POST'
                })
                .done(function(d) {
                    if (d.status) {
                        if (d.jumpurl) {
                            location.href = d.jumpurl;
                        } else if ($form.length) {
                            if (eldata.close) {
                                // 注意顺序，先刷新，关闭后配置重新设置就不能刷新了
                                eldata.$el.closest('.jq-modal').jqModal('hide');
                            }
                            if (eldata.reload) {
                                // eldata.reload = '.aaa'
                                if (eldata.reload != 1) {
                                    // 刷新指定区域
                                    _self.flush({
                                        target: eldata.reload
                                    });
                                } else {
                                    // 刷新默认区域
                                    // 仅刷新
                                    var isAdd = !$form.data('id');
                                    // add时不需要保留分页
                                    _self.flush({
                                        target: eldata.target,
                                        isAdd: isAdd
                                    });

                                }
                            }
                        } else {
                            // 列表时 仅刷新
                            _self.flush({
                                target: eldata.target
                            });
                        }
                    }
                })
                .always(function() {
                });
            };

            if ($form.length) {
                // 表单验证通过
                if ($form.valid()) {
                    // 表单提交
                    $form
                        .off('.submit')
                        .on('submitFn.submit', submitFn);

                    if ($form.hasEvent('dataBefore')) {
                        $form.trigger('dataBefore');
                    }
                    var serializeDataParams = utils.objectToSerializeArray(data);
                    // 注意反转，保存数据如果有相同的name时后面的起作用
                    var serializeData = $form.find('input, select, textarea')
                                            .serializeArray().reverse();
                    // 过滤掉name相同的数据
                    data = utils.unique(serializeData.concat(serializeDataParams), 'name');
                    // 有绑定函数，就不提交在before提交表单
                    if ($form.hasEvent('before')) {
                        $form.trigger('before');
                        return;
                    }
                    $form.trigger('submitFn');
                } else {
                    // 第一个聚焦
                    $form.data('validator').focusInvalid();
                }
            } else {
                // 批量和行内操作

                var aid = eldata.$el.closest('[data-id]').data('id');
                var ids;

                if (aid) {
                    // 行内编辑
                    ids = aid;
                } else {
                    // 批量
                    ids = _self.getListData();
                }

                // ajax 注意顺序
                if (eldata.ajax) {
                    var oldTitle = eldata.$el.html();
                    if (eldata.ajaxTitle) {
                        eldata.$el.html(eldata.ajaxTitle);
                    }
                    $.ajax({
                            tip: tip,
                            url: ajaxUrl,
                            type: 'GET',
                            data: data
                        })
                        .always(function() {
                            eldata.$el.html(oldTitle);
                        });
                    return false;
                }

                if (!ids) {
                    $.jqModal.tip('请选择要操作的内容！');
                    return false;
                }

                // confirm
                if (eldata.confirm) {
                    data._id = ids;
                    $.jqModal.confirm({
                        text: eldata.confirm == 1 ? '确定要执行吗？' : eldata.confirm,
                        accept: function() {
                            var oldTitle = eldata.$el.html();
                            if (eldata.ajaxTitle) {
                                eldata.$el.html(eldata.ajaxTitle);
                            }
                            $.ajax({
                                tip: tip,
                                url: ajaxUrl,
                                type: 'GET',
                                data: data
                            })
                            .done(function(d) {
                                if (d.status) {
                                    $.jqModal.alert(d.info, 'success');
                                }
                            })
                            .always(function() {
                                eldata.$el.html(oldTitle);
                            });
                        }
                    });
                    return false;
                }

                // 保存需要拆分的字段, 作为批处理，其它字段就全部相同
                var _aBatch = []; 
                if (eldata.add) {
                    // 批量添加 
                    if (utils.parseJSON(eldata.add)) {
                        
                        // 批量添加多个字段信息 data-add="{formid: 'id', name: 'name'}"
                        $.each(utils.parseJSON(eldata.add), function(k, v) {
                            _aBatch.push(k);
                            data[k] = _self.getListData(v);
                        });
                    } else {
                        // 批量添加单个字段 data-add="formid"
                        _aBatch.push(eldata.add);
                        data[eldata.add] = ids;
                    }
                } else {
                    // 批量修改
                    _aBatch = ['id'];
                    data['id'] = ids;
                }

                // 删除多余的设置
                var siderCategory = $('[data-sider-category]').eq(0).data('sider-category');
                if (data[siderCategory] && eldata.name != siderCategory) {
                    delete data[siderCategory];
                }

                data[eldata.name] = eldata.value;
                // 表示表单验证只验证传入（指定）的字段
                data._is_appointed = 1;
                // 表示是批量操作
                data._batch = _aBatch.join(',');

                // 弹窗配置
                if (eldata.modalOpt) {
                    // 发送短信配置
                    var modalOpt = utils.parseJSON(eldata.modalOpt);
                    $.jqModal.confirm({
                        text: modalOpt.text,
                        template: '<div class="h">{text}</div>\
                                    <div class="b">\
                                        <textarea cols="30" rows="3"></textarea>\
                                    </div>',
                        done: function() {
                            // 清空原有的数据
                            this.find('textarea').val('');
                        },
                        accept: function() {
                            var info = this.find('textarea').val();
                            if (info) {
                                data[modalOpt.field] = info;
                                submitFn();
                                return true;
                            } else {
                                $.jqModal.tip('请输入内容！！！');
                                return false;
                            }
                        }
                    }, {
                        head: '提示！！！！'
                    });
                } else {
                    submitFn();
                }
            }

            // return false;
        },
        tableFixed: function() {
            // 没有配置不执行
            if (!this.param) {
                return;
            }
            var $filter = this.$area.find(this.param.fixed);
            var $list = this.$area.find(this.param.list);
            var $fixed = $list.find('tr').eq(0);

            var $filterTbWrap = $filter.find('.table-wrap');
            // debugger;

            if ($filterTbWrap.length) {
                $filter.find('.chked').removeClass('chked');
            } else {
                $filterTbWrap = $('<div class="table-wrap">').appendTo($filter);

                $('<table>').appendTo($filterTbWrap)
                    .html($fixed.clone())
                    .addClass($list.find('table').attr('class'));
            }
            // 重置浮动的表格头宽度
            var setTbWidth = function() {
                // $filterTbWrap.width($list.find('table').eq(0).width());
                var diffValue = $list.width() - $list.find('table').eq(0).width();
                $filterTbWrap.css('padding-right', diffValue);
            };
            // 做延迟才能获取准确宽度
            setTimeout(setTbWidth, 0);
            $(window).off('.tbresize').on('resize.tbresize', setTbWidth);
            // 始终隐藏原th
            if (!$list.hasClass('table-inline')) {
                $fixed.find('th')
                    .html(function(i, c) {
                        return '<div style="overflow: hidden;height: 0">'+ c +'</div>';
                    })
                    .removeAttr('data-sort')
                    .css({
                        height: 0,
                        paddingTop: 0,
                        paddingBottom: 0
                    });
            }

        },
        setRoute: function(i, str) {
            var _self = this;
            _self.noRoute = true;
            utils.route(i, str);
            // 调整_self.noRoute和hashchange的执行顺序
            setTimeout(function() {
                _self.noRoute = false;
            }, 0);
        },
        /**
         * @method route
         * @description 路由器
         * @param  {Object} eldata $(this).data() data数据
         * @param  {String} eldata.role 选择器类型
         * @param  {String} eldata.route 路由的标识，就是显示在浏览器地址上的标识，实际请求的地址还是`href`的地址
         * @param  {bealean} eldata.routeType 路由类型，`noRoute` 地址栏不更新路由标识只记录，一般是顶级菜单使用 `onlyHashChange` 不请求，只更新地址栏链接标识
         * @param  {number} eldata.routeIndex=0 路由索引，当前标识更新到对应索引的路由值, 从0开始
         * @param  {String} eldata.target 返回结果后需要替换`target`里的内容， 不存在时找其上级的`data-target`
         * @示例 单个设置target
         * ```html
         *   <a data-role='route' data-target=".target" data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航</a>
         * ```
         * @示例 批量设置target
         * ```html
         * <div data-target=".target">
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航1</a>
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航2</a>
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航3</a>
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航4</a>
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航5</a>
         * </div>
         * ```
         * @示例 地址栏不记录route
         * ```html
         *   <a data-role='route' data-route-type="noRoute" data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航</a>
         *   执行前
         *   http://www.a.com
         *   执行后，没变化但内部已经做了ajax请求
         *   http://www.a.com
         * ```
         * @示例 地址栏记录route
         * ```html
         *   <a data-role='route' data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航</a>
         *   执行前
         *   http://www.a.com
         *   执行后
         *   http://www.a.com#/111
         * ```
         * @示例 地址栏route的位置0
         * ```html
         *   <a data-role='route' data-route-index="0" data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航</a>
         *   执行前
         *   http://www.a.com
         *   执行后
         *   http://www.a.com#/111
         * ```
         * @示例 地址栏route的位置
         * ```html
         *   <a data-role='route' data-route-index="1" data-route='#/111' href='/newcore/web/app_dev.php/house/mnewsmanage/'>导航</a>
         *   执行前
         *   http://www.a.com
         *   执行后
         *   http://www.a.com#/default/111
         * ```
         */
        route: function(eldata) {
            var _self = this;
            if (eldata.routeType == 'onlyHashChange') {
                location.hash = eldata.route;
            } else {
                // 锁定路由，地址栏改变时不去触发hashchange
                _self.menu(eldata.$el, function() {
                    var $target = $(_self.getTarget(eldata));
                    // 触发下级
                    _self.triggerNext($target);

                    // 设置路由
                    eldata.routeIndex = eldata.routeIndex || 0;
                    if (eldata.routeType == 'noRoute') {
                        // 只记录
                        _self.arrRouteParams[eldata.routeIndex] = eldata.route;
                        return;
                    }
                    // 一级没有就给一个默认的
                    // _self.arrRouteParams[0] = _self.arrRouteParams[0] || 'default';
                    // 删除之后的
                    _self.arrRouteParams.splice(eldata.routeIndex, _self.arrRouteParams.length);
                    // 设置当前的
                    _self.arrRouteParams[eldata.routeIndex] = eldata.route;

                    _self.setRoute(1, _self.arrRouteParams.join('/'));
                });
            }
        },
        menu: function($el, fn) {
            if (!$el.length) {
                return;
            }
            // debugger;
            var _self = this;
            // 路由时需要清空原来的设置
            delete _self.param;
            var eldata = _self.middleware({currentTarget: $el, isJq: 1});
            // 如果是jump直接点击
            if (eldata.role == 'jump') {
                // $el.click();
                _self.jump(eldata);
                return false;
            }
            // 类型
            var menuType = $el.closest('[data-menu="1"]').length ? 'header' : 'aside';
            // ajax配置
            var setting = _self.getSetting(eldata);
            // console.log(setting);
            var _setting = {
                // 启用缓存
                localCache: menuType == 'header',
                // localCache: false,
                // 缓存时间(h)
                cacheTTL: 24,
                // 缓存变量
                cacheKey: _self.cacheKeyPre + eldata.route,
                // 在缓存前处理数据
                onCache: function(sData) {
                    // 替换掉前缀
                    var reg = new RegExp(BASE_URL, 'g');
                    return sData.replace(reg, 'BASE_URL');
                },
                // 在返回数据前处理数据
                onResponse: function(sResult) {
                    return sResult.replace(/BASE_URL/g, BASE_URL);
                }
            };

            $.extend(true, _setting, setting);
            var $target = $(_self.getTarget(eldata));
            var done = function(d) {
                    if (menuType != 'header') {
                        // 移除日期插件
                        $('[data-date]').each(function() {
                            if ($(this).data('daterangepicker')) {
                                $(this).data('daterangepicker').remove();
                            }
                        });

                    }
                    if ($.type(d) == 'string') {
                        try {
                            $target.empty().html(eldata.part ? $(d).find(eldata.part).html() : d);
                        } catch (err) {
                            console.warn(err);
                        }
                    } else if (eldata.tpl) {
                        // 左侧菜单
                        $target.empty().html(template(eldata.tpl.slice(1), d));
                    }
                    // 高亮当前menu
                    _self.menuClasschange($el);

                };

            var always = function() {
                    // 内容是列表时执行
                    _self.setParam($target, function(isList) {
                        if (isList) {
                            // 处理表格头fixed
                            _self.tableFixed();
                            // 处理分页
                            _self.page();
                        }
                    });

                    if (fn) {
                        fn.call();
                    }

                    _self.resizeWindows('[data-content="1"]');
                    // 处理底部的说明
                    _self.markdown($target);
                };

            try {
                // 停止掉所有ajax请求
                _self.ajaxAbort();
                $.ajax(_setting).done(done).always(always);
            } catch(err) {
                console.warn(err);
            }
        },
        triggerNext: function($target) {
             $target.find('[data-trigger-next=1] a[data-level=1]').eq(0).click();
        },
        // noRoute 表示地址栏改变时不触发hashchange事件
        router: function() {
            var _self = this;
            /**
             * 执行下级路由
             * @param  {Object} $thisRoute 下级的路由标识对应的jquery对象
             * @param  {String|Number} nextRoute  下下级的路由标识
             */
            var triggerRoute = function(arrRouteParams, i) {
                i = i || 0;
                // 遍历完就停止
                if (!arrRouteParams[i]) {
                    return;            
                }
                // 排除掉不路由的
                var $route = $('[data-route="' + arrRouteParams[i] + '"]')
                                .not('[data-route-type="onlyHashChange"]');
                if ($route.length) {
                    if ($route.hasClass('on')) {
                    // 如是已经是激活的, 跳过menu处理，直接进入下一个（menu）路由标识处理
                        triggerRoute(arrRouteParams, i + 1);
                    } else {
                        _self.menu($route, function() {
                            // 需要触发下下一级
                            if (!arrRouteParams[i + 1]) {
                                _self.triggerNext($(_self.getTarget($route)));
                            }
                            triggerRoute(arrRouteParams, i + 1);
                        });
                    }
                } else if (!$route.length) {
                    // 没有找到就返回首页
                    triggerRoute([_self.homePage]);
                }
            };

            var parseRoute = function() {
                // 获取参数
                var arrRouteParams = utils.route();
                // 删除第一个#
                arrRouteParams.shift();

                // 顶部菜单
                try {
                     if (!arrRouteParams.length) {
                        arrRouteParams = [_self.homePage];
                    }
                    // 保存下当前的路由，手动点击二级菜单时要用,
                    // 一定要是复制，否则arrRouteParams的变动会影响_self.arrRouteParams
                    _self.arrRouteParams = arrRouteParams.slice();

                    /*if (arrRouteParams[0] == 'default') {
                        // 第一级是左侧时, 把第一级去掉，从第二级开始
                        arrRouteParams.shift();
                    }*/


                    triggerRoute(arrRouteParams);
                } catch (err) {
                    console.warn(err);
                }

            };
            // 调用
            parseRoute();
            // 监听hash
            $(window).off('.hash').on('hashchange.hash', function() {
                if (!_self.noRoute) {
                    console.log('地址栏路由已改变！');
                    // 延迟防止连续change 影响数据请求错误
                    parseRoute();
                } 
            });
        },
        /**
         * @method delete
         * @description 删除信息
         * @param  {Object} eldata `data`数据
         * @param  {String} eldata.role 选择器类型
         * @param  {String} eldata.id 父级有`data-id`时属于单条删除,否则为批量删除
         * @其它参数 见`select`方法
         * @示例 调用 单条删除
         * ```html
         * <tr data-id="1">
         *   <a data-role="delete">删除</a>
         * </tr>
         * ```
         * @示例 调用 批量删除 在`param.list`列表外面为批量删除
         * ```html
         * <a data-role="delete">批量删除</a>
         * <section data-param="{
         *       defUrl    : 'aaa/list',
         *       showUrl   : 'aaa/show',
         *       submitUrl : 'aaa/save',
         *       deleteUrl : 'aaa/delete',
         *       fixed     : '.filter',
         *       list      : '.list-con',
         *       data      : {}
         *   }">
         * <div class="list-con">
         * <table class="list">
         *   <tr data-id="1">
         *       标题标题标题
         *   </tr>
         * </table>
         * </section>
         * </div>
         * ```
         */
        delete: function(eldata) {
            var _self = this;
            var $row = eldata.$el.closest('[data-id]');
            // setting只做ajax配置使用，其它参数仍使用eldata处理
            var setting = _self.getSetting(eldata);
            var ids;

            if ($row.length) {
                // 单条
                ids = $row.data('id');
            } else {
                // 批量
                ids = _self.getListData();
            }

            if (!ids) {
                $.jqModal.tip(eldata.txt || '请选择您要删除的内容！');
            } else {
                setting.data.id = ids;
                $.jqModal.confirm({
                    text: eldata.txt || '确定要删除数据吗？',
                    accept: function() {
                        $.ajax({
                                url: setting.url,
                                data: setting.data,
                                type: 'DELETE'
                            })
                            .done(function(d) {
                                if (d.status) {
                                    _self.flush({
                                        target: eldata.target
                                    });
                                }
                            })
                            .always(function() {
                            });
                    }
                });
            }
        },
        /**
         * @method getListData
         * @description 获取已经选择列表的数据
         * @param  {String} field=id 字段名
         * @param  {String} item=".chked" 列表项,其上面带有field对应的参数
         * @return  {String} id字串以`,`号连接
         * @示例 调用
         * ```html
         * <ul class="list">
         *   <li data-id="1" class="chked" data-name="列表1">列表1</li>
         *   <li data-id="2"  data-name="列表2">列表2</li>
         *   <li data-id="3" class="chked" data-name="列表3">列表3</li>
         * </ul>
         * ```
         * ```js
         *   var sId;
         *   sId = getListData('name', '.chked');
         *   console.log(sId) // '列表1,列表3'
         * ```
         */
        getListData: function(field, item) {
            // console.log(this.$area.find(this.param.list).find(item || '.chked'));
            return this.$area.find(this.param.list).find(item || '.chked')
                .map(function() {
                    return $(this).data(field || 'id') || undefined;
                }).get().join(',');
        },
        /**
         * @method markdown
         * @description markdown转换成html
         * @param  {Object} $target jquery对象，从该中查找`.markdown` `.md`的内容，并让内容转到成html
         * @示例 调用
         * ```html
         * <div class="md">
         *   # 标题
         * </div>
         * ```
         * ```js
         *   main.markdown($('.md'))  // 生成 <div class=".md"><h1>标题</h1></div>
         * ```
         */
        markdown: function($target) {
            var reg = new RegExp("\\/\\/\\s*@require\\((\"|\')markdown(\"|\')\\)", 'g');
            var htmlFn = function(i, con) {
                            // 处理兼容原来的说明 如果是promptUl, 内容里需要有 `// @require('markdown')` 才会解析
                            if ($(this).hasClass('promptUl')) {
                                // 是否启用markdonw
                                // console.log(i+'\n'+con)
                                var requireMarked = reg.test(con);
                                // var requireMarked = /\/\/ require('markdown')/.test(con);
                                return requireMarked && marked(con.replace(reg, '')) || con;
                            } else {
                                return marked(con);
                            }
                        };

            if (!$target.hasClass('markdown') && !$target.hasClass('md')) {
                $target = $target.find('.promptUl, .markdown, .md');
            }

            $target.html(htmlFn).addClass('markdown');
        },
        getTarget: function(eldata) {
            // 判断是否是jquery对象
            if (eldata instanceof jQuery) {
                return eldata.data('target') || eldata.closest('[data-target]').data('target');           
            } else {
                return eldata.target || eldata.$el.closest('[data-target]').data('target');
            }
        }
    };

    out = new Manage();

    module.exports = out;
});
