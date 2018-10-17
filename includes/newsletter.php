<?php

$html = '<div id="newsletter" class="slickModal newsletter">
    <div class="slickWindow">
        <div class="wrap">
			<div class="title">Đăng ký nhận tin khuyến mãi</div>
			<p>Đừng bỏ lỡ những chương trình khuyến mãi nào của chúng tôi bằng cách đăng ký nhận tin khuyến mãi qua E-mail</p>
			<form action="/includes/signup-newsletter.php" method="POST" accept-charset="utf-8" name="signup-form" id="signup-form">
				<p>
					<label for="name"/>Tên Quý khách:</label>
					<input class="field" type="text" name="name"/>
				</p>
				<p>
					<label for="email"/>Email:</label>
					<input class="field" type="text" name="email"/>
				</p>
				<p>
					<input class="closeModal send" type="submit" value="Đăng ký" id="submit-btn"/>
				</p>
				<p id="status"></p>
			</form>
			<label class="deny closeModal setCookie-1">Nếu Quý khách không đăng ký nhận tin? Quý khách đóng biểu mẫu này tại đây.</label>
		</div>
    </div>
</div>
<script type="text/javascript" src="/themes/default/js/slickModal.min.js"></script>
<!--<script type="text/javascript" src="/themes/default/js/jquery_ui-1.12.1 .js"></script>-->
<script type="text/javascript" src="/themes/default/js/signup-newsletter.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(\'#newsletter\').slickModals({			
            popupType: \'delayed\',
			delayTime: 1000,
			setCookie: true,
			cookieDays: 30,
			cookieTriggerClass: \'setCookie-1\',
			cookieName: \'slickModal-1\',
			cookieScope: \'domain\',
			pageAnimationDuration: \'0.4\',
			pageAnimationEffect: \'scale\',
			pageScaleValue: \'1\',
			popupWidth: \'560px\',
			popupHeight: \'430px\',
			popupLocation: \'center\',
			popupAnimationDuration: \'0.6\',
			popupAnimationEffect: \'rotateIn\',
			popupBoxShadow: \'0 0 20px 0 rgba(0,0,0,0.4)\',
			popupBackground: "rgba(255,255,255,1)",
			popupMargin: \'0\',
			popupPadding: \'0\',
			mobileBreakPoint: \'560px\',
			mobileLocation: \'bottomCenter\',
			mobileWidth: \'100%\',
			mobileHeight: \'auto\',
			mobilePadding: \'0\',
        });

    });
</script>';

//$pns->showHTML($html);