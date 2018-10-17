// JavaScript Document

// Check xem trình duyệt là IE6 hay IE7
var isIE		= (navigator.userAgent.toLowerCase().indexOf("msie") == -1 ? false : true);
var isIE6	= (navigator.userAgent.toLowerCase().indexOf("msie 6") == -1 ? false : true);
var isIE7	= (navigator.userAgent.toLowerCase().indexOf("msie 7") == -1 ? false : true);
var isChrome= (navigator.userAgent.toLowerCase().indexOf("chrome") == -1 ? false : true);

function addCommas(nStr){
	nStr += ''; x = nStr.split(',');	x1 = x[0]; x2 = ""; x2 = x.length > 1 ? ',' + x[1] : ''; var rgx = /(\d+)(\d{3})/; while (rgx.test(x1)) { x1 = x1.replace(rgx, '$1' + '.' + '$2'); } return x1 + x2;
}


function ajaxLink(){
	$("a[rel^='ajax']").click(function(){
		domEle= $(this);
		temp	= domEle.attr("rel").match(/\[(.*?)\]/);
		if(temp !== null){
			sel	= temp[1];
			$.get(domEle.attr("href"), function(data){
				$(sel).html(data);
				if(typeof(domEle.attr("addJS")) != "undefined") eval(domEle.attr("addJS"));
			});
		}
		return false;
	});
}

var ajaxDataAbsoluteTimeout	= null;
var arrAjaxDataAbsolute			= [];
function ajaxDataAbsolute(ob, mkey, link){
	
	opts	= {
		top		: ob.offset().top + ob.outerHeight(),
		left		: ob.offset().left,
		handler	: null
	}
	args	= ajaxDataAbsolute.arguments;
	if(typeof(args[3]) != "undefined") $.extend(opts, args[3]);
	
	obTemp	= $('<div class="ajax_data_absolute"></div>').html('<img src="' + fs_imagepath + 'loading.gif" />').hover(
		function(){ clearTimeout(ajaxDataAbsoluteTimeout); },
		function(){ $(".ajax_data_absolute").remove(); }
	);
	$("body").append(obTemp);
	offsetTop	= opts.top;
	offsetLeft	= opts.left;
	obTemp.css("top", offsetTop);
	fixPositionRight(obTemp, offsetLeft);
	
	showAjaxDataAbsolute	= function(data){
		data		= (typeof(opts.handler) == "function" ? opts.handler(data) : data);
		ob			= $(data);
		$("body").append(ob);
		obTemp.width(ob.width()).empty().append(ob);
		fixPositionRight(obTemp, offsetLeft);
	}
	if(typeof(arrAjaxDataAbsolute[mkey]) == "undefined"){
		$.get(link, function(data){
			arrAjaxDataAbsolute[mkey]	= data;
			showAjaxDataAbsolute(arrAjaxDataAbsolute[mkey]);
		});
	}
	else{
		showAjaxDataAbsolute(arrAjaxDataAbsolute[mkey]);
	}
	
	ajaxDataAbsoluteTimeout	= setTimeout('$(".ajax_data_absolute").remove()', 2000);
	
}

function baokimPayment(){
	return '<a class="colorbox_iframe_baokim tooltip_content" tooltipContent="baokim_tooltip" tooltipWidth="310" href="/ajax_v2/load_news.php?iData=7964" target="_blank" rel="nofollow"><img align="absmiddle" src="/images/baokim_icon.gif" /></a>';
}

function changePriceText(id, value){
	formatCurrency(id, value);
	if(parseInt(value) > 0) $("#" + id).css("display", "block");
	else $("#" + id).css("display", "none");
}

function checkAjaxResponse(response){
	if(response.substr(0, 7) == "[error]"){
		alert(response.replace("[error]", "").replace(/<br \/>/gi, '\n').replace(/\&bull;/gi, '-'));
		return false;
	}
	return true;
}

function checkForm(form_name, arrControl){
	frm	= eval("document." + form_name + ";");
	for(i=0; i<arrControl.length; i++){
		arrTemp	= arrControl[i].split("{#}");
		type		= arrTemp[0];
		defVal	= arrTemp[1];
		control	= arrTemp[2];
		title		= arrTemp[3];
		ob			= eval("frm." + control + ";");
		errMsg	= "";
		switch(type){
			case "0": if($.trim(ob.value) == "" || $.trim(ob.value) == defVal){ errMsg = "Bạn chưa nhập " + title + "."; } break;
			case "1": if(parseFloat(ob.value) <= parseFloat(defVal)){ errMsg = title + " phải lớn hơn " + addCommas(defVal) + "."; } break;
			case "2": if(ob.value == defVal){ errMsg = "Bạn chưa chọn " + title + "."; } break;
			case "3": if(!isEmail(ob.value)){ errMsg = title + " không hợp lệ."; } break;
			case "4": if($.trim(ob.value).length < defVal){ errMsg = title + " phải có ít nhất " + addCommas(defVal) + " ký tự."; } break;
			case "5": if(!isUrl(ob.value)){ errMsg = title + " không hợp lệ."; } break;
			case "6": if(parseFloat(ob.value) < parseFloat(defVal)){ errMsg = title + " phải lớn hơn hoặc bằng " + addCommas(defVal) + "."; } break;
			case "7": if(parseFloat(ob.value) > parseFloat(defVal)){ errMsg = title + " phải nhỏ hơn hoặc bằng " + addCommas(defVal) + "."; } break;
		}
		
		if(errMsg != ""){
			alert(errMsg);
			if($(".header_bar").length){
				// Check xem có moveScrollTop hay không
				defTop = 40;
				if(typeof($("form[name='" + form_name + "']").attr("fixScrollTop")) != "undefined"){
					defTop	= $("form[name='" + form_name + "']").attr("fixScrollTop");
				}
				move		= 0;
				obTemp	= $("form[name='" + form_name + "'] [name='" + control + "']");
				if($(window).scrollTop() + defTop < obTemp.offset().top){
					if((obTemp.offset().top - ($(window).scrollTop() + $(window).height())) > 0) move = 1;
				}
				else move = 1;
				// Move or focus
				if(move == 0) ob.focus();
				else moveScrollTop(obTemp, { top: defTop, finish: function(){ obTemp.focus(); } });
			}
			else{
				try{ob.focus();}
				catch(e){}
			}
			return false;
		}
	}
	
	// Nếu có thêm javascript thì execute
	args	= checkForm.arguments;
	if(typeof(args[2]) != "undefined"){
		opts	= { stop: false, callback: null }
		switch(typeof(args[2])){
			case "string"	: eval(args[2]); break;
			case "function": args[2](); break;
			case "object"	: $.extend(opts, args[2]); break;
		}
		if(typeof(opts.callback) == "function") opts.callback();
		if(opts.stop == true) return false;
	}
	
	if(typeof(formErrorOnSubmit) != "undefined" && formErrorOnSubmit[form_name] == 1) return false;
	// Nếu là form post_final thì khi không có lỗi phải disabled mấy nút submit đi để user không click nhiều, tránh duplicate
	if(form_name == "post_final"){
		$("form[name='" + form_name + "'] :submit").attr("disabled", "disabled").val("Vui lòng đợi...").blur();
	}
	// Submit form
	frm.submit();
}

function checkChangeBg(ob){
	ob.each(function(index, domEle){
		$(domEle).find("input[name='c\[\]']").click(function(){
			$(domEle).toggleClass("checked");
		});
	});
}

function checkAll(ob, type){
	ob2	= ob.find("input[name='c\[\]']");
	if(type == 0){
		ob2.attr("checked", false);
		ob.removeClass("checked");
	}
	else if(type == 1){
		ob2.attr("checked", true);
		ob.addClass("checked");
	}
	else{
		ob2.each(function(){
			$(this).click();
		});
	}
}

