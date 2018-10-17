var max_quantity = 99;
jQuery(document).ready(function ($) {
    var lang_result = new Array();
    lang_result['cartStatus_vi-VN'] = '<strong>Giỏ hàng của Quý khách thì trống!</strong><br/><br/>Nếu Quý khách không thể thêm bất cứ sản phẩm nào vào giỏ hàng, xin Quý khách vui lòng kiểm tra rằng trình duyệt Internet của quý khách đã bật tính năng Cookies và các phần mềm an ninh khác thì không ngăn chặn việc mua hàng của Quý khách.';
    lang_result['cartStatus_en-US'] = '<strong>Your shopping cart is empty!</strong><br /><br />If you can not add any product to cart, would you please check that your Internet browser have enabled Cookies and other security software is not blocking the purchase of the customer.';
    lang_result['removeCart_vi-VN'] = 'Giỏ hàng được xóa thành công.';
    lang_result['removeCart_en-US'] = 'Your cart has been successfully removed.';
    lang_result['createCart_vi-VN'] = "Giỏ hàng đã được tạo.\nHiện đang có %qty% sản phẩm trong giỏ hàng.\nMời Quý khách tiếp tục mua hàng.";
    lang_result['createCart_en-US'] = "Your cart has been created.\nThere is(are) currently %qty% product(s) in your shopping cart.\nYou can continue shopping.";
    lang_result['addItem_vi-VN'] = 'Quý khách đã thêm <b>%qty%</b> sản phẩm <b><i>%name%</i></b> vào giỏ hàng.';
    lang_result['addItem_en-US'] = 'You added <b>%qty%</b> product(s) <b><i>%name%</i></b> to your shopping cart.';
    lang_result['maxQty_vi-VN'] = 'Số lượng cho phép đặt hàng tối đa là <b>' + max_quantity + '</b> đơn vị trên mỗi sản phẩm.';
    lang_result['maxQty_en-US'] = 'Maximum allowed number of orders is <b>' + max_quantity + '</b> on each product unit.';
    lang_result['current_vi-VN'] = '<br />Hiện có <b>' + max_quantity + '</b> sản phẩm <b><i>%name%</i></b> trong giỏ hàng.';
    lang_result['current_en-US'] = '<br />There are <b>' + max_quantity + '</b> products <b><i>%name%</i></b> in your shopping cart.';
    lang_result['expand_vi-VN'] = 'Mở ra';
    lang_result['expand_en-US'] = 'Expand';
    lang_result['collaps_vi-VN'] = 'Đóng lại';
    lang_result['collaps_en-US'] = 'Collaps';
    lang_result['alert_tax1_vi-VN'] = '<u>Lưu ý</u>: Tùy chọn này sẽ (+) 10% thuế GTGT vào đơn hàng. Vui lòng điền đầy đủ và chính xác các thông tin bên dưới.';
    lang_result['alert_tax1_en-US'] = '<u>Note</u>: This option (+) 10% VAT on the order. Please complete and accurate information below.';
    lang_result['alert_tax2_vi-VN'] = 'Tùy chọn xuất hóa đơn đã được hủy bỏ.';
    lang_result['alert_tax2_en-US'] = 'VAT option has been canceled.';
    lang_result['langCode_vi-VN'] = 'vi';
    lang_result['langCode_en-US'] = 'en';
    lang_result['productRewrite_vi-VN'] = 'san-pham';
    lang_result['productRewrite_en-US'] = 'product';
    lang_result['checkoutRewrite_vi-VN'] = 'thanh-toan';
    lang_result['checkoutRewrite_en-US'] = 'checkout';
    lang_result['chooseColor_vi-VN'] = 'Vui lòng chọn màu.';
    lang_result['chooseColor_en-US'] = 'Please choose color.';
    lang_result['cart_title_vi-VN'] = 'Giỏ hàng';
    lang_result['cart_title_en-US'] = 'Your cart';
    lang_result['cart_detail_vi-VN'] = 'Chi tiết giỏ hàng';
    lang_result['cart_detail_en-US'] = 'Your Cart Detail';
    lang_result['cart_href_vi-VN'] = '/gio-hang.html';
    lang_result['cart_href_en-US'] = '/cart.html';
    lang_result['checkout_title_vi-VN'] = 'Thanh toán';
    lang_result['checkout_title_en-US'] = 'Checkout';
    lang_result['checkout_href_vi-VN'] = '/thanh-toan.html';
    lang_result['checkout_href_en-US'] = '/checkout.html';
    lang_result['contact_vi-VN'] = 'Liên hệ';
    lang_result['contact_en-US'] = 'Contact';

    var emptyCart = '<div class="fullbox" style="margin: 5px 15px; padding: 10px; text-align: center; position: relative;">' + lang_result['cartStatus_' + lang] + '</div>';

    var productId = $('#product_id').val();

    $('.order').live('click', function () {
        var notice = '';
        var name = $(this).attr('title');
        var flg = $(this).attr('class');
        var qty = $('#quantity').val();
        if (qty == '' || qty == 0) {
            qty = 1;
            $('#quantity').val(qty);
        }
        qty = qty >= max_quantity ? max_quantity : qty;
        if($('ul.product-attribute').length && !$('ul.product-attribute li').hasClass('selected')) {
            $attribute = $('.product-attribute');
            $('.tooltip-error').removeClass('hidden');
            $('.tooltip-error').css({
                'display': 'block',
                'left': $attribute.position().left,
                'top': $attribute.position().top - $attribute.height()
            });
            return false;
        }
        $eleSelected = $('ul.product-attribute li.selected input');
        var value = 0;
        if($eleSelected.val() != 'undefined') {
            value = $eleSelected.val();
        }
        $.ajax({
            url: '/dat-mua',
            type: 'post',
            data: {
                'action': 'addtoCart',
                'product_id': productId,
                'value': value,
                'qty': qty,
                'rand': Math.random(),
                'attribute': $eleSelected.attr('class')
            },
            dataType: 'json',
            success: function (j, status) {
                if (j.code == 'success' && status == 'success') {
                    if (flg == 'order order-now') {
                        document.location.href = lang_result['checkout_href_' + lang];
                        return false;
                    }
                    var result = j;
                    var itotal = $('ul.cart-detail li').length;
                    var p = j.pnsdotvn.item;
                    var iitem = $('ul.cart-detail li#pd_' + p.id).length;
                    if (iitem == 0) {
                        var tmp = (p.color != '' && p.color != 'nocolor-0') || p.size != '' && p.size != 'nosize-0' ? (' (' + (p.color != '' && p.color != 'nocolor-0' ? p.color : '') + (p.color != '' && p.color != 'nocolor-0' && p.size != '' && p.size != 'nosize-0' ? ' - ' : '') + (p.size != '' && p.size != 'nosize-0' ? p.size : '') + ')') : '';
                        var ihtml = '<li class="item' + (itotal == 2 || itotal % 2 == 0 ? 2 : 1) + '" id="pd_' + p.id + '"><a href="' + p.href + '" title="' + p.title + '"><img alt="' + p.alt + '" src="' + p.src + '" width="50" height="50" title="' + p.title + '" /><p>' + p.name + '</p><p class="price">' + (p.price > 0 ? p.price_txt : lang_result['contact_' + lang]) + '</p><p>x<span id="qty_' + p.id + '">' + p.qty + '</span><span id="desc_' + p.id + '">' + tmp + '</span></p></a></li>';
                        $('ul.cart-detail').append(ihtml);
                    } else $('ul.cart-detail span#qty_' + p.id).html(p.qty);
                    var _qty = $('div#my-cart span#item-cart').text();
                    if (_qty == 0) {
                        var chtml = '<div class="cart" id="my-cart"><a href="' + lang_result['cart_href_' + lang] + '" title="' + lang_result['cart_title_' + lang] + '"><i class="fa fa-shopping-cart"></i></a> <span id="item-amount">' + j.pnsdotvn.total_amount_txt + '</span><br /><span id="item-cart">' + j.pnsdotvn.total_item + '</span> sản phẩm<div class="cart-content"><ul class="cart-detail"><li class="item2" id="pd_' + p.id + '"><a href="' + p.href + '" title="' + p.title + '"><img alt="' + p.alt + '" src="' + p.src + '" width="50" height="50" title="' + p.title + '" /><p>' + p.name + '</p><p class="price">' + (p.price > 0 ? p.price_txt : lang_result['contact_' + lang]) + '</p><p>x<span id="qty_' + p.id + '">' + p.qty + '</span><span id="desc_' + p.id + '">' + tmp + '</span></p></a></li></ul><div class="cart-view"><a href="' + lang_result['cart_href_' + lang] + '" title="' + lang_result['cart_detail_' + lang] + '">' + lang_result['cart_detail_' + lang] + '</a> | <a href="' + lang_result['checkout_href_' + lang] + '" title="' + lang_result['checkout_title_' + lang] + '">' + lang_result['checkout_title_' + lang] + '</a></div></div></div>';
                        $('div#header li#top-cart p').hide();
                        $('div#header .banner .cart').remove();
                        $('div#header .banner').append(chtml);
                        $('#header-wrapper #toolbars li.yourcart').hide();
                        alert(lang_result['createCart_' + lang].replace('%qty%', j.pnsdotvn.qty));
                    }
                    $('div#my-cart span#item-cart').html(j.pnsdotvn.total_item);
                    $('div#my-cart span#item-amount').html(j.pnsdotvn.total_amount_txt);
                    if (j.pnsdotvn.qty == max_quantity) {
                        notice = lang_result['maxQty_' + lang] + lang_result['current_' + lang].replace('%name%', name);
                        $('input#quantity').val(0);
                    } else if (j.pnsdotvn.qty < qty) {
                        notice = lang_result['maxQty_' + lang] + ' ' + lang_result['addItem_' + lang].replace('%qty%', j.pnsdotvn.qty).replace('%name%', name);
                    } else {
                        notice = lang_result['addItem_' + lang].replace('%qty%', j.pnsdotvn.qty).replace('%name%', name);
                    }
                    var windowWidth = document.documentElement.clientWidth;
                    var popupWidth = $('#pnsdotvn-notification').width();
                    popupWidth = popupWidth == 0 ? 500 : popupWidth;
                    $('#pnsdotvn-notification').html(notice).css({
                        'z-index': 99999,
                        'top': '100px',
                        'left': (windowWidth / 2) - (popupWidth / 2)
                    }).fadeIn('fast').animate({
                        opacity: 1.0
                    }, 1000).click(function () {
                        $(this).fadeOut('slow', function () {
                            $(this).hide()
                        })
                    }).fadeOut(6000);
                }
            }
        });
    });

    $('button[name=btnAlsoBought]').live('click', function () {
        var products = [];
        $("input[name^='plist']:checked").each(function () {
            products.push($(this).val());
        });
        $.ajax({
            url: '/mua-tat-ca',
            type: 'post',
            data: {
                'action': 'alsobought',
                'id': products,
                'rand': Math.random()
            },
            dataType: 'json',
            success: function (j, status) {
                if (j.code == 'success' && status == 'success') {
                    $('div#my-cart span#item-amount').html(j.pnsdotvn.total_amount_txt);
                    $('div#my-cart span#item-cart').html(j.pnsdotvn.total_item + ' sản phẩm');
                    var notice = 'Đã thêm thành công ' + j.pnsdotvn.total_bought + ' sản phẩm vào giỏ hàng.';
                    var windowWidth = document.documentElement.clientWidth;
                    var popupWidth = $('#pnsdotvn-notification').width();
                    popupWidth = popupWidth == 0 ? 500 : popupWidth;
                    $('#pnsdotvn-notification').html(notice).css({
                        'z-index': 99999,
                        'top': '100px',
                        'left': (windowWidth / 2) - (popupWidth / 2)
                    }).fadeIn('fast').animate({
                        opacity: 1.0
                    }, 1000).click(function () {
                        $(this).fadeOut('slow', function () {
                            $(this).hide()
                        })
                    }).fadeOut(6000);
                }
            }
        });
    });

    $('input#quantity').live('keyup', function () {
        var qty = $(this).val();
        if (qty.substring(0, 1) == 0) qty = qty.replace(0, '');
        while (qty.length > 0 && isNaN(qty))
            qty = qty.substring(0, qty.length - 1);
        qty = $.trim(qty).replace(/ /g, '').replace('-', '');
        $(this).val(qty);
    });

    $('input#quantity').live('mouseout', function () {
        var qty = $(this).val();
        if (qty == '' || qty == 0 || qty.length == 0) $(this).val(1);
    });

    $('[rel=removeCart]').live('click', function () {
        var _seft = $(this);
        var url = _seft.attr('href');
        $.ajax({
            url: url,
            type: 'get',
            data: {
                'action': 'removeCart'
            },
            dataType: 'html',
            success: function () {
                $('#totalItems').html('0');
                $('#totalPrice').html('0');
            }
        });
        $("div#miniCart").hide("slow").removeClass('showMiniCart').addClass('hideMiniCart');
        $('div#my-cart span#item-cart').html(0);
        alert(lang_result["removeCart_" + lang]);
        return false;
    });

    $('input[name=btnRemoveItem]').live('click', function () {
        $remove = $(this);
        $.ajax({
            url: '/xoa-san-pham',
            type: 'post',
            data: {
                'action': 'removeItem',
                'id': $remove.val()
            },
            dataType: 'json',
            success: function (j, status) {
                if (j.code == 'success' && status == 'success') {
                    $remove.parent().parent().remove();
                    if(j.pnsdotvn.total_amount == 0) {
                        $('#pns-cart .clear').remove();
                        $('#pns-cart .b-transaction').remove();
                        $('#pns-cart .fullbox').removeClass('hidden');
                    }
                }
            }
        });
        return false;
    });

    $('input[name=btnUpdateItem]').live('click', function () {
        var id = $(this).val();
        var qty = $('input#qty_' + id).val();
        $.ajax({
            url: '/cap-nhat-gio-hang',
            type: 'post',
            data: {
                'action': 'updateCart',
                'id': id,
                'qty': qty
            },
            dataType: 'json',
            success: function (j, status) {
                if (j.code == 'success' && status == 'success') {
                    j.pnsdotvn.amount == 0 || j.pnsdotvn.subtotal == 0 ? $('#item_' + id).remove() : $('span#price_' + id).html(j.pnsdotvn.amount);
                    j.pnsdotvn.subtotal == 0 ? $('#pns-cart').html(emptyCart) : $('#totalPrice').html(j.pnsdotvn.subtotal);
                    $('div#my-cart span#item-cart').html(j.pnsdotvn.total_item);
                    $('div#my-cart span#item-amount').html(j.pnsdotvn.total_amount_txt);
                    $('tr').find('td.ordering').each(function (i) {
                        $(this).html(i + 1);
                    });
                }
            }
        });
        return false;
    });

    $('input[class=p_list_input]').live('mouseout', function () {
        var qty = $(this).val();
        if (qty == '' || qty == 0 || qty.length == 0) $(this).val(1);
    });

    $('input[class=p_list_input]').live('keyup', function () {
        var qty = $(this).val();
        if (qty.substring(0, 1) == 0) qty = qty.replace(0, '');
        while (qty.length > 0 && isNaN(qty))
            qty = qty.substring(0, qty.length - 1);
        qty = $.trim(qty).replace(/ /g, '').replace('-', '');
        $(this).val(qty);
        qty = (qty > 0) ? qty : 1;
        var name = $(this).attr('name');
        var pro = name.split('|');
        var tr = $('tr.item_' + pro[1]);
        var o = tr.offset();
        var w = tr.width();
        var h = tr.height();
        $.ajax({
            url: '/cap-nhat-gio-hang',
            type: 'post',
            data: {
                'action': 'updateCart',
                'id': pro[1],
                'qty': qty,
                'rand': Math.random()
            },
            beforeSend: function () {
                $('input#qty_' + pro[1]).blur();
                $('body').append('<div id="pnsdotvn-processing" style="background-color:#FFF; zoom: 1; filter: alpha(opacity=90); opacity: 0.9; position:absolute; top:' + o.top + 'px; left:' + o.left + 'px; width:' + w + 'px; height:' + h + 'px; line-height:' + h + 'px; text-align: center;"><img alt="loading" src="/themes/default/images/loading.gif" title="loading" /></div>');
            },
            dataType: 'json',
            success: function (j, status) {
                if (j.code == 'success' && status == 'success') {
                    $('ul.cart-detail span#qty_' + j.pnsdotvn.item.id).html(j.pnsdotvn.item.qty);
                    if (j.pnsdotvn.total_item == 0) $('#pns-cart').html(emptyCart);
                    $('span#price_' + pro[1]).html(j.pnsdotvn.amount > 0 ? j.pnsdotvn.amount_txt : lang_result['contact_' + lang]);
                    $('#totalPrice').html(j.pnsdotvn.subtotal > 0 ? j.pnsdotvn.subtotal_txt : lang_result['contact_' + lang]);
                    /*j.pnsdotvn.amount == 0 || j.pnsdotvn.sub_total == 0 ? $('#item_' + pro[1]).remove() : $('span#price_' + pro[1]).html(j.pnsdotvn.amount_txt);
                    j.pnsdotvn.subtotal == 0 ? $('#pns-cart').html(emptyCart) : $('#totalPrice').html(j.pnsdotvn.subtotal_txt);*/
                    $('div#my-cart span#item-cart').html(j.pnsdotvn.total_item);
                    $('div#my-cart span#item-amount').html(j.pnsdotvn.total_amount_txt);
                    $('tr').find('td.ordering').each(function (i) {
                        $(this).html(i + 1);
                    });
                    $('div#pnsdotvn-processing').remove();
                }
            }
        });
        return false;
    });
    var canSplit = function(str, token){
        return (str || '').split(token).length > 1;
    }
    $('span[name=adjust]').live('click', function () {
        $adjust = $(this);
        var name = $(this).parent().find('input');
        var tmp = name.val();
        var qty = 0;
        if($(this).hasClass('p_list_add')) {
            qty = parseInt(tmp) + 1;
        } else {
            qty = parseInt(tmp) - 1 > 0 ? parseInt(tmp) - 1 : 1;
        }
        qty = ((qty > max_quantity) ? max_quantity : qty);

        var id = '';
        var value = '';
        var info = name.data('info') + '|';
            info = info.split('|');
        if(info.length > 2) {
            id = info[0];
            value = info[2];
        } else {
            id = info[0];
        }
        $.ajax({
            url: '/cap-nhat-gio-hang',
            type: 'post',
            data: {
                'action': 'updateCart',
                'id': id,
                'qty': qty,
                'value': value
            },
            dataType: 'json',
            success: function (result) {
                if (result.code == 'success') {
                    name.val(result.pnsdotvn.item.qty);
                    $adjust.parent().parent().find('td.pTotal span').text(result.pnsdotvn.amount_txt);
                    $adjust.parent().parent().find('td.pPrice').text(result.pnsdotvn.price_text);
                    $('#totalPrice').text(result.pnsdotvn.total_amount_txt);
                }
            }
        });
        return false;
    });

    $('span[name=taxinfo]').live('click', function () {
        var cls = $(this).attr('class');
        var tmp = cls.split(" ");
        if (tmp[1] == 'expandable') {
            $(this).removeClass('expandable').addClass('collapsable');
            $(this).attr('title', lang_result["collaps_" + lang]);
            $('div.taxzone').show("fast");
            $.ajax({
                url: '/tinh-thue-gtgt',
                type: 'post',
                data: {
                    'action': 'calcTax',
                    'type': 'plus'
                },
                dataType: 'json',
                success: function (j) {
                    $('span#sub-total').html(j.subtotal);
                    $('span#tax-amount').html(j.taxamount);
                    $('span#total').html(j.total);
                    $('div#alert-tax').html(lang_result["alert_tax1_" + lang]);
                    $('input#tax').val(1);
                }
            });
        } else if (tmp[1] == 'collapsable') {
            $(this).removeClass('collapsable').addClass('expandable');
            $(this).attr('title', lang_result["expand_" + lang]);
            $.ajax({
                url: '/tinh-thue-gtgt',
                type: 'post',
                data: {
                    'action': 'calcTax',
                    'type': 'minus'
                },
                dataType: 'json',
                success: function (j) {
                    $('span#sub-total').html(j.subtotal);
                    $('span#tax-amount').html(j.taxamount);
                    $('span#total').html(j.total);
                    $('div#alert-tax').html(lang_result["alert_tax2_" + lang]);
                    $('input#tax').val(0);
                }
            });
            $('div.taxzone').hide("slow");
        }
        return false;
    });

    $('div#my-cart').live('mouseover', function () {
        var _qty = $('div#my-cart span#item-cart').text();
        if (_qty > 0) {
            $(".cart-content").show();
            $('#my-cart').addClass('cart-button');
        }
    }).live('mouseout', function () {
        $(".cart-content").hide();
        $('#my-cart').removeClass('cart-button');
    });

    /*
    $('li.selectOption').live('click', function() {
      var current = $(this);
      $('li.selectOption').each(function() {
        if ($(this).hasClass('selected')) {
          $(this).removeClass("selected");
        }
      });
      current.addClass("selected");
      $('.tooltip-error').css('display', 'none');
      return false;
    });
    */

    /*
    $('#pro_detail ul.color li').live('click', function() {
      var current = $(this);
      $('#pro_detail ul.color li').each(function() {
        if ($(this).hasClass('selected')) $(this).removeClass("selected");
      });
      current.addClass('selected');
      return false;
    });
    */

    $('li.selectOption').live('click', function () {
        var current = $(this);
        var price = $(this).find('input').data('price');
        $('.tooltip-error').addClass('hidden');
        $('#pns_price').html(price);
        $('.selectOption').removeClass("selected");
        current.addClass("selected");
        $('#product-color .tooltip-error').css('display', 'none');
        return false;
    });
    // show wholesale modal
    $('#view-more-wholesale').live('click', function () {
        $('#wholesale').modal('show');
        $('#header-wrapper').css('z-index', 100);
    });
});

function modelessDialogShow(url, width, height) {
    var w = (screen.availWidth - width) / 2;
    var h = (screen.availHeight - height) / 2;
    showModalDialog(url, '', 'dialogWidth:' + width + 'px; dialogHeight:' + height + 'px; center:1; dialogLeft:' + w + 'px; dialogTop:' + h + 'px; help:off; resizable:on; status:off;');
}

function checkNumber(textBox) {
    while (textBox.value.length > 0 && isNaN(textBox.value)) {
        textBox.value = textBox.value.substring(0, textBox.value.length - 1)
    }
    textBox.value = trim(textBox.value);
}