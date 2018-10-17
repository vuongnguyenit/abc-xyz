$(function () {
  /*$('.rating').each(function () {
	$('<span class="label label-default"></span>')
	  .text($(this).val() || ' ')
	  .insertAfter(this);
  });*/
  $('.rating').on('change', function () {
	var a = $(this).val();  
	var b = ITEM_ID;  
	$(this).attr('disabled','disabled').next('.label').text(a);
	$.ajax({
	  url: "/xu-ly-du-lieu",
	  type: "post",
	  data: {
		  'action': 'cmsrating',
		  'cms': b,
		  'point': a,
		  'rand': Math.random()
	  },
	  dataType: "json",
	  success: function(data, status) {
	    return false;
	  }
	});
  });
});