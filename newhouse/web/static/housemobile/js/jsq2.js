/*
 * @name <%= name %>
 * @author <%= author %>
 * @date <%= date %>
 * @description:related calculator
 */
define(['toolrate', 'sm'], function (require, exports, module) {
var _alert = $.alert;
var rate = require('toolrate');
var lilv_array = rate.arr();
function exc_zuhe(fmobj,v){
	//var fmobj=document.calc1;
	if (fmobj.name=="calc1"){
		if (v==3){
			document.getElementById('calc1_zuhe').style.display='block';
			document.getElementById('calc22').style.display='none';
			fmobj.jisuan_radio[1].checked = true;
			exc_js(fmobj,2);
		}else{
			document.getElementById('calc1_zuhe').style.display='none';
			document.getElementById('calc22').style.display='block';
		}
	}else{
		if (v==3){
			document.getElementById('calc2_zuhe').style.display='block';
			document.getElementById('calc22').style.display='none';
			fmobj.jisuan_radio[1].checked = true;
			exc_js(fmobj,2);
		}else{
			document.getElementById('calc2_zuhe').style.display='none';
			document.getElementById('calc22').style.display='block';
		}
	}
}

function formReset(fmobj){
	//var fmobj=document.calc1;
	if (fmobj.name=="calc1"){
		document.getElementById('calc1_js_div1').style.display='block';
		document.getElementById('calc1_js_div2').style.display='none';
		document.getElementById('calc1_zuhe').style.display='none';
		document.getElementById('calc1_benjin').style.display='none';
	}else{
		document.getElementById('calc2_js_div1').style.display='block';
		document.getElementById('calc2_js_div2').style.display='none';
		document.getElementById('calc2_zuhe').style.display='none';
		document.getElementById('calc2_benxi').style.display='none';
	}
}

//验证是否为数字
function reg_Num(str){
	if (str.length==0){return false;}
	var Letters = "1234567890.";

	for (i=0;i<str.length;i++){
		var CheckChar = str.charAt(i);
		if (Letters.indexOf(CheckChar) == -1){return false;}
	}
	return true;
}

//得到利率
function getlilv(lilv_class,type,years){
	var lilv_class = parseInt(lilv_class);
    if (years<=5){
		 return lilv_array[lilv_class][type][5];
	}else{
		return lilv_array[lilv_class][type][10];
	}
}

//本金还款的月还款额(参数: 年利率 / 贷款总额 / 贷款总月份 / 贷款当前月0～length-1)
function getMonthMoney2(lilv,total,month,cur_month){
	var lilv_month = lilv / 12;//月利率
	//return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
	var benjin_money = total/month;
	return (total - benjin_money * cur_month) * lilv_month + benjin_money;

}

//本息还款的月还款额(参数: 年利率/贷款总额/贷款总月份)
function getMonthMoney1(lilv,total,month){
	var lilv_month = lilv / 12;//月利率
	return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
}

(function(outFun){
	module.exports = outFun;
})({
	//商业贷款计算
	ext_total:function(fmobj){
		//var fmobj=document.calc1;
		//先清空月还款数下拉框
		var $fmobj = $(fmobj);
		var fangkuan_total1= $fmobj.find(".fangkuan_total1")[0];// document.getElementById("fangkuan_total1");
	    var daikuan_total1= $fmobj.find(".daikuan_total1")[0];// document.getElementById("daikuan_total1");
	    var _all_total1= $fmobj.find(".all_total1")[0];//document.getElementById("all_total1");
	    var accrual1= $fmobj.find(".accrual1")[0];//document.getElementById("accrual1");
	    var money_first1= $fmobj.find(".money_first1")[0];//document.getElementById("money_first1");
	    var month1= $fmobj.find(".month1")[0];//document.getElementById("month1");
	    var _month_money1= $fmobj.find(".month_money1")[0];//document.getElementById("month_money1");
	    var daikuan_total2= $fmobj.find(".daikuan_total2")[0];//document.getElementById("daikuan_total2");
	    var _all_total2= $fmobj.find(".all_total2")[0];//document.getElementById("all_total2");
	    var fangkuan_total2= $fmobj.find(".fangkuan_total2")[0];//document.getElementById("fangkuan_total2");
	    var accrual2= $fmobj.find(".accrual2")[0];//document.getElementById("accrual2");
	    var money_first2= $fmobj.find(".money_first2")[0];//document.getElementById("money_first2");
	    var month2= $fmobj.find(".month2")[0];//document.getElementById("month2");
	    var _month_money2= $fmobj.find(".month_money2")[0];//document.getElementById("month_money2");
		while ((k=fmobj.month_money2.length-1)>=0){
			fmobj.month_money2.options.remove(k);
		}
		var years = fmobj.years.value;
		var month = fmobj.years.value * 12;

		month1.innerHTML = month+"(月)";
		month2.innerHTML = month+"(月)";

		
		if (fmobj.type.value == 3 ){
			//--  组合型贷款(组合型贷款的计算，只和商业贷款额、和公积金贷款额有关，和按贷款总额计算无关)
				if (!reg_Num(fmobj.total_sy.value)){	
					_alert('混合型贷款请填写商贷比例');
					fmobj.total_sy.focus();return false;
				}
				if (!reg_Num(fmobj.total_gjj.value)){
					_alert('混合型贷款请填写公积金比例'); 
					fmobj.total_gjj.focus();return false;
				}
				if (fmobj.total_sy.value==null){fmobj.total_sy.value=0;}
				if (fmobj.total_gjj.value==null){fmobj.total_gjj.value=0;}
				var total_sy = fmobj.total_sy.value*10000;
				var total_gjj = fmobj.total_gjj.value*10000;
				fangkuan_total1.innerHTML = "略";//房款总额
				fangkuan_total2.innerHTML = "略";//房款总额
				money_first1.innerHTML = 0;//首期付款
				money_first2.innerHTML = 0;//首期付款

				//贷款总额
				var total_sy = parseInt(fmobj.total_sy.value*10000);
				var total_gjj = parseInt(fmobj.total_gjj.value*10000);
				var daikuan_total = total_sy + total_gjj;
				daikuan_total1.innerHTML = Math.round(daikuan_total);
				daikuan_total2.innerHTML = Math.round(daikuan_total);

				//月还款
				var lilv_sd = getlilv(fmobj.lilv.value,1, years);//得到商贷利率
				var lilv_gjj = getlilv(fmobj.lilv.value,2, years);//得到公积金利率

				//1.本金还款
					//月还款
					var all_total2 = 0;
					var month_money2 = "";
					for(j=0;j<month;j++) {
						//调用函数计算: 本金月还款额
						huankuan = getMonthMoney2(lilv_sd,total_sy,month,j) + getMonthMoney2(lilv_gjj,total_gjj,month,j);
						all_total2 += huankuan;
						huankuan = Math.round(huankuan*100)/100;
						//fmobj.month_money2.options[j] = new Option( (j+1) +"月," + huankuan + "(元)", huankuan);
						month_money2 += (j+1) +"月," + huankuan + "(元)\n";
					}
					_month_money2.value = month_money2;
					//还款总额
					_all_total2.innerHTML = Math.round(all_total2*100)/100;
					//支付利息款
					accrual2.innerHTML = Math.round( (all_total2 - daikuan_total) *100)/100;


				//2.本息还款
					//月均还款
					var month_money1 = getMonthMoney1(lilv_sd,total_sy,month) + getMonthMoney1(lilv_gjj,total_gjj,month);//调用函数计算
					_month_money1.innerHTML = Math.round(month_money1*100)/100 + "(元)";
					//还款总额
					var all_total1 = month_money1 * month;
					_all_total1.innerHTML = Math.round(all_total1*100)/100;
					//支付利息款
					accrual1.innerHTML = Math.round( (all_total1 - daikuan_total) *100)/100;

		}else{
			//--  商业贷款、公积金贷款
				var lilv = getlilv(fmobj.lilv.value,fmobj.type.value, fmobj.years.value);//得到利率
				if (fmobj.jisuan_radio[0].checked == true){
					//------------ 根据单价面积计算
					if (!reg_Num(fmobj.price.value)){
						_alert('请填写单价');
               		 	fmobj.price.focus();return false;
               		}
					if (!reg_Num(fmobj.sqm.value)){
						_alert('请填写面积');
               			fmobj.sqm.focus();return false;
               		}

					//房款总额
					var fangkuan_total = fmobj.price.value * fmobj.sqm.value;
					fangkuan_total1.innerHTML = fangkuan_total;
					fangkuan_total2.innerHTML = fangkuan_total;
					//贷款总额
					var daikuan_total = (fmobj.price.value * fmobj.sqm.value) * (fmobj.anjie.value/10);
					daikuan_total1.innerHTML = Math.round(daikuan_total);
					daikuan_total2.innerHTML = Math.round(daikuan_total);
					//首期付款
					var money_first = fangkuan_total - daikuan_total;
					money_first1.innerHTML = Math.round(money_first);
					money_first2.innerHTML = Math.round(money_first);
				}else{
					//------------ 根据贷款总额计算
					if (!reg_Num(fmobj.daikuan_total000.value)){
						_alert('请填写贷款总额');
						fmobj.daikuan_total000.focus();return false;
					}

					//房款总额
					fangkuan_total1.innerHTML = "略";
					fangkuan_total2.innerHTML = "略";
					//贷款总额
					//var daikuan_total = fmobj.daikuan_total000.value;
					var daikuan_total = fmobj.daikuan_total000.value*10000;
					daikuan_total1.innerHTML = Math.round(daikuan_total);
					daikuan_total2.innerHTML = Math.round(daikuan_total);
					//首期付款
					money_first1.innerHTML = 0;
					money_first2.innerHTML = 0;
				}
				//1.本金还款
					//月还款
					var all_total2 = 0;
					var month_money2 = "";
					for(j=0;j<month;j++) {
						//调用函数计算: 本金月还款额
						huankuan = getMonthMoney2(lilv,daikuan_total,month,j);
						all_total2 += huankuan;
						huankuan = Math.round(huankuan*100)/100;
						//fmobj.month_money2.options[j] = new Option( (j+1) +"月," + huankuan + "(元)", huankuan);
						month_money2 += (j+1) +"月," + huankuan + "(元)\n";
					}
					_month_money2.value = month_money2;
					//还款总额
					_all_total2.innerHTML = Math.round(all_total2*100)/100;
					//支付利息款
					accrual2.innerHTML = Math.round( (all_total2 - daikuan_total) *100)/100;


				//2.本息还款
					//月均还款
					var month_money1 = getMonthMoney1(lilv,daikuan_total,month);//调用函数计算
					_month_money1.innerHTML = Math.round(month_money1*100)/100 + "(元)";
					//还款总额
					var all_total1 = month_money1 * month;
					_all_total1.innerHTML = Math.round(all_total1*100)/100;
					//支付利息款
					accrual1.innerHTML = Math.round( (all_total1 - daikuan_total) *100)/100;

		}
	},
	//商业贷款计算end
	//提前还歀计算
	play:function(fm){
		var tqhdjsq = fm||document.tqhdjsq;
	  if (tqhdjsq.dkzws.value==''){
	  		_alert('请填写贷款总额');
	        return false;
	  }else dkzys=parseFloat(tqhdjsq.dkzws.value)*10000;

	  if(tqhdjsq.tqhkfs[1].checked && tqhdjsq.tqhkws.value==''){
	  	_alert('请填入部分提前还款额度');
	    return false;
	   }
	  s_yhkqs=parseInt(tqhdjsq.yhkqs.value);

	  //月利率

		if(tqhdjsq.dklx[0].checked){
			if (s_yhkqs>60){
				dklv = getlilv(tqhdjsq.dklv_class.value,2,10)/12; //公积金贷款利率5年以上4.23%
			}else{
				dklv = getlilv(tqhdjsq.dklv_class.value,2,3)/12;  //公积金贷款利率5年(含)以下3.78%
			}
		}
		if(tqhdjsq.dklx[1].checked){
			if (s_yhkqs>60){
				dklv=getlilv(tqhdjsq.dklv_class.value,1,10)/12; //商业性贷款利率5年以上5.31%
			}else{
				dklv=getlilv(tqhdjsq.dklv_class.value,1,3)/12; //商业性贷款利率5年(含)以下4.95%
			}
		}

	  //已还贷款期数
	  yhdkqs=(parseInt(tqhdjsq.tqhksjn.value)*12+parseInt(tqhdjsq.tqhksjy.value))-(parseInt(tqhdjsq.yhksjn.value)*12 + parseInt(tqhdjsq.yhksjy.value));

	  if(yhdkqs<0 || yhdkqs>s_yhkqs){
	  		_alert('预计提前还款时间与第一次还款时间有矛盾，请查实');
	   		return false;
	   }

	  yhk=dkzys*(dklv*Math.pow((1+dklv),s_yhkqs))/(Math.pow((1+dklv),s_yhkqs)-1);
	  yhkjssj=Math.floor((parseInt(tqhdjsq.yhksjn.value)*12+parseInt(tqhdjsq.yhksjy.value)+s_yhkqs-2)/12)+'年'+((parseInt(tqhdjsq.yhksjn.value)*12+parseInt(tqhdjsq.yhksjy.value)+s_yhkqs-2)%12+1)+'月';
	  yhdkys=yhk*yhdkqs;

	  yhlxs=0;
	  yhbjs=0;
	  for(i=1;i<=yhdkqs;i++){
	     yhlxs=yhlxs+(dkzys-yhbjs)*dklv;
	     yhbjs=yhbjs+yhk-(dkzys-yhbjs)*dklv;
	   }

	  remark='';
	  if(tqhdjsq.tqhkfs[1].checked){
	    tqhkys=parseInt(tqhdjsq.tqhkws.value)*10000;
	     if(tqhkys+yhk>=(dkzys-yhbjs)*(1+dklv)){
	         remark='您的提前还款额已足够还清所欠贷款！';
	     }else{
		        yhbjs=yhbjs+yhk;
	            byhk=yhk+tqhkys;
				if(tqhdjsq.clfs[0].checked){
				  yhbjs_temp=yhbjs+tqhkys;
	              for(xdkqs=0;yhbjs_temp<=dkzys;xdkqs++) yhbjs_temp=yhbjs_temp+yhk-(dkzys-yhbjs_temp)*dklv;
				  xdkqs=xdkqs-1;
	              xyhk=(dkzys-yhbjs-tqhkys)*(dklv*Math.pow((1+dklv),xdkqs))/(Math.pow((1+dklv),xdkqs)-1);
	              jslx=yhk*s_yhkqs-yhdkys-byhk-xyhk*xdkqs;
				  xdkjssj=Math.floor((parseInt(tqhdjsq.tqhksjn.value)*12+parseInt(tqhdjsq.tqhksjy.value)+xdkqs-2)/12)+'年'+((parseInt(tqhdjsq.tqhksjn.value)*12+parseInt(tqhdjsq.tqhksjy.value)+xdkqs-2)%12+1)+'月'; 
	             }else{
			       xyhk=(dkzys-yhbjs-tqhkys)*(dklv*Math.pow((1+dklv),(s_yhkqs-yhdkqs)))/(Math.pow((1+dklv),(s_yhkqs-yhdkqs))-1);
	               jslx=yhk*s_yhkqs-yhdkys-byhk-xyhk*(s_yhkqs-yhdkqs);
				   xdkjssj=yhkjssj;
				  }
	       }
	   }

	  if(tqhdjsq.tqhkfs[0].checked || remark!=''){
	    byhk=(dkzys-yhbjs)*(1+dklv);
	    xyhk=0;
	    jslx=yhk*s_yhkqs-yhdkys-byhk;
	    xdkjssj=tqhdjsq.tqhksjn.value+'年'+tqhdjsq.tqhksjy.value+'月';
		}

	  ykhke.innerHTML=Math.round(yhk*100)/100;
	  yhkze.innerHTML=Math.round(yhdkys*100)/100;
	  yhlxe.innerHTML=Math.round(yhlxs*100)/100;
	  gyyihke.innerHTML=Math.round(byhk*100)/100;
	  xyqyhke.innerHTML=Math.round(xyhk*100)/100;
	  jslxzc.innerHTML=Math.round(jslx*100)/100;
	  yzhhkq.innerHTML=yhkjssj;
	  xdzhhkq.innerHTML=xdkjssj;
	  jsjgts.innerHTML=remark;
	},
	//提前还歀计算end
	//结果切换
	showR:function(fmobj,data){
		var div1=fmobj.find(".divr1");//document.getElementById("divr1");
		var div2=fmobj.find(".divr2");//document.getElementById("divr2");
		if(data==1){
			div1.show()//.style.display="block";
			div2.hide()//.style.display="none";
		}else{
			div2.show()//.style.display="block";
			div1.hide()//.style.display="none";
		}
	},
	//结果切换end
	//计算方法切换
	exc_js:function(fmobj,v){
		// var div1=document.getElementById("divr1");
		// var div2=document.getElementById("divr2");
		// if(fmobj.htype[0].checked == true){
		// 	div1.style.display="block";
		// 	div2.style.display="none";
		// }else{
		// 	div2.style.display="block";
		// 	div1.style.display="none";
		// }
		if (fmobj.name=="calc1"){
			if (v==1){
				document.getElementById('calc1_js_div1').style.display='block';
				document.getElementById('calc1_js_div2').style.display='none';
				document.getElementById('calc1_zuhe').style.display='none';
				fmobj.type.value=1;
			}else{
				document.getElementById('calc1_js_div1').style.display='none';
				document.getElementById('calc1_js_div2').style.display='block';
			}
		}else{
			if (v==1){
				document.getElementById('calc2_js_div1').style.display='block';
				document.getElementById('calc2_js_div2').style.display='none';
				//document.getElementById('calc2_zuhe').style.display='none';
				fmobj.type.value=1;
			}else{
				document.getElementById('calc2_js_div1').style.display='none';
				document.getElementById('calc2_js_div2').style.display='block';
			}
		}
	}
	//计算方法切换end






});




})
//# sourceMappingURL=http://localhost:8888/public/js/jsq2.js.map
