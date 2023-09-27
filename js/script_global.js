/*
|| Script code by Snape Nguyen
|| -----------------------------------
|| PLEASE DON'T REMOVE IT IF YOU USE. THANKS YOU.
*/

$(document).ready(function() {
	$('.carousel').carousel({
		interval: 5000 //changes the speed
	});
	//show_big_loading();
    $('[data-toggle="tooltip"]').tooltip();
});

jQuery.validator.setDefaults({
    highlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
			$(element).closest('.form-group').attr("aria-describedby","inputError2Status");
            $(element).closest('.form-group').find('span.glyphicon').remove();
            $(element).closest('.form-group').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
			$(element).closest('.form-group').attr("aria-describedby","inputSuccess2Status");
            $(element).closest('.form-group').find('span.glyphicon').remove();
            $(element).closest('.form-group').append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
        }
    }
});

var options = {};
options.ui = {
    container: "#pwd-container",
    verdicts: [
        "<span class='fa fa-exclamation-triangle'></span> Yếu",
        "<span class='fa fa-exclamation-triangle'></span> Bình thường",
        "<span class='fa fa-exclamation'></span> Tạm được",
        "<span class='fa fa-thumbs-up'></span> An toàn",
        "<span class='fa fa-thumbs-up'></span> Rất an toàn"
    ],
    showVerdictsInsideProgressBar: true,
    viewports: {
        progress: ".pwstrength_viewport_progress"
    }
};

var PopupClass = {
    popup_window: null,
    interval: null,
    interval_time: 80,
    waitForPopupClose: function() {
        if (PopupClass.isPopupClosed()) {
            PopupClass.destroyPopup();
            //window.location.reload();
        }
    },
    destroyPopup: function() {
        this.popup_window = null;
        window.clearInterval(this.interval);
        this.interval = null;
    },
    isPopupClosed: function() {
        return (!this.popup_window || this.popup_window.closed);
    },
    open: function(url, width, height) {
        this.popup_window = window.open(url, "", this.getWindowParams(width, height));
        return this.popup_window;
    },
    getWindowParams: function(width, height) {
        var center = this.getCenterCoords(width, height);
        return "width=" + width + ",height=" + height + ",status=1,location=0,resizable=no,left=" + center.x + ",top=" + center.y;
    },
    getCenterCoords: function(width, height) {
        var parentPos = this.getParentCoords();
        var parentSize = this.getWindowInnerSize();
        var xPos = parentPos.width + Math.max(0, Math.floor((parentSize.width - width) / 2));
        var yPos = parentPos.height + Math.max(0, Math.floor((parentSize.height - height) / 2));
        return {
            x: xPos,
            y: yPos
        };
    },
    getWindowInnerSize: function() {
        var w = 0;
        var h = 0;
        if ('innerWidth' in window) {
            /* For non-IE */
            w = window.innerWidth;
            h = window.innerHeight;
        } else {
            /* For IE*/
            var elem = null;
            if (('BackCompat' === window.document.compMOMOode) && ('body' in window.document)) {
                elem = window.document.body;
            } else if ('documentElement' in window.document) {
                elem = window.document.documentElement;
            }
            if (elem !== null) {
                w = elem.offsetWidth;
                h = elem.offsetHeight;
            }
        }
        return {
            width: w,
            height: h
        };
    },
    getParentCoords: function() {
        var w = 0;
        var h = 0;
        if ('screenLeft' in window) {
            /* IE-compatible variants*/
            w = window.screenLeft;
            h = window.screenTop;
        } else if ('screenX' in window) {
            /* Firefox-compatible*/
            w = window.screenX;
            h = window.screenY;
        }
        return {
            width: w,
            height: h
        };
    }
};

