var chkorder_lng = new Array();
chkorder_lng['inprocess_vi-VN'] = 'Vui lòng chờ..';
chkorder_lng['inprocess_en-US'] = 'Please waiting..';
chkorder_lng['error_invalid_email_vi-VN'] = 'Địa chỉ email không hợp lệ.';
chkorder_lng['error_invalid_email_en-US'] = 'Email is invalided.';
chkorder_lng['error_email_vi-VN'] = 'Email bắt buộc và phải đúng định dạng.';
chkorder_lng['error_email_en-US'] = 'Email incorrect format.';
chkorder_lng['error_missing_vi-VN'] = 'Vui lòng nhập thông tin đầy đủ.';
chkorder_lng['error_missing_en-US'] = 'Please fill in the form.';

function lockButton() {
    $('#send').addClass('disabled');
    $('#send').attr('disabled', 'disabled');
}

function enableButton() {
    $('#send').removeClass('disabled');
    $('#send').removeAttr('disabled');
}
document.frmChkorder.code.focus();
$(function() {
    $('#refreshimg').live('click', function(event) {
        event.preventDefault();
        $.post('/captcha-new-session');
        $('#captchaimage').load('/captcha-require', function(data, status) {
            if (status == 'success') $('input#captcha').focus();
        });
        return false;
    });
});
$(function() {
    var loader = $('<div class="loader">' + chkorder_lng['inprocess_' + lang] + '</div>').css({
        position: "fixed",
        top: "4em",
        right: "0.9em"
    }).appendTo('body').hide();
    $(document).ajaxStart(function() {
        loader.fadeIn(100).show(1);
        lockButton();
        $('div#showdata').hide();
    }).ajaxStop(function() {
        loader.animate({
            'opacity': 1.0
        }, 500).fadeOut(100).hide(1);
        enableButton();
    }).ajaxError(function(a, b, e) {
        throw e;
    });
    var container = $('div#showdata');
    var v = $('#frmChkorder').validate({
        debug: false,
        errorContainer: container,
        errorLabelContainer: $('ol', container),
        meta: 'validate',
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                resetForm: false,
                dataType: 'json',
                success: function(data, status) {
                    if (data.code == 'success' && status == 'success') {
                        v.resetForm();
						var _name = '';
						var name = data.order.detail.name;
						$.each(name, function(i) {
							_name += '<span class="item">' + name[i] + '</span><br /><br />';
						});
						var _price = '';
						var price = data.order.detail.price;
						$.each(price, function(i) {
							_price += '<span class="item">' + price[i] + '</span><br /><br />';
						});
						var _quantity = '';
						var quantity = data.order.detail.quantity;
						$.each(quantity, function(i) {
							_quantity += '<span class="item">' + quantity[i] + '</span><br /><br />';
						});
						var _amount = '';
						var amount = data.order.detail.amount;
						$.each(amount, function(i) {
							_amount += '<span class="item">' + amount[i] + '</span><br /><br />';
						});
						var _status = '';
						var status = data.order.status;
						_status = '<ul class="order_history">';
						$.each(status, function(i) {
							_status += '<li' + (i % 2 == 0 ? ' class="highline"' : '') + '><span class="status_time">' + status[i].date + '</span><span class="status_text">' + status[i].desc + '</span></li>';
						});
						_status += '</ul>';
                        var out =
						'<div class="order_content_box">' +
						  '<div class="order_content_box" id="order_content_box">' +
							'<div class="order_header">' +
							  '<h3>Chào bạn, dưới đây là thông tin đơn hàng <strong id="order-code">' + data.order.code + '</strong> của bạn:</h3>' +
							  '<div class="clear">' +
								'<label>Thời gian mua hàng:</label>' +
								'<span id="date">' + data.order.date + '</span> </div>' +
							  '<div class="clear">' +
								'<label>Phương thức thanh toán:</label>' +
								'<span id="payment">' + data.order.payment + '</span> </div>' +
							  '<div class="clear">' +
								'<label>Phương thức vận chuyển:</label>' +
								'<span id="shipping">' + data.order.shipping + '</span> </div>' +
							'</div>' +
							'<div class="order_content">' +
							  '<table style="border-bottom: 1px solid #ddd;">' +
								'<thead>' +
								  '<tr>' +
									'<td>Trạng thái đơn hàng</td>' +
								  '</tr>' +
								'</thead>' +
								'<tbody>' +
								  '<tr>' +
									'<td>' + _status + '</td>' +
								  '</tr>' +
								'</tbody>' +
							  '</table>' +
							  '<table>' +
								'<thead>' +
								  '<tr>' +
									'<td>Tên sản phẩm</td>' +
									'<td>Giá bán</td>' +
									'<td>Số lượng</td>' +
									'<td>Thành tiền</td>' +
								  '</tr>' +
								'</thead>' +
								'<tbody>' +
								  '<tr>' +
									'<td class="product_list">' + _name + '</td>' +
									'<td class="pricestr">' + _price + '</td>' +
									'<td class="quantity">' + _quantity + '</td>' +
									'<td class="amountstr">' + _amount + '</td>' +
								  '</tr>' +
								  '<tr>' +
									'<td colspan="3" height="30">TỔNG SỐ TIỀN</td>' +
									'<td class="total-all">' + data.order.cost + '</td>' +
								  '</tr>' +
								'</tbody>' +
							  '</table>' +
							'</div>' +
						  '</div>' +
						'</div>';
						$('#chkorder-result').html(out);
                    } else if (data.code == 'invalidData') {
                        alert(chkorder_lng['error_invalid_' + lang]);
                        $('#email').focus();
                    } else if (data.code == 'invalidEmail') {
                        alert(chkorder_lng['error_email_' + lang]);
                        $('#email').focus();
                    } else if (data.code == 'missingData') {
                        alert(chkorder_lng['error_missing_' + lang]);
                        $('#email').focus();
                    }
                    return false;
                }
            });
        }
    });
});