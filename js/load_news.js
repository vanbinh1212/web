/*
|| Code by Snape Nguyen
|| -------------------------
|| Don't remove if you use it. Thanks very much!
*/

SnapeNews = {

	htmlId : {
		contentRoot: '.news_list',
		pagin : '.pagin_news'
	},

	vars : {
		currentPage: 0,
		totalPage: 0,
		visiblePages : 7,
		urlResult : full_url + '/ajax/loadnews.php',
		dataNews : []
	},

	init: function (page) {
		// load news
		SnapeNews.getResult(page, true);
	},

	pagin: function() {
		$(SnapeNews.htmlId.pagin).twbsPagination({
	        totalPages: SnapeNews.vars.totalPage,
	        visiblePages: SnapeNews.vars.visiblePages,
	        startPage: SnapeNews.vars.currentPage,
	        first: 'Đầu',
	        prev: 'Trước',
	        next : 'Sau',
	        last : 'Cuối',
	        onPageClick: function (event, page) {
	            SnapeNews.getResult(page, false);
	        }
	    });
	},

	getResult: function(page, updatePagin) {
		if(updatePagin)
			$(SnapeNews.htmlId.contentRoot).html("<div align='center'><img src='"+full_url+"/images/loading.gif' /> Đang tải dữ liệu...</div>");
		else
			$(SnapeNews.htmlId.contentRoot).css("opacity", 0.5);
		$.post(SnapeNews.vars.urlResult, {"page" : page}, function(data) {
			// init news
			SnapeNews.vars.currentPage = page;
			SnapeNews.vars.totalPage = data['totalpage'];
			SnapeNews.vars.dataNews = data['list'];

			// init pagin
			if(updatePagin)
				SnapeNews.pagin();

			// update result
			SnapeNews.updateContent();

			$(SnapeNews.htmlId.contentRoot).css("opacity", 1);
		}, 'json');
	},

	updateContent: function() {
		var shtml = "";
		for (var i = 0; i < SnapeNews.vars.dataNews.length; i++) {
			shtml += '<div class="article">';
			shtml += '<div class="article-header">';
			shtml += '<div class="title_article"><span id="txtpointfree" class="badge">'+SnapeNews.vars.dataNews[i].Type+'</span> <a href="'+SnapeNews.vars.dataNews[i].Link+'" title="Title">'+SnapeNews.vars.dataNews[i].Title+'</a> <span class="post_date">['+SnapeNews.vars.dataNews[i].TimeCreate+']</span></div>';
			shtml += '</div>';
			shtml += '<div class="article-des">';
			shtml += '<div class="post_des">'+SnapeNews.vars.dataNews[i].Content+'</div>';
			shtml += '</div>';
			shtml += '</div>';

			if(i >= SnapeNews.vars.dataNews.length - 1) continue;

			shtml += '<div class="separator"></div>';
		}
		$(SnapeNews.htmlId.contentRoot).html(shtml);
	},
	getTop: function(type) {
		SnapeClass.show_big_loading();
		$.post( full_url + "/ajax/gettop.php", {type}, function( data ) {
			if(data.content && data.content == 'timeout') {//treo loading
				setTimeout(function(){ SnapeNews.getTop(type); }, 2300);
				return;
			}
			var shtml = '';
			Object.keys(data).map(serverName => {
				shtml += '<table class="table table-hover">';
				shtml += '<thead>';
				shtml += '<tr>';
				shtml += '<th>Server</th>';
				data[serverName].data && data[serverName].data[0] && Object.keys(data[serverName].data[0]).map(itemHeadName => {
					shtml += '<th>'+itemHeadName+'</th>';
				});
				shtml += '</tr>';
				shtml += '</thead>';
				shtml += '<tbody>';
				data[serverName].data.map(itemAccount => {
					shtml += '<tr class="success">';
					shtml += '<td>' + serverName + ' </td>';
					Object.keys(itemAccount).map(itemRowName => {
						shtml += '<td>' + (!isNaN(itemAccount[itemRowName]) ? $.number(itemAccount[itemRowName]) :  itemAccount[itemRowName]) + '</td>';
					});
					shtml += '</tr>';
					
				});
				shtml += '</tbody>';
				shtml += '</table>';;
				
		  });
		  $('.article').html(shtml);

		  SnapeClass.hide_big_loading();
		}, 'json').fail(function() {
			SnapeClass.hide_big_loading();
		});
	}
}