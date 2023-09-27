/*
    æ–°å¼¹å¼¹å ‚ è¿ç§» wanå¹³å° å…¬å…±è„šæœ¬
*/
var DDT_COM = DDT_COM || {};
var D2_Util = {};
DDT_COM.gameName = "ddt";
DDT_COM.ddtServerListMap = {};
var DDT_ACTION = {
	gid : "26",
    checkLogin:"/Index/isLogin",
	checkLogin2:"https://7.wan.com/accounts/isLogin.html",
	login2 : "https://7.wan.com/accounts/checklogin.html",
    loginIn:"/Public/Checklogin1",
    gameurl:"https://7.wan.com/game/play?sid=",
    loginOut:"/Public/loginout",
    checkUserName:"https://7.wan.com/platform/checkUserName.action",
    checkVerifyCode:"https://7.wan.com/platform/checkVerifyCode.action",
    CheckLoginGame:"https://7.wan.com/platform/onlineGame/loginGame.action"
};

/*---------------------------------------------------------------------------------------------
    ç”¨æˆ· ç™»å½•ï¼Œé€€å‡º ç›¸å…³æ“ä½œ
---------------------------------------------------------------------------------------------*/
DDT_COM.userLoginOperate = {

     //åˆ¤æ–­æ˜¯å¦ç™»å½•
    checkLogin : function(){
		
		$.getJSON(DDT_ACTION.checkLogin2+"?jsonpCallback=?",function(data){
			data = typeof data == "string" ? eval("("+data+")") : data;
			if(data.state == "1"){

                DDT_COM.loginBox.logged(data);
            }else{
                DDT_COM.loginBox.loginOut(data);
            }
		});
		
		/*
        $.post(DDT_ACTION.checkLogin,{},function(data){
            data = typeof data == "string" ? eval("("+data+")") : data;
            if(data.state == "1"){

                DDT_COM.loginBox.logged(data);
            }else{
                DDT_COM.loginBox.loginOut(data);
            }
        })*/
    },
    //ç”¨æˆ· ç™»å½•
    login : function( inName, password1,remember,successCallback, errorCallback){
		/*
		$.getJSON(DDT_ACTION.login2+"?cn="+inName+"&pwd="+password1+"&callBack=?",function(){
			data = typeof data == "string" ? eval("("+data+")") : data;
			if(data.state == "1"){
                $("body").append(data.script);
                setTimeout(function(){
                    window.location.reload();
                },500)
            }else{
                alert(data.msg);
            }
		});*/
		
		
		
		password1 = encodeURIComponent(password1);
        $.getJSON(DDT_ACTION.login2+"?cn="+inName+"&pwd="+password1+"&jsonpCallback=?",function(data){
            if(data.state == "1"){
                $("body").append(data.script);
                setTimeout(function(){
                    window.location.reload();
                },500)
            }else{
                alert(data.msg);
            }
        });
    },

    //ç”¨æˆ· é€€å‡º
    loginOut : function( successCallback, errorCallback ){
        $.post(DDT_ACTION.loginOut,{},function(data){
            data = typeof data == "string" ? eval("("+data+")") : data;
            if(data.state == "1"){
                $('body').append(data.script);
                setTimeout(function(){
                    window.location.reload();
                },500);
            }
        });

    },
    //å–æœåŠ¡å™¨åˆ—è¡¨
    getServerList : function(){
        $.post(DDT_ACTION.getNewServer,{},function(data){   //æ— å‚æ•°è¡¨ç¤ºèŽ·å–å…¨éƒ¨ï¼Œc:1è¡¨ç¤ºå–ä¸€æ¡
            $.each(data,function(i,n){
                if(data[i].unstart == 0){
                    if($(".first-val").length > 0){     //å¡«å……æŽ’è¡Œæ¦œæœåŠ¡å™¨åˆ—è¡¨
                        $(".f-first-ul").append("<li servernum="+data[i].sid+" gameid="+data[i].gid+">"+data[i].servername+"</li>");
                    }
                    DDT_COM.ddtServerListMap[data[i].line] = data[i].url;
                }
            });
            var li = $(".f-first-ul li:first");
            $(".first-val").text(li.text()).attr({"servernum":li.attr("servernum"),"gameid":li.attr("gameid")});
            D2_Util.loadRankHtml($(".first-val").attr("servernum"),$(".second-val").text());
        });
    }
};