var lastviewid = 0;
$(function () {
    var hideDelay = 500;
	var currentID;
	var hideTimer = null;

	// One instance that's reused to show info for the current person
	var container = $('<div id="personPopupContainer">'
      + '<table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="personPopupPopup">'
      + '<tr>'
      + '   <td class="corner topLeft"></td>'
      + '   <td class="top"></td>'
      + '   <td class="corner topRight"></td>'
      + '</tr>'
      + '<tr>'
      + '   <td class="left">&nbsp;</td>'
      + '   <td><div id="personPopupContent"></div></td>'
      + '   <td class="right">&nbsp;</td>'
      + '</tr>'
      + '<tr>'
      + '   <td class="corner bottomLeft">&nbsp;</td>'
      + '   <td class="bottom">&nbsp;</td>'
      + '   <td class="corner bottomRight"></td>'
      + '</tr>'
      + '</table>'
      + '</div>');

	$('body').append(container);

	$(document).on('mouseover', '.personPopupTrigger', function () {
		// format of 'rel' tag: pageid,personguid
		var settings = $(this).attr('alt').split(',');
		var pageID = settings[0];
		currentID = settings[1];
		// If no guid in url rel tag, don't popup blank
		if (currentID == '')
			return;

		if (hideTimer)
			clearTimeout(hideTimer);

		var pos = $(this).offset();
                //var width = $(this).width();
        container.css({
			left: (pos.left + 70) + 'px',
            top: (pos.top + 40) + 'px'
        });
		
		if(lastviewid != pageID) {
			
			$('#personPopupContent').html('<img src="'+full_url+'/images/loading.gif"/>');

			$.ajax({
				type: 'GET',
				url: full_url + '/ajax/readiteminfo.php',
				data: 'id=' + pageID + '&key=' + currentID,
				success: function (data) {
						
					$('#personPopupContent').html(data);

				}
			});
			
		}
		lastviewid = pageID;
		
        container.css('display', 'block');
    });
           
    $(document).on('mouseout', '.personPopupTrigger', function () {
        if (hideTimer)
			clearTimeout(hideTimer);
        hideTimer = setTimeout(function () {
            container.css('display', 'none');
        }, hideDelay);
    });

    // Allow mouse over of details without hiding details
    $('#personPopupContainer').mouseover(function () {
        if (hideTimer)
            clearTimeout(hideTimer);
    });

    // Hide after mouseout
    $('#personPopupContainer').mouseout(function () {
		
        if (hideTimer)
            clearTimeout(hideTimer);
		
        hideTimer = setTimeout(function () {
           container.css('display', 'none');
        }, hideDelay);
    });
});


