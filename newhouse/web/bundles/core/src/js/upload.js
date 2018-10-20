/**
 * Created with JetBrains WebStorm.
 * User: Anday
 * Date: 15-07-10
 * Time: 上午11:12
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
     var self;
     var core = require("core");

     //对象的实例的参数
     var options = {
				//点击选择文件ID
				gui_id: '',
				//元素类型的输入ID
				input_id: '',
				//文件容器ID
				list_wrap_id: '',
				//上传的URL地址
				upload_url: '',
				//删除文件的URL地址
				remove_url: '',
				//已存在的文件的容器
				ex_list_wrap_id: false,
				//已存在的删除URL地址
				ex_remove_url: false
     		};

    (function(out){
    	//初始化
    	out.init();
        module.exports=out;
        
    }) ({
    	//调用函数
    	/**
    	 * 调用函数
    	 * @page 函数名， par参数名
    	 * @return function
    	 */
    	init:function(ops){
    		//初始化加載的腳本
    		self = this;
			self.options = $.extend({},options, ops);
			
			self.bindFileUploader();
    	}
    	,bindFileUploader:function()
    	{
    		$(self.options.gui_id).off().on("click",function (event) {
                event.preventDefault();
                $(self.options.gui_id).off();
                $(self.options.input_id).click();
            });

            $(self.options.input_id).change($.proxy(function () {

                var container = $(self.options.list_wrap_id);
                var file = document.querySelector(self.options.input_id);

//				container.empty();
                for (var i = 0; i < file.files.length; i++) {
                    var f = file.files[i];
                    container.append(
                            $('<a href="javascript:void(0);" data-filename="' + f.name + '"><img src="'+jsbase+'/tripodupload/images/progress.gif"> ' + f.name + '</a>')
                    );
                    self.upload(f);
                }

                self.bindFileRemovers();

            }, this));

            self.bindFileRemovers();
            self.bindExFileRemovers();
    	}
    	,bindFileRemovers:function()
    	{
    		var removers = $(self.options.list_wrap_id + ' [data-filename]');
            removers.unbind();
            removers.click($.proxy(function (event) {
                event.preventDefault();
                if ($(event.target).find('img').length == 0) {

                    var filename = $(event.target).attr('data-filename');
                    $.ajax({
                        url: self.options.remove_url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: JSON.stringify({'name': filename}),
                        headers: {
                            'Content-Type': 'application/json; charset=utf-8'
                        },
                        success: $.proxy(function (data) {
                            if (data.status == 'ok') {
                                $(self.options.list_wrap_id + ' [data-filename]').each($.proxy(function (index, element) {
                                    if ($(element).attr('data-filename') == filename) {
                                        $(element).remove();
                                    }
                                }, this));
                            }
                        }, this)
                    });

                }
            }, this));
    	}
    	,bindExFileRemovers:function()
    	{
    		if (self.options.ex_list_wrap_id && self.options.ex_remove_url) {
                var removers = $(self.options.ex_list_wrap_id + ' [data-filename]');
                removers.unbind();
                removers.click($.proxy(function (event) {
                    event.preventDefault();

                    var filename = $(event.target).attr('data-filename');
                    $.ajax({
                        url: self.options.ex_remove_url,
                        type: 'POST',
                        dataType: 'json',
                        data: JSON.stringify({'name': filename}),
                        headers: {
                            'Content-Type': 'application/json; charset=utf-8'
                        },
                        success: $.proxy(function (data) {
                            if (data.status == 'ok') {
                                $(self.options.ex_list_wrap_id + ' [data-filename]').each($.proxy(function (index, element) {
                                    if ($(element).attr('data-filename') == filename) {
                                        $(element).remove();
                                    }
                                }, this));
                            }
                        }, this)
                    });

                }, this));
            }
    	}
    	,setProgress:function(f, progress)
    	{
    		$(self.options.list_wrap_id + ' [data-filename]').each($.proxy(function (index, element) {
                if ($(element).attr('data-filename') == f.name && progress == 100) {
                    $(element).find('img').remove();
                }
            }, this));
    	}
    	,upload:function(f)
    	{
    		if (f.size > 2097152 /* 2M */) {
                // Chunk send
                this.sendChunk(0, f);
            } else {
                // Send
                this.sendFile(f);
            }
    	}
    	,sendFile:function(f)
    	{
    		var freader = new FileReader();
            freader.readAsBinaryString(f);
            freader.onload = $.proxy(function (e) {
                var sendData = {
                    'type': f.type,
                    'size': f.size,
                    'name': f.name,
                    'chunked': false,
                    'content': btoa(e.target.result)
                };
                core.generalFunction.ajaxSubmit({
                    url: self.options.upload_url,
                    data:JSON.stringify(sendData),
                    automatic:false,
                    type: 'PUT',
                    dataType:'json',
                    headers: {'Content-Type': 'application/json; charset=utf-8'},
                    complete:function()
                    {
                    	self.setProgress(f, 100);
                    	self.bindFileUploader();
                    },
                    success:function(data){                	
                    	core.generalTip.ontextTip("上传成功","right");
                    },
                    error: function(data){
                    	core.generalTip.ontextTip("上传失败","error");
                    }
                });
            }, this);
    	}
    	,sendChunk:function(i, f)
    	{
    		var to;
            if (((i * 2097152) + (2097152)) > f.size) {
                to = f.size;
            } else {
                to = (i * 2097152) + (2097152);
            }

            var b = f.slice(i * 2097152, to);
            var freader = new FileReader();
            freader.readAsBinaryString(b);
            freader.onload = $.proxy(function (e) {
                var sendData = {
                    'type': f.type,
                    'size': f.size,
                    'name': f.name,
                    'chunked': true,
                    'chunk': i,
                    'chunkSize': b.size,
                    'content': btoa(e.target.result),
                    'startByte': i * 2097152,
                    'to': to
                };
                
                core.generalFunction.ajaxSubmit({
                    url: self.options.upload_url,
                    data:JSON.stringify(sendData),
                    automatic:false,
                    type: 'PUT',
                    dataType:'json',
                    istiphide:true,
                    headers: {'Content-Type': 'application/json; charset=utf-8'},
                    complete:function()
                    {
                    	self.setProgress(f, 100);
                    },
                    success:function(data){                	
                    	if ((i + 1) < Math.ceil(f.size / 2097152)) {
                        	var size = parseInt(((i * 2097152) * 100) / f.size);
                        	self.sendChunk(i + 1, f);
                            core.generalTip.ontextTip("已上传["+size+"%]","right");
                            //this.setProgress(f, ((i * 2097152) * 100) / f.size);
                        } else {
                        	self.setProgress(f, 100);
                            core.generalTip.ontextTip("上传成功","right");
                            self.bindFileUploader();
                        }
                    },
                    error: function(data){
                    	core.generalTip.ontextTip("上传失败","error");
                    	self.bindFileUploader();
                    }
                });
            }, this);
    	}
    });
});