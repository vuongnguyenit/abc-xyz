/*GalleryView*/
$(document).ready(function(){
  $('#gallergyView').galleryView({
	  panel_width: 580,
	  panel_height: 300,
	  frame_width: 100,
	  frame_height: 50,
	  easing: 'easeInOutQuad',
  });
});

/*Tooltip*/
$(function(){
  $(".picture_thumbnail").tooltip({
	  bodyHandler: function(){
		  $("#tooltip").css("width", "auto");
		  return this.rel;
	  },
	  track: true,
	  showURL: false,
	  extraClass: ""
  
  });
});	