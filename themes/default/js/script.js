var path_js = '/themes/default/js/';
var path_css = '/themes/default/css/';
var path_image = '/themes/default/images/';

function flashWrite(url,w,h,id,bg,vars,win){
    var flashStr="<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+"<param name='allowScriptAccess' value='always' />"+"<param name='movie' value='"+url+"' />"+"<param name='FlashVars' value='"+vars+"' />"+"<param name='wmode' value='"+win+"' />"+"<param name='menu' value='false' />"+"<param name='quality' value='high' />"+"<param name='bgcolor' value='"+bg+"' />"+"<embed src='"+url+"' FlashVars='"+vars+"' wmode='"+win+"' menu='false' quality='high' bgcolor='"+bg+"' width='"+w+"' height='"+h+"' name='"+id+"' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />"+"</object>";
    document.write(flashStr);
}

function openBox(fileSrc,winWidth,winHeight)
{
    var w=(screen.availWidth-winWidth)/2;
    var h=(screen.availHeight-winHeight)/2;
    newParameter="width="+winWidth+",height="+winHeight+",addressbar=no,scrollbars=yes,toolbar=no,top="+h+",left="+w+", resizable=no";
    newWindow=window.open(fileSrc,"a",newParameter);
    newWindow.focus();
}

function modelessDialogShow(url,width,height)
{
    var w=(screen.availWidth-width)/2;
    var h=(screen.availHeight-height)/2;
    return window.showModalDialog(url,window,'dialogWidth:'+width+'px; dialogHeight:'+height+'px; center:1; dialogLeft:'+w+'px; dialogTop:'+h+'px; help:off; resizable:on; status:off;');
}

function checkNumber(textBox) {
	while(textBox.value.length > 0 && isNaN(textBox.value)) {
		textBox.value = textBox.value.substring(0, textBox.value.length - 1)
	}
	textBox.value = trim(textBox.value);
}

function newWindow(mypage,myname,w,h,scrolla)
{
    var winl=(screen.availWidth-w)/2;
    var wint=(screen.availHeight-h)/2;
    winprops='height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scrolla+',resizable=1,toolbar=yes,statusbar=yes'
    win=window.open(mypage,myname,winprops);
}

function changeLang(returnURL,lang)
{
    var theExprire = 365;
	eraseCookie('lang')
	createCookie('lang', lang, theExprire);
    window.location = returnURL;
}


function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

$("span.agree").live('click', function() {
  $("input#agree").attr("checked","checked");
  $("input#register").removeAttr("disabled");
});

$("span.remember").live('click', function() {
  $("input#remember").attr("checked","checked");
});

function Signout(processPage)
{
  if(confirm("Xác nhận thoát khỏi chế độ thành viên?"))
  {
	$.get(processPage,"",function(data,status)
	{
	  if(data == "success")
	  {
		window.location="/";
		return;
	  }
	});
  }
  return false;
}

function getCookie(c_name)
{
  	var i,x,y,ARRcookies = document.cookie.split(";");
  	for (i = 0; i < ARRcookies.length; i++)
  	{
		x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g,"");
		if (x == c_name)
		{
			return unescape(y);
		}
  	}
}

var lang = getCookie('lang');
if(lang == '' || lang == null) lang = 'vi-VN';

var lng = new Array();
lng["choose_district_vi-VN"] = "Chọn Quận/Huyện";
lng["choose_district_en-US"] = "Please choose your district";
lng["choose_ward_vi-VN"] = "Chọn Phường/Xã";
lng["choose_ward_en-US"] = "Please choose your ward";

