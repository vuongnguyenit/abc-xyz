$(window).load(function()
{
  $(".swap").fadeIn("fast");
  $("#username").focus();
});
function refreshImage()
{
    $.post('../plugins/captcha/newsession.php');
		$("#captchaimage").load('../plugins/captcha/require.php');
		return false;
}
$(function()
{
   $("#refreshimg").click(function(){
  		refreshImage();
   });
});

jQuery(function() {
    var loader = jQuery('<div id="loader">Please wait...</div>')
    .css({position: "absolute", top: "1em", left: "1em"})
    .appendTo("body")
    .hide();
    jQuery().ajaxStart(function() {
    	loader.show();
    }).ajaxStop(function() {
    	loader.hide();
    }).ajaxError(function(a, b, e) {
    	throw e;
    });
    var v = jQuery("#frmLogin").validate({
                  debug: false,
                  errorElement: "em",
                  success: function(label) {
          				label.text("").addClass("success");
          		},
          		rules: {
          		  username:
                    {
                      required: true,
                      minlength:6,
                      maxlength: 30
                    },
                    password:
                    {
                      required: true,
                      minlength:6,
                      maxlength: 30
                    },
                    captcha:
                    {
                      required: true,
                      remote: "../plugins/captcha/process.php"
                    }
          		},
                  messages:
                  {
                    username:
                    {
                      required: "Nhập username",
                      minlength: "Nhập 6 kí tự trở lên",
                      maxlength: "Tối đa 30 kí tự"
                    },
                    password:
                    {
                      required: "Nhập mật khẩu",
                      minlength: "Nhập 6 kí tự trở lên",
                      maxlength: "Tối đa 30 kí tự"
                    },
                    captcha:
                    {
                      required: "Nhập mã bảo vệ",
                      remote: "Mã bảo vệ sai"
                    }
                  },
                  submitHandler: function(form) {
                  		jQuery(form).ajaxSubmit({
                  		      target:"#result",
                              resetForm: true,
                              success:function(data)
                              {
                                data=data.split("+");
                                if(data[0]=="success")
                                {
                                  $("#result").html("Đang xác thực thành viên...");
                                  window.location=data[1];
                                  return false;
                                }else if(data[0]=="accountLocked")
                                {
                                  $("#result").html("Tài khoản đã bị khóa vì bạn đã đăng nhập sai hơn 5 lần. Vui lòng liên hệ administrator.");
                                }
                                else if(data[0]=="locked_anonymous")
                                {
                                  $("#result").html("Đăng nhập tạm thời bị khóa vì bạn đã đăng nhập sai hơn 5 lần. Vui lòng chờ 10-15' sau.");
                                }
                                else if(data[0]=="locked_account")
                                {
                                  $("#result").html("Tài khoản tạm thời bị khóa vì bạn đã đăng nhập sai hơn 5 lần. Vui lòng thông báo cho administrator để kích hoạt mở khóa.");
                                }
                                else
                                {
                                  $("#result").html("Tên người dùng hoặc mật khẩu không đúng.");
                                }
                                refreshImage();
                              }
                  	    });
                  }
      	});
        jQuery("#reset").click(function()
        {
          v.resetForm();
          $("#username").focus();
        });
});