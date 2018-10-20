seajs.use(['tooljsq', 'toolgjj', 'sm'], function(tooljsq, toolgjj, sm) {
    var _alert = $.alert;
    /*common 清空表单*/
    $('.reset').click(function() {
        $(this).parents('form')[0].reset();
    });

    /*商业贷款*/
    $('#form2-btn').click(function() {
       tooljsq.ext_total(document.calc2);

    });
    //计算方法切换
    $('#calc2_radio1').click(function() {
        tooljsq.exc_js(this.form, 1);
    });
    $('#calc2_radio2').click(function() {
        tooljsq.exc_js(this.form, 2);
    });

    /*组合贷款*/
    $('#form5-btn').click(function() {
        tooljsq.ext_total(document.calc3);
    });
    //还贷方式结果切换
    $('#hdfs1,#hdfs2,#hdfs3,#hdfs4').click(function() {
        tooljsq.showR($(this).parents('form'), parseInt($(this).val()));
    });

    /*个人公积金贷款*/
    $('#form4-btn').click(function() {
        toolgjj.gjjloan2(this.form)
    });
    $('.money').blur(function() {
        var val = parseFloat($(this).val());
        if (isNaN(val)) {
            _alert('输入格式错误，请重新输入！');
        }
    });
    $('.years').blur(function() {
        var val = parseFloat($(this).val());
        if (isNaN(val)) {
            _alert('输入格式错误，请重新输入！');
        }
        else if (val > 30) {
             _alert('公积金贷款年限不得超过30年，请重新输入！');
        }
    });

    /*税费*/
    $('#form7-btn').click(function() {
        runjs3(this.form);
    })


    /*提前还款*/
    $('#form6-btn').click(function() {
        tooljsq.play()
    });
    //部分提前还款
    $('#tqhkfs1,#tqhkfs').on('click', function() {
        if ($('#tqhkfs1').prop('checked'))
            $('#clfs').show()
        else
            $('#clfs').hide()
    });
    $('[name=tqhkws],[name=dkzws]').blur(function() {
        var val = parseFloat($(this).val());
        if (isNaN(val)) {
            _alert('输入格式错误，请重新输入！');
        }

    });
    //税费
    var _fkz3 = document.getElementById('fkz3');
    var _q = document.getElementById('q');
    var _yh = document.getElementById('yh');
    var _gzh = document.getElementById('gzh');
    var _fw = document.getElementById('fw');
    var _wt = document.getElementById('wt');
    //税费
    function runjs3(obj) {
            var dj3 = parseFloat(obj.dj3.value);
            var mj3 = parseFloat(obj.mj3.value);
            if (isNaN(dj3)) {
                _alert('请输入单价');
            }
            else if (isNaN(mj3)) {
                _alert('请输入面积');
            }else {

                var fkz3 = dj3 * mj3;
                var yh = fkz3 * 0.0005;
                if (dj3 <= 9432) {
                    var q = fkz3 * 0.015;
                } else if (dj3 > 9432) {
                    var q = fkz3 * 0.03;
                }
                if (mj3 <= 120) {
                    var fw = 500;
                } else if (120 < mj3 && mj3 <= 5000) {
                    var fw = 1500;
                }
                if (mj3 > 5000) {
                    var fw = 5000;
                }
                if (mj3 < 90) {
                    qishui = fkz3 * 1 / 100;
                } else if (90 < mj3 < 140 || mj3 == 90) {
                    qishui = fkz3 * 1.5 / 100;
                } if (mj3 > 140 || mj3 == 140) {
                    qishui = fkz3 * 3 / 100;
                }
                var gzh = fkz3 * 0.003;
                _yh.innerHTML = (Math.round(yh * 100, 5) / 100)|0;
                _q.innerHTML = (Math.round(qishui))|0;
                _fkz3.innerHTML = (Math.round(fkz3 * 100, 5) / 100)|0;
                _gzh.innerHTML = (Math.round(gzh * 100, 5) / 100)|0;
                _wt.innerHTML = (Math.round(gzh * 100, 5) / 100)|0;
                _fw.innerHTML = (Math.round(fw * 100, 5) / 100)|0;
            }
        }
        //税费end


    //个人公积金贷款
    var ze22 = document.getElementById('ze22');
    var lx2 = document.getElementById('lx2');
    var sfk2 = document.getElementById('sfk2');
    var lx3 = document.getElementById('lx3');
    var sfksan = document.getElementById('sfksan');
    var lx4 = document.getElementById('lx4');
    var lx5 = document.getElementById('lx5');
    var lx6 = document.getElementById('lx6');
    //提前还款
    var ykhke = document.getElementById('ykhke');
    var gyyihke = document.getElementById('gyyihke');
    var yzhhkq = document.getElementById('yzhhkq');
    var xyqyhke = document.getElementById('xyqyhke');
    var yhkze = document.getElementById('yhkze');
    var jslxzc = document.getElementById('jslxzc');
    var yhlxe = document.getElementById('yhlxe');
    var xdzhhkq = document.getElementById('xdzhhkq');
    var jsjgts = document.getElementById('jsjgts');

    //能力评估版块
    var rg7 = document.getElementById('rg7');
    var rg8 = document.getElementById('rg8');
    var rg10 = document.getElementById('rg10');
    var rg11 = document.getElementById('rg11');
    var rg12 = document.getElementById('rg12');
    var rg13 = document.getElementById('rg13');
    var rg14 = document.getElementById('rg14');
    var rg15 = document.getElementById('rg15');

$('#form1-btn').click(function() {
     if (isNaN(document.myform.rg01.value) || document.myform.rg01.value == '') {
            _alert('请填写现可用于购房的资金');
           return false;
     }
     else if (isNaN(document.myform.rg02.value) || document.myform.rg02.value == '') {
            _alert('请填写现家庭月收入');
            return false;
    }
    else if (isNaN(document.myform.rg03.value) || document.myform.rg03.value == '') {
             _alert('请填写预计家庭每月可用于购房支出');
            return false;
    }
    else if (isNaN(document.myform.rg06.value) || document.myform.rg06.value == '') {
             _alert('请填写您计划购买房屋的面积');
            return false;
    }
    else {
         chk04()
  }
})
});
//能力评估版块
rhb = new Array(440.104, 301.103, 231.7, 190.136, 163.753, 144.08, 129.379, 117.991, 108.923, 101.542, 95.425, 90.282, 85.902, 82.133, 78.861, 75.997, 73.473, 71.236, 69.241, 67.455, 65.848, 64.397, 63.082, 61.887, 60.798, 59.802, 58.890, 58.052, 57.282)
yhz = new Array(1.978, 2.9344, 3.8699, 4.7847, 5.6794, 6.5544, 7.4102, 8.2472, 9.0657, 9.8662, 10.6491, 11.4148, 12.1636, 12.8959, 13.6121, 14.3126, 14.9977, 15.6677, 16.3229, 16.9637, 17.5904, 18.2034, 18.8028, 19.389, 19.9624, 20.5231, 21.0715, 21.6078, 22.1323)

