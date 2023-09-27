/**
 * Create by GunHT.Com
 * http://gunht.com
 */
$(document).ready(function() {
	
	var toggle = false;
	
	var fixedBoxsize = $('#chat_fixed').outerHeight()+'px';
	
	var Parent = $("#chat_fixed"); // cache parent div
	
	var Header = $(".chat_fixedHeader"); // cache header div
	
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
	
});
