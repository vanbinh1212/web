/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

SnapeHistory = {

	htmlId : {
		contentRoot: '.history_list',
		pagin : '.page_history'
	},

	vars : {
		currentPage: 0,
		totalPage: 0,
		visiblePages : 7,
		urlResult : full_url + '/ajax/loadhistory.php',
		dataHistory : []
	},

	init: function (page) {
		// load news
		SnapeHistory.getResult(page, true);
	},

	pagin: function() {
		$(SnapeHistory.htmlId.pagin).twbsPagination({
	        totalPages: SnapeHistory.vars.totalPage,
	        visiblePages: SnapeHistory.vars.visiblePages,
	        startPage: SnapeHistory.vars.currentPage,
	        first: 'Đầu',
	        prev: 'Trước',
	        next : 'Sau',
	        last : 'Cuối',
	        onPageClick: function (event, page) {
	            SnapeHistory.getResult(page, false);
	        }
	    });
	},

	getResult: function(page, updatePagin) {
		if(updatePagin)
			$(SnapeHistory.htmlId.contentRoot).html("<div align='center'><img src='"+full_url+"/images/loading.gif' /> Đang tải dữ liệu...</div>");
		else
			$(SnapeHistory.htmlId.contentRoot).css("opacity", 0.5);
		$.post(SnapeHistory.vars.urlResult, {"page" : page}, function(data) {
			// init news
			SnapeHistory.vars.currentPage = page;
			SnapeHistory.vars.totalPage = data['totalpage'];
			SnapeHistory.vars.dataHistory = data['list'];
			
			if(SnapeHistory.vars.dataHistory.length <= 0) {
				$(SnapeHistory.htmlId.contentRoot).html("<div align='center'>Chưa có giao dịch nào được thực hiện</div>");
				return;
			}
			// init pagin
			if(updatePagin)
				SnapeHistory.pagin();

			// update result
			SnapeHistory.updateContent();

			$(SnapeHistory.htmlId.contentRoot).css("opacity", 1);
		}, 'json');
	},

	updateContent: function() {
		var shtml = '<table class="table table-striped table-hover">';
		shtml += '<thead><tr><th>Mã GD</th><th>Loại giao dịch</th><th>Nội dung</th><th>CASH</th></tr></thead>';
		shtml += '<tbody>';
		for (var i = 0; i < SnapeHistory.vars.dataHistory.length; i++) {
			shtml += '<tr>';
			
			shtml += '<th scope="row">#'+SnapeHistory.vars.dataHistory[i].ID+'</th>';
			shtml += '<td>'+SnapeHistory.vars.dataHistory[i].Type+'</td>';
			shtml += '<td>'+SnapeHistory.vars.dataHistory[i].Content+'</td>';
			shtml += '<td>'+SnapeHistory.vars.dataHistory[i].Cash+'</td>';

			shtml += '</tr>';
		}
		shtml += '</tbody>';
		shtml += '</table>';
		$(SnapeHistory.htmlId.contentRoot).html(shtml);
	}
}