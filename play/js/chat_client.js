/**
 * Create by GunHT.Com
 * http://gunht.com
 */
$(document).ready(function() {
	
	var toggle = false;
	
	var user = "";
	
	var searchBoxText= "Nhập ở đây...";
	
	var fixIntv;
	
	var fixedBoxsize = $('#chat_fixed').outerHeight()+'px';
	
	var Parent = $("#chat_fixed"); // cache parent div
	
	var Header = $(".chat_fixedHeader"); // cache header div
	
	var Chatbox = $(".chat_userinput"); // cache header div
	
	var config = null;
	
	var ws = null;
	
	Chatbox.val(searchBoxText);
	
	Parent.css('height', '30px');

	Header.click(function(){           
		toggle = (!toggle) ? true : false;
		if(toggle)
		{
			Parent.animate({'height' : fixedBoxsize}, 300);                    
		}
		else
		{
			Parent.animate({'height' : '30px'}, 300); 
		}
		
	});
	
	function addChatLog(username, type, system, txt) {
		
		if(system) {
			
			if(type == null || type == '')
				type = 'chat_syserr';
			
			$('.chat_fixedContent').prepend("<div class='chat_userwrap'><span class='"+type+"'>" + txt + "</span></div>");
		} else {
			$('.chat_fixedContent').prepend("<div class='chat_userwrap'><span class='chat_user "+type+"'>"+username+"</span><span class='chat_messages'>"+txt+"</span></div>");
		}
	}
	
	function connectServer(user, pass) {
		
		ws = new WebSocketConnection(
            config.host, config.port, config.resource, config.secure,
            config.protocols
        );
		
		//addChatLog('', '', true, 'Đang kết nối tới máy chủ tán gẫu.');
		
		try {
			
			ws.open();
			
			ws.socket.onopen = function() {
				
				var stringLogin = '{ "username": "' + user + '", "password": "' + pass + '" }';
				
				ws.send(stringLogin);
				
            }
			
			ws.socket.onmessage = function(msg) {
				
				var json = $.parseJSON(msg.data);
				
				addChatLog(json.username, json.type, json.system, json.text);
				
            }
			
			ws.socket.onclose = function() {
                
				addChatLog('', '', true, 'Mất kết nối tới máy chủ tán gẫu.');
				
            }
			
		} catch (ex) {
			
            alert('Exception: ' + ex);
			
        }
	}
	
	function handleSend() {
		
		if (ws == null || ws.isClosed()) {
            addChatLog('', '', true, 'Không thể kết nối tới máy chủ tán gẫu.');
			return;
        }
		
		if(Chatbox.val().length < 5 || Chatbox.val().length > 200) {
			
			addChatLog('', '', true, 'Nội dung ít nhất 5 ký tự và nhiều nhất 200 ký tự');
			
		} else {
			
			try {
				
                ws.send(Chatbox.val());
				
                Chatbox.val('');
				
            } catch (ex) {
				
				addChatLog('', '', true, 'Lỗi gửi dữ liệu tới máy chủ tán gẫu.');
				
            }
			
		}
	}
	
	$.ajax({
        url: 'configchat.php',
        async: false,
        dataType: 'json',
        success: function(response) {
            config = response;
        },
        error: function(response) {
            addChatLog('', '', true, 'Không thể đọc dữ liệu kết nối.');
        }
    });
	
	if (!('WebSocket' in window)) {
		addChatLog('', '', true, 'Trình duyệt không hỗ trợ giao thức yêu cầu.');
	} else {
		connectServer(ssUsername, ssPassword);
	}
	
	Chatbox.focus(function() {
		$(this).val(($(this).val()==searchBoxText)? '' : $(this).val());
	}).blur(function(){
		$(this).val(($(this).val()=='')? searchBoxText : $(this).val());
	}).keyup(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);       
		if(code==13) {
			handleSend();
		}
		
	});
});
