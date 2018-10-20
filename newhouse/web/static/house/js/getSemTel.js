seajs.use(['zgrequest'],function (zgrequest){
    var spread = WebCookie.getCookie('spread');
    console.log(spread,'spread')
	var houseId = $('#j_houseId').val();
	var url = '';
    var testStr1 = /\bsem/;
    var testStr2 = /\bia/;
    if(testStr1.test(spread)){
        url = '/sem';
    }
    if(testStr2.test(spread)){
        url = '/dsp';
    }
	var data = {
		spread : spread,
        houseIds : houseId
	}
	function setSemTel(val){
		var phoneBox = $('.j_phoneBox span');
		phoneBox.text(val);
	}
	if(spread){
		zgrequest.post(url,data,function(xhr){
			if(xhr && xhr.data && xhr.data[houseId]){
				var phoneNum = xhr.data[houseId];
				setSemTel(phoneNum)
            }
		},2,'get')
	}
})
