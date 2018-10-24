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
    // Biến dùng kiểm tra nếu đang gửi ajax thì ko thực hiện gửi thêm
    var is_busy = false;

    // Biến lưu trữ trang hiện tại
    var page = 1;

    // Số record trên mỗi trang
    var record_per_page = 3;

    // Biến lưu trữ rạng thái phân trang
    var stopped = false;

    // Khi kéo scroll thì xử lý
    $('#loadMore').on('click', function () {
        // Element append nội dung
        $element = $('#content');

        // ELement hiển thị chữ loadding
        $button = $(this);

        // Nếu đang gửi ajax thì ngưng
        if (is_busy == true) {
            return false;
        }

        // Tăng số trang lên 1
        page++;

        // Hiển thị loadding ...
        $button.html('Đang tải ...');

        // Gửi Ajax
        $.ajax(
            {
                type: 'get',
                dataType: 'json',
                url: '/load-more',
                data: {page: page},
                success: function (result) {
                    var html = '';

                    // Trường hợp hết dữ liệu cho trang kết tiếp
                    if (result.length <= record_per_page) {
                        // Lặp dữ liêụ
                        $.each(result, function (key, obj) {
                            html += '<div>' + obj.id + ' - ' + obj.name + '-' + obj.website + '</div>';
                        });

                        // Thêm dữ liệu vào danh sách
                        $element.append(html);

                        // Xóa button
                        $button.remove();
                    }
                    else { // Trường hợp còn dữ liệu cho trang kế tiếp
                        // Lặp dữ liêụ, trường hợp này ta lặp bỏ đi phần record cuối cùng vì ta selec với limit + 1
                        $.each(result, function (key, obj) {
                            if (key < result.length - 1) {
                                html += '<div>' + obj.id + ' - ' + obj.name + '-' + obj.website + '</div>';
                            }
                        });

                        // Thêm dữ liệu vào danh sách
                        $element.append(html);
                    }

                }
            })
            .always(function () {
                // Sau khi thực hiện xong thì đổi giá trị cho button
                $button.html('Xem thêm');
                is_busy = false;
            });

    });
});