function chk01() {
    var _alert = $.alert;
    if (parseFloat(document.myform.rg01.value) < 4.7)
        _alert('--您确定是' + parseFloat(document.myform.rg01.value) + '万元?--' + '\n\n' + '那么您目前尚不具备购房能力，' + '\n\n' + '建议积攒积蓄或能筹集更多的资金。')
    if (parseFloat(document.myform.rg01.value) > 10000)
        _alert('您确定拥有超过一亿元的购房资金？');

}

function chk02() {
    var _alert = $.alert;
    if (parseFloat(document.myform.rg03.value) > parseFloat(document.myform.rg02.value) * 0.7) {
        _alert('您预计家庭每月可用于购房支出已超过家庭月收入的70%，' + '\n\n' + '是否确定不会影响您的正常生活消费？' + '\n\n' + '建议在40%（' + parseFloat(document.myform.rg02.value) * 0.4 + '元）左右')
    }
}

function chk04() {
        //您可购买的房屋总价=（家庭月收入-家庭月固定支出）×( (（1＋月利率）＾还款月数)－1  )÷［月利率×（1＋月利率）＾还款月数］+持有资金
        var month = parseInt(document.myform.rg04.options[document.myform.rg04.selectedIndex].value);
        var year = parseInt(month / 12);
        /*var lilu = 0.00576;
        if (year > 5)
            lilu = 0.00594;*/
        js00_n = parseFloat(document.myform.rg01.value); //现持有
        js01_n = parseFloat(document.myform.rg02.value); //月收入
        js02_n = parseFloat(document.myform.rg03.value); //月支出
        js03_n = parseFloat(document.myform.rg06.value); //面积

        /*var d1 = js01_n - js02_n;
        var d2 = Math.pow(1 + lilu, month) - 1;
        var d3 = lilu * Math.pow(1 + lilu, month)

        rg07.innerHTML = Math.round(((d1 * d2) / d3) + js00_n);
        rg08.innerHTML = parseFloat(rg07.innerHTML) / js03_n;*/
        //////////////////////

        js00 = js00_n * 10000 //现持有
        js01 = js02_n // 月支出
        js02 = Math.round(js01 / rhb[parseInt(document.myform.rg04.options[document.myform.rg04.selectedIndex].value) / 12 - 2]) * 10000
        js03 = js03_n

        if (js02 > js00 * 3.2)
            js02 = js00 * 3.2
        rg07.innerHTML = Math.round((js02 + 0.8 * js00) * 100) / 100
        rg08.innerHTML = Math.round(parseFloat(rg07.innerHTML) / js03 * 100) / 100

        if (js03 < 90) {
            rg10.value = Math.round(parseFloat(rg07.value) * 1) / 100
        } else if (js03 >= 90 && js03 < 144) {
            rg10.value = Math.round(parseFloat(rg07.value) * 1.5) / 100
        } else {
            rg10.value = Math.round(parseFloat(rg07.value) * 3) / 100
        }

        if (js03 < 120)
            rg10.innerHTML = Math.round(parseFloat(rg07.innerHTML) * 2) / 100
        else
            rg10.innerHTML = Math.round((parseFloat(rg07.innerHTML) - parseFloat(rg08.innerHTML) * 120) * 4 + parseFloat(rg08.innerHTML) * 120 * 2) / 100


        rg11.innerHTML = Math.round(parseFloat(rg07.innerHTML) * 2) / 100
        rg12.innerHTML = Math.round(parseFloat(rg07.innerHTML) * 20) / 100
        rg13.innerHTML = Math.round(Math.round(parseFloat(rg07.innerHTML) * 0.05) / 100 * yhz[parseInt(document.myform.rg04.options[document.myform.rg04.selectedIndex].value) / 12 - 2] * 100) / 100
        rg14.innerHTML = Math.round(parseFloat(rg07.innerHTML) * 0.3) / 100
        rg15.innerHTML = '200~500'

}
    //能力评估版块end
