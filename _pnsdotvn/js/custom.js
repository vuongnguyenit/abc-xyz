function loadItem(processPage, arrDIVContainer, param) {
    $.ajax({
        url: processPage, data: param, dataType: "HTML", success: function (html) {
            $("#" + arrDIVContainer).html(html);
            $("#" + arrDIVContainer).fadeIn(500)
        }
    })
}

function loadModule(processPage, arrayDIVContainer, param) {
    $("#" + arrayDIVContainer).hide(10);
    $("#" + arrayDIVContainer).load(processPage, param, function callback(data, status) {
        $("#" + arrayDIVContainer).fadeIn(500)
    })
}

function getModule(processPage, arrayDIVContainer, param, type) {
    $(arrayDIVContainer).html("<td colspan='12' style='background-color:#EBF1F6;text-align:right;padding:6px'>Please wait...</td>");
    $.get(processPage, param, function (data, status) {
        if (status == "success") {
            $(arrayDIVContainer).html(data)
        }
    }, type)
}

jQuery(document).ready(function ($) {
    var $wholesalePrice = $('#wholesale-price');
    $wholesalePrice.on('input', function () {
        $('.wholesale-price').css('display', 'none');
    });
    $(document).on('click', '#add-wholesale', function () {
        var productId = $(this).data('id');
        var quantityFrom = $('#quantity-from option:selected').val();
        var quantityTo = $('#quantity-to option:selected').val();

        if($wholesalePrice.val() == 0 || !$.isNumeric($wholesalePrice.val())) {
            $('.wholesale-price').css('display', 'block');
        }
        $.ajax({
            url: 'wholesale.php',
            type: 'get',
            data: {
                'product_id': productId,
                'quantity_from': quantityFrom,
                'quantity_to': quantityTo,
                'wholesale_price': $wholesalePrice.val()
            },
            dataType: 'json',
            success: function (result, status) {
                if (result.wholesale.length > 0) {
                    $('div#wholesale-data table').html('');
                    var html = '<tr>';
                    html += '<th>Số lượng</th>';
                    html += '<th>Số lượng</th>';
                    html += '<th></th>';
                    html += '</tr>';
                    $.each(result.wholesale, function (i, item) {
                        html += '<tr class="content-sale">';
                        html += '<td>' + item.quantity_from + ' -> ' + item.quantity_to + '</td>';
                        html += '<td>' + item.wholesale_price + '</td>';
                        html += '<td><a href="javascript:void(0);" class="delete-whole-sale" data-sid="' + item.id + '" data-id="'+result.product_id+'">Xóa</a></td>';
                        html += '</tr>';
                    })
                    $('div#wholesale-data table').append(html);
                } else {
                    $('div#wholesale-data table').html('');
                }
                $('#quantity-from').val(0);
                $('#quantity-to').val(0);
                $wholesalePrice.val('');
            }
        });
    });
    $(document).on('click', '.delete-whole-sale', function () {
        $.ajax({
            url: 'wholesale.php',
            type: 'get',
            data: {
                'sid': $(this).data('sid'),
                'product_id': $(this).data('id'),
                'action': 'delete'
            },
            dataType: 'json',
            success: function (result, status) {
                if (result.wholesale.length > 0) {
                    $('div#wholesale-data table').html('');
                    var html = '<tr>';
                    html += '<th>Số lượng</th>';
                    html += '<th>Số lượng</th>';
                    html += '<th></th>';
                    html += '</tr>';
                    $.each(result.wholesale, function (i, item) {
                        html += '<tr class="content-sale">';
                        html += '<td>' + item.quantity_from + ' -> ' + item.quantity_to + '</td>';
                        html += '<td>' + item.wholesale_price + '</td>';
                        html += '<td><a href="javascript:void(0);" class="delete-whole-sale" data-sid="' + item.id + '" data-id="'+result.product_id+'">Xóa</a></td>';
                        html += '</tr>';
                    })
                    $('div#wholesale-data table').html(html);
                } else {
                    $('div#wholesale-data table').html('');
                }
                $('#quantity-from').val(0);
                $('#quantity-to').val(0);
                $wholesalePrice.val('');
            }
        });
    });
});