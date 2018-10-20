define(['ajax'], function (require, exports, module) {
    var ajax = require('ajax');
    $('.bar-tab .tab-item').removeClass('active')
        .siblings('a[href="' + (decodeURI(location.pathname.slice(1)) || 'index.html') + '"]').addClass('active');
    $(document)
        .off('.sm')
        //当为编辑是, 则自动判断其id, 显示title
        .ready(function(){
            var scek = $('[data-id]').find(':checked')
            if($(scek).length>0){
                $(scek).each(function(index, item) {
                    $('[data-select='+$(item).attr("name")+']').val($(this).parent().find('.item-title').text())
                });
            }
            $('[data-select]').removeAttr('name');
        }) 
       //以下代码为ajax提交,使用form提交, 按钮增加id为btn即可
       .on('click', '#btn', function(e) {
            var $this = $(this),
                $form = $this.closest('form'),
                data = {},
                eldata = $this.data();
            if ($form.length) {
                data = $form.serializeArray();
                eldata.type = 'POST';
            }
            ajax({
                url: eldata.url || $form.attr('action'),
                data: data,
                type: eldata.type,// || 'GET',
                dataType: 'json',
                success:function(result, that){
                if(result.status) {
                    $.toast(result.info);
                    if (result.url) {
                        location.href = result.url;
                    } else {
                        // location.href = baseUrl + '/house';
                    }
                    $form[0].reset();
                } else{
                     $.toast(result.info);
                }
                }
            })
            e.stopPropagation();
            return false;
        })
        //ajax提交代码结束
        .on('show', '.tab', function() {
            // $(this).find('.list-block').scroller()
        })
        .on('click', '.level-2 .all', function(e) {
            $(this).siblings('li').find(':checked').prop('checked', '');
            return false;
        })
        .on('click', '.close-modal', function(e) {
            $.closeModal();
            return false;
        })
        .on('click', '.tab-link2', function(e) {
            var $this = $(this);
            var $target = $($this.attr('href'));
            var $parent = $this.parent();
            var $row = $this.closest('.row');
            $parent.addClass('act').siblings('li').removeClass('act')
            if (!$parent.hasClass('all')) {
                $row.removeClass('row-all');
                $target.show().siblings('.list-block').hide();
            } else {
                $row.addClass('row-all');
                $target.hide().siblings('.list-block').hide();
            }
            // $.refreshScroller();
            return false;
        })
        .on('click', '.jump', function(e) {
            var $this = $(this);
            var $level = $this.closest('.level');
            var $next = $($this.attr('href'));

            /*if ($this.hasClass('no')) {
                $level.find(':checked').prop('checked', '')    
            }*/
            if ($this.hasClass('yes')) {
                var levelID = $level[0].id;
                var sSelected = $level.find(':checked').map(function() {
                    return $(this).parent().find('.item-title').text();
                }).get().join(',');
                // 同分类下设置
                if ($this.hasClass('remove') && sSelected) {
                    $('[data-same]').find('.item-after').text('不限')
                };
                if (sSelected) {
                    $('a[href="#'+ levelID +'"]').find('.item-after').text(sSelected)
                }
            }

            if ($level.hasClass('level-1')) {
                $level.addClass('page-from-center-to-left').animationEnd(function(ev) {
                    $(this).removeClass('page-from-center-to-left').hide();
                })
                $next.addClass('page-from-right-to-center').animationEnd(function(ev) {
                    $(this).removeClass('page-from-right-to-center');
                }).show()
            } else if ($level.hasClass('level-2')) {
                $level.addClass('page-from-center-to-right').animationEnd(function(ev) {
                    $(this).removeClass('page-from-center-to-right').hide();
                })
                $next.addClass('page-from-left-to-center').animationEnd(function(ev) {
                    $(this).removeClass('page-from-left-to-center');
                }).show()
            }
            // debugger;
            return false;
        })
        .on('click', '.more-btn', function() {
            var $this = $(this),
                eldata = $this.data(),
                html = $this.html();
            if (!eldata.up) {
                $this.data('up', html)    
                eldata.up = html;
            }

            $(eldata.target || $this.attr('href')).toggleClass(eldata.class)
            $this.html(html == eldata.up && eldata.down || eldata.up);

            if (eldata.thisClass) {
                $this.toggleClass(eldata.thisClass)
            }
            return false;
        })
        .on('click', '.label-switch-password', function() {
            var $checkbox = $(this).find('input');
            var $input = $checkbox.closest('.item-inner').find('.item-input input');
            $input.attr('type', $checkbox.prop('checked') ? 'text' : 'password');
            $checkbox.prop('checked', !$checkbox.prop('checked'));
        })
        .on('input', '.input', function() {
            $(this).closest('.item-inner').find('.clear-txt').show();
        })
        .on('click', '.clear-txt', function() {
            $(this).hide().closest('.item-inner').find('.item-input input').val('');
        })
        .on('click', '[data-select]', function(e) {
            e.preventDefault();
            var $this = $(this);
            var ID = $this.data('select');
            var sname= $('form').find('input[name='+ID+']');
            var _checked = [];
            if (sname.length == 0) {
                $(this).after('<input type="hidden" name='+ ID +'><br>');
            } else {
                _checked = sname.val().split(',');
            };
            // if ($.device.isWeixin && $.device.android ) {
                /*jshint validthis:true */
                // $this.focus();
                // $this.blur();
            // }
            
            var sID = $('[data-id='+ID+']');
            // 先清除已选项
            sID.find('input').removeAttr('checked');
            // 选中已选择项
            $.each(_checked, function (k, v) {
                if (v) {
                    sID.find('input[value="'+ v +'"]').attr('checked' , 'checked');
                };
            });
            var isMultiple = $this.data('multiple');
            var title = '请选择' + $this.closest('.item-inner').find('.label').text();
            var header = '<header class="bar bar-nav">\
                            ' +  (isMultiple ? '<button class="button button-link button-' + ID + ' pull-right"> 确定 </button>' : '') + '\
                                <h1 class="title">'+ title +'</h1>\
                            </header>';
            var modal = $.modal({
                text: '<div class="select-modal-inner">\
                            '+ header +'\
                            '+ $(sID).html() +'\
                        </div>',
                modalCloseByOutside: 1,
                extraClass: 'select-modal'
            })
            $(modal).data('id', ID).addClass(isMultiple ? 'multiple' : 'radio');
            e.stopPropagation();
            return false;
        })
        .on('click', '.select-modal .button-link', function() {
            var $this = $(this);
            var $selectModal = $this.closest('.select-modal');
            var ID = $selectModal.data('id');
            var sSelected = $selectModal.find(':checked').map(function() {
                    return $(this).parent().find('.item-title').text();
                }).get().join(',')
            var sSelectedval = $selectModal.find(':checked').map(function() {
                    return $(this).parent().find('input').val();
                }).get().join(',');
            if (sSelected) {
                if ($('input[data-select="'+ ID +'"]').length) {
                    $('input[data-select="'+ ID +'"]').val(sSelected);
                } else {
                    $('[data-select="'+ ID +'"]').html(sSelected)
                };
                $('input[name="'+ ID +'"]').val(sSelectedval)
            }
            $.closeModal($selectModal);
            return false;
        })
        .on('click', '.radio .label-checkbox', function() {
            var $this = $(this);
            var $selectModal = $this.closest('.select-modal');
            var ID = $selectModal.data('id');
            var sSelected = $(this).parent().find('.item-title').text();
            var sSelectedval = $(this).find('input').val();
            if (sSelected) {
                if ($('input[data-select="'+ ID +'"]').length) {
                    $('input[data-select="'+ ID +'"]').val(sSelected);
                } else {
                    $('[data-select="'+ ID +'"]').html(sSelected)
                };
                $('input[name="'+ ID +'"]').val(sSelectedval)
            }
            $.closeModal($selectModal);
            return false;
        })
        .on('click', '.back', function () {
            // 返回上一页
            if (window.history.length > 0) {
                window.history.go(-1);
            } else {
                return;
            }
        }).on('click', '[data-copy]', function () {
            // 使用方法 <a data-copy="选择内容id">点击复制</a>
            var $this = $(this);
            var data = $this.data();
            var _selectTag = data.copy;
            var elem = document.getElementById(_selectTag);
            /*复制代码到剪切板*/
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // 如果是input标签或textarea，则直接指定该节点
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // 如果不是，则使用节点的textContent
                target = document.getElementById(targetId);
                if (!target) {
                    // 如果不存在，则创建一个
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // 聚焦目标节点，选中它的内容
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // 进行复制操作
            var succeed;
            try {
                succeed = document.execCommand("copy");
                $.alert('复制成功!');
            } catch(e) {
                $.alert('暂不支持此功能，请手动复制!');
                succeed = false;
            }
            // 不再聚焦
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }

            if (isInput) {
                // 清空临时数据
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // 清空临时数据
                target.textContent = "";
            }
            // return succeed;
        }).on('click', '[data-logout]', function (e) {
            // 模拟跳转
            var loop = true;
            e.preventDefault();
            if (loop) {
                loop = false;
                $.confirm('确定要退出?', function () {
                    ajax({
                        url: BASE_URL + '/logout',
                        dataType: 'json',
                        type: 'GET',
                        data: {
                            _isApp: 1
                        },
                        success: function (data) {
                            // $(document).find('.return-index').click();
                        },
                        error: function (e) {
                            // $(document).find('.return-index').click();
                        },
                        complete: function () {
                            location.href = '../index/index.html';
                            loop = true;
                        }
                    })
                })
            };
        }).on('click', '[data-delete]', function (e) {
            var $this = $(this);
            var _data = $this.data();
            var _item = $this.closest(_data.item || 'li')
            var _deleteHref = _data.delete;
            $.confirm('确定要删除吗？', function (e) {
                ajax({
                    url: _deleteHref,
                    dataType: 'json',
                    type: 'get',
                    success: function (data) {
                        $.alert(data.info);
                        _item.remove();
                    },
                    error: function () {
                        $.alert('删除失败！');
                    }
                })
            });
            return false;
        }).on('click', '.ajax-error', function () {
            // 重新加载
            location.reload();
        })

        // 统计
        var _getcount = $('[data-counts]');
        if (_getcount.length && crossDomain) {
            require.async(['getcounts'], function (getcounts) {
                getcounts.init({
                    url: BASE_URL + '/house/igetcounts/index'
                });
            })
        };

        // 按需加载列表
        var modules = {
            // 手机统计
            // getcounts: function (getcounts) {
            //     if(crossDomain){
            //         getcounts.init({url: BASE_URL + '/house/igetcounts/index'});
            //     }
            // },
            // 手机收藏
            collect: function (collect) {}
        }

        // 按需加载
        $.each(modules, function(k, v) {
            var _k = k.substr(1).toLowerCase();
            var $k = $('[data-'+ _k +']');
            if ($k.length) {
                require.async(k, v)
            };
        });

        $("input[date-picker]").datetimePicker({
            // value: ['2016', '06', '06']
        });
        $('input[data-select]').prop('readonly', true);
        // 显示隐藏
        $('[data-more-attr]').click(function () {
            var $this = $(this);
            var _obj = $this.data('moreAttr');
            var _open = $this.data('open');
            var _close = $this.data('close');
            if ($(_obj).css('display') == 'none') {
                $(_obj).show();
                $this.removeClass('more-attr-close').addClass('more-attr-open').html(_close);
            } else {
                $(_obj).hide();
                $this.removeClass('more-attr-open').addClass('more-attr-close').html(_open);
            };
        });
        var backBtn = '';
        function plusReady(){
            // 监听键按下事件
            plus.key.addEventListener("keydown",function(e){
                console.log("keydown: "+e.keyCode);
                if (!backBtn) {
                    if ($('.back').length) {
                        $('.back').click();
                    } else {
                        backBtn = new Date().getTime();
                        $.toast('再按一次退出应用');
                    }
                    setTimeout(function() {
                        backBtn = null;
                    }, 1000);
                } else {
                    if (new Date().getTime() - backBtn < 1000) {
                        plus.runtime.quit();
                    }
                }
            },false);
        }
        if(window.plus){
            plusReady();
        }else{
            document.addEventListener("plusready",plusReady,false);
        }

    $.init();
})