var SnapeClass = {

    encryptCurrentURL: "",
	
	urlLoadSupport : full_url + "/ajax/loadsupport.php",
	
	urlReplySupport : full_url + "/ajax/replysupport.php",
	
	urlUpdateCoinGame : full_url + "/form/changecg.php",
	
	urlLoadPlayersList : full_url + "/ajax/getlistplayers.php",

    // var
    show_big_loading: function() {
        var randomLoad = Math.floor((Math.random() * max_loading_gif_count));
        $("#image_loading_big").attr("src", full_url + "/images/big_loading_" + randomLoad + ".gif");
        $("#div_loading").fadeIn(500);
    },

    hide_big_loading: function() {
        $("#div_loading").fadeOut(200);
    },

    openSocialConnect: function(name, type) {
        if (typeof(type) == "undefined")
            type = "login";

        switch(name) {
            case "facebook":
                PopupClass.open(full_url + "/social/facebook/?action=" + type + "&return=" + SnapeClass.encryptCurrentURL, 800, 550);
                break;
            case "yahoo":
                PopupClass.open(full_url + "/social/yahoo/?action=" + type + "&return=" + SnapeClass.encryptCurrentURL, 800, 550);
                break;
            case "google":
                PopupClass.open(full_url + "/social/google/?action=" + type + "&return=" + SnapeClass.encryptCurrentURL, 800, 550);
                break;
        }
    },

    openModal: function(title, content, buttons) {
        $('#modalSnape').on('show.bs.modal', function (event) {
            var modal = $(this);
            modal.find('.modal-title').html(title);
            modal.find('.modal-body').html(content);
            var stringButton = '';

            for (var i = 0; i < buttons.length; i++) {
                var addLink ='';
                if(buttons[i].Link) {
                    addLink = 'onclick="window.location=\''+buttons[i].Link+'\'"';
                }
                stringButton += '<button type="button" class="btn btn-primary" '+addLink+'>'+buttons[i].Name+'</button>';
            }

            stringButton += '<button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button>';
            $('.modal-footer').html(stringButton);
        });
        $('#modalSnape').modal('show');
    },

    openConfirm: function(title, content, buttons) {
		
        $('#modalSnape').on('show.bs.modal', function (event) {
            var modal = $(this);
            modal.find('.modal-title').html(title);
            modal.find('.modal-body').html(content);
            var stringButton = '';

            for (var i = 0; i < buttons.length; i++) {
                var addLink ='';
                if(buttons[i].Action) {
                    addLink = 'onclick="'+buttons[i].Action+'"';
                }
                stringButton += '<button type="button" class="btn btn-primary" '+addLink+' data-dismiss="modal">'+buttons[i].Name+'</button>';
            }

            stringButton += '<button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>';
            $('.modal-footer').html(stringButton);
        });
		
        $('#modalSnape').modal('show');
		
    },
	
	loadPlayersList: function(serverid, fieldLoad) {
		
		if($.isNumeric(serverid) && serverid > 0) {
			
			$(fieldLoad).empty();
			
			$.post(SnapeClass.urlLoadPlayersList, "serverid=" + serverid, function(data) {
				if(data['type'] == 0) {
					
					for(var i in data['items']) {
						
						$(fieldLoad).append(new Option(data['items'][i]['NickName'], data['items'][i]['UserID']));
						
					}
					
				} else {
					SnapeClass.openConfirm('Lỗi cập nhật', data['content'], []); 
					$(fieldload).html(oldResult);
				}
			}, 'json');
		}
	},
	
	readSupport: function(id) {
		SnapeClass.show_big_loading();
		$.post(SnapeClass.urlLoadSupport, {"id" : id}, function(data) {
			SnapeClass.hide_big_loading();
			// load it
			if(data['type'] == 0) {
				var shtml = '<div id="chat_support_box" class="panel-body box_chat_scroll">';
				shtml += '<ul class="chat">';
				
				for(var i = 0; i < data['result'].length; i++) {
					var typeChat = data['result'][i].IsStaff;
					shtml += '<li class="'+((typeChat == true) ? 'right' : 'left')+' clearfix">';
					shtml += '<span class="chat-img pull-'+((typeChat == true) ? 'right' : 'left')+'"><img src="'+data['result'][i].Picture+'" alt="'+data['result'][i].Name+'" class="img-circle"></span>';
					shtml += '<div class="chat-body clearfix"><div class="header"><strong class="'+((typeChat == true) ? 'pull-right' : '')+' primary-font">'+data['result'][i].Name+'</strong><small class="pull-'+((typeChat == true) ? '' : 'right')+' text-muted"><i class="fa fa-clock-o fa-fw"></i> '+data['result'][i].Time+'</small></div><p>'+data['result'][i].Content+'</p></div>';
					shtml += '</li>';
				}
				
				shtml += '</ul>';
				shtml += '</div>';
				
				shtml += '<div class="panel-footer"><div class="input-group"><input id="txtReplySupport" type="text" class="form-control input-sm" placeholder="'+data['TypeSupportText']+'" '+((data['TypeSupport'] == 1) ? '' : 'readonly')+'><span class="input-group-btn"><button class="btn btn-warning btn-sm" id="btn-reply-support" onclick="SnapeClass.replySupport('+id+')" '+((data['TypeSupport'] == 1) ? '' : 'disabled')+'>Hồi âm</button></span></div></div>';
				
				SnapeClass.openModal(data['title'], shtml, []);
				
			} else {
				SnapeClass.openModal("Lỗi lấy dữ liệu", data['content'], []);
			}
		}, 'json');
	},
	
	replySupport : function(id) {
		var textContent = $("#txtReplySupport").val();
		if(textContent.length < 10 || textContent.length > 5000) {
			alert("Nội dung hồi âm hỗ trợ ít nhất là 10 ký tự");
			return;
		}
		$("#btn-reply-support").attr("disabled", "disabled");
		$.post(SnapeClass.urlReplySupport, {"id" : id, "text" : textContent}, function(data) {
			if(data['type'] == 0) {
				// add text
				var typeChat = data['result'].IsStaff;
				var shtml = '<li class="'+((typeChat == true) ? 'right' : 'left')+' clearfix">';
				shtml += '<span class="chat-img pull-'+((typeChat == true) ? 'right' : 'left')+'"><img src="'+data['result'].Picture+'" alt="'+data['result'].Name+'" class="img-circle"></span>';
				shtml += '<div class="chat-body clearfix"><div class="header"><strong class="'+((typeChat == true) ? 'pull-right' : '')+' primary-font">'+data['result'].Name+'</strong><small class="pull-'+((typeChat == true) ? '' : 'right')+' text-muted"><i class="fa fa-clock-o fa-fw"></i> '+data['result'].Time+'</small></div><p>'+data['result'].Content+'</p></div>';
				shtml += '</li>';
				$("ul.chat").append(shtml).fadeIn('slow');
			} else {
				alert(data['content']);
				$("#btn-reply-support").removeAttr('disabled');
			}
		}, 'json');
	},

    resetCaptcha: function() {
        if (typeof grecaptcha != "undefined") {
            grecaptcha.reset();
        } else {
            alert('no captcha avalible!');
        }
    },
	
	resetNewCaptcha: function(field) {
		document.getElementById(field) ? document.getElementById(field).src=full_url+'/captcha.php?'+Math.random() : null;
	},

    disableSubmit: function() {
        $('input[type="submit"]').attr("disabled", "disabled");
    },

    undisableSubmit: function() {
        $('input[type="submit"]').removeAttr('disabled');
    },

    clearAllInput: function() {
        $('input[type="text"], input[type="password"]').val('');
    },

    logout: function() {
        $.post(full_url + "/ajax/logout.php", function(data) {
            window.location = full_url + "/dang-nhap/";
        });
    },

    setConnectedSocial: function(name) {
        switch(name) {
            case 'facebook':
            $("#connect_yahoo_status").html('<span class="connected_social">Đã kết nối</span>');
            break;

            case 'yahoo':
            $("#connect_yahoo_status").html('<span class="connected_social">Đã kết nối</span>');
            break;

            case 'google':
            $("#connect_yahoo_status").html('<span class="connected_social">Đã kết nối</span>');
            break;
        }
    },
	
	updateCoingame: function(serverid, fieldload) {
		
		var oldResult = $(fieldload).html();
		
		$(fieldload).html("<img src='"+full_url+"/images/loading.gif' />");
		
		$.post(SnapeClass.urlUpdateCoinGame, "txtServer=" + serverid + "&quickchange=true", function(data) {
			if(data['type'] == 0) {
				SnapeClass.loadCoingame(serverid, fieldload);
			} else {
				SnapeClass.openConfirm('Lỗi cập nhật', data['content'], []); 
				$(fieldload).html(oldResult);
			}
		}, 'json');
	},
	
	loadCoingame: function(serverid, fieldload) {
		if(serverid != 0) {
			$(fieldload).html("<img src='"+full_url+"/images/loading.gif' />");
			$.post(full_url+"/ajax/loadcoingame.php?id=" + serverid, function(data) {
				$(fieldload).html(data);
			});
		}
	}
};