function convertVideoLink(ob){
	
	opts	= {
		limit	: 5,
		width	: 560,
		height: 315
	}
	
	args	= convertVideoLink.arguments;
	if(typeof(args[1]) != "undefined") $.extend(opts, args[1]);
	
	youtubeIDextract	= function(text){
		var replace = "$1";
		if(!text.match(/(?:http:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g)) return false;
		if(text.match(/^[^v]+v.([^&^=^\/]{11}).*/)) return text.replace(/^[^v]+v.([^&^=^\/]{11}).*/,replace);
		else if(text.match(/^[^v]+\?v=([^&^=^\/]{11}).*/)) return text.replace(/^[^v]+\?v=([^&^=^\/]{11}).*/,replace);
		else return false;
	}
	ob.each(function(){
		$(this).find("a").filter(function(){
			return this.href.match(/(?:http:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g);
		}).each(function(index, domEle){
			code	= youtubeIDextract($(domEle).attr("href"));
			if(code.length > 20) return;
			if(code !== false){
				iframe= $('<iframe width="' + opts.width + '" height="' + opts.height + '" src="http://www.youtube.com/embed/' + htmlspecialbo(code) + '" frameborder="0" allowfullscreen="true" style="margin:5px 0px"></iframe>');
				iframe.insertAfter($(domEle));
				$(domEle).remove();
			}
			if(index >= (opts.limit - 1)) return false;
		});
	});
	
}

function countDown(object){
	ob	= $("#" + object);
	if(ob.length == 0) return;
	ob.html(function(index, html){
		return (parseInt(html) - 1);
	});
	if(ob.html() > 0) setTimeout("countDown('" + object + "')", 1000);
	else window.location.href	= redirect;
}

function fixAnchorLink(){
	hrefTemp	= window.location.href;
	if(hrefTemp.indexOf("#") != -1){
		arrTemp	= hrefTemp.split("#");
		obTemp	= $("#" + arrTemp[arrTemp.length-1]);
		if(obTemp.length) $(window).scrollTop(obTemp.offset().top - 40);
	}
}

function fixImageSize(width, height, maxWidth, maxHeight){
	// Check if the current width is larger than the max
	ratio		= maxWidth / width;   // Get ratio for scaling image
	w			= maxWidth;
	h			= height * ratio;    // Reset height to match scaled image
	// Check if current height is larger than max
	if(h > maxHeight){
		ratio	= maxHeight / height; // Get ratio for scaling image
		w		= width * ratio;    // Reset width to match scaled image
		h		= maxHeight;
	}
	return Array(parseInt(w), parseInt(h));
}

function fixPositionRight(ob, oLeft){
	oRight	= 5;
	defLeft	= oLeft;
	args		= fixPositionRight.arguments;
	if(typeof(args[2]) != "undefined") oRight		= args[2];
	if(typeof(args[3]) != "undefined") defLeft	= args[3];
	// Set vị trí bên phải để tránh bị overflow
	if((defLeft + ob.width()) > $("#body").width()) ob.css({ left: "auto", right: oRight });
	else ob.css("left", oLeft);
}

var defPosition	= Array();
function fixedPosition(ob1, ob2, key){
	if(isIE6 || isIE7) return;
	if(ob1.offset() == null) return;
	defPosition[key]				= ob1.offset().top;
	defPosition[key + "Class"]	= "";
	$(window).scroll(function(){
		temp	= $(window).scrollTop() + 38;
		if(defPosition[key] == ob2.offset().top && defPosition[key] > temp) return;
		if(temp > defPosition[key]){
			if(defPosition[key + "Class"] == ""){
				ob2.addClass("fixed_position");
				defPosition[key + "Class"]	= "fixed_position";
			}
		}
		else{
			if(defPosition[key + "Class"] != ""){
				ob2.removeClass("fixed_position");
				defPosition[key + "Class"]	= "";
			}
		}
	});
}

function fixedPositionDataOption(){
	fixedPosition($(".data_option_fixed:first"), $(".data_option:first"), "dataOption");
	$(".data_option:first").width($(".data_option_fixed:first").width() - 10);
	$(".data_option_fixed:first").css("background", "none");
}

function formatCurrency(div_id, str_number){
	document.getElementById(div_id).innerHTML = addCommas(str_number);
}

// Form login
function generateFormLogin(){
	
	formLoginOpts	= {
		redirect	: "",
		success	: null
	}
	
	args	= generateFormLogin.arguments;
	switch(typeof(args[0])){
		case "string"	: formLoginOpts.redirect	= args[0]; break;
		case "function": formLoginOpts.success		= args[0]; break;
		case "object"	: $.extend(formLoginOpts, args[0]); break;
	}
	
	quickLoginColorBox	= function(link){
		$.colorbox({ href: link, iframe: true, width: "735px", height: "95%", overlayClose: false, onCleanup: function(){ window.location.reload(); } });
		domEleWindowPrompt().remove();
	}
	
	quickLoginAjaxSuccess	= function(){
		if(typeof(formLoginOpts.success) == "function") formLoginOpts.success();
		else if(formLoginOpts.redirect != "") window.location.href	= "/home/redirect.php?url=" + formLoginOpts.redirect;
		else window.location.reload();
	}
	
	quickLoginAjax	= function(){
		frmEle	= $("form[name='quick_login']");
		$.post(frmEle.attr("action"), frmEle.serialize(), function(data){
			if(!checkAjaxResponse(data)) return;
			quickLoginAjaxSuccess();
		});
	}
	
	onSubmit	= "checkForm(this.name, arrCtrlLogin, { stop: true, callback: quickLoginAjax }); return false;";
	
	html		= '<form class="form" name="quick_login" action="/home/act_login.php" method="post" enctype="multipart/form-data" onSubmit="' + onSubmit + '">' +
	'<table class="form_table" cellpadding="0" cellspacing="0" align="center">' +
		'<tr><td class="form_name"></td><td class="form_text"><span class="form_text_note">Những ô có dấu sao (<span class="form_asterisk">*</span>) là bắt buộc phải nhập.</span></td></tr>' +
		'<tr><td class="form_name"></td><td class="form_text"><div class="form_errorMsg_content"><span class="form_errorMsg">&bull; Tên truy cập có thể là <b>Username</b> hoặc <b>Email</b><br />bạn đã đăng ký trên Vatgia.com.<br /></span></div></td></tr>' +
		'<tr><td class="form_name"><font class="form_asterisk">* </font>Tên truy cập :</td><td class="form_text"><input class="form_control" type="text" autocomplete="off" title="Tên truy cập (Username hoặc Email)" id="login_loginname" name="loginname" maxlength="250" style="width:250px" /></td></tr>' +
		'<tr><td class="form_name"><font class="form_asterisk">* </font>Mật khẩu :</td><td class="form_text"><input class="form_control" type="password" title="Mật khẩu" id="login_password" name="password" maxlength="250" style="width:250px" /></td></tr>' +
		'<tr><td></td><td class="form_text"><input type="checkbox" id="login_remember_password" name="remember_password" value="1" /><label for="login_remember_password">Nhớ mật khẩu</label></td></tr>' +
		'<tr><td></td><td><input type="submit" class="form_button" value="Đăng nhập hệ thống" /></td></tr>' +
	'</table>' +
	'<input type="hidden" name="ajax" value="1" />' +
	'<input type="hidden" name="user_login" value="login" />' +
	'</form>' +
	'<div align="center" style="margin-top:5px">Nếu bạn chưa có tài khoản, hãy <a class="text_link_2" href="/home/register.php">Đăng ký</a> ngay bây giờ.</div>' +
	'<div align="center" style="margin-top:5px">Trong trường hợp bạn quên mật khẩu, hãy <a class="text_link_2" href="/home/lost_password.php">Click vào đây</a>.</div>';
	return html;
}

// Xuat html tu data json truyen vao
function generateMenuQuickAccess(json_data){
	var ob				= $.parseJSON(json_data);
	eval("var obTemp = ob.cat_" + catParentId + ";");
	var html_return	= '<ul>';

	// Neu la cat cuoi thi moi show root
	if(catHasChild == 0){
		html_return	+=	'<li class="root"><a class="text_link" href="' + obTemp.link + '">' + obTemp.name + '</a></li>';
	}
	
	i	= 0;
	var num_link	= (catHasChild == 0) ? 1 : 5;
	$.each(ob, function(key, value){
		// Truong hop cat has child do minh da thay cat_id = cat parent nen phai sanh voi cat_parent con ko thi sanh voi cat_id de loai bo
		if(key != ("cat_" + (catHasChild == 0 ? catParentId : iCat))){
			i++;
			if(catHasChild == 0){
				var class_hidden	= (value.id == iCat) ? ' current' : ' hidden';	
			}else{
				var class_hidden	= (i > num_link) ? ' hidden' : '';
			}
			html_return	+=	'<li class="sub' + class_hidden + '"><a title="' + value.name + '" class="text_link" href="' + value.link + '">' + value.short_name + '</a></li>';
		}
	});
	
	if(i > num_link){
		html_return	+=	'<li class="view_more showing"><a title="Xem thêm" class="text_link" href="javascript:tongleMoreQuickAccess();">Xem thêm</a></li>';
	}
	html_return		+= '</ul>';
	
	return html_return;
}

function generateShareBox(type, record_id, favorites, authen_code){
	switchShareBox	= function(){
		if(domEle.find(".switch_bar").hasClass("switch_bar_collapse")){
			domEle.css("left", $("#container_body").offset().left+1001-9).find(".box").hide().end().find(".switch_bar").removeClass("switch_bar_collapse");
		}
		else{
			domEle.css("left", $("#container_body").offset().left+1001-130).find(".box").show().end().find(".switch_bar").addClass("switch_bar_collapse");
		}
	}
	if(!$(".share_box").length){
		
		ob	= '<div class="share_box"><a class="switch_bar" href="javascript:;" onClick="switchShareBox(); this.blur();"></a><div class="box">';
			
			// Kiem tra truong hop neu la cat cuoi cung thi show theo cat_parent_id
			var id	= iCat;
			if(catHasChild == 0){
				id	= catParentId;
			}
			
			// Trang chi tiet thi moi show doan nay
			if(typeof(expire) == "undefined") expire = 0;
			if(record_id > 0 && expire == 0){
				
				ob	+=	'<div class="favorites">';
				if(user_logged != 1) ob	+=	'<a title="Theo dõi" class="add" href="javascript:;" rel="nofollow" onClick="windowPrompt(\'Đăng nhập\', generateFormLogin)">Theo dõi</a>';
				else if(type == "hoidap"){
					if(favorites == 0) ob += '<a title="Theo dõi" class="add" href="/home/addfavorites.php?addto=hoidap&record_id=' + record_id + '&redirect=' + fs_redirect + '" rel="nofollow">Theo dõi</a>';
					else ob += '<a title="Bỏ theo dõi" class="remove" href="javascript:if(confirm(\'Bạn có muốn bỏ theo dõi câu hỏi này không?\')) window.location.href=\'/home/delete_favorites.php?deleteto=hoidap&record_id=' + record_id + '&authen_code=' + authen_code + '&redirect=' + fs_redirect + '\';" rel="nofollow">Bỏ theo dõi</a>';
				}
				else if(type == "raovat"){
					if(favorites == 0) ob += '<a title="Theo dõi" class="add" href="/home/addfavorites.php?addto=raovat&record_id=' + record_id + '&redirect=' + fs_redirect + '" rel="nofollow">Theo dõi</a>';
					else ob += '<a title="Bỏ theo dõi" class="remove" href="javascript:if(confirm(\'Bạn có muốn bỏ theo dõi rao vặt này không?\')) window.location.href=\'/home/delete_favorites.php?deleteto=raovat&record_id=' + record_id + '&authen_code=' + authen_code + '&redirect=' + fs_redirect + '\';" rel="nofollow">Bỏ theo dõi</a>';
				}
				else if(type == "product"){
					if(favorites == 0) ob += '<a title="Theo dõi" class="add" href="/home/addfavorites.php?addto=product&record_id=' + record_id + '&redirect=' + fs_redirect + '" rel="nofollow">Theo dõi</a>';
					else ob += '<a title="Bỏ theo dõi" class="remove" href="javascript:if(confirm(\'Bạn có muốn bỏ theo dõi sản phẩm này không?\')) window.location.href=\'/home/delete_favorites.php?deleteto=product&record_id=' + record_id + '&authen_code=' + authen_code + '&redirect=' + fs_redirect + '\';" rel="nofollow">Bỏ theo dõi</a>';
				}
				ob	+=	'</div>';
				
				// Báo vi phạm
				switch(type){
					case "hoidap"	: ob += '<div class="report"><a title="Gửi báo cáo vi phạm đến ban quản trị." class="colorbox_iframe" href="' + con_ajax_path + 'load_send_error_hoidap.php?record_id=' + record_id + '" rel="nofollow">Báo vi phạm</a></div>'; break;
					case "raovat"	: ob += '<div class="report"><a title="Gửi báo cáo vi phạm đến ban quản trị." class="colorbox_iframe" href="' + con_ajax_path + 'load_send_error_raovat.php?record_id=' + record_id + '" rel="nofollow">Báo vi phạm</a></div>'; break;
				}
				
				urlFB	= (typeof(realURL) != "undefined" ? realURL : window.location.href);
				if(!isIE6 && !isIE7) ob	+= '<div class="facebook_like"><iframe src="http://www.facebook.com/plugins/like.php?href=' + urlFB + '&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></div>';
				if(!isIE) ob += '<div class="google_plusone"><g:plusone size="medium"></g:small></div>';
				ob	+= '<div class="facebook_share end"><a title="Chia sẻ qua Facebook." href="http://www.facebook.com/sharer.php?u=' + urlFB + '" rel="nofollow"><img src="' + fs_imagepath + 'icon_facebook_share.gif" /></a></div>';	
				ob	+= '<div class="break_module_line"></div>';
				
			}// End if(record_id > 0)
			
			// Quick Access
			switch(type){
				case "raovat":
				case "product":
					ob	+=	'<div class="quick_access"><div class="title">Truy cập nhanh</div><div class="quick_access_category"></div></div>';
					var sel	= ".quick_access .quick_access_category";
					$.get(con_ajax_path + "load_category_quick_access.php?iCat=" + id + "&nType=json", function(json_data){
						$(sel).html( generateMenuQuickAccess(json_data) );
					});
					break;
			}// End switch(type)
			
		ob	+= '</div></div>';
		$("body").append(ob);
		
		// Khởi tạo lại initColorBox
		initColorBox();
		
	}// End if(!$(".share_box").length)
	
	domEle	= $(".share_box");
	if(isIE6) domEle.css("position", "absolute");
	domEle.css("top", 50);
	width	= $(window).width();
	if(width > 1182){
		domEle.find(".switch_bar").removeClass("switch_bar_collapse").hide().end().find(".box").show();
		domEle.css("left", $("#container_body").offset().left+1001).find(".box").removeClass("box_2");
	}
	else{
		domEle.find(".box").hide().end().find(".switch_bar").show().removeClass("switch_bar_collapse");
		domEle.css("left", $("#container_body").offset().left+1001-9).find(".box").addClass("box_2");
	}
}

function generateSort(type, value, def, url){
	arrType	= type.split("|");
	arrValue	= value.split("|");
	for(i=0; i<arrType.length; i++){
		src	= fs_imagepath + "sort" + arrType[i] + (arrValue[i] == def ? "_selected" : "") + ".gif";
		document.write('<a href="' + url + '&sort=' + arrValue[i] + '"><img hspace="2" src="' + src + '" style="margin-top:3px" /></a>');
	}
}

// Tạo star bình chọn
function generateVote(numberstar){
	
	// Tính theo thang điểm 5 hay 10 star
	maxStar		= 10;
	
	args			= generateVote.arguments;
	if(typeof(args[1]) != "undefined") maxStar = args[1];
	
	// Loại star (to hay bé)
	starType		= (typeof(starType) == "undefined" ? "" : starType);
	
	numberstar	= parseFloat(numberstar)
	intStar		= parseInt(numberstar);
	if(isNaN(numberstar)) return;
	
	// Kiểm tra sai số
	if(intStar < 0){ intStar = 0; numberstar = 0; }
	if(intStar > maxStar){ intStar = maxStar; numberstar = maxStar; }
	
	strReturn	= "";
	
	// Ghi star xịn ra
	for(i=1; i<=intStar; i++) strReturn += '<img src="' + fs_imagepath + 'star_' + starType + '1.gif" />';
	
	// Nếu intStar != numberstar thì thêm 0.5 vào và cộng intStar thêm 1
	if(intStar != numberstar){
		temp	= numberstar.toString().split(".");
		num	= parseInt(temp[1].substr(0, 1));
		if(num <= 3)		img = 'star_' + starType + '0.3.gif';
		else if(num <= 7) img = 'star_' + starType + '0.5.gif';
		else					img = 'star_' + starType + '0.7.gif';
		strReturn	+= '<img src="' + fs_imagepath + img + '" />';
		intStar++;
	}
	
	// Ghi ra số star = 0 còn lại
	for(i=intStar+1; i<=maxStar; i++) strReturn += '<img src="' + fs_imagepath + 'star_' + starType + '0.gif" />';
	
	return strReturn;
	
}

/*** Header Bar ***/
var arrHeaderBarMenu		= [];
var fixHeaderBarContent	= 0;
var checkKeywordFocus	= 0;

function keywordFocus(ob){
	if(!isIE6 && !isIE7){
		checkKeywordFocus		= 1;
		if($(".header_bar .search .select").is(".hide")){
			$(".header_bar .fl .bar:has(.cart)").hide().nextAll().hide();
			$(".header_bar .search .select").removeClass("hide");
		}
	}
	ob.val(function(index, value){
		return (value == defSearchKeyword ? "" : value);
	}).removeClass("form_control_null_value");
}

function keywordBlur(ob){
	checkKeywordFocus		= 0;
	ob.val(function(index, value){
		return (value == "" ? defSearchKeyword : value);
	}).addClass(function(index2, value2){
		return (ob.val() == defSearchKeyword ? "form_control_null_value" : "");
	});
}

var headerBarFocus		= false;
var headerBarTimeout		= null;
var supportOnlineClick	= false;
function initHeaderBar(){
	
	if(isIE6 || isIE7) $(".header_bar .hidden").css("margin-top", "0px");
	
	var domEle		= null;
	
	showHeaderBarContent	= function(){
		ob	= domEle.nextAll(".hidden");
		// Nếu là class notification
		if(domEle.hasClass("notification")){
			domEle.find("span").addClass("loading").find("b").text("0").hide();
			ob.load(domEle.attr("href") + "&" + Math.random(), function(response, status, xhr){
				if(ob.position() !== null){
					ob.show();
					// Set vị trí bên phải để tránh bị overflow
					fixPositionRight(ob, ob.position().left, 0);
					domEle.find("span").removeClass("loading");
				}
			});
		}
		else{
			ob.show();
			// Set vị trí bên phải để tránh bị overflow
			fixPositionRight(ob, ob.position().left, 0);
		}
	}
	
	resetHeaderBar	= function(){
		headerBarFocus			= false;
		supportOnlineClick	= false;
		clearTimeout(headerBarTimeout);
		$(".header_bar .bar_link span").removeClass("loading");
		$(".header_bar .hidden").hide();
		$(".header_menu_sub").remove();
		$(".left_menu_drop").remove();
		$(".left_menu_drop_sep").remove();
	}
	
	showHeaderBarMenu	= function(data){
		obTemp		= $(data);
		$("body").append(obTemp);
		offsetTop	= domEle.height();
		offsetLeft	= domEle.offset().left - (domEle.is(".menu_current") ? 1 : 0);
		obTemp.css("top", offsetTop);
		// Set vị trí bên phải để tránh bị overflow
		fixPositionRight(obTemp, offsetLeft);
		// Header menu sub
		$(".header_menu_sub li").mouseenter(function(){
			headerBarFocus = true;
			clearTimeout(headerBarTimeout);
			$(".header_menu_sub li").removeClass("drop_current");
			$(".left_menu_drop").remove();
			$(".left_menu_drop_sep").remove();
			$(this).addClass("drop_current");
			id				= $(this).attr("iData");
			switch(domEle.attr("module")){
				case "hoidap"	: content = leftMenuDropHoiDap(id); break;
				case "raovat"	: content = leftMenuDropRaoVat(id); break;
				default			: content = leftMenuDrop(id); break;
			}
			ob				= $(content);
			$("body").append(ob);
			if(!$(this).is(".post")) $("body").append('<div class="left_menu_drop_sep"></div>');
			if(ob.find(".sub").length == 0) ob.addClass("left_menu_drop_fix");
			$(".left_menu_drop").addClass("left_menu_drop_2");
			// Get offset
			offsetTop	= $(this).offset().top;
			offsetLeft	= $(this).offset().left - ob.outerWidth() + 1;
			tempH			= $(window).height() - (offsetTop - $(window).scrollTop());
			if(($(window).height() - $(".header_bar").height()) < ob.outerHeight(true)) offsetTop	= $(window).scrollTop() + $(".header_bar").height();
			else if(tempH < ob.outerHeight(true)) offsetTop = offsetTop + (tempH - ob.outerHeight(true));
			// Fix offset
			$(".left_menu_drop_sep").css({ top: $(this).offset().top + 2, left: $(this).offset().left - 1 });
			ob.css({ top: offsetTop, left: offsetLeft });
		});
	}
	
	$(".header_bar .hidden").hover(
		function(){ headerBarFocus = true; clearTimeout(headerBarTimeout); },
		function(){ $(this).hide(); }
	);
	
	$(".header_bar .bar_link").hoverIntent({
		over: function(){
			resetHeaderBar();
			domEle			= $(this);
			if(domEle.hasClass("support_online")) return;
			
			// Check menu
			if(typeof(domEle.attr("module")) != "undefined"){
				if(isIE6 || isIE7) return;
				// Check menu data
				if(typeof(arrHeaderBarMenu[domEle.attr("module")]) == "undefined"){
					// Get menu data
					domEle.after('<div class="hidden" style="display:block"><img src="' + fs_imagepath + 'loading.gif" /></div>');
					ajaxLink	= con_ajax_path + "load_header_menu.php?module=" + domEle.attr("module");
					if(domEle.attr("module") == module) ajaxLink	+= "&iCat=" + iCat;
					$.get(ajaxLink, function(data){
						arrHeaderBarMenu[domEle.attr("module")]	= data;
						cacheFile	= "";
						switch(domEle.attr("module")){
							case "hoidap"	: if(typeof(leftMenuDropHoiDap) == "undefined")	cacheFile	= "/cache/left_menu/left_menu_drop_hoidap.js"; break;
							case "raovat"	: if(typeof(leftMenuDropRaoVat) == "undefined")	cacheFile	= "/cache/left_menu/left_menu_drop_raovat.js"; break;
							default			: if(typeof(leftMenuDrop) == "undefined")			cacheFile	= "/cache/left_menu/left_menu_drop.js"; break;
						}
						if(cacheFile != ""){
							$.getScript(cacheFile, function(){
								showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
							});
						}
						else showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
						domEle.next().remove();
					});
				}
				showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
			}
			else{
				if(!domEle.next(".hidden").length) return;
				showHeaderBarContent();
			}
			
		},
		out: function(){
			if(!headerBarFocus) headerBarTimeout = setTimeout("resetHeaderBar()", 2000);
		}
	});
	
	$(".header_bar .bar_link.support_online").click(function(){
		domEle	= $(this);
		domEle.blur();
		if(typeof(support_online) == "undefined"){
			$.getScript("/cache/support_online/support_online_v2.js", function(){
				domEle.next().find(".support").html(support_online);
			});
		}
		else domEle.next().find(".support").html(support_online);
		showHeaderBarContent();
	});
	
	if(!isIE6 && !isIE7){
		// Header bar search
		$(".header_bar .search").hoverIntent({
			over: function(){
				resetHeaderBar();
				$(".header_bar .fl .bar:has(.cart)").hide().nextAll().hide();
				$(".header_bar .search .select").removeClass("hide");
			},
			out: function(){
				$(".header_banner, .header_season, .header_navigate_div, #container_content, div[id^='container_content_']:not(#container_content_left), #container_footer").mouseenter(function(){
					if(checkKeywordFocus == 0 && !$(".header_bar .search .select").is(".hide")){
						$(".header_bar .search .select").addClass("hide");
						$(".header_bar .fl .bar:has(.cart)").show().nextAll().show();
					}
				});
			}
		});
	}
	
}

function initHeaderBar_v2(){
	
	resetHeaderBar	= function(){
		clearTimeout(headerBarTimeout);
		$(".header_bar_v2 .icon span").removeClass("loading");
		$(".header_menu_sub").remove();
		$(".left_menu_drop").remove();
		$(".left_menu_drop_sep").remove();
	}
	
	showHeaderBarMenu	= function(data){
		obTemp		= $(data);
		$("body").append(obTemp);
		offsetTop	= $(".header_bar_v2").outerHeight();
		offsetLeft	= domEle.offset().left - (domEle.is(".menu_current") ? 1 : 0);
		obTemp.css("top", offsetTop);
		// Set vị trí bên phải để tránh bị overflow
		fixPositionRight(obTemp, offsetLeft);
		// Header menu sub
		$(".header_menu_sub li").mouseenter(function(){
			headerBarFocus = true;
			clearTimeout(headerBarTimeout);
			$(".header_menu_sub li").removeClass("drop_current");
			$(".left_menu_drop").remove();
			$(".left_menu_drop_sep").remove();
			$(this).addClass("drop_current");
			id				= $(this).attr("iData");
			switch(domEle.attr("module")){
				case "hoidap"	: content = leftMenuDropHoiDap(id); break;
				case "raovat"	: content = leftMenuDropRaoVat(id); break;
				default			: content = leftMenuDrop(id); break;
			}
			ob				= $(content);
			$("body").append(ob);
			if(!$(this).is(".post")) $("body").append('<div class="left_menu_drop_sep"></div>');
			if(ob.find(".sub").length == 0) ob.addClass("left_menu_drop_fix");
			// Get offset
			offsetTop	= $(this).offset().top;
			offsetLeft	= $(this).offset().left + $(this).outerWidth(true) - 1;
			tempH			= $(window).height() - (offsetTop - $(window).scrollTop());
			if(($(window).height() - $(".header_bar_v2").height()) < ob.outerHeight(true)) offsetTop	= $(window).scrollTop() + $(".header_bar_v2").height();
			else if(tempH < ob.outerHeight(true)) offsetTop = offsetTop + (tempH - ob.outerHeight(true));
			// Fix offset
			$(".left_menu_drop_sep").css({ top: $(this).offset().top + 2, left: offsetLeft });
			ob.css({ top: offsetTop, left: offsetLeft });
		});
	}
	
	$(".header_bar_v2 .menu[module]").hoverIntent({
		over: function(){
			resetHeaderBar();
			domEle		= $(this);
			moduleTemp	= domEle.attr("module");
			// Check menu
			if(typeof(moduleTemp) != "undefined"){
				// Check menu data
				if(typeof(arrHeaderBarMenu[moduleTemp]) == "undefined"){
					// Get menu data
					domEle.after('<div class="menu_loading"><img src="' + fs_imagepath + 'loading.gif" /></div>');
					ajaxLink	= con_ajax_path + "load_header_menu.php?module=" + domEle.attr("module");
					if(domEle.attr("module") == module) ajaxLink	+= "&iCat=" + iCat;
					$.get(ajaxLink, function(data){
						arrHeaderBarMenu[domEle.attr("module")]	= data;
						cacheFile	= "";
						switch(domEle.attr("module")){
							case "hoidap"	: if(typeof(leftMenuDropHoiDap) == "undefined")	cacheFile	= "/cache/left_menu/left_menu_drop_hoidap.js"; break;
							case "raovat"	: if(typeof(leftMenuDropRaoVat) == "undefined")	cacheFile	= "/cache/left_menu/left_menu_drop_raovat.js"; break;
							default			: if(typeof(leftMenuDrop) == "undefined")			cacheFile	= "/cache/left_menu/left_menu_drop.js"; break;
						}
						if(cacheFile != ""){
							$.getScript(cacheFile, function(){
								showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
							});
						}
						else showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
						domEle.next().remove();
					});
				}
				showHeaderBarMenu(arrHeaderBarMenu[domEle.attr("module")]);
			}
			
		},
		out: function(){
			headerBarTimeout = setTimeout("resetHeaderBar()", 1000);
		}
	});
	
	$(".header_bar_v2 .support_online").mousemove(function(){
		if(typeof(support_online) == "undefined"){
			$.getScript("/cache/support_online/support_online_v2.js", function(){
				$(".header_bar_v2 .support").html(support_online);
			});
		}
		else $(".header_bar_v2 .support").html(support_online);
	});
	
}
/*-- End Header Bar --*/

function htmlspecialbo(string){
	arrStr	= ['<', '"', '>'];
	arrRep	= ['&lt;', '&quot;', '&gt;'];
	for(i=0; i<arrStr.length; i++){
		eval('string	= string.replace(/' + arrStr[i] + '/g, "' + arrRep[i] + '");');
	}
	return string;
}

function initColorBox(){
	$(".colorbox").colorbox({
		maxWidth: "95%",
		maxHeight: "95%",
		current: "ảnh {current} / {total}",
		fixed: true,
		onComplete: function(){
			strHtml		= "";
			if(typeof($(this).attr("tooltipContent")) != "undefined"){
				arrTemp	= $(this).attr("tooltipContent").split("@#@");
				name		= (typeof(arrTemp[2]) != "undefined" ? '<a href="' + htmlspecialbo(arrTemp[2]) + '" target="blank">' + htmlspecialbo(arrTemp[0]) + '</a>' : htmlspecialbo(arrTemp[0]));
				price		= (parseFloat(arrTemp[1]) > 0 ? parseFloat(arrTemp[1]) : 0);
				link		= (typeof(arrTemp[2]) != "undefined" ? ' - <a href="' + htmlspecialbo(arrTemp[2]) + '" target="blank">Xem chi tiết</a>' : '');
				if(name != "")strHtml += '<div class="cboxText">' + name + '</div>';
				if(price > 0) strHtml += '<div class="cboxPrice">Giá: <b>' + addCommas(price) + ' VNĐ</b>' + link + '</div>';
			}
			if(strHtml != "") $("#cboxLoadedContent").append('<div class="cboxContent">' + strHtml + '</div>');
		}
	});
	$(".colorbox_iframe").colorbox({ iframe: true, width: "735px", height: "95%", overlayClose: false, fixed: true });
	$(".colorbox_iframe_baokim").colorbox({ iframe: true, width: "735px", height: "95%", overlayClose: false, fixed: true });
	$(".colorbox_iframe_upload").colorbox({ iframe: true, width: "800px", height: "95%", fixed: true });
}

function isEmail(s){
	var re	= /^(\w|[^_]\.[^_]|[\-])+(([^_])(\@){1}([^_]))(([a-z]|[\d]|[_]|[\-])+|([^_]\.[^_]) *)+\.[a-z]{2,3}$/i;
	return re.test(s);
}

function isUrl(s){
	var re	= /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	return re.test(s);
}

function initListCategory(){
	if (isIE6 || isIE7) return;
	$(".list_category a.has_child").hoverIntent({
		over: function(){
			link	= con_ajax_path + "load_category_quick_access.php?iCat=" + $(this).attr("iData");
			ajaxDataAbsolute($(this), "list_category_" + $(this).attr("iData"), link);
		},
		out: function(){}
	});
}

function initTimeago(){
	$.timeago.settings.refreshMillis	= 60000;
	$(".timeago").timeago();
}

function moveScrollTop(ob){
	args	= moveScrollTop.arguments;
	opts	= {
		top	: 40,
		time	: 1000,
		finish: false
	};
	if(typeof(args[1] != "undefined")) $.extend(opts, args[1]);
	if(!isChrome) $("html").animate({ scrollTop: (ob.offset().top - opts.top) }, opts.time, "easeOutExpo", opts.finish);
	else $("body").animate({ scrollTop: (ob.offset().top - opts.top) }, opts.time, "easeOutExpo", opts.finish);
}

// Nếu ảnh bị lỗi thì thay bằng no_photo.gif
function pictureError(sel){
	$(sel + " .picture_xx_small img").error(function(){ $(this).attr("src", fs_imagepath + "no_photo_xx_small.gif"); });
	$(sel + " .picture_x_small img").error(function(){ $(this).attr("src", fs_imagepath + "no_photo_x_small.gif"); });
	$(sel + " .picture_small img").error(function(){ $(this).attr("src", fs_imagepath + "no_photo_small.gif"); });
	$(sel + " .picture_medium img").error(function(){ $(this).attr("src", fs_imagepath + "no_photo_medium.gif"); });
	$(sel + " .picture_larger img").error(function(){ $(this).attr("src", fs_imagepath + "no_photo_larger.gif"); });
	
	$(sel + " .avatar_xx_small img").error(function(){ $(this).attr("src", fs_imagepath + "no_avatar_xx_small.gif"); });
	$(sel + " .avatar_x_small img").error(function(){ $(this).attr("src", fs_imagepath + "no_avatar_x_small.gif"); });
}

// Resize image with maxWidth + maxHeight
function resizeImage(){
	$("img[maxWidth],img[maxHeight]").each(function(){
		var maxWidth	= $(this).attr("maxWidth"); // Max width for the image
		var maxHeight	= $(this).attr("maxHeight"); // Max height for the image
		var width		= $(this).width();    // Current image width
		var height		= $(this).height();  // Current image height
		// Fix maxWidth
		if(isNaN(maxWidth) || maxWidth <= 0) maxWidth	= width;
		// Check if the current width is larger than the max
		ratio		= maxWidth / width;   // Get ratio for scaling image
		width		= maxWidth;
		height	= height * ratio;    // Reset height to match scaled image
		// Check if current height is larger than max
		if(height > maxHeight){
			ratio		= maxHeight / height; // Get ratio for scaling image
			height	= maxHeight;
			width		= width * ratio;    // Reset width to match scaled image
		}
		// Chỉ resize những ảnh có độ rộng hoặc độ cao vượt quá giới hạn
		if($(this).attr("resizeType") == "overflow"){
			if($(this).width() < width && $(this).height() < height) return;
		}
		// Resize image
		$(this).attr({ width: width, height: height });
	});
}

function resizeImageSrc(src, width, height, maxWidth, maxHeight){
	opts	= {
		resizeLess	: true,
		html			: ""
	}
	args	= resizeImageSrc.arguments;
	if(typeof(args[5]) != "undefined") $.extend(opts, args[5]);
	
	if(opts.resizeLess == true || (width > maxWidth || height > maxHeight)){
		// Check if the current width is larger than the max
		ratio		= maxWidth / width;   // Get ratio for scaling image
		width		= maxWidth;
		height	= height * ratio;    // Reset height to match scaled image
		// Check if current height is larger than max
		if(height > maxHeight){
			ratio		= maxHeight / height; // Get ratio for scaling image
			height	= maxHeight;
			width		= width * ratio;    // Reset width to match scaled image
		}
	}
	
	return '<img src="' + src + '" width="' + parseInt(width) + '" height="' + parseInt(height) + '" ' + opts.html + '/>';
}

var simpleTipFocus	= false;
var simpleTipTimeout	= null;
var simpleTipObject	= null;
function simpleTip(){
	$(".simple_tip").hoverIntent({
		over: function(){
			simpleTipObject	= $($(this).attr("rel"));
			simpleTipFocus		= false;
			$(".simple_tip_content, .simple_tip_content_2").hide();
			clearTimeout(simpleTipTimeout);
			domEle				= $(this);
			
			posTop				= domEle.offset().top + domEle.height();
			if(typeof(domEle.attr("fixTop")) != "undefined") posTop = parseInt(domEle.attr("fixTop"));
			if(typeof(domEle.attr("posTop")) != "undefined") posTop += parseInt(domEle.attr("posTop"));
			else posTop += 3;
			
			posLeft				= domEle.offset().left;
			if(typeof(domEle.attr("posLeft")) != "undefined") posLeft += parseInt(domEle.attr("posLeft"));
			
			defLeft				= posLeft;
			posRight				= 5;
			// fix absolute (Trong trường hợp simple_tip_content nằm trên 1 DOM có thuộc tính absolute, ví dụ như header bar)
			if(typeof(domEle.attr("fixAbsolute")) != "undefined"){
				posLeft			-= ($("#body").outerWidth(true) - $("#container_body").width()) / 2;
				posRight			= 0;
			}
			// fix position right overflow
			fixPositionRight(simpleTipObject, posLeft, posRight, defLeft);
			
			// Show ob
			simpleTipObject.show();
			
			// Nếu sử dụng giao diện v2 và ob căn right thì fix lại icon_arrow_up
			if(simpleTipObject.hasClass("simple_tip_content_2")){
				temp	= domEle.offset().left - simpleTipObject.offset().left;
				simpleTipObject.css("backgroundPosition", temp + parseInt((domEle.outerWidth(true)-6)/2) + "px 0px");
			}
			
			simpleTipObject.hover(
				function(){ simpleTipFocus	= true; clearTimeout(simpleTipTimeout); },
				function(){
					if(typeof(domEle.attr("manualClose")) == "undefined") $(this).hide();
				}
			).css({ top: posTop });
		},
		out: function(){
			if(!simpleTipFocus) simpleTipTimeout	= setTimeout('simpleTipObject.hide();', 1000);
		}
	});
}

// Ẩn, hiện, thêm, xem menu quick access
function tongleMoreQuickAccess(){
	$(".quick_access_category ul li.hidden").toggle();
	var check_hidden	= $(".quick_access_category ul li").is(":hidden");
	if(check_hidden == true){
		$(".quick_access_category ul li.view_more a").html("Xem thêm");
		$(".quick_access_category ul li.view_more").addClass("showing").removeClass("hiding");
	}else{
		$(".quick_access_category ul li.view_more a").html("Thu gọn");
		$(".quick_access_category ul li.view_more").addClass("hiding").removeClass("showing");
	}
}

function tooltipContent(){
	if(isIE6 || isIE7) return;
	$(".tooltip_content").tooltip({
		bodyHandler: function(){
			width		= 500;
			if(typeof($(this).attr("tooltipWidth")) != "undefined"){
				width	= parseInt($(this).attr("tooltipWidth"));
			}
			$("#tooltip").css("width", width + "px");
			eval("content	= " + $(this).attr("tooltipContent") + ";");
			return content;
		},
		track: true,
		showURL: false,
		extraClass: "tooltip_content"
	});
}

function tooltipPicture(){
	if(isIE6 || isIE7) return;
	$(".tooltip_picture[tooltipContent!=''], .tooltip_picture[tooltipPicture!='hide']").tooltip({
		bodyHandler: function(){
			width			= 350;
			height		= 350;
			if(typeof($(this).attr("tooltipWidth")) != "undefined"){
				width		= parseInt($(this).attr("tooltipWidth"));
			}
			if(typeof($(this).attr("tooltipHeight")) != "undefined"){
				height	= parseInt($(this).attr("tooltipHeight"));
			}
			$("#tooltip").css("width", width + "px");
			strReturn	= "";
			if(typeof($(this).attr("tooltipContent")) != "undefined"){
				arrTemp	= $(this).attr("tooltipContent").split("@#@");
				name		= htmlspecialbo(arrTemp[0]);
				price		= (parseFloat(arrTemp[1]) > 0 ? parseFloat(arrTemp[1]) : 0);
				if(name != "")strReturn	+= '<div class="name">' + name + '</div>';
				if(price > 0) strReturn	+= '<div class="price_product">Giá: <b class="price">' + addCommas(price) + ' VNĐ</b></div>';
			}
			tooltipPicture	= (typeof($(this).attr("tooltipPicture")) != "undefined" ? $(this).attr("tooltipPicture") : "");
			if(tooltipPicture != "hide"){
				picturePath	= "";
				if(typeof($(this).attr("picturePath")) != "undefined"){
					picturePath	= $(this).attr("picturePath");
				}else{
					picturePath	= $(this).attr("href");
				}
				
				strReturn	+= '<div class="picture">' + resizeImageSrc(picturePath, $(this).find("img").width(), $(this).find("img").height(), width, height) + '</div>';
			}
			return strReturn;
		},
		track: true,
		showURL: false,
		extraClass: "tooltip_picture"
	});
}

function tooltipProduct(ob){
	if(isIE6 || isIE7) return;
	/*
	var obj = ".product_realtime_list_home  pre, .list_product pre, .view_list_table pre, .view_collapse_table pre,"
				 + " .compare_table pre, .suggestion_content pre, .upload_gallery pre, .other_estore_product_exclusive_table pre,"
				 + " .show_cart_table pre, .product_follow pre, .product_table_template pre";
	if(typeof(ob) != "undefined") obj = ob;
	*/
	// Nếu có khai báo ob thì gán luôn cho strTooltipProductOb
	if(typeof(ob) != "undefined") strTooltipProductOb = ob;
	// Nếu ko tồn tại strTooltipProductOb thì return luôn
	if(typeof(strTooltipProductOb) == "undefined") return;
	// Bẻ ra array
	arrTooltipProductOb	= strTooltipProductOb.split(",");
	// Lấy array unique
	arrTooltipProductOb	= $.unique(arrTooltipProductOb);
	// Lấy obj để đưa vào jquery
	obj	= "";
	$.each(arrTooltipProductOb, function(index, value){
		if(obj != "") obj += ",";
		obj += $.trim(value);
	});
	$(obj).each(function(index, domEle){
		var obTT	= $(domEle).parent().find(".tooltip");
		var img	= $(domEle).parent().find(".tooltip img");
		obTT.tooltip({
			bodyHandler: function(){
				tooltipWidth	= (typeof(obTT.attr("tooltipWidth")) != "undefined" ? obTT.attr("tooltipWidth") : 350);
				$("#tooltip").css("width", tooltipWidth + "px");
				$(domEle).find(".picture, .picture_only").html(function(index, html){
					width			= (typeof($(this).attr("width"))	!= "undefined" ? $(this).attr("width") : img.width());
					height		= (typeof($(this).attr("height"))!= "undefined" ? $(this).attr("height") : img.height());
					maxWidth		= (typeof($(this).attr("maxWidth"))	!= "undefined" ? $(this).attr("maxWidth") : 350);
					maxHeight	= (typeof($(this).attr("maxHeight"))!= "undefined" ? $(this).attr("maxHeight") : 350);
					arrFixSize	= fixImageSize(width, height, maxWidth, maxHeight);
					return '<img width="' + arrFixSize[0] + '" height="' + arrFixSize[1] + '" src="' + $(this).attr("src") + '" />';
				});
				return $(domEle).html();
			},
			track: true,
			showURL: false,
			extraClass: "tooltip_product"
		});
	});
}

function windowPrompt(data){
	
	$(".wPrompt").remove();
	
	var wPromptOpts	= {
		width			: "auto",
		height		: "auto",
		title			: "",
		content		: "",
		comment		: "",
		fixed			: true,
		showBottom	: true,
		href			: null,
		ajax			: false,
		iframe		: false,
		overlay		: true,
		overlayClose: true,
		alert			: false,
		confirm		: false,
		
		onOpen		: null,
		onComplete	: null,
		onCleanup	: null,
		onClosed		: null
	};
	
	var optsAlert	= {
		value			: "Đồng ý",
		callback		: null
	}
	
	var optsConfirm= {
		valueTrue	: "Đồng ý",
		valueFalse	: "Từ chối",
		callback		: null
	}
	
	args	= windowPrompt.arguments;
	if(args.length == 2){
		wPromptOpts.title	= args[0];
		data			= args[1];
	}
	
	// Extend data
	if(typeof(data) == "object"){
		$.extend(wPromptOpts, data);
		if(wPromptOpts.alert != false || wPromptOpts.confirm != false){
			// Khi alert, confirm cho showBottom mặc định = false
			if(typeof(data.showBottom) == "undefined") wPromptOpts.showBottom = false;
		}
	}
	
	// Extend alert
	if(typeof(wPromptOpts.alert) == "object") $.extend(optsAlert, wPromptOpts.alert);
	else if(typeof(wPromptOpts.alert) == "function") optsAlert.callback = wPromptOpts.alert;
	
	// Extend confirm
	if(typeof(wPromptOpts.confirm) == "object") $.extend(optsConfirm, wPromptOpts.confirm);
	else if(typeof(wPromptOpts.confirm) == "function") optsConfirm.callback = wPromptOpts.confirm;
	
	// Get DOM element
	domEleWindowPrompt	= function(){
		domEle	= $(".wPrompt, .wPromptOverlay");
		domEle	= $.extend(domEle, {wPrompt: $(".wPrompt"), wPromptOverlay: $(".wPromptOverlay")});
		return domEle;
	}
	
	// Alert function
	alertWindowPrompt	= function(){
		closeWindowPrompt();
		if(typeof(optsAlert.callback) == "function") optsAlert.callback();
	}
	
	// Confirm function
	confirmWindowPrompt	= function(confirm){
		closeWindowPrompt();
		if(typeof(optsConfirm.callback) == "function") optsConfirm.callback(confirm);
	}
	
	// Close function
	closeWindowPrompt = function(){
		if(typeof(wPromptOpts.onCleanup) == "function") wPromptOpts.onCleanup(domEleWindowPrompt());
		$(".wPrompt, .wPromptOverlay").remove();
		if(typeof(wPromptOpts.onClosed) == "function") wPromptOpts.onClosed();
	}
	
	if(typeof(data) == "object"){
		// Ajax
		if(wPromptOpts.ajax && wPromptOpts.href != null) wPromptOpts.content	= $.ajax({ url: wPromptOpts.href, async: false }).responseText;
		// Iframe
		else if(wPromptOpts.iframe && wPromptOpts.href != null) wPromptOpts.content	= '<iframe class="wPromptIframe" name="wPromptIframe" frameborder="0" src="' + wPromptOpts.href + '" onload="window.frames[\'wPromptIframe\'].document.body.style.marginRight=\'5px\'"></iframe>';
		// Function
		else if(typeof(wPromptOpts.content) == "function") wPromptOpts.content = wPromptOpts.content();
	}
	else if(typeof(data) == "function") wPromptOpts.content = data();
	else wPromptOpts.content = data;
	
	// Width
	if(wPromptOpts.width != "auto"){
		if(String(wPromptOpts.width).indexOf("%") !== -1)	wPromptOpts.width	= parseInt(wPromptOpts.width)/100 * ($(window).width() - 21);
		wPromptOpts.width	= parseInt(wPromptOpts.width) + "px";
	}
	// Height
	if(wPromptOpts.height != "auto"){
		if(String(wPromptOpts.height).indexOf("%") !== -1)	wPromptOpts.height= parseInt(wPromptOpts.height)/100 * ($(window).height() - (wPromptOpts.showBottom ? 53 : 26));
		wPromptOpts.height= parseInt(wPromptOpts.height) + "px";
	}
	
	// onOpen
	if(typeof(wPromptOpts.onOpen) == "function") wPromptOpts.onOpen();
	
	// Nếu isIE6 thì không cho overlay
	if(isIE6) wPromptOpts.overlay	= false;
	
	html	= '';
	if(wPromptOpts.overlay && !$(".wPromptOverlay").length) html += '<div class="wPromptOverlay"' + (wPromptOpts.overlayClose ? ' style="cursor:pointer" onClick="closeWindowPrompt()"' : '') + '></div>';
	wPromptAbsolute	= (!wPromptOpts.fixed || isIE6 ? ' wPromptAbsolute' : '');
	html += '<div class="wPrompt' + wPromptAbsolute + '">';
		html += '<div class="wPromptWrapper" style="width:' + wPromptOpts.width + '">';
			html += '<div class="wPromptLoadedContent" style="width:' + wPromptOpts.width + '; height:' + wPromptOpts.height + '">';
				if(wPromptOpts.iframe && wPromptOpts.href != null) html += wPromptOpts.content;
				else{
					if(wPromptOpts.title != "") html += '<div class="wPromptTitle">' + wPromptOpts.title + '</div>';
					cssIcon	= '';
					if(wPromptOpts.alert != false)	cssIcon = ' wPromptAlert';
					if(wPromptOpts.confirm != false) cssIcon = ' wPromptConfirm';
					html += '<div class="wPromptContent' + cssIcon + '">';
					html += wPromptOpts.content;
					if(wPromptOpts.alert != false){
						html += '<div class="wPromptAlertButton"><input type="button" class="wPromptInputButton" value="' + optsAlert.value + '" onClick="alertWindowPrompt()" /></div>';
					}
					if(wPromptOpts.confirm != false){
						html += '<div class="wPromptConfirmButton">';
							html += '<input type="button" class="wPromptInputButton" value="' + optsConfirm.valueTrue + '" onClick="confirmWindowPrompt(true)" /> &nbsp;';
							html += '<input type="button" class="wPromptInputButton" value="' + optsConfirm.valueFalse + '" onClick="confirmWindowPrompt(false)" />';
						html += '</div>';
					}
					html += '</div>';
				}
			html += '</div>';
			html += '<div class="clear"></div>';
			if(wPromptOpts.showBottom){
				html += '<div class="wPromptBottom">';
					if(wPromptOpts.comment != "") html += '<div class="wPromptComment">' + wPromptOpts.comment + '</div>';
					html += '<a title="Đóng" class="wPromptClose" href="javascript:;" onClick="closeWindowPrompt()"></a>';
					html += '<div class="clear"></div>';
				html += '</div>';
			}
		html += '</div>';
	html += '</div>';
	
	ob	= $(html);
	
	$("body").prepend(ob);
	
	if(wPromptOpts.alert != false || wPromptOpts.confirm != false) ob.find(".wPromptInputButton:first").focus();
	
	ob.filter(".wPrompt").css({
		top: function(){
			offsetTop	= parseInt(($(window).height() - $(this).find(".wPromptLoadedContent").height() - 53) / 2);
			if(offsetTop < 0) offsetTop = 0;
			if(!wPromptOpts.fixed || isIE6) offsetTop += $(window).scrollTop();
			return offsetTop + "px";
		},
		left: function(){
			offsetLeft	= parseInt(($(window).width() - $(this).find(".wPromptLoadedContent").width() - 42) / 2);
			if(offsetLeft < 0) offsetLeft = 0;
			return offsetLeft + "px";
		}
	});
	
	if(wPromptOpts.width == "auto" && (isIE6 || isIE7)){
		fixW	= ob.find(".wPromptLoadedContent").width();
		ob.find(".wPromptWrapper").width(fixW);
	}
	
	// onComplete
	if(typeof(wPromptOpts.onComplete) == "function") wPromptOpts.onComplete(domEleWindowPrompt());
	
}

/*** Auto complete ***/
function setAutoComplete(){
	$(".search_keyword").autocomplete("/search-suggestion.html", {
		width: 550,
		delay: 150,
		scroll: false,
		max: 20,
		selectFirst: false,
		formatResult: formatResult,
		formatItem: formatItem
	});
}

function removeAutoComplete(){
	$(":input").unautocomplete();
}
function formatItem(row){
	return row[0];
}

function formatResult(row){
	return row[1];
}
/*-- End Auto complete --*/

/*** Quick search ***/
var defaultLang	= "vn";
function changeSearchLang(ob){
	if(defaultLang == "vn"){
		defaultLang	= "en";
		ob.attr("src", fs_imagepath + "en.gif");
		AVIMObj.setMethod(-1);
	}
	else{
		defaultLang	= "vn";
		ob.attr("src", fs_imagepath + "vn.gif");
		AVIMObj.setMethod(0);
	}
	ob.parent().parent().find(".search_keyword").focus();
}

function changeSearchLang_v2(ob){
	if(defaultLang == "vn"){
		defaultLang	= "en";
		$(".image_language").attr("src", fs_imagepath + "en.gif");
		AVIMObj.setMethod(-1);
	}
	else{
		defaultLang	= "vn";
		$(".image_language").attr("src", fs_imagepath + "vn.gif");
		AVIMObj.setMethod(0);
	}
	ob.parent().parent().find(".search_keyword").focus();
}

function changeSearchType(number){
	$("#search_shop").attr("disabled", true);
	frm	= $(".header_bar form[name='header_search']");
	switch(parseInt(number)){
		case 0: frm.attr("action", "/home/quicksearch.php"); setAutoComplete(); break;
		case 1: frm.attr("action", "/raovat/quicksearch.php"); removeAutoComplete(); break;
		case 2: frm.attr("action", "/hoidap/quicksearch.php"); removeAutoComplete(); break;
		case 3: frm.attr("action", "/review/quicksearch.php"); removeAutoComplete(); break;
		case 4: frm.attr("action", "/home/shop.php"); $("#search_shop").attr("disabled", false); removeAutoComplete(); break;
		case 5: frm.attr("action", "/batdongsan/quicksearch.php"); removeAutoComplete(); break;
	}
	$(".header_bar form[name='header_search'] .search_keyword").focus();
}

function changeSearchType_v2(id){
	$("#search_shop").attr("disabled", true);
	$("#header_bar_category_id").attr("disabled", true);
	$("#header_bar_season_id").attr("disabled", true);
	
	frm				= $(".header_search_v2 form[name='header_search']");
	switch(parseInt(id)){
		case 0: frm.attr("action", "/home/quicksearch.php"); setAutoComplete(); break;
		case 1: frm.attr("action", "/raovat/quicksearch.php"); removeAutoComplete(); break;
		case 2: frm.attr("action", "/hoidap/quicksearch.php"); removeAutoComplete(); break;
		case 3: frm.attr("action", "/review/quicksearch.php"); removeAutoComplete(); break;
		case 4: frm.attr("action", "/home/shop.php"); $("#search_shop").attr("disabled", false); removeAutoComplete(); break;
		case 5: frm.attr("action", "/batdongsan/quicksearch.php"); removeAutoComplete(); break;
		case 6: frm.attr("action", "/home/season_search.php"); $("#header_bar_season_id").attr("disabled", false); removeAutoComplete(); break;
	}
	
	str	= $(".header_search_v2 .search_type_list #search_" + id + " a").html();
	$(".header_search_v2 .search_type .search_in").html(str);
	$(".header_search_v2 form[name='header_search'] .search_keyword").focus();
	
	toggleSearchType();
}
function changeSearchCategory(searchModule, id){
	removeAutoComplete();
	
	frm				= $(".header_search_v2 form[name='header_search']");
	frm.attr("action", "/" + searchModule + "/quicksearch.php");
	
	iCatInputField	= $("#header_bar_category_id");
	iCatInputField.val(id);
	iCatInputField.removeAttr("disabled"); // Nếu chọn là danh mục hiện tại thì enable lên
	
	str	= $(".header_search_v2 .search_type_list #search_category_" + id + " a").html();
	$(".header_search_v2 .search_type .search_in").html(str);
	$(".header_search_v2 form[name='header_search'] .search_keyword").focus();
	
	toggleSearchType();
}

function toggleSearchType(){
	$(".header_search_v2 .search_type_list").hide();
}

function defaultValueHeaderSearch(){
	ob	= $("form[name='header_search'] input[name='keyword']");
	if(ob.val() == "" || ob.val() == defSearchKeyword){
		ob.val(defSearchKeyword).addClass("form_control_null_value");
	}
}
/*-- End Quick search --*/

/*** Realtime ***/
var realtime_box;
var domEle_realtime_box;
var save_box;
var domEle_save_box;

// Khởi tạo box realtime
function initRealtime(){
	width	= $(window).width();

	// Ko phai IE 6 và màn hình lớn hơn 1200 thi moi show realtime
	if(isIE6 == false && width >= 1200){
		
		if(!$(".realtime_box").length){
			
			ob_realtime_box	= '<div class="realtime_box"><div class="box"><div class="title"><span title="Rao vặt mới đăng, mới up">Rao vặt mới</span></div><ul></ul></div></div>';
			$("body").append(ob_realtime_box);
			
			ob_save_box	= '<div class="save_box"><div class="box"><ul></ul></div></div>';
			$("body").append(ob_save_box);
			
			// Gán lại biến biến sử dụng về sau
			realtime_box			= $(".realtime_box");
			domEle_realtime_box	= $(".realtime_box ul");
			
			save_box				= $(".save_box");
			domEle_save_box		= $(".save_box ul");
			
			// Fix lại độ rộng
			box_width	= (width - 1000)/2;
			
			//realtime_box.css({"left":"1px", "width":box_width});
			realtime_box.css({"right":"-1px", "width":box_width});
			realtime_box.find(".box").width(box_width - 12);
			
			save_box.css({"right":"-1px", "width":box_width});
			save_box.find(".box").width(box_width - 12);
			
		}// End if(!$(".realtime_box").length)
		
	}// End if(isIE6 == false && width >= 1200)
	
	// Mặc định ban đầu ẩn box realtime
	$(".realtime_box, .save_box").hide();
}

// Reset lai ve rong va an box realtime
function resetBoxRealtime(){
	realtime_box.animate({ height:0 }, 1000, function(){
		domEle_realtime_box.html("");
		realtime_box.css({"height":"auto"}).hide();
	});
}

// Luu lai tin realtime
function saveRealtime(record_id){
	var obCheck	= domEle_save_box.find('li[iData="' + record_id + '"]');

	domEle_realtime_box.find('li[iData="' + record_id + '"]').animate({ opacity: 0, height:0 }, 500, function(){
		
		if(obCheck.length == false){
			
			var data		= '<li iData="' + record_id + '">';
			data	+= $(this).html();
			data	+= '<div align="right">'
			data	+= '<a class="favorites" title="Theo dõi" href="javascript:favoritesRealtime(' + record_id + ')">Theo dõi</a>';
			data	+= '<a class="delete" title="Xóa" href="javascript:deleteSavedRealtime(' + record_id + ')">Xóa</a>';
			data	+= '</div>'
			data	+= '</li>';

			var insert	= $(data).css({
				opacity: 0,
				display: "none"
			}).appendTo(domEle_save_box);
			
			if(save_box.is(":hidden")) save_box.show();
			
			insert.show().animate({ opacity: 1 }, 500, function(){
				tooltipRaovat(); //trường hợp là rao vặt thì show tooltip của tin mới load ajax ra
			});
			
		}// End if(obCheck.length == false)
		
		$(this).remove();
		
		// Kiểm tra nếu ko còn content thì ẩn luôn cả box
		if(domEle_realtime_box.html() == ""){
			realtime_box.hide();
		}	
	});
	
}

// Luu theo doi
function favoritesRealtime(record_id){
	window.location.href	= '/home/addfavorites.php?addto=raovat&record_id=' + record_id + '&redirect=' + fs_redirect;
}

// Xoa khoi box luu tin realtime ben phai
function deleteSavedRealtime(record_id){
	if(confirm("Bạn có muốn loại bỏ tin Rao vặt này không?")){
		domEle_save_box.find('li[iData="' + record_id + '"]').animate({ opacity: 0, height:0 }, 500, function(){
			$(this).remove();
			// Kiểm tra nếu ko còn content thì ẩn luôn cả box
			if(domEle_save_box.html() == ""){
				save_box.hide();
			}
		});
	}
}

// Đếm lại toàn bộ notify ở quả cầu
function recountNotifyAll(value){
	if(typeof(value) == "number"){
		value		= value;
	}else{
		obTemp	= $(value);
		value		= parseInt(obTemp.text());	
	}
	
	if(value > 0){
		ob = $("#count_all b");
	   ob.css("display", "block");
	   var num =  parseInt(ob.text());
	   
	   args	= recountNotifyAll.arguments;
	   if(args.length == 2){ // Trừ đi ở box count
	   	num = num - value;
	   }else{
	   	num = num + value;
	   }
	   
	   if(num < 0) num = 0;
	   
	   ob.text(num);
	   
		// Nếu tổng số thông báo = 0 sau khi recount thì ẩn đi
		if(num == 0) ob.css("display", "none");
	}
}
/*-- End Realtime --*/

(function($){
	$.fn.outerHTML	= function(s){
		return (s) ? this.before(s).remove() : $('<p>').append(this.eq(0).clone()).html();
	}
})(jQuery);

/*** Initiate Load Main ***/
function footerLoadMain(){
	
	simpleTip();
	
	if(!isIE6 && !isIE7) initColorBox();
	
	defaultValueHeaderSearch();
	
	ajaxLink();
	
	initTimeago();
	
	if(isIE6 || isIE7) return;
	
	$("ul.header_navigate li:not(.header_navigate_sub li)").hover(
		function(){
			if(isHeaderNavigate == 0){
				resetLeftMenuDrop();
				$(".header_navigate ul").css("visibility", "hidden");
			}
			isHeaderNavigate	= 1;
			$(this).addClass("hover");
			$("ul:first", this).css("visibility", "visible");
		},
		function(){
			if(isHeaderNavigate == 1){
				$(this).removeClass("hover");
				$("ul:first", this).css("visibility", "hidden");
			}
		}
	);
	$("ul.header_navigate li ul li:has(ul)").find("a:first").addClass("level_1");
	
	pictureError("");
	
}

var isHeaderNavigate	= 1;
function initLoadMain(){
	
	if(isIE6 || isIE7) initColorBox();
	initHeaderBar();
	initHeaderBar_v2();
	
}

// Run a function when the page is fully loaded
function initLoadedMain(){
	
	resizeImage();
	
	tooltipContent();
	
	$.get(con_ajax_path + "load_footer_top_static.php?hisData=" + (typeof(hisData) != "undefined" ? hisData : ""), function(data){
		$(".footer_menu:last").before(data);
	});
	
}
/*-- End Initiate Main On Load --*/