jQuery('button[name=login]').live('click', function() {
  var email = jQuery('input#email').val();
  if(email == '' || check_email(email) == false)
  {
	alert('Vui lòng nhập email.');
	return false;	  
  }
  var password = jQuery('input#password').val();
  if(password == '')
  {
	alert('Vui lòng nhập mật khẩu.');
	return false;	  
  }
  jQuery.ajax({
      url: '/xu-ly-dang-nhap',
      type: 'post',
      data: {
        'email': email,
        'password': password,
        'rand': Math.random()
      },
      dataType: 'json',
      success: function(j, status) {
		if (j.code == 'success' && status == 'success') {
 		  jQuery('input#email').val('');	
		  jQuery('input#password').val('');		
		  document.location.href = '/';
		} else if (j.code == 'missingData') {
		  alert('Vui lòng nhập đầy đủ dữ liệu.');
		  return false;
		} else if (j.code == 'fail') {
		  alert('Sai email / mật khẩu.');
		  return false;
		} 
		return false;
	  }
  });
});

