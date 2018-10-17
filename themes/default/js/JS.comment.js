function lockButton() {
  $('#btnComment').addClass('disabled');
  $('#btnComment').attr('disabled', 'disabled');
}
function enableButton() {
  $('#btnComment').removeClass('disabled');
  $('#btnComment').removeAttr('disabled');
}
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
  var loader = $('<div class="loader">Vui lòng chờ..</div>').css({
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
  var v = $('#frmComment').validate({
    debug: false,
    errorContainer: container,
    errorLabelContainer: $('ol', container),
    meta: 'validate',
    submitHandler: function(form) {
      $(form).ajaxSubmit({
        resetForm: false,
        dataType: 'json',
        success: function(d, status) {
          if (d.code == 'success' && status == 'success') {
            v.resetForm();
            //pnsdotvn_popup('notify', pns.comment.success.new);
			alert('Cám ơn Bạn đã bình luận cho sản phẩm/tin này. Nội dung bình luận sẽ được đăng lên sau khi được xét duyệt.');
            $('#captchaimage img').attr('src', '/captcha-image?' + d.time);
          } else if (d.code == 'wordnumber') {
            //pnsdotvn_popup('error', pns.comment.error.wordnumber.replace('%PNSDOTVN_WORD_NUMBER%',d.wordnumber));
			alert('Nội dung bình luận dài hơn %PNSDOTVN_WORD_NUMBER% từ, vui lòng rút gọn.');
          } else if (d.code == 'notavailable') {
            //pnsdotvn_popup('error', pns.comment.error.notavailable);
			alert('Sản phẩm/Tin được bình luận hiện không tồn tại hoặc đã bị khóa.');
          } else if (d.code == 'missing') {
            //pnsdotvn_popup('error', pns.comment.error.missing);
			alert('Vui lòng nhập thông tin đầy đủ.');
            $('#content').focus();
          } else if (d.code == 'captcha') {
            //pnsdotvn_popup('error', pns.comment.error.captcha);
			alert('Mã bảo vệ không đúng.');
          } else if (d.code == 'nologgin') {
            //pnsdotvn_popup('signin', d.callback);
			alert('Vui lòng đăng nhập để sử dụng chức năng này.');
          }
		  return false;
        }
      });
    }
  });
  $('#btnClear').click(function() {
    v.resetForm();
    $('#content').focus();
  })
});