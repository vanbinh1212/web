/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

LuckyBoxSpin = {
	htmlId : {
		contentRoot: '#luckyboxbg',
		serverid : '#txtserverspin',
		point: '#txtpointfree',
		coin: '#txtcoin',
		coingame: '#txtcoingame'
	},
	vars : {
		boxId: 0,
		boxName: '',
		urlListBox : full_url + '/ajax/loadluckybox.php',
		urlResult : full_url + '/ajax/resultluckybox.php',
		urlTop: full_url + '/ajax/loadtopluckybox.php',
		urlGetPoint : full_url + '/tinh-nang/diem-mien-phi/',
		urlReCharge : full_url + '/tai-khoan/nap-the/',
		dataBox : [],
		dataSpin: [],
		isLoadTop: false,
		dataTop: [],
		countSpin: 0,
		isSpin: false,
		maxLoop: 40,
		typeSpin : 0,
		priceCoin: 1000,
		priceCG: 1000,
		isNotice: true
	},
	init: function (boxId) {
		if(LuckyBoxSpin.vars.isSpin) {
			SnapeClass.openModal('Cảnh báo', "Đang mở hộp quà! Vui lòng chờ mở xong hộp quà mới được chuyển", []);
			return;
		}
		SnapeClass.show_big_loading();
		LuckyBoxSpin.vars.boxId = boxId;
		LuckyBoxSpin.vars.isNotice = true;
		$.post(LuckyBoxSpin.vars.urlListBox + "?id=" + boxId, function(data) {
			SnapeClass.hide_big_loading();
			LuckyBoxSpin.vars.dataBox = data['list'];
			LuckyBoxSpin.vars.dataSpin = data['review'];
			LuckyBoxSpin.vars.boxName = data['Name'];
			LuckyBoxSpin.vars.priceCoin = data['Coin'];
			LuckyBoxSpin.vars.priceCG = data['CoinGame'];
			$('#namebox').html("Hộp quà " + LuckyBoxSpin.vars.boxName);
			$(LuckyBoxSpin.htmlId.coin).html(LuckyBoxSpin.vars.priceCoin);
			$(LuckyBoxSpin.htmlId.coingame).html(LuckyBoxSpin.vars.priceCG);
			$("#list_luckybox a").removeClass('active');
			$("#openbox_" + boxId).addClass('active');
			var isLoadFirst = false;
			for (var i = 0; i < LuckyBoxSpin.vars.dataSpin.length; i++) {
				if(LuckyBoxSpin.vars.dataSpin[i].IsHot == true) {
					var shtml = '<div class="spinItem">';
					shtml += '<img src="'+ LuckyBoxSpin.vars.dataSpin[i].Picture +'" alt="'+ LuckyBoxSpin.vars.dataSpin[i].Name +'" />';
					shtml += '</div>';
					$("#luckybox_content", LuckyBoxSpin.htmlId.contentRoot).html(shtml);
					isLoadFirst = true;
				} else if(isLoadFirst){
					break;
				}
			}
		}, 'json');
	},
	spin: function (typeSpin) {
		if(LuckyBoxSpin.vars.isSpin) return;
		LuckyBoxSpin.vars.typeSpin = typeSpin;
		if($(LuckyBoxSpin.htmlId.serverid).val() == null || $(LuckyBoxSpin.htmlId.serverid).val() == '0') {
			SnapeClass.openConfirm('Chọn máy chủ', 'Vui lòng chọn máy chủ nhận quà', []);
			return;
		}
		
		// check type spin
		switch(typeSpin) {
			case 1:
			LuckyBoxSpin.nextSpin();
			break;

			case 2:
			
			if(LuckyBoxSpin.vars.priceCoin <= 0) {
				SnapeClass.openConfirm('Lỗi giao dịch', 'Hộp quà này không thể mở bằng Coin', []);
				return;
			}
			
			if(LuckyBoxSpin.vars.isNotice == true)
				SnapeClass.openConfirm('Yêu cầu xác thực', 'Bạn có chắc là sẽ thanh toán <b>'+LuckyBoxSpin.vars.priceCoin+'</b> Coin để mở hộp quà này?', [{Name:"Mở hộp quà", Action:'LuckyBoxSpin.nextSpin()'}]);
			else
				LuckyBoxSpin.nextSpin();
			
			LuckyBoxSpin.vars.isNotice = false;
			
			break;

			case 3:
			
			if(LuckyBoxSpin.vars.priceCG <= 0) {
				SnapeClass.openConfirm('Lỗi giao dịch', 'Hộp quà này không thể mở bằng CoinGame', []);
				return;
			}
			
			if(LuckyBoxSpin.vars.isNotice == true)
				SnapeClass.openConfirm('Yêu cầu xác thực', 'Bạn có chắc là sẽ thanh toán <b>'+LuckyBoxSpin.vars.priceCG+'</b> Coin Game để mở hộp quà này?', [{Name:"Mở hộp quà", Action:'LuckyBoxSpin.nextSpin()'}]);
			else
				LuckyBoxSpin.nextSpin();
			
			LuckyBoxSpin.vars.isNotice = false;
			
			break;
		}
		
		
	},
	nextSpin: function() {
		SnapeClass.show_big_loading();
		// check can spin & result
		$.post(LuckyBoxSpin.vars.urlResult + "?id=" + LuckyBoxSpin.vars.boxId + "&type=" + LuckyBoxSpin.vars.typeSpin + "&serverid=" + $(LuckyBoxSpin.htmlId.serverid).val(), function(data) {
			// check can spin
			SnapeClass.hide_big_loading();
			if(data['type'] == 0) {
				LuckyBoxSpin.vars.isSpin = true;
				$("#txtaward").html("<font color='red'>Đang mở hộp quà...</font>");
				LuckyBoxSpin.finalSpin(data['award']);
			} else {
				switch(data['type']) {
					case -101:
					SnapeClass.openModal('Thông báo', data['content'], [{Name:"Nạp thêm COIN", Link:LuckyBoxSpin.vars.urlReCharge}]);
					break;
					case -102:
					SnapeClass.openModal('Thông báo', data['content'], [{Name:"Nhận thêm điểm", Link:LuckyBoxSpin.vars.urlGetPoint}]);
					break;
					default:
					SnapeClass.openModal('Thông báo', data['content'], []);
					break;
				}
			}
		}, 'json');
	},
	finalSpin: function(resultData) {
		//LuckyBoxSpin.vars.maxLoop = 0;
		var countLoop = 0;
		var placeSpin = 0;
		(function step() {
			if(countLoop < LuckyBoxSpin.vars.maxLoop) {
				if(placeSpin >= LuckyBoxSpin.vars.dataSpin.length)
					placeSpin = 0;
				LuckyBoxSpin.changeSpin(LuckyBoxSpin.vars.dataSpin[placeSpin]);
				setTimeout(step, 100);
				countLoop++;
				placeSpin++;
				//console.log('placeSkin: ' + placeSpin + ' - countLoop: ' + countLoop);
			} else {
				LuckyBoxSpin.showAward(resultData);
			}
		})();
	},
	changeSpin: function(result) {
		var shtml = '<div class="spinItem">';
		shtml += '<img src="'+ result.Picture +'" alt="'+ result.Name +'" />';
		shtml += '</div>';
		$("#luckybox_content", LuckyBoxSpin.htmlId.contentRoot).html(shtml);
	},
	showAward: function(result) {
		LuckyBoxSpin.vars.isSpin = false;
		LuckyBoxSpin.changeSpin(result);
		$("#txtaward").html("Bạn nhận được: <i><font color='red'>" + result['Name'] + "x" + result['Count'] + "</font></i>");
		var itemAward = {"Name": result['Name'], "Count": result['Count'], "Picture": result['Picture'], "VaildDate": "Vĩnh viễn", "IsBind": "Đã khóa"};
		ItemBag.addItem(itemAward);
	},
	listaward: function() {
		var lists = '';
		for (var i = 0; i < LuckyBoxSpin.vars.dataBox.length; i++) {
			lists += '<div id="item_game" class="col-xs-6 col-md-3"><a href="#" class="thumbnail" style="height: 90px;"><span class="count_items">'+LuckyBoxSpin.vars.dataBox[i].Count+'</span><img src="'+LuckyBoxSpin.vars.dataBox[i].Picture+'" alt="'+LuckyBoxSpin.vars.dataBox[i].Name+'" style="height: 80px;"></a></div>';
		}
		var shtml = '<div class="row">'+lists+'</div>';
		SnapeClass.openModal('Vật phẩm hộp quà ' + LuckyBoxSpin.vars.boxName, shtml, []);
	},
	loadTop: function() {
		if(!LuckyBoxSpin.vars.isLoadTop) {
			// load top
			SnapeClass.show_big_loading();
			$.post(LuckyBoxSpin.vars.urlTop, function(data) {
				SnapeClass.hide_big_loading();
				if(data['type'] == 0) {
					// top
					LuckyBoxSpin.vars.isLoadTop = true;
					LuckyBoxSpin.vars.dataTop = data['content'];
					LuckyBoxSpin.showTop();
				} else {
					SnapeClass.openModal('Thông báo', 'Không thể load TOP. Vui lòng thử lại sau.', []);
				}
			}, 'json');
		} else {
			LuckyBoxSpin.showTop();
		}
	},
	showTop: function() {
		
		var lists = '';
		for (var i = 0; i < LuckyBoxSpin.vars.dataTop.length; i++) {
			lists += '<tr><th scope="row">' + (i + 1) + '</th><td>'+LuckyBoxSpin.vars.dataTop[i].Username+'</td><td>'+LuckyBoxSpin.vars.dataTop[i].CountLucky+'</td></tr>';
		}
		var shtml = '<table class="table table-hover"><thead><tr><th>TOP</th><th>Tài khoản</th><th>Lượt mở</th></tr></thead><tbody>'+lists+'</tbody></table>';
		SnapeClass.openModal('TOP 10 NGƯỜI CHƠI MỞ NHIỀU NHẤT', shtml, []);
	}
}