$(document).ready(function() {
            $("ul.photos li").hover(function() { 
		var imageOver = $(this).find("img").attr("src");
		$(this).find("a.image").css({'background' : 'url(' + imageOver + ') no-repeat center bottom'});
		$(this).find("span").stop().fadeTo('normal', 0 , function() {
			$(this).hide()
		});
	} , function() { 
		$(this).find("span").stop().fadeTo('normal', 1).show();
	});
});

$(function(){  
    $("#slider").css({height:"auto" , overflow:"visible"}); // just reset it again :)  
    $("#show").JWslider();  
});