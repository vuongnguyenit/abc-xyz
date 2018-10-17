$(document).ready(function() {
  $(window).bind('load', function() {
    $.ajax({
      url: '/xu-ly-binh-luan',
      type: 'post',
      data: {
        'action': 'load',
        'id': ITEM_ID,
		'type': ITEM_TYPE,
        'rand': Math.random()
      },
      dataType: 'json',
      success: function(j, status) {
        if (j.code == 'success' && status == 'success') {
          var c = j.data;
          $.each(c, function(i) {
            var html = '';
            html += '<div class="' + c[i].type + '-block cmt-item" id="comment_' + c[i].id + '">';
            html += '<div class="avatar"><img alt="avatar-icon" align="absmiddle" src="' + path_image + 'no_avatar.jpg" class="avatar" width="64" height="64" title="" onerror="$(this).css({display:\'none\'})" /></div>';
            html += '<div class="' + c[i].type + '-content">';
            html += '<div class="user">' + c[i].member + '</div>';
            html += '<div class="text"><span class="arrow"></span>' + c[i].content + '</div>';
            html += '<div class="reply"><span class="date">(' + c[i].date + ')</span>';
            html += '<div class="fr">';
            html += '<!--<span class="report"><a rel="nofollow" id="reportComment_' + c[i].id + '" class="tt-tipsy reportComment" original-title="Báo cáo vi phạm" href="javascript:;"><img alt="report-icon" align="absmiddle" src="' + path_image + 'warning.png" height="16" width="16" title="Báo cáo vi phạm" onerror="$(this).css({display:\'none\'})" /></a></span>--> ';
            html += '<span class="like"><a rel="nofollow" id="likeComment_' + c[i].id + '" class="tt-tipsy likeComment' + (c[i].liked == 1 ? ' unlike' : '') + '" original-title="Thích bình luận này" href="javascript:;"><img alt="like-icon" align="absmiddle" src="' + path_image + (c[i].liked == 0 ? 'like.png' : 'liked.png') + '" height="16" width="16" title="Thích" onerror="$(this).css({display:\'none\'})" /></a> <a rel="nofollow" id="countLike_' + c[i].id + '" class="countLike" href="javascript:;" original-title="">' + c[i].like + '</a></span> ';
            html += '<span class="btnReply"><a rel="nofollow" id="replyComment_' + c[i].id + '" class="tt-tipsy showReplyBox" original-title="Trả lời bình luận này" href="javascript:;"><img alt="reply-icon" align="absmiddle" src="' + path_image + 'comments_reply.png" height="16" width="16" title="Trả lời bình luận này" onerror="$(this).css({display:\'none\'})" /></a></span>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('div#loadComment').append(html);
          });
          $('div.cmt-item:last-child').addClass('last');
        }
      }
    });
    return false;
  });

/*http://learn.jquery.com/javascript-101/functions/
  http://learn.jquery.com/plugins/basic-plugin-creation/*/

  $('#loadComment a.likeComment').live('click', function() {
    var a = $(this),
        b = a.attr('id').replace('likeComment_', ''),
        c = a.hasClass('unlike') ? 'unlike' : 'like',
        d = a.children();
    $.ajax({
      type: 'POST',
      url: '/like-comment',
      data: {
        id: b,
        action: c,
		ctype: ITEM_TYPE
      },
      dataType: 'json',
      success: function(f, status) {
        if (f.code == 'success' && status == 'success') {
          $('a#countLike_' + b).text(f.like);
          if (c == 'like') {
            d.attr('src', path_image + 'liked.png');
            a.addClass('unlike');
          } else {
            d.attr('src', path_image + 'like.png');
            a.removeClass('unlike');
          }
        } else if (f.code == 'limit') {
          //pnsdotvn_popup('notify', pns.comment.error.limit.replace('%PNSDOTVN_TIME%', f.time));
		  alert('Bạn đã sử dụng chức năng này. Bạn phải đợi %PNSDOTVN_TIME% nữa mới được thao tác tiếp.'.replace('%PNSDOTVN_TIME%', f.time));
        }
      }
    });
  });

  $('#loadComment a.showReplyBox').live('click', function() {
    var a = $(this),
        b = a.attr('id').replace('replyComment_', ''),
        c = a.parent().parent().parent(),
        d = c.width(),
        e = c.parent().parent(),
        f = document.getElementById('loadComment').scrollHeight,
        g = '';
    $('div.cmt-reply').remove();
    g += '<div class="cmt-reply" id="replyBox_' + b + '" style="float: right; margin-right:' + (f > 800 ? 16 : 34) + 'px;">';
    g += '<textarea id="content_' + b + '" name="content_' + b + '" class="replyContent" placeholder="Ý kiến của Bạn" style="width: ' + d + 'px; height: 50px;"></textarea>';
    g += '<div class="reply"><span class="post">Gửi</span></div></div>';
    $('div#comment_' + b).append(g);
    if (e.hasClass('last')) $('#loadComment').scrollTo('#comment_' + b);
    $('textarea#content_' + b).focus();
  });

  $('#loadComment span.post').live('click', function() {
    var a = $(this),
        b = a.parent().parent(),
        c = b.attr('id').replace('replyBox_', ''),
        d = $('textarea#content_' + c),
        e = d.val();
    if (e == '') {
	  d.focus();
      //pnsdotvn_popup('error', pns.comment.error.missing);	 
	  alert('Vui lòng nhập thông tin đầy đủ.'); 
	  return false;
    }
	$.ajax({
	  type: 'POST',
	  url: '/xu-ly-binh-luan',
	  data: {
		id: c,
		content: e,
		type: 'follow',
		ctype: ITEM_TYPE
	  },
	  dataType: 'json',
	  success: function(f, status) {
		if (f.code == 'success' && status == 'success') {
		  b.remove();
		  //pnsdotvn_popup('notify', pns.comment.success.follow);
		  alert('Cám ơn Bạn đã trả lời cho bình luận này. Nội dung trả lời sẽ được đăng lên sau khi được xét duyệt.');
		} else if (f.code == 'invalid') {
		  //pnsdotvn_popup('error', pns.comment.error.invalid);
		  alert('Bình luận được trả lời hiện không tồn tại hoặc đã bị khóa.');
		} else if (f.code == 'wordnumber') {
		  //pnsdotvn_popup('error', pns.comment.error.wordnumber.replace('%PNSDOTVN_WORD_NUMBER%',f.wordnumber));
		  alert();
        } else if (f.code == 'notavailable') {
		  //pnsdotvn_popup('error', pns.comment.error.notavailable);
		  alert('Sản phẩm được bình luận hiện không tồn tại hoặc đã bị khóa.');
		} else if (f.code == 'missing') {
		  //pnsdotvn_popup('error', pns.comment.error.missing);
		  alert('Vui lòng nhập thông tin đầy đủ.');
		  d.focus();
		} else if (f.code == 'nologgin') {
		  //pnsdotvn_popup('signin', f.callback);
		  alert('Vui lòng đăng nhập để sử dụng chức năng này.');
		}
		return false;
	  }
	});
  });

  /*$('.countLike, .tt-tipsy').tipsy({
    gravity: 's',
    html: true
  });*/
  //End Tipsy  
});