$("select.city").live('change', function() {
	var rst = "";
	var id = $(this).val();	
	var name = $(this).attr('name');
	var tmp = name.split("_");
	var prefix = (tmp.length==2) ? tmp[0] + '_' : '';
	var cls = (tmp.length==2) ? 'sel short' : 'select';
	$.ajax({
		url: '/xu-ly-du-lieu',
		type: 'post',
		data: {'action' : 'district', 'id' : id, 'time' : Math.random()},
		dataType: 'json',
		success: function(district){
			var data = '<select id="' + prefix + 'district" name="' + prefix + 'district" class="' + cls + ' district dropdown-select {validate:{required:true}} cus"><option value="">' + lng["choose_district_" + lang] + '</option>';
			$.each(district, function(i) {				
				rst += '<option value="' + district[i].id + '">' + district[i].name + '</option>';					
			});
			data += rst;
			data += '</select>';		
			$("span#" + prefix + "district").html(data);
			var data2 = '<select id="' + prefix + 'ward" name="' + prefix + 'ward" class="' + cls + ' dropdown-select {validate:{required:true}} cus"><option value="">' + lng["choose_ward_" + lang] + '</option></select>';
			$("span#" + prefix + "ward").html(data2);	
			if ($('input#chkSame').is(':checked')) {
				var prefix2 = (tmp[0] == 'billing' ? 'shipping' : 'billing');
				$("span#" + prefix2 + "_district").html(data.replace(prefix, prefix2 + '_').replace(prefix, prefix2 + '_'));
				$("span#" + prefix2 + "_ward").html(data2.replace(prefix, prefix2 + '_').replace(prefix, prefix2 + '_'));
			}
		}
	});		
	return false;
});

$("select.district").live('change', function() {
	var rst = "";
	var id = $(this).val();	
	var name = $(this).attr('name');
	var tmp = name.split("_");
	var prefix = (tmp.length==2) ? tmp[0] + '_' : '';
	var cls = (tmp.length==2) ? 'sel short' : 'select';
	$.ajax({
		url: '/xu-ly-du-lieu',
		type: 'post',
		data: {'action' : 'ward', 'id' : id, 'time' : Math.random()},
		dataType: 'json',
		success: function(ward){
			var data = '<select id="' + prefix + 'ward" name="' + prefix + 'ward" class="' + cls + ' dropdown-select {validate:{required:true}} cus"><option value="">' + lng["choose_ward_" + lang] + '</option>';
			$.each(ward, function(i) {				
				rst += '<option value="' + ward[i].id + '">' + ward[i].name + '</option>';					
			});
			data += rst;
			data += '</select>';		
			$("span#" + prefix + "ward").html(data);	
			if ($('input#chkSame').is(':checked')) {
				var prefix2 = (tmp[0] == 'billing' ? 'shipping' : 'billing');
				$("span#" + prefix2 + "_ward").html(data.replace(prefix, prefix2 + '_').replace(prefix, prefix2 + '_'));
			}
		}
	});		
	return false;
});

