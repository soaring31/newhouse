/*!
 * @autor: Ahuing
 * @name: zepto.autocomplete
 * @date: 2017-10-24
 * @modify: 2017-10-24
 */

!function ($, win, undefined) {
    'use strict';
    var ZeptoAutocomplete = function(options) {
            this._clear();
            if (!this._isDataSourceDefined(options)) {
                return;
            }

            if (this._isLocal(options)) {
                this._initLocal(options);
                return;
            }
            if (this._isRemote(options)) {
                this._initRemote(options);
            }
    }

    ZeptoAutocomplete.prototype = {
        init: function(options) {
            this.limit           = options.limit;
            this._data           = options.data;
            this.el              = options.el;
            this.template        = options.template;
            this.remoteTimeout   = options.remoteTimeout;
            this.onItemClick     = options.onItemClick;
            this.getNodataTpl    = options.getNodataTpl;
            this.getItemTpl      = options.getItemTpl;
            this.onSearchBefore  = options.onSearchBefore;
            this.resultContainer = $('<div class="autocomplete-result"></div>').insertAfter(this.el);
            this.caseSensitive   = typeof options.caseSensitive !== 'undefined' ? options.caseSensitive: true;
            this.showFunction = typeof options.showFunc == 'function' ? options.showFunc: function() {
                this.resultContainer.show();
            };
            this.hideFunction = typeof options.hideFunc == 'function' ? options.hideFunc: function() {
                this.resultContainer.hide();
            };
            this._setSelectionRange();

            options.onInited && options.onInited.call(this.el);
        },
        _clear: function() {
            this.limit = 2;
            this._data = '';
            this.remoteTimeout = 3000;
        },
        _setSelectionRange: function() {
            $(this.el).click(function(event) {
                this.setSelectionRange(0, this.value.length);
            });
        },
        _isDataSourceDefined: function(options) {
            return typeof options !== "undefined" && typeof options.datasource !== "undefined" && options.datasource !== "" && options.datasource;
        },
        _isRemote: function(options) {
            return options.datasource === 'remote' && typeof options.data !== "undefined" && options.data;
        },
        _isLocal: function(options) {
            return options.datasource === 'local' && typeof options.data !== "undefined" && $.isArray(options.data);
        },
        _initLocal: function(options) {
            this.init(options);
            var searchTextField = $(options.el);
            var _this = this;
            searchTextField.bind("keyup", $.proxy(this._handleLocalSearch, _this));
        },
        _initRemote: function(options) {
            this.init(options);
            var searchTextField = $(options.el);
            var _this = this;
            searchTextField.bind("keyup", $.proxy(this._handleRemoteSearch, _this));
        },
        _isWithinLimit: function(message) {
            return message !== undefined && message.length > this.limit;
        },
        _handleLocalSearch: function() {
            var message = $(this.el).val();
            if (!this._isWithinLimit(message)) {
                this._clearResults();
                return;
            }
            this._successHandler(this.data.filter(function(i) {
                if (!this.caseSensitive) {
                    return i.toLowerCase().indexOf(message.toLowerCase()) > -1;
                } else {
                    return i.indexOf(message) > -1;
                }

            }));
        },
        _handleRemoteSearch: function(evt) {
            var message = $(this.el).val();
            var url = this._data + message;

            if (!this._isWithinLimit(message)) {
                this._clearResults();
                return;
            }
            this.onSearchBefore.call(this);
            this.ajax && this.ajax.abort();
            this.ajax = $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'json',
                    success: $.proxy(this._successHandler, this),
                    error: function(err) {
                        console.log('Request failed to load suggestions.');
                    },
                    timeout: this.remoteTimeout
                });
        },
        _successHandler: function(data) {
            var _this = this;
            var autocompleteHTML = ['<ul>'];

            if (data.suggestions && data.suggestions.length <= 0) {
                autocompleteHTML.push(_this.getNodataTpl());
            } else {
                $.map(data.suggestions,
                    function(listItem) {
                        autocompleteHTML.push(_this.getItemTpl(listItem))
                    });
            }
            autocompleteHTML.push('</ul>');
            _this.resultContainer.html(autocompleteHTML.join(' '));

            $('.autocomplete-result .zept-auto').on('click',
                function(evt) {
                    var selectedValue = $(this).text();
                    $(_this.el).val(selectedValue);
                    _this.onItemClick && _this.onItemClick.call(this, _this);
                    _this._clearResults();
                });
            _this.showFunction();
        },
        _clearResults: function() {
            this.resultContainer.html('');
            this.hideFunction();
        }
    }; 
    // 默认设置
    ZeptoAutocomplete.DEFAULTS = {
        limit         : 0, // 输入几个字符开始搜索
        datasource    : '', // local remote
        remoteTimeout : 3000, // 请求超时
        
        onItemClick   : null, // 列表项点击时的回函
        onInited      : null, // 初始化完成后的回函
        onSearchBefore: null, // 搜索之前的回函
        getNodataTpl : function() { // 没有数据时的提示
                        return "<li class ='zept-auto' data-id=''>没有内容！</li>";
                    },
        getItemTpl   : function(item) { // 列表项的模板
                        var _data = item.data;
                        return "<li class ='zept-auto' data-id='" + _data.id + "'>" + _data.name + "</li>";
                    }
    };

    function Plugin(option, arg) {

        return this.each(function() {
            var $this = $(this),
                data = $this.data('ZeptoAutocomplete'),
                options;

            if (!data) {
                options = $.extend({}, ZeptoAutocomplete.DEFAULTS, {el: this}, $this.data(), typeof option == 'object' && option);

                $this.data('ZeptoAutocomplete', data = new ZeptoAutocomplete(options))
                options = null;
            }
            if (typeof option == 'string') {
                data[option](arg)
            }
        })
    }

    var old = $.fn.autocomplete;

    $.fn.autocomplete             = Plugin
    $.fn.autocomplete.Constructor = ZeptoAutocomplete;

    $.fn.autocomplete.noConflict = function () {
        $.fn.autocomplete = old
        return this
    }

}($, window);
//# sourceMappingURL=zepto.autocomplete.js.map
