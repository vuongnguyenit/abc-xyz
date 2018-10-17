var member_lng = new Array();
member_lng['inprocess_vi-VN'] = 'Vui lòng chờ..';
member_lng['inprocess_en-US'] = 'Please waiting..';
member_lng['success_vi-VN'] = 'Thông tin của Bạn đã được cập nhật.';
member_lng['success_en-US'] = 'Your information has been updated.';
member_lng['error_birthdate_format_vi-VN'] = "Định dạng ngày sinh (dd/mm/yyyy) không hợp lệ.\nHoặc năm sinh phải trong khoảng [" + FROM_YEAR + " - " + TO_YEAR + "].";
member_lng['error_birthdate_format_en-US'] = "Format date of birth (dd/mm /yyyy) is invalid.\nOr birth year should be between [" + FROM_YEAR + " - " + TO_YEAR + "].";
member_lng['error_missing_vi-VN'] = 'Vui lòng nhập thông tin đầy đủ.';
member_lng['error_missing_en-US'] = 'Please fill in the form.';
$('span#modify').click(function() {
    $(this).hide();
    $('input#salutation_name').hide();
    $('select#salutation').show();
    $('input#address').removeClass('expand');
    $('input#country_name').hide();
    $('select#country').show();
    $('input#city_name').hide();
    $('select#city').show();
    $('input#district_name').hide();
    $('select#district').show();
	$('input#ward_name').hide();
    $('select#ward').show();
    $('#frm').find('input').removeClass('none').removeAttr('readonly');
    $('div#member').find('.hide').removeClass('hide').addClass('on');
});
$('span.cancel').click(function() {
    $('input#salutation_name').show();
    $('select#salutation').hide();
    $('input#address').addClass('expand');
    $('input#country_name').show();
    $('select#country').hide();
    $('input#city_name').show();
    $('select#city').hide();
    $('input#district_name').show();
    $('select#district').hide();
	 $('input#ward_name').show();
    $('select#ward').hide();
    $('#frm').find('input').addClass('none').attr('readonly', 'readonly');
    $('div#member').find('.on').removeClass('on').addClass('hide');
    $('span#modify').show();
    $.getJSON('/tai-du-lieu', function(data) {
        $.each(data.member, function(key, val) {
            $('#frm input#' + key).val(val);
        });
    });
});

function lockButton() {
    $('#change').addClass('disabled');
    $('#change').attr('disabled', 'disabled');
}

function enableButton() {
    $('#change').removeClass('disabled');
    $('#change').removeAttr('disabled');
}
$(function() {
    var loader = $('<div class="loader">' + member_lng['inprocess_' + lang] + '</div>').css({
        position: 'fixed',
        top: '4em',
        right: '0.9em'
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
    var v = $('#frm').validate({
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
                        alert(member_lng['success_' + lang]);
                        $('input#salutation_name').val(data.info.salutation).show();
                        $('select#salutation').hide();
                        $('select#salutation option:selected').each(function() {
                            var salutation = $(this).text();
                            $('input#salutation_name').val(salutation);
                        });
                        $('input#address').addClass('expand');
                        $('input#country_name').show();
                        $('select#country').hide();
                        $('select#country option:selected').each(function() {
                            var country = $(this).text();
                            $('input#country_name').val(country);
                        });
                        $('input#city_name').val(data.info.city).show();
                        $('select#city').hide();
                        $('select#city option:selected').each(function() {
                            var city = $(this).text();
                            $('input#city_name').val(city);
                        });
                        $('input#district_name').val(data.info.district).show();
                        $('select#district').hide();
                        $('select#district option:selected').each(function() {
                            var district = $(this).text();
                            $('input#district_name').val(district);
                        });
						$('input#ward_name').val(data.info.ward).show();
                        $('select#ward').hide();
                        $('select#ward option:selected').each(function() {
                            var ward = $(this).text();
                            $('input#ward_name').val(ward);
                        });
                        $('#frm').find('input').addClass('none').attr('readonly', 'readonly');
                        $('div#member').find('.on').removeClass('on').addClass('hide');
                        $('span#modify').show();
                    } else if (data.code == 'birthdateNotValid') {
                        alert(member_lng['error_birthdate_format_' + lang]);
                        $('#birthdate').focus();
                    } else if (data.code == 'missingData') {
                        alert(member_lng['error_missing_' + lang]);
                        $('#name').focus();
                    }
                    return false;
                }
            });
        }
    });
});