$(document).ready(function() {
	$('select#billing_city').live('change', function(){		
		var city = $(this).val();
		$('select#billing_city option').removeAttr('selected');
		$('select#billing_city option[value="'+ city +'"]').attr("selected","selected");		
	});
	
	$('select#shipping_city').live('change', function(){		
		var city = $(this).val();
		$('select#shipping_city option').removeAttr('selected');
		$('select#shipping_city option[value="'+ city +'"]').attr("selected","selected");		
	});
	
	$('select#billing_district').live('change', function(){		
		var district = $(this).val();
		$('select#billing_district option').removeAttr('selected');
		$('select#billing_district option[value="'+ district +'"]').attr("selected","selected");		
	});
	
	$('select#shipping_district').live('change', function(){		
		var district = $(this).val();
		$('select#shipping_district option').removeAttr('selected');
		$('select#shipping_district option[value="'+ district +'"]').attr("selected","selected");		
	});
	
	$('select#billing_ward').live('change', function(){		
		var ward = $(this).val();
		$('select#billing_ward option').removeAttr('selected');
		$('select#billing_ward option[value="'+ ward +'"]').attr("selected","selected");		
	});
	
	$('select#shipping_ward').live('change', function(){		
		var ward = $(this).val();
		$('select#shipping_ward option').removeAttr('selected');
		$('select#shipping_ward option[value="'+ ward +'"]').attr("selected","selected");		
	});
	
	$('input#chkNotSame').live('click', function(){
		var chk = $(this).attr('checked');
		if (chk == 'checked') {
			$("#shipping_name").val('');
			$("#shipping_phone").val('');
			$("#shipping_mobile").val('');
			$('select#shipping_city option').removeAttr('selected');
			$('select#shipping_city option[value="51"]').attr("selected","selected");
			$('select#shipping_district option').removeAttr('selected');
			$('select#shipping_district option[value="621"]').attr("selected","selected");
			$('select#shipping_ward option').removeAttr('selected');
			$('select#shipping_ward option[value="10072"]').attr("selected","selected");
			$("#shipping_address").val('');
			var rst = '';
			$.ajax({
				url: '/xu-ly-du-lieu',
				type: 'post',
				data: {'action' : 'district', 'id' : 51, 'time' : Math.random()},
				dataType: 'json',
				success: function(district) {
					var data = '<select id="shipping_district" name="shipping_district" class="dropdown-select sel short district {validate:{required:true}} cus"><option value="">' + lng["choose_district_" + lang] + '</option>';
					$.each(district, function(i) {				
						rst += '<option value="' + district[i].id + '">' + district[i].name + '</option>';					
					});
					data += rst;
					data += '</select>';		
					$("span#shipping_district").html(data);							
				}
			});				
			var data2 = '<select id="shipping_ward" name="shipping_ward" class="dropdown-select sel short {validate:{required:true}} cus"><option value="">' + lng["choose_ward_" + lang] + '</option></select>';
			$("span#shipping_ward").html(data2);
		} else {
			$("#shipping_name").val($("#billing_name").val());
			$("#shipping_phone").val($("#billing_phone").val());
			$("#shipping_mobile").val($("#billing_mobile").val());
			var city = $("select#shipping_city").val();			
			$('select#billing_city option').clone().appendTo('select#shipping_city');		
			$('select#shipping_district').html('<option value="' + $("#billing_district").val() + '" seleted>DISTRICT_NAME</option>');
			/*$('select#billing_district option').clone().appendTo('select#shipping_district');*/
			$('select#shipping_ward').html('<option value="' + $("#billing_ward").val() + '" seleted>WARD_NAME</option>');
			/*$('select#billing_ward option').clone().appendTo('select#shipping_ward');*/
			$("#shipping_address").val($("#billing_address").val());
		}
	});
	
	$('input#chkSame').live('click', function(){
		var chk = $(this).attr('checked');
		if(chk == 'checked')
		{
			$("#shipping_name").val($("#billing_name").val());
			$("#shipping_phone").val($("#billing_phone").val());
			$("#shipping_mobile").val($("#billing_mobile").val());
			var city = $("select#shipping_city").val();			
			$('select#billing_city option').clone().appendTo('select#shipping_city');		
			$('select#shipping_district').html('');
			$('select#billing_district option').clone().appendTo('select#shipping_district');
			$('select#shipping_ward').html('');
			$('select#billing_ward option').clone().appendTo('select#shipping_ward');
			$("#shipping_address").val($("#billing_address").val());
		} else
		{
			$("#shipping_name").val('');
			$("#shipping_phone").val('');
			$("#shipping_mobile").val('');
			$('select#shipping_city option').removeAttr('selected');
			$('select#shipping_city option[value="51"]').attr("selected","selected");
			$('select#shipping_district option').removeAttr('selected');
			$('select#shipping_district option[value="621"]').attr("selected","selected");
			$('select#shipping_ward option').removeAttr('selected');
			$('select#shipping_ward option[value="10072"]').attr("selected","selected");
			$("#shipping_address").val('');
			var rst = '';
			$.ajax({
				url: '/xu-ly-du-lieu',
				type: 'post',
				data: {'action' : 'district', 'id' : 51, 'time' : Math.random()},
				dataType: 'json',
				success: function(district) {
					var data = '<select id="shipping_district" name="shipping_district" class="dropdown-select sel short {validate:{required:true}} cus"><option value="">' + lng["choose_district_" + lang] + '</option>';
					$.each(district, function(i) {				
						rst += '<option value="' + district[i].id + '">' + district[i].name + '</option>';					
					});
					data += rst;
					data += '</select>';		
					$("span#shipping_district").html(data);							
				}
			});				
			var data2 = '<select id="shipping_ward" name="shipping_ward" class="dropdown-select sel short {validate:{required:true}} cus"><option value="">' + lng["choose_ward_" + lang] + '</option></select>';
			$("span#shipping_ward").html(data2);	
			//return false;
		}
	});
	
	$('input.cus').live('keyup', function(){	
		if($('input#chkSame').is(':checked'))
		{
			var id = $(this).attr('id');
			var tmp = id.split('_');
			var to_field = (tmp[0] == 'billing' ? 'shipping' : 'billing') + '_' + tmp[1];			
			$('#' + to_field).val($(this).val());
		}
	});
	
	$('select.cus').live('change', function(){	
		if($('input#chkSame').is(':checked'))
		{	
			var id = $(this).attr('id');
			var tmp = id.split('_');	
			var to_id = (tmp[0] == 'billing' ? 'shipping' : 'billing');
			var to_field =  to_id + '_' + tmp[1];
			if(tmp[1] == 'district') $('select#' + to_id + '_district').html('');
			$('select#' + to_field).html('');
			$('select#' + id + ' option').clone().appendTo('select#' + to_field);	
			if(tmp[1] == 'city')
			{	
				var from_field = tmp[0] + '_district';
				var to_id2 = (tmp[0] == 'billing' ? 'shipping' : 'billing');
				var to_field2 = to_id2 + '_district';				
				$('select#' + to_field2).html('');
				$('select#' + from_field + ' option').clone().appendTo('select#' + to_field2);				
					
			}
			
		}
	});
	
});

