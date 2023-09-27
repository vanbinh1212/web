/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

ItemBag = {
	htmlId : {
		contentRoot : '#contentItemsBag',
		loadingItems : '#loadingItems',
		contentResult : '#resultItems',
		serverField : '#txtserver',
		characterField : '#txtcharacter'
	},
	vars : {
		urlLoadItem : full_url + '/ajax/loaditembag.php',
		urlSendItem : full_url + '/ajax/senditembag.php',
		listItems : []
	},
	init: function() {
		ItemBag.showLoading('Đang tải vật phẩm web...');
		$.post(ItemBag.vars.urlLoadItem, function(data) {
			if(data['type'] == 0) {
				ItemBag.vars.listItems = data['list'];
				ItemBag.showItems();
				ItemBag.hideLoading();
			} else {
				SnapeClass.openModal('Lỗi lấy Vật phẩm Web', data['content'], []);
			}
		}, 'json');
	},
	showItems: function() {
		var shtml = '';
		for (var i = 0; i < ItemBag.vars.listItems.length; i++) {
			shtml += '<tr><th style="text-align: center;"><img class="personPopupTrigger" alt="'+ItemBag.vars.listItems[i].TemplateID+','+ItemBag.vars.listItems[i].VaildDate+'.'+ItemBag.vars.listItems[i].IsBind+'" src="'+ItemBag.vars.listItems[i].Picture+'" /></th><td>'+ItemBag.vars.listItems[i].Count+'</td><td>'+(ItemBag.vars.listItems[i].VaildDate == 0 ? 'Vĩnh viễn' : ItemBag.vars.listItems[i].VaildDate)+'</td><td>'+ItemBag.vars.listItems[i].IsBind+'</td><td>'+ItemBag.vars.listItems[i].ServerName+'</td></tr>';
		}
		$(ItemBag.htmlId.contentResult).html(shtml);
	},
	addItem: function(item) {
		var shtml = '<tr><th style="text-align: center;"><img src="'+item.Picture+'" /></th><td>'+item.Count+'</td><td>'+item.VaildDate+'</td><td>'+item.IsBind+'</td><td>'+item.ServerName+'</td></tr>' + $(ItemBag.htmlId.contentResult).html();
		$(ItemBag.htmlId.contentResult).html(shtml);
	},
	sendItems: function() {
		var svid = $(ItemBag.htmlId.serverField).val();
		
		var cid = $(ItemBag.htmlId.characterField).val();
		
		if(!svid || svid <= 0)
		{
			SnapeClass.openModal('Lỗi gửi vật phẩm', "Vui lòng chọn máy chủ nhận vật phẩm.", []);
			return;
		}
		
		if(!cid || cid <= 0)
		{
			SnapeClass.openModal('Lỗi gửi vật phẩm', "Vui lòng chọn nhân vật.", []);
			return;
		}
		
		ItemBag.showLoading('Đang gửi vật phẩm vào game. Vui lòng không tắt trang...');
		$.post(ItemBag.vars.urlSendItem + "?svid=" + svid + "&cid=" + cid, function(data) {
			if(data['type'] == 0) {
				SnapeClass.openModal('Thành công', "Đã gửi vật phẩm vào game thành công. Vui lòng kiểm tra thư trong game. Nếu gặp vấn đề không nhận được vật phẩm. Vui lòng liên hệ Game Master để được hỗ trợ.", []);
				ItemBag.init();
			} else {
				SnapeClass.openModal('Lỗi lấy Vật phẩm Web', data['content'], []);
			}
			ItemBag.hideLoading();
		}, 'json');
	},
	showLoading: function(text) {
		$(ItemBag.htmlId.loadingItems).html("<div align='center'><img src='"+full_url+"/images/loading.gif' /> "+text+"</div>").fadeIn();
		$(ItemBag.htmlId.contentRoot).hide();
	},
	hideLoading: function() {
		$(ItemBag.htmlId.loadingItems).hide();
		$(ItemBag.htmlId.contentRoot).fadeIn();
	}
}