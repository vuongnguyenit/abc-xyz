jQuery(document).ready(function() {	
	$( ".spec tr:odd" ).css( "background-color", "none" );
	$('.spec tr').hover(function(e) {
		$('.spec tr').removeClass('highlighted');
		$(this).addClass('highlighted');
	});	
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');
	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});
	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
	$('#left .categories .item:has(span.pro-count)').addClass('show');
	$('#pro_detail .beefup').beefup();
	var $beefup = $('#left .categories').beefup();
	$beefup.open($('#left .categories'));
	//$('.menu-top-slicknav').slicknav();
});
//Remove Cookies
/*function removeCookies() {
	var res = document.cookie;
    var multiple = res.split(";");
    for(var i = 0; i < multiple.length; i++) {
    	var key = multiple[i].split("=");
        document.cookie = key[0]+" =; expires = Thu, 01 Jan 1970 00:00:00 UTC";
    }
}*/