//å…¬å…±å·¦ä¸Šè§’ç™»å½•æ¡†çš„ç›¸å…³æ“ä½œ
DDT_COM.loginBox = {
    
    //ç™»å½•äº†ï¼Œæ”¹å˜çŠ¶æ€
    logged : function( data ){
        var $userLogin = $(".unlogin-box");
        var $userLogged = $(".login-box");
        
        var $username = $("#user");
        var $pwd = $("#userPass");
        
        var $dataUser = $(".userVal");
        
        var $latestSeverLink = $(".lastLogin");

        $dataUser.html(data.datas.nickname);
        var latestSeverHtml= "";
        if( typeof data.datas.recently == "object"){
			$.each(data.datas.recently,function(i,n){
				if(n.gid == DDT_ACTION.gid){
					latestSeverHtml = n.servername;
					$latestSeverLink.attr({"href":DDT_ACTION.gameurl+n.sid}).html(latestSeverHtml);
					if($(".sub-my-ser").length>0){ // é€‰æœé¡µï¼Œæˆ‘çš„æœåŠ¡å™¨
						$(".my-box").html('<a href="'+DDT_ACTION.gameurl+n.sid+'" target="_blank">'+latestSeverHtml+'</a>');
					}
					return false;
				}
			});
		}else{
            $latestSeverLink.attr({"href":"javascript:void(0)"}).html(latestSeverHtml);
            if($(".sub-my-ser").length>0){ // é€‰æœé¡µï¼Œæˆ‘çš„æœåŠ¡å™¨
                $(".my-box").html(latestSeverHtml);
            }
        }



        
        //æ¸…æŽ‰å¯†ç 
        $pwd.val("");

        $userLogin.hide();
        $userLogged.show();
    },
    
    //é€€å‡ºäº†ï¼Œæ”¹å˜çŠ¶æ€
    loginOut : function(data){
        var $userLogin = $(".unlogin-box");
        var $userLogged = $(".login-box");
        this.inputState();
        $userLogin.show();
        $userLogged.hide();
    },
    
    //ä¸¤ä¸ªè¾“å…¥æ¡†çš„çŠ¶æ€
    inputState : function(){
        var $loginUsername = $("#user");
        var $loginPassword = $("#userPass");
        var $loginUsernameLabel = $loginUsername.prev();
        var $loginPasswordLabel = $loginPassword.prev();
        
        //ç»‘å®š å¸å·ï¼Œå¯†ç inputæ¡†äº‹ä»¶
		/*
        if( WAN_COM.formCheck.isEmpty( $loginUsername.val() ) ){
            $loginUsernameLabel.show();
            $loginPasswordLabel.show();
        }*/
    },
    
    //åˆå§‹åŒ–
    init : function(){
        DDT_COM.userLoginOperate.checkLogin(function(data){
            DDT_COM.loginBox.logged(data);
        }, function(data){
            DDT_COM.loginBox.loginOut(data);
        });
       //DDT_COM.userLoginOperate.getServerList();
    }//end init

};

//æ ¹æ®è¿”å›žå€¼ï¼Œå¾—åˆ°æœ€è¿‘è¿›æ¸¸æˆçš„æ¸¸æˆåœ°å€
DDT_COM.getGameUrl = function(serverId){

    // å¹³å°æ‰€æœ‰è¿›æ¸¸æˆï¼Œéƒ½æ˜¯è¿™ç§å†™æ³•ã€‚
    return DDT_ACTION.gameurl+"?serverid="+serverId;

};

// ç”Ÿæˆå¿«é€Ÿé€‰æœæ˜ å°„å¯¹è±¡
DDT_COM.numberServerMap = {
  "7road":{},
  "37ww":{}
};



/*---------------------------------------------------------------------------------------------
    å·¦ä¾§ æœåŠ¡å™¨åˆ—è¡¨ åˆå§‹åŒ–
---------------------------------------------------------------------------------------------*/
DDT_COM.sideServerListInit = function(){

  
    var $serverListWrap = $(".recom-box ul");

};

/*---------------------------------------------------------------------------------------------
    å¿«é€Ÿé€‰æœ
---------------------------------------------------------------------------------------------*/
DDT_COM.quickSelectServer = function( $input,platName ){
    var v = $.trim($input.val());

    if( !/^\d+$/.test( v ) ){
        alert("number required");
        return false;
    }else if(!DDT_COM.ddtServerListMap[v]){
        alert("server not exists!");
        return false;
    }
    //è¿›æ¸¸æˆ
    window.open(DDT_COM.ddtServerListMap[v]);


   /* var maxVal = 0;
    if($('.recom-box').length>0){
      maxVal = Number($('.recom-box li:first').children('a').text().match(/\d+/)[0]);
    }else{
      maxVal =  Number($(".recommend-box").children('a').text().match(/\d+/)[0]);
    }
   
    if( !/^\d+$/.test( v ) ){
        alert("è¯·è¾“å…¥æ•°å­—ï¼Œè¿›è¡Œé€‰æœ");
        return false;
    }else if(Number(v) > maxVal){
        alert("æ‚¨è¾“å…¥çš„åŒºæœä¸å­˜åœ¨!");
        return false;
    }*/
    /*v = DDT_COM.numberServerMap[platName][v];
    var gameurl = DDT_COM.getGameUrl(v);*/
    

};



//åˆå§‹åŒ–
$(function(){
  DDT_COM.loginBox.init();
  //DDT_COM.sideServerListInit();
});



// æ·»åŠ åˆ†å‰²æ•°ç»„å‡½æ•°
DDT_COM.sliceArray = function(arr,size){
  var arrNum = Math.ceil(arr.length/size);
  var newArr = [];
  for(var i=0;i<arrNum;i++){
    newArr.push(arr.slice(size*i,size*(i+1)));
  };
  return newArr;
}

// è‡ªåŠ¨ç”Ÿæˆé€‰é¡¹å¡tabåˆ‡æ¢èœå•(1-50æœï¼Œ51-100æœ..) (50,123,"span")
DDT_COM.autoCreateMenuObj = function (count,tagName){

  var num = Number(DDT_COM.sliceTabVal);
  count = Number(count);
  var y = Math.ceil(count/num);
  var z = [];
  for(var i =0; i<y;i++){
    if(i==y-1){
      z.push("<"+tagName+" class='select'>S"+(num*i+1)+"-"+num*(i+1)+"</"+tagName+">");
    }else{
      z.push("<"+tagName+">"+(num*i+1)+"-S"+num*(i+1)+"</"+tagName+">");
    }
    
  };
  return z.reverse().join('');
};





// è®¾ç½®é€‰æœé¡µé€‰é¡¹å¡tabèœå•åˆ†å‰²å€¼
DDT_COM.sliceTabVal = 10;


$(document).ready(function () {
	$(".btn-login").on("click",function(){
		var userval = $("#user").val(),
			userpas = $("#userPass").val();
		if( !userval || !userpas){
			alert("Input your account & pass to login");
			return false;
		}
		DDT_COM.userLoginOperate.login(userval,userpas,0);
	});
});










