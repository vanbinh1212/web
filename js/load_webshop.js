/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

SnapeWebshop = {

	htmlId : {
		contentRoot: '.webshop-items',
		pagin : '.webshop-pagin'
	},

	vars : {
		currentPage: 0,
		totalPage: 0,
		visiblePages : 7,
		urlResult : full_url + '/ajax/loadwebshop.php',
		currentType: 0,
		dataWebshop : [],
		cart : []
	},

	init: function (page, type) {
		// load
		
		SnapeWebshop.vars.currentType = type;
		
		SnapeWebshop.getResult(page, type, true);
	},

	pagin: function() {
		if($(SnapeWebshop.htmlId.pagin).data("twbs-pagination")) {
            $(SnapeWebshop.htmlId.pagin).twbsPagination('destroy');
        }
		
		$(SnapeWebshop.htmlId.pagin).twbsPagination({
	        totalPages: SnapeWebshop.vars.totalPage,
	        visiblePages: SnapeWebshop.vars.visiblePages,
	        startPage: SnapeWebshop.vars.currentPage,
	        first: 'Đầu',
	        prev: 'Trước',
	        next : 'Sau',
	        last : 'Cuối',
	        onPageClick: function (event, page) {
	            SnapeWebshop.getResult(page, SnapeWebshop.vars.currentType, false);
	        }
	    });
	},

	getResult: function(page, type, updatePagin) {
		if(updatePagin)
			$(SnapeWebshop.htmlId.contentRoot).html("<div align='center'><img src='"+full_url+"/images/loading.gif' /> Đang tải dữ liệu...</div>");
		else
			$(SnapeWebshop.htmlId.contentRoot).css("opacity", 0.5);
		
		$.post(SnapeWebshop.vars.urlResult, {"page" : page, "type" : SnapeWebshop.vars.currentType}, function(data) {
			// init news
			SnapeWebshop.vars.currentPage = page;
			SnapeWebshop.vars.totalPage = data['totalpage'];
			SnapeWebshop.vars.dataWebshop = data['list'];

			// init pagin
			if(updatePagin && SnapeWebshop.vars.totalPage > 0)
				SnapeWebshop.pagin();

			// update result
			SnapeWebshop.updateContent();

			$(SnapeWebshop.htmlId.contentRoot).css("opacity", 1);
		}, 'json');
	},

	updateContent: function() {
		var shtml = "";
		for (var i = 0; i < SnapeWebshop.vars.dataWebshop.length; i++) {
			shtml += '<article class="webshop-item-v1">';
			shtml += '<img class="personPopupTrigger" alt="'+SnapeWebshop.vars.dataWebshop[i].TemplateID+','+SnapeWebshop.vars.dataWebshop[i].VaildDate+'.'+SnapeWebshop.vars.dataWebshop[i].IsBind+'" width="100px" height="100px" src="'+SnapeWebshop.vars.dataWebshop[i].Picture+'" />';
			shtml += '<h2>'+SnapeWebshop.subString(SnapeWebshop.vars.dataWebshop[i].Name)+'</h2>';
			shtml += '<div class="webshop-linetext">Giá: <em><b>'+$.number(SnapeWebshop.vars.dataWebshop[i].Price)+'</b></em> Coin</div>';
			shtml += '<div class="webshop-linetext">Số lượng: <em><b>'+$.number(SnapeWebshop.vars.dataWebshop[i].Count)+'</b></em></div>';
			shtml += '<div class="webshop-linetext">Người bán: <b>'+SnapeWebshop.vars.dataWebshop[i].Seller+'</b></div>';
			shtml += '<div class="webshop-linetext">Máy chủ: <font color="green"><b>'+SnapeWebshop.vars.dataWebshop[i].ServerName+'</b></font></div>';
			shtml += '<button id="add-to-cart" class="btn btn-primary btn-block" type="button" data="'+SnapeWebshop.vars.dataWebshop[i].DataCart+'">Thêm vào giỏ</button>';
			shtml += '</article>';
		}
		$(SnapeWebshop.htmlId.contentRoot).html(shtml);
	},
	
	addToCart: function(data) {
		
		var canAddCart = false;
		
		if(SnapeWebshop.vars.cart.length >= 10) {
			SnapeClass.openModal("Lỗi thêm giỏ hàng", "Giỏ hàng chỉ chứa tối đa 10 vật phẩm.", []);
			return false;
		}
		
		var dataArr = data.split(",");
		
		var dataObject = {"itemid" : dataArr[0], "sellername" : dataArr[1], "templateid" : dataArr[2], "count" : dataArr[3], "price" : dataArr[4], "vailddate" : dataArr[5], "isbind" : dataArr[6], "multibuy" : dataArr[7], "picture" : dataArr[8], "sellerid" : dataArr[9], "realcount" : 1, "servername" : dataArr[10]};
		
		// check item can buy more
		var checkOnCart = SnapeWebshop.getItemOnCart(dataObject.itemid);
		
		if(checkOnCart >= 0) {
			
			// in cart
			if(SnapeWebshop.vars.cart[checkOnCart].sellerid != 0) {
				
				SnapeClass.openModal("Lỗi thêm giỏ hàng", "Vật phẩm do người chơi bán không thể mua nhiều lần.", []);
				
			} else if(SnapeWebshop.vars.cart[checkOnCart].sellerid == 0 && SnapeWebshop.vars.cart[checkOnCart].multibuy == true && SnapeWebshop.vars.cart[checkOnCart].realcount < 999) {
				
				canAddCart = true;
				SnapeWebshop.vars.cart[checkOnCart].realcount++;
				
			} else {
				
				canAddCart = true;
				SnapeWebshop.vars.cart[SnapeWebshop.vars.cart.length] = dataObject;
				
			}
			
		} else {
			
			canAddCart = true;
			SnapeWebshop.vars.cart[SnapeWebshop.vars.cart.length] = dataObject;
			
		}
		
		return canAddCart;
	},
	
	getItemOnCart: function(itemid) {
		
		for (var i = 0; i < SnapeWebshop.vars.cart.length; i++) {
			
			if(SnapeWebshop.vars.cart[i].itemid == itemid && SnapeWebshop.vars.cart[i].realcount < 999) {
				
				return i;
				
			}
		}
		
		return -1;
	},
	
	getTotalMoney: function(fieldupdate) {
		
		var totalpay = 0;
		
		for (var i = 0; i < SnapeWebshop.vars.cart.length; i++) {
			
			
			
			var textCount = $("#txtcount" + SnapeWebshop.vars.cart[i].itemid).val();
			
			if(isNaN(textCount) == true || textCount <= 0 || textCount > 999) {
				
				textCount = 1;
				$("#txtcount" + SnapeWebshop.vars.cart[i].itemid).val(textCount);
				
			}
			
			SnapeWebshop.vars.cart[i].realcount = textCount;
			
			totalpay += SnapeWebshop.vars.cart[i].price * SnapeWebshop.vars.cart[i].realcount;
			
		}
		
		$(fieldupdate).html($.number(totalpay));
		
	},
	
	clearResult: function(fieldupdate) {
		
		SnapeWebshop.vars.cart = [];
		
		$(fieldupdate).html('0');
		
		SnapeWebshop.showCart();
		
	},
	
	showCart: function() {
		
		var lists = '';
		
		var totalpay = 0;
		
		for (var i = 0; i < SnapeWebshop.vars.cart.length; i++) {
			
			var disableRealCount = false;
			if(SnapeWebshop.vars.cart[i].sellerid != 0 || SnapeWebshop.vars.cart[i].multibuy == false)
				disableRealCount = true;
			
			totalpay += SnapeWebshop.vars.cart[i].price * SnapeWebshop.vars.cart[i].realcount;
			
			lists += '<tr><th scope="row"><img class="personPopupTrigger" alt="'+SnapeWebshop.vars.cart[i].templateid+','+SnapeWebshop.vars.cart[i].vailddate+'.'+SnapeWebshop.vars.cart[i].isbind+'" src="' + SnapeWebshop.vars.cart[i].picture + '" width="50px" /></th><td>'+SnapeWebshop.vars.cart[i].sellername+'</td><td>'+SnapeWebshop.vars.cart[i].servername+'</td><td>'+SnapeWebshop.vars.cart[i].count+'</td><td>'+$.number(SnapeWebshop.vars.cart[i].price)+'</td><td><input type="text" class="form-control count_item_cart" id="txtcount'+SnapeWebshop.vars.cart[i].itemid+'" value="'+SnapeWebshop.vars.cart[i].realcount+'" maxlength="3" style="width: 50px;" '+((disableRealCount == true) ? 'disabled' : '')+' /></div></tr>';
		}
		lists += '<tr align="right"><td colspan="5"><b>Tổng Coin phải trả:</b></td><td><font color="blue"><b id="totalmoneypay_text">'+$.number(totalpay)+'</b> Coin</font></td></tr>';
		lists += '<tr align="right"><td colspan="6"><button id="submitBuyItems" type="button" class="btn btn-success">Mua vật phẩm</button> <button type="button" class="btn btn-danger" onclick="SnapeWebshop.clearResult(\'#totalitems\')">Xóa toàn bộ</button></td></tr>';
		var shtml = '<table id="tbCart" class="table table-hover"><thead><tr><th>V.phẩm</th><th>Người bán</th><th>Máy chủ</th><th>S.lượng</th><th>Giá bán</th><th>Số mua</th></tr></thead><tbody>'+lists+'</tbody></table><div id="txtresultbuy" class="alert alert-danger" role="alert" style="display:none"></div>';
		SnapeClass.openModal('Giỏ hàng', shtml, []);
	},
	
	getParams: function() {
		
		var params = [];
		
		for (var i = 0; i < SnapeWebshop.vars.cart.length; i++) {
			
			params[i] = SnapeWebshop.vars.cart[i].itemid + "." + SnapeWebshop.vars.cart[i].realcount;
			
		}
		
		return params.toString();
	},
	
	subString: function(str) {
		
		if(str.length > 15) {
			return str.substring(0, 15) + '...';
		}
		else
			return str;
		
	}
}