$("select#sorting").live('change', function() {  			
  var display = getCookie('PRODUCT_DISPLAY');
  var _html = '';
  var orderby = $(this).val();
  var catalog = $(this).parent().parent().attr('id');
  var c_position = $(this).offset();
  
  var did = $(this).parent().parent().parent().attr('id');
  did = did == 'head_toolbar' ? 'foot_toolbar' : 'head_toolbar';  
  $('#' + did + ' select#sorting option').removeAttr('selected');
  $('#' + did + ' select#sorting option[value="'+ orderby +'"]').attr('selected','selected');
  
  $.ajax({
	url: '/xu-ly-du-lieu',
	type: 'post',
	data: {'action' : 'sorting', 'orderby' : orderby, 'catalog' : catalog, 'time' : Math.random()},
	dataType: 'json',
	success: function(p) {				
	  if (display == 'PRODUCT_DISPLAY_GRID') {
		var j = 0;		  
		$.each(p, function(i) {          
		  _html +=
		  /*
		  '<div class="block col-md-3 col-sm-4 col-xs-6">' +
			'<div class="border">' +
			  (p[i].new == 1 ? '<div class="icon-new"></div>' : '') +
			  (p[i].brand.status == 1 ? '<div class="brand"><a href="' + p[i].brand.href + '" title="' + p[i].brand.title + '"><img alt="' + p[i].brand.alt + '" src="' + p[i].brand.src + '" height="15" title="' + p[i].brand.title + '" /></a></div>' : '') +
			  '<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="' + p[i].src + '" title="' + p[i].title + '" /></a></div>' +
			  '<div class="proname"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>' +
			  '<div class="g-price"><span class="list-price">' + p[i].price_txt + '</span>' + (p[i].list_price > p[i].price ? '<span class="price">' + p[i].list_price_txt + '</span>' : '') + '</div>' +
			'</div>' +
		  '</div>';
		  */		  
		  '<div class="block box col-lg-15 col-md-3 col-sm-4 col-xs-6 wow fadeIn animated" data-wow-delay="' + (j * 100) + 'ms">' +
			'<div class="border box-img"><span class="over-layer"></span>' +
			  (p[i].new == 1 ? '<div class="sale-box">New</div>' : '') +
			  (p[i].sale_off > 0 ? '<span class="label red">-' + p[i].sale_off + '%</span>' : '') +
			  '<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="' + p[i].src + '" title="' + p[i].title + '" /></a></div>' +
			  '<div class="proname"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>' +
			  '<div class="gr-price">' + (p[i].list_price > p[i].price ? '<span class="list-price"><span>' + p[i].list_price_txt + '</span></span>' : '') + '<span class="price"><span>' + p[i].price_txt + '</span></span></div>' +
			'</div>' +
			'<div class="box-content">' +
			  '<p class="description"> ' + p[i].name + ' </p>' +
			  '<ul class="links">' +
				'<li><a href="' + p[i].href + '" title="' + p[i].title + '"><i class="fa fa-link"></i></a></li>' +
				'<li><a rel="nofollow" id="' + p[i].id + '" href="javascript:;" class="btnAddtoCart" title="' + p[i].title + '"><i class="fa fa-cart-arrow-down"></i></a></li>' +
			  '</ul>' +
			'</div>' +
		  '</div>';
		  j++;
        });	  
	  } else {
		$.each(p, function(i) {          
		  _html += '<div class="block detail">';
          _html += '<div class="col-right">';
          _html += '<div class="price">';
          _html += p[i].price > 0 ? '<span class="price-new">' + p[i].price_txt + '</span>' : p[i].contact_txt;
          _html += p[i].discount > 0 ? '<br /><span class="price-old">' + p[i].list_price_txt + '</span>' : '';
          _html += '</div>';
          /*
		  if (p[i].price > 0 && p[i].state == 1) {
            _html += '<div class="order">';
            _html += '<input type="hidden" name="product_' + p[i].id + '" id="product_' + p[i].id + '" value="' + p[i].id + '" />';
            _html += '<input type="button" id="' + p[i].id + '" name="btnAddtoCart" value="' + p[i].button + '" title="' + p[i].title + '" />';
            _html += '</div>';
          }
		  */
          _html += '</div>';
          _html += '<div class="col-left">';
          _html += '<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="/thumb/500x500' + p[i].src + '" title="' + p[i].title + '" onerror="$(this).css({display:\'none\'})" /></a></div>';
          _html += '<div class="name"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>';
          _html += '<div class="description">' + p[i].desc + '</div>';
          _html += '</div>';
          _html += '</div>';		  
        });		  
	  }		
	  $('section.products div.content').html(_html);
	  var new_position = $('#header').offset();
	  if(c_position.top > 500)
  	  	$('html,body').animate({ scrollTop: new_position.top }, 1000);					
	}
  });		
  return false;
});

