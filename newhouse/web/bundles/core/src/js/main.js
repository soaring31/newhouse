/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月13日
* To change this template use File | Settings | File Templates.
*/
define(function (require, exports, module) {
     var core = require("core");

     var self;

    (function(out){
        module.exports=out;
    }) ({
        //调用函数
        /*
         * 调用函数
         * @page 函数名， par参数名
         * @return function
         */
        init:function(page,par){
            //初始化加載的腳本
            try{
                self = this;
                self[page](par);
            }catch(e) {
                core.generalTip.ontextTip("JS脚本错误！["+e+"]","error");
            }
        }
        //入口函数
        ,main:function()
        {
            $('.body_nbsp').height($(window).height()-$('#header').height()-30);
            $(window).resize(function(){
                $('.body_nbsp').height($(window).height()-$('#header').height()-30);
            });
            self.jumpleftFrame();
        }
        ,index:function()
        {
            $(".showApiInfo").off().on("click",function(){
                var param = $(this).attr("data");
                if(!param) return false;
                param = param.split(",");

                var content = $("#apiInfo");

                $("#apiUrl",content).val(param[2]);
                $("#apiToken",content).val(param[3]);
                $("#apiAESKey",content).val(param[4]);
                dialog({
                    title:param[1]+' 接口信息',
                    content:content,
                    scrolling: 'auto',
                    lock:true,
                    onclose:function(){
                        //console.log(this);
                    }

                }).showModal();
            });
            
            $(".addFee").off().on("click",function(){
                dialog({
                    title: '充值续费',
                    url: "{{ 'Alipay/add'|U }}",
                    width:600,
                    height:400,
                    zIndex:10,
                    scrolling: 'auto',
                    onclose: function () {
                        if (this.returnValue) {
                            location.reload();
                        }
                        //console.log('onclose');
                    }
                }).showModal();
                return false;
            });
        }
        ,set:function()
        {
            self.ajaxSubmit();
            var start = $("[name='start']").val();
            self.startAction(start);
            
            $("[name='start']").off().on("change", function(){
                start = $("[name='start']").val();
                self.startAction(start);
            });
            
            //图片上传、图片预览、选择文件
            core.generalEvent.initViewImg();
            core.generalEvent.initUploadImg();
            core.generalEvent.chooseFile();
        }
        ,startAction:function(start)
        {
            switch(Number(start))
            {
                case 2:
                case 3:
                    $("#div23").show();
                    $("#div4").hide();
                    break;
                case 4:
                    $("#div4").show();
                    $("#div23").hide();
                    break;
                default:
                    $("#div4").hide();
                    $("#div23").hide();
                    break;
            }
        }
        /**
         * iframe跳转
         */
        ,jumpleftFrame:function(){
            $(".navlink").off().on("click",function(){
                var param = $(this).attr("data");
                if(!param) return false;
                param = param.split(",");
                
                self.showhover(param[1]);
                document.getElementById("left").src=param[0];
            })
        }
        ,showhover:function(id){
            $('.curr').removeClass('curr');
            $('#link'+id).addClass('curr');
        }
        
        /**
         * 全选功能
         */
        ,checkAllFun:function()
        {
            // 全选的实现
            $(".check-all").off().on("click",function() {
                $(".ids").prop("checked", this.checked);
            });
            $(".ids").off().on("click",function() {
                var option = $(".ids");
                option.each(function(i) {
                    if (!this.checked) {
                        $(".check-all").prop("checked", false);
                        return false;
                    } else {
                        $(".check-all").prop("checked", true);
                    }
                });
            });
        }
        
        /**
         * Ajax提交
         */
        ,ajaxSubmit:function()
        {
            self.ajaxPost();
            self.ajaxGet();
        }
        ,ajaxPost:function()
        {
            
            $('.ajax-post').off().on("click",(function() {
                var target, query, form;
                var target_form = $(this).attr('target-form');
                var that = this;
                var nead_confirm = false;
                if (($(this).attr('type') == 'submit')|| (target = $(this).attr('href'))|| (target = $(this).attr('url')))
                {
                    form = $('.' + target_form);

                    // 无数据时也可以使用的功能
                    if ($(this).attr('hide-data') === 'true') {
                        form = $('.hide-data');
                        query = form.serialize();
                        if ($(this).hasClass('confirm')) {
                            if (!confirm('确认要执行该操作吗?')) {
                                return false;
                            }
                        }
                    } else if (form.get(0) == undefined) {
                        return false;
                    } else if (form.get(0).nodeName == 'FORM') {
                        if ($(this).hasClass('confirm')) {
                            if (!confirm('确认要执行该操作吗?')) {
                                return false;
                            }
                        }
                        if ($(this).attr('url') !== undefined) {
                            target = $(this).attr('url');
                        } else {
                            target = form.get(0).action;
                        }
                        query = form.serialize();
                    } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA'){
                        form.each(function(k, v) {
                            if (v.type == 'checkbox'&& v.checked == true) {
                                nead_confirm = true;
                            }
                        })
                        if (nead_confirm&& $(this).hasClass('confirm')) {
                            if (!confirm('确认要执行该操作吗?')) {
                                return false;
                            }
                        }
                        query = form.serialize();
                    } else {
                        if ($(this).hasClass('confirm')) {
                            if (!confirm('确认要执行该操作吗?')) {
                                return false;
                            }
                        }
                        query = form.find('input,select,textarea').serialize();
                    }
                    
                    //提交数据
                    core.generalFunction.ajaxSubmit({
                        url: target,
                        data: query,
                        beforeSend:function()
                        {
                            //卸载事件,防止重复点击
                            $(that).prop('disabled',true);
                        },
                        complete:function()
                        {
                            $(that).prop('disabled', false);
                        }
                    }).done(function(data){
                            if(data.status)
                            {
                                if(data.url)
                                    location.href = data.url;
                                else if($(that).hasClass('reload')) location.reload();
                            }  else {
                                alert(data.info);
                            }
                        });
                }
                return false;
            }));
        }
        ,ajaxGet:function()
        {
            //ajax get请求
            $('.ajax-get').off().on("click",function() {
                var target;
                var that = this;
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                if ((target = $(this).attr('href'))|| (target = $(this).attr('url'))) {
                    //提交数据
                    core.generalFunction.ajaxSubmit({
                        url: target,
                        data: {},
                        type: "GET",
                        beforeSend:function()
                        {
                            //卸载事件,防止重复点击
                        },
                        complete:function()
                        {
                            
                        }
                    }).done(function(data){
                        if(data.status) {
                            if(data.url)
                                location.href = data.url;
                            else if($(that).hasClass('reload')) location.reload();
                        } else {
                            alert(data.info);
                        }
                    });
                }
                return false;
            });
        }
    });
});