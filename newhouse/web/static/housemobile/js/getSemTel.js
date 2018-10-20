seajs.use(['zgrequest'],function (zgrequest){
    var spread = WebCookie.getCookie('spread');
	var houseId = $('#j_houseId').val();
    console.log(houseId,'houseId')
	var url = '/sems/' + houseId;
	var data = {
		spread : spread
	}
	function setSemTel(val){
		var phoneBox = $('.j_phoneBox b');
		phoneBox.text(val);
	}

	if(spread){
		zgrequest.post(url,data,function(xhr){
            console.log(xhr)
			if(xhr && xhr.code ==200 && xhr.data && xhr.data.length > 0)
            console.log(xhr,'xhr')
				var phoneNum = xhr.data.tel;
				setSemTel(phoneNum)
		},2,'get')
	}
})