$('a.display').live('click', function() {  
  var display = $(this).attr('id');
  var _old = $('div#central div.products div.content').attr('id');
  var _new = display.replace('DISPLAY', 'PRODUCT');
  if (_new != _old) {  
	var _html = '';
	var catalog = $(this).parent().parent().attr('id');
	var c_position = $(this).offset();
	var _display = display.replace('PRODUCT_DISPLAY_', '').toLowerCase();
	var _mark = _display == 'list' ? 'grid' : 'list';  
	
	$('div.sorter .views').find('a').removeClass('current').removeClass('active_' + _mark);
	$('div.sorter .views').find('a.' + _display).addClass('current').addClass('active_' + _display);
	$('div#central div.products').find('div.content').removeClass().addClass('content ' + _display).removeAttr('id').attr('id', _new);
	$.ajax({
	  url: '/xu-ly-du-lieu',
	  type: 'post',
	  data: { 'action': 'display', 'display': display, 'catalog': catalog, 'time': Math.random() },
	  dataType: 'json',
	  success: function(p) {
		if (display == 'PRODUCT_DISPLAY_GRID') {
		  var j = 0;			
		  $.each(p, function(i) {			
			_html +=
			/*
			'<div class="block col-md-3 col-sm-4 col-xs-6">' +
			  '<div class="border">' +
				(p[i].new == 1 ? '<div class="icon-new"></div>' : '') +
				(p[i].brand.status == 1 ? '<div class="brand"><a href="' + p[i].brand.href + '" title="' + p[i].brand.title + '"><img alt="' + p[i].brand.alt + '" src="' + p[i].brand.src + '" height="15" title="' + p[i].brand.title + '" /></a></div>' : '') +
				'<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="' + p[i].src + '" title="' + p[i].title + '" /></a></div>' +
				'<div class="proname"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>' +
				'<div class="g-price"><span class="list-price">' + p[i].price_txt + '</span>' + (p[i].list_price > p[i].price ? '<span class="price">' + p[i].list_price_txt + '</span>' : '') + '</div>' +
			  '</div>' +
			'</div>';
			*/
			'<div class="block box col-lg-15 col-md-3 col-sm-4 col-xs-6 wow fadeIn animated" data-wow-delay="' + (j * 100) + 'ms">' +
			  '<div class="border box-img"><span class="over-layer"></span>' +
				(p[i].new == 1 ? '<div class="sale-box">New</div>' : '') +
				(p[i].sale_off > 0 ? '<span class="label red">-' + p[i].sale_off + '%</span>' : '') +
				'<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="' + p[i].src + '" title="' + p[i].title + '" /></a></div>' +
				'<div class="proname"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>' +
				'<div class="gr-price">' + (p[i].list_price > p[i].price ? '<span class="list-price"><span>' + p[i].list_price_txt + '</span></span>' : '') + '<span class="price"><span>' + p[i].price_txt + '</span></span></div>' +
			  '</div>' +
			  '<div class="box-content">' +
				'<p class="description"> ' + p[i].name + ' </p>' +
				'<ul class="links">' +
				  '<li><a href="' + p[i].href + '" title="' + p[i].title + '"><i class="fa fa-link"></i></a></li>' +
				  '<li><a rel="nofollow" id="' + p[i].id + '" href="javascript:;" class="btnAddtoCart" title="' + p[i].title + '"><i class="fa fa-cart-arrow-down"></i></a></li>' +
				'</ul>' +
			  '</div>' +
			'</div>';
			j++;
		  });	  
		} else {
		  $.each(p, function(i) {
			_html += '<div class="block detail">';
			_html += '<div class="col-right">';
			_html += '<div class="price">';
			_html += p[i].price > 0 ? '<span class="price-new">' + p[i].price_txt + '</span>' : p[i].contact_txt;
			_html += p[i].discount > 0 ? '<br /><span class="price-old">' + p[i].list_price_txt + '</span>' : '';
			_html += '</div>';
			/*
			if (p[i].price > 0 && p[i].state == 1) {
			  _html += '<div class="order">';
			  _html += '<input type="hidden" name="product_' + p[i].id + '" id="product_' + p[i].id + '" value="' + p[i].id + '" />';
			  _html += '<input type="button" id="' + p[i].id + '" name="btnAddtoCart" value="' + p[i].button + '" title="' + p[i].title + '" />';
			  _html += '</div>';
			}
			*/
			_html += '</div>';
			_html += '<div class="col-left">';
			_html += '<div class="picture"><a href="' + p[i].href + '" title="' + p[i].title + '"><img alt="' + p[i].alt + '" src="/thumb/500x500' + p[i].src + '" title="' + p[i].title + '" onerror="$(this).css({display:\'none\'})" /></a></div>';
			_html += '<div class="name"><a href="' + p[i].href + '" title="' + p[i].title + '">' + p[i].name + '</a></div>';
			_html += '<div class="description">' + p[i].desc + '</div>';
			_html += '</div>';
			_html += '</div>';
		  });		  
		}
		$('section.products div.content').html(_html);
		var new_position = $('#header').offset();
		if(c_position.top > 500)
		  $('html,body').animate({ scrollTop: new_position.top }, 1000);
	  }
	});
  }
  return false;
});

