jQuery(document).ready(function ($) {
    var priceProduct = '#price-product';
    // check attibute name code
    $(priceProduct + ' #code').on('change', function () {
        if ($(this).val() != '') {
            $(this).attr('name', 'code');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name name
    $(priceProduct + ' #name').on('change', function () {
        if ($(this).val() != '') {
            $(this).attr('name', 'name');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name category
    $(priceProduct + ' #category').on('change', function () {
        if ($(this).val() != '') {
            $(this).attr('name', 'category');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name brand
    $(priceProduct + ' #brand').on('change', function () {
        if ($(this).val() != '') {
            $(this).attr('name', 'brand');
        } else {
            $(this).attr('name', '');
        }
    });
    var addCommas = function (str) {
        var parts = (str + "").split("."),
            main = parts[0],
            len = main.length,
            output = "",
            first = main.charAt(0),
            i;

        if (first === '-') {
            main = main.slice(1);
            len = main.length;
        } else {
            first = "";
        }
        i = len - 1;
        while(i >= 0) {
            output = main.charAt(i) + output;
            if ((len - i) % 3 === 0 && i > 0) {
                output = "," + output;
            }
            --i;
        }
        // put sign back
        output = first + output;
        // put decimal part back
        if (parts.length > 1) {
            output += "." + parts[1];
        }
        return output;
    }

    var is_busy = false;
    var page = 1;
    var record_per_page = $('input[name="limit"]').val();
    var list = [];
    $('input.category').on('click', function () {
        $('div#loadMore').html('<a href="javascript:void(0);" class="btn btn-default">Xem thêm</a>')
        $('div#loadMore a').hide();
        if($(this).is(':checked')) {
            list.push($(this).val());
        } else {
            var index = list.indexOf($(this).val());
            if (index !== -1) list.splice(index, 1);
        }
        $element = $('.products.search div.content');
        $.ajax(
            {
                type: 'post',
                dataType: 'json',
                url: '/sale-filter',
                data: {
                    'listCate': list,
                    'action': 'saleFilter',
                },
                success: function (result) {
                    var html = '';
                    $.each(result.result, function (key, obj) {
                        html += '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">';
                        html += '<div class="border box-img"><span class="over-layer-0"></span>';
                        html += '<div class="picture">';
                        html += '<a href="' + obj.link + '" title="' + obj.name + '">';
                        html += '<img alt="' + obj.name + '" src="' + obj.image + '" title="' + obj.name + '">';
                        html += '</a>';
                        html += '</div>';
                        html += '<div class="proname">';
                        html += '<a href="' + obj.link + '" title="' + obj.name + '">' + obj.name + '</a>';
                        html += '</div>';
                        html += '<div class="gr-price">';
                        html += '<span class="list-price"><span>'+ addCommas (obj.list_price)+'đ</span></span>';
                        html += '<span class="price"><span>'+ addCommas (obj.price)+'đ</span></span>';
                        html += '</div>';
                        html += '</div></div>';
                    });
                    console.log(result.total);
                    if(result.total > record_per_page) {
                        page = 1;
                        $('div#loadMore a').show();
                    }
                    $element.html('');
                    $element.html(html);
                }
            }
        )
    })
    $(document).on('click','div#loadMore a', function () {
        $element = $('.products.search div.content');
        $button = $(this);
        if (is_busy == true) {
            return false;
        }
        page++;
        $button.html('Đang tải ...');
        // Gửi Ajax
        $.ajax(
            {
                type: 'post',
                dataType: 'json',
                url: '/load-more',
                data: {
                    'page': page,
                    'action': 'loadMore',
                    'listCate': list,
                    'limit': record_per_page
                },
                success: function (result) {
                    var html = '';
                    if (result.length <= record_per_page) {
                        $.each(result, function (key, obj) {
                            html += '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">';
                            html += '<div class="border box-img"><span class="over-layer-0"></span>';
                            html += '<div class="picture">';
                            html += '<a href="' + obj.link + '" title="' + obj.name + '">';
                            html += '<img alt="' + obj.name + '" src="' + obj.image + '" title="' + obj.name + '">';
                            html += '</a>';
                            html += '</div>';
                            html += '<div class="proname">';
                            html += '<a href="' + obj.link + '" title="' + obj.name + '">' + obj.name + '</a>';
                            html += '</div>';
                            html += '<div class="gr-price">';
                            html += '<span class="list-price"><span>'+ addCommas (obj.list_price)+'đ</span></span>';
                            html += '<span class="price"><span>'+ addCommas (obj.price)+'đ</span></span>';
                            html += '</div>';
                            html += '</div></div>';
                        });
                        $element.append(html);
                        $button.remove();
                    }
                    else {
                        $.each(result, function (key, obj) {
                            if (key < result.length - 1) {
                                html += '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">';
                                html += '<div class="border box-img"><span class="over-layer-0"></span>';
                                html += '<div class="picture">';
                                html += '<a href="' + obj.link + '" title="' + obj.name + '">';
                                html += '<img alt="' + obj.name + '" src="' + obj.image + '" title="' + obj.name + '">';
                                html += '</a>';
                                html += '</div>';
                                html += '<div class="proname">';
                                html += '<a href="' + obj.link + '" title="' + obj.name + '">' + obj.name + '</a>';
                                html += '</div>';
                                html += '<div class="gr-price">';
                                html += '<span class="list-price"><span>'+ addCommas (obj.list_price)+'đ</span></span>';
                                html += '<span class="price"><span>'+ addCommas (obj.price)+'đ</span></span>';
                                html += '</div>';
                                html += '</div></div>';
                            }
                        });
                        $element.append(html);
                    }
                }
            })
            .always(function () {
                $button.html('Xem thêm');
                is_busy = false;
            });

    });
});