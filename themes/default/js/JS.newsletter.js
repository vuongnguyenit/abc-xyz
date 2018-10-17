var newsletter_lng = new Array();
newsletter_lng['inprocess_vi-VN'] = 'Vui lòng chờ..';
newsletter_lng['inprocess_en-US'] = 'Please waiting..';
newsletter_lng['success_vi-VN'] = 'Cám ơn Quý khách đã đăng ký nhận bản tin.';
newsletter_lng['success_en-US'] = 'Thank you for registering for our newsletter.';
newsletter_lng['error_isset_email_vi-VN'] = 'Địa chỉ email này đã tồn tại trong hệ thống.';
newsletter_lng['error_isset_email_en-US'] = 'This email address already exists in the system.';
newsletter_lng['error_email_vi-VN'] = 'Email bắt buộc và phải đúng định dạng.';
newsletter_lng['error_email_en-US'] = 'Email incorrect format.';
newsletter_lng['error_phone_vi-VN'] = 'Điện thoại phải tối thiểu 7 số.';
newsletter_lng['error_phone_en-US'] = 'Phone number must be at least 7.';
newsletter_lng['error_missing_vi-VN'] = 'Vui lòng nhập thông tin đầy đủ.';
newsletter_lng['error_missing_en-US'] = 'Please fill in the form.';

function check_email(str) {
  var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  return emailPattern.test(str);
}

function check_number(str) {
  if (isNaN(str) == true) {
    return false;
  }
  return true;
}

jQuery('input[name=newsletter_button]').live('click', function() {
  var email = jQuery('input#newsletter_email').val();
  if(email == '' || check_email(email) == false)
  {
	alert(newsletter_lng['error_email_' + lang]);
	document.frmRegNewsletter.newsletter_email.focus();
	return false;	  
  }
  var phone = jQuery('input#newsletter_phone').val();
  if(phone == '' || phone.length < 7 || check_number(phone) == false)
  {
	alert(newsletter_lng['error_phone_' + lang]);
	document.frmRegNewsletter.newsletter_phone.focus();
	return false;	  
  }
  jQuery.ajax({
      url: '/xu-ly-dang-ky-nhan-tin',
      type: 'post',
      data: {
        'email': email,
        'phone': phone,
        'rand': Math.random()
      },
      dataType: 'json',
      success: function(j, status) {
		if (j.code == 'success' && status == 'success') {
 		  jQuery('input#newsletter_email').val('');	
		  jQuery('input#newsletter_phone').val('');		
		  alert(newsletter_lng['success_' + lang]);			
		} else if (j.code == 'emailAddress') {
		  alert(newsletter_lng['error_isset_email_' + lang]);
		  document.frmRegNewsletter.newsletter_email.focus();
		} else if (j.code == 'invalidEmail') {
		  alert(newsletter_lng['error_email_' + lang]);
		  document.frmRegNewsletter.newsletter_email.focus();
		} else if (j.code == 'invalidNumber') {
		  alert(newsletter_lng['error_phone_' + lang]);
		  document.frmRegNewsletter.newsletter_phone.focus();
		} else if (j.code == 'missingData') {
		  alert(newsletter_lng['error_missing_' + lang]);
		  document.frmRegNewsletter.newsletter_email.focus();
		}
		return false;
	  }
  });
});