$('button[name=btnAddtoFavorite]').live('click', function(){
  var j = $(this);
  var c = j.attr('class');
  var f = c.replace(' ','-');
  if (f == 'add-to-favorite-added')
  	return false;
  if (f == 'add-to-favorite-signin')
  {
	alert('Vui lòng đăng nhập để sử dụng chức năng này!');
	return false;
  }
  var id = j.attr('id');
  var name = j.attr('title');
  $.ajax({
	url: '/xu-ly-san-pham-yeu-thich',
	type: 'post',
	data: {
	  'action': 'add',
	  'id': id,
	  'rand': Math.random()
	},
	dataType: 'json',
	success: function(j, status){
	  if (j.code == 'success' && status == 'success')
	  {
		$('div.favorite span#favorite').text(' Sản phẩm yêu thích');	
		$('div.favorite i').removeClass('fa-heart').addClass('fa-heart-o'); 
		$('div.favorite button').addClass('added');   
		var notice = 'Sản phẩm <b><i>' + name + '</i></b> đã được thêm vào danh sách yêu thích của Bạn.';		  
		var windowWidth = document.documentElement.clientWidth;
		var popupWidth = $('#pnsdotvn-notification').width();
		popupWidth = popupWidth == 0 ? 500 : popupWidth;
		$('#pnsdotvn-notification').html(notice).css({
		  'z-index': 99999,
		  'top': '100px',
		  'left': (windowWidth / 2) - (popupWidth / 2)
		}).fadeIn('fast').animate({
		  opacity: 1.0
		}, 1000).click(function() {
		  $(this).fadeOut('slow', function() {
			$(this).hide()
		  })
		}).fadeOut(6000);		  
	  }
	}
  });
});

