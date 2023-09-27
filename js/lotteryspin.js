var LotterySpin = {
	
	htmlId : {
		countSpin : "",
		contentRoot : "",
	},
	
	vars : {
		index : 1,
		speed : 400,
		roll:0,
		cycle : 1,
		times : 4,
		prize : -1,
		isRun: false,
		dataItems : [],
		totalSpin : 0,
		urlSpin : "http://localhost/",
		urlInit : "http://localhost/",
	},
	
	init : function () {
		if(LotterySpin.vars.isRun)
			return;
		
		SnapeClass.show_big_loading();
		$.post(LotterySpin.vars.urlInit, function(data) {
			
			SnapeClass.hide_big_loading();
			
			LotterySpin.vars.dataItems = data.list;
			
			LotterySpin.vars.totalSpin = data.totalSpin;
			
			var shtml = '<ul class="lucky_spin" id="lottery_spin_gunht">';
			for (var i = 1; i <= LotterySpin.vars.dataItems.length; i++) {
				shtml += '<li class="li1 roll roll-' + i + ' ' + ((i == 1) ? 'active' : '') + '"><p>' + LotterySpin.vars.dataItems[i - 1].Name + '</p><img src="'+LotterySpin.vars.dataItems[i - 1].Picture+'" alt="" style="left:40px;bottom:61px"/></li>';
			}
			shtml += '<li class="btn_lotteryspin btn_lotteryspin_effect" id="start_spin"><p></p></li>';
			shtml += '</ul>';
			
			$(LotterySpin.htmlId.contentRoot).html(shtml);
			
			$(LotterySpin.htmlId.countSpin).html(LotterySpin.vars.totalSpin);
			
		}, 'json');
	},
	
	reset : function () {
		
		if(LotterySpin.vars.isRun == true)
			return;
		
		LotterySpin.vars.isRun = true;
		
		$(LotterySpin.htmlId.contentRoot, "#start_spin").removeClass('btn_lotteryspin_effect').addClass("btn_lotteryspin_disable");
		
		$.post(LotterySpin.vars.urlSpin, function(data) {
			
			LotterySpin.vars.prize = data.result;
			
			LotterySpin.vars.totalSpin = data.totalSpin;
			
			LotterySpin.vars.cycle = 0;
			
			$(LotterySpin.htmlId.countSpin).html(LotterySpin.vars.totalSpin);
			
			LotterySpin.run();
			
		}, 'json');
    },
	
	run : function () {
        var before = LotterySpin.vars.index == 1 ? 10 : LotterySpin.vars.index - 1;
        $(LotterySpin.htmlId.contentRoot, ".roll-" + LotterySpin.vars.index).addClass("active");
        $(LotterySpin.htmlId.contentRoot, ".roll-" + before).removeClass("active");
		
        LotterySpin.upSpeed();
        LotterySpin.downSpeed();
        LotterySpin.vars.index += 1;
        LotterySpin.vars.index = LotterySpin.vars.index == 11 ? 1 : LotterySpin.vars.index;
    },
	
	upSpeed : function () {
        if (LotterySpin.vars.cycle < 2 && LotterySpin.vars.speed > 100) {
            LotterySpin.vars.speed -= LotterySpin.vars.index * 10;
            LotterySpin.stop();
            LotterySpin.start();
        }
    },
	
	downSpeed:function () {
        if (LotterySpin.vars.index == 10) {
            LotterySpin.vars.cycle += 1;
        }
        if (LotterySpin.vars.cycle > LotterySpin.vars.times - 1 && LotterySpin.vars.speed < 400) {
            LotterySpin.vars.speed += 20;
            LotterySpin.stop();
            LotterySpin.start();
        }
        if (LotterySpin.vars.cycle > LotterySpin.vars.times && LotterySpin.vars.index == LotterySpin.vars.prize) {
            LotterySpin.stop();
            LotterySpin.showPrize();
        }
    },
	
	showPrize:function(){
        setTimeout(function(){
			
      	    SnapeClass.openModal('Phần thưởng', "Bạn nhận được <font color='red'><b>\"" + LotterySpin.vars.dataItems[LotterySpin.vars.prize].Name + "x" + LotterySpin.vars.dataItems[LotterySpin.vars.prize].Count +"\"</b></font>", []);
			
			LotterySpin.vars.isRun = false;
			
			$(LotterySpin.htmlId.contentRoot, "#start_spin").removeClass('btn_lotteryspin_disable').addClass("btn_lotteryspin_effect");
			
        },700);
    },
	
	start : function () {
        LotterySpin.vars.roll = setInterval(LotterySpin.run, LotterySpin.vars.speed);
    },
 
    stop : function () {
        clearInterval(LotterySpin.vars.roll);
    }
}

$(document).ready(function() {
 	$(LotterySpin.htmlId.contentRoot, "#start_spin").bind('click', LotterySpin.reset);
});

