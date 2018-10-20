/**
 * author : Ahuing
 * date   : 2017-04-26
 * name   : webupload
 * modify : 2017-05-03
 */
// @require('babel');

define(['webuploader', 'webuploadCss', 'utils', 'sortable'], function(require, exports, module) {
    var utils = require('utils');
    var out = {
        init: function(o) {
            var _self = this;
            var $wrap = _self.$wrap = $(o.wrap);

            var _opt = _self.opt = $.extend(true, {}, {

                // 选完文件后，是否自动上传。
                auto: true,

                // swf文件路径
                swf: jsbase + '/core/js/Uploader.swf',

                // 文件接收服务端。
                server: 'http://webuploader.duapp.com/server/fileupload.php',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: {
                    id: $wrap.find('.filePicker')[0]
                },
                // 多个实例时不能设置
                // paste: document.body,
                formData: {
                    dataType: 'json'
                },
                // 允许重复上传
                duplicate: true,
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            }, o);

            var $list = $wrap.find('.uploader-list');
            var uploader = WebUploader.create(_opt);

            var sortable = function() {
                // 图集
                if (_opt.pick.multiple) {
                    if ($list.find('.file-item').length) {
                        $list.sortable('destroy')
                            .sortable({
                                items: '.file-item'
                            })
                            .find('.fengmian').show();
                    }
                };
            }
            sortable();
            
            $wrap.data('uploader', uploader);
            // 当有文件添加进来的时候
            uploader.on( 'fileQueued', function( file ) {
                if (_opt.type == 'file') {
                    return false;
                }
                var $li = $('<div id="' + file.id + '" class="file-item thumbnail">\
                                <input name="'+ _opt.name +'source[]" value=""  type="hidden">\
                                <input name="'+ _opt.name +'X[]" id="'+ _opt.name +'X"  type="hidden">\
                                <input name="'+ _opt.name +'Y[]" id="'+ _opt.name +'Y"  type="hidden">\
                                <input name="'+ _opt.name +'W[]" id="'+ _opt.name +'W"  type="hidden">\
                                <input name="'+ _opt.name +'H[]" id="'+ _opt.name +'H"  type="hidden">\
                                <input name="'+ _opt.name +'Rotate[]" id="'+ _opt.name +'Rotate"  type="hidden">\
                                <input name="'+ _opt.name +'ScaleX[]" id="'+ _opt.name +'ScaleX"  type="hidden">\
                                <input name="'+ _opt.name +'ScaleY[]" id="'+ _opt.name +'ScaleY"  type="hidden">\
                                <img width="130" height="100">\
                            </div>');
                var $img = $li.find('img');
                // 图集
                if (_opt.pick.multiple) {
                    $li.prepend('<input name="'+ _opt.name +'[]" value="" type="hidden">')
                        .append('<div class="textarea"><textarea placeholder="请输入描述" name="'+ _opt.name +'desc[]"></textarea></div>')
                };

                // $list为容器jQuery实例, 单图和多图
                $list[ _opt.pick.multiple ? 'append' : 'html']( $li );

                // 创建缩略图
                // 如果为非图片文件，可以不用调用此方法。
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                /*if (file.type) {
                    uploader.makeThumb( file, function( error, src ) {
                        if ( error ) {
                            $img.replaceWith('<span>不能预览</span>');
                            return;
                        }

                        $img.attr( 'src', src );
                    }, _opt.thumbnailWidth, _opt.thumbnailHeight );
                };*/
            });
            // 文件上传过程中创建进度条实时显示。
            uploader.on( 'uploadProgress', function( file, percentage ) {
                if (_opt.type == 'image') {
                       var $li = $( '#'+file.id ),
                           $percent = $li.find('.progress span');
                    // 避免重复创建
                    if ( !$percent.length ) {
                        $percent = $('<p class="progress"><span></span></p>')
                                    .appendTo( $li )
                                    .find('span');
                    }
                   $percent.css( 'width', percentage * 100 + '%' );   
                    
                }
                // 如果是文件
                else{
                        var $dataUpload = $('[data-upload="'+_opt.name+'"]');
                        var $progress = $dataUpload.find('.progressfile span');
                        var $inputval = $dataUpload.children('input').val();
                        // 避免重复创建
                        if ( !$progress.length ) {
                            $dataUpload.append('<p class="progressfile"><span></span></p>');
                        }
                    }

            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file, res ) {
                if (res.msg && res.status) {
                    var $li = $( '#'+file.id ).addClass('upload-state-done').attr('data-url', res.msg);
                    // var $success = $li.find('div.success');
                    if (_opt.pick.multiple) {
                        $li.find('[name="'+ _opt.name +'[]"], [name="'+ _opt.name +'source[]"]').val(res.msg)
                    } else {
                        $wrap.find('[name="'+ _opt.name +'[]"], [name="' + _opt.name + 'source[]"]').val(res.msg);
                    // 文件上传成功删掉进度条
                        $('[data-upload="'+_opt.name+'"]').children('p').remove()
                    }

                    if (_opt.type == 'file') {
                        // 如果是文件至此结束
                        return false;
                    };

                    var $toolbar = $li.find('.toolbar');
                    if (!$toolbar.length) {
                        $toolbar = $('<div class="toolbar"></div>').appendTo($li)
                    };
                    $li.find('img').attr('src', _self.parseImgUrl(res.msg))
                        .load(function() {
                            $li.find('.progress').remove();
                        });
                    $toolbar
                        .removeClass('error')
                        .html('<i title="查看大图" class="preview font-ico-38"></i>\
                                <i title="删除" class="delete font-ico-3"></i>')
                    // $success.text('上传成功');
                    _self.updateImgNum($wrap);

                    sortable();
                } else if (!res.status) {
                    // 重置已经上传的但没有成功的文件
                    uploader.cancelFile( file );
                    $.jqModal.tip(res.info);
                }
            });
            // 图片上传失败，显示上传出错。
            uploader.on('error', function(type) {
                if (type == "Q_TYPE_DENIED") {
                    // gif,jpg,jpeg,bmp,png
                    $.jqModal.tip('请上传' + _opt.accept.extensions + '格式的文件');
                }
            });
            // 文件上传过程中失败，显示上传出错。
            uploader.on( 'uploadError', function( file ) {
                var $li = $( '#'+file.id ),
                    $error = $li.find('.toolbar');
                // 避免重复创建
                if ( !$error.length ) {
                    $error = $('<div class="toolbar"></div>').appendTo( $li );
                }
                var _html = '上传失败 <i class="retry font-ico-29" title="重传"></i> <i title="忽略" class="delete font-ico-3"></i>'
                $error.addClass('error').html(_html);

            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on( 'uploadComplete', function( file ) {
                $( '#'+file.id ).find('.progress').remove();
            });

            _self.events();
        },
        // 更新图片数量用于js表单验证
        updateImgNum: function($wrap) {
            try {
                var num = $wrap.find('.file-item').length;
                // 隐藏封面图标
                if (!num) {
                    $wrap.find('.fengmian').hide();
                }
                if ($wrap.find('[data-img-num="1"]').length) {
                   $wrap.find('[data-img-num="1"]').val(num).valid();
                }
            } catch (err) {
                console.warn(err);
            }
        },
        parseImgUrl: function(url) {
            if (!/http/.test(url)) {
                var urlPre = BASE_URL.replace(/app_dev.php/, "");
                url = urlPre + '/' + url;
                url = REMOTE_URL + url.replace(/\/+/g, '/');
            }
            return url;
        },
        loadImg: function(url, cb) {
            var imgUrl = this.parseImgUrl(url);
            // 图片不能有缓存，否则预览时看不到新加的水印
            // imgUrl += '?t=' + parseInt(new Date().getTime()/1000);
            var _img = new Image();
            // 图片加载完成再弹出，否则会错位
            _img.onload = function() {
                var winW = $(window).width();
                var winH = $(window).height();
                // 弹窗的图片大小限制在w<1200和h<600
                var aImgWH = utils.getImgSize(_img, winW * .9, winH * .8);
                // 处理小图片
                var _h = Math.max(aImgWH[1], 150) + 'px';
                var html = '<div class="imgs-lay" data-url="'+ url +'" style="height: '+ _h +'">\
                                <img style="width: '+ aImgWH[0] +'px; height: '+ aImgWH[1] +'px" src="'+ imgUrl +'" />\
                                <div class="imgs-prev font-ico-arwl" style="line-height: '+ _h +'"></div>\
                                <div class="imgs-next font-ico-arwr" style="line-height: '+ _h +'"></div>\
                            </div>';
                cb && cb.call(this, html);
            }
            _img.onerror = function() {
                $.jqModal.tip('图片加载错误！', 'error');
            }
            _img.src = imgUrl;
        },
        events: function() {
            var _self = this;
            var _opt = _self.opt;
            _self.$wrap
                .off('.upload')
                .on('mouseover.upload',  '.file-item', function() {
                    $(this).addClass('file-item-hover');
                    $(this).find('textarea').focus();
                })
                .on('mouseout.upload', '.file-item', function() {
                    $(this).removeClass('file-item-hover');
                })
                .on('click.upload', '.preview', function() {
                    // 补全地址
                    var url = $(this).closest('.file-item').data('url');
                    _self.loadImg(url, function(html) {
                        // 调用弹窗
                        $.jqModal.lay(html);
                    })
                })
                .on('click.upload', '.delete', function() {
                    // 先缓存下来，不然删除后找不到
                    var $wrap = $(this).closest('[data-upload]');
                    $(this).closest('.file-item').remove();
                    $wrap.find('.img-url').val('');
                    _self.updateImgNum($wrap);
                })
                .on('input.upload propertychange.upload', '.img-url', function() {
                    var $this = $(this);
                    var thisName = $this.attr('name').replace('[]', '');
                    var $wrap = $this.closest('[data-upload]');
                    var _val = this.value;
                    var imgUrl;
                    if (_val) {
                        imgUrl = _self.parseImgUrl(_val);

                        // 没有file-item要重新加一个
                        if (!$wrap.find('.file-item').length && _opt.type == 'image') {
                            var uploader = $wrap.data('uploader');
                            var _id = parseInt(new Date().getTime()/1000);
                            uploader.trigger('fileQueued', {
                                id: 'pick-' + _id,
                                name: imgUrl
                            })
                            uploader.trigger('uploadSuccess', {
                                id: 'pick-' + _id,
                                name: imgUrl
                            }, {
                                msg: imgUrl,
                                status: 1
                            });
                        }
                        $wrap.find('.file-item img').attr('src', imgUrl);
                        $wrap.find('.file-item').attr('data-url', _val);
                        $wrap.find('input[name="thumbsource[]"]').val(_val);
                    };
                })
                .on('click.upload', '.upimages', function() {
                    var $this = $(this);
                    var url = $this.data('url');
                    var title = $this.html();
                    $.jqModal.modal({
                        head : title,
                        animate: null,
                        content : url,
                        type: 'iframe',
                        css: {
                            width : 600,
                            height : 400
                        }
                    }).on('hideFun', function() {
                        var imgData = $(this).data('returnValue');
                        // 没数据返回不设置
                        if (!imgData) {
                            return false;
                        };
                        var uploader = $this.closest('[data-upload]').data('uploader');
                        var _id = parseInt(new Date().getTime()/1000);
                        uploader.trigger('fileQueued', {
                            id: 'pick-' + _id,
                            name: imgData
                        })
                        uploader.trigger('uploadSuccess', {
                            id: 'pick-' + _id,
                            name: imgData
                        }, {
                            msg: imgData,
                            status: 1
                        });
                    })
                })
                .on('click.upload', '.retry', function() {
                    // 重新上传
                    $(this).closest('[data-upload]').data('uploader').retry();
                })
                // 相册浏览
                $(document)
                    .off('.imgs-gallery')
                    .on('mousemove.imgs-gallery', '.imgs-lay', function(e) {
                        var wrapL = $(this).offset().left + $(this).width() / 2;
                        var $pnBtns = $(this).find('.imgs-prev, .imgs-next').attr({
                                            unselectable: 'on',
                                            onselectstart: 'return false;'
                                        });

                        $pnBtns[(e.pageX > wrapL) * 1].style.display = 'block';
                        $pnBtns[(e.pageX < wrapL) * 1].style.display = 'none';
                    })
                    .on('mouseenter.imgs-gallery', '.imgs-lay', function(e) {
                        var wrapL = $(this).offset().left + $(this).width() / 2;

                        $(this).find('.imgs-prev, .imgs-next').eq(e.pageX > wrapL).animate({
                            opacity: 'show'
                        });
                    })
                    .on('mouseleave.imgs-gallery', '.imgs-lay', function(e) {
                        $(this).find('.imgs-prev, .imgs-next').css('display','none');
                    })
                    .on('click.imgs-gallery', '.imgs-lay', function(e) {
                        var $this = $(this);
                        var wrapL = $this.offset().left + $this.width() / 2;
                        var run = function(item) {
                            var url = $this.data('url');
                            var newUrl = $('.file-item[data-url="'+ url +'"]')[item]().data('url');
                            if (newUrl) {
                                $this.data('url', newUrl)
                                    .find('img').attr('src', _self.parseImgUrl(newUrl));
                                _self.loadImg(newUrl, function(html) {
                                    $this.replaceWith(html);
                                    $('.jqlay').jqModal('setPos');
                                })
                            }
                        }

                        run(e.pageX  > wrapL ? 'next' : 'prev')
                    })
        }
    }
    module.exports = out;
});