$('a.removeWishlist').live('click', function(){
  var j = $(this);
  var id = j.attr('id');
  var name = $('button#' + id).attr('title');
  $.ajax({
	url: '/xu-ly-san-pham-yeu-thich',
	type: 'post',
	data: {
	  'action': 'remove',
	  'id': id,
	  'rand': Math.random()
	},
	dataType: 'json',
	success: function(j, status){
	  if (j.code == 'success' && status == 'success')
	  {
		$('tr#item_' + id).remove();
		var notice = 'Sản phẩm <b><i>' + name + '</i></b> đã loại bỏ khỏi danh sách yêu thích của Bạn.';		  
		var windowWidth = document.documentElement.clientWidth;
		var popupWidth = $('#pnsdotvn-notification').width();
		popupWidth = popupWidth == 0 ? 500 : popupWidth;
		$('#pnsdotvn-notification').html(notice).css({
		  'z-index': 99999,
		  'top': '100px',
		  'left': (windowWidth / 2) - (popupWidth / 2)
		}).fadeIn('fast').animate({
		  opacity: 1.0
		}, 1000).click(function() {
		  $(this).fadeOut('slow', function() {
			$(this).hide()
		  })
		}).fadeOut(6000);
	  }
	}
  });
});

function check_email(str) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(str);
}

$(document).ready(function() {
    $('button[name=btnNewsletter]').live('click', function() {
        var email = $('input#newsletter').val();
        if (email == null || email == '') {
            alert('Vui lòng nhập email của Bạn.');
            $("input#newsletter").focus();
            return false;
        } else if (check_email(email) == false) {
            alert('Địa chỉ email không hợp lệ.');
            $("input#newsletter").focus();
            return false;
        }

        $.ajax({
            url: "/xu-ly-dang-ky-nhan-tin",
            type: "post",
            data: {
                'action': 'newsletter',
                'email': email,
                'rand': Math.random()
            },
            dataType: "json",
            success: function(data, status) {
                if (data.code == "success" && status == "success") {
                    $("input#newsletter").val('');
                    alert("Cám ơn Bạn đã đăng ký.");
                } else if (data.code == "emailAddress") {
                    alert("Email này đã tồn tại trên hệ thống.");
                    $("input#newsletter").val('').focus();
                } else if (data.code == "invalidEmail") {
                    alert("Email bắt buộc và phải đúng định dạng.");
                    $("input#newsletter").focus();
                } else if (data.code == "missingData") {
                    alert("Vui lòng nhập đầy đủ thông tin.");
                    $("input#newsletter").focus();
                }
                return false;
            }
        });
        return false;

    });			
});

/*$(document).ready(function() {
    $('button[name=btnNewsletter]').live('click', function() {
        var email = $('input#newsletter').val();
        if (email == null || email == '') {
            alert('Vui lòng nhập email của Bạn.');
            $("input#newsletter").focus();
            return false;
        } else if (check_email(email) == false) {
            alert('Địa chỉ email không hợp lệ.');
            $("input#newsletter").focus();
            return false;
        }

        $.ajax({
            url: "/xu-ly-dang-ky-nhan-tin",
            type: "post",
            data: {
                'action': 'newsletter',
                'email': email,
                'rand': Math.random()
            },
            dataType: "json",
            success: function(data, status) {
                if (data.code == "success" && status == "success") {
                    $("input#newsletter").val('');
                    alert("Cám ơn Bạn đã đăng ký.");
                } else if (data.code == "emailAddress") {
                    alert("Email này đã tồn tại trên hệ thống.");
                    $("input#newsletter").val('').focus();
                } else if (data.code == "invalidEmail") {
                    alert("Email bắt buộc và phải đúng định dạng.");
                    $("input#newsletter").focus();
                } else if (data.code == "missingData") {
                    alert("Vui lòng nhập đầy đủ thông tin.");
                    $("input#newsletter").focus();
                }
                return false;
            }
        });
        return false;

    });			
});*/

// Extend the default Number object with a formatMoney() method:
// usage: someVar.formatMoney(decimalPlaces, symbol, thousandsSeparator, decimalSeparator)
// defaults: (2, "$", ",", ".")
Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 0;
	symbol = symbol !== undefined ? symbol : "đ";
	thousand = thousand || ".";
	decimal = decimal || ",";
	var number = this, 
	    negative = number < 0 ? "-" : "",
	    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
	    j = (j = i.length) > 3 ? j % 3 : 0;
	return negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "") + ' ' + symbol;
};
