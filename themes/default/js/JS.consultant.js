function check_email(str) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(str);
}

$(document).ready(function() {
    $('input[name=btnConsultant]').live('click', function() {
        var email = $('input#c_email').val();
		var phone = $('input#c_phone').val();
		var quantity = $('input#c_quantity').val();
		var name = $('input#c_name').val();
		var href = $('input#c_href').val();
        if (phone == null || phone == '') {
            alert('Vui lòng nhập số điện thoại của Bạn.');
            $("input#c_phone").focus();
            return false;
        } else if (email == null || email == '') {
            alert('Vui lòng nhập email của Bạn.');
            $("input#c_email").focus();
            return false;
        } else if (check_email(email) == false) {
            alert('Địa chỉ email không hợp lệ.');
            $("input#c_email").focus();
            return false;
        }

        $.ajax({
            url: "/xu-ly-dang-ky-tu-van",
            type: "post",
            data: {
                'action': 'consultant',
                'phone': phone,
				'email': email,
				'quantity': quantity,
				'name': name,
				'href': href,
                'rand': Math.random()
            },
            dataType: "json",
            success: function(data, status) {
                if (data.code == "success" && status == "success") {
                    $("input#c_phone").val('');
					$("input#c_email").val('');
                    alert("Cám ơn Bạn đã đăng ký.");
                } else if (data.code == "invalidNumber") {
                    alert("Số điện thoại không hợp lệ.");
                    $("input#c_phone").focus();
                } else if (data.code == "invalidEmail") {
                    alert("Email bắt buộc và phải đúng định dạng.");
                    $("input#c_email").focus();
                } else if (data.code == "missingData") {
                    alert("Vui lòng nhập đầy đủ thông tin.");
                    $("input#c_phone").focus();
                }
                return false;
            }
        });
        return false;
    });			
});