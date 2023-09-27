/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

SnapeGuild = {
	vars : {
		guildTour : null
	},
	
	guildHome : function() {
		guildTour = new Anno([
			{
				target: '.list-group',
				content: 'Đây là danh mục truy cập nhanh.'
		    },
			{
				target: '#guildPlay',
				content: 'Nhấp vào đây để trải nghiệm game ngay lập tức.'
			},
			{
				target: '#guildAccount',
				content: 'Ở đây cho phép bạn xem được các thông tin tài khoản và thay đổi chúng.'
			},
			{
				target: '#guildWebshop',
				content: 'Webshop bày bán các vật phẩm HOT trong game, tài khoản mới được khuyến mãi 500.000 Coin bạn có thể sử dụng chúng tại đây để mua nhiều vật phẩm hấp dẫn.'
			},
			{
				target: '#guildBag',
				content: 'Túi web chứa các vật phẩm mua từ Webshop, hộp quà hoặc sự kiện trên web. Hãy vào đây chuyển vô game để sử dụng nhé.'
			},
			{
				target: '#guildHelp',
				content: 'Bất cứ yêu cầu hỗ trợ hay góp ý nào hãy vào đây để gửi yêu cầu ngay nhé.'
			}
		]);
		return guildTour;
	},
	
	guildWebshop : function() {
		guildTour = new Anno([
			{
				target: '#menuws',
				content: 'Bạn có thể chọn hiển thị các loại danh mục vật phẩm như Vũ khí, Trang sức, Thú cưng, vv.. ở đây để dễ dàng lựa chọn.'
			}
		]);
		return guildTour;
		
